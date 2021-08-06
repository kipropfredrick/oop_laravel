
// public function createTransaction($account,$amount,$biller_name,$phone){

// $hashkey = env('IpayKey');
// $IpayId=env('IpayId');
// $datastring = "account=".$account."&amount=".$amount."&biller_name=".$biller_name."&phone=".$phone."&vid=".$IpayId ;
// $hashid = hash_hmac("sha256", $datastring, $hashkey);
// $url="https://apis.ipayafrica.com/ipay-billing/transaction/create";  

// $fields=Array("vid"=>$IpayId,"hash"=>$hashid,"account"=>$account,"biller_name"=>$biller_name,"phone"=>$phone,"amount"=>$amount);



// // $fields=Array("hash"=>$hashid,"vid"=>"nelmasoft");
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//     $result = curl_exec($ch);
// return $result;
// }

// function validateAccount($account,$account_type){
// $hashkey = env('IpayKey');
// $IpayId=env('IpayId');
// $datastring = "account=".$account."&account_type=".$account_type."&vid=".$IpayId ;
// $hashid = hash_hmac("sha256", $datastring, $hashkey);
// $url="https://apis.ipayafrica.com/ipay-billing/billing/validate/account";  

// $fields=Array("vid"=>$IpayId,"hash"=>$hashid,"account"=>$account,"account_type"=>$account_type);



// // $fields=Array("hash"=>$hashid,"vid"=>"nelmasoft");
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     curl_setopt($ch, CURLOPT_POST, 1);
//     // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//     $result = curl_exec($ch);
// return $result;

// }

// public function phonelookup($prefix){

//   $hashkey = env('IpayKey');
// $IpayId=env('IpayId');
// $datastring = "prefix=".$prefix."&vid=".$IpayId ;
// $hashid = hash_hmac("sha256", $datastring, $hashkey);
// $url="https://apis.ipayafrica.com/ipay-billing/billing/phone/lookup";  

// $fields=Array("vid"=>$IpayId,"hash"=>$hashid,"prefix"=>$prefix);



// // $fields=Array("hash"=>$hashid,"vid"=>"nelmasoft");
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     //curl_setopt($ch, CURLOPT_POST, 1);
//     // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//     $result = curl_exec($ch);
// return $result;

// }

// public function getAccountBalance(){

//   $hashkey = env('IpayKey');
// $IpayId=env('IpayId');
// $datastring = "vid=".$IpayId ;
// $hashid = hash_hmac("sha256", $datastring, $hashkey);
// $url="https://apis.ipayafrica.com/ipay-billing/billing/account/balance?vid=".$IpayId."&hash=".$hashid;  

// $fields=Array("vid"=>$IpayId);



// // $fields=Array("hash"=>$hashid,"vid"=>"nelmasoft");
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     //curl_setopt($ch, CURLOPT_POST, 1);
//     // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//     $result = curl_exec($ch);
// return $result;

// }
// function checkstatus($reference){

//   $hashkey = env('IpayKey');
// $IpayId=env('IpayId');
// $datastring = "reference=".$reference."&vid=".$IpayId ;
// $hashid = hash_hmac("sha256", $datastring, $hashkey);
// $url="https://apis.ipayafrica.com/ipay-billing/transaction/check/status";  

// $fields=Array("vid"=>$IpayId,"hash"=>$hashid,"reference"=>$reference);



// // $fields=Array("hash"=>$hashid,"vid"=>"nelmasoft");
//     $ch = curl_init();
//     curl_setopt($ch, CURLOPT_URL, $url);
//     //curl_setopt($ch, CURLOPT_POST, 1);
//     // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
//     $result = curl_exec($ch);
// return $result;

// }