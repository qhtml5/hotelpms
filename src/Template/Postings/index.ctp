<?php 
use App\Model\Value\ConfigsValue;
use Cake\Routing\Router;
?>
	<!-- Modal -->
	
	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
		<div class="row">
			<ol class="breadcrumb">
				<li><a href="#">
					<em class="fa fa-home"></em>
				</a></li>
				<li class="active">Minibar Posting</li>
			</ol>
		</div><!--/.row-->
		<?= $this->Flash->render() ?>	
		<div class="col-lg-12">
			<div class="col-lg-12">
				<h2></h2>
			</div>
			<div class="row"><!-- END #content   -->
				<center><h2>Check Room</h2></center>
				<?php echo $this->Form->create('', array('id'=>'form-search', 'class' => 'col-md-12'))?>			   
				    <div class="col-lg-9">
			            <?= $this->Form->input(
	                    'type_new', [
	                    'type' => 'select',
	                    'multiple' => false,
	                    'label' => false,
	                    'empty' => true,
	                    'id' => 'room',
	                    'class' => 'form-control']);?>
			        </div>
			        <div class="col-lg-2">
			           	<button id="btn-search" class="btn btn-outline-secondary border-left-0 border" type="button">
		                    <i class="fa fa-search"></i>
		                </button>
			        </div>
			    <?= $this->Form->end(); ?>
			</div>
			<div class="row">
				<div class="white-box">
                    <div class="row">
                        <div class="col-md-12" style="padding-top: 18px;">
                            <table style="clear: both" class="table table-bordered table-striped" id="user">
                                <tbody>
                                    <tr>
                                        <td width="35%">Name</td>
                                        <td width="65%"><a href="#" id="username" data-type="text" data-pk="1" data-title="Enter username" class="editable editable-click" data-original-title="" title=""><?php if ( !empty($reservation_equipment)) echo $reservation_equipment['reservation_detail']['client_info']['first_name']. ' '.$reservation_equipment['reservation_detail']['client_info']['last_name']; ?></a></td>
                                        <?php if( !empty($reservation_equipment) ) { ?>
                                        <?= $this->Form->hidden('name',['id'=> 'name', 'value' => $reservation_equipment['reservation_detail']['client_info']['first_name']. ' '.$reservation_equipment['reservation_detail']['client_info']['last_name']]); ?>
                                    <?php } else { ?>
                                    <?= $this->Form->hidden('name',['id'=> 'name', 'value' => '']); ?>
                                    <?php } ?>

                                    </tr>
                                    <tr>
                                        <td>Arrival Date</td>
                                        <td>
                                            <a href="#" id="firstname" data-type="text" data-pk="1" data-placement="right" data-placeholder="Required" data-title="Enter your firstname" class="editable editable-click editable-empty" data-original-title="" title=""><?php if ( !empty($reservation_equipment))  echo date_format($reservation_equipment['reservation_detail']['arrival_date'],"Y-m-d"); ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Departure Date</td>
                                        <td>
                                            <a href="#" id="sex" data-type="select" data-pk="1" data-value="" data-title="Select sex" class="editable editable-click" style="color: rgb(152, 166, 173);"><?php if ( !empty($reservation_equipment)) echo date_format($reservation_equipment['reservation_detail']['departure_date'],"Y-m-d"); ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Nights</td>
                                        <td><a href="#" id="status" data-type="select" data-pk="1" data-value="0" data-source="/status" data-title="Select status" class="editable editable-click"><?php if ( !empty($reservation_equipment)) echo (strtotime($reservation_equipment['reservation_detail']['departure_date']) - strtotime($reservation_equipment['reservation_detail']['arrival_date'])) / (60 * 60 * 24);?></a></td>
                                    </tr>
                                    <tr>
                                        <td>BookingID</td>
                                        <td>
                                            <a href="#" id="dob" data-type="combodate" data-value="1984-05-15" data-format="YYYY-MM-DD" data-viewformat="DD/MM/YYYY" data-template="D / MMM / YYYY" data-pk="1" data-title="Select Date of birth" class="editable editable-click"><?php if ( !empty($reservation_equipment)) echo $reservation_equipment['reservation_detail']['reservation_info']['reservation_number']; ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Room Type</td>
                                        <td><a href="#" id="comments" data-type="textarea" data-pk="1" data-placeholder="Your comments here..." data-title="Enter comments" class="editable editable-pre-wrapped editable-click"><?php if ( !empty($reservation_equipment)) echo $reservation_equipment['equipment_type']['name']; ?></a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
			</div>
				<div class="panel-footer">
					<button type="submit" class="btn btn-lg btn-block btn-danger" data-dismiss="modal" id="btn-post" disabled="true">Posting</button>
				</div>
		</div>
	<div class="modal fade" id="myModal" role="dialog">
	    <div class="modal-dialog">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Minibar Post</h4>
	        </div>
	        <div class="modal-body">
	          <p>Do you post of this room?</p>
	        </div>
	        <div class="modal-footer">
	        	<button type="submit" class="sending" data-dismiss="modal">Yes</button>
	        	<button type="submit" class="button-close" data-dismiss="modal">No</button>
	        </div>
	      </div>
	      
	    </div>
	  </div>
	 <?php echo $this->Form->create('', array('id'=>'form-search', 'class' => 'col-md-12'))?>
	<div class="modal fade" id="myPost" role="dialog">
	    <div class="modal-dialog">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Minibar List</h4>
	        </div>
	        <?= $this->Form->hidden('id',['id'=> 'reservation_detail_id', 'value' => $reservation_equipment['reservation_detail']['id']]); ?>
	        <div class="modal-body">
	          <div class="row">
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-body">
						<input class="form-control" id="myInput" type="text" placeholder="Search..">
					</div>
					<table class="table table-hover" id="recordsTable">
						<thead>
							<tr>
								<th>#</th>
								<th>Name</th>
								<th>Price</th>
								<th>Number</th>
							</tr>
						</thead>
						<tbody id = "myTable">
							<?php foreach ($descriptions as $key => $description) 
								{ ?>
							<tr>
								<td><input type='checkbox' id='pos_<?php echo $description['id']; ?>'></td>
								<td><?php echo $description['name']; ?></td>
								<td><?php echo $description['price']; ?></td>
								<td><input type='number', class="form-control" id='number_<?php echo $description['id']; ?>', disabled ?></td>
							</tr>
							<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
							<script type="text/javascript">
								$(document).ready(function(){
									var update_disposal = function () {
							            if ($("#pos_<?php echo $description['id'];?>").is(":checked")) {
							                $('#number_<?php echo $description['id']; ?>').prop('disabled', false);
							                $('#number_<?php echo $description['id']; ?>').val(1);
							            }
							            else {
							                $('#number_<?php echo $description['id']; ?>').prop('disabled', 'disabled');
							                $('#number_<?php echo $description['id']; ?>').val();
							            }
							        };
							        $("#pos_<?php echo $description['id'];?>").change(update_disposal);
							    });
							</script>
							<?php } ?>
						</tbody>
					</table>

				</div>
			</div>
		</div>
	        </div>
	        <div class="modal-footer">
	        	<button type="submit" class="posting-yes" data-dismiss="modal">Yes</button>
	        	<button type="submit" class="button-close" data-dismiss="modal">No</button>
	        </div>
	      </div>
	     
	    </div>
	  </div>
	  <div class="modal fade" id="mySuccess" role="dialog">
			    <div class="modal-dialog">
			    
			      <!-- Modal content-->
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal">&times;</button>
			          <h4 class="modal-title">Success</h4>
			        </div>
			        <div class="modal-body">
			          <p style="color: red;">Post Minibar SucessFull</p>
			        </div>
			        <div class="modal-footer">
			        	<button type="submit" class="button-close" data-dismiss="modal">Close</button>
			        </div>
			      </div>
			      
			    </div>
			  </div>
		</div>
	  <?= $this->Form->end(); ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	<script src="js/jquery-1.11.1.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chart-data.js"></script>
	<script src="js/easypiechart.js"></script>
	<script src="js/easypiechart-data.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script src="js/custom.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script>
		$(document).ready(function(){
			$('#btn-search').on('click', function() {
		        var room = $("#room").val();
		        console.log(room);
		        var action = '';
		        if(action.lastIndexOf('index') == 1 ) action = action + '/index';
		        if(room) action = action + "?room=" + room;

		        $("#form-search").attr('action', action);
		        $("#form-search").submit();
		    });
		    if ( $('#name').val().length > 0 ) {
				$('#btn-post').prop('disabled', false);
			} else {
				$('#btn-post').prop('disabled', true);
			}
		    $("#myInput").on("keyup", function() {
			    var value = $(this).val().toLowerCase();
			    $("#myTable tr").filter(function() {
			      $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
			    });
			});

		 //    $('.sending').on('click', function(){
		 //    	var status = $("#status option:selected").val();	
		 //    	var id = $("#id").val();
		 //    	console.log(status);
			// 	$.ajax({
					// url: "<?= Router::url(['controller' => 'Housekeepings', 'action' => 'updateStatus']) ?>",
			// 		type: 'POST',
			// 		data: {status, id},
			// 		success: function(tab){
   //                  	$modal = $('#mySuccess');
			//   			$modal.modal('show');
	  //               },
	  //               error: function (tab) {
	  //                   alert('error');
	  //               }
			// 	});
			// });
		});


	</script>
	<script type="text/javascript">
	    $('#room').select2({
	        placeholder: 'Select an item',
	        ajax: {
	          url: "<?= Router::url(['controller' => 'Postings', 'action' => 'searchRoom']) ?>",
	          dataType: 'json',
	          delay: 250,
	          processResults: function (data) {
	            return {
	              results: data
	            };
	          },
	          cache: true,
	          escapeMarkup: function (markup) { return markup; }
	        }
	    });

	    $('#btn-post').on('click', function() {
	        $modal = $('#myModal');
			$modal.modal('show');
	    });
	    $('.sending').on('click', function() {
	        $modal = $('#myPost');
			$modal.modal('show');
	    });

	    $('.posting-yes').on('click', function(){
	     	var post_arr = [];
	     	var number_arr = [];
	     	var reservation_detail_id = $("#reservation_detail_id").val();
	     	var number = $("#number").val();
	    	$('#recordsTable input[type=checkbox]').each(function() {
		      if (jQuery(this).is(":checked")) {
		        var id = this.id;
		        var splitid = id.split('_');
		        var postid = splitid[1];
		        post_arr.push(postid);
		        number_arr.push(number);
		        
		      }
		    });
	    	if(post_arr.length > 0){
	    		console.log(123); 
		        // AJAX Request
		        $.ajax({
		          	url: "<?= Router::url(['controller' => 'Postings', 'action' => 'postRoom']) ?>",
					type: 'POST',
					data: {post_id: post_arr, reservation_detail_id : reservation_detail_id, number : number_arr},
					success: function(tab){
	                	$modal = $('#mySuccess');
			  			$modal.modal('show');
	                },
	                error: function (tab) {
	                    alert('error');
	                }
		        });
		    } 
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
		.posting-yes {
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
		*{margin:0px; padding:0px;}
		body{ background:url(../img/bg_2cwebvn.png); }

		/* CSS cho textbox nhập nội dung search, có chứa hình ảnh loading ở bên phải textbox */
		body.faq .faqsearch .faqsearchinputbox input {
			font-size:16px;	color:#6e6e6e;
			padding:10px;   border:1px solid #f8d043; 
			outline: #333;  width:100%;
			background:url(img/loading_static.gif) no-repeat right 50%;	
		}
		body.faq .faqsearch .faqsearchinputbox input.loading {
			background:url(img/loading_animate.gif) no-repeat right 50%;
		}
		#content{	width:700px; margin:70px auto 0px;}
		.faq-articles{margin:20px 0px ;}
		h4{margin:0px 0px 30px;}
		a {	color:#000;text-decoration:none;}
		p { 	color:#888;}
		p.back{	color:#0000FF;	margin-top:5px;	text-align:center;}
		p.goiy{ margin-bottom:-20px;}
		p.title{margin:10px 0px 15px;}
		h2,p.title a{ color:#DB2E66;}
		p.back a,a:hover, a:focus {	color:#00f; }

		#prod-content {
			border:1px solid #DB2E66;
		}
		*html #prod-content {	margin-top:3px;	}
		*html body#cxpage #prod-content {	margin-top:15px;	}
		.lead { font-size: 33px;margin-bottom:0px; }

	</style>
