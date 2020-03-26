<?php

namespace App\Helpers;

class Universal {
    
    public static function clearSpesialKarakter($string) { 
        return preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $string);
    }

    public static function changeSpasi($string) {
        return str_replace("\u{00a0}", ' ', $string);
    }

    public static function findQueryData($queryData = [], $searchWhat = false, $searchValue = false, $isArray = false) {
		if (!$searchWhat || !$searchValue) {
			return false;
		}
		if (count($queryData) > 0) {
			foreach($queryData as $key => $value) {
				if ($isArray) {
					if ($value[$searchWhat] == $searchValue) {
						return $value;
					}
				} else {
					if ($value->{$searchWhat} == $searchValue) {
						return $value;
					}
				}
			}
		}
		return false;
	}
}
