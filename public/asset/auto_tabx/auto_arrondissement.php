<?php
    $mysqli = new mysqli('127.0.0.1', 'root', 'lolo', 'aalfa');
    if ($mysqli->connect_errno) { die('Connect Error: ' . $mysqli->connect_errno); }

    ///*************** list of arrondissement from database *****************.
    $arrondissement=trim($_GET['term']);
    $req1x="SELECT DISTINCT(`arrondissement`) FROM centre_etat_civil WHERE arrondissement like '".$arrondissement."%' order by arrondissement ASC ";
    $result1x=$mysqli->query($req1x);
    while ($row1x = $result1x->fetch_assoc()) {
        // code...
        $data1x[] = $row1x['arrondissement'];
    }
    echo json_encode($data1x) ;
?>
