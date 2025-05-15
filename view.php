<?php
include $_SERVER['DOCUMENT_ROOT'] . '/../shared_includes/db.php';
include 'includes/session.php';
requireLogin(); // Ensure only logged-in users can access this page

// Pagination
$recordsPerPage = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $recordsPerPage;

// Search functionality
$searchCarNumber = isset($_GET['search_car_number']) ? $_GET['search_car_number'] : '';
$searchDate = isset($_GET['search_date']) ? $_GET['search_date'] : '';

// Build the SQL query
$sql = "SELECT * FROM car_photos WHERE 1=1";
if (!empty($searchCarNumber)) {
    $sql .= " AND car_number LIKE :car_number";
}
if (!empty($searchDate)) {
    $sql .= " AND recorded_date = :recorded_date";
}
$sql .= " LIMIT :limit OFFSET :offset";

// Prepare and execute the query
$stmt = $conn->prepare($sql);
if (!empty($searchCarNumber)) {
    $stmt->bindValue(':car_number', "%$searchCarNumber%", PDO::PARAM_STR);
}
if (!empty($searchDate)) {
    $stmt->bindValue(':recorded_date', $searchDate, PDO::PARAM_STR);
}
$stmt->bindValue(':limit', $recordsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get total number of records for pagination
$totalRecords = $conn->query("SELECT COUNT(*) FROM car_photos")->fetchColumn();
$totalPages = ceil($totalRecords / $recordsPerPage);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Car Photos</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- External CSS -->
</head>
<body>
    <!-- Header with Menu -->
    <header class="header-container">
        <div class="container">
            <nav>
                <div class="logo">
                    <a href="index.php">Cek Data WAC</a>
                </div>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="view.php">View Records</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
                <div class="menu-toggle" id="mobile-menu">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container">
        <h1>View Car Photos</h1>

        <!-- Search Form -->
        <div class="search-form">
            <form method="GET" action="view.php">
                <div class="form-row">
                    <div class="form-group">
                        <input type="text" name="search_car_number" placeholder="Search by Car Number" 
                            value="<?php echo htmlspecialchars($searchCarNumber); ?>">
                    </div>
                    <div class="form-group">
                        <input type="date" name="search_date" 
                            value="<?php echo htmlspecialchars($searchDate); ?>">
                    </div>  
                    <div class="form-group">
                        <button type="submit" class="btn">Search</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Records Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Car Number</th>
                        <th>Front Image</th>
                        <th>Left Image</th>
                        <th>Back Image</th>
                        <th>Right Image</th>
                        <th>Dashboard Image</th>
                        <th>Recorded Date</th>
                        <th class="action-col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="9" class="no-records">No records found</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($records as $record): ?>
                        <tr>
                            <td data-label="ID"><?php echo $record['id']; ?></td>
                            <td data-label="Car Number"><?php echo htmlspecialchars($record['car_number']); ?></td>
                            <td data-label="Front Image">
                                <a href="images/<?php echo htmlspecialchars($record['front_image']); ?>" target="_blank">
                                    <img src="images/<?php echo htmlspecialchars($record['front_image']); ?>" width="100" loading="lazy">
                                </a>
                            </td>
                            <td data-label="Left Image">
                                <a href="images/<?php echo htmlspecialchars($record['left_image']); ?>" target="_blank">
                                    <img src="images/<?php echo htmlspecialchars($record['left_image']); ?>" width="100" loading="lazy">
                                </a>
                            </td>
                            <td data-label="Back Image">
                                <a href="images/<?php echo htmlspecialchars($record['back_image']); ?>" target="_blank">
                                    <img src="images/<?php echo htmlspecialchars($record['back_image']); ?>" width="100" loading="lazy">
                                </a>
                            </td>
                            <td data-label="Right Image">
                                <a href="images/<?php echo htmlspecialchars($record['right_image']); ?>" target="_blank">
                                    <img src="images/<?php echo htmlspecialchars($record['right_image']); ?>" width="100" loading="lazy">
                                </a>
                            </td>
                            <td data-label="Dashboard Image">
                                <a href="images/<?php echo htmlspecialchars($record['dashboard_image']); ?>" target="_blank">
                                    <img src="images/<?php echo htmlspecialchars($record['dashboard_image']); ?>" width="100" loading="lazy">
                                </a>
                            </td>
                            <td data-label="Recorded Date"><?php echo htmlspecialchars($record['recorded_date']); ?></td>
<!--                            <td class="action-col">
                                <button class="btn btn-view" onclick="viewRecord(<?php echo $record['id']; ?>)">View</button>
                                <button class="btn btn-delete" onclick="confirmDelete(<?php echo $record['id']; ?>)">Delete</button>
                            </td>
                        -->
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="view.php?page=<?php echo $page - 1; ?>&search_car_number=<?php echo urlencode($searchCarNumber); ?>&search_date=<?php echo urlencode($searchDate); ?>">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="view.php?page=<?php echo $i; ?>&search_car_number=<?php echo urlencode($searchCarNumber); ?>&search_date=<?php echo urlencode($searchDate); ?>" <?php echo $i === $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="view.php?page=<?php echo $page + 1; ?>&search_car_number=<?php echo urlencode($searchCarNumber); ?>&search_date=<?php echo urlencode($searchDate); ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/script.js"></script> <!-- External JavaScript -->
</body>
</html>