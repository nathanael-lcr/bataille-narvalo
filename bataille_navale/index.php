<?php
session_start();

//précise le chemin du fichier de stockage de l'état des joueurs
$fichier = "etat_joueurs.json";

//si le fichier n'existe pas, le créer avec les deux joueurs à null
if (!file_exists($fichier)) {
  file_put_contents($fichier, json_encode(["j1" => null, "j2" => null]));
}

$etat = json_decode(file_get_contents($fichier), true);

//fonction pour sauvegarder l'état des joueurs dans le fichier JSON
function save_state($file, $data) {
  file_put_contents($file, json_encode($data));
}

$message = "";

//gestion de la réinitialisation totale de la partie
if (isset($_POST["reset_total"])) {
  $etat = ["j1" => null, "j2" => null];
  save_state($GLOBALS['fichier'], $etat);

  session_unset();
  session_destroy();
  header("Location: index.php");
  exit;
}

$currentSession = session_id();

//gestion de la connexion en tant que Joueur 1
if (isset($_POST["joueur1"])) {
    if ($etat["j1"] === null) {
        if ($etat["j2"] === $currentSession) {
            $message = "Impossible : vous occupez déjà le poste Joueur 2 depuis ce navigateur.";
        } else {
            $etat["j1"] = $currentSession;
            $_SESSION["role"] = "Joueur 1";
            save_state($fichier, $etat);

            if ($etat["j2"] !== null) {
                // l'autre joueur déjà présent -> lancer la partie pour moi
                header("Location: userA.php");
                exit;
            } else {
                $message = "Vous êtes enregistré comme Joueur 1. En attente d'un adversaire...";
            }
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

            if ($etat["j1"] !== null) {
                // l'autre joueur déjà présent -> lancer la partie pour moi
                header("Location: userB.php");
                exit;
            } else {
                $message = "Vous êtes enregistré comme Joueur 2. En attente d'un adversaire...";
            }
        }
    } else {
        $message = "Joueur 2 est déjà occupé.";
    }
}

// redirection uniquement quand les deux joueurs sont connectés
if ($etat["j1"] !== null && $etat["j2"] !== null) {
    if ($etat["j1"] === $currentSession) {
        header("Location: userA.php");
        exit;
    }
    if ($etat["j2"] === $currentSession) {
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