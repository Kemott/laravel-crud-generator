<?php
    namespace Kemott\CrudGenerator\Services;
    
    use Kemott\CrudGenerator\Services\Interfaces\GenerateService;
    use Kemott\CrudGenerator\Services\Classes\Collections\FileModelCollection;

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