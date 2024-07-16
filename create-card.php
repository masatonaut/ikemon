<?php
// Include necessary functions and start a session
require_once 'functions.php';
sessionStart();

// Redirect if not logged in or if not an admin user
if (!isset($_SESSION['user_id']) || !isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header('Location: login.php');
    exit;
}

// Include the header template
include 'header.php';

// Initialize the error message array if not already set
if (!isset($_SESSION['error_messages'])) {
    $_SESSION['error_messages'] = [];
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form input data
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $hp = trim($_POST['hp'] ?? '');
    $attack = trim($_POST['attack'] ?? '');
    $defense = trim($_POST['defense'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');

    // Check for negative values
    if ($hp < 0 || $attack < 0 || $defense < 0 || $price < 0) {
        $_SESSION['error_messages'][] = 'HP, Attack, Defense, and Price must be positive numbers.';
    }

    // Basic validation
    if (empty($name) || empty($type) || empty($image) || !empty($_SESSION['error_messages'])) {
        $_SESSION['error_messages'][] = 'Please fill in all required fields correctly.';
    } else {
        // Load existing cards
        $cards = getJsonData('cards.json');

        // Check if the card name already exists (case-insensitive)
        if (isset($cards[strtolower($name)])) {
            $_SESSION['error_messages'][] = 'A card with this name already exists.';
        } else {
            // Add new card
            $cards[strtolower($name)] = [
                'name' => $name,
                'type' => $type,
                'hp' => (int) $hp,
                'attack' => (int) $attack,
                'defense' => (int) $defense,
                'price' => (float) $price,
                'description' => $description,
                'image' => $image
            ];

            // Save the updated cards data
            if (saveJsonData('cards.json', $cards)) {
                $_SESSION['success_message'] = 'Card created successfully.';
                header('Location: create-card.php');
                exit;
            } else {
                $_SESSION['error_messages'][] = 'Error saving the card.';
            }
        }
    }
}
?>

<h1>Create New Card</h1>

<?php
// Check if there are error messages to display
if (!empty($_SESSION['error_messages'])):
    foreach ($_SESSION['error_messages'] as $error_message) {
        echo "<p class='error-message'>$error_message</p>";
    }
    // Clear error messages after displaying them
    $_SESSION['error_messages'] = [];
endif;

if (!empty($_SESSION['success_message'])):
    echo "<p class='success-message'>" . $_SESSION['success_message'] . "</p>";
    // Clear success message after displaying it
    unset($_SESSION['success_message']);
endif;
?>

<form action="create-card.php" method="post">
    Name: <input type="text" name="name" required><br>
    Type: <input type="text" name="type" required><br>
    HP: <input type="number" name="hp" required><br>
    Attack: <input type="number" name="attack" required><br>
    Defense: <input type="number" name="defense" required><br>
    Price: <input type="number" name="price" required><br>
    Description: <textarea name="description" required></textarea><br>
    Image URL: <input type="text" name="image" required><br>
    <button type="submit">Create Card</button>
</form>

<?php include 'footer.php'; // Include the footer ?>
