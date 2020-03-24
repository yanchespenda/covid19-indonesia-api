<?php 
namespace App\Http\Middleware;

class CorsMiddleware {
    public function handle($request, \Closure $next){
        $IlluminateResponse = 'Illuminate\Http\Response';
        $SymfonyResopnse = 'Symfony\Component\HttpFoundation\Response';
        $temp_origin = $request->header('Origin');
        if(empty($temp_origin)){
            $temp_origin = "'*'";
        }
        $temp_ah = $request->header('Access-Control-Request-Headers');
        if(empty($temp_ah)){
            $temp_ah = "GET";
        }

        $headers = [
            'Access-Control-Allow-Origin'      => $temp_origin,
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, X-Api-Key'
        ];

        if ($request->isMethod('OPTIONS')){
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                $headers['Access-Control-Allow-Methods'] = 'POST, GET, OPTIONS, PUT, DELETE';
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                $headers['Access-Control-Allow-Headers'] = $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'];

            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }

        $response = $next($request);

        if($response instanceof $IlluminateResponse) {
            foreach ($headers as $key => $value) {
                $response->header($key, $value);
            }
            return $response;
        }
        
        if($response instanceof $SymfonyResopnse) {
            foreach ($headers as $key => $value) {
                $response->headers->set($key, $value);
            }
            return $response;
        }

        return $response;
    }
}