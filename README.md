# Ikemon

Welcome to Ikemon, a PHP-based card game application. This project includes a variety of features for viewing, managing, and interacting with cards, along with user registration and login functionalities. Additionally, an admin panel is available for managing the card inventory.

## Overview

Ikemon is a dynamic card game where users can register, log in, and manage their collection of monster cards. Each card has unique attributes such as name, HP, description, and element. The main page displays all available cards, and users can click on a card to view its details. Admin users have the ability to create new cards and manage the existing inventory.

## Features

Ikemon includes a comprehensive set of features designed to enhance the user experience:

1. **Main Page**: The main page lists all cards with their pictures. Users can click on the name of a card to view its details.
2. **Card Details**: Each card's details page displays the card's name, HP, description, and element. The associated image of the card is also shown. The color or background color of elements on the page changes according to the monster's element (e.g., Fire is red, Lightning is yellow).
3. **Admin Panel**: Admin users can create new cards with error handling and successful save functionality, even without authentication.

### User Account Management

1. **Registration**: The registration form contains appropriate elements and includes error handling with status maintenance. Users can successfully register.
2. **Login**: The login form includes error handling, and users can successfully log in.
3. **Logout**: Users can log out of their accounts.
4. **User Information**: The main page displays the user's name and money. Clicking on the username navigates to the user's details page, which displays the user's name, email address, and money, along with the cards associated with the user.

### Card Management

1. **Card Filtering**: Users can filter cards by type on the main page.
2. **Card Selling**: On the user details page, a sell button next to each card allows users to sell the card. The sold card is deleted from the user's cards, and the user receives 90% of the card's price. The sold card is returned to the admin deck.
3. **Card Buying**: When logged in, users see a buy button under each card on the main page. Users can buy cards if they have enough money, but can only buy up to 5 cards.

### Admin Features

1. **Admin Login**: Admin users can log in with their details.
2. **Admin Card Creation**: New card creation is only available to admin users.

### Additional Features

1. **Design**: The application features a user-friendly and visually appealing design.

## Installation and Setup

To set up Ikemon on your local machine, follow these steps:

1. Clone the repository:

   ```sh
   git clone https://github.com/username/ikemon.git
   cd ikemon
   ```

2. Install the necessary dependencies:

   ```sh
   composer install
   ```

3. Set up the database:

   ```sh
   php artisan migrate
   php artisan db:seed
   ```

4. Start the development server:

   ```sh
   php artisan serve
   ```

5. Open your browser and navigate to `http://localhost:8000` to access the application.
