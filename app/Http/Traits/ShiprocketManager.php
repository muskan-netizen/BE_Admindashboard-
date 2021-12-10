<?php
namespace App\Http\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\{ShippingOption};

trait ShiprocketManager{

    private $email;
    private $password;
    private $api_url;
    public function __construct()
    {
        $ship_creds = ShippingOption::select('credentials', 'test_mode')->where('code', 'shiprocket')->where('status', 1)->first();
        $creds_arr = json_decode($ship_creds->credentials);
        $this->email = $creds_arr->username;
        $this->password = $creds_arr->password;
        $this->api_url = 'https://apiv2.shiprocket.in/v1/external';
    }
   
    public function getAuthToken():object{
        $endpoint='/auth/login';
        $data=[
           'email'=>$this->email,
           'password'=>$this->password
        ];
        $response=$this->postCurl($endpoint,$data);
        return $response;
    }

        public function createOrder($token,$data){
        $endpoint="/orders/create/adhoc";
        // $data=[
        //     'order_id'                  =>  $data['order_id'],
        //     'order_date'                =>  $data['order_date'],
        //     'pickup_location'           =>  $data['pickup_location']??null,
        //     'billing_customer_name'     =>  $data['billing_customer_name'],
        //     'billing_last_name'         =>  $data['billing_last_name']??'',
        //     'billing_address'           =>  $data['billing_address'],
        //     'billing_address_2'         =>  $data['billing_address_2']??null,
        //     'billing_city'              =>  $data['billing_city'],
        //     'billing_pincode'           =>  $data['billing_pincode'],
        //     'billing_state'             =>  $data['billing_state'],
        //     'billing_country'           =>  $data['billing_country'],
        //     "billing_email"             => $data['billing_email'],
        //     "billing_phone"             =>  $data["billing_phone"],
        //     "shipping_is_billing"       => $data["shipping_is_billing"],
        //     "order_items" => $data['order_items'],
        //     "payment_method"=>$data['payment_method']??"Prepaid",
        //     "shipping_charges"=> $data['shipping_charges']??0,
        //     "giftwrap_charges"=>$data['giftwrap_charges']??0 ,
        //     "transaction_charges"=>$data['transaction_charges']??0,
        //     "total_discount"=>$data['total_discount']??0,
        //     "sub_total" =>  $data['sub_total'],
        //     "length"=>  $data['length'],
        //     "breadth"=>  $data['breadth'],
        //     "height"=> $data['height'],
        //     "weight"=>  $data['weight']

        // ];
        $data = array (
              'order_id' => '22411447',
              'order_date' => '2021-08-24 11:11',
              'pickup_location' => 'Primary',
              'channel_id' => '',
              'comment' => 'Reseller: M/s Goku',
              'billing_customer_name' => 'TEsting',
              'billing_last_name' => 'Uzumaki',
              'billing_address' => 'Code brew Labs, CDCL Building',
              'billing_address_2' => '',
              'billing_city' => 'Chandigarh',
              'billing_pincode' => '160002',
              'billing_state' => 'Chandigarh',
              'billing_country' => 'India',
              'billing_email' => 'sujatacodebrew@gmail.com',
              'billing_phone' => '8059272673',
              'shipping_is_billing' => true,
              'shipping_customer_name' => '',
              'shipping_last_name' => '',
              'shipping_address' => '',
              'shipping_address_2' => '',
              'shipping_city' => '',
              'shipping_pincode' => '',
              'shipping_country' => '',
              'shipping_state' => '',
              'shipping_email' => '',
              'shipping_phone' => '',
              'order_items' => 
              array (
                0 => 
                array (
                  'name' => 'Kunai',
                  'sku' => 'chakra123',
                  'units' => 10,
                  'selling_price' => '900',
                  'discount' => '',
                  'tax' => '',
                  'hsn' => 441122,
                ),
                0 => 
                array (
                  'name' => 'Kunai 2',
                  'sku' => 'chakra123-2',
                  'units' => 10,
                  'selling_price' => '900',
                  'discount' => '',
                  'tax' => '',
                  'hsn' => 441122,
                ),
              ),
              'payment_method' => 'Prepaid',
              'shipping_charges' => 0,
              'giftwrap_charges' => 0,
              'transaction_charges' => 0,
              'total_discount' => 0,
              'sub_total' => 18000,
              'length' => 10,
              'breadth' => 15,
              'height' => 20,
              'weight' => 2.5,
            );
        // dd($endpoint,$data,trim($token),$token);
        $response=$this->postCurl($endpoint,$data,trim($token));
        return $response;
    }

    public function generateAWBForShipment($token,$data){
        $endpoint="/courier/assign/awb";
        $response=$this->postCurl($endpoint,$data,$token);
        return $response;
    }

    public function returnOrder($token,$data){
        $endpoint='/orders/create/return';
        $response=$this->postCurl($endpoint,$data,trim($token));
        return $response;
        
    }

    public function cancelOrder($token,$ids){
        $endpoint="orders/cancel";
        $data = [
            'ids' => $ids
        ];
        // dd($data);
        $response=$this->postCurl($endpoint,$data,$token);
        return $response;
    }

    public function trackingThroughAWB($token,$awbCode){
        $endpoint="/courier/track/awb/$awbCode";
        $response=$this->getCurl($endpoint,$token);
        return $response;
    }




    public function trackingThroughShipmentId($token,$shipmentId){
        $endpoint="/shipments/$shipmentId";
        $response=$this->getCurl($endpoint,$token);
        return $response;
    }

    

    public function updateOrderPickupAddress($token,$data)
    {
        $endpoint="orders/address/pickup";
        $response=$this->patchCurl($endpoint,$data,$token);
        return $response;
    }

    public function updateOrderDeliveryAddress($token,$data){
        $endpoint="orders/address/update";
        $response=$this->postCurl($endpoint,$data,$token);
        return $response;
    }


    public function addAddress($token,$data){
        $endpoint='/settings/company/addpickup';
        $data = array (
              'pickup_location' => "".rand(1000,99999999),
              'name' => 'Deadpool',
              'email' => 'deadpool@yopmail.com',
              'phone' => '8059272673',
              'address' => 'Mutant Facility, Sector 4 ',
              'address_2' => '',
              'city' => 'Pune',
              'state' => 'Maharshtra',
              'country' => 'India',
              'pin_code' => '110022',
            );
        $response=$this->postCurl($endpoint,$data,trim($token));
        return $response;
    }

    public function checkCourierService($token)
    {
        $endpoint='/courier/serviceability';
        $data = array (
          'pickup_postcode' => 110030,
          'delivery_postcode' => 122002,
          'cod' => 0,
          'weight' => 2,
          'length' => 15,
          'breadth' => 10,
          'height' => 5,
          'declared_value' => 50,
        );
        $response=$this->getCurl($endpoint,$data,trim($token));
        return $response;
    }

    private function postCurl($endpoint,$data,$token=null):object{
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $this->api_url.''.$endpoint);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
                $headers = array();
                $headers[] = 'Accept: */*';
                if(!is_null($token)){

                   $headers[] = "Authorization: Bearer ${token}";
                    // dd( $headers);
                }
              $headers[] = 'Content-Type: application/json';
                 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                return json_decode($result); 
    }

    private function getCurl($endpoint,$token=null):object{
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->api_url.''.$endpoint);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            $headers = array();
            $headers[] = 'Accept: */*';
            if(!is_null($token)){
                $headers[] = "Authorization: Bearer $token";
            }
            $headers[] = 'Content-Type: application/json';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            return json_decode($result); 
    }

    private function patchCurl($endpoint,$data,$token=null):object{
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->api_url.''.$endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        $headers = array();
        $headers[] = 'Accept: */*';
        if(!is_null($token)){
            $headers[] = "Authorization: Bearer $token";
        }
        $headers[] = 'Content-Type: application/json';
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return json_decode($result); 

    }

}