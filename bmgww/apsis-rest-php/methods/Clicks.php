<?php

class Clicks extends ApsisConnector {

	public static function GetClicksBySendQueueIdPaginated($ApiUrl, $ApiKey, $PageSize, $PageNumber, $SendQueueId)
	{
		$apiServiceUri = sprintf('%s/v1/clicks/sendqueues/%s/page/%s/size/%s', $ApiUrl, $SendQueueId, $PageNumber, $PageSize);
		$response = ApsisConnector::CallRestApiMethod($apiServiceUri, "GET", $ApiKey);
		set_time_limit(100);
		$links = array();
		foreach($response->xpath('//Response/Result/Items/ClickItem') as $item)
			array_push($links, $item->Link);
		return $links;
	}
}