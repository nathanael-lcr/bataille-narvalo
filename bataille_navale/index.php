<?php
session_start();

$fichier = "etat_joueurs.json";

if (!file_exists($fichier)) {
  file_put_contents($fichier, json_encode(["j1" => null, "j2" => null]));
}

$etat = json_decode(file_get_contents($fichier), true);

function save_state($file, $data) {
  file_put_contents($file, json_encode($data));
}

if (isset($_POST["reset_total"])) {
  $etat = ["j1" => null, "j2" => null];
  save_state($GLOBALS['fichier'], $etat);

  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
}

if (isset($_POST["joueur1"])) {
    if ($etat["j1"] === null) {
        $etat["j1"] = session_id();
        $_SESSION["role"] = "Joueur 1";
        save_state($fichier, $etat);
    }
}

if (isset($_POST["joueur2"])) {
    if ($etat["j2"] === null) {
        $etat["j2"] = session_id();
        $_SESSION["role"] = "Joueur 2";
        save_state($fichier, $etat);
    }
}

// Vérification et redirection selon le rôle
if ($etat["j1"] !== null && $etat["j2"] !== null) {
    if ($_SESSION["role"] === "Joueur 1") {
        header("Location: userA.php");
        exit;
    } elseif ($_SESSION["role"] === "Joueur 2") {
        header("Location: userB.php");
        exit;
    }
}

$role = $_SESSION["role"] ?? "Aucun rôle";

header('refresh:5');
?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Joueur 1 / Joueur 2</title>
  </head>
  <body>
    <h1>Connexion aux rôles</h1>
    <h2>Votre rôle actuel : <strong><?php echo $role ?></strong></h2>
    <p>
      Joueur 1 : <?php echo $etat["j1"] ? "🔴 Occupé" : "🟢 Libre" ?><br>
      Joueur 2 : <?php echo $etat["j2"] ? "🔴 Occupé" : "🟢 Libre" ?>
    </p>

    <form method="post">
      <button action="game.php" type="submit" name="joueur1"
          <?= $etat["j1"] !== null ? "disabled" : "" ?>>
          🎮 Devenir Joueur 1
      </button>
      <button action="game.php" type="submit" name="joueur2"
          <?= $etat["j2"] !== null ? "disabled" : "" ?>>
          🎮 Devenir Joueur 2
      </button>
      <button type="submit" name="reset_total">
          ❌ Fin de partie (RESET)
      </button>
    </form>
  </body>
</html>