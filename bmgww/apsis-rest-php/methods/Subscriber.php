<?php

class Subscriber extends ApsisConnector {

	public static function DeleteSubscribersFromOptOutAll($ApiUrl, $ApiKey, $SubscriberIds)
	{
		$root = new SimpleXMLElement('<q1:ArrayOflong xmlns:q1="http://schemas.microsoft.com/2003/10/Serialization/Arrays"/>');
		foreach ($SubscriberIds as $subscriberId)
			$root->addChild('q1:long', $subscriberId);
		$apiServiceUri = sprintf('%s/v1/optouts/global/subscribers/queued', $ApiUrl);
		$response = ApsisConnector::CallRestApiMethod($apiServiceUri, "DELETE", $ApiKey, $root->asXML());
		$checkUrl = $response->Result->PollURL[0];
		$timesChecked = 0;
		set_time_limit(300); // Fix for taking longer than server allowed.
		while ($timesChecked < 10)
		{
			sleep($timesChecked++);
			$pollResponse = simplexml_load_file($checkUrl);
			if($pollResponse->State == 2)
			{
				$response = simplexml_load_file($pollResponse->DataUrl);
				$result = array('deleted' => array(), 'failed' => array());
				$response->registerXPathNamespace('a', 'http://schemas.microsoft.com/2003/10/Serialization/Arrays');
				foreach($response->xpath('//DeleteOptOutAllResultCollection/FailedSubscriberIds/a:KeyValueOflongstring/a:Key') as $value)
					array_push($result['failed'], json_decode($value));
				foreach($response->xpath('//DeleteOptOutAllResultCollection/DeletedSubscriberIds/a:KeyValueOflongstring/a:Key') as $value)
					array_push($result['deleted'], json_decode($value));
				return $result;
			}
		}
		send500('Did not return result in time frame.');
	}

	public static function UpdateSubscribers($ApiUrl, $ApiKey, $SubscriberIds)
	{
		$root = new SimpleXMLElement('<ArrayOfUpdateQueuedSubscriber/>');
		foreach ($SubscriberIds as $subscriberId)
		{
			$subScriberRoot = $root->addChild('UpdateQueuedSubscriber');
			$subScriberRoot->addChild('Id', $subscriberId);
			$subScriberRoot->addChild('Format', 'text');
			$subScriberRoot->addChild('PhoneNumber', '0123465789');
			$dem1 = $subScriberRoot->addChild('DemDataFields');
			$dem2 = $dem1->addChild('DemDataField');
			$dem2->addChild('Key', 'Country');
			$dem2->addChild('Value', 'Sweden');
		}

		$apiServiceUri = sprintf('%s/v1/subscribers/queue', $ApiUrl);
		$response = ApsisConnector::CallRestApiMethod($apiServiceUri, "POST", $ApiKey, $root->asXML());
		$checkUrl = $response->Result->PollURL[0];
		$timesChecked = 0;
		set_time_limit(300); // Fix for taking longer than server allowed.
		while ($timesChecked < 10)
		{
			sleep($timesChecked++);
			$pollResponse = simplexml_load_file($checkUrl);
			if($pollResponse->State == 2)
			{
				$response = simplexml_load_file($pollResponse->DataUrl);
				$result = array('updated' => array(), 'failed' => array());
				$response->registerXPathNamespace('a', 'http://schemas.microsoft.com/2003/10/Serialization/Arrays');
				foreach($response->xpath('//UpdateSubscribersResultCollection/FailedUpdatedSubscribers/a:KeyValueOflongstring/a:Key') as $value)
					array_push($result['failed'], json_decode($value));
				foreach($response->xpath('//UpdateSubscribersResultCollection/UpdatedSubscriberIds/a:KeyValueOflongstring/a:Key') as $value)
					array_push($result['updated'], json_decode($value));
				return $result;
			}
		}
		send500('Did not return result in time frame.');
	}
}