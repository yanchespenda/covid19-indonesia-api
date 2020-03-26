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
            'kabkot' => 'Kabupaten Blitar',
            'lat' => -8.0955,
            'lng' => 112.1609
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Tulungagung',
            'lat' => -8.0912,
            'lng' => 111.9642
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Trenggalek',
            'lat' => -8.1824,
            'lng' => 111.6184
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Bojonegoro',
            'lat' => -7.3175,
            'lng' => 111.7615
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Situbondo',
            'lat' => -7.7889,
            'lng' => 114.1915
        ];
        $data[] = [
            'kabkot' => 'Kota Batu',
            'lat' => -7.8831,
            'lng' => 112.5334
        ];
        $data[] = [
            'kabkot' => 'Kota Surabaya',
            'lat' => -7.2575,
            'lng' => 112.7521
        ];
        $data[] = [
            'kabkot' => 'Kota Blitar',
            'lat' => -8.0955,
            'lng' => 112.1609
        ];
        $data[] = [
            'kabkot' => 'Kota Probolinggo',
            'lat' => -7.7764,
            'lng' => 113.2037
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Nganjuk',
            'lat' => -7.5944,
            'lng' => 111.9046
        ];
        $data[] = [
            'kabkot' => 'Kota Kediri',
            'lat' => -7.848,
            'lng' => 112.0178
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Lumajang',
            'lat' => -8.0944,
            'lng' => 113.1442
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Banyuwangi',
            'lat' => -8.2191,
            'lng' => 114.3691
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Jember',
            'lat' => -8.1845,
            'lng' => 113.6681
        ];
        $data[] = [
            'kabkot' => 'Kota Malang',
            'lat' => -8.2422,
            'lng' => 112.7152
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Ponorogo',
            'lat' => -7.8651,
            'lng' => 111.4696
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Pacitan',
            'lat' => -8.1263,
            'lng' => 111.1414
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Pamekasan',
            'lat' => -7.1051,
            'lng' => 113.5252
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Madiun',
            'lat' => -7.6093,
            'lng' => 111.6184
        ];
        $data[] = [
            'kabkot' => 'Kota Pasuruan',
            'lat' => -7.6469,
            'lng' => 112.8999
        ];
        $data[] = [
            'kabkot' => 'Kota Mojokerto',
            'lat' => -7.4705,
            'lng' => 112.4401
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Bondowoso',
            'lat' => -7.9674,
            'lng' => 113.9061
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Sumenep',
            'lat' => -6.9254,
            'lng' => 113.9061
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Ngawi',
            'lat' => -7.461,
            'lng' => 111.3322
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Pasuruan',
            'lat' => -7.786,
            'lng' => 112.8582
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Gresik',
            'lat' => -7.155,
            'lng' => 112.5722
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Jombang',
            'lat' => -7.5741,
            'lng' => 112.2861
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Sampang',
            'lat' => -7.0402,
            'lng' => 113.2394
        ];

        $data[] = [
            'kabkot' => 'Kabupaten Kediri',
            'lat' => -7.8232,
            'lng' => 112.1907
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Sidoarjo',
            'lat' => -7.4726,
            'lng' => 112.6675
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Tuban',
            'lat' => -6.8955,
            'lng' => 112.0298
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Mojokerto',
            'lat' => -7.4699,
            'lng' => 112.4351
        ];
        $data[] = [
            'kabkot' => 'Kota Madiun',
            'lat' => -7.6311,
            'lng' => 111.53
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Malang',
            'lat' => -8.2422,
            'lng' => 112.7152
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Magetan',
            'lat' => -7.6433,
            'lng' => 111.356
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Bangkalan',
            'lat' => -7.0384,
            'lng' => 112.9137
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Probolinggo',
            'lat' => -7.8718,
            'lng' => 113.4776
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Lamongan',
            'lat' => -7.1269,
            'lng' => 112.3338
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
            $endpoint = "https://raw.githubusercontent.com/yanchespenda/covid19-indonesia-datasource-csv/master/provinsi/hari%20ini/Jawa%20Timur.csv";
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
