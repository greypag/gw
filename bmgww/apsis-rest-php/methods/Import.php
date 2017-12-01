<?php

class Import extends ApsisConnector {

	public static function CreateImportByXML($ApiUrl, $ApiKey, $MailinglistId, $Subscribers)
	{
		$responses = Array();
		$sizeOfBatch = 5; // Should be max 2000 in live environment
		$apiServiceUri = sprintf('%s/v1/import/mailinglist/%s/demographicmapping/%s', $ApiUrl, $MailinglistId, 'true');
		$currentSubOfBatch = 1;
		$subString = '<Subscribers>';
		foreach ($Subscribers->xpath('//Subscribers/Subscriber') as $subscriber)
		{
			$subString .= $subscriber->asXML();
			if ($currentSubOfBatch++ == $sizeOfBatch)
			{
				$subString .= '</Subscribers>';
				$createImport = new SimpleXMLElement('<CreateImport/>');
				$createImport->addChild('XmlData', htmlentities($subString)); // Server want them formated, don't know why
				$response = ApsisConnector::CallRestApiMethod($apiServiceUri, "POST", $ApiKey, $createImport->asXML())->Result;
				array_push($responses, $response[0]);
				$currentSubOfBatch = 1;
				$subString = '<Subscribers>';
			}
		}
		return $responses;
	}

	public static function CreateImportByCSV($ApiUrl, $ApiKey, $MailinglistId, $CsvUrl)
	{
		$createImport = new SimpleXMLElement('<CreateQueuedImportCsv/>');
		$createImport->addChild('Url', $CsvUrl);
		$createImport->addChild('HasHeader','false');
		$createImport->addChild('Delimiter',';');
		$createImport->addChild('Quote','"');
		$createImport->addChild('Escape','\\');
		$createImport->addChild('Comment', '#');
		$apiServiceUri = sprintf('%s/v1/import/csv/mailinglist/%s/isZipped/%s', $ApiUrl, $MailinglistId, 'false');
		$response = ApsisConnector::CallRestApiMethod($apiServiceUri, "POST", $ApiKey, $createImport->asXML());
		$checkUrl = $response->Result->PollURL[0];
		$timesChecked = 0;
		set_time_limit(300); // Fix for taking longer than server allowed.
		while ($timesChecked < 10)
		{
			sleep($timesChecked++);
			$pollResponse = simplexml_load_file($checkUrl);
			if($pollResponse->State == 2)
				return simplexml_load_file($pollResponse->DataUrl);
		}
		send500('Did not return result in time frame.');
	}

	public static function GetImportStatus($ApiUrl, $ApiKey, $ImportIds)
	{
		$result = array();
		foreach($ImportIds as $ImportId)
		{
			$apiServiceUri = sprintf('%s/v1/import/%s', $ApiUrl, $ImportId);
			$response = ApsisConnector::CallRestApiMethod($apiServiceUri, "GET", $ApiKey);
			array_push($result, $ImportId.' - '.$response->Message);
		}
		return $result;
	}
}