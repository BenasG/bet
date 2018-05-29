<?php

namespace Benasg\Bet;

class Bet
{
    protected $success = true;
    protected $betslip;

    public function make($betslip = [])
    {
        $this->validate(new Betslip($betslip));

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

    protected function validate(Betslip $betslip)
    {
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
    }
}