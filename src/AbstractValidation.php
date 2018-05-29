<?php

namespace Benasg\Bet;

abstract class AbstractValidation
{
    protected $errorCodes = [
        0  => 'Unknown error',
        1  => 'Betslip structure mismatch',
        2  => 'Minimum stake amount is %s',
        3  => 'Maximum stake amount is %s',
        4  => 'Minimum number of selections is %s',
        5  => 'Maximum number of selections is %s',
        6  => 'Minimum odds are %s',
        7  => 'Maximum odds are %s',
        8  => 'Duplicate IDs are not allowed',
        9  => 'Maximum win amount is %s',
        10 => 'Your previous action is not finished yet',
        11 => 'Insufficient balance',
    ];

    abstract function __construct(Betslip $betslip);
}