<?php

namespace Kemott\CrudGenerator\Misc;

use Exception;
use Illuminate\Support\Str;

class ParamOfTable implements \Serializable
{
    protected array $table = [];

    public function __construct(string|array $tab)
    {
        if(gettype($tab) == 'string') return $this->unserialize($tab);
        elseif(is_array($tab))
        {
            $this->table = $tab;
            return $this;
        }else{
            return null;
        }
    }

    /**
     * String representation of object.
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string|null The string representation of the object or null
     * @throws Exception Returning other type than string or null
     */
    public function serialize(): ?string
    {
        $result = '';
        foreach($this->table as $element)
        {
            if(is_array($element))
            {
                foreach($element as $subElement)
                {
                    if(is_array($subElement)) {
                        foreach ($subElement as $subSubElement)
                        {
                            $result .= $subSubElement.':';
                        }
                        $result = Str::of($result)->substr(0,-1);
                    }else {
                        $result .= $subElement . '-';
                    }
                }
                $result = Str::of($result)->substr(0,-1);
            }else {
                $result .= $element.';';
            }
        }
        return Str::of($result)->substr(0, -1);;
    }

    /**
     * Constructs the object.
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $data The string representation of the object.
     * @return ParamOfTable
     */
    public function unserialize(string $data): ParamOfTable
    {
        return $this;
    }

    public function getTable(): array
    {
        return $this->table;
    }
}