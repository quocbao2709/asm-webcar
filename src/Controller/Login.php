<?php
session_start();
$user="";
$pass="";
if(isset($_POST["Login"]))
{
    iF(!empty ($_POST["user"]))
    {
        $user=$_POST["user"];
    }
    iF(!empty ($_POST["pass"]))
    {
        $pass=$_POST["pass"];
    }
    if($user!=""&&$pass!="")
    {        require_once 'ketnoi.php';
        $sql="Select * from taikhoan where username='$user' and password='$pass'";
        $kq=mysqli_query($conn,$sql);
        if($kq && $kq->num_rows >0){
          $row= $kq->fetch_assoc();

		  $_SESSION["username"]=$row['username'];
            header ("Location: http://localhost/asm-webcar/"); 
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>thêm thong tin tai khoan</title>
                <style>
#warning-container{
                    display: none;
                }
input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}

div {
    border-radius: 5px;
    background-color: #f2f2f2;
    padding: 40px;
}
body{
    width: 100%;
}
form{
    width :30%;
     margin: 0 auto;
    display: block;
}
</style>
    </head>
    <body>
        <form action="Login.php" method="POST" >
         <table>
            <caption><b>Đăng nhập Thong tin tai khoan</b></caption>      
            <tbody>
                
                <tr>
                    <td><p>name</p></td>
                    <td><input name="user" type="text"  value=""></td>
                </tr>
                 <tr>
                    <td><p>password</p></td>
                    <td><input name="pass" type="text" value=""></td>
                </tr>                 
                 <tr>                   
                    <td><input name="btndangnhap" type="submit" value="Đăng nhập"></td>
                </tr>
            </tbody>
         </table>
        </form>
    </body>
</html>