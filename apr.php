<?php
Class Apr{
	private $cookie;
	private $hidden;
	private $server;

	public function __construct($cookie = null, $hidden = null, $server = null){
		if($cookie !== null && $hidden !== null && $server !== null){
			$this->cookie = $cookie;
			$this->hidden = $hidden;
			$this->server = $server;
		}
		else
		{
			$this->authenticate();
		}
	}

	public function getCookie(){
		return $this->cookie;
	}

	public function getHidden(){
		return $this->hidden;
	}

	public function getServer(){
		return $this->server;
	}

	public function search(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://pretraga2.apr.gov.rs/ObjedinjenePretrage/Search/SearchResult");

		$data = [
			"__RequestVerificationToken" => $this->hidden,
			"rdbtnSelectInputType" => "poslovnoIme",
			"SearchByNameString" => "sbb",
			"SelectedRegisterId" => "1",
			"X-Requested-With" => "XMLHttpRequest"
		];

		$headers = [
			"Cookie: ".$this->cookie."; SERVERID=".$this->server.";"
		];

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$server_output = curl_exec ($ch);
		curl_close($ch);

		return $server_output;
	}

	public function authenticate(){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://pretraga2.apr.gov.rs/ObjedinjenePretrage/Search/Search");

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_HEADER, 1);

		$server_output = curl_exec ($ch);
		curl_close($ch);

		$server_output = str_replace("\r", "", $server_output);
		$server_output = explode("\n", $server_output);

		foreach($server_output as $key => $line){
			if(strpos($line, "__RequestVerificationToken_") != false){
				$this->cookie = explode(";", explode(": ", $line)[1])[0];
			}

			if(strpos($line, "__RequestVerificationToken\"") != false){
				$this->hidden = explode("\"", explode("value=\"", $line)[1])[0];
			}

			if(strpos($line, "SERVERID=") != false){
				$this->server = explode(";", explode("SERVERID=", $line)[1])[0];
			}
		}
	}
}

$apr = new Apr();
echo $apr->search();

