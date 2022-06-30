<?php
    namespace Kemott\CrudGenerator\Enums;

    enum IdTypes : String
    {
        case standard = "id()";
        case uuid = "uuid('id')->primary()";
    }