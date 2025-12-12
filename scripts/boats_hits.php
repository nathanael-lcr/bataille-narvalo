<?php
include('./sql-connect.php');

//gestion du listing des bateaux
if (!isset($_SESSION['boat_hits'])) {
    $_SESSION['boat_hits'] = [
        2 => 0, 
        3 => 0,
        4 => 0,
        5 => 0  
    ];
}

$cell = $_POST['cell'];

$query = "SELECT checked, boat FROM joueur2 WHERE idgrid = ?";
$req = $sql->db->prepare($query);
$req->execute([$cell]);
$case = $req->fetch(PDO::FETCH_ASSOC);

$boat_id = (int)$case['boat'];

if ($boat_id > 0 && $case['checked'] == 0) {
    $_SESSION['boat_hits'][$boat_id]++;  // on incrémente le compteur dès qu'un boat est touché 
}

$update = $sql->db->prepare("UPDATE joueur2 SET checked = 1 WHERE idgrid = ?");
$update->execute([$cell]);