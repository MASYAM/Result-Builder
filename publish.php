<?php ob_start(); ?>
<?php
require 'authenticate.php';
require 'includes/functions.php';
require 'includes/functions2.php';
require 'includes/functions3.php';
require 'includes/functions7.php';
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
if($category == 'Publish')
 {
            if($_GET['subcat'] == 'Distinct Publish') 
            {
                        if(strlen($db->get_column_exists()) !== 0)
                        {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                        else{
                              echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                              echo publish_selection_menu("DISTINCT RESULT PUBLISH","distinct");
                           }

                     if(isset($_POST['section_name']))
                     {
                           $term_name[]    = $_POST['term_name'];
                           $section_name[] = $_POST['section_name'];
                           $print_number   = $_POST['print_number'];
                           $only_grade     = $_POST['only_grade'];
                           $total_number   = $_POST['total_number'];
                           $gpa            = $_POST['gpa'];
                                if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms')
                                {
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections</b></div>';                 
                                }elseif($only_grade !== 'only_grade' && $total_number !== 'total_number' && $gpa !== 'gpa')
                                {
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available result type</b></div>';                 
                                }else{
                                        $section_exists    = $db->get_section_exists($term_name,$section_name);

                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }elseif($db->find_by_sql("*","student_n_numbers","term='$term_name[0]' AND section='$section_name[0]'","") == 'No Result Found')
                                        {
                                            echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                        }else{
                                               echo '<div style="padding: 5px;margin:auto;width: 500px;"></div>';
                                                echo '<div id="loadingImg" style="display:none;width:40px;height:40px;margin:10px auto; " >';
                                                echo '<img src="images/load3.gif" width="40px" height="40px" >';
                                                echo '</div>';
                                               echo publish_report($term_name,$section_name,$print_number,$only_grade,$total_number,$gpa);
                                            }
                                    }
                      }elseif(isset($_GET['section']) && isset ($_GET['term']) && isset ($_GET['total']) && isset ($_GET['gpa']) && isset ($_GET['only_grade']) && isset ($_GET['print']) && isset ($_GET['page']))
                      {
                             $term_name[]    = $_GET['term'];
                             $section_name[] = $_GET['section'];
                             $print_number   = $_GET['print'];
                             $only_grade     = $_GET['only_grade'];
                             $total_number   = $_GET['total'];
                             $gpa            = $_GET['gpa'];
                                if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms')
                                {
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections</b></div>';                 
                                }elseif($only_grade !== 'only_grade' && $total_number !== 'total_number' && $gpa !== 'gpa')
                                {
                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available result type</b></div>';                 
                                }else{
                                        $section_exists    = $db->get_section_exists($term_name,$section_name);

                                        if(empty($section_exists) == FALSE)
                                        {
                                           echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                        }elseif($db->find_by_sql("*","student_n_numbers","term='$term_name[0]' AND section='$section_name[0]'","") == 'No Result Found')
                                        {
                                            echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                        }else{
                                               echo '<div style="padding:5px;margin:auto;width: 500px;"></div>';
                                                echo '<div id="loadingImg" style="display:none;width:40px;height:40px;margin:10px auto; " >';
                                                echo '<img src="images/load3.gif" width="40px" height="40px" >';
                                                echo '</div>';
                                               echo publish_report($term_name,$section_name,$print_number,$only_grade,$total_number,$gpa);
                                            }
                                    }
                          
                      }

            }
           elseif($_GET['subcat'] == '% Number Aggr. Publish')
            {
                         if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  echo publish_selection_menu2();
                               }
                       
                         if(isset($_POST['section_name']))
                         {
                               $term_name[]    = $_POST['term_name'];
                               $section_name[] = $_POST['section_name'];
                               $print_number   = $_POST['print_number'];
                               $only_grade     = $_POST['only_grade'];
                               $total_number   = $_POST['total_number'];
                               $gpa            = $_POST['gpa'];
                                    if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms')
                                    {
                                        echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections</b></div>';                 
                                    }elseif($only_grade !== 'only_grade' && $total_number !== 'total_number' && $gpa !== 'gpa')
                                    {
                                        echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available result type</b></div>';                 
                                    }else{
                                            $section_exists = $db->get_section_exists($term_name,$section_name);
                                            
                                            if(empty($section_exists) == FALSE)
                                            {
                                               echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                            }elseif($db->find_by_sql("*","student_n_numbers","term='$term_name[0]' AND section='$section_name[0]'","") == 'No Result Found')
                                            {
                                                echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                            }else{
                                                   echo '<div style="padding: 5px;margin:auto;width: 500px;"></div>';
                                                   echo '<div id="loadingImg" style="display:none;width:40px;height:40px;margin:10px auto; " >';
                                                   echo '<img src="images/load3.gif" width="40px" height="40px" >';
                                                   echo '</div>';
                                                   echo percent_aggregate_publish($term_name,$section_name,$print_number,$only_grade,$total_number,$gpa);
                                                }
                                        }
                           }elseif(isset($_GET['section']) && isset ($_GET['term']) && isset ($_GET['total']) && isset ($_GET['gpa']) && isset ($_GET['only_grade']) && isset ($_GET['print']) && isset ($_GET['page']))
                            {
                                   $term_name[]    = $_GET['term'];
                                   $section_name[] = $_GET['section'];
                                   $print_number   = $_GET['print'];
                                   $only_grade     = $_GET['only_grade'];
                                   $total_number   = $_GET['total'];
                                   $gpa            = $_GET['gpa'];
                                      if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms')
                                    {
                                       echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections</b></div>';                 
                                    }elseif($only_grade !== 'only_grade' && $total_number !== 'total_number' && $gpa !== 'gpa')
                                    {
                                       echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available result type</b></div>';                 
                                    }else{
                                            $section_exists = $db->get_section_exists($term_name,$section_name);
                                            
                                            if(empty($section_exists) == FALSE)
                                            {
                                               echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                            }elseif($db->find_by_sql("*","student_n_numbers","term='$term_name[0]' AND section='$section_name[0]'","") == 'No Result Found')
                                            {
                                                echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                            }else{
                                                    echo '<div style="padding: 5px;margin:auto;width: 500px;"></div>';
                                                    echo '<div id="loadingImg" style="display:none;width:40px;height:40px;margin:10px auto; " >';
                                                    echo '<img src="images/load3.gif" width="40px" height="40px" >';
                                                    echo '</div>';
                                                    echo percent_aggregate_publish($term_name,$section_name,$print_number,$only_grade,$total_number,$gpa);
                                                }
                                        }

                            }
            } 
            elseif($_GET['subcat'] == 'Total Number Aggr. Publish')
            {
                           if(strlen($db->get_column_exists()) !== 0)
                            {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                            else{
                                  echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
                                  echo selection_menu("TOTAL NUMBER AGGREGATE RESULT PUBLISH");
                               }
                       
                         if(isset($_POST['section_name']))
                         {
                               $term_name[]    = $_POST['term_name'];
                               $section_name[] = $_POST['section_name'];
                               
                                    if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms')
                                    {
                                        echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections</b></div>';                 
                                    }else{
                                            $section_exists    = $db->get_section_exists($term_name,$section_name);
                                            
                                            if(empty($section_exists) == FALSE)
                                            {
                                               echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                            }elseif($db->find_by_sql("*","student_n_numbers","term='$term_name[0]' AND section='$section_name[0]'","") == 'No Result Found')
                                            {
                                                echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                            }else{
                                                    echo '<div style="padding: 5px;margin:auto;width: 500px;"></div>';
                                                    echo '<div id="loadingImg" style="display:none;width:40px;height:40px;margin:10px auto; " >';
                                                    echo '<img src="images/load3.gif" width="40px" height="40px" >';
                                                    echo '</div>';
                                                    echo total_aggregate_publish($term_name,$section_name,$print_number,$only_grade,$total_number,$gpa);
                                                }
                                        }
                           }elseif(isset($_GET['section']) && isset ($_GET['term']) && isset ($_GET['total']) && isset ($_GET['gpa']) && isset ($_GET['only_grade']) && isset ($_GET['print']) && isset ($_GET['page']))
                            {
                                   $term_name[]    = $_GET['term'];
                                   $section_name[] = $_GET['section'];
                                   $print_number   = $_GET['print'];
                                   $only_grade     = $_GET['only_grade'];
                                   $total_number   = $_GET['total'];
                                   $gpa            = $_GET['gpa'];
                                      if($section_name[0] == 'Available Sections' || $term_name[0] == 'Available Terms')
                                    {
                                        echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Please choose any available terms and sections</b></div>';                 
                                    }else{
                                            $section_exists    = $db->get_section_exists($term_name,$section_name);
                                            
                                            if(empty($section_exists) == FALSE)
                                            {
                                               echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>'.$section_exists.'</b></div>';
                                            }elseif($db->find_by_sql("*","student_n_numbers","term='$term_name[0]' AND section='$section_name[0]'","") == 'No Result Found')
                                            {
                                                echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Result Found</b></div>';
                                            }else{
                                                   echo '<div style="padding: 5px;margin:auto;width: 500px;"></div>';
                                                   echo '<div id="loadingImg" style="display:none;width:40px;height:40px;margin:10px auto; " >';
                                                   echo '<img src="images/load3.gif" width="40px" height="40px" >';
                                                   echo '</div>';
                                                   echo total_aggregate_publish($term_name,$section_name,$print_number,$only_grade,$total_number,$gpa);
                                                }
                                        }

                            }
            }
            elseif($_GET['subcat'] == 'Excel Publish') 
            {
                   if(strlen($db->get_column_exists()) !== 0)
                    {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message" ><b>'.$db->get_column_exists().'</b></div>';}
                    else{
                          echo '<div style="padding:1px;margin:auto;width: 500px;height:10px;"></div>';
                          echo '<div style="margin:auto;width: 800px;height:100px;">';
                            echo '<div style="float:left;width: 350px;height:100px;">';
                              echo selection_menu("PUBLISH SECTION DETAILS IN EXCEL");
                            echo '</div>';
                            echo '<div style="float:right;width: 350px;height:100px;">';
                              echo selection_menu6("PUBLISH SECTION DETAILS IN EXCEL");
                            echo '</div>';
                          echo '</div>';
                       }

                    if(isset($_POST['section_name']) && isset($_POST['hidden']))
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
                                            $student = $db->find_by_sql("DISTINCT student_id,student_name", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]'  ORDER BY LENGTH(student_id),student_id", "");
                                            $subject = $db->find_by_sql("DISTINCT subject", "subject_list", "term='$term_name[0]' AND section='$section_name[0]'  ORDER BY subject", "");
                                            if ($student !== 'No Result Found') {
                                                foreach ($subject as $subj_val) {
                                                    $exm_type[$subj_val['subject']] = $db->find_by_sql("exam_type,full_mark", "exam_type_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_val['subject']}'  ORDER BY exam_type", "");
                                                    if ($exm_type[$subj_val['subject']] == 'No Result Found') {
                                                        $not_found = 'not found';
                                                        $not_found_sub = $subj_val['subject'];
                                                        break;
                                                    }
                                                    $num_of_exam_type_per_subject[$subj_val['subject']] = count($exm_type[$subj_val['subject']]);
                                                }
                                            }

                                            if ($not_found == 'not found') {
                                                echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Exam-type not found in Subject: "' . $not_found_sub . '", Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
                                            }elseif ($student == 'No Result Found') {
                                                echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No student found in Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
                                            }else{
                                           
                                                    echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                                        <div id="message">
                                                        <form action="excel_publish.php" method="post">
                                                          <input type="hidden" name="term" value="'.$term_name[0].'">
                                                          <input type="hidden" name="section" value="'.$section_name[0].'">
                                                          <input type="submit" id="submit_button" style="width:180px;" name="submit" value="Excel Publish">
                                                        </form>
                                                        </div>';
                                                 }
                                        }
                                }
                    }else if(isset ($_POST['hidden2']) && isset ($_POST['section_name']))
                    {
                           $section_name[]  = $_POST['section_name'];
                                echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div>
                                                        <div id="message">
                                                        <form action="excel_publish_aggregate.php" method="post">
                                                          <input type="hidden" name="section" value="'.$section_name[0].'">
                                                          <input type="submit" id="submit_button" style="width:250px;" name="submit" value="Excel Publish of Aggr.">
                                                        </form>
                                                        </div>';
                        
                    }
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
