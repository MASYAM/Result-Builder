<?php ob_start(); ?>
<?php
require 'authenticate.php';
require ('includes/MySqlDb.php');
ini_set('max_execution_time', 1800); //3600 seconds = 60 minutes
ini_set("memory_limit","1500M");

if(isset($_POST['term']) && isset($_POST['section']))
{   
    $section_name[] = $_POST['section'];
    $term_name[]    = $_POST['term'];
    
    $student = $db->find_by_sql("DISTINCT student_id", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]'  ORDER BY LENGTH(student_id),student_id", "");
    $subject = $db->find_by_sql("DISTINCT subject", "subject_list", "term='$term_name[0]' AND section='$section_name[0]'  ORDER BY LENGTH(priority_order),priority_order", "");
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

        $class_name = $db->find_by_sql("class_name","class_n_shift_name","term='$term_name[0]' AND section='$section_name[0]'","");
        if($class_name !== 'No Result Found'){ $cn = $class_name[0]['class_name']; }else{ $cn = 'Not set'; }
        $file="Class:($cn)-Section:($section_name[0])-($term_name[0]).xls";
        
        $r .='<div><b>Term: ' . $term_name[0] . ', Section: ' . $section_name[0] . '</b></div>';
        $r .='<table style=" border:1px solid #999;" cellpadding="5" border="1">';

        $r .='<tr style="background:PapayaWhip ;color:#000;font-weight:bold;">';
        $r .='<td style="text-align:center;">Student Id</td>';
        $r .='<td style="text-align:center;">Name</td>';
        foreach ($subject as $subj_value) 
        {
            $count_exam_type = $num_of_exam_type_per_subject[$subj_value['subject']] + 2;
            $r .='<td style="text-align:center;" colspan="' . $count_exam_type . '">' . $subj_value['subject'] . '</td>';
        }
        $r .='<td></td>';
        $r .='<td></td>';
        $r .='<td></td>';
        $r .='<td></td>';
        $r .='</tr>';
        $r .='<tr style="background:OldLace;font-weight:bold;">';
        $r .='<td style="text-align:center;"></td>';
        $r .='<td style="text-align:center;"></td>';
        foreach ($subject as $subj_val) 
        {
            foreach ($exm_type[$subj_val['subject']] as $exam_type_val) 
            {
                $r .='<td style="text-align:center;">' . $exam_type_val['full_mark'] . '</td>';
                $total += $exam_type_val['full_mark'];
            }

            $r .='<td style="text-align:center;">'.$total.'</td>';
            $r .='<td style="text-align:center;"></td>';
            $total= '';
        }
        $r .='<td></td>';
        $r .='<td></td>';
        $r .='<td></td>';
        $r .='<td></td>';
        $r .='</tr>';
        $r .='<tr style="background:OldLace;font-weight:bold; ">';
        $r .='<td></td>';
        $r .='<td></td>';
        foreach ($subject as $subj_val) 
        {
            foreach ($exm_type[$subj_val['subject']] as $exam_type_val) 
            {
                $r .='<td>' . $exam_type_val['exam_type'] . '</td>';
            }

            $r .='<td style="text-align:center;">Total</td>';
            $r .='<td style="text-align:center;">Grade</td>';
        }
        $r .='<td>Working Days</td>';
        $r .='<td>Absent</td>';
        $r .='<td>Detention</td>';
        $r .='<td>Class Roll</td>';
        $r .='</tr>';
        
        $n = 0;
        foreach ($student as $stu_value) {
           $disp_n_roll_result = $db->find_by_sql("working_days,absent,detention,class_roll","discipline_n_roll","term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_value['student_id']}'","") ;
           if($disp_n_roll_result !== 'No Result Found'){ $working_days = $disp_n_roll_result[0]['working_days']; $absent = $disp_n_roll_result[0]['absent'];
                                                         $detention = $disp_n_roll_result[0]['detention'];  $class_roll = $disp_n_roll_result[0]['class_roll']; }
           else{ $working_days = ''; $absent = ''; $detention = '';  $class_roll = 'Not set';}
            
            $r .= '<tr ';
            if ($n % 2 == 0) {
                $r .= ' style="background-color:#fff;" ';
            } else {
                $r .= ' style="background-color:#eee;" ';
            }
            $r .= ' >';
            $r .='<td style="text-align:center;">' . substr($stu_value['student_id'],4) . '</td>';
            $name_of_stu = $db->find_by_sql("student_name", "student_n_numbers", "student_id='{$stu_value['student_id']}'", "1");
            if($name_of_stu == 'No Result Found' || empty($name_of_stu) == TRUE){ $name_of_stu = ''; }else{ $name_of_stu = $name_of_stu[0]['student_name']; }

            $r .='<td style="text-align:center;">' . $name_of_stu . '</td>';

            foreach ($subject as $subj_value) 
            {
                $grade[$subj_value['subject']] = $db->find_by_sql("num_from,num_to,grade,point", "grade_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_value['subject']}'", "");
                
                foreach ($exm_type[$subj_value['subject']] as $exam_type_value) 
                {
                    $r .='<td style="text-align:center;">';
                    // echo 'Stu id: '.$stu_val.' Subject :'.$value.' EXM-type: '.$value2.'<br>';
                    $number = $db->find_by_sql("number", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_value['student_id']}' AND subject='{$subj_value['subject']}' AND exam_type='{$exam_type_value['exam_type']}'", "");
                    if ($number !== 'No Result Found') 
                    {
                        foreach ($number as $numb_val) 
                        {
                            $number_value = $numb_val['number'];
                            $catch_addition += $numb_val['number'];
                        }
                    }
                    $r .=$number_value;
                    $r .='</td>';
                    $number_value = ''; //set $number_value as an epty var
                }
                
                 $catch_addition = round($catch_addition); // make total number into round figure
                      if ($grade[$subj_value['subject']] !== 'No Result Found') 
                       {
                              foreach ($grade[$subj_value['subject']] as $grade_val) 
                              {
                                   if (($catch_addition >= $grade_val['num_from']) && ($catch_addition <= $grade_val['num_to']))
                                   {
                                       $desire_grade = $grade_val['grade']; //grade_making                                      
                                   }
                               }
                               if (empty($desire_grade) == TRUE) 
                               {
                                   $desire_grade = '?';
                               }

                       }else 
                           {
                             $desire_grade = 'Not set';
                           }

                
                $r .='<td style="text-align:center;"><b>' . $catch_addition . '<b></td>';
                $r .='<td style="text-align:center;"><b>' . $desire_grade . '<b></td>';
                $catch_addition = ''; //set this var as empty
                $desire_grade = ''; //set this var as empty
            }

            $r .='<td>'.$working_days.'</td>';
            $r .='<td>'.$absent.'</td>';
            $r .='<td>'.$detention.'</td>';
            $r .='<td>'.$class_roll.'</td>';
            $r .='</tr>';
            $n++;           
        }

        $r .='</table>';

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        echo $r ;


}