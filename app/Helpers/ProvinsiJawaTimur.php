<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use duzun\hQuery;

use App\Helpers\Universal;

class ProvinsiJawaTimur {
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
            'kabkot' => '',
            'lat' => 0,
            'lng' => 0
        ];
        return $data;
    }


    public function init() {
        ini_set("allow_url_fopen", 1);

        hQuery::$cache_path = storage_path('app/odp-pdp');
        hQuery::$cache_expires = 3600;

        return $this;
    }

    public function run() {
        $list = [];
        $csv = $this->getDataJawaTimur();
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

    private function getDataJawaTimur() {
        $cacheName = 'getDataJawaTimur';
        $cacheData = Cache::remember($cacheName, 3600, function () {
            $endpoint = "https://raw.githubusercontent.com/yanchespenda/covid19-indonesia-datasource-csv/master/provinsi/Jawa%20Timur.csv";
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
