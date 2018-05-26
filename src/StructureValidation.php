<?php

namespace Benasg\Bet;

class StructureValidation
{
    public static function validateStructure($structure = [], $data = [])
    {
        $structureValidation = \Garden\Schema\Schema::parse($structure);
        
        return $structureValidation->isValid($data);
    }
}