<?php 
use App\Model\Value\ConfigsValue;
use Cake\Routing\Router;
?>
<!DOCTYPE html>
<html>
<head>	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lumino - Panels</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link href="css/datepicker3.css" rel="stylesheet">
	<link href="css/styles.css" rel="stylesheet">
	
	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span></button>
				<a class="navbar-brand" href="#"><span>PMS</span>Admin</a>
				<ul class="nav navbar-top-links navbar-right">
					<li class="dropdown"><a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
						<em class="fa fa-envelope"></em><span class="label label-danger">15</span>
					</a>
						<ul class="dropdown-menu dropdown-messages">
							<li>
								<div class="dropdown-messages-box"><a href="profile.html" class="pull-left">
									<img alt="image" class="img-circle" src="http://placehold.it/40/30a5ff/fff">
									</a>
									<div class="message-body"><small class="pull-right">3 mins ago</small>
										<a href="#"><strong>John Doe</strong> commented on <strong>your photo</strong>.</a>
									<br /><small class="text-muted">1:24 pm - 25/03/2015</small></div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="dropdown-messages-box"><a href="profile.html" class="pull-left">
									<img alt="image" class="img-circle" src="http://placehold.it/40/30a5ff/fff">
									</a>
									<div class="message-body"><small class="pull-right">1 hour ago</small>
										<a href="#">New message from <strong>Jane Doe</strong>.</a>
									<br /><small class="text-muted">12:27 pm - 25/03/2015</small></div>
								</div>
							</li>
							<li class="divider"></li>
							<li>
								<div class="all-button"><a href="#">
									<em class="fa fa-inbox"></em> <strong>All Messages</strong>
								</a></div>
							</li>
						</ul>
					</li>
					
				</ul>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar">
			<div class="profile-userpic">
				<img src="http://placehold.it/50/30a5ff/fff" class="img-responsive" alt="">
			</div>
			<div class="profile-usertitle">
				<div class="profile-usertitle-name"><?php echo $user_name; ?></div>
				<div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>
		<form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		</form>
		<ul class="nav menu">
			<li><a href="index.html"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
			<li><a href="housekeepings"><em class="fa fa-calendar">&nbsp;</em> House Keeping</a></li>
			<li><a href="users/login"><em class="fa fa-power-off">&nbsp;</em> Logout</a></li>
		</ul>
	</div><!--/.sidebar-->
	<!-- Modal -->
	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">House Keeping</li>
			</ol>
		</div><!--/.row-->
		<?= $this->Flash->render() ?>
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">House Keeping</h1>
			</div>
		</div><!--/.row-->	
		<div class="row">
			<?= $this->Flash->render('flash', [
				    'element' => '/Flash/error'
				]); ?>
			<?php echo $this->Form->create('realtys', array('id'=>'form-search', 'class' => 'col-md-12'))?>	
				<div class="col-lg-4">
		            <?php 
		            $options = array();
	                foreach ($equipment_types as $equipment_type) {
	                  $options[$equipment_type->id] =  $equipment_type->name;
	                }
		            ?>
	                <?= $this->Form->input(
	                    'type_new', [
	                    'type' => 'select',
	                    'multiple' => false,
	                    'label' => false,
	                    'options' => $options,
	                    'empty' => true,
	                    'id' => 'equipment-type',
	                    'class' => 'form-control']);?>
		        </div>
		        <div class="col-lg-3">
		            <?= $this->Form->control('room', ['label'=> false, 'placeholder' => "room", 'name' => "room" , 'class' => "form-control"]) ?>
		        </div>
		        <div class="col-lg-2">
		           	<button id="btn-search" class="btn btn-outline-secondary border-left-0 border" type="button">
	                    <i class="fa fa-search"></i>
	                </button>
		        </div>
		    <?= $this->Form->end(); ?>
			<div class="col-lg-12">
				<h2></h2>
			</div>
			<?php foreach ($equipment_infos as $equipment_info ) { ?>
			<div class="col-md-4">
				<div class="panel panel-default">
					<div class="panel-heading"><?= $equipment_info['name'] ?>
						<span class="pull-right clickable panel-toggle"><em class="fa fa-toggle-up"></em></span></div>
					<div class="panel-body">
						<label>Status</label>
                        <?php $options = [ConfigsValue::CLS_CLEAN => 'Clean', ConfigsValue::CLS_DIRTY => 'Dirty', ConfigsValue::CLS_INSPECTED => 'Inspected', ConfigsValue::CLS_PICKUP => 'Pickup'];?>
                        <?= $this->Form->input(
                            'type_new', [
                            'type' => 'select',
                            'multiple' => false,
                            'label' => false,
                            'value' => $equipment_info['equipment_state']['clean_state'],
                            'options' => $options,
                            'empty' => false,
                            'id' => 'status',
                            'class' => 'form-control status']);?>
					</div>
				</div>
			</div>
			<?= $this->Form->create('updateStatus', ['url' => ['controller' => 'HouseKeepings', 'action' => 'updateStatus/', $equipment_info['id']]]); ?>
			<div class="modal fade" id="myModal" role="dialog">
			    <div class="modal-dialog">
			    
			      <!-- Modal content-->
			      <div class="modal-content">
			        <div class="modal-header">
			        	<?= $this->Form->hidden('id',['id'=> 'id', 'value' => $equipment_info['id']]); ?>
			        	<?= $this->Form->hidden('status_hidden',['id'=> 'status_hidden']); ?>
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Update Status</h4>
			        </div>
			        <div class="modal-body">
			          <p>Do you update status of this room?</p>
			        </div>
			        <div class="modal-footer">
			        	<button type="submit" class="sending" data-dismiss="modal">Yes</button>
			        	<button type="submit" class="button-close" data-dismiss="modal">No</button>
			        </div>
			      </div>
			      
			    </div>
			  </div>
			  <?= $this->Form->end(); ?>
			<?php } ?>
		</div><!-- /.row -->
		<div class="modal fade" id="mySuccess" role="dialog">
			    <div class="modal-dialog">
			    
			      <!-- Modal content-->
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Success</h4>
			        </div>
			        <div class="modal-body">
			          <p style="color: red;">Update Room SucessFull</p>
			        </div>
			        <div class="modal-footer">
			        	<button type="submit" class="button-close" data-dismiss="modal">Close</button>
			        </div>
			      </div>
			      
			    </div>
			  </div>
		</div>
	</div><!--/.main-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/custom.js"></script>
	<script>
		$(document).ready(function(){
		    $('.form-control.status').on('change', function() {
		      var status = $("#status option:selected").val();
			  $('#status_hidden').val( status );	
			  $modal = $('#myModal');
			  $modal.modal('show');
			});

			$('#btn-search').on('click', function() {
		        var type = $("#equipment-type").val();
		        var room = $("#room").val();
		        var action = '';
		        if(action.lastIndexOf('index') == 1 ) action = action + '/index';
		        if(type) action = action + "?type=" + type;
		        if(room) action = action + "&room=" + room;

		        $("#form-search").attr('action', action);
		        $("#form-search").submit();
		    });

		    $('.sending').on('click', function(){
		    	var status = $("#status option:selected").val();	
		    	var id = $("#id").val();
				$.ajax({
					url: "<?= Router::url(['controller' => 'HouseKeepings', 'action' => 'updateStatus']) ?>",
					type: 'POST',
					data: {status, id},
					success: function(tab){
                    	$modal = $('#mySuccess');
			  			$modal.modal('show');
	                },
	                error: function (tab) {
	                    alert('error');
	                }
				});
			});
		});
	</script>
	<style type="text/css">
		input.form-control {
		    height: 34px !important;
		}
		.sending {
			width: 85px;
		    height: 34px;
		    border-radius: 25px;
		    background: cornflowerblue;
		}
		.button-close {
			width: 85px;
		    height: 34px;
		    border-radius: 25px;
		    background: #eee;
		}
	</style>
</body>
</html>
