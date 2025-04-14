<?php
include_once("Database/koneksi.php");
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'data') {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'GET') {
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
                'parent' => $data['parent'],
                'image_url' => isset($data['image_url']) ? $data['image_url'] : ''
            ];
            $tasks[] = $task;

            $json_tasks = json_encode($tasks, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG);
            if ($json_tasks === false) {
                echo json_encode(["status" => "error", "message" => "JSON encoding error: " . json_last_error_msg()]);
                exit;
            }

            $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? WHERE id_gant = ?");
            $stmt->bind_param("si", $json_tasks, $id_gant);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "id" => $task['id']]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update database"]);
            }
            exit;
        }

        if ($action == 'update') {
            foreach ($tasks as &$task) {
                if ($task['id'] == $data['id']) {
                    $task = $data; // replace whole task
                    break;
                }
            }

            $json_tasks = json_encode($tasks, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG);
            if ($json_tasks === false) {
                echo json_encode(["status" => "error", "message" => "JSON encoding error: " . json_last_error_msg()]);
                exit;
            }

            $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? WHERE id_gant = ?");
            $stmt->bind_param("si", $json_tasks, $id_gant);

            if ($stmt->execute()) {
                echo json_encode(["status" => "updated"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update database"]);
            }
            exit;
        }

        if ($action == 'delete') {
            $tasks = array_filter($tasks, function ($task) use ($data) {
                return $task['id'] != $data['id'];
            });

            $json_tasks = json_encode(array_values($tasks), JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_TAG);
            if ($json_tasks === false) {
                echo json_encode(["status" => "error", "message" => "JSON encoding error: " . json_last_error_msg()]);
                exit;
            }

            $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? WHERE id_gant = ?");
            $stmt->bind_param("si", $json_tasks, $id_gant);

            if ($stmt->execute()) {
                echo json_encode(["status" => "deleted"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to update database"]);
            }
            exit;
        }
    }
}

// ==========================================
// Below is normal form handling (insert/update/delete)
// ==========================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $id_customer = $_POST['id_customer'];
        $tanggal = $_POST['tanggal'];
        $task_data = '[]';

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
        $id_gant = $_POST['id_gant'];
        $id_customer = $_POST['id_customer'];
        $tanggal = $_POST['tanggal'];

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

// Detail
if (isset($_GET['detail'])) {
    $id_gant = $_GET['detail'];
    $sql = "SELECT * FROM gant_customer WHERE id_gant = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_gant);
    $stmt->execute();
    $result = $stmt->get_result();
    $detail_data = $result->fetch_assoc();
}

// Edit
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
