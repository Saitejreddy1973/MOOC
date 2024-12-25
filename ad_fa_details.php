<?php
include 'config.php';

$sql = "SELECT fa_name, fa_id, section, batch, slot FROM fas";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        $fas = array();
        while ($row = $result->fetch_assoc()) {
            $fas[] = $row;
        }
        echo json_encode($fas);
    } else {
        echo json_encode(array("message" => "No FA details found"));
    }
} else {
    echo json_encode(array("error" => $conn->error));
}

$conn->close();
?>
