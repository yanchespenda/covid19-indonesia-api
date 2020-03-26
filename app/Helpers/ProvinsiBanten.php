<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use duzun\hQuery;

use App\Helpers\Universal;

class ProvinsiBanten {
    public static $list = [];

    private static $_instance = null;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public static function getLangLong() {
        $data = [];
        $data[] = [
            'kabkot' => 'Kabupaten Pandeglang',
            'lat' => -6.748271,
            'lng' => 105.688179
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Lebak',
            'lat' => -6.564396,
            'lng' => 106.252213
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Tangerang',
            'lat' => -6.187210,
            'lng' => 106.487709
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Serang',
            'lat' => -6.139734,
            'lng' => 106.040504
        ];
        $data[] = [
            'kabkot' => 'Kota Tangerang',
            'lat' => -6.170540,
            'lng' => 106.636848
        ];
        $data[] = [
            'kabkot' => 'Kota Cilegon',
            'lat' => -6.002534,
            'lng' => 106.011124
        ];
        $data[] = [
            'kabkot' => 'Kota Serang',
            'lat' => -6.120540,
            'lng' => 106.184753
        ];
        $data[] = [
            'kabkot' => 'Kota Tangerang Selatan',
            'lat' => -6.342820,
            'lng' => 106.752670
        ];
        return $data;
    }

    public function init() {
        hQuery::$cache_path = storage_path('app/odp-pdp');
        hQuery::$cache_expires = 3600;

        return $this;
    }

    public function run() {
        $list = [];
        $csv = $this->getDataBanten();
        if ($csv) {
            foreach ($csv as $key => $value) {
                $row = [];

                $row['kabkot'] = @Universal::changeSpasi(@$value[0]);
                $row['positif'] = (int) @Universal::clearSpesialKarakter($value[1]);
                $row['sembuh'] = (int) @Universal::clearSpesialKarakter($value[2]);
                $row['meninggal'] = (int) @Universal::clearSpesialKarakter($value[3]);
                $row['odp'] = (int) @Universal::clearSpesialKarakter($value[4]);
                $row['pdp'] = (int) @Universal::clearSpesialKarakter($value[5]);

                $list[] = $row;
            }
        }

        self::$list = $list;

        return $this;
    }

    public function get() {
        return self::$list;
    }

    private function getDataBanten() {
        $cacheName = 'getDataBanten';
        $cacheData = Cache::remember($cacheName, 3600, function () {
            $endpoint = "https://raw.githubusercontent.com/yanchespenda/covid19-indonesia-datasource-csv/master/provinsi/hari%20ini/Banten.csv";
            if (($h = fopen($endpoint, "r")) !== FALSE) {
                $rows = [];
                $numbers = 1;
                while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                    if ($numbers != 1) {
                        $rows[] = $data;
                    }
                    $numbers++;
                }
                fclose($h);
                return $rows;
            }

            return false;
        });
        if (!$cacheData) {
            Cache::forget($cacheName);
        }
        return $cacheData;
    }

}
