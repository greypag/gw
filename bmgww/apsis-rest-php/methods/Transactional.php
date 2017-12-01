<?php

class Transactional extends ApsisConnector {
	public static function SendTransactionalEmail($ApiUrl, $ApiKey, $ProjectId, $SendDate, $SubscriberEmail, $Format, $PhoneNbr)
	{
		$apiServiceUri = sprintf('%s/v1/transactional/projects/%s/sendEmail?sendDate=%s', $ApiUrl, $ProjectId, $SendDate);
		$dataBody = new SimpleXMLElement('<TransactionalEmailSending/>');
		$dataBody->addChild('Email', $SubscriberEmail);
		$dataBody->addChild('Format', $Format);
		$dataBody->addChild('PhoneNumber', $PhoneNbr);
		return ApsisConnector::CallRestApiMethod($apiServiceUri, "POST", $ApiKey, $dataBody->asXML());
	}
}