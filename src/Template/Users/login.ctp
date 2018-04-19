<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lumino - Login</title>
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<link href="../css/datepicker3.css" rel="stylesheet">
	<link href="../css/styles.css" rel="stylesheet">

	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
  <?= $this->Form->create() ?>
	<div class="row">
		<div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">Log in</div>
				<div class="panel-body">
					<form role="form">
						<fieldset>
							<div class="form-group">
								<?= $this->Form->control('username', ['label'=> false, 'placeholder' => "username", 'name' => "username" , 'class' => "form-control"]) ?>
							</div>
							<div class="form-group">
                <?= $this->Form->control('password', ['label'=> false, 'placeholder' => "password", 'name' => "password" , 'class' => "form-control", 'type' => "password"]) ?>
							</div>
              <?= $this->Form->button(__('Login'), ['class' => "btn btn-primary"]); ?>
					</form>
				</div>
			</div>
		</div><!-- /.col-->
	</div><!-- /.row -->	
<?= $this->Form->end() ?>

<script src="../js/jquery-1.11.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</body>
</html>
