<?php

function get_create_term($term)
{
    $r = '';
    $r.='<div style="padding: 20px;margin:auto;width: 400px;height:150px;">';
      $r.='<div id="term_div">';   
         $r.='<form action="" method="post">';
            $r.='<table>';
               $r.='<tr>';
                 $r.='<td style="font-size:18px ;padding:0px 0px 7px 0;text-align:left;"><b>CREATE EXAM TERM</b></td>';
               $r.='</tr>';
               $r.='<tr>';
                $r.='<td><input type="text" id="text_box" name="term" value="'.$term.'" placeholder="Term name" autofocus="autofocus" required></td>';
               $r.='</tr>';
               $r.='<tr>';
                $r.='<td style="padding: 20px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Create"></td>';
               $r.='</tr>';
            $r.='</table>';
         $r.='</form>';
       $r.='</div>';
      $r.='</div>';
      
      return $r;
}


function get_cre_num_of_section()
{
    $r = '';
  $r.='<div style="padding: 20px;margin:auto;width: 500px;height:200px;">';
      $r.='<div id="numb_of_sec_div">';    
         $r.='<form action="" method="post">';
            $r.='<table style="margin:auto;">';
               $r.='<tr>';
                  $r.='<td style="font-size:18px ;padding:0px 0px 14px 0;text-align:center;"><b>CREATE SECTION</b></td>';
               $r.='</tr>';
               $r.='<tr>';
                  $r.='<td style="font-size:13px ;padding:0px 0px 7px 0;text-align:left;"><b>Number of Section: </b>';
                       $r.='<input type="text" size="5" name="numOfSec" autofocus="autofocus" required>';
                  $r.='</td>';
               $r.='</tr>';
               $r.='<tr>';
                  $r.='<td style="text-align:left;font-size:13px;"><input type="checkbox" style="cursor:pointer;" name="fourth_subject" value="fourth_subject"><b>Implement 4th subject</b></td>';
               $r.='</tr>';
               $r.='<tr>';
                  $r.='<td style="padding: 20px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Submit"></td>';
               $r.='</tr>';
             $r.='</table>';
          $r.='</form>';
       $r.='</div>';
    $r.='</div>';
    
    return $r;
}


function get_create_section($numOfSec,$fourth_subject,$section_name,$selected_term)
{   
    global $available_terms;
    $r = '';
    $r .='<div style="padding:20px 34px 0 20px;margin:auto;width: 400px;height:370px;">';
    $r .='<div id="cre_sec_header"><b>CREATE SECTION</b></div>';
    $r .='<div id="create_sec_div">';
    if($fourth_subject == 'fourth_subject'){$r .='<div id="message" style="font-size:12px;"><b>Implementation of 4th subject is on</b></div>';}
    else{$r .='<div id="message" style="font-size:12px;"><b>Implementation of 4th subject is off</b></div>';}
    $r .='<form action="" method="post">';
    $r .='<table style="float:left;margin-right:22px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Terms</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($available_terms as $value)
        { 
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="term_name[]" value="'.$value['term'].'"  '.$selected_term[$value['term']].'></td>';
            $r .='<td style="width:160px;">'.$value['term'].'</td>';
            $r .='</tr>';
            $j++;
         }
       $r .='</table>';
       $r .='<table style="float:left;border:1px solid #999;" cellpadding="3" border="1">';
       $r .='<tr>';
       $r .='<td><b>Section</b></td>';
       $r .='</tr>';
         for($i=0; $i<$numOfSec; $i++)
         { 
                $r .= '<tr class="tr1_hover">' ;                                                    
                $r .='<td style="width:130px;"><input type="text" size="16" name="section_name[]" value="'.$section_name[$i].'" placeholder="name" required></td>';
                $r .='</tr>';          
          }                                  
        $r .='</table>';
        $r .='</div>';
        if($fourth_subject == 'fourth_subject')
        {$r .='<input type="hidden" name="fourth_subject" value="fourth_subject">';}
        $r .='<table>';
        $r .='<tr>';
        $r .='<td style=" padding:10px;"><input type="checkbox" id="selectall" /><b>Select All</b></td>';
        $r .='<td style="padding: 10px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Create"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';                           
        $r .='</div>';
    
       return $r ;
}


function get_create_section2($count_section,$count_term,$section_name,$term_name,$fourth_subject,$selected_term)
{
    global $db,$available_terms;
            for ($i=0;$i<$count_section;$i++)
            {    $escape_section = $db->escape_value($section_name[$i]);
                    for($j=0;$j<$count_term;$j++)
                    {                                           
                      $check = $db->query("SELECT * FROM section_list WHERE term='$term_name[$j]' AND section='$escape_section'");
                      if($db->num_rows($check))
                      { 
                          $exist_sec_name = $section_name[$i];
                          $exist_term_name = $term_name[$j];
                          $exist = 'exist';
                          break;
                      }
                    }
                    if($exist == 'exist'){break;}
             }
             if($exist == 'exist')
             {    
                 echo get_create_section($count_section,$fourth_subject,$section_name,$selected_term);
                 echo '<div id="message"><b>Section: "'.$exist_sec_name.'" already exist in Term: "'.$exist_term_name.'"</b></div>';

             }else{
                       for ($i=0;$i<$count_section;$i++)
                        {  $escape_section = $db->escape_value($section_name[$i]);
                            for($j=0;$j<$count_term;$j++)
                            {
                              $result = $db->insert("section_list","term,section","'$term_name[$j]','$escape_section'","term='115122fau'");
                              if($fourth_subject == 'fourth_subject'){$implement_fourth_subject = $db->insert("implement_4th_subject","term,section,implement","'$term_name[$j]','$escape_section','Yes'","term='115122fau'");}
                              $result_class_n_shift = $db->insert("class_n_shift_name","term,section","'$term_name[$j]','$escape_section'","term='115122fau'");
                            }
                        }
                        echo get_create_section($count_section,"","","");
                        echo $db->get_message($count_section,$fourth_subject,"section",$result);
                  }
    
}


function get_cre_num_of_subject()
{
    $r = '';
  $r.='<div style="padding: 20px;margin:auto;width: 500px;height:200px;">';
      $r.='<div id="numb_of_sub_div">';    
         $r.='<form action="" method="post">';
            $r.='<table style="margin:auto;">';
               $r.='<tr>';
                  $r.='<td style="font-size:18px ;padding:0px 0px 14px 0;text-align:center;"><b>CREATE SUBJECT</b></td>';
               $r.='</tr>';
               $r.='<tr>';
                  $r.='<td style="font-size:13px ;padding:0px 0px 7px 0;text-align:left;"><b>Number of Subject: </b>';
                       $r.='<input type="text" size="5" name="numOfSub" autofocus="autofocus" required>';
                  $r.='</td>';
               $r.='</tr>';
               $r.='<tr>';
                  $r.='<td style="padding: 20px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Submit"></td>';
               $r.='</tr>';
             $r.='</table>';
          $r.='</form>';
       $r.='</div>';
    $r.='</div>';
    
    return $r;
}


function get_create_subject($numOfSub,$subject_name,$selected_term,$selected_section)
{
    global $available_terms,$available_section;
    $r = '';
    $r .='<div style="padding:20px 34px 0 20px;margin:auto;width: 630px;height:370px; ">';
    $r .='<div id="cre_sub_header"><b>CREATE SUBJECT</b></div>';
    $r .='<div id="create_sub_div">';   
    $r .='<form action="" method="post">';
    $r .='<table style="float:left;margin-right:22px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Terms</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($available_terms as $value)
        { 
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="term_name[]" value="'.$value['term'].'" '.$selected_term[$value['term']].'></td>';
            $r .='<td style="width:160px;">'.$value['term'].'</td>';
            $r .='</tr>';
            $j++;
         }
       $r .='</table>';
    $r .='<table style="float:left;margin-right:22px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Sections</b></td>';
    $r .='</tr>';
         $k = 0;
     foreach ($available_section as $value)
        { 
           $r .= '<tr ';
            if($k%2 == 0)
             {
                $r .= 'class="tr1_hover" ';
             }else 
                 {
                    $r .= 'class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="section_name[]" value="'.$value['section'].'"  '.$selected_section[$value['section']].'></td>';
            $r .='<td style="width:160px;">'.$value['section'].'</td>';
            $r .='</tr>';
            $k++;
         }
       $r .='</table>';
       $r .='<table style="float:left;border:1px solid #999;" cellpadding="3" border="1">';
       $r .='<tr>';
       $r .='<td><b>Subject</b></td>';
       $r .='</tr>';
         for($i=0; $i<$numOfSub; $i++)
         { 
                $r .= '<tr class="tr1_hover">' ;                                                    
                $r .='<td style="width:130px;"><input type="text" size="16" name="subject_name[]" value="'.$subject_name[$i].'" placeholder="name" required></td>';
                $r .='</tr>';          
          }                                  
        $r .='</table>';
        $r .='</div>';
        $r .='<table>';
        $r .='<tr>';
        $r .='<td style=" padding:10px;"><input type="checkbox" id="selectall" /><b>Select All</b></td>';
        $r .='<td style="padding: 10px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Create"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';                           
        $r .='</div>';
    
       return $r ;
}


function get_create_subject2($count_section,$count_term,$count_subject,$section_name,$term_name,$subject_name,$selected_term,$selected_section)
{
    global $db,$available_terms,$available_section;
    
            for ($i=0;$i<$count_subject;$i++)
            {    $escape_subject = $db->escape_value($subject_name[$i]);
                 for($j=0;$j<$count_section;$j++)
                  {
                        for($k=0;$k<$count_term;$k++)
                        {                                           
                            $check = $db->query("SELECT * FROM subject_list WHERE term='$term_name[$k]' AND section='$section_name[$j]' AND subject='$escape_subject'");
                            if($db->num_rows($check))
                            { 
                                $exist_sub_name = $subject_name[$i];
                                $exist_sec_name = $section_name[$j];
                                $exist_term_name = $term_name[$k];             
                                $exist = 'exist';
                                break;
                            }
                        }
                        if($exist == 'exist'){break;}
                  }
                 if($exist == 'exist'){break;}
             }
             if($exist == 'exist')
             {
                 echo get_create_subject($count_subject,$subject_name,$selected_term,$selected_section);
                 echo '<div id="message"><b>Subject: "'.$exist_sub_name.'" already exist in Term: "'.$exist_term_name.'", Section: "'.$exist_sec_name.'"</b></div>';

             }else{
                       for ($i=0;$i<$count_subject;$i++)
                        {  $escape_subject = $db->escape_value($subject_name[$i]);
                            for($j=0;$j<$count_section;$j++)
                            {
                                for($k=0;$k<$count_term;$k++)
                                {
                                  $result = $db->insert("subject_list","term,section,subject","'$term_name[$k]','$section_name[$j]','$escape_subject'","term='115122fau'");                              
                                }
                            }
                        }
                        echo get_create_subject($count_subject,"","","");
                        echo $db->get_message($count_subject,"subject",$result);
                  }
    
}


function get_cre_num_of_exmtype()
{
    $r = '';
  $r.='<div style="padding: 20px;margin:auto;width: 500px;height:200px;">';
      $r.='<div id="numb_of_exmtype_div">';    
         $r.='<form action="" method="post">';
            $r.='<table style="margin:auto;">';
               $r.='<tr>';
                  $r.='<td style="font-size:18px ;padding:0px 0px 14px 0;text-align:center;"><b>CREATE EXAM-TYPE</b></td>';
               $r.='</tr>';
               $r.='<tr>';
                  $r.='<td style="font-size:13px ;padding:0px 0px 7px 0;text-align:left;"><b>Number of exam-type: </b>';
                       $r.='<input type="text" size="5" name="numOfExmtype" autofocus="autofocus" required>';
                  $r.='</td>';
               $r.='</tr>';
               $r.='<tr>';
                  $r.='<td style="padding: 20px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Submit"></td>';
               $r.='</tr>';
             $r.='</table>';
          $r.='</form>';
       $r.='</div>';
    $r.='</div>';
    
    return $r;
}


function get_create_exmtype($numOfExmtype,$exmtype_name,$selected_term,$selected_section,$selected_subject)
{
    global $available_terms,$available_section,$available_subject;
    $r = '';
    $r .='<div style="padding:20px 34px 0 20px;margin:auto;width: 820px;height:370px; ">';
    $r .='<div id="cre_exmtype_header"><b>CREATE EXAM-TYPE</b></div>';
    $r .='<div id="create_exmtype_div">';   
    $r .='<form action="" method="post">';
    $r .='<table style="float:left;margin-right:22px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Terms</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($available_terms as $value)
        { 
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="term_name[]" value="'.$value['term'].'"  '.$selected_term[$value['term']].'></td>';
            $r .='<td style="width:160px;">'.$value['term'].'</td>';
            $r .='</tr>';
            $j++;
         }
       $r .='</table>';
    $r .='<table style="float:left;margin-right:22px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Sections</b></td>';
    $r .='</tr>';
         $k = 0;
     foreach ($available_section as $value)
        { 
           $r .= '<tr ';
            if($k%2 == 0)
             {
                $r .= 'class="tr1_hover" ';
             }else 
                 {
                    $r .= 'class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="section_name[]" value="'.$value['section'].'"  '.$selected_section[$value['section']].'></td>';
            $r .='<td style="width:160px;">'.$value['section'].'</td>';
            $r .='</tr>';
            $k++;
         }
       $r .='</table>';
       $r .='<table style="float:left;margin-right:22px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Subjects</b></td>';
    $r .='</tr>';
         $l = 0;
     foreach ($available_subject as $value)
        { 
           $r .= '<tr ';
            if($l%2 == 0)
             {
                $r .= 'class="tr1_hover" ';
             }else 
                 {
                    $r .= 'class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="subject_name[]" value="'.$value['subject'].'" '.$selected_subject[$value['subject']].'></td>';
            $r .='<td style="width:160px;">'.$value['subject'].'</td>';
            $r .='</tr>';
            $l++;
         }
       $r .='</table>';
       $r .='<table style="float:left;border:1px solid #999;" cellpadding="3" border="1">';
       $r .='<tr>';
       $r .='<td><b>Exm-type</b></td>';
       $r .='</tr>';
         for($i=0; $i<$numOfExmtype; $i++)
         { 
                $r .= '<tr class="tr1_hover" >' ;                                                    
                $r .='<td style="width:130px;"><input type="text" size="16" name="exmtype_name[]" value="'.$exmtype_name[$i].'" placeholder="name" required></td>';
                $r .='</tr>';           
          }                                  
        $r .='</table>';
        $r .='</div>';
        $r .='<table>';
        $r .='<tr>';
        $r .='<td style=" padding:10px;"><input type="checkbox" id="selectall" /><b>Select All</b></td>';
        $r .='<td style="padding: 10px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Create"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';                           
        $r .='</div>';
    
       return $r ;
}


function get_create_exmtype2($count_section,$count_term,$count_subject,$count_exmtype,$section_name,$term_name,$subject_name,$exmtype_name,$selected_term,$selected_section,$selected_subject)
{
    global $db,$available_terms,$available_section,$available_subject;

            for ($i=0;$i<$count_exmtype;$i++)
               {   $escape_exmtype = $db->escape_value($exmtype_name[$i]);
                    for ($j=0;$j<$count_subject;$j++)
                    {    
                         for($k=0;$k<$count_section;$k++)
                          {
                                for($l=0;$l<$count_term;$l++)
                                {                                           
                                    $check = $db->query("SELECT * FROM exam_type_list WHERE term='$term_name[$l]' AND section='$section_name[$k]' AND subject='$subject_name[$j]' AND exam_type='$escape_exmtype'");
                                    if($db->num_rows($check))
                                    {
                                        $exist_exmtype_name = $exmtype_name[$i];
                                        $exist_sub_name     = $subject_name[$j];
                                        $exist_sec_name     = $section_name[$k];
                                        $exist_term_name    = $term_name[$l];             
                                        $exist = 'exist';
                                        break;
                                    }
                                }
                                if($exist == 'exist'){break;}
                          }
                         if($exist == 'exist'){break;}
                     }
                     if($exist == 'exist'){break;}
                }
             if($exist == 'exist')
             {
                 echo get_create_exmtype($count_exmtype,$exmtype_name,$selected_term,$selected_section,$selected_subject);
                 echo '<div id="message"><b>Exam-type: "'.$exist_exmtype_name.'" already exist in Term: "'.$exist_term_name.'", Section: "'.$exist_sec_name.'", Subject: "'.$exist_sub_name.'"</b></div>';

             }else{
                     for ($i=0;$i<$count_exmtype;$i++)
                       {  $escape_exmtype = $db->escape_value($exmtype_name[$i]);
                            for ($j=0;$j<$count_subject;$j++)
                             {  
                                 for($k=0;$k<$count_section;$k++)
                                 {
                                     for($l=0;$l<$count_term;$l++)
                                     {
                                       $result = $db->insert("exam_type_list","term,section,subject,exam_type","'$term_name[$l]','$section_name[$k]','$subject_name[$j]','$escape_exmtype'","term='115122fau'");                              
                                     }
                                 }
                             }
                       }
                       for($k=0;$k<$count_section;$k++)
                       {
                            for($l=0;$l<$count_term;$l++)
                            {
                               $check_student_exists = $db->find_by_sql("DISTINCT student_id,student_name","student_n_numbers","term='$term_name[$l]' AND section='$section_name[$k]'","") ;
                               if($check_student_exists !== 'No Result Found')
                               {
                                   for ($i=0;$i<$count_exmtype;$i++)
                                    {  $escape_exmtype = $db->escape_value($exmtype_name[$i]);
                                        for ($j=0;$j<$count_subject;$j++)
                                        {
                                            foreach ($check_student_exists as $stu_val)
                                            {
                                              $add_all_exam_things = $db->insert("student_n_numbers","term,section,student_id,student_name,subject,exam_type","'$term_name[$l]','$section_name[$k]','{$stu_val['student_id']}','{$stu_val['student_name']}','$subject_name[$j]','$escape_exmtype'","term='115122fau'");
                                            }
                                        }
                                    }
                                }
                            }
                       }
                       
                        echo get_create_exmtype($count_exmtype,"","","","");
                        echo $db->get_message($count_exmtype,"exam-type",$result);
                  }
    
}


function get_cre_num_of_grade()
{
    $r = '';
  $r.='<div style="padding: 20px;margin:auto;width: 500px;height:200px;">';
      $r.='<div id="numb_of_grade_div">';    
         $r.='<form action="" method="post">';
            $r.='<table style="margin:auto;">';
               $r.='<tr>';
                  $r.='<td style="font-size:18px ;padding:0px 0px 14px 0;text-align:center;"><b>ASSIGN GRADE</b></td>';
               $r.='</tr>';
               $r.='<tr>';
                  $r.='<td style="font-size:13px ;padding:0px 0px 7px 0;text-align:left;"><b>Number of grade per subject: </b>';
                       $r.='<input type="text" size="5" name="numOfgrade" autofocus="autofocus" required>';
                  $r.='</td>';
               $r.='</tr>';
               $r.='<tr>';
                  $r.='<td style="padding: 20px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Submit"></td>';
               $r.='</tr>';
             $r.='</table>';
          $r.='</form>';
       $r.='</div>';
    $r.='</div>';
    
    return $r;
}


function get_create_grade($numOfGrade,$num_from,$num_to,$grade,$point,$selected_term,$selected_section,$selected_subject)
{
    global $available_terms,$available_section,$available_subject;
    $r = '';
    $r .='<div style="padding:20px 34px 0 20px;margin:auto;width:920px;height:370px; ">';
    $r .='<div id="cre_grade_header"><b>ASSIGN GRADE</b></div>';
    $r .='<div id="create_grade_div">';   
    $r .='<form action="" method="post">';
    $r .='<table style="float:left;margin-right:10px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Terms</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($available_terms as $value)
        { 
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="term_name[]" value="'.$value['term'].'"  '.$selected_term[$value['term']].'></td>';
            $r .='<td style="width:160px;">'.$value['term'].'</td>';
            $r .='</tr>';
            $j++;
         }
       $r .='</table>';
    $r .='<table style="float:left;margin-right:10px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Sections</b></td>';
    $r .='</tr>';
         $k = 0;
     foreach ($available_section as $value)
        { 
           $r .= '<tr ';
            if($k%2 == 0)
             {
                $r .= 'class="tr1_hover" ';
             }else 
                 {
                    $r .= 'class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="section_name[]" value="'.$value['section'].'"  '.$selected_section[$value['section']].'></td>';
            $r .='<td style="width:160px;">'.$value['section'].'</td>';
            $r .='</tr>';
            $k++;
         }
       $r .='</table>';
       $r .='<table style="float:left;margin-right:10px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Subjects</b></td>';
    $r .='</tr>';
         $l = 0;
     foreach ($available_subject as $value)
        { 
           $r .= '<tr ';
            if($l%2 == 0)
             {
                $r .= 'class="tr1_hover" ';
             }else 
                 {
                    $r .= 'class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="subject_name[]" value="'.$value['subject'].'"  '.$selected_subject[$value['subject']].'></td>';
            $r .='<td style="width:160px;">'.$value['subject'].'</td>';
            $r .='</tr>';
            $l++;
         }
       $r .='</table>';
       $r .='<table style="float:left;border:1px solid #999;" cellpadding="3" border="1">';
       $r .='<tr style="font-size:12px;">';
       $r .='<td><b>Num. From</b></td>';
       $r .='<td><b>Num. To</b></td>';
       $r .='<td><b>GRADE</b></td>';
       $r .='<td><b>Point</b></td>';
       $r .='</tr>';
         for($i=0; $i<$numOfGrade; $i++)
         { 
                $r .= '<tr class="tr1_hover" >' ;                                                    
                $r .='<td style="width:60px;"><input type="text" size="3" name="num_from[]" value="'.$num_from[$i].'" placeholder="from" required></td>';
                $r .='<td style="width:60px;"><input type="text" size="3" name="num_to[]"   value="'.$num_to[$i].'"   placeholder="to" required></td>';
                $r .='<td style="width:60px;"><input type="text" size="3" name="grade[]"    value="'.$grade[$i].'"    placeholder="grade" required></td>';
                $r .='<td style="width:30px;"><input type="text" size="3" name="point[]"    value="'.$point[$i].'"    placeholder="point" required></td>';
                $r .='</tr>';           
          }                                  
        $r .='</table>';
        $r .='</div>';
        $r .='<table>';
        $r .='<tr>';
        $r .='<td style=" padding:10px;"><input type="checkbox" id="selectall" /><b>Select All</b></td>';
        $r .='<td style="padding: 10px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Create"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';                           
        $r .='</div>';
    
       return $r ;
}


function get_create_grade2($count_section,$count_term,$count_subject,$count_exmtype,$count_num_from,$section_name,$term_name,$subject_name,$num_from,$num_to,$grade,$point,$selected_term,$selected_section,$selected_subject)
{
    global $db,$available_terms,$available_section,$available_subject;
    
            for ($i=0;$i<$count_num_from;$i++)
               {   $escape_num_from = $db->escape_value($num_from[$i]);
                   $escape_num_to   = $db->escape_value($num_to[$i]);
                   $escape_grade    = $db->escape_value($grade[$i]);
                    for ($j=0;$j<$count_subject;$j++)
                    {    
                         for($k=0;$k<$count_section;$k++)
                          {
                                for($l=0;$l<$count_term;$l++)
                                {                                           
                                    $check = $db->query("SELECT * FROM grade_list WHERE term='$term_name[$l]' AND section='$section_name[$k]' AND subject='$subject_name[$j]' AND num_from='$escape_num_from' AND num_to='$escape_num_to' AND grade='$escape_grade' ");
                                    if($db->num_rows($check))
                                    {
                                        $exist_num_from     = $num_from[$i];
                                        $exist_num_to       = $num_to[$i];
                                        $exist_grade        = $grade[$i];
                                        $exist_sub_name     = $subject_name[$j];
                                        $exist_sec_name     = $section_name[$k];
                                        $exist_term_name    = $term_name[$l];             
                                        $exist = 'exist';
                                        break;
                                    }
                                }
                                if($exist == 'exist'){break;}
                          }
                         if($exist == 'exist'){break;}
                     }
                     if($exist == 'exist'){break;}
                }
             if($exist == 'exist')
             {
                 echo get_create_grade($count_num_from,$num_from,$num_to,$grade,$point,$selected_term,$selected_section,$selected_subject);
                 echo '<div id="message"><b>Number-from: "'.$exist_num_from.'" or Number-to: "'.$exist_num_to.'" or Grade: "'.$exist_grade.'" already exist in Term: "'.$exist_term_name.'", Section: "'.$exist_sec_name.'", Subject: "'.$exist_sub_name.'"</b></div>';

             }else{
                     for ($i=0;$i<$count_num_from;$i++)
                       {  $escape_num_from = $db->escape_value($num_from[$i]);
                          $escape_num_to   = $db->escape_value($num_to[$i]);
                          $escape_grade    = $db->escape_value($grade[$i]);
                          $escape_point    = $db->escape_value($point[$i]);
                            for ($j=0;$j<$count_subject;$j++)
                             {  
                                 for($k=0;$k<$count_section;$k++)
                                 {
                                     for($l=0;$l<$count_term;$l++)
                                     {
                                       $result = $db->insert("grade_list","term,section,subject,num_from,num_to,grade,point","'$term_name[$l]','$section_name[$k]','$subject_name[$j]','$escape_num_from','$escape_num_to','$escape_grade','$escape_point'","term='115122fau'");                              
                                     }
                                 }
                             }
                       }
                        echo get_create_grade($count_num_from,"","","","","","","");
                        echo $db->get_message($count_num_from,"grade",$result);
                  }
    
}


function get_create_pass_mark($pass_mark,$selected_term,$selected_section,$selected_subject)
{
    global $available_terms,$available_section,$available_subject;
    $r = '';
    $r .='<div style="padding:20px 34px 0 20px;margin:auto;width:920px;height:370px; ">';
    $r .='<div id="cre_pass_mark_header"><b>SET PASS MARK</b></div>';
    $r .='<div id="create_pass_mark_div">';   
    $r .='<form action="" method="post">';
    $r .='<table style="float:left;margin-right:25px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Terms</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($available_terms as $value)
        { 
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="term_name[]" value="'.$value['term'].'"  '.$selected_term[$value['term']].'></td>';
            $r .='<td style="width:160px;">'.$value['term'].'</td>';
            $r .='</tr>';
            $j++;
         }
       $r .='</table>';
    $r .='<table style="float:left;margin-right:25px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Sections</b></td>';
    $r .='</tr>';
         $k = 0;
     foreach ($available_section as $value)
        { 
           $r .= '<tr ';
            if($k%2 == 0)
             {
                $r .= 'class="tr1_hover" ';
             }else 
                 {
                    $r .= 'class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="section_name[]" value="'.$value['section'].'"  '.$selected_section[$value['section']].'></td>';
            $r .='<td style="width:160px;">'.$value['section'].'</td>';
            $r .='</tr>';
            $k++;
         }
       $r .='</table>';
       $r .='<table style="float:left;margin-right:25px;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Subjects</b></td>';
    $r .='</tr>';
         $l = 0;
     foreach ($available_subject as $value)
        { 
           $r .= '<tr ';
            if($l%2 == 0)
             {
                $r .= 'class="tr1_hover" ';
             }else 
                 {
                    $r .= 'class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="subject_name[]" value="'.$value['subject'].'"  '.$selected_subject[$value['subject']].'></td>';
            $r .='<td style="width:160px;">'.$value['subject'].'</td>';
            $r .='</tr>';
            $l++;
         }
       $r .='</table>';
       $r .='<table style="float:left;border:1px solid #999;" cellpadding="3" border="1">';
       $r .='<tr style="font-size:14px;">';
       $r .='<td><b>PASS MARK</b></td>';
       $r .='</tr>';
        $r .= '<tr class="tr1_hover" >' ;                                                    
           $r .='<td style="width:130px;"><input type="text" size="10" name="pass_mark" value="'.$pass_mark.'" placeholder="number" required></td>';
        $r .='</tr>';                                          
        $r .='</table>';
        $r .='</div>';
        $r .='<table>';
        $r .='<tr>';
        $r .='<td style=" padding:10px;"><input type="checkbox" id="selectall" /><b>Select All</b></td>';
        $r .='<td style="padding: 10px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Create"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';                           
        $r .='</div>';
    
       return $r ;
}


function get_create_pass_mark2($count_section,$count_term,$count_subject,$section_name,$term_name,$subject_name,$pass_mark,$selected_term,$selected_section,$selected_subject)
{
    global $db;
   
                for ($j=0;$j<$count_subject;$j++)
                {    
                     for($k=0;$k<$count_section;$k++)
                      {
                            for($l=0;$l<$count_term;$l++)
                            {                                           
                                $check = $db->query("SELECT mark FROM pass_mark_list WHERE term='$term_name[$l]' AND section='$section_name[$k]' AND subject='$subject_name[$j]' AND mark='$pass_mark'");
                                if($db->num_rows($check))
                                {
                                    $exist_sub_name     = $subject_name[$j];
                                    $exist_sec_name     = $section_name[$k];
                                    $exist_term_name    = $term_name[$l];             
                                    $exist = 'exist';
                                    break;
                                }
                            }
                            if($exist == 'exist'){break;}
                      }
                     if($exist == 'exist'){break;}
                 }
                
             if($exist == 'exist')
             {
                 echo get_create_pass_mark($pass_mark,$selected_term,$selected_section,$selected_subject);
                 echo '<div id="message"><b>Pass-Mark: "'.$pass_mark.'" already exist in Term: "'.$exist_term_name.'", Section: "'.$exist_sec_name.'", Subject: "'.$exist_sub_name.'"</b></div>';

             }else{
                       
                        for ($j=0;$j<$count_subject;$j++)
                         {  
                             for($k=0;$k<$count_section;$k++)
                             {
                                 for($l=0;$l<$count_term;$l++)
                                 {
                                   $result = $db->insert("pass_mark_list","term,section,subject,mark","'$term_name[$l]','$section_name[$k]','$subject_name[$j]','$pass_mark'","term='115122fau'");                              
                                 }
                             }
                         }
                       
                        echo get_create_pass_mark("","","","");
                        echo $db->get_message('A',"pass-mark",$result);
                  }
    
}


function validate_grade_field($num_from,$num_to,$grade,$point)
{
    $ready_grade = array_count_values($grade);
    $ready_point = array_count_values($point);
    $count = count($num_from);

    foreach($ready_grade as $key => $value)
    {
        if($value > 1 )
        {
            return 'Grade: '.$key;
            break;          
        }
    }
    foreach($ready_point as $k => $v)
    {
        if($v > 1 )
        {
            return 'Point: '.$k;
            break; 
        }
    }


    for($i = 0; $i<$count; $i++)
    {
        $array1[] = range($num_from[$i], $num_to[$i],.5);
        if($num_from[$i] == $num_to[$i])
        { return 'Value: '.$num_from[$i]; }
    }
    for($i = 0; $i<$count; $i++)
    {
        $array2[] = range($num_from[$i], $num_to[$i],.5);
    }
    //print_r ($u);
    for($k=0;$k<$count;$k++)
    {
        foreach ($array1[$k] as $value1)
        {
            //echo $val.'val<br>';
            for($l=0;$l<$count;$l++)
             {
                if($k !== $l)
                {    
                    foreach ($array2[$l] as $value2)
                    {
                        if($value1 == $value2)
                        {
                            $exist = 'exist';
                            return 'Value: '.$value1;
                            break;                             
                        }
                    }
                }
                if($exist == 'exist'){break;}
             }
            if($exist == 'exist'){break;}
        }
       if($exist == 'exist'){break;}
    }
    //print_r(array_intersect($t, $u));
}







?>
