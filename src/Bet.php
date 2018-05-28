<?php

namespace Benasg\Bet;

class Bet
{
    protected $success = true;
    protected $betslip;

    public function make($betslip = [])
    {
        $betslip = new Betslip($betslip);

        if (StructureValidation::validateStructure($betslip->getStructure(), $betslip->getBetslipArray())) {
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

        } else {
            $betslip->addGlobalError('Betslip structure mismatch');
            $betslip->setValid(false);
        }

        $this->setBetslip($betslip);

        $this->setSuccess($betslip->isValid());

        return $this;
    }

    protected function setBetslip($betslip)
    {
        $this->betslip = $betslip;
    }

    public function getBetslip()
    {
        return $this->betslip;
    }

    protected function setSuccess($success)
    {
        $this->success = $success;
    }

    public function isSuccess()
    {
        return $this->success;
    }
}