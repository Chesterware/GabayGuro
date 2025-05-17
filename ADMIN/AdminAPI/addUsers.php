<?php
require_once __DIR__ . '/../../api/db_connection.php'; 

$errorMessage = '';

function openConsoleWithMessage($message) {
    return "<script type='text/javascript'>
                console.log('Error: ' + '{$message}');
                alert('Error: ' + '{$message}');
            </script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['firstName'],
              $_POST['middleInitial'],
              $_POST['lastName'],
              $_POST['email'],
              $_POST['password'],
              $_POST['confirmPassword'])) {

        $firstname = $_POST['firstName'];
        $middleinitial = $_POST['middleInitial'];
        $lastname = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];

        if ($password !== $confirmPassword) {
            echo openConsoleWithMessage('Password do not match.');
            header("Location: ../manageUser.php");
            exit();
        }
        elseif (strlen($password) < 8 || !preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password) || !preg_match("/[0-9]/", $password)) {
            echo openConsoleWithMessage('Password must be at least 8 characters long, include uppercase, lowercase, and a number.');
            header("Location: ../manageUser.php");
            exit(); 
        }

        if (empty($_SESSION['errorMessage'])) {
            $checkSql = "SELECT admin_id FROM admin ORDER BY admin_id DESC LIMIT 1";
            $stmt = $conn->prepare($checkSql);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($maxAdminId);
                $stmt->fetch();
                $adminId = $maxAdminId + 1;
            } else {
                $adminId = 1; 
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $checkEmailSql = "SELECT * FROM admin WHERE email = ?";
            $stmt = $conn->prepare($checkEmailSql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo openConsoleWithMessage('Email already exists! Use another email.');
                header("Location: ../manageUser.php");
                exit(); 
            } else {
                $sql = "INSERT INTO admin (admin_id, first_name, middle_initial, last_name, email, password) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isssss", $adminId, $firstname, $middleinitial, $lastname, $email, $hashedPassword);
                
                if ($stmt->execute()) {
                
                    if (!isset($_SESSION['admin_id'])) {
                        $_SESSION['admin_id'] = $adminId;
                        $_SESSION['first_name'] = $firstname;
                        $_SESSION['middle_initial'] = $middleinitial;
                        $_SESSION['last_name'] = $lastname;
                        $_SESSION['email'] = $email;
                    }

                
                    echo "<script type='text/javascript'>
                            alert('You have successfully registered a new Administrator!');
                        </script>";

                    
                    header("Location: ../manageUser.php");
                    exit();
                } else {
                    $_SESSION['errorMessage'] = "Error while registering user: " . $stmt->error;
                    echo openConsoleWithMessage("Error while registering user: " . $stmt->error);
                    header("Location: ../manageUser.php");
                    exit(); 
                }
            }
        }
    } else {
        $_SESSION['errorMessage'] = "All fields are required.";
        echo openConsoleWithMessage("All fields are required.");
        header("Location: ../manageUser.php");
        exit(); 
    }
}
?>
