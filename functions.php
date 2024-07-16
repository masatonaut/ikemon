<?php

function sessionStart(){
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }
}

function getJsonData($filename) {
    if (!file_exists($filename)) {
        return []; // Return an empty array if the file does not exist
    }
    $jsonData = file_get_contents($filename);
    return json_decode($jsonData, true) ?: []; // Return the decoded array, or an empty array if decoding fails
}

function saveJsonData($filename, $data) {
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    if (!$jsonData) {
        error_log("JSON encoding failed: " . json_last_error_msg());
        return false;
    }

    $file = fopen($filename, 'w');
    if (!$file) {
        error_log("Failed to open {$filename} for writing.");
        return false;
    }

    fwrite($file, $jsonData);
    fclose($file);

    error_log("Data successfully saved to {$filename}.");
    return true;
}

function getUserDetails($userId) {
    $users = getJsonData('users.json');
    return $users[$userId] ?? null;
}

function addToAdminDeck($cardId, &$users) {
    // Ensure that 'admin' is the correct key for the admin in the $users array
    if (isset($users['admin'])) {
        // Add the card ID to the admin's list of owned cards
        $users['admin']['owned_cards'][] = $cardId;
    }
}

function is_card_owned($cardId, $users) {
    foreach ($users as $user) {
        if (in_array($cardId, $user['owned_cards'] ?? [])) {
            return true;
        }
    }
    return false;
}

function releaseAllCardsFromAdmin(&$users) {
    // Check if admin has any cards to release
    if (!empty($users['admin']['owned_cards'])) {
        // Clear the admin's owned cards
        $users['admin']['owned_cards'] = [];
        return true; // Cards released successfully
    }
    return false; // Admin did not have any cards to release
}

?>