<?php
    namespace TomaszBurzynski\CrudGenerator\Services\Classes\Models;

    use TomaszBurzynski\CrudGenerator\Services\Classes\TextGivers\TextGiver;
    use TomaszBurzynski\CrudGenerator\Services\Classes\TextGivers\ClassText;

    class FileModel extends TextGiver
    {
        protected $namespace = '';
        protected $usedClasses = [];
        protected $fileName = '';
        protected $path = '';

        public function __construct($startLevel = 0)
        {
            $this->level = $startLevel;
            $this->addLine("<?php");
            $this->LevelDown();
        }

        public function addClassUsage(string $classNamespace)
        {
            $this->usedClasses[] = $classNamespace;
        }

        public function generateUseSection()
        {
            $this->setLevel(1);
            foreach($this->usedClasses as $usedClass)
            {
                $this->addLine('use '.$usedClass.';');
            }
        }

        public function addClass(ClassText $classGenerator)
        {
            $this->fileContent .= $classGenerator->getText();
            foreach($classGenerator->getUsageList() as $useNamespace)
            {
                $this->usedClasses[] = $useNamespace;
            }
        }

        public function save()
        {
            $file = fopen($this->path, 'w');
            fwrite($file, $this->fileContent);
            fclose($file);
        }

    }