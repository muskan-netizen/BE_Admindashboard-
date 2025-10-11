<?php

namespace App\Http\Traits;

use App\Jobs\OnBoardingProcessJob;
use Config, Exception, Log;
use Carbon\Carbon;
use App\Models\{Client, AddonOption, AddonOptionTranslation, AddonSet, AddonSetTranslation, OrderVendorProduct, Banner, MobileBanner, Brand, BrandCategory, BrandTranslation, Cart, CartAddon, CartCoupon, CartProduct, CartProductPrescription, Category, CategoryHistory, CategoryTranslation, Celebrity, CsvProductImport, CsvVendorImport, LoyaltyCard, Order, OrderProductAddon, OrderProductPrescription, OrderProductRating, OrderProductRatingFile, OrderReturnRequest, OrderReturnRequestFile, OrderTax, OrderVendor, Payment, PaymentOption, Product, ProductAddon, ProductCategory, ProductCelebrity, ProductCrossSell, ProductImage, ProductInquiry, ProductRelated, ProductTranslation, ProductUpSell, ProductVariant, ProductVariantImage, ProductVariantSet, Promocode, PromoCodeDetail, PromocodeRestriction, ServiceArea, SlotDay, SocialMedia, Transaction, User, UserAddress, UserDevice, UserLoyaltyPoint, UserPermissions, UserRefferal, UserVendor, UserWishlist, Variant, VariantCategory, VariantOption, VariantOptionTranslation, VariantTranslation, Vendor, VendorCategory, VendorMedia, VendorOrderStatus, VendorSlot, VendorSlotDate, Wallet, CabBookingLayout, CabBookingLayoutCategory, CabBookingLayoutTranslation, AppStyling, AppStylingOption, Tag, TagTranslation, ProductTag, CartProductCoupon};
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Mail\OnBoardAccountCompletedMail;
use Mail;
use DB;

trait OnBoardingProcessManager
{



    public function startOnboarding($onboard_data)
    {

        Log::info('startOnboarding Traits ');

        OnBoardingProcessJob::dispatch($onboard_data);
    }

    public static function updateSQl($schemaName, $business_type)
    {
        try {
            Log::info('table truncate start');

            DB::connection($schemaName)->beginTransaction();
            DB::connection($schemaName)->statement("SET foreign_key_checks=0");
            Cart::on($schemaName)->truncate();
            Brand::on($schemaName)->truncate();
            Order::on($schemaName)->truncate();
            Banner::on($schemaName)->truncate();
            MobileBanner::on($schemaName)->truncate();
            Vendor::on($schemaName)->truncate();
            SlotDay::on($schemaName)->truncate();
            Payment::on($schemaName)->truncate();
            Variant::on($schemaName)->truncate();
            Product::on($schemaName)->truncate();
            AddonSet::on($schemaName)->truncate();
            Category::on($schemaName)->truncate();
            OrderTax::on($schemaName)->truncate();
            Promocode::on($schemaName)->truncate();
            CartAddon::on($schemaName)->truncate();
            Celebrity::on($schemaName)->truncate();
            VendorSlot::on($schemaName)->truncate();
            CartCoupon::on($schemaName)->truncate();
            AddonOption::on($schemaName)->truncate();
            LoyaltyCard::on($schemaName)->truncate();
            ServiceArea::on($schemaName)->truncate();
            VendorMedia::on($schemaName)->truncate();
            CartProduct::on($schemaName)->truncate();
            SocialMedia::on($schemaName)->truncate();
            Transaction::on($schemaName)->truncate();
            OrderVendor::on($schemaName)->truncate();
            ProductAddon::on($schemaName)->truncate();
            ProductImage::on($schemaName)->truncate();
            ProductUpSell::on($schemaName)->truncate();
            VariantOption::on($schemaName)->truncate();
            BrandCategory::on($schemaName)->truncate();
            VendorSlotDate::on($schemaName)->truncate();
            VendorCategory::on($schemaName)->truncate();
            ProductRelated::on($schemaName)->truncate();
            ProductVariant::on($schemaName)->truncate();
            ProductInquiry::on($schemaName)->truncate();
            ProductCategory::on($schemaName)->truncate();
            CsvVendorImport::on($schemaName)->truncate();
            VariantCategory::on($schemaName)->truncate();
            PromoCodeDetail::on($schemaName)->truncate();
            CategoryHistory::on($schemaName)->truncate();
            CsvProductImport::on($schemaName)->truncate();
            BrandTranslation::on($schemaName)->truncate();
            ProductCelebrity::on($schemaName)->truncate();
            ProductCrossSell::on($schemaName)->truncate();
            ProductVariantSet::on($schemaName)->truncate();
            VendorOrderStatus::on($schemaName)->truncate();
            CartProductCoupon::on($schemaName)->truncate();
            OrderProductAddon::on($schemaName)->truncate();
            OrderProductRating::on($schemaName)->truncate();
            ProductTranslation::on($schemaName)->truncate();
            VariantTranslation::on($schemaName)->truncate();
            OrderVendorProduct::on($schemaName)->truncate();
            OrderReturnRequest::on($schemaName)->truncate();
            AddonSetTranslation::on($schemaName)->truncate();
            CategoryTranslation::on($schemaName)->truncate();
            ProductVariantImage::on($schemaName)->truncate();
            PromocodeRestriction::on($schemaName)->truncate();
            AddonOptionTranslation::on($schemaName)->truncate();
            OrderProductRatingFile::on($schemaName)->truncate();
            OrderReturnRequestFile::on($schemaName)->truncate();
            CartProductPrescription::on($schemaName)->truncate();
            CartProductPrescription::on($schemaName)->truncate();
            VariantOptionTranslation::on($schemaName)->truncate();
            OrderProductPrescription::on($schemaName)->truncate();
            CabBookingLayout::on($schemaName)->truncate();
            CabBookingLayoutCategory::on($schemaName)->truncate();
            CabBookingLayoutTranslation::on($schemaName)->truncate();
            AppStyling::on($schemaName)->truncate();
            AppStylingOption::on($schemaName)->truncate();
            Tag::on($schemaName)->truncate();
            TagTranslation::on($schemaName)->truncate();
            ProductTag::on($schemaName)->truncate();

            Log::info('table truncate done');
            Log::info('sql_files import');
            $sqlfilename = config('constants.BusinessTypesDataBase.' . $business_type);
            Log::info($sqlfilename);
            // DB::connection($schemaName)->unprepared(file_get_contents((asset('sql_files/suel.sql'))));
            DB::connection($schemaName)->unprepared(file_get_contents((public_path('sql_files/' .  $sqlfilename))));
            Log::info('sql_files done');

            $updateVendor = [
                'dine_in' =>  config('constants.VendorTypesAccordingBusiness.' . $business_type . '.dinein'),
                'takeaway' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.takeaway'),
                'delivery' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.delivery'),
                'rental' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.rental'),
                'pick_drop' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.pick_drop'),
                'on_demand' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.on_demand'),
                'laundry' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.laundry'),
                'appointment' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.appointment'),
                'p2p' => 0
            ];
            $updateCP = [
                'dinein_check' =>  config('constants.VendorTypesAccordingBusiness.' . $business_type . '.dinein'),
                'takeaway_check' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.takeaway'),
                'delivery_check' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.delivery'),
                'rental_check' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.rental'),
                'pick_drop_check' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.pick_drop'),
                'on_demand_check' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.on_demand'),
                'laundry_check' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.laundry'),
                'appointment_check' => config('constants.VendorTypesAccordingBusiness.' . $business_type . '.appointment'),
                'p2p_check' => 0
            ];
            DB::connection($schemaName)->table('vendors')->update($updateVendor);
            DB::connection($schemaName)->table('client_preferences')->update($updateCP);

            DB::connection($schemaName)->commit();
            DB::connection($schemaName)->statement("SET foreign_key_checks=1");
            return 1;
        } catch (\PDOException $e) {
            Log::info('getting eerror in Import Sql done');
            Log::info($e->getMessage());
            DB::connection($schemaName)->rollBack();
            return 0;
        }
    }

    public static function isValidImage($url)
    {
        // return true;
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false, // Disable peer verification (not recommended for production)
                'verify_peer_name' => false, // Disable peer name verification (not recommended for production)
            ],
        ]);
        //$headers = get_headers($url);
        $headers = @get_headers($url, 0, $context);

        if ($headers !== false) {
            $statusCode = substr($headers[0], 9, 3); // Extract the HTTP status code

            if ($statusCode === '200') {
                // Check if the URL is using HTTPS
                if (parse_url($url, PHP_URL_SCHEME) === 'https') {
                    return true;
                } else {
                    //echo "The URL is reachable, but it doesn't use HTTPS.";
                }
            } else {
                //echo "The URL returned a non-200 status code: $statusCode";
            }
        } else {
            //echo "Error fetching headers for the URL. Check your connection and try again.";
        }

        // if (stripos($headers[0], '200 OK') !== false) {
        //     // Check if the URL is using HTTPS
        //     if (parse_url($url, PHP_URL_SCHEME) === 'https') {
        //         return true;
        //     }
        // }
        return false;
    }

    public static function isImageURLValid($url)
    {
        $headers = @get_headers($url);

        if ($headers && strpos($headers[0], '200 OK')) {
            // The URL is valid and the image exists (HTTP status code 200 OK).
            return true;
        }

        // The URL is not valid or the image does not exist.
        return false;
    }


    public  function checkDatabase($database)
    {
        if (!Client::on('god')->where('database_name', $database)->exists()) {
            return $database;
        }

        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $randomString = "X";

            $database =   $database . $randomString;
        } while (!empty(Client::on('god')->where('database_name', $database)->exists()));
        return $database;
    }

    public static function randomString()
    {
        $random_string = substr(md5(microtime()), 0, 8);
        // after creating, check if string is already used

        while (Client::where('code', $random_string)->exists()) {
            $random_string = substr(md5(microtime()), 0, 8);
        }
        return $random_string;
    }

    public static function  checkProductSku($sku, $schemaName)
    {
        if (!DB::connection($schemaName)->table('products')->where('sku', $sku)->exists()) {
            return $sku;
        }

        do {
            $skunew =   $sku . Self::randomString();
        } while (!empty(DB::connection($schemaName)->table('products')->where('sku', $skunew)->exists()));
        return $skunew;
    }

    public static function checkCategorySku($slug, $schemaName)
    {
        if (!DB::connection($schemaName)->table('categories')->where('slug', $slug)->exists()) {
            return $slug;
        }

        do {
            $slugnew =   $slug . Self::randomString();
        } while (!empty(DB::connection($schemaName)->table('categories')->where('slug', $slugnew)->exists()));
        return $slugnew;
    }

    public static function getDbName()
    {

        $dbToUpdate = DB::connection('god')->table('dummy_db')->where('status', 0)->first();

        if (!empty($dbToUpdate)) {
            DB::connection('god')->table('dummy_db')->where('dbname', $dbToUpdate->dbname)->update(['status' => 1]);
            $dbname = explode('_', $dbToUpdate->dbname);
            return $dbname[1];
        } else {
            $dbname = Self::createDBNew();
            return $dbname;
        }
    }

    public static function createDBNew()
    {
        $dbname = 'royo_' . Self::randomStringName(8);
        $database = Self::createDbName($dbname);

        $schemaName = 'royo_' . $database ?: config("database.connections.mysql.database");
        $default = [
            'driver' => env('DB_CONNECTION', 'mysql'),
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => $schemaName,
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => false,
            'engine' => null,
            'options'   => [
                \PDO::ATTR_EMULATE_PREPARES => true
            ]
        ];

        $query = "CREATE DATABASE $schemaName;";
        Log::info("database created: {$database}!");
        DB::statement($query);

        Config::set("database.connections.$schemaName", $default);
        config(["database.connections.mysql.database" => $schemaName]);
        Log::info('migrate start');
        Artisan::call('migrate', ['--database' => $schemaName, '--force' => true]);

        Log::info("migrate done");

        Log::info("DatabaseSeeder start");
        try {
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder', '--database' => $schemaName, '--force' => true]);
        } catch (\PDOException $e) {
            Log::info('getting eerror in DatabaseSeeder');
            Log::info($e->getMessage());
            //DB::connection($schemaName)->rollBack();
            return 0;
        }
        Log::info("DatabaseSeeder done");

        DB::disconnect($schemaName);
        return $database;
    }

    static function randomStringName($n)
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randomString = '';

        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }

    static public function createDbName($dbname)
    {
        if (!DB::connection('god')->table('dummy_db')->where('status', 0)->where('dbname', $dbname)->exists()) {
            $dbid = DB::connection('god')->table('dummy_db')->insertGetId([
                'dbname' => $dbname,
                'business_type' => 'vc_ecommerce',
                'status' => 0,
            ]);
            $dbname = explode('_', $dbname);

            return $dbname[1];
        }

        do {
            $randomString = $this->randomStringName(2);

            $database =   $dbname . $randomString;
        } while (!empty(DB::connection('god')->table('dummy_db')->where('status', 0)->where('dbname', $database)->exists()));

        $dbid = DB::connection('god')->table('dummy_db')->insertGetId([
            'dbname' => $database,
            'business_type' => 'vc_ecommerce',
            'status' => 0,
        ]);

        $dbname = explode('_', $database);

        return $dbname[1];
        //return $database;
    }



    public static function insertDynamicHtml($schemaName,  $language_id, $business_type, $whychooseustext)
    {
        Log::info("insertDynamicHtml count 0");
        \App\Models\DynamicContentForHtml::savingContent($schemaName, $business_type, $whychooseustext);
        Log::info("insertDynamicHtml done");
    }


    public function insertDynamicHtml2($schemaName, $language_id)
    {
        Log::info("insertDynamicHtml start");
        $already = DB::connection($schemaName)->table('cab_booking_layouts')->where('slug', 'dynamic_page')->count();
        if ($already == 0) {
            Log::info("insertDynamicHtml count 0");
            $cab_booking_id = DB::connection($schemaName)->table('cab_booking_layouts')->insertGetId([
                'title' => 'Dynamic HTML',
                'slug' => 'dynamic_page',
                'order_by'   => 13,
                'created_at' => Carbon::now(),
            ]);

            $home_page = DB::connection($schemaName)->table('cab_booking_layout_transaltions')->insertGetId([
                'title' => 'Dynamic HTML',
                'cab_booking_layout_id' =>  $cab_booking_id,
                'language_id' => $language_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'body_html' => '<!DOCTYPE html>
                <html>
                <head>
                    <title>Chomart</title>
                    <meta charset="utf-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                    <!-- Bootstrap CSS -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
                    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous"></script>
                
                <style type="text/css">
                .Why-Choose {
                    position: relative;
                    padding: 0;
                    margin-top: 50px ;
                }
                
                .Why-Choose::before {
                    content: "";
                    background-image: url(https://www.drishtigems.com/templates/s-cart-light/images/bg-04.jpg);
                    background-repeat: no-repeat;
                    background-position: center center;
                    background-attachment: scroll;
                    background-size: 100% 100%;
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    height: 100%;
                    opacity: 0.1;
                }
                
                .Why-Choose.WC-about-page {
                    padding-top: 200px;
                }
                
                .Why-Choose.WC-about-page .row {
                    margin: 0 -15px 15px;
                    padding: 0;
                    border: 0;
                }
                
                .Why-Choose.WC-about-page h5 {
                    font-size: 14px;
                    text-transform: uppercase;
                }
                
                .Why-Choose .row {
                    margin: 0;
                    padding: 50px 0 20px;
                }
                
                .Why-Choose .client-title p {
                    text-align: center;
                }
                
                .Why-Choose h2 {
                    margin: 0 0 25px;
                    color: #262626;
                    font-size: 28px;
                    font-style: normal;
                    font-weight: 700;
                    line-height: normal;
                }
                
                .Why-Choose p {
                    color: #727272;
                    font-size: 14px;
                    font-style: normal;
                    font-weight: 400;
                    line-height: 27px;
                }
                
                .Why-Choose .left {
                    padding: 0 50px 0 15px;
                    margin-bottom: 30px;
                }
                
                .Why-Choose ul {
                    margin: 65px -270px 0 0;
                    padding: 10px;
                    position: relative;
                    z-index: 5;
                    border-radius: 10px;
                    border: 1px solid #ECECEC;
                    background: #FFF;
                    box-shadow: 0px 2px 6px 0px rgba(0, 0, 0, 0.08);
                }
                
                .Why-Choose ul li {
                    text-align: center;
                    display: inline-block;
                    vertical-align: top;
                    list-style: none;
                    padding: 10px 15px;
                    border-right: 1px solid #ECECEC;
                    width: 19%;
                    text-transform: uppercase;
                }
                
                .Why-Choose ul li:last-child {
                    border-right: 0;
                    padding-right: 24px;
                }
                
                .Why-Choose ul li img {
                    display: block;
                    margin: 0 auto 15px;
                    width: 40.809px;
                    height: 32px;
                }
                
                .Why-Choose h5 {
                    color: #262626;
                    text-align: center;
                    font-size: 14px;
                    font-style: normal;
                    font-weight: 600;
                    line-height: normal;
                    letter-spacing: 0.14px;
                    text-transform: capitalize;
                }
                
                .Why-Choose .btn {
                    background: none;
                    border: 0;
                    border-bottom: 2px solid #eeae4b;
                    text-decoration: none;
                    -webkit-border-radius: 0;
                    -moz-border-radius: 0;
                    border-radius: 0;
                    position: relative;
                    z-index: 1;
                    display: inline-block;
                    text-align: center;
                    font-size: 16px;
                    color: #eeae4b;
                    margin: 0;
                    padding: 0;
                    text-shadow: none;
                    box-shadow: none;
                    text-transform: uppercase;
                }
                
                .Why-Choose .btn:hover {
                    color: #323232;
                }
                
                .Why-Choose .btn:after {
                    content: "";
                    position: absolute;
                    height: 2px;
                    left: 0;
                    bottom: -2px;
                    width: 0;
                    -webkit-transition: all .3s;
                    -moz-transition: all .3s;
                    -o-transition: all .3s;
                    transition: all .3s;
                    background: #eeae4b;
                    z-index: -1;
                }
                
                .Why-Choose .btn:hover:after {
                    width: 100%;
                    background: #323232;
                }
                
                .Why-Choose  .images {
                    width: 100%;
                }
                
                @media (min-width: 960px) and (max-width: 1090px) {
                    .Why-Choose ul {
                        margin: 25px -345px 0 0;
                    }
                }
                
                
                @media (min-width: 768px) and (max-width: 959px) {
                    .Why-Choose ul {
                        margin: 25px -345px 0 0;
                    }
                }
                
                @media (min-width: 481px) and (max-width: 767px) {
                    .Why-Choose ul {
                        margin: 25px 0 0;
                    }
                    .Why-Choose ul li {
                        border: 1px solid #e6e6e6 !important;
                        margin: 0 0 2px;
                        width: 49%;
                    }
                }
                
                
                @media (min-width: 320px) and (max-width: 480px){
                    .vendors section:last-child {
                        margin: 0 auto;
                    }
                    .Why-Choose .row {
                        padding-top: 0;
                    }
                    .Why-Choose .left {
                        padding: 0;
                    }   
                    .Why-Choose ul {
                        margin: 25px 0 0;
                        padding: 10px 0 0;
                    }
                    .Why-Choose ul li {
                        display: block;
                        border-right: 0 !important;
                        border-bottom: 1px solid #e6e6e6 !important;
                        width: 100%;
                    }
                
                    .Why-Choose h2 {
                        color: #505050;
                        font-size: 20px;
                        margin: 0 0 15px;
                    }
                }
                </style>
                    <section class="Why-Choose">
                        <div class="container">
                        <div class="row">
                            <div class="col-sm-7 left mb-30">
                                <h2>Why Choose Us</h2>
                                <p>Choose us for your business needs, and you will discover a trusted partner dedicated to your success. With a proven track record of delivering exceptional results and a team of dedicated professionals, we are your trusted partner. We understand the unique challenges and opportunities your business faces, and we tailor our solutions to meet your specific goals. When you choose us, you are choosing a path to growth, success, and a brighter future for your business. </p>
                                <ul>
                                    <li>
                                    <figure><img src="https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/pbsEIjjuY8DfN1F9bTU3DK2rkz0naOH8SagdkTmV.svg" alt="Image"></figure>
                                    <h5>Pay <br> Online</h5>
                                    </li>
                                    <li>
                                    <figure><img src="https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/dsVowQKk6LuGMcQ1AaZRXL3OvOAPOd5yQFIPuyjG.svg" alt="Image"></figure>
                                    <h5>Safe <br> & Securen</h5>
                                    </li>
                                    <li>
                                    <figure><img src="https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/EyzNI7XLNpF6q23u9EGOemHPk9HMXknkX3PiCqMN.svg" alt="Image"></figure>
                                    <h5>offers <br> & promo code</h5>
                                    </li>
                                    <li>
                                    <figure><img src="https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/A9Wcmp3ngtMfk3cOfWFt9tkBFjKxrM0CbNtxx4Zy.svg" alt="Image"></figure>
                                    <h5>fast <br>  delivery</h5>
                                    </li>
                                    <li>
                                    <figure><img src="https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/pa51jbIc6srnqVQJO2FeWmMP9iaLa8b1RZnmA46g.svg" alt="Image"></figure>
                                    <h5>24x7 <br> support</h5>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-sm-5 right mrb-30">
                                <figure><img class="images" src="https://s3.us-west-2.amazonaws.com/royoorders2.0-assets/prods/c4Cz7QhbVIXebyhWqn8xfyXctcfUMlYz1yV0znrL.png" alt=""></figure>
                            </div>
                        </div>
                        </div>
                    </section>
                </body>
                </html>',
            ]);
            Log::info("insertDynamicHtml insert DONE");
        }
    }
}
