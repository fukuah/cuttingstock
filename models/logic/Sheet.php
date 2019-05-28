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

    public $material;

    // array of app\models\logic\Plank
    public $planks = [];

    // part remained of sheet after a cut
    public $offcuts = [];

    // wastes remained of sheet after a cut
    private $waste = [];

    // wastes' volume remained of sheet after a cut
    private $wasteVol = 0;

    public function __construct($length, $width, $material, $offcut = [])
    {
        $this->length = $length;
        $this->width = $width;
        $this->material = $material;

        $this->offcuts = [0 => [self::DOWN_LEFT => [0, 0], self::UP_RIGHT => [$length, $width]]];
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
    public function getOffcuts()
    {
        return $this->offcuts;
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
            }
        }

        $this->uniteOffcuts();

        return $planks;
    }


    /**
     * This function tries to put the plank to the sheet, if it is impossible returns false.
     * param Plank $plank
     * @return bool whether plank was put
     */
    private function tryToPutThePlank(Plank $plank)
    {
        foreach ($this->offcuts as $key => $offcut) {
            $offcutLength = $offcut[self::UP_RIGHT][self::X] - $offcut[self::DOWN_LEFT][self::X];
            $offcutWidth = $offcut[self::UP_RIGHT][self::Y] - $offcut[self::DOWN_LEFT][self::Y];

            // Try to put plank two times
            $dimension = 2;
            for ($i = 0; $i < $dimension; $i++) {
                if ($plank->width <= $offcutWidth && $plank->length <= $offcutLength) {
                    $plank->x = $offcut[self::DOWN_LEFT][self::X];
                    $plank->y = $offcut[self::DOWN_LEFT][self::Y];
                    $this->planks[] = $plank;

                    $this->offcuts[] = [
                        self::DOWN_LEFT => [
                            $plank->x,
                            $plank->y + $plank->width
                        ],
                        self::UP_RIGHT => [
                            $plank->x + $offcutLength,
                            $plank->y + $offcutWidth,
                        ]
                    ];

                    $this->offcuts[] = [
                        self::DOWN_LEFT => [
                            $plank->x + $plank->length,
                            $plank->y
                        ],
                        self::UP_RIGHT => [
                            $plank->x + $offcutLength,
                            $plank->y + $plank->width
                        ]
                    ];

                    unset($this->offcuts[$key]);
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
        usort($this->offcuts, function ($a, $b) {
            return $b[self::DOWN_LEFT][self::Y] > $a[self::DOWN_LEFT][self::Y];
        });
        usort($this->offcuts, function ($a, $b) {
            return $b[self::DOWN_LEFT][self::X] > $a[self::DOWN_LEFT][self::X];
        });

        foreach ($this->offcuts as $key => $currentOffcut) {
            $offcutLength = $currentOffcut[self::UP_RIGHT][self::X] - $currentOffcut[self::DOWN_LEFT][self::X];
            $offcutWidth = $currentOffcut[self::UP_RIGHT][self::Y] - $currentOffcut[self::DOWN_LEFT][self::Y];

            // Delete wastes
            if ($offcutLength < self::$wasteConstraintMM || $offcutWidth < self::$wasteConstraintMM) {
                unset($this->offcuts[$key]);
            }
        }
    }

    private function uniteOffcuts()
    {
        usort($this->offcuts, function ($a, $b) {
            return $b[self::DOWN_LEFT][self::Y] > $a[self::DOWN_LEFT][self::Y];
        });

        foreach ($this->offcuts as $key => $curOffcut) {
            if (isset($prevOffcut) && isset($prevKey)) {
                $prevWidth = $prevOffcut[self::UP_RIGHT][self::Y] - $prevOffcut[self::DOWN_LEFT][self::Y];
                if (
                    $prevOffcut[self::DOWN_LEFT][self::X] == $curOffcut[self::DOWN_LEFT][self::X]
                    && $prevOffcut[self::UP_RIGHT][self::X] == $curOffcut[self::UP_RIGHT][self::X]
//                    && $prevOffcut[self::DOWN_LEFT][self::Y] + $prevWidth == $curOffcut[self::DOWN_LEFT][self::Y]
                ) {

                    $this->offcuts[$key] = [
                        self::DOWN_LEFT => [
                            $curOffcut[self::DOWN_LEFT][self::X],
                            $curOffcut[self::DOWN_LEFT][self::Y]
                        ],
                        self::UP_RIGHT => [
                            $prevOffcut[self::UP_RIGHT][self::X],
                            $prevOffcut[self::UP_RIGHT][self::Y]
                        ]
                    ];

                    $prevOffcut = $this->offcuts[$key];
//                    unset($this->offcuts[$prevKey]);
                }
            } else {
                $prevOffcut = $curOffcut;
            }
            $prevKey = $key;
        }
        //            if (isset($previousOffcut) && isset($previousKey)) {
//
//            }
    }
}