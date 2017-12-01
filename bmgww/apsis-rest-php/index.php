<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Apsis REST API sample - PHP-XML</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/apsis.css" rel="stylesheet">
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="brand" href="#">Apsis REST API sample - PHP-XML</a>
		</div>
	</div>
</div>

<div class="container">
	<div class="navbar navbar-subnav">
		<div class="navbar-inner">
			<div class="row">
				<div class="span4">
					<div class="form-horizontal control-group">
						<label class="control-label" for="inputEmail">API Root URL:</label>
						<div class="controls">
							<input type="text" id="apiUrl" placeholder="URL" value="http://se.api.anpdm.com">
						</div>
					</div>
				</div>
				<div class="span4">
					<div class="form-horizontal control-group">
						<label class="control-label" for="inputEmail">API Key:</label>
						<div class="controls">
							<input type="text" id="apiKey" placeholder="API Key">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="spacer"></div>
	<div class="row">
		<div class="span12">
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a href="#sendMailMethod">Send Mail Method</a></li>
				<li><a href="#mailingListMethods">Mailinglist Methods</a></li>
				<li><a href="#importMethods">Import Methods</a></li>
				<li><a href="#subscriptionMethods">Subscription Methods</a></li>
				<li><a href="#subscriberMethods">Subscriber Methods</a></li>
				<li><a href="#getClicks">Get Clicks</a></li>
			</ul>

			<div class="tab-content">
				<div class="tab-pane active" id="sendMailMethod">
					<h4>Send Transactional Email</h4>
					<div class="form-horizontal">
						<div class="control-group">
							<label class="control-label" for="sMMProjectId">Project Id:</label>
							<div class="controls">
								<input type="text" id="sMMProjectId">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="sMMSubscriberEmail">Subscriber Email:</label>
							<div class="controls">
								<input type="email" id="sMMSubscriberEmail" required>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="sMMFormat">Format:</label>
							<div class="controls">
								<select id="sMMFormat">
									<option>HTML</option>
									<option>text</option>
								</select>
							</div>
						</div>
						<div class="control-group">
							<div class="controls">
								<button type="submit" class="btn" id="sMMSend">Send</button>
							</div>
						</div>
						<div><p>Transaction Id: <span id="sMMTransactionId"></span></p></div>
					</div>
				</div>
				<div class="tab-pane" id="mailingListMethods">
					<div class="row">
						<div class="span5">
							<h4>Get Mailinglist Paginated</h4>
							<div class="form-inline">
								<label class="select">
									Pagenumber:
									<select id="mLMPagenumber" class="span1">
										<option>1</option>
										<option>2</option>
										<option>3</option>
										<option>4</option>
										<option>5</option>
									</select>
								</label>
								<label class="select">
									Pagesize:
									<select id="mLMPagesize" class="span1">
										<option>5</option>
										<option>10</option>
										<option>15</option>
										<option>20</option>
									</select>
								</label>
								<button type="submit" id="mLMGet" class="btn">Get</button>
								<p>Result:</p>
								<div id="mLMResult" class="resultBox">

								</div>
							</div>
						</div>
						<div class="span5">
							<h4>Create Mailinglist</h4>
							<div class="form-horizontal">
								<div class="control-group">
									<label class="control-label" for="mLMName">Name:</label>
									<div class="controls">
										<input type="text" id="mLMName" required>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="mLMFromName">From Name:</label>
									<div class="controls">
										<input type="text" id="mLMFromName" required>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="mLMFromEmail">From Email:</label>
									<div class="controls">
										<input type="email" id="mLMFromEmail" required>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="mLMDescription">Description:</label>
									<div class="controls">
										<input type="text" id="mLMDescription">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="mLMCharacterSet">Character Set:</label>
									<div class="controls">
										<input type="text" id="mLMCharacterSet" value="utf-8">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label" for="mLMFolderId">Folder Id:</label>
									<div class="controls">
										<input type="text" id="mLMFolderId">
									</div>
								</div>
								<div class="control-group">
									<div class="controls">
										<button type="submit" class="btn" id="mLMCreate">Create</button>
									</div>
								</div>
								<div><p>Mailinglist Id: <span id="mLMMalinglistId"></span></p></div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="importMethods">
					<div class="row">
						<div class="span5">
							<h4>Create Import By XML</h4>
							<div class="control-group">
								<label class="control-label" for="iMMI">Mailinglist Id:</label>
								<div class="controls">
									<input type="text" id="iMMI">
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="submit" class="btn" id="iMMISend">Send</button>
								</div>
							</div>
						</div>
						<div class="span5">
							<h4>Create Import By CSV</h4>
							<div class="control-group">
								<label class="control-label" for="iMMI2">Mailinglist Id:</label>
								<div class="controls">
									<input type="text" id="iMMI2">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="iMCSVUrl">CSV Url:</label>
								<div class="controls">
									<input type="text" id="iMCSVUrl" value="http://custfiles.anpdm.com/apsis/restapi/examples/apsis_example_file_no_headers.csv">
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="submit" class="btn" id="iMMISend2">Send</button>
								</div>
							</div>
						</div>
						<div class="span11">
							<h4>Get Import ID Status</h4>
							<div class="control-group">
								<label class="control-label" for="iMImportId">Import Id:</label>
								<div class="controls">
									<input type="text" id="iMImportId" disabled>
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="submit" class="btn" id="iMGetStatus">Get Status</button>
								</div>
							</div>
							<p>Status:</p>
							<div id="iMResult" class="resultBox">

							</div>
						</div>
					</div>


				</div>
				<div class="tab-pane" id="subscriptionMethods">
					<div class="row">
						<div class="span5">
							<h4>Delete Mailinglist Subscriptions</h4>
							<div class="control-group">
								<label class="control-label" for="sMMailinglistId">Mailinglist Id:</label>
								<div class="controls">
									<input type="text" id="sMMailinglistId">
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="submit" class="btn" id="smDelete">Send</button>
								</div>
							</div>
							<p>Affected rows: <span id="smAffectedRows"></span></p>
						</div>
						<div class="span5">
							<h4>Delete Single Subscription</h4>
							<div class="control-group">
								<label class="control-label" for="sMMailinglistId2">Mailinglist Id:</label>
								<div class="controls">
									<input type="text" id="sMMailinglistId2">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="sMSubscriberId">Subscriber Id:</label>
								<div class="controls">
									<input type="text" id="sMSubscriberId">
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="submit" class="btn" id="smDelete2">Delete</button>
								</div>
							</div>
							<p>Result: <span id="sMResult"></span></p>
						</div>

					</div>
				</div>
				<div class="tab-pane" id="subscriberMethods">
					<div class="row">
						<div class="span6">
							<h4>Delete Subscribers From Opt Out All</h4>
							<div class="control-group">
								<label class="control-label" for="sM2SubscriberId1">Subscriber Id 1:</label>
								<div class="controls">
									<input type="text" id="sM2SubscriberId1">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="sM2SubscriberId2">Subscriber Id 2:</label>
								<div class="controls">
									<input type="text" id="sM2SubscriberId2">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="sM2SubscriberId3">Subscriber Id 3:</label>
								<div class="controls">
									<input type="text" id="sM2SubscriberId3">
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="submit" class="btn" id="sM2Delete">Delete</button>
								</div>
							</div>
							<div class="row">
								<div class="span2">
									<p>Deleted Ids:</p>
									<div id="sM2DeletedIds" class="resultBox">

									</div>
								</div>
								<div class="span2">
									<p>Failed Ids:</p>
									<div id="sM2FailedIds" class="resultBox">

									</div>
								</div>
							</div>
						</div>
						<div class="span5">
							<h4>Update Subscribers</h4>
							<div class="control-group">
								<label class="control-label" for="sM2SubscriberId12">Subscriber Id 1:</label>
								<div class="controls">
									<input type="text" id="sM2SubscriberId12">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="sM2SubscriberId22">Subscriber Id 2:</label>
								<div class="controls">
									<input type="text" id="sM2SubscriberId22">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="sM2SubscriberId32">Subscriber Id 3:</label>
								<div class="controls">
									<input type="text" id="sM2SubscriberId32">
								</div>
							</div>
							<div class="control-group">
								<div class="controls">
									<button type="submit" class="btn" id="sM2Update">Update</button>
								</div>
							</div>
							<div class="row">
								<div class="span2">
									<p>Updated Ids:</p>
									<div id="sM2UpdatedIds" class="resultBox">

									</div>
								</div>
								<div class="span2">
									<p>Failed Ids:</p>
									<div id="sM2FailedIds2" class="resultBox">

									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="getClicks">
					<h4>Get Clicks By Send Queue Id Paginated</h4>
					<div class="form-inline">
						<div class="control-group">
							<label class="control-label" for="gCSendQueueId">Send Queue Id:</label>
							<div class="controls">
								<input type="text" id="gCSendQueueId">
							</div>
						</div>
						<label class="select">
							Pagenumber:
							<select id="gCPagenumber" class="span1">
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5</option>
							</select>
						</label>
						<label class="select">
							Pagesize:
							<select id="gCMPagesize" class="span1">
								<option>5</option>
								<option>10</option>
								<option>15</option>
								<option>20</option>
							</select>
						</label><br>
						<button type="submit" id="gCGet" class="btn">Get</button>
						<p>Result:</p>
						<div id="gCResult" class="resultBox">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="span12">
			<div id="error-holder">
				<p>Error message:</p>
				<div id="error-list">

				</div>
				<button class="btn btn-small" id="btn-clear-errors">Clear</button>
			</div>
		</div>
	</div>

</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/apsis.js"></script>
</body>
</html>
