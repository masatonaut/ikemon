<?php
$title = 'Login';
require_once 'functions.php'; // This starts the session as well.
include 'header.php'; // Make sure header.php does not start the session again

$message = ''; // Variable to hold messages for the user

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Load users data from JSON file
    $users = getJsonData('users.json');
    
    // Search for the user by username
    $user = array_values(array_filter($users, function ($user) use ($username, $password) {
        return $user['username'] === $username && $user['password'] === $password;
    }))[0] ?? null;

    if ($user) {
        // Set the session user ID and other info to mark as logged in
        $_SESSION['user_id'] = $user['id']; // or an actual user ID if available
        $_SESSION['username'] = $user['username'];
        $_SESSION['balance'] = $user['balance'] ?? 0;
        $_SESSION['isAdmin'] = $user['isAdmin'] ?? false;

        // Redirect to home page for both admin and regular users
        header('Location: list.php');
        exit;
    } else {
        // Set an error message if the login fails
        $message = 'Invalid username or password.';
    }
}
?>

<main>
    <section class="auth-form">
        <h2>Login</h2>
        <?php if (!empty($message)): ?>
            <p class="error-message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required />

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required />

            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    </section>
</main>

<?php include 'footer.php'; ?>
