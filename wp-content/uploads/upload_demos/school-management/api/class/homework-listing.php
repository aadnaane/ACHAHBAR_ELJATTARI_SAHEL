<?php
class HomeworkListing
{
    public function __construct()
    {
        add_action('template_redirect', array($this, 'redirectMethod'), 1);

    }
    public function redirectMethod()
    {
        //error_reporting(0);
        if ($_REQUEST["smgt-json-api"] == 'homework-listing') {
            if (isset($_REQUEST["current_user"])) {

                $response = $this->homework_listing($_REQUEST);
            }
            if (is_array($response)) {
                echo json_encode($response);
            } else {
                header("HTTP/1.1 401 Unauthorized");
            }
            die();
        }
        if ($_REQUEST["smgt-json-api"] == 'submit-homework') {
            if (isset($_REQUEST["homework_id"])) {

                $response = $this->homework_submit($_REQUEST);

            }

            if (is_array($response)) {
                echo json_encode($response);
            } else {
                header("HTTP/1.1 401 Unauthorized");
            }
            die();
        }
    }
    public function homework_listing($data)
    {

        global $wpdb;

        $tablename = "mj_smgt_homework";

        $response = array();

        $role = mj_smgt_get_user_role($data["current_user"]);

        $obj_homework = new Smgt_Homework();
        $menu_access_data = mj_smgt_get_userrole_wise_access_right_array_in_api($data['current_user'], 'homework');

        if ($role == 'student') {

            if ($menu_access_data['view'] == '1' && $menu_access_data['own_data'] == 1) {

                $class_id = get_user_meta($data['current_user'], 'class_name', true);

                global $wpdb;
                $user_id = $data['current_user'];
                $table_name = $wpdb->prefix . 'mj_smgt_homework';
                $table_name2 = $wpdb->prefix . 'mj_smgt_student_homework';
                $all_exam = $wpdb->get_results("SELECT * FROM $table_name as a LEFT JOIN $table_name2 as b ON a.`homework_id` = b.`homework_id`WHERE b.student_id = $user_id");

                //$all_exam    = mj_smgt_get_all_data($tablename);

            } elseif ($menu_access_data['view'] == '1' && $menu_access_data['own_data'] == 0) {

                $all_exam = mj_smgt_get_all_data($tablename);

            } else {

                $all_exam = "";

            }

        }

        if ($role == 'student') {

            if (!empty($all_exam)) {

                foreach ($all_exam as $retrieved_data) {

                    $homework_id = $retrieved_data->homework_id;
                 /*    $homwork_data = $obj_homework->view_submission($homework_id);
                    var_dump($retrieved_data);
                    die; */

                    $doc_data = json_decode($retrieved_data->homework_document);

                    if (!empty($doc_data[0]->value)) {
                        $document_file = content_url() . '/uploads/school_assets/' . $doc_data[0]->value;
                    } else {
                        $document_file = "";
                    }
					if ($retrieved_data->status == 1) {

						$student_homework_document= content_url().'/uploads/homework_file/'.$retrieved_data->file;
					}
					else
					{
						$student_homework_document=	'';
					}

                    if ($retrieved_data->status == 1) {
                        if (date('Y-m-d', strtotime($retrieved_data->uploaded_date)) <= $retrieved_data->submition_date) {

                            $status = 'Submitted';

                        } else {
                            $status = 'Late-Submitte';

                        }
                    } else {
                        $status = 'Pending';

                    }

                    $exam_array = array(

                        'homework_id' => $retrieved_data->homework_id,

                        'title' => $retrieved_data->title,

                        'class_name' => mj_smgt_get_class_name($retrieved_data->class_name),

                        'subject' => mj_smgt_get_single_subject_name($retrieved_data->subject),

                        'content' => $retrieved_data->content,

                        'status' => $status,

                        'submition_date' => mj_smgt_getdate_in_input_box($retrieved_data->submition_date),

                        'created_date' => mj_smgt_getdate_in_input_box($retrieved_data->created_date),

                        'homework_document' => $document_file,

                        'student_homework_document' => $student_homework_document,

                    );

                    $result_array[] = $exam_array;

                }

                $response['status'] = 1;

                $response['date_formate'] = get_option('smgt_datepicker_format');

                $response['resource'] = $result_array;

            } else {

                //$error['message']=__("Please Fill All Fields",'school-mgt');

                $response['status'] = 0;

                $response['message'] = __("Please Fill All Fields", 'school-mgt');

            }

        } else {

            //$error['message']=__("No Record Found",'school-mgt');

            $response['status'] = 0;

            $response['message'] = __("No Record Found", 'school-mgt');

        }

        return $response;

    }
    public function homework_submit($data)
    {
        if ($data['homework_id'] != "" && $data['student_id'] != "") 
		{
            $objj = new Smgt_Homework();
            $classdata = $objj->mj_smgt_parent_update_detail_api($data['homework_id'], $data['student_id']);
            $stu_homework_id = $classdata->stu_homework_id;
            $uploadfile = array('stu_homework_id' => $stu_homework_id,
                'homework_id' => $data['homework_id'],
                'upload_file' => $_FILES['file'],
            );
            $response = array();
            if (!empty($_FILES['file']))
			{
                $randm = mt_rand(5, 15);
                $file_name = "H" . $randm . "_" . $_FILES['file']['name'];
                $file_tmp = $_FILES['file']['tmp_name'];
                $filename= str_replace(' ', '_', $file_name);
                $upload = wp_upload_dir();
                $upload_dir_path = $upload['basedir'];
                $upload_dir = $upload_dir_path . '/homework_file';

                if (!is_dir($upload_dir)) 
				{
                    mkdir($upload_dir, 0700);
                }
                $up = move_uploaded_file($file_tmp, $upload_dir . '/' . $filename);
                global $wpdb;
                $mj_smgt_student_homework = $wpdb->prefix . "mj_smgt_student_homework";
                $stud_id = $data['student_id'];
                $homework_id = $data['homework_id'];
                $status = 1;
                $uploaded_date = date("Y-m-d H:i:s");
                $result = $wpdb->update($mj_smgt_student_homework, array(
                    'homework_id' => $homework_id, // string
                    'student_id' => $stud_id, // integer (number)
                    'status' => $status,
                    'uploaded_date' => $uploaded_date,
                    'file' => $filename),
                    array('stu_homework_id' => $stu_homework_id),
                    array('%d', '%d', '%d', '%s', '%s'),
                    array('%d'));
            }
            if ($result) 
			{
                $message['message'] = __("Homework Upload successfully 1234 !", 'school-mgt');

                $response['status'] = 1;

                $response['resource'] = $message;

            }
			
            return $response;

        }
		else 
		{

            $error['message'] = __("Please Fill All Fields", 'school-mgt');

            $response['status'] = 0;

            $response['resource'] = $error;

            return $response;

        }

    }
}
