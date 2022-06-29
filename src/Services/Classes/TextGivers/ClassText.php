<?php
    namespace TomaszBurzynski\CrudGenerator\Services\Classes\TextGivers;

    class ClassText extends TextGiver
    {
        private $className;
        private $extendsNamespace;
        private $implements = [];
        private $traits = [];
        private $usedNamespaces = [];
        private $methods = [];

        public function __construct(string $className, int $startLevel = 1, string $extendsNamespace = '')
        {
            $this->className = $className;
            $this->level = $startLevel;
            $this->extendsNamespace = $extendsNamespace;
        }

        public function addInterface(string $interfaceNamespace)
        {
            $this->implements[] = $interfaceNamespace;
        }

        public function addTrait(string $traitNamespace)
        {
            $this->traits[] = $traitNamespace;
        }

        public function addMethod(MethodText $method)
        {
            $this->methods[] = $method;
            $this->usedNamespaces = array_merge($this->usedNamespaces, $method->getUsedNamespaces);
        }

        public function getUsageList()
        {
            $array = array_merge($this->implements, $this->traits);
            if($this->extendsNamespace) $array[] = $this->extendsNamespace;
            return $array;
        }

        public function getText()
        {
            $this->getStart();
            $this->generateMethods();
            $this->getEnd();
            return parent::getText();
        }

        private function getStart()
        {
            if($this->extendsNamespace) $this->modifiedFile->addClassUsage($this->extendsNamespace);
            foreach($this->implements as $interface)
            {
                $this->modifiedFile->addClassUsage($interface);
            }
            foreach($this->traits as $trait)
            {
                $this->modifiedFile->addClassUsage($trait);
            }
            $this->setLevel($this->level);
            $this->addLine($this->getClassFirstLine());
            $this->addLine('{');
            $this->levelDown();
            $this->addLine($this->getTraitsSection());
            $this->addLine();
        }

        private function getEnd()
        {
            $this->levelUp();
            $this->addLine('}');
        }

        private function generateMethods()
        {
            $this->addLine('Tutaj będą metody');
        }

        private function getClassFirstLine()
        {
            $txt = "class ".$this->className;
            if($this->extendsNamespace)
            {
                $txt .= " extends ".$this->getNameFromNamespace($this->extendsNamespace);
            }
            if(count($this->implements) > 0)
            {
                $txt .= " implements ";
                foreach($this->implements as $interface)
                {
                    $txt .= $this->getNameFromNamespace($interface).', ';
                }
                $txt = substr($txt, 0, -2);
            }
            return $txt;
        }

        private function getTraitsSection()
        {
            $txt = 'use ';
            foreach($this->traits as $traitNamespace)
            {
                $txt .= $this->getNameFromNamespace($traitNamespace);
                $txt .= ", ";
            }
            $txt = substr($txt, 0, -2);
            $txt .= ';';
            return $txt;
        }
    }