<?php 
include $_SERVER['DOCUMENT_ROOT'] . '/../shared_includes/db.php';
include 'includes/session.php';
requireLogin(); // Ensure only logged-in users can access this page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Photo Upload</title>
    <link rel="stylesheet" href="css/styles.css"> <!-- External CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <!-- Header with Menu -->
    <header class="header-container">
        <div class="container">
            <nav>
                <div class="logo">
                    <a href="index.php">INPUT WAC MOBIL</a>
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
        <h1>Upload Car Photos</h1>
        <form id="uploadForm" action="upload.php" method="post" enctype="multipart/form-data">
            <!-- Car Number and Recorded Date Side by Side -->
            <div class="form-row">
                <div class="form-group">
                    <label for="car_number">Plat Nomor:</label>
                    <input type="text" name="car_number" id="car_number">
                </div>
                <div class="form-group">
                    <label for="recorded_date">Recorded Date:</label>
                    <input type="date" name="recorded_date" id="recorded_date">
                </div>
            </div>

            <!-- File Inputs with Two Options (Take Photo or Upload File) -->
            <div class="form-group">
                <label>Front Image:</label>
                <div class="file-options">
                    <button type="button" class="btn take-photo" onclick="takePhoto('front_image')">Take Photo</button>
                    <input type="file" name="front_image" id="front_image" accept="image/*" style="display: none;">
                    <button type="button" class="btn upload-file" onclick="document.getElementById('front_image').click()">Upload File</button>
                </div>
                <div id="front_image_preview"></div>
                <div id="front_image_status"></div>
            </div>

            <div class="form-group">
                <label>Left Image:</label>
                <div class="file-options">
                    <button type="button" class="btn take-photo" onclick="takePhoto('left_image')">Take Photo</button>
                    <input type="file" name="left_image" id="left_image" accept="image/*" style="display: none;">
                    <button type="button" class="btn upload-file" onclick="document.getElementById('left_image').click()">Upload File</button>
                </div>
                <div id="left_image_preview"></div>
                <div id="left_image_status"></div>
            </div>

            <div class="form-group">
                <label>Back Image:</label>
                <div class="file-options">
                    <button type="button" class="btn take-photo" onclick="takePhoto('back_image')">Take Photo</button>
                    <input type="file" name="back_image" id="back_image" accept="image/*" style="display: none;">
                    <button type="button" class="btn upload-file" onclick="document.getElementById('back_image').click()">Upload File</button>
                </div>
                <div id="back_image_preview"></div>
                <div id="back_image_status"></div>
            </div>

            <div class="form-group">
                <label>Right Image:</label>
                <div class="file-options">
                    <button type="button" class="btn take-photo" onclick="takePhoto('right_image')">Take Photo</button>
                    <input type="file" name="right_image" id="right_image" accept="image/*" style="display: none;">
                    <button type="button" class="btn upload-file" onclick="document.getElementById('right_image').click()">Upload File</button>
                </div>
                <div id="right_image_preview"></div>
                <div id="right_image_status"></div>
            </div>

            <div class="form-group">
                <label>Dashboard Image:</label>
                <div class="file-options">
                    <button type="button" class="btn take-photo" onclick="takePhoto('dashboard_image')">Take Photo</button>
                    <input type="file" name="dashboard_image" id="dashboard_image" accept="image/*" style="display: none;">
                    <button type="button" class="btn upload-file" onclick="document.getElementById('dashboard_image').click()">Upload File</button>
                </div>
                <div id="dashboard_image_preview"></div>
                <div id="dashboard_image_status"></div>
            </div>

            <button type="submit" class="btn">Upload</button>
        </form>
    </div>
    
    <script src="js/script.js"></script> <!-- External JavaScript -->
    <script>
        // Set default value for recorded_date to today's date
        document.getElementById('recorded_date').valueAsDate = new Date();
        // SweetAlert notification
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['upload_success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Data uploaded successfully!',
                    timer: 3000,
                    showConfirmButton: false
                });
                <?php unset($_SESSION['upload_success']); ?>
            <?php elseif (isset($_SESSION['upload_error'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '<?php echo addslashes($_SESSION['upload_error']); ?>'
                });
                <?php unset($_SESSION['upload_error']); ?>
            <?php endif; ?>
        });
    </script>
</body>
</html>