<?php

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Content-Type: application/json');

    $method = $_SERVER['REQUEST_METHOD'];

    $conn = mysqli_connect("localhost", "root", "", "fake_api");

    if(!$conn){
        die('Could not connect: ' . mysqli_connect_error($conn));
        
    }
    if($method == "GET") {
        if(isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM students WHERE id=$id";
            $result = mysqli_query($conn, $sql);

            if(mysqli_num_rows($result) > 0) {
                $data = mysqli_fetch_assoc($result);
                echo json_encode($data);
            } else {
                echo json_encode('No Record Found!');
            }
        } else {
            $sql = "SELECT * FROM students";
            $result = mysqli_query($conn, $sql);
            $data = array();

            if(mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    $data[] = $row;
                }
                echo json_encode($data);
            } else {
                echo json_encode('No Record Found!');
            }
        }
    }

    if($method == "POST") {
        $temp = urldecode(file_get_contents('php://input'));
        parse_str($temp, $value);

        $name = mysqli_real_escape_string($conn, $value['name']);
        $course = mysqli_real_escape_string($conn, $value['course']);

        $sql = "INSERT INTO students (name, course) VALUES ('$name', '$course')";
        if(mysqli_query($conn, $sql)) {
            $response = [
                "message" => "Post Success",
                "data" => $value
            ];
            echo json_encode($response);
        } else {
            echo json_encode('Error: ' . mysqli_error($conn));
        }
    }

    if ($method == "PUT") {
        parse_str(file_get_contents("php://input"), $data);
        $id = $_GET['id'];

        $name = mysqli_real_escape_string($conn, $data['name']);
        $course = mysqli_real_escape_string($conn, $data['course']);

        $sql = "UPDATE students SET name='$name', course='$course' WHERE id=$id";
        if (mysqli_query($conn, $sql)) {
            $response = [
                "message" => "Update Success",
                "data" => $data
            ];
            echo json_encode($response);
        } else {
            echo json_encode(['message' => 'Error: ' . mysqli_error($conn)]);
        }
    }

    if ($method == "DELETE") {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "DELETE FROM students WHERE id=$id";
            if (mysqli_query($conn, $sql)) {
                $response = [
                    "message" => "Delete Success",
                    "data" => []
                ];
                echo json_encode($response);
            } else {
                echo json_encode(['message' => 'Error: ' . mysqli_error($conn)]);
            }
        } else {
            echo json_encode(['message' => 'No Record Found!']);
    }
}
?>