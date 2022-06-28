<?php

namespace TomaszBurzynski\CrudGenerator;

use Illuminate\Support\ServiceProvider;

use TomaszBurzynski\CrudGenerator\Console\GenerateCrud;

class GenerateCrudServiceProvider extends ServiceProvider
{
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
        GenerateCrud::class
      ]);
    }
  }
}
