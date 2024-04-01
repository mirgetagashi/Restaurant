<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/main.css">
    <script src="https://kit.fontawesome.com/1506ca5daa.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <style>
        .nav-item:nth-child(6) .nav-link{
                color: white;
        }
        .nav-item:nth-child(7) .nav-link{
                color: var(--gold-yellow);
        }
    </style>
</head>
<body>

<?php

session_start();

if($_SESSION['counter']){

    $_SESSION['counter']++;
 
 }else{
 
    $_SESSION['counter'] = 1;
 
 }
$pageVisitTime= $_SESSION['counter'];


$name_pattern="/^([a-zA-Z]){2,30}$/";
$surname_pattern="/^([a-zA-Z]){2,30}$/";
$email_pattern="/^[^ ]+@[^ ]+\.[a-z]{2,3}$/";
//$mobileno_pattern="/^\+?\(?383\)?[-.\s]?\d{2}[-.\s]?\d{3}[-.\s]?\d{3}?/";
$mobileno_pattern="/^\d{8,}$/";
$pass_pattern="/^.{8,}$/";
$name_error = $surname_error = $email_error = $number_error = $password1_error = $password2_error = "";

$name=$surname=$email=$number=$password1=$password2="";
$passErr="";
$namevalid=$surnamevalid=$emailvalid=$numbervalid=$pass1valid=$pass2valid=false;

function input_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty($_POST["name"])){
        $name_error = "border-bottom: 1px solid red;";
    } else {
        $name=input_data($_POST["name"]);
        if(!preg_match($name_pattern,$name)){
            $name_error = "border-bottom: 1px solid red;";
        }else{
            $namevalid=true;
        }
    }

    if(empty($_POST["surname"])){
        $surname_error = "border-bottom: 1px solid red;";
    } else {
        $surname=input_data($_POST["surname"]);
        if(!preg_match($surname_pattern,$surname)){
            $surname_error = "border-bottom: 1px solid red;";
        }else{
            $surnamevalid=true;
        }
    }

    if(empty($_POST["email"])){
        $email_error = "border-bottom: 1px solid red;";
    } else {
        $email=input_data($_POST["email"]);
        if(!preg_match($email_pattern,$email)){
            $email_error = "border-bottom: 1px solid red;";
        }else{
            $emailvalid=true;
            $_SESSION['email']=$email;

        }
    }

    if (empty($_POST["number"])) {
        $number_error = "border-bottom: 1px solid red;";
    } else {
        $number = input_data($_POST["number"]);
        if (!preg_match($mobileno_pattern, $number)) {
            $number_error = "border-bottom: 1px solid red;";
        }else{
            $numbervalid=true;
        }
    }

    if(empty($_POST["password1"])){
        $password1_error = "border-bottom: 1px solid red;";
    } else {
        $password1=input_data($_POST["password1"]);
        if(!preg_match($pass_pattern,$password1)){
            $password1_error = "border-bottom: 1px solid red;";
        }else{
            $pass1valid=true;
        }
    }

    if(empty($_POST["password2"])){
        $password2_error = "border-bottom: 1px solid red;";
    } else {
        $password2=input_data($_POST["password2"]);
        if(!preg_match($pass_pattern,$password2)){
            $password2_error = "border-bottom: 1px solid red;";
        } elseif ($_POST["password1"] !== $_POST["password2"]) {
            $password2_error = "border-bottom: 1px solid red;";
            $passErr="Password should be the same";
        }else{
                $pass2valid=true;
            $_SESSION['password2']=$password2;

        }
    }

    if ($namevalid && $surnamevalid && $emailvalid && $numbervalid && $pass1valid && $pass2valid) {
        // Vendosni emrin e faqes së loginit në këtë variabël
        $login_page = "login.php";
        // Kalimi në faqen e loginit
        header("Location: $login_page");
        exit();
    }
}

?>
    <header class="header">
        <nav class="nav row" id="nav">
            <h1 class="header-title">LaTulipe</h1>

            <a href="javascript:void(0);" onclick="displayMenu()" class="menu-mobile" id="menu-mobile">
                <i class="fa-solid fa-bars" ></i>
            </a>
            <ul class="nav-list row" id="nav-list">
                <li class="nav-item"><a href="javascript:void(0);" onclick="removeMenu()" class="nav-link"><i class="fa-solid fa-xmark"></i></a></li>
                <li class="nav-item"><a href="index.html" class="nav-link">Home</a></li>
                <li class="nav-item"><a href="menu.html" class="nav-link">Menu</a></li>
                <li class="nav-item"><a href="contact.html" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="delivery.html" class="nav-link">Delivery</a></li>
                <li class="nav-item"><a href="login.php" class="nav-link">Log In</a></li>
                <li class="nav-item"><a href="register.php" class="nav-link">Register</a></li>
                <li class="nav-item"><a href="booknow.php" class="nav-link btn">Book now</a></li>
            </ul>
        </nav>

        <div class="login-content">
            <div class="login-container">

                <div class="login-details">
                    <h1>Register</h1>

                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" > 
                    <input type="name" name="name" id="name" placeholder="Name" style="<?php echo $name_error; ?>">
                    <input type="surname" name="surname" id="surname" placeholder="Surname"  style="<?php echo $surname_error; ?>">
                    <input type="number" name="number" id="number" placeholder="Phone number"  style="<?php echo $number_error; ?>">
                    <input type="email" name="email" id="email" placeholder="Email"  style="<?php echo $email_error; ?>">
                    <input type="password" name="password1" id="password1" placeholder="Password"  style="<?php echo $password1_error; ?>">
                    <input type="password" name="password2" id="password2" placeholder="Confirm Password"  style="<?php echo $password2_error; ?>">

                        <input type="submit" id="sign" name="sign" class="btn" value="Sign up">
                        <!--<button type="submit" id="sign" name="sign" class="btn">Sign Up</button>-->
                        <p id="errorMsg" name="errorMsg"><?php echo $passErr; ?></p> 
                    </form>

                </div>
                <div class="login-photo">
                    
                </div>
            </div>
        </div>
    </header>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-col">
                    <p>40 Park Ave, Brooklyn, New York</p>
                    <p>1-800-111-2222</p>
                    <p>contact@example.com</p>
                    <div class="footer-socials">
                        <a href="#"><i class="fa-brands fa-facebook"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-content">
                <h1>LaTulipe</h1>
            </div>
            <div class="footer-content">
                <p>Monday - Friday: 9AM - 9PM</p>
                <p>Saturday: 9AM - 11PM</p>
                <p>Sunday: 9AM - 11PM</p>
                <p>Happy Hours: 9AM - 12AM</p>
            </div>
        </div>

    </footer>

    <script src="javascript/index.js"></script>
    
</body>


</html>