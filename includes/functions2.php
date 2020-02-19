<?php

function get_cre_num_of_student($fourth_subject,$section_name) 
{
    $r = '';
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:200px;">';
    $r.='<div id="numb_of_idname_div">';
    $r.='<form action="" method="post">';
    $r.='<table style="margin:auto;">';
    if($fourth_subject == 'fourth_subject'){$r .='<div id="message" style="font-size:12px;"><b>4th subject is implemented</b></div>';}
    else{$r .='<div id="message" style="font-size:12px;"><b>4th subject is not implemented</b></div>';}
    $r.='<tr>';
    $r.='<td style="font-size:13px ;padding:0px 0px 7px 0;text-align:left;"><b>Number of student: </b>';
    $r.='<input type="text" size="5" name="numOfStudent" autofocus="autofocus" required>';
    $r.='</td>';
    $r.='</tr>';
    $r.='<tr>';
    $r.='<td style="padding: 20px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Submit"></td>';
    $r.='</tr>';
    $r.='</table>';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'">';
    if($fourth_subject == 'fourth_subject')
    {$r .='<input type="hidden" name="fourth_subject" value="fourth_subject">';}
    $r.='</form>';
    $r.='</div>';
    $r.='</div>';

    return $r;
}


function get_create_student($numOfStudent,$student_id,$student_name,$section_name,$fourth_subject,$selected_subject) 
{
   global $db,$available_subject;
   $belong_term = $db->find_by_sql("term","section_list","section='$section_name'","");
   foreach ($belong_term as $belong_term_val){ $term[] = $belong_term_val['term'];}

    $r = '';
    $r .='<div style="padding:20px 34px 0 20px;margin:auto;width: 820px;height:370px; ">';
    $r .='<div id="cre_student_header"><b>INSERT NAME & ID</b></div>';
    $r .='<div id="create_student_div">';
    $r .='<div id="message" style="font-size:13px;"><b>Section: "'.$section_name.'" belongs Term: '.  join(",", $term).'</b></div>';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:auto;border:1px solid #999;" cellpadding="3" border="1">';
    $r .='<tr>';
    $r .='<td><b>Student Id</b></td>';
    $r .='<td><b>Student Name</b></td>';
    if($fourth_subject == 'fourth_subject'){ $r .='<td><b>4th Subject</b></td>'; }
    $r .='</tr>';
    for ($i = 0; $i < $numOfStudent; $i++) {
        $r .= '<tr class="tr1_hover">';
        $r .='<td style="width:130px;"><input type="text" size="16" name="student_id[]"   value="' . $student_id[$i] . '"   placeholder="id" required></td>';
        $r .='<td style="width:130px;"><input type="text" size="16" name="student_name[]" value="' . $student_name[$i] . '" placeholder="name" required></td>';
        if($fourth_subject == 'fourth_subject')
        {
                $r .='<td style="width:130px;">';
                $r .='<select name="subject_name[]" title="Available Subjects">';
                $r .='<option value="Available Subjects">Available Subjects</option>';
                $r .='<optgroup label="Subjects :">';
                foreach ($available_subject as $value) {
                    $r .='<option value="' . $value['subject'] . '"  ' . $selected_subject[$i][$value['subject']] . '>' . $value['subject'] . '</option>';
                }
                $r .= '</optgroup>';
                $r .='</select>';
                $r .='</td>';
        }
        $r .='</tr>';
    }
    $r .='</table>';
    $r .='</div>';
    $r .='<table style="width:480px;margin:auto;" >';
    $r .='<tr>';
    $r .='<td style="padding: 10px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Insert"></td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'">';
    $r .='</form>';
    $r .='</div>';

    return $r;
}


function get_create_student2($count_student_id,$section_name,$student_id,$student_name,$fourth_subject_name,$selected_fourth,$fourth_subject) 
{
    global $db,$available_section,$getting_4_codes;
    $belong_term = $db->find_by_sql("term","section_list","section='$section_name'","");
    foreach ($belong_term as $belong_term_val){ $term_name[] = $belong_term_val['term'];}
    foreach ($available_section as $available_section_val){ $sec[] = $available_section_val['section'];}
    $count_term = count($term_name);
    $count_section = count($sec);
  
    for ($i = 0; $i < $count_student_id; $i++) 
    {
        for($j=0;$j<$count_section;$j++)
        {
            $escape_student_id = $getting_4_codes.''.$db->escape_value($student_id[$i]);
            $escape_student_name = $db->escape_value($student_name[$i]);
            $check = $db->query("SELECT * FROM student_n_numbers WHERE section='$sec[$j]' AND student_id='$escape_student_id'");
               if ($db->num_rows($check)) 
               {
                   $exist_student_id = $student_id[$i];
                   $exist_sec_name   =  $sec[$j];
                   $exist = 'exist';
                   break;
               }
        }
        if ($exist == 'exist') { break;}
    }
    if ($exist == 'exist') 
    {
        echo get_create_student($count_student_id, $student_id, $student_name,$section_name, $fourth_subject, $selected_fourth);
        echo '<div id="message"><b>Student id: "' . $exist_student_id . '" already exist in Section: "' . $exist_sec_name . '"<br>Student Id must be unique</b></div>';
    } else {
               for($j=0;$j<$count_term;$j++)
               {
                    $subject = $db->find_by_sql("DISTINCT subject", "subject_list", "term='$term_name[$j]' AND section='$section_name'", "");
                    if ($subject !== 'No Result Found') 
                    {
                        foreach ($subject as $subj_val) 
                         {
                            $exm_type[$subj_val['subject']] = $db->find_by_sql("DISTINCT exam_type", "exam_type_list", "term='$term_name[$j]' AND section='$section_name' AND subject='{$subj_val['subject']}'", "");
                            if ($exm_type[$subj_val['subject']] == 'No Result Found') 
                            {
                                $not_found = 'not found';
                                $not_found_sub = $subj_val['subject'];
                                break;
                            }
                        }
                    }
                    if($not_found == 'not found'){ $exist_term = $term_name[$j];break; }
                    if ($subject == 'No Result Found'){ $exist_term = $term_name[$j];break; }
               }

        if ($not_found == 'not found') {
            echo get_create_student($count_student_id, $student_id, $student_name,$section_name, $fourth_subject, $selected_fourth);
            echo '<div id="message"><b>Exam-type not found in Subject: "' . $not_found_sub . '", Term :"' . $exist_term . '", Section :"' . $section_name . '"</b></div>';
        } elseif ($subject == 'No Result Found') {
            echo get_create_student($count_student_id, $student_id, $student_name,$section_name, $fourth_subject, $selected_fourth);
            echo '<div id="message"><b>No subject found in Term :"' . $exist_term . '", Section :"' . $section_name . '"</b></div>';
        } else {
                    foreach ($subject as $subj_value) 
                    {
                        foreach ($exm_type[$subj_value['subject']] as $exam_type_value) 
                        {
                            for ($k = 0; $k < $count_student_id; $k++) 
                            {
                               for($j=0;$j<$count_term;$j++)
                                { 
                                   $escape_student_id = $getting_4_codes.''.$db->escape_value($student_id[$k]);
                                   $escape_student_name = $db->escape_value($student_name[$k]);
                                    //echo 'Subject :'.$subj_value['subject'].' EXM-type: '.$exam_type_value['exam_type'].'<br>';
                                   $result = $db->insert("student_n_numbers", "term,section,student_id,student_name,subject,exam_type", "'$term_name[$j]','$section_name','$escape_student_id','$escape_student_name','{$subj_value['subject']}','{$exam_type_value['exam_type']}'", "term='115122fau'");
                                }
                                
                             }
                        }
                    }
                    for ($m = 0; $m < $count_student_id; $m++) 
                    {
                       for($j=0;$j<$count_term;$j++)
                       { 
                         $escape_student_id = $getting_4_codes.''.$db->escape_value($student_id[$m]);
                         $escape_student_name = $db->escape_value($student_name[$m]);
                         $result_discipline_roll = $db->insert("discipline_n_roll", "term,section,student_id", "'$term_name[$j]','$section_name','$escape_student_id'", "term='115122fau'");
                       }
                       $insert_student_account = $db->insert("student_account", "student_id,student_name", "'$escape_student_id','$escape_student_name'", "student_id='$escape_student_id'");
                       if($insert_student_account == 'already exist'){
                         $update_student_account = $db->update("student_account","student_id='$escape_student_id',student_name='$escape_student_name'", "student_id='$escape_student_id'");    
                       }
                       $insert_student_info = $db->insert("student_information", "student_id,student_name", "'$escape_student_id','$escape_student_name'", "student_id='$escape_student_id'");
                       if($insert_student_info == 'already exist'){
                         $insert_student_info = $db->update("student_information","student_id='$escape_student_id',student_name='$escape_student_name'", "student_id='$escape_student_id'");    
                       }
                    }
                    if(is_array($fourth_subject_name) == true)
                    {
                        for ($l = 0; $l < $count_student_id; $l++) 
                        {
                            for($j=0;$j<$count_term;$j++)
                            {
                              $escape_student_id = $getting_4_codes.''.$db->escape_value($student_id[$l]);
                              $escape_student_name = $db->escape_value($student_name[$l]);
                              $result_4th_implement = $db->insert("fourth_subject", "term,section,student_id,subject", "'$term_name[$j]','$section_name','$escape_student_id','$fourth_subject_name[$l]'", "term='115122fau'");
                            }
                        }
                    }
                    echo get_create_student($count_student_id,"","",$section_name, $fourth_subject, $selected_fourth);
                    echo $db->get_message($count_student_id, "student", $result);
        }
    }
}


function get_generate_grade($section_name, $term_name, $selected_term, $selected_section) 
{
    global $db;

    $student = $db->find_by_sql("DISTINCT student_id,student_name", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]'", "");
    $subject = $db->find_by_sql("DISTINCT subject", "subject_list", "term='$term_name[0]' AND section='$section_name[0]'", "");

    $check_exists_number = $db->find_by_sql("number", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]' ", "");
    if ($student !== 'No Result Found') {
        foreach ($subject as $subj_value) {
            $exm_type[$subj_value['subject']] = $db->find_by_sql("DISTINCT exam_type", "exam_type_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_value['subject']}'", "");
            $grade[$subj_value['subject']] = $db->find_by_sql("num_from,num_to,grade,point", "grade_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_value['subject']}'", "");
            if ($exm_type[$subj_value['subject']] == 'No Result Found') {
                $not_found = 'not found';
                $not_found_sub = $subj_value['subject'];
                break;
            }
            $num_of_exam_type[$subj_value['subject']] = count($exm_type[$subj_value['subject']]);
        }
        foreach ($check_exists_number as $check_numb) {
            $check_ex_num = $check_numb['number'];
            if (strlen($check_ex_num) > 0) {
                $check_ex_num = 'found';
                break;
            }
        }
    }

    if ($not_found == 'not found') {
        echo '<div id="message"><b>Exam-type not found in Subject: "' . $not_found_sub . '", Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
    } elseif ($student == 'No Result Found') {
        echo '<div id="message"><b>No student found in Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
    } elseif ($check_ex_num !== 'found') {
        echo '<div id="message"><b>No student number found in Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
    } else {

        $count_point_n_sub = 0;
    foreach ($student as $stu_val) 
    {
            $fourth_subject = $db->find_by_sql("subject", "fourth_subject", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_val['student_id']}' ", "");
            if ($fourth_subject == 'No Result Found') {
                $fourth_subject = 'No Result Found';
            }else{
                $fourth_subject = $fourth_subject[0]['subject'];
            }

         foreach ($subject as $subj_val) 
          {
                //echo 'Stu id: '.$stu_val.' Subject :'.$value.' EXM-type: '.$value2.'<br>';
                $priority_order = $db->find_by_sql("priority_order", "subject_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_val['subject']}' ", "");
                $result = $db->find_by_sql("number", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_val['student_id']}' AND subject='{$subj_val['subject']}' ", "");
                $pass_mark = $db->find_by_sql("mark", "pass_mark_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_val['subject']}' ", "");
                $count_num = count($result);
//                echo $stu_val['student_id'].'<br>'.$subj_val['subject'];
              if ($result !== 'No Result Found') 
              {
                    $catch_number = '';
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
                                    if( !($section_name[0] == 'Concords' || $section_name[0] == 'Jets' || $section_name[0] == 'Rockets' || $section_name[0] == 'Giants' || $section_name[0] == 'Stars' || $section_name[0] == 'Titans') )
                                    { 
                                        $insert = $db->insert("add_n_grade", "term,section,subject,priority_order,student_id,student_name,addition,pass,grade,point", "'$term_name[0]','$section_name[0]','{$subj_val['subject']}','{$priority_order[0]['priority_order']}','{$stu_val['student_id']}','{$stu_val['student_name']}','$sum','$pass_value','',''", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'");
                                        if ($insert == 'already exist') {
                                            $update = $db->update("add_n_grade", "student_name='{$stu_val['student_name']}',priority_order='{$priority_order[0]['priority_order']}',addition='$sum',pass='$pass_value',grade='',point=''", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'");
                                        }
                                    }
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

                            $insert = $db->insert("add_n_grade", "term,section,subject,priority_order,student_id,student_name,addition,pass,grade,point", "'$term_name[0]','$section_name[0]','{$subj_val['subject']}','{$priority_order[0]['priority_order']}','{$stu_val['student_id']}','{$stu_val['student_name']}','$sum','$pass_value','$desire_grade','$desire_point'", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'");
                            if ($insert == 'already exist') {
                                $update = $db->update("add_n_grade", "student_name='{$stu_val['student_name']}',priority_order='{$priority_order[0]['priority_order']}',addition='$sum',pass='$pass_value',grade='$desire_grade',point='$desire_point'", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'");
                            }
                            $catch_all_sub_number += $sum;
                            $catch_number = array(); // set $catch_number as an empty array
                            $desire_grade = '';
                            $desire_point = ''; // set $desire_grade as an empty var
                            $pass_value = '';
                            
                   }
              }
              
              $check_exists_per_subject_any_number2 = '';
          }//end of subject loop

           if($check_exists_per_subject_any_number == 'found')
           {
                $insert_total = $db->insert("total_result", "term,section,student_id,total,aggregate", "'$term_name[0]','$section_name[0]','{$stu_val['student_id']}','$catch_all_sub_number','No'", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_val['student_id']}' AND aggregate='No'");
                if ($insert_total == 'already exist') {
                    $update_total = $db->update("total_result", "total='$catch_all_sub_number'", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_val['student_id']}' AND aggregate='No'");
                }

                if (!($count_point_n_sub == 0 || $fail == 'fail')) {
                    //echo$count_point_n_sub.' ';
                    //echo '<br>'.$catch_all_grade_point.'<br>'.$count_point_n_sub.'<br>';
                    $gpa = $catch_all_grade_point / $count_point_n_sub;
                }
                $insert_gpa = $db->insert("gpa_result", "term,section,student_id,gpa,fourth,percent", "'$term_name[0]','$section_name[0]','{$stu_val['student_id']}','$gpa','$fourth_exist','No'", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_val['student_id']}' AND percent='No'");
                if ($insert_gpa == 'already exist') {
                    $update_gpa = $db->update("gpa_result", "gpa='$gpa',fourth='$fourth_exist'", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_val['student_id']}' AND percent='No'");
                }
                $catch_all_sub_number = '';
                $count_point_n_sub = 0;
                $catch_all_grade_point = '';
                $gpa = '';
                $fail = '';
                $fourth_subject = '';
                $check_exists_per_subject_any_number ='';
           }
                
    }


        if (($insert == TRUE || $insert_total == TRUE || $insert_gpa == TRUE) || ($update == TRUE || $update_total == TRUE || $update_gpa == TRUE)) {
            echo '<div id="message"><b>Result has been successfully created for Section :"' . $section_name[0] . '", Term :"' . $term_name[0] . '"</b></div>';
        }
    }
}


function get_section_details($section_name, $term_name, $selected_term, $selected_section)
{
    global $db;

    $student = $db->find_by_sql("DISTINCT student_id,student_name", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]'  ORDER BY LENGTH(student_id),student_id", "");
    $subject = $db->find_by_sql("DISTINCT subject,priority_order", "subject_list", "term='$term_name[0]' AND section='$section_name[0]'  ORDER BY LENGTH(priority_order),priority_order ", "");
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
        $r .='<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Exam-type not found in Subject: "' . $not_found_sub . '", Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
    } elseif ($student == 'No Result Found') {
        $r .='<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No student found in Term :"' . $term_name[0] . '", Section :"' . $section_name[0] . '"</b></div>';
    } else {
        $r .='<div id="section_details_header"><b>Term: ' . $term_name[0] . ', Section: ' . $section_name[0] . '</b></div>';
        $r .='<div id="section_details">';
        $r .='<table style="margin:3px auto 0 auto; border:1px solid #999;font-size:14px;" cellpadding="2" border="1">';

        $r.=section_details_header($student, $subject, $exm_type, $num_of_exam_type_per_subject);
        $n = 0;
        foreach ($student as $stu_value) 
        {
           $disp_n_roll_result = $db->find_by_sql("working_days,absent,detention,class_roll","discipline_n_roll","term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_value['student_id']}'","") ;
           if($disp_n_roll_result !== 'No Result Found'){ $working_days = $disp_n_roll_result[0]['working_days']; $absent = $disp_n_roll_result[0]['absent'];
                                                         $detention = $disp_n_roll_result[0]['detention'];  $class_roll = $disp_n_roll_result[0]['class_roll']; }
           else{ $working_days = ''; $absent = ''; $detention = '';  $class_roll = 'Not set';}

            
            $r .= '<tr ';
            if ($n % 2 == 0) {
                $r .= ' class="tr1_hover" ';
            } else {
                $r .= ' class="tr2_hover" ';
            }
            $r .= ' >';
            $r .='<td>' . substr($stu_value['student_id'],4) . '</td>';
            $r .='<td>' . $stu_value['student_name'] . '</td>';

            foreach ($subject as $subj_value) 
            {
                $grade[$subj_value['subject']] = $db->find_by_sql("num_from,num_to,grade,point", "grade_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='{$subj_value['subject']}'", "");
                foreach ($exm_type[$subj_value['subject']] as $exam_type_value) 
                {
                    $r .='<td style="width:580px;">';
                    // echo 'Stu id: '.$stu_val.' Subject :'.$value.' EXM-type: '.$value2.'<br>';
                    $number = $db->find_by_sql("number", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_value['student_id']}' AND subject='{$subj_value['subject']}' AND exam_type='{$exam_type_value['exam_type']}'", "");
                    if ($number !== 'No Result Found') {
                        foreach ($number as $numb_val) {
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
            if($n % 14 == 0)
            {  $r.=section_details_header($student, $subject, $exm_type, $num_of_exam_type_per_subject);}
        }

        $r .='</table>';
        $r .='</div>';
    }


    return $r;
}


function section_details_header($student, $subject, $exm_type, $num_of_exam_type_per_subject) 
{
    $r .='<tr style="background:PapayaWhip ;color:#000;font-weight:bold;">';
    $r .='<td>&nbsp;Student&nbsp;Id&nbsp;</td>';
    $r .='<td>&nbsp;<span style="color:transparent;">Nameeeeeeee</span>Name<span style="color:transparent;">Nameeeeeeee</span>&nbsp;</td>';
    foreach ($subject as $subj_value) {
        $count_exam_type = $num_of_exam_type_per_subject[$subj_value['subject']] + 2;
        $r .='<td colspan="' . $count_exam_type . '">' . $subj_value['subject'] . '</td>';
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
            $r .='<td>' . $exam_type_val['full_mark'] . '</td>';
            $total += $exam_type_val['full_mark'];
        }

        $r .='<td>'.$total.'</td>';
        $r .='<td></td>';       
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

        $r .='<td>Total</td>';
        $r .='<td>Grade</td>';
    }
    $r .='<td>Working Days</td>';
    $r .='<td>Absent</td>';
    $r .='<td>Detention</td>';
    $r .='<td>Class Roll</td>';
    $r .='</tr>';


    return $r;
}


function get_insert_numbers($section_name, $term_name, $subject_name, $exam_type_name) 
{
    global $db;

    $student = $db->find_by_sql("DISTINCT student_id,student_name", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]'  ORDER BY LENGTH(student_id),student_id", "");
//    foreach ($student as $check_student_id) 
//    {
//        $check_exist_all_things = $db->find_by_sql("student_id", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]' AND subject='$subject_name[0]' AND exam_type='$exam_type_name[0]' AND student_id='{$check_student_id['student_id']}'", "");
//        if ($check_exist_all_things == 'No Result Found') {
//            $check_exist_all_things = 'No Result Found';
//            break;
//        }
//    }

    if ($student == 'No Result Found') {
        $r .='<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Student Found</b></div>';
    } elseif ($check_exist_all_things == 'No Result Found') {
        $r .='<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Student Field Not Found in Term :"' . $term_name[0] . '", Section : "' . $section_name[0] . '", Subject : "' . $subject_name[0] . '", Exam-type: "' . $exam_type_name[0] . '" <br> Please recreate all students for this section and term</b></div>';
    } else {
        $r .='<div id="insert_number_header"><b>Term :' . $term_name[0] . ', Section :' . $section_name[0] . ', Subject :' . $subject_name[0] . ', Exam-type: ' . $exam_type_name[0] . '<b></div>';
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="border:1px solid #ccc;font-size:14px;" cellpadding="0" border="1">';
        $r .='<tr style="background:OldLace ;">';
        $r .='<td style="width:120px;"><b>Student Id</b></td>';
        $r .='<td style="width:250px;"><b>Name</b></td>';
        $r .='<td style="width:60px;"><b>Number</b></td>';
        $r .='</tr>';
        $r .='<tr>';
        $n = 0;

        foreach ($student as $stu_val) {
            $r .= '<tr ';
            if ($n % 2 == 0) {
                $r .= ' class="tr1_hover" ';
            } else {
                $r .= ' class="tr2_hover" ';
            }
            $r .= ' >';
            $number = $db->find_by_sql("number", "student_n_numbers", "term='$term_name[0]' AND section='$section_name[0]' AND subject='$subject_name[0]' AND exam_type='$exam_type_name[0]' AND student_id='{$stu_val['student_id']}'", "");
            if($number == 'No Result Found'){ $final_number = ''; }else{ $final_number = $number[0]['number'];  }
            
            $r .='<td>' . substr($stu_val['student_id'],4) . '</td>';
            $r .='<td>' . $stu_val['student_name'] . '</td>';
            $r .='<td class="tr1_hover" style="width:55px;"><input type="text" style="text-align:right;" size="6" name="number[]" value="' .$final_number. '"></td>';
            $r .='<input type="hidden" name="student_id[]" value="' . $stu_val['student_id'] . '">';
            $r .='<input type="hidden" name="student_name[]" value="' . $stu_val['student_name'] . '">';
            $r .='</tr>';
            $n++;
        }

        $r .='</table>';
        $r .='<input type="hidden" name="term_name"      value="' . $term_name[0] . '">';
        $r .='<input type="hidden" name="section_name"   value="' . $section_name[0] . '">';
        $r .='<input type="hidden" name="subject_name"   value="' . $subject_name[0] . '">';
        $r .='<input type="hidden" name="exam_type_name" value="' . $exam_type_name[0] . '">';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" colspan="4"><input type="submit" id="submit_button" name="insert_number" value="Insert"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    }
    return $r;
}


function file_selection($term_name,$section_name,$fourth_subject) 
{
    $r = '';
    $r .='<div id="selection_menu3">';
    $r .='<div style="width:590px;height:20px;text-align:center;background:#ccc;border="1px solid #ccc;"><b>CHOOSE A CSV FILE</b></div>';
    $r .='<form action="" method="post" enctype="multipart/form-data">';
    $r .='<table style="margin:5px auto 0 auto;font-weight:bold;" >';
    $r .='<tr>';
    $r .='<td><input type="file"  name="userfile"  size="22" style="border:1px solid black;"></td>';
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td style="text-align:center;padding-top:15px;" colspan="4"><input type="submit" id="submit_button" name="upload" value="Upload"></td>';
    $r .='</tr>';
    $r.='</table>';
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'">';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'">';
    $r .='<input type="hidden" name="fourth_subject" value="'.$fourth_subject.'">';
    $r .='</form>';
    $r .='</div>';

    return $r;
}


function upload_file($term_name,$section_name,$fourth_subject)
{
    global $db,$getting_4_codes;

    $name = $_FILES['userfile']['name'];
    $csv_file = $_FILES['userfile']['tmp_name'];
    $type = $_FILES['userfile']['type'];
    $size = ($_FILES['userfile']['size'] / 1024) / 1024; //bytes convert to MB
    //$file_parts = pathinfo($test);
    // echo $file_parts['extension'];

    if (!is_file($csv_file)) {
        echo '<div style="padding: 10px;margin:auto;width: 500px;height:10px;"></div><div id="message" style="color:#CC3300;"><b>File Not Found</b></div>';
    } elseif ($size > 100) {
        echo '<div style="padding: 10px;margin:auto;width: 500px;height:10px;"></div><div id="message" style="color:#CC3300;"><b>File size: ' . $size . 'MB (Maximum file size 100MB will be accepted)</b></div>';
    } else {
        $mime_type = mime_content_type($csv_file);
        $extention = pathinfo($name, PATHINFO_EXTENSION);
        if ( $mime_type == 'text/plain' && $extention == 'csv') 
        {
            if (($handle = fopen($csv_file, "r")) !== FALSE) 
            {
               $s = upload_file_validation($handle,$term_name,$section_name,$fourth_subject);
                if (strlen($s) == 0) 
                {
                    if (($handle = fopen($csv_file, "r")) !== FALSE) 
                    {
                        $subject = $db->find_by_sql("DISTINCT subject", "subject_list", "term='$term_name' AND section='$section_name'  ORDER BY LENGTH(priority_order),priority_order", "");
                        foreach ($subject as $subj_val) 
                        {
                            $exm_type[$subj_val['subject']] = $db->find_by_sql("DISTINCT exam_type", "exam_type_list", "term='$term_name' AND section='$section_name' AND subject='{$subj_val['subject']}'  ORDER BY exam_type", "");
                        }

                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
                         {
                                $data0_id = $getting_4_codes.''.$db->escape_value($data[0]);
                                $data1_name = $db->escape_value($data[1]);
                                $p = 2;
                                if ($db->find_by_sql("student_id", "student_n_numbers", "term='$term_name' AND section='$section_name' AND student_id='$data0_id'","") !== 'No Result Found') 
                                {
                                        //update
                                            foreach ($subject as $subj_value) 
                                            {
                                                foreach ($exm_type[$subj_value['subject']] as $exam_type_value) {
                                                    if(strlen($data[$p]) > 0)
                                                    {
                                                        $delete = $db->delete("student_n_numbers", "term='$term_name' AND section='$section_name' AND student_id='$data0_id' AND subject='{$subj_value['subject']}' AND exam_type='{$exam_type_value['exam_type']}'");
                                                        $insert = $db->insert("student_n_numbers", "term,section,student_id,student_name,subject,exam_type,number", "'$term_name','$section_name','$data0_id','$data1_name','{$subj_value['subject']}','{$exam_type_value['exam_type']}','$data[$p]'", "id='115122fau'");
                                                    }
                                                    $p++;
                                                }
                                            }
                                             $update_fourth = $db->update("fourth_subject", "subject='$data[$p]'", "term='$term_name' AND section='$section_name' AND student_id='$data0_id' ");
//                                             $table_array = array("student_account","student_information");
//                                             foreach ($table_array as $table_val)
//                                             {
//                                                $update_student = $db->update("$table_val","student_name='{$data1_name}'"," student_id='{$data0_id}'");
//                                             }
                                             
                                }else{
                                            //insert   for reason of late loading
                                            foreach ($subject as $subj_value) 
                                            {
                                                foreach ($exm_type[$subj_value['subject']] as $exam_type_value) 
                                                {
                                                    if(strlen($data[$p]) > 0)
                                                    {
                                                      $set_all_things = $db->insert("student_n_numbers", "term,section,student_id,student_name,subject,exam_type,number", "'$term_name','$section_name','$data0_id','$data1_name','{$subj_value['subject']}','{$exam_type_value['exam_type']}','$data[$p]'", "id='115122fau'");
                                                    }
                                                    $p++; 
                                                }
                                            }
                                            

                                            $result_discipline_roll = $db->insert("discipline_n_roll", "term,section,student_id", "'$term_name','$section_name','$data0_id'", "term='$term_name' AND section='$section_name'  AND student_id='$data0_id' ");
//
//                                            $insert_student_account = $db->insert("student_account", "student_id,student_name", "'$data0_id','$data1_name'", "student_id='$data0_id'");
//                                            if($insert_student_account == 'already exist'){
//                                              $update_student_account = $db->update("student_account","student_name='$data1_name'", "student_id='$data0_id'");    
//                                            }
//                                            $insert_student_info = $db->insert("student_information", "student_id,student_name", "'$data0_id','$data1_name'", "student_id='$data0_id'");
//                                            if($insert_student_info == 'already exist'){
//                                              $update_student_info = $db->update("student_information","student_name='$data1_name'", "student_id='$data0_id'");    
//                                            }

                                            if($fourth_subject == 'fourth_subject')
                                             {
                                                 if ($db->find_by_sql("subject", "fourth_subject", "term='$term_name' AND section='$section_name' AND student_id='$data0_id'") !== 'No Result Found')
                                                 {
                                                   $update_fourth = $db->update("fourth_subject", "subject='$data[$p]'", "term='$term_name' AND section='$section_name' AND student_id='$data0_id' "); 
                                                 }else{
                                                   $insert_fourth = $db->insert("fourth_subject", "term,section,student_id,subject", "'$term_name','$section_name','$data0_id','$data[$p]'", "term='115122fau'");
                                                 }
                                             }
                                    }
                        }//end of while loop
                         if($insert == 'created succesfully' || $insert == 'not been created' || $set_all_things == 'created succesfully' || $set_all_things == 'not been created')
                         {
                           echo '<div style="padding: 10px;margin:auto;width: 500px;height:5px;"></div><div id="message" style="color:#336600;"><b>File has been uploaded successfully</b></div>';
                         }
                        
                    }
                    fclose($handle);
                }else{
                       echo '<div style="padding: 5px;margin:auto;width: 500px;height:5px;"></div><div id="message"  style="color:#CC3300;overflow:auto;"><b>'.$s.'</b></div>';
                     }
            }
            
        } else {
                 echo '<div style="padding: 10px;margin:auto;width: 500px;height:5px;"></div><div id="message" style="color:#CC3300;"><b>File Type Not Supported</b></div>';
              }
    }
}


function upload_file_validation($handle,$term_name,$section_name,$fourth_subject)
{ 
    global $db,$available_section,$getting_4_codes;

        foreach ($available_section as $available_section_val){ $sec[] = $available_section_val['section'];}
        $count_section = count($sec);

        $subject = $db->find_by_sql("DISTINCT subject", "subject_list", "term='$term_name' AND section='$section_name'  ORDER BY LENGTH(priority_order),priority_order ", "");
        foreach ($subject as $subj_val) {
            $exm_type[$subj_val['subject']] = $db->find_by_sql("DISTINCT exam_type", "exam_type_list", "term='$term_name' AND section='$section_name' AND subject='{$subj_val['subject']}'  ORDER BY exam_type", "");
        } 
        $count_for_limit = 0;

       while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
       {
            ++$count_for_limit;
            $data0_id = $db->escape_value($data[0]);
            $data1_name = $db->escape_value($data[1]);
            if(preg_match("/([',\"])/", $data[1]))
            {
               return 'Invalid Data Found for <br>Student Id : '.$data[0].', Name : '.$data[1].'<br>(Invalid! student name)<br>' ;						
            }
            
            $p = 2;
                if (strlen($data[0]) == 0 )
                {
                  return 'Id can not be empty<br>' ;							
                }
//                for($j=0;$j<$count_section;$j++)
//                {
//                    $escape_student_id = $getting_4_codes.''.$data0_id;
//                    $escape_student_name = $data1_name;
//                    if($sec[$j] !== $section_name)
//                    {  $check = $db->query("SELECT * FROM student_n_numbers WHERE section='$sec[$j]' AND student_id='$escape_student_id'");
//                       if ($db->num_rows($check)) 
//                       {
//                           $exist_student_id = $data0_id;
//                           $exist_sec_name   =  $sec[$j];
//                           return  'Student id: "' . $exist_student_id . '" already exist in Section: "' . $exist_sec_name . '"<br>Student Id must be unique';
//                       }
//                    }
//                }

                foreach ($subject as $subj_value) 
                {
                    foreach ($exm_type[$subj_value['subject']] as $exam_type_value) 
                    {
                        
                            $full_mark = $db->find_by_sql("full_mark","exam_type_list","term='$term_name' AND section='$section_name' AND subject='{$subj_value['subject']}' AND exam_type='{$exam_type_value['exam_type']}'","");
                            if($full_mark[0]['full_mark'] == '')
                            { 
                                if ( preg_match("/^[0-9.]*$/", $data[$p]) == NULL )
                                {
                                   return 'Invalid Data Found for <br>Student Id : '.$data[0].', Name : '.$data[1].'<br>Subject : '.$subj_value['subject'].', Exam-type : '.$exam_type_value['exam_type'].'<br>Number : '.$data[$p].'(Invalid! Float Numbers Only)<br>' ;						
                                }
                            }else{
                                $mark = $full_mark[0]['full_mark'];
                                if ( preg_match("/^[0-9.]*$/", $data[$p]) == NULL )
                                {
                                   return 'Invalid Data Found for <br>Student Id : '.$data[0].', Name : '.$data[1].'<br>Subject : '.$subj_value['subject'].', Exam-type : '.$exam_type_value['exam_type'].'<br>Number : '.$data[$p].'(Invalid! Float Numbers Only)<br>' ;						
                                }elseif($data[$p] > $mark){
                                   return 'Invalid Data Found for <br>Student Id : '.$data[0].', Name : '.$data[1].'<br>Subject : '.$subj_value['subject'].', Exam-type : '.$exam_type_value['exam_type'].'<br>Number : '.$data[$p].'(Invalid! Full Mark:'.$mark.')<br>' ;						
                                }
                            }
                            $p++;
                    }
                }
                if($fourth_subject == 'fourth_subject')
                {
                        if (strlen($data[$p]) == 0 )
                        {
                           return '4th subject field can not be empty<br>' ;							
                        }elseif($db->find_by_sql("subject","subject_list","term='$term_name' AND section='$section_name' AND subject='{$data[$p]}' ","") == 'No Result Found')
                        {
                            return '4th Subject :"'.$data[$p].'" not found in Term :"'.$term_name.'" and Section :"'.$section_name.'" for Student Id : '.$data[0].', Name : '.$data[1].'<br>' ;
                        }
                }
//                if($count_for_limit > 20)
//                {
//                    return 'You can upload maximum 20 student\'s number at a time'.$count_for_limit  ;
//                }
        }
}

        
function set_percentage($count_multi_term, $term_name, $multi_term_name,$writed_multi_percent,$writed_percent) 
{
    global $available_section;
    $r .='<div id="set_percentage">';
    $r .='<div style="width:350px;height:20px;text-align:center;color:#fff;background:#0099CC;"><b>GENERATE AGGREGATE GRADE</b></div>';
    $r .='<div style="width:350px;height:20px;font-size:13px;text-align:center;background:#FFCC99;"><b>Set percentage to aggr. with: ' . $term_name[0] . '</b></div>';
    $r .='<form action="" method="post" >';
    $r .='<div style="height:130px;overflow:auto;">';
    $r .='<table style="margin:auto;border:1px solid #999;" border="1">';
    $r .='<tr style="background:">';
    $r .='<td><b>Term<b></td>';
    $r .='<td><b>Percentage<b></td>';
    $r .='</tr>';
    for ($i = 0; $i < $count_multi_term; $i++) {
        $r .= '<tr ';
        if ($i % 2 == 0) {
            $r .= ' class="tr1_hover" ';
        } else {
            $r .= ' class="tr2_hover" ';
        }
        $r .= ' >';
        $r .='<input type="hidden" name="multi_term_name[]" value="' . $multi_term_name[$i] . '" >';
        $r .='<td style="width:160px;padding:0;">' . $multi_term_name[$i] . '</td>';
        $r .='<td style="width:20px;padding:0;"><input type="text" style="text-align:right;" size="3px" name="multi_percent[]" value="'.$writed_multi_percent[$i].'"  required>%</td>';
        $r .='</tr>';
    }
    $r .='<tr class="tr2_hover">';
    $r .='<td style="width:160px;padding:0;">' . $term_name[0] . '</td>';
    $r .='<td style="width:20px;padding:0;"><input type="text" style="text-align:right;" size="3px" name="percent" value="'.$writed_percent.'" required>%</td>';
    $r .='</tr>';
    $r .='</tr>';
    $r .='</table>';
    $r .='</div>';
    $r .='<table style="margin:auto;" cellpadding="5">';
    $r .='<tr><td>';
    $r .='<select name="section_name" title="Available Sections">';
    $r .='<option value="Available Sections">Available Sections</option>';
    $r .='<optgroup label="Sections :">';
    foreach ($available_section as $value) {
        $r .='<option value="' . $value['section'] . '" >' . $value['section'] . '</option>';
    }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td></tr>';
    $r .='<tr>';
    $r .='<td><input type="submit" id="submit_button" name="generate" value="Generate"></td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="term_name" value="' . $term_name[0] . '" >';
    $r .='<input type="hidden" name="count_multi_term" value="' . $count_multi_term . '" >';
    $r .='</form>';
    $r .='</div>';

    return $r;
}


function get_percentage_grade() 
{
    global $db;
    $count_multi_term = $_POST['count_multi_term'];
    $term_name = $_POST['term_name'];
    $multi_term_name = $_POST['multi_term_name'];
    $multi_percent = $_POST['multi_percent'];
    $percent = $_POST['percent'];
    $section_name = $_POST['section_name'];
    
    $main_section[] = array('section'=>$section_name); //this is for single section work at a time
    //$main_section = $db->find_by_sql("DISTINCT section", "add_n_grade", "term='$term_name'",""); this is for all section in work together

    foreach ($main_section as $sec_val) {
        $main_student[$sec_val['section']] = $db->find_by_sql("DISTINCT student_id,student_name", "add_n_grade", "term='$term_name' AND section='{$sec_val['section']}'", "");
        $main_subject[$sec_val['section']] = $db->find_by_sql("DISTINCT subject", "add_n_grade", "term='$term_name' AND section='{$sec_val['section']}'", "");
    }

    foreach ($main_section as $sec_val) {
        foreach ($main_subject[$sec_val['section']] as $subj_val) {
            $grade[$subj_val['subject']] = $db->find_by_sql("num_from,num_to,grade,point", "grade_list", "term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}'", "");
        }
    }
    $p = 0;
    foreach($multi_term_name as $term_name_value)
    {
        $term_priority = $db->find_by_sql("term_priority", "terms", "term='$term_name_value'", "");
        if ($db->insert("aggregate_percent_term", "base_term,section,percent_agre,percentage,percent_term_priority", "'$term_name','$section_name','$term_name_value','$multi_percent[$p]','{$term_priority[0]['term_priority']}'", "base_term='$term_name' AND section='$section_name' AND percent_agre='$term_name_value'") == 'already exist') {
          $update_aggregate_term = $db->update("aggregate_percent_term", "percentage='$multi_percent[$p]',percent_term_priority='{$term_priority[0]['term_priority']}'", "base_term='$term_name' AND section='$section_name' AND percent_agre='$term_name_value' ");
        }
        $p++;
    }
    $term_priority = $db->find_by_sql("term_priority", "terms", "term='$term_name'", "");
    if ($db->insert("aggregate_percent_term", "base_term,section,percent_agre,percentage,percent_term_priority", "'$term_name','$section_name','$term_name','$percent','{$term_priority[0]['term_priority']}'", "base_term='$term_name' AND section='$section_name' AND percent_agre='$term_name' ") == 'already exist') {
          $update_aggregate_term = $db->update("aggregate_percent_term", "percentage='$percent',percent_term_priority='{$term_priority[0]['term_priority']}'", "base_term='$term_name' AND section='$section_name' AND percent_agre='$term_name' ");
        }

    $count_point_n_sub = 0;
    foreach ($main_section as $sec_val) 
    {
        foreach ($main_student[$sec_val['section']] as $stu_val) 
        {
            //check student number exist
            for ($i = 0; $i < $count_multi_term; $i++) 
            {
              $exist_number = $db->find_by_sql("addition", "add_n_grade", "term='$multi_term_name[$i]' AND section='{$sec_val['section']}' AND student_id='{$stu_val['student_id']}'", "");
              if($exist_number !== 'No Result Found') 
              {
                    foreach ($main_subject[$sec_val['section']] as $subj_val) 
                    {
                        $main_number = $db->find_by_sql("addition", "add_n_grade", "term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'", "");
                           if($main_number !== 'No Result Found')
                           {
                                    $priority_order = $db->find_by_sql("priority_order", "add_n_grade", "term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' ", "");
                                    $fourth_subject = $db->find_by_sql("subject", "fourth_subject", "term='$term_name' AND section='{$sec_val['section']}' AND student_id='{$stu_val['student_id']}' ", "");
                                    if ($fourth_subject == 'No Result Found') {
                                        $fourth_subject = 'No Result Found';
                                    } else {
                                        $fourth_subject = $fourth_subject[0]['subject'];
                                    }

                                    //print_r($main_number);
                                    $percent_addition = ($main_number[0]['addition'] * $percent) / 100;
                                    
                                    $added_percent = $percent . '%';
                                    for ($i = 0; $i < $count_multi_term; $i++) 
                                    {
                                        $multi_number = $db->find_by_sql("addition", "add_n_grade", "term='$multi_term_name[$i]' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'", "");
                                        if($multi_number !== 'No Result Found')
                                        {
                                            $percent_addition += ($multi_number[0]['addition'] * $multi_percent[$i]) / 100;
                                            
                                            $added_percent .= ' + ' . $multi_percent[$i] . '%';
                                        }

                                    }
                                    $percent_addition = round($percent_addition); // make aggrigate to round number

                                    foreach ($grade[$subj_val['subject']] as $grade_val) 
                                    {
                                        if (($percent_addition >= $grade_val['num_from']) && ($percent_addition <= $grade_val['num_to'])) 
                                        {
                                                $desire_grade = $grade_val['grade']; //grade_making
                                                $desire_point = $grade_val['point'];
                                                if ($fourth_subject == 'No Result Found') {
                                                    $fourth_exist = 'Not set';
                                                } else {
                                                    $fourth_exist = 'Set';
                                                }

                                                if ($subj_val['subject'] !== $fourth_subject) 
                                                {
                                                    $count_point_n_sub++;
                                                    if (($desire_point == 0)) {
                                                        $fail = 'fail';
                                                    $catch_all_grade_point += $grade_val['point'];
                                                        $gpa = $desire_grade;
                                                    }
                                                }
                                        }
                                    }

                                    if (empty($desire_grade) == TRUE) {
                                        $fail = 'fail';
                                        $gpa = '?';
                                        $desire_grade = '?';
                                        $desire_point = '?';
                                    }
                                    $insert = $db->insert("percent_add_n_grade", "term,section,subject,priority_order,student_id,student_name,addition,grade,point,percentage", "'$term_name','{$sec_val['section']}','{$subj_val['subject']}','{$priority_order[0]['priority_order']}','{$stu_val['student_id']}','{$stu_val['student_name']}','$percent_addition','$desire_grade','$desire_point','$added_percent'", "term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'");
                                    if ($insert == 'already exist') {
                                        $update = $db->update("percent_add_n_grade", "addition='$percent_addition',grade='$desire_grade',point='$desire_point',percentage='$added_percent'", "term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'");
                                    }
                                    $percent_addition = '';
                                    
                                    $added_percent = '';
                                    $desire_grade = '';
                                    $desire_point = '';
                           }// end of main number
                           
                    }// end of main subject loop

                                if ($fail !== 'fail') {
                                    $gpa = $catch_all_grade_point / $count_point_n_sub;
                                }
                                $insert_gpa = $db->insert("gpa_result", "term,section,student_id,gpa,fourth,percent", "'$term_name','{$sec_val['section']}','{$stu_val['student_id']}','$gpa','$fourth_exist','Yes'", "term='$term_name' AND section='{$sec_val['section']}' AND student_id='{$stu_val['student_id']}' AND percent='Yes'");
                                if ($insert_gpa == 'already exist') {
                                    $update_gpa = $db->update("gpa_result", "gpa='$gpa',fourth='$fourth_exist'", "term='$term_name' AND section='{$sec_val['section']}' AND student_id='{$stu_val['student_id']}' AND percent='Yes'");
                                }
                                $count_point_n_sub = 0;
                                $catch_all_grade_point = '';
                                $gpa = '';
                                $fail = '';
                                $fourth_subject = '';
                                
              }//end of check exist number
            }// end of student id check exist for loop
        }
    }

    echo '<div style="padding:1px;margin:auto;width: 500px;height:2px;"></div>';
    echo set_percentage($count_multi_term,array($term_name),$multi_term_name,$multi_percent,$percent);

    if (($update == 'updated succesfully') || ($insert == 'created succesfully') || ($update == 'not been updated')) {
        $r .='<div style="margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Percentage result has been created successfully for Section:'.$section_name.', Term: '.$term_name.' and Percentage:( '.$term_name.' = ' . $percent . '%';
        for ($i = 0; $i < $count_multi_term; $i++) {
            $r .= ' + ' . $multi_term_name[$i] . ' = ' . $multi_percent[$i] . '%';
        }
        $r .=')</b></div>';
    }
    return $r;
}


function validate_percent($count_multi_term, $term_name, $multi_term_name) 
{
    global $db;

    $check_exist_main_term = $db->find_by_sql("*", "add_n_grade", "term='$term_name[0]'", "");
    if ($check_exist_main_term == 'No Result Found') {
        return 'No Exam Result Found in Term: "' . $term_name[0] . '"';
    }

    for ($i = 0; $i < $count_multi_term; $i++) {
        $check_exist_term = $db->find_by_sql("*", "add_n_grade", "term='$multi_term_name[$i]'", "");
        if ($check_exist_term == 'No Result Found') {
            return 'No Exam Result Found in Term: "' . $multi_term_name[$i] . '"';
        }
    }
}


function total_aggregate_selection($multi_selected_terms, $term_name) 
{
    global $available_terms,$available_section;
    $r = '';
    $r .='<div id="set_percentage">';
    $r .='<div style="width:350px;height:20px;text-align:center;color:#fff;background:#0099CC;"><b>GENERATE AGGREGATE GRADE</b></div>';
    $r .='<div style="width:350px;height:20px;font-size:13px;text-align:center;background:#FFCC99;"><b>Add term to aggr. with: ' . $term_name[0] . '</b></div>';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:5px auto 0 auto;font-weight:bold;">';
    $r .='<tr>';
    $r .='<td>';
    $r .='<div style="height:130px;overflow:auto;">';
    $r .='<table style="border:1px solid #999;" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Term</b></td>';
    $r .='</tr>';
    $j = 0;
    foreach ($available_terms as $value) {
        $r .= '<tr ';
        if ($j % 2 == 0) {
            $r .= ' class="tr1_hover" ';
        } else {
            $r .= ' class="tr2_hover" ';
        }
        $r .= ' >';
        if ($term_name[0] !== $value['term']) {
            $r .='<td><input type="checkbox" class="case" name="multi_term_name[]" value="' . $value['term'] . '"  ' . $multi_selected_terms[$value['term']] . '></td>';
            $r .='<td style="width:160px;">' . $value['term'] . '</td>';
            $r .='</tr>';
        }
        $j++;
    }
    $r .='</table>';
    $r .='</div>';
    $r .='</tr>';
    $r .='</td>';
    $r.='</table>';
    $r .='<table style="margin:auto;">';
    $r .='<tr><td colspan="8">';
    $r .='<select name="section_name" title="Available Sections">';
    $r .='<option value="Available Sections">Available Sections</option>';
    $r .='<optgroup label="Sections :">';
    foreach ($available_section as $value) {
        $r .='<option value="' . $value['section'] . '" >' . $value['section'] . '</option>';
    }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td></tr>';
    $r .='<tr>';
    $r .='<td style="padding:10px 15px 0 0;" colspan="4"><input type="checkbox" id="selectall"><b>Select All</b></td>';
    $r .='<td style="padding-top:10px;" colspan="4"><input type="submit" id="submit_button" name="process" value="Generate"></td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="term_name_again" value="' . $term_name[0] . '">';
    $r .='</form>';
    $r .='</div>';

    return $r;
}


function get_total_aggregate($count_multi_term, $term_name, $multi_term_name) 
{
    global $db;
    $term_name = $term_name[0];
    $section_name = $_POST['section_name'];
    
    $main_section[] = array('section'=>$section_name); //this is for single section work at a time

   // $main_section = $db->find_by_sql("DISTINCT section", "add_n_grade", "term='$term_name'", ""); this is for all section in work together

    foreach ($main_section as $sec_val) {
        $main_student[$sec_val['section']] = $db->find_by_sql("DISTINCT student_id,student_name", "add_n_grade", "term='$term_name' AND section='{$sec_val['section']}'", "");
        $main_subject[$sec_val['section']] = $db->find_by_sql("DISTINCT subject", "subject_list", "section='{$sec_val['section']}'", "");
    }
    
    foreach($multi_term_name as $term_name_value)
    {
        $term_priority = $db->find_by_sql("term_priority", "terms", "term='$term_name_value'", "");
        if ($db->insert("aggregate_total_term", "base_term,section,total_agre,total_term_priority", "'$term_name','$section_name','$term_name_value','{$term_priority[0]['term_priority']}'", "base_term='$term_name' AND section='$section_name' AND total_agre='$term_name_value' ") == 'already exist') {
          $update_aggregate_term = $db->update("aggregate_total_term", "total_term_priority='{$term_priority[0]['term_priority']}'", "base_term='$term_name' AND section='$section_name' AND total_agre='$term_name_value' ");
        }
    }
    $term_priority = $db->find_by_sql("term_priority", "terms", "term='$term_name'", "");
    if ($db->insert("aggregate_total_term", "base_term,section,total_agre,total_term_priority", "'$term_name','$section_name','$term_name','{$term_priority[0]['term_priority']}'", "base_term='$term_name' AND section='$section_name' AND total_agre='$term_name' ") == 'already exist') {
          $update_aggregate_term = $db->update("aggregate_total_term", "total_term_priority='{$term_priority[0]['term_priority']}'", "base_term='$term_name' AND section='$section_name' AND total_agre='$term_name'");
        }

    foreach ($main_section as $sec_val) 
    {
        foreach ($main_student[$sec_val['section']] as $stu_val) 
        {
            //check student number exist
            for ($i = 0; $i < $count_multi_term; $i++) 
            {
              $exist_number = $db->find_by_sql("addition", "add_n_grade", "term='$multi_term_name[$i]' AND section='{$sec_val['section']}' AND student_id='{$stu_val['student_id']}'", "");
              
              if($exist_number !== 'No Result Found')
              {
            
                    foreach ($main_subject[$sec_val['section']] as $subj_val) 
                    {
                        $main_number = $db->find_by_sql("addition", "add_n_grade", "term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'", "");
                        if($main_number !== 'No Result Found')
                         {
                                $priority_order = $db->find_by_sql("priority_order", "add_n_grade", "term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' ", "");
                                $pass_mark = $db->find_by_sql("mark","pass_mark_list","term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' ","");
                                $total_addition += $main_number[0]['addition'];
                                $per_subject_total = $main_number[0]['addition'];
                                if($pass_mark !== 'No Result Found'){$aggregate_pass_mark = $pass_mark[0]['mark'];}else{ $aggregate_pass_mark = 'Not set'; }

                                for ($i = 0; $i < $count_multi_term; $i++) 
                                {
                                    $multi_number = $db->find_by_sql("addition", "add_n_grade", "term='$multi_term_name[$i]' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'", "");
                                    if($multi_number !== 'No Result Found')
                                    {
                                        $total_addition += $multi_number[0]['addition'];
                                        $per_subject_total += $multi_number[0]['addition'];
                                        if($aggregate_pass_mark !== 'Not set')
                                        {
                                            $multi_pass_mark = $db->find_by_sql("mark","pass_mark_list","term='$multi_term_name[$i]' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' ","");
                                            if($pass_mark !== 'No Result Found'){$aggregate_pass_mark += $pass_mark[0]['mark'];}else{ $aggregate_pass_mark = 'Not set'; }
                                        }
                                    }
                                }
                                if($aggregate_pass_mark !== 'Not set'){ if($per_subject_total < $aggregate_pass_mark){ $pass_value='Not Pass'; }else{ $pass_value = 'Pass';} }else{ $pass_value = 'Not set'; }

                                $insert = $db->insert("total_add", "term,section,subject,priority_order,student_id,student_name,addition,pass", "'$term_name','{$sec_val['section']}','{$subj_val['subject']}','{$priority_order[0]['priority_order']}','{$stu_val['student_id']}','{$stu_val['student_name']}','$per_subject_total','$pass_value'", "term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'");
                                if ($insert == 'already exist') {
                                    $update = $db->update("total_add", "priority_order='{$priority_order[0]['priority_order']}',addition='$per_subject_total',pass='$pass_value'", "term='$term_name' AND section='{$sec_val['section']}' AND subject='{$subj_val['subject']}' AND student_id='{$stu_val['student_id']}'");
                                }
                                $per_subject_total = '';
                                $aggregate_pass_mark='';
                                $pass_value='';
                         }
                    }

                    $insert_total = $db->insert("total_result", "term,section,student_id,total,aggregate", "'$term_name','{$sec_val['section']}','{$stu_val['student_id']}','$total_addition','Yes'", "term='$term_name' AND section='{$sec_val['section']}' AND student_id='{$stu_val['student_id']}' AND aggregate='Yes'");
                    if ($insert_total == 'already exist') {
                        $update_total = $db->update("total_result", "total='$total_addition'", "term='$term_name' AND section='{$sec_val['section']}' AND student_id='{$stu_val['student_id']}' AND aggregate='Yes'");
                    }
                    $total_addition = '';
                } //End of check student number exist
            } //End of check student number exist for loop
        }
    }



    if (($update == 'updated succesfully') || ($insert == 'created succesfully') || ($update == 'not been updated')) {
        $r .='<div style="padding:20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Total number aggregate result has been created successfully for Section:'.$section_name.' and Term: (' . $term_name;
        for ($i = 0; $i < $count_multi_term; $i++) {
            $r .= ' + ' . $multi_term_name[$i];
        }
        $r .=')</b></div>';
    }
    return $r;
}


function ed_name_id($section_name)
{
    global $db;
    $belong_term = $db->find_by_sql("term","section_list","section='$section_name'","");
    foreach ($belong_term as $belong_term_val){ $term_name[] = $belong_term_val['term'];}
    $student = $db->find_by_sql("DISTINCT student_id","student_n_numbers","section='$section_name'  ORDER BY LENGTH(student_id),student_id","");
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header" style="font-size:10px;"><b>EDIT & DELETE NAME & ID in SECTION: '.$section_name.' belongs TERM: '.join(",",$term_name).'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Student ID</b></td>';
    $r .='<td><b>Student Name</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($student as $value)
        {  
          $name = $db->find_by_sql("DISTINCT student_name","student_n_numbers","section='$section_name' AND student_id='{$value['student_id']}'","");
          $id = $db->find_by_sql("id","student_n_numbers","student_id='{$value['student_id']}' AND section='$section_name'","1");
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="selected_id[]" value="'.$id[0]['id'].'" ></td>';
            $r .='<td style="width:130px;">'.substr($value['student_id'],4).'</td>';
            $r .='<td style="width:200px;">'.$name[0]['student_name'].'</td>';
            $r .='</tr>';
            $j++;
         }
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:left;padding:7px 0 0 0;"><input type="checkbox" id="selectall" /><b>Select All</b>
          &nbsp; &nbsp; <input type="image" name="edit" alt="submit" value="submit" src="images/Edit.png" width="20" height="20" ">
          &nbsp; &nbsp; <input type="image" name="selected_delete" alt="submit" value="submit" src="images/DeleteRed.png" width="20" height="20" onclick="return confirm(\'Are you sure you want to delete?\');"></td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}


function edit_name_id($selected_id,$edited_student_id,$edited_student_name,$selected_student_id,$section_name)
{   global $db;
    $belong_term = $db->find_by_sql("term","section_list","section='$section_name'","");
    foreach ($belong_term as $belong_term_val){ $term_name[] = $belong_term_val['term'];}
    $r.='<div style="margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header" style="font-size:10px;"><b>EDIT & DELETE NAME & ID in SECTION: '.$section_name.' belongs TERM: '.join(",",$term_name).'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td><b>Student Id</b></td>';
    $r .='<td><b>Student Name</b></td>';
    $r .='</tr>';
      $count_selected_id = count($selected_id); 
         $j = 0;
     for($i=0;$i<$count_selected_id; $i++)
        {
          $student = $db->find_by_sql("student_id,student_name","student_n_numbers"," id='$selected_id[$i]'","");
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<input type="hidden" name="selected_id[]" value="'.$selected_id[$i].'" >';
            
            if(is_array($edited_student_id) == FALSE){
                $r .='<td style="width:130px;"><input type="text" name="edited_student_id[]"   value="'.substr($student[0]['student_id'],4).'" ></td>';
                $r .='<td style="width:200px;"><input type="text" size="30px" name="edited_student_name[]" value="'.$student[0]['student_name'].'" ></td>';
                $r .='<input type="hidden" name="selected_student_id[]" value="'.$student[0]['student_id'].'" >';
            }
            else{
                $r .='<td style="width:130px;"><input type="text" name="edited_student_id[]" value="'.$edited_student_id[$i].'" ></td>';
                $r .='<td style="width:200px;"><input type="text" size="30px" name="edited_student_name[]"   value="'.$edited_student_name[$i].'" ></td>';
                $r .='<input type="hidden" name="selected_student_id[]" value="'.$selected_student_id[$i].'" >';
            }
            $r .='</tr>';
            $j++;
         }
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:right;padding:10px 0 0 0;"><input type="submit" id="submit_button" name="finally_edit" value="Edit"  onclick="return confirm(\'If you continue student account will also be edited. Are you sure you want to edit ?\');">
          </td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}


function edit_name_id2($edited_student_id,$edited_student_name,$selected_id,$section_name)
{   global $db,$user_db_select;
    
    $belong_term = $db->find_by_sql("term","section_list","section='$section_name'","");

    $student = $db->find_by_sql("student_id,student_name","student_n_numbers","id='$selected_id'","");
    $table_array = array("student_n_numbers","add_n_grade","percent_add_n_grade");
    $table_array2 = array("fourth_subject","gpa_result","total_add","total_result","discipline_n_roll");

     foreach ($table_array as $table_val)
     {
        foreach ($belong_term as $belong_term_val)
        {
          $update_student = $db->update("$table_val","student_id='$edited_student_id',student_name='{$edited_student_name}'"," student_id='{$student[0]['student_id']}' AND section='$section_name' AND term='{$belong_term_val['term']}'");
        }
     }
     foreach ($table_array2 as $table_val2)
     {
        foreach ($belong_term as $belong_term_val)
        {
          $update_student = $db->update("$table_val2","student_id='$edited_student_id'"," student_id='{$student[0]['student_id']}' AND section='$section_name' AND term='{$belong_term_val['term']}'");
        }
     }
     $update_account = $db->update("student_account","student_id='$edited_student_id',student_name='{$edited_student_name}'"," student_id='{$student[0]['student_id']}'");
     $update_info    = $db->update("student_information","student_id='$edited_student_id',student_name='{$edited_student_name}'"," student_id='{$student[0]['student_id']}'");

     $db->select_db("pdbdorg_rp_student_users");
     $update_user = $db->update("authentication","username='$edited_student_id'"," username='{$student[0]['student_id']}'");
     $db->select_db($user_db_select);
     
     return $update_student;
}


function delete_name_id($selected_id,$section_name)
{ global $db;
     $belong_term = $db->find_by_sql("term","section_list","section='$section_name'","");
     $student = $db->find_by_sql("student_id","student_n_numbers","id='$selected_id'","");
     $table_array = array("student_n_numbers","add_n_grade","percent_add_n_grade","fourth_subject","gpa_result","total_add","total_result","discipline_n_roll");
     
     if($student !== 'No Result Found')
     {  foreach ($table_array as $table_val)
        {
           foreach ($belong_term as $belong_term_val)
           {
             $delete_student = $db->delete("$table_val","student_id='{$student[0]['student_id']}' AND section='$section_name' AND term='{$belong_term_val['term']}'");
             if(empty($delete_student) == FALSE){ $actual_delete = $delete_student; }
           }
        }
     }
     return $actual_delete;
}












function selection_menu($title_name) 
{
    global $available_terms, $available_section;
    $r = '';
    $r .='<div id="selection_menu">';
    $r .='<div style="width:350px;height:20px;text-align:center;color:#fff;background:#0099CC;"><b>' . $title_name . '</b></div>';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:5px auto 0 auto;font-weight:bold;">';
    $r .='<tr>';
    $r .='<td style="padding-right:15px;">';
    $r .='<select name="term_name" title="Available Terms"  id="term" onchange="handle_select(this)">';
    $r .='<option value="Available Terms">Available Terms</option>';
    $r .='<optgroup label="Terms :">';
    foreach ($available_terms as $value) {
        $r .='<option value="' . $value['term'] . '" >' . $value['term'] . '</option>';
    }
    $r .='</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='<td>';
    $r .='<select name="section_name" title="Available Sections"  id="section" onchange="this.form.submit()" disabled>';
    $r .='<option value="Available Section">Available Sections</option>';
    $r .='<optgroup label="Sections :">';
    foreach ($available_section as $value) {
        $r .='<option value="' . $value['section'] . '" >' . $value['section'] . '</option>';
    }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="hidden" value="">';
    $r .='</form>';
    $r .='</div>';

    return $r;
}


function selection_menu2($selected_term, $selected_section, $selected_subject, $selected_exam_type) 
{
    global $available_terms, $available_section, $available_subject, $available_exam_type;
    $r = '';
    $r .='<div id="selection_menu2">';
    $r .='<div style="width:750px;height:20px;text-align:center;color:#fff;background:#0099CC;"><b>INSERT NUMBER</b></div>';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:5px auto 0 auto;font-weight:bold;">';
    $r .='<tr>';
    $r .='<td style="padding-right:15px;">';
    $r .='<select name="term_name" title="Available Terms">';
    $r .='<option value="Available Terms">Available Terms</option>';
    $r .='<optgroup label="Terms :">';
    foreach ($available_terms as $value) {
        $r .='<option value="' . $value['term'] . '"  ' . $selected_term[$value['term']] . '>' . $value['term'] . '</option>';
    }
    $r .='</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='<td style="padding-right:15px;">';
    $r .='<select name="section_name" title="Available Sections">';
    $r .='<option value="Available Sections">Available Sections</option>';
    $r .='<optgroup label="Sections :">';
    foreach ($available_section as $value) {
        $r .='<option value="' . $value['section'] . '"  ' . $selected_section[$value['section']] . '>' . $value['section'] . '</option>';
    }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='<td style="padding-right:15px;">';
    $r .='<select name="subject_name" title="Available Subjects">';
    $r .='<option value="Available Subjects">Available Subjects</option>';
    $r .='<optgroup label="Subjects :">';
    foreach ($available_subject as $value) {
        $r .='<option value="' . $value['subject'] . '"  ' . $selected_subject[$value['subject']] . '>' . $value['subject'] . '</option>';
    }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='<td>';
    $r .='<select name="exam_type_name" title="Available Exam-type">';
    $r .='<option value="Available Exam-type">Available Exam-type</option>';
    $r .='<optgroup label="Exam-type :">';
    foreach ($available_exam_type as $value) {
        $r .='<option value="' . $value['exam_type'] . '"  ' . $selected_exam_type[$value['exam_type']] . '>' . $value['exam_type'] . '</option>';
    }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td style="text-align:center;padding-top:15px;" colspan="4"><input type="submit" id="submit_button" name="submit" value="Submit"></td>';
    $r .='</tr>';
    $r.='</table>';
    $r .='</form>';
    $r .='</div>';

    return $r;
}


function selection_menu4($selected_terms) 
{
    global $available_terms;
    $r = '';
    $r .='<div id="selection_menu4">';
    $r .='<div style="width:350px;height:20px;text-align:center;color:#fff;background:#0099CC;"><b>GENERATE AGGREGATE GRADE</b></div>';
    $r .='<div style="width:350px;height:20px;font-size:13px;text-align:center;background:#FFCC99;"><b>Choose a term to make aggr. under that term</b></div>';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:55px auto 0 auto;font-weight:bold;">';
    $r .='<tr>';
    $r .='<td>';
    $r .='<select name="term_name" title="Available Terms"  id="term" onchange="this.form.submit()">';
    $r .='<option value="Available Terms">Available Terms</option>';
    $r .='<optgroup label="Terms :">';
    foreach ($available_terms as $value) {
        $r .='<option value="' . $value['term'] . '"  ' . $selected_terms[$value['term']] . '>' . $value['term'] . '</option>';
    }
    $r .='</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r.='</table>';
    $r .='<input type="hidden" name="1st_box_menu">';
    $r .='</form>';
    $r .='</div>';

    return $r;
}


function selection_menu5($multi_selected_terms, $term_name) 
{
    global $available_terms;
    $r = '';
    $r .='<div id="selection_menu4">';
    $r .='<div style="width:350px;height:20px;text-align:center;color:#fff;background:#0099CC;"><b>GENERATE AGGREGATE GRADE</b></div>';
    $r .='<div style="width:350px;height:20px;font-size:13px;text-align:center;background:#FFCC99;"><b>Add term to aggr. with: ' . $term_name[0] . '</b></div>';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:5px auto 0 auto;font-weight:bold;">';
    $r .='<tr>';
    $r .='<td>';
    $r .='<div style="height:100px;overflow:auto;">';
    $r .='<table style="border:1px solid #999;" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Term</b></td>';
    $r .='</tr>';
    $j = 0;
    foreach ($available_terms as $value) {
        $r .= '<tr ';
        if ($j % 2 == 0) {
            $r .= ' class="tr1_hover" ';
        } else {
            $r .= ' class="tr2_hover" ';
        }
        $r .= ' >';
        if ($term_name[0] !== $value['term']) {
            $r .='<td><input type="checkbox" class="case" name="multi_term_name[]" value="' . $value['term'] . '"  ' . $multi_selected_terms[$value['term']] . '></td>';
            $r .='<td style="width:160px;">' . $value['term'] . '</td>';
            $r .='</tr>';
        }
        $j++;
    }
    $r .='</table>';
    $r .='</div>';
    $r .='</tr>';
    $r .='</td>';
    $r.='</table>';
    $r .='<table style="margin:auto;">';
    $r .='<tr>';
    $r .='<td style="padding:10px 15px 0 0;" colspan="4"><input type="checkbox" id="selectall"><b>Select All</b></td>';
    $r .='<td style="padding-top:10px;" colspan="4"><input type="submit" id="submit_button" name="process" value="Submit"></td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="term_name_again" value="' . $term_name[0] . '">';
    $r .='</form>';
    $r .='</div>';

    return $r;
}


function selection_menu6($title_name) 
{
    global $db,$available_section;
    
    $r = '';
    $r .='<div id="selection_menu">';
    $r .='<div style="width:350px;height:20px;text-align:center;color:#fff;background:#0099CC;"><b>' . $title_name . '</b></div>';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:5px auto 0 auto;font-weight:bold;">';
    $r .='<tr>';   
    $r .='<td>';
    $r .='<select name="section_name" title="Available Sections"  id="section" onchange="this.form.submit()">';
    $r .='<option value="Available Section">Available Sections</option>';
    $r .='<optgroup label="Sections :">';

        foreach($available_section as $sec_val){
        $r .='<option value="' . $sec_val['section'] . '" >' . $sec_val['section'] . '</option>';
        }
        $r .= '</optgroup>';
    
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r .='</table>';
    $r .= '<input type="hidden" name="only_4_name_id">';
    $r .= '<input type="hidden" name="hidden2">';
    $r .='</form>';
    $r .='</div>';

    return $r;
}

?>
