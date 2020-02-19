<?php

function ed_term($edited_term)
{
    global $db,$available_terms;
    
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header"><b>EDIT & DELETE TERM</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Terms</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($available_terms as $value)
        { 
           $term_id = $db->find_by_sql("id","terms","term='{$value['term']}'","");
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td><input type="checkbox" class="case" name="selected_term_id[]" value="'.$term_id[0]['id'].'" ></td>';
            $r .='<td style="width:160px;">'.$value['term'].'</td>';
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
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_term($selected_term_id,$edited_term_name)
{
     global $db;
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header"><b>EDIT TERM</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td><b>Selected Terms</b></td>';
    $r .='</tr>';
      $count_selected_term_id = count($selected_term_id); 
         $j = 0;
     for($i=0;$i<$count_selected_term_id; $i++)
        { 
         $term = $db->find_by_sql("term","terms","id='$selected_term_id[$i]'","");
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<input type="hidden" name="selected_term_id[]" value="'.$selected_term_id[$i].'" >';
            if(is_array($edited_term_name) == FALSE){$r .='<td style="width:160px;"><input type="text" name="edited_term_name[]" value="'.$term[0]['term'].'" ></td>';}
            else{$r .='<td style="width:160px;"><input type="text" name="edited_term_name[]" value="'.$edited_term_name[$i].'" ></td>';}
            $r .='</tr>';
            $j++;
         }
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:right;padding:10px 0 0 0;"><input type="submit" id="submit_button" name="finally_edit" value="Edit">
          </td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_term2($edited_term,$selected_term_id)
{ global $db;
    $term = $db->find_by_sql("term","terms","id='$selected_term_id'","");
    $table_array = array("terms","section_list","subject_list","exam_type_list","grade_list","pass_mark_list","student_n_numbers",
                        "add_n_grade","percent_add_n_grade","total_add","gpa_result","total_result","fourth_subject","implement_4th_subject",
                        "class_n_shift_name","discipline_n_roll");
     foreach ($table_array as $table_val)
     {
        $update_term = $db->update("$table_val","term='$edited_term'","term='{$term[0]['term']}'");
     }
     return $update_term;
}

function delete_term($selected_term_id)
{ global $db;
    $term = $db->find_by_sql("term","terms","id='$selected_term_id'","");
    $table_array = array("terms","section_list","subject_list","exam_type_list","grade_list","pass_mark_list","student_n_numbers",
                        "add_n_grade","percent_add_n_grade","total_add","gpa_result","total_result","fourth_subject","implement_4th_subject",
                        "class_n_shift_name","discipline_n_roll");
  if($term !== 'No Result Found')
  {
     foreach ($table_array as $table_val)
     {
        $delete_term = $db->delete("$table_val","term='{$term[0]['term']}'");
        if(empty($delete_term) == FALSE){ $actual_delete = $delete_term; }
     }
  }
     return $actual_delete;
}

function ed_section($term_name)
{
    global $db;
    $section = $db->find_by_sql("DISTINCT section,id","section_list"," term='$term_name'   ORDER BY section ASC","");
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header"><b>EDIT & DELETE SECTION in TERM: '.$term_name.'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Section</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($section as $value)
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
            $r .='<td><input type="checkbox" class="case" name="selected_section_id[]" value="'.$value['id'].'" ></td>';
            $r .='<td style="width:160px;">'.$value['section'].'</td>';
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
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_section($selected_section_id,$edited_section_name,$term_name)
{  global $db;  
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header"><b>EDIT SECTION</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td><b>Selected Sections</b></td>';
    $r .='</tr>';
      $count_selected_section_id = count($selected_section_id); 
         $j = 0;
     for($i=0;$i<$count_selected_section_id; $i++)
        {  
          $section = $db->find_by_sql("section","section_list"," id='$selected_section_id[$i]' AND term='$term_name'","");
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<input type="hidden" name="selected_section_id[]" value="'.$selected_section_id[$i].'" >';
            if(is_array($edited_section_name) == FALSE){$r .='<td style="width:160px;"><input type="text" name="edited_section_name[]" value="'.$section[0]['section'].'" ></td>';}
            else{$r .='<td style="width:160px;"><input type="text" name="edited_section_name[]" value="'.$edited_section_name[$i].'" ></td>';}
            $r .='</tr>';
            $j++;
         }
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:right;padding:10px 0 0 0;"><input type="submit" id="submit_button" name="finally_edit" value="Edit">
          </td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_section2($edited_section,$selected_section_id,$term_name)
{ global $db;
    $section = $db->find_by_sql("section","section_list","id='$selected_section_id'","");
    $table_array = array("section_list","subject_list","exam_type_list","grade_list","pass_mark_list","student_n_numbers",
                        "add_n_grade","percent_add_n_grade","total_add","gpa_result","total_result","fourth_subject","implement_4th_subject",
                        "class_n_shift_name","discipline_n_roll");

     foreach ($table_array as $table_val)
     {
        $update_section = $db->update("$table_val","section='$edited_section'"," section='{$section[0]['section']}' AND term='$term_name'");
     }
     return $update_section;
}

function delete_section($selected_section_id,$term_name)
{ global $db;
    $section = $db->find_by_sql("section","section_list","id='$selected_section_id'","");
    $table_array = array("section_list","subject_list","exam_type_list","grade_list","pass_mark_list","student_n_numbers",
                        "add_n_grade","percent_add_n_grade","total_add","gpa_result","total_result","fourth_subject","implement_4th_subject",
                        "class_n_shift_name","discipline_n_roll");
   if($section !== 'No Result Found')
    {
        foreach ($table_array as $table_val)
        {
           $delete_section = $db->delete("$table_val","section='{$section[0]['section']}' AND term='$term_name'");
           if(empty($delete_section) == FALSE){ $actual_delete = $delete_section; }
        }
    }
     return $actual_delete;
}

function ed_subject($term_name,$section_name)
{
    global $db;
    $subject = $db->find_by_sql("DISTINCT subject,id","subject_list","term='$term_name' AND section='$section_name'  ORDER BY section ASC","");
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header"><b>EDIT & DELETE SUBJECT in TERM: '.$term_name.', SECTION: '.$section_name.'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Subject</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($subject as $value)
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
            $r .='<td><input type="checkbox" class="case" name="selected_subject_id[]" value="'.$value['id'].'" ></td>';
            $r .='<td style="width:160px;">'.$value['subject'].'</td>';
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
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_subject($selected_subject_id,$edited_subject_name,$term_name,$section_name)
{   global $db;
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header"><b>EDIT SUBJECT</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td><b>Selected Subjects</b></td>';
    $r .='</tr>';
      $count_selected_subject_id = count($selected_subject_id); 
         $j = 0;
     for($i=0;$i<$count_selected_subject_id; $i++)
        {
          $subject = $db->find_by_sql("subject","subject_list"," id='$selected_subject_id[$i]' AND term='$term_name' AND section='$section_name'","");
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<input type="hidden" name="selected_subject_id[]" value="'.$selected_subject_id[$i].'" >';
            if(is_array($edited_subject_name) == FALSE){$r .='<td style="width:160px;"><input type="text" name="edited_subject_name[]" value="'.$subject[0]['subject'].'" ></td>';}
            else{$r .='<td style="width:160px;"><input type="text" name="edited_subject_name[]" value="'.$edited_subject_name[$i].'" ></td>';}
            $r .='</tr>';
            $j++;
         }
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:right;padding:10px 0 0 0;"><input type="submit" id="submit_button" name="finally_edit" value="Edit">
          </td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_subject2($edited_subject,$selected_subject_id,$term_name,$section_name)
{ global $db;
    $subject = $db->find_by_sql("subject","subject_list","id='$selected_subject_id'","");
    $table_array = array("subject_list","exam_type_list","grade_list","pass_mark_list","student_n_numbers",
                        "add_n_grade","percent_add_n_grade","total_add","fourth_subject");

     foreach ($table_array as $table_val)
     {
        $update_subject = $db->update("$table_val","subject='$edited_subject'"," subject='{$subject[0]['subject']}' AND section='$section_name' AND term='$term_name'");
     }
     return $update_subject;
}

function delete_subject($selected_subject_id,$term_name,$section_name)
{ global $db;
    $subject = $db->find_by_sql("subject","subject_list","id='$selected_subject_id'","");
    $table_array = array("subject_list","exam_type_list","grade_list","pass_mark_list","student_n_numbers",
                        "add_n_grade","percent_add_n_grade","total_add","fourth_subject");
   if($subject !== 'No Result Found')
   {
     foreach ($table_array as $table_val)
     {
        $delete_subject = $db->delete("$table_val","subject='{$subject[0]['subject']}' AND section='$section_name' AND term='$term_name'");
        if(empty($delete_subject) == FALSE){ $actual_delete = $delete_subject; }
     }
   }
     return $actual_delete;
}

function ed_exam_type($term_name,$section_name,$subject_name)
{
    global $db;
    $exam_type = $db->find_by_sql("DISTINCT exam_type,id","exam_type_list","term='$term_name' AND section='$section_name' AND subject='$subject_name'  ORDER BY exam_type ASC","");
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header" style="font-size:10px;"><b>EDIT & DELETE EXAM-TYPE in TERM: '.$term_name.', SECTION: '.$section_name.', SUBJECT:'.$subject_name.'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Exam-type</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($exam_type as $value)
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
            $r .='<td><input type="checkbox" class="case" name="selected_exam_type_id[]" value="'.$value['id'].'" ></td>';
            $r .='<td style="width:160px;">'.$value['exam_type'].'</td>';
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
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='<input type="hidden" name="subject_name" value="'.$subject_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_exam_type($selected_exam_type_id,$edited_exam_type_name,$term_name,$section_name,$subject_name)
{
    global $db;
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header" style="font-size:10px;"><b>EDIT EXAM-TYPE in TERM: '.$term_name.', SECTION: '.$section_name.', SUBJECT:'.$subject_name.'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td><b>Selected Exam-types</b></td>';
    $r .='</tr>';
      $count_selected_exam_type_id = count($selected_exam_type_id); 
         $j = 0;
     for($i=0;$i<$count_selected_exam_type_id; $i++)
        {
           $exam_type = $db->find_by_sql("exam_type","exam_type_list"," id='$selected_exam_type_id[$i]' AND term='$term_name' AND section='$section_name' AND subject='$subject_name'","");
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<input type="hidden" name="selected_exam_type_id[]" value="'.$selected_exam_type_id[$i].'" >';
            if(is_array($edited_exam_type_name) == FALSE){$r .='<td style="width:160px;"><input type="text" name="edited_exam_type_name[]" value="'.$exam_type[0]['exam_type'].'" ></td>';}
            else{$r .='<td style="width:160px;"><input type="text" name="edited_exam_type_name[]" value="'.$edited_exam_type_name[$i].'" ></td>';}
            $r .='</tr>';
            $j++;
         }
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:right;padding:10px 0 0 0;"><input type="submit" id="submit_button" name="finally_edit" value="Edit">
          </td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='<input type="hidden" name="subject_name" value="'.$subject_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_exam_type2($edited_exam_type,$selected_exam_type_id,$term_name,$section_name,$subject_name)
{ global $db;
    $exam_type = $db->find_by_sql("exam_type","exam_type_list","id='$selected_exam_type_id'","");
    $table_array = array("exam_type_list","student_n_numbers");

     foreach ($table_array as $table_val)
     {
        $update_exam_type = $db->update("$table_val","exam_type='$edited_exam_type'"," exam_type='{$exam_type[0]['exam_type']}' AND subject='$subject_name' AND section='$section_name' AND term='$term_name'");
     }
     return $update_exam_type;
}

function delete_exam_type($selected_exam_type_id,$term_name,$section_name,$subject_name)
{ global $db;
    $exam_type = $db->find_by_sql("exam_type","exam_type_list","id='$selected_exam_type_id'","");
    $table_array = array("exam_type_list","student_n_numbers");
    if($exam_type !== 'No Result Found')
    {
        foreach ($table_array as $table_val)
        {
           $delete_exam_type = $db->delete("$table_val"," exam_type='{$exam_type[0]['exam_type']}' AND  subject='$subject_name'  AND section='$section_name' AND term='$term_name'");
           if(empty($delete_exam_type) == FALSE){ $actual_delete = $delete_exam_type; }
        }
    }
     return $actual_delete;
}

function ed_pass_mark($term_name,$section_name,$subject_name)
{
    global $db;
    $pass_mark = $db->find_by_sql("id,mark","pass_mark_list","term='$term_name' AND section='$section_name' AND subject='$subject_name' ","");
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header" style="font-size:10px;"><b>EDIT & DELETE EXAM-TYPE in TERM: '.$term_name.', SECTION: '.$section_name.', SUBJECT:'.$subject_name.'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Available Pass-mark</b></td>';
    $r .='</tr>';
        
    $r .= '<tr class="tr1_hover" >' ;
    $r .='<td><input type="checkbox" class="case" name="selected_pass_mark_id" value="'.$pass_mark[0]['id'].'" ></td>';
    $r .='<td style="width:160px;">'.$pass_mark[0]['mark'].'</td>';
    $r .='</tr>';
         
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:left;padding:7px 0 0 0;"><input type="checkbox" id="selectall" /><b>Select All</b>
          &nbsp; &nbsp; <input type="image" name="edit" alt="submit" value="submit" src="images/Edit.png" width="20" height="20" ">
          &nbsp; &nbsp; <input type="image" name="selected_delete" alt="submit" value="submit" src="images/DeleteRed.png" width="20" height="20" onclick="return confirm(\'Are you sure you want to delete?\');"></td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='<input type="hidden" name="subject_name" value="'.$subject_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_pass_mark($selected_pass_mark_id,$edited_pass_mark_name,$term_name,$section_name,$subject_name)
{
    global $db;
    $pass_mark = $db->find_by_sql("id,mark","pass_mark_list","id='$selected_pass_mark_id' AND term='$term_name' AND section='$section_name' AND subject='$subject_name' ","");
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header" style="font-size:10px;"><b>EDIT PASS-MARK in TERM: '.$term_name.', SECTION: '.$section_name.', SUBJECT:'.$subject_name.'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td><b>Selected Pass-mark</b></td>';
    $r .='</tr>';
    $r .= '<tr class="tr1_hover" >' ;
    $r .='<input type="hidden" name="selected_pass_mark_id" value="'.$selected_pass_mark_id.'" >';
    if(is_array($edited_pass_mark_name) == FALSE){$r .='<td style="width:160px;"><input type="text" name="edited_pass_mark_name" value="'.$pass_mark[0]['mark'].'"  required></td>';}
    else{$r .='<td style="width:160px;"><input type="text" name="edited_pass_mark_name" value="'.$edited_pass_mark_name[$i].'"  required></td>';}
    $r .='</tr>';
         
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:right;padding:10px 0 0 0;"><input type="submit" id="submit_button" name="finally_edit" value="Edit">
          </td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='<input type="hidden" name="subject_name" value="'.$subject_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_pass_mark2($edited_pass_mark,$selected_pass_mark_id,$term_name,$section_name,$subject_name)
{ global $db;
     $pass_mark = $db->find_by_sql("mark","pass_mark_list","id='$selected_pass_mark_id'","");
     $update_pass_mark = $db->update("pass_mark_list","mark='$edited_pass_mark'"," mark='{$pass_mark[0]['mark']}' AND subject='$subject_name' AND section='$section_name' AND term='$term_name'");
     
     return $update_pass_mark;
}

function delete_pass_mark($selected_pass_mark_id,$term_name,$section_name,$subject_name)
{ global $db;
    $pass_mark = $db->find_by_sql("mark","pass_mark_list","id='$selected_pass_mark_id'","");
    if($pass_mark !== 'No Result Found')
    {
     $delete_pass_mark = $db->delete("pass_mark_list","mark='{$pass_mark[0]['mark']}' AND  subject='$subject_name'  AND section='$section_name' AND term='$term_name'");
     if(empty($delete_pass_mark) == FALSE){ $actual_delete = $delete_pass_mark; }
    }
     return $actual_delete;
}

function ed_grade($term_name,$section_name,$subject_name)
{
    global $db;
    $grade = $db->find_by_sql("id,num_from,num_to,grade,point","grade_list","term='$term_name' AND section='$section_name' AND subject='$subject_name' ","");
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header" style="font-size:10px;"><b>EDIT & DELETE GRADE in TERM: '.$term_name.', SECTION: '.$section_name.', SUBJECT:'.$subject_name.'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr style="font-size:13px;">';
    $r .='<td></td>';
    $r .='<td><b>Num From</b></td>';
    $r .='<td><b>Num To</b></td>';
    $r .='<td><b>Grade</b></td>';
    $r .='<td><b>Point</b></td>';
    $r .='</tr>';
         $j = 0;
     foreach ($grade as $value)
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
            $r .='<td><input type="checkbox" class="case" name="selected_grade_id[]" value="'.$value['id'].'" ></td>';
            $r .='<td style="width:60px;">'.$value['num_from'].'</td>';
            $r .='<td style="width:60px;">'.$value['num_to'].'</td>';
            $r .='<td style="width:60px;">'.$value['grade'].'</td>';
            $r .='<td style="width:60px;">'.$value['point'].'</td>';
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
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='<input type="hidden" name="subject_name" value="'.$subject_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_grade($selected_grade_id,$edited_grade_name,$term_name,$section_name,$subject_name)
{ global $db;   
    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header" style="font-size:10px;"><b>EDIT GRADE in TERM: '.$term_name.', SECTION: '.$section_name.', SUBJECT:'.$subject_name.'</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr style="font-size:13px;">';
    $r .='<td><b>Num From</b></td>';
    $r .='<td><b>Num To</b></td>';
    $r .='<td><b>Grade</b></td>';
    $r .='<td><b>Point</b></td>';
    $r .='</tr>';
      $count_selected_grade_id = count($selected_grade_id); 
         $j = 0;
     for($i=0;$i<$count_selected_grade_id; $i++)
        {
          $grade = $db->find_by_sql("id,num_from,num_to,grade,point","grade_list"," id='$selected_grade_id[$i]' AND term='$term_name' AND section='$section_name' AND subject='$subject_name' ","");
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<input type="hidden" name="selected_grade_id[]" value="'.$grade[0]['id'].'" ">';
            if(is_array($edited_grade_name) == FALSE)
            {
                $r .='<td style="width:60px;"><input type="text" size="3px" name="edited_num_from[]" value="'.$grade[0]['num_from'].'" required></td>';
                $r .='<td style="width:60px;"><input type="text" size="3px" name="edited_num_to[]"   value="'.$grade[0]['num_to'].'" required></td>';
                $r .='<td style="width:60px;"><input type="text" size="3px" name="edited_grade[]"    value="'.$grade[0]['grade'].'" required></td>';
                $r .='<td style="width:60px;"><input type="text" size="3px" name="edited_point[]"    value="'.$grade[0]['point'].'" required></td>';
            }
            else{
                $r .='<td style="width:60px;"><input type="text" size="3px" name="edited_num_from[]" value="'.$edited_grade_name[0][$i].'" required></td>';
                $r .='<td style="width:60px;"><input type="text" size="3px" name="edited_num_to[]"   value="'.$edited_grade_name[1][$i].'" required></td>';
                $r .='<td style="width:60px;"><input type="text" size="3px" name="edited_grade[]"    value="'.$edited_grade_name[2][$i].'" required></td>';
                $r .='<td style="width:60px;"><input type="text" size="3px" name="edited_point[]"    value="'.$edited_grade_name[3][$i].'" required></td>';
            }
            $r .='</tr>';
            $j++;
         }
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:right;padding:10px 0 0 0;"><input type="submit" id="submit_button" name="finally_edit" value="Edit">
          </td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='<input type="hidden" name="term_name" value="'.$term_name.'" ">';
    $r .='<input type="hidden" name="section_name" value="'.$section_name.'" ">';
    $r .='<input type="hidden" name="subject_name" value="'.$subject_name.'" ">';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function edit_grade2($count_grade,$edited_grade_name,$selected_grade_id,$term_name,$section_name,$subject_name)
{ global $db;
     for($i=0;$i<$count_grade;$i++)
     {
       $update_grade = $db->update("grade_list","num_from='{$edited_grade_name[0][$i]}',num_to='{$edited_grade_name[1][$i]}',grade='{$edited_grade_name[2][$i]}',point='{$edited_grade_name[3][$i]}'"," id='$selected_grade_id[$i]' AND subject='$subject_name' AND section='$section_name' AND term='$term_name'");
     }
     return $update_grade ;
}

function delete_grade($selected_grade_id,$term_name,$section_name,$subject_name)
{ global $db;

    $delete_grade = $db->delete("grade_list","id='$selected_grade_id' AND  subject='$subject_name'  AND section='$section_name' AND term='$term_name'");
    if(empty($delete_grade) == FALSE){ $actual_delete = $delete_grade; }
     
     return $actual_delete;
}





function ed_selection_menu($title_name) 
{
    global $available_terms ;
    $r = '';
    $r .='<div id="selection_menu">';
    $r .='<div style="width:350px;height:20px;text-align:center;background:#ccc;border="1px solid #ccc;"><b>' . $title_name . '</b></div>';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:5px auto 0 auto;font-weight:bold;">';
    $r .='<tr>';
    $r .='<td style="padding-right:15px;">';
    $r .='<select name="term_name" title="Available Terms"  id="term" onchange="this.form.submit()">';
    $r .='<option value="Available Terms">Available Terms</option>';
    $r .='<optgroup label="Terms :">';
    foreach ($available_terms as $value) {
        $r .='<option value="' . $value['term'] . '" >' . $value['term'] . '</option>';
    }
    $r .='</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r .='</table>';
    $r .= '<input type="hidden" name="ed_selection_menu">';
    $r .='</form>';
    $r .='</div>';

    return $r;
}

function ed_selection_menu2($title_name) 
{
    global $available_terms,$available_section ;
    $r = '';
    $r .='<div id="selection_menu">';
    $r .='<div style="width:350px;height:20px;text-align:center;background:#ccc;border="1px solid #ccc;"><b>' . $title_name . '</b></div>';
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
    $r .= '<input type="hidden" name="ed_selection_menu2">';
    $r .='</form>';
    $r .='</div>';

    return $r;
}

function ed_selection_menu3($title_name)
{
    global $available_terms, $available_section, $available_subject;
    $r = '';
    $r .='<div id="selection_menu2">';
    $r .='<div style="width:650px;height:20px;text-align:center;background:#ccc;border="1px solid #ccc;"><b>'.$title_name.'</b></div>';
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
    $r .='<option value="Available Section">Available Sections</option>';
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
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td style="text-align:center;padding-top:15px;" colspan="4"><input type="submit" id="submit_button" name="ed_selection_menu3" value="Submit"></td>';
    $r .='</tr>';
    $r.='</table>';
    $r .='</form>';
    $r .='</div>';

    return $r;
}

?>