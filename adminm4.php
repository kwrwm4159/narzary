<?php
include 'dbc.php'; // Include your database connection

// Add a new movie hall with location
if (isset($_POST['add_hall'])) {
    $location_name = trim($_POST['location_name']);
    $hall_name = trim($_POST['hall_name']);
    $detail = trim($_POST['details']);
    $seat = trim($_POST['seat']);

    if (empty($location_name) || empty($hall_name) || empty($seat)) {
        $message = "All fields are required.";
        $message_type = "error";
    } else {
        $query = "INSERT INTO mlocation (name, location, seats, details) VALUES (?, ?, ?, ?)";
        $stmt_hall = $con->prepare($query);
        $stmt_hall->bind_param("siss", $hall_name, $seat, $location_name, $detail);

        if ($stmt_hall->execute()) {
            $message = "Movie Hall added successfully with location!";
            $message_type = "success";
        } else {
            $message = "Error adding movie hall: " . $stmt_hall->error;
            $message_type = "error";
        }
    }
}

// Add a new movie
if (isset($_POST['add_movie'])) {
    $movie_title = $_POST['movie_title'];
    $hall_id = $_POST['hall_id'];
    $trailer_url = $_POST['trailer_url'];
    $movie_details = $_POST['movie_details'];

    $query_movie = "INSERT INTO movie (name, hall_name, trailer_url, details) VALUES (?, ?, ?, ?)";
    $stmt_movie = $con->prepare($query_movie);
    $stmt_movie->bind_param("siss", $movie_title, $hall_id, $trailer_url, $movie_details);

    if ($stmt_movie->execute()) {
        $message = "Movie added successfully!";
        $message_type = "success";
    } else {
        $message = "Error adding movie: " . $stmt_movie->error;
        $message_type = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Movie Booking System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h1, h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input, textarea, select, button {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        hr {
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <h2>Movie Booking System</h2>

        <!-- Display Messages -->
        <?php if (isset($message)): ?>
            <div class="message <?php echo $message_type; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <!-- Add New Movie Hall with Location Form -->
        <h2>Add New Movie Hall</h2>
        <form method="POST">
            <label for="location_name">Location Name:</label>
            <input type="text" name="location_name" id="location_name" required>

            <label for="hall_name">Hall Name:</label>
            <input type="text" name="hall_name" id="hall_name" required>

            <label for="details">Hall Details:</label>
            <input type="text" name="details" id="details">

            <label for="seat">Seat Details:</label>
            <input type="number" name="seat" id="seat" required>

            <button type="submit" name="add_hall">Add Movie Hall and Location</button>
        </form>

        <hr>

        <!-- Add New Movie Form -->
        <h2>Add New Movie</h2>
        <form method="POST">
            <label for="movie_title">Movie Title:</label>
            <input type="text" name="movie_title" id="movie_title" required>

            <label for="hall_id">Select Movie Hall:</label>
            <select name="hall_id" id="hall_id" required>
                <option value="">--Select Movie Hall--</option>
                <?php
                $query_hall = "SELECT id, name FROM mlocation";
                $halls = $con->query($query_hall);
                while ($hall = $halls->fetch_assoc()) {
                    echo "<option value='{$hall['id']}'>{$hall['name']}</option>";
                }
                ?>
            </select>

            <label for="trailer_url">Trailer URL:</label>
            <input type="url" name="trailer_url" id="trailer_url" required>

            <label for="movie_details">Movie Details:</label>
            <textarea name="movie_details" id="movie_details" required></textarea>

            <button type="submit" name="add_movie">Add Movie</button>
        </form>
    </div>
</body>
</html>
