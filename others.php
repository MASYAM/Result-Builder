<?php
require 'authenticate.php';
require 'includes/functions.php';
require 'includes/functions2.php';
require 'includes/functions5.php';
require_once('includes/MySqlDb.php');
$available_terms     = $db->find_by_sql("*","terms  ORDER BY term ASC","","");
$available_section   = $db->find_by_sql("DISTINCT section","section_list  ORDER BY section ASC","","");
$available_subject   = $db->find_by_sql("DISTINCT subject","subject_list  ORDER BY subject ASC","","");
$available_exam_type = $db->find_by_sql("DISTINCT exam_type","exam_type_list  ORDER BY exam_type ASC","","");
?>


<?php require 'includes/header.php';   ?>

      <div id="main_content">
  <?php
 if($category == 'Others')
 {
            if($_GET['subcat'] == 'Insert Discipline and Roll') 
            {
                      if(!isset($_POST['submit']))
                       {
                            if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  echo disp_selection_menu($selected_term,$selected_section,$selected_disp_type);
                               }
                       }
                        if(isset($_POST['submit']))
                        {
                                $section_name[]  = $_POST['section_name'];
                                $term_name[]     = $_POST['term_name'];
                                $disp[]          = $_POST['disp'];
                                 
                                if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms' || $disp[0] == 'disp')
                                {
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo disp_selection_menu($selected_term,$selected_section,$selected_disp_type);
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections and discipline</b></div>';                 
                                }else{
                                        $section_exists    = $db->get_section_exists($term_name,$section_name);
                                        $selected_term     = $db->set_checked($term_name,"selected");
                                        $selected_section  = $db->set_checked($section_name,"selected");
                                        $selected_disp_type = $db->set_checked($disp,"selected");
                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo disp_selection_menu($selected_term,$selected_section,$selected_disp_type);
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }else{
                                               echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                               echo disp_selection_menu($selected_term,$selected_section,$selected_disp_type);
                                               echo get_insert_disp($section_name,$term_name,$disp);
                                             }
                                    }
                        }
                        if(isset($_POST['insert_disp']))
                        {  $student_id    = $_POST['student_id'];
                           $disp_value    = $_POST['disp_value'];
                           $section_name  = $_POST['section_name'];
                           $term_name     = $_POST['term_name'];
                           $disp          = $_POST['disp'];
                           $disp_table    = $_POST['disp_table'];
                           
                          if($disp_table == 'working_days')
                          {
                             $student_id = $db->find_by_sql("student_id","discipline_n_roll","term='$term_name' AND section='$section_name'","");
                              foreach ($student_id as $value)
                              { $result = $db->update("discipline_n_roll","$disp_table = '$disp_value'","student_id='{$value['student_id']}' AND term='$term_name' AND section='$section_name' ");}
                            
                          }elseif($disp_table == 'class_roll')
                          {
                                $belong_term = $db->find_by_sql("term","section_list","section='$section_name'","");
                                foreach ($belong_term as $belong_term_val){ $term_name_array[] = $belong_term_val['term'];}
                                $count_term = count($term_name_array);
                                for($i=0;$i<$count_term;$i++)
                                {
                                    foreach ($student_id as $key => $value)
                                    { $result = $db->update("discipline_n_roll","$disp_table = '$disp_value[$key]'","student_id='$value' AND term='{$term_name_array[$i]}' AND section='$section_name' "); }
                                }
                                $term_name = 'all term';
                          }else{
                              foreach ($student_id as $key => $value)
                              { $result = $db->update("discipline_n_roll","$disp_table = '$disp_value[$key]'","student_id='$value' AND term='$term_name' AND section='$section_name' "); }
                          }
                          if($result){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Given '.$disp.' number has been inserted successfully for Term :"'.$term_name.'", Section :"'.$section_name.'"</b></div>'; }
                        }
            }            
            elseif($_GET['subcat'] == 'Insert Class and Shift Name') 
            {
                      
                        if(strlen($db->get_column_exists()) !== 0)
                        {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                        else{
                              echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                              echo class_or_shift_selection_menu();
                           }
                       
                        if(isset($_POST['cs']))
                        {
                                $term_name       = $_POST['term_name'];
                                $cs              = $_POST['cs'];
                                 
                                if( $term_name == 'Available Terms' || $cs == 'cs')
                                {
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and class or shift name</b></div>';                 
                                }else{
                                         echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                         echo get_insert_cs($term_name,$cs);
                                    }
                        }
                        if(isset($_POST['insert_cs']))
                        {  
                           $cs_value      = $_POST['cs_value'];
                           $section       = $_POST['section'];
                           $term_name     = $_POST['term_name'];
                           $cs_name       = $_POST['cs_name'];
                           $cs_table      = $_POST['cs_table'];
                         
                            foreach ($section as $key => $value)
                            { $result = $db->update("class_n_shift_name","$cs_table = '$cs_value[$key]'"," term='$term_name' AND section='$value' "); }
                          
                            if($result){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Given '.$cs_name.' has been inserted successfully for Term :"'.$term_name.'"</b></div>'; }
                        }
            }
            elseif($_GET['subcat'] == 'Set Subject Order') 
            {
                      
                        if(strlen($db->get_column_exists()) !== 0)
                        {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                        else{
                              echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                              echo selection_menu6("SET SUBJECT ORDER");
                           }
                       
                        if(isset($_POST['section_name']))
                        {
                                $section_name       = $_POST['section_name'];
                                $belong_subject = $db->find_by_sql("DISTINCT subject,priority_order","subject_list","section='$section_name' ORDER BY LENGTH(priority_order),priority_order","");
                                if($belong_subject == 'No Result Found')
                                {
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo '<div id="message">No Result Found</div>';
                                }else{
                                   echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                   echo get_set_subject_order($section_name,$belong_subject);
                                }

                        }
                        if(isset($_POST['insert_order']))
                        {  
                           $subject       = $_POST['subject'];
                           $order         = $_POST['order'];
                         
                            foreach ($subject as $key => $value)
                            { 
                                $result  = $db->update("subject_list","priority_order = '$order[$key]'"," subject='$value' "); 
                                $result2 = $db->update("add_n_grade","priority_order = '$order[$key]'"," subject='$value' ");
                                $result3 = $db->update("percent_add_n_grade","priority_order = '$order[$key]'"," subject='$value' ");
                                $result4 = $db->update("total_add","priority_order = '$order[$key]'"," subject='$value' ");
                            }
                          
                            if($result){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Given order has been inserted successfully</b></div>'; }
                        }
            }
            elseif($_GET['subcat'] == 'Set Full Mark') 
            {
                       if(!isset($_POST['submit']))
                       {
                            if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  echo full_mark_selection_menu($selected_term,$selected_section,$selected_subject);
                               }
                       }
                       
                        if(isset($_POST['submit']))
                        {
                                $term_name[]       = $_POST['term_name'];
                                $section_name[]    = $_POST['section_name'];
                                $subject_name[]    = $_POST['subject_name'];
                                if( $term_name[0] == 'Available Terms' || $section_name[0] == 'Available Sections' || $subject_name[0] == 'Available Subjects')
                                {
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    echo full_mark_selection_menu($selected_term,$selected_section,$selected_subject);
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections and subjects</b></div>';                 
                                }else{
                                        $section_exists   = $db->get_section_exists($term_name,$section_name);
                                        $subject_exists   = $db->get_subject_exists($term_name,$section_name,$subject_name);
                                        $selected_term    = $db->set_checked($term_name,"selected");
                                        $selected_section = $db->set_checked($section_name,"selected");
                                        $selected_subject = $db->set_checked($subject_name,"selected");
                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo full_mark_selection_menu($selected_term,$selected_section,$selected_subject);
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }elseif(empty($subject_exists) == FALSE)
                                        {
                                           echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                           echo full_mark_selection_menu($selected_term,$selected_section,$selected_subject);
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$subject_exists.'</b></div>';                                                   
                                        }else{
                                            echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                            echo full_mark_selection_menu($selected_term,$selected_section,$selected_subject);
                                            echo get_insert_full_mark($term_name,$section_name,$subject_name);
                                        }
                                    }
                        }
                        if(isset($_POST['insert_full_mark']))
                        {  
                           $full_mark_value  = $_POST['full_mark_value'];
                           $exam_type     = $_POST['exam_type'];
                           $section_name  = $_POST['section_name'];
                           $term_name     = $_POST['term_name'];
                           $subject_name  = $_POST['subject_name'];
                         
                            foreach ($exam_type as $key => $value)
                            { $result = $db->update("exam_type_list","full_mark = '$full_mark_value[$key]'"," term='$term_name' AND section='$section_name' AND subject='$subject_name' AND exam_type='$value' "); }
                          
                            if($result){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Given full mark has been inserted successfully for Term :"'.$term_name.'", Section :"'.$section_name.'", Subject :"'.$subject_name.'"</b></div>'; }
                        }   
            }
            elseif($_GET['subcat'] == 'Set % Aggr.') 
            {
                        if(strlen($db->get_column_exists()) !== 0)
                        {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                        else{
                              echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                              echo selection_menu6("SET % AGGREGATE");
                           }
                       
                        if(isset($_POST['section_name']))
                        {
                            $update_permission = $db->update("account_option","permission = 'Yes'"," item='insert_aggr_reload' ");
                            $section_name    = $_POST['section_name'];
                            $result = $db->find_by_sql("percent_agre,percentage,base_term","aggregate_percent_term","section='$section_name' ORDER BY percent_term_priority","");
                            if($result !== 'No Result Found')
                            {
                                echo field_of_update_percent_aggregate($result,$section_name);
                            }else{
                                echo field_of_added_percent_aggregate($section_name);
                            }
                        }
                        if(isset($_POST['next']))
                        {
                            $percent_agre  = $_POST['percent_agre'];
                            $base_term  = $_POST['base_term'];
                            $section       = $_POST['section'];
                            if(empty($percent_agre) == TRUE || empty($base_term) == True)
                            {
                                echo field_of_added_percent_aggregate($section);
                                echo '<div style="margin:auto;width: 500px;height:20px;"></div><div id="message"><b>Please select any added terms and aggregate term</b></div>';
                            }else{
                               echo field_of_set_added_percent_aggregate($section,$base_term,$percent_agre,"");
                            }
                        }
                        if(isset($_POST['insert_new']))
                        {
                            $percent_agre  = $_POST['percent_agre'];
                            $base_term     = $_POST['base_term'];
                            $priority      = $_POST['priority'];
                            $percent       = $_POST['percent'];
                            $section       = $_POST['section'];
                            foreach ($percent as $value) 
                            {
                                if(!is_numeric($value))
                                {
                                    echo field_of_set_added_percent_aggregate($section,$base_term,$percent_agre,$percent);
                                    echo '<div style="margin:auto;width: 500px;height:20px;"></div><div id="message"><b>Invalid value: "'.$value.'" found (Only number allow)</b></div>';
                                    $error = 'error';
                                    break;
                                }
                            }
                            
                                $permission = $db->find_by_sql("permission","account_option","item='insert_aggr_reload'","");
                                 if($error !== 'error' && $permission[0]['permission'] == 'Yes')
                                 {
                                     foreach ($percent_agre as $key => $value)
                                     { $result = $db->insert("aggregate_percent_term","base_term,section,percent_agre,percentage,percent_term_priority","'$base_term','$section','$value','$percent[$key]','$priority[$key]'","id='115122fau'"); }

                                     if($result){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Given percentage has been inserted successfully for Section :"'.$section.'"</b></div>'; }
                                 }
                                $update_permission = $db->update("account_option","permission = 'No'"," item='insert_aggr_reload' ");
                        }
                        
                        if(isset($_POST['update']))
                        {  
                           $percentage    = $_POST['percentage'];
                           $percent_agre  = $_POST['percent_agre'];
                           $section       = $_POST['section'];
                         
                            foreach ($percent_agre as $key => $value)
                            { $result = $db->update("aggregate_percent_term","percentage = '$percentage[$key]'"," section='$section' AND percent_agre='$value' "); }
                          
                            if($result){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Given percentage has been updated successfully for Section :"'.$section.'"</b></div>'; }
                        }   
                        if(isset($_POST['delete']))
                        {
                            $section = $_POST['section'];
                            $delete  = $db->delete("aggregate_percent_term","section='$section'");
                            if($delete){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Percentage aggregate has been deleted for section :"'.$section.'"</b></div>'; }
                        }
            } 
            elseif($_GET['subcat'] == 'Set Total Number Aggr.') 
            {
                       if(strlen($db->get_column_exists()) !== 0)
                        {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                        else{
                              echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                              echo selection_menu6("SET TOTAL NUMBER AGGREGATE");
                           }
                       
                        if(isset($_POST['section_name']))
                        {
                            $update_permission = $db->update("account_option","permission = 'Yes'"," item='insert_total_aggr_reload' ");
                            $section_name    = $_POST['section_name'];
                            $result = $db->find_by_sql("total_agre,base_term","aggregate_total_term","section='$section_name' ORDER BY total_term_priority","");
                            if($result !== 'No Result Found')
                            {
                                echo field_of_delete_total_aggregate($result,$section_name);
                            }else{
                                echo field_of_added_total_aggregate($section_name);
                            }
                        }
                        
                        if(isset($_POST['insert']))
                        {
                            $total_agre  = $_POST['total_agre'];
                            $base_term     = $_POST['base_term'];
                            $priority      = $_POST['priority'];
                            $section       = $_POST['section'];
                            
                                if(empty($total_agre) == TRUE || empty($base_term) == True)
                                {
                                    echo field_of_added_total_aggregate($section);
                                    echo '<div style="margin:auto;width: 500px;height:20px;"></div><div id="message"><b>Please select any added terms and aggregate term</b></div>';
                                }else{
                            
                                            $permission = $db->find_by_sql("permission","account_option","item='insert_total_aggr_reload'","");
                                             if($permission[0]['permission'] == 'Yes')
                                             {
                                                 foreach ($total_agre as $key => $value)
                                                 { $result = $db->insert("aggregate_total_term","base_term,section,total_agre,total_term_priority","'$base_term','$section','$value','$priority[$key]'","id='115122fau'"); }

                                                 if($result){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Given total number aggregate has been inserted successfully for Section :"'.$section.'"</b></div>'; }
                                             }
                                            $update_permission = $db->update("account_option","permission = 'No'"," item='insert_total_aggr_reload' ");
                                    }
                        }
                        
                        if(isset($_POST['delete']))
                        {
                            $section = $_POST['section'];
                            $delete  = $db->delete("aggregate_total_term","section='$section'");
                            if($delete){  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Total number aggregate has been deleted for section :"'.$section.'"</b></div>'; }
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

