<?php
require 'authenticate.php';
require_once('includes/Mysqldb.php');
?>

<?php require 'includes/header.php'; ?>
<?php
 if($_GET['category'] == 'Home')
 {
 $year = date("Y");
 $student_info = $db->find_by_sql("student_name,section,class_name,shift_name,class_roll","result_$year","student_id='$user115122'","1") ;
 $available_years = array("$year","2014");
 if(!isset($_POST['year']))
       { 
           $selected_year = array($year => 'selected');
           $result_year = $year;
           $result_element = $db->find_by_sql("section,class_name,shift_name,class_roll","result_$year","student_id='$user115122'","1");
           $term = $db->find_by_sql("DISTINCT term","result_$year","student_id='$user115122'  ORDER BY term","");
       }
       else{
            if($_POST['year'] !== 'Available Years')
            {   $submitted_year = $_POST['year'];
                $result_year = $submitted_year;
                $selected_year = array($submitted_year => 'selected');
                $result_element = $db->find_by_sql("section,class_name,shift_name,class_roll","result_$result_year","student_id='$user115122'","1");
                $term = $db->find_by_sql("DISTINCT term","result_$result_year","student_id='$user115122'  ORDER BY term","");
            }
                
          }
       //print_r($result_element);
 ?>
<div style="width:17%;height:100%;float:right;">
      <div id="student_photo">
        <?php
           $photo = $db->find_by_sql("photo","student_information","student_id='$user115122'","");
           if($photo == 'No Result Found')
           { echo '<div id="message" style="margin-top:50px;"><b>No Result Found</b></div>'; }
           else{
             echo '<img src="'.$photo[0]['photo'].'" width="180px" height="180px">';
           }
        ?>
      </div>

      <div id="student_identity" >
        <?php
           if($student_info == 'No Result Found')
           { echo '<div id="message" style="margin-top:100px;"><b>No Result Found</b></div>'; }
           else{
              echo '<table style="font-size:13px;" cellpadding="2" >';
              echo '<tr><td style="text-align:left;"><b>Student Id: </b>'.substr($user115122,4).'</td></tr>
                    <tr><td style="text-align:left;"><b>Name: </b>'.$student_info[0]['student_name'].'</td></tr>
                    <tr><td style="text-align:left;"><b>Class Roll: </b>'.$student_info[0]['class_roll'].'</td></tr>';
              echo '<tr><td style="text-align:left;"><b>Class: </b>'.$student_info[0]['class_name'].'</td></tr>
                    <tr><td style="text-align:left;"><b>Section: </b>'.$student_info[0]['section'].'</td></tr>
                    <tr><td style="text-align:left;"><b>Shift: </b>'.$student_info[0]['shift_name'].'</td></tr>';
              echo'</table>';
           }
        ?>
     </div>
</div>


<div style="width:80%;height:100%;float:left;margin:0 0 0 2%;border: 1px solid #0099CC;">
<div id="result_year">
    <div style="width:125px;height:20px;margin:4px auto;">
        <form action="" method="post">
        <select name="year" title="Available Years"  onchange="this.form.submit()">';
          <option value="Available Years">Available Years</option>';
          <optgroup label="Years :">
           <?php 
             foreach ($available_years as $value)
             { echo '<option value="'.$value.'"  '.$selected_year[$value].'>'.$value.'</option>' ;}
            ?>
          </optgroup>
        </select>         
        </form>
    </div>
</div>
<div id="student_result">
<?php 
  if($result_element == 'No Result Found')
  {
      echo '<div id="message" style="margin-top:150px;"><b>No Result Found</b></div>';
  }elseif($_POST['year'] == 'Available Years')
  {  
      echo '<div id="message" style="margin-top:150px;"><b>Please select any available year</b></div>';
  }else{
?>
    <div  id="yearly_student_identity">
        <?php 
          echo '<table cellpadding="5" >';
          echo '<tr>
                <td style="text-align:left;" colspan="3"><b>Year: </b>'.$result_year.'</td>
               </tr>';
          echo '<tr><td style="text-align:left;"><b>Class Roll: </b>'.$result_element[0]['class_roll'].'</td></tr>
                <tr><td style="text-align:left;"><b>Class: </b>'.$result_element[0]['class_name'].'</td></tr>
                <tr><td style="text-align:left;"><b>Section: </b>'.$result_element[0]['section'].'</td></tr>
                <tr><td style="text-align:left;"><b>Shift: </b>'.$result_element[0]['shift_name'].'</td></tr>';
          echo'</table>';
        ?>
    </div>
    <div>
        <?php 
            foreach($term as $term_val)
            {
                $subject = $db->find_by_sql("DISTINCT subject","result_$result_year","term='{$term_val['term']}' AND student_id='$user115122'  ORDER BY subject","");
                $rc_n_rt = $db->find_by_sql("rc_name,rt_name","result_$result_year","term='{$term_val['term']}' AND student_id='$user115122'","1");
                $permission = $db->find_by_sql("permission","account_option","item='print'","");
                $gpa_n_total = $db->find_by_sql("gpa,total_number,aggre_total_number","result_$result_year","term='{$term_val['term']}' AND student_id='$user115122'","1");
                
                if($rc_n_rt[0]['rt_name'] == 'Only Grade')
                { $only_grade = 'only_grade'; }
                elseif($rc_n_rt[0]['rt_name'] == 'GPA')
                { $gpa = 'gpa'; }
                
                if($rc_n_rt[0]['rt_name'] == 'Total Number')
                { $total_number = 'total_number'; }
                if($rc_n_rt[0]['rc_name'] == 'Total Number Aggregate')
                { $total_number_aggre = 'Total Number Aggregate'; }
                
                if($permission[0]['permission'] == 'Yes')
                { $print_number = 'print_it'; }
                
                $r .='<div id="yearly_term_name"><b>'.$term_val['term'].' Examination</b></div>';
                
                $r .= '<table style="margin:30px auto;border:1px solid #999;" border="1">';
                $r .='<tr style="background:#fff1e3;">';
                $r .='<td style="width:200px;padding:3px 5px;text-align:left;"><b>Subject</b></td>';
                if($print_number == 'print_it' || $total_number == 'total_number')
                {$r .='<td style="padding:3px 5px;text-align:center;"><b>Number</b></td>';}
                if($only_grade == 'only_grade' || $gpa == 'gpa')
                {$r .='<td style="padding:3px 5px;text-align:center;"><b>Grade</b></td>';}
                if($total_number_aggre == 'Total Number Aggregate' && $total_number == 'total_number')
                {$r .='<td style="padding:3px 5px;text-align:center;"><b>Aggregate Number</b></td>';}
                $r .='</tr>';
                        $n = 0;
                        foreach ($subject as $subj)
                        {
                                   $result = $db->find_by_sql("addition,pass,aggre_total_num_addition,aggre_total_num_pass,grade,point","result_$result_year","term='{$term_val['term']}' AND section='{$result_element[0]['section']}' AND subject='{$subj['subject']}' AND student_id='$user115122'","");

                                    if($result !== 'No Result Found')
                                    {
                                        foreach ($result as $res)
                                         {     $r .= '<tr ';
                                                if ($n % 2 == 0) {
                                                    $r .= ' class="tr2_hover" ';
                                                } else {
                                                    $r .= ' class="tr1_hover" ';
                                                }
                                                $r .= ' >';
                                                $r .='<td style="padding:2px;text-align:left;">'.$subj['subject'].'</td>';                        
                                                if($total_number == 'total_number')
                                                { 
                                                    if($res['pass'] == 'Not Pass')
                                                    {$r .='<td style="padding:2px;text-align:center;color:red;text-decoration:underline;font-weight:bold;"><b>'.$res['addition'].'</b></td>';}
                                                    else{$r .='<td style="padding:2px;text-align:center;">'.$res['addition'].'</td>';}
                                                 }
                                                if($total_number_aggre == 'Total Number Aggregate' && $total_number == 'total_number')
                                                { 
                                                    if($res['aggre_total_num_pass'] == 'Not Pass')
                                                    {$r .='<td style="padding:2px;text-align:center;color:red;text-decoration:underline;font-weight:bold;"><b>'.$res['aggre_total_num_addition'].'</b></td>';}
                                                    else{$r .='<td style="padding:2px;text-align:center;">'.$res['aggre_total_num_addition'].'</td>';}
                                                 }
                                                if($print_number == 'print_it' && ($only_grade == 'only_grade' || $gpa == 'gpa'))
                                                { 
                                                    if($res['point'] == 0)
                                                    {$r .='<td style="padding:2px;text-align:center;color:red;text-decoration:underline;font-weight:bold;"><b>'.$res['addition'].'</b></td>';}
                                                    else{$r .='<td style="padding:2px;text-align:center;">'.$res['addition'].'</td>';}
                                                 }
                                                if($only_grade == 'only_grade' || $gpa == 'gpa')
                                                {
                                                    if($res['point'] == 0)
                                                    {$r .='<td style="padding:2px;text-align:center;color:red;text-decoration:underline;font-weight:bold;"><b>'.$res['grade'].'</b></td>';}
                                                    else{$r .='<td style="padding:2px;text-align:center;">'.$res['grade'].'</td>';}
                                                }
                                              $r .='</tr>';
                                         }
                                    }
                           $n++;
                        }
                        if($total_number == 'total_number')
                        {
                         $r .='<tr class="tr1_hover">';
                           $r .='<td style="padding:2px;text-align:left;">Grand Total</td>';
                           $r .='<td style="padding:2px;text-align:center;" >'.$gpa_n_total[0]['total_number'].'</td>';
                           if($total_number_aggre == 'Total Number Aggregate' && $total_number == 'total_number')
                           {
                             $r .='<td style="padding:2px;text-align:center;" >'.$gpa_n_total[0]['aggre_total_number'].'</td>';
                           }
                         $r .='</tr>';
                        }

                        if($total_number == 'total_number')
                        {
                         $r .='<tr class="tr2_hover">';
                           $r .='<td style="padding:2px;text-align:left;">Section Merit List</td>';
                           $r .='<td style="padding:2px;text-align:center;" >'.$section_pos.'</td>';
                           if($total_number_aggre == 'Total Number Aggregate' && $total_number == 'total_number')
                           {
                             $r .='<td style="padding:2px;text-align:center;" >'.$section_pos.'</td>';
                           }
                         $r .='</tr>';
                        }
                        if($total_number == 'total_number')
                        {
                         $r .='<tr class="tr1_hover">';
                           $r .='<td style="padding:2px;text-align:left;">Class Merit List</td>';
                           $r .='<td style="padding:2px;text-align:center;" >'.$class_pos.'</td>';
                           if($total_number_aggre == 'Total Number Aggregate' && $total_number == 'total_number')
                           {
                             $r .='<td style="padding:2px;text-align:center;" >'.$class_pos.'</td>';
                           }
                         $r .='</tr>';
                        }
                        if($gpa == 'gpa')
                        {
                         $r .='<tr class="tr1_hover">';
                           $r .='<td style="padding:2px;text-align:left;">GPA</td>';
                           $r .='<td style="padding:2px;text-align:center;" ';
                           if($print_number == 'print_it')
                           { 
                              $r .= ' colspan="2"';
                           }
                           $r .= '>'.$gpa_n_total[0]['gpa'].'</td>';
                         $r .='</tr>';
                        }
                $r .='</table>';
                
                $only_grade = ''; 
                $gpa = '';
                $total_number = '';
                $print_number = '';
                $total_number_aggre='';
            }
            echo $r;
        ?>
    </div>
    <?php } ?>
</div>  
</div>
<?php 
 }else{
     echo 'No Page Found';
 }
require 'includes/footer.php';   

?>