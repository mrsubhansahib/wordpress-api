<?php

namespace App\Http\Controllers;

use App\Http\Resources\metalPriceResource;
use App\Models\metal_price;
use Illuminate\Http\Request;

class metalPriceController extends Controller
{
    public function index()
    {
        function gold_silver_prices(){
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.metalpriceapi.com/v1/latest?api_key=37b0e36c9fe2eddfd6a6dc14cafba3bd&base=GBP&currencies=XAU,XAG',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response);
            $values = ["GBPXAG" => $response->rates->GBPXAG, "GBPXAU" => $response->rates->GBPXAU];
            return $values;
        }

        $current_date = date('d-m-Y');
        $dateString = metal_price::select('created_at')->orderBy('id', 'desc')->first(); // Get the first record with 'created_at'

        if(!$dateString){
            metal_price::create([
                'gold_price' => gold_silver_prices()['GBPXAG'],
                'silver_price' => gold_silver_prices()['GBPXAU'],
            ]);

            $data = metal_price::get();
            return metalPriceResource::collection($data);
        }

        $formated_latest_date = $dateString->created_at->format('d-m-Y'); // Format the 'created_at' field
        if ($current_date == $formated_latest_date) {
            $data = metal_price::get();
            return metalPriceResource::collection($data);

        }else{
            metal_price::create([
                'gold_price' => gold_silver_prices()['GBPXAG'],
                'silver_price' => gold_silver_prices()['GBPXAU'],
            ]);
            return redirect()->back();
        }
    }
}
