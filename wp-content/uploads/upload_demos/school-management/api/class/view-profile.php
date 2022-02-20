<?php 

class ViewProfile{

	public function __construct() 

	{

		add_action('template_redirect', array($this,'redirectMethod'), 1);

	}

	public function redirectMethod()

	{

			if($_REQUEST["smgt-json-api"]=='view-profile')

			{

				$response=$this->view_profile($_REQUEST["user_id"]);	 

			

				if(is_array($response)){

					echo json_encode($response);

				}

				else

				{

					header("HTTP/1.1 401 Unauthorized");

				}

				die();

			}
			if($_REQUEST["smgt-json-api"]=='profile_image_update')
			{
				$response=$this->update_user_profile($_REQUEST["user_id"]);	 
				if(is_array($response))
				{

					echo json_encode($response);
				}
				else
				{
					header("HTTP/1.1 401 Unauthorized");
				}
				die();
			}
	}
	public function view_profile($user_id)
	{
	if(isset($_REQUEST["user_id"]) && $_REQUEST["user_id"]!="")

	{		

		if($user_id!=0)

		{			

			$user_data=get_userdata($user_id); 

		}

		$school_obj = new School_Management($user_id);

		if(!empty($user_data)){

			$umetadata=mj_smgt_get_user_image($user_id);
			
			if(empty($umetadata))
			{
				$imageurl=get_option('smgt_student_thumb');
			}
			else
			{
				//$imageurl=$umetadata['meta_value'];
				$imageurl=$umetadata;
			}

			$result['ID']=$user_data->ID;

			$result['image']=$imageurl;

			$result['name']=$user_data->display_name;

			$result['username']=$user_data->user_login;

			$result['email']=$user_data->user_email;

			$result['address']=$user_data->address;

			$result['city']=$user_data->city;

			$result['state']=$user_data->state;

			$result['phone']=$user_data->phone;

			if($school_obj->role=='student'){

				if($user_data->class_name!="")

					$classname=mj_smgt_get_class_name($user_data->class_name);

					if(isset($user_data->class_section) && $user_data->class_section!=0){

							$section=mj_smgt_get_section_name($user_data->class_section); 

					}

					else

					{

						$section=__('No Section','school-mgt');;

					}							

					$parentdata =get_user_meta($user_data->ID, 'parent_id', true);					

					$result['class']=$classname;

					$result['section']=$section;

					foreach($parentdata as $parentid)

					{

						$parent=get_userdata($parentid);						

						$parentarray['name']=$parent->display_name;						

						if($parent->smgt_user_avatar)

						{								

							$parentarray['image'] = $parent->smgt_user_avatar;

						}

						else

						{						

							$parentarray['image'] = get_option('smgt_student_thumb');

						} 

						

						

						$parentarray['relation']=$parent->relation;

						$parents[]=$parentarray;

						

					}

					if(!empty($parents))

						$result['parents']=$parents;

							

					}

					if($school_obj->role=='parent')

					{

						$childsdata =get_user_meta($user_data->ID, 'child', true); 

						foreach($childsdata as $childid)

						{

							$child=get_userdata($childid);

							$childsarray['name']=$child->display_name;							

							$childsarray['image']=$child->smgt_user_avatar;

							$childrens[]=$childsarray;

						}

						if(!empty($childrens))

							$result['child']=$childrens;

					}

					if($school_obj->role=='teacher')

					{

						$result['subjects']=get_subject_name_by_teacher($user_data->ID);

					}

			$response['status']=1;

			$response['resource']=$result;

			return $response;

		}

		else

		{

			//$error['message']=__("Please Fill All Fields",'school-mgt');

			$response['status']=0;

			$response['message']=__("Record Not Found",'school-mgt');

		}

	}

	else

	{

		$response['status']=0;

		$response['message']=__("Please Fill All Fields",'school-mgt');

	}

	return $response;

		

	}

public function update_user_profile($user_id)
{
		//$image ='iVBORw0KGgoAAAANSUhEUgAAAE0AAAA+CAYAAABuv5bfAAAACXBIWXMAAAsTAAALEwEAmpwYAAAGiWlUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMxNDIgNzkuMTYwOTI0LCAyMDE3LzA3LzEzLTAxOjA2OjM5ICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgeG1sbnM6cGhvdG9zaG9wPSJodHRwOi8vbnMuYWRvYmUuY29tL3Bob3Rvc2hvcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIiB4bXA6Q3JlYXRlRGF0ZT0iMjAxOC0wMi0yN1QxMzo0NTowOSswNTozMCIgeG1wOk1vZGlmeURhdGU9IjIwMTgtMDItMjdUMTQ6Mzk6MTArMDU6MzAiIHhtcDpNZXRhZGF0YURhdGU9IjIwMTgtMDItMjdUMTQ6Mzk6MTArMDU6MzAiIGRjOmZvcm1hdD0iaW1hZ2UvcG5nIiBwaG90b3Nob3A6Q29sb3JNb2RlPSIzIiBwaG90b3Nob3A6SUNDUHJvZmlsZT0ic1JHQiBJRUM2MTk2Ni0yLjEiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MTgzN2Y5NDQtZTRhZS0xMDQ4LWI5OTYtZjFiODRkYmUzNzhkIiB4bXBNTTpEb2N1bWVudElEPSJhZG9iZTpkb2NpZDpwaG90b3Nob3A6Y2Q3ZDgyY2MtOWUzMC0yYzRjLTkzODctZGM5MGE0YzQ0OTdhIiB4bXBNTTpPcmlnaW5hbERvY3VtZW50SUQ9InhtcC5kaWQ6NDAzMmY0MzEtYTEzMi1kMzRjLWJiMDYtYTI0YzYwZGRlM2RiIj4gPHBob3Rvc2hvcDpEb2N1bWVudEFuY2VzdG9ycz4gPHJkZjpCYWc+IDxyZGY6bGk+YWRvYmU6ZG9jaWQ6cGhvdG9zaG9wOmNjMDI1M2YzLTcxMDctZjk0OC1hNmQwLTliYTYwODQ3MzMxZTwvcmRmOmxpPiA8L3JkZjpCYWc+IDwvcGhvdG9zaG9wOkRvY3VtZW50QW5jZXN0b3JzPiA8eG1wTU06SGlzdG9yeT4gPHJkZjpTZXE+IDxyZGY6bGkgc3RFdnQ6YWN0aW9uPSJjcmVhdGVkIiBzdEV2dDppbnN0YW5jZUlEPSJ4bXAuaWlkOjQwMzJmNDMxLWExMzItZDM0Yy1iYjA2LWEyNGM2MGRkZTNkYiIgc3RFdnQ6d2hlbj0iMjAxOC0wMi0yN1QxMzo0NTowOSswNTozMCIgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWRvYmUgUGhvdG9zaG9wIENDIChXaW5kb3dzKSIvPiA8cmRmOmxpIHN0RXZ0OmFjdGlvbj0ic2F2ZWQiIHN0RXZ0Omluc3RhbmNlSUQ9InhtcC5paWQ6MTgzN2Y5NDQtZTRhZS0xMDQ4LWI5OTYtZjFiODRkYmUzNzhkIiBzdEV2dDp3aGVuPSIyMDE4LTAyLTI3VDE0OjM5OjEwKzA1OjMwIiBzdEV2dDpzb2Z0d2FyZUFnZW50PSJBZG9iZSBQaG90b3Nob3AgQ0MgKFdpbmRvd3MpIiBzdEV2dDpjaGFuZ2VkPSIvIi8+IDwvcmRmOlNlcT4gPC94bXBNTTpIaXN0b3J5PiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PvewBWEAAAt+SURBVHic7Zx7jF1VFcZ/8yzTdqAPplSmD0oL02KnQB+88QFR0YiAokhE/QMkqBCNgAkqGhFEomIw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';
	
		/*$upload_dir  = wp_upload_dir();
		$image =$_REQUEST['image'];
		//$name = 'TestImagenew123661.jpg';
		$name = $_REQUEST['name'].'.jpg';;
		$path=$upload_dir['path'].'/'.$name;
		$baseurl_path=$upload_dir['url'].'/'.$name;


		$file=file_put_contents($path,base64_decode($image));*/
		
		if(isset($user_id) && $user_id!="")
		{	
			if($_FILES['image']['size'] > 0)
			{
				$smgt_avatar_image = mj_smgt_user_avatar_image_upload('image');
				$smgt_avatar = content_url().'/uploads/school_assets/'.$smgt_avatar_image;
			}
			$result=update_user_meta( $user_id,'smgt_user_avatar',$smgt_avatar );
			if($result)
			{
				$response['status']=1;
				$response['message']=__("Profile image successfully updated!","school-mgt");
			}
			else
			{
				$response['status']=0;
				$response['message']=__("Profile image not updated",'school-mgt');
			}
			return $response;
		}

	}

} ?>