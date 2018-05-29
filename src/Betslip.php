<?php

namespace Benasg\Bet;

class Betslip extends AbstractBetslip
{
    /**
     * @param array
     */
    protected $betslip = [];

    /**
     * @param boolean
     */
    protected $isValid = true;

    function __construct(array $betslip = [])
    {
        $this->setBetslip($betslip);
    }

    /**
     * Set betslip
     */
    public function setBetslip($betslip)
    {
        $this->betslip = $betslip;
    }

    /**
     * Get structure
     * 
     * @return array
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set valid
     */
    public function setValid($valid)
    {
        $this->isValid = $valid;
    }

    /**
     * Checks if valid
     * 
     * @return boolean
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * Add global error
     */
    public function addGlobalError($error)
    {
        $this->betslip['errors'][] = $error;
    }

    /**
     * Get betslip array
     * 
     * @return array
     */
    public function getBetslipArray(): array
    {
        return $this->betslip;
    }

    /**
     * Get player id
     * 
     * @return integer
     */
    public function getPlayerId()
    {
        return $this->betslip['player_id'];
    }

    /**
     * Get stake amount
     * 
     * @return integer
     */
    public function getStakeAmount()
    {
        return isset($this->betslip['stake_amount']) ? $this->betslip['stake_amount'] : 0;
    }

    /**
     * Get selections
     * 
     * @return array
     */
    public function getSelections(): array
    {
        return isset($this->betslip['selections']) ? $this->betslip['selections'] : [];
    }

    /**
     * Get expected win
     * 
     * @return integer
     */
    public function getExpectedWin()
    {
        $expectedWin = 1;

        foreach ($this->getSelections() as $selection) {
            $expectedWin *= $selection['odds'];
        }

        return $expectedWin * $this->getStakeAmount();
    }

    /**
     * Get global errors
     * 
     * @return array
     */
    public function getGlobalErrors(): array
    {
        return isset($this->betslip['errors']) ? $this->betslip['errors'] : [];
    }

    /**
     * Add selection error
     */
    public function addSelectionError($index, $error)
    {
        $this->betslip['selections'][$index]['errors'][] = $error;
    }

    /**
     * Get betslip selection errors
     * 
     * @return array
     */
    public function getSelectionErrors(): array
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

    /**
     * Get all errors
     * 
     * @return array
     */
    public function getErrors(): array
    {
        return [
            'globals' => $this->getGlobalErrors(),
            'selections' => $this->getSelectionErrors()
        ];
    }
}