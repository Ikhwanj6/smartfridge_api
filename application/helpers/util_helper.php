<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


	function LoginPasswordValidator($method, $url, $data){

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_ENCODING, "gzip");
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt_array($curl, array(
						  CURLOPT_HTTPHEADER => array(
							"Content-Type: application/json",
							"Postman-Token: 6ffcef3b-8e7f-423b-b064-95511d9bb93f",
							"cache-control: no-cache"
						  ),
						));


			   $result = curl_exec($curl);
			   if(!$result){die("Connection Failure");}
			   curl_close($curl);
			   return $result;
	}

	function GetProfileViaEmail($method, $url, $data){

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_ENCODING, "gzip");
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				//curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				curl_setopt_array($curl, array(
				  CURLOPT_HTTPHEADER => array(
					"Accept: */*",
					"Accept-Encoding: gzip, deflate",
					"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
					"Cache-Control: no-cache",
					"Connection: keep-alive",
					"Host: api-berjaya.stg-sessionm.com",
					"Postman-Token: 8006e459-fc0a-4d0a-86dc-fb670093081a,614638b5-0976-4061-bb34-aad0654c0df5",
					"User-Agent: PostmanRuntime/7.18.0",
					"cache-control: no-cache"
				  ),
				));

					$response = curl_exec($curl);
					$err = curl_error($curl);

					curl_close($curl);

					if ($err) {
					  echo "cURL Error #:" . $err;
					} else {
					  return $response;
					}
	}

	function CreateAccount($method, $url, $data){

				$curl = curl_init();
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_ENCODING, "gzip");
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				curl_setopt_array($curl, array(
						  CURLOPT_HTTPHEADER => array(
											"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
											"Content-Type: application/json",
											"Postman-Token: cc64d6bf-6c75-4aa3-afa2-afddf3559175",
											"cache-control: no-cache"
						  ),
						));

						$response = curl_exec($curl);
						$err = curl_error($curl);

						curl_close($curl);

						if ($err) {
						  echo "cURL Error #:" . $err;
						} else {
						  return $response;
						}
	}
	function UpdatePassword($method, $url, $data){

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_ENCODING, "gzip");
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt_array($curl, array(
						  CURLOPT_HTTPHEADER => array(
							"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
							"Content-Type: application/json",
							"Postman-Token: e55dc02f-a039-4d28-a600-c2b2f58cd686",
							"cache-control: no-cache"
						  ),
						));


			   $result = curl_exec($curl);
			   if(!$result){die("Connection Failure");}
			   curl_close($curl);
			   return $result;

	}

	function UpdateProfile($method, $url, $data){

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_ENCODING, "gzip");
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt_array($curl, array(
						  CURLOPT_HTTPHEADER => array(
							"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
							"Content-Type: application/json",
							"Postman-Token: e55dc02f-a039-4d28-a600-c2b2f58cd686",
							"cache-control: no-cache"
						  ),
						));


			   $result = curl_exec($curl);
			   if(!$result){die("Connection Failure");}
			   curl_close($curl);
			   return $result;

	}

	function SetDefaultCard($method, $url, $data){

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_ENCODING, "gzip");
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt_array($curl, array(
						  CURLOPT_HTTPHEADER => array(
							"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
							"Content-Type: application/json",
							"Postman-Token: e55dc02f-a039-4d28-a600-c2b2f58cd686",
							"cache-control: no-cache"
						  ),
						));


			   $result = curl_exec($curl);
			   if(!$result){die("Connection Failure");}
			   curl_close($curl);
			   return $result;

	}

	function PasswordReset($method, $url, $data){

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_ENCODING, "gzip");
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt_array($curl, array(
						  CURLOPT_HTTPHEADER => array(
							"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
							"Content-Type: application/json",
							"Postman-Token: e55dc02f-a039-4d28-a600-c2b2f58cd686",
							"cache-control: no-cache"
						  ),
						));


			   $result = curl_exec($curl);
			   if(!$result){die("Connection Failure");}
			   curl_close($curl);
			   return $result;


	}

	function GetAllCards($method, $url, $data){

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_ENCODING, "gzip");
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt_array($curl, array(
						  CURLOPT_HTTPHEADER => array(
							"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
							"Content-Type: application/json",
							"Postman-Token: e55dc02f-a039-4d28-a600-c2b2f58cd686",
							"cache-control: no-cache"
						  ),
						));


			   $result = curl_exec($curl);
			   if(!$result){die("Connection Failure");}
			   curl_close($curl);
			   return $result;


	}

	function AddCard($method, $url, $data){

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_ENCODING, "gzip");
				curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);
				curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
				curl_setopt_array($curl, array(
						  CURLOPT_HTTPHEADER => array(
							"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
							"Content-Type: application/json",
							"Postman-Token: e55dc02f-a039-4d28-a600-c2b2f58cd686",
							"cache-control: no-cache"
						  ),
						));


			   $result = curl_exec($curl);
			   if(!$result){die("Connection Failure");}
			   curl_close($curl);
			   return $result;


	}

	function TransferBalance($method, $url, $data){

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt_array($curl, array(
				  CURLOPT_URL => $url,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => $method,
				  CURLOPT_POSTFIELDS => "{\n\t\"transfer_balance_request\" : {\n\t\t\"local_transaction_time\" : \"".$data['transfer_balance_request']['local_transaction_time']."\",\n\t\t\"user_id\" : \"".$data['transfer_balance_request']['user_id']."\",\n\t\t\"source_card_number\": \"".$data['transfer_balance_request']['source_card_number']."\",\n\t\t\"dest_card_number\": \"".$data['transfer_balance_request']['dest_card_number']."\",\n\t\t\"transaction_amount\": ".$data['transfer_balance_request']['transaction_amount'].",\n\t\t\"terminal_id\": \"".$data['transfer_balance_request']['terminal_id']."\",\n\t\t\"store_id\":\"".$data['transfer_balance_request']['store_id']."\"\n\t}\n}\n",
				  CURLOPT_HTTPHEADER => array(
					"Accept: */*",
					"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
					"Cache-Control: no-cache",
					"Connection: keep-alive",
					"Content-Type: application/json",
					"Host: wa8fj4i6.stg-sessionm.com",
					"Postman-Token: e97da049-3e9d-4a5f-b786-75c05a74b5ba,74c88e91-0fb7-4775-a0d4-162fb36c6c21",
					"User-Agent: PostmanRuntime/7.11.0",
					"accept-encoding: gzip, deflate",
					"cache-control: no-cache",
					"content-length: 327"
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  echo "cURL Error #:" . $err;
				} else {
				  return $response;
				}
	}

	function ReloadCard($method, $url, $data){

				$curl = curl_init();

				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt_array($curl, array(
				  CURLOPT_URL => $url,
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => $method,
				  CURLOPT_POSTFIELDS => "{\n\t\"reload_card_request\" : {\n\t\t\"local_transaction_time\" : \"".$data['reload_card_request']['local_transaction_time']."\",\n\t\t\"transaction_amount\": ".$data['reload_card_request']['transaction_amount'].",\n\t\t\"terminal_id\": \"".$data['reload_card_request']['terminal_id']."\",\n        \"card_number\": \"".$data['reload_card_request']['card_number']."\",\n\t\t\"store_id\":\"".$data['reload_card_request']['store_id']."\"\n\t}\n}\n\n",
				  CURLOPT_HTTPHEADER => array(
					"Accept: */*",
					"Authorization: Basic YTA4NTc0MjlkY2M0Zjk5MTg0NTc4YjZmNzY0MmVlNTc0YjQzMjIxYTo3OTNiNWEwZGJhODc4NWViYTU4MTE1YzVkYWU1YzljNDQ5OWEzYmUw",
					"Cache-Control: no-cache",
					"Connection: keep-alive",
					"Content-Type: application/json",
					"Host: wa8fj4i6.stg-sessionm.com",
					"Postman-Token: 23e4245e-d686-492b-9a9a-56386c67d59a,93ed33fb-0713-4004-aa5f-3c1496ee9536",
					"User-Agent: PostmanRuntime/7.11.0",
					"accept-encoding: gzip, deflate",
					"cache-control: no-cache",
					"content-length: 231"
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  echo "cURL Error #:" . $err;
				} else {
				  return $response;
				}
	}

	function encrypt($string, $key) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char    = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1	, 1);
			$char    = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return bin2hex(base64_encode(base64_encode(base64_encode($result))));
	}

	function decrypt($string, $key) {
		$result = '';
		$string = base64_decode(base64_decode(base64_decode(hex2bin($string))));
		for($i=0; $i<strlen($string); $i++) {
			$char    = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char    = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}

	function old_encrypt($string, $key) {
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1	, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}
		return base64_encode($result);
	}

	function old_decrypt($string, $key) {
		$result = '';
		$string = base64_decode($string);
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		return $result;
	}

	function getreverseCalcDate($dateValue)
{
	//$dateValue = "28-01-2015";
	$str = $dateValue;
	$arr = explode("-",$str);

	$year= $arr[2];
	$splitYear = str_split($year);
	$convertedYear = '1'.$splitYear[2].$splitYear[3];

	$numberofDays = date("z", mktime(0,0,0,$arr[1],$arr[0],$year)) + 1;

	$convertedDate = $convertedYear.sprintf('%03d',$numberofDays);
	return $convertedDate;
}


function getCalcDate($dateValue)
{
	$str = $dateValue;
	$arr = str_split($str);

	$year= "20".$arr[1].$arr[2];
	$dayoftheYear = (int)($arr[3].$arr[4].$arr[5]);


	//The total number of days for the variable Year
	$numberofDays = date("z", mktime(0,0,0,12,31,$year)) + 1;

	if($numberofDays==366)
	{
		//Leap Year Calculations
		if($dayoftheYear<=31)
		{
			$month=1;
			$date=$dayoftheYear;

		}
		else if($dayoftheYear>31 && $dayoftheYear<=60)
		{
			$month=2;
			$date=$dayoftheYear-31;

		}
		else if($dayoftheYear>60 && $dayoftheYear<=91)
		{
			$month=3;
			$date=$dayoftheYear-60;

		}
		else if($dayoftheYear>91 && $dayoftheYear<=121)
		{
			$month=4;
			$date=$dayoftheYear-91;

		}
		else if($dayoftheYear>121 && $dayoftheYear<=152)
		{
			$month=5;
			$date=$dayoftheYear-121;

		}
		else if($dayoftheYear>152 && $dayoftheYear<=182)
		{
			$month=6;
			$date=$dayoftheYear-152;

		}
		else if($dayoftheYear>182 && $dayoftheYear<=213)
		{
			$month=7;
			$date=$dayoftheYear-182;

		}
		else if($dayoftheYear>213 && $dayoftheYear<=244)
		{
			$month=8;
			$date=$dayoftheYear-213;

		}
		else if($dayoftheYear>244 && $dayoftheYear<=274)
		{
			$month=9;
			$date=$dayoftheYear-244;

		}
		else if($dayoftheYear>274 && $dayoftheYear<=305)
		{
			$month=10;
			$date=$dayoftheYear-274;

		}
		else if($dayoftheYear>305 && $dayoftheYear<=335)
		{
			$month=11;
			$date=$dayoftheYear-305;

		}
		else
		{
			$month=12;
			$date=$dayoftheYear-335;

		}
	} else if($numberofDays==365){
		//Non-Leap Year Calculations
		if($dayoftheYear<=31)
		{
			$month=1;
			$date=$dayoftheYear;

		}
		else if($dayoftheYear>31 && $dayoftheYear<=59)
		{
			$month=2;
			$date=$dayoftheYear-31;

		}
		else if($dayoftheYear>59 && $dayoftheYear<=90)
		{
			$month=3;
			$date=$dayoftheYear-59;

		}
		else if($dayoftheYear>90 && $dayoftheYear<=120)
		{
			$month=4;
			$date=$dayoftheYear-90;

		}
		else if($dayoftheYear>120 && $dayoftheYear<=151)
		{
			$month=5;
			$date=$dayoftheYear-120;

		}
		else if($dayoftheYear>151 && $dayoftheYear<=181)
		{
			$month=6;
			$date=$dayoftheYear-151;

		}
		else if($dayoftheYear>181 && $dayoftheYear<=212)
		{
			$month=7;
			$date=$dayoftheYear-181;

		}
		else if($dayoftheYear>212 && $dayoftheYear<=243)
		{
			$month=8;
			$date=$dayoftheYear-212;

		}
		else if($dayoftheYear>243 && $dayoftheYear<=273)
		{
			$month=9;
			$date=$dayoftheYear-243;

		}
		else if($dayoftheYear>273 && $dayoftheYear<=304)
		{
			$month=10;
			$date=$dayoftheYear-273;

		}
		else if($dayoftheYear>304 && $dayoftheYear<=334)
		{
			$month=11;
			$date=$dayoftheYear-304;

		}
		else
		{
			$month=12;
			$date=$dayoftheYear-334;

		}
	}

	$calculatedDate= date("d-m-Y",mktime(0,0,0,$month,$date,$year));
	return $calculatedDate;

}
?>
