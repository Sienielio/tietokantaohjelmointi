<?php

require "dbconnection.php";
$dbcon = createDbConnection();

// Set the header to allow cross-origin requests
header('Access-Control-Allow-Origin: *');

// Check if the required parameters are set
if(isset($_POST['artist_name']) && isset($_POST['album_title'])) {
    
    // Connect to the database
    $dbcon = new mysqli('127.0.0.1', 'root', '', 'chinook');
    if($dbcon->connect_error) {
        die('Connection failed: ' . $dbcon->connect_error);
    }
    
    // Prepare the SQL statements for adding the artist and album
    $add_artist_sql = "INSERT INTO artists (Name) VALUES ('".$_POST['artist_name']."')";
    $add_album_sql = "INSERT INTO albums (Title, ArtistId) VALUES ('".$_POST['album_title']."', LAST_INSERT_ID())";
    
    // Execute the SQL statements
    if($dbcon->query($add_artist_sql) === TRUE && $dbcon->query($add_album_sql) === TRUE) {
        echo "New artist and album added successfully";
    } else {
        echo "Error: " . $dbcon->error;
    }
    
    // Close the database connection
    $dbcon->close();
    
} else {
    echo "Error: Required parameters missing";
}