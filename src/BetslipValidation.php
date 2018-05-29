<?php

namespace Benasg\Bet;

class BetslipValidation extends AbstractValidation
{   
    const STAKE_AMOUNT_MIN = 0.3;
    const STAKE_AMOUNT_MAX = 10000;
    const SELECTIONS_MIN_NUMBER = 1;
    const SELECTIONS_MAX_NUMBER = 20;
    const ODD_MIN = 1;
    const ODD_MAX = 10000;
    const MAXIMUM_WIN = 20000;

    protected $betslip;

    function __construct(Betslip $betslip)
    {
        $this->betslip = $betslip;
    }

    public function getBetslip()
    {
        return $this->betslip;
    }

    /**
     * Checks min stake amount constraint
     * 
     * @return object
     */
    public function checkMinStakeAmount()
    {
        if ($this->betslip->getStakeAmount() < self::STAKE_AMOUNT_MIN) {
            $this->betslip->addGlobalError(sprintf($this->errorCodes[2], self::STAKE_AMOUNT_MIN));
            $this->betslip->setValid(false);
        }

        return $this;
    }

    /**
     * Checks max stake amount constraint
     * 
     * @return object
     */
    public function checkMaxStakeAmount()
    {
        if ($this->betslip->getStakeAmount() > self::STAKE_AMOUNT_MAX) {
            $this->betslip->addGlobalError(sprintf($this->errorCodes[3], self::STAKE_AMOUNT_MAX));
            $this->betslip->setValid(false);
        }

        return $this;
    }

    /**
     * Checks min selections number constraint
     * 
     * @return object
     */
    public function checkMinSelectionsNumber()
    {
        if (count($this->betslip->getSelections()) < self::SELECTIONS_MIN_NUMBER) {
            $this->betslip->addGlobalError(sprintf($this->errorCodes[4], self::SELECTIONS_MIN_NUMBER));
            $this->betslip->setValid(false);
        }

        return $this;
    }

    /**
     * Checks max selections number constraint
     * 
     * @return object
     */
    public function checkMaxSelectionsNumber()
    {
        if (count($this->betslip->getSelections()) > self::SELECTIONS_MAX_NUMBER) {
            $this->betslip->addGlobalError(sprintf($this->errorCodes[5], self::SELECTIONS_MAX_NUMBER));
            $this->betslip->setValid(false);
        }

        return $this;
    }

    /**
     * Checks selection unique id constraint
     * 
     * @return object
     */
    public function checkSelectionUniqueId()
    {   
        $events = [];

        foreach ($this->betslip->getSelections() as $index => $selection) {
            if (in_array($selection['id'], $events)) {
                $this->betslip->setValid(false);
                $this->betslip->addSelectionError($index, $this->errorCodes[8]);
            } else {
                $events[] = $selection['id'];
            }
        }
        
        return $this;
    }

    /**
     * Checks min odds interval constraint
     * 
     * @return object
     */
    public function checkMinOddsInterval()
    {
        foreach ($this->betslip->getSelections() as $index => $selection) {
            if ($selection['odds'] < self::ODD_MIN) {
                $this->betslip->addSelectionError($index, sprintf($this->errorCodes[6], self::ODD_MIN));
                $this->betslip->setValid(false);
            }
        }

        return $this;
    }

     /**
     * Checks max odds interval constraint
     * 
     * @return object
     */
    public function checkMaxOddsInterval()
    {
        foreach ($this->betslip->getSelections() as $index => $selection) {
            if ($selection['odds'] > self::ODD_MAX) {
                $this->betslip->addSelectionError($index, sprintf($this->errorCodes[7], self::ODD_MAX));
                $this->betslip->setValid(false);
            }
        }

        return $this;
    }

     /**
     * Checks expected win constraint
     * 
     * @return object
     */
    public function checkExpectedWin()
    {   
        if ($this->betslip->getExpectedWin() > self::MAXIMUM_WIN) {
            $this->betslip->addGlobalError(sprintf($this->errorCodes[9], self::MAXIMUM_WIN));
            $this->betslip->setValid(false);
        }

        return $this;
    }
}