<?php
// Test upload configuration
echo "<h2>PHP Upload Configuration Test</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Setting</th><th>Current Value</th><th>Recommended</th></tr>";

$settings = [
    'upload_max_filesize' => '5M',
    'post_max_size' => '10M',
    'max_execution_time' => '300',
    'max_input_time' => '300',
    'memory_limit' => '256M',
    'file_uploads' => 'On',
    'max_file_uploads' => '20'
];

foreach ($settings as $setting => $recommended) {
    $current = ini_get($setting);
    $status = ($current == $recommended || (is_numeric($current) && is_numeric($recommended) && $current >= $recommended)) ? '✅' : '❌';
    echo "<tr>";
    echo "<td>$setting</td>";
    echo "<td>$current</td>";
    echo "<td>$recommended $status</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>Test File Upload</h3>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    $file = $_FILES['test_file'];
    echo "<p>File: " . $file['name'] . "</p>";
    echo "<p>Size: " . round($file['size'] / 1024 / 1024, 2) . " MB</p>";
    echo "<p>Type: " . $file['type'] . "</p>";
    echo "<p>Error: " . $file['error'] . "</p>";
    
    if ($file['error'] === UPLOAD_ERR_OK) {
        echo "<p style='color: green;'>✅ Upload successful!</p>";
    } else {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File too large (exceeds upload_max_filesize)',
            UPLOAD_ERR_FORM_SIZE => 'File too large (exceeds MAX_FILE_SIZE)',
            UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
        ];
        echo "<p style='color: red;'>❌ Upload failed: " . ($errors[$file['error']] ?? 'Unknown error') . "</p>";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <p>Test file upload (max 5MB):</p>
    <input type="file" name="test_file" accept="audio/*" required>
    <br><br>
    <button type="submit">Test Upload</button>
</form>
