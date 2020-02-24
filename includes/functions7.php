<?php
function result_making1($subject,$grade,$term_name,$section_name,$student_id,$working_days,$absent,$detention,$only_grade,$total_number,$gpa_system,$print_number)
{
           global $db;
                        $fourth_subject = $db->find_by_sql("subject", "fourth_subject", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='$student_id' ", "");
                           if ($fourth_subject == 'No Result Found') {
                               $fourth_subject = 'No Result Found';
                           }else{
                               $fourth_subject = $fourth_subject[0]['subject'];
                           }

                        foreach ($subject as $subj_val) 
                         {
                            $r .= '<tr>';
                            $r .='<td style="padding:1px;font-size:14px;width:250px;text-align:center;">'.$subj_val['subject'].'</td>';
                            
                               $result = $db->find_by_sql("number", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='$student_id' AND subject='{$subj_val['subject']}' ", "");
                               $pass_mark = $db->find_by_sql("mark", "pass_mark_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_val['subject']}' ", "");
                               $count_num = count($result);

                             if ($result !== 'No Result Found') 
                             {
                                   //$catch_number[] = '';
                                  foreach ($result as $result_val) 
                                   {
                                       if (empty($result_val['number']) == FALSE)
                                       {
                                           $catch_number[] = $result_val['number'];
                                           $check_exists_per_subject_any_number = 'found';
                                           $check_exists_per_subject_any_number2 = 'found';
                                       }else{
                                           $catch_number[] = '';
                                       }
                                   }

                                 if (is_array($catch_number)) 
                                 {
                                           if($check_exists_per_subject_any_number2 == 'found')
                                           {$sum = array_sum($catch_number); //numbers addition making
                                            $sum = round($sum);
                                           }else{

                                                   if($print_number == 'print_it' && ($only_grade == 'only_grade' || $gpa_system == 'gpa')){$r .='<td style="padding:1px;font-size:14px;text-align:center;"></td>';}
                                                   $r .='<td style="padding:1px;font-size:14px;text-align:center;"></td>';
                                                   continue;
                                           }

                                           if ($pass_mark !== 'No Result Found') 
                                           {
                                               if ($sum < $pass_mark[0]['mark']) 
                                               {
                                                   $pass_value = 'Not Pass';
                                               }else{ $pass_value = 'Pass'; }
                                           }else{
                                                   $pass_value = 'Not set';
                                                }

                                           if ($grade[$subj_val['subject']] !== 'No Result Found') 
                                           {
                                                  foreach ($grade[$subj_val['subject']] as $grade_val) 
                                                  {
                                                       if (($sum >= $grade_val['num_from']) && ($sum <= $grade_val['num_to'])) 
                                                       {
                                                           $desire_grade = $grade_val['grade']; //grade_making
                                                           $desire_point = $grade_val['point'];
                                                           if ($fourth_subject == 'No Result Found') {
                                                               $fourth_exist = 'Not set';
                                                           } else {
                                                               $fourth_exist = 'Set';
                                                           }
                                                             //echo$stu_val['student_id'].'<br>';
                                                           if ($subj_val['subject'] !== $fourth_subject) 
                                                           { 
                                                               $count_point_n_sub++;
                                                               $catch_all_grade_point += $grade_val['point'];
                                                               if (($desire_point == 0)) {
                                                                   $fail = 'fail';
                                                                   $gpa = $desire_grade;
                                                               }
                                                           }
                                                       }
                                                   }
                                                   if (empty($desire_grade) == TRUE) 
                                                   {
                                                       $fail = 'fail';
                                                       $gpa = '?';
                                                       $desire_grade = '?';
                                                       $desire_point = '?';
                                                   }

                                           }else 
                                               {
                                                 $desire_grade = 'Not set';
                                                 $desire_point = 'Not set';
                                                 $fail = 'Not set';
                                                 $desire_grade = 'Not set';
                                                 $desire_point = 'Not set';
                                               }


                                                                       
                                               if($total_number == 'total_number')
                                               { 
                                                   if($pass_value == 'Not Pass')
                                                   {$r .='<td style="padding:1px;font-size:14px;text-align:center;color:red;text-decoration:underline;font-weight:bold;"><b>'.$sum.'</b></td>';}
                                                   else{$r .='<td style="padding:1px;font-size:14px;width:100px;text-align:center;">'.$sum.'</td>';}
                                               }
                                               if($print_number == 'print_it' && ($only_grade == 'only_grade' || $gpa_system == 'gpa'))
                                               { 
                                                   if($desire_point == 0)
                                                   {$r .='<td style="padding:1px;font-size:14px;text-align:center;color:red;text-decoration:underline;font-weight:bold;"><b>'.$sum.'</b></td>';}
                                                   else{$r .='<td style="padding:1px;font-size:14px;width:100px;text-align:center;">'.$sum.'</td>';}
                                               }
                                               if($only_grade == 'only_grade' || $gpa_system == 'gpa')
                                               {
                                                   if($desire_point == 0)
                                                   {$r .='<td style="padding:1px;font-size:10px;text-align:center;color:red;text-decoration:underline;font-weight:bold;"><b>'.$desire_grade.'</b></td>';}
                                                   else{$r .='<td style="padding:1px;font-size:14px;width:100px;text-align:center;">'.$desire_grade.'</td>';}
                                               }



                                           $catch_all_sub_number += $sum;
                                           $catch_number = array(); // set $catch_number as an empty array
                                           $desire_grade = '';
                                           $desire_point = ''; // set $desire_grade as an empty var
                                           $pass_value = '';

                             
                                     }
                             }else{
                                     if($print_number == 'print_it' && ($only_grade == 'only_grade' || $gpa_system == 'gpa')){$r .='<td style="padding:1px;font-size:14px;text-align:center;"></td>';}
                                     $r .='<td style="padding:1px;font-size:14px;text-align:center;"></td>';
                                 }
                             
                             $r .= '</tr>';
                             $check_exists_per_subject_any_number2 = '';

                         }//end of subject loop

                          if($check_exists_per_subject_any_number == 'found')
                          {

                               if (!($count_point_n_sub == 0 || $fail == 'fail')) {
                                   //echo$count_point_n_sub.' ';
                                   //echo '<br>'.$catch_all_grade_point.'<br>'.$count_point_n_sub.'<br>';
                                   $gpa_result = $catch_all_grade_point / $count_point_n_sub;
                               }

                               $r .= extra($only_grade,$total_number,$gpa_system,$print_number,$gpa_result,$catch_all_sub_number,$section_pos,$class_pos,$working_days,$absent,$detention);

                               $catch_all_sub_number = '';
                               $count_point_n_sub = 0;
                               $catch_all_grade_point = '';
                               $gpa_result = '';
                               $fail = '';
                               $fourth_subject = '';
                               $check_exists_per_subject_any_number ='';
                          }
           return $r;
      }
               
      
function result_making2($cols,$subject,$base_term,$percent_term,$section_name,$student_id,$only_grade,$gpa_system,$print_number)
{
    global $db;

    foreach ($subject as $subj_val)
     {
         $r .='<tr>';
         $r .='<td style="padding:2px;width:135px;text-align:center;"><b>'.$subj_val['subject'].'</b></td>';
          foreach ($percent_term as $added_term)
           { 
                   $fourth_subject = $db->find_by_sql("subject", "fourth_subject", "term='{$added_term['percent_agre']}' AND section='$section_name[0]' AND student_id='$student_id' ", "");
                    if ($fourth_subject == 'No Result Found') 
                    {$fourth_subject = 'No Result Found';}
                    else{
                        $fourth_subject = $fourth_subject[0]['subject'];
                    }
            
                   $grade[$subj_val['subject']] = $db->find_by_sql("num_from,num_to,grade,point", "grade_list", "term='{$added_term['percent_agre']}' AND section='$section_name[0]' AND subject='{$subj_val['subject']}'", "");
                  
                   $result = $db->find_by_sql("number", "student_n_numbers", "term='{$added_term['percent_agre']}' AND section='$section_name[0]' AND student_id='$student_id' AND subject='{$subj_val['subject']}' ", "");

                   if ($result !== 'No Result Found') 
                   {
                          foreach ($result as $result_val) 
                          {
                               if (empty($result_val['number']) == FALSE)
                               {
                                   $catch_number[] = $result_val['number'];
                                   $check_exists_per_subject_any_number = 'found';
                                   $check_exists_per_subject_any_number2 = 'found';
                               }else{
                                   $catch_number[] = '';
                               }
                          }

                             if (is_array($catch_number)) 
                             {
                                        if($check_exists_per_subject_any_number2 == 'found')
                                        {$sum = array_sum($catch_number); //numbers addition making
                                         $sum = round($sum);
                                        }else{
                                                $sum = '';
                                                if($print_number == 'print_it'){$r .='<td style="padding:2px;text-align:center;"></td>';}
                                                $r .='<td style="padding:2px;text-align:center;"></td>';
                                                
                                                continue;
                                        }
                                        
                                        if ($grade[$subj_val['subject']] !== 'No Result Found') 
                                        {
                                                   foreach ($grade[$subj_val['subject']] as $grade_val) 
                                                   {
                                                        if (($sum >= $grade_val['num_from']) && ($sum <= $grade_val['num_to'])) 
                                                        {
                                                            $desire_grade = $grade_val['grade']; //grade_making
                                                            $desire_point = $grade_val['point'];
                                                            if ($fourth_subject == 'No Result Found') {
                                                                $fourth_exist = 'Not set';
                                                            } else {
                                                                $fourth_exist = 'Set';
                                                            }
                                                              //echo$stu_val['student_id'].'<br>';
                                                            if ($subj_val['subject'] !== $fourth_subject) 
                                                            { 
                                                                $count_point_n_sub++;
                                                                $catch_all_grade_point += $grade_val['point'];
                                                                if (($desire_point == 0)) {
                                                                    $fail = 'fail';
                                                                    $gpa = $desire_grade;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    if (empty($desire_grade) == TRUE) 
                                                    {
                                                        $fail = 'fail';
                                                        $gpa = '?';
                                                        $desire_grade = '?';
                                                        $desire_point = '?';
                                                    }

                                         }else 
                                             {
                                                  $desire_grade = 'Not set';
                                                  $desire_point = 'Not set';
                                                  $fail = 'Not set';
                                                  $desire_grade = 'Not set';
                                                  $desire_point = 'Not set';
                                             }

                                       if($print_number == 'print_it'){$r .='<td style="padding:2px;text-align:center;">'.$sum.'</td>';}
                                       $r .='<td style="padding:2px;text-align:center;">'.$desire_grade.'</td>';

                                       $percentage = $db->find_by_sql("percentage","aggregate_percent_term","section='$section_name[0]' AND percent_agre='{$added_term['percent_agre']}'","");
                                       $grab_all_sum[$added_term['percent_agre']] = ($sum * $percentage[0]['percentage']) / 100;
                                       $grab_all_term_points[$added_term['percent_agre']] = $desire_point;
                                       $catch_number = array(); // set $catch_number as an empty array
                                       $desire_grade = '';
                                       $desire_point = ''; // set $desire_grade as an empty var
                                       $pass_value = '';
                                       
                              }
                   }else{
                        if($print_number == 'print_it'){$r .='<td style="padding:2px;text-align:center;"></td>';}
                        $r .='<td style="padding:2px;text-align:center;"></td>';
                   }
                             
               $check_exists_per_subject_any_number2 = '';
           }//end of total term
                 
                  if(empty($grab_all_sum) == FALSE)
                  {
                      if($print_number == 'print_it'){$r .='<td style="padding:2px;text-align:center;">'.round(array_sum($grab_all_sum)).'</td>';}
                      $r .='<td style="padding:2px;text-align:center;">'.  get_aggregate_grade(round(array_sum($grab_all_sum)),$student_id,$subj_val['subject'],$base_term, $section_name, $only_grade, $gpa_system, $print_number).'</td>';
                  }else{
                      if($print_number == 'print_it'){$r .='<td style="padding:2px;text-align:center;"></td>';}
                      $r .='<td style="padding:2px;text-align:center;"></td>';
                  }
                                   
                  $r .='</tr>';
                                                      
                  $grab_all_term_points = array();
                  $grab_all_sum = array();
                  $check_exists_per_subject_any_number2 = '';
     }//end of subject loop

          return $r; 
                                    
 }     
      
      
function get_aggregate_grade($sum,$student_id,$subject,$base_term,$section_name,$only_grade,$gpa_system,$print_number)
{
   global $db;
   
   $grade = $db->find_by_sql("num_from,num_to,grade,point", "grade_list", "term='{$base_term[0]['base_term']}' AND section='$section_name[0]' AND subject='$subject'", "");
   
   $fourth_subject = $db->find_by_sql("subject", "fourth_subject", "term='{$base_term[0]['base_term']}' AND section='$section_name[0]' AND student_id='$student_id' ", "");
    if ($fourth_subject == 'No Result Found') 
    {$fourth_subject = 'No Result Found';}
    else{
        $fourth_subject = $fourth_subject[0]['subject'];
    }

   if ($grade !== 'No Result Found') 
    {
           foreach ($grade as $grade_val) 
           {
                if (($sum >= $grade_val['num_from']) && ($sum <= $grade_val['num_to'])) 
                {
                    $desire_grade = $grade_val['grade']; //grade_making
                    $desire_point = $grade_val['point'];
                    if ($fourth_subject == 'No Result Found') {
                        $fourth_exist = 'Not set';
                    } else {
                        $fourth_exist = 'Set';
                    }
                      //echo$stu_val['student_id'].'<br>';
                    if ($subj_val['subject'] !== $fourth_subject) 
                    { 
                        $count_point_n_sub++;
                        $catch_all_grade_point += $grade_val['point'];
                        if (($desire_point == 0)) {
                            $fail = 'fail';
                            $gpa = $desire_grade;
                        }
                    }
                }
            }
            if (empty($desire_grade) == TRUE) 
            {
                $fail = 'fail';
                $gpa = '?';
                $desire_grade = '?';
                $desire_point = '?';
            }

    }else 
     {
          $desire_grade = 'Not set';
          $desire_point = 'Not set';
          $fail = 'Not set';
          $desire_grade = 'Not set';
          $desire_point = 'Not set';
     }

    return $desire_grade;
      
}
    
      
function result_making3($subject,$total_term,$term_name,$section_name,$student_id)
{
     global $db;                              
            
     foreach ($subject as $subj_val)
     {
         $r .='<tr>';
         $r .='<td style="padding:2px;width:135px;text-align:center;"><b>'.$subj_val['subject'].'</b></td>';
          foreach ($total_term as $added_term)
           { 
                  $finally_added_term = trim($added_term['total_agre']);
                  
                   $result = $db->find_by_sql("number", "student_n_numbers", "term='$finally_added_term' AND section='$section_name[0]' AND student_id='$student_id' AND subject='{$subj_val['subject']}' ", "");
                   $pass_mark = $db->find_by_sql("mark", "pass_mark_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_val['subject']}' ", "");

                   if ($result !== 'No Result Found') 
                   {
                          
                          foreach ($result as $result_val) 
                          {
                               if (empty($result_val['number']) == FALSE)
                               {
                                   $catch_number[] = $result_val['number'];
								   $check_exists_per_subject_any_number2 = 'found';
                               }else{
                                   $catch_number[] = '';
                               }
                          }

                             if (is_array($catch_number)) 
                             {
                                       if($check_exists_per_subject_any_number2 == 'found')
                                        {$sum = array_sum($catch_number); //numbers addition making
                                         $sum = round($sum);
                                        }else{
                                                $sum = '';
                                                $r .='<td style="padding:2px;text-align:center;"></td>';
                                                
                                                continue;
                                        }

                                        if ($pass_mark !== 'No Result Found') 
                                        {
                                            if ($sum < $pass_mark[0]['mark']) 
                                            {
                                                $pass_value = 'Not Pass';
                                            }else{ $pass_value = 'Pass'; }
                                        }else{
                                                $pass_value = 'Not set';
                                             }

                                       if($pass_value == 'Not Pass')
                                       {$r .='<td style="padding:2px;text-align:center;color:red;text-decoration:underline;font-weight:bold;"><b>'.$sum.'</b></td>';}
                                       else{$r .='<td style="padding:2px;text-align:center;">'.$sum.'</td>';}


                                       $catch_all_number += $sum;
                                       $catch_number = array(); // set $catch_number as an empty array
                                       $pass_value = '';
                                       $sum_of_all_terms += $sum;
                              }
                   }else{
                       $r .='<td style="padding:2px;text-align:center;"></td>';
                   }
                             
                $check_exists_per_subject_any_number2 = '';
           }//end of total term
                 
                  $r .='<td style="padding:2px;text-align:center;">'.$sum_of_all_terms.'</td>';                 
                  $r .='</tr>';
                  $sum_of_all_terms = '';
				  $check_exists_per_subject_any_number2 = '';
     }//end of subject loop
     
     foreach ($total_term as $added_term)
     {
        $total_result[$added_term['total_agre']] = $db->find_by_sql("SUM(number) AS total_result","student_n_numbers","term='{$added_term['total_agre']}' AND section='$section_name[0]' AND student_id='$student_id'","");
     }      
     $r .= total_extra($catch_all_number,$total_result,$total_term);
          return $r;    
  }      
          
      
?>
