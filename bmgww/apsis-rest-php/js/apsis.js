$('#myTab a').click(function(e){
	e.preventDefault();
	$(this).tab('show');
});
var temp = null;
$(function(){
	var apiUrl = $('#apiUrl');
	var apiKey = $('#apiKey');
	var errorList = $('#error-list');

	$('#sMMSend').click(function(){
		var projectId = $('#sMMProjectId');
		var subscriberEmail = $('#sMMSubscriberEmail');
		var error = false;
		if (projectId.val() == '') {
			error = true;
			projectId.parents('.control-group').addClass('error');
		}
		else
			projectId.parents('.control-group').removeClass('error');
		if (subscriberEmail.val() == '') {
			error = true;
			subscriberEmail.parents('.control-group').addClass('error');
		}
		else
			subscriberEmail.parents('.control-group').removeClass('error');

		if (error) return;

		var url = '/api.php?method=sendTransactionalEmail';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			Format: $('#sMMFormat').val(),
			ProjectId: projectId.val(),
			SubscriberEmail: subscriberEmail.val()
		}
		$.post(url, JSON.stringify(data)).done(function( data ) {
			$('#sMMTransactionId').text(data.TransactionId);
		}).fail(function( data ) {
			errorList.prepend($('<p>').text(data.responseText));
		});
	});

	$('#mLMGet').click(function(){
		var resultTable = $('#mLMResult').empty();
		var url = '/api.php?method=getMailinglistPaginated';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			PageNumber: $('#mLMPagenumber').val(),
			PageSize: $('#mLMPagesize').val()
		}
		$.post(url, JSON.stringify(data)).done(function( data ) {
			$.each(data.Items.MailingListSummaryItem, function(i, item){
				$('<p>').text(item.Name).appendTo(resultTable);
			});
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
		});
	});

	$('#mLMCreate').click(function(){
		var name = $('#mLMName');
		var fromName = $('#mLMFromName');
		var fromEmail = $('#mLMFromEmail');
		var description = $('#mLMDescription');
		var characterSet = $('#mLMCharacterSet');
		var folderId = $('#mLMFolderId');
		var error = false;
		if (name.val() == '') {
			error = true;
			name.parents('.control-group').addClass('error');
		}
		else
			name.parents('.control-group').removeClass('error');
		if (fromName.val() == '') {
			error = true;
			fromName.parents('.control-group').addClass('error');
		}
		else
			fromName.parents('.control-group').removeClass('error');
		if (fromEmail.val() == '') {
			error = true;
			fromEmail.parents('.control-group').addClass('error');
		}
		else
			fromEmail.parents('.control-group').removeClass('error');

		if (error) return;

		var url = '/api.php?method=createMailingList';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			CharacterSet: characterSet.val() == '' ? null : characterSet.val(),
			Description: description.val() == '' ? null : description.val(),
			FolderID: folderId.val() == '' ? null : folderId.val(),
			FromEmail: fromEmail.val(),
			FromName: fromName.val(),
			Name: name.val()
		}

		$.post(url, JSON.stringify(data)).done(function( data ) {
			$('#mLMMalinglistId').text(data[0]);
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
		});
	});

	$('#iMMISend').click(function(){
		var mailingListId = $('#iMMI');
		if (mailingListId.val() == '') {
			mailingListId.parents('.control-group').addClass('error');
			return;
		}
		else
			mailingListId.parents('.control-group').removeClass('error');


		var url = '/api.php?method=createImportByXML';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			MailinglistId: mailingListId.val()
		}
		$.post(url, JSON.stringify(data)).done(function( data ) {
			$('#iMImportId').val(data);
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
			});
	});

	$('#iMMISend2').click(function(){
		var mailingListId = $('#iMMI2');
		var csvUrl = $('#iMCSVUrl');
		var error = false;
		if (mailingListId.val() == '') {
			mailingListId.parents('.control-group').addClass('error');
			error = true;
		}
		else
			mailingListId.parents('.control-group').removeClass('error');
		if (csvUrl.val() == '') {
			csvUrl.parents('.control-group').addClass('error');
			error = true;
		}
		else
			csvUrl.parents('.control-group').removeClass('error');
		if (error) return;

		var url = '/api.php?method=createImportByCSV';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			MailinglistId: mailingListId.val(),
			CsvUrl: csvUrl.val()
		}
		$.post(url, JSON.stringify(data)).done(function( data ) {
			$('#iMImportId').val(data[0]);
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
			});

	});

	$('#iMGetStatus').click(function(){
		var importIds = $('#iMImportId');
		if (importIds.val() == '') {
			importIds.parents('.control-group').addClass('error');
			return;
		}
		else
			importIds.parents('.control-group').removeClass('error');

		var url = '/api.php?method=getImportIDStatus';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			ImportIds: importIds.val().split(',')
		};
		$.post(url, JSON.stringify(data)).done(function( data ) {
			var text = '';
			$.each(data, function(i, item){
				text += item + '<br>';
			});

			$('#iMResult').html(text);
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
			});
	});

	$('#smDelete').click(function(){
		var mailinglistId = $('#sMMailinglistId');
		if (mailinglistId.val() == '') {
			mailinglistId.parents('.control-group').addClass('error');
			return;
		}
		else
			mailinglistId.parents('.control-group').removeClass('error');

		var url = '/api.php?method=deleteMailinglistSubscriptions';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			MailinglistId: mailinglistId.val()
		};
		$.post(url, JSON.stringify(data)).done(function( data ) {
			$('#smAffectedRows').text(JSON.parse(data).AffectedRows);
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
			});
	});

	$('#smDelete2').click(function(){
		var mailingListId = $('#sMMailinglistId2');
		var subscriberId = $('#sMSubscriberId');
		var error = false;
		if (mailingListId.val() == '') {
			mailingListId.parents('.control-group').addClass('error');
			error = true;
		}
		else
			mailingListId.parents('.control-group').removeClass('error');
		if (subscriberId.val() == '') {
			subscriberId.parents('.control-group').addClass('error');
			error = true;
		}
		else
			subscriberId.parents('.control-group').removeClass('error');
		if (error) return;

		var url = '/api.php?method=deleteSingleSubscription';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			MailinglistId: mailingListId.val(),
			SubscriberId: subscriberId.val()
		}
		$.post(url, JSON.stringify(data)).done(function( data ) {
			$('#sMResult').text(data[0]);
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
			});

	});

	$('#sM2Delete').click(function(){
		var subsriberIds = [];
		if ($('#sM2SubscriberId1').val() != '')
			subsriberIds.push($('#sM2SubscriberId1').val());
		if ($('#sM2SubscriberId2').val() != '')
			subsriberIds.push($('#sM2SubscriberId2').val());
		if ($('#sM2SubscriberId3').val() != '')
			subsriberIds.push($('#sM2SubscriberId3').val());

		if (subsriberIds.length == 0) return;

		var url = '/api.php?method=deleteSubscribersFromOptOutAll';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			SubscriberIds: subsriberIds
		}
		$.post(url, JSON.stringify(data)).done(function( data ) {
			$('#sM2DeletedIds, #sM2FailedIds').empty();
			$.each(JSON.parse(data).deleted, function(){
				$('<p>').text(this).appendTo($('#sM2DeletedIds'));
			});
			$.each(JSON.parse(data).failed, function(){
				$('<p>').text(this).appendTo($('#sM2FailedIds'));
			});
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
			});
	});

	$('#sM2Update').click(function(){
		var subsriberIds = [];
		if ($('#sM2SubscriberId12').val() != '')
			subsriberIds.push($('#sM2SubscriberId12').val());
		if ($('#sM2SubscriberId22').val() != '')
			subsriberIds.push($('#sM2SubscriberId22').val());
		if ($('#sM2SubscriberId32').val() != '')
			subsriberIds.push($('#sM2SubscriberId32').val());

		if (subsriberIds.length == 0) return;

		var url = '/api.php?method=updateSubscribers';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			SubscriberIds: subsriberIds
		}
		$.post(url, JSON.stringify(data)).done(function( data ) {
			$('#sM2UpdatedIds, #sM2FailedIds2').empty();
			$.each(JSON.parse(data).updated, function(){
				$('<p>').text(this).appendTo($('#sM2UpdatedIds'));
			});
			$.each(JSON.parse(data).failed, function(){
				$('<p>').text(this).appendTo($('#sM2FailedIds2'));
			});
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
			});
	});

	$('#gCGet').click(function(){
		var sendQueueId = $('#gCSendQueueId').val() == '' ? 0 : $('#gCSendQueueId').val();
		var url = '/api.php?method=getClicksBySendQueueIdPaginated';
		var data = {
			ApiUrl: apiUrl.val(),
			ApiKey: apiKey.val(),
			SendQueueId: sendQueueId,
			PageNumber: $('#gCPagenumber').val(),
			PageSize: $('#gCMPagesize').val()
		}
		$.post(url, JSON.stringify(data)).done(function( data ) {
			$.each(data, function(){
				$('<a>').text(this[0]).attr('href', this[0]).appendTo($('#gCResult'));
			});
		}).fail(function( data ) {
				errorList.prepend($('<p>').text(data.responseText));
			});

	});



	$('#btn-clear-errors').click(function(){
		errorList.empty();
	});
});

