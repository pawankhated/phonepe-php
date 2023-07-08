<?php

$event_payload=[
  "merchantId"=> "MERCHANTID",
  "merchantTransactionId"=> "MT7850590068188104",
  "merchantUserId"=> "MUSERID1",
  "amount"=> 10000,  // 100 RS : 100*100
  "redirectUrl"=> "http://localhost/custom-phone-pay/response.php",
  "redirectMode"=> "POST",
  "callbackUrl"=> "http://localhost/custom-phone-pay/response.php",
  "mobileNumber"=> "9999999999",
  "paymentInstrument"=> [
    "type"=> "PAY_PAGE"
  	]
];

 			$encoded_payload = base64_encode(json_encode($event_payload));
            $saltKey = 'merchantsaltkey';
            $saltIndex = 1;
            $encode = $event_payload;            
            $string = $encoded_payload.'/pg/v1/pay'.$saltKey;            
            $sha256 = hash('sha256',$string);

            $finalXHeader = $sha256.'###'.$saltIndex;


		  $header=array(
		              'Content-Type' => 'application/json',
		              'X-VERIFY' => $finalXHeader
		            );

		  	  $headers = array("Content-Type: application/json",
                    		 'X-VERIFY:'. $finalXHeader
                     		);


  		$phone_pay_url="https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay";
        //$phone_pay_url = 'https://api-preprod.phonepe.com/apis/merchant-simulator/pg/v1/pay';

$ch = curl_init($phone_pay_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['request' => $encoded_payload]));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$decodeJson=json_decode($response);
$paymentUrl=$decodeJson->data->instrumentResponse->redirectInfo->url;
header("Location: ".$paymentUrl);
exit;