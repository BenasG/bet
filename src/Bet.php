<?php

namespace Benasg\Bet;

class Bet
{
    protected $success = true;
    protected $betslip = [];
    protected $structure = [
        'player_id:i',
        'stake_amount:f',
        'selections:a' => ['id:i','odds:f']
    ];

    public function make($betslip = [])
    {
        $this->setBetslip($betslip);

        if (StructureValidation::validateStructure($this->structure, $betslip)) {
            $betslipValidation = new BetslipValidation($betslip);
            
            $betslipValidation
                ->checkMinStakeAmount()
                ->checkMaxStakeAmount()
                ->checkMinSelectionsNumber()
                ->checkMaxSelectionsNumber()
                ->checkSelectionUniqueId()
                ->checkMinOddsInterval()
                ->checkMaxOddsInterval()
                ->checkExpectedWin();
            
            $this->setSuccess($betslipValidation->getIsValid());

        } else {
            $this->setSuccess(false);
        }
    }

    public function setSuccess($success)
    {
        $this->success = $success;
    }

    public function getSuccess()
    {
        return $this->success;
    }

    public function setBetslip($betslip)
    {
        $this->betslip = $betslip;
    }

    public function getBetslip($betslip)
    {
        return $this->betSlip;
    }
}