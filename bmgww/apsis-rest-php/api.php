<?php
require 'methods/Connector.php';
require 'methods/Clicks.php';
require 'methods/Mailinglist.php';
require 'methods/Import.php';
require 'methods/Transactional.php';
require 'methods/Subscriber.php';
header('Content-type: application/json');
switch ($_GET['method'])
{
	case 'sendTransactionalEmail':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];
		$missingParams =  Array();
		if (!isset($requestData['ProjectId']))
			array_push($missingParams, 'Project Id');
		if (!isset($requestData['SubscriberEmail']))
			array_push($missingParams, 'Subscriber Email');
		if(sizeof($missingParams) > 0)
			send406(implode(', ', $missingParams));

		$response = Transactional::SendTransactionalEmail($ApiUrl, $ApiKey, $requestData['ProjectId'], '', $requestData['SubscriberEmail'], $requestData['Format'], "12345");
		exit(json_encode($response->Result));
		break;

	case 'getMailinglistPaginated':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];
		$missingParams =  Array();
		if (!isset($requestData['PageNumber']))
			array_push($missingParams, 'Page Number');
		if (!isset($requestData['PageSize']))
			array_push($missingParams, 'Page Size');
		if(sizeof($missingParams) > 0)
			send406(implode(', ', $missingParams));

		$response = Mailinglist::GetMailingListPaginated($ApiUrl, $ApiKey, $requestData['PageSize'], $requestData['PageNumber']);

		if(!empty($response->Result))
			exit(json_encode($response->Result));
		else exit();
		break;

	case 'createMailingList':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];

		$missingParams =  Array();
		if (!isset($requestData['Name']))
			array_push($missingParams, 'Name');
		if (!isset($requestData['FromEmail']))
			array_push($missingParams, 'From Email');
		if (!isset($requestData['FromName']))
			array_push($missingParams, 'From Name');
		if(sizeof($missingParams) > 0)
			send406(implode(', ', $missingParams));

		$response = Mailinglist::CreateMailingList($ApiUrl, $ApiKey, $requestData['Name'], $requestData['FromName'], $requestData['FromEmail'], isset($requestData['FolderID']) ? $requestData['FolderID'] : 0, $requestData['Description'], $requestData['CharacterSet']);
		exit(json_encode($response->Result));
		break;

	case 'createImportByXML':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];
		if (!isset($requestData['MailinglistId']))
			send406('Mailinglist Id');

		// Create fake subscribers
		$createImport = new SimpleXMLElement('<CreateImport/>');
		$subs = new SimpleXMLElement('<Subscribers/>');
		for($i = 0; $i < 25; $i++)
		{
			$sub = $subs->addChild('Subscriber');
			$sub->addChild('Email', 'test'.$i.'@test.com');
			$sub->addChild('Format', 'HTML');
			$sub->addChild('Name', 'Test Testsson '.$i);
			$demo = $sub->addChild('DemographicData');
			$demo2 = $demo->addChild('Demograpic', $i+1);
			$demo2->addAttribute("mapTo", "Age");
		}
		$createImport->addChild('XmlData', htmlentities($subs->asXML()));

		//Get subscribers, may need to be edited according to format on XML.
		$allSubs = new SimpleXMLElement(array_shift(array_values($createImport->xpath('//CreateImport/XmlData'))));

		exit(json_encode(implode(',',Import::CreateImportByXML($ApiUrl, $ApiKey, $requestData['MailinglistId'], $allSubs))));
		break;

	case 'createImportByCSV':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];
		$missingParams =  Array();
		if (!isset($requestData['MailinglistId']))
			array_push($missingParams, 'Mailinglist Id');
		if (!isset($requestData['CsvUrl']))
			array_push($missingParams, 'CSV Url');
		if(sizeof($missingParams) > 0)
			send406(implode(', ', $missingParams));

		exit(json_encode(Import::CreateImportByCSV($ApiUrl, $ApiKey, $requestData['MailinglistId'], $requestData['CsvUrl'])));

		break;

	case 'getImportIDStatus':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];
		if (!isset($requestData['ImportIds']))
			send406('Import Ids');

		exit(json_encode(Import::GetImportStatus($ApiUrl, $ApiKey, $requestData['ImportIds'])));
		break;

	case 'deleteMailinglistSubscriptions':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];
		if (!isset($requestData['MailinglistId']))
			send406('Mailinglist Id');

		exit(json_encode(Mailinglist::DeleteMailinglistSubscriptions($ApiUrl, $ApiKey, $requestData['MailinglistId'])));
		break;

	case 'deleteSingleSubscription':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];
		$missingParams =  Array();
		if (!isset($requestData['MailinglistId']))
			array_push($missingParams, 'Mailinglist Id');
		if (!isset($requestData['SubscriberId']))
			array_push($missingParams, 'Subscriber Id');
		if(sizeof($missingParams) > 0)
			send406(implode(', ', $missingParams));

		$response = Mailinglist::DeleteSingleSubscription($ApiUrl, $ApiKey, $requestData['MailinglistId'], $requestData['SubscriberId']);
		exit(json_encode($response->Result));
		break;

	case 'deleteSubscribersFromOptOutAll':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];
		if (sizeof($requestData['SubscriberIds']) == 0)
			send406('Subscriber Ids');

		exit(json_encode(Subscriber::DeleteSubscribersFromOptOutAll($ApiUrl, $ApiKey, $requestData['SubscriberIds'] )));
		break;

	case 'updateSubscribers':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];
		if (sizeof($requestData['SubscriberIds']) == 0)
			send406('Subscriber Ids');

		exit(json_encode(Subscriber::UpdateSubscribers($ApiUrl, $ApiKey, $requestData['SubscriberIds'] )));
		break;

	case 'getClicksBySendQueueIdPaginated':
		$requestData = json_decode(file_get_contents("php://input"), true);
		$ApiUrl = $requestData['ApiUrl'];
		$ApiKey = $requestData['ApiKey'];

		$missingParams =  Array();
		if (!isset($requestData['SendQueueId']))
			array_push($missingParams, 'Send Queue Id');
		if (!isset($requestData['PageNumber']))
			array_push($missingParams, 'Page Number');
		if (!isset($requestData['PageSize']))
			array_push($missingParams, 'Page Size');
		if(sizeof($missingParams) > 0)
			send406(implode(', ', $missingParams));

		exit(json_encode(Clicks::GetClicksBySendQueueIdPaginated($ApiUrl, $ApiKey, $requestData['PageSize'], $requestData['PageNumber'], $requestData['SendQueueId'])));
		break;

	default:
		send404();
}

function send400($reason)
{
	header('HTTP/1.0 400 Bad Request');
	exit('Error 400 - Reason: '.$reason);
}

function send403($reason)
{
	header('HTTP/1.0 403 Forbidden');
	exit('Error 403 - Reason: '.$reason);
}

function send404()
{
	header('HTTP/1.0 404 Not Found');
	exit('404');
}

function send406($missingParameters)
{
	header('HTTP/1.0 406 Not Acceptable');
	exit('Error 406 - Method expects parameters: '.$missingParameters);
}

function send500($reason)
{
	header('HTTP/1.0 500 Internal Server Error');
	exit('Error 500 - Reason: '.$reason);
}