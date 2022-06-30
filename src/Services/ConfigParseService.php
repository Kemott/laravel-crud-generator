<?php
    namespace Kemott\CrudGenerator\Services;

use Kemott\CrudGenerator\Enums\DatabaseRelations;
use Kemott\CrudGenerator\Enums\IdTypes;
use Kemott\CrudGenerator\Services\Classes\Models\Migration;

    class ConfigParseService
    {
        private $models = [];
        private $migrations = [];
        private $controllers = [];
        private $views = [];

        private $map = [
            "password" => "string",
            "email" => "string"
        ];

        public function getListOfFiles()
        {
            if(!$this->readConf()) return false;
            $this->getMigrationsFromModels();
            // print_r($this->models);
            return [
                'migrations' => $this->migrations,
                'controllers' => $this->controllers,
                'views' => $this->views,
            ];
        }

        private function readConf()
        {
            if(count(config('crudgenerator.models')) > 0){
                $this->models = config('crudgenerator.models');
                $this->models = $this->mergeWithDefaults($this->models);
                return true;
            }else{
                return false;
            }
        }

        private function mergeWithDefaults($models)
        {
            foreach($models as $tab)
            {
                $tab['rememberToken'] = isset($tab['rememberToken']) ? $tab['rememberToken'] : false;
                $tab['timestamps'] = isset($tab['timestamps']) ? $tab['timestamps'] : true;
                if(isset($tab['columns']))
                {
                    foreach($tab['columns'] as $columnName => $column)
                    {
                        if($columnName == 'id')
                        {
                            $column['type'] = isset($column['type']) ? $column['type'] : IdTypes::standard;
                        }else{
                            $column['type'] = isset($column['type']) ? $column['type'] : 'string';
                            $column['unique'] = isset($column['unique']) ? $column['unique'] : false;
                            $column['nullable'] = isset($column['nullable']) ? $column['nullable'] : false;
                        }
                        if($column['type'] == 'relation')
                        {
                            $column['relation']['id'] = isset($column['relation']['id']) ? $column['relation']['id'] : IdTypes::standard;
                            $column['relation']['type'] = isset($column['relation']['type']) ? $column['relation']['type'] : DatabaseRelations::oneToOne;
                        }
                    }
                }
            }
            //Coś jest nie tak
            return $models;
        }

        private function getMigrationsFromModels()
        {
            foreach($this->models as $name => $data)
            {
                $migration = new Migration($name);
                foreach($data['columns'] as $columnName => $column)
                {
                    if($this->isRelation($column)) continue;
                    if($columnName == 'id')
                    {
                        $migration->addIdColumn($column);
                        continue;
                    }
                    $migration->addColumn([
                        'name' => $columnName,
                        'type' => $column['type'] ? $this->mapType($column['type']) : 'string',
                        'unique' => $column['unique'] ?? false,
                        'nullable' => $column['nullable'] ?? false,
                    ]);
                    array_push($this->migrations, $migration);
                }
                $this->addSpecialColumns($migration, $data);
            }
        }

        private function isRelation($data)
        {
            if(!isset($data['type'])) return false;
            return $data['type'] == 'relation';
        }

        private function mapType($type)
        {
            return $this->map[$type] ?? $type;
        }

        private function addSpecialColumns(Migration $migration, $model)
        {
            if(isset($model['rememberToken']) && $model['rememberToken']) $migration->addColumn(['type' => 'rememberToken']);
            if(isset($model['timestamps']) && $model['timestamps']) $migration->addColumn(['type' => 'timestamps']);
        }
    }