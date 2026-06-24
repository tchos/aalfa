<?php
    $mysqli = new mysqli('127.0.0.1', 'root', 'lolo', 'aalfa');
    if ($mysqli->connect_errno) { die('Connect Error: ' . $mysqli->connect_errno); }

    ///*************** list of code_cec from database *****************.
    $code_cec=trim($_GET['term']);
    $req1x="SELECT CONCAT(`code_cec`,'-',`libelle_cec`) AS cec FROM centre_etat_civil WHERE code_cec like '".$code_cec."%' order by code_cec ASC ";
    $result1x=$mysqli->query($req1x);
    while ($row1x = $result1x->fetch_assoc()) {
        // code...
        $data1x[] = $row1x['cec'];
    }
    echo json_encode($data1x) ;
?>
