<?php
    namespace TomaszBurzynski\CrudGenerator\Services\Classes\Collections;

    use Illuminate\Support\Collection;
    use TomaszBurzynski\CrudGenerator\Services\Interfaces\FileModel;

    class FileModelCollection extends Collection
    {
        public function push($model)
        {
            if($model instanceof FileModel){
                parent::push($model);
                return $this;
            }else{
                return false;
            }
        }

        public function generateFiles()
        {
            foreach($this->all() as $model)
            {
                $model->generateFile();
            }
            return $this;
        }
    }