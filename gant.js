$(document).ready(function () {
    if ($("#gantt_here").length > 0) {
        gantt.config.date_format = "%Y-%m-%d";
        gantt.init("gantt_here");

        // Ambil data dari server
        $.getJSON("action.php?id_gant=1", function (data) { // Ganti id_gant=1 sesuai kebutuhan
            gantt.parse({ data: data });
            updateTaskCards(data);
        });

        // Tambah data baru
        gantt.attachEvent("onAfterTaskAdd", function (id, task) {
            $.ajax({
                url: "action.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    action: "create",
                    id_gant: 1, // Ganti sesuai kebutuhan
                    text: task.text,
                    start_date: gantt.date.date_to_str("%Y-%m-%d")(task.start_date),
                    duration: task.duration,
                    progress: task.progress,
                    parent: task.parent
                }),
                success: function (response) {
                    console.log("Server response:", response);
                    let res = JSON.parse(response);
                    gantt.changeTaskId(id, res.id);
                    updateTaskCards(gantt.serialize().data);
                },
                error: function (_xhr, _status, error) {
                    console.error("Error:", error);
                }
            });
        });

        // Update data
        gantt.attachEvent("onAfterTaskUpdate", function (id, task) {
            $.ajax({
                url: "action.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    action: "update",
                    id_gant: 1, // Ganti sesuai kebutuhan
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
                url: "action.php",
                type: "POST",
                contentType: "application/json",
                data: JSON.stringify({
                    action: "delete",
                    id_gant: 1, // Ganti sesuai kebutuhan
                    id: id
                }),
                success: function () {
                    console.log("Task deleted");
                    updateTaskCards(gantt.serialize().data);
                },
                error: function (_xhr, _status, error) {
                    console.error("Error:", error);
                }
            });
        });
    }
});
