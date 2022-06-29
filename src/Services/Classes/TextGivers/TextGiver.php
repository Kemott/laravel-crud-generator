<?php
    namespace TomaszBurzynski\CrudGenerator\Services\Classes\TextGivers;

    use Illuminate\Support\Str;

    class TextGiver
    {
        protected $level = 0;
        protected $fileContent = "";

        public function addLine($content = '')
        {
            if($this->level > 0)
            {
                for($i = 0; $i < $this->level; $i++)
                {
                    $this->fileContent .= "/t";
                }
            }
            $this->fileContent .=  $content."\n";
        }

        public function levelDown()
        {
            $this->level = $this->level + 1;
        }

        public function levelUp()
        {
            $this->level = $this->level - 1;
        }

        public function setLevel(int $level)
        {
            $this->level = $level;
        }

        public function addPlaceholder(string $name)
        {
            $this->fileContent .= "{{".$name."}}";
        }

        public function changePlaceholder(string $placeholderName, string $newContent)
        {
            $this->fileContent = Str::replace('{{'.$placeholderName.'}}', $newContent, $this->fileContent);
        }

        public function getText()
        {
            return $this->fileContent;
        }

        protected function getNameFromNamespace(string $namespace)
        {
            return Str::of($namespace)->explode('\\')->last();
        }
    }