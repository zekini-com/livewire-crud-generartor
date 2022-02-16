<?php

namespace Zekini\CrudGenerator\Commands\Generators;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Zekini\CrudGenerator\Traits\ColumnTrait;
use Zekini\CrudGenerator\Traits\HasRelations;

abstract class BaseGenerator extends Command
{
    use ColumnTrait;

    use HasRelations;

    protected $hidden = true;

    protected $classType;

    protected $className;

    protected $classNameKebab;

    protected $controllerNamespace;

    protected $namespace;

    protected $componentName;

    /**
     * files
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * __construct
     *
     * @param  \Illuminate\Filesystem\Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    protected abstract function getViewData();

    /**
     * rootNamespace
     */
    public function rootNamespace(): string
    {
        return $this->laravel->getNamespace();
    }

    /**
     * Get the name of the class
     */
    protected function getClassName(): string
    {
        return Str::studly(Str::singular($this->argument('table')));
    }

    protected function getLivewireComponentDir($addedPath = null)
    {
        $namespace = $this->getDefaultNamespace($this->rootNamespace());
        $path = $this->getPathFromNamespace($namespace) . DIRECTORY_SEPARATOR . Str::plural($this->className);
        return $addedPath ? $path . DIRECTORY_SEPARATOR . $addedPath : $path;
    }

    /**
     * Get the path of a file from the namespace
     */
    protected function getPathFromNamespace(string $namespace): string
    {
        // replace the slashes in the namespace
        $namespace = str_replace('\\', DIRECTORY_SEPARATOR, trim($namespace, '\\'));
        $namespace = preg_replace('/^App/', 'app', $namespace);
        return $namespace;
    }

    /**
     * Replaces the content in the file
     */
    protected function replaceContent(): string
    {
        $variables = $this->getViewData();

        $view = "zekini/stubs::templates." . $this->classType;

        return view($view, $variables)->render();
    }

    /**
     * Get Column Faker Map
     */
    protected function getColumnFakerMap(): \Illuminate\Support\Collection
    {
        $columns = $this->getColumnDetails();

        return $columns->map(function ($colArr) {
            return [
                'name' => $colArr['name'],
                'faker' => $this->decideFaker($colArr['type'], $colArr['name'])
            ];
        });
    }

    /**
     * Decide what faker to user
     */
    protected function decideFaker(string $type, string $name): string
    {
        if (Str::isRelation($name)) return "\App\Models\\" . ucfirst(Str::relationName($name)) . "::inRandomOrder()->value('id')";
        if ($name == 'name') return '$this->faker->name()';
        if ($name == 'email') return '$this->faker->unique()->safeEmail()';
        if ($name == 'image' || $name == 'file') return "[\Illuminate\Http\UploadedFile::fake()->image('file.jpg')]";
        if ($name == preg_match('/phone/', $name)) return '$this->faker->phoneNumber()';

        $faker = '';

        switch ($type) {
            case 'string':
                $faker =  '$this->faker->word()';
                break;
            case 'boolean':
                $faker =  '$this->faker->boolean()';
                break;
            case 'char':
                $faker =  '$this->faker->randomLetter()';
                break;
            case 'datetime':
                $faker =  '$this->faker->dateTime()';
                break;
            case 'float':
            case 'decimal':
                $faker =  '200.05';
                break;
            case 'integer':
            case 'bigint':
                $faker =  '$this->faker->randomNumber()';
                break;
            case 'text':
                $faker =  '$this->faker->sentence()';
                break;
            case 'json':
                $faker = 'json_encode(["faker_data"])';
                break;
            default:
                $faker =  '$this->faker->word()';
        }

        return $faker;
    }
}
