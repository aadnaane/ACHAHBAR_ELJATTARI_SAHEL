<?php $obj_lib = new Smgtlibrary();	?>
		<?php $bookid=0;
		if(isset($_REQUEST['book_id']))
			$bookid=$_REQUEST['book_id'];
		$edit=0;
		if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'edit')
		{
			$edit=1;
			$result=$obj_lib->mj_smgt_get_single_books($bookid);
		}?>
       
        <div class="panel-body">	
        <form name="book_form" action="" method="post" class="form-horizontal" id="book_form">
          <?php $action = isset($_REQUEST['action'])?$_REQUEST['action']:'insert';?>
		<input type="hidden" name="action" value="<?php echo $action;?>">
		<input type="hidden" name="book_id" value="<?php echo $bookid;?>">
        <div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="isbn"><?php esc_attr_e('ISBN','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="isbn" class="form-control validate[required,custom[address_description_validation]]" type="text" maxlength="50" value="<?php if($edit){ echo $result->ISBN;}?>" name="isbn">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="category_data"><?php esc_attr_e('Book Category','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="bookcat_id" id="category_data" class="form-control smgt_bookcategory validate[required] max_width_100">
					<option value=""><?php esc_attr_e('Select Book Category','school-mgt');?></option>
						<?php 
						$activity_category=mj_smgt_get_all_category('smgt_bookcategory');
						if(!empty($activity_category))
						{
							if($edit)
							{
								$fees_val=$result->cat_id; 
							}
							else
							{
								$fees_val=''; 
							}
						
							foreach ($activity_category as $retrive_data)
							{ 		 	
							?>
								<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$fees_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
							<?php }
						} 
						?> 
			</select>
			</div>
			<div class="col-sm-2">
				<button id="addremove_cat" class="btn btn-info margin_top_10" model="smgt_bookcategory"><?php esc_attr_e('Add','school-mgt');?></button>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="book_name"><?php esc_attr_e('Book Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>
			<div class="col-sm-8">
				<input id="book_name" class="form-control validate[required,custom[address_description_validation]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo stripslashes($result->book_name);}?>" name="book_name">
			</div>
		</div>
		<?php wp_nonce_field( 'save_book_admin_nonce' ); ?>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="author_name"><?php esc_attr_e('Author Name','school-mgt');?><span class="require-field"><span class="require-field">*</span></span></label>
			<div class="col-sm-8">
				<input id="author_name" class="form-control validate[required,custom[onlyLetter_specialcharacter]] text-input" maxlength="50" type="text" value="<?php if($edit){ echo stripslashes($result->author_name);}?>" name="author_name">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="category_data"><?php esc_attr_e('Rack Location','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<select name="rack_id" id="rack_category_data" class="form-control smgt_rack validate[required] max_width_100">
					<option value=""><?php esc_attr_e('Select Book Category','school-mgt');?></option>
						<?php 
						$activity_category=mj_smgt_get_all_category('smgt_rack');
						if(!empty($activity_category))
						{
							if($edit)
							{
								$rank_val=$result->rack_location; 
							}
							else
							{
								$rank_val=''; 
							}
						
							foreach ($activity_category as $retrive_data)
							{ 		 	
							?>
								<option value="<?php echo $retrive_data->ID;?>" <?php selected($retrive_data->ID,$rank_val);  ?>><?php echo esc_attr($retrive_data->post_title); ?> </option>
							<?php }
						} 
						?> 
			</select> 
			</div>
			<div class="col-sm-2">
				<button id="addremove_cat" class="btn btn-info margin_top_10" model="smgt_rack"><?php esc_attr_e('Add','school-mgt');?></button>
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="book_price"><?php esc_attr_e('Price','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="book_price" class="form-control validate[required,min[0],maxSize[8]]" type="number" step="0.01" value="<?php if($edit){ echo $result->price;}?>" name="book_price" >
			</div>
		</div>
		
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="class_capacity"><?php esc_attr_e('Quantity','school-mgt');?><span class="require-field">*</span></label>
			<div class="col-sm-8">
				<input id="quentity"  class="form-control validate[required,min[0],maxSize[5]]" type="number" value="<?php if($edit){ echo $result->quentity;}?>" name="quentity">
			</div>
		</div>
		<div class="form-group row mb-3">
			<label class="col-sm-2 control-label col-form-label text-md-end" for="description"><?php esc_attr_e('Description','school-mgt');?></label>
			<div class="col-sm-8">
				<textarea id="description" name="description" class="form-control"><?php if($edit){ echo $result->description;}?> </textarea>
			</div>
		</div>
		<div class="offset-sm-2 col-sm-8">        	
        	<input type="submit" value="<?php if($edit){ esc_attr_e('Save Book','school-mgt'); }else{ esc_attr_e('Add Book','school-mgt');}?>" name="save_book" class="btn btn-success" />
        </div>
           	
        
        </form>
        </div>
       
<?php ?>