<?php
namespace Zekini\CrudGenerator\Providers;

use Illuminate\Support\ServiceProvider;
use Zekini\CrudGenerator\Commands\CrudGenerator;
use Zekini\CrudGenerator\Commands\Generators\GenerateModel;

class CrudServiceProvider extends ServiceProvider
{
    
    /**
     * boot
     *
     * @return void
     */
    public function boot()
    {

        $this->commands([
            CrudGenerator::class,
            GenerateModel::class
        ]);
    }
    
    /**
     * register
     *
     * @return void
     */
    public function register()
    {
        
    }
}