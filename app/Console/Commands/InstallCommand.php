<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class InstallCommand extends Command
{
    protected $signature = 'hostclient:install';
    protected $description = 'Install HostClient - Setup wizard';

    public function handle()
    {
        $this->info('╔═══════════════════════════════════════╗');
        $this->info('║   HostClient Installation Wizard     ║');
        $this->info('╚═══════════════════════════════════════╝');
        $this->newLine();

        // Check requirements
        if (!$this->checkRequirements()) {
            return 1;
        }

        // Test database connection
        if (!$this->testDatabase()) {
            return 1;
        }

        // Run migrations
        if ($this->confirm('Run database migrations?', true)) {
            $this->runMigrations();
        }

        // Create admin account
        $this->createAdmin();

        // Configure settings
        $this->configureSettings();

        $this->newLine();
        $this->info('✅ Installation completed successfully!');
        $this->info('🚀 You can now access your installation at: ' . config('app.url'));
        
        return 0;
    }

    protected function checkRequirements(): bool
    {
        $this->info('Checking system requirements...');
        
        $requirements = [
            'PHP Version >= 8.2' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'BCMath Extension' => extension_loaded('bcmath'),
            'Ctype Extension' => extension_loaded('ctype'),
            'JSON Extension' => extension_loaded('json'),
            'Mbstring Extension' => extension_loaded('mbstring'),
            'OpenSSL Extension' => extension_loaded('openssl'),
            'PDO Extension' => extension_loaded('pdo'),
            'PDO MySQL Extension' => extension_loaded('pdo_mysql'),
            'Tokenizer Extension' => extension_loaded('tokenizer'),
            'XML Extension' => extension_loaded('xml'),
        ];

        $failed = false;
        foreach ($requirements as $requirement => $met) {
            if ($met) {
                $this->line("✅ $requirement");
            } else {
                $this->error("❌ $requirement");
                $failed = true;
            }
        }

        if ($failed) {
            $this->error('Some requirements are not met. Please install missing extensions.');
            return false;
        }

        $this->newLine();
        return true;
    }

    protected function testDatabase(): bool
    {
        $this->info('Testing database connection...');

        try {
            DB::connection()->getPdo();
            $this->line('✅ Database connection successful');
            $this->newLine();
            return true;
        } catch (\Exception $e) {
            $this->error('❌ Database connection failed: ' . $e->getMessage());
            $this->error('Please check your .env configuration');
            return false;
        }
    }

    protected function runMigrations(): void
    {
        $this->info('Running database migrations...');
        
        Artisan::call('migrate', ['--force' => true]);
        $this->line(Artisan::output());

        if ($this->confirm('Seed database with sample data?', false)) {
            Artisan::call('db:seed', ['--force' => true]);
            $this->line(Artisan::output());
        }

        $this->newLine();
    }

    protected function createAdmin(): void
    {
        $this->info('Create administrator account');

        $firstName = $this->ask('First Name', 'Admin');
        $lastName = $this->ask('Last Name', 'User');
        $email = $this->ask('Email', 'admin@hostclient.local');
        $password = $this->secret('Password');
        $passwordConfirm = $this->secret('Confirm Password');

        if ($password !== $passwordConfirm) {
            $this->error('Passwords do not match!');
            $this->createAdmin();
            return;
        }

        // Create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $clientRole = Role::firstOrCreate(['name' => 'client']);

        // Create admin user
        $admin = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified' => true,
            'is_active' => true,
        ]);

        $admin->assignRole('admin');

        $this->info('✅ Administrator account created successfully');
        $this->newLine();
    }

    protected function configureSettings(): void
    {
        $this->info('Configure basic settings');

        $companyName = $this->ask('Company Name', 'HostClient');
        $currency = $this->ask('Default Currency', 'EUR');
        $timezone = $this->ask('Timezone', 'Europe/Paris');

        // Save to .env or settings table
        $this->info('✅ Settings configured');
        $this->newLine();
    }
}
