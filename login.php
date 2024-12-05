<!DOCTYPE html>
<html>
<head>
    <title>BookEasy Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    
    <div class="container">
    <h1>Book Easy</h1>
    <h4>Login</h4>
        
        <form action="" method="post">
            <table>
                <tr>
                    <td><label for="email">Email:</label></td>
                    <td><input type="text" id="email" name="email" placeholder="@gmail.com"></td>
                </tr>
                <tr>
                    <td><label for="password">Password:</label></td>
                    <td><input type="password" id="password" name="pass" placeholder="Enter password"></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" name="submit" value="Login">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>


<?php
session_start();
include 'dbc.php';
if(isset($_POST['submit']))
{
    $username=$_POST['email'];
    $passw=$_POST['pass'];
    $sql="SELECT * from users where email= '$username'";
    $query= mysqli_query($con,$sql);
    $uname_count=mysqli_num_rows($query);
    if($uname_count)
    {
        $upass=mysqli_fetch_assoc($query);
        $_SESSION['user_id'] = $upass['email']; // Assuming 'id' is the primary key in the 'users' table
        $_SESSION['name'] = $upass['email']; // Optional, if you need the email elsewhere

        $dpass=$upass['pass'];
        $pass_decode=password_verify($passw,$dpass);
        if($dpass==$passw)
        {
            echo "Log in successful";
        ?>
        <script>
            window.location.href='home2.php';
            </script>
        <?php 
        }
        else
        {
            echo"Password wrong";
        }  
    }
   
    else
    {
        echo "Invalid username";
    } 
    
}    

?>