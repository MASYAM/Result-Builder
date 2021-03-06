<?php
require 'authenticate.php';
require 'includes/functions.php';
require 'includes/functions2.php';
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
 if($category == 'Generate')
 {
        if($_GET['subcat'] == 'Generate Distinct') 
        {

                        if(strlen($db->get_column_exists()) !== 0)
                        {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                        else{
                              echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                              echo selection_menu("GENERATE GRADE");
                           }

                        if(isset($_POST['section_name']))
                        {
                                $section_name[]  = $_POST['section_name'];
                                $term_name[]    = $_POST['term_name'];

                                if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms')
                                {
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections</b></div>';                 
                                }else{
                                        $section_exists   = $db->get_section_exists($term_name,$section_name);
                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';

                                        }else{
                                               echo '<div style="padding:20px;margin:auto;width: 500px;height:30px;"></div>';
                                               get_generate_grade($section_name,$term_name,$selected_term,$selected_section);
                                            }
                                    }
                        }
        }
        elseif($_GET['subcat'] == 'Generate % Number Aggregate')
        {
                 if(!(isset($_POST['1st_box_menu']) ||  isset($_POST['process']) || isset($_POST['multi_percent'])) )
                 {
                    if(strlen($db->get_column_exists()) !== 0)
                    {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                    else{
                          echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                          echo selection_menu4("");
                       }
                 }
                 if(isset($_POST['1st_box_menu']))
                 {
                     $term_name[] = $_POST['term_name'];
                     echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                     echo selection_menu5("",$term_name);
                 }

                   if(isset($_POST['process']))
                    {
                            $count_multi_term = count($_POST['multi_term_name']);
                            $term_name[]      = $_POST['term_name_again'];
                            $multi_term_name  = $_POST['multi_term_name'];

                            if($count_multi_term == 0)
                            {
                                 echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                 echo selection_menu5("",$term_name);
                                 echo '<div style="padding:1px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any Add %terms</b></div>';                 
                            }else{
                                       $multi_selected_terms =  $db->set_checked($multi_term_name,"checked");
                                       $validate_percent = validate_percent($count_multi_term,$term_name,$multi_term_name);
                                        if( empty($validate_percent) == FALSE)
                                        {
                                            echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                            echo selection_menu5($multi_selected_terms,$term_name);
                                            echo '<div style="padding:1px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$validate_percent.'</b></div>';
                                        }else{
                                                echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                                echo set_percentage($count_multi_term,$term_name,$multi_term_name,'','');
                                             }
                                }
                    }
                    if(isset($_POST['multi_percent']))
                    {
                        $count_multi_term = $_POST['count_multi_term'];
                        $term_name[] = $_POST['term_name'];
                        $multi_term_name = $_POST['multi_term_name'];
                        $multi_percent = $_POST['multi_percent'];
                        $percent = $_POST['percent'];
                        $section = $_POST['section_name'];
                        
                        if($section == 'Available Sections')
                        {
                            echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                            echo set_percentage($count_multi_term,$term_name,$multi_term_name,$multi_percent,$percent);
                            echo '<div style="padding:1px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available section</b></div>';
                        }else{
                               if($db->find_by_sql("*", "add_n_grade", "term='{$term_name[0]}' AND section='$section'", "") == 'No Result Found')
                               {
                                   echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                   echo set_percentage($count_multi_term,$term_name,$multi_term_name,$multi_percent,$percent);
                                   echo '<div style="padding:1px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No result made for Section:'.$section.', Term:'.$term_name[0].'</b></div>';
                               }else{
                                   echo get_percentage_grade();
                               }
                        }
                    }

        }
        elseif($_GET['subcat'] == 'Generate Total Number Aggregate')
        {
                 if(!(isset($_POST['1st_box_menu']) ||  isset($_POST['process'])) )
                 {
                    if(strlen($db->get_column_exists()) !== 0)
                    {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                    else{
                          echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                          echo selection_menu4("");
                       }
                 }
                 if(isset($_POST['1st_box_menu']))
                 {
                     $term_name[] = $_POST['term_name'];
                     echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                     echo total_aggregate_selection("",$term_name);
                 }

                   if(isset($_POST['process']))
                    {
                            $count_multi_term = count($_POST['multi_term_name']);
                            $term_name[]      = $_POST['term_name_again'];
                            $multi_term_name  = $_POST['multi_term_name'];

                            if($count_multi_term == 0)
                            {
                                 echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                 echo total_aggregate_selection("",$term_name);
                                 echo '<div style="padding:1px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any Add %terms</b></div>';                 
                            }else{
                                       $multi_selected_terms =  $db->set_checked($multi_term_name,"checked");
                                       $validate_percent = validate_percent($count_multi_term,$term_name,$multi_term_name);
                                        if( empty($validate_percent) == FALSE)
                                        {
                                            echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                            echo total_aggregate_selection($multi_selected_terms,$term_name);
                                            echo '<div style="padding:1px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$validate_percent.'</b></div>';
                                        }else{
                                                    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                                    echo total_aggregate_selection($multi_selected_terms,$term_name);
                                                    $section = $_POST['section_name'];

                                                    if($section == 'Available Sections')
                                                    {
                                                       echo '<div style="padding:1px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available section</b></div>';
                                                    }else{
                                                           echo get_total_aggregate($count_multi_term,$term_name,$multi_term_name);
                                                    }
                                             }
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
