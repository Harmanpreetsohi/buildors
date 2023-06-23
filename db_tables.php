<?php
	$hostname = "localhost";
	$username = "root";
	$password = "";
	$database = "buildors";
	$link = mysqli_connect($hostname, $username, $password, $database);
    // Create the table query
    $menusTableQuery = "CREATE TABLE custom_menus (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50) NOT NULL,
        menu_id INT(11) NOT NULL,
        position INT(11) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
     )";

    // Execute the table creation query
    if (mysqli_query($link, $menusTableQuery)) {
        echo "Table 'custom_menus' created successfully.";
    } else {
        echo "Error creating table: " . mysqli_error($link);
    }

    // $sectionsTableQuery = "CREATE TABLE sections (
    //     id INT(11) AUTO_INCREMENT PRIMARY KEY,
    //     custom_menu_id INT(11) NOT NULL,
    //     name VARCHAR(50) NOT NULL,
    //     position INT(11) NOT NULL,
    //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    //     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    //     FOREIGN KEY (custom_menu_id) REFERENCES menus(id) ON DELETE CASCADE
    //  )";
     

    // Close the connection
    mysqli_close($link);


?>
