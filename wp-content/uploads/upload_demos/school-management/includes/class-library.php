<?php
class Smgtlibrary
{
	public function mj_smgt_add_book($data)
	{
		global $wpdb;
		$table_book=$wpdb->prefix.'smgt_library_book';
		$bookdata['ISBN']=mj_smgt_address_description_validation($data['isbn']);
		$bookdata['book_name']=mj_smgt_address_description_validation($data['book_name']);
		$bookdata['author_name']=mj_smgt_onlyLetter_specialcharacter_validation($data['author_name']);
		$bookdata['cat_id']=mj_smgt_onlyNumberSp_validation($data['bookcat_id']);
		$bookdata['rack_location']=mj_smgt_onlyNumberSp_validation($data['rack_id']);
		$bookdata['price']=mj_smgt_onlyNumberSp_validation($data['book_price']);
		$bookdata['quentity']=mj_smgt_onlyNumberSp_validation($data['quentity']);
		$bookdata['description']=mj_smgt_address_description_validation($data['description']);
		$bookdata['added_by']=get_current_user_id();
		$bookdata['added_date']=date('Y-m-d');
		
		if($data['action']=='edit')
		{
			$book_id['id']=$data['book_id'];
			$result=$wpdb->update( $table_book, $bookdata ,$book_id);
			return $result;
		}
		else
		{
			
			$result=$wpdb->insert( $table_book,$bookdata);
			return $result;
		}
	}
	public function mj_smgt_get_all_books()
	{
		global $wpdb;
		$table_book=$wpdb->prefix.'smgt_library_book';
		
		$result = $wpdb->get_results("SELECT * FROM $table_book");
		return $result;
		
	}
	public function mj_smgt_get_all_books_creted_by($user_id)
	{
		global $wpdb;
		$table_book=$wpdb->prefix.'smgt_library_book';
		
		$result = $wpdb->get_results("SELECT * FROM $table_book where added_by=".$user_id);
		return $result;
		
	}
	public function mj_smgt_get_single_books($id)
	{
		global $wpdb;
		$table_book=$wpdb->prefix.'smgt_library_book';
		$result = $wpdb->get_row("SELECT * FROM $table_book where id=".$id);
		return $result;
		
	}
	public function mj_smgt_get_bookcat()
	{
		$args= array('post_type'=> 'smgt_bookcategory','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
		$result = get_posts( $args );		
		return $result;		
	}
	public function mj_smgt_add_bookcat($data)
	{
		global $wpdb;
		$result = wp_insert_post( 
			array(
				'post_status' => 'publish',
				'post_type' => 'smgt_bookcategory',
				'post_title' => mj_smgt_popup_category_validation($data['category_name'])
			) 
		);
		return $result;			
	}
	
	public function mj_smgt_delete_cat_type($cat_id)
	{
		$result=wp_delete_post($cat_id);
		
		return $result;
	}
	public function mj_smgt_get_racklist()
	{
		$args= array('post_type'=> 'smgt_rack','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
		$result = get_posts( $args );		
		return $result;		
	}
	public function mj_smgt_add_rack($data)
	{
		global $wpdb;
		$result = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'smgt_rack',
						'post_title' => mj_smgt_popup_category_validation($data['category_name'])) );
		
			return $result;			
	}
	public function mj_smgt_delete_rack_type($cat_id)
	{
		$result=wp_delete_post($cat_id);
		
		return $result;
	}
	public function mj_smgt_delete_book($id)
	{
		global $wpdb;
		$table_book=$wpdb->prefix.'smgt_library_book';
		$result = $wpdb->query("DELETE FROM $table_book where id= ".$id);
		return $result;
	}
	public function mj_smgt_add_period($data)
	{
		global $wpdb;
		$result = wp_insert_post( array(
						'post_status' => 'publish',
						'post_type' => 'smgt_bookperiod',
						'post_title' => mj_smgt_popup_category_validation($data['category_name'])) );
		
			return $result;			
	}
	public function mj_smgt_get_periodlist()
	{
		$args= array('post_type'=> 'smgt_bookperiod','posts_per_page'=>-1,'orderby'=>'post_title','order'=>'Asc');
		$result = get_posts( $args );		
		return $result;		
	}
	public function mj_smgt_delete_period($cat_id)
	{
		$result=wp_delete_post($cat_id);
		
		return $result;
	}
	public function mj_smgt_add_issue_book($data)
	{		
		global $wpdb;
		$table_issue	=	$wpdb->prefix.'smgt_library_book_issue';		
		$issuedata['class_id']	=	mj_smgt_onlyNumberSp_validation($data['class_id']);
		if(isset($data['class_section']))
		$issuedata['section_id']	=	mj_smgt_onlyNumberSp_validation($data['class_section']);
		$issuedata['student_id']	=	mj_smgt_onlyNumberSp_validation($data['student_id']);
		$issuedata['cat_id']		=	mj_smgt_onlyNumberSp_validation($data['bookcat_id']);
		$issuedata['issue_date']	=	date('Y-m-d',strtotime($data['issue_date']));
		$issuedata['end_date']		=	date('Y-m-d',strtotime($data['return_date']));
		$issuedata['period']		=	mj_smgt_onlyNumberSp_validation($data['period_id']);
		$issuedata['fine']			=	0;
		if(isset($data['fine']))
			$issuedata['fine']		=	$data['fine'];
		$issuedata['status']		=	'Issue';
		$issuedata['issue_by']		=	get_current_user_id();
		
		if($data['action']=='edit')
		{
			$issue_id['id']		=	$data['issue_id'];
			foreach($data['book_id'] as $book)
			{
				$issuedata['book_id']	=	$book;
				$result		=	$wpdb->update( $table_issue, $issuedata ,$issue_id);
			}
			return $result;
		}
		else
		{
			foreach($data['book_id'] as $book)
			{
				$issuedata['book_id']	=	$book;
				$this->mj_smgt_get_qty_book_id($book,'issue');	 		
				$result		=	$wpdb->insert( $table_issue,$issuedata);				
			} 		
			return $result;
		}
	}
	
	
	public function mj_smgt_get_all_issuebooks()
	{
		global $wpdb;
		$table_issuebook=$wpdb->prefix.'smgt_library_book_issue';
		
		$result = $wpdb->get_results("SELECT * FROM $table_issuebook");
		return $result;
		
	}
	public function mj_smgt_get_all_issuebooks_created_by($user_id)
	{
		global $wpdb;
		$table_issuebook=$wpdb->prefix.'smgt_library_book_issue';
		
		$result = $wpdb->get_results("SELECT * FROM $table_issuebook where issue_by=".$user_id);
		return $result;
		
	}
	public function mj_smgt_get_single_issuebooks($id)
	{
		global $wpdb;
		$table_issuebook=$wpdb->prefix.'smgt_library_book_issue';
		$result = $wpdb->get_row("SELECT * FROM $table_issuebook where id=".$id);
		return $result;
		
	}
	public function mj_smgt_delete_issuebook($id)
	{
		global $wpdb;
		$table_issuebook=$wpdb->prefix.'smgt_library_book_issue';
		$result = $wpdb->query("DELETE FROM $table_issuebook where id= ".$id);
		return $result;
	}
	
	public function mj_smgt_get_qty_book_id($id,$action)
	{
		global $wpdb;
		$tbl_book_issue		=	$wpdb->prefix.'smgt_library_book_issue';
		$tbl_book			=	$wpdb->prefix.'smgt_library_book';
		$Book = $this->mj_smgt_get_single_books($id);
		
		$sql = "SELECT COUNT(*) FROM $tbl_book_issue WHERE book_id=$id AND status='Issue'";			
		$BookData = $wpdb->get_var($sql); 
		if($action == "issue")
		{
			if($BookData==0)
			{
				$BookData = 1;
			}
			$QTY = $Book->quentity - $BookData;
		}
		else
		{			
			$QTY = $Book->quentity + 1;
		}		
		$UpdateData['quentity'] = $QTY;
		$where['id'] = $id;
		$wpdb->update($tbl_book,$UpdateData,$where);
		return $QTY;
		
	}
	public function mj_smgt_submit_return_book($data)
	{	
		global $wpdb;
		$table_issuebook=$wpdb->prefix.'smgt_library_book_issue';
		foreach($data['books_return'] as $key=>$book_id)
		{
			$issue = $this->mj_smgt_get_single_issuebooks($book_id);			
			$this->mj_smgt_get_qty_book_id($issue->book_id,'');
			$issue_id['id']			=	$book_id;
			$issuedata['status']	=	'Submitted';
			$issuedata['fine']		=	$data['fine'][$key];
			$issuedata['actual_return_date']	=	date('Y-m-d');
			$result=$wpdb->update( $table_issuebook, $issuedata ,$issue_id);
		}
		return $result;
	}
}
 ?>