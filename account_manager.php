<?php
$action = isset($_GET['action']) ? $_GET['action'] : '';

$dir = "sessions/";

if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}

// Upload File
if ($action == 'upload' && $_FILES["file"]["error"] === UPLOAD_ERR_OK) {
    $filename = basename($_FILES["file"]["name"]);
    $target_file = $dir . $filename;

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        echo "Upload thành công";
    } else {
        http_response_code(500);
        echo "Lỗi khi lưu file";
    }
}

// Liệt kê các file (Tài khoản)
elseif ($action == 'list') {
    $files = array_values(array_filter(scandir($dir), function($file) {
        return !in_array($file, ['.', '..']) && pathinfo($file, PATHINFO_EXTENSION) === 'db';
    }));

    header('Content-Type: application/json');
    echo json_encode($files);
}

// Xoá Tài khoản
elseif ($action == 'delete' && isset($_POST['file'])) {
    $filename = basename($_POST['file']);
    $file_path = $dir . $filename;

    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            echo "Đã xoá thành công";
        } else {
            http_response_code(500);
            echo "Không thể xoá file";
        }
    } else {
        http_response_code(404);
        echo "File không tồn tại";
    }
}

// Nếu không có action
else {
    echo "Thiếu hoặc action không hợp lệ.";
}
?>