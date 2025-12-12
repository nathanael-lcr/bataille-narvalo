<?php
session_start();
include('./sql-connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


if (isset($_POST["cell"])) {
  $sql = new SqlConnect();

  // Lire l'état du jeu depuis le fichier JSON
  $jsonFile = '../etat_joueurs.json';
  $gameState = json_decode(file_get_contents($jsonFile), true);
  $currentTurn = $gameState['current_turn'] ?? 'joueur1';

  // Si ce n'est pas le tour du joueur, rediriger sans rien faire
  if ($_SESSION["role"] !== $currentTurn) {
    header("Location: ../index.php");
    exit;
  }

  $player = $_SESSION["role"] === 'joueur1' ? 'joueur2' : 'joueur1';
  //var_dump($player);
  $query = '
    UPDATE ' . $player . '
    SET checked = CASE WHEN checked = 0 THEN 1 ELSE 0 END
    WHERE idgrid = :cell;
  ';

  $req = $sql->db->prepare($query);
  $req->execute(['cell' => $_POST["cell"]]);

  // Changer le tour dans le fichier JSON
  $nextTurn = $_SESSION["role"] === 'joueur1' ? 'joueur2' : 'joueur1';
  $gameState['current_turn'] = $nextTurn;
  file_put_contents($jsonFile, json_encode($gameState));

  header("Location: ../index.php");

  exit;
}