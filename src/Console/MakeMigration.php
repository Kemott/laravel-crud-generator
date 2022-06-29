<?php

namespace TomaszBurzynski\CrudGenerator\Console;

use Illuminate\Support\Str;

class MakeMigration extends \Illuminate\Console\GeneratorCommand
{
    protected $name = 'make:crud:migration';
    protected $description = 'Create filled migration';
    protected $type = 'Migration';
    protected array $usages = [];

    /**
     * @inheritDoc
     */
    protected function getStub(): string
    {
        return __DIR__.'/stubs/crud.migration.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace."\\..\\database\\migrations";
    }

    protected function getNameInput(): string
    {
        return date('Y_m_d_His').'_create_'.Str::plural(Str::lower(parent::getNameInput())).'_table.php';
    }

    public function handle()
    {
        parent::handle();
        $this->fillGaps();
    }

    private function fillGaps()
    {
        $class = $this->qualifyClass($this->getNameInput());
        $path = $this->getPath($class);
        $content = file_get_contents($path);
        $content = $this->changePlaceholders($content);
        file_put_contents($path, $content);
    }

    private function changePlaceholders(string $content): string
    {
        $newContent = $this->changeUseSection($content);
        return $newContent;
    }

    private function changeUseSection(string $content): string
    {
        $newContent = $content;
        $replace = '';
        return Str::replace("{{useSection}}", $replace, $newContent);
    }


}