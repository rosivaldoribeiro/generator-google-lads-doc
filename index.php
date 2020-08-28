<html lang="en">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"> 
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Doc.Leads | Google</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
  </head>
  	<body>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-8 col-lg-6  column col-sm-offset-0 col-md-offset-2 col-lg-offset-3">
					<form action="upload.php" method="post" enctype="multipart/form-data">
					<form class="form-horizontal">
						<fieldset>
							<legend>Select client</legend>
							<div class="form-group">
								<label class="col-md-3 control-label" for="selectbasic">URL of Client</label>
								<div class="col-md-9">
									<input type="text" class="form-control" id="client" name="client">
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label" for="selectbasic">Type</label>
								<div class="col-md-9">
									<label class="checkbox-inline">
										<input type="radio" id="type" name="type" value="download" checked> Direct Download</label><BR>
									<label class="checkbox-inline">
										<input type="radio" id="type" name="type" value="filterdata"> Filter data</label>
								</div>
							</div>
						</fieldset>
						<button type="submit" class="btn btn-primary">Next</button>
					</form>
				</div>
			</div>
		</div>
    </body>
</html>