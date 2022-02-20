<?php

namespace Zekini\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Zekini\CrudGenerator\Helpers\Utilities;

class AdminScaffold extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:scaffold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command scaffolds the admin with permissions for CRUD';

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

        // Create file migration for the default admin user
        $this->publishVendors();

        $this->comment('Installing jetstream livewire');

        $this->call('jetstream:install --teams', ['stack' => 'livewire']);

        $this->comment('Generating default model classes');

        $this->generateDefaultModelCruds();

        $this->call('livewire-crud-generator:superadmin');

        $this->call('livewire-crud-generator:version');


        $this->comment('Congratulations on deploying version '. config('zekini-admin.version'));

        return Command::SUCCESS;
    }

    protected function generateDefaultModelCruds()
    {
        $this->call('admin:crud:generate', ['table' => 'users', '--user' => true]);
        $this->call('admin:crud:generate', ['table' => 'permissions']);
        $this->call('admin:crud:generate', ['table' => 'roles']);
        $this->call('admin:crud:generate', ['table' => config('activitylog.table_name'), '--readonly' => true]);
    }

    /**
     * Publish All vendors
     *
     * @return void
     */
    protected function publishVendors(): void
    {
        $this->publishSpatiePermissionVendor();

        $this->publishSpatieActivitylogVendor();

        $this->publishZekini();
    }

    protected function publishSpatiePermissionVendor(): void
    {
        $this->info(__FUNCTION__);

        self::undoPreviousMigrations([
            'model_has_permissions',
            'role_has_permissions',
            'permissions',
            'model_has_roles',
            'roles',
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'Spatie\\Permission\\PermissionServiceProvider',
            '--tag' => 'migrations'
        ]);

        $this->call('vendor:publish', [
            '--provider' => 'Spatie\\Permission\\PermissionServiceProvider',
            '--tag' => 'config'
        ]);
    }

    protected function publishSpatieActivitylogVendor(): void
    {
        $this->info(__FUNCTION__);

        self::undoPreviousMigrations(['activity_logs']);

        $this->call('vendor:publish', [
            '--provider' => "Spatie\Activitylog\ActivitylogServiceProvider",
            '--tag' => 'activitylog-migrations'
        ]);

        $this->call('vendor:publish', [
            '--provider' => "Spatie\Activitylog\ActivitylogServiceProvider",
            '--tag' => 'activitylog-config'
        ]);
    }

    protected function publishZekini(): void
    {
        $this->info(__FUNCTION__);

        $tags = [
            'config',
            'migrations',
            'resources',
            'views',
            'controllers'
        ];

        foreach ($tags as $tag) {
            $this->call('vendor:publish', [
                '--provider' => 'Zekini\\CrudGenerator\\LivewireCrudGeneratorServiceProvider',
                '--tag' => $tag
            ]);
        }
    }

    
    private static function undoPreviousMigrations(array $tablesArray): void
    {
        foreach ($tablesArray as $table) {
            Schema::dropIfExists($table);
            DB::table('migrations')->where('migration', 'like', '%' . $table . '%')->delete();
        }
    }
}
