<?php
namespace Zekini\CrudGenerator;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Zekini\CrudGenerator\Http\Middleware\CheckRole;
use Zekini\CrudGenerator\Mixin\StrMixin;
use Illuminate\Contracts\Http\Kernel;
use Zekini\CrudGenerator\Http\Middleware\CheckAdminDashboard;

class LivewireCrudGeneratorServiceProvider extends ServiceProvider
{
     /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        // my custom str mixin
        Str::mixin(new StrMixin);

        $this->registerCommands();

        $this->publishLandingPage();

        $this->publishAdminViews();

        $this->setConfigValues();

        $this->publishAdminControllers();

        $this->loadViewsFrom(__DIR__.'/../stubs', 'zekini/stubs');

        $this->app['view']->addNamespace('zekini/livewire-crud-generator', resource_path('views/vendor/zekini'));

        Blade::component('zekini/livewire-crud-generator::components.modal', 'c.modal');
        
        $this->loadRoutesFrom(__DIR__.'./../routes/web.php');

        // register commands
        if ($this->app->runningInConsole()) {
           $this->publishConfig();
           $this->publishMigrations();
           $this->publishResources();
        }
        
        $this->setupMiddlewares();
    }

    
    /**
     * Publishes admin views
     *
     * @return void
     */
    protected function publishAdminViews()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/zekini'),
        ], 'views');
    }

    /**
     * Publishes admin views
     *
     * @return void
     */
    protected function publishLandingPage()
    {
        $this->publishes([
            __DIR__ . '/../resources/views/welcome.blade.php' => resource_path('views/welcome.blade.php'),
        ], 'views');
    }
    
    /**
     * Publishes admin controllers
     *
     * @return void
     */
    protected function publishAdminControllers()
    {
        $this->publishes([
            __DIR__ . '/Http/Controllers' => app_path('Http/Controllers/Admin'),
        ], 'controllers');
    }

    /**
     * Registers Command
     *
     * @return void
     */
    protected function registerCommands()
    {
        $this->commands([
            Commands\AdminScaffold::class,
            Commands\CrudGenerator::class,
            Commands\VersionCommand::class,
            Commands\GenerateSuperAdmin::class,
            Commands\GeneratePackageTableCrud::class,

            Commands\Generators\GenerateModel::class,
            Commands\Generators\GenerateController::class,
            Commands\Generators\GenerateForm::class,
            Commands\Generators\GenerateImport::class,
            Commands\Generators\View\GenerateIndexView::class,
            Commands\Generators\GeneratePermission::class,

            Commands\Generators\Test\GenerateDatatableTest::class,
            Commands\Generators\Test\GenerateIndexTest::class,
        
            Commands\Generators\GenerateFactory::class,
           
            Commands\Generators\GenerateRoutes::class,

            Commands\Generators\Component\GenerateDatatableComponent::class,
            Commands\Generators\Component\GenerateIndexComponent::class,
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
     * publishResources
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([
            __DIR__.'/../resources/css/app.css'=> public_path('app.css')
        ], 'resources');
        $this->publishes([
            __DIR__.'/../resources/js/app.js'=> public_path('app.js')
        ], 'resources');
       
    }

    /**
     * Publishes migration files
     *
     * @return void
     */
    protected function publishMigrations()
    {
        $publishableMigrations = [
            'add_softdeletes_to_roles.php',
            'add_softdeletes_to_permissions.php',
            'add_softdeletes_to_activity_log_table.php',
            'setup_admin_roles_and_permissions.php'
        ];

        foreach($publishableMigrations as $migrationFileName) {
            $this->publishes([
                __DIR__ . "/../database/migrations/$migrationFileName" => $this->getMigrationFileName($migrationFileName),
            ], 'migrations');
        }
    }

     /**
     * Returns existing migration file if found, else uses the current timestamp.
     *
     * @return string
     */
    protected function getMigrationFileName($migrationFileName): string
    {
        $timestamp = Carbon::now()->addSeconds(3)->format('Y_m_d_His');

        $filesystem = $this->app->make(Filesystem::class);

        return Collection::make($this->app->databasePath().DIRECTORY_SEPARATOR.'migrations'.DIRECTORY_SEPARATOR)
            ->flatMap(function ($path) use ($filesystem, $migrationFileName) {
                return $filesystem->glob($path.'*_'.$migrationFileName);
            })
            ->push($this->app->databasePath()."/migrations/{$timestamp}_{$migrationFileName}")
            ->first();
    }

    protected function setConfigValues()
    {
        config(['activitylog.table_name'=> 'activity_logs']);
    }

    /**
     * Setup Middlewares
     *
     * @return void
     */
    protected function setupMiddlewares()
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('role', CheckRole::class);

        $this->registerMiddleware(CheckAdminDashboard::class);
    }

    /**
     * Register Middleware
     *
     * @param  string $middleware
     */
    protected function registerMiddleware($middleware)
    {
        $kernel = $this->app[Kernel::class];
        $kernel->pushMiddleware($middleware);
    }

    public function register()
    {
        
    }
}