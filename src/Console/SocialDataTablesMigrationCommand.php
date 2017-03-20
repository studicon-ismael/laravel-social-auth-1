<?php
namespace Social\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;


/**
 * Class SocialDataTablesCommand
 * @package Social\Console
 */
class SocialDataTablesMigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'social:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply migration for social package';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $path = implode(DIRECTORY_SEPARATOR, [File::dirname(__FILE__), 'Database', 'migrations']);
        $path = str_replace('Console' . DIRECTORY_SEPARATOR, "", $path);
        $path = str_replace(base_path(), "", $path);
        var_dump($path);
        Artisan::call('migrate',['--path' => $path ]);

    }
}
