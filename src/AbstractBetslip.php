<?php

namespace Benasg\Bet;

abstract class AbstractBetslip 
{
    protected $structure = [
        'player_id:i',
        'stake_amount:f',
        'selections:a' => ['id:i','odds:f']
    ];

    abstract function __construct(array $betslip);
    abstract public function getErrors();
    abstract public function isValid();
}