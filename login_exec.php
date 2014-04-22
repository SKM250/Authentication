<?php

       
        //Start session
	session_start();
 
	//Include database connection details
	require_once('connection.php');
  
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
 
	//Sanitize the POST values
	$cpr = clean($_POST['cpr']);
	$password = clean($_POST['password']);
  
 
	//Create query
	$qry="SELECT * FROM member WHERE cpr='$cpr' AND password='$password'";
	$result=mysql_query($qry);
            
	//Check whether the query was successful or not
	if($result) {

            if($password === '321' && $cpr === '131313' && mysql_num_rows($result) > 0) {
			//Login Successful
			session_regenerate_id();
                        session_set_cookie_params(0);
			$member = mysql_fetch_assoc($result);
			$_SESSION['SESS_MEMBER_ID'] = $cpr;
			header("location: http://localhost:81/TaxAdmin/annual_statement/administrator.php");
			exit();
		}
            
            if(mysql_num_rows($result) > 0) {
			//Login Successful for Administrator
			session_regenerate_id();
                        session_set_cookie_params(0);
                        $member = mysql_fetch_assoc($result);
			$_SESSION['SESS_MEMBER_ID'] = $cpr;
                 
			header("location: http://localhost:81/Tax/annual_statement/annualStatementView.php");
			exit();
	
            } else {
			//Login failed
			$errmsg_arr[] = 'cpr and password not found';
			$errflag = true;
			if($errflag) {
				$_SESSION['ERRMSG_ARR'] = $errmsg_arr; 
                                session_write_close();
                                 header("location: index.html?err=1");
                                exit();
			}
		}
	}else {
		die("Query failed");
	}

?>


