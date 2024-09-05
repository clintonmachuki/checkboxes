<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user color preference
    $color = $_POST['box_color'];
    $stmt = $pdo->prepare("UPDATE users SET box_color = ? WHERE id = ?");
    $stmt->execute([$color, $user_id]);
    
    // Redirect to profile page with success message
    header("Location: profile.php?success=1");
    exit();
} else {
    // Fetch current user data
    $stmt = $pdo->prepare("SELECT box_color FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Set Checkbox Color</title>
    <style>
        .checkbox-preview {
            display: inline-block;
            margin-top: 10px;
        }

        input[type="checkbox"] {
            appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #000;
            background-color: <?php echo htmlspecialchars($user['box_color']); ?>;
            cursor: pointer;
        }

        input[type="checkbox"]:checked {
            background-color: <?php echo htmlspecialchars($user['box_color']); ?>;
        }
    </style>
</head>
<body>
    <h1>Set Your Checkbox Color</h1>

    <?php if (isset($_GET['success'])): ?>
        <p style="color: green;">Your color preference has been updated!</p>
    <?php endif; ?>

    <form method="POST" action="profile.php">
        <label for="box_color">Choose Checkbox Color:</label>
        <input type="color" id="box_color" name="box_color" value="<?php echo htmlspecialchars($user['box_color']); ?>" required>
        <br><br>
        <button type="submit">Save</button>
    </form>

    <!-- Preview the checkbox in the selected color -->
    <div class="checkbox-preview">
        <p>Checkbox Preview:</p>
        <input type="checkbox" id="checkboxPreview" checked>
    </div>

    <br><br>
    <a href="index.php">Go back to the homepage</a>

    <script>
        // JavaScript to dynamically change the checkbox color as user selects it
        document.getElementById('box_color').addEventListener('input', function() {
            document.getElementById('checkboxPreview').style.backgroundColor = this.value;
        });
    </script>
</body>
</html>
