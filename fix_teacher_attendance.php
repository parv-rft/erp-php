<?php
// Database connection settings - Common defaults for most setups
$db_host = 'localhost';
$db_name = isset($_GET['db']) ? $_GET['db'] : ''; // Get from URL or leave empty for auto-detection
$db_user = 'root';      // Common default username
$db_pass = '';          // Common default password (blank)

// Auto-detect database name if not provided
if (empty($db_name)) {
    // Try to read database name from CodeIgniter config
    if (file_exists('application/config/database.php')) {
        include('application/config/database.php');
        if (isset($db[$active_group]['database'])) {
            $db_name = $db[$active_group]['database'];
            echo "<p>Auto-detected database name: {$db_name}</p>";
        }
    }
}

// Connect to the database
try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    echo "<h2>Fix Teacher Attendance Table Utility</h2>";
    echo "<p>Connected to database: {$db_name}</p>";
    
    // Check if teacher_attendance table exists
    $result = $conn->query("SHOW TABLES LIKE 'teacher_attendance'");
    if ($result->num_rows == 0) {
        // Create the table from scratch with the correct structure (no remark column)
        $sql = "CREATE TABLE `teacher_attendance` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `teacher_id` int(11) NOT NULL,
            `date` date NOT NULL,
            `status` tinyint(1) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p class='success'>✅ Teacher attendance table created successfully without 'remark' column</p>";
        } else {
            echo "<p class='error'>❌ Error creating teacher_attendance table: " . $conn->error . "</p>";
        }
    } else {
        // Table exists, check if remark column exists and remove it if it does
        $result = $conn->query("SHOW COLUMNS FROM `teacher_attendance` LIKE 'remark'");
        if ($result->num_rows > 0) {
            // Drop the remark column
            $sql = "ALTER TABLE `teacher_attendance` DROP COLUMN `remark`";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>✅ Removed 'remark' column from teacher_attendance table</p>";
            } else {
                echo "<p class='error'>❌ Error removing 'remark' column: " . $conn->error . "</p>";
            }
        } else {
            echo "<p class='success'>✅ The 'remark' column does not exist in teacher_attendance table (good)</p>";
        }
        
        // Make sure other required columns exist
        $result = $conn->query("SHOW COLUMNS FROM `teacher_attendance` LIKE 'id'");
        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE `teacher_attendance` ADD `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>✅ Added 'id' column to teacher_attendance table</p>";
            } else {
                echo "<p class='error'>❌ Error adding 'id' column: " . $conn->error . "</p>";
            }
        }
        
        $result = $conn->query("SHOW COLUMNS FROM `teacher_attendance` LIKE 'teacher_id'");
        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE `teacher_attendance` ADD `teacher_id` int(11) NOT NULL AFTER `id`";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>✅ Added 'teacher_id' column to teacher_attendance table</p>";
            } else {
                echo "<p class='error'>❌ Error adding 'teacher_id' column: " . $conn->error . "</p>";
            }
        }
        
        $result = $conn->query("SHOW COLUMNS FROM `teacher_attendance` LIKE 'date'");
        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE `teacher_attendance` ADD `date` date NOT NULL AFTER `teacher_id`";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>✅ Added 'date' column to teacher_attendance table</p>";
            } else {
                echo "<p class='error'>❌ Error adding 'date' column: " . $conn->error . "</p>";
            }
        }
        
        $result = $conn->query("SHOW COLUMNS FROM `teacher_attendance` LIKE 'status'");
        if ($result->num_rows == 0) {
            $sql = "ALTER TABLE `teacher_attendance` ADD `status` tinyint(1) NOT NULL DEFAULT '0' AFTER `date`";
            if ($conn->query($sql) === TRUE) {
                echo "<p class='success'>✅ Added 'status' column to teacher_attendance table</p>";
            } else {
                echo "<p class='error'>❌ Error adding 'status' column: " . $conn->error . "</p>";
            }
        }
    }
    
    echo "<h3>Teacher Attendance Table Structure:</h3>";
    $result = $conn->query("DESCRIBE `teacher_attendance`");
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["Field"] . "</td>";
            echo "<td>" . $row["Type"] . "</td>";
            echo "<td>" . $row["Null"] . "</td>";
            echo "<td>" . $row["Key"] . "</td>";
            echo "<td>" . $row["Default"] . "</td>";
            echo "<td>" . $row["Extra"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    echo "<p>Teacher attendance table has been fixed successfully.</p>";
    echo "<a href='javascript:history.back()'>Go back</a> | <a href='".(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http') . "://" . $_SERVER['HTTP_HOST']."/admin/teacher_attendance'>Go to Teacher Attendance</a>";
    
} catch (Exception $e) {
    echo "<h2>Error</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    margin: 20px;
}
h2 {
    color: #333;
    margin-bottom: 20px;
}
p {
    margin-bottom: 10px;
}
.success {
    color: green;
    font-weight: bold;
}
.error {
    color: red;
    font-weight: bold;
}
table {
    border-collapse: collapse;
    margin: 15px 0;
}
th {
    background-color: #f2f2f2;
}
a {
    color: #0066cc;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
</style> 