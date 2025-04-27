<?php

$connection = new mysqli("pma.has.bik", "student", "student", "britov_ai_blog");

if($connection -> connect_error) {
    die("Connection error: {$connection -> connect_error}");
}

$query = $connection -> query("SELECT * FROM users;");
if($query -> num_rows > 0) {
    print("Result: {$query -> num_rows}<br><ul>");

    while($row = $query -> fetch_assoc()) {
        print("<li>{$row["name"]}</li>");
    }

    print("</ul>");
} else {
    print("Result: 0<br>");
}    

$connection -> close();