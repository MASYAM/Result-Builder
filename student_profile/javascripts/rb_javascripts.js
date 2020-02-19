
/* loading gif on content.php */

	$(window).bind("load", function() {
	    $('#loading').fadeOut(4000);
	});
   
/* select all,checkbox */

$(document).ready(function() { 
    // add multiple select / deselect functionality
      $("#selectall").click(function() {
      $('.case').attr('checked', this.checked);
       });
   // if all checkbox are selected, check the selectall checkbox  also        
   $(".case").click(function() {
      if ($(".case").length == $(".case:checked").length) {
      $("#selectall").attr("checked", "checked");
       }
      else {
      $("#selectall").removeAttr("checked");
       }       
       });
   });


   
/* submit form with multiple actions by three different button */ 

    function submitMe(obj)
    {
          if(obj.value == "Save as Recipients")
          {
           document.getElementById('frm').action = 'manage_grouprecords.php';
           
          }else if(obj.value == "Process to Sms")
          {
           document.getElementById('frm').action = 'send_groupsms.php';
          }
          document.getElementById('frm').submit();
    }
    
    function submitCross()
    {
          if(confirm("Are you sure want to delete ?"))
          {
            document.getElementById('frm').action = 'delete_data.php';
            document.getElementById('frm').submit();
          }          
    }
/* input id,name,phone validation */


function manId(f) 
 {
	if (!/^[a-zA-Z0-9-_]*$/.test(f.value)) 
      {
		alert("Invalid input!");
		f.value = f.value.replace(/[^\D]/g,"");
		f.value = f.value.replace(/[^\d]/g,"");		
	  }
 }
function manName(f) 
{
	if (!/^[a-zA-Z0-9 '.]*$/.test(f.value)) 
     {
		alert("Invalid input!");
		f.value = f.value.replace(/[^\D]/g,"");
		f.value = f.value.replace(/[^\d]/g,"");		
	  }
}
function manPhone(f) 
{
	//if(!/^\-?([0]|01|017|016|011|015|018|019|)((\.|,)\d{0,11})?$/.test(f.value)) 
     if (!/^[0-9]*$/.test(f.value))
     {
        alert("Invalid input!");
        f.value = f.value.replace(/[^\D]/g,"");
        f.value = f.value.replace(/[^\d]/g,"");		
     }
}
function manPhoneWhenEdit(f) 
{
	//if(!/^\-?([0]|01|017|016|011|015|018|019|)((\.|,)\d{0,11})?$/.test(f.value)) 
     if (!/^[0-9]*$/.test(f.value))
     {
        alert("Invalid input!");
        f.value = f.value.replace(/[^\D]/g,"");
        f.value = f.value.replace(/[^\d]/g,"");		
     }
}


/* instant sms */
    
    function instant_permission(f)
    {
      if (f.value == 'upload')
       {
        document.getElementById('input').checked = false;
        document.getElementById('process_sms').disabled = false;
       }
       if (f.value == 'input')
       {
        document.getElementById('upload').checked = false;
        document.getElementById('process_sms').disabled = false;
       }
    }
   

/* password matching images */

function checkPasswordMatch() {

     var password = $("#txtNewPassword").val();
     var confirmPassword = $("#txtConfirmPassword").val(); 

     if(password != confirmPassword )
      {
         $("#divCheckPasswordMatch").html("<img src=\"images/cross_octagon.png\" width=\"17px\" height=\"17px\" >");        
      }
     else
     { 
         $("#divCheckPasswordMatch").html("<img src=\"images/tick.png\" width=\"17px\" height=\"17px\" >") ;        
     }
	
}

$(document).ready(function () {
	$("#txtConfirmPassword").keyup(checkPasswordMatch);
	
});


/* Password strength checking */

$(document).ready(function() {

	$('#txtNewPassword').keyup(function(){
		$('#result').html(checkStrength($('#txtNewPassword').val()))
	})	
	
	function checkStrength(password){
    
	//initial strength
    var strength = 0
	
    //if the password length is less than 8, return message.
    if (password.length < 8) { 
		$('#result').removeClass()
		$('#result').addClass('short')
		return '<p style="color:#FF99CC;font-size:11px;"><b>Too Short</b></p>' 
	}
    
    //length is ok.
	
	//if length is 8 characters or more, increase strength value
	if (password.length > 7) strength += 1
	
	//if password contains both lower and uppercase characters, increase strength value
	if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1
	
	//if it has numbers and characters, increase strength value
	if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1 
	
	//if it has one special character, increase strength value
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1
	
	//if it has two special characters, increase strength value
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
		
	
	//if value is less than 2
	if (strength < 2 ) {
		$('#result').removeClass()
		$('#result').addClass('weak')
		return '<p style="color:#FF99CC;"><b>Weak</b></p>'			
	} else if (strength == 2 ) {
		$('#result').removeClass()
		$('#result').addClass('good')
		return '<p style="color:orange;"><b>Good</b></p>'		
	} else {
		$('#result').removeClass()
		$('#result').addClass('strong')
		return '<p style="color:#99CC00;"><b>Strong</b></p>'
	}
}
});



/* Loading GIF */

function doLoading()
        {
	  document.getElementById('loadingImg').style.display="block" ;	    
	}
function doLoadingDR()
        {
	  document.getElementById('loadingImg').style.visibility="visible" ;	    
	}        


/* print report */
    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data) 
    {
        var mywindow = window.open('', 'report');
        
        mywindow.document.write('<html><head><title>Progress Report</title>');
 
        mywindow.document.write('</head><body >');
       
        mywindow.document.write(data);
       
        mywindow.document.write('</body></html>');

        mywindow.print();
        mywindow.close();

        return true;
    }

//add calender into html form

  $(document).ready(function() {
    $("#datepicker1").datepicker();
  });
  $(document).ready(function() {
    $("#datepicker2").datepicker();
  });

//select option anable/disable

function handle_select(s) {
    if (s.value !== 'Available Terms') 
    {
        document.getElementById('section').disabled = false;
        document.getElementById('print_number').disabled = false;
        document.getElementById('total_number').disabled = false;
        document.getElementById('only_grade').disabled = false;
        document.getElementById('gpa').disabled = false;
        document.getElementById('cs').disabled = false;
    }
}


function publish_selection(s)
{
    if (s.value == 'only_grade') 
    {
        document.getElementById('total_number').checked = false;
        document.getElementById('gpa').checked = false;
    }else if(s.value == 'total_number')
    {
        document.getElementById('only_grade').checked = false;
        document.getElementById('gpa').checked = false;
    }else if(s.value == 'gpa')
    {
        document.getElementById('total_number').checked = false;
        document.getElementById('only_grade').checked = false;
    }
}



function checkPasswordMatch() {

     var password = $("#txtNewPassword").val();
     var confirmPassword = $("#txtConfirmPassword").val(); 

     if(password != confirmPassword )
      {
         $("#divCheckPasswordMatch").html("<img src=\"javascript/cross_octagon.png\" width=\"17px\" height=\"17px\" >");        
      }
     else
     { 
         $("#divCheckPasswordMatch").html("<img src=\"javascript/tick.png\" width=\"17px\" height=\"17px\" >") ;        
     }
	
}

$(document).ready(function () {
	$("#txtConfirmPassword").keyup(checkPasswordMatch);
	
});


$(document).ready(function() {

	$('#txtNewPassword').keyup(function(){
		$('#result').html(checkStrength($('#txtNewPassword').val()))
	})	
	
	function checkStrength(password){
    
	//initial strength
    var strength = 0
	
    //if the password length is less than 8, return message.
    if (password.length < 8) { 
		$('#result').removeClass()
		$('#result').addClass('short')
		return '<span style="color:#CC3300;font-size:12px;"><b>Too Short</b></span>' 
	}
    
    //length is ok.
	
	//if length is 8 characters or more, increase strength value
	if (password.length > 7) strength += 1
	
	//if password contains both lower and uppercase characters, increase strength value
	if (password.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1
	
	//if it has numbers and characters, increase strength value
	if (password.match(/([a-zA-Z])/) && password.match(/([0-9])/))  strength += 1 
	
	//if it has one special character, increase strength value
    if (password.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1
	
	//if it has two special characters, increase strength value
    if (password.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,%,&,@,#,$,^,*,?,_,~])/)) strength += 1
		
	
	//if value is less than 2
	if (strength < 2 ) {
		$('#result').removeClass()
		$('#result').addClass('weak')
		return '<span style="font-size:12px;color:#CC0000;"><b>Weak</b></span>'			
	} else if (strength == 2 ) {
		$('#result').removeClass()
		$('#result').addClass('good')
		return '<span style="font-size:12px;color:#0079ac;"><b>Good</b></span>'		
	} else {
		$('#result').removeClass()
		$('#result').addClass('strong')
		return '<span style="font-size:12px;color:#006600;"><b>Strong</b></span>'
	}
}
});
