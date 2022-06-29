<?php
    namespace TomaszBurzynski\CrudGenerator\Services\Classes\Models;

    use TomaszBurzynski\CrudGenerator\Enums\IdTypes;

    class Migration extends FileModel
    {
        private $tabName = '';
        private $columns = [];

        public function __construct($tableName)
        {
            $this->tabName = $tableName;
            $this->setName();
            $this->addClassUsage('Illuminate\Database\Migrations\Migration');
            $this->addClassUsage('Illuminate\Database\Schema\Blueprint');
            $this->addClassUsage('Illuminate\Support\Facades\Schema');
        }

        // public function generateFile()
        // {
        //     $this->generateUpperPart();
        //     $this->generateSchemaCreateCode();
        //     $this->generateLowerPart();
        //     return $this->fileContent;
        // }

        // private function generateUpperPart()
        // {
        //     $this->fileContent .= "\treturn new class extends Migration\n";
        //     $this->fileContent .= "\t{\n";
        //     $this->fileContent .= "\t\t/**\n";
        //     $this->fileContent .= "\t\t* Run the migrations.\n";
        //     $this->fileContent .= "\t\t*\n";
        //     $this->fileContent .= "\t\t* @return void\n";
        //     $this->fileContent .= "\t\t*/\n";
        //     $this->fileContent .= "\t\tpublic function up()\n";
        //     $this->fileContent .= "\t\t{\n";
        // }

        // private function generateSchemaCreateCode()
        // {
        //     $this->fileContent .= "\t\t\tSchema::create('".$this->tabName."', function (Blueprint \$table){\n";
        //     $this->generateSchemaColumns();                
        //     $this->fileContent .= "\t\t\t});\n";                
        // }

        // private function generateLowerPart()
        // {
        //     $this->fileContent .= "\t\t}\n";
        //     $this->fileContent .= "\n";
        //     $this->fileContent .= "\t\t/**\n";
        //     $this->fileContent .= "\t\t* Reverse the migrations.\n";
        //     $this->fileContent .= "\t\t*\n";
        //     $this->fileContent .= "\t\t* @return void\n";
        //     $this->fileContent .= "\t\t*/\n";
        //     $this->fileContent .= "\t\tpublic function down()\n";
        //     $this->fileContent .= "\t\t{\n";
        //     $this->fileContent .= "\t\t\tSchema::dropIfExists('".$this->tabName."');\n";
        //     $this->fileContent .= "\t\t}\n";
        //     $this->fileContent .= "\t};";
        // }

        // private function generateSchemaColumns()
        // {
        //     $normalColumns = [];
        //     $relations = [];
        //     foreach($this->columns as $columnName => $columnData)
        //     {
        //         if($this->setDefaults($columnData)['type'] == 'relation') $relations[$columnName] = $columnData;
        //         else $normalColumns[$columnName] = $columnData;
        //     }
        //     foreach($normalColumns as $columnName => $columnData)
        //     {
        //         $this->fileContent .= "\t\t\t\t";
        //         $this->fileContent .= "\$table->";
        //         $values = $this->setDefaults($columnData);
        //         if($columnName == 'id')
        //         {
        //             foreach(IdTypes::cases() as $case)
        //             {
        //                 if($case == $values['type']){
        //                     $this->fileContent .= $case->value;
        //                 }
        //             }
        //         }else{
        //             $this->fileContent .= $this->mapTypes($values['type']);
        //             $this->fileContent .= "('";
        //             $this->fileContent .= $columnName;
        //             $this->fileContent .= "')";
        //             if($values['nullable']) $this->fileContent .= "->nullable()";
        //             if($values['unique']) $this->fileContent .= "->unique()";
        //         }
        //         $this->fileContent .= ";\n";
        //     }
        //     $this->prepareSpecialColumns();
        // }

        /**
         * Odtąd efekt refaktoringu
         */

        public function addColumn($column)
        {
            array_push($this->columns, $column);
        }

        public function addIdColumn($column)
        {

        }

        private function setName()
        {
            $this->fileName = $this->migrationExists() ?? $this->buildName();
            $this->path = database_path('migrations/'.$this->fileName);
        }

        private function migrationExists()
        {
            foreach(scandir(database_path('migrations')) as $file)
            {
                $exploded = explode('_', $file);
                foreach($exploded as $i => $part)
                {
                    if($part == 'create' && $exploded[$i+1] == $this->tabName)
                    {
                        return $file;
                    }
                }
            }
            return false;
        }

        private function buildName()
        {
            return date('Y_m_d_His').'_create_'.$this->tabName.'_table.php';
        }
    }