<?php
require 'authenticate.php';
require 'includes/functions.php';
require 'includes/functions2.php';
require 'includes/functions6.php';
require_once('includes/MySqlDb.php');
$available_terms     = $db->find_by_sql("*","terms  ORDER BY term ASC","","");
$available_section   = $db->find_by_sql("DISTINCT section","section_list  ORDER BY section ASC","","");
$available_subject   = $db->find_by_sql("DISTINCT subject","subject_list  ORDER BY subject ASC","","");
$available_exam_type = $db->find_by_sql("DISTINCT exam_type","exam_type_list  ORDER BY exam_type ASC","","");
?>


<?php require 'includes/header.php';   ?>

      <div id="main_content">
  <?php
 if($category == 'Student Account')
 {
            if($_GET['subcat'] == 'Save Result') 
            {
                         if(!isset($_POST['submit']))
                         {
                           if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  if($db->find_by_sql("student_id","student_account","","") == 'No Result Found')
                                  {echo '<div style="padding: 20px;margin:auto;width: 500px;height:20px;"></div><div id="message"><b>No Result Found</b></div>'; }
                                  else{
                                      echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                      echo save_result_menu($selected_term,$selected_rc,$selected_rt);
                                  }
                               }
                         }
                         if(isset($_POST['submit']))
                         {
                             $year = $_POST['year'];
                             $term_name = $_POST['term_name'];
                             $rc_name = $_POST['rc_name'];
                             $rt_name = $_POST['rt_name'];
                             if($term_name == 'Available Terms' || $rc_name == 'Result Category' || $rt_name == 'Result Type')
                             {
                                 echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                 echo save_result_menu($selected_term,$selected_rc,$selected_rt);
                                 echo '<div style="padding: 20px;margin:auto;width: 500px;height:20px;"></div><div id="message"><b>Please choose any available terms and result category and result type</b></div>';
                             }else{
                                    $selected_term     = $db->set_checked(array($term_name),"selected");
                                    $selected_rc       = $db->set_checked(array($rc_name),"selected");
                                    $selected_rt       = $db->set_checked(array($rt_name),"selected");
                                    $check_exist_rt    = $db->get_rt_exist($rc_name,$rt_name);
                                    if(empty($check_exist_rt) == FALSE)
                                    {
                                        echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                        echo save_result_menu($selected_term,$selected_rc,$selected_rt);
                                        echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$check_exist_rt.'</b></div>';
                                    }elseif($rc_name == 'Distinct')
                                    {
                                        if($db->find_by_sql("*","add_n_grade","term='$term_name'","") == 'No Result Found')
                                        {
                                            echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                            echo save_result_menu($selected_term,$selected_rc,$selected_rt);
                                            echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                        }else{
                                           $save = distinct($year,$term_name,$rc_name,$rt_name);
                                        }
                                    }
                                    elseif($rc_name == '% Number Aggregate')
                                    {
                                        if($db->find_by_sql("*","percent_add_n_grade","term='$term_name'","") == 'No Result Found')
                                        {
                                            echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                            echo save_result_menu($selected_term,$selected_rc,$selected_rt);
                                            echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                        }else{
                                           $save = percent_aggregate($year,$term_name,$rc_name,$rt_name); 
                                        }
                                    }
                                    elseif($rc_name == 'Total Number Aggregate')
                                    { 
                                       if($db->find_by_sql("*","total_add","term='$term_name'","") == 'No Result Found')
                                        {
                                            echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                            echo save_result_menu($selected_term,$selected_rc,$selected_rt);
                                            echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                        }else{
                                           $save = total_aggregate($year,$term_name,$rc_name,$rt_name); 
                                        }
                                    }
                                    if(empty($save) == FALSE)
                                    {
                                        echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                        echo save_result_menu("","","");
                                        echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$save.'</b></div>';
                                    }
                             }
                         }
            }
            elseif($_GET['subcat'] == 'Insert Information')
            {
                
            }
            elseif($_GET['subcat'] == 'Account Option') 
            {
                
            }
            elseif($_GET['subcat'] == 'Create Account') 
            {
                         if(!isset($_POST['edited_pass']))
                         {
                           if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  if($db->find_by_sql("student_id","student_account","","") == 'No Result Found')
                                  {echo '<div style="padding: 20px;margin:auto;width: 500px;height:20px;"></div><div id="message"><b>No Result Found</b></div>'; }
                                  else{echo get_create_account($password);}
                               }
                         }
                            if(isset($_POST['edited_pass']))
                            { 
                                    $count_username = count($_POST['username']);
                                    $username       = $_POST['username'];
                                    $password       = $_POST['edited_pass'];
                                    for ($i=0;$i<$count_username;$i++)
                                    {
                                       if(empty($password[$i]) == FALSE)
                                       {
                                           $check_strength = checkPassword(trim($db->escape_value($password[$i])));
                                           if($check_strength == 'Too Short' || $check_strength == 'Weak')
                                           {$prob_user = $username[$i];$prob = $check_strength;break;}
                                       }
                                    }
                                    if($prob == 'Too Short')
                                    { 
                                        echo get_create_account($password);
                                        echo '<div style="padding: 10px;margin:auto;width: 500px;height:5px;"></div><div id="message"><b>Your given password is Too Short for username :"'.$prob_user.'"<br>Password length must be minimum 8 characters long</b></div>'; 
                                    }elseif($prob == 'Weak')
                                    { 
                                        echo get_create_account($password);
                                        echo '<div style="padding: 10px;margin:auto;width: 500px;height:5px;"></div><div id="message"><b>Your given password is weak for username :"'.$prob_user.'"<br>You have to improve your password strength to continue</b></div>'; 
                                    }else{
                                        get_create_account2($count_username,$username,$password);
                                    }
                            }
            }
            elseif($_GET['subcat'] == 'Delete Account') 
            {
                      if(strlen($db->get_column_exists()) !== 0)
                       {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                      else{
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    if(!isset($_POST['selected_delete_x']))
                                    {
                                        if($db->find_by_sql("student_id","student_account","","") == 'No Result Found')
                                        {echo '<div style="padding: 20px;margin:auto;width: 500px;height:20px;"></div><div id="message"><b>No Result Found</b></div>'; }
                                        else{echo delete_account();}
                                    }
                          }                        
                         if(isset($_POST['selected_delete_x']))
                         {
                             $count_selected_id = count($_POST['selected_id']);
                             $selected_id = $_POST['selected_id'];
                             if($count_selected_id == 0){echo delete_account();  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please select any username</b></div>';}
                             else{
                                 for($i=0;$i<$count_selected_id;$i++)
                                 { $delete_account = delete_account2($selected_id[$i]);}
                                 if($delete_account){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected account and its related data have been deleted successfully</b></div>'; }
                             }
                         }
            }
            elseif($_GET['subcat'] == 'Delete Result Term') 
            {
                      if(strlen($db->get_column_exists()) !== 0)
                       {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                      else{
                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                    if(!isset($_POST['selected_delete_x']))
                                    {
                                        if($db->find_by_sql("student_id","student_account","","") == 'No Result Found')
                                        {echo '<div style="padding: 20px;margin:auto;width: 500px;height:20px;"></div><div id="message"><b>No Result Found</b></div>'; }
                                        else{echo delete_account();}
                                    }
                          }                        
                         if(isset($_POST['selected_delete_x']))
                         {
                             $count_selected_id = count($_POST['selected_id']);
                             $selected_id = $_POST['selected_id'];
                             if($count_selected_id == 0){echo delete_account();  echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please select any username</b></div>';}
                             else{
                                 for($i=0;$i<$count_selected_id;$i++)
                                 { $delete_account = delete_account2($selected_id[$i]);}
                                 if($delete_account){ echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Selected account and its related data have been deleted successfully</b></div>'; }
                             }
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
