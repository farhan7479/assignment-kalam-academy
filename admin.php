<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "admin";

// Create a mysqli connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT
            lm.Lead_ID,
            lm.Name,
            lm.Mobile,
            lm.Alternate_Mobile,
            lm.Whatsapp,
            lm.Email,
            lm.Interested_In,
            lm.Source,
            lm.Status,
            cs.DOR AS Summary_DOR,
            ca.Name AS Caller,
            lm.State,
            lm.City
        FROM
            crm_lead_master_data lm
        LEFT JOIN (
            SELECT
                Lead_ID,
                MAX(DOR) AS DOR
            FROM
                crm_calling_status
            GROUP BY
                Lead_ID
        ) cs ON lm.Lead_ID = cs.Lead_ID
        LEFT JOIN crm_admin ca ON lm.Caller_ID = ca.Admin_ID";
$result = $conn->query($sql);

$data = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($data);
?>
