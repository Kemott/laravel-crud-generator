<?php
    namespace TomaszBurzynski\CrudGenerator\Enums;

    enum DatabaseRelations
    {
        case manyToMany;
        case oneToOne;
        case oneToManyParent;
        case oneToManyChild;
    }