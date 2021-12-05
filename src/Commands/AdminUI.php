<?php
namespace Zekini\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Zekini\CrudGenerator\Helpers\Utilities;

class AdminUI extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:ui-scafold';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command scafolds the admin ui';

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
    public function handle(Filesystem $files)
    {
       // Require core ui
       $this->requireCoreUIPackageJson($files);

       $this->prepareWebPackFile($files);

       //publish any vendor files to where they belong
        
        return Command::SUCCESS;
    }
    
    /**
     * Requires Core UI In the package.json file
     *
     * @return void
     */
    protected function requireCoreUIPackageJson($files)
    {
        $packageJsonFile = base_path('package.json');
        $packageJson = $files->get($packageJsonFile);
        $packageJsonContent = json_decode($packageJson, JSON_OBJECT_AS_ARRAY);
        $packageJsonContent["dependencies"]["@coreui/coreui"] = "^4.1.0";

        $files->put($packageJsonFile, json_encode($packageJsonContent, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        $this->info('package.json changed');
    }
    
    /**
     * Prepares Webpack File
     *
     * @param  mixed $files
     * @return void
     */
    protected function prepareWebPackFile($files)
    {
        $content = File::get('webpack.mix.js');
        if (preg_match('|resources/admin/scss|', $content)) return;
    
        $webpackConfig = $files->get('./../../stubs/admin-ui/webpack.mix.js');

        File::put('webpack.mix.js', $content.$webpackConfig);
    }


}