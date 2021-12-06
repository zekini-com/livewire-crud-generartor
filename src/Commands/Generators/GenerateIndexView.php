<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateIndexView extends Command
{

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:view-index {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates index view';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }
    
    /**
     * getTemplate
     *
     * @return void
     */
    protected function getTemplateUrl()
    {
        return __DIR__.'../../../../templates/index-view.php.stub';
    }
    
    /**
     * getClassNamespace
     *
     * @return string
     */
    protected function getClassNamespace($table)
    {
        return "App\Http\Controllers\Admin\\".ucfirst($table);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
        $this->info('Generating Index View');
       
       //publish any vendor files to where they belong
       $content = $files->get($this->getTemplateUrl());

       $tableName = $this->argument('table');
    
       $content = str_replace('DummyNamespace', $this->getClassNamespace($tableName), $content);
       $content = str_replace('DummyClassName', ucfirst($tableName), $content);
        
        return Command::SUCCESS;
    }
    

  
}