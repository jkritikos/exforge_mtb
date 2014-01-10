<script type="text/javascript" src="/js/jquery.form.js"></script>
<script>
	$(document).ready(function(){
		$('.resolved').click(function(){
			var id = $(this).attr('id');
	        var reportId = 'report_'+id;
	        var isChecked = $(this).attr('checked')?1:0;
	        
	        
			//start spinner
			$(this).hide();
			$('#loader_'+id).show();
			//post
	        
	        var options = {
	            url: "/reports/updateResolved",
	            type: "POST",
	            dataType: "json",
	            data: ({reportId:id, flag:isChecked}),
	            success: function(d){
	                if(d == 1){
	                	$('#'+reportId).remove();
	                } else {
	                	alert('Error');
	                }
	            }
	        };
	
	        $.ajax(options);
        });
	});
</script>

<style type="text/css">
	td {text-align:center; padding:5px;}
</style>


<section id="content">
	<div class="wrapper">
         <div class="grid_6 first">
		     <div class="grid_6 first">
	
				 <header><h2>Question Reports</h2></header>
				 <section>
				     <table id="timesheetTable" class="paginate sortable full">
					 	<tr>
					 		<thead>
						 		<th>Player</th>
						 		<th>Question</th>
						 		<th>Errors</th>
						 		<th></th>
					 		</thead>
					 	</tr>
					 	<?php foreach($getQuestionReports as $i => $values): ?>
					 		  	<?php foreach($values as $v => $data): 
					 		  		
					 		  			$name = $data['name'];
										$facebookId = $data['facebookId'];
					 		  			$questionId = $data['question_id'];
										$errors = $data['errors'];
										$id = $data['id'];
										$resolved = $data['resolved'];
					 		  	?>
							 	<tr id="report_<?php echo $id ?>">
							 		<?php if(isset($facebookId) && $facebookId != null) { ?>
							 			<td><a href="http://www.facebook.com/<?php echo $facebookId ?>"><?php echo $name; ?></a></td>
							 		<?php }else { ?>
							 			<td><?php echo $name; ?></td>
							 	<?php } ?>
							 		<td><a href="<?php echo "/questions/edit/$questionId" ?>"><?php echo $questionId; ?></td>
					 		  		<td><?php echo $errors; ?></td>
					 		  		
					 		  		<td>
					 		  			<img id="loader_<?php echo $id; ?>" style="top:5px;display:none;position:relative;width:22px;height:22px;" src="/img/ajax-loader.gif" />
					 		  			<input <?php if($resolved==1) echo "checked";?> class="resolved" value="1" id="<?php echo $id; ?>" type="checkbox"></input></td>
									
							 	</tr>
					 		 <?php endforeach; ?>
					 	     <?php endforeach; ?>
					 </table>
				 </section>
		     </div>	     
		 </div>
	</div>	
		
	<?php
         echo $this->element('menu_report');
     ?>
</section>


