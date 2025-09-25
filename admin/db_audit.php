<?php
// Include your DB config
require_once __DIR__ . '/../app/config/db.php';

echo "<h2>📊 Database Audit Tool</h2>";
echo "<p>This script lists all tables and shows the structure of key tables.</p>";

try {
    // 1️⃣ - Show all tables in the database
    echo "<h3>All Tables in Database:</h3>";
    $tablesQuery = $DB_con->query("SHOW TABLES");
    $tables = $tablesQuery->fetchAll(PDO::FETCH_COLUMN);

    if (count($tables) > 0) {
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>$table</li>";
        }
        echo "</ul>";
    } else {
        echo "<p><strong>No tables found!</strong></p>";
    }

    // 2️⃣ - Describe selected tables
    $targetTables = ['students', 'faculty', 'courses', 'semesters', 'enrollments', 'departments', 'users'];

    foreach ($targetTables as $table) {
        if (in_array($table, $tables)) {
            echo "<h3>📁 Table: <code>$table</code></h3>";
            $stmt = $DB_con->query("DESCRIBE `$table`");
            $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo "<table border='1' cellpadding='6' cellspacing='0' style='border-collapse: collapse; margin-bottom: 20px;'>";
            echo "<tr style='background:#f0f0f0;'>
                    <th>Field</th>
                    <th>Type</th>
                    <th>Null</th>
                    <th>Key</th>
                    <th>Default</th>
                    <th>Extra</th>
                  </tr>";

            foreach ($columns as $col) {
                echo "<tr>
                        <td>{$col['Field']}</td>
                        <td>{$col['Type']}</td>
                        <td>{$col['Null']}</td>
                        <td>{$col['Key']}</td>
                        <td>{$col['Default']}</td>
                        <td>{$col['Extra']}</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<h3>⚠️ Table <code>$table</code> not found in database.</h3>";
        }
    }
} catch (PDOException $e) {
    echo "<p style='color:red;'>❌ Error: " . $e->getMessage() . "</p>";
}
 