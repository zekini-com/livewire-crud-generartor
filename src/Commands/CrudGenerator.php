<?php
namespace Zekini\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Zekini\CrudGenerator\Helpers\Utilities;

class CrudGenerator extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:crud:generate 
                            {table : table to generate crud for } 
                            {--user : When added the crud is generated for a user model} 
                            {--exclude=* : An array of classes not generate}
                            {--readonly : The datatable is read only no create and edit buttons}
                            {--only= : generates a single component}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates crud for a particular table in the db given the table name';

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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $tableName = $this->argument('table');
     
        if (! $this->tableExists($tableName)) {
            
            return Command::FAILURE;
        } 

        // if we are generating a single component
        // we stop here and continue
        $component = $this->option('only');

        $component ?  $this->generateSingleComponent($component) : $this->generateMultipleComponents();

        return Command::SUCCESS;
    }

    protected function generateSingleComponent($component)
    {
        $command = $this->getGenerators()[$component];

        $this->call($command, $this->getOptionsArgument($command));
    }

    protected function generateMultipleComponents()
    {
        foreach($this->getGenerators() as $index=>$command) {
            if(in_array($index, $this->option('exclude'))) continue;
            $this->call($command, $this->getOptionsArgument($command));
        }
    }


    protected function tableExists($tableName):  ?bool
    {
        if(! Schema::hasTable($tableName)) {
            $this->error('Cannot find table. exiting');
           return false;
        }

        return true;
    }


    protected function getOptionsArgument($command)
    {
        $array = ['table'=> $this->argument('table')];
        if($this->option('user')) {
            $array['--user'] = $this->option('user');
        }

        $readonlyCommands = [
            'admin:generate:component:datatable',
            'admin:generate:component:index',
            'admin:generate:test:datatable',
            'admin:generate:test:index',
        ];
       

        if($this->option('readonly') && in_array($command, $readonlyCommands)){
          
            $array['--readonly'] = $this->option('readonly');
        }

        return $array;
    }

    
    /**
     * Get Generators
     *
     * @return array
     */
    protected function getGenerators()
    {
        return [
            'model'=> 'admin:generate:model',

            'route'=>'admin:generate:route',
            'form'=>'admin:generate:form',

            // livewire views
            'view-index'=>'admin:generate:views:index',
     

            // livewire components
            'component-datatable'=>'admin:generate:component:datatable',
            'component-index'=>'admin:generate:component:index',

            //controller
            //'admin:generate:controller',

            'permission'=>'admin:generate:permission',
            
            'test-datatable'=>'admin:generate:test:datatable',
            'test-index'=>'admin:generate:test:index',

            'factory'=>'admin:generate:factory'
        ];
    }

 
}
