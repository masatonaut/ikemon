<?php
$title = 'User Details';
require_once 'header.php';
require_once 'functions.php'; // This starts the session as well.

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$users = getJsonData('users.json');
$cards = getJsonData('cards.json');
$userId = $_SESSION['user_id'];

$isExist = false;
foreach ($users as $user) {
    if ($user['id'] == $userId) {
        $isExist = true;
        $userDetails = $user;
    }
}
if (!$isExist) {
    echo "User not found.";
    exit;
}

$username = $userDetails['username'];
$email = $userDetails['email'];
$balance = $userDetails['balance'];
$ownedCardIds = $userDetails['owned_cards'] ?? []; // Default to an empty array if not set

// Get the details of the user's owned cards
$ownedCards = array_intersect_key($cards, array_flip($ownedCardIds));
?>

<main>
    <section class="user-detail">
        <h2>User: <?php echo htmlspecialchars($username); ?></h2>
        <p>Email: <?php echo htmlspecialchars($email); ?></p>
        <p>Balance: $<?php echo htmlspecialchars(number_format($balance, 2)); ?></p>

        <!-- User's owned cards grid -->
        <div class="card-container">
            <?php if (empty($ownedCards)): ?>
                <p>No cards owned.</p>
            <?php else: ?>
                <?php foreach ($ownedCards as $cardId => $card): ?>
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($card['image']); ?>"
                             alt="<?php echo htmlspecialchars($card['name']); ?>" />
                        <div class="card-body">
                            <h3><?php echo htmlspecialchars($card['name']); ?></h3>
                            <p>Price: $<?php echo htmlspecialchars(number_format($card['price'], 2)); ?></p>
                            <!-- Assuming there is a mechanism to sell cards -->
                            <!-- Disable the sell button for admin -->
                            <?php if (!$_SESSION['isAdmin']): ?>
                                <form action="sell-card.php" method="POST">
                                    <input type="hidden" name="card_id" value="<?php echo htmlspecialchars($cardId); ?>">
                                    <button type="submit">Sell</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
