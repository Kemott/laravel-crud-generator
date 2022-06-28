<?php
    namespace TomaszBurzynski\CrudGenerator\Services;

    use Illuminate\Console\Command;
    use Illuminate\Support\Str;

    use TomaszBurzynski\CrudGenerator\Services\Classes\Migration;

    class MigrationsGenerateService
    {
        private $models;

        public function getModels(Command $command){
            $command->info('Looking for models from config file...');
            if(count(config('crudgenerator.models')) > 0){
                $command->info('Models found');
                $this->models = config('crudgenerator.models');
                return true;
            }else{
                $command->info('No models to generate crud');
                return false;
            }
        }
        
        public function generateMigrations(Command $command)
        {
            $command->info('Creating list of migrations..');
            foreach($this->models as $name => $data)
            {
                $migration = new Migration(Str::plural(Str::lower($name)), $data['columns']);
                $command->info('Generating '.$name.' migration');
                $this->generateMigration($migration, $command);
            }
        }

        private function generateMigration(Migration $migration, Command $command)
        {
            $command->info($migration->fileName);
            $migration->generateFile();
            $migration->saveFile();
            $command->info('Wygenerowano');
        }
    }