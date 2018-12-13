<?php

class AdminImages
{

    private $conn;

    public function __construct()
    {
        $user = "homestead";
        $password = "secret";
        $server = "localhost";
        $name_db = "image_database";
        $connection = mysqli_connect($server, $user, $password);
        mysqli_select_db($connection, $name_db);

        $this->conn = $connection;
    }

    function create()
    {
        $filename = $_POST['fileName'];
        $description = $_POST['description'];
        $filesize = $_FILES["file"]["size"] / 1024;
        $validExtensions = array("jpeg", "jpg", "png");
        $temporary = explode(".", $_FILES["file"]["name"]);
        $file_extension = end($temporary);

        if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg")) && ($_FILES["file"]["size"] > 2097152) && in_array($file_extension, $validExtensions)) {
            return "There image is not supported.";
        }

        $image = file_get_contents($_FILES["file"]["tmp_name"]);
        $imageData = base64_encode($image);

        $sql = "INSERT INTO images (filename , size , description , image ) VALUES ('$filename', '$filesize', '$description','$imageData')";

        if ($this->conn->query($sql) === TRUE) {
            return "New image was created successfully";
        } else {
            return "Error";
        }
    }

    function index()
    {
        $request = "SELECT * FROM images";
        $result = $this->conn->query($request);
        $rows = array();
        while ($data = $result->fetch_array()) {
            $rows[] = $data;
        }
        $resultArray = json_encode($rows);

        return $resultArray;
    }

    function destroy()
    {
        $idImage = $_POST['id'];

        $sql = "DELETE FROM images WHERE id='$idImage'";

        if ($this->conn->query($sql) === TRUE) {
            return "The image was deleted successfully";
        } else {
            return "Error";
        }
    }

}

if (!empty($_POST['action'])) {
    $image = new AdminImages();
    switch ($_POST['action']) {
        case 'create':
            $result = $image->create();
            echo $result;
            break;
        case 'destroy':
            $result = $image->destroy();
            echo $result;
            break;
        case 'index':
            $result = $image->index();
            echo $result;
            break;

    }
}