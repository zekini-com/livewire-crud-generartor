<?php
namespace Zekini\CrudGenerator;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Zekini\CrudGenerator\Http\Middleware\RedirectIfAuthenticated;
use Zekini\CrudGenerator\Providers\CrudServiceProvider;

class LivewireCrudGeneratorServiceProvider extends ServiceProvider

{

     /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

        //$this->register(CrudServiceProvider::class);
        $this->registerCommands();

        $this->loadViewsFrom(__DIR__.'./../resources/views', 'zekini/livewire-crud-generator');
        $this->loadRoutesFrom(__DIR__.'./../routes/web.php');

        // register commands
        if ($this->app->runningInConsole()) {
           $this->publishConfig();
           $this->publishMigrations();
        }
        
        $this->setupMiddlewares();
    }

    
    /**
     * Registers Command
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            Commands\AdminScafold::class,
            Commands\CrudGenerator::class,
            Commands\Generators\GenerateModel::class,
            Commands\Generators\GenerateController::class,
            Commands\Generators\GenerateForm::class,
            Commands\Generators\GenerateIndexView::class,
            Commands\Generators\GenerateEditView::class,
            Commands\Generators\GenerateCreateView::class,
            Commands\Generators\GeneratePermission::class,
            Commands\Generators\GenerateUnitTest::class,
            Commands\Generators\GenerateFactory::class,
            Commands\Generators\GenerateRequestIndex::class,
            Commands\Generators\GenerateRequestStore::class,
            Commands\Generators\GenerateRequestUpdate::class,
            Commands\Generators\GenerateRequestDestroy::class,
            Commands\Generators\GenerateRoutes::class
        ]);
    }
    
    /**
     * publishConfig
     *
     * @return void
     */
    protected function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../config/zekini-admin.php' => config_path('zekini-admin.php'),
        ], 'config');
    }
    

    
    /**
     * Publishes migration files
     *
     * @return void
     */
    protected function publishMigrations()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/fill_zekini_admin_default.php' => $this->getMigrationFileName('fill_zekini_admin_default.php'),
        ], 'migrations');
        
        $this->publishes([
            __DIR__ . '/../database/migrations/create_zekini_admins_table.php' => $this->getMigrationFileName('create_zekini_admins_table.php'),
        ], 'migrations');
        
        $this->publishes([
            __DIR__ . '/../database/migrations/create_zekini_admin_password_resets.php' => $this->getMigrationFileName('create_zekini_admin_password_resets_table.php'),
        ], 'migrations');
        
    }

     /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = date('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }

    
    /**
     * Setup Middlewares
     *
     * @return void
     */
    protected function setupMiddlewares()
    {
        $router = $this->app->make(Router::class);
        $guard = config('zekini-admin.defaults.guard');
        $router->aliasMiddleware('guest.'.$guard, RedirectIfAuthenticated::class);
    }

    public function register()
    {
        
    }
}