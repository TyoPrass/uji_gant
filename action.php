<?php
include_once("Database/koneksi.php");
// Function to handle insert, update, and delete operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        // Insert operation
        $id_customer = $_POST['id_customer'];
        $tanggal = $_POST['tanggal'];

        $sql = "INSERT INTO gant_customer (id_customer, tanggal) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $id_customer, $tanggal);

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

<!-- Nanti coba disatukan menjadi satu
DATA GANT DAN DATA DARI CUSTOMER DIJADIKAN SATU UNTUK DATA 
-->


<?php
$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // Ambil data tugas dari kolom JSON
    $sql = "SELECT task_data FROM tasks_json LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo $row ? $row['task_data'] : '[]';
}

if ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['action'])) {
        $action = $data['action'];

        // Ambil data JSON saat ini
        $sql = "SELECT task_data FROM tasks_json LIMIT 1";
        $result = $conn->query($sql);
        $tasks = [];
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $tasks = json_decode($row['task_data'], true);
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
            
            // Check if record exists first
            $check_sql = "SELECT COUNT(*) as count FROM tasks_json WHERE id = 1";
            $check_result = $conn->query($check_sql);
            $check_row = $check_result->fetch_assoc();
            
            if ($check_row['count'] > 0) {
                // Update existing record
                $stmt = $conn->prepare("UPDATE tasks_json SET task_data = ? WHERE id = 1");
                $stmt->bind_param("s", $json_tasks);
            } else {
                // Insert new record
                $stmt = $conn->prepare("INSERT INTO tasks_json (id, task_data) VALUES (1, ?)");
                $stmt->bind_param("s", $json_tasks);
            }
            
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
            $stmt = $conn->prepare("UPDATE tasks_json SET task_data = ? WHERE id = 1");
            $stmt->bind_param("s", $json_tasks);
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
            $stmt = $conn->prepare("UPDATE tasks_json SET task_data = ? WHERE id = 1");
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

$conn->close();
?> 



