<div class="panel-body">
   <form method="post" id="fee_payment_report">  
    
    <div class="row">
		<div class="form-group col-md-2">
			<label for="class_id"><?php esc_attr_e('Select Class','school-mgt');?><span class="require-field">*</span></label>
		   <select name="class_id"  id="class_list" class="form-control load_fee_type_single validate[required]s">
							<?php 
								$select_class = isset($_REQUEST['class_id'])?$_REQUEST['class_id']:'';
							?>
						<option value=" "><?php esc_attr_e('Select Class Name','school-mgt');?></option>
						<?php
							  foreach(mj_smgt_get_allclass() as $classdata)
							  {   ?>
							   <option  value="<?php echo $classdata['class_id'];?>" <?php echo selected($select_class,$classdata['class_id']);?>><?php echo $classdata['class_name'];?></option>
						 <?php } ?>
					</select>
		</div>
			<div class="form-group col-md-2">
			<label for="class_id"><?php esc_attr_e('Fee Type','school-mgt');?><span class="require-field">*</span></label>
		   <select id="fees_data" class="form-control validate[required]" name="fees_id">
						<option value=" "><?php esc_attr_e('Select Fee Type','school-mgt');?></option>
						<?php 
							if(isset($_REQUEST['fees_id']))
							{
								echo '<option value="'.$_REQUEST['fees_id'].'" '.selected($_REQUEST['fees_id'],$_REQUEST['fees_id']).'>'.mj_smgt_get_fees_term_name($_REQUEST['fees_id']).'</option>';
							}
						?>
				</select>
		</div>
			 <div class="form-group col-md-2">
			<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
						<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>" readonly>
			</div>
		<div class="form-group col-md-2">
			<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
					<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
		</div>
			<div class="form-group col-md-12 button-possition">
			<label for="subject_id">&nbsp;</label>
			  <input type="submit" name="report_4" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
		</div>
	</div>
    </form>
		</div>
    	  <div class="clearfix"> </div>
    	<?php
			if(isset($_POST['report_4']))
			{
				if($_POST['class_id']!=' ' && $_POST['fees_id']!=' ' && $_POST['sdate']!=' ' && $_POST['edate']!=' '){
				$class_id = $_POST['class_id'];
				$section_id=0;
				if(isset($_POST['class_section']))
					$section_id = $_POST['class_section'];
				$fee_term =$_POST['fees_id'];
				$sdate = $_POST['sdate'];
				$edate = $_POST['edate'];
				$result_feereport = mj_smgt_get_payment_report($class_id,$fee_term,$sdate,$edate);
				}
			?>
		<div class="table-responsive">
        <table id="example4" class="display" cellspacing="0" width="100%">
        	 <thead>
            <tr>                
                <th><?php esc_attr_e('Fee Type','school-mgt');?></th>  
				<th><?php esc_attr_e('Student Name','school-mgt');?></th>  
				<th><?php esc_attr_e('Roll No','school-mgt');?></th>  
                <th><?php esc_attr_e('Class','school-mgt');?> </th>  
				<th><?php esc_attr_e('Payment Status','school-mgt'); ?></th>
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
				 <th><?php esc_attr_e('Due Amount','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th>  
				<th><?php esc_attr_e('Year','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>                 
            </tr>
        </thead>
 
        <tfoot>
            <tr>
				<th><?php esc_attr_e('Fee Type','school-mgt');?></th>  
				<th><?php esc_attr_e('Student Name','school-mgt');?></th>
				<th><?php esc_attr_e('Roll No','school-mgt');?></th>  
                <th><?php esc_attr_e('Class','school-mgt');?> </th>  
				<th><?php esc_attr_e('Payment Status','school-mgt'); ?></th>
                <th><?php esc_attr_e('Amount','school-mgt');?></th>
				 <th><?php esc_attr_e('Due Amount','school-mgt');?></th>
                <th><?php esc_attr_e('Description','school-mgt');?></th> 
				<th><?php esc_attr_e('Year','school-mgt');?></th>
                <th><?php esc_attr_e('Action','school-mgt');?></th>         
            </tr>
        </tfoot>
 
        <tbody>
          <?php 
			if(!empty($result_feereport))
		 	foreach ($result_feereport as $retrieved_data){ 
			
		 ?>
            <tr>
				 <td><?php echo mj_smgt_get_fees_term_name($retrieved_data->fees_id);?></td>
				 <td><?php echo mj_smgt_get_user_name_byid($retrieved_data->student_id);?></td>
				  <td><?php echo get_user_meta($retrieved_data->student_id, 'roll_id',true);?></td>
				  <td><?php echo mj_smgt_get_class_name($retrieved_data->class_id);?></td>
				  <td>
				  <?php 
						$payment_status=mj_smgt_get_payment_status($retrieved_data->fees_pay_id);
						echo "<span class='btn btn-success btn-xs'>";
						echo esc_html__("$payment_status","school-mgt");
						echo "</span>";
				?>
				</td>
					<td><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" . $retrieved_data->total_amount;?></td>
				    <?php $due=  $retrieved_data->total_amount-$retrieved_data->fees_paid_amount;?>
				    <td><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" . $due?></td>
					<td><?php echo $retrieved_data->description;?></td>
					<td><?php echo $retrieved_data->start_year.'-'.$retrieved_data->end_year;?></td>
              
               <td>			
				<a href="#" class="show-view-payment-popup btn btn-default" idtest="<?php echo $retrieved_data->fees_pay_id; ?>" view_type="view_payment"><?php esc_attr_e('View','school-mgt');?></a>              
            </tr>
            <?php } ?>
     
        </tbody>
        
        </table>
       </div>
			<?php
			}
		?>