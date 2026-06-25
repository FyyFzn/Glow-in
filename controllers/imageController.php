<?php
header('Content-Type: application/json');

$files = [];
$dir = __DIR__ . '/../assets/IMG/';

if (is_dir($dir)) {
    foreach (scandir($dir) as $f) {
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
            $files[] = '../assets/IMG/' . $f;
        }
    }
}

echo json_encode($files);
?>
