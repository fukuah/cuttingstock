<?php
/**
 * Created by PhpStorm.
 * User: Alexei
 * Date: 14.05.2019
 * Time: 19:18
 */

namespace app\models\logic;

class Plank
{
    public $length;
    public $width;
    public $x;
    public $y;

    public function __construct($l, $w, $x = -1, $y = -1)
    {
        $this->length = $l;
        $this->width = $w;
        $this->x = $x;
        $this->y = $y;
    }
}