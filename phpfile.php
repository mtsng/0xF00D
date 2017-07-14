<?php 
$res = json_decode($_REQUEST['data'], true); 
$res["php_message"] = $res["api"]; 
$curl_url = 'https://api.locu.com/v2/venue/search/';

	$stuff = stripslashes($res["array"][0]);
	$curl_data_arr3 = array($stuff);
	
	$curl_post_fields = array();
	foreach($curl_data_arr3 as $key => $value){
		$curl_post_fields[] = $key . '=' . urlencode($value);
	}
	$curl_array = array(
		CURLOPT_URL => $curl_url,
		CURLOPT_POSTFIELDS => implode('&', $curl_data_arr3),
		CURLOPT_POST => TRUE,
		CURLOPT_RETURNTRANSFER => TRUE,
	);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt_array($curl, $curl_array);
	$data = curl_exec($curl);
	$result = '';
	if($error = curl_error($curl)){
		$result = $error;
		echo "no";
	}
	curl_close($curl);
	if(empty($error)){
		$result = $data;
	}
	
	$json_a = json_decode($result, true);
	$res["success"] = $json_a["http_status"];
	if($res["menu"]){
		$returndata = "";
	
		for ($x = 0; $x < 5; $x++) {
			$contents = $json_a["venues"][0]["menus"][0]["sections"][0]["subsections"][0]["contents"][$x];
			$returndata .= implode("#", $contents) . ":";
		}
	
		$res["stuff"] = $returndata;

	}
	if($res["contact"]){
		$res["stuff"] = $json_a["venues"][0]["contact"]["phone"];
	}
	if($res["location"]){
		$res["stuff"] = $json_a["venues"][0]["name"] . ":" . $json_a["venues"][1]["name"] . ":" . $json_a["venues"][2]["name"] . ":" . $json_a["venues"][3]["name"] . ":" . $json_a["venues"][4]["name"];
	}
	echo json_encode($res);
?>