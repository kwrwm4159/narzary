<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Easy Signup</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <div class="container">
        <h1>Book Easy</h1>
        <h4>Register Yourself</h4>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post">
            <table>
                <tr>
                    <td><label for="firstn">First Name</label></td>
                    <td><input type="text" id="firstn" name="firstn" placeholder="Enter your first name" required></td>
                </tr>
                <tr>
                    <td><label for="lastn">Last Name</label></td>
                    <td><input type="text" id="lastn" name="lastn" placeholder="Enter your last name" required></td>
                </tr>
                <tr>
                    <td><label for="pno">Phone No.</label></td>
                    <td><input type="text" id="pno" name="pno" placeholder="123-456-7890" required></td>
                </tr>
                <tr>
                    <td><label for="mail">Email</label></td>
                    <td><input type="email" id="mail" name="mail" placeholder="@gmail.com" required></td>
                </tr>
                <tr>
                    <td><label for="pword">Password</label></td>
                    <td><input type="password" id="pword" name="pword" placeholder="Enter your password" required></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        <input type="submit" name="submit" value="Register">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>


<?php
include 'dbc.php';
if(isset($_POST['submit']))
    {
        $fn= $_POST[ "firstn"];
        $ln= $_POST["lastn"];
        $em= $_POST["mail"];
        $pn= $_POST["pno"];
        $ps= $_POST["pword"];

    $inq="INSERT INTO users (fname,lname,email,phn,pass)
    values ('$fn','$ln','$em','$pn','$ps')";

    $qu=mysqli_query($con,$inq);
    if($qu)
{?>
    <script>
        alert("submited successfully");
    </script>
    <script>
            window.location.href='home.php';
            </script>
<?php
}
    else{
    ?>
    <script>
        alert("Error");
    </script>
<?php } }
?>