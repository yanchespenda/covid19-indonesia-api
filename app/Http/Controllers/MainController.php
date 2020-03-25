<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

use App\Helpers\OdpPdpBanten;
use App\Helpers\OdpPdpDKIJakarta;

class MainController extends Controller
{

    private $dataLangLat = [];

    private $positifRadius = 500;
    private $sembuhRadius = 500;
    private $meninggalRadius = 500;

    private $odpRadius = 250;
    private $pdpRadius = 250;
    
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

    public function dataProvinsiPositif(Request $request) {
        $getProvinsi = $this->getDataProvinsiCache();
        $getLangLat = $this->getLangLatProvinsi();

        $dataReturn = [];
        foreach ($getProvinsi as $key => $value) {
            $rawData = $value->attributes;

            $getLangLatProvinsi = $this->findQueryData($getLangLat, 'id', $rawData->Kode_Provi, true);
            $raw = [
                'positif' => $rawData->Kasus_Posi,
                'center' => [
                    'lat' => '',
                    'lng' => '',
                ],
                'radius' => $this->positifRadius,
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

    public function dataProvinsiSembuh(Request $request) {
        $getProvinsi = $this->getDataProvinsiCache();
        $getLangLat = $this->getLangLatProvinsi();

        $dataReturn = [];
        foreach ($getProvinsi as $key => $value) {
            $rawData = $value->attributes;

            $getLangLatProvinsi = $this->findQueryData($getLangLat, 'id', $rawData->Kode_Provi, true);
            $raw = [
                'sembuh' => $rawData->Kasus_Semb,
                'center' => [
                    'lat' => '',
                    'lng' => '',
                ],
                'radius' => $this->sembuhRadius,
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

    public function dataProvinsiMeninggal(Request $request) {
        $getProvinsi = $this->getDataProvinsiCache();
        $getLangLat = $this->getLangLatProvinsi();

        $dataReturn = [];
        foreach ($getProvinsi as $key => $value) {
            $rawData = $value->attributes;

            $getLangLatProvinsi = $this->findQueryData($getLangLat, 'id', $rawData->Kode_Provi, true);
            $raw = [
                'meninggal' => $rawData->Kasus_Meni,
                'center' => [
                    'lat' => '',
                    'lng' => '',
                ],
                'radius' => $this->meninggalRadius,
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

    public function dataProvinsiOdp(Request $request) {
        $dataReturn = [];

        /* Provinsi Banten */
        $getDataBanten = OdpPdpBanten::getInstance()->init()->runOdp()->getOdp();
        $getLangLatBanten = OdpPdpBanten::getBantenLangLong();
        if ($getDataBanten) {
            foreach ($getDataBanten as $key => $value) {
                $raw = [
                    'odp' => $value['odp'],
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->odpRadius,
                ];

                $getLangLatFix = $this->findQueryData($getLangLatBanten, 'kabkot', $value['kabkot'], true);
                if ($getLangLatFix) {
                    $raw['center']['lat'] = $getLangLatFix['lat'];
                    $raw['center']['lng'] = $getLangLatFix['lng'];
                }

                $dataReturn[$value['kabkot']] = $raw;
            }
        }

        /* Provinsi DKI Jakarta */
        $getDataJakarta = OdpPdpDKIJakarta::getInstance()->init()->runOdp()->getOdp();
        $getLangLatJakarta = OdpPdpDKIJakarta::getBantenLangLong();
        if ($getDataJakarta) {
            foreach ($getDataJakarta as $key => $value) {
                $raw = [
                    'odp' => $value['odp'],
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->odpRadius,
                ];

                $getLangLatFix = $this->findQueryData($getLangLatJakarta, 'kabkot', $value['kabkot'], true);
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
            /* 'deb' => [
                'jakarta' => @$getDataJakarta,
            ] */
        ], 200);
    }

    public function dataProvinsiPdp(Request $request) {
        $dataReturn = [];

        /* Provinsi Banten */
        $getDataBanten = OdpPdpBanten::getInstance()->init()->runPdp()->getPdp();
        $getLangLat = OdpPdpBanten::getBantenLangLong();
        if ($getDataBanten) {
            foreach ($getDataBanten as $key => $value) {
                $raw = [
                    'pdp' => $value['pdp'],
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->pdpRadius,
                ];

                $getLangLatFix = $this->findQueryData($getLangLat, 'kabkot', $value['kabkot'], true);
                if ($getLangLatFix) {
                    $raw['center']['lat'] = $getLangLatFix['lat'];
                    $raw['center']['lng'] = $getLangLatFix['lng'];
                }

                $dataReturn[$value['kabkot']] = $raw;
            }
        }

        /* Provinsi DKI Jakarta */
        $getDataJakarta = OdpPdpDKIJakarta::getInstance()->init()->runPdp()->getPdp();
        $getLangLatJakarta = OdpPdpDKIJakarta::getBantenLangLong();
        if ($getDataJakarta) {
            foreach ($getDataJakarta as $key => $value) {
                $raw = [
                    'pdp' => $value['pdp'],
                    'center' => [
                        'lat' => '',
                        'lng' => '',
                    ],
                    'radius' => $this->pdpRadius,
                ];

                $getLangLatFix = $this->findQueryData($getLangLatJakarta, 'kabkot', $value['kabkot'], true);
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
            /* 'deb' => [
                'jakarta' => @$getDataJakarta,
            ] */
        ], 200);
    }

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

    private function findQueryData($queryData = [], $searchWhat = false, $searchValue = false, $isArray = false) {
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
