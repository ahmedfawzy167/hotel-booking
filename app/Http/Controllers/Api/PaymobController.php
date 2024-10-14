<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class PaymobController extends Controller
{
    public function credit()
    {
        $tokens = $this->getToken();
        $order = $this->createOrder($tokens);
        $paymentToken = $this->getPaymentToken($order, $tokens);

        // Generate the payment link
        $paymentLink = 'https://accept.paymob.com/api/acceptance/iframes/' . env('PAYMOB_IFRAME_ID') . '?payment_token=' . $paymentToken;

        // Return the payment link in the response
        return response()->json([
            'status' => 'Success',
            'paymentLink' => $paymentLink,
        ], 301);
    }

    public function getToken()
    {
        $response = Http::post('https://accept.paymob.com/api/auth/tokens', [
            'api_key' => env('PAYMOB_API_KEY')
        ]);

        if ($response->successful()) {
            return $response->json()['token'];
        }

        throw new \Exception('Failed to Retrieve Token: ' . $response->body());
    }

    public function createOrder($tokens)
    {
        //this function takes last step token and send new order to paymob dashboard

        // an example for test only
        $total = 100;
        $items = [
            [
                "name" => "ASC1515",
                "amount_cents" => "500000",
                "description" => "Smart Watch",
                "quantity" => "1"
            ],
            [
                "name" => "ERT6565",
                "amount_cents" => "200000",
                "description" => "Power Bank",
                "quantity" => "1"
            ]
        ];

        $data = [
            "auth_token" =>   $tokens,
            "delivery_needed" => "false",
            "amount_cents" => $total * 100,
            "currency" => "EGP",
            "items" => $items,

        ];
        $response = Http::post('https://accept.paymob.com/api/ecommerce/orders', $data);
        return $response->json();
    }

    public function getPaymentToken($order, $token)
    {
        //this function to add details to paymob order dashboard and you can fill this data from your Model Class as below

        //we just added dummy data for test
        //all data we fill is required for paymob
        $billingData = [
            "apartment" => '45',
            "email" => "ahmed.moh.fawzy2001@gmail.com",
            "floor" => '5',
            "first_name" => 'ahmed',
            "street" => "NA",
            "building" => "NA",
            "phone_number" => '01063574479',
            "shipping_method" => "NA",
            "postal_code" => "NA",
            "city" => "cairo",
            "country" => "NA",
            "last_name" => "fawzy",
            "state" => "NA"
        ];
        $data = [
            "auth_token" => $token,
            "amount_cents" => 100 * 100,
            "expiration" => 3600,
            "order_id" => $order['id'], // this order id created by paymob
            "billing_data" => $billingData,
            "currency" => "EGP",
            "integration_id" => env('PAYMOB_INTEGRATION_ID')
        ];
        $response = Http::post('https://accept.paymob.com/api/acceptance/payment_keys', $data);

        // Handle potential errors
        if ($response->successful()) {
            return $response->json()['token']; // Access token from array
        }

        throw new \Exception('Failed to retrieve payment token: ' . $response->body());
    }


    public function callback(Request $request)
    {
        //this call back function its return the data from paymob and we show the full response and we checked if hmac is correct means successfull payment
        Log::info('Paymob Callback Data: ', $request->all());
        $data = $request->all();
        ksort($data);
        $hmac = $data['hmac'];
        $array = [
            'amount_cents',
            'created_at',
            'currency',
            'error_occured',
            'has_parent_transaction',
            'id',
            'integration_id',
            'is_3d_secure',
            'is_auth',
            'is_capture',
            'is_refunded',
            'is_standalone_payment',
            'is_voided',
            'order',
            'owner',
            'pending',
            'source_data_pan',
            'source_data_sub_type',
            'source_data_type',
            'success',
        ];
        $connectedString = '';
        foreach ($data as $key => $element) {
            if (in_array($key, $array)) {
                $connectedString .= $element;
            }
        }
        $secret = env('PAYMOB_HMAC');
        $hased = hash_hmac('sha512', $connectedString, $secret);
        if ($hased == $hmac) {
            $status = $data['success'];
            if ($status === "true") {
                return response()->json([
                    'message' => 'Payment was Successful!',
                    'data' => $data
                ], 200);
            }
        } else {
            return response()->json('message', 'Something Went Wrong Please Try Again');
        }
    }
}
