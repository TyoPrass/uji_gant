<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "PE";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    // Ambil data tugas dari kolom JSON
    $sql = "SELECT task_data FROM gant_customer LIMIT 1";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo $row ? $row['task_data'] : '[]';
}

if ($method == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['action'])) {
        $action = $data['action'];

        // Ambil data JSON saat ini
        $sql = "SELECT task_data FROM gant_customer LIMIT 1";
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
            $check_sql = "SELECT COUNT(*) as count FROM gant_customer WHERE id = 1";
            $check_result = $conn->query($check_sql);
            $check_row = $check_result->fetch_assoc();
            
            if ($check_row['count'] > 0) {
                // Update existing record
                $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? WHERE id = 1");
                $stmt->bind_param("s", $json_tasks);
            } else {
                // Insert new record
                $stmt = $conn->prepare("INSERT INTO gant_customer (id, task_data) VALUES (1, ?)");
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
            $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? WHERE id = 1");
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
            $stmt = $conn->prepare("UPDATE gant_customer SET task_data = ? WHERE id = 1");
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