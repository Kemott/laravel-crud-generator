<?php

namespace TomaszBurzynski\CrudGenerator\Console;

use Illuminate\Console\Command;

use TomaszBurzynski\CrudGenerator\Services\CrudGenerationService;

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
    public function handle(CrudGenerationService $crudService)
    {
        $crudService->generateCRUD();
        return 0;
    }
}
