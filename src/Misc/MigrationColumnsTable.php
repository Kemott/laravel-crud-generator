<?php

namespace Kemott\CrudGenerator\Misc;

use Illuminate\Support\Str;

class MigrationColumnsTable extends ParamOfTable
{
    public function unserialize(string $data): ParamOfTable
    {
        $modifiersTab = [];
        $exploded = Str::of($data)->explode(';');
        if(isset($exploded[2])) {
            $modifiersText = $exploded[2];
            $modifiersList = Str::of($modifiersText)->explode('-');
            foreach ($modifiersList as $modifier) {
                $expl = Str::of($modifier)->explode(':');
                $modifiersTab[] = [
                    'type' => $expl[0],
                    'params' => $expl[1] ?? ''
                ];
            }
        }
        $this->table = [
            'type' => $exploded[0],
            'params' => $exploded[1] ?? '',
            'modifiers' => $modifiersTab
        ];
        return $this;
    }
}