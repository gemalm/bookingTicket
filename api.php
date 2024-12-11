<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

header('Content-Type: application/json');

// Open SQLite database
$db = new SQLite3('tickets.db');

// Create tickets table if it doesn't exist
$db->exec('CREATE TABLE IF NOT EXISTS tickets (
    ticket_id TEXT PRIMARY KEY,
    departure_time TEXT,
    source TEXT,
    destination TEXT,
    seat TEXT,
    passport_id TEXT
)');

// Function to generate a random seat number
function generate_seat() {
    $rows = 32;
    $letters = ['A', 'B', 'C', 'D'];
    return $letters[array_rand($letters)] . rand(1, $rows);
}

// Function to validate passport ID
function is_valid_passport_id($passport_id) {
    return ctype_alnum($passport_id) && strlen($passport_id) >= 5;
}

$request_method = $_SERVER['REQUEST_METHOD'];

// Handle different API endpoints
if ($request_method === 'POST') {
    // Create a new ticket
    $input = json_decode(file_get_contents('php://input'), true);
    
    $departure_time = $input['departure_time'] ?? null;
    $source = $input['source'] ?? null;
    $destination = $input['destination'] ?? null;
    $passport_id = $input['passport_id'] ?? null;

    // Validate input
    if (empty($departure_time) || empty($source) || empty($destination) || empty($passport_id)) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit;
    }

    if (strtotime($departure_time) <= time()) {
        http_response_code(400);
        echo json_encode(["error" => "Departure time must be in the future."]);
        exit;
    }

    if (!is_valid_passport_id($passport_id)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid passport ID format."]);
        exit;
    }

    // Generate a unique ticket ID and seat
    $ticket_id = uniqid();
    $seat = generate_seat();

    // Insert the ticket into the database
    $stmt = $db->prepare('INSERT INTO tickets (ticket_id, departure_time, source, destination, seat, passport_id) VALUES (:ticket_id, :departure_time, :source, :destination, :seat, :passport_id)');
    $stmt->bindValue(':ticket_id', $ticket_id);
    $stmt->bindValue(':departure_time', $departure_time);
    $stmt->bindValue(':source', $source);
    $stmt->bindValue(':destination', $destination);
    $stmt->bindValue(':seat', $seat);
    $stmt->bindValue(':passport_id', $passport_id);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode([
            "ticket_id" => $ticket_id,
            "departure_time" => $departure_time,
            "source" => $source,
            "destination" => $destination,
            "seat" => $seat,
            "passport_id" => $passport_id
        ]);
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["error" => "Failed to create ticket."]);
    }
    exit; // Ensure there is no further output
}

// For DELETE requests
if ($request_method === 'DELETE') {
    $ticket_id = basename($_SERVER['REQUEST_URI']); // Get the ticket ID from the URL
    
    $stmt = $db->prepare('DELETE FROM tickets WHERE ticket_id = :ticket_id');
    $stmt->bindValue(':ticket_id', $ticket_id); 
    $stmt->execute();

    // Check if rows were affected
    $changes = $db->changes();

    if ($changes > 0) {
        echo json_encode(["message" => "Ticket cancelled successfully."]);
    } else {
        http_response_code(404); // Not found
        echo json_encode(["error" => "Ticket not found."]); // Custom error message
    }
    exit; // Ensure there is no further output
}

// If we get here, it means the request method is not supported
http_response_code(405); // Method Not Allowed
echo json_encode(["error" => "Method not allowed."]);

// Close the database connection
$db->close();
?>