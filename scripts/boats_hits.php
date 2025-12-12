<?php
include('./sql-connect.php');

//on récupère la cell touchée où il y a un bateau dessus
$query = "SELECT checked, boat 
		FROM joueur2 
		WHERE idgrid = ?";
$req = $sql->db->prepare($query);
$req->execute([$cell]);
$case = $req->fetch(PDO::FETCH_ASSOC);

$boat_id = $case['boat'];

$query = "SELECT name, value 
		FROM bateau 
		WHERE id = ?";
$req = $sql->db->prepare($query);
$req->execute([$boat_id]);
$boat_info = $req->fetch(PDO::FETCH_ASSOC); //on recup sous forme de tableau associatif

$boat_name = $boat_info['name'];
$boat_size = $boat_info['value']; 

//verif bateau touché
$is_hit = ($touches == $boat_size);

if ($is_hit) {
    $_SESSION['sunk_message'] = "<p>Le bateau **$boat_name** est touché. </p>";
}