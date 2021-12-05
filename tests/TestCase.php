<?php
namespace Zekini\CrudGenerator\Tests;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Orchestra\Testbench\TestCase as Orchestra;
use Zekini\CrudGenerator\LivewireCrudGeneratorServiceProvider;

abstract class TestCase extends Orchestra
{

    public function setUp():void
    {
        parent::setUp();

        // setup application
        $this->artisan('admin:scafold');
       
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app): array
    {
        return [
            LivewireCrudGeneratorServiceProvider::class,
        ];
    }

    /**
     * Setup app environment
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
    
       // Setup default database to use sqlite :memory:
       $app['config']->set('database.default', 'testbench');
       $app['config']->set('database.connections.testbench', [
           'driver'   => 'sqlite',
           'database' => ':memory:',
           'prefix'   => '',
       ]);
    }


}