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
    public $orderID;
    public $material;
    public $length;
    public $width;
    public $x;
    public $y;

    public function __construct($l, $w, $id, $material, $x = -1, $y = -1)
    {
        $this->length = $l;
        $this->width = $w;
        $this->orderID = $id;
        $this->material = $material;
        $this->x = $x;
        $this->y = $y;
    }
}