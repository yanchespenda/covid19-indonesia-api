<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use duzun\hQuery;

use App\Helpers\Universal;

class OdpPdpDKIJakarta {
    public static $listOdp = [];
    public static $listPdp = [];

    private static $_instance = null;

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public static function getBantenLangLong() {
        $data = [];
        $data[] = [
            'kabkot' => 'Jakarta Pusat',
            'lat' => -6.175110,
            'lng' => 106.865036
        ];
        $data[] = [
            'kabkot' => 'Jakarta Selatan',
            'lat' => -6.261493,
            'lng' => 106.810600
        ];
        $data[] = [
            'kabkot' => 'Jakarta Barat',
            'lat' => -6.168329,
            'lng' => 106.758850
        ];
        $data[] = [
            'kabkot' => 'Jakarta Timur',
            'lat' => -6.225014,
            'lng' => 106.900444
        ];
        $data[] = [
            'kabkot' => 'Jakarta Utara',
            'lat' => -6.138377,
            'lng' => 106.866463
        ];
        $data[] = [
            'kabkot' => 'Kepulauan Seribu',
            'lat' => -5.594460,
            'lng' => 106.559002
        ];
        return $data;
    }

    public function init() {
        ini_set("allow_url_fopen", 1);

        hQuery::$cache_path = storage_path('app/odp-pdp');
        hQuery::$cache_expires = 3600;

        return $this;
    }

    public function runOdp() {

        $list = [];
        $csv = $this->getDataDKIJakarta();
        if ($csv) {
            foreach ($csv as $key => $value) {
                $row = [];
                $dataKota = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value[0]);
                $dataJumlah = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value[4]);
    
                $row['kabkot'] = $dataKota;
                $row['odp'] = $dataJumlah;
    
                $list[] = $row;
            }
        }
        
        self::$listOdp = $list;

        return $this;
    }

    public function getOdp() {
        return self::$listOdp;
    }

    public function runPdp() {

        $list = [];
        $csv = $this->getDataDKIJakarta();
        if ($csv) {
            foreach ($csv as $key => $value) {
                $row = [];
                $dataKota = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value[0]);
                $dataJumlah = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value[5]);
    
                $row['kabkot'] = $dataKota;
                $row['pdp'] = $dataJumlah;
    
                $list[] = $row;
            }
        }
        self::$listPdp = $list;

        return $this;
    }

    public function getPdp() {
        return self::$listPdp;
    }

    private function getDataDKIJakarta() {
        $cacheName = 'getDataDKIJakarta';
        $cacheData = Cache::remember($cacheName, 3600, function () {
            $endpoint = "https://raw.githubusercontent.com/yanchespenda/covid19-indonesia-dataset-csv/master/provinsi/DKI%20Jakarta.csv";
            if (($h = fopen($endpoint, "r")) !== FALSE) {
                $rows = [];
                while (($data = fgetcsv($h, 1000, ",")) !== FALSE) {
                    $rows[] = $data;
                }
                Storage::disk('local')->put('csv-odp-pdp/dki-jakarta.csv', $h);
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

