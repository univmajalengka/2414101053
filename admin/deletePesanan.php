<?php
require_once __DIR__ . '/db.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    echo "<script>alert('ID pesanan tidak valid.'); window.location.href='orders.php';</script>";
    $conn->close();
    exit;
}

$sql = "DELETE FROM pesanan WHERE id_pesanan = $id";
if ($conn->query($sql) === true) {
    echo "<script>alert('Pesanan berhasil dihapus.'); window.location.href='orders.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus pesanan: " . addslashes($conn->error) . "'); window.location.href='orders.php';</script>";
}

$conn->close();
?>
