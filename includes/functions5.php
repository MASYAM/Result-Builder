<?php

function disp_selection_menu($selected_term, $selected_section, $selected_disp_type) 
{
    global $available_terms, $available_section;
    $disp_type = array("Working Days","Absent","Detention","Class Roll");
    $r = '';
    $r .='<div id="selection_menu2">';
    $r .='<div style="width:650px;height:20px;text-align:center;background:#ccc;border="1px solid #ccc;"><b>INSERT DISCIPLINE & CLASS ROLL</b></div>';
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
    $r .='<select name="disp" title="Discipline n Roll">';
    $r .='<option value="disp">Discipline n Roll</option>';
    foreach ($disp_type as $value) {
        $r .='<option value="' . $value. '"  ' . $selected_disp_type[$value] . '>' .$value. '</option>';
    }
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

function get_insert_disp($section_name, $term_name, $disp) 
{
    global $db;

    $student = $db->find_by_sql("DISTINCT student_id","discipline_n_roll", "term='$term_name[0]' AND section='$section_name[0]'  ORDER BY LENGTH(student_id),student_id", "");   
    if($disp[0] == 'Working Days'){ $disp_table = 'working_days'; }
    elseif($disp[0] == 'Absent') { $disp_table = 'absent'; }
    elseif($disp[0] == 'Detention') { $disp_table = 'detention'; }
    elseif($disp[0] == 'Class Roll') { $disp_table = 'class_roll'; }
    
    if ($student == 'No Result Found') {
        $r .='<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Student Found</b></div>';
    }else {
        if($disp_table == 'class_roll')
        {$r .='<div id="insert_number_header"><b>For all Terms belong Section :' . $section_name[0] . ', Class Roll<b></div>';}
        else{ $r .='<div id="insert_number_header"><b>Term :' . $term_name[0] . ', Section :' . $section_name[0] . ', Discipline :' .$disp[0].'<b></div>'; }
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="margin:5px auto;border:1px solid #ccc;font-size:14px;" cellpadding="0" border="1">';
        $r .='<tr style="background:OldLace ;">';
      if($disp[0] !== 'Working Days')
      {  
        $r .='<td style="width:120px;"><b>Student Id</b></td>';
        $r .='<td style="width:100px;"><b>'.$disp[0].'</b></td>';
      }else{ $r .='<td style="width:150px;"><b>'.$disp[0].'</b></td>'; }
        $r .='</tr>';
        $r .='<tr>';
        $n = 0;
      if($disp[0] !== 'Working Days')
      { foreach ($student as $stu_val) 
        {
            $r .= '<tr ';
            if ($n % 2 == 0) {
                $r .= ' class="tr1_hover" ';
            } else {
                $r .= ' class="tr2_hover" ';
            }
            $r .= ' >';
            $disp_value = $db->find_by_sql("$disp_table", "discipline_n_roll", "term='$term_name[0]' AND section='$section_name[0]' AND student_id='{$stu_val['student_id']}'", "");
            $r .='<td>' . substr($stu_val['student_id'],4) . '</td>';
            $r .='<td class="tr1_hover" style="width:55px;"><input type="text" style="text-align:right;" size="6" name="disp_value[]" value="' .$disp_value[0][$disp_table]. '"></td>';
            $r .='<input type="hidden" name="student_id[]" value="' . $stu_val['student_id'] . '">';
            $r .='</tr>';
            $n++;
        }
      }else{
            $disp_value = $db->find_by_sql("$disp_table", "discipline_n_roll", "term='$term_name[0]' AND section='$section_name[0]' ", "1");
            $r .= '<tr class="tr1_hover" >';
            $r .='<td class="tr1_hover" style="width:55px;"><input type="text" style="text-align:right;" size="12" name="disp_value" value="' .$disp_value[0][$disp_table]. '"></td>';
            $r .='</tr>';
      }

        $r .='</table>';
        $r .='<input type="hidden" name="term_name"      value="' . $term_name[0] . '">';
        $r .='<input type="hidden" name="section_name"   value="' . $section_name[0] . '">';
        $r .='<input type="hidden" name="disp"           value="' . $disp[0] . '">';
        $r .='<input type="hidden" name="disp_table"     value="' . $disp_table. '">';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" colspan="4"><input type="submit" id="submit_button" name="insert_disp" value="Insert"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    }
    return $r;
}

function get_create_class_or_shift($title,$class_or_shift,$selected_term,$selected_section)
{
    global $available_terms,$available_section;
    $r = '';
    $r .='<div style="padding:20px 34px 0 20px;margin:auto;width: 630px;height:370px; ">';
    $r .='<div id="cre_sub_header"><b>'.$title.'</b></div>';
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
       if($title == 'CREATE CLASS NAME'){$r .='<td><b>Class Name</b></td>';}
       else{$r .='<td><b>Shift Name</b></td>';}
       $r .='</tr>';
        $r .= '<tr class="tr1_hover">' ;                                                    
        if($title == 'CREATE CLASS NAME'){$r .='<td style="width:130px;"><input type="text" size="16" name="class_name" value="'.$class_or_shift.'" placeholder="name" required></td>';}
        else{$r .='<td style="width:130px;"><input type="text" size="16" name="shift_name" value="'.$class_or_shift.'" placeholder="name" required></td>';}
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

function class_or_shift_selection_menu() 
{
    global $available_terms;
    $cs_type = array("Class Name","Shift Name");
    $r = '';
    $r .='<div id="selection_menu">';
    $r .='<div style="width:350px;height:20px;text-align:center;background:#ccc;border="1px solid #ccc;"><b>INSERT CLASS & SHIFT NAME</b></div>';
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
    $r .='<td style="padding-right:15px;">';
    $r .='<select name="cs" title="Class & Shift" id="cs" onchange="this.form.submit()" >';
    $r .='<option value="cs">Class & Shift</option>';
    foreach ($cs_type as $value) {
        $r .='<option value="' . $value. '" >' .$value. '</option>';
    }
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r.='</table>';
    $r .='</form>';
    $r .='</div>';

    return $r;
}

function get_insert_cs($term_name, $cs) 
{
    global $db;

    $section = $db->find_by_sql("section","class_n_shift_name", "term='$term_name' ORDER BY section", "");   
    if($cs == 'Class Name'){ $cs_table = 'class_name'; }
    else{ $cs_table = 'shift_name'; }
    
    if ($section == 'No Result Found') {
        $r .='<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Section Found</b></div>';
    }else {
        $r .='<div id="insert_number_header"><b>Term :' . $term_name . ', Type :' . $cs . '<b></div>';
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="margin:5px auto;border:1px solid #ccc;font-size:14px;" cellpadding="0" border="1">';
        $r .='<tr style="background:OldLace ;">';
        $r .='<td style="width:120px;"><b>Sections</b></td>';
        $r .='<td style="width:100px;"><b>'.$cs.'</b></td>';    
        $r .='</tr>';
        $r .='<tr>';
        $n = 0;
        foreach ($section as $sec_val) 
        {
            $r .= '<tr ';
            if ($n % 2 == 0) {
                $r .= ' class="tr1_hover" ';
            } else {
                $r .= ' class="tr2_hover" ';
            }
            $r .= ' >';
            $cs_value = $db->find_by_sql("$cs_table", "class_n_shift_name", "term='$term_name' AND section='{$sec_val['section']}'", "");
            $r .='<td>' . $sec_val['section'] . '</td>';
            $r .='<td class="tr1_hover" style="width:55px;"><input type="text" style="text-align:right;" size="6" name="cs_value[]" value="' .$cs_value[0][$cs_table]. '"></td>';
            $r .='<input type="hidden" name="section[]" value="' . $sec_val['section'] . '">';
            $r .='</tr>';
            $n++;
        }
      
        $r .='</table>';
        $r .='<input type="hidden" name="term_name"   value="' . $term_name . '">';
        $r .='<input type="hidden" name="cs_name"          value="' . $cs . '">';
        $r .='<input type="hidden" name="cs_table"    value="' . $cs_table. '">';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" colspan="4"><input type="submit" id="submit_button" name="insert_cs" value="Insert"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    }
    return $r;
}

function get_set_subject_order($section_name,$belong_subject) 
{
    global $db;
    
        $r .='<div id="insert_number_header"><b>Section :' . $section_name . '<b></div>';
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="margin:5px auto;border:1px solid #ccc;font-size:14px;" cellpadding="0" border="1">';
        $r .='<tr style="background:OldLace ;">';
        $r .='<td style="width:120px;"><b>Sections</b></td>';
        $r .='<td style="width:100px;"><b>Order</b></td>';    
        $r .='</tr>';
        $r .='<tr>';
        $n = 0;
        foreach ($belong_subject as $val) 
        {
            $r .= '<tr ';
            if ($n % 2 == 0) {
                $r .= ' class="tr1_hover" ';
            } else {
                $r .= ' class="tr2_hover" ';
            }
            $r .= ' >';
            $r .='<td style="width:200px;">' . $val['subject'] . '</td>';
            $r .='<td class="tr1_hover" style="width:55px;"><input type="text" style="text-align:right;" size="4" name="order[]" value="' .$val['priority_order']. '"></td>';
            $r .='<input type="hidden" name="subject[]" value="' .$val['subject']. '">';
            $r .='</tr>';
            $n++;
        }
      
        $r .='</table>';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" colspan="4"><input type="submit" id="submit_button" name="insert_order" value="Insert"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    
    return $r;
}

function full_mark_selection_menu($selected_term, $selected_section, $selected_subject) 
{
    global $available_terms, $available_section, $available_subject;
    $r = '';
    $r .='<div id="selection_menu2">';
    $r .='<div style="width:650px;height:20px;text-align:center;background:#ccc;border="1px solid #ccc;"><b>SET FULL MARK</b></div>';
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
    $r .='</optgroup>';
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

function get_insert_full_mark($term_name,$section_name,$subject_name) 
{
    global $db;

    $exam_type = $db->find_by_sql("exam_type","exam_type_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='$subject_name[0]'  ORDER BY exam_type", "");   
    
    if ($exam_type == 'No Result Found') {
        $r .='<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>No Exam-type Found</b></div>';
    }else {
        $r .='<div id="insert_number_header"><b>Set full mark for Term :'.$term_name[0].', Section :'.$section_name[0].', Subject :'.$subject_name[0].'<b></div>';
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="margin:5px auto;border:1px solid #ccc;font-size:14px;" cellpadding="0" border="1">';
        $r .='<tr style="background:OldLace ;">';
        $r .='<td style="width:120px;"><b>Exam-types</b></td>';
        $r .='<td style="width:100px;"><b>Full Mark</b></td>';    
        $r .='</tr>';
        $r .='<tr>';
        $n = 0;
        foreach ($exam_type as $exam_type_val) 
        {
            $r .= '<tr ';
            if ($n % 2 == 0) {
                $r .= ' class="tr1_hover" ';
            } else {
                $r .= ' class="tr2_hover" ';
            }
            $r .= ' >';
            $full_mark_value = $db->find_by_sql("full_mark", "exam_type_list", "term='$term_name[0]' AND section='$section_name[0]' AND subject='$subject_name[0]' AND exam_type='{$exam_type_val['exam_type']}'", "");
            $r .='<td>' . $exam_type_val['exam_type'] . '</td>';
            $r .='<td class="tr1_hover" style="width:55px;"><input type="text" style="text-align:right;" size="6" name="full_mark_value[]" value="' .$full_mark_value[0]['full_mark']. '"></td>';
            $r .='<input type="hidden" name="exam_type[]" value="' . $exam_type_val['exam_type'] . '">';
            $r .='</tr>';
            $n++;
        }
      
        $r .='</table>';
        $r .='<input type="hidden" name="term_name"     value="' . $term_name[0]. '">';
        $r .='<input type="hidden" name="section_name"  value="' .$section_name[0] . '">';
        $r .='<input type="hidden" name="subject_name"  value="' . $subject_name[0]. '">';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" colspan="4"><input type="submit" id="submit_button" name="insert_full_mark" value="Insert"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    }
    return $r;
}

function field_of_update_percent_aggregate($result,$section_name)
{
      global $db;
    
        $r .='<div id="insert_number_header"><b>Section :' . $section_name . '<b></div>';
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="margin:5px auto;border:1px solid #ccc;font-size:14px;background:GhostWhite ;" cellpadding="2" border="1">';
        $r .='<tr style="background:OldLace ;">';
        $r .='<td style="width:120px;"><b>Added(+) Terms</b></td>';
        $r .='<td style="width:100px;"><b>Percentage</b></td>';
        $r .='<td style="width:100px;"><b>Aggregate Term</b></td>';
        $r .='</tr>';
        $r .='<tr>';
        foreach ($result as $val) 
        {
            $r .= '<tr>';
            $r .='<td style="width:200px;">' . $val['percent_agre'] . '</td>';
            $r .='<td style="width:55px;"><input type="text" style="text-align:right;" size="4" name="percentage[]" value="' .$val['percentage']. '"></td>';
            $r .='<td style="width:150px;">' . $val['base_term'] . '</td>';
            $r .='</tr>';
            $r .= '<input type="hidden" name="percent_agre[]" value="'.$val['percent_agre'].'">';
            $r .= '<input type="hidden" name="base_term" value="'.$val['base_term'].'">';
        }
        $r .= '<input type="hidden" name="section" value="'.$section_name.'">';
        $r .='</table>';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" ><input type="submit" id="submit_button" name="delete" value="Delete" onclick="return confirm(\'Are you sure you want to delete?\');" > &nbsp; &nbsp; 
                                                              <input type="submit" id="submit_button" name="update" value="Update"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    
    return $r;
}

function field_of_added_percent_aggregate($section_name)
{
      global $db,$available_terms;
    
        $r .='<div id="insert_number_header"><b>Section :' . $section_name . '<b></div>';
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="margin:5px auto;border:1px solid #000;font-size:14px;background:GhostWhite ;" cellpadding="3" border="1">';
        $r .='<tr style="background:Teal;color:#fff;">';
        $r .='<td></td>';
        $r .='<td style="width:120px;"><b>Added(+) Terms</b></td>';
        $r .='<td></td>';
        $r .='<td style="width:100px;"><b>Aggregate Term</b></td>';
        $r .='</tr>';
        $r .='<tr>';
        foreach ($available_terms as $val) 
        {
            $r .= '<tr>';
            $r .='<td><input type="checkbox" name="percent_agre[]" value="'.$val['term'].'" ></td>';
            $r .='<td style="width:200px;">' . $val['term'] . '</td>';
            $r .='<td><input type="radio" name="base_term" value="'.$val['term'].'" ></td>';
            $r .='<td style="width:150px;">' . $val['term'] . '</td>';
            $r .='</tr>';
        }
        $r .= '<input type="hidden" name="section" value="'.$section_name.'">';
        $r .='</table>';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" ><input type="submit" id="submit_button" name="next" value="Next"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    
    return $r;
}

function field_of_set_added_percent_aggregate($section_name,$base_term,$percent_agre,$percent)
{
      global $db;
    
        $r .='<div id="insert_number_header"><b>Section :' . $section_name . '<b></div>';
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="margin:5px auto;border:1px solid #000;font-size:14px;background:GhostWhite ;" cellpadding="3" border="1">';
        $r .='<tr style="background:Teal;color:#fff;">';
        $r .='<td style="width:120px;"><b>Added(+) Terms</b></td>';
        $r .='<td><b>Set Percent</b></td>';
        $r .='</tr>';
        $r .='<tr>';
        $i = 0;
        foreach ($percent_agre as $val) 
        {
            $priority = $db->find_by_sql("term_priority","terms","term='$val'","");
            $r .= '<tr>';
            $r .='<td style="width:200px;"><input type="text" style="background:transparent;border:1px solid transparent;" name="percent_agre[]" value="'.$val.'" readonly></td>';
            $r .='<td style="width:120px;"><input type="text" style="text-align:right;" size="3" name="percent[]" value="'.$percent[$i].'" required>%</td>';
            $r .='</tr>';
            $i++;
            $r .= '<input type="hidden" name="priority[]" value="'.$priority[0]['term_priority'].'">';
        }
        $r .= '<input type="hidden" name="section" value="'.$section_name.'">';
        $r .= '<input type="hidden" name="base_term" value="'.$base_term.'">';
        $r .='</table>';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" ><input type="submit" id="submit_button" name="insert_new" value="Insert"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    
    return $r;
}

function field_of_delete_total_aggregate($result,$section_name)
{
      global $db;
    
        $r .='<div id="insert_number_header"><b>Section :' . $section_name . '<b></div>';
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="margin:5px auto;border:1px solid #ccc;font-size:14px;background:GhostWhite ;" cellpadding="2" border="1">';
        $r .='<tr style="background:OldLace ;">';
        $r .='<td style="width:120px;"><b>Added(+) Terms</b></td>';
        $r .='<td style="width:100px;"><b>Aggregate Term</b></td>';
        $r .='</tr>';
        $r .='<tr>';
        foreach ($result as $val) 
        {
            $r .= '<tr>';
            $r .='<td style="width:200px;">' . $val['total_agre'] . '</td>';
            $r .='<td style="width:150px;">' . $val['base_term'] . '</td>';
            $r .='</tr>';
            $r .= '<input type="hidden" name="base_term" value="'.$val['base_term'].'">';
        }
        $r .= '<input type="hidden" name="section" value="'.$section_name.'">';
        $r .='</table>';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" ><input type="submit" id="submit_button" name="delete" value="Delete" onclick="return confirm(\'Are you sure you want to delete?\');" >';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    
    return $r;
}

function field_of_added_total_aggregate($section_name)
{
      global $db,$available_terms;
    
        $r .='<div id="insert_number_header"><b>Section :' . $section_name . '<b></div>';
        $r .='<div id="insert_number_div">';
        $r .='<form action="" method="post">';
        $r .='<table style="margin:5px auto;border:1px solid #000;font-size:14px;background:GhostWhite ;" cellpadding="3" border="1">';
        $r .='<tr style="background:Teal;color:#fff;">';
        $r .='<td></td>';
        $r .='<td style="width:120px;"><b>Added(+) Terms</b></td>';
        $r .='<td></td>';
        $r .='<td style="width:100px;"><b>Aggregate Term</b></td>';
        $r .='</tr>';
        $r .='<tr>';
        foreach ($available_terms as $val) 
        {
            $priority = $db->find_by_sql("term_priority","terms","term='{$val['term']}'","");
            $r .= '<tr>';
            $r .='<td><input type="checkbox" name="total_agre[]" value="'.$val['term'].'" ></td>';
            $r .='<td style="width:200px;">' . $val['term'] . '</td>';
            $r .='<td><input type="radio" name="base_term" value="'.$val['term'].'" ></td>';
            $r .='<td style="width:150px;">' . $val['term'] . '</td>';
            $r .='</tr>';
            $r .= '<input type="hidden" name="priority[]" value="'.$priority[0]['term_priority'].'">';
        }
        $r .= '<input type="hidden" name="section" value="'.$section_name.'">';
        $r .='</table>';
        $r .='</div>';
        $r .='<table style="margin:auto;width:430px">';
        $r .='<tr>';
        $r .='<td style="text-align:right;padding-top:10px;" ><input type="submit" id="submit_button" name="insert" value="Insert"></td>';
        $r .='</tr>';
        $r .='</table>';
        $r .='</form>';
    
    return $r;
}



?>
