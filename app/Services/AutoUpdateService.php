<?php

namespace App\Services;

use App\Models\AutoUpdate;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class AutoUpdateService
{
    protected string $githubRepo = 'Nitrohebergeur/hostclient';
    protected string $tempPath;
    protected string $backupPath;

    public function __construct()
    {
        $this->tempPath = storage_path('app/updates');
        $this->backupPath = storage_path('app/backups');

        if (!File::exists($this->tempPath)) {
            File::makeDirectory($this->tempPath, 0755, true);
        }

        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
    }

    /**
     * Obtenir la version actuelle
     */
    public function getCurrentVersion(): string
    {
        $composerFile = base_path('composer.json');
        
        if (File::exists($composerFile)) {
            $composer = json_decode(File::get($composerFile), true);
            return $composer['version'] ?? '1.0.0';
        }

        return '1.0.0';
    }

    /**
     * Obtenir la dernière version disponible depuis GitHub
     */
    public function getLatestVersion(?string $branch = null): array
    {
        $branch = $branch ?? SystemSetting::get('auto_update_branch', 'main');

        try {
            // Obtenir le dernier commit de la branche
            $response = Http::get("https://api.github.com/repos/{$this->githubRepo}/commits/{$branch}");

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch latest version from GitHub');
            }

            $commit = $response->json();

            // Obtenir le composer.json pour la version
            $composerResponse = Http::get("https://raw.githubusercontent.com/{$this->githubRepo}/{$branch}/composer.json");
            $composer = $composerResponse->json();

            return [
                'version' => $composer['version'] ?? 'unknown',
                'commit_hash' => $commit['sha'],
                'commit_message' => $commit['commit']['message'],
                'commit_date' => $commit['commit']['author']['date'],
                'author' => $commit['commit']['author']['name'],
                'branch' => $branch,
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get latest version', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Télécharger une mise à jour
     */
    public function downloadUpdate(AutoUpdate $update): void
    {
        try {
            $update->update(['status' => 'downloading', 'started_at' => now()]);

            $zipUrl = "https://github.com/{$this->githubRepo}/archive/{$update->commit_hash}.zip";
            $zipPath = $this->tempPath . "/{$update->commit_hash}.zip";

            // Télécharger le fichier ZIP
            $response = Http::timeout(300)->get($zipUrl);

            if (!$response->successful()) {
                throw new \Exception('Failed to download update');
            }

            File::put($zipPath, $response->body());

            // Extraire le ZIP
            $extractPath = $this->tempPath . "/{$update->commit_hash}";
            $zip = new ZipArchive;

            if ($zip->open($zipPath) === true) {
                $zip->extractTo($extractPath);
                $zip->close();
                
                // Supprimer le fichier ZIP
                File::delete($zipPath);

                $update->update(['status' => 'pending']);
            } else {
                throw new \Exception('Failed to extract update');
            }
        } catch (\Exception $e) {
            $update->markAsFailed($e->getMessage());
            Log::error('Failed to download update', [
                'update_id' => $update->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Installer une mise à jour
     */
    public function installUpdate(AutoUpdate $update): void
    {
        try {
            $update->markAsInProgress();

            // Créer une sauvegarde si activée
            if (SystemSetting::get('auto_update_backup_enabled', true)) {
                $this->createBackup($update);
            }

            // Mettre l'application en maintenance
            Artisan::call('down');

            $extractPath = $this->tempPath . "/{$update->commit_hash}";
            
            // Trouver le dossier extrait (GitHub ajoute le nom du repo)
            $folders = File::directories($extractPath);
            $sourcePath = $folders[0] ?? $extractPath;

            // Copier les fichiers (en excluant certains dossiers)
            $this->copyUpdateFiles($sourcePath, base_path());

            // Exécuter les migrations
            Artisan::call('migrate', ['--force' => true]);

            // Vider les caches
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            // Optimiser l'application
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            // Remettre l'application en ligne
            Artisan::call('up');

            // Nettoyer les fichiers temporaires
            File::deleteDirectory($extractPath);

            $update->markAsCompleted();

            Log::info('Update installed successfully', ['update_id' => $update->id]);
        } catch (\Exception $e) {
            // Remettre l'application en ligne en cas d'erreur
            Artisan::call('up');
            
            $update->markAsFailed($e->getMessage());
            
            Log::error('Failed to install update', [
                'update_id' => $update->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Créer une sauvegarde avant mise à jour
     */
    protected function createBackup(AutoUpdate $update): void
    {
        $backupName = 'backup_' . $update->commit_hash . '_' . time();
        $backupFullPath = $this->backupPath . '/' . $backupName;

        // Créer un backup de la base de données
        Artisan::call('backup:run', ['--only-db' => true]);

        // Sauvegarder certains fichiers critiques
        $filesToBackup = [
            '.env',
            'composer.json',
            'composer.lock',
            'package.json',
        ];

        File::makeDirectory($backupFullPath, 0755, true);

        foreach ($filesToBackup as $file) {
            $source = base_path($file);
            if (File::exists($source)) {
                File::copy($source, $backupFullPath . '/' . $file);
            }
        }

        $update->update([
            'backup_data' => [
                'path' => $backupFullPath,
                'created_at' => now()->toDateTimeString(),
            ]
        ]);
    }

    /**
     * Copier les fichiers de mise à jour
     */
    protected function copyUpdateFiles(string $source, string $destination): void
    {
        $excludedPaths = [
            '.git',
            'node_modules',
            'vendor',
            'storage',
            '.env',
            'public/storage',
        ];

        $files = File::allFiles($source);

        foreach ($files as $file) {
            $relativePath = str_replace($source . DIRECTORY_SEPARATOR, '', $file->getPathname());
            
            // Vérifier si le chemin doit être exclu
            $shouldExclude = false;
            foreach ($excludedPaths as $excluded) {
                if (str_starts_with($relativePath, $excluded)) {
                    $shouldExclude = true;
                    break;
                }
            }

            if (!$shouldExclude) {
                $targetPath = $destination . DIRECTORY_SEPARATOR . $relativePath;
                $targetDir = dirname($targetPath);

                if (!File::exists($targetDir)) {
                    File::makeDirectory($targetDir, 0755, true);
                }

                File::copy($file->getPathname(), $targetPath);
            }
        }
    }

    /**
     * Rollback d'une mise à jour
     */
    public function rollbackUpdate(AutoUpdate $update): void
    {
        try {
            if (!$update->backup_data || !isset($update->backup_data['path'])) {
                throw new \Exception('No backup available for this update');
            }

            $backupPath = $update->backup_data['path'];

            if (!File::exists($backupPath)) {
                throw new \Exception('Backup files not found');
            }

            // Mettre l'application en maintenance
            Artisan::call('down');

            // Restaurer les fichiers depuis la sauvegarde
            $files = File::allFiles($backupPath);
            foreach ($files as $file) {
                $relativePath = str_replace($backupPath . DIRECTORY_SEPARATOR, '', $file->getPathname());
                $targetPath = base_path($relativePath);
                
                File::copy($file->getPathname(), $targetPath);
            }

            // Restaurer la base de données (si une sauvegarde existe)
            // TODO: Implémenter la restauration de la base de données

            // Remettre l'application en ligne
            Artisan::call('up');

            $update->update(['status' => 'rolled_back']);

            Log::info('Update rolled back successfully', ['update_id' => $update->id]);
        } catch (\Exception $e) {
            Artisan::call('up');
            
            Log::error('Failed to rollback update', [
                'update_id' => $update->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Vérifier automatiquement les mises à jour
     */
    public function checkAndUpdate(): void
    {
        if (!SystemSetting::get('auto_update_enabled', false)) {
            return;
        }

        try {
            $latestVersion = $this->getLatestVersion();
            $currentVersion = $this->getCurrentVersion();

            if (version_compare($latestVersion['version'], $currentVersion, '>')) {
                $update = AutoUpdate::create([
                    'version' => $latestVersion['version'],
                    'commit_hash' => $latestVersion['commit_hash'],
                    'branch' => $latestVersion['branch'],
                    'changelog' => $latestVersion['commit_message'],
                    'status' => 'pending',
                    'auto_applied' => true,
                ]);

                $this->downloadUpdate($update);
                $this->installUpdate($update);

                Log::info('Auto-update completed', [
                    'from' => $currentVersion,
                    'to' => $latestVersion['version']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Auto-update failed', ['error' => $e->getMessage()]);
        }
    }
}
