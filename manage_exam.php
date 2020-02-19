<?php
require 'authenticate.php';
require 'includes/functions.php';
require 'includes/functions2.php';
require 'includes/functions4.php';
require_once('includes/MySqlDb.php');
$available_terms     = $db->find_by_sql("*","terms  ORDER BY term ASC","","");
$available_section   = $db->find_by_sql("DISTINCT section","section_list  ORDER BY section ASC","","");
$available_subject   = $db->find_by_sql("DISTINCT subject","subject_list  ORDER BY subject ASC","","");
$available_exam_type = $db->find_by_sql("DISTINCT exam_type","exam_type_list  ORDER BY exam_type ASC","","");
?>


<?php require 'includes/header.php';   ?>

      <div id="main_content">
  <?php
 if($category == 'Manage Exam-profile')
 {
            if($_GET['subcat'] == 'Term') 
            {
                       if(!(isset($_POST['edit_x']) || isset($_POST['finally_edit']) || isset($_POST['selected_delete_x'])))
                       {   if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  echo ed_term("");
                               }
                       }
                       if(isset($_POST['edit_x']))
                       {
                           $selected_term_id = $_POST['selected_term_id'];
                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                           echo edit_term($selected_term_id,"");
                       }
                       if(isset($_POST['finally_edit']))
                       {
                            $count_selected_term_id = count($_POST['selected_term_id']);
                            $selected_term_id = $_POST['selected_term_id'];
                            $edited_term = $_POST['edited_term_name'];
                            $duplicate_check = $db->duplicate_value_check($edited_term);
                            foreach($edited_term as $edited_term_name_val)
                            {
                                $check_exist_term = $db->find_by_sql("term","terms","term='$edited_term_name_val'","");
                                if($check_exist_term !== 'No Result Found'){ $exist = $check_exist_term[0]['term']; break; }
                            }
                            if(empty($exist) == FALSE){  
                                  echo edit_term($selected_term_id,$edited_term); echo '<div id="message"><b>Term : "' .$exist. '" already exist</b></div>';
                            }elseif(empty ($duplicate_check) == FALSE)
                            {
                               echo edit_term($selected_term_id,$edited_term);
                               echo '<div id="message"><b>'.$duplicate_check.'</b></div>'; 
                            }else{
                                     for($i=0;$i<$count_selected_term_id;$i++)
                                     {  $update_term = edit_term2($edited_term[$i],$selected_term_id[$i]); }
                                     if($update_term){ echo edit_term($selected_term_id,$edited_term); echo '<div id="message"><b>Given term name has been updated successfully</b></div>'; }
                                 }
                       }
                       if(isset($_POST['selected_delete_x']))
                       {
                           $count_selected_term_id = count($_POST['selected_term_id']);
                           $selected_term_id = $_POST['selected_term_id'];
                           for($i=0;$i<$count_selected_term_id;$i++)
                           { $delete_term = delete_term($selected_term_id[$i]); }
                           if($delete_term){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected term and its related data have been deleted successfully</b></div>'; }
                       }
            }
            elseif($_GET['subcat'] == 'Section') 
            {
                          if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  echo ed_selection_menu("EDIT & DELETE SECTION");
                                  if(isset($_POST['ed_selection_menu']))
                                  {
                                      $term_name = $_POST['term_name'];
                                     if($db->find_by_sql("section","section_list","term='$term_name' ","") == 'No Result Found')
                                      {
                                        echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                        echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                      }else{
                                         echo ed_section($term_name);
                                      }
                                  }
                               }
                       
                       if(isset($_POST['edit_x']))
                       {
                           $selected_section_id = $_POST['selected_section_id'];
                           $term_name = $_POST['term_name'];
                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                           echo edit_section($selected_section_id,"",$term_name);
                       }
                       if(isset($_POST['finally_edit']))
                       {
                            $count_selected_section_id = count($_POST['selected_section_id']);
                            $selected_section_id = $_POST['selected_section_id'];
                            $edited_section = $_POST['edited_section_name'];
                            $term_name = $_POST['term_name'];
                            $duplicate_check = $db->duplicate_value_check($edited_section);
                            foreach($edited_section as $edited_section_name_val)
                            {
                                $check_exist_section = $db->find_by_sql("section","section_list"," section='$edited_section_name_val' AND term='$term_name'","");
                                if($check_exist_section !== 'No Result Found'){ $exist = $check_exist_section[0]['section']; break; }
                            }
                            if(empty($exist) == FALSE){  
                                  echo edit_section($selected_section_id,$edited_section,$term_name); echo '<div id="message"><b>Section : "' .$exist. '" already exist in Term :"'.$term_name.'"</b></div>';
                            }elseif(empty ($duplicate_check) == FALSE)
                            {
                               echo edit_section($selected_section_id,$edited_section,$term_name);
                               echo '<div id="message"><b>'.$duplicate_check.'</b></div>';
                            }else{
                                        for($i=0;$i<$count_selected_section_id;$i++)
                                        {  $update_section = edit_section2($edited_section[$i],$selected_section_id[$i],$term_name); }
                                        if($update_section){ echo edit_section($selected_section_id,$edited_section,$term_name); echo '<div id="message"><b>Given section name has been updated successfully</b></div>'; }
                                 }
                       }
                       if(isset($_POST['selected_delete_x']))
                       {
                           $count_selected_section_id = count($_POST['selected_section_id']);
                           $selected_section_id = $_POST['selected_section_id'];
                           $term_name = $_POST['term_name'];
                           for($i=0;$i<$count_selected_section_id;$i++)
                           { $delete_section = delete_section($selected_section_id[$i],$term_name); }
                           if($delete_section){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected section and its related data have been deleted successfully</b></div>'; }
                       }
            }
            elseif($_GET['subcat'] == 'Subject') 
            {
                        if(strlen($db->get_column_exists()) !== 0)
                              {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                              else{
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo ed_selection_menu2("EDIT & DELETE SUBJECT");
                                    if(isset($_POST['ed_selection_menu2']))
                                    {
                                        $term_name = $_POST['term_name'];
                                        $section_name = $_POST['section_name'];
                                        $section_exists    = $db->get_section_exists(array($term_name),array($section_name));
                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }elseif ($db->find_by_sql("subject","subject_list","term='$term_name' AND section='$section_name' ","") == 'No Result Found')
                                         {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                         }else{
                                          echo ed_subject($term_name,$section_name);
                                        }
                                    }
                                 }

                         if(isset($_POST['edit_x']))
                         {
                             $selected_subject_id = $_POST['selected_subject_id'];
                             $term_name = $_POST['term_name'];
                             $section_name = $_POST['section_name'];
                             echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                             echo edit_subject($selected_subject_id,"",$term_name,$section_name);
                         }
                         if(isset($_POST['finally_edit']))
                         {
                              $count_selected_subject_id = count($_POST['selected_subject_id']);
                              $selected_subject_id = $_POST['selected_subject_id'];
                              $edited_subject = $_POST['edited_subject_name'];
                              $term_name = $_POST['term_name'];
                              $section_name = $_POST['section_name'];
                              $duplicate_check = $db->duplicate_value_check($edited_subject);
                              foreach($edited_subject as $edited_subject_name_val)
                              {
                                  $check_exist_subject = $db->find_by_sql("subject","subject_list"," subject='$edited_subject_name_val' AND section='$section_name' AND term='$term_name'","");
                                  if($check_exist_subject !== 'No Result Found'){ $exist = $check_exist_subject[0]['subject']; break; }
                              }
                              if(empty($exist) == FALSE){  
                                    echo edit_subject($selected_subject_id,$edited_subject,$term_name,$section_name); echo '<div id="message"><b>Subject : "' .$exist. '" already exist in Term :"'.$term_name.'", Section :"'.$section_name.'"</b></div>';
                              }elseif(empty ($duplicate_check) == FALSE)
                              {
                                   echo edit_subject($selected_subject_id,$edited_subject,$term_name,$section_name);
                                   echo '<div id="message"><b>'.$duplicate_check.'</b></div>';
                              }else{
                                          for($i=0;$i<$count_selected_subject_id;$i++)
                                          {  $update_subject = edit_subject2($edited_subject[$i],$selected_subject_id[$i],$term_name,$section_name); }
                                          if($update_subject){ echo edit_subject($selected_subject_id,$edited_subject,$term_name,$section_name); echo '<div id="message"><b>Given subject name has been updated successfully</b></div>'; }
                                   }
                         }
                         if(isset($_POST['selected_delete_x']))
                         {
                             $count_selected_subject_id = count($_POST['selected_subject_id']);
                             $selected_subject_id = $_POST['selected_subject_id'];
                             $term_name = $_POST['term_name'];
                             $section_name = $_POST['section_name'];
                             for($i=0;$i<$count_selected_subject_id;$i++)
                             { $delete_subject = delete_subject($selected_subject_id[$i],$term_name,$section_name); }
                             if($delete_subject){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected subject and its related data have been deleted successfully</b></div>'; }
                         }
            }
            elseif($_GET['subcat'] == 'Exam-type') 
            {
                            if(strlen($db->get_column_exists()) !== 0)
                              {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                              else{
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo ed_selection_menu3("EDIT & DELETE EXAM-TYPE");
                                    if(isset($_POST['ed_selection_menu3']))
                                    {
                                        $term_name = $_POST['term_name'];
                                        $section_name = $_POST['section_name'];
                                        $subject_name = $_POST['subject_name'];
                                        $section_exists    = $db->get_section_exists(array($term_name),array($section_name));
                                        $subject_exists    = $db->get_subject_exists(array($term_name),array($section_name),array($subject_name));
                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }elseif(empty($subject_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$subject_exists.'</b></div>';
                                        }elseif ($db->find_by_sql("exam_type","exam_type_list","term='$term_name' AND section='$section_name' AND subject='$subject_name' ","") == 'No Result Found')
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                        }else{
                                           echo ed_exam_type($term_name,$section_name,$subject_name);
                                        }
                                    }
                                 }

                         if(isset($_POST['edit_x']))
                         {
                             $selected_exam_type_id = $_POST['selected_exam_type_id'];
                             $term_name = $_POST['term_name'];
                             $section_name = $_POST['section_name'];
                             $subject_name = $_POST['subject_name'];
                             echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                             echo edit_exam_type($selected_exam_type_id,"",$term_name,$section_name,$subject_name);
                         }
                         if(isset($_POST['finally_edit']))
                         {
                              $count_selected_exam_type_id = count($_POST['selected_exam_type_id']);
                              $selected_exam_type_id = $_POST['selected_exam_type_id'];
                              $edited_exam_type = $_POST['edited_exam_type_name'];
                              $term_name = $_POST['term_name'];
                              $section_name = $_POST['section_name'];
                              $subject_name = $_POST['subject_name'];
                              $duplicate_check = $db->duplicate_value_check($edited_exam_type);
                              foreach($edited_exam_type as $edited_exam_type_name_val)
                              {
                                  $check_exist_exam_type = $db->find_by_sql("exam_type","exam_type_list"," exam_type='$edited_exam_type_name_val' AND subject='$subject_name' AND section='$section_name' AND term='$term_name'","");
                                  if($check_exist_exam_type !== 'No Result Found'){ $exist = $check_exist_exam_type[0]['exam_type']; break; }
                              }
                              if(empty($exist) == FALSE){  
                                    echo edit_exam_type($selected_exam_type_id,$edited_exam_type,$term_name,$section_name,$subject_name); echo '<div id="message"><b>Exam-type : "' .$exist. '" already exist in Term :"'.$term_name.'", Section :"'.$section_name.'", Subject :"'.$subject_name.'"</b></div>';
                              }elseif(empty ($duplicate_check) == FALSE)
                              {
                                   echo edit_exam_type($selected_exam_type_id,$edited_exam_type,$term_name,$section_name,$subject_name);
                                   echo '<div id="message"><b>'.$duplicate_check.'</b></div>';
                              }else{
                                          for($i=0;$i<$count_selected_exam_type_id;$i++)
                                          {  $update_exam_type = edit_exam_type2($edited_exam_type[$i],$selected_exam_type_id[$i],$term_name,$section_name,$subject_name); }
                                          if($update_exam_type){ echo edit_exam_type($selected_exam_type_id,$edited_exam_type,$term_name,$section_name,$subject_name); echo '<div id="message"><b>Given exam-type name has been updated successfully</b></div>'; }
                                   }
                         }
                         if(isset($_POST['selected_delete_x']))
                         {
                             $count_selected_exam_type_id = count($_POST['selected_exam_type_id']);
                             $selected_exam_type_id = $_POST['selected_exam_type_id'];
                             $term_name = $_POST['term_name'];
                             $section_name = $_POST['section_name'];
                             $subject_name = $_POST['subject_name'];
                             for($i=0;$i<$count_selected_exam_type_id;$i++)
                             { $delete_exam_type = delete_exam_type($selected_exam_type_id[$i],$term_name,$section_name,$subject_name); }
                             if($delete_exam_type){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected exam-type and its related data have been deleted successfully</b></div>'; }
                         }
            }
            elseif($_GET['subcat'] == 'Grade')
            {
                             if(strlen($db->get_column_exists()) !== 0)
                              {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                              else{
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo ed_selection_menu3("EDIT & DELETE GRADE");
                                    if(isset($_POST['ed_selection_menu3']))
                                    {
                                        $term_name = $_POST['term_name'];
                                        $section_name = $_POST['section_name'];
                                        $subject_name = $_POST['subject_name'];
                                        $section_exists    = $db->get_section_exists(array($term_name),array($section_name));
                                        $subject_exists    = $db->get_subject_exists(array($term_name),array($section_name),array($subject_name));
                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }elseif(empty($subject_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$subject_exists.'</b></div>';
                                        }elseif ($db->find_by_sql("num_from,num_to,grade,point","grade_list","term='$term_name' AND section='$section_name' AND subject='$subject_name' ","") == 'No Result Found')
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                        }else{
                                           echo ed_grade($term_name,$section_name,$subject_name);
                                        }
                                    }
                                 }

                         if(isset($_POST['edit_x']))
                         {
                             $selected_grade_id = $_POST['selected_grade_id'];
                             $term_name = $_POST['term_name'];
                             $section_name = $_POST['section_name'];
                             $subject_name = $_POST['subject_name'];
                             echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                             echo edit_grade($selected_grade_id,"",$term_name,$section_name,$subject_name);
                         }
                         if(isset($_POST['finally_edit']))
                         {    
                              $selected_grade_id = $_POST['selected_grade_id'];
                              $count_grade = count($_POST['selected_grade_id']);
                              $edited_num_from = $_POST['edited_num_from'];
                              $edited_num_to = $_POST['edited_num_to'];
                              $edited_grade  = $_POST['edited_grade'];
                              $edited_point  = $_POST['edited_point'];
                              $edited_grade_name = array($edited_num_from,$edited_num_to,$edited_grade,$edited_point);
                              $term_name = $_POST['term_name'];
                              $section_name = $_POST['section_name'];
                              $subject_name = $_POST['subject_name'];
                              $validation = validate_grade_field($edited_num_from,$edited_num_to,$edited_grade,$edited_point);
                             
                              for($i=0;$i<$count_grade;$i++)
                              {
                                  $check_exist_grade = $db->find_by_sql("num_from,num_to,grade,point","grade_list"," num_from='$edited_num_from[$i]' AND num_to='$edited_num_to[$i]' AND grade='$edited_grade[$i]' AND point='$edited_point[$i]' AND subject='$subject_name' AND section='$section_name' AND term='$term_name'","");
                                  if($check_exist_grade !== 'No Result Found'){ $exist = 'Num From='.$edited_num_from[$i].' or Num To='.$edited_num_to[$i].' or Grade='.$edited_grade[$i].' or Point='.$edited_point[$i]; break; }
                              }
                              if(empty($exist) == FALSE){  
                                  echo edit_grade($selected_grade_id,$edited_grade_name,$term_name,$section_name,$subject_name); echo '<div id="message"><b>' .$exist. ' already exist in Term :"'.$term_name.'", Section :"'.$section_name.'", Subject :"'.$subject_name.'"</b></div>';
                              }elseif (empty($validation) == FALSE) 
                              {
                                  echo edit_grade($selected_grade_id,$edited_grade_name,$term_name,$section_name,$subject_name); echo '<div id="message"><b>Invalid Format! duplicate range value found ('.$validation.')</b></div>';
                              }else{
                                       $update_grade = edit_grade2($count_grade,$edited_grade_name,$selected_grade_id,$term_name,$section_name,$subject_name);
                                       if($update_grade){ echo edit_grade($selected_grade_id,$edited_grade_name,$term_name,$section_name,$subject_name); echo '<div id="message"><b>Given grade formats have been updated successfully</b></div>'; }
                                   }
                         }
                         if(isset($_POST['selected_delete_x']))
                         {
                             $selected_grade_id = $_POST['selected_grade_id'];
                             $count_selected_grade = count($_POST['selected_grade_id']);
                             $term_name = $_POST['term_name'];
                             $section_name = $_POST['section_name'];
                             $subject_name = $_POST['subject_name'];
                             for($i=0;$i<$count_selected_grade;$i++)
                             { $delete_grade = delete_grade($selected_grade_id[$i],$term_name,$section_name,$subject_name); }
                             if($delete_grade){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected grade format have been deleted successfully</b></div>'; }
                         }
            }
            elseif($_GET['subcat'] == 'Pass Mark') 
            {
                         if(strlen($db->get_column_exists()) !== 0)
                              {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                              else{
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo ed_selection_menu3("EDIT & DELETE PASS MARK");
                                    if(isset($_POST['ed_selection_menu3']))
                                    {
                                        $term_name = $_POST['term_name'];
                                        $section_name = $_POST['section_name'];
                                        $subject_name = $_POST['subject_name'];
                                        $section_exists    = $db->get_section_exists(array($term_name),array($section_name));
                                        $subject_exists    = $db->get_subject_exists(array($term_name),array($section_name),array($subject_name));
                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }elseif(empty($subject_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$subject_exists.'</b></div>';
                                        }elseif ($db->find_by_sql("mark","pass_mark_list","term='$term_name' AND section='$section_name' AND subject='$subject_name' ","") == 'No Result Found')
                                         {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                         }else{
                                           echo ed_pass_mark($term_name,$section_name,$subject_name);
                                         }
                                    }
                                 }

                         if(isset($_POST['edit_x']))
                         {
                             $selected_pass_mark_id = $_POST['selected_pass_mark_id'];
                             $term_name = $_POST['term_name'];
                             $section_name = $_POST['section_name'];
                             $subject_name = $_POST['subject_name'];
                             echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                             echo edit_pass_mark($selected_pass_mark_id,"",$term_name,$section_name,$subject_name);
                         }
                         if(isset($_POST['finally_edit']))
                         {
                              $selected_pass_mark_id = $_POST['selected_pass_mark_id'];
                              $edited_pass_mark = $_POST['edited_pass_mark_name'];
                              $term_name = $_POST['term_name'];
                              $section_name = $_POST['section_name'];
                              $subject_name = $_POST['subject_name'];
                              
                              $check_exist_pass_mark = $db->find_by_sql("mark","pass_mark_list"," mark='$edited_pass_mark' AND subject='$subject_name' AND section='$section_name' AND term='$term_name'","");
                              if($check_exist_pass_mark !== 'No Result Found'){ $exist = $check_exist_pass_mark[0]['mark']; }
                             
                              if(empty($exist) == FALSE){  
                                    echo edit_pass_mark($selected_pass_mark_id,$edited_pass_mark,$term_name,$section_name,$subject_name); echo '<div id="message"><b>Pass-mark : "' .$exist. '" already exist in Term :"'.$term_name.'", Section :"'.$section_name.'", Subject :"'.$subject_name.'"</b></div>';
                              }else{
                                          $update_pass_mark = edit_pass_mark2($edited_pass_mark,$selected_pass_mark_id,$term_name,$section_name,$subject_name); 
                                          if($update_pass_mark){ echo edit_pass_mark($selected_pass_mark_id,$edited_pass_mark,$term_name,$section_name,$subject_name); echo '<div id="message"><b>Given pass-mark has been updated successfully</b></div>'; }
                                   }
                         }
                         if(isset($_POST['selected_delete_x']))
                         {
                             $selected_pass_mark_id = $_POST['selected_pass_mark_id'];
                             $term_name = $_POST['term_name'];
                             $section_name = $_POST['section_name'];
                             $subject_name = $_POST['subject_name'];
                             $delete_pass_mark = delete_pass_mark($selected_pass_mark_id,$term_name,$section_name,$subject_name);
                             if($delete_pass_mark){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected pass-mark has been deleted successfully</b></div>'; }
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
