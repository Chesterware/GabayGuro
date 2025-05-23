<?php
require_once '../../db_connection.php';
require_once 'auth_admin.php';

if (!isset($_GET['tutor_id'], $_GET['type'])) {
    die("Invalid request");
}

$tutor_id = (int)$_GET['tutor_id'];
$type = $_GET['type'];

$allowed_types = [
    'diploma' => 'diploma',
    'certificate' => 'other_certificates'
];

if (!array_key_exists($type, $allowed_types)) {
    die("Invalid file type");
}

$column = $allowed_types[$type];

$sql = "SELECT $column, email FROM tutor WHERE tutor_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tutor_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    die("No file found");
}

$stmt->bind_result($file_path, $email);
$stmt->fetch();

if (empty($file_path) || !file_exists(__DIR__ . '/../../' . $file_path)) {
    die("File not available");
}

$full_path = realpath(__DIR__ . '/../../' . $file_path);

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($full_path);

$extension = '';
switch ($mime) {
    case 'application/pdf':
        $extension = 'pdf';
        break;
    case 'image/jpeg':
        $extension = 'jpg';
        break;
    case 'image/png':
        $extension = 'png';
        break;
    default:
        $extension = 'bin';
}

$filename = $type . '_' . $email . '.' . $extension;

header("Content-Type: $mime");
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . filesize($full_path));

readfile($full_path);
exit;
?>
