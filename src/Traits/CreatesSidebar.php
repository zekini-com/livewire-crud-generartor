<?php
namespace Zekini\CrudGenerator\Traits;


trait CreatesSidebar
{
    
    /**
     * Makes sidebar navigation
     *
     * @return void
     */
    public function makeSideBar()
    {
        
        @$this->files->makeDirectory($path  = resource_path('views/admin/layout'), 0777, true);

        if (! $this->files->exists($filename = $path.'/sidebar.blade.php')) {
            $filename = $path.'/sidebar.blade.php';
            $view = "zekini/livewire-crud-generator::templates.sidebar";
            $templateContent =  view($view)->render();
            $this->files->put($filename, $templateContent);
        }

        // if file exists simply append the sidebar for this
        $view = "zekini/livewire-crud-generator::templates.sidebar-single";
        $resource = strtolower($this->getClassName());
        $resourceRoute =  "url('admin/$resource')";
        $templateContent =  view($view, [
            'modelBaseName'=> $this->getClassName(),
            'resourceRoute'=> $resourceRoute
        ])->render();

        $fileContent = $this->files->get($filename);

        // check if the sidebar does not exists
        if (strpos($fileContent, $resourceRoute) == false){
             // replace some things in the string before append
            $find = "{{--@AutoGenerator--}}";
            $replaceWith = $templateContent."
            $find";
            $this->files->put($filename, str_replace($find, $replaceWith, $fileContent));

        }

       // skip  and so nothings
    }
}