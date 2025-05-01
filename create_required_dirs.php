<?php
// Script to create required directories that might be causing 500 errors
echo "Starting directory creation script...\n";

// Define required directories
$directories = [
    'assets',
    'assets/css',
    'assets/js',
    'assets/vendors',
    'assets/vendors/toastr',
    'assets/vendors/fullcalendar',
    'assets/vendors/moment',
    'uploads',
    'uploads/admin_image',
    'uploads/syllabus'
];

// Create each directory if it doesn't exist
foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        if (mkdir($dir, 0777, true)) {
            echo "Created directory: $dir\n";
        } else {
            echo "Failed to create directory: $dir\n";
        }
    } else {
        echo "Directory already exists: $dir\n";
    }
}

// Create placeholder files in empty directories
$placeholder_files = [
    'assets/css/toastr.min.css' => '/* Placeholder for toastr CSS */',
    'assets/js/toastr.min.js' => '/* Placeholder for toastr JS */',
    'assets/vendors/toastr/toastr.min.css' => '/* Placeholder for toastr CSS */',
    'assets/vendors/toastr/toastr.min.js' => '/* Placeholder for toastr JS */',
    'assets/vendors/fullcalendar/fullcalendar.min.css' => '/* Placeholder for fullcalendar CSS */',
    'assets/vendors/fullcalendar/fullcalendar.min.js' => '/* Placeholder for fullcalendar JS */',
    'assets/vendors/moment/moment.min.js' => '/* Placeholder for moment JS */'
];

foreach ($placeholder_files as $file => $content) {
    if (!file_exists($file)) {
        if (file_put_contents($file, $content)) {
            echo "Created placeholder file: $file\n";
        } else {
            echo "Failed to create placeholder file: $file\n";
        }
    } else {
        echo "File already exists: $file\n";
    }
}

echo "Directory creation script completed.\n";
?> 