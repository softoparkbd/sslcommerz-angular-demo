<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Library\SslCommerz\SslCommerzNotification;

class SslCommerzPaymentController extends Controller
{

    public function exampleEasyCheckout()
    {
        return view('exampleEasycheckout');
    }

    public function exampleHostedCheckout()
    {
        return view('exampleHosted');
    }

    public function index(Request $request)
    {
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = 'Payment'; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = 'Customer Name';
        $post_data['cus_email'] = 'Customer Email';
        $post_data['cus_add1'] = 'Customer Address';
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = 'Mobile Number';
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['user_id'] = "User ID";
        $post_data['plan_id'] = "Plan ID";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";
        $post_data['created_at'] = date('Y-d-m H:i:s');
        $post_data['updated_at'] = date('Y-d-m H:i:s');

        return response()->json($post_data);

        #Before  going to initiate the payment order status need to insert or update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency']
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    // Method that wowweber uses 
    public function payViaAjax(Request $request)
    {

        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

        $post_data = array();
        $post_data['total_amount'] = $request->input('amount'); # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = uniqid(); // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $request->input('customer_name');
        $post_data['cus_email'] = $request->input('customer_email');
        $post_data['cus_add1'] = $request->input('address');
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $request->input('customer_phone');
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "Store Test";
        $post_data['ship_add1'] = "Dhaka";
        $post_data['ship_add2'] = "Dhaka";
        $post_data['ship_city'] = "Dhaka";
        $post_data['ship_state'] = "Dhaka";
        $post_data['ship_postcode'] = "1000";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "Computer";
        $post_data['product_category'] = "Goods";
        $post_data['product_profile'] = "physical-goods";

        # OPTIONAL PARAMETERS
        $post_data['user_id'] = $request->input('user_id');
        $post_data['plan_id'] = $request->input('plan_id');
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";
        $post_data['created_at'] = date('Y-m-d H:i:s');
        $post_data['updated_at'] = date('Y-m-d H:i:s');

        // return response()->json($post_data);

        #Before  going to initiate the payment order status need to update as Pending.
        $update_product = DB::table('orders')
            ->where('transaction_id', $post_data['tran_id'])
            ->updateOrInsert([
                'name' => $post_data['cus_name'],
                'email' => $post_data['cus_email'],
                'phone' => $post_data['cus_phone'],
                'amount' => $post_data['total_amount'],
                'status' => 'Pending',
                'address' => $post_data['cus_add1'],
                'transaction_id' => $post_data['tran_id'],
                'currency' => $post_data['currency'],
                'user_id' => $post_data['user_id'],
                'plan_id' => $post_data['plan_id'],
                'created_at' => $post_data['created_at'],
                'updated_at' => $post_data['updated_at'],
            ]);

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payment gateway here )
        $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }
    public function success2(Request $request){
        echo "Success";
        return redirect()->to('/my-api-call');
    }
    
    public function apicall(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'localhost:4200/secure/billing/subscriptions',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
                "user_id": 3,
                "renews_at": "2021-01-20 18:00:00",
                "plan_id": 1,
                "description": "null"
        }',
          CURLOPT_HTTPHEADER => array(
            'X-XSRF-TOKEN: eyJpdiI6ImVpa2s3UDRZVGVYQjdCMHlwZFVMb0E9PSIsInZhbHVlIjoiRUM1NkxBc0h6bTJ3c0wxXC9MblRqbEtcL3o2TWdkWW10RHAxNzVwVnpsalB1dkpnSHY1c01zeVRJU1dBZlwvWEdzdUVwR1VVTEpza2U2cUVvdDhWWG5abGc9PSIsIm1hYyI6ImZjN2JiOGQ1YjAyNmVjZTlmZWM4YTc1NDBkYTg2M2ZlODU1YjQ3YmI1NDZlM2ZjN2UwMzEwNGQyYzE2MDJmNjYifQ==',
            'Cookie: WoWweber_cookie_notice=1; wowweber.theme=light; remember_web_59ba36addc2b2f9401580f014c7f58ea4e30989d=eyJpdiI6IkNQRFQxYnJKZUx4c0NEMXM2Z1l4bkE9PSIsInZhbHVlIjoiUFNUQ0luZytqOEdXMjh0SHdDN2U2bGlXeXhwemZHaHhCWm95MVF0VVExT3NDYzJpOWUzcGZwQ3gxbStSa29oZGVsM2RwTWpSVFIxek9nZW1hUmp2VWZrc3p0U01nckpEaENvemJ3R3NuRWc9IiwibWFjIjoiN2YyYzE5YmU2Zjk1OTc1MzM1MzZjNGNlM2ZiZGVhNTg3YmUyMGQ2NjBmMGQ5ODkwOTg5ZWM4ZWE3Y2M4NjQxZiJ9; XSRF-TOKEN=eyJpdiI6ImVpa2s3UDRZVGVYQjdCMHlwZFVMb0E9PSIsInZhbHVlIjoiRUM1NkxBc0h6bTJ3c0wxXC9MblRqbEtcL3o2TWdkWW10RHAxNzVwVnpsalB1dkpnSHY1c01zeVRJU1dBZlwvWEdzdUVwR1VVTEpza2U2cUVvdDhWWG5abGc9PSIsIm1hYyI6ImZjN2JiOGQ1YjAyNmVjZTlmZWM4YTc1NDBkYTg2M2ZlODU1YjQ3YmI1NDZlM2ZjN2UwMzEwNGQyYzE2MDJmNjYifQ%3D%3D; laravel_session=eyJpdiI6InhOV29aMGoxK05qaXFCS3AzZ1ozaWc9PSIsInZhbHVlIjoiSUFWUXowaFhiSk5KQmtlRFgzUE1iVVdmNFFSUzJvVFVhWW5NdHgzNWQ0czhacVpxMXcwS0JjbjAzeDNtQ1BHOFp3bTJDM055cjA3Y3FaVlR5Sk11Umc9PSIsIm1hYyI6ImJmNzA2ZDMzMjcxYzMyNzQ1OTU4YmIzZTJlYzFmMzUyZjJmOGUwZWY1N2YwNWRiMTg0NGQ0MTA5NDRkYWQ3ODEifQ%3D%3D; XSRF-TOKEN=eyJpdiI6ImVBa3o1TWlDYVpSeFh3d1lKRndFd2c9PSIsInZhbHVlIjoiaDM0clcwTkVTMkl1cUJNXC81MkxqNWVkZ2Facm1aV1pnZ3BiZ01IQTc5MERJREF6MVpBK0YwbStaTHVTRmw1Y1pvUDdBS3F3MEk1SGVpZUVTQWE0WGhBPT0iLCJtYWMiOiJkMjE1NDAwYjZjZjZkYTZhZjFkYzljMjNkOWRmMTQ2OTc4NWI5MWE1M2Y1NTZkM2EwNGFhN2M4NzVlYTU2MDY4In0%3D; laravel_session=eyJpdiI6InhFUWJwMHFoaE9SYVR4XC9cL2h2SHVTdz09IiwidmFsdWUiOiJTc2lvSjB2bmM2V1wvZFNqUFhCRnZ1RjJqdWYwT0lNYkhLUXpXTXFCa09uY3NtVldMbys1S25JUGgwTFI5SFNQR09STmJGajZheG0xMEJ5dzZRXC94T0FnPT0iLCJtYWMiOiI1YjFmYTcxYTM5MzAwMmQ0NjEyZTY0NGRmYjkzYTk5NzE3YjhlMzQ1ZGQ2ODBhZWUyYWUwZjkyY2M5YmYwYjhjIn0%3D; XSRF-TOKEN=eyJpdiI6ImFiTjJcL0FhdmhaMGRab200Tmc5SzNnPT0iLCJ2YWx1ZSI6IngwbnpCWSttd2krandyeCt6MHd4Skt1XC80MW1EN1ZxTUxaK3l0VkFcL295RThIQUZ6TTRySlJlXC9JNEFONWJSM2pqbFJrSVpSTEFQbjMxMWRyZ1ZjMEt3PT0iLCJtYWMiOiIwYmJkNmUzZGI1OTViMmFlYTc4ODZmMDAzOTc5Nzg2ZDZmMzY0ODlmNWI1ODI2YTQxZjI3M2Y3ZGIwNzc1ODFlIn0%3D; laravel_session=eyJpdiI6IlVaSloreFJCRWRUeUNPNUVNWGR1dVE9PSIsInZhbHVlIjoiamRTOW9tenJOeXZHSmI2Mk9idThvNlRFYWIzMEZ3QTRkYTgwVUs2VUEwMUJ1TGxaZVFmZmFVRmZlbnFmRmVkMThsVXVMSXBSUDB4WitTNDhDbjFuVXc9PSIsIm1hYyI6IjMyZDU5YjU3OTAxOGRjM2JlM2U1ZWEwODAzY2U4NGJiN2YxYmZjZTlkODQ0MzcxZWY3OGRkM2Y4YmM3NjA0OWMifQ%3D%3D',
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;
        return redirect('http://localhost:4200/dashboard')->with('status', 'Success');
    
    }

    public function success(Request $request)
    {
        echo "Transaction is Successful";

        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $sslc = new SslCommerzNotification();

        #Check order status in order table against the transaction id or order id.
        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());

            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Processing']);

                echo "<br >Transaction is successfully Completed";
                // displays all the data 
                dd($request->all());
            } else {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                Here you need to update order status as Failed in order table.
                */
                $update_product = DB::table('orders')
                    ->where('transaction_id', $tran_id)
                    ->update(['status' => 'Failed']);
                echo "validation Fail";
            }
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            /*
             That means through IPN Order status already updated. Now you can just show the customer that transaction is completed. No need to udate database.
             */
            echo "Transaction is successfully Completed";
        } else {
            #That means something wrong happened. You can redirect customer to your product page.
            echo "Invalid Transaction";
        }
    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Failed']);
            echo "Transaction is Falied";
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order_detials = DB::table('orders')
            ->where('transaction_id', $tran_id)
            ->select('transaction_id', 'status', 'currency', 'amount')->first();

        if ($order_detials->status == 'Pending') {
            $update_product = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->update(['status' => 'Canceled']);
            echo "Transaction is Cancel";
        } else if ($order_detials->status == 'Processing' || $order_detials->status == 'Complete') {
            echo "Transaction is already Successful";
        } else {
            echo "Transaction is Invalid";
        }
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_id', $tran_id)
                ->select('transaction_id', 'status', 'currency', 'amount')->first();

            if ($order_details->status == 'Pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($tran_id, $order_details->amount, $order_details->currency, $request->all());
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Processing']);

                    echo "Transaction is successfully Completed";
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_id', $tran_id)
                        ->update(['status' => 'Failed']);

                    echo "validation Fail";
                }
            } else if ($order_details->status == 'Processing' || $order_details->status == 'Complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully Completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }
}
