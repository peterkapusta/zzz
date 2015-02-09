<?php

if (isset($_POST["location"])) {
    $db = new PDO('mysql:host=mysql51.websupport.sk;dbname=kamnabic;port=3309', 'tlhl3ze3', 'jq78Nh234Pm');
    $st = $db->prepare("SELECT name FROM location WHERE id=?");
    $st->execute(array($_POST['location']));
    $row = $st->fetch(PDO::FETCH_ASSOC);
    
    $st = $db->prepare("DELETE FROM county_location WHERE location_id=?");
    $st->execute(array($_POST['location']));
    $st = $db->prepare("DELETE FROM location WHERE id=?");
    $st->execute(array($_POST['location']));
    echo 'location ' . $row['name'] . ' deleted';
}