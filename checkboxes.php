<?php
session_start();
include 'db.php';

function generateRandomString($length = 15) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        exit('Unauthorized');
    }

    // Retrieve POST data
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $is_checked = isset($_POST['is_checked']) ? 1 : 0;
    $user_id = $_SESSION['user_id'];

    if ($id) {
        // Update existing checkbox
        $stmt = $pdo->prepare("UPDATE checkboxes SET is_checked = ?, user_id = ? WHERE id = ?");
        $stmt->execute([$is_checked, $user_id, $id]);
    } else {
        // Generate a unique checkbox ID and insert new record
        $checkbox_id = generateRandomString();
        $stmt = $pdo->prepare("INSERT INTO checkboxes (id, is_checked, user_id) VALUES (?, ?, ?)");
        $stmt->execute([$checkbox_id, $is_checked, $user_id]);

        // Keep only the last 20 checkboxes
        $stmt = $pdo->query("SELECT id FROM checkboxes ORDER BY id DESC LIMIT 18446744073709551615 OFFSET 20");
        $idsToDelete = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if (!empty($idsToDelete)) {
            $idsToDelete = implode(',', array_map([$pdo, 'quote'], $idsToDelete));
            $pdo->exec("DELETE FROM checkboxes WHERE id IN ($idsToDelete)");
        }
    }
} else {
    // Handle GET request to fetch checkboxes with pagination
    if (!isset($_SESSION['user_id'])) {
        http_response_code(403);
        exit('Unauthorized');
    }

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $perPage = 3000;
    $page = max($page, 1); // Ensure page is at least 1
    $offset = ($page - 1) * $perPage;

    // Debugging: Print the SQL query and parameters
    error_log("Page: $page, PerPage: $perPage, Offset: $offset");
// Fetch checkboxes with user color
$stmt = $pdo->prepare("
    SELECT c.*, u.box_color AS user_color
    FROM checkboxes c
    LEFT JOIN users u ON c.user_id = u.id
    ORDER BY c.id DESC
    LIMIT :perPage OFFSET :offset
");

$stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$checkboxes = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($checkboxes);

}
?>
