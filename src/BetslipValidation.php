<?php

namespace Benasg\Bet;

class BetslipValidation
{   
    const STAKE_AMOUNT_MIN = 0.3;
    const STAKE_AMOUNT_MAX = 10000;
    const SELECTIONS_MIN_NUMBER = 1;
    const SELECTIONS_MAX_NUMBER = 20;
    const ODD_MIN = 1;
    const ODD_MAX = 10000;
    const MAXIMUM_WIN = 20000;

    protected $betslip = [];
    protected $isValid = true;

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

    function __construct($betslip = [])
    {
        $this->betslip = $betslip;
    }

    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
    }

    public function getIsValid()
    {
        return $this->isValid;
    }

    public function setBetslip($betslip)
    {
        $this->betslip = $betslip;
    }

    public function getBetslip()
    {
        return $this->betslip;
    }

    public function checkMinStakeAmount()
    {
        if ($this->betslip['stake_amount'] < self::STAKE_AMOUNT_MIN) {
            $this->betslip['errors'][] = sprintf($this->errorCodes[2], self::STAKE_AMOUNT_MIN);
            $this->setIsValid(false);
        }

        return $this;
    }

    public function checkMaxStakeAmount()
    {
        if ($this->betslip['stake_amount'] > self::STAKE_AMOUNT_MAX) {
            $this->betslip['errors'][] = sprintf($this->errorCodes[3], self::STAKE_AMOUNT_MAX);
            $this->setIsValid(false);
        }

        return $this;
    }

    public function checkMinSelectionsNumber()
    {
        if (count($this->betslip['selections']) < self::SELECTIONS_MIN_NUMBER) {
            $this->betslip['errors'][] = sprintf($this->errorCodes[4], self::SELECTIONS_MIN_NUMBER);
            $this->setIsValid(false);
        }

        return $this;
    }

    public function checkMaxSelectionsNumber()
    {
        if (count($this->betslip['selections']) > self::SELECTIONS_MAX_NUMBER) {
            $this->betslip['errors'][] = sprintf($this->errorCodes[5], self::SELECTIONS_MAX_NUMBER);
            $this->setIsValid(false);
        }

        return $this;
    }

    public function checkSelectionUniqueId()
    {   
        $events = [];

        foreach ($this->betslip['selections'] as $index => $selection) {
            if (in_array($selection['id'], $events)) {
                $this->setIsValid(false);
                $this->betslip['selections'][$index]['errors'][] = $this->errorCodes[8];
            } else {
                $events[] = $selection['id'];
            }
        }
        
        return $this;
    }

    public function checkMinOddsInterval()
    {
        foreach ($this->betslip['selections'] as $index => $selection) {
            if ($selection['odds'] < self::ODD_MIN) {
                $this->betslip['selections'][$index]['errors'][] = sprintf($this->errorCodes[6], self::ODD_MIN);
                $this->setIsValid(false);
            }
        }

        return $this;
    }

    public function checkMaxOddsInterval()
    {
        foreach ($this->betslip['selections'] as $index => $selection) {
            if ($selection['odds'] > self::ODD_MAX) {
                $this->betslip['selections'][$index]['errors'][] = sprintf($this->errorCodes[7], self::ODD_MAX);
                $this->setIsValid(false);
            }
        }

        return $this;
    }

    public function checkExpectedWin()
    {   
        $odds = 1;

        foreach ($this->betslip['selections'] as $selection) {
            $odds *= $selection['odds'];
        }

        if ($this->betslip['stake_amount'] * $odds > self::MAXIMUM_WIN) {
            $this->betslip['errors'][] = sprintf($this->errorCodes[9], self::MAXIMUM_WIN);
            $this->setIsValid(false);
        }

        return $this;
    }
}