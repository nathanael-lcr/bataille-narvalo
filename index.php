<?php
header("Refresh: 2");
session_start();

$fichier = "./etat_joueurs.json";

if (!file_exists($fichier)) {
  file_put_contents($fichier, json_encode(["j1" => null, "j2" => null]));
}

$etat = json_decode(file_get_contents($fichier), true);

// Initialiser current_turn s'il n'existe pas
if (!isset($etat['current_turn'])) {
  $etat['current_turn'] = 'joueur1';
  file_put_contents($fichier, json_encode($gameetatState));
}

$current_turn = $etat['current_turn'];

// Vérifier si c'est le tour du joueur actuel
$is_my_turn = ($_SESSION["role"] === $current_turn);


if ($etat["j1"] != null && $etat["j2"] != null) {
  include('./views/game.php');
} else {
  include('./views/players-selected.php');
}