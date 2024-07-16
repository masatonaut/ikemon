<?php
require 'functions.php';
sessionStart();

if (!isset($_SESSION['user_id']) || !isset($_POST['card_id'])) {
    $_SESSION['error_message'] = 'Sell action failed. Missing user session or card ID.';
    header('Location: user-detail.php');
    exit;
}

$userId = $_SESSION['user_id'];
$cardId = $_POST['card_id'];
$users = getJsonData('users.json');
$cards = getJsonData('cards.json');

if (!in_array($cardId, $users[$userId]['owned_cards'])) {
    $_SESSION['error_message'] = 'Sell action failed. Card not found in the user\'s collection.';
    header('Location: user-detail.php');
    exit;
}

$sellPrice = round($cards[$cardId]['price'] * 0.9); // 90% of the card's price, rounded
$users[$userId]['balance'] += $sellPrice; // Update the user's balance
$_SESSION['balance'] = $users[$userId]['balance']; // Update session balance for header display

$users[$userId]['owned_cards'] = array_diff($users[$userId]['owned_cards'], [$cardId]); // Remove the card from the user's collection
addToAdminDeck($cardId, $users);

// Save the updated data back to the users.json and cards.json files
if (saveJsonData('users.json', $users) && saveJsonData('cards.json', $cards)) {
    $_SESSION['success_message'] = "Card sold for \${$sellPrice}.";
    header('Location: user-detail.php');
    exit;
} else {
    $_SESSION['error_message'] = 'Error updating user data.';
    header('Location: user-detail.php');
    exit;
}
?>
