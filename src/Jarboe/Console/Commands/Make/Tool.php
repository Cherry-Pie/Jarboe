<?php

namespace Yaro\Jarboe\Console\Commands\Make;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yaro\Jarboe\Table\Toolbar\Interfaces\ToolInterface;

class Tool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jarboe:make:tool {class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make tool for toolbar.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $className = (string) $this->argument('class');

        $packagePath = __DIR__ .'/../../../../';
        $stub = file_get_contents($packagePath .'stubs/tool.stub');

        $position = $this->choice('Tool position?', [
            ToolInterface::POSITION_HEADER,
            ToolInterface::POSITION_BODY_TOP,
            ToolInterface::POSITION_BODY_BOTTOM,
            ToolInterface::POSITION_BODY_BOTH,
        ]);
        $position = strtoupper('position_'. $position);
        $ident = Str::random();
        $view = Str::snake($className);

        $tool = sprintf($stub, $className, $position, $ident, $view);
        $filepath = app_path(sprintf('Jarboe/Toolbar/%s.php', $className));

        if (!File::exists(app_path('Jarboe/Toolbar'))) {
            File::makeDirectory(app_path('Jarboe/Toolbar'),0775,true,false);
        }
        File::put($filepath, $tool);

        $this->info('Created: '. $filepath);
    }
}
