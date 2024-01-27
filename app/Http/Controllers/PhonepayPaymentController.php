<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; 
use App\Models\User; 
use Ixudra\Curl\Facades\Curl;

class PhonepayPaymentController extends Controller{
//=========================================OnlinePayment======================================// 

public function index(Request $request){
    
    return view('welcome');

}



public function payWithphonepay(Request $request){
        
    $order = Order::where(['id' => $request->order_id, 'user_id'=>$request->customer_id])->first();
    $user= User::where('id',$request->customer_id)->first();
    $merchantId = "######################"; // put your merchandid 
    $salt_key = "XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"; // put your salt key
   
    $data = array (
           'merchantId' => $merchantId,// change merchandid from your phone pe account  ,
           'merchantTransactionId' => uniqid(),
           'merchantUserId' => $order->user_id, // Change Your  user Id , 
           'amount' => $order->amount*100, // $order->amount*100 change according to order amount
           'redirectUrl' => route('phonepay-status',['order_id'=>$order->id]),  // put order id according to your order table 
           'redirectMode' => 'POST',
           'callbackUrl' => route('phonepay-status',['order_id'=>$order->id]),  //
           'mobileNumber' => $user->phone, // change user Mobile number,
           'paymentInstrument' => array ('type' => 'PAY_PAGE')
         );
          //dd(json_encode($data));
         $encode = base64_encode(json_encode($data, JSON_PRETTY_PRINT));
        
         $saltKey =$salt_key; // change salkey  from your phonepe account 
         $saltIndex = 1;
         $string = $encode.'/pg/v1/pay'.$saltKey;
         $sha256 = hash('sha256',$string);
 
         $finalXHeader = $sha256.'###'.$saltIndex;
 
         $response = Curl::to('https://api.phonepe.com/apis/hermes/pg/v1/pay') 
                 ->withHeader('Content-Type:application/json')
                 ->withHeader('X-VERIFY:'.$finalXHeader)
                 ->withData(json_encode(['request' => $encode]))
                 ->post();
         $rData = json_decode($response);
         //dd($rData);
         return redirect()->to($rData->data->instrumentResponse->redirectInfo->url);
     
     
     }


     public function getPaymentStatus(Request $request,$order_id){
        $input = $request->all();
        //dd($order_id);
        $order = Order::where(['id'=>$order_id])->first();
        $saltKey = "###################################";
        $saltIndex = 1;
        $finalXHeader = hash('sha256','/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'].$saltKey).'###'.$saltIndex;

        $response = Curl::to('https://api.phonepe.com/apis/hermes/pg/v1/status/'.$input['merchantId'].'/'.$input['transactionId'])
                ->withHeader('Content-Type:application/json')
                ->withHeader('accept:application/json')
                ->withHeader('X-VERIFY:'.$finalXHeader)
                ->withHeader('X-MERCHANT-ID:'.$input['merchantId'])
                ->get();
       
        $res=json_decode($response);
        //dd($res->data->responseCode);
        if($res->data->responseCode=="SUCCESS"){
            $order->status="paid";
            $order->save();
           return \redirect()->route('payment-success');  
            
        }else{
            $order->status="fails";
            $order->save();
            return \redirect()->route('payment-fail');
        }
         //dd($res->data->responseCode);        
    }
    public function success(){
        
        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail(){
    
        return response()->json(['message' => 'Payment failed'], 403);
    }

//=========================================/OnlinePayment======================================// 
}
