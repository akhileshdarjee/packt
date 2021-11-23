<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function products(Request $request)
    {
        $data = [
            'data' => [],
            'success' => false,
            'message' => 'Oops! Something went wrong. Please try again'
        ];

        $apiKey = config('services.packt.token');

        if ($apiKey) {
            $headers = [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ];

            $params = [];

            if ($request->filled('page') && trim($request->get('page')) && intval($request->get('page'))) {
                $params['page'] = intval($request->get('page'));
            } else {
                $params['page'] = 1;
            }

            if ($request->filled('per_page') && trim($request->get('per_page')) && intval($request->get('per_page'))) {
                $params['limit'] = intval($request->get('per_page'));
            } else {
                $params['limit'] = 12;
            }

            try {
                $client = new Client();
                $request = $client->get('https://api.packt.com/api/v1/products', [
                    'headers' => $headers,
                    'query' => $params
                ]);

                $response = json_decode($request->getBody());

                if (isset($response->errorMessage) && $response->errorMessage) {
                    $data['message'] = $response->errorMessage;
                } else {
                    $data = [
                        'data' => $response,
                        'success' => true,
                        'message' => 'Success'
                    ];
                }
            }
            catch (Exception $e) {
               $data['message'] = $e->getMessage();
            }
        } else {
            $data['message'] = 'Please set PACKT_API_TOKEN in .env';
        }

        return response()->json($data, 200);
    }

    public function singleProduct(Request $request, $id)
    {
        $data = [
            'data' => [],
            'success' => false,
            'message' => 'Oops! Something went wrong. Please try again'
        ];

        $apiKey = config('services.packt.token');

        if ($apiKey) {
            $headers = [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ];

            try {
                $client = new Client();
                $request = $client->get('https://api.packt.com/api/v1/products/' . $id, [
                    'headers' => $headers
                ]);

                $response = json_decode($request->getBody(), true);

                if (isset($response->errorMessage) && $response->errorMessage) {
                    $data['message'] = $response->errorMessage;
                } else {
                    $data = [
                        'data' => $response,
                        'success' => true,
                        'message' => 'Success'
                    ];
                }
            }
            catch (Exception $e) {
               $data['message'] = $e->getMessage();
            }
        } else {
            $data['message'] = 'Please set PACKT_API_TOKEN in .env';
        }

        return response()->json($data, 200);
    }

    public function productImage(Request $request, $id, $size = false)
    {
        $image = null;
        $apiKey = config('services.packt.token');

        if ($apiKey) {
            $headers = [
                'Authorization' => 'Bearer ' . $apiKey
            ];

            if (!in_array($size, ['small', 'large'])) {
                $size = 'small';
            }

            try {
                $client = new Client();
                $response = $client->get('https://api.packt.com/api/v1/products/' . $id . '/cover/' . $size, [
                    'headers' => $headers
                ]);

                $image = $response->getBody()->getContents();
                $base64 = base64_encode($image);
                $mimeType = $response->getHeader('Content-Type')[0];
                $img = ('data:' . $mimeType . ';base64,' . $base64);
                return "<img src=" . $img . ">";
            }
            catch (Exception $e) {
               logger('Image not found for Product ID: ' . $id);
            }
        }

        if (!$image) {
            return $this->getDefaultProductImage($size);
        }

        return $image;
    }

    public function getDefaultProductImage($size = false)
    {
        $image = null;
        $dimensions = '240x300';

        if ($size == 'large') {
            $dimensions = '810x1000';
        }

        $params = [
            'text' => 'Packt'
        ];

        try {
            $client = new Client();
            $response = $client->get('https://via.placeholder.com/' . $dimensions, [
                'query' => $params
            ]);

            $image = $response->getBody()->getContents();
            $base64 = base64_encode($image);
            $mimeType = $response->getHeader('Content-Type')[0];
            $img = ('data:' . $mimeType . ';base64,' . $base64);
            return "<img src=" . $img . ">";
        }
        catch (Exception $e) {
           logger('Default Image not found');
        }

        return $image;
    }
}
