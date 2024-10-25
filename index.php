<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location:./login.php");
    exit;
}
require_once('DBConnection.php');
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
if ($_SESSION['type'] != 1 && in_array($page, array('maintenance', 'products', 'stocks'))) {
    header("Location:./");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo ucwords(str_replace('_', '', $page)) ?> | JEWELLERY SHOP MANAGEMENT SYSTEM</title>
    <link rel="stylesheet" href="./Font-Awesome-master/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./select2/css/select2.min.css">
    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./js/popper.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="./DataTables/datatables.min.css">
    <script src="./DataTables/datatables.min.js"></script>
    <script src="./select2/js/select2.full.min.js"></script>
    <script src="./Font-Awesome-master/js/all.min.js"></script>
    <script src="./js/script.js"></script>
    <style>
        :root {
            --primary-color: white; /* Dimmed orange */
        }

        body,
        html {
            height: 100%;
            width: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: var(--light-color);
        }

        .navbar {
            background-color: var(--primary-color) !important;
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            z-index: 1000;
        }

        .navbar .navbar-brand,
        .navbar-nav .nav-link {
            color: white !important;
        }

        .sidebar {
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: var(--primary-color);
            padding-top: 20px;
            z-index: 1000;
        }
        

        .sidebar a {
            padding: 10px 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            border-radius: 25px; /* Rounded corners */
            background-color: green; /* Green background */
            margin: 10px 0; /* Space between buttons */
            text-align: center; /* Center text */
            transition: background-color 0.3s; /* Smooth transition for hover */
        }
        .sidebar-logo {
    max-width: 100%; /* Ensure the logo fits within the sidebar */
    height: auto; /* Maintain aspect ratio */
    margin: -10px 0 15px; /* Negative top margin to move it up, space below */
}

.sidebar img {
    max-width: 100%; /* Ensure the logo fits within the sidebar */
    height: auto; /* Maintain aspect ratio */
    margin: 0 auto 20px; /* Center the logo and add margin below */
    display: block; /* Center block */
}

        .sidebar a:hover {
            background-color: yellow; /* Darker green on hover */
        }

        main {
            margin-top: 56px; /* Height of the navbar */
            margin-left: 250px; /* Width of the sidebar */
            padding: 20px;
            height: calc(100vh - 56px);
            overflow-y: auto;
            background-image: url('images/jewel.jpg'); /* Path to your cover photo */
            background-size: cover; /* Ensures the image covers the entire area */
            background-position: center; /* Center the image */
            background-repeat: no-repeat; /* Prevent the image from repeating */
            color: white; /* Change text color for better visibility on the background */
        }

        .container-fluid {
            max-width: 100%;
        }

        #page-container {
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .dynamic_alert {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1050;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: var(--primary-color);
            border-radius: 5px;
        }

        .truncate-1,
        .truncate-3 {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-box-orient: vertical;
        }

        .truncate-1 {
            -webkit-line-clamp: 1;
        }

        .truncate-3 {
            -webkit-line-clamp: 3;
        }

        .modal-dialog.large {
            width: 80% !important;
        }

        .modal-dialog.mid-large {
            width: 50% !important;
        }

        .modal-header,
        .modal-footer {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <img src="./images/logo1.png" alt="Bakery Logo"> <!-- Logo at the top of the sidebar -->
        <h2 class="text-center" style="color: black;">JEWELLERY SHOP M.S</h2>
        <a href="./" class="<?php echo ($page == 'home') ? 'active' : '' ?>">Home</a>
        <?php if ($_SESSION['type'] == 1) : ?>
            <a href="./?page=products" class="<?php echo ($page == 'products') ? 'active' : '' ?>">Products</a>
            <a href="./?page=stocks" class="<?php echo ($page == 'stocks') ? 'active' : '' ?>">Stock</a>
        <?php endif; ?>
        <a href="./?page=sales" class="<?php echo ($page == 'sales') ? 'active' : '' ?>">Sales</a>
        <a href="./?page=sales_report" class="<?php echo ($page == 'sales_report') ? 'active' : '' ?>">POS Report</a>
        <?php if ($_SESSION['type'] == 1) : ?>
            <a href="./?page=users" class="<?php echo ($page == 'users') ? 'active' : '' ?>">Users</a>
            <a href="./?page=maintenance" class="<?php echo ($page == 'maintenance') ? 'active' : '' ?>">Maintenance</a>
        <?php endif; ?>
    </div>
    <!-- Main content -->
    <main>
        <div class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <span class="navbar-brand">Hello, <?php echo $_SESSION['fullname'] ?></span>
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Account
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="./?page=manage_account">Manage Account</a></li>
                        <li><a class="dropdown-item" href="./Actions.php?a=logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="page-container">
            <?php 
            if (isset($_SESSION['flashdata'])) :
            ?>
                <div class="dynamic_alert alert alert-<?php echo $_SESSION['flashdata']['type'] ?> rounded-0 shadow">
                    <div class="float-end">
                        <a href="javascript:void(0)" class="text-dark text-decoration-none" onclick="$(this).closest('.dynamic_alert').hide('slow').remove()">x</a>
                    </div>
                    <?php echo $_SESSION['flashdata']['msg'] ?>
                </div>
            <?php unset($_SESSION['flashdata']) ?>
            <?php endif; ?>
            <?php include $page . '.php'; ?>
        </div>
    </main>

    <!-- Modals -->
    <div class="modal fade" id="uni_modal" role='dialog' data-bs-backdrop="static" data-bs-keyboard="true">
        <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md modal-dialog-centered rounded-0" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header py-2">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <p id="confirm_text">Are you sure you want to proceed?</p>
                </div>
                <div class="modal-footer py-1">
                    <button type="button" class="btn btn-sm rounded-0 btn-primary" id='confirm' onclick="$('#confirm_modal').modal('hide')">Yes</button>
                    <button type="button" class="btn btn-sm rounded-0 btn-secondary" data-bs-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
