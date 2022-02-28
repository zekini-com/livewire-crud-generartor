<?php

namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;

class GeneratePermission extends BaseGenerator
{
    protected $classType = 'permission';

    protected $model;

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
    protected $signature = 'admin:generate:permission {table : table to generate crud for } {--user : When added the crud is generated for a user model}';

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
        return 'FillPermissionsFor' . Str::plural(Str::studly($this->argument('table'))) . 'Table';
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

        $this->model = Str::singular(Str::studly($this->argument('table')));

        $this->namespace = $this->getDefaultNamespace($this->rootNamespace());

        $templateContent = $this->replaceContent();

        $stampedFilename = $this->getFileName();

        $this->files->put($stampedFilename, $templateContent);
        $pathToFile = str_replace('\\', '/', substr($stampedFilename, strpos($stampedFilename, 'database')));

        //the new permission
        //$this->call('migrate', [
        //    '--path' => $pathToFile
        //]);

        return Command::SUCCESS;
    }

    /**
     * Get filename
     *
     * @return string
     */
    protected function getFileName()
    {
        $filesystem = $this->files;
        $migrationFileName = Str::snake($this->className, '_') . ".php";
        $timestamp = date('Y_m_d_His');

        return Collection::make(database_path() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path . '*_' . $migrationFileName);
            })
            ->push(database_path() . "/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
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
            'modelDotNotation' => Str::singular($this->argument('table'))
        ];
    }
}
