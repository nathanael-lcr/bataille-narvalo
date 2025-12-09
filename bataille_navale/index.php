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

$message = "";

if (isset($_POST["reset_total"])) {
  $etat = ["j1" => null, "j2" => null];
  save_state($fichier, $etat);

  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
}

$currentSession = session_id();

if (isset($_POST["joueur1"])) {
    if ($etat["j1"] === null) {
        if ($etat["j2"] === $currentSession) {
            $message = "Impossible : vous occupez déjà le poste Joueur 2 depuis ce navigateur.";
        } else {
            $etat["j1"] = $currentSession;
            $_SESSION["role"] = "Joueur 1";
            save_state($fichier, $etat);
            header("Location: game.php");
            exit;
        }
    } else {
        $message = "Joueur 1 est déjà occupé.";
    }
}

if (isset($_POST["joueur2"])) {
    if ($etat["j2"] === null) {
        if ($etat["j1"] === $currentSession) {
            $message = "Impossible : vous occupez déjà le poste Joueur 1 depuis ce navigateur.";
        } else {
            $etat["j2"] = $currentSession;
            $_SESSION["role"] = "Joueur 2";
            save_state($fichier, $etat);
            header("Location: game.php");
            exit;
        }
    } else {
        $message = "Joueur 2 est déjà occupé.";
    }
}

// Redirection si les deux joueurs sont connectés et l'utilisateur a un rôle
if ($etat["j1"] !== null && $etat["j2"] !== null) {
    if (($etat["j1"] === $currentSession || $etat["j2"] === $currentSession) && isset($_SESSION["role"])) {
        header("Location: game.php");
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
      <title>Bataille Navale - Connexion</title>
  </head>
  <body>
    <h1>Connexion aux rôles</h1>
    <?php if ($message): ?>
      <p style="color:crimson;"><strong><?= htmlspecialchars($message) ?></strong></p>
    <?php endif; ?>
    <h2>Votre rôle actuel : <strong><?php echo htmlspecialchars($role) ?></strong></h2>
    <p>
      Joueur 1 : <?php echo $etat["j1"] ? "🔴 Occupé" : "🟢 Libre" ?><br>
      Joueur 2 : <?php echo $etat["j2"] ? "🔴 Occupé" : "🟢 Libre" ?>
    </p>

    <form method="post">
      <button type="submit" name="joueur1"
          <?= $etat["j1"] !== null ? "disabled" : "" ?>>
          🎮 Devenir Joueur 1
      </button>
      <button type="submit" name="joueur2"
          <?= $etat["j2"] !== null ? "disabled" : "" ?>>
          🎮 Devenir Joueur 2
      </button>
      <button type="submit" name="reset_total">
          ❌ Fin de partie (RESET)
      </button>
    </form>
  </body>
</html>