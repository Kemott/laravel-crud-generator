<?php
    namespace TomaszBurzynski\CrudGenerator\Services\Interfaces;

    use TomaszBurzynski\CrudGenerator\Services\Classes\Collections\FileModelCollection;

    interface GenerateService
    {
        public function generateAll($config);
    }