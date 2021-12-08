<?php
namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GeneratePermission extends BaseGenerator
{

    protected $classType = 'permission';
    
    /**
     * class name
     *
     * @var string
     */
    protected $className;
    
    /**
     * class namespace
     *
     * @var string
     */
    protected $namespace;

     /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:permission {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates model permissions';

    
    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

     /**
     * Get the name of the class
     *
     * @return string
     */
    protected function getClassName()
    {
        return 'FillPermissionsFor'.rtrim(Str::studly($this->argument('table')), 's').'Table';    
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Generating Permissions Class');

        $this->className = $this->getClassName();

        $this->model = rtrim($this->argument('table'), 's');

        $this->namespace = $this->getDefaultNamespace($this->rootNamespace());

        $templateContent = $this->replaceContent();
        $path = database_path('migrations');
        $filename = $path.'/'.$this->getFileName();
       
        $this->files->put($filename, $templateContent);
       
        return Command::SUCCESS;
    }


    
    /**
     * Get filename
     *
     * @return string
     */
    protected function getFileName()
    {
        $timestamp = date('Y_m_d_His');
        return "{$timestamp}_".Str::snake($this->className, '_').".php";
    }


     /**
     * Get view data
     *
     * @return array
     */
    protected function getViewData()
    {
        return [
            'className' => $this->getClassName(),
            'modelDotNotation'=> strtolower($this->model)
        ];
    }

   
    

  
}