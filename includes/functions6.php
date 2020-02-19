<?php


function get_create_account($edited_pass) 
{    global $db;
    $student = $db->find_by_sql("DISTINCT student_id,student_name","student_account ORDER BY LENGTH(student_id),student_id","","");
    $r = '';
    $r .='<div style="padding:10px 34px 0 20px;margin:auto;width: 820px;height:85%; ">';
    $r .='<div id="cre_student_header"><b>SET PASSWORD TO CREATE ACCOUNT</b></div>';
    $r .='<div id="create_student_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td><b>Username</b></td>';
    $r .='<td><b>Student Name</b></td>';
    $r .='<td><b>Password</b></td>';
    $r .='</tr>';
    $j = 0;
     foreach ($student as $value)
        { $db->select_db("pdbdorg_rp_student_users");  
          $pass = $db->find_by_sql("actual_pass","authentication","username='{$value['student_id']}'","");
          if($pass == 'No Result Found'){$actual_pass='' ; }else{ $actual_pass = $pass[0]['actual_pass']; }
           $r .= '<tr ';
            if($j%2 == 0)
             {
                $r .= ' class="tr1_hover" ';
             }else 
                 {
                    $r .= ' class="tr2_hover" ';
                 }
            $r .= ' >' ;
            $r .='<td style="width:130px;">'.$value['student_id'].'</td>';
            $r .='<td style="width:200px;">'.$value['student_name'].'</td>';
            $r .='<input type="hidden"  name="username[]"  value="'.$value['student_id'].'">';
            if(is_array($edited_pass) == FALSE){$r .='<td style="width:200px;"><input type="text"  name="edited_pass[]"  value="'.$actual_pass.'" ></td>';}
            else{$r .='<td style="width:200px;"><input type="text"  name="edited_pass[]"  value="'.$edited_pass[$j].'" ></td>';}
            $r .='</tr>';
            $j++;
         }
    $r .='</table>';
    $r .='</div>';
    $r .='<table style="width:610px;margin:auto;" >';
    $r .='<tr>';
    $r .='<td style="padding: 5px 0 0 0;"><input type="submit" style="float: right;" id="submit_button" name="submit" value="Insert"></td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='</form>';
    $r .='</div>';

    return $r;
}

function get_create_account2($count_username,$username,$password)
{
    global $db;
    $db->select_db("pdbdorg_rp_student_users");
    for($i=0;$i<$count_username;$i++)
    {
        $hashed_pass = HashPassword($password[$i]);
        $insert = $db->insert("authentication","username,actual_pass,password","'$username[$i]','$password[$i]','$hashed_pass'","username='$username[$i]'");
        if($insert == 'already exist')
        {
            $update = $db->update("authentication","username='$username[$i]',actual_pass='$password[$i]',password='$hashed_pass'","username='$username[$i]'");
        }
    }                               
    if($insert == TRUE || $update == TRUE)
    {echo '<div style="padding: 20px;margin:auto;width: 500px;height:30px;"></div><div id="message"><b>Accounts have been created or updated successfully</b></div>';}
    
}

function delete_account()
{
     global $db;
     $student = $db->find_by_sql("DISTINCT student_id,student_name,id","student_account ORDER BY LENGTH(student_id),student_id","","");

    $r.='<div style="padding: 20px;margin:auto;width: 500px;height:350px;">';
    $r .='<div id="ed_term_header"><b>DELETE STUDENT ACCOUNT</b></div>';
    $r .='<div id="ed_term_div">';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:10px auto 0 auto;border:1px solid #999;" cellpadding="2" border="1">';
    $r .='<tr>';
    $r .='<td></td>';
    $r .='<td><b>Username</b></td>';
    $r .='<td><b>Student Name</b></td>';
    $r .='</tr>';

     foreach($student as $stu_val)
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
            $r .='<td><input type="checkbox" class="case" name="selected_id[]" value="'.$stu_val['id'].'" ></td>';
            $r .='<td style="width:120px;">'.$stu_val['student_id'].'</td>';
            $r .='<td style="width:180px;">'.$stu_val['student_name'].'</td>';
            $r .='</tr>';
            $j++;
         }
    $r .='</table>';
    $r .='</div>';       
    $r .='<table style="margin:auto;width:430px;" >';
    $r .='<tr>';
    $r .='<td style="text-align:left;padding:7px 0 0 0;"><input type="checkbox" id="selectall" /><b>Select All</b>
          &nbsp; &nbsp; <input type="image" name="selected_delete" alt="submit" value="submit" src="images/DeleteRed.png" width="20" height="20" onclick="return confirm(\'If you delete you will be lost any related data belong selected username(student-id). Are you sure you want to delete?\');"></td>';
    $r .='</tr>';
    $r .='</table>';
    $r .='</form>';
    $r .='</div>';
    
    return $r;
}

function delete_account2($selected_id)
{ global $db,$user_db_select;
     $db->select_db($user_db_select);
     $student = $db->find_by_sql("student_id","student_account","id='$selected_id'","");
     $table_array = array("student_account","student_information","student_n_numbers","add_n_grade","percent_add_n_grade","fourth_subject","gpa_result","total_add","total_result","discipline_n_roll");
     
     if($student !== 'No Result Found')
     {  foreach ($table_array as $table_val)
        {
            $delete_student = $db->delete("$table_val","student_id='{$student[0]['student_id']}'");
            if(empty($delete_student) == FALSE){ $actual_delete = $delete_student; }
        }
     }
     $db->select_db("pdbdorg_rp_student_users");
     if($student !== 'No Result Found')
     {  
        $delete_user = $db->delete("authentication","username='{$student[0]['student_id']}'");
     }
     return $actual_delete;
}

function save_result_menu($selected_term,$selected_rc,$selected_rt) 
{
    global $available_terms;
    $available_rc   = array('Distinct','% Number Aggregate','Total Number Aggregate');
    $available_rt   = array('Only Grade','GPA','Total Number');
    $r = '';
    $r .='<div id="selection_menu2">';
    $r .='<div style="width:650px;height:20px;text-align:center;background:#ccc;border="1px solid #ccc;"><b>SAVE RESULT FOR STUDENT ACCOUNT</b></div>';
    $r .='<form action="" method="post">';
    $r .='<table style="margin:5px auto 0 auto;font-weight:bold;">';
    $r .='<tr>';   
    $r .='<td style="padding-right:15px;">Year: '.date("Y").'</td>';
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
    $r .='<select name="rc_name" title="Result Category">';
    $r .='<option value="Result Category">Result Category</option>';
    $r .='<optgroup label="Categories :">';
    foreach ($available_rc as $value) {
        $r .='<option value="' . $value . '"  ' . $selected_rc[$value] . '>' . $value . '</option>';
    }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='<td>';
    $r .='<select name="rt_name" title="Result Type">';
    $r .='<option value="Result Type">Result Type</option>';
    $r .='<optgroup label="Types :">';
    foreach ($available_rt as $value) {
        $r .='<option value="' . $value . '"  ' . $selected_rt[$value] . '>' . $value . '</option>';
    }
    $r .= '</optgroup>';
    $r .='</select>';
    $r .='</td>';
    $r .='</tr>';
    $r .='<tr>';
    $r .='<td style="text-align:center;padding-top:15px;" colspan="4"><input type="submit" id="submit_button" name="submit" value="Submit" onclick="return confirm(\'Are you sure want to save result ?\');"></td>';
    $r .='</tr>';
    $r.='</table>';
    $r .='<input type="hidden" name="year" value="'.date("Y").'" >';
    $r .='</form>';
    $r .='</div>';

    return $r;
}

function distinct($year,$term_name,$rc_name,$rt_name)
{ global $db;
    $add_n_grade = $db->find_by_sql("*","add_n_grade","term='$term_name'","");
    foreach ($add_n_grade as $value)
    {
       $class_n_shift_name = $db->find_by_sql("class_name,shift_name","class_n_shift_name","section='{$value['section']}'","");
       $class_roll = $db->find_by_sql("class_roll","discipline_n_roll","student_id='{$value['student_id']}' AND term='$term_name'","");

       if($class_n_shift_name == 'No Result Found'){ $class = 'None';$shift='None'; }else{ $class = $class_n_shift_name[0]['class_name'];$shift=$class_n_shift_name[0]['shift_name']; }
       if($class_roll == 'No Result Found'){ $roll = 'None'; }else{ $roll = $class_roll[0]['class_roll']; }
       
       if($rt_name == 'GPA')
       { $gpa_result = $db->find_by_sql("gpa","gpa_result","term='$term_name' AND section='{$value['section']}' AND student_id='{$value['student_id']}' AND percent='No'","");
         if($gpa_result == 'No Result Found'){ $gpa = ''; }else{ $gpa = $gpa_result[0]['gpa']; }
       }else{ $gpa = ''; }

       if($rt_name == 'Total Number')
       { $total_number_result = $db->find_by_sql("total","total_result","term='$term_name' AND section='{$value['section']}' AND student_id='{$value['student_id']}' AND aggregate='No'","");
         if($total_number_result == 'No Result Found'){ $total_number = ''; }else{ $total_number = $total_number_result[0]['total']; }
       }else{ $total_number = ''; }

       $insert_save_result = $db->insert("result_$year","term,section,subject,student_id,student_name,addition,pass,grade,point,gpa,total_number,class_name,shift_name,class_roll,rc_name,rt_name",
       "'$term_name','{$value['section']}','{$value['subject']}','{$value['student_id']}','{$value['student_name']}','{$value['addition']}','{$value['pass']}','{$value['grade']}','{$value['point']}','$gpa','$total_number','$class','$shift','$roll','$rc_name','$rt_name'",
       "term='$term_name' AND section='{$value['section']}' AND subject='{$value['subject']}' AND student_id='{$value['student_id']}'");
       if($insert_save_result == 'already exist')
       {
          $update_save_result = $db->update("result_$year","student_name='{$value['student_name']}',addition='{$value['addition']}',pass='{$value['pass']}',grade='{$value['grade']}',point='{$value['point']}',gpa='$gpa',total_number='$total_number',class_name='$class',shift_name='$shift',class_roll='$roll',rc_name='$rc_name',rt_name='$rt_name'",
          "term='$term_name' AND section='{$value['section']}' AND subject='{$value['subject']}' AND student_id='{$value['student_id']}'");
       }
    }
    if($insert_save_result == 'created succesfully' || $insert_save_result == 'not been created' || $update_save_result == 'updated succesfully' || $update_save_result == 'not been updated')
    { return  'Result is save for Term :"'.$term_name.'", Result-category :"'.$rc_name.'" and Result-type:"'.$rt_name.'"'; }
}

function percent_aggregate($year,$term_name,$rc_name,$rt_name)
{ global $db;
    $percent_add_n_grade = $db->find_by_sql("*","percent_add_n_grade","term='$term_name'","");
    foreach ($percent_add_n_grade as $value)
    {
       $class_n_shift_name = $db->find_by_sql("class_name,shift_name","class_n_shift_name","section='{$value['section']}'","");
       $class_roll = $db->find_by_sql("class_roll","discipline_n_roll","student_id='{$value['student_id']}' AND term='$term_name'","");

       if($class_n_shift_name == 'No Result Found'){ $class = 'None';$shift='None'; }else{ $class = $class_n_shift_name[0]['class_name'];$shift=$class_n_shift_name[0]['shift_name']; }
       if($class_roll == 'No Result Found'){ $roll = 'None'; }else{ $roll = $class_roll[0]['class_roll']; }
       
       if($class_n_shift_name == 'No Result Found'){ $class = 'None';$shift='None'; }else{ $class = $class_n_shift_name[0]['class_name'];$shift=$class_n_shift_name[0]['shift_name']; }
       if($rt_name == 'GPA')
       { $gpa_result = $db->find_by_sql("gpa","gpa_result","term='$term_name' AND section='{$value['section']}' AND student_id='{$value['student_id']}' AND percent='Yes'","");
         if($gpa_result == 'No Result Found'){ $gpa = ''; }else{ $gpa = $gpa_result[0]['gpa']; }
       }else{ $gpa = ''; }

       $insert_save_result = $db->insert("result_$year","term,section,subject,student_id,student_name,addition,grade,point,gpa,class_name,shift_name,class_roll,aggregate_term,rc_name,rt_name",
       "'$term_name','{$value['section']}','{$value['subject']}','{$value['student_id']}','{$value['student_name']}','{$value['addition']}','{$value['grade']}','{$value['point']}','$gpa','$class','$shift','$roll','{$value['percent_term']}','$rc_name','$rt_name'",
       "term='$term_name' AND section='{$value['section']}' AND subject='{$value['subject']}' AND student_id='{$value['student_id']}'");
       if($insert_save_result == 'already exist')
       {
          $update_save_result = $db->update("result_$year","student_name='{$value['student_name']}',addition='{$value['addition']}',grade='{$value['grade']}',point='{$value['point']}',gpa='$gpa',class_name='$class',shift_name='$shift',class_roll='$roll',aggregate_term='{$value['percent_term']}',rc_name='$rc_name',rt_name='$rt_name'",
          "term='$term_name' AND section='{$value['section']}' AND subject='{$value['subject']}' AND student_id='{$value['student_id']}'");
       }
    }
    if($insert_save_result == 'created succesfully' || $insert_save_result == 'not been created' || $update_save_result == 'updated succesfully' || $update_save_result == 'not been updated')
    { return  'Result is save for Term :"'.$term_name.'", Result-category :"'.$rc_name.'" and Result-type:"'.$rt_name.'"'; }

}

function total_aggregate($year,$term_name,$rc_name,$rt_name)
{ global $db;
    $total_add = $db->find_by_sql("*","total_add","term='$term_name'","");
    foreach ($total_add as $value)
    {
       $class_n_shift_name = $db->find_by_sql("class_name,shift_name","class_n_shift_name","section='{$value['section']}'","");
       $class_roll = $db->find_by_sql("class_roll","discipline_n_roll","student_id='{$value['student_id']}' AND term='$term_name'","");

       if($class_n_shift_name == 'No Result Found'){ $class = 'None';$shift='None'; }else{ $class = $class_n_shift_name[0]['class_name'];$shift=$class_n_shift_name[0]['shift_name']; }
       if($class_roll == 'No Result Found'){ $roll = 'None'; }else{ $roll = $class_roll[0]['class_roll']; }
      
       if($rt_name == 'Total Number')
       { $total_number_result = $db->find_by_sql("total","total_result","term='$term_name' AND section='{$value['section']}' AND student_id='{$value['student_id']}' AND aggregate='Yes'","");
         if($total_number_result == 'No Result Found'){ $total_number = ''; }else{ $total_number = $total_number_result[0]['total']; }
       }else{ $total_number = ''; }
       
       $add_n_grade = $db->find_by_sql("*","add_n_grade","term='$term_name' AND section='{$value['section']}' AND subject='{$value['subject']}' AND student_id='{$value['student_id']}'","");
       $non_agge_total_number = $db->find_by_sql("total","total_result","term='$term_name' AND section='{$value['section']}' AND student_id='{$value['student_id']}' AND aggregate='No'","");

       $insert_save_result = $db->insert("result_$year","term,section,subject,student_id,student_name,addition,pass,total_number,aggre_total_num_addition,aggre_total_num_pass,aggre_total_number,class_name,shift_name,class_roll,aggregate_term,rc_name,rt_name",
       "'$term_name','{$value['section']}','{$value['subject']}','{$value['student_id']}','{$value['student_name']}','{$add_n_grade[0]['addition']}','{$add_n_grade[0]['pass']}','{$non_agge_total_number[0]['total']}','{$value['addition']}','{$value['pass']}','$total_number','$class','$shift','$roll','{$value['aggregate_term']}','$rc_name','$rt_name'",
       "term='$term_name' AND section='{$value['section']}' AND subject='{$value['subject']}' AND student_id='{$value['student_id']}'");
       if($insert_save_result == 'already exist')
       {
          $update_save_result = $db->update("result_$year","student_name='{$value['student_name']}',addition='{$add_n_grade[0]['addition']}',pass='{$add_n_grade[0]['pass']}',total_number='{$non_agge_total_number[0]['total']}',aggre_total_num_addition='{$value['addition']}',aggre_total_num_pass='{$value['pass']}',aggre_total_number='$total_number',class_name='$class',shift_name='$shift',class_roll='$roll',aggregate_term='{$value['aggregate_term']}',rc_name='$rc_name',rt_name='$rt_name'",
          "term='$term_name' AND section='{$value['section']}' AND subject='{$value['subject']}' AND student_id='{$value['student_id']}'");
       }
    }
    if($insert_save_result == 'created succesfully' || $insert_save_result == 'not been created' || $update_save_result == 'updated succesfully' || $update_save_result == 'not been updated')
    { return  'Result is save for Term :"'.$term_name.'", Result-category :"'.$rc_name.'" and Result-type:"'.$rt_name.'"'; }

}

/*------------password strength checker-----------*/

function checkPassword($pwd)
{
	$score = 0;

	if (strlen($pwd) < 8)
	{
           return 'Too Short';
	}
	if (strlen($pwd) > 7)
	{
		$score++;
	}

	if ( preg_match("/([a-z].*[A-Z])|([A-Z].*[a-z])/", $pwd) )
	{
		$score++;
	}
	if ( preg_match("/([a-zA-Z])/", $pwd) && preg_match("/([0-9])/", $pwd) )
	{
		$score++;
	}
	if (preg_match("/([!,%,&,@,#,$,^,*,?,_,~])/", $pwd))
	{
		$score++;
	}
	if (preg_match("/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/", $pwd))
	{
		$score++;
	}

	if ($score < 2 )
	{
		return 'Weak';
	}elseif ($score == 2 )
	{
		return 'Good';
	}else
	{
		return 'Strong';
	}


}

function HashPassword($input)
{
    //This is secure hashing the consist of strong hash algorithm sha 256 and using highly random salt
    $salt = bin2hex(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM)); 
    $hash = hash("sha256", $salt . $input); 
    $final = $salt . $hash;
    return $final;
}
 

?>