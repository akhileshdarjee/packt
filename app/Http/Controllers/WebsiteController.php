<?php

namespace App\Http\Controllers;

use Str;
use Exception;
use Validator;
use Carbon\Carbon;
use GuzzleHttp\Promise;
use GuzzleHttp\Client;
use App\Models\Subscription;
use App\Models\Contact;
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
        if ($request->ajax()) {
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
                    $image = null;
                    $prices = [];
                    $product = null;
                    $currency = session('defaultCurrency');
                    $currencySymbol = $this->getCurrencySymbol($currency);
                    $promises = [];
                    $client = new Client();

                    $promises['product'] = $client->getAsync('https://api.packt.com/api/v1/products/' . $id, [
                        'headers' => $headers
                    ]);

                    $promises['price'] = $client->getAsync('https://api.packt.com/api/v1/products/' . $id . '/price/' . $currency, [
                        'headers' => $headers
                    ]);

                    // $promises['image'] = $client->getAsync('https://api.packt.com/api/v1/products/' . $id . '/cover/large', [
                    //     'headers' => $headers
                    // ]);

                    $responses = Promise\settle($promises)->wait();

                    foreach ($responses as $key => $response) {
                        //response state is either 'fulfilled' or 'rejected'
                        if ($response['state'] === 'rejected') {
                            if ($key == 'product') {
                                break;
                            } else {
                                continue;
                            }
                        }

                        //$result is a Guzzle Response object
                        $result = $response['value'];

                        if ($key == 'product') {
                            $result = json_decode($result->getBody());
                            $product = $result;

                            if (isset($product->product_type) && $product->product_type) {
                                $product->product_type = Str::singular($product->product_type);

                                if (isset($product->tagline) && $product->tagline) {
                                    $product->tagline = strip_tags($product->tagline);
                                }
                                if (isset($product->learn) && $product->learn) {
                                    $product->learn = strip_tags($product->learn);
                                }
                                if (isset($product->features) && $product->features) {
                                    $product->features = strip_tags($product->features);
                                }
                                if (isset($product->description) && $product->description) {
                                    $product->description = strip_tags($product->description);
                                }
                                if (isset($product->publication_date) && $product->publication_date) {
                                    $publication_date = Carbon::parse($product->publication_date);
                                    $product->publication_date = $publication_date->format('d-m-Y');
                                }

                                if (isset($product->authors) && $product->authors && count($product->authors)) {
                                    foreach ($product->authors as $key => $author) {
                                        $product->authors[$key]->about = strip_tags($author->about);
                                    }
                                }
                            }
                        } elseif ($key == 'price') {
                            $result = json_decode($result->getBody());

                            if (isset($result->prices) && $result->prices) {
                                foreach ($result->prices as $type => $productPrice) {
                                    $price = $currencySymbol . ' ' .$productPrice->{$currency};

                                    if ($type == 'print') {
                                        $priceDetails = [
                                            'name' => 'Print',
                                            'price' => $price,
                                        ];

                                        array_push($prices, $priceDetails);
                                    } elseif ($type == 'ebook') {
                                        $priceDetails = [
                                            'name' => 'eBook',
                                            'price' => $price
                                        ];

                                        array_push($prices, $priceDetails);
                                    } elseif ($type == 'video') {
                                        $priceDetails = [
                                            'name' => 'Video',
                                            'price' => $price
                                        ];

                                        array_push($prices, $priceDetails);
                                    }
                                }
                            }
                        } else {
                            $image = $result->getBody()->getContents();
                            $base64 = base64_encode($image);
                            $mimeType = $result->getHeader('Content-Type')[0];
                            $image = ('data:' . $mimeType . ';base64,' . $base64);
                        }
                    }

                    if ($product) {
                        if ($image) {
                            $product->image = $image;
                        }

                        if ($prices) {
                            if (isset($product->isbns) && $product->isbns) {
                                foreach ($product->isbns as $type => $sku) {
                                    foreach ($prices as $idx => $price) {
                                        if (strtolower($price['name']) == $type) {
                                            $prices[$idx]['buy_link'] = 'https://www.packtpub.com/buyitem/index/index/sku/' . $sku;
                                        }
                                    }
                                }
                            }

                            $product->prices = $prices;
                        }

                        $data = [
                            'data' => [
                                'title' => $product->title . ' - Product - ' . config('app.name'),
                                'product' => $product
                            ],
                            'success' => true,
                            'message' => 'Success'
                        ];
                    } else {
                        $data['message'] = 'Product not found';
                    }
                }
                catch (Exception $e) {
                   $data['message'] = $e->getMessage();
                }
            } else {
                $data['message'] = 'Please set PACKT_API_TOKEN in .env';
            }

            return response()->json($data, 200);
        } else {
            return view('website.layouts.product')->with(['productId' => $id]);
        }
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

                            // $promises[$product->id . '_image'] = $client->getAsync('https://api.packt.com/api/v1/products/' . $product->id . '/cover/small', [
                            //     'headers' => $headers
                            // ]);

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
            if (isset($products_response->products) && $products_response->products) {
                $products_response->products = $products;
                $products = $products_response;

                foreach ($products as $key => $value) {
                    if (in_array($key, ['first_page_url', 'last_page_url', 'next_page_url', 'prev_page_url', 'path'])) {
                        $products_route = route('products');
                        $products->{$key} = str_replace('https://api.packt.com/api/v1/products', $products_route, $value);
                    }
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

    public function subscribe(Request $request)
    {
        $data = [
            'data' => [],
            'success' => false,
            'message' => 'Oops! Something went wrong. Please try again'
        ];

        if ($request->filled('email')) {
            $email = trim($request->get('email'));

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $alreadySubscribed = Subscription::select('id', 'email')
                    ->where('email', $email)
                    ->first();

                if ($alreadySubscribed) {
                    $data['success'] = true;
                    $data['message'] = 'You are already subscribed';
                } else {
                    $subscriptionData = [
                        'email' => $email
                    ];

                    $created = Subscription::create($subscriptionData);

                    if ($created) {
                        $data = [
                            'data' => $created,
                            'success' => true,
                            'message' => 'Yay! You have been added to the subscription list'
                        ];
                    }
                }
            } else {
                $data['message'] = 'Please enter valid email address';
            }
        } else {
            $data['message'] = 'Please enter valid email address';
        }

        return response()->json($data, 200);
    }

    public function validationsForContact($data)
    {
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required|string'
        ];

        $messages = [
            'name.required' => 'Please enter name',
            'name.exists' => 'Name should be a string',
            'email.required' => 'Please enter email',
            'email.email' => 'Please enter valid email address',
            'subject.required' => 'Please enter subject',
            'subject.exists' => 'Subject should be a string',
            'message.required' => 'Please enter message',
            'message.exists' => 'Message should be a string',
        ];

        return Validator::make($data, $rules, $messages);
    }

    public function saveContact(Request $request)
    {
        $data = [
            'data' => [],
            'success' => false,
            'message' => 'Oops! Something went wrong. Please try again'
        ];

        $validator = $this->validationsForContact($request->all());

        if ($validator->fails()) {
            $data['message'] = $validator->errors()->first();
        } else {
            $contactData = [
                'name' => trim($request->get('name')),
                'email' => trim($request->get('email')),
                'subject' => trim($request->get('subject')),
                'message' => trim($request->get('message'))
            ];

            $created = Contact::create($contactData);

            if ($created) {
                $data = [
                    'data' => $created,
                    'success' => true,
                    'message' => 'Your message has been saved. We will contact you shortly'
                ];
            }
        }

        return response()->json($data, 200);
    }
}
