<?php

require_once ("config.php");

class myDatabase{
    
    private $connection;
    private $magic_quotes_active;
    private $real_escape_string_exists;
    public $last_query;
   
            
    function __construct($user_db_select) 
    {
        $this->open_connection($user_db_select);
        $this->magic_quotes_active = get_magic_quotes_gpc();
	$this->real_escape_string_exists = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
    }
    
    public function open_connection($user_db_select)
    {
        $this->connection = mysql_connect("localhost","root","") or die(mysql_error());
        $this->select_db($user_db_select);
    }
    
    public function select_db($user_db_select)
    {
        $db_select = mysql_select_db($user_db_select,  $this->connection) or die(mysql_error());
    }

    public function close_connection()
    {
        if(isset($this->connection))
        {
            mysql_close($this->connection);
            unset($this->connection);
        }
    }
    
    public function query($sql)
    {
       $this->last_query = $sql;
       $query_run = mysql_query($sql,  $this->connection);
       if(!$query_run)
       {
          $error = 'Last query: '.$this->last_query .'<br>Mysql error: '.mysql_error();
          die($error);
       }
       return $query_run;
    }
    
    public function escape_value( $value ) 
    {	
	if( $this->real_escape_string_exists ) 
        { // PHP v4.3.0 or higher
		// undo any magic quote effects so mysql_real_escape_string can do the work
		if( $this->magic_quotes_active ) { $value = stripslashes( $value ); }
		$value = mysql_real_escape_string( $value );
	} else 
            { // before PHP v4.3.0
		// if magic quotes aren't already on then add slashes manually
		if( !$this->magic_quotes_active ) { $value = addslashes( $value ); }
		// if magic quotes are active, then the slashes already exist
	     }
	return $value;
    }
    
    public function fetch_array($result)
    {
        return mysql_fetch_array($result);
    }
    
    public function fetch_field($result)
    {
        return mysql_fetch_field($result);
    }
    
    public function num_rows($result)
    {
        return mysql_num_rows($result);
    }
    
    public function affected_rows()
    {
        return mysql_affected_rows($this->connection);
    }
    
    public function insert_id()
    {
        //get the last id inserted over the current db connection
        return mysql_insert_id($this->connection);
    }
    
    public function find_by_sql($column,$table,$where,$limit)
    {
        
        if((empty($where) == 1) && (empty($limit) == 1))
        {
            $query_run = $this->query("SELECT $column FROM $table");
        }elseif (empty($where) == 0) 
        {
            $query_run = $this->query("SELECT $column FROM $table WHERE $where");
        }elseif (empty($limit) == 0) 
        {
            $query_run = $this->query("SELECT $column FROM $table LIMIT $limit");
        }
        if($this->num_rows($query_run) == NULL)
        {
            return 'No Result Found';           
        }else
            {
                while ($row2 = $this->fetch_field($query_run))
                {
                    $field[] = $row2->name;
                }

                while($row = $this->fetch_array($query_run))
                {
                    $result[] = $this->set_result_array($row,$field);
                } 
                return $result;
            }              
    }
    
    private function set_result_array($result,$field)
    {
        foreach($result as $key => $value)
        {
            if(in_array($key,$field))
            {
               if($key !== 0)
               {             
                 $catch[$key] = $value; // just like upper style : $object->username = $record['username'];
               }
            }
        }

        return $catch;
    }
    
    
    public function insert($table,$columns,$values,$check_exist)
    {
        $check = $this->query(" SELECT * FROM $table WHERE $check_exist ");
        if($this->num_rows($check) == 1)
        {
            return 'already exist';
        }else 
            {
                $sql = " INSERT INTO $table ($columns) VALUES ($values)";
                $query_run = $this->query($sql);

                return $this->affected_rows() ? 'created succesfully' : 'not been created';
            }       
    }
    
    public function update($table,$set,$where)
    {      
        $sql = " UPDATE $table SET $set WHERE $where";
        $query_run = $this->query($sql);

        return $this->affected_rows() ? 'updated succesfully' : 'not been updated';           
    }
    
    
    public function delete($table,$where)
    {
        $sql = " DELETE FROM $table WHERE $where";
        $query_run = $this->query($sql);

        return $this->affected_rows() ? TRUE : FALSE;
    }
    
    public function get_message($number,$subject,$condition)
    {
        switch ($condition) 
        {
            case 'already exist':
                return "<div id=\"message\"><b>Given $subject already exist</b></div>";
                break;
            case 'not been created':
                return "<div id=\"message\"><b>Sorry,$subject has not been created</b></div>";
                break;

            default:
                return "<div id=\"message\"><b>$number new $subject has been created successfully</b></div>";
                break;
        }
    }
    
    public function get_column_exists()
    {
            if(($this->find_by_sql("*","terms","","") == 'No Result Found'))
            {
                return "No Term Found";
            }elseif(($this->find_by_sql("*","section_list","","") == 'No Result Found'))
            {
                return "No Section Found";
            }elseif (($this->find_by_sql("*","subject_list","","") == 'No Result Found')) 
            {
                return "No subject Found";
            }elseif (($this->find_by_sql("*","exam_type_list","","") == 'No Result Found')) 
            {
                return "No Exam-type Found";
            }
    }
    
    public function get_section_exists($terms,$sections)
    {
        foreach ($sections as $value1)
        {
            foreach ($terms as $value2)
            {
                $result = $this->find_by_sql("section","section_list","section='$value1' AND term='$value2'", "");
                if($result == 'No Result Found')
                { return 'Section: "'.$value1.'" does not exist in Term: "'.$value2.'"' ;}
            }
        }
    }
        
    public function get_subject_exists($terms,$sections,$subjects)
    {
        foreach ($subjects as $value1)
        {
            foreach ($sections as $value2)
            {
                foreach ($terms as $value3)
                {
                   $result = $this->find_by_sql("subject","subject_list","section='$value2' AND term='$value3' AND subject='$value1'", "");
                   if($result == 'No Result Found')
                   { return 'Subject: "'.$value1.'" does not exist in Section: "'.$value2.'", Term: "'.$value3.'"' ;}
                }
            }
        }
    }
    
    public function get_exam_type_exists($term,$section,$subject,$exam_type)
    {
        foreach ($exam_type as $value)
        {
            foreach ($subject as $value1)
            {
                foreach ($section as $value2)
                {
                    foreach ($term as $value3)
                    {
                       $result = $this->find_by_sql("exam_type","exam_type_list","section='$value2' AND term='$value3' AND subject='$value1' AND exam_type='$value'", "");
                       if($result == 'No Result Found')
                       { return 'Exam-type: "'.$value.'" does not exist in Subject :"'.$value1.'", Section: "'.$value2.'", Term: "'.$value3.'"' ;}
                    }
                }
            }
        }
    }
    
    public function get_rt_exist($rc_name,$rt_name)
    {
       if($rc_name == '% Number Aggregate' && $rt_name == 'Total Number')
       { return 'Result-type: "'.$rt_name.'" is not available in Result-category :"'.$rc_name.'"' ; }
       elseif($rc_name == 'Total Number Aggregate' && ($rt_name == 'Only Grade' || $rt_name == 'GPA'))
       { return 'Result-type: "'.$rt_name.'" is not available in Result-category :"'.$rc_name.'"' ; }
    }
    
    public function set_checked($selected_value,$specifier)
    {
        foreach($selected_value as $key => $value)
        {            
            $catch[$value] = $specifier; 
        }
        return $catch;
    }
    
    public function fourth_checked($selected_value,$specifier)
    {
        $i = 0;
        foreach($selected_value as $key => $value)
        {            
            $catch[$i][$value] = $specifier;
            $i++;
        }
        return $catch;
    }
    
    public function duplicate_value_check($field)
    {
        $ready_field = array_count_values($field);

        foreach($ready_field as $key => $value)
        {
                if($value > 1 )
                {
                    return 'Duplicate value: "'.$key.'" found';
                    break;          
                }
        }
    }
    
    
    
}



$user115122 = $_SESSION['username'];
$first_four_words = substr($user115122,0,4);
$user_db_select = get_user_database($first_four_words);

$db = new myDatabase($user_db_select);


function get_user_database($user)
{

    if($user == '') //first four letter of admin's username
    {
       $r = ""; //define database name
    }elseif($user == '')//first four letter of admin's username
    {
       $r = ""; //define database name
    }elseif($user == '')//first four letter of admin's username
    {
       $r = ""; //define database name
    }
    
  return $r ;
}


$getting_4_codes = substr($user_db_select,11,4);











?>
