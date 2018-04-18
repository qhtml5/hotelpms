<style>
    .alert-error {
    color: #ea1c0d;
    background-color: #fff8f7;
    border-color: #fddfe2;
}

</style>

<?php 
	if($this->Session->check('Message.flash')) 
	{ 
		
          	echo $this->Session->flash();
        
		
	}
?>