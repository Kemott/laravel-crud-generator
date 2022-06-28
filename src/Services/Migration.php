<?php
    namespace TomaszBurzynski\CrudGenerator\Services;

    use TomaszBurzynski\CrudGenerator\Enums\IdTypes;

    class Migration
    {
        public $fileName = "";
        public $path;
        public $columns;

        private $fileContent;
        private $usedClasses = [];
        private $tabName;

        public function __construct($tableName, $columns)
        {
            $this->columns = $columns;
            $this->tabName = $tableName;
            foreach(scandir(database_path('migrations')) as $file)
            {
                $exploded = explode('_', $file);
                foreach($exploded as $i => $part)
                {
                    if($part == 'create' && $exploded[$i+1] == $tableName)
                    {
                        $this->fileName = $file;
                        break;
                    }
                }
                if($this->fileName == "") $this->fileName = date('Y_m_d_His').'_create_'.$tableName.'_table.php';
            }
            $this->path = database_path('migrations/'.$this->fileName);
            $this->fileContent = "<?php\n";
            $this->fileContent .= "\tuse Illuminate\Database\Migrations\Migration;\n";
            $this->fileContent .= "\tuse Illuminate\Database\Schema\Blueprint;\n";
            $this->fileContent .= "\tuse Illuminate\Support\Facades\Schema;\n\n";
        }

        public function generateFile()
        {
            $this->generateUseSection();
            $this->generateUpperPart();
            $this->generateSchemaCreateCode();
            $this->generateLowerPart();
            return $this->fileContent;
        }

        public function saveFile()
        {
            $file = fopen($this->path, 'w');
            fwrite($file, $this->fileContent);
            fclose($file);
        }

        private function generateUseSection()
        {
            foreach($this->usedClasses as $cl)
            {
                $this->fileContent .= "\tuse ";
                $this->fileContent .= $cl;
                $this->fileContent .= ";\n";
            }
        }

        private function generateUpperPart()
        {
            $this->fileContent .= "\treturn new class extends Migration\n";
            $this->fileContent .= "\t{\n";
            $this->fileContent .= "\t\t/**\n";
            $this->fileContent .= "\t\t* Run the migrations.\n";
            $this->fileContent .= "\t\t*\n";
            $this->fileContent .= "\t\t* @return void\n";
            $this->fileContent .= "\t\t*/\n";
            $this->fileContent .= "\t\tpublic function up()\n";
            $this->fileContent .= "\t\t{\n";
        }

        private function generateSchemaCreateCode()
        {
            $this->fileContent .= "\t\t\tSchema::create('".$this->tabName."', function (Blueprint \$table){\n";
            $this->generateSchemaColumns();                
            $this->fileContent .= "\t\t\t});\n";                
        }

        private function generateLowerPart()
        {
            $this->fileContent .= "\t\t}\n";
            $this->fileContent .= "\n";
            $this->fileContent .= "\t\t/**\n";
            $this->fileContent .= "\t\t* Reverse the migrations.\n";
            $this->fileContent .= "\t\t*\n";
            $this->fileContent .= "\t\t* @return void\n";
            $this->fileContent .= "\t\t*/\n";
            $this->fileContent .= "\t\tpublic function down()\n";
            $this->fileContent .= "\t\t{\n";
            $this->fileContent .= "\t\t\tSchema::dropIfExists('".$this->tabName."');\n";
            $this->fileContent .= "\t\t}\n";
            $this->fileContent .= "\t};";
        }

        private function generateSchemaColumns()
        {
            $normalColumns = [];
            $relations = [];
            foreach($this->columns as $columnName => $columnData)
            {
                if($this->setDefaults($columnData)['type'] == 'relation') $relations[$columnName] = $columnData;
                else $normalColumns[$columnName] = $columnData;
            }
            foreach($normalColumns as $columnName => $columnData)
            {
                $this->fileContent .= "\t\t\t\t";
                $this->fileContent .= "\$table->";
                $values = $this->setDefaults($columnData);
                if($columnName == 'id')
                {
                    foreach(IdTypes::cases() as $case)
                    {
                        if($case == $values['type']){
                            $this->fileContent .= $case->value;
                        }
                    }
                }else{
                    $this->fileContent .= $this->mapTypes($values['type']);
                    $this->fileContent .= "('";
                    $this->fileContent .= $columnName;
                    $this->fileContent .= "')";
                    if($values['nullable']) $this->fileContent .= "->nullable()";
                    if($values['unique']) $this->fileContent .= "->unique()";
                }
                $this->fileContent .= ";\n";
            }
            $this->prepareSpecialColumns();
        }

        private function mapTypes($type)
        {
            $types = [
                'email' => 'string',
                'password' => 'string',
            ];
            if(isset($types[$type])) return $types[$type];
            else return $type;
        }

        private function prepareSpecialColumns()
        {
            //Tutaj dodać takie pola jak rememberToken czy timestamps
        }

        private function setDefaults($data)
        {
            return [
                'type' => $data['type'] ?? 'string', 
                'nullable' => $data['nullable'] ?? false,
                'unique' => $data['unique'] ?? false,
            ];
        }
    }