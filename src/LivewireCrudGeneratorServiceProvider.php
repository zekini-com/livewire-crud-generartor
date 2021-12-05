<?php
namespace Zekini\CrudGenerator;

use Illuminate\Support\Collection;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class LivewireCrudGeneratorServiceProvider extends ServiceProvider

{

     /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {

        $this->commands([
            Commands\AdminScafold::class,
        ]);

        $this->loadViewsFrom(__DIR__.'./../resources/views', 'zekini/livewire-crud-generator');
        $this->loadRoutesFrom(__DIR__.'./../routes/web.php');

        // register commands
        if ($this->app->runningInConsole()) {
           $this->publishConfig();
           $this->publishMigrations();
        }
        
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

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/zekini-admin.php',
            'zekini-admin'
        );
    }
}