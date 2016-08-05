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

	public function search($query, $category = 1){
		$results = $this->_search($query, $category);

		if($results == "error"){
			$this->authenticate();
			$results = $this->_search($query, $category);
		}

		$dom = new DOMDocument;
		@$dom->loadHTML("<?xml encoding=\"UTF-8\">".$results);

		$tables = $dom->getElementsByTagName("table");

		$search = [];

		$results = ["type", "pib", "name", "status", "link"];

		foreach($tables as $table){
			$rows = $table->getElementsByTagName("tr");

			foreach($rows as $row){
				$columns = $row->getElementsByTagName("td");

				$result = [];

				foreach($columns as $key => $column){
					if($results[$key] == "link"){
						$links = $column->getElementsByTagName("a");

						foreach($links as $link){}

						$result[$results[$key]] = $link->getAttribute("href");
					}
					else
					{
						$result[$results[$key]] = trim($column->textContent);
					}
				}

				$search[] = $result;
			}

			return $search;
		}
	}

	public function _search($query, $category = 1){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://pretraga2.apr.gov.rs/ObjedinjenePretrage/Search/SearchResult");

		$data = [
			"__RequestVerificationToken" => $this->hidden,
			"rdbtnSelectInputType" => "poslovnoIme",
			"SearchByNameString" => $query,
			"SelectedRegisterId" => $category,
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

		if(strpos($server_output, "Error")){
			return "error";
		}
		else
		{
			return $server_output;
		}
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
			if(strpos($line, "__RequestVerificationToken_")){
				$this->cookie = explode(";", explode(": ", $line)[1])[0];
			}

			if(strpos($line, "__RequestVerificationToken\"")){
				$this->hidden = explode("\"", explode("value=\"", $line)[1])[0];
			}

			if(strpos($line, "SERVERID=")){
				$this->server = explode(";", explode("SERVERID=", $line)[1])[0];
			}
		}
	}
}

