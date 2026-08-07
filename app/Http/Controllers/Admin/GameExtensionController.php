<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameExtension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GameExtensionController extends Controller
{
    public function index(Request $request)
    {
        $query = GameExtension::with('uploader')->orderBy('created_at', 'desc');

        if ($request->filled('game_type')) {
            $query->where('game_type', $request->game_type);
        }
        if ($request->filled('extension_type')) {
            $query->where('extension_type', $request->extension_type);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $extensions = $query->paginate(20)->withQueryString();

        $gameTypes = GameExtension::$gameTypes;
        $extensionTypes = GameExtension::$extensionTypes;

        return view('admin.game-extensions.index', compact('extensions', 'gameTypes', 'extensionTypes'));
    }

    public function create()
    {
        $gameTypes = GameExtension::$gameTypes;
        $extensionTypes = GameExtension::$extensionTypes;

        return view('admin.game-extensions.create', compact('gameTypes', 'extensionTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'version' => 'nullable|string|max:50',
            'author' => 'nullable|string|max:255',
            'game_type' => 'required|string',
            'extension_type' => 'required|string',
            'is_active' => 'boolean',
            'auto_install' => 'boolean',
            'compatible_versions' => 'nullable|string',
            'file' => 'required|file|max:524288|mimes:zip,jar,pk3,cfg,json,yaml,yml,lua,tar,gz',
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $slug = Str::slug($validated['name']);
        
        // Stocker dans storage/app/extensions/{game_type}/
        $path = $file->storeAs(
            "extensions/{$validated['game_type']}",
            $slug . '-v' . ($validated['version'] ?? '1.0.0') . '.' . $file->getClientOriginalExtension(),
            'local'
        );

        // Convertir les versions compatibles (texte CSV → tableau)
        $compatibleVersions = null;
        if (!empty($validated['compatible_versions'])) {
            $compatibleVersions = array_map(
                'trim',
                explode(',', $validated['compatible_versions'])
            );
        }

        GameExtension::create([
            'name' => $validated['name'],
            'slug' => $slug . '-' . time(),
            'description' => $validated['description'] ?? null,
            'version' => $validated['version'] ?? '1.0.0',
            'author' => $validated['author'] ?? null,
            'game_type' => $validated['game_type'],
            'extension_type' => $validated['extension_type'],
            'file_path' => $path,
            'file_name' => $originalName,
            'file_size' => $file->getSize(),
            'file_hash' => md5_file($file->getPathname()),
            'compatible_versions' => $compatibleVersions,
            'is_active' => $request->boolean('is_active', true),
            'auto_install' => $request->boolean('auto_install', false),
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.game-extensions.index')
            ->with('success', "Extension \"{$validated['name']}\" uploaded successfully.");
    }

    public function show(GameExtension $gameExtension)
    {
        $gameExtension->load(['uploader', 'services.user']);

        return view('admin.game-extensions.show', compact('gameExtension'));
    }

    public function edit(GameExtension $gameExtension)
    {
        $gameTypes = GameExtension::$gameTypes;
        $extensionTypes = GameExtension::$extensionTypes;

        return view('admin.game-extensions.edit', compact('gameExtension', 'gameTypes', 'extensionTypes'));
    }

    public function update(Request $request, GameExtension $gameExtension)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'version' => 'nullable|string|max:50',
            'author' => 'nullable|string|max:255',
            'game_type' => 'required|string',
            'extension_type' => 'required|string',
            'is_active' => 'boolean',
            'auto_install' => 'boolean',
            'compatible_versions' => 'nullable|string',
            'file' => 'nullable|file|max:524288|mimes:zip,jar,pk3,cfg,json,yaml,yml,lua,tar,gz',
        ]);

        // Convertir les versions compatibles
        $compatibleVersions = $gameExtension->compatible_versions;
        if (isset($validated['compatible_versions'])) {
            $compatibleVersions = !empty($validated['compatible_versions'])
                ? array_map('trim', explode(',', $validated['compatible_versions']))
                : null;
        }

        $updateData = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'version' => $validated['version'] ?? $gameExtension->version,
            'author' => $validated['author'] ?? null,
            'game_type' => $validated['game_type'],
            'extension_type' => $validated['extension_type'],
            'compatible_versions' => $compatibleVersions,
            'is_active' => $request->boolean('is_active', true),
            'auto_install' => $request->boolean('auto_install', false),
        ];

        // Remplacer le fichier si un nouveau est fourni
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $slug = Str::slug($validated['name']);

            // Supprimer l'ancien fichier
            if (Storage::disk('local')->exists($gameExtension->file_path)) {
                Storage::disk('local')->delete($gameExtension->file_path);
            }

            $path = $file->storeAs(
                "extensions/{$validated['game_type']}",
                $slug . '-v' . ($validated['version'] ?? '1.0.0') . '.' . $file->getClientOriginalExtension(),
                'local'
            );

            $updateData['file_path'] = $path;
            $updateData['file_name'] = $file->getClientOriginalName();
            $updateData['file_size'] = $file->getSize();
            $updateData['file_hash'] = md5_file($file->getPathname());
        }

        $gameExtension->update($updateData);

        return redirect()
            ->route('admin.game-extensions.index')
            ->with('success', 'Extension updated successfully.');
    }

    public function destroy(GameExtension $gameExtension)
    {
        // Supprimer le fichier physique
        if (Storage::disk('local')->exists($gameExtension->file_path)) {
            Storage::disk('local')->delete($gameExtension->file_path);
        }

        $gameExtension->delete();

        return redirect()
            ->route('admin.game-extensions.index')
            ->with('success', 'Extension deleted successfully.');
    }

    public function toggle(GameExtension $gameExtension)
    {
        $gameExtension->update(['is_active' => !$gameExtension->is_active]);

        $status = $gameExtension->is_active ? 'enabled' : 'disabled';

        return redirect()->back()->with('success', "Extension {$status}.");
    }

    public function download(GameExtension $gameExtension)
    {
        if (!Storage::disk('local')->exists($gameExtension->file_path)) {
            abort(404, 'File not found.');
        }

        $gameExtension->incrementDownloads();

        return Storage::disk('local')->download(
            $gameExtension->file_path,
            $gameExtension->file_name
        );
    }
}
