<?php

class ApsisConnector {
	public function CallRestApiMethod($url, $method, $apiKey, $body = null)
	{
		$curl = curl_init();
		switch ($method)
		{
			case "POST":
				curl_setopt($curl, CURLOPT_POST, 1);
				if ($body) curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
				break;

			case "PUT":
				curl_setopt($curl, CURLOPT_PUT, 1);
				break;

			case 'DELETE':
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if ($body) curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
				break;

			default:
				if ($body) $url = sprintf("%s?%s", $url, http_build_query($body));
		}

		curl_setopt($curl,CURLOPT_HTTPHEADER,array('Authorization: Basic '.base64_encode($apiKey),
			'Content-Type: text/xml; charset=UTF-8',
			'Accept: text/xml; charset=UTF-8'));

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		if( !$response = curl_exec($curl))
			trigger_error(curl_error($curl));
		curl_close($curl);
		$xml = new SimpleXMLElement($response);
		switch($xml->Code)
		{
			case 1:
				return $xml;
			case -4:
				send403($xml->Message);
			case -2:
				send400($xml->Message);
			case -3:
				send404();
			default:
				exit($response);
		}
	}
}

