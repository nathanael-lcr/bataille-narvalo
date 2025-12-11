<?php
if (isset($_POST["reset_total"])) {
  include('./sql-connect.php');

  $sql = new SqlConnect();

  $query = 'UPDATE joueur1 SET checked = 0';
  $req = $sql->db->prepare($query);
  $req->execute();

  $query = 'UPDATE joueur2 SET checked = 0';
  $req = $sql->db->prepare($query);
  $req->execute();

  // Réinitialiser le tour dans le fichier JSON
  $jsonFile = '../etat_joueurs.json';
  $gameState = json_decode(file_get_contents($jsonFile), true);
  $gameState['current_turn'] = 'joueur1';
  file_put_contents($jsonFile, json_encode($gameState));

  include('./destory_session.php');

  header("Location: ../index.php");
  exit;
}