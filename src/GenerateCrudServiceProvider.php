<?php

namespace Kemott\CrudGenerator;

use Illuminate\Support\ServiceProvider;

use Kemott\CrudGenerator\Console\GenerateCrud;
use Kemott\CrudGenerator\Console\MakeMigration;
use Kemott\CrudGenerator\Services\ConfigParseService;
use Kemott\CrudGenerator\Services\ControllersGenerateService;
use Kemott\CrudGenerator\Services\CrudGenerationService;
use Kemott\CrudGenerator\Services\MigrationsGenerateService;
use Kemott\CrudGenerator\Services\ViewsGenerateService;

class GenerateCrudServiceProvider extends ServiceProvider
{
  public $bindings = [
      ConfigParseService::class => ConfigParseService::class,
      ControllersGenerateService::class => ControllersGenerateService::class,
      CrudGenerationService::class => CrudGenerationService::class,
      MigrationsGenerateService::class => MigrationsGenerateService::class,
      ViewsGenerateService::class => ViewsGenerateService::class
  ];

  public function register()
  {
    $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'crudgenerator');
  }

  public function boot()
  {
    if($this->app->runningInConsole()){
      $this->publishes([
        __DIR__.'/../config/config.php' => config_path('crudgenerator.php'),
      ], 'config');
      $this->commands([
          GenerateCrud::class,
          MakeMigration::class,
      ]);
    }
  }
}
