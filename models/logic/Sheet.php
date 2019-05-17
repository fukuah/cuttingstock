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

    public $planks;

    public function __construct($length, $width)
    {
        $this->length = $length;
        $this->width = $width;
    }
}