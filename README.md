
# Introduction

  

The **zekini/livewire-crud-generator** package allows us to generate an admin panel and generate crud for applications

  

**Installation**

    composer require zekini/livewire-crud-generator



**Usage**

To scafold admin ui and authentication


    php artisan admin:scafold


To run the created migrations and access admin 

    php artisan migrate

To setup relationship mappings on a model simply go to the zekini-admin config under relationships

To generate crud for a particular model. Simply create the migration file for that crud and migrate to the database
After the you simply run admin:crud generate command wth the table name

    php artisan admin:crud:generate tablename

This will generate all views, routes, factories, controllers, permissions, requests, unit test, models need to crud this application
