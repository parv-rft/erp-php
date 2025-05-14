<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Custom Helper Functions
 */

/**
 * Check if a directory exists and is writable, create it if it doesn't exist
 * 
 * @param string $dir Directory path
 * @return array Status array with 'success' flag and 'message'
 */
function ensure_directory_exists($dir) {
    $result = array(
        'success' => false,
        'message' => ''
    );
    
    // Check if directory exists
    if (!is_dir($dir)) {
        // Try to create the directory
        if (!mkdir($dir, 0777, true)) {
            $result['message'] = "Failed to create directory: $dir";
            error_log($result['message']);
            return $result;
        }
        
        // Directory created successfully
        $result['success'] = true;
        $result['message'] = "Directory created: $dir";
        return $result;
    }
    
    // Directory exists, check if it's writable
    if (!is_writable($dir)) {
        $result['message'] = "Directory exists but is not writable: $dir";
        error_log($result['message']);
        return $result;
    }
    
    // Directory exists and is writable
    $result['success'] = true;
    $result['message'] = "Directory exists and is writable: $dir";
    return $result;
}

/**
 * Check and validate a file upload
 * 
 * @param array $file $_FILES array element
 * @param array $allowed_types Allowed MIME types
 * @param int $max_size Maximum file size in bytes
 * @param string $upload_path Directory to upload to
 * @param string $filename Filename to use (without extension)
 * @return array Status array with 'success' flag, 'message', and 'path' if successful
 */
function validate_and_upload_file($file, $allowed_types, $max_size, $upload_path, $filename) {
    $result = array(
        'success' => false,
        'message' => '',
        'path' => ''
    );
    
    // Check if file was uploaded
    if (empty($file['name'])) {
        $result['message'] = "No file was uploaded";
        return $result;
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        $result['message'] = "File size exceeds limit of " . round($max_size / (1024 * 1024), 2) . "MB";
        error_log($result['message'] . ": " . $file['size'] . " bytes");
        return $result;
    }
    
    // Check file type
    if (!in_array($file['type'], $allowed_types)) {
        $result['message'] = "Invalid file type: " . $file['type'] . ". Allowed types: " . implode(', ', $allowed_types);
        error_log($result['message']);
        return $result;
    }
    
    // Check upload directory
    $dir_check = ensure_directory_exists($upload_path);
    if (!$dir_check['success']) {
        $result['message'] = $dir_check['message'];
        return $result;
    }
    
    // Generate complete filename with extension
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fullpath = $upload_path . $filename . '.' . $ext;
    
    // Upload the file
    if (!move_uploaded_file($file['tmp_name'], $fullpath)) {
        $result['message'] = "Failed to move uploaded file to: $fullpath";
        error_log($result['message']);
        return $result;
    }
    
    // Success
    $result['success'] = true;
    $result['message'] = "File uploaded successfully";
    $result['path'] = $fullpath;
    error_log("Successfully uploaded file to: $fullpath");
    
    return $result;
}

/**
 * Check server environment for common issues
 * 
 * @return array Array of issues found
 */
function check_server_environment() {
    $issues = array();
    
    // Check PHP version
    if (version_compare(PHP_VERSION, '5.6.0', '<')) {
        $issues[] = "PHP version is outdated: " . PHP_VERSION . ". Recommended: 5.6.0 or higher.";
    }
    
    // Check memory limit
    $memory_limit = ini_get('memory_limit');
    $memory_limit_bytes = return_bytes($memory_limit);
    if ($memory_limit_bytes < 128 * 1024 * 1024) { // 128 MB
        $issues[] = "Memory limit is low: $memory_limit. Recommended: 128M or higher.";
    }
    
    // Check upload_max_filesize
    $upload_max_filesize = ini_get('upload_max_filesize');
    $upload_max_filesize_bytes = return_bytes($upload_max_filesize);
    if ($upload_max_filesize_bytes < 8 * 1024 * 1024) { // 8 MB
        $issues[] = "upload_max_filesize is low: $upload_max_filesize. Recommended: 8M or higher.";
    }
    
    // Check post_max_size
    $post_max_size = ini_get('post_max_size');
    $post_max_size_bytes = return_bytes($post_max_size);
    if ($post_max_size_bytes < 8 * 1024 * 1024) { // 8 MB
        $issues[] = "post_max_size is low: $post_max_size. Recommended: 8M or higher.";
    }
    
    // Check common upload directories
    $upload_dirs = array(
        'uploads/',
        'uploads/student_image/',
        'uploads/parent_image/',
        'uploads/student_documents/'
    );
    
    foreach ($upload_dirs as $dir) {
        $dir_check = ensure_directory_exists($dir);
        if (!$dir_check['success']) {
            $issues[] = $dir_check['message'];
        }
    }
    
    return $issues;
}

/**
 * Convert PHP ini values like "2M" to bytes
 * 
 * @param string $val Value to convert
 * @return int Value in bytes
 */
function return_bytes($val) {
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    $val = (int) $val;
    
    switch($last) {
        case 'g':
            $val *= 1024;
        case 'm':
            $val *= 1024;
        case 'k':
            $val *= 1024;
    }
    
    return $val;
} 