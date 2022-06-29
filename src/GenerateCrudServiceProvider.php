<?php

namespace TomaszBurzynski\CrudGenerator;

use Illuminate\Support\ServiceProvider;

use TomaszBurzynski\CrudGenerator\Console\GenerateCrud;
use TomaszBurzynski\CrudGenerator\Console\MakeMigration;
use TomaszBurzynski\CrudGenerator\Services\ConfigParseService;
use TomaszBurzynski\CrudGenerator\Services\ControllersGenerateService;
use TomaszBurzynski\CrudGenerator\Services\CrudGenerationService;
use TomaszBurzynski\CrudGenerator\Services\MigrationsGenerateService;
use TomaszBurzynski\CrudGenerator\Services\ViewsGenerateService;

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
