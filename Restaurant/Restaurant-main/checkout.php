
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/contact.css">
    <script src="https://kit.fontawesome.com/1506ca5daa.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <title>Checkout</title>
    <style>
        .nav-item:nth-child(4) .nav-link{
            color: white;
        }
        .contact-form {
            padding: 3em;
            background-color: var(--main-dark);
        }
        .hidden {
            display: none;
}

#successMessage p {
        margin: 0; /* Reset margin for paragraph inside success message */
        background-color: rgba(0, 0, 0, 0.6);
    text-align: center;
    height: 90vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    font-family: 'Crimson Text', serif;;
    font-size: 50px;
    }


    </style>
</head>
<body>

<?php
session_start();

require_once 'error_handler.php';
require_once 'db_connect.php'; // lidhja me db

if (!isset($_COOKIE['cart'])) {
    header('Location: delivery.php');
    exit();
}

$cart = json_decode($_COOKIE['cart'], true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // i merr te dhenat e userit nga login session!1
    $userId = $_SESSION['user_id'];
    $address = $_POST['address'];
    $orderDate = date('Y-m-d H:i:s');

    if (empty($address)) {
        $error_message = "Please provide a delivery address.";
    } else {
        foreach ($cart as $item) {
            $itemName = $item['name'];
            $itemPrice = $item['price'];
            $quantity = $item['quantity'];
            $totalAmount = $itemPrice * $quantity;
            // insertimi i te dhenave te porosise ne db
            $stmt = $conn->prepare("INSERT INTO orders (user_id, order_date, item_name, item_price, quantity, total_amount, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issdiis", $userId, $orderDate, $itemName, $itemPrice, $quantity, $totalAmount, $address);

            // shfaqja e nje errori nese porosia deshton!
            if (!$stmt->execute()) {
                trigger_error("Order could not be saved: " . $stmt->error, E_USER_ERROR);
            }

            $stmt->close();
        }

        setcookie('cart', '', time() - 3600, '/');
        header('Location: index.php');
        exit();
    }
}


if(isset($_SESSION['registration_data'])) {
    $registration_data = $_SESSION['registration_data'];

    $name_value = isset($registration_data['name']) ? $registration_data['name'] : '';
    $email_value = isset($registration_data['email']) ? $registration_data['email'] : '';
    $number_value = isset($registration_data['number']) ? $registration_data['number'] : '';
} else {
    $name_value = '';
    $email_value = '';
    $number_value = '';
}

$name_pattern = "/^[a-zA-Z]+(?:\s+[a-zA-Z]+)*$/";
$email_pattern="/^[^ ]+@[^ ]+\.[a-z]{2,3}$/";
$mobileno_pattern="/^\d{8,}$/";
$address_pattern="/^[a-zA-Z0-9\s]{1,50}$/";
$name_error =$email_error = $number_error= $address_error = "";

$name=$email=$number=$address="";
$namevalid=$emailvalid=$numbervalid=$addressvalid=false;

function capitalizeFirstLetter($string) {
    return preg_replace_callback(
        '/\b\w/i', 
        function($matches) {
            return strtoupper($matches[0]); // Bën shkronjën e parë të madhe
        },
        $string
    );
}

function input_data($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(empty($_POST["namee"])){
        $name_error = "border-bottom: 1px solid red;";
    } else {
        $name = capitalizeFirstLetter(input_data($_POST["namee"]));
        if(!preg_match($name_pattern,$name)){
            $name_error = "border-bottom: 1px solid red;";
        }else{
            $namevalid=true;
        }
    }

    if(empty($_POST["address"])){
        $address_error = "border-bottom: 1px solid red;";
    } else {
        $address=input_data($_POST["address"]);
        if(!preg_match($address_pattern,$address)){
            $address_error = "border-bottom: 1px solid red;";
        }else{
            $addressvalid=true;
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
}
?>

<?php include 'header.php'; ?>  


<aside  class="contact-form">
<?php if (isset($error_message)) { echo "<p class='error'>$error_message</p>"; } ?>
    <form method="POST" action="checkout.php">
        <label for="address">Delivery Address:</label>
        <input type="text" id="address" name="address" required>
        <button type="submit" class="btn">Confirm Order</button>
    </form>
            </aside>
        </div>
        <div id="successMessage" class="hidden">
            <p> <?php echo "Dear ".$name.",";?> Thank you for your order!</p>

        </div>

  

        <section class="map">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d11736.673577176116!2d21.15335725!3d42.6577868!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1354f2cbab67d493%3A0x5c97e5834932545a!2sNEWBORN!5e0!3m2!1sen!2s!4v1674140564590!5m2!1sen!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </section>

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
        <script>
    window.onload = function() {
        var success = "<?php echo  $namevalid && $addressvalid && $emailvalid && $numbervalid  ? 'true' : 'false'; ?>";
        
        if (success === "true") {
            var headerContent = document.querySelector(".headerContent");
            var successMessage = document.getElementById("successMessage");

            // dimensions of success message
            successMessage.style.width = headerContent.offsetWidth + "px";
            successMessage.style.height = headerContent.offsetHeight + "px";

            headerContent.style.display = "none"; // Hide the header content
            successMessage.classList.remove("hidden"); // Display the success message
        }
    };
</script>

    

</body>
</html>