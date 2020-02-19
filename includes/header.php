<!DOCTYPE html>
<html>
  <head>
    <title>Result Builder</title>
    <link rel="shortcut icon" href="images/Logofinal.ico" />
    <link href="stylesheets/design.css" media="all" rel="stylesheet" type="text/css" />
    <link href="stylesheets/design2.css" media="all" rel="stylesheet" type="text/css" />
    <link href="stylesheets/css.pagination2.css" media="all" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="javascripts/jquery.min.js"> </script>
    <script type="text/javascript" src="javascripts/rb_javascripts.js"> </script>
 
  </head>
  <body>
    <div id="header_nav">
        <table style="margin:5px 0px 10px 20px;float: left;" cellpadding="10" >
            <?php  
                $set_header_nav =array('Home'   => 'home.php?category=Home',
                                      'Create Exam-profile'  => 'create.php?category=Create Exam-profile&&subcat=Term',
                                      'Manage Records'  => 'manage_records.php?category=Manage Records&&subcat=Insert Name and Id',
                                      //'Generate'     => 'generate.php?category=Generate&&subcat=Generate Distinct',
                                      'Manage Exam-profile' => 'manage_exam.php?category=Manage Exam-profile&&subcat=Term',
                                      'View'    => 'view.php?category=View&&subcat=Section Details',
                                      'Publish' => 'publish.php?category=Publish&&subcat=Distinct Publish',
                                      'Others'  => 'others.php?category=Others&&subcat=Insert Discipline and Roll',
                                      //'Student Account'  => 'student_account.php?category=Student Account&&subcat=Save Result',
                                      'Help'    => '#');
                echo '<td style="padding:0px;"><img src="images/leaf.svg" width="30px" height="30px"></td>';
                foreach ($set_header_nav as $key => $value)
                {
                   if($_GET['category'] == $key)
                   {
                       echo '<td style="padding:7px 10px 0 0;"><a class="selected_header_nav_link" href="'.$value.'"><b>'.$key.'</b></a></td>';           
                   }else
                       {
                          echo '<td style="padding:7px 10px 0 0;"><a class="header_nav_link" href="'.$value.'">'.$key.'</a></td>';
                       }
                }
            ?>
        </table>
        
        <table style="margin:1px 10px 10px 0px;float: right;" cellpadding="10">
            <td><a class="header_nav_link" href="logout.php" onclick="return confirm('Are you sure want to log out?')">Log out</a></td>
        </table>
    </div>
      
  <div id="main">
      
      <div id="main_content_nav">
          
         <?php
            $category = $_GET['category'];

            if($category !== 'Home' && ($category == 'Create Exam-profile' || $category == 'Manage Records' || $category == 'Generate' || 
              $category == 'Manage Exam-profile' || $category == 'View' || $category == 'Publish' || $category == 'Others' || $category == 'Student Account'))
            {
                $set_main_content_nav=array('Home'  => array(1 => 'create_terms.php',
                                                    2 =>'create_sec.php',
                                                    3 =>'create_subject.php'),
                                           'Create Exam-profile' => array('Term' => 'create.php',
                                                             'Section' =>'create.php',
                                                             'Subject' =>'create.php',
                                                             'Exam-type' =>'create.php',
                                                             'Grade' =>'create.php',
                                                             'Pass Mark' =>'create.php'),
                                           'Manage Records' => array('Insert Name and Id' => 'manage_records.php',
                                                             'Insert Number' =>'manage_records.php',
                                                             'Edit Name and Id' =>'manage_records.php',
                                                             'Upload File' =>'manage_records.php'),
                                           'Generate'   => array('Generate Distinct' => 'generate.php',
                                                             'Generate % Number Aggregate' => 'generate.php',
                                                             'Generate Total Number Aggregate' => 'generate.php'),
                                           'Manage Exam-profile' => array('Term' =>'manage_exam.php',
                                                             'Section' =>'manage_exam.php',
                                                             'Subject' =>'manage_exam.php',
                                                             'Exam-type' =>'manage_exam.php',
                                                             'Grade' =>'manage_exam.php',
                                                             'Pass Mark' =>'manage_exam.php'),
                                           'View'   => array('Section Details' => 'view.php',
                                                             'Exam-profile' => 'view.php'  
                                                            ),
                                           'Publish'=> array('Distinct Publish' => 'publish.php',
                                                            '% Number Aggr. Publish' => 'publish.php',
                                                            'Total Number Aggr. Publish' => 'publish.php',
                                                            'Add Multiple Term then Publish' => 'publish.php',
                                                            'Excel Publish' => 'publish.php'
                                                            ),
                                           'Others'=> array('Insert Discipline and Roll' => 'others.php',
                                                            'Insert Class and Shift Name' => 'others.php',
                                                            'Set Subject Order' => 'others.php',
                                                            'Set Full Mark' => 'others.php',
                                                            'Set % Aggr.' => 'others.php',
                                                            'Set Total Number Aggr.' => 'others.php'
                                                            ),
                                           'Student Account'=> array('Save Result' => 'student_account.php',
                                                            'Insert Information' => 'student_account.php',
                                                            'Account Option' => 'student_account.php',
                                                            'Create Account' => 'student_account.php',
                                                            'Delete Account' => 'student_account.php',
                                                            'Delete Result Term'=>'student_account.php'
                                                            ),
                                           'Help'   => array('Insert Name and Id' => 'manage.php',
                                                             'Insert Numbers' =>'manage.php'
                                                            )
                                      ) ;


                foreach ($set_main_content_nav[$category] as $key => $value)
                {

                    if($_GET['subcat'] == $key)
                    {
                      echo '<div style="float:left;margin:3px 0px 0 4px;"><a class="selected_main_content_link" href="'.$value.'?category='.$category.'&&subcat='.$key.'" >'.$key.'</a></div>';
                    }else 
                        {
                           echo '<div style="float:left;margin:3px 0px 0 4px;"><a class="main_content_link" href="'.$value.'?category='.$category.'&&subcat='.$key.'" >'.$key.'</a></div>';
                        }

                }
                
            }
             echo '<div style="float:right;margin:3px 0px 0 4px;"><b>Username:</b> '.$user115122.'</div>';
          ?>

          
      </div>
    
