<?php

require "dbconnection.php";
$dbcon = createDbConnection();

//Haetaan artistin tiedot artist_id:n perusteella
$artist_id = 1; // Voit vaihtaa tähän halutun artistin id:n

// Haetaan artistin nimi
$query = "SELECT Name FROM artists WHERE ArtistId = ?";
$stmt = $dbcon->prepare($query);
$stmt->execute([$artist_id]);
$albums1 = $stmt->fetch(PDO::FETCH_ASSOC);

// Haetaan artistin albumit ja kappaleet
$query = "SELECT albums.Title AS album_title, tracks.Name AS track_name   
            FROM albums 
            JOIN tracks ON albums.AlbumId = tracks.AlbumId 
            WHERE albums.ArtistId = ?;
            ORDER BY albums.Title";

$stmt = $dbcon->prepare($query);
$stmt->execute([$artist_id]);
$albums2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

$result = array(
    "artist" => $albums,
    "albums" => array()
);
$curret_album = null;
foreach ($albums as $album) {
    if ($curret_album != $album["album_title"]) {
        $result["albums"][$album["album_title"]] = array();
        $curret_album = $album["album_title"];
    }
    $result["albums"][$album["album_title"]][] = $album["track_name"];
}

$response = array(
    "artist_name" => $albums1,
    "albums" => $albums2
);

echo json_encode($response);