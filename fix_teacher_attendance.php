<?php
// Database connection settings - update these with your actual settings
$db_host = 'localhost';
$db_name = 'school_db'; // Update this to your actual database name
$db_user = 'root';      // Update this to your actual database username
$db_pass = '';          // Update this to your actual database password

// Connect to database
try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully to database<br>";
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if teacher_attendance table exists
try {
    $stmt = $conn->prepare("SHOW TABLES LIKE 'teacher_attendance'");
    $stmt->execute();
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        // Create the table if it doesn't exist
        echo "Creating teacher_attendance table...<br>";
        $conn->exec("CREATE TABLE `teacher_attendance` (
            `attendance_id` int(11) NOT NULL AUTO_INCREMENT,
            `teacher_id` int(11) NOT NULL,
            `date` date NOT NULL,
            `status` tinyint(1) NOT NULL COMMENT '1=present, 2=absent, 3=late',
            `remark` text,
            `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`attendance_id`),
            UNIQUE KEY `teacher_date` (`teacher_id`,`date`),
            KEY `teacher_id` (`teacher_id`),
            KEY `date` (`date`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
        echo "Table created successfully<br>";
    } else {
        echo "Table teacher_attendance already exists<br>";
        
        // Check if remark column exists
        $stmt = $conn->prepare("SHOW COLUMNS FROM teacher_attendance LIKE 'remark'");
        $stmt->execute();
        $remarkExists = $stmt->rowCount() > 0;
        
        if (!$remarkExists) {
            // Add remark column if it doesn't exist
            echo "Adding missing 'remark' column...<br>";
            $conn->exec("ALTER TABLE `teacher_attendance` ADD COLUMN `remark` text AFTER `status`");
            echo "Column added successfully<br>";
        } else {
            echo "Column 'remark' already exists<br>";
        }
    }
    
    echo "<br><strong>Database structure check complete!</strong><br>";
    echo "You can now <a href='index.php'>return to your application</a> and the teacher attendance feature should work correctly.";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
?> 