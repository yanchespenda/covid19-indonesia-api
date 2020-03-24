<?php

namespace App\Helpers;

use Carbon\Carbon;
use duzun\hQuery;

class OdpPdpBanten {

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
            'kabkot' => 'Kabupaten Pandeglang',
            'lat' => -6.6181859,
            'lng' => 105.3577574
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Pandeglang',
            'lat' => -6.6425932,
            'lng' => 105.9144039
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Tangerang',
            'lat' => -6.1815249,
            'lng' => 106.3925029
        ];
        $data[] = [
            'kabkot' => 'Kabupaten Serang',
            'lat' => -6.1062485,
            'lng' => 105.9777114
        ];
        $data[] = [
            'kabkot' => 'Kota Tangerang',
            'lat' => -6.1765123,
            'lng' => 106.5799939
        ];
        $data[] = [
            'kabkot' => 'Kota Cilegon',
            'lat' => -5.9685002,
            'lng' => 105.9337864
        ];
        $data[] = [
            'kabkot' => 'Kota Serang',
            'lat' => -6.1106106,
            'lng' => 106.1415104
        ];
        $data[] = [
            'kabkot' => 'Kota Tangerang Selatan',
            'lat' => -6.2954166,
            'lng' => 106.673293
        ];
        return $data;
    }

    public function init() {
        hQuery::$cache_path = storage_path('app/odp-pdp');
        hQuery::$cache_expires = 3600;

        return $this;
    }

    public function runOdp() {
        $doc = hQuery::fromUrl('https://infocorona.bantenprov.go.id/odp', ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);

        $table = $doc->find('table.table tbody');

        $list = [];

        if ( $table ) {
            foreach ($table as $key => $value) {
                $row = [];
                $findKabKota = $value->find('tr th');
                $findNilai = $value->find('tr td');
                if ($findKabKota) {
                    $number = 1;
                    foreach ($findKabKota as $keyX => $valueX) {
                        $getValX = $valueX->text();
                        if ($getValX != 'JUMLAH' && $getValX != 'TOTAL' && !is_numeric($getValX)) {
                            $row['kabkot'] = $valueX->text();
                            $row['odp'] = $findNilai[($number * 3) - 1]->text();
                            $list[] = $row;
                            $number++;
                        }
                    }
                    self::$listOdp = $list;
                }
            }
        }
        return $this;
    }

    public function getOdp() {
        return self::$listOdp;
    }

    public function runPdp() {
        $doc = hQuery::fromUrl('https://infocorona.bantenprov.go.id/pdp', ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);

        $table = $doc->find('table.table tbody');

        $list = [];

        if ( $table ) {
            foreach ($table as $key => $value) {
                $row = [];
                $findKabKota = $value->find('tr th');
                $findNilai = $value->find('tr td');
                if ($findKabKota) {
                    $number = 1;
                    foreach ($findKabKota as $keyX => $valueX) {
                        $getValX = $valueX->text();
                        if ($getValX != 'JUMLAH' && $getValX != 'TOTAL' && !is_numeric($getValX)) {
                            $row['kabkot'] = $valueX->text();
                            $row['pdp'] = $findNilai[($number * 3) - 1]->text();
                            $list[] = $row;
                            $number++;
                        }
                    }
                    self::$listPdp = $list;
                }
            }
        }
        return $this;
    }

    public function getPdp() {
        return self::$listPdp;
    }

}
