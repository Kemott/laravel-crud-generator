<?php

namespace Kemott\CrudGenerator\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Kemott\CrudGenerator\Enums\IdTypes;
use Kemott\CrudGenerator\Misc\MigrationColumnsTable;

class MakeMigration extends GeneratorCommand
{
    protected $name = 'make:crud:migration';
    protected $signature = 'make:crud:migration {name} {idType} {column*} {--T|timestamps} {--U|usages=}';
    protected $description = 'Create filled migration';
    protected $type = 'Migration';

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

    protected function getIdTypeInput(): ?IdTypes
    {
        return match ($this->argument('idType')) {
            'standard' => IdTypes::standard,
            'uuid' => IdTypes::uuid,
            default => null,
        };
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
        if($this->option('usages') != null)
        {
            $usages = Str::of($this->option('usages'))->explode(";");
        }else
        {
            $usages = [];
        }

        return Str::of($content)->swap([
            "{{useSection}}" => $this->getUseSection($usages),
            "{{tableName}}" => Str::plural(Str::lower(parent::getNameInput())),
            "{{id}}" => $this->getIdLine(),
            "{{columns}}" => $this->getColumnsLines(),
            "{{timestamps}}" => $this->getTimestampsLine(),
        ]);
    }

    private function getUseSection(array|Collection $usages): string
    {
        $result = '';
        foreach($usages as $usage)
        {
            $result .= "use ".$usage.";\n\t";
        }
        return $result;
    }

    private function getIdLine(): string
    {
        return "\$table->".$this->getIdTypeInput()->value.";";
    }

    private function getColumnsLines(): string
    {
        $result = '';
        $columns = $this->mapColumnArguments();
        foreach($columns as $column)
        {
            $result .= "\$table->".$column['type']."(";
            if(Arr::has($column, 'params'))
            {
                $result .= $column['params'];
            }
            $result .= ")";
            if(Arr::hasAny($column, 'modifiers'))
            {
                foreach($column['modifiers'] as $modifier)
                {
                    $result .= "->".$modifier['type']."(";
                    if(Arr::hasAny($modifier,'params'))
                    {
                        $result .= $modifier['params'];
                    }
                    $result .= ")";
                }
            }
            $result .= ";\n\t\t\t\t";
        }
        return Str::of($result)->substr(0, -5);
    }

    private function getTimestampsLine(): string
    {
        if($this->option('timestamps')) return "\$table->timestamps();";
        else return '';
    }

    private function mapColumnArguments(): array
    {
        $args = $this->argument('column');
        $columns = [];
        foreach($args as $arg) {
            $tab = new MigrationColumnsTable($arg);
            $columns[] = $tab->getTable();
        }
        return $columns;
    }


}