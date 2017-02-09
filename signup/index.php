<?php 
include("../helpers/conn.php");
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
 {
// username and password received from loginform 
$first=mysqli_real_escape_string($conn,$_POST['first']);
$last=mysqli_real_escape_string($conn,$_POST['last']);
$username=mysqli_real_escape_string($conn,$_POST['username']);
$password=md5(mysqli_real_escape_string($conn,$_POST['password']));
$email=mysqli_real_escape_string($conn,$_POST['email']);
$address=mysqli_real_escape_string($conn,$_POST['address']);
$sID = "";
$zipCode=mysqli_real_escape_string($conn,$_POST['zipCode']);



$sql_query="INSERT INTO users(id,first, last, username,email,login,address,zipCode) VALUES('sID','$first','$last','$username','$email','$password','$address','$zipCode')";
$result=mysqli_query($conn,$sql_query)or die(mysqli_error($conn));
//$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
//$count=mysqli_num_rows($result);


// If result matched $username and $password, table row must be 1 row
if($result)
{
$_SESSION['login_user']=$username;

header("../home/index.php");
}
else
{
$error="Registration Failed!";
}
}
?>
<!DOCTYPE html>
<html>
<head>
<link href="../helpers/header_footer.css" type="text/css" rel="stylesheet" />
<link href="style.css" type="text/css" rel="stylesheet" />
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<title>WWYDH Registration</title>
</head>

<body>

<div id="signup">
<div class="width">
    <div id="signupTitle">Registration</div>
     <div id="form">
    <form method="post" action="#" name="loginform">
    <input type="text" value="" placeholder="Enter First Name"  name="first" class="form-size" />
	<input type="text" value="" placeholder="Enter Last Name"  name="last" class="form-size" />
    <input type="text" value="" placeholder="Enter Username"  name="username" class="form-size"/>
    <input type="password" value="" placeholder="Enter Password"  name="password" class="form-size" />
    <input type="text" value="" placeholder="Enter Email"  name="email" class="form-size" />
    <input type="text" value="" placeholder="Enter Address"  name="address" class="form-size" />
    <input type="text" value="" placeholder="Enter Zip Code" name="zipCode" class="form-size" />
   <input type="submit" id="enter" class="form-size" value="Sign Up">
    </form>
    </div>
    </div>
</div>
 <div id="footer">
            <div class="grid-inner">
                &copy; Copyright WWYDH <?php echo date("Y") ?>
            </div>
    </div>
</body>

</html>