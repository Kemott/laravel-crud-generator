<?php
    namespace TomaszBurzynski\CrudGenerator\Services;
    
    use TomaszBurzynski\CrudGenerator\Services\Interfaces\GenerateService;
    use TomaszBurzynski\CrudGenerator\Services\Classes\Collections\FileModelCollection;

    class MigrationsGenerateService implements GenerateService
    {

        public function generateAll($config)
        {
            $collection = new FileModelCollection();
            foreach($config as $migration)
            {
                $collection->push($migration);
            }
            $collection->generateFiles();
        }
    }