<?php

require "dbconnection.php";
$dbcon = createDbConnection();

// Haetaan soittolistan kappaleiden nimet ja säveltäjät playlist_id:n perusteella
$playlist_id = 1; // Voit vaihtaa tähän halutun soittolistan id:n
$sql = "SELECT tracks.Name, tracks.Composer FROM tracks 
        INNER JOIN playlist_track ON tracks.TrackId = playlist_track.TrackId 
        WHERE playlist_track.PlaylistId = :playlist_id";
$stmt = $dbcon->prepare($sql);
$stmt->bindParam(':playlist_id', $playlist_id);
$stmt->execute();

// Tulostetaan kappaleiden tiedot
echo "Soittolistan kappaleet:\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "- " . $row['Name'] . " (säveltäjä: " . $row['Composer'] . ")\n". "<br>";
}