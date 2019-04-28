<?php

namespace Yaro\Jarboe\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use PhpSchool\CliMenu\CliMenu;

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
        $this->buildMenu();
    }

    private function isNavigationViewExists()
    {
        return file_exists(base_path('resources/views/vendor/jarboe/inc/navigation.blade.php'));
    }

    public function copyNavigationView(CliMenu $menu)
    {
        shell_exec(sprintf(
            'mkdir -p "%s" && cp "%s" "%s"',
            base_path('resources/views/vendor/jarboe/inc/'),
            base_path('vendor/yaro/jarboe/src/resources/views/inc/navigation.blade.php'),
            base_path('resources/views/vendor/jarboe/inc/navigation.blade.php')
        ));

        $this->flash('Navigation view created: resources/views/vendor/jarboe/inc/navigation.blade.php', $menu);
        $menu->closeThis();

        $this->buildMenu();
    }

    private function isMigrationFilesExist()
    {
        return (bool) glob(database_path('migrations/*_create_admins_table.php'));
    }

    public function copyMigrationFiles(CliMenu $menu)
    {
        $name = date('Y_m_d_His') .'_create_admins_table.php';
        shell_exec(sprintf(
            'cp "%s" "%s"',
            base_path('vendor/yaro/jarboe/src/database/migrations/2018_06_28_152903_create_admins_table.php'),
            database_path('migrations/'. $name)
        ));

        $this->flash('Migration file created: '. $name, $menu);
        $menu->closeThis();

        $this->buildMenu();
    }

    private function flash($message, CliMenu $menu)
    {
        $flash = $menu->flash($message);
        $flash->getStyle()->setBg('green')->setFg('default');
        $flash->display();

        return $flash;
    }

    private function buildMenu()
    {
        $menu = $this->menu('Jarboe Installer')
            ->addItem('Publish public assets', [$this, 'publishAssets'], $this->isAssetsPublished())
            ->addItem('Publish configuration files', [$this, 'publishConfigs'], $this->isConfigsPublished())
            ->addItem('Create navigation view', [$this, 'copyNavigationView'], $this->isNavigationViewExists())
            ->addItem('Create migration files', [$this, 'copyMigrationFiles'], $this->isMigrationFilesExist())
            ->addItem('Publish third-party migration files', [$this, 'copyThirdPartyMigrationFiles'], $this->isThirdPartyMigrationFilesExist())
            ->setItemExtra('[COMPLETE!]')
            ->addLineBreak('-')
            ->setBackgroundColour('cyan')
            ->setForegroundColour('default')
            ->build();

        $menu->open();
    }

    public function copyThirdPartyMigrationFiles(CliMenu $menu)
    {
        Artisan::call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
            '--tag' => 'migrations',
        ]);
        Artisan::call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
            '--tag' => 'config',
        ]);

        $this->flash('Migration files and configuration files created for `spatie/laravel-permission` package', $menu);
        $menu->closeThis();

        $this->buildMenu();
    }

    public function publishAssets(CliMenu $menu)
    {
        shell_exec('php artisan vendor:publish --provider="Yaro\Jarboe\ServiceProvider" --tag=public --force > /dev/null 2>/dev/null &');

        $this->flash('Assets will be published shortly', $menu);
        $menu->closeThis();

        $this->buildMenu();
    }

    private function isAssetsPublished()
    {
        return file_exists(public_path('vendor/jarboe'));
    }

    public function publishConfigs(CliMenu $menu)
    {
        shell_exec('php artisan vendor:publish --provider="Yaro\Jarboe\ServiceProvider" --tag=config > /dev/null 2>/dev/null &');

        $this->flash('Config files will be published shortly', $menu);
        $menu->closeThis();

        $this->buildMenu();
    }

    private function isConfigsPublished()
    {
        return file_exists(config_path('jarboe'));
    }

    private function isThirdPartyMigrationFilesExist()
    {
        return (bool) glob(database_path('migrations/*_create_permission_tables.php'));
    }
}
