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

        $this->call('jetstream:install', ['stack' => 'livewire']);

        $this->call('livewire-crud-generator:version');

        $this->comment('Congratulations on deploying version '. config('zekini-admin.version'));

        $this->comment('Run php artisan migrate and npm run dev to continue');

        return Command::SUCCESS;
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
        if (!Schema::hasTable('migrations')) { // if no migration exit
            return;
        }

        foreach ($tablesArray as $table) {
            Schema::dropIfExists($table);
            DB::table('migrations')->where('migration', 'like', '%' . $table . '%')->delete();
        }
    }
}
