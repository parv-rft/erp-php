<?php
// Set error reporting to show all errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define base URL for the application
define('BASE_URL', 'http://localhost:8080/');

echo "=== Timetable Debug Tool ===\n\n";

// Check if request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get POST data
    $data = [
        'class_id' => $_POST['class_id'] ?? '',
        'section_id' => $_POST['section_id'] ?? '',
        'subject_id' => $_POST['subject_id'] ?? '',
        'teacher_id' => $_POST['teacher_id'] ?? '',
        'start_date' => $_POST['start_date'] ?? '',
        'end_date' => $_POST['end_date'] ?? '',
        'start_time' => $_POST['start_time'] ?? '',
        'end_time' => $_POST['end_time'] ?? ''
    ];
    
    // Add timetable_id if editing
    if (!empty($_POST['timetable_id'])) {
        $data['timetable_id'] = $_POST['timetable_id'];
    }
    
    // Log data
    echo "POST data received:\n";
    print_r($data);
    
    // Send request to save_timetable_ajax
    $ch = curl_init(BASE_URL . 'admin/save_timetable_ajax');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    // Execute curl request
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    // Check for errors
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch) . "\n";
    }
    
    // Close curl
    curl_close($ch);
    
    // Output response
    echo "Response status: " . $status . "\n";
    echo "Response body:\n";
    echo $response . "\n";
    
    // Try to decode JSON
    $json = json_decode($response, true);
    if ($json) {
        echo "Decoded JSON:\n";
        print_r($json);
    } else {
        echo "Failed to decode JSON. JSON error: " . json_last_error_msg() . "\n";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Debug Timetable Save</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 15px; background: #4CAF50; color: white; border: none; cursor: pointer; }
        pre { background: #f4f4f4; padding: 15px; border-radius: 5px; overflow: auto; }
    </style>
</head>
<body>
    <h1>Debug Timetable Save</h1>
    
    <form method="post" action="">
        <div class="form-group">
            <label for="class_id">Class ID:</label>
            <input type="text" id="class_id" name="class_id" required>
        </div>
        
        <div class="form-group">
            <label for="section_id">Section ID:</label>
            <input type="text" id="section_id" name="section_id" required>
        </div>
        
        <div class="form-group">
            <label for="subject_id">Subject ID:</label>
            <input type="text" id="subject_id" name="subject_id" required>
        </div>
        
        <div class="form-group">
            <label for="teacher_id">Teacher ID:</label>
            <input type="text" id="teacher_id" name="teacher_id" required>
        </div>
        
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" id="start_date" name="start_date" required>
        </div>
        
        <div class="form-group">
            <label for="end_date">End Date:</label>
            <input type="date" id="end_date" name="end_date" required>
        </div>
        
        <div class="form-group">
            <label for="start_time">Start Time:</label>
            <input type="time" id="start_time" name="start_time" required>
        </div>
        
        <div class="form-group">
            <label for="end_time">End Time:</label>
            <input type="time" id="end_time" name="end_time" required>
        </div>
        
        <div class="form-group">
            <label for="timetable_id">Timetable ID (optional for update):</label>
            <input type="text" id="timetable_id" name="timetable_id">
        </div>
        
        <button type="submit">Test Save Timetable</button>
    </form>
</body>
</html> 