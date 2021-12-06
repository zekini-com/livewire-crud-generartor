<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateForm extends Command
{

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:forms {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates Forms';

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
        return __DIR__.'../../../../templates/form.php.stub';
    }
    

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
        $this->info('Generating Edit Forms');
       
       //publish any vendor files to where they belong
       $content = $files->get($this->getTemplateUrl());

       $tableName = $this->argument('table');

        
        return Command::SUCCESS;
    }
    

  
}