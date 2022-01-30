<?php

namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Zekini\CrudGenerator\Traits\CreatesSidebar;

class GenerateRoutes extends BaseGenerator
{
    use CreatesSidebar;

    protected $classType = 'routes';

    protected $resourceController;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:generate:route {table : table to generate crud for } {--user : When added the crud is generated for a user model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates routes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(Filesystem $files)
    {
        $this->info('Generating CRUD Routes');

        //publish any vendor files to where they belong
        $this->className = $this->getClassName();

        $routeName = "admin/" . strtolower($this->className) . "/index";

        if (Route::has($routeName)) {
            $this->info('Skipping Routes. Route already exists');
            return Command::SUCCESS;
        }

        $this->resourceController = $this->className . 'Controller';

        $templateContent = $this->appendContent();

        $filename = base_path('routes/web.php');

        $this->files->append($filename, $templateContent);

        $this->makeSideBar();

        return Command::SUCCESS;
    }

    /**
     * Append the content in the file
     *
     * @return string
     */
    protected function appendContent()
    {
        $variables = $this->getViewData();

        $view = "zekini/stubs::templates." . $this->classType;

        return view($view, $variables)->render();
    }

    /**
     * Get view data
     *
     * @return array
     */
    protected function getViewData()
    {
        $modelVariableName = Str::studly($this->className);

        return [
            'resourceController' => $this->resourceController,
            'resource' => strtolower($this->className),
            'modelVariableName' => $modelVariableName,
            'livewireFolderName' => Str::plural($modelVariableName)
        ];
    }
}
