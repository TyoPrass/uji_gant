<?php
include_once("Database/koneksi.php");

// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE || !isset($_SESSION)) {
    session_start();
}

// Handle AJAX requests for Gantt chart data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    $request = json_decode(file_get_contents("php://input"), true);
    
    if (isset($request['action'])) {
        switch ($request['action']) {
            case 'create':
            case 'update':
            case 'delete':
                // Get the current task_data JSON for the given id_gant
                $id_gant = $request['id_gant'];
                $sql = "SELECT task_data FROM gant_customer WHERE id_gant = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id_gant);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();
                
                $tasks = [];
                if ($row && !empty($row['task_data'])) {
                    $tasks = json_decode($row['task_data'], true);
                }
                
                // Modify the tasks array based on the action
                if ($request['action'] == 'create') {
                    $tasks[] = [
                        'id' => count($tasks) + 1, // Generate a new ID
                        'text' => $request['text'],
                        'start_date' => $request['start_date'],
                        'duration' => $request['duration'],
                        'progress' => $request['progress'],
                        'parent' => $request['parent']
                    ];
                    $response = ['success' => true, 'id' => count($tasks)];
                } elseif ($request['action'] == 'update') {
                    foreach ($tasks as &$task) {
                        if ($task['id'] == $request['id']) {
                            $task['text'] = $request['text'];
                            $task['start_date'] = $request['start_date'];
                            $task['duration'] = $request['duration'];
                            $task['progress'] = $request['progress'];
                            $task['parent'] = $request['parent'];
                            break;
                        }
                    }
                    $response = ['success' => true];
                } elseif ($request['action'] == 'delete') {
                    foreach ($tasks as $key => $task) {
                        if ($task['id'] == $request['id']) {
                            unset($tasks[$key]);
                            break;
                        }
                    }
                    $tasks = array_values($tasks); // Reindex array
                    $response = ['success' => true];
                }
                
                // Update the task_data JSON in the database
                $task_data_json = json_encode($tasks);
                $sql = "UPDATE gant_customer SET task_data = ? WHERE id_gant = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $task_data_json, $id_gant);
                $stmt->execute();
                $stmt->close();
                
                echo json_encode($response);
                exit();
        }
    }
}

// Fetch Gantt chart data for a specific id_gant
if (isset($_GET['get_tasks']) && isset($_GET['id_gant'])) {
    $id_gant = $_GET['id_gant'];
    $sql = "SELECT task_data FROM gant_customer WHERE id_gant = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_gant);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    
    if ($row && !empty($row['task_data'])) {
        echo $row['task_data'];
    } else {
        echo '[]';
    }
    exit();
}

// Function to handle insert, update, and delete operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        // Insert operation
        $id_customer = $_POST['id_customer'];
        $tanggal = $_POST['tanggal'];
        
        // Process task_data from the form submission
        $task_data = isset($_POST['task_data_json']) ? $_POST['task_data_json'] : '[]';
        
        $sql = "INSERT INTO gant_customer (id_customer, tanggal, task_data) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $id_customer, $tanggal, $task_data);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Record inserted successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error inserting record: " . $conn->error;
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['update'])) {
        // Update operation
        $id_gant = $_POST['id_gant'];
        $id_customer = $_POST['id_customer'];
        $tanggal = $_POST['tanggal'];
        
        // Process task_data from the form submission
        $task_data = isset($_POST['task_data_json']) ? $_POST['task_data_json'] : null;
        
        // If task data is provided, update it along with other fields
        if ($task_data !== null) {
            $sql = "UPDATE gant_customer SET id_customer = ?, tanggal = ?, task_data = ? WHERE id_gant = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $id_customer, $tanggal, $task_data, $id_gant);
        } else {
            // Otherwise, just update the other fields
            $sql = "UPDATE gant_customer SET id_customer = ?, tanggal = ? WHERE id_gant = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $id_customer, $tanggal, $id_gant);
        }
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Record updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error updating record: " . $conn->error;
            $_SESSION['message_type'] = "danger";
        }
        $stmt->close();
        header("Location: index.php");
        exit();
    }
}

if (isset($_GET['delete'])) {
    // Delete operation
    $id_gant = $_GET['delete'];
    
    $sql = "DELETE FROM gant_customer WHERE id_gant = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_gant);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Record deleted successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting record: " . $conn->error;
        $_SESSION['message_type'] = "danger";
    }
    $stmt->close();
    header("Location: index.php");
    exit();
}
?>



<!DOCTYPE html>
 <html lang="en">
     
 <!-- Mirrored from coderthemes.com/hyper/saas/tables-datatable.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:21:55 GMT -->
 <head>
         <meta charset="utf-8" />
         <title>Datatables | Hyper - Responsive Bootstrap 5 Admin Dashboard</title>
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
         <meta content="Coderthemes" name="author" />
         <!-- App favicon -->
         <link rel="shortcut icon" href="assets/images/favicon.ico">
 
         <!-- third party css -->
         <link href="assets/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
         <link href="assets/css/vendor/responsive.bootstrap5.css" rel="stylesheet" type="text/css" />
         <link href="assets/css/vendor/buttons.bootstrap5.css" rel="stylesheet" type="text/css" />
         <link href="assets/css/vendor/select.bootstrap5.css" rel="stylesheet" type="text/css" />
         <link href="assets/css/vendor/fixedHeader.bootstrap5.css" rel="stylesheet" type="text/css" />
         <link href="assets/css/vendor/fixedColumns.bootstrap5.css" rel="stylesheet" type="text/css" />
         <!-- third party css end -->
 
         <!-- App css -->
         <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
         <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" id="app-style"/>
    <!-- Gantt Chart CSS -->
        <link rel="stylesheet" href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css">
        <style>
            #gantt_here {
                width: 100%;
                height: 500px;
                background: white;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .card {
                margin-bottom: 10px;
            }
            .navbar {
                margin-bottom: 20px;
            }
        </style>
     </head>
 
     <body class="loading" data-layout-color="light" data-leftbar-theme="dark" data-layout-mode="fluid" data-rightbar-onstart="true">
         <!-- Begin page -->
         <div class="wrapper">
             <!-- ========== Left Sidebar Start ========== -->
             <div class="leftside-menu">
     
                 <!-- LOGO -->
                 <a href="index.html" class="logo text-center logo-light">
                     <span class="logo-lg">
                         <img src="assets/images/logo.png" alt="" height="16">
                     </span>
                     <span class="logo-sm">
                         <img src="assets/images/logo_sm.png" alt="" height="16">
                     </span>
                 </a>
 
                 <!-- LOGO -->
                 <a href="index.html" class="logo text-center logo-dark">
                     <span class="logo-lg">
                         <img src="assets/images/logo-dark.png" alt="" height="16">
                     </span>
                     <span class="logo-sm">
                         <img src="assets/images/logo_sm_dark.png" alt="" height="16">
                     </span>
                 </a>
     
                 <div class="h-100" id="leftside-menu-container" data-simplebar>
 
                     <!--- Sidemenu -->
                     <ul class="side-nav">
 
                         <li class="side-nav-title side-nav-item">Navigation</li>
 
                         <li class="side-nav-item">
                             <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                                 <i class="uil-home-alt"></i>
                                 <span> Dashboards </span>
                             </a>
                         </li>
 
                         <li class="side-nav-title side-nav-item">Apps</li>
 
                         <li class="side-nav-item">
                             <a data-bs-toggle="collapse" href="#sidebarProjects" aria-expanded="false" aria-controls="sidebarProjects" class="side-nav-link">
                                 <i class="uil-briefcase"></i>
                                 <span> Projects </span>
                                 <span class="menu-arrow"></span>
                             </a>
                             <div class="collapse" id="sidebarProjects">
                                 <ul class="side-nav-second-level">
                                     <li>
                                         <a href="apps-projects-list.html">List</a>
                                     </li>
                                     <li>
                                         <a href="apps-projects-details.html">Details</a>
                                     </li>
                                     <li>
                                         <a href="apps-projects-add.html">Create Project</a>
                                     </li>
                                 </ul>
                             </div>
                         </li>
 
                         <li class="side-nav-item">
                             <a href="apps-social-feed.html" class="side-nav-link">
                                 <i class="uil-rss"></i>
                                 <span> Social Feed </span>
                             </a>
                         </li>
 
                         <li class="side-nav-item">
                             <a data-bs-toggle="collapse" href="#sidebarTasks" aria-expanded="false" aria-controls="sidebarTasks" class="side-nav-link">
                                 <i class="uil-clipboard-alt"></i>
                                 <span> Tasks </span>
                                 <span class="menu-arrow"></span>
                             </a>
                             <div class="collapse" id="sidebarTasks">
                                 <ul class="side-nav-second-level">
                                     <li>
                                         <a href="apps-tasks.html">List</a>
                                     </li>
                                     <li>
                                         <a href="apps-tasks-details.html">Details</a>
                                     </li>
                                     <li>
                                         <a href="apps-kanban.html">Kanban Board</a>
                                     </li>
                                 </ul>
                             </div>
                         </li>
 
                         <!-- end of list -->
                     </ul>
 
                     <!-- Help Box -->
                     <div class="help-box text-white text-center">
                         <a href="javascript: void(0);" class="float-end close-btn text-white">
                             <i class="mdi mdi-close"></i>
                         </a>
                         <img src="assets/images/help-icon.svg" height="90" alt="Helper Icon Image" />
                         <h5 class="mt-3">PE-STAMPING</h5>
                         <p class="mb-3">TES JERAPAHNAIKKUDA</p>
                     </div>
                     <!-- end Help Box -->
                     <!-- End Sidebar -->
 
                     <div class="clearfix"></div>
 
                 </div>
                 <!-- Sidebar -left -->
 
             </div>
             <!-- Left Sidebar End -->
 
             <!-- ============================================================== -->
             <!-- Start Page Content here -->
             <!-- ============================================================== -->
 
             <div class="content-page">
                 <div class="content">
                     <!-- Topbar Start -->
                     <div class="navbar-custom">
                         <ul class="list-unstyled topbar-menu float-end mb-0">
                             <li class="dropdown notification-list d-lg-none">
                                 <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                     <i class="dripicons-search noti-icon"></i>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                                     <form class="p-3">
                                         <input type="text" class="form-control" placeholder="Search ..." aria-label="Recipient's username">
                                     </form>
                                 </div>
                             </li>
                             <li class="dropdown notification-list topbar-dropdown">
                                 <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                     <img src="assets/images/flags/us.jpg" alt="user-image" class="me-0 me-sm-1" height="12"> 
                                     <span class="align-middle d-none d-sm-inline-block">English</span> <i class="mdi mdi-chevron-down d-none d-sm-inline-block align-middle"></i>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu">
 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <img src="assets/images/flags/germany.jpg" alt="user-image" class="me-1" height="12"> <span class="align-middle">German</span>
                                     </a>
 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <img src="assets/images/flags/italy.jpg" alt="user-image" class="me-1" height="12"> <span class="align-middle">Italian</span>
                                     </a>
                 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <img src="assets/images/flags/spain.jpg" alt="user-image" class="me-1" height="12"> <span class="align-middle">Spanish</span>
                                     </a>
 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <img src="assets/images/flags/russia.jpg" alt="user-image" class="me-1" height="12"> <span class="align-middle">Russian</span>
                                     </a>
 
                                 </div>
                             </li>
 
                             <li class="dropdown notification-list">
                                 <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                     <i class="dripicons-bell noti-icon"></i>
                                     <span class="noti-icon-badge"></span>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg">
 
                                     <!-- item-->
                                     <div class="dropdown-item noti-title px-3">
                                         <h5 class="m-0">
                                             <span class="float-end">
                                                 <a href="javascript: void(0);" class="text-dark">
                                                     <small>Clear All</small>
                                                 </a>
                                             </span>Notification
                                         </h5>
                                     </div>
 
                                     <div class="px-3" style="max-height: 300px;" data-simplebar>
 
                                         <h5 class="text-muted font-13 fw-normal mt-0">Today</h5>
                                         <!-- item-->
                                         <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card unread-noti shadow-none mb-2">
                                             <div class="card-body">
                                                 <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                 <div class="d-flex align-items-center">
                                                     <div class="flex-shrink-0">
                                                         <div class="notify-icon bg-primary">
                                                             <i class="mdi mdi-comment-account-outline"></i>
                                                         </div>
                                                     </div>
                                                     <div class="flex-grow-1 text-truncate ms-2">
                                                         <h5 class="noti-item-title fw-semibold font-14">Datacorp <small class="fw-normal text-muted ms-1">1 min ago</small></h5>
                                                         <small class="noti-item-subtitle text-muted">Caleb Flakelar commented on Admin</small>
                                                     </div>
                                                   </div>
                                             </div>
                                         </a>
 
                                         <!-- item-->
                                         <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                             <div class="card-body">
                                                 <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                 <div class="d-flex align-items-center">
                                                     <div class="flex-shrink-0">
                                                         <div class="notify-icon bg-info">
                                                             <i class="mdi mdi-account-plus"></i>
                                                         </div>
                                                     </div>
                                                     <div class="flex-grow-1 text-truncate ms-2">
                                                         <h5 class="noti-item-title fw-semibold font-14">Admin <small class="fw-normal text-muted ms-1">1 hours ago</small></h5>
                                                         <small class="noti-item-subtitle text-muted">New user registered</small>
                                                     </div>
                                                   </div>
                                             </div>
                                         </a>
 
                                         <h5 class="text-muted font-13 fw-normal mt-0">Yesterday</h5>
 
                                         <!-- item-->
                                         <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                             <div class="card-body">
                                                 <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                 <div class="d-flex align-items-center">
                                                     <div class="flex-shrink-0">
                                                         <div class="notify-icon">
                                                             <img src="assets/images/users/avatar-2.jpg" class="img-fluid rounded-circle" alt="" />
                                                         </div>
                                                     </div>
                                                     <div class="flex-grow-1 text-truncate ms-2">
                                                         <h5 class="noti-item-title fw-semibold font-14">Cristina Pride <small class="fw-normal text-muted ms-1">1 day ago</small></h5>
                                                         <small class="noti-item-subtitle text-muted">Hi, How are you? What about our next meeting</small>
                                                     </div>
                                                   </div>
                                             </div>
                                         </a>
 
                                         <h5 class="text-muted font-13 fw-normal mt-0">30 Dec 2021</h5>
 
                                         <!-- item-->
                                         <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                             <div class="card-body">
                                                 <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                 <div class="d-flex align-items-center">
                                                     <div class="flex-shrink-0">
                                                         <div class="notify-icon bg-primary">
                                                             <i class="mdi mdi-comment-account-outline"></i>
                                                         </div>
                                                     </div>
                                                     <div class="flex-grow-1 text-truncate ms-2">
                                                         <h5 class="noti-item-title fw-semibold font-14">Datacorp</h5>
                                                         <small class="noti-item-subtitle text-muted">Caleb Flakelar commented on Admin</small>
                                                     </div>
                                                   </div>
                                             </div>
                                         </a>
 
                                          <!-- item-->
                                          <a href="javascript:void(0);" class="dropdown-item p-0 notify-item card read-noti shadow-none mb-2">
                                             <div class="card-body">
                                                 <span class="float-end noti-close-btn text-muted"><i class="mdi mdi-close"></i></span>   
                                                 <div class="d-flex align-items-center">
                                                     <div class="flex-shrink-0">
                                                         <div class="notify-icon">
                                                             <img src="assets/images/users/avatar-4.jpg" class="img-fluid rounded-circle" alt="" />
                                                         </div>
                                                     </div>
                                                     <div class="flex-grow-1 text-truncate ms-2">
                                                         <h5 class="noti-item-title fw-semibold font-14">Karen Robinson</h5>
                                                         <small class="noti-item-subtitle text-muted">Wow ! this admin looks good and awesome design</small>
                                                     </div>
                                                   </div>
                                             </div>
                                         </a>
 
                                         <div class="text-center">
                                             <i class="mdi mdi-dots-circle mdi-spin text-muted h3 mt-0"></i>
                                         </div>
                                     </div>
 
                                     <!-- All-->
                                     <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item border-top border-light py-2">
                                         View All
                                     </a>
 
                                 </div>
                             </li>
 
                             <li class="dropdown notification-list d-none d-sm-inline-block">
                                 <a class="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                     <i class="dripicons-view-apps noti-icon"></i>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated dropdown-lg p-0">
 
                                     <div class="p-2">
                                         <div class="row g-0">
                                             <div class="col">
                                                 <a class="dropdown-icon-item" href="#">
                                                     <img src="assets/images/brands/slack.png" alt="slack">
                                                     <span>Slack</span>
                                                 </a>
                                             </div>
                                             <div class="col">
                                                 <a class="dropdown-icon-item" href="#">
                                                     <img src="assets/images/brands/github.png" alt="Github">
                                                     <span>GitHub</span>
                                                 </a>
                                             </div>
                                             <div class="col">
                                                 <a class="dropdown-icon-item" href="#">
                                                     <img src="assets/images/brands/dribbble.png" alt="dribbble">
                                                     <span>Dribbble</span>
                                                 </a>
                                             </div>
                                         </div>
 
                                         <div class="row g-0">
                                             <div class="col">
                                                 <a class="dropdown-icon-item" href="#">
                                                     <img src="assets/images/brands/bitbucket.png" alt="bitbucket">
                                                     <span>Bitbucket</span>
                                                 </a>
                                             </div>
                                             <div class="col">
                                                 <a class="dropdown-icon-item" href="#">
                                                     <img src="assets/images/brands/dropbox.png" alt="dropbox">
                                                     <span>Dropbox</span>
                                                 </a>
                                             </div>
                                             <div class="col">
                                                 <a class="dropdown-icon-item" href="#">
                                                     <img src="assets/images/brands/g-suite.png" alt="G Suite">
                                                     <span>G Suite</span>
                                                 </a>
                                             </div>
                                         </div> <!-- end row-->
                                     </div>
 
                                 </div>
                             </li>
 
                             <li class="notification-list">
                                 <a class="nav-link end-bar-toggle" href="javascript: void(0);">
                                     <i class="dripicons-gear noti-icon"></i>
                                 </a>
                             </li>
 
                             <li class="dropdown notification-list">
                                 <a class="nav-link dropdown-toggle nav-user arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                                     aria-expanded="false">
                                     <span class="account-user-avatar"> 
                                         <img src="assets/images/users/avatar-1.jpg" alt="user-image" class="rounded-circle">
                                     </span>
                                     <span>
                                         <span class="account-user-name">Dominic Keller</span>
                                         <span class="account-position">Founder</span>
                                     </span>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-end dropdown-menu-animated topbar-dropdown-menu profile-dropdown">
                                     <!-- item-->
                                     <div class=" dropdown-header noti-title">
                                         <h6 class="text-overflow m-0">Welcome !</h6>
                                     </div>
 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <i class="mdi mdi-account-circle me-1"></i>
                                         <span>My Account</span>
                                     </a>
 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <i class="mdi mdi-account-edit me-1"></i>
                                         <span>Settings</span>
                                     </a>
 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <i class="mdi mdi-lifebuoy me-1"></i>
                                         <span>Support</span>
                                     </a>
 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <i class="mdi mdi-lock-outline me-1"></i>
                                         <span>Lock Screen</span>
                                     </a>
 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <i class="mdi mdi-logout me-1"></i>
                                         <span>Logout</span>
                                     </a>
                                 </div>
                             </li>
 
                         </ul>
                         <button class="button-menu-mobile open-left">
                             <i class="mdi mdi-menu"></i>
                         </button>
                         <div class="app-search dropdown d-none d-lg-block">
                             <form>
                                 <div class="input-group">
                                     <input type="text" class="form-control dropdown-toggle"  placeholder="Search..." id="top-search">
                                     <span class="mdi mdi-magnify search-icon"></span>
                                     <button class="input-group-text btn-primary" type="submit">Search</button>
                                 </div>
                             </form>
 
                             <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
                                 <!-- item-->
                                 <div class="dropdown-header noti-title">
                                     <h5 class="text-overflow mb-2">Found <span class="text-danger">17</span> results</h5>
                                 </div>
 
                                 <!-- item-->
                                 <a href="javascript:void(0);" class="dropdown-item notify-item">
                                     <i class="uil-notes font-16 me-1"></i>
                                     <span>Analytics Report</span>
                                 </a>
 
                                 <!-- item-->
                                 <a href="javascript:void(0);" class="dropdown-item notify-item">
                                     <i class="uil-life-ring font-16 me-1"></i>
                                     <span>How can I help you?</span>
                                 </a>
 
                                 <!-- item-->
                                 <a href="javascript:void(0);" class="dropdown-item notify-item">
                                     <i class="uil-cog font-16 me-1"></i>
                                     <span>User profile settings</span>
                                 </a>
 
                                 <!-- item-->
                                 <div class="dropdown-header noti-title">
                                     <h6 class="text-overflow mb-2 text-uppercase">Users</h6>
                                 </div>
 
                                 <div class="notification-list">
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <div class="d-flex">
                                             <img class="d-flex me-2 rounded-circle" src="assets/images/users/avatar-2.jpg" alt="Generic placeholder image" height="32">
                                             <div class="w-100">
                                                 <h5 class="m-0 font-14">Erwin Brown</h5>
                                                 <span class="font-12 mb-0">UI Designer</span>
                                             </div>
                                         </div>
                                     </a>
 
                                     <!-- item-->
                                     <a href="javascript:void(0);" class="dropdown-item notify-item">
                                         <div class="d-flex">
                                             <img class="d-flex me-2 rounded-circle" src="../../assets/images/users/avatar-5.jpg" alt="Generic placeholder image" height="32">
                                             <div class="w-100">
                                                 <h5 class="m-0 font-14">Jacob Deo</h5>
                                                 <span class="font-12 mb-0">Developer</span>
                                             </div>
                                         </div>
                                     </a>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <!-- end Topbar -->
 
                     <!-- Start Content-->
                     <div class="container-fluid">
                         
                         <!-- start page title -->
                         <div class="row">
                             <div class="col-12">
                                 <div class="page-title-box">
                                     <div class="page-title-right">
                                         <ol class="breadcrumb m-0">
                                             <li class="breadcrumb-item"><a href="javascript: void(0);">Hyper</a></li>
                                             <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                                             <li class="breadcrumb-item active">Data Tables</li>
                                         </ol>
                                     </div>
                                     <h4 class="page-title">Data Tables</h4>
                                 </div>
                             </div>
                         </div>
                         <!-- end page title --> 
 
 
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Gantt Chart Management</h4>
                                        
                                        
                                        <?php if (isset($_SESSION['message'])): ?>
                                            <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                                                <?php echo $_SESSION['message']; ?>
                                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
                                            <?php unset($_SESSION['message']); unset($_SESSION['message_type']); ?>
                                        <?php endif; ?>

                                        <?php if (isset($_GET['detail'])): 
                                            // Fetch the record to be viewed
                                            $id_gant = $_GET['detail'];
                                            $detail_sql = "SELECT * FROM gant_customer WHERE id_gant = ?";
                                            $detail_stmt = $conn->prepare($detail_sql);
                                            $detail_stmt->bind_param("i", $id_gant);
                                            $detail_stmt->execute();
                                            $detail_result = $detail_stmt->get_result();
                                            $detail_data = $detail_result->fetch_assoc();
                                            $detail_stmt->close();
                                            
                                            if (!$detail_data) {
                                                $_SESSION['message'] = "Record not found!";
                                                $_SESSION['message_type'] = "danger";
                                                header("Location: index.php");
                                                exit();
                                            }
                                        ?>
                                            <!-- Detail View -->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <h6 class="text-uppercase fw-bold">Gantt Chart Details</h6>
                                                        <table class="table table-sm">
                                                            <tr>
                                                                <th>Nama Customer</th>
                                                                <td>: <?php echo htmlspecialchars($detail_data['id_customer']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Date</th>
                                                                <td>: <?php echo htmlspecialchars($detail_data['tanggal']); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Gantt JSON</th>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <a href="index.php" class="btn btn-secondary">
                                                    <i class="mdi mdi-arrow-left"></i> Back
                                                </a>
                                                <a href="index.php?edit=<?php echo $detail_data['id_gant']; ?>" class="btn btn-info">
                                                    <i class="mdi mdi-pencil"></i> Edit
                                                </a>
                                            </div>
                                        <?php elseif (isset($_GET['edit'])): 
                                            // Fetch the record to be edited
                                            $id_gant = $_GET['edit'];
                                            $edit_sql = "SELECT * FROM gant_customer WHERE id_gant = ?";
                                            $edit_stmt = $conn->prepare($edit_sql);
                                            $edit_stmt->bind_param("i", $id_gant);
                                            $edit_stmt->execute();
                                            $edit_result = $edit_stmt->get_result();
                                            $edit_data = $edit_result->fetch_assoc();
                                            $edit_stmt->close();
                                            
                                            if (!$edit_data) {
                                                $_SESSION['message'] = "Record not found!";
                                                $_SESSION['message_type'] = "danger";
                                                header("Location: index.php");
                                                exit();
                                            }
                                        ?>
                                                                                    <!-- Edit Form -->
                                                <form action="index.php" method="post">
                                                    <input type="hidden" name="id_gant" value="<?php echo htmlspecialchars($edit_data['id_gant']); ?>">
                                                    <input type="hidden" name="update" value="true">
                                                <div class="mb-3">
                                                    <label for="id_customer" class="form-label">Nama Customer</label>
                                                    <input type="text" class="form-control" id="id_customer" name="id_customer" value="<?php echo htmlspecialchars($edit_data['id_customer']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tanggal" class="form-label">Date</label>
                                                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo htmlspecialchars($edit_data['tanggal']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="gant_json" class="form-label">Gantt JSON</label>
                                                    <div id="gantt_here"></div>
                                                    </div>
                                                <button type="submit" class="btn btn-primary">Update</button>
                                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                            </form>
                                            <?php elseif (isset($_GET['insert'])): ?>
                                            <!-- Insert Form -->
                                            <form action="index.php" method="post">
                                                <input type="hidden" name="submit" value="true">
                                                <input type="hidden" name="task_data_json" id="task_data_json" value="[]">
                                                <div class="mb-3">
                                                    <label for="id_customer" class="form-label">Nama Customer</label>
                                                    <input type="text" class="form-control" id="id_customer" name="id_customer" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="tanggal" class="form-label">Date</label>
                                                    <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="gant_json" class="form-label">Gantt JSON</label>
                                                    <div id="gantt_here"></div>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Insert</button>
                                                <a href="index.php" class="btn btn-secondary">Cancel</a>
                                            </form>
                                        <?php else: ?>
                                            <!-- Display Records Table -->
                                            <div class="mt-3 mb-3">
                                                <a href="index.php?insert=true" class="btn btn-success">
                                                    <i class="mdi mdi-plus"></i> Insert New Gantt Chart
                                                </a>
                                            </div>
                                            <div class="table-responsive">
                                                <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Customer ID</th>
                                                            <th>Date</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $sql = "SELECT * FROM gant_customer ORDER BY id_gant ASC";
                                                        $result = $conn->query($sql);
                                                        $no = 1;
                                                        while ($row = $result->fetch_assoc()): ?>
                                                            <tr>
                                                                <td><?php echo $no++; ?></td>
                                                                <td><?php echo htmlspecialchars($row['id_customer']); ?></td>
                                                                <td><?php echo htmlspecialchars($row['tanggal']); ?></td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <a href="index.php?edit=<?php echo $row['id_gant']; ?>" 
                                                                           class="btn btn-info btn-sm" 
                                                                           data-bs-toggle="tooltip" 
                                                                           title="Edit">
                                                                            <i class="mdi mdi-pencil"></i>
                                                                        </a>
                                                                        <a href="index.php?delete=<?php echo $row['id_gant']; ?>" 
                                                                           class="btn btn-danger btn-sm" 
                                                                           onclick="return confirm('Are you sure you want to delete this record?');"
                                                                           data-bs-toggle="tooltip" 
                                                                           title="Delete">
                                                                            <i class="mdi mdi-delete"></i>
                                                                        </a>
                                                                        <a href="index.php?detail=<?php echo $row['id_gant']; ?>" 
                                                                           class="btn btn-primary btn-sm" 
                                                                           data-bs-toggle="tooltip" 
                                                                           title="Detail">
                                                                            <i class="mdi mdi-eye"></i>
                                                                        </a>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>

                                    </div> <!-- end card body-->
                                </div> <!-- end card -->
                            </div><!-- end col-->
                        </div> <!-- end row-->
                     </div> <!-- container -->
                 </div> <!-- content -->
 
                 <!-- Footer Start -->
                 <footer class="footer">
                     <div class="container-fluid">
                         <div class="row">
                             <div class="col-md-6">
                                 <script>document.write(new Date().getFullYear())</script> © Hyper - Coderthemes.com
                             </div>
                             <div class="col-md-6">
                                 <div class="text-md-end footer-links d-none d-md-block">
                                     <a href="javascript: void(0);">About</a>
                                     <a href="javascript: void(0);">Support</a>
                                     <a href="javascript: void(0);">Contact Us</a>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </footer>
                 <!-- end Footer -->
 
             </div>
 
             <!-- ============================================================== -->
             <!-- End Page content -->
             <!-- ============================================================== -->
 
 
         </div>
         <!-- END wrapper -->
 
 
         <!-- Right Sidebar -->
         <div class="end-bar">
 
             <div class="rightbar-title">
                 <a href="javascript:void(0);" class="end-bar-toggle float-end">
                     <i class="dripicons-cross noti-icon"></i>
                 </a>
                 <h5 class="m-0">Settings</h5>
             </div>
 
             <div class="rightbar-content h-100" data-simplebar>
 
                 <div class="p-3">
                     <div class="alert alert-warning" role="alert">
                         <strong>Customize </strong> the overall color scheme, sidebar menu, etc.
                     </div>
 
                     <!-- Settings -->
                     <h5 class="mt-3">Color Scheme</h5>
                     <hr class="mt-1" />
 
                     <div class="form-check form-switch mb-1">
                         <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="light" id="light-mode-check" checked>
                         <label class="form-check-label" for="light-mode-check">Light Mode</label>
                     </div>
 
                     <div class="form-check form-switch mb-1">
                         <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="dark" id="dark-mode-check">
                         <label class="form-check-label" for="dark-mode-check">Dark Mode</label>
                     </div>
        
 
                     <!-- Width -->
                     <h5 class="mt-4">Width</h5>
                     <hr class="mt-1" />
                     <div class="form-check form-switch mb-1">
                         <input class="form-check-input" type="checkbox" name="width" value="fluid" id="fluid-check" checked>
                         <label class="form-check-label" for="fluid-check">Fluid</label>
                     </div>
 
                     <div class="form-check form-switch mb-1">
                         <input class="form-check-input" type="checkbox" name="width" value="boxed" id="boxed-check">
                         <label class="form-check-label" for="boxed-check">Boxed</label>
                     </div>
         
 
                     <!-- Left Sidebar-->
                     <h5 class="mt-4">Left Sidebar</h5>
                     <hr class="mt-1" />
                     <div class="form-check form-switch mb-1">
                         <input class="form-check-input" type="checkbox" name="theme" value="default" id="default-check">
                         <label class="form-check-label" for="default-check">Default</label>
                     </div>
 
                     <div class="form-check form-switch mb-1">
                         <input class="form-check-input" type="checkbox" name="theme" value="light" id="light-check" checked>
                         <label class="form-check-label" for="light-check">Light</label>
                     </div>
 
                     <div class="form-check form-switch mb-3">
                         <input class="form-check-input" type="checkbox" name="theme" value="dark" id="dark-check">
                         <label class="form-check-label" for="dark-check">Dark</label>
                     </div>
 
                     <div class="form-check form-switch mb-1">
                         <input class="form-check-input" type="checkbox" name="compact" value="fixed" id="fixed-check" checked>
                         <label class="form-check-label" for="fixed-check">Fixed</label>
                     </div>
 
                     <div class="form-check form-switch mb-1">
                         <input class="form-check-input" type="checkbox" name="compact" value="condensed" id="condensed-check">
                         <label class="form-check-label" for="condensed-check">Condensed</label>
                     </div>
 
                     <div class="form-check form-switch mb-1">
                         <input class="form-check-input" type="checkbox" name="compact" value="scrollable" id="scrollable-check">
                         <label class="form-check-label" for="scrollable-check">Scrollable</label>
                     </div>
 
                     <div class="d-grid mt-4">
                         <button class="btn btn-primary" id="resetBtn">Reset to Default</button>
             
                         <a href="https://themes.getbootstrap.com/product/hyper-responsive-admin-dashboard-template/"
                             class="btn btn-danger mt-3" target="_blank"><i class="mdi mdi-basket me-1"></i> Purchase Now</a>
                     </div>
                 </div> <!-- end padding-->
 
             </div>
         </div>
 
         <div class="rightbar-overlay"></div>
         <!-- /End-bar -->
 
 
         <!-- bundle -->
         <script src="assets/js/vendor.min.js"></script>
         <script src="assets/js/app.min.js"></script>
 
         <!-- third party js -->
         <script src="assets/js/vendor/jquery.dataTables.min.js"></script>
         <script src="assets/js/vendor/dataTables.bootstrap5.js"></script>
         <script src="assets/js/vendor/dataTables.responsive.min.js"></script>
         <script src="assets/js/vendor/responsive.bootstrap5.min.js"></script>
         <script src="assets/js/vendor/dataTables.buttons.min.js"></script>
         <script src="assets/js/vendor/buttons.bootstrap5.min.js"></script>
         <script src="assets/js/vendor/buttons.html5.min.js"></script>
         <script src="assets/js/vendor/buttons.flash.min.js"></script>
         <script src="assets/js/vendor/buttons.print.min.js"></script>
         <script src="assets/js/vendor/dataTables.keyTable.min.js"></script>
         <script src="assets/js/vendor/dataTables.select.min.js"></script>
         <script src="assets/js/vendor/fixedColumns.bootstrap5.min.js"></script>
         <script src="assets/js/vendor/fixedHeader.bootstrap5.min.js"></script>
         <!-- third party js ends -->
 
         <!-- demo app -->
         <script src="assets/js/pages/demo.datatable-init.js"></script>
         <!-- end demo js-->
         <script>
            $(document).ready(function () {
    // Store tasks temporarily
        let tempTasks = [];
        
        if ($("#gantt_here").length > 0) {
            gantt.config.date_format = "%Y-%m-%d";
            gantt.init("gantt_here");
            
            // Check if we're in edit mode and need to fetch existing data
            const urlParams = new URLSearchParams(window.location.search);
            const editMode = urlParams.get('edit');
            const insertMode = urlParams.get('insert');
            let currentIdGant = editMode || 'temp';
            
            if (editMode) {
                // Load existing data for editing
                $.getJSON("index.php?get_tasks=true&id_gant=" + editMode, function (data) {
                    tempTasks = data || [];
                    gantt.parse({ data: tempTasks });
                });
            } else if (insertMode) {
                // Start with empty chart for insert
                tempTasks = [];
                gantt.parse({ data: [] });
            }
            
            // Add task
            gantt.attachEvent("onAfterTaskAdd", function (id, task) {
                // Generate a unique temporary ID
                const tempId = 'temp_' + new Date().getTime() + '_' + Math.floor(Math.random() * 1000);
                
                // Store in our temporary array
                tempTasks.push({
                    id: tempId,
                    text: task.text,
                    start_date: gantt.date.date_to_str("%Y-%m-%d")(task.start_date),
                    duration: task.duration,
                    progress: task.progress,
                    parent: task.parent
                });
                
                // Update hidden form field
                $("#task_data_json").val(JSON.stringify(tempTasks));
                
                // Update gantt chart ID
                gantt.changeTaskId(id, tempId);
            });
            
            // Update task
            gantt.attachEvent("onAfterTaskUpdate", function (id, task) {
                // Update in our temporary array
                for (let i = 0; i < tempTasks.length; i++) {
                    if (tempTasks[i].id == id) {
                        tempTasks[i] = {
                            id: id,
                            text: task.text,
                            start_date: gantt.date.date_to_str("%Y-%m-%d")(task.start_date),
                            duration: task.duration,
                            progress: task.progress,
                            parent: task.parent
                        };
                        break;
                    }
                }
                
                // Update hidden form field
                $("#task_data_json").val(JSON.stringify(tempTasks));
            });
            
            // Delete task
            gantt.attachEvent("onAfterTaskDelete", function (id) {
                // Remove from our temporary array
                tempTasks = tempTasks.filter(task => task.id != id);
                
                // Update hidden form field
                $("#task_data_json").val(JSON.stringify(tempTasks));
            });
            
            // Make sure form submission includes task data
            $("form").on("submit", function() {
                // Ensure task data is included
                if (!$("#task_data_json").val()) {
                    $("#task_data_json").val(JSON.stringify(tempTasks));
                }
                return true;
            });
        }
    });

        </script>
        <script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
     </body>
 
 <!-- Mirrored from coderthemes.com/hyper/saas/tables-datatable.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 29 Jul 2022 10:22:01 GMT -->
 </html>