<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = $wallet_id = $account_type = "";
$username_err = $password_err = $confirm_password_err = $wallet_id_err = "";
$account_type = 2;

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } 
    // Removed this section to allow for XSS payloads
    // elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))) {
    // $username_err = "Username can only contain letters, numbers, and underscores.";
    // } 

        else {
        // Prepare a select statement
        $sql = "SELECT id FROM userdata WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $username_err = "This username is already taken.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 4) {
        $password_err = "Password must have at least 4 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate wallet id 
    if (empty(trim($_POST["wallet_id"]))) {
        $wallet_id_err = "Please enter a wallet_id.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["wallet_id"]))) {
        $wallet_id_err = "That is not a valid wallet id!";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM userdata WHERE username = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_wallet_id);

            // Set parameters
            $param_wallet_id = trim($_POST["wallet_id"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $wallet_id_err = "This wallet id is already taken.";
                } else {
                    $wallet_id = trim($_POST["wallet_id"]);
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // The vulnerability lies here
    // This part is 100% unncessary but allows people to edit the value and have it stored
    $account_type = $_POST["account_type"];

    // Check input errors before inserting in database
    if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($wallet_id_err)) {

        // Prepare an insert statement
        $sql = "INSERT INTO userdata (username, password, walletID, accountType) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_wallet_id, $param_account_type);

            // Set parameters
            $param_username = $username;
            $param_password = $password;
            $param_wallet_id = $wallet_id;
            $param_account_type = $account_type;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                header("location: login.php");
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/header.css">
    <link rel="stylesheet" href="assets/main.scss">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</head>

<body>
    <?php include("header.php") ?>

    <div class="form">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Wallet ID</label>
                <input type="text" name="wallet_id" class="form-control <?php echo (!empty($wallet_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $wallet_id; ?>">
                <span class="invalid-feedback"><?php echo $wallet_id_err; ?></span>
            </div>
            <div class="form-group">
                <input name="account_type" type="hidden" value="<?php echo $account_type; ?>">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>
</body>

</html>