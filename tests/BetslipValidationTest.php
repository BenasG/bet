<?php

use PHPUnit\Framework\TestCase;
use Benasg\Bet\Betslip;
use Benasg\Bet\BetslipValidation;

class BetslipValidationTest extends TestCase
{
    /**
     * @dataProvider betslipProvider
     */
    public function testMinStakeAmount($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkMinStakeAmount();
        $this->assertEquals(1, $betslip->isValid());

        // Incorrect stake_amount
        $modifiedBetslip = $betslip->getBetslipArray();
        $modifiedBetslip['stake_amount'] = 0;
        $betslip->setBetslip($modifiedBetslip);

        $betslipValidation->checkMinStakeAmount();
        $this->assertEquals(0, $betslip->isValid());
        $this->assertEquals('Minimum stake amount is 0.3', $betslip->getGlobalErrors()[0]);
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testMaxStakeAmount($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkMaxStakeAmount();
        $this->assertEquals(1, $betslip->isValid());

        // Incorrect stake_amount
        $modifiedBetslip = $betslip->getBetslipArray();
        $modifiedBetslip['stake_amount'] = 99999;
        $betslip->setBetslip($modifiedBetslip);

        $betslipValidation->checkMaxStakeAmount();
        $this->assertEquals(0, $betslip->isValid());
        $this->assertEquals('Maximum stake amount is 10000', $betslip->getGlobalErrors()[0]);
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testMinSelectionsNumber($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkMinSelectionsNumber();
        $this->assertEquals(1, $betslip->isValid());

        // Incorrect selections number
        $modifiedBetslip = $betslip->getBetslipArray();
        unset($modifiedBetslip['selections'][0]);
        $betslip->setBetslip($modifiedBetslip);

        $betslipValidation->checkMinSelectionsNumber();
        $this->assertEquals(0, $betslip->isValid());
        $this->assertEquals('Minimum number of selections is 1', $betslip->getGlobalErrors()[0]);
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testMaxSelectionsNumber($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkMaxSelectionsNumber();
        $this->assertEquals(1, $betslip->isValid());

        // Incorrect selections number
        $modifiedBetslip = $betslip->getBetslipArray();
        for ($i = 0; $i < 20; $i++) {
            $modifiedBetslip['selections'][] = [
                'id' => 1,
                'odds' => 1.601,
                'errors' => [],
            ];
        }
        $betslip->setBetslip($modifiedBetslip);

        $betslipValidation->checkMaxSelectionsNumber();
        $this->assertEquals(0, $betslip->isValid());
        $this->assertEquals('Maximum number of selections is 20', $betslip->getGlobalErrors()[0]);
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testSelectionUniqueId($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkSelectionUniqueId();
        $this->assertEquals(1, $betslip->isValid());

        // Incorrect selection event id
        $modifiedBetslip = $betslip->getBetslipArray();
        $modifiedBetslip['selections'][] = [
            'id' => 1,
            'odds' => 1.601,
            'errors' => [],
        ];
        $betslip->setBetslip($modifiedBetslip);

        $betslipValidation->checkSelectionUniqueId();
        $this->assertEquals(0, $betslip->isValid());
        $this->assertEquals(
            'Duplicate IDs are not allowed',
            $betslip->getSelectionErrors()[0]
        );
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testMinOddsInterval($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkMinOddsInterval();
        $this->assertEquals(1, $betslip->isValid());

        // Incorrect min odd
        $modifiedBetslip = $betslip->getBetslipArray();
        $modifiedBetslip['selections'][0]['odds'] = 0;
        $betslip->setBetslip($modifiedBetslip);

        $betslipValidation->checkMinOddsInterval();
        $this->assertEquals(0, $betslip->isValid());
        $this->assertEquals(
            'Minimum odds are 1',
            $betslip->getSelectionErrors()[0]
        );
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testMaxOddsInterval($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkMaxOddsInterval();
        $this->assertEquals(1, $betslip->isValid());

        // Incorrect max odd
        $modifiedBetslip = $betslip->getBetslipArray();
        $modifiedBetslip['selections'][0]['odds'] = 99999;
        $betslip->setBetslip($modifiedBetslip);

        $betslipValidation->checkMaxOddsInterval();
        $this->assertEquals(0, $betslip->isValid());
        $this->assertEquals(
            'Maximum odds are 10000',
            $betslip->getSelectionErrors()[0]
        );
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testExpectedWin($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkExpectedWin();
        $this->assertEquals(1, $betslip->isValid());

        $modifiedBetslip = $betslip->getBetslipArray();
        $modifiedBetslip['selections'][0]['odds'] = 10000;
        $betslip->setBetslip($modifiedBetslip);

        $betslipValidation->checkExpectedWin();
        $this->assertEquals(0, $betslip->isValid());
        $this->assertEquals(
            'Maximum win amount is 20000',
            $betslip->getGlobalErrors()[0]
        );
    }

    public function betslipProvider()
    {
        $betslip = [
            'player_id' => 1,
            'stake_amount' => 10,
            'errors' => [],
            'selections' => [
                [
                    'id' => 1,
                    'odds' => 1.601,
                    'errors' => [],
                ]
            ],
        ];

        return [
            [
                new Betslip($betslip)
            ]
        ];
    }
}