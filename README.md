# Bet
Betslip validation composer package

# Install

```
composer require benasg/bet
```

# Usage

```
use Benasg\Bet\Bet;

$response = (new Bet)->make($betslip);

```
# Betslip example

```
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
```
# Available methods
```
isSuccess();
```
```
getBetslip();
```
```
getBetslip()->isValid();
```
```
getBetslip()->getBetslipArray();
```
```
getBetslip()->getPlayerId();
```
```
getBetslip()->getStakeAmount();
```
```
getBetslip()->getSelections();
```
```
getBetslip()->getExpectedWin();
```
```
getBetslip()->getGlobalErrors();
```
```
getBetslip()->getSelectionErrors();
```
```
getBetslip()->getErrors();
```
