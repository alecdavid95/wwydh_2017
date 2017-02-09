<?php
include("conn.php");
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST")
 {
// username and password received from loginform
$username=mysqli_real_escape_string($conn,$_POST['username']);
$password=mysqli_real_escape_string($conn,$_POST['password']);
$password=md5($password);

$sql_query="SELECT * FROM users WHERE username='$username' and password='$password'";
$result=mysqli_query($conn,$sql_query)or die(mysqli_error($conn));
$row=mysqli_fetch_array($result,MYSQLI_ASSOC);
$count=mysqli_num_rows($result);


// If result matched $username and $password, table row must be 1 row
if($count==1)
{
$_SESSION['login_user']=$username;

header("location: homepg.php");
}
else
{
$error="Username or Password is invalid";
}
}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WWYDH Login</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<div class="container">

 <div id="login-form">
    <form method="post" action="" autocomplete="off">

     <div class="col-md-12">

         <div class="form-group">
             <h2 class="">Sign In.</h2>
            </div>

         <div class="form-group">
             <hr />
            </div>

            <?php
   if ( isset($errMSG) ) {

    ?>
    <div class="form-group">
             <div class="alert alert-danger">
    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
             </div>
                <?php
   }
   ?>

            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
             <input type="email" name="email" class="form-control" placeholder="Your Email" value="<?php echo isset($email) ? $email : ""; ?>" maxlength="40" />
                </div>
                <span class="text-danger"><?php echo isset($emailError) ? $emailError : ""; ?></span>
            </div>

            <div class="form-group">
             <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
             <input type="password" name="pass" class="form-control" placeholder="Your Password" value="<?php echo isset($pass) ? $pass : ""; ?>" maxlength="15" />
                </div>
                <span class="text-danger"><?php echo isset($passError) ? $passError : ""; ?></span>
            </div>

            <div class="form-group">
             <hr />
            </div>

            <div class="form-group">
             <button type="submit" class="btn btn-block btn-primary" name="btn-login">Sign In</button>
            </div>

            <div class="form-group">
             <hr />
            </div>

            <div class="form-group">
             <a href="register.php">Sign Up Here...</a>
            </div>

        </div>

    </form>
    </div>

</div>

</body>
</html>
