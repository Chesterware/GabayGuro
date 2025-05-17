    <?php
    require_once 'db_connection.php';

    // Check if admin ID is set in session
    if (!isset($_SESSION['admin_id'])) {
        header("Location: login.php");
        exit();
    }

    $adminId = $_SESSION['admin_id'];

    // Fetch the current password if no new password is provided
    $query = "SELECT password FROM admin WHERE admin_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $adminId);
    $stmt->execute();
    $stmt->bind_result($existing_password);
    $stmt->fetch();
    $stmt->close();

    // Handle form submission when the user updates the profile (POST request)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get the form data
        $first_name = $_POST['first_name'];
        $middle_initial = $_POST['middle_initial'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email']; // Ensure this is passed correctly
        $password = $_POST['password'];

        // If password is updated, hash it, otherwise keep the existing password
        if (!empty($password)) {
            $password = password_hash($password, PASSWORD_BCRYPT);
        } else {
            $password = $existing_password;
        }

        // Update the profile in the database
        $updateQuery = "UPDATE admin SET first_name = ?, middle_initial = ?, last_name = ?, email = ?, password = ? WHERE admin_id = ?";

        if ($stmt = $conn->prepare($updateQuery)) {
            $stmt->bind_param('sssssi', $first_name, $middle_initial, $last_name, $email, $password, $adminId);

            if ($stmt->execute()) {
                // Success - send a JSON response indicating the profile was updated successfully
                echo json_encode(["success" => true, "message" => "Profile updated successfully!"]);
            } else {
                // Failure - log the error and send a failure response
                error_log("Error executing query: " . $stmt->error);  // Log the error
                echo json_encode(["success" => false, "message" => "Error updating profile."]);
            }
        } else {
            // Failure - log the error and send a failure response
            error_log("Error preparing query: " . $conn->error);  // Log the error
            echo json_encode(["success" => false, "message" => "Error preparing the SQL query."]);
        }
    }

    ?>
