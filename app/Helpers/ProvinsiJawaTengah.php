<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use duzun\hQuery;

use App\Helpers\Universal;

class ProvinsiJawaTengah {
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
            'kabkot' => 'Brebes',
            'lat' => -6.9592,
            'lng' => 108.9027
        ];
        $data[] = [
            'kabkot' => 'Cilacap',
            'lat' => -7.6178,
            'lng' => 108.9027
        ];
        $data[] = [
            'kabkot' => 'Kota Tegal',
            'lat' => -6.8797,
            'lng' => 109.1256
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Tegal',
            'lat' => -6.8588,
            'lng' => 109.1048
        ];
        $data[] = [
            'kabkot' => 'Banyumas',
            'lat' => -7.4832,
            'lng' => 109.1404
        ];
        $data[] = [
            'kabkot' => 'Pemalang',
            'lat' => -7.0599,
            'lng' => 109.4259
        ];
        $data[] = [
            'kabkot' => 'Purbalingga',
            'lat' => -7.3059,
            'lng' => 109.4259
        ];
        $data[] = [
            'kabkot' => 'Kota Pekalongan',
            'lat' => -6.8898,
            'lng' => 109.6746
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Pekalongan',
            'lat' => -7.0517,
            'lng' => 109.6163
        ];
        $data[] = [
            'kabkot' => 'Banjarnegara',
            'lat' => -7.3794,
            'lng' => 109.6163
        ];
        $data[] = [
            'kabkot' => 'Kebumen',
            'lat' => -7.6681,
            'lng' => 109.6525
        ];
        $data[] = [
            'kabkot' => 'Batang',
            'lat' => -7.0392,
            'lng' => 109.9021
        ];
        $data[] = [
            'kabkot' => 'Wonosobo',
            'lat' => -7.3632,
            'lng' => 109.9002
        ];
        $data[] = [
            'kabkot' => 'Purworejo',
            'lat' => -7.6965,
            'lng' => 109.9989
        ];
        $data[] = [
            'kabkot' => 'Kendal',
            'lat' => -7.0265,
            'lng' => 110.1879
        ];
        $data[] = [
            'kabkot' => 'Temanggung',
            'lat' => -7.2749,
            'lng' => 110.0892
        ];
        $data[] = [
            'kabkot' => 'Kota Magelang',
            'lat' => -7.4797,
            'lng' => 110.2177
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Magelang',
            'lat' => -7.4305,
            'lng' => 110.2832
        ];
        $data[] = [
            'kabkot' => 'Kota Semarang',
            'lat' => -7.0051,
            'lng' => 110.4381
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Semarang',
            'lat' => -7.1765,
            'lng' => 110.4739
        ];
        $data[] = [
            'kabkot' => 'Kota Salatiga',
            'lat' => -7.3305,
            'lng' => 110.5084
        ];
        $data[] = [
            'kabkot' => 'Klaten',
            'lat' => -7.6579,
            'lng' => 110.6646
        ];
        $data[] = [
            'kabkot' => 'Boyolali',
            'lat' => -7.4318,
            'lng' => 110.6884
        ];
        $data[] = [
            'kabkot' => 'Wonogiri',
            'lat' => -7.8846,
            'lng' => 111.046
        ];
        $data[] = [
            'kabkot' => 'Sukoharjo',
            'lat' => -7.6484,
            'lng' => 110.8553
        ];
        $data[] = [
            'kabkot' => 'Karanganyar',
            'lat' => -7.6387,
            'lng' => 111.046
        ];
        $data[] = [
            'kabkot' => 'Surakarta',
            'lat' => -7.5755,
            'lng' => 110.8243
        ];
        $data[] = [
            'kabkot' => 'Sragen',
            'lat' => -7.4303,
            'lng' => 111.0092
        ];
        $data[] = [
            'kabkot' => 'Grobogan',
            'lat' => -7.1542,
            'lng' => 110.9507
        ];
        $data[] = [
            'kabkot' => 'Demak',
            'lat' => -6.9239,
            'lng' => 110.6646
        ];
        $data[] = [
            'kabkot' => 'Jepara',
            'lat' => -6.5827,
            'lng' => 110.6787
        ];
        $data[] = [
            'kabkot' => 'Kudus',
            'lat' => -6.7726,
            'lng' => 110.8791
        ];
        $data[] = [
            'kabkot' => 'Pati',
            'lat' => -6.745,
            'lng' => 111.046
        ];
        $data[] = [
            'kabkot' => 'Rembang',
            'lat' => -6.8082,
            'lng' => 111.4276
        ];
        $data[] = [
            'kabkot' => 'Blora',
            'lat' => -7.0122,
            'lng' => 111.3799
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
        $csv = $this->getDataJawaTengah();
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

    private function getDataJawaTengah() {
        $cacheName = 'getDataJawaTengah';
        $cacheData = Cache::remember($cacheName, 3600, function () {
            $endpoint = "https://raw.githubusercontent.com/yanchespenda/covid19-indonesia-datasource-csv/master/provinsi/Jawa%20Tengah.csv";
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
