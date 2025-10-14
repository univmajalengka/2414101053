<?php
include 'db.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];

  $sql = "SELECT gambar FROM produk WHERE id=$id";
  $result = $conn->query($sql);
  $row = $result ? $result->fetch_assoc() : null;
  $gambar = $row['gambar'] ?? '';

  $sql = "DELETE FROM produk WHERE id=$id";

  if ($conn->query($sql) === TRUE) {
    $targetDir = realpath(__DIR__ . 'gambar');
    if ($targetDir === false) {
      $targetDir = __DIR__ . '/gambar';
    }

    $filePath = $targetDir . DIRECTORY_SEPARATOR . basename($gambar);
    if (!empty($gambar) && file_exists($filePath)) {
      unlink($filePath);
    }

    echo "<script>alert(' deleted successfully.'); window.location.href='dashboard.php';</script>";
  } else {
    echo "Error: " . $conn->error;
  }
}
$conn->close();
