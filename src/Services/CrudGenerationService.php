<?php
    namespace Kemott\CrudGenerator\Services;

    class CrudGenerationService
    {
        private $parser;
        private $migrationsService;
        private $controllersService;
        private $viewsService;

        public function __construct(ConfigParseService $parser,
            MigrationsGenerateService $migrationsService,
            ControllersGenerateService $controllersService,
            ViewsGenerateService $viewsService)
        {
            $this->parser = $parser;
            $this->migrationsService = $migrationsService;
            $this->controllersService = $controllersService;
            $this->viewsService = $viewsService;
        }

        public function generateCRUD()
        {
            $files = $this->parser->getListOfFiles();
            $this->migrationsService->generateAll($files['migrations']);
            $this->controllersService->generateAll($files['controllers']);
            $this->viewsService->generateAll($files['views']);
        }
    }