<?php

require "dbconnection.php";
$dbcon = createDbConnection();

// Get the artist_id parameter
if (!isset($_GET['artist_id'])) {
    echo "Error: artist_id parameter not set";
    exit();
  }
  $artist_id = $_GET['artist_id'];
  
  try {
    // Begin transaction
    $dbcon->beginTransaction();
  
    // Delete invoice items for tracks associated with albums by the artist
    $sql = "DELETE ii FROM invoice_items ii
            JOIN tracks t ON ii.TrackId = t.TrackId
            JOIN albums a ON t.AlbumId = a.AlbumId
            WHERE a.ArtistId = :artist_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id);
    $stmt->execute();
  
    // Delete tracks associated with albums by the artist
    $sql = "DELETE t FROM tracks t
            JOIN albums a ON t.AlbumId = a.AlbumId
            WHERE a.ArtistId = :artist_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id);
    $stmt->execute();
  
    // Delete albums by the artist
    $sql = "DELETE FROM albums WHERE ArtistId = :artist_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id);
    $stmt->execute();
  
    // Delete the artist
    $sql = "DELETE FROM artists WHERE ArtistId = :artist_id";
    $stmt = $dbcon->prepare($sql);
    $stmt->bindParam(':artist_id', $artist_id);
    $stmt->execute();
  
    // Commit transaction
    $dbcon->commit();
  
    echo "Artist and associated albums, tracks, and invoice items successfully deleted.";
  
  } catch (PDOException $e) {
    // Rollback transaction on error
    $dbcon->rollBack();
    echo "Error deleting artist: " . $e->getMessage();
  }