
  

# Introduction

 
The **zekini/livewire-crud-generator** package allows us to generate an admin panel and generate crud for applications

 
## Installation

`composer require --dev zekini/livewire-crud-generator`

## Usage
To scaffold admin ui and authentication
`php artisan admin:scaffold`

To run the created migrations
`php artisan migrate`

To generate a super admin
`php artisan admin:superuser`
ensure user class uses the `HasRoles` spatie trait

To generate crud for default package tables
`php artisan admin:crud:package-tables`

To setup relationship mappings on a model simply go to the zekini-admin config under relationships
To generate crud for a particular model. Simply create the migration file for that crud and migrate to the database

After the you simply run admin:crud generate command wth the table name

`php artisan admin:crud:generate tablename`
This will generate all views, routes, factories, controllers, permissions, requests, unit test, models need to crud this application

## Relationships
For tables with foreign keys ensure to update the relationships array of the zekini config file

Eg for a post relationship belonging to users. where record title is the record you want to show from the foreign table

	'posts'=> [
		[
		'name' => 'belongs_to',
		'table'=> 'users',
		'record_title'=> 'name'
		]
	]

## Images and Files
By default when a table has  column name of `image` or `file` the traits for file upload will automatically be generated and added but incase where your column name is an image and is not named in that manner you can do the following to process image uploads.
In your edit and create components
	`use  Livewire\WithFileUploads;`
	
Add this line just before the line that creates the model
	
	`$this->column_name = $this->getFile($this->column_name)`
	
For edit components you simply check if the first element of that array is a TemporaryUploadedFile

	`if($this->column_name[0] instanceof TemporaryUploadedFile) {
	$this->column_name = $this->getFile($this->column_name);
	$this->deleteFile($this->$modelClass->column_name); // delete old image
	}`
In your list component change the column to a callback to display an image

    `Column::callback(['column_name'], function ($column_name) {
		return view('zekini/livewire-crud-generator::datatable.image-display', ['file' => $column_name]);
    })->unsortable()->excludeFromExport(),`

In your form.blade.php for the model simply add the input type as file and multiple on it.

For your unit test replace the column values for a fake uploaded file in an array as files are threated as multiple uploads for a model                 `[\Illuminate\Http\UploadedFile::fake()->image('file.jpg')]` 
and also in your factories replace image column value with an array `'["file.jpeg"]'`


## Laravel Jetstream
With the integration of laravel jetstream, ensure you run the following commands
`npm install`
`npm run dev`

## Landing Page
In other to force delete the current landing page run
`php artisan vendor:publish --tags=views --force`

## Options
You can use this additional arguments with crud generator command to customize what components gets generated
`php artisan admin:crud:generate --only=component-datatable`
The above will generate only the datatable component

`php artisan admin:crud:generate --exclude=model`
The above will generate all other components except the model

`php artisan admin:crud:generate --readonly`
The above will generate all components with a readonly datatable

A full list of keys is given below

            'model' => 'Model Component',
            'route' => 'Route and Sidebar',
            'form' => 'Generate Form',
            'view-index' => 'Livewire Index view',
            'component-datatable' => 'Livewire datatable component',
            'component-index' => 'Livewire index component',
            'permission' => 'Generate Permissions',
            'test-datatable' => 'Datatable Test',
            'test-index' => 'Index Component Test',
            'factory' => 'Factory class'
            
