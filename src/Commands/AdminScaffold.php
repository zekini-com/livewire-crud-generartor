<?php

namespace Zekini\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
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
        // Setup the zekiniAdmin authentication guard
        $this->setupAdminAuthGuard();

        // Create file migration for the default admin user
        $this->publishVendors();

        $this->info('Email :  ' . config('zekini-admin.defaults.default-email'));
        $this->info('Password : ' . config('zekini-admin.defaults.default-password'));

        // migrate generated tables
        $this->call('migrate');

        $this->generateDefaultModelCruds();

        //call jetstream installation
        $this->call('jetstream:install', ['stack' => 'livewire']);

        return Command::SUCCESS;
    }

    protected function generateDefaultModelCruds()
    {
        $this->call('admin:crud:generate', ['table' => 'zekini_admins', '--user' => true]);
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

        Schema::dropIfExists('permissions');

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

        Schema::dropIfExists('activity_logs');

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

        $tags = ['config', 'migrations', 'resources', 'views', 'controllers'];

        foreach ($tags as $tag) {
            $this->call('vendor:publish', [
                '--provider' => 'Zekini\\CrudGenerator\\LivewireCrudGeneratorServiceProvider',
                '--tag' => $tag
            ]);
        }
    }


    /**
     * We try to setup the admin guards for authentication same thing we will do manually
     *
     * @return void
     */
    protected function setupAdminAuthGuard(): void
    {
        $pathToFile = config_path('auth.php');

        $find = '\'guards\' => [';
        $replaceWith = '\'guards\' => [
        \'zekini_admin\'=> [
            \'driver\' => \'session\',
            \'provider\' => \'zekini_admins\'
        ],';

        Utilities::strReplaceInFile($pathToFile, $find, $replaceWith);

        $find = '\'providers\' => [';
        $replaceWith = '\'providers\' => [
        \'zekini_admins\'=> [
            \'driver\' => \'eloquent\',
            \'model\' => Zekini\CrudGenerator\Models\ZekiniAdmin::class
        ],';

        Utilities::strReplaceInFile($pathToFile, $find, $replaceWith);

        $find = '\'passwords\' => [';
        $replaceWith = '\'passwords\' => [
        \'zekini_admins\' => [
            \'provider\' => \'zekini_admins\',
            \'table\' => \'zekini_admin_password_resets\',
            \'expire\' => 60,
            \'throttle\' => 60
        ],';

        Utilities::strReplaceInFile($pathToFile, $find, $replaceWith);
    }
}
