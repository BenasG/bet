<?php

namespace Benasg\Bet;

require_once __DIR__ . '/../vendor/autoload.php';

include 'Bet.php';

$betslip = [
    // type: int
    // (mandatory) unique player id in the system
    'player_id' => 1,

    // type: float
    // (mandatory) amount of money player wants to bet
    'stake_amount' => 10,

    // type: array
    // (optional) error codes of betslip level errors
    'errors' => [],

    // type: array
    // (mandatory) selection (events) on which player wants to bet
    'selections' => [
        [
            // type: int
            // (mandatory) selection (event) ID on which player want to bet
            'id' => 1,

            // type: float, max number of numbers after dot is 3
            // (mandatory) odds (coefficient) of our selection,
            'odds' => 1.601,

            // type: array
            // (optional) error codes of selection level errors
            'errors' => [],
        ],
        [
            // type: int
            // (mandatory) selection (event) ID on which player want to bet
            'id' => 2,

            // type: float, max number of numbers after dot is 3
            // (mandatory) odds (coefficient) of our selection,
            'odds' => 1.601,

            // type: array
            // (optional) error codes of selection level errors
            'errors' => [],
        ],
    ],
];

$bet = new Bet();
$bet->make($betslip);

var_dump($bet->getSuccess());

// $schema = \Garden\Schema\Schema::parse(
//     [
//         'player_id:i',
//         'stake_amount:f',
//         'selections:a' => ['id:i','odds:f']
//     ]
// );

// try {
//     // $u1 will be ['id' => 123, 'name' => 'John']
//     $u1 = $schema->validate($betslip);
    
// } catch (Garden\Schema\ValidationException $ex) {
//     // $ex->getMessage() will be: 'id is not a valid integer. name is required.'
//     print_r($ex->getMessage());  
// }





