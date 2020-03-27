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
            'kabkot' => 'Kabupaten Bandung',
            'lat' => -7.1341,
            'lng' => 107.6215
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Bandung Barat',
            'lat' => -6.8652,
            'lng' => 107.492
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Bekasi',
            'lat' => -6.2474,
            'lng' => 107.1485
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Bogor',
            'lat' => -6.5518,
            'lng' => 106.6291
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Ciamis',
            'lat' => -7.3321,
            'lng' => 108.3493
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Cianjur',
            'lat' => -7.358,
            'lng' => 107.1957
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Cirebon',
            'lat' => -6.6899,
            'lng' => 108.4751
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Garut',
            'lat' => -7.5012,
            'lng' => 107.7636
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Indramayu',
            'lat' => -6.3373,
            'lng' => 108.3258
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Karawang',
            'lat' => -6.3227,
            'lng' => 107.3376
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Kuningan',
            'lat' => -7.0138,
            'lng' => 108.5701
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Majalengka',
            'lat' => -6.7791,
            'lng' => 108.2852
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Pangandaran',
            'lat' => -7.6151,
            'lng' => 108.4988
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Purwakarta',
            'lat' => -6.5649,
            'lng' => 107.4322
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Subang',
            'lat' => -6.3488,
            'lng' => 107.7636
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Sukabumi',
            'lat' => -6.8649,
            'lng' => 106.9536
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Sumedang',
            'lat' => -6.8329,
            'lng' => 107.9532
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Tasikmalaya',
            'lat' => -7.6513,
            'lng' => 108.1429
        ];
        $data[] = [
            'kabkot' => 'Kota Bandung',
            'lat' => -6.9175,
            'lng' => 107.6191
        ];
        $data[] = [
            'kabkot' => 'Kota Banjar',
            'lat' => -7.3707,
            'lng' => 108.5342
        ];
        $data[] = [
            'kabkot' => 'Kota Bekasi',
            'lat' => -6.2383,
            'lng' => 106.9756
        ];
        $data[] = [
            'kabkot' => 'Kota Bogor',
            'lat' => -6.5971,
            'lng' => 106.806
        ];
        $data[] = [
            'kabkot' => 'Kota Cimahi',
            'lat' => -6.8841,
            'lng' => 107.5413
        ];
        $data[] = [
            'kabkot' => 'Kota Cirebon',
            'lat' => -6.732,
            'lng' => 108.5523
        ];
        $data[] = [
            'kabkot' => 'Kota Depok',
            'lat' => -6.4025,
            'lng' => 106.7942
        ];
        $data[] = [
            'kabkot' => 'Kota Sukabumi',
            'lat' => -6.9277,
            'lng' => 106.93
        ];
        $data[] = [
            'kabkot' => 'Kota Tasikmalaya',
            'lat' => -7.3506,
            'lng' => 108.2172
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
            $endpoint = "https://raw.githubusercontent.com/yanchespenda/covid19-indonesia-datasource-csv/master/provinsi/hari%20ini/Jawa%20Barat.csv";
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
