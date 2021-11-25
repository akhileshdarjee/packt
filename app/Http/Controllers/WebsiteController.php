<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Promise;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function __construct()
    {
        if (!session('defaultCurrency')) {
            session()->put('defaultCurrency', 'USD');
        } 
    }

    public function getDefaultCurrency()
    {
        if (session('defaultCurrency')) {
            $defaultCurrency = session('defaultCurrency');
        } else {
            $defaultCurrency = 'USD';
        }
    }

    public function setDefaultCurrency(Request $request)
    {
        $data = [
            'data' => [],
            'success' => true,
            'message' => 'Default currency set successfully'
        ];

        if ($request->filled('currency')) {
            $currency = trim($request->get('currency'));

            if (!in_array($currency, ['USD', 'GBP', 'EUR', 'INR', 'AUD'])) {
                $currency = 'USD';
            }

            session()->put('defaultCurrency', $currency);
        }

        return response()->json($data, 200);
    }

    public function index()
    {
        return view('website.layouts.index');
    }

    public function about()
    {
        return view('website.layouts.about');
    }

    public function contact()
    {
        return view('website.layouts.contact');
    }

    public function latestProducts(Request $request)
    {
        $data = [
            'data' => [],
            'success' => false,
            'message' => 'Oops! Something went wrong. Please try again'
        ];

        $apiKey = config('services.packt.token');

        if ($apiKey) {
            $products = $this->getProducts(1, 8);

            $data = [
                'data' => [
                    'products' => $products
                ],
                'success' => true,
                'message' => 'Success'
            ];

            // $headers = [
            //     'Authorization' => 'Bearer ' . $apiKey,
            //     'Content-Type' => 'application/json'
            // ];

            // $params = [
            //     'page' => 1,
            //     'limit' => 8
            // ];

            // try {
            //     $client = new Client();
            //     $request = $client->get('https://api.packt.com/api/v1/products', [
            //         'headers' => $headers,
            //         'query' => $params
            //     ]);

            //     $response = json_decode($request->getBody());

            //     if (isset($response->errorMessage) && $response->errorMessage) {
            //         $data['message'] = $response->errorMessage;
            //     } else {
            //         $products = [];

            //         if (isset($response->total) && $response->total && isset($response->products) && $response->products) {
            //             foreach ($response->products as $product) {
            //                 $product->url = route('single.product', ['id' => $product->id]);
            //                 $product->price = $this->getCurrencySymbol(session('defaultCurrency')) . ' ' . $this->getProductPrice($product->id);
            //                 $product->image = $this->getProductImage($product->id);

            //                 array_push($products, $product);
            //             }
            //         }

            //         $data = [
            //             'data' => [
            //                 'products' => $products
            //             ],
            //             'success' => true,
            //             'message' => 'Success'
            //         ];
            //     }
            // }
            // catch (Exception $e) {
            //    $data['message'] = $e->getMessage();
            // }
        } else {
            $data['message'] = 'Please set PACKT_API_TOKEN in .env';
        }

        return response()->json($data, 200);
    }

    public function products(Request $request)
    {
        if ($request->ajax()) {
            $data = [
                'data' => [],
                'success' => false,
                'message' => 'Oops! Something went wrong. Please try again'
            ];

            $apiKey = config('services.packt.token');

            if ($apiKey) {
                $page = 1;
                $per_page = 12;

                if ($request->filled('page') && trim($request->get('page'))) {
                    $page = intval($request->get('page'));
                }

                if ($request->filled('per_page') && trim($request->get('per_page'))) {
                    $per_page = intval($request->get('per_page'));
                }

                logger($page);
                logger($per_page);
                $products = $this->getProducts($page, $per_page, true);

                $data = [
                    'data' => $products,
                    'success' => true,
                    'message' => 'Success'
                ];
            } else {
                $data['message'] = 'Please set PACKT_API_TOKEN in .env';
            }

            return response()->json($data, 200);
        } else {
            return view('website.layouts.products');
        }
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

    public function getProducts($page, $per_page, $full_response = false)
    {
        $products = [];
        $products_response = null;
        $apiKey = config('services.packt.token');

        if ($apiKey) {
            $headers = [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ];

            $params = [
                'page' => $page,
                'limit' => $per_page
            ];

            try {
                $client = new Client();
                $response = $client->get('https://api.packt.com/api/v1/products', [
                    'headers' => $headers,
                    'query' => $params
                ]);

                $response = json_decode($response->getBody());

                if (isset($response->errorMessage) && $response->errorMessage) {
                    $data['message'] = $response->errorMessage;
                } else {
                    if (isset($response->total) && $response->total && isset($response->products) && $response->products) {
                        $products_response = $response;
                        $image = null;
                        $currency = session('defaultCurrency');
                        $price = 0.00;
                        $promises = [];

                        foreach ($response->products as $product) {
                            $product->url = route('single.product', ['id' => $product->id]);
                            $product->price = $price;
                            $product->image = null;

                            $promises[$product->id . '_price'] = $client->getAsync('https://api.packt.com/api/v1/products/' . $product->id . '/price/' . $currency, [
                                'headers' => $headers
                            ]);

                            $promises[$product->id . '_image'] = $client->getAsync('https://api.packt.com/api/v1/products/' . $product->id . '/cover/small', [
                                'headers' => $headers
                            ]);

                            $products[$product->id] = $product;
                        }

                        $responses = Promise\settle($promises)->wait();

                        foreach ($responses as $key => $response) {
                            $key = explode('_', $key);
                            $productId = head($key);
                            $type = last($key);

                            //response state is either 'fulfilled' or 'rejected'
                            if ($response['state'] === 'rejected') {
                                //handle rejected
                                continue;
                            }

                            //$result is a Guzzle Response object
                            $result = $response['value'];

                            if ($type == 'price') {
                                $result = json_decode($result->getBody());

                                if (isset($result->prices) && $result->prices) {
                                    $lowestPrice = null;

                                    foreach ($result->prices as $type => $productPrice) {
                                        if ($lowestPrice) {
                                            if ($productPrice->{$currency} < $lowestPrice) {
                                                $lowestPrice = $productPrice->{$currency};
                                            }
                                        } else {
                                            $lowestPrice = $productPrice->{$currency};
                                        }
                                    }

                                    if ($lowestPrice && isset($products[$productId]) && $products[$productId]) {
                                        $products[$productId]->price = $this->getCurrencySymbol($currency) . ' ' . $lowestPrice;
                                    }
                                }
                            } else {
                                $image = $result->getBody()->getContents();
                                $base64 = base64_encode($image);
                                $mimeType = $result->getHeader('Content-Type')[0];
                                $image = ('data:' . $mimeType . ';base64,' . $base64);

                                if ($image && isset($products[$productId]) && $products[$productId]) {
                                    $products[$productId]->image = $image;
                                }
                            }
                        }
                    }
                }
            }
            catch (Exception $e) {
               logger('Error fetching Packt products');
               logger($e);
            }
        } else {
            logger('Please set PACKT_API_TOKEN in .env');
        }

        $products = array_values($products);

        if ($full_response) {
            $products_response->products = $products;
            $products = $products_response;

            foreach ($products as $key => $value) {
                if (in_array($key, ['first_page_url', 'last_page_url', 'next_page_url', 'prev_page_url', 'path'])) {
                    $products_route = route('products');
                    $products->{$key} = str_replace('https://api.packt.com/api/v1/products', $products_route, $value);
                }
            }
        }

        return $products;
    }

    public function getProductImage($id, $size = false)
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
                $image = ('data:' . $mimeType . ';base64,' . $base64);
            }
            catch (Exception $e) {
               logger('Image not found for Product ID: ' . $id);
            }
        }

        if (!$image) {
            $image = $this->getDefaultProductImage($size);
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
            $image = ('data:' . $mimeType . ';base64,' . $base64);
        }
        catch (Exception $e) {
           logger('Default Image not found');
        }

        return $image;
    }

    public function getProductPrice($id)
    {
        $currency = session('defaultCurrency');
        $price = 0.00;
        $apiKey = config('services.packt.token');

        if ($apiKey) {
            $headers = [
                'Authorization' => 'Bearer ' . $apiKey
            ];

            try {
                $client = new Client();
                $response = $client->get('https://api.packt.com/api/v1/products/' . $id . '/price/' . $currency, [
                    'headers' => $headers
                ]);

                $response = json_decode($response->getBody());

                if (isset($response->errorMessage) && $response->errorMessage) {
                    logger('Price not found for Product ID: ' . $id);
                } else {
                    if (isset($response->prices) && $response->prices) {
                        $lowestPrice = null;

                        foreach ($response->prices as $type => $productPrice) {
                            if ($lowestPrice) {
                                if ($productPrice->{$currency} < $lowestPrice) {
                                    $lowestPrice = $productPrice->{$currency};
                                }
                            } else {
                                $lowestPrice = $productPrice->{$currency};
                            }
                        }

                        $price = $lowestPrice;
                    }
                }
            }
            catch (Exception $e) {
               logger('Price not found for Product ID: ' . $id);
               logger($e);
            }
        }

        return $price;
    }

    public function getCurrencySymbol($currency)
    {
        $currency = $currency ?? 'USD';
        $symbol = '$';

        $currencySymbolMap = [
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
            'INR' => '₹',
            'AUD' => 'A$'
        ];

        if (isset($currencySymbolMap[$currency]) && $currencySymbolMap[$currency]) {
            $symbol = $currencySymbolMap[$currency];
        }

        return $symbol;
    }
}
