<?php
class XML {

	public function __construct() {}

	public static function out($data) {
		$xmlHeader = "<?xml version=\"1.0\"?>";
		// if(is_string($endpoint)) {
		// 	$endpoint .= str_replace(' ', '', ucwords($endpoint));
		// 	$xmlHeader .= "<$endpoint></$endpoint>";
		// }
		$xml = new SimpleXMLElement($xmlHeader);
		$node = $xml->addChild('request');
		// if data is object -- convert to array
		$data = json_decode(json_encode($data), true);
		$this->arrayToXML($data, $node);
		header('Content-Type: xml/application');
		echo $xml->asXML();
	}

	public function arrayToXML($array, &$xml) {
		foreach($array as $key => $value) {
	        if(is_array($value)) {
	            if(!is_numeric($key)){
	                $subnode = $xml->addChild("$key");
	                $this->arrayToXML($value, $subnode);
	            } else {
	                $this->arrayToXML($value, $xml);
	            }
	        } else {
	            $xml->addChild("$key","$value");
	        }
	    }
	}
}