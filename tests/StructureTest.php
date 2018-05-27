<?php

use PHPUnit\Framework\TestCase;
use Benasg\Bet\StructureValidation;

class StructureTest extends TestCase
{
    /**
     * @dataProvider structureAndInputProvider
     */
    public function testValideStructure($structure, $input, $expected)
    {
        $structureValidation = StructureValidation::validateStructure($structure, $input);
        
        $this->assertEquals($expected, $structureValidation);
    }

    public function structureAndInputProvider()
    {
        $structure = [
            'player_id:i',
            'stake_amount:f',
            'selections:a' => ['id:i','odds:f']
        ];

        return [
            /*
             * Valid structure
             */
            [
                $structure,
                [
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
                ],
                true
            ],
            /*
             * Invalid structure. Missing player_id
             */
            [
                $structure,
                [
                    'stake_amount' => 10,
                    'errors' => [],
                    'selections' => [
                        [
                            'id' => 1,
                            'odds' => 1.601,
                            'errors' => [],
                        ]
                    ],
                ],
                false
            ],
            /*
             * Invalid structure. Missing stake_amount
             */
            [
                $structure,
                [
                    'player_id' => 1,
                    'errors' => [],
                    'selections' => [
                        [
                            'id' => 1,
                            'odds' => 1.601,
                            'errors' => [],
                        ]
                    ],
                ],
                false
            ],
            /*
             * Invalid structure. Missing selections
             */
            [
                $structure,
                [
                    'player_id' => 1,
                    'stake_amount' => 10,
                    'errors' => []
                ],
                false
            ],
            /*
             * Invalid structure. Missing selections.id
             */
            [
                $structure,
                [
                    'player_id' => 1,
                    'stake_amount' => 10,
                    'errors' => [],
                    'selections' => [
                        [
                            'odds' => 1.601,
                            'errors' => [],
                        ]
                    ],
                ],
                false
            ],
            /*
             * Invalid structure. Missing selections.odds
             */
            [
                $structure,
                [
                    'player_id' => 1,
                    'stake_amount' => 10,
                    'errors' => [],
                    'selections' => [
                        [
                            'id' => 1,
                            'errors' => [],
                        ]
                    ],
                ],
                false
            ],
            /*
             * Invalid structure. Incorrect player_id type
             */
            [
                $structure,
                [
                    'player_id' => 'one',
                    'stake_amount' => 10,
                    'errors' => [],
                    'selections' => [
                        [
                            'id' => 1,
                            'odds' => 1.601,
                            'errors' => [],
                        ]
                    ],
                ],
                false
            ],
            /*
             * Invalid structure. Incorrect stake_amount type
             */
            [
                $structure,
                [
                    'player_id' => 1,
                    'stake_amount' => 'ten',
                    'errors' => [],
                    'selections' => [
                        [
                            'id' => 1,
                            'odds' => 1.601,
                            'errors' => [],
                        ]
                    ],
                ],
                false
            ],
            /*
             * Invalid structure. Incorrect selections type
             */
            [
                $structure,
                [
                    'player_id' => 1,
                    'stake_amount' => 10,
                    'errors' => [],
                    'selections' => 'array',
                ],
                false
            ],
            /*
             * Invalid structure. Incorrect selections.id type
             */
            [
                $structure,
                [
                    'player_id' => 1,
                    'stake_amount' => 10,
                    'errors' => [],
                    'selections' => [
                        [
                            'id' => 'one',
                            'odds' => 1.601,
                            'errors' => [],
                        ]
                    ],
                ],
                false
            ],
            /*
             * Invalid structure. Incorrect selections.odds type
             */
            [
                $structure,
                [
                    'player_id' => 1,
                    'stake_amount' => 10,
                    'errors' => [],
                    'selections' => [
                        [
                            'id' => 1,
                            'odds' => 'one',
                            'errors' => [],
                        ]
                    ],
                ],
                false
            ],
            /*
             * Invalid structure. Incorrect selections array
             */
            [
                $structure,
                [
                    'player_id' => 1,
                    'stake_amount' => 10,
                    'errors' => [],
                    'selections' => [
                        [
                            'id' => 1,
                            'odds' => 1.601,
                            'errors' => [],
                        ],
                        [
                            'id' => 2,
                            'errors' => [],
                        ]
                    ],
                ],
                false
            ],
        ];
    }
}