$(document).ready(function () {
    gantt.config.date_format = "%Y-%m-%d";

    gantt.init("gantt_here");

    // Ambil data dari server
    $.getJSON("data.php", function (data) {
        gantt.parse({ data: data });
        updateTaskCards(data);
    });

    // Tambah data baru
    gantt.attachEvent("onAfterTaskAdd", function (id, task) {
        $.ajax({
            url: "data.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                action: "create",
                text: task.text,
                start_date: gantt.date.date_to_str("%Y-%m-%d")(task.start_date),
                duration: task.duration,
                progress: task.progress,
                parent: task.parent
            }),
            success: function (response) {
                console.log("Server response:", response); // Tambahkan logging
                let res = JSON.parse(response);
                gantt.changeTaskId(id, res.id);
                updateTaskCards(gantt.serialize().data);
            },
            error: function (_xhr, _status, error) {
                console.error("Error:", error); // Tambahkan logging untuk error
            }
        });
    });

    // Update data
    gantt.attachEvent("onAfterTaskUpdate", function (id, task) {
        $.ajax({
            url: "data.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                action: "update",
                id: id,
                text: task.text,
                start_date: gantt.date.date_to_str("%Y-%m-%d")(task.start_date),
                duration: task.duration,
                progress: task.progress,
                parent: task.parent
            }),
            success: function () {
                console.log("Task updated");
                updateTaskCards(gantt.serialize().data);
            }
        });
    });

    // Hapus data   
    gantt.attachEvent("onAfterTaskDelete", function (id) {
        $.ajax({
            url: "data.php",
            type: "POST",
            contentType: "application/json",
            data: JSON.stringify({
                action: "delete",
                id: id
            }),
            success: function () {
                console.log("Task deleted");
                updateTaskCards(gantt.serialize().data);
            }
        });
    });
});