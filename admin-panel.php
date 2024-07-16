<?php
// Include necessary functions and start a session
require_once 'functions.php';
sessionStart();

// Check if the user is logged in and is an admin; if not, redirect to the login page
if (!isset($_SESSION['user_id']) || !isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: login.php');
    exit;
}

// Check if the HTTP request method is POST and if the "release_all_cards" form field is set
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['release_all_cards'])) {
    // Retrieve user data from a JSON file
    $users = getJsonData('users.json');

    // Call the "releaseAllCardsFromAdmin" function to release all cards owned by the admin
    if (releaseAllCardsFromAdmin($users)) {
        // If the card release is successful, save the updated user data to the JSON file
        if (saveJsonData('users.json', $users)) {
            $_SESSION['success_message'] = "All cards released successfully.";
        } else {
            $_SESSION['error_message'] = 'Error updating admin data.';
        }
    } else {
        $_SESSION['error_message'] = "Admin does not own any cards to release.";
    }
    // Redirect back to the admin panel page
    header('Location: admin-panel.php');
    exit;
}

// Set the title for the admin panel page
$title = 'Admin Panel';

// Include the header content
include 'header.php';
?>

<main>
    <h1>Admin Panel</h1>
    <?php if (!empty($_SESSION['success_message'])): ?>
        <p class="success"><?php echo $_SESSION['success_message']; ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php elseif (!empty($_SESSION['error_message'])): ?>
        <p class="error"><?php echo $_SESSION['error_message']; ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
    <div>
        <a href="create-card.php">Create New Card</a>
    </div>
    <!-- Create a form to release all cards -->
    <form method="POST" action="admin-panel.php">
        <input type="submit" name="release_all_cards" value="Release All Cards">
    </form>
</main>

<?php
// Include the footer content
include 'footer.php';
?>
