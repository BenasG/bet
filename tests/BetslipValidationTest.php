<?php

use PHPUnit\Framework\TestCase;
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
        $this->assertEquals(1, $betslipValidation->getIsValid());

        // Incorrect stake_amount
        $betslip['stake_amount'] = 0;

        $betslipValidation->setBetslip($betslip);
        $betslipValidation->checkMinStakeAmount();
        $this->assertEquals(0, $betslipValidation->getIsValid());
        $this->assertEquals('Minimum stake amount is 0.3', $betslipValidation->getBetslip()['errors'][0]);
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testMaxStakeAmount($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkMaxStakeAmount();
        $this->assertEquals(1, $betslipValidation->getIsValid());

        // Incorrect stake_amount
        $betslip['stake_amount'] = 999999;

        $betslipValidation->setBetslip($betslip);
        $betslipValidation->checkMaxStakeAmount();
        $this->assertEquals(0, $betslipValidation->getIsValid());
        $this->assertEquals('Maximum stake amount is 10000', $betslipValidation->getBetslip()['errors'][0]);
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testMinSelectionsNumber($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkMinSelectionsNumber();
        $this->assertEquals(1, $betslipValidation->getIsValid());

        // Incorrect selections number
        unset($betslip['selections'][0]);

        $betslipValidation->setBetslip($betslip);
        $betslipValidation->checkMinSelectionsNumber();
        $this->assertEquals(0, $betslipValidation->getIsValid());
        $this->assertEquals('Minimum number of selections is 1', $betslipValidation->getBetslip()['errors'][0]);
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testMaxSelectionsNumber($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkMaxSelectionsNumber();
        $this->assertEquals(1, $betslipValidation->getIsValid());

        // Incorrect selections number
        for ($i = 0; $i < 20; $i++) {
            $betslip['selections'][] = [
                'id' => 1,
                'odds' => 1.601,
                'errors' => [],
            ];
        }

        $betslipValidation->setBetslip($betslip);
        $betslipValidation->checkMaxSelectionsNumber();
        $this->assertEquals(0, $betslipValidation->getIsValid());
        $this->assertEquals('Maximum number of selections is 20', $betslipValidation->getBetslip()['errors'][0]);
    }

    /**
     * @dataProvider betslipProvider
     */
    public function testSelectionUniqueId($betslip)
    {
        $betslipValidation = new BetslipValidation($betslip);
        
        // Correct
        $betslipValidation->checkSelectionUniqueId();
        $this->assertEquals(1, $betslipValidation->getIsValid());

        // Incorrect selection event id
        $betslip['selections'][] = [
            'id' => 1,
            'odds' => 1.601,
            'errors' => [],
        ];

        $betslipValidation->setBetslip($betslip);
        $betslipValidation->checkSelectionUniqueId();
        $this->assertEquals(0, $betslipValidation->getIsValid());
        $this->assertEquals(
            'Duplicate IDs are not allowed',
            $betslipValidation->getBetslip()['selections'][1]['errors'][0]
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
        $this->assertEquals(1, $betslipValidation->getIsValid());

        // Incorrect min odd
        $betslip['selections'][0]['odds'] = 0;

        $betslipValidation->setBetslip($betslip);
        $betslipValidation->checkMinOddsInterval();
        $this->assertEquals(0, $betslipValidation->getIsValid());
        $this->assertEquals(
            'Minimum odds are 1',
            $betslipValidation->getBetslip()['selections'][0]['errors'][0]
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
        $this->assertEquals(1, $betslipValidation->getIsValid());

        // Incorrect max odd
        $betslip['selections'][0]['odds'] = 99999;

        $betslipValidation->setBetslip($betslip);
        $betslipValidation->checkMaxOddsInterval();
        $this->assertEquals(0, $betslipValidation->getIsValid());
        $this->assertEquals(
            'Maximum odds are 10000',
            $betslipValidation->getBetslip()['selections'][0]['errors'][0]
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
        $this->assertEquals(1, $betslipValidation->getIsValid());

        $betslip['selections'][0]['odds'] = 10000;

        $betslipValidation->setBetslip($betslip);
        $betslipValidation->checkExpectedWin();
        $this->assertEquals(0, $betslipValidation->getIsValid());
        $this->assertEquals(
            'Maximum win amount is 20000',
            $betslipValidation->getBetslip()['errors'][0]
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
                $betslip
            ]
        ];
    }
}