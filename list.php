<?php
$title = 'Main Page';
include 'header.php';
require_once 'functions.php'; // This starts the session as well.

$users = getJsonData('users.json');

// Load Pokémon data from JSON file
$pokemon_data = json_decode(file_get_contents('cards.json'), true);
$typeFilter = $_GET['type'] ?? 'all'; // Get the filter type from the query string, default to 'all'

// Filter the $pokemon_data array if a type filter is set and not 'all'
if ($typeFilter !== 'all') {
    $pokemon_data = array_filter($pokemon_data, function ($card) use ($typeFilter) {
        return strtolower($card['type']) === strtolower($typeFilter);
    });
}

foreach ($pokemon_data as $id => $card) {
    $pokemon_data[$id]['is_owned'] = is_card_owned($id, $users);
}
?>

<main>
    <!-- Display success or error messages -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success"><?php echo $_SESSION['success_message']; ?></p>
        <?php unset($_SESSION['success_message']); ?>
    <?php elseif (isset($_SESSION['error_message'])): ?>
        <p class="error"><?php echo $_SESSION['error_message']; ?></p>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <!-- Add form for filtering by type -->
    <form action="list.php" method="GET">
        <label for="type-filter">Filter by type:</label>
        <select id="type-filter" name="type">
            <option value="all">All Types</option>
            <option value="electric">Electric</option>
            <option value="fire">Fire</option>
            <option value="grass">Grass</option>
            <option value="water">Water</option>
            <option value="bug">Bug</option>
            <option value="normal">Normal</option>
            <option value="poison">Poison</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <section class="card-container">
        <?php foreach ($pokemon_data as $id => $pokemon): ?>
            <div class="card">
                <img src="<?php echo htmlspecialchars($pokemon['image']); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?>">
                <div class="card-body">
                    <h2><?php echo htmlspecialchars($pokemon['name']); ?></h2>
                    <p class="type"><?php echo htmlspecialchars($pokemon['type']); ?></p>
                    <p class="stats">HP: <?php echo htmlspecialchars($pokemon['hp']); ?> | Attack: <?php echo htmlspecialchars($pokemon['attack']); ?> | Defense: <?php echo htmlspecialchars($pokemon['defense']); ?></p>
                    <p class="price">Price: <?php echo htmlspecialchars($pokemon['price']); ?></p>
                    <a href="card-detail.php?card=<?php echo urlencode($id); ?>">Details</a>
                    <?php if (isset($_SESSION['user_id']) && !$pokemon['is_owned']): ?>
                        <!-- Buy button only for logged-in users -->
                        <form action="buy-card.php" method="POST">
                            <input type="hidden" name="card_id" value="<?php echo htmlspecialchars($id); ?>">
                            <input type="hidden" name="return_url" value="list.php">
                            <button type="submit">Buy</button>
                        </form>
                    <?php elseif($pokemon['is_owned']): ?>
                        <p class="sold">Sold</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </section>
</main>

<?php include 'footer.php'; ?>
