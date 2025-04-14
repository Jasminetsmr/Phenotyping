<!DOCTYPE html>
<html>

<head>
    <?php include_once "../../_db/db_conn.php"; ?>
    <?php
            // Initialize the session
            session_start();
            
            // Check if the user is already logged in, if yes then redirect him to welcome page
            if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
                header("location: ../../pg/dashboard_main/");
                exit;
            }
            
            // Define variables and initialize with empty values
            $username = $password = "";
            $username_err = $password_err = $login_err = "";
            
            // Processing form data when form is submitted
            if($_SERVER["REQUEST_METHOD"] == "POST"){
            
                // Check if username is empty
                if(empty(trim($_POST["username"]))){
                    $username_err = "Please enter username.";
                } else{
                    $username = trim($_POST["username"]);
                }
                
                // Check if password is empty
                if(empty(trim($_POST["password"]))){
                    $password_err = "Please enter your password.";
                } else{
                    $password = trim($_POST["password"]);
                }
                
                // Validate credentials
                if(empty($username_err) && empty($password_err)){
                    // Prepare a select statement
                    $sql = "SELECT id_user, username, password, level_user FROM users WHERE username = ?";
                    
                    if($stmt = $mysqli->prepare($sql)){
                        // Bind variables to the prepared statement as parameters
                        $stmt->bind_param("s", $param_username);
                        
                        // Set parameters
                        $param_username = $username;
                        
                        // Attempt to execute the prepared statement
                        if($stmt->execute()){
                            // Store result
                            $stmt->store_result();
                            
                            // Check if username exists, if yes then verify password
                            if($stmt->num_rows == 1){                    
                                // Bind result variables
                                $stmt->bind_result($id, $username, $hashed_password, $level_user);
                                if($stmt->fetch()){
                                    if(password_verify($password, $hashed_password)){
                                        // Password is correct, so start a new session
                                        session_start();
                                        
                                        // Store data in session variables
                                        $_SESSION["loggedin"] = true;
                                        $_SESSION["id_user"] = $id;
                                        $_SESSION["username"] = $username;
                                        $_SESSION["level_user"] = $level_user;                            
                                        
                                        // Redirect user to welcome page
                                        header("location: ../../pg/dashboard_main/");
                                    } else{
                                        // Password is not valid, display a generic error message
                                        $login_err = "Invalid username or password.";
                                    }
                                }
                            } else{
                                // Username doesn't exist, display a generic error message
                                $login_err = "Invalid username or password.";
                            }
                        } else{
                            echo "Oops! Something went wrong. Please try again later.";
                        }

                        // Close statement
                        $stmt->close();
                    }
                }
                
                // Close connection
                $mysqli->close();
            }
            ?>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login System</title>

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../dist/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="../../dist/css/ionicons.min.css">
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css">
</head>
<body class="hold-transition login-page">
  <?php include_once("main_header.php") ?>
  <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
  <script src="../../bootstrap/js/bootstrap.min.js"></script>
  <script src="../../plugins/iCheck/icheck.min.js"></script>
  <script src="script.js"></script>
</body>
</html>