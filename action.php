<?php
include_once("Database/koneksi.php");
session_start();

// Handle AJAX API requests for Gantt chart data
if (isset($_GET['action']) && $_GET['action'] == 'data') {
    $method = $_SERVER['REQUEST_METHOD'];
    
    if ($method == 'GET') {
        // Fetch task data for a specific gantt chart
        $id_gant = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id_gant > 0) {
            $sql = "SELECT task_data FROM gant_customer WHERE id_gant = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id_gant);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            echo $row ? $row['task_data'] : '[]';
        } else {
            echo '[]';
        }
        exit;
    }

    if ($method == 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $id_gant = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id_gant == 0 || !isset($data['action'])) {
            echo json_encode(["status" => "error", "message" => "Invalid request"]);
            exit;
        }

        $action = $data['action'];

        // Get current task data
        $sql = "SELECT task_data FROM gant_customer WHERE id_gant = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_gant);
        $stmt->execute();
        $result = $stmt->get_result();
        $tasks = [];
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tasks = json_decode($row['task_data'], true) ?: [];
        }

        if ($action == 'create') {
            $task = [
                'id' => uniqid(),
                'text' => $data['text'],
                'start_date' => $data['start_date'],
                'duration' => $data['duration'],
                'progress' => $data['progress'],
                'parent' => $data['parent']
            ];
            $tasks[] = $task;

            $json_tasks = json_encode($tasks);
            $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? WHERE id_gant = ?");
            $stmt->bind_param("si", $json_tasks, $id_gant);
            
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "id" => $task['id']]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update database"]);
            }
        }

        if ($action == 'update') {
            foreach ($tasks as &$task) {
                if ($task['id'] == $data['id']) {
                    $task = $data;
                    break;
                }
            }

            $json_tasks = json_encode($tasks);
            $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? WHERE id_gant = ?");
            $stmt->bind_param("si", $json_tasks, $id_gant);
            
            if ($stmt->execute()) {
                echo json_encode(["status" => "updated"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update database"]);
            }
        }

        if ($action == 'delete') {
            $tasks = array_filter($tasks, function ($task) use ($data) {
                return $task['id'] != $data['id'];
            });

            $json_tasks = json_encode(array_values($tasks));
            $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? WHERE id_gant = ?");
            $stmt->bind_param("si", $json_tasks, $id_gant);
            
            if ($stmt->execute()) {
                echo json_encode(["status" => "deleted"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update database"]);
            }
        }
        
        exit;
    }
}

// Function to handle insert, update, and delete operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        // Insert operation
        $id_customer = $_POST['id_customer'];
        $tanggal = $_POST['tanggal'];
        $task_data = '[]'; // Initialize with empty JSON array
        
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
        
        // Only update customer ID and date, preserve task_data
        $sql = "UPDATE gant_customer SET id_customer = ?, tanggal = ? WHERE id_gant = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $id_customer, $tanggal, $id_gant);

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

// Get detail data if requested
if (isset($_GET['detail'])) {
    $id_gant = $_GET['detail'];
    $sql = "SELECT * FROM gant_customer WHERE id_gant = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_gant);
    $stmt->execute();
    $result = $stmt->get_result();
    $detail_data = $result->fetch_assoc();
}

// Get edit data if requested
if (isset($_GET['edit'])) {
    $id_gant = $_GET['edit'];
    $sql = "SELECT * FROM gant_customer WHERE id_gant = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_gant);
    $stmt->execute();
    $result = $stmt->get_result();
    $edit_data = $result->fetch_assoc();
}
?>
