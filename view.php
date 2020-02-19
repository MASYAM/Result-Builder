<?php
require 'authenticate.php';
require 'includes/functions.php';
require 'includes/functions2.php';
require_once('includes/MySqlDb.php');
ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
ini_set("memory_limit","1500M");

$available_terms     = $db->find_by_sql("*","terms  ORDER BY term ASC","","");
$available_section   = $db->find_by_sql("DISTINCT section","section_list  ORDER BY section ASC","","");
$available_subject   = $db->find_by_sql("DISTINCT subject","subject_list  ORDER BY priority_order ASC","","");
$available_exam_type = $db->find_by_sql("DISTINCT exam_type","exam_type_list  ORDER BY exam_type ASC","","");

?>

<?php require 'includes/header.php';   ?>

      <div id="main_content">
  <?php
  if($category == 'View')
  {
        if($_GET['subcat'] == 'Section Details') 
        {
                    if(strlen($db->get_column_exists()) !== 0)
                    {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                    else{
                          echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                          echo selection_menu("VIEW SECTION DETAILS");
                       }

                    if(isset($_POST['section_name']))
                    {
                            $section_name[]  = $_POST['section_name'];
                            $term_name[]     = $_POST['term_name'];

                            if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms')
                            {
                                echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections</b></div>';                 
                            }else{
                                    $section_exists   = $db->get_section_exists($term_name,$section_name);
                                    if(empty($section_exists) == FALSE)
                                    {
                                       echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                    }else{
                                           echo get_section_details($section_name,$term_name,$selected_term,$selected_section);
                                        }
                                }
                    }
        }elseif($_GET['subcat'] == 'Exam Details') 
        {
            echo 'I m in exm details.';
        }else 
            {
               echo 'Page not found';
            }
  }else
      {
         echo 'Page not found';
      }
  ?> 
          
      </div>

<?php require 'includes/footer.php';   ?>
