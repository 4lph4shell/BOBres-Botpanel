<?php
session_start();
ob_start();
//if (isset($_SESSION['username']) && isset($_COOKIE['cookie_username'])) {
if (isset($_SESSION['username'])) {
    header("Location: index.php");
} else {
}


include_once "includ/db.php";
include 'includ/notif.php';
include 'includ/session.php';


?>
<!DOCTYPE html>
<html :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - Bobres-4LPH4Bot</title>
    <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
            rel="stylesheet"
    />
   <link rel="stylesheet" href="assets/css/login.css"/>
    <script
            src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
            defer
    ></script>
    <script src="assets/js/init-alpine.js"></script>
</head>
<body style="background-color: #171717;">

<?php
session_notif_bobres();
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
    if (time() - $_SESSION['last_login_attempt'] < 300) {
        die('

      <div class=" h-full max-w-4xl mx-auto rounded-lg dark:bg-gray-800">
        <h1 style="text-align: center;font-size: 30px;margin-top: 50px;color: #202124">Too many failed login attempts. Please try again later ...</h1>
        <br>
        <svg id="Layer_1" class="mx-auto" height="40" fill="#5a189a" viewBox="0 0 24 24" width="40" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m9.856 20.743a9 9 0 1 1 11.144-8.743 1.5 1.5 0 0 0 3 0 12 12 0 1 0 -14.856 11.657 1.464 1.464 0 0 0 .356.043 1.5 1.5 0 0 0 .355-2.957z"/><path d="m23.621 15.939a1.5 1.5 0 0 0 -2.121 0l-4.785 4.782-2.133-2.26a1.5 1.5 0 0 0 -2.121-.043 1.5 1.5 0 0 0 -.042 2.121l2.581 2.721a2.362 2.362 0 0 0 1.674.74h.037a2.368 2.368 0 0 0 1.661-.688l5.254-5.252a1.5 1.5 0 0 0 -.005-2.121z"/><path d="m10.5 7.5v3.555l-2.4 1.5a1.5 1.5 0 0 0 -.475 2.068 1.5 1.5 0 0 0 2.068.475l2.869-1.8a2 2 0 0 0 .938-1.7v-4.098a1.5 1.5 0 0 0 -3 0z"/></svg>
    </div>

');
    }
    unset($_SESSION['login_attempts']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $usernames = input($_POST["username"]);
    $passwords = input($_POST["password"]);
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? AND password=?");
    $stmt->bind_param("ss", $usernames, $passwords);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    if (!$result) {
        die('خطا به وجود اومد' . mysqli_error($conn));
    } else {
    }

    if ($result->num_rows == 1 && $row['username'] == $usernames) {
        $_SESSION['username'] = $row['username'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        welcombobres();
        header('Location: index.php');
    } else {
        // Login failed
        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 1;
        } else {
            $_SESSION['login_attempts']++;
        }
        $_SESSION['last_login_attempt'] = time();
        die('

      <div class=" h-full max-w-4xl mx-auto rounded-lg dark:bg-gray-800">
        <h1 style="text-align: center;font-size: 30px;margin-top: 50px;color: #202124">Invalid username or password</h1>
        <br>
        <svg class="mx-auto" fill="#c9184a" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="40" height="40"><path d="M12,24A12,12,0,1,1,24,12,12.013,12.013,0,0,1,12,24ZM12,2A10,10,0,1,0,22,12,10.011,10.011,0,0,0,12,2Zm5.746,15.667a1,1,0,0,0-.08-1.413A9.454,9.454,0,0,0,12,14a9.454,9.454,0,0,0-5.666,2.254,1,1,0,0,0,1.33,1.494A7.508,7.508,0,0,1,12,16a7.51,7.51,0,0,1,4.336,1.748,1,1,0,0,0,1.41-.081ZM6,10c0,1,.895,1,2,1s2,0,2-1a2,2,0,0,0-4,0Zm8,0c0,1,.895,1,2,1s2,0,2-1a2,2,0,0,0-4,0Z"/></svg>
    </div>

');
    }
    $stmt->close();
//    }
}
function input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>

<div class="container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-content">
                <div class="lottie-container" id="lottie-animation"></div>
                <h1 class="title">Wellcome Bobres Bot</h1>
                <label class="block text-sm">
                    <span>Username</span>
                    <input type="text" name="username" id="username" required>
                </label>
                <label class="block text-sm">
                    <span>Password</span>
                    <input type="password" name="password" id="password" required>
                </label>
                <input type="hidden" id="inputcheckbox" value="1" name="check_login" 
                    <?php if (isset($_COOKIE['cookie_username']) && isset($_COOKIE['cookie_password'])) echo 'checked'; ?>>
                <button type="submit" name="submit">Login</button>
                <footer class="footer">
                    
                     Bobres version 1.7.4.7 alpha 🐺
                </footer>
            </div>
        </form>
    </div>
      <!-- Lottie Web Library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lottie-web/5.7.4/lottie.min.js"></script>

<script>
  // Initialize Lottie animation
  lottie.loadAnimation({
    container: document.getElementById('lottie-animation'), // ID of the element
    renderer: 'svg',
    loop: true,
    autoplay: true,
    path: 'assets/lotti/Animation - 1731674159439.json' // Path to the Lottie JSON file
  });
</script>
<script src="assets/bobres.js"></script>
</body>
</html>
