<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 14.05.2019
 * Time: 19:30
 */

namespace app\models\logic;


class Sheet
{
    public $length;
    public $width;

    const DOWN_LEFT = 0;
    const UP_RIGHT = 1;
    const X = 0;
    const Y = 1;

    // if cutoff length is less than this constraint it goes to wastes
    private static $wasteConstraintMM = 10;

    private static $sheetCount;
    private static $sheetUsed;

    // array of app\models\logic\Plank
    private $planks = [];

    // part remained of sheet after a cut
    private $offcut = [];

    // wastes remained of sheet after a cut
    private $waste = [];

    // wastes' volume remained of sheet after a cut
    private $wasteVol = 0;

    public function __construct($length, $width, $offcut = [])
    {
        $this->length = $length;
        $this->width = $width;

        $this->offcut = [0 => [self::DOWN_LEFT => [0, 0], self::UP_RIGHT => [$length, $width]]];
    }


    /**
     * Sets waste constraint to the class.
     * @param $wasteConstraint
     *
     * @return void
     */
    public static function setWasteConstraint($wasteConstraint)
    {
        self::$wasteConstraintMM = $wasteConstraint;
    }

    /**
     * Adds a plank to waste.
     * @param Plank $wastePlank
     *
     * @return void
     */
    public function addToWaste(Plank $wastePlank)
    {
        $this->waste[] = $wastePlank;
        $this->wasteVol += $wastePlank->length * $wastePlank->width;
    }

    /**
     * @return array
     */
    public function getOffcut()
    {
        return $this->offcut;
    }

    /**
     * @return integer
     */
    public function getWasteVol()
    {
        return $this->wasteVol;
    }

    /**
     * fill the sheet with planks.
     * WARNING! Planks are needed to be sorted by descending
     * param array of Plank
     *
     * @return array of remained planks of type Plank
     */
    public function fill(array $planks)
    {
        foreach ($planks as $key => $plank) {
            if ($this->tryToPutThePlank($plank)) {
                unset($planks[$key]);
//                echo 'Plank: ';
//                echo '<pre>';
//                print_r($plank);
//                echo '</pre>';
            }
        }

        return $planks;
    }


    /**
     * TODO This function tries to put the plank to the sheet, if it is impossible returns false.
     * param Plank $plank
     * @return bool whether plank was put
     */
    private function tryToPutThePlank(Plank $plank)
    {
        foreach ($this->offcut as $key => $offcut) {
            $offcutLength = $offcut[self::UP_RIGHT][self::X] - $offcut[self::DOWN_LEFT][self::X];
            $offcutWidth = $offcut[self::UP_RIGHT][self::Y] - $offcut[self::DOWN_LEFT][self::Y];

            // Try to put plank two times
            $dimension = 2;
            for ($i = 0; $i < $dimension; $i++) {
                if ($plank->width <= $offcutWidth && $plank->length <= $offcutLength) {
                    $plank->x = $offcut[self::DOWN_LEFT][self::X];
                    $plank->y = $offcut[self::DOWN_LEFT][self::Y];
                    $this->planks[] = $plank;

                    $this->offcut[] = [
                        self::DOWN_LEFT => [
                            $plank->x,
                            $plank->y + $plank->width
                        ],
                        self::UP_RIGHT => [
                            $plank->x + $offcutLength,
                            $plank->y + $offcutWidth,
                        ]
                    ];

                    $this->offcut[] = [
                        self::DOWN_LEFT => [
                            $plank->x + $plank->length,
                            $plank->y
                        ],
                        self::UP_RIGHT => [
                            $plank->x + $offcutLength,
                            $plank->y + $plank->width
                        ]
                    ];

                    unset($this->offcut[$key]);
                    $this->cleanUpCutoff();
                    return true;
                }

                // try to put plank other way
                $swap = $plank->width;
                $plank->width = $plank->length;
                $plank->length = $swap;
            }
        }

        return false;
    }

    private function cleanUpCutoff()
    {
        foreach ($this->offcut as $key => $offcut) {
            $offcutLength = $offcut[self::UP_RIGHT][self::X] - $offcut[self::DOWN_LEFT][self::X];
            $offcutWidth = $offcut[self::UP_RIGHT][self::Y] - $offcut[self::DOWN_LEFT][self::Y];

            // Delete wastes
            if ($offcutLength < self::$wasteConstraintMM || $offcutWidth < self::$wasteConstraintMM) {
                unset($this->offcut[$key]);
            }

            // Unite offcuts if it is possible
//            if (isset($previousOffcut)) {
//                if () {
//
//                }
//            }
//
//            $previousOffcut = $offcut;
        }
    }
}