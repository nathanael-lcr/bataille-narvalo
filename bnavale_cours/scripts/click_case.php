<?php
session_start();
include('./sql-connect.php');

if (isset($_POST["cell"])) {
  $sql = new SqlConnect();

  $player = $_SESSION["role"] === 'joueur1' ?  'joueur2' : 'joueur1';
  var_dump($player);
  $query = '
    UPDATE '.$player.'
    SET checked = CASE WHEN checked = 0 THEN 1 ELSE 0 END
    WHERE idgrid = :cell;
  ';

  $req = $sql->db->prepare($query);
  $req->execute(['cell' => $_POST["cell"]]);

  header("Location: ../index.php");

  exit;
}