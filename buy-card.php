<?php
// Include necessary functions and start a session
require_once 'functions.php';
sessionStart();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check if the logged-in user is an admin; if so, redirect with an error message
if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) {
    $_SESSION['error_message'] = 'Admin cannot buy cards.';
    header('Location: list.php');
    exit;
}

// Initialize variables
$userId = $_SESSION['user_id'];
$cardId = $_POST['card_id'] ?? null;

// The return URL is expected to be passed in the form submission; default to 'list.php' if not set
$returnUrl = $_POST['return_url'] ?? 'list.php';

// Load users and cards data from JSON files
$users = json_decode(file_get_contents('users.json'), true);
$cards = json_decode(file_get_contents('cards.json'), true);

// Validate that the user exists in the users array
if (!isset($users[$userId])) {
    $_SESSION['error_message'] = 'User not found.';
    header('Location: ' . $returnUrl);
    exit;
}

// Check if the card exists and is not already owned by the user
if (!isset($cards[$cardId]) || is_card_owned($cardId, $users)) {
    $_SESSION['error_message'] = 'Card not found or already owned.';
    header('Location: ' . $returnUrl);
    exit;
}

// Check if the user already owns 5 cards (limit is 5)
if (count($users[$userId]['owned_cards']) >= 5) {
    $_SESSION['error_message'] = 'You can own up to 5 cards.';
    header('Location: ' . $returnUrl);
    exit;
}

// Check if the user can afford the card based on their balance
if ($users[$userId]['balance'] < $cards[$cardId]['price']) {
    $_SESSION['error_message'] = 'Insufficient balance.';
    header('Location: ' . $returnUrl);
    exit;
}

// Deduct the price of the card from the user's balance and add the card to their owned cards
$users[$userId]['balance'] -= $cards[$cardId]['price'];
$users[$userId]['owned_cards'][] = $cardId;

// Update the user's session balance
$_SESSION['balance'] = $users[$userId]['balance'];

// Save the updated user data to the 'users.json' file
file_put_contents('users.json', json_encode($users, JSON_PRETTY_PRINT));

// Redirect with a success message
$_SESSION['success_message'] = 'Card purchased successfully.';
header('Location: ' . $returnUrl);
exit;
?>
