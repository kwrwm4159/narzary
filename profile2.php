<?php
session_start();
include 'dbc.php'; // Include your database connection

// Assuming user_id is stored in the session after login
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo "Please log in to view your profile.";
    exit;
}

// Handle cancellation of bookings
if (isset($_POST['cancel_booking'])) {
    $booking_id = $_POST['booking_id'];

    $query = "DELETE FROM bookings WHERE id = ? AND user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $booking_id, $user_id);

    if ($stmt->execute()) {
        $message = "Booking cancelled successfully!";
    } else {
        $message = "Error cancelling booking: " . $stmt->error;
    }
}

// Handle updating of hotel bookings
if (isset($_POST['edit_hotel_booking'])) {
    $booking_id = $_POST['booking_id'];
    $new_checkin = $_POST['checkin_date'];
    $new_checkout = $_POST['checkout_date'];

    $query = "UPDATE bookings SET checkin_date = ?, checkout_date = ? WHERE id = ? AND user_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssii", $new_checkin, $new_checkout, $booking_id, $user_id);

    if ($stmt->execute()) {
        $message = "Hotel booking updated successfully!";
    } else {
        $message = "Error updating hotel booking: " . $stmt->error;
    }
}

// Fetch hotel bookings
$query_hotels = "SELECT b.id, b.cin, b.cout, h.name AS hotel_name
                 FROM bhotal b
                 LEFT JOIN hotels h ON b.hotel = h.id
                 WHERE b.id = ? ";
$stmt_hotels = $con->prepare($query_hotels);
$stmt_hotels->bind_param("i", $user_id);
$stmt_hotels->execute();
$hotel_bookings = $stmt_hotels->get_result();

// Fetch movie bookings
$query_movies = "SELECT b.id, m.name AS movie_name
                 FROM movie b
                 LEFT JOIN movie m ON b.details = m.id
                 WHERE b.user_id = ? ";
$stmt_movies = $con->prepare($query_movies);
$stmt_movies->bind_param("i", $user_id);
$stmt_movies->execute();
$movie_bookings = $stmt_movies->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
</head>
<body>
    <h1>Welcome to Your Profile</h1>

    <!-- Display Messages -->
    <?php if (isset($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>

    <!-- Hotel Bookings Table -->
    <h2>Your Hotel Bookings</h2>
    <?php if ($hotel_bookings->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Hotel Name</th>
                <th>Check-in Date</th>
                <th>Check-out Date</th>
                <th>Actions</th>
            </tr>
            <?php while ($hotel = $hotel_bookings->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $hotel['hotel_name']; ?></td>
                    <td><?php echo $hotel['checkin_date']; ?></td>
                    <td><?php echo $hotel['checkout_date']; ?></td>
                    <td>
                        <!-- Edit Hotel Booking -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="booking_id" value="<?php echo $hotel['id']; ?>">
                            <input type="date" name="checkin_date" value="<?php echo $hotel['checkin_date']; ?>" required>
                            <input type="date" name="checkout_date" value="<?php echo $hotel['checkout_date']; ?>" required>
                            <button type="submit" name="edit_hotel_booking">Update</button>
                        </form>

                        <!-- Cancel Hotel Booking -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="booking_id" value="<?php echo $hotel['id']; ?>">
                            <button type="submit" name="cancel_booking">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no hotel bookings yet.</p>
    <?php endif; ?>

    <!-- Movie Bookings Table -->
    <h2>Your Movie Bookings</h2>
    <?php if ($movie_bookings->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Movie Name</th>
                <th>Actions</th>
            </tr>
            <?php while ($movie = $movie_bookings->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $movie['movie_name']; ?></td>
                    <td>
                        <!-- Cancel Movie Booking -->
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="booking_id" value="<?php echo $movie['id']; ?>">
                            <button type="submit" name="cancel_booking">Cancel</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no movie bookings yet.</p>
    <?php endif; ?>
</body>
</html>
