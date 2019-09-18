<?php

namespace Yaro\Jarboe\Console\Commands;

use Illuminate\Console\Command;
use SplFileObject;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jarboe:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure package';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->publishAssets();
        $this->publishConfigs();
        $this->copyNavigationView();
        $this->copyMigrationFiles();
        $this->copyThirdPartyMigrationFiles();

        if ($this->confirm("Add admin's guard and provider to auth config file?")) {
            $this->addAdminsGuardToConfigFile();
        }

        if ($this->confirm('Run migrations?')) {
            $this->call('migrate');
        }

        $this->info('Completed');
    }

    private function copyNavigationView()
    {
        $this->comment('Creating navigation view:');
        if ($this->isNavigationViewExists()) {
            $this->comment('  - already exists');
            return;
        }

        shell_exec(sprintf(
            'mkdir -p "%s" && cp "%s" "%s"',
            base_path('resources/views/vendor/jarboe/inc/'),
            base_path('vendor/yaro/jarboe/src/resources/views/inc/navigation.blade.php'),
            base_path('resources/views/vendor/jarboe/inc/navigation.blade.php')
        ));
    }

    private function isNavigationViewExists()
    {
        return file_exists(base_path('resources/views/vendor/jarboe/inc/navigation.blade.php'));
    }

    private function copyMigrationFiles()
    {
        $this->comment('Creating migration files:');
        if ($this->isMigrationFilesExist()) {
            $this->comment('  - already exists');
            return;
        }

        $name = date('Y_m_d_His') . '_create_admins_table.php';
        shell_exec(sprintf(
            'cp "%s" "%s"',
            base_path('vendor/yaro/jarboe/src/database/migrations/2018_06_28_152903_create_admins_table.php'),
            database_path('migrations/' . $name)
        ));
    }

    private function isMigrationFilesExist()
    {
        return (bool)glob(database_path('migrations/*_create_admins_table.php'));
    }

    private function copyThirdPartyMigrationFiles()
    {
        $this->comment('Publishing third-party migration files:');
        if ($this->isThirdPartyMigrationFilesExist()) {
            $this->comment('  - already exists');
            return;
        }

        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
            '--tag' => 'migrations',
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
            '--tag' => 'config',
        ]);
    }

    private function isThirdPartyMigrationFilesExist()
    {
        return (bool)glob(database_path('migrations/*_create_permission_tables.php'));
    }

    private function publishAssets()
    {
        $this->comment('Publishing public assets:');
        if ($this->isAssetsPublished()) {
            $this->comment('  - already exists');
            return;
        }

        $this->call('vendor:publish', [
            '--provider' => "Yaro\Jarboe\ServiceProvider",
            '--tag' => 'public',
            '--force' => true,
        ]);
    }

    private function isAssetsPublished()
    {
        return file_exists(public_path('vendor/jarboe'));
    }

    private function publishConfigs()
    {
        $this->comment('Publishing config files:');
        if ($this->isConfigsPublished()) {
            $this->comment('  - already exists');
            return;
        }

        $this->call('vendor:publish', [
            '--provider' => "Yaro\Jarboe\ServiceProvider",
            '--tag' => 'config',
        ]);
    }

    private function isConfigsPublished()
    {
        return file_exists(config_path('jarboe'));
    }

    private function addAdminsGuardToConfigFile()
    {
        $shouldAddGuardsSection = true;
        if (config('auth.guards.admin')) {
            $shouldAddGuardsSection = false;
            $this->comment('  - guard [admin] already exist');
        }

        $shouldAddProvidersSection = true;
        if (config('auth.guards.providers.admins')) {
            $shouldAddProvidersSection = false;
            $this->comment('  - provider [admins] already exist');
        }

        if (!$shouldAddGuardsSection && !$shouldAddProvidersSection) {
            return;
        }

        $configFile = config_path('auth.php');

        $output = '';
        $file = new SplFileObject($configFile, 'r');
        foreach ($file as $lineNumber => $line) {
            $output .= $line;
            if ($line == "    'guards' => [\n" && $shouldAddGuardsSection) {
                $output .= "        'admin' => [\n";
                $output .= "            'driver' => 'session',\n";
                $output .= "            'provider' => 'admins',\n";
                $output .= "        ],\n\n";
            }

            if ($line == "    'providers' => [\n" && $shouldAddProvidersSection) {
                $output .= "        'admins' => [\n";
                $output .= "            'driver' => 'eloquent',\n";
                $output .= "            'model' => \Yaro\Jarboe\Models\Admin::class,\n";
                $output .= "        ],\n\n";
            }
        }

        $file = new SplFileObject($configFile, 'w+');
        $file->fwrite($output);
    }
}
