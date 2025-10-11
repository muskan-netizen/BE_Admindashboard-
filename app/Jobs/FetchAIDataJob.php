<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchAIDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $fetch_data;
    protected $onboardingid;
    protected $sell_prompt;
    protected $business_type;
    protected $businessname;

    public $failOnTimeout = true;

    public $timeout = 120000;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fetch_data, $onboardingid, $sell_prompt, $business_type, $businessname)
    {
        $this->fetch_data = $fetch_data;
        $this->onboardingid = $onboardingid;
        $this->sell_prompt = $sell_prompt;
        $this->business_type = $business_type;
        $this->businessname = $businessname;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //try {
            set_time_limit(800); //300 seconds = 5 minutes
            Log::info("FetchAIDataJob start for onboarding id:- " . $this->onboardingid);
            $finaljsonresponse = '';
            $finalresult = '';
            $result = '{}';
            $logoresponse = '{}';
            $aiflag = 1;
            $url = 'https://api.openai.com/v1/chat/completions';
            //$apiKey = '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'; // From Harbans 
            $apiKey = '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'; // From Office
            $searchImageText = 'Your user input here'; // Replace with your user input
            if ($this->fetch_data == 2 || $this->fetch_data == 3 || $this->fetch_data == 4 || $this->fetch_data == 5) {
                $data = [
                    'model' => 'gpt-3.5-turbo-1106',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Provide a valid JSON string with 5 correct categories, each with actual variants which sells ' . $this->sell_prompt . ' . Include at least 3 vendors/stores and list a minimum of 5 products for each store with correct product names related to my business. 
                        {
                            "categories": [
                                {
                                    "name": "Cateogry Name",
                                    "detail": "Cateogry Detail",
                                    "url_slug" : "Url Slug",//single word
                                    "variants": [
                                        {
                                            "title": "Variant Name",
                                            "type": "1", //1-> dropdown, 2->color
                                            "option": [  // every time same string is comes option not options
                                                {
                                                    "title": "Option Name",
                                                    "colorcode": "#ccc" // ->if variant type is 2
                                                }
                                            ]
                                        }
                                    ],
                                }
                            ],
                            "stores": [
                                {
                                    "store_name": "Store Name",
                                    "slug": "Slug",
                                    "categoryname": "Category Name", //related to above category
                                    "categoryslug" : "category url slug", //related to above category
                                    "products": [
                                        {
                                            "product_name": "Product name",
                                            "description": "Product Description", // around 2 lines
                                            "price": "Price without currency",
                                            "sku" : "Sku name",
                                            "url_slug" : "Url Slug"
                                        }
                                    ]
                                }
                            ],
                            "brand": [
                                {
                                    "name": "Brand Name",
                                    "slug": "Slug",
                                    "categoryname": "Category Name", //related to above category
                                    "categoryslug" : "category url slug", //related to above category,
                                    "productslug" : "Product url slug", //related to above product for assign brand with same store category,
                                }
                            ]
                        }',
                        ],
                    ],
                    'temperature' => 0.7,
                    'stream' => false,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                ])->post($url, $data);

                if ($response->successful()) {
                    // Log::info('success ai response');
                    // Log::info($response->json());

                    $result = $response->json('choices.0.message.content');


                    $startPosition = strpos($result, '{');

                    if ($startPosition !== false) {
                        $trimmedJsonData = trim(str_replace('```json', '', $result), '```');
                        // Extract the substring starting from the first '{'
                        //$trimmedJsonData = substr($result, $startPosition);
                    
                        // Attempt to decode the JSON
                        $fixedJsonData = $this->fixJson($trimmedJsonData);
                    
                        // Check if decoding was successful
                        if ($fixedJsonData === null) {
                            $aiflag = 0;
                            // Log::info('Invalid JSON not parsing');
                        } else {
                            // Process the decoded data
                            $result = json_encode($fixedJsonData);
                            // Log::info('After fix json');
                            // Log::info($result);
                        }
                    }

                    //Session::forget('aiData');
                    //Session::put('aiData', $result);

                    $finaljsonresponse = $response->successful();
                    $finalresult = json_decode($result);
                    //Log::info($finalresult);
                } else {
                    Log::info('error ai response');
                    Log::info($response->json());
                    Log::info($response->json('error.message'));
                    //echo '<pre>';print_r($response->json());echo '</pre>';die;
                    $result = $response->json('error.message');
                    $finaljsonresponse = 'false';
                    $finalresult = $result;
                    $aiflag = 0;
                    // You can also use $response->status() to get the HTTP status code
                    // and $response->json() to get the full JSON response from the API.
                }
            }
            // Log::info('Ai data end');

            //Please add the text 'ABC Company' positioned on the right side of the shape, in the Arial font with a font size of 20px and a fill color of blacklogoresponse
            //Design a website logo. Logo name is 'Code Brew Labs'. Industry type is Retail. I want color scheme of logo is pastel. And font style use Handwritten.
            /*********WORKING CODE IMAGE*************/
            if ($this->fetch_data == 2 || $this->fetch_data == 5) {
                // Log::info('image generation start for logos');
                /*$logoresponse = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $apiKey, // Replace with your OpenAI API key
            ])
                ->post('https://api.openai.com/v1/images/generations', [
                    'prompt' => "Please add the exact text '" . $this->businessname . "'. in logo format. Take intial of business name with Robot font with spacing all around",
                    'n' => 1,
                    'size' => '256x256',
                ]);*/
            }

            $catimageUrls = array();
            $storeimageUrls = [];
            $productimageUrls = [];
            $catimageUrls = [];
            $brandimageUrls = [];
            $prevBrand = null;
            $bannerImages = [];

            if (($this->fetch_data == 4 || $this->fetch_data == 5) && $aiflag == 1) {
                // Log::info('image generation start for stores product banner brands');
                if (isset($result)) {
                    $datann = json_decode($result, true);
                    if (isset($datann['stores'])) {
                        foreach ($datann['stores']  as $key => $store) {
                            $storename = $store['store_name'];
                            $storePromptText =  $storename;
                            $count = 1;
                            $storeImages = $this->searchImage($storePromptText, $count, $storename, 1);
                            if (count($storeImages) > 0)
                                $storeimageUrls = array_merge($storeimageUrls, $storeImages);

                            if (count($store['products']) > 0) {
                                foreach ($store['products'] as $product) {
                                    $title = $product['product_name'];

                                    $prodPromptText = " . $title . ";
                                    $count = 1;
                                    $productImages = $this->searchImage($prodPromptText, $count, $title, 1);
                                    if (count($productImages) > 0)
                                        $productimageUrls = array_merge($productimageUrls, $productImages);
                                }
                            }
                        }
                    }

                    if (isset($datann['categories'])) {
                        foreach ($datann['categories']  as $key => $category) {
                            $name = $category['name'];

                            $catPromptText = "Please get the store image named as '" . $name . "' in logo format";
                            $count = 1;
                            $catImages = $this->searchImage($catPromptText, $count, $name, 1);
                            if (count($catImages) > 0)
                                $catimageUrls = array_merge($catimageUrls, $catImages);
                        }
                    }

                    if (isset($datann['brand'])) {
                        foreach ($datann['brand']  as $key => $brand) {
                            $brandname = $brand['name'];
                            if ($brandname !== $prevBrand) {
                                $brandPromptText = "Please get the brand image named as '" . $brandname . "' in logo format";
                                $count = 1;
                                $brandImages = $this->searchImage($brandPromptText, $count, $brandname, 1);
                                if (count($brandImages) > 0)
                                    $brandimageUrls = array_merge($brandimageUrls, $brandImages);

                                $prevBrand = $brandname;
                            }
                            //$brandname = $brand['name'];
                        }
                    }
                }

                //$businessnamePromptText = "Please get the banner image named as '" . $this->sell_prompt . "' in high resolution image format without watermark";
                $businessnamePromptText = " . $this->sell_prompt . ";
                $count = 5;
                $bannerImages = $this->searchImage($businessnamePromptText, $count, $this->businessname, 1);
            }

            try {
                $responseimgnew =  ''; //$responseimg->getBody();
                $jsonimage =  ''; //json_decode($responseimgnew);
                $imgresult =  ''; //$jsonimage->image;//die;
                $imgresponse = 'ok';


                $logoresponsenew = 'ok';
                $logoimgresult = json_decode($logoresponse); //json_decode($logoresponse);//$logoresponse;//$logoresponsenew->data;

            } catch (\Exception $e) {
                //$imgresponse = $e->getMessage();
                $logoresponsenew = $e->getMessage();
                $logoimgresult = '';
                //return response()->json(['error' => $e->getMessage()], 500);
            }

            /************** Why Choose Use ***************/

            $wcu_url = 'https://api.openai.com/v1/chat/completions';
            $wcu_data = [
                'model' => 'gpt-3.5-turbo-1106',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Please send "Why choose us" text in paragraph format around 70 words for business "' . $this->businessname . '" which sells "' . $this->sell_prompt . '"
                    ',
                    ],
                ],
                'temperature' => 0.7,
                'stream' => false,
            ];

            $wcu_response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json'
            ])->post($wcu_url, $wcu_data);

            if ($wcu_response->successful()) {
                // Log::info('why choose us success ai response');
                // Log::info($wcu_response->json());
                $wcu_result = $wcu_response->json('choices.0.message.content');
            } else {
                // Log::info('why choose us error ai response');
                // Log::info($wcu_response->json());
                // Log::info($wcu_response->json('error.message'));
                $wcu_result = $response->json('error.message');
            }

            /************** Why Choose Use ***************/
        try {
            if ($aiflag == 1) {
                DB::connection('god')
                    ->table('dummy_onboard_data')
                    ->where('id', $this->onboardingid)
                    ->update([
                        'logojson' => ($this->fetch_data == 2 || $this->fetch_data == 5) ? $logoresponse : '{}',
                        'fulldata' => $result,
                        'storeimages' => ($this->fetch_data == 4 || $this->fetch_data == 5) ? json_encode($storeimageUrls) : '{}',
                        'catimages' => (($this->fetch_data == 4 || $this->fetch_data == 5) && count($catimageUrls) > 0) ? json_encode($catimageUrls) : '{}',
                        'prodimages' => (($this->fetch_data == 4 || $this->fetch_data == 5) && count($productimageUrls) > 0) ? json_encode($productimageUrls) : NULL,
                        'brandimages' => (($this->fetch_data == 4 || $this->fetch_data == 5) && count($brandimageUrls) > 0) ? json_encode($brandimageUrls) : NULL,
                        'bannerimages'  => (($this->fetch_data == 4 || $this->fetch_data == 5)  && count($bannerImages) > 0) ? json_encode($bannerImages) : NULL,
                        'whychooseustext' => $wcu_result,
                        'aistatus' => 1
                    ]);
            } else {
                DB::connection('god')
                    ->table('dummy_onboard_data')
                    ->where('id', $this->onboardingid)
                    ->update([
                        'fetch_data'  => 5,
                        'logojson' => '{}',
                        'fulldata' => NULL,
                        'storeimages' => '{}',
                        'catimages' => '{}',
                        'prodimages' => NULL,
                        'brandimages' => NULL,
                        'bannerimages'  => (($this->fetch_data == 4 || $this->fetch_data == 5)  && count($bannerImages) > 0) ? json_encode($bannerImages) : NULL,
                        'whychooseustext' => '',
                        'aistatus' => 0
                    ]);
            }
            // Log::info("FetchAIDataJob DONE for onboarding id:- " . $this->onboardingid);
        } catch (\Exception $e) {
            // Log::info("FetchAIDataJob error comes:- " . $this->onboardingid);
            // Log::info($e->getMessage());
            DB::connection('god')
                ->table('dummy_onboard_data')
                ->where('id', $this->onboardingid)
                ->update([
                    'fetch_data'  => 5,
                    'logojson' => '{}',
                    'fulldata' => $result,
                    'storeimages' => '{}',
                    'catimages' => '{}',
                    'prodimages' => NULL,
                    'brandimages' => NULL,
                    'bannerimages'  => NULL,
                    'whychooseustext' => '',
                    'aistatus' => 0
                ]);
        }

        $dummy_onboard_data_new = DB::connection('god')->table('dummy_onboard_data')->where('id', $this->onboardingid)->first();
        //DB::connection('god')->commit();
        if (@$dummy_onboard_data_new->client_id != '' && $dummy_onboard_data_new->fetch_data != 1)
            \App\Jobs\OnBoardingDataProcessJob::dispatch($dummy_onboard_data_new);
    }

    public function fixJson($jsonString)
    {
        // Add your logic to fix the JSON structure if needed
        // For example, removing invalid characters or correcting syntax
        // Return the fixed JSON string
        if (!empty($jsonString) && $jsonString[strlen($jsonString) - 1] !== '}') {
            // Add a closing brace
            $jsonString .= '}';
        }

        // Remove extra commas before closing curly braces
        $jsonString = preg_replace('/,\s*}/', '}', $jsonString);

        // Remove extra commas before closing square brackets
        $jsonString = preg_replace('/,\s*]/', ']', $jsonString);

        // Attempt to decode the JSON
        $decoded = json_decode($jsonString);

        // Check if decoding was successful
        if ($decoded !== null || json_last_error() === JSON_ERROR_NONE) {
            return $decoded;
        }

        // Handle specific issues, add more cases as needed
        if (json_last_error() === JSON_ERROR_SYNTAX) {
            // JSON syntax error, attempt to fix
            $jsonString = '{' . $jsonString . '}';
            $decoded = json_decode($jsonString);
            if ($decoded !== null || json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }

        // Unable to fix JSON
        return null;
    }


    public function searchImage($text, $count, $aname, $searchType)
    {
        $searchConfigType = config('constants.ImageGeneratedAPI');
        // Log::info("search type." . $searchConfigType);
        switch ($searchConfigType) {
            case 0:
                return $this->searchBingImage($text, $count, $aname);
            case 1:
                return $this->searchGoogleImage($text, $count, $aname);
            case 2:
                return $this->searchUnsplashImage($text, $count, $aname);
            case 3:
                return $this->searchOriginalGoogleImage($text, $count, $aname);
            default:
                throw new \InvalidArgumentException("Invalid search type: $searchType");
        }
        /*
        if(config('constants.ImageGeneratedAPI') == 1)
            return $images = $this->searchGoogleImage($text, $count, $aname);
        else if(config('constants.ImageGeneratedAPI') == 2) 
            return $images = $this->searchUnsplashImage($text, $count, $aname);
        else
            return $images = $this->searchBingImage($text, $count, $aname);
        */
    }

    public function searchGoogleImage($text, $count, $aname)
    {
        $url = 'https://google-api31.p.rapidapi.com/imagesearch';
        // call the api with guzzle
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('POST', $url, [
                'body' => '{
                    "text": "' . $text . '",
                    "safesearch": "on",
                    "region": "wt-wt",
                    "color": "",
                    "size": "Large",
                    "type_image": "photo",
                    "layout": "",
                    "max_results": ' . $count . '
                }',
                'headers' => [
                    'X-RapidAPI-Host' => 'google-api31.p.rapidapi.com',
                    //'X-RapidAPI-Key' => '4a3256dbf5msh779f74cee3b9586p10b58cjsn8a849b31605f', // By Code studio GMAIL
                    //'X-RapidAPI-Key' => '79ef6795cbmshec778b6aeb7933ep1d0937jsnf43302058cea', // By Harbans
                    //'X-RapidAPI-Key' => '783096a43bmsha953a70ff01066dp1d1380jsn6e342a28f88b', // By Pawan
                    //'X-RapidAPI-Key' => 'c6fcf1c949msh9399cff7b22b50ap196b36jsn0d4f329acdca', // By Mohit CB innov
                    //'X-RapidAPI-Key' => '04ce199d0dmsh5fdde65e97905b9p1a0ecbjsn2cec32f7c4d0', // By Narinder
                    //'X-RapidAPI-Key' => '5b71142830msh94569a30854e643p1bb2f0jsn356990c7c7f8', // By Amit Singla
                    //  'X-RapidAPI-Key' => 'f3c682d187msh6678ed146f9c839p1bbd7fjsnb155ed89ca49',   // By Shivani Mam paid key
                    'X-RapidAPI-Key' => '83f494f8b8msh85a6f2acb4593dfp1fcad1jsn7f0f7f228de5',   // By sandeep free

                    'content-type' => 'application/json',
                ],
            ]);
            //echo $res->getBody();die;
            $response = json_decode($res->getBody(), true); //echo '<pre>';pr($response);die;
            $imagesArray = $response['result'];

            //pr($imagesArray);die;

            $imagesArrayURL = array_map(function ($item) use ($aname) {
                $url = explode("?",  $item['image'])[0];
                $filename = basename($url);
                return ['image_url' => $url, 'imagedefault' => 0, 'name' => @$aname];
            }, $imagesArray);

            return $imagesArrayURL;
        } catch (\Exception $e) {
            return []; //['image_url' => 'https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/default/default_images.png', 'imagedefault' => 1, 'name' => @$aname];
        }
        return $imagesArrayURL;
    }

    public function searchBingImage($text, $count, $aname)
    {
        try {
            $url = 'https://bing-image-search1.p.rapidapi.com/images/search?q=' . $text . '&count=' . $count;
            // call the api with guzzle
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $url, [
                'headers' => [
                    'X-RapidAPI-Host' => 'bing-image-search1.p.rapidapi.com',
                    'X-RapidAPI-Key' => '4a3256dbf5msh779f74cee3b9586p10b58cjsn8a849b31605f', //'by codestudio',
                    //'X-RapidAPI-Key' => '6e78a086c6msh8c12c9ef8f68d49p15583ajsn7246032b4771', //'by harbans',
                    //'X-RapidAPI-Key' => 'c6fcf1c949msh9399cff7b22b50ap196b36jsn0d4f329acdca' // by mohitanant codebrewinnov
                    //'X-RapidAPI-Key' => '783096a43bmsha953a70ff01066dp1d1380jsn6e342a28f88b'    // by pawan codebrewinnov
                ],
            ]);
            $response = json_decode($res->getBody(), true);
            //  pr($response);
            // images dump geting only image url 
            $imagesArray = $response['value'];
            /*$imagesArrayURL = array_map(function ($item) {
                $url = explode("?",  $item['contentUrl'])[0];
                return $url;
            }, $imagesArray);*/

            $imagesArrayURL = array_map(function ($item) use ($aname) {
                $url = explode("?",  $item['contentUrl'])[0];
                $filename = basename($url);
                return ['image_url' => $url, 'name' => @$aname];
            }, $imagesArray);
            return $imagesArrayURL;
        } catch (\Exception $e) {
            return []; //['image_url' => '', 'imagedefault' => 1, 'name' => @$aname];
            //$imgresponse = $e->getMessage();
            //echo $logoresponsenew = $e->getMessage();die;
            //return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchUnsplashImage($text, $count, $aname)
    {
        $access_key = 'd3zw4Tc0H3Rufh-NaGrULcmWY-Nd3Af7QVhU5Vl2aYc';
        $url = 'https://api.unsplash.com/search/photos?page=1&query=' . $text . '&client_id=' . $access_key . '&per_page=' . $count;
        // call the api with guzzle
        try {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', $url, [
                'headers' => [
                    'content-type' => 'application/json',
                ],
            ]);
            $response = json_decode($res->getBody(), true);
            $imagesArray = $response['results'];

            $imagesArrayURL = array_map(function ($item) use ($aname) {
                //$url = explode("?",  $item['urls']['regular'])[0];
                $url = $item['urls']['regular'];
                $filename = basename($url);
                return ['image_url' => $url, 'imagedefault' => 0, 'name' => @$aname];
            }, $imagesArray);

            return $imagesArrayURL;
        } catch (\Exception $e) {
            //echo $imgresponse = $e->getMessage();die;
            return [];
            //return ['image_url' => 'https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/default/default_images.png', 'imagedefault' => 1, 'name' => @$aname];
        }
        return $imagesArrayURL;
    }

    public function searchOriginalGoogleImage($text = "mattress", $count = 1, $aname = "uas")
    {
        // call the api with guzzle
        try {
            //$apiKey = "AIzaSyBFeVmVwvLoDlxA8E3uhS2YLAfdYa_zN9E";// mohit
            //$apiKey   = "AIzaSyA0XgUSdDiQBFfYrwf1isC6-mBaLgvAPQc"; // harbans
            $text = $text." in png or jpg";
            $apiKey       = env('customsearch') ??  "AIzaSyDHXxMIXMIfpqcJz1drNslAF69hSS1oATg"; // shivani mam
            $client = new \GuzzleHttp\Client();
            $response = $client->get("https://customsearch.googleapis.com/customsearch/v1?cx=939a29ddb9c9f4a9f&q=$text&searchType=image&key=$apiKey&safe=high&num=$count&start=4", [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $imagesArray = $data['items']; //pr($imagesArray);
            $imagesArrayURL = array_map(function ($item) use ($aname,$text) {
                $url = explode("?",  $item['link'])[0];
                $last_dig = substr($url, -4);
                if (in_array($last_dig, ['.jpg', 'JPEG', '.PNG', '.png', 'jpeg', '.JPG','webp'])) {
                    return ['image_url' => $url, 'imagedefault' => 0, 'name' => @$aname];
                }
                    // }else{
                //     $image = $this->imageGet($text);
                    
                //     // Log::info("images : ".$aname);
                
                //     Log::info("new images : ");
                //     Log::info($image);
                //     return ['image_url' =>$image , 'imagedefault' => 0, 'name' => @$aname];
                // }
                return ['image_url' => 'default/default_images.png', 'imagedefault' => 0, 'name' => @$aname];
            }, $imagesArray);
            // Log::info('searchOriginalGoogleImage');
            // Log::info($imagesArrayURL);
            return $imagesArrayURL;
        } catch (\Exception $e) {
            return [];
            // return ['image_url' => 'https://logodix.com/logo/1615890.jpg', 'imagedefault' => 1, 'name' => @$aname];
        }

        return $imagesArrayURL;
    }

    public function imageGet($text = "mattress", $count = 2, $aname = "uas")
    {
        // call the api with guzzle
        try {
            // $apiKey = "AIzaSyBFeVmVwvLoDlxA8E3uhS2YLAfdYa_zN9E";// mohit
            // $apiKey   = "AIzaSyA0XgUSdDiQBFfYrwf1isC6-mBaLgvAPQc"; // harbans
            $apiKey       = env('customsearch') ??  "AIzaSyDHXxMIXMIfpqcJz1drNslAF69hSS1oATg"; // shivani mam
            $client      = new \GuzzleHttp\Client();
            $response = $client->get("https://customsearch.googleapis.com/customsearch/v1?cx=939a29ddb9c9f4a9f&q=$text&searchType=image&key=$apiKey&safe=high&num=$count", [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $imagesArray = $data['items']; //pr($imagesArray);
            // Log::info("imageGet");
            //":large"
            $valid_image = "default/default_images.png";
            foreach( $imagesArray as $key =>$item){
                $url = explode("?",  $item['link'])[0];
                // Log::info("image url : " .$url);
                $last_dig = substr($url, -4);
                // Log::info( $last_dig. " image url : " .$url);
                if (in_array($last_dig,  ['.jpg', 'JPEG', '.PNG', '.png', 'jpeg', '.JPG','webp'])) {
                    $valid_image= $url;
                }
               
            }
            // Log::info("imagesArray valid image : ". $valid_image );
            // Log::info($imagesArray);
            return $valid_image;

            $access_key = 'd3zw4Tc0H3Rufh-NaGrULcmWY-Nd3Af7QVhU5Vl2aYc';
            $url = 'https://api.unsplash.com/search/photos?page=1&query=' . $text . '&client_id=' . $access_key . '&per_page=' . $count;
            // call the api with guzzle
            try {
                $client = new \GuzzleHttp\Client();
                $res = $client->request('GET', $url, [
                    'headers' => [
                        'content-type' => 'application/json',
                    ],
                ]);
                $response = json_decode($res->getBody(), true);
                $imagesArray = $response['results'];
                foreach( $imagesArray as $key =>$item){
                    return $item['urls']['regular'];
                }
                
                return 'default/default_images.png';
            
            } catch (\Exception $e) {
                return 'default/default_images.png';
            }

            return 'default/default_images.png';
        } catch (\Exception $e) {
            return 'default/default_images.png';
        }
    }
}
