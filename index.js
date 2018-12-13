$(document).ready(function () {
    loadTable();
    resetDataOfModal();
});

function createImage() {
    $('#imageForm').submit(function (event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'backend.php',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if (data != 'Error') {

                    loadTable();
                } else {
                    alert(data);
                }

            }
        })
    })
}

function deleteImage(id) {

    $.ajax({
        type: 'POST',
        url: 'backend.php',
        data: {'id': id, 'action': 'destroy'},
        datatype: 'json',
        success: function (data) {
            if (data != 'Error') {
                loadTable();
                resetDataOfModal();
            } else {
                alert(data);
            }

        }
    })
}

function loadTable() {
    var table = $("#tableImages tbody");

    $.ajax({
        url: 'backend.php',
        method: "POST",
        data: {"action": 'index'},
        success: function (data) {
            let array = JSON.parse(data);
            table.empty();
            table.append(
                "<tr align='center'>" +
                "<td>IMAGE</td>" +
                "<td>FILENAME</td>" +
                "<td>SIZE</td>" +
                "<td>DESCRIPTION</td>" +
                "<td>ACTION</td></tr>");
            $.each(array, function (a, b) {
                table.append(
                    "<tr>" +
                    "<td style='width: 200px !important;' class='table__td'><img src='data:image/png;base64," + b.image + "' width='200px' height='200px'></td>" +
                    "<td class='table__td'>" + b.filename + "</td>" +
                    "<td class='table__td'>" + b.size + "KB</td>" +
                    "<td class='table__td'>" + b.description + "</td>" +
                    "<td  class='table__td'><button type='button' class='btn btn-danger' onclick='deleteImage(" + b.id + ")'>Delete</button></td></tr>");
            });
        }
    });
}

function resetDataOfModal() {
    $(".modal").on("hidden.bs.modal", function () {
        $(".modal-body input").val("");
        $(".modal-body  textarea").val("");
    });
}