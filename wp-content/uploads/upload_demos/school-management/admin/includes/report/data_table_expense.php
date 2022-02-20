<div class="panel-body clearfix">
		<div class="panel-body clearfix">
			<form method="post">  
				<div class="row">
					<div class="form-group col-md-3">
						<label for="exam_id"><?php esc_attr_e('Start Date','school-mgt');?></label>
							<input type="text"  id="sdate" class="form-control" name="sdate" value="<?php if(isset($_REQUEST['sdate'])) echo $_REQUEST['sdate'];else echo date('Y-m-d');?>" readonly>
					</div>
					<div class="form-group col-md-3">
						<label for="exam_id"><?php esc_attr_e('End Date','school-mgt');?></label>
							<input type="text"  id="edate" class="form-control" name="edate" value="<?php if(isset($_REQUEST['edate'])) echo $_REQUEST['edate'];else echo date('Y-m-d');?>" readonly>
					</div>
					<div class="form-group col-md-3 button-possition">
						<label for="subject_id">&nbsp;</label>
						<input type="submit" name="report_6" Value="<?php esc_attr_e('Go','school-mgt');?>"  class="btn btn-info"/>
					</div>
				</div>	
			</form>
		</div>		
		 <?php
		 if(isset($_REQUEST['report_6']))
		 {
        $start_date = $_POST['sdate'];
        $end_date = $_POST['edate'];
?>
        
        <div class="panel-body">
                    <div class="table-responsive">
                    <form id="frm-example" name="frm-example" method="post">
                <table id="tblexpence" class="display" cellspacing="0" width="100%">
                <thead>
                <tr> 
                        <th> <?php esc_attr_e( 'Supplier Name', 'school-mgt' ) ;?></th>
                        <th> <?php esc_attr_e( 'Amount', 'school-mgt' ) ;?></th>
                        <th> <?php esc_attr_e( 'Date', 'school-mgt' ) ;?></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th> <?php esc_attr_e( 'Supplier Name', 'school-mgt' ) ;?></th>
                        <th> <?php esc_attr_e( 'Amount', 'school-mgt' ) ;?></th>
                        <th> <?php esc_attr_e( 'Date', 'school-mgt' ) ;?></th>
                    </tr>
                </tfoot>
                <tbody>
                <?php 
                    global $wpdb;
                    $table_income=$wpdb->prefix.'smgt_income_expense';
                    $report_6 = $wpdb->get_results("SELECT * FROM $table_income where invoice_type='expense' AND income_create_date BETWEEN '$start_date' AND '$end_date'");
                    if(!empty($report_6))
                        foreach($report_6 as $result)
                        {	
                        $all_entry=json_decode($result->entry);
                        $total_amount=0;
                        foreach($all_entry as $entry){
                            $total_amount += $entry->amount;	
                ?>
                    <tr>
                        <td class="patient_name"><?php echo $result->supplier_name;?></td>
                        <td class="income_amount"><?php echo "<span> ". mj_smgt_get_currency_symbol() ." </span>" . $total_amount;?></td>
                        <td class="status"><?php echo mj_smgt_getdate_in_input_box($result->income_create_date);?></td>
                    </tr>
                    <?php } 
                    } ?>     
                </tbody>        
                </table>
            </form>
        </div>
        </div>
        <?php	
        }
    ?>
    </div>