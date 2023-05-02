<?php
// Database file name
$db_file = 'visitors.sqlite3';

// Connect to the SQLite database
$db = new SQLite3($db_file);

// Fetch all records from the visitors table
$result = $db->query("SELECT * FROM visitors");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Information</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Visitor Information</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User Agent</th>
                <th>IP Address</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['user_agent']); ?></td>
                    <td><?php echo $row['ip_address']; ?></td>
                    <td><?php echo $row['timestamp']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
<?php
// Close the database connection
$db->close();
?>
