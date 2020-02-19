<?php
require 'authenticate.php';
require 'includes/functions.php';
require 'includes/functions2.php';
require 'includes/functions4.php';
require_once('includes/MySqlDb.php');
ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
ini_set("memory_limit","1500M");

$available_terms     = $db->find_by_sql("*","terms  ORDER BY term ASC","","");
$available_section   = $db->find_by_sql("DISTINCT section","section_list  ORDER BY section ASC","","");
$available_subject   = $db->find_by_sql("DISTINCT subject","subject_list  ORDER BY subject ASC","","");
$available_exam_type = $db->find_by_sql("DISTINCT exam_type","exam_type_list  ORDER BY exam_type ASC","","");

?>


<?php require 'includes/header.php';   ?>

      <div id="main_content">
  <?php
 if($category == 'Manage Records')
 {
            if($_GET['subcat'] == 'Insert Name and Id') 
            {
                           
                            if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  echo selection_menu6("INSERT NAME & ID");
                               }
                            if(isset($_POST['only_4_name_id']))
                            {
                                $section_name   = $_POST['section_name'];
                                if( $db->find_by_sql("implement","implement_4th_subject"," section='$section_name'","") !== 'No Result Found'){ $fourth_subject = 'fourth_subject'; }
                                echo get_cre_num_of_student($fourth_subject,$section_name); 
                            }
                            
                            if(isset($_POST['numOfStudent']))
                            {
                                $section_name     = $_POST['section_name'];
                                $numOfStudent     = $_POST['numOfStudent'];
                                $fourth_subject   = $_POST['fourth_subject'];
                                echo get_create_student($numOfStudent,"","",$section_name,$fourth_subject,$selected_fourth);
                            }
                            if(isset($_POST['student_id']) && isset($_POST['student_name']))
                            {                  
                                    $count_student_id = count($_POST['student_id']);
                                    $section_name     = $_POST['section_name'];
                                    $student_id       = $_POST['student_id'];
                                    $student_name     = $_POST['student_name'];
                                    $fourth_subject_name = $_POST['subject_name'];
                                    $duplicate_check = $db->duplicate_value_check($student_id);
                                    if(is_array($fourth_subject_name) == TRUE)
                                    {
                                       $selected_fourth = $db->fourth_checked($fourth_subject_name,"selected"); $fourth_subject = 'fourth_subject';
                                       $i = 0;
                                       foreach ($fourth_subject_name as $fourth_subject_name_val)
                                       {
                                           if($fourth_subject_name_val == 'Available Subjects'){ $not_set='not set';$exist_id = $student_id[$i]; break;} 
                                           if($db->find_by_sql("subject","subject_list ","subject='$fourth_subject_name_val' AND section='$section_name'","") == 'No Result Found')
                                           {$not_found='not found';$exist_id = $student_id[$i]; $exist_sub = $fourth_subject_name_val;break;}
                                           $i++;
                                       }
                                    }
                                    if($not_set == 'not set')
                                    {
                                       echo get_create_student($count_student_id, $student_id, $student_name,$section_name, $fourth_subject, $selected_fourth);
                                       echo '<div id="message"><b>4th subject not set for student-id:"'.$exist_id.'"</b></div>'; 
                                    }elseif($not_found == 'not found')
                                    {
                                       echo get_create_student($count_student_id, $student_id, $student_name,$section_name, $fourth_subject, $selected_fourth);
                                       echo '<div id="message"><b>4th subject :"'.$exist_sub.'" not found in Section :"'.$section_name.'" for student-id:"'.$exist_id.'"</b></div>'; 
                                    }elseif(empty ($duplicate_check) == FALSE){
                                       echo get_create_student($count_student_id, $student_id, $student_name,$section_name, $fourth_subject, $selected_fourth);
                                       echo '<div id="message"><b>'.$duplicate_check.' in student id field</b></div>'; 
                                    }else
                                        {
                                           get_create_student2($count_student_id,$section_name,$student_id,$student_name,$fourth_subject_name,$selected_fourth,$fourth_subject);
                                        }                            
                                      
                            }
            }
            elseif($_GET['subcat'] == 'Insert Number') 
            {
                       if(!isset($_POST['submit']))
                       {
                            if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  echo selection_menu2($selected_term,$selected_section,$selected_subject,$selected_exam_type);
                               }
                       }
                        if(isset($_POST['submit']))
                        {
                                $section_name[]  = $_POST['section_name'];
                                $term_name[]     = $_POST['term_name'];
                                $subject_name[]  = $_POST['subject_name'];
                                $exam_type_name[]= $_POST['exam_type_name'];

                                if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms' || $subject_name[0] == 'Available Subjects' || $exam_type_name[0] == 'Available Exam-type')
                                {
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo selection_menu2($selected_term,$selected_section,$selected_subject,$selected_exam_type);
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections and subjects and exam-type</b></div>';                 
                                }else{
                                        $section_exists    = $db->get_section_exists($term_name,$section_name);
                                        $subject_exists    = $db->get_subject_exists($term_name,$section_name,$subject_name);
                                        $exam_type_exists  = $db->get_exam_type_exists($term_name,$section_name,$subject_name,$exam_type_name);
                                        $selected_term     = $db->set_checked($term_name,"selected");
                                        $selected_section  = $db->set_checked($section_name,"selected");
                                        $selected_subject  = $db->set_checked($subject_name,"selected");
                                        $selected_exam_type= $db->set_checked($exam_type_name,"selected");
                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo selection_menu2($selected_term,$selected_section,$selected_subject,$selected_exam_type);
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }elseif(empty($subject_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo selection_menu2($selected_term,$selected_section,$selected_subject,$selected_exam_type);
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$subject_exists.'</b></div>';
                                        }elseif(empty($exam_type_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo selection_menu2($selected_term,$selected_section,$selected_subject,$selected_exam_type);
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$exam_type_exists.'</b></div>';
                                        }else{
                                               echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                               echo selection_menu2($selected_term,$selected_section,$selected_subject,$selected_exam_type);
                                               echo get_insert_numbers($section_name,$term_name,$subject_name,$exam_type_name);
                                             }
                                    }
                        }
                        if(isset($_POST['insert_number']))
                        {  $student_id    = $_POST['student_id'];
                           $student_name  = $_POST['student_name'];
                           $number        = $_POST['number'];
                           $section_name  = $_POST['section_name'];
                           $term_name     = $_POST['term_name'];
                           $subject_name  = $_POST['subject_name'];
                           $exam_type_name= $_POST['exam_type_name'];
                            
                            foreach ($student_id as $key => $value)
                            {
                                $result = $db->insert("student_n_numbers","student_id,student_name,term,section,subject,exam_type,number","'$value','$student_name[$key]','$term_name','$section_name','$subject_name','$exam_type_name','$number[$key]'","student_id='$value' AND term='$term_name' AND section='$section_name' AND subject='$subject_name' AND exam_type='$exam_type_name' ");
                                if($result == 'already exist')
                                {
                                  $result = $db->update("student_n_numbers","number = '$number[$key]'","student_id='$value' AND term='$term_name' AND section='$section_name' AND subject='$subject_name' AND exam_type='$exam_type_name' ");
                                }
                            }
                            if($result){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Number has been inserted successfully for Term :"'.$term_name.'", Section :"'.$section_name.'", Subject :"'.$subject_name.'", Exam-type :"'.$exam_type_name.'"</b></div>'; }
                        }
            }            
            elseif($_GET['subcat'] == 'Edit Name and Id') 
            {
                             if(strlen($db->get_column_exists()) !== 0)
                              {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                              else{
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo selection_menu6("EDIT & DELETE NAME & ID");
                                    if(isset($_POST['only_4_name_id']))
                                    {
                                        $section_name = $_POST['section_name'];
                                        $section_exists = $db->get_section_exists(array($term_name),array($section_name));
                                        if ($db->find_by_sql("student_id","student_n_numbers","section='$section_name' ","") == 'No Result Found')
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                        }else{
                                          echo ed_name_id($section_name);
                                        }
                                    }
                                 }

                         if(isset($_POST['edit_x']))
                         {
                             $selected_id = $_POST['selected_id'];
                             $section_name = $_POST['section_name'];
                             echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                             echo edit_name_id($selected_id,"","","",$section_name);
                         }
                         if(isset($_POST['finally_edit']))
                         {
                              $count_selected_id = count($_POST['selected_id']);
                              $selected_id = $_POST['selected_id'];
                              $selected_student_id = $_POST['selected_student_id'];
                              $edited_student_id = $_POST['edited_student_id'];
                              $edited_student_name = $_POST['edited_student_name'];
                              $section_name = $_POST['section_name'];
                              $duplicate_check = $db->duplicate_value_check($edited_student_id);
                              $j = 0;
                              foreach($edited_student_id as $edited_student_id_val)
                              {
                                  $stu_id = $getting_4_codes.''.$edited_student_id_val;
                                  if($selected_student_id[$j] == $stu_id)
                                  {
                                      
                                  }else{
                                      $check_exist_student_id = $db->find_by_sql("student_id","student_n_numbers"," student_id='$stu_id' AND section='$section_name'","");
                                      if($check_exist_student_id !== 'No Result Found'){ $exist = substr($check_exist_student_id[0]['student_id'],4); break; }
                                  }
                                  $j++;
                              }
                              if(empty($exist) == FALSE){  
                                   echo '<div id="message" style="color:#CC3300;"><b>Student-id : "' .$exist. '" already exist in Section :"'.$section_name.'" Student id must be unique</b></div>'; echo edit_name_id($selected_id,$edited_student_id,$edited_student_name,$selected_student_id,$section_name);
                              }elseif(empty ($duplicate_check) == FALSE)
                              {
                                  echo '<div id="message" style="color:#CC3300;"><b>'.$duplicate_check.' in student id field</b></div>';  echo edit_name_id($selected_id,$edited_student_id,$edited_student_name,$selected_student_id,$section_name);                                   
                              }else{
                                          for($i=0;$i<$count_selected_id;$i++)
                                          {  $update_student = edit_name_id2($getting_4_codes.''.$edited_student_id[$i],$edited_student_name[$i],$selected_id[$i],$section_name); }
                                          if($update_student){ echo '<div style="margin:auto;width: 500px;height:100px;"></div><div id="message"><b>Given student id & name has been updated successfully</b></div>'; }
                                   }
                         }
                         if(isset($_POST['selected_delete_x']))
                         {
                             $count_selected_id = count($_POST['selected_id']);
                             $selected_id = $_POST['selected_id'];
                             $section_name = $_POST['section_name'];
                             for($i=0;$i<$count_selected_id;$i++)
                             { $delete_subject = delete_name_id($selected_id[$i],$section_name); }
                             if($delete_subject){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected student and its related data have been deleted successfully</b></div>'; }
                         }
            }
            elseif($_GET['subcat'] == 'Upload File') 
            {
                            if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  echo selection_menu("UPLOAD CSV FILE (MAX:100MB)");
                               }
                       
                        if(isset($_POST['section_name']))
                        {
                                $section_name  = $_POST['section_name'];
                                $term_name     = $_POST['term_name'];
                                if($section_name == 'Available Sections' || $term_name == 'Available Terms')
                                {
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections</b></div>';                 
                                }else{
                                        $section_exists    = $db->get_section_exists(array($term_name),array($section_name));
                                        $selected_term     = $db->set_checked(array($term_name),"selected");
                                        $selected_section  = $db->set_checked(array($section_name),"selected");
                                        $subject = $db->find_by_sql("DISTINCT subject","subject_list","term='$term_name' AND section='$section_name'","");
                                        
                                            if($subject !== 'No Result Found')
                                            {
                                                 foreach ($subject as $subj_val)
                                                 {
                                                    $exm_type[$subj_val['subject']] = $db->find_by_sql("DISTINCT exam_type","exam_type_list","term='$term_name' AND section='$section_name' AND subject='{$subj_val['subject']}'","");
                                                    if($exm_type[$subj_val['subject']] == 'No Result Found')
                                                    { $not_found = 'not found';$not_found_sub=$subj_val['subject'];break; }
                                                 }
                                            }

                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }elseif($not_found == 'not found')
                                        {
                                            echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Exam-type not found in Subject: "'.$not_found_sub.'", Term :"'.$term_name.'", Section :"'.$section_name.'"</b></div>';
                                        }elseif($subject == 'No Result Found')
                                        {
                                             echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No subject found in Term :"'.$term_name.'", Section :"'.$section_name.'"</b></div>';
                                        }else{
                                                 echo '<div style="margin:10px auto;width:99%;height:250px;border:4px solid #0099CC;background:whitesmoke;overflow:auto;">';
                                                    echo '<div id="message" style="color:#fff;font-size:14px;background:darkred;"><b>Upload File Maintainance for Term :"'.$term_name.'", Section :"'.$section_name.'"</b></div>';
                                                        $fourth_subject_implement = $db->find_by_sql("implement","implement_4th_subject","term='$term_name' AND section='$section_name'","");
                                                        $subject = $db->find_by_sql("DISTINCT subject","subject_list","term='$term_name' AND section='$section_name'  ORDER BY LENGTH(priority_order),priority_order","");
                                                        foreach ($subject as $subj_val)
                                                        {
                                                          $exm_type[$subj_val['subject']] = $db->find_by_sql("DISTINCT exam_type","exam_type_list","term='$term_name' AND section='$section_name' AND subject='{$subj_val['subject']}'  ORDER BY exam_type","");
                                                        }
                                                        $p =2;
                                                        echo 'Column <b>0</b> :: <b>Student Id</b><br>';
                                                        echo 'Column <b>1</b> :: <b>Student Name</b><br>';
                                                        foreach ($subject as $subj_value)
                                                        {                                          
                                                            foreach ($exm_type[$subj_value['subject']] as $exam_type_value )
                                                            {
                                                                $full_mark = $db->find_by_sql("full_mark","exam_type_list","term='$term_name' AND section='$section_name' AND subject='{$subj_value['subject']}' AND exam_type='{$exam_type_value['exam_type']}'","");
                                                                if($full_mark[0]['full_mark'] == ''){ $mark = 'Not set'; }else{ $mark = $full_mark[0]['full_mark']; }
                                                                echo 'Column <b>'.$p.'</b> :: Subject-><b>'.$subj_value['subject'].'</b> Exm-type-><b>'.$exam_type_value['exam_type'].' </b>(Full Mark: '.$mark.')<br>';
                                                                $p++;
                                                            }     
                                                        }
                                                       if($fourth_subject_implement !== 'No Result Found'){echo 'Column <b>'.$p.'</b> :: <b>4th subject name</b><br>'; $fourth_subject = 'fourth_subject';}
                                                 echo '</div>';
                                                 echo file_selection($term_name,$section_name,$fourth_subject);
                                             }
                                    }
                        }
                        if(isset($_POST['upload']))
                        {
                            $section_name  = $_POST['section_name'];
                            $term_name     = $_POST['term_name'];
                            $fourth_subject = $_POST['fourth_subject'];
                            echo upload_file($term_name,$section_name,$fourth_subject);
                        }
            }
            else{
                   echo 'Page not found';
                 }
  }else{
         echo 'Page not found';
      }
 ?> 
          
      </div>

<?php require 'includes/footer.php';   ?>
