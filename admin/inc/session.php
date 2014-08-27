<?php 
include("inc/libs/db.conf.php");
$localhost = pg_pconnect("host=$hostname port=$port dbname=$dbname user=$username password=$password");
?>
<?php
if (!isset($_SESSION)) {
  	session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['user'])) {
  $loginUsername=$_POST['user'];
  $password=md5($_POST['pass']);
  $MM_redirectLoginSuccess = "index.php";
  $MM_redirectLoginFailed = "login.php";
  $MM_redirecttoReferrer = false;

  $LoginRS__query=sprintf("SELECT id, username, password, realname, role, permission, father FROM users WHERE username='%s' AND password='%s'",
  get_magic_quotes_gpc() ? $loginUsername : addslashes($loginUsername), get_magic_quotes_gpc() ? $password : addslashes($password));
  
  $LoginRS = pg_query($localhost, $LoginRS__query);
  $loginFoundUser = pg_num_rows($LoginRS);
  $row = pg_fetch_assoc($LoginRS);
  
  if ($loginFoundUser) {
    $name = $row['realname'];    $id   = $row['id'];
    $role = $row['role'];
    $permission = $row['permission'];//colocar en SM
	$father = $row['father'];
	
  	
    //declare two session variables and assign them
    $_SESSION['Username'] = $loginUsername;
    $_SESSION['Realname'] = $name;
    $_SESSION['UserId']   = $id;
    $_SESSION['Role']     = $role;
    $_SESSION['permission']     = $permission;//colocar en SM
	$_SESSION['father']	  = $father;
	$_SESSION['alive']    = TRUE;
	
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: ". $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
?>
