<?php
require 'authenticate.php';
require 'includes/functions.php';
require 'includes/functions2.php';
require_once('includes/MySqlDb.php');
$available_terms     = $db->find_by_sql("*","terms  ORDER BY term ASC","","");
$available_section   = $db->find_by_sql("DISTINCT section","section_list  ORDER BY section ASC","","");
$available_subject   = $db->find_by_sql("DISTINCT subject","subject_list  ORDER BY subject ASC","","");
$available_exam_type = $db->find_by_sql("DISTINCT exam_type","exam_type_list  ORDER BY exam_type ASC","","");
?>

<?php require 'includes/header.php';   ?>

      <div id="main_content">
<?php
if($category == 'Create Exam-profile')
{
             if($_GET['subcat'] == 'Term') 
             {
                            if(isset($_POST['submit']) && isset($_POST['term']))
                            {
                                 $term = $db->escape_value($_POST['term']);
                                 $result = $db->insert("terms","term","'$term'","term='$term'");
                                 if(($result == 'already exist') || ($result == 'not been created'))
                                 {
                                   echo get_create_term($term);
                                   echo $db->get_message("1","exam term",$result);
                                 }else{
                                       echo get_create_term("");
                                       echo $db->get_message("1","exam term",$result);
                                      }
                            }else{
                                  echo get_create_term("");
                                 }  
             }
//Start Create Section            
             elseif($_GET['subcat'] == 'Section') 
             {
                        if(!isset($_POST['submit']))
                        {
                            if($db->find_by_sql("*","terms","","") == 'No Result Found')
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                   <div id="message" ><b>No Exam Term Found</b></div>';}
                            else{
                                  echo get_cre_num_of_section();
                                }
                        }
                        if(isset($_POST['numOfSec']))
                        {
                            $numOfSec = $_POST['numOfSec'];
                            $fourth_subject = $_POST['fourth_subject'];
                            echo get_create_section($numOfSec,$fourth_subject,"","");
                        }
                        if(isset($_POST['section_name']))
                        {
                                $count_section = count($_POST['section_name']);
                                $count_term    = count($_POST['term_name']);
                                $section_name  = $_POST['section_name'];
                                $term_name     = $_POST['term_name'];
                                $fourth_subject = $_POST['fourth_subject'];
                                $duplicate_check = $db->duplicate_value_check($section_name);
                                if($count_term == 0)
                                {
                                    echo get_create_section($count_section,$fourth_subject,$section_name,"" );
                                    echo '<div id="message"><b>Please choose any available terms</b></div>';
                                }else{
                                       $selected_term    = $db->set_checked($term_name,"checked");
                                       if(empty ($duplicate_check) == FALSE){
                                       echo get_create_section($count_section,$fourth_subject,$section_name,"" );
                                       echo '<div id="message"><b>'.$duplicate_check.' in section field</b></div>'; 
                                       }else{
                                         get_create_section2($count_section,$count_term,$section_name,$term_name,$fourth_subject,$selected_term);
                                        }
                                     }
                        }
                    
              }
//Start Create Subject                 
             elseif($_GET['subcat'] == 'Subject') 
             {                          
                            if(!isset($_POST['submit']))
                            {
                                if($db->find_by_sql("*","section_list","","") == 'No Result Found')
                                {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                       <div id="message" ><b>No Section Found</b></div>';}
                                else{
                                      echo get_cre_num_of_subject();
                                    }
                            }
                            if(isset($_POST['numOfSub']))
                            {
                                $numOfSub = $_POST['numOfSub'];
                                echo get_create_subject($numOfSub,"","","");
                            }
                            if(isset($_POST['subject_name']))
                            {
                                    $count_section = count($_POST['section_name']);
                                    $count_term    = count($_POST['term_name']);
                                    $count_subject = count($_POST['subject_name']);
                                    $section_name  = $_POST['section_name'];
                                    $term_name     = $_POST['term_name'];
                                    $subject_name  = $_POST['subject_name'];
                                    $duplicate_check = $db->duplicate_value_check($subject_name);
                                    if($count_term == 0 || $count_section == 0)
                                    {
                                        echo get_create_subject($count_subject,$subject_name,"","");
                                        echo '<div id="message"><b>Please choose any available terms and sections</b></div>';                 
                                    }else{
                                            $section_exists   = $db->get_section_exists($term_name,$section_name);
                                            $selected_term    = $db->set_checked($term_name,"checked");
                                            $selected_section = $db->set_checked($section_name,"checked");
                                            if(empty($section_exists) == FALSE)
                                            {
                                               echo get_create_subject($count_subject,$subject_name,$selected_term,$selected_section);
                                               echo '<div id="message"><b>'.$section_exists.'</b></div>';

                                            }elseif(empty ($duplicate_check) == FALSE)
                                            {
                                                echo get_create_subject($count_subject,$subject_name,$selected_term,$selected_section);
                                                echo '<div id="message"><b>'.$duplicate_check.' in subject field</b></div>'; 
                                            }else{
                                                   get_create_subject2($count_section,$count_term,$count_subject,$section_name,$term_name,$subject_name,$selected_term,$selected_section);
                                                }
                                        }
                            }

             }
//Start Create Exam-type             
             elseif($_GET['subcat'] == 'Exam-type') 
             {
                            if(!isset($_POST['submit']))
                            {
                                if($db->find_by_sql("*","subject_list","","") == 'No Result Found')
                                {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                       <div id="message" ><b>No Subject Found</b></div>';}
                                else{
                                     echo get_cre_num_of_exmtype();
                                    }
                            }
                            if(isset($_POST['numOfExmtype']))
                            {
                                $numOfExmtype = $_POST['numOfExmtype'];
                                echo get_create_exmtype($numOfExmtype,"","","","");
                            }
                            if(isset($_POST['exmtype_name']))
                            {
                                    $count_section = count($_POST['section_name']);
                                    $count_term    = count($_POST['term_name']);
                                    $count_subject = count($_POST['subject_name']);
                                    $count_exmtype = count($_POST['exmtype_name']);
                                    $section_name  = $_POST['section_name'];
                                    $term_name     = $_POST['term_name'];
                                    $subject_name  = $_POST['subject_name'];
                                    $exmtype_name  = $_POST['exmtype_name'];
                                    $duplicate_check = $db->duplicate_value_check($exmtype_name);
                                    if($count_term == 0 || $count_section == 0 || $count_subject == 0)
                                     {
                                        echo get_create_exmtype($count_exmtype,$exmtype_name,"","","");
                                        echo '<div id="message"><b>Please choose any available terms and sections and subjects</b></div>';
                                      }else{                          
                                                $section_exists   = $db->get_section_exists($term_name,$section_name);
                                                $subject_exists   = $db->get_subject_exists($term_name,$section_name,$subject_name);
                                                $selected_term    = $db->set_checked($term_name,"checked");
                                                $selected_section = $db->set_checked($section_name,"checked");
                                                $selected_subject = $db->set_checked($subject_name,"checked");
                                                if(empty($section_exists) == FALSE)
                                                {
                                                   echo get_create_exmtype($count_exmtype,$exmtype_name,$selected_term,$selected_section,$selected_subject);
                                                   echo '<div id="message"><b>'.$section_exists.'</b></div>';
                                                }elseif(empty($subject_exists) == FALSE)
                                                {
                                                   echo get_create_exmtype($count_exmtype,$exmtype_name,$selected_term,$selected_section,$selected_subject);
                                                   echo '<div id="message"><b>'.$subject_exists.'</b></div>';                                                   
                                                }elseif(empty ($duplicate_check) == FALSE)
                                                {
                                                    echo get_create_exmtype($count_exmtype,$exmtype_name,$selected_term,$selected_section,$selected_subject);
                                                    echo '<div id="message"><b>'.$duplicate_check.' in exam-type field</b></div>'; 
                                                }else{
                                                       get_create_exmtype2($count_section,$count_term,$count_subject,$count_exmtype,$section_name,$term_name,$subject_name,$exmtype_name,$selected_term,$selected_section,$selected_subject);
                                                     }
                                           }
                            }
             }
//Start Assign Grade              
             elseif($_GET['subcat'] == 'Grade') 
             {
                            if(!isset($_POST['submit']))
                            {
                                if($db->find_by_sql("*","subject_list","","") == 'No Result Found')
                                {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                       <div id="message" ><b>No Subject Found</b></div>';}
                                else{
                                      echo get_cre_num_of_grade();
                                    }
                            }
                            if(isset($_POST['numOfgrade']))
                            {
                                $numOfgrade = $_POST['numOfgrade'];
                                echo get_create_grade($numOfgrade,"","","","","","","");
                            }
                            if(isset($_POST['num_from']) && isset($_POST['num_to']))
                            {
                                    $count_section = count($_POST['section_name']);
                                    $count_term    = count($_POST['term_name']);
                                    $count_subject = count($_POST['subject_name']);
                                    $count_num_from = count($_POST['num_from']); // or count_num_to(only need one of them)
                                    $section_name  = $_POST['section_name'];
                                    $term_name     = $_POST['term_name'];
                                    $subject_name  = $_POST['subject_name'];
                                    $num_from      = $_POST['num_from'];
                                    $num_to        = $_POST['num_to'];
                                    $grade         = $_POST['grade'];
                                    $point         = $_POST['point'];
                                    $validation = validate_grade_field($num_from,$num_to,$grade,$point);
                                    if($count_term == 0 || $count_section == 0 || $count_subject == 0)
                                    {
                                        echo get_create_grade($count_num_from,$num_from,$num_to,$grade,$point,$selected_term,$selected_section,$selected_subject);
                                        echo '<div id="message"><b>Please choose any available terms and sections and subjects</b></div>';                 
                                    }else{
                                           $section_exists   = $db->get_section_exists($term_name,$section_name);
                                           $subject_exists   = $db->get_subject_exists($term_name,$section_name,$subject_name);
                                           $selected_term    = $db->set_checked($term_name,"checked");
                                           $selected_section = $db->set_checked($section_name,"checked");
                                           $selected_subject = $db->set_checked($subject_name,"checked");
                                                if(empty($section_exists) == FALSE)
                                                {
                                                   echo get_create_grade($count_num_from,$num_from,$num_to,$grade,$point,$selected_term,$selected_section,$selected_subject);
                                                   echo '<div id="message"><b>'.$section_exists.'</b></div>';
                                                }elseif(empty($subject_exists) == FALSE)
                                                {
                                                   echo get_create_grade($count_num_from,$num_from,$num_to,$grade,$point,$selected_term,$selected_section,$selected_subject);
                                                   echo '<div id="message"><b>'.$subject_exists.'</b></div>';                                                   
                                                }else{
                                                        if(empty($validation) == TRUE)
                                                        {
                                                          get_create_grade2($count_section,$count_term,$count_subject,$count_exmtype,$count_num_from,$section_name,$term_name,$subject_name,$num_from,$num_to,$grade,$point,$selected_term,$selected_section,$selected_subject);
                                                        }else{
                                                                echo get_create_grade($count_num_from,$num_from,$num_to,$grade,$point,$selected_term,$selected_section,$selected_subject);
                                                                echo '<div id="message"><b>Invalid Format! duplicate range value found ('.$validation.')</b></div>';
                                                             }
                                                     }
                                         }

                            }
             }
             elseif($_GET['subcat'] == 'Pass Mark')
             {
                           if(!isset($_POST['pass_mark']))
                            {
                                if($db->find_by_sql("*","subject_list","","") == 'No Result Found')
                                {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                       <div id="message" ><b>No Subject Found</b></div>';}
                                else{
                                      echo get_create_pass_mark("","","","");
                                    }
                            }
                            
                            if(isset($_POST['pass_mark']))
                            {
                                    $count_section = count($_POST['section_name']);
                                    $count_term    = count($_POST['term_name']);
                                    $count_subject = count($_POST['subject_name']);
                                    $section_name  = $_POST['section_name'];
                                    $term_name     = $_POST['term_name'];
                                    $subject_name  = $_POST['subject_name'];
                                    $pass_mark     = $db->escape_value($_POST['pass_mark']);
                                    
                                    if($count_term == 0 || $count_section == 0 || $count_subject == 0)
                                    {
                                        echo get_create_pass_mark($pass_mark,$selected_term,$selected_section,$selected_subject);
                                        echo '<div id="message"><b>Please choose any available terms and sections and subjects</b></div>';                 
                                    }else{
                                           $section_exists   = $db->get_section_exists($term_name,$section_name);
                                           $subject_exists   = $db->get_subject_exists($term_name,$section_name,$subject_name);
                                           $selected_term    = $db->set_checked($term_name,"checked");
                                           $selected_section = $db->set_checked($section_name,"checked");
                                           $selected_subject = $db->set_checked($subject_name,"checked");
                                                if(empty($section_exists) == FALSE)
                                                {
                                                   echo get_create_pass_mark($pass_mark,$selected_term,$selected_section,$selected_subject);
                                                   echo '<div id="message"><b>'.$section_exists.'</b></div>';
                                                }elseif(empty($subject_exists) == FALSE)
                                                {
                                                   echo get_create_pass_mark($pass_mark,$selected_term,$selected_section,$selected_subject);
                                                   echo '<div id="message"><b>'.$subject_exists.'</b></div>';                                                   
                                                }else{
                                                       echo get_create_pass_mark2($count_section,$count_term,$count_subject,$section_name,$term_name,$subject_name,$pass_mark,$selected_term,$selected_section,$selected_subject);
                                                    }
                                         }

                            }
             }
             else{
                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                    <div id="message" ><b>No Page Found</b></div>';
                 }
                  
}else{
       echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
             <div id="message" ><b>No Page Found</b></div>';
     }
?> 
          
      </div>

<?php require 'includes/footer.php';   ?>