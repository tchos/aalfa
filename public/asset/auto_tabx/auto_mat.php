
<?php
    $mysqli = new mysqli('127.0.0.1', 'root', 'lolo', 'aalfa');
    if ($mysqli->connect_errno) { die('Connect Error: ' . $mysqli->connect_errno); }

    ///***************list matricules from database *****************.
    $matricule=trim($_GET['term']);
    $req1x="SELECT  `matricule` FROM  agent WHERE matricule like '".$matricule."%' order by matricule ASC ";
    $result1x=$mysqli->query($req1x);
    while ($row1x = $result1x->fetch_assoc()) {
        // code...
        $data1x[] = $row1x['matricule'];
    }
    echo json_encode($data1x) ;
?>