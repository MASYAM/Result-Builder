<?php

function publish_selection_menu($title_name,$condition)
{
    global $available_terms,$available_section;
    $r = '';
    $r .='<div id="publish_selection_menu">';
    $r .='<div style="width:455px;height:20px;text-align:center;color:#fff;background:#0099CC;"><b>'.$title_name.'</b></div>';
    $r .='<form action="" method="post">';
    $r .='<br>';
    $r .='<table style="text-align:left;margin:5px auto 0 auto;font-weight:bold;" >';
    $r .='<tr>';
    $r .='<td>';
    $r .='<select name="term_name" title="Available Terms"  id="term" onchange="handle_select(this)">';
    $r .='<option value="Available Terms">Available Terms</option>';
    $r .='<optgroup label="Terms :">';
     foreach ($available_terms as $value)
        { 
            $r .='<option value="'.$value['term'].'" >'.$value['term'].'</option>';
        }
    $r .='</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='<td></td>';
    $r .='<td></td>';
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td style="text-align:left;"><input type="checkbox" name="print_number" id="print_number" value="print_it"  disabled><b>Print Number</b></td>';
    $r .='</tr>';
  if($condition == 'percent' || $condition == 'distinct')
  { $r .='<tr>';
    $r .='<td></td>';
    $r .='<td style="text-align:left;"><input type="radio" name="only_grade" id="only_grade" value="only_grade"  onchange="publish_selection(this)"  disabled><b>Only Grade</b></td>';
    $r .='</tr>';
  }if($condition == 'percent' || $condition == 'distinct')
  { $r .='<tr>';
    $r .='<td></td>';
    //$r .='<td style="text-align:left;"><input type="radio" name="gpa" id="gpa" value="gpa" onchange="publish_selection(this)" disabled><b>GPA</b></td>';
    $r .='</tr>';
  }if($condition == 'total' || $condition == 'distinct')
  { $r .='<tr>';
    $r .='<td></td>';
    $r .='<td style="text-align:left;"><input type="radio" name="total_number" id="total_number" value="total_number"  onchange="publish_selection(this)"  disabled><b>Total Number</b></td>';
    $r .='</tr>';
  }
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td></td>';
    $r .='<td>';
    $r .='<select name="section_name" title="Available Sections"  id="section" onchange="this.form.submit()" disabled>';
    $r .='<option value="Available Section">Available Sections</option>';
    $r .='<optgroup label="Sections :">';
     foreach ($available_section as $value)
        { 
            $r .='<option value="'.$value['section'].'" >'.$value['section'].'</option>';
        }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r.='</table>'; 
    $r .='</form>';                           
    $r .='</div>';
    
       return $r ;
}


function publish_selection_menu2()
{
    global $available_terms,$available_section;
    $r = '';
    $r .='<div id="publish_selection_menu">';
    $r .='<div style="width:455px;height:20px;text-align:center;color:#fff;background:#0099CC;"><b>% NUMBER AGGREGATE RESULT PUBLISH</b></div>';
    $r .='<form action="" method="post">';
    $r .='<br>';
    $r .='<table style="text-align:left;margin:5px auto 0 auto;font-weight:bold;" >';
    $r .='<tr>';
    $r .='<td>';
    $r .='<select name="term_name" title="Available Terms"  id="term" onchange="handle_select(this)">';
    $r .='<option value="Available Terms">Available Terms</option>';
    $r .='<optgroup label="Terms :">';
     foreach ($available_terms as $value)
        { 
            $r .='<option value="'.$value['term'].'" >'.$value['term'].'</option>';
        }
    $r .='</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='<td></td>';
    $r .='<td></td>';
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td style="text-align:left;"><input type="checkbox" name="print_number" id="print_number" value="print_it"  disabled><b>Print Number</b></td>';
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td style="text-align:left;"><input type="radio" name="only_grade" id="only_grade" value="only_grade"  onchange="publish_selection(this)"  disabled><b>Only Grade</b></td>';
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td></td>';
    //$r .='<td style="text-align:left;"><input type="radio" name="gpa" id="gpa" value="gpa" onchange="publish_selection(this)" disabled><b>GPA</b></td>';
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td style="text-align:left;"><input type="hidden" id="total_number"  onchange="publish_selection(this)"  disabled><b></b></td>';
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td></td>';
    $r .='<td>';
    $r .='<select name="section_name" title="Available Sections"  id="section" onchange="this.form.submit()" disabled>';
    $r .='<option value="Available Section">Available Sections</option>';
    $r .='<optgroup label="Sections :">';
     foreach ($available_section as $value)
        { 
            $r .='<option value="'.$value['section'].'" >'.$value['section'].'</option>';
        }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r.='</table>'; 
    $r .='</form>';                           
    $r .='</div>';
    
       return $r ;
}


function publish_report($term_name,$section_name,$print_number,$only_grade,$total_number,$gpa_system)
{  global $db;
    $student = $db->find_by_sql("DISTINCT student_id","student_n_numbers","section='$section_name[0]'  ORDER BY LENGTH(student_id),student_id ","");
    $subject = $db->find_by_sql("DISTINCT subject","subject_list","term='$term_name[0]' AND section='$section_name[0]' ORDER BY LENGTH(priority_order),priority_order","");
    
    $target_page = "publish.php?category=Publish&&subcat=Distinct Publish";   
    $limit = 15;
    $pagination = pagination($student,$target_page,$limit,$section_name,$term_name,$print_number,$only_grade,$total_number,$gpa_system);
        
    if($_GET['page'] > $pagination[1])
    {
       echo '<div id="message" style="font-size:16px;color:#CC3300;"><b>No Result Found</b></div>';
    }else{
        	/* Get data. */
            $all_students = array_slice($student,$pagination[2],$pagination[3]);

             $check_exists_number = $db->find_by_sql("number", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]' ", "");
                if ($all_students !== 'No Result Found') 
                {
                    foreach ($subject as $subj_value) 
                    {
                        $exm_type[$subj_value['subject']] = $db->find_by_sql("DISTINCT exam_type", "exam_type_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_value['subject']}'", "");
                        $grade[$subj_value['subject']] = $db->find_by_sql("num_from,num_to,grade,point", "grade_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_value['subject']}'", "");
                        if ($exm_type[$subj_value['subject']] == 'No Result Found') {
                            $not_found = 'not found';
                            $not_found_sub = $subj_value['subject'];
                            break;
                        }
                        $num_of_exam_type[$subj_value['subject']] = count($exm_type[$subj_value['subject']]);
                    }
                    foreach ($check_exists_number as $check_numb) 
                    {
                        $check_ex_num = $check_numb['number'];
                        if (strlen($check_ex_num) > 0) {
                            $check_ex_num = 'found';
                            break;
                        }
                    }
                }

                if ($not_found == 'not found') {
                    echo '<div id="message"><b>Exam-type not found in Subject: "' . $not_found_sub . '", Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
                } elseif ($all_students == 'No Result Found') {
                    echo '<div id="message"><b>No student found in Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
                } elseif ($check_ex_num !== 'found') {
                    echo '<div id="message"><b>No student number found in Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
                } else {
    
    
    
    
    
                    $r .= '<div style="display:none;">';  //this is for not showing the table into the web page
                    $r .='<div id="print_report">';				     				     						     			

                     $count_point_n_sub = 0;
                     foreach ($all_students as $stu_val)
                     { 
                         $student_name = $db->find_by_sql("DISTINCT student_name","student_n_numbers","section='$section_name[0]' AND student_id='{$stu_val['student_id']}'","");
                         if($student_name == 'No Result Found')
                          {
                               $student_name = '';
                          }else{ $student_name = $student_name[0]['student_name'];  }
                                      
                          $report_card = $db->find_by_sql("header,footer,comments,grade_table,note","report_card","id='1'","");

                          $disp_n_roll_result = $db->find_by_sql("working_days,absent,detention,class_roll","discipline_n_roll","term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_val['student_id']}'","") ;
                          if($disp_n_roll_result !== 'No Result Found'){ $working_days = $disp_n_roll_result[0]['working_days']; $absent = $disp_n_roll_result[0]['absent'];
                                                                        $detention = $disp_n_roll_result[0]['detention'];  $class_roll = $disp_n_roll_result[0]['class_roll']; }
                          else{ $working_days = ''; $absent = ''; $detention = '';  $class_roll = 'None';}

                           date_default_timezone_set('Asia/Dhaka');
                           $r .= '<div style="page-break-after:always;">';                     

                           if($print_number == 'print_it'){ $cols = 2; }else{ $cols = 1; }

                           $r .= $report_card[0]['header'];
                           $r .= name_id($student_name,$stu_val['student_id'],$class_roll,$section_name[0],$term_name[0]);

                           $r .='<div style="margin:auto;width:700px;height:750px;font-family:calibri;overflow:hidden;">';
                           $r .= '<table style="margin:auto;border-collapse:collapse;border:1px solid #000;" border="1">';
                           $r .='<tr>';
                           $r .='<td style="padding:5px;width:250px;text-align:center;"><b>Subject</b></td>';
                           if($print_number == 'print_it' || $total_number == 'total_number')
                           {$r .='<td style="padding:5px;width:100px;text-align:center;"><b>Number</b></td>';}
                           if($only_grade == 'only_grade' || $gpa_system == 'gpa')
                           {$r .='<td style="padding:5px;width:100px;text-align:center;"><b>Grade</b></td>';}
                           $r .='</tr>';


                           $r .= result_making1($subject,$grade,$term_name,$section_name,$stu_val['student_id'],$working_days,$absent,$detention,$only_grade,$total_number,$gpa_system,$print_number);

                          $r .='</table>';          

                           $r .='</div>';
                           //$r .= '<div style="margin:10px auto;width:700px;height:70px;font-family:calibri; ">';
                           //$r .= $report_card[0]['comments'];
                          // $r .= '</div>';
                          // $r .= prodigy_footer();
                           $r .='</div>'; 


                     }//end of student loop          

                   $r .='</div>';
                   $r .='</div>';
                   $r .='<div id="message"><input type="button" id="submit_button" value="PUBLISH"   onClick="PrintElem(print_report)" ><br><b> Section: '.$section_name[0].' ('.$term_name[0].'), Result-type: '.$only_grade.''.$total_number.''.$gpa_system.'</b></div>' ;
                   } // end of $check_ex_num !== 'found else
    
                   echo $r;
    echo $pagination[0];
    
  }// end of if($_GET['page'] > $lastpage)else    
        
}


function extra($only_grade,$total_number,$gpa,$print_number,$gpa_result,$grand_total,$section_pos,$class_pos,$working_days,$absent,$detention)
{
           if($total_number == 'total_number')
           {
            $r .='<tr>';
              $r .='<td style="padding:2px;width:250px;">Grand Total</td>';
              $r .='<td style="padding:2px;width:100px;text-align:center;" colspan="2" >'.$grand_total.'</td>';
            $r .='</tr>';
           }

//           if($total_number == 'total_number')
//           {
//            $r .='<tr>';
//              $r .='<td style="padding:2px;width:250px;">Section Merit List</td>';
//              $r .='<td style="padding:2px;width:100px;text-align:center;" colspan="2" >'.$section_pos.'</td>';
//            $r .='</tr>';
//           }
//           if($total_number == 'total_number')
//           {
//            $r .='<tr>';
//              $r .='<td style="padding:2px;width:250px;">Class Merit List</td>';
//              $r .='<td style="padding:2px;width:100px;text-align:center;" colspan="2" >'.$class_pos.'</td>';
//            $r .='</tr>';
//           }
           if($gpa == 'gpa')
           {
            $r .='<tr>';
              $r .='<td style="padding:2px;width:250px;">GPA <span style="font-size:10px;">(Excluding 4th subject)</span></td>';
              $r .='<td style="padding:2px;text-align:center;" ';
              if($print_number == 'print_it')
              { 
                 $r .= ' colspan="2"';
              }
              $r .= '>'.$gpa_result.'</td>';
            $r .='</tr>';
           }
            $r .='<tr>';
              $r .='<td style="padding:1px;width:250px;font-size:13px;">Total Working Days</td>';
              $r .='<td style="padding:1px;text-align:center;font-size:13px;" ';
              if($print_number == 'print_it' || $total_number == 'total_number')
              { 
                 $r .= ' colspan="2"';
              }
              $r .= '>'.$working_days.'</td>';
            $r .='</tr>';
            $r .='<tr>';
              $r .='<td style="padding:1px;width:250px;font-size:13px;">Days Absent</td>';
              $r .='<td style="padding:1px;text-align:center;font-size:13px;" ';
              if($print_number == 'print_it' || $total_number == 'total_number')
              { $r .= ' colspan="2"';}    
              $r .= '>'.$absent.'</td>';
            $r .='</tr>';
            $r .='<tr>';
              $r .='<td style="padding:1px;width:250px;font-size:13px;">No of Detention</td>';
              $r .='<td style="padding:1px;text-align:center;font-size:13px;" ';
              if($print_number == 'print_it' || $total_number == 'total_number')
              { $r .= ' colspan="2"';}   
              $r .= '>'.$detention.'</td>';
            $r .='</tr>';
            
     return $r;
}   




function percent_aggregate_publish($term_name,$section_name,$print_number,$only_grade,$total_number,$gpa)
{  global $db;

    $student = $db->find_by_sql("DISTINCT student_id","student_n_numbers","section='$section_name[0]'  ORDER BY LENGTH(student_id),student_id ","");
   
    $subject = $db->find_by_sql("DISTINCT subject","subject_list","section='$section_name[0]' ORDER BY LENGTH(priority_order),priority_order","");
    $percent_term = $db->find_by_sql("percent_agre","aggregate_percent_term","section='$section_name[0]' ORDER BY LENGTH(percent_term_priority),percent_term_priority ",""); 
    $base_term = $db->find_by_sql("DISTINCT base_term","aggregate_percent_term","section='$section_name[0]'","");
    
    $target_page = "publish.php?category=Publish&&subcat=% Number Aggr. Publish";
    $limit = 15;
    $pagination = pagination($student,$target_page,$limit,$section_name,$term_name,$print_number,$only_grade,$total_number,$gpa_system);
        
    if($_GET['page'] > $pagination[1])
    {
       echo '<div id="message" style="font-size:16px;color:#CC3300;"><b>No Result Found</b></div>';
    }else{
        	/* Get data. */
            $all_students = array_slice($student,$pagination[2],$pagination[3]);

    
                    $r .= '<div style="display:none;">';  //this is for not showing the table into the web page
                    $r .='<div id="print_report">';				     				     						     			

                     $count_point_n_sub = 0;
                     foreach ($all_students as $stu_value)
                     {  

                         $student_name = $db->find_by_sql("DISTINCT student_name","student_n_numbers","section='$section_name[0]' AND student_id='{$stu_value['student_id']}'","");
                         if($student_name == 'No Result Found')
                          {
                               $student_name = '';
                          }else{ $student_name = $student_name[0]['student_name'];  }     
 

                            $q = 0;
                            foreach ($percent_term as $added_term){ $q++; }
                            $q = $q + 1;
                            foreach ($percent_term as $added_term){
                               $finally_added_term = trim($added_term['percent_agre']);

                               $disp_n_roll_result[$added_term['percent_agre']] = $db->find_by_sql("working_days,absent,detention,class_roll","discipline_n_roll","term='{$added_term['percent_agre']}' AND section='$section_name[0]' AND student_id='{$stu_value['student_id']}'","") ;
                               if($disp_n_roll_result[$added_term['percent_agre']] !== 'No Result Found'){ $working_days[$added_term['percent_agre']] = $disp_n_roll_result[$added_term['percent_agre']][0]['working_days']; $absent[$added_term['percent_agre']] = $disp_n_roll_result[$added_term['percent_agre']][0]['absent'];
                                                                             $detention[$added_term['percent_agre']] = $disp_n_roll_result[$added_term['percent_agre']][0]['detention'];  }
                               else{ $working_days[$added_term['percent_agre']] = ''; $absent[$added_term['percent_agre']] = ''; $detention[$added_term['percent_agre']] = ''; }
                            }
                            $class_roll_aggre = $db->find_by_sql("class_roll","discipline_n_roll","term='{$base_term[0]['base_term']}' AND section='$section_name[0]' AND student_id='{$stu_value['student_id']}'","") ;
                            if($class_roll_aggre !== 'No Result Found')
                            { $class_roll = $class_roll_aggre[0]['class_roll']; }
                            else{  $class_roll = 'None';  }

                             $report_card = $db->find_by_sql("header,footer,grade_table,note","report_card","id='1'","");
                             date_default_timezone_set('Asia/Dhaka');
                             $r .= '<div style="page-break-after:always;">';                     

                             if($print_number == 'print_it'){ $cols = 2; }else{ $cols = 1; }

                             $r .= $report_card[0]['header'];
                             $r .= name_id($student_name,$stu_value['student_id'],$class_roll,$section_name[0],$base_term[0]['base_term']);

                             $r .='<div style="float:left;width:450px;height:760px;font-family:calibri;overflow:hidden;">';
                             $r .='<table style="border-collapse:collapse;margin:auto;font-family:calibri; font-size:14px;" border="1">';
                             $r .='<tr>';
                             $r .='<td style="padding:3px;text-align:center;"><b>Subject</b></td>';
                             foreach ($percent_term as $added_term)
                             {$r .='<td style="padding:3px;text-align:center;" colspan="'.$cols.'"><b>'.$added_term['percent_agre'].'</b></td>';}
                             $r .='<td style="padding:3px;text-align:center;" colspan="'.$cols.'"><b>% Aggregate</b></td>';
                             $r .='</tr>';
                             $r .='<tr>';
                             $r .='<td></td>';
                             foreach ($percent_term as $added_term)
                             {
                                 if($print_number == 'print_it'){$r .='<td style="padding:2px;text-align:center;font-size:13px;"><b>Number</b></td>';}
                                 $r .='<td style="padding:2px;text-align:center;font-size:13px;"><b>Grade</b></td>';
                             }
                             if($print_number == 'print_it'){$r .='<td style="padding:2px;text-align:center;font-size:13px;"><b>Number</b></td>';}
                             $r .='<td style="padding:2px;text-align:center;font-size:13px;"><b>Grade</b></td>';
                             $r .='</tr>';


                           $r .= result_making2($cols,$subject,$base_term,$percent_term,$section_name,$stu_value['student_id'],$only_grade,$gpa_system,$print_number);
                            $r .= '</table>';

                           $r .= '</div>';
                             
                            $r .='<div style="float:right;width:200px;height:760px;font-family:calibri;overflow:hidden;">';
                           $r .='<table style="margin-top:20px;text-align:center;border:1px solid #000;border-collapse:collapse; float:right;font-family:calibri; font-size:14px;" border="1">';
                             $r .='<tr>';
                               $r .='<td style="padding:0px"><b>Term</b></td>';
                               $r .='<td style="padding:0px"><b>Working Days</b></td>';
                               $r .='<td style="padding:0px"><b>Absent</b></td>';
                               $r .='<td style="padding:0px"><b>Detention</b></td>';
                             $r .='</tr>';
                            foreach ($percent_term as $added_term)
                             {
                                $r .='<tr>';
                                  $r .='<td style="padding:0px"><b>'.$added_term['percent_agre'].'</b></td>';
                                  $r .='<td style="padding:0px">'.$working_days[$added_term['percent_agre']].'</td>';
                                  $r .='<td style="padding:0px">'.$absent[$added_term['percent_agre']].'</td>';
                                  $r .='<td style="padding:0px">'.$detention[$added_term['percent_agre']].'</td>';
                                $r .='</tr>';
                             }
                            $r .= '</table>';
                            $r .= '</div>';


                           //$r .= prodigy_footer();

                           $r .= '</div>';

                     }//end of student loop          

                   $r .='</div>';
                   $r .='</div>';
                   $r .='<div id="message"><input type="button" id="submit_button" value="PUBLISH"   onClick="PrintElem(print_report)" ><br><b> Section: '.$section_name[0].' ('.$term_name[0].'), Result-type: '.$only_grade.''.$total_number.''.$gpa_system.'</b></div>' ;
    
                   echo $r;
    echo $pagination[0];
    
  }// end of if($_GET['page'] > $lastpage)else
}


function percent_extra($only_grade,$gpa,$print_number,$aggregate_gpa_result,$gpa_result,$percent_term,$cols)
{
           if($gpa == 'gpa')
           {
            $r .='<tr>';
              $r .='<td style="padding:2px"><b>GPA <span style="font-size:10px;">(Excluding 4th subject)</span></b></td>';
              $i = 0;
              foreach ($percent_term as $added_term)
              {
                 $r .='<td style="padding:2px;text-align:center;" colspan="'.$cols.'"><b>'.$gpa_result[$i].'</b></td>'; 
                 $i++;
              }
              $r .='<td style="padding:2px;text-align:center;" colspan="'.$cols.'"><b>'.$aggregate_gpa_result[0]['gpa'].'<b></td>';
            $r .='</tr>';
           }
         
     return $r;
}


function total_aggregate_publish($term_name,$section_name,$print_number,$only_grade,$total_number,$gpa)
{  global $db;
    $total_term = $db->find_by_sql("total_agre","aggregate_total_term","base_term='$term_name[0]' AND section='$section_name[0]' ORDER BY LENGTH(total_term_priority),total_term_priority ","");
    $student = $db->find_by_sql("DISTINCT student_id","student_n_numbers","section='$section_name[0]'  ORDER BY LENGTH(student_id),student_id ","");
    $subject = $db->find_by_sql("DISTINCT subject","subject_list","section='$section_name[0]' ORDER BY LENGTH(priority_order),priority_order","");
    
    $target_page = "publish.php?category=Publish&&subcat=Total Number Aggr. Publish";
    $limit = 10;
    $pagination = pagination($student,$target_page,$limit,$section_name,$term_name,$print_number,$only_grade,$total_number,$gpa_system);
        
    if($_GET['page'] > $pagination[1])
    {
       echo '<div id="message" style="font-size:16px;color:#CC3300;"><b>No Result Found</b></div>';
    }else{
        	/* Get data. */
            $all_students = array_slice($student,$pagination[2],$pagination[3]);
 
     $r .= '<div style="display:none;">';  //this is for not showing the table into the web page
     $r .='<div id="print_report">';				     				     						     			
     
      foreach ($all_students as $stu_value)
      {
          $student_name = $db->find_by_sql("DISTINCT student_name","student_n_numbers","section='$section_name[0]' AND student_id='{$stu_value['student_id']}'","");
                         if($student_name == 'No Result Found')
                          {
                               $student_name = '';
                          }else{ $student_name = $student_name[0]['student_name'];  }
                      
           $q = 0;
           foreach ($total_term as $added_term){ $q++; }
           $q = $q + 1;
           foreach ($total_term as $added_term)
           {  
              $finally_added_term = trim($added_term['total_agre']);
              
              $disp_n_roll_result[$added_term['total_agre']] = $db->find_by_sql("working_days,absent,detention,class_roll","discipline_n_roll","term='$finally_added_term' AND section='$section_name[0]' AND student_id='{$stu_value['student_id']}'","") ;
              if($disp_n_roll_result[$added_term['total_agre']] !== 'No Result Found'){ $working_days[$added_term['total_agre']] = $disp_n_roll_result[$added_term['total_agre']][0]['working_days']; $absent[$added_term['total_agre']] = $disp_n_roll_result[$added_term['total_agre']][0]['absent'];
                                                            $detention[$added_term['total_agre']] = $disp_n_roll_result[$added_term['total_agre']][0]['detention'];  }
              else{ $working_days[$added_term['total_agre']] = ''; $absent[$added_term['total_agre']] = ''; $detention[$added_term['total_agre']] = ''; }
           }
           $class_roll_aggre = $db->find_by_sql("class_roll","discipline_n_roll","term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_value['student_id']}'","") ;
           if($class_roll_aggre !== 'No Result Found')
           { $class_roll = $class_roll_aggre[0]['class_roll']; }
           else{  $class_roll = 'None';  }
           
           $report_card = $db->find_by_sql("header,footer,comments,note","report_card","id='1'","");
           
           date_default_timezone_set('Asia/Dhaka');
           
            $r .= '<div style="page-break-after:always;">';
            
            $r .= $report_card[0]['header'];
            $r .= name_id($student_name,$stu_value['student_id'],$class_roll,$section_name[0],$term_name[0]);
            
            $r .='<div style="float:left;width:450px;height:760px;font-family:calibri;overflow:hidden;">';
            $r .='<table style="border-collapse:collapse;margin:auto;font-family:calibri; font-size:14px;" border="1">';
            $r .='<tr>';
            $r .='<td style="padding:3px;text-align:center;"><b>Subject</b></td>';
            foreach ($total_term as $added_term)
            {$r .='<td style="padding:3px;text-align:center;" ><b>'.$added_term['total_agre'].'</b></td>';}
            $r .='<td style="padding:3px;text-align:center;" ><b>Total Aggregate</b></td>';
            $r .='</tr>';
            $r .='<tr>';
            $r .='<td></td>';
            foreach ($total_term as $added_term)
            {
                $r .='<td style="padding:2px;text-align:center;font-size:13px;"><b>Number</b></td>';
            }
            $r .='<td style="padding:2px;text-align:center;font-size:13px;"><b>Number</b></td>';
            $r .='</tr>';
            
            $r .= result_making3($subject, $total_term, $term_name, $section_name, $stu_value['student_id']);
            
            $r .='</table>';
           $r .='</div>';
           
           $r .='<div style="float:right;width:200px;height:760px;font-family:calibri;overflow:hidden;">';
            $r .='<table style="margin-top:20px;text-align:center;border:1px solid #000;border-collapse:collapse; float:right;font-family:calibri; font-size:14px;" border="1">';
              $r .='<tr>';
              $r .='<td style="padding:0px"><b>Term</b></td>';
              $r .='<td style="padding:0px"><b>Working Days</b></td>';
              $r .='<td style="padding:0px"><b>Absent</b></td>';
              $r .='<td style="padding:0px"><b>Detention</b></td>';
            $r .='</tr>';
             foreach ($total_term as $added_term){
            $r .='<tr>';
              $r .='<td style="padding:0px"><b>'.trim($added_term['total_agre']).'</b></td>';
              $r .='<td style="padding:0px">'.$working_days[$added_term['total_agre']].'</td>';
              $r .='<td style="padding:0px">'.$absent[$added_term['total_agre']].'</td>';
              $r .='<td style="padding:0px">'.$detention[$added_term['total_agre']].'</td>';
            $r .='</tr>';
             }
           $r .= '</table>';
           
           $r .= '</div>';
		   
           //$r .= prodigy_footer();
                   
            $r .='</div>'; 
            
      }
      
    $r .='</div>';
    $r .='</div>';
    $r .='<div id="message"><input type="button" id="submit_button" value="PUBLISH"   onClick="PrintElem(print_report)" ><br><b> Section: '.$section_name[0].' ('.$term_name[0].')</b></div>' ;
    }
    echo $r;
    echo $pagination[0];
}


function total_extra($aggregate_total_result,$total_result,$total_term)
{
   //  Grand Total
    $r .='<tr>';
      $r .='<td style="padding:2px"><b>Grand Total</b></td>';
      foreach ($total_term as $added_term){
         $r .='<td style="padding:2px;text-align:center;"><b>'.$total_result[$added_term['total_agre']][0]['total_result'].'</b></td>'; 
      }
      $r .='<td style="padding:2px;text-align:center;" ><b>'.$aggregate_total_result.'</b></td>';
    $r .='</tr>';
    $r .='<tr>';
	
	/*
      $r .='<td style="padding:2px"><b>Section Merit List</b></td>';
      foreach ($total_term as $added_term){
         $r .='<td style="padding:2px;text-align:center;">'.$total_result[$added_term['total_agre']][0]['section_pos'].'</td>'; 
      }
      $r .='<td style="padding:2px;text-align:center;" >'.$aggregate_total_result[0]['section_pos'].'</td>';
    $r .='</tr>';
    $r .='<tr>';
      $r .='<td style="padding:2px"><b>Class Merit List</b></td>';
      foreach ($total_term as $added_term){
         $r .='<td style="padding:2px;text-align:center;">'.$total_result[$added_term['total_agre']][0]['class_pos'].'</td>'; 
      }
      $r .='<td style="padding:2px;text-align:center;" >'.$aggregate_total_result[0]['class_pos'].'</td>';
    $r .='</tr>';
       */  
     return $r;
}


function name_id($student_name,$student_id,$class_roll,$section,$term)
{
    $r .='<div style="font-size:15px;width:600px;height:35px;text-align:center;margin:1px auto;"><b>Progress Report<br>'.$term.' Examination-'.date("Y").'</b></div>';
   
    $r .= '<div style="margin:10px auto 5px auto;text-align:center;width:700px;height:25px;font-size:13px;font-family:calibri;overflow:hidden;" >' ;
    $r .= '<b>Name: </b>'.$student_name.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;' ;
    $r .= '<b>Class Roll: </b>'.substr($student_id,4).' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;' ;
    $r .= '<b>Section: </b>'.$section.' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;' ;
    $r .= '</div>' ;
    
    return $r;
}


function prodigy_footer()
{
    $r .= '<div style="width:700px;height:15px;font-family:calibri;margin:10px auto 0 auto;">';
    $r .= '<span style="font-size:10px;float:left;"><b>'.'Date:'.date("d-m-Y", time()) . '</b></span>' ;
    $r .= '<span style="font-size:8px;margin-left:540px;float:left;vertical-align:middle;"><b>Powered by: <img src="images/pdbd-logo.png" style="vertical-align:middle;" width="40px" height="10px"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;www.pdbd.org</b></span>';				     				     		
    $r .='</div>';
    
    return $r;
}



function pagination($student,$targetpage,$limit,$section_name,$term_name,$print_number,$only_grade,$total_number,$gpa_system)
{
         $adjacents = 3;
        
	$total_pages = count($student);
	
	/* Setup vars for query. */
	$targetpage = $targetpage; //your file name  (the name of this file)
	$limit = $limit; 				//how many items to show per page
	$page = $_GET['page'];
	if($page) 
		$start = ($page - 1) * $limit; 	//first item to display on this page
	else
		$start = 0;	//if no page var is given, set start to 0
	      
	
	/* Setup page vars for display. */
	if ($page == 0) $page = 1;	//if no page var is given, default to 1.
	$prev = $page - 1;		//previous page is page - 1
	$next = $page + 1;		//next page is page + 1
	$lastpage = ceil($total_pages/$limit);	//lastpage is = total pages / items per page, rounded up.
	$lpm1 = $lastpage - 1;	//this is previous page of last page //last page minus 1
	
	/* 
            Now we apply our rules and draw the pagination object. 
            We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
		$pagination .= "<div class=\"pagination\">";
                $pagination .= '<span style="padding:2px 4px;border:1px solid black;background:whitesmoke;">Page '.$page.'/'.$lastpage.'</span>';
		//previous button
		if ($page > 1) 
			$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$prev\" onclick=\"doLoading()\"><< Previous</a>";
		else
			$pagination.= "<span class=\"disabled\"><< Previous</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination.= "<span class=\"current\" >$counter</span>";
				else
					$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$counter\" onclick=\"doLoading()\">$counter</a>";					
			}
		}
		elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 2))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$counter\" onclick=\"doLoading()\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&&section=$section_name&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$lpm1\" onclick=\"doLoading()\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$lastpage\" onclick=\"doLoading()\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=1\" onclick=\"doLoading()\">1</a>";
				$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=2\" onclick=\"doLoading()\">2</a>";
				$pagination.= "...";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$counter\" onclick=\"doLoading()\">$counter</a>";					
				}
				$pagination.= "...";
				$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$lpm1\" onclick=\"doLoading()\">$lpm1</a>";
				$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$lastpage\" onclick=\"doLoading()\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=1\" onclick=\"doLoading()\">1</a>";
				$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=2\" onclick=\"doLoading()\">2</a>";
				$pagination.= "...";
				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\" >$counter</span>";
					else
						$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$counter\" onclick=\"doLoading()\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination.= "<a href=\"$targetpage&&section=$section_name[0]&&term=$term_name[0]&&total=$total_number&&gpa=$gpa_system&&only_grade=$only_grade&&print=$print_number&&page=$next\" onclick=\"doLoading()\">Next >></a>";
		else
			$pagination.= "<span class=\"disabled\" >Next >></span>";
		$pagination.= "</div>\n";		
	}
        
        return array(0=>$pagination,1=>$lastpage,2=>$start,3=>$limit);
}


?>
