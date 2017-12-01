<?php

class Mailinglist extends ApsisConnector {

	public static function GetMailingListPaginated($ApiUrl, $ApiKey, $PageSize, $PageNumber)
	{
		$apiServiceUri = sprintf('%s/v1/mailinglists/%s/%s', $ApiUrl, $PageNumber, $PageSize);
		return ApsisConnector::CallRestApiMethod($apiServiceUri, "GET", $ApiKey, null);
	}

	public static function CreateMailingList($ApiUrl, $ApiKey, $Name, $FromName, $FromEmail, $FolderID = 0, $Description, $CharacterSet)
	{
		$apiServiceUri = sprintf('%s/v1/mailinglists/', $ApiUrl);
		$dataBody = new SimpleXMLElement('<CreateMailinglist/>');
		$dataBody->addChild('CharacterSet', $CharacterSet);
		$dataBody->addChild('Description', $Description);
		$dataBody->addChild('FolderID', $FolderID);
		$dataBody->addChild('FromEmail', $FromEmail);
		$dataBody->addChild('FromName', $FromName);
		$dataBody->addChild('Name', $Name);
		return ApsisConnector::CallRestApiMethod($apiServiceUri, "POST", $ApiKey, $dataBody->asXML());
	}

	public static function DeleteMailinglistSubscriptions($ApiUrl, $ApiKey, $MailinglistId)
	{

		$apiServiceUri = sprintf('%s/v1/mailinglists/%s/subscriptions/all', $ApiUrl, $MailinglistId);
		$response = ApsisConnector::CallRestApiMethod($apiServiceUri, "DELETE", $ApiKey);
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

	public static function DeleteSingleSubscription($ApiUrl, $ApiKey, $MailinglistId, $SubscriberId)
	{
		$apiServiceUri = sprintf('%s/v1/mailinglists/%s/subscriptions/%s', $ApiUrl, $MailinglistId, $SubscriberId);
		return ApsisConnector::CallRestApiMethod($apiServiceUri, "DELETE", $ApiKey);
	}
}