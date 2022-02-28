<?php
namespace Zekini\CrudGenerator\Commands;

use Illuminate\Console\Command;

class GeneratePackageTableCrud extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:crud:package-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generates package crud for package tables";



    /**
     * Generates crud for default package tables
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Generating default model classes');

        $this->generateDefaultModelCruds();
        
    }

    protected function generateDefaultModelCruds():void
    {
        $this->call('admin:crud:generate', ['table' => 'users', '--user' => true, '--exclude'=> ['model']]);
        $this->call('admin:crud:generate', ['table' => 'permissions']);
        $this->call('admin:crud:generate', ['table' => 'roles']);
        $this->call('admin:crud:generate', ['table' => config('activitylog.table_name'), '--readonly' => true]);
    }

}