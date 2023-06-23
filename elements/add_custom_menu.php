<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $menuName = $_POST['name'];
    echo "<pre>";print_r($menuName);die;

    // Perform form processing logic (e.g., insert into database)

    // Prepare the response data
    $response = [
        'success' => true,
        'menuName' => $menuName
    ];

    // Send the response as JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
