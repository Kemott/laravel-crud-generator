<?php

namespace TomaszBurzynski\CrudGenerator\Console;

use Illuminate\Console\Command;

use TomaszBurzynski\CrudGenerator\Services\MigrationsGenerateService;

class GenerateCrud extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create crud files based on configuration file';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(MigrationsGenerateService $migrationsService)
    {
        if(!$migrationsService->getModels($this)) return 0;
        $migrationsService->generateMigrations($this);
        return 0;
    }
}
