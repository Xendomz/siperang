<?php
namespace App\Traits;

trait HelperTrait {
    public function generateOutletCode($length = 3){
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $rand_chars = substr( str_shuffle( $chars ), 0, $length );
        return $rand_chars;
    }
}
