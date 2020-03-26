<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Helpers\Universal;
use App\Helpers\ProvinsiBanten;
use App\Helpers\ProvinsiDKIJakarta;
use App\Helpers\ProvinsiJawaBarat;
use App\Helpers\ProvinsiJawaTengah;
use App\Helpers\ProvinsiJawaDIYogyakarta;
use App\Helpers\ProvinsiJawaTimur;

class MainController extends Controller
{

    private $dataLangLat = [];

    private $nasionalRadius = 500;
    private $provinsiRadius = 750;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function dataKasus(Request $request) {
        $getKasus = $this->getDataIndonesiaCache();
        $getKasusUpdate = Storage::disk('local')->get('last_update.txt');

        $dataReturn = [
            'positif' => 0,
            'sembuh' => 0,
            'meninggal' => 0,
            'update' => $getKasusUpdate,
        ];
        if ($getKasus) {
            $dataReturn['positif'] = $getKasus[0]->positif;
            $dataReturn['sembuh'] = $getKasus[0]->sembuh;
            $dataReturn['meninggal'] = $getKasus[0]->meninggal;
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $dataReturn
        ], 200);
    }

    /* Nasional */

    public function dataNasionalSummary(Request $request) {
        $getProvinsi = $this->getDataProvinsiCache();
        $getLangLat = $this->getLangLatProvinsi();

        $dataReturn = [];
        foreach ($getProvinsi as $key => $value) {
            $rawData = $value->attributes;

            $getLangLatProvinsi = Universal::findQueryData($getLangLat, 'id', $rawData->Kode_Provi, true);
            $raw = [
                'positif' => $rawData->Kasus_Posi,
                'sembuh' => $rawData->Kasus_Semb,
                'meninggal' => $rawData->Kasus_Meni,
                'center' => [
                    'lat' => '',
                    'lng' => '',
                ],
                'radius' => $this->nasionalRadius,
            ];

            if ($getLangLatProvinsi) {
                $raw['center']['lat'] = $getLangLatProvinsi['latitude'];
                $raw['center']['lng'] = $getLangLatProvinsi['longitude'];
            }

            $dataReturn[$rawData->Provinsi] = $raw;
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $dataReturn
        ], 200);
    }

    public function dataNasionalPositif(Request $request) {
        $getProvinsi = $this->getDataProvinsiCache();
        $getLangLat = $this->getLangLatProvinsi();

        $dataReturn = [];
        foreach ($getProvinsi as $key => $value) {
            $rawData = $value->attributes;

            $getLangLatProvinsi = Universal::findQueryData($getLangLat, 'id', $rawData->Kode_Provi, true);
            $raw = [
                'positif' => $rawData->Kasus_Posi,
                'center' => [
                    'lat' => '',
                    'lng' => '',
                ],
                'radius' => $this->nasionalRadius,
            ];

            if ($getLangLatProvinsi) {
                $raw['center']['lat'] = $getLangLatProvinsi['latitude'];
                $raw['center']['lng'] = $getLangLatProvinsi['longitude'];
            }

            $dataReturn[$rawData->Provinsi] = $raw;
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $dataReturn
        ], 200);
    }

    public function dataNasionalSembuh(Request $request) {
        $getProvinsi = $this->getDataProvinsiCache();
        $getLangLat = $this->getLangLatProvinsi();

        $dataReturn = [];
        foreach ($getProvinsi as $key => $value) {
            $rawData = $value->attributes;

            $getLangLatProvinsi = Universal::findQueryData($getLangLat, 'id', $rawData->Kode_Provi, true);
            $raw = [
                'sembuh' => $rawData->Kasus_Semb,
                'center' => [
                    'lat' => '',
                    'lng' => '',
                ],
                'radius' => $this->nasionalRadius,
            ];

            if ($getLangLatProvinsi) {
                $raw['center']['lat'] = $getLangLatProvinsi['latitude'];
                $raw['center']['lng'] = $getLangLatProvinsi['longitude'];
            }

            $dataReturn[$rawData->Provinsi] = $raw;
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $dataReturn
        ], 200);
    }

    public function dataNasionalMeninggal(Request $request) {
        $getProvinsi = $this->getDataProvinsiCache();
        $getLangLat = $this->getLangLatProvinsi();

        $dataReturn = [];
        foreach ($getProvinsi as $key => $value) {
            $rawData = $value->attributes;

            $getLangLatProvinsi = Universal::findQueryData($getLangLat, 'id', $rawData->Kode_Provi, true);
            $raw = [
                'meninggal' => $rawData->Kasus_Meni,
                'center' => [
                    'lat' => '',
                    'lng' => '',
                ],
                'radius' => $this->nasionalRadius,
            ];

            if ($getLangLatProvinsi) {
                $raw['center']['lat'] = $getLangLatProvinsi['latitude'];
                $raw['center']['lng'] = $getLangLatProvinsi['longitude'];
            }

            $dataReturn[$rawData->Provinsi] = $raw;
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $dataReturn
        ], 200);
    }


    /* Provinsi */

    public function dataProvinsiSummary(Request $request) {
        $indexText = 'all';

        return $this->dataProvinsi($request, $indexText, true);
    }

    public function dataProvinsiPositif(Request $request) {
        $indexText = 'positif';

        return $this->dataProvinsi($request, $indexText);
    }

    public function dataProvinsiSembuh(Request $request) {
        $indexText = 'sembuh';

        return $this->dataProvinsi($request, $indexText);
    }

    public function dataProvinsiMeninggal(Request $request) {
        $indexText = 'meninggal';

        return $this->dataProvinsi($request, $indexText);
    }

    public function dataProvinsiOdp(Request $request) {
        $indexText = 'odp';

        return $this->dataProvinsi($request, $indexText);
    }

    public function dataProvinsiPdp(Request $request) {
        $indexText = 'pdp';

        return $this->dataProvinsi($request, $indexText);
    }

    private function dataProvinsi($request = false, $indexText = '', $isAll = false) {
        $dataReturn = [];

        /* Provinsi Banten */
        $getDataBanten = ProvinsiBanten::getInstance()->init()->run()->get();
        $getLangLatBanten = ProvinsiBanten::getLangLong();
        if ($getDataBanten) {
            foreach ($getDataBanten as $key => $value) {
                $raw = [
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->provinsiRadius,
                ];
                if (!$isAll) {
                    $raw[$indexText] = $value[$indexText];
                } else {
                    $raw['positif'] = $value['positif'];
                    $raw['sembuh'] = $value['sembuh'];
                    $raw['meninggal'] = $value['meninggal'];
                    $raw['odp'] = $value['odp'];
                    $raw['pdp'] = $value['pdp'];
                }

                $getLangLatFix = Universal::findQueryData($getLangLatBanten, 'kabkot', $value['kabkot'], true);
                if ($getLangLatFix) {
                    $raw['center']['lat'] = $getLangLatFix['lat'];
                    $raw['center']['lng'] = $getLangLatFix['lng'];
                }

                $dataReturn[$value['kabkot']] = $raw;
            }
        }

        /* Provinsi DKI Jakarta */
        $getDataJakarta = ProvinsiDKIJakarta::getInstance()->init()->run()->get();
        $getLangLatJakarta = ProvinsiDKIJakarta::getLangLong();
        if ($getDataJakarta) {
            foreach ($getDataJakarta as $key => $value) {
                $raw = [
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->provinsiRadius,
                ];
                if (!$isAll) {
                    $raw[$indexText] = $value[$indexText];
                } else {
                    $raw['positif'] = $value['positif'];
                    $raw['sembuh'] = $value['sembuh'];
                    $raw['meninggal'] = $value['meninggal'];
                    $raw['odp'] = $value['odp'];
                    $raw['pdp'] = $value['pdp'];
                }

                $getLangLatFix = Universal::findQueryData($getLangLatJakarta, 'kabkot', $value['kabkot'], true);
                if ($getLangLatFix) {
                    $raw['center']['lat'] = $getLangLatFix['lat'];
                    $raw['center']['lng'] = $getLangLatFix['lng'];
                }

                $dataReturn[$value['kabkot']] = $raw;
            }
        }

        /* Provinsi Jawa Barat */
        $getDataJawaBarat = ProvinsiJawaBarat::getInstance()->init()->run()->get();
        $getLangLatJawaBarat = ProvinsiJawaBarat::getLangLong();
        if ($getDataJawaBarat) {
            foreach ($getDataJawaBarat as $key => $value) {
                $raw = [
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->provinsiRadius,
                ];
                if (!$isAll) {
                    $raw[$indexText] = $value[$indexText];
                } else {
                    $raw['positif'] = $value['positif'];
                    $raw['sembuh'] = $value['sembuh'];
                    $raw['meninggal'] = $value['meninggal'];
                    $raw['odp'] = $value['odp'];
                    $raw['pdp'] = $value['pdp'];
                }

                $getLangLatFix = Universal::findQueryData($getLangLatJawaBarat, 'kabkot', $value['kabkot'], true);
                if ($getLangLatFix) {
                    $raw['center']['lat'] = $getLangLatFix['lat'];
                    $raw['center']['lng'] = $getLangLatFix['lng'];
                }

                $dataReturn[$value['kabkot']] = $raw;
            }
        }

        /* Provinsi Jawa Tengah */
        $getDataJawaTengah = ProvinsiJawaTengah::getInstance()->init()->run()->get();
        $getLangLatJawaTengah = ProvinsiJawaTengah::getLangLong();
        if ($getDataJawaTengah) {
            foreach ($getDataJawaTengah as $key => $value) {
                $raw = [
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->provinsiRadius,
                ];
                if (!$isAll) {
                    $raw[$indexText] = $value[$indexText];
                } else {
                    $raw['positif'] = $value['positif'];
                    $raw['sembuh'] = $value['sembuh'];
                    $raw['meninggal'] = $value['meninggal'];
                    $raw['odp'] = $value['odp'];
                    $raw['pdp'] = $value['pdp'];
                }

                $getLangLatFix = Universal::findQueryData($getLangLatJawaTengah, 'kabkot', $value['kabkot'], true);
                if ($getLangLatFix) {
                    $raw['center']['lat'] = $getLangLatFix['lat'];
                    $raw['center']['lng'] = $getLangLatFix['lng'];
                }

                $dataReturn[$value['kabkot']] = $raw;
            }
        }

        /* Provinsi DIYogyakarta */
        $getDataDIYogyakarta = ProvinsiJawaDIYogyakarta::getInstance()->init()->run()->get();
        $getLangLatDIYogyakarta = ProvinsiJawaDIYogyakarta::getLangLong();
        if ($getDataDIYogyakarta) {
            foreach ($getDataDIYogyakarta as $key => $value) {
                $raw = [
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->provinsiRadius,
                ];
                if (!$isAll) {
                    $raw[$indexText] = $value[$indexText];
                } else {
                    $raw['positif'] = $value['positif'];
                    $raw['sembuh'] = $value['sembuh'];
                    $raw['meninggal'] = $value['meninggal'];
                    $raw['odp'] = $value['odp'];
                    $raw['pdp'] = $value['pdp'];
                }

                $getLangLatFix = Universal::findQueryData($getLangLatDIYogyakarta, 'kabkot', $value['kabkot'], true);
                if ($getLangLatFix) {
                    $raw['center']['lat'] = $getLangLatFix['lat'];
                    $raw['center']['lng'] = $getLangLatFix['lng'];
                }

                $dataReturn[$value['kabkot']] = $raw;
            }
        }

        /* Provinsi Jawa Timur */
        $getDataJawaTimur = ProvinsiJawaTimur::getInstance()->init()->run()->get();
        $getLangLatJawaTimur = ProvinsiJawaTimur::getLangLong();
        if ($getDataJawaTimur) {
            foreach ($getDataJawaTimur as $key => $value) {
                $raw = [
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->provinsiRadius,
                ];
                if (!$isAll) {
                    $raw[$indexText] = $value[$indexText];
                } else {
                    $raw['positif'] = $value['positif'];
                    $raw['sembuh'] = $value['sembuh'];
                    $raw['meninggal'] = $value['meninggal'];
                    $raw['odp'] = $value['odp'];
                    $raw['pdp'] = $value['pdp'];
                }

                $getLangLatFix = Universal::findQueryData($getLangLatJawaTimur, 'kabkot', $value['kabkot'], true);
                if ($getLangLatFix) {
                    $raw['center']['lat'] = $getLangLatFix['lat'];
                    $raw['center']['lng'] = $getLangLatFix['lng'];
                }

                $dataReturn[$value['kabkot']] = $raw;
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Success',
            'data' => $dataReturn,
        ], 200);
    }

    /* Lainnya */

    private function getLangLatProvinsi() {
        $preData = Storage::disk('local')->get('provinsi.json');
        if ($preData) {
            $preData = json_decode($preData, true);
            if (!$preData) {
                $preData = [];
            }
        }
        $this->dataLangLat = $preData;
        return $this->dataLangLat;
    }

    private function getLangLatKabKot() {
        $preData = Storage::disk('local')->get('kabkot.json');
        if ($preData) {
            $preData = json_decode($preData, true);
            if (!$preData) {
                $preData = [];
            }
        }
        $this->dataLangLat = $preData;
        return $this->dataLangLat;
    }

    private function getDataIndonesia() {
        $endpoint = "https://api.kawalcorona.com/indonesia/";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint, ['query' => [
            
        ]]);

        $statusCode = $response->getStatusCode();
        $payload = json_decode($response->getBody()->getContents());
        return [
            'status' => ($statusCode == 200)?true:false,
            'data' => $payload,
        ];
    }
    private function getDataIndonesiaCache() {
        $cacheName = 'getDataIndonesiaCache';
        $cacheData = Cache::remember($cacheName, 3600, function () {
            $getData = $this->getDataIndonesia();
            Storage::disk('local')->put('last_update.txt', Carbon::now('Asia/Jakarta'));
            if ($getData['status']) {
                return $getData['data'];
            }
            return false;
        });
        if (!$cacheData) {
            Cache::forget($cacheName);
        }
        return $cacheData;
    }

    private function getDataProvinsi() {
        $endpoint = "https://api.kawalcorona.com/indonesia/provinsi/";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint, ['query' => [
            
        ]]);

        $statusCode = $response->getStatusCode();
        $payload = json_decode($response->getBody()->getContents());
        return [
            'status' => ($statusCode == 200)?true:false,
            'data' => $payload,
        ];
    }
    private function getDataProvinsiCache() {
        $cacheName = 'getDataProvinsiCache';
        $cacheData = Cache::remember($cacheName, 3600, function () {
            $getData = $this->getDataProvinsi();
            if ($getData['status']) {
                return $getData['data'];
            }
            return false;
        });
        if (!$cacheData) {
            Cache::forget($cacheName);
        }
        return $cacheData;
    }

}
