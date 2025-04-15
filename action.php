<?php
include_once("Database/koneksi.php");
session_start();

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch JSON data
    $sql = "SELECT task_data FROM gant_customer LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo $row ? $row['task_data'] : '[]';
    exit();
}

if ($method === 'POST') {
    if (isset($_POST['submit'])) {
        // Insert operation
        $id_customer = $_POST['id_customer'];
        $tanggal = $_POST['tanggal'];

        $sql = "INSERT INTO gant_customer (id_customer, tanggal, task_data) VALUES (?, ?, ?)";
        $default_task_data = '[]';
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $id_customer, $tanggal, $default_task_data);

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
    } else {
        // Handle JSON data
        $data = json_decode(file_get_contents("php://input"), true);

        if (isset($data['action'])) {
            $action = $data['action'];

            // Fetch current JSON data
            $sql = "SELECT task_data FROM gant_customer LIMIT 1";
            $result = $conn->query($sql);
            $tasks = [];
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $tasks = json_decode($row['task_data'], true);
            }

            if ($action === 'create') {
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

                // Check if record exists
                $check_sql = "SELECT COUNT(*) as count FROM gant_customer LIMIT 1";
                $check_result = $conn->query($check_sql);
                $check_row = $check_result->fetch_assoc();

                if ($check_row['count'] > 0) {
                    // Update existing record
                    $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? LIMIT 1");
                    $stmt->bind_param("s", $json_tasks);
                } else {
                    // Insert new record
                    $stmt = $conn->prepare("INSERT INTO gant_customer (id_customer, tanggal, task_data) VALUES ('default', CURRENT_DATE(), ?)");
                    $stmt->bind_param("s", $json_tasks);
                }

                if ($stmt->execute()) {
                    echo json_encode(["status" => "success", "id" => $task['id']]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to update database"]);
                }
            }

            if ($action === 'update') {
                foreach ($tasks as &$task) {
                    if ($task['id'] == $data['id']) {
                        $task = $data;
                        break;
                    }
                }

                $json_tasks = json_encode($tasks);
                $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? LIMIT 1");
                $stmt->bind_param("s", $json_tasks);
                if ($stmt->execute()) {
                    echo json_encode(["status" => "updated"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to update database"]);
                }
            }

            if ($action === 'delete') {
                $tasks = array_filter($tasks, function ($task) use ($data) {
                    return $task['id'] != $data['id'];
                });

                $json_tasks = json_encode(array_values($tasks));
                $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? LIMIT 1");
                $stmt->bind_param("s", $json_tasks);
                if ($stmt->execute()) {
                    echo json_encode(["status" => "deleted"]);
                } else {
                    echo json_encode(["status" => "error", "message" => "Failed to update database"]);
                }
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Invalid action"]);
        }
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

$conn->close();
?>
