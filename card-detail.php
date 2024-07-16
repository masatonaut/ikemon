<?php
// Include necessary functions and start a session
require_once 'functions.php';

// Set the title for this page
$title = 'Card Detail';

// Include the header template
include 'header.php';

// Define colors for different types of Pokémon
$type_colors = [
    'electric' => '#FFEA70', // Yellow
    'fire' => '#F08030',     // Red
    'grass' => '#78C850',    // Green
    'water' => '#6890F0',    // Blue
    'bug' => '#A8B820',      // Olive
    'normal' => '#A8A878',   // Khaki
    'poison' => '#A040A0',   // Purple
];

// Load Pokémon data from 'cards.json'
$pokemon_data = json_decode(file_get_contents('cards.json'), true);

// Get the Pokémon ID from the URL parameter (default to 'pikachu' if not set)
$pokemon_id = strtolower($_GET['card'] ?? 'pikachu');

// Get the Pokémon data based on the ID or default to the first Pokémon if not found
$pokemon = $pokemon_data[$pokemon_id] ?? reset($pokemon_data);

// Get the type of the Pokémon and convert it to lowercase
$type = strtolower($pokemon['type']);

// Get the background color based on the Pokémon's type or default to a light gray
$background_color = $type_colors[$type] ?? '#F8F8F8';
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
    
    <section class="card-detail" style="background-color: <?php echo htmlspecialchars($background_color); ?>;">
        <img src="<?php echo htmlspecialchars($pokemon['image']); ?>" alt="<?php echo htmlspecialchars($pokemon['name']); ?>">
        <div class="card-info" style="background-color: <?php echo htmlspecialchars($background_color); ?>;">
            <h2><?php echo htmlspecialchars($pokemon['name']); ?></h2>
            <p class="type">Type: <?php echo htmlspecialchars($pokemon['type']); ?></p>
            <p class="stats">HP: <?php echo htmlspecialchars($pokemon['hp']); ?> | Attack: <?php echo htmlspecialchars($pokemon['attack']); ?> | Defense: <?php echo htmlspecialchars($pokemon['defense']); ?></p>
            <p class="price">Price: <?php echo htmlspecialchars($pokemon['price']); ?></p>
            <p class="description"><?php echo htmlspecialchars($pokemon['description']); ?></p>
            <!-- Buy button only for logged-in users -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Inside the buy form -->
                <form action="buy-card.php" method="POST">
                    <input type="hidden" name="card_id" value="<?php echo htmlspecialchars($pokemon_id); ?>">
                    <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>">
                    <button type="submit">Buy</button>
                </form>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
