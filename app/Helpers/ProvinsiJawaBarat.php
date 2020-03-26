<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use duzun\hQuery;

use App\Helpers\Universal;

class ProvinsiJawaBarat {
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
            'kabkot' => 'Depok',
            'lat' => -6.402484,
            'lng' => 106.794243
        ];
        $data[] = [
            'kabkot' => 'Bogor',
            'lat' => -6.597147,
            'lng' => 106.806038
        ];
        $data[] = [
            'kabkot' => 'Bekasi',
            'lat' => -6.238270,
            'lng' => 106.975571
        ];
        $data[] = [
            'kabkot' => 'Karawang',
            'lat' => -6.3227,
            'lng' => 107.3376
        ];
        $data[] = [
            'kabkot' => 'Sukabumi',
            'lat' => -6.8649,
            'lng' => 106.9536
        ];
        $data[] = [
            'kabkot' => 'Cianjur',
            'lat' => -7.358,
            'lng' => 107.1957
        ];
        $data[] = [
            'kabkot' => 'Purwakarta',
            'lat' => -6.5649,
            'lng' => 107.4322
        ];
        $data[] = [
            'kabkot' => 'Subang',
            'lat' => -6.3488,
            'lng' => 107.7636
        ];
        $data[] = [
            'kabkot' => 'Indramayu',
            'lat' => -6.3373,
            'lng' => 108.3258
        ];
        $data[] = [
            'kabkot' => 'Sumedang',
            'lat' => -6.8329,
            'lng' => 107.9532
        ];
        $data[] = [
            'kabkot' => 'Majalengka',
            'lat' => -6.7791,
            'lng' => 108.2852
        ];
        $data[] = [
            'kabkot' => 'Cirebon',
            'lat' => -6.732,
            'lng' => 108.5523
        ];
        $data[] = [
            'kabkot' => 'Kuningan',
            'lat' => -7.0138,
            'lng' => 108.5701
        ];
        $data[] = [
            'kabkot' => 'Ciamis',
            'lat' => -7.3321,
            'lng' => 108.3493
        ];
        $data[] = [
            'kabkot' => 'Pangandaran',
            'lat' => -7.6151,
            'lng' => 108.4988
        ];
        $data[] = [
            'kabkot' => 'Tasikmalaya',
            'lat' => -7.6513,
            'lng' => 108.1429
        ];
        $data[] = [
            'kabkot' => 'Garut',
            'lat' => -7.5012,
            'lng' => 107.7636
        ];
        $data[] = [
            'kabkot' => 'Bandung',
            'lat' => -6.9175,
            'lng' => 107.6191
        ];
        $data[] = [
            'kabkot' => 'Bandung Barat',
            'lat' => -6.8652,
            'lng' => 107.492
        ];
        $data[] = [
            'kabkot' => 'Cimahi',
            'lat' => -6.8841,
            'lng' => 107.5413
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
        $csv = $this->getDataJawaBarat();
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

    private function getDataJawaBarat() {
        $cacheName = 'getDataJawaBarat';
        $cacheData = Cache::remember($cacheName, 3600, function () {
            $endpoint = "https://raw.githubusercontent.com/yanchespenda/covid19-indonesia-datasource-csv/master/provinsi/Jawa%20Barat.csv";
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
