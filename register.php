<?php
require 'functions.php';  // Assuming this file contains sessionStart() and getJsonData()

$title = 'Register';
sessionStart(); // Start the session using your custom function or session_start()

// Initialize an error message array if not already set
if (!isset($_SESSION['error_messages'])) {
    $_SESSION['error_messages'] = [];
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    // Validate inputs
    if ($username === '') {
        $_SESSION['error_messages'][] = 'Username cannot be blank.';
    }
    if ($email === '') {
        $_SESSION['error_messages'][] = 'Email cannot be blank.';
    }
    if ($password === '') {
        $_SESSION['error_messages'][] = 'Password cannot be blank.';
    }
    if ($confirmPassword === '') {
        $_SESSION['error_messages'][] = 'Confirm password cannot be blank.';
    }
    if ($password !== $confirmPassword) {
        $_SESSION['error_messages'][] = 'Passwords do not match.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_messages'][] = 'Invalid email format.';
    }

    // Load users data from JSON file if there are no initial errors
    if (empty($_SESSION['error_messages'])) {
        $users = getJsonData('users.json');

        // Check if the username or email already exists
        foreach ($users as $user) {
            if ($user['username'] === $username || $user['email'] === $email) {
                $_SESSION['error_messages'][] = 'Username or email already exists.';
                break;
            }
        }

        if (empty($_SESSION['error_messages'])) {
            // Create a new user array without hashing the password
            $newUser = [
                "username" => $username,
                "email" => $email,
                "password" => $password, // Store the plaintext password
                "balance" => 10000, // Default balance
                "owned_cards" => [] // Default empty array for owned cards
            ];

            // Generate a unique ID for the new user
            $newUserId = uniqid('user_', true);
            $newUser['id'] = $newUserId;

            // Add the new user to the users array
            $users[$newUserId] = $newUser;

            // Save the updated users array back to the JSON file
            if (saveJsonData('users.json', $users)) {
                // Set a success message
                $_SESSION['success_message'] = 'Registration successful.';
                // Redirect to the login page
                header('Location: login.php');
                exit;
            } else {
                // Set an error message if the save operation fails
                $_SESSION['error_messages'][] = 'There was a problem with the registration.';
            }
        }
    }
}

include 'header.php'; // Include the header after session start
?>

<main>
    <section class="auth-form">
        <h2>Register</h2>
        <?php
        // Check if there are error messages to display
        if (!empty($_SESSION['error_messages'])):
            echo '<div class="error-messages">';
            foreach ($_SESSION['error_messages'] as $error_message) {
                echo "<p class='error-message'>$error_message</p>";
            }
            echo '</div>';
            // Clear error messages after displaying them
            $_SESSION['error_messages'] = [];
        endif;
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required />

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required />

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required />

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required />

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </section>
</main>

<?php include 'footer.php'; ?>
