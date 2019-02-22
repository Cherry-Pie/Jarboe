<?php

namespace Yaro\Jarboe\Console\Commands;

use Illuminate\Console\Command;
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
            'cp %s %s',
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
            'cp %s %s',
            base_path('packages/jarboe/src/database/migrations/2018_06_28_152903_create_admins_table.php'),
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
            ->setItemExtra('[COMPLETE!]')
            ->addLineBreak('-')
            ->setBackgroundColour('cyan')
            ->setForegroundColour('default')
            ->build();

        $menu->open();
    }

    public function publishAssets(CliMenu $menu)
    {
        $menu->confirm('Run command: php artisan vendor:publish --provider="Yaro\Jarboe\ServiceProvider" --tag=public --force')->display('Ok');
    }

    private function isAssetsPublished()
    {
        return file_exists(public_path('vendor/jarboe'));
    }

    public function publishConfigs(CliMenu $menu)
    {
        $menu->confirm('Run command: php artisan vendor:publish --provider="Yaro\Jarboe\ServiceProvider" --tag=config')->display('Ok');
    }

    private function isConfigsPublished()
    {
        return file_exists(config_path('jarboe'));
    }
}
