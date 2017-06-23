<?php
/**
 * Created by PhpStorm.
 * User: MadMax
 * Date: 23.06.2017
 * Time: 15:11
 */

namespace app\models;

class Country {
    public static function getCoutries() {
        return require(__DIR__.'/../data/countries.php');
    }
}