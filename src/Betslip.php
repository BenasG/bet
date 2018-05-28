<?php

namespace Benasg\Bet;

class Betslip
{
    protected $betslip = [];
    protected $isValid = true;
    protected $structure = [
        'player_id:i',
        'stake_amount:f',
        'selections:a' => ['id:i','odds:f']
    ];

    function __construct(array $betslip = [])
    {
        $this->betslip = $betslip;
    }

    public function getStructure()
    {
        return $this->structure;
    }

    public function setValid($valid)
    {
        $this->isValid = $valid;
    }

    public function isValid()
    {
        return $this->isValid;
    }

    public function addGlobalError($error)
    {
        $this->betslip['errors'][] = $error;
    }

    public function getBetslip()
    {
        return $this->betslip;
    }

    public function getPlayerId()
    {
        return $this->betslip['player_id'];
    }

    public function getStakeAmount()
    {
        return $this->betslip['stake_amount'];
    }

    public function getSelections() 
    {
        return $this->betslip['selections'];
    }

    public function getExpectedWin()
    {
        $expectedWin = 1;

        foreach ($this->getSelections() as $selection) {
            $expectedWin *= $selection['odds'];
        }

        return $expectedWin * $this->getStakeAmount();
    }

    public function getGlobalErrors()
    {
        return isset($this->betslip['errors']) ? $this->betslip['errors'] : [];
    }

    public function addSelectionError($index, $error)
    {
        $this->betslip['selections'][$index]['errors'][] = $error;
    }

    public function getSelectionErrors()
    {
        $selectionsErrors = [];

        if (isset($this->betslip['selections'])) {
            foreach ($this->betslip['selections'] as $selection) {
                if (isset($selection['errors'])) {
                    foreach ($selection['errors'] as $error) {
                        $selectionsErrors[] = $error;
                    }
                }
            }
        }

        return $selectionsErrors;
    }

    public function getErrors()
    {
        return [
            'globals' => $this->getGlobalErrors(),
            'selections' => $this->getSelectionErrors()
        ];
    }
}