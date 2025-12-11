<?php
  session_start();
  include('./scripts/save_state.php');

  $currentSession = session_id();

  if (isset($_POST["joueur1"])) {
    if ($etat["j1"] === null) {
      // Vérifier que cette session n'est pas déjà joueur2
      if ($etat["j2"] === $currentSession) {
        $error = "❌ Erreur : Vous êtes déjà Joueur 2 !";
      } else {
        $etat["j1"] = $currentSession;
        $_SESSION["role"] = "joueur1";
        save_state("./etat_joueurs.json", $etat);
      }
    } else {
      $error = "❌ Joueur 1 est déjà occupé !";
    }
  }

  if (isset($_POST["joueur2"])) {
    if ($etat["j2"] === null) {
      // Vérifier que cette session n'est pas déjà joueur1
      if ($etat["j1"] === $currentSession) {
        $error = "❌ Erreur : Vous êtes déjà Joueur 1 !";
      } else {
        $etat["j2"] = $currentSession;
        $_SESSION["role"] = "joueur2";
        save_state("./etat_joueurs.json", $etat);
      }
    } else {
      $error = "❌ Joueur 2 est déjà occupé !";
    }
  }

  $role = $_SESSION["role"] ?? "Aucun rôle";
  $error = $error ?? "";
?>

<!DOCTYPE html>
<html>
  <head>
      <meta charset="UTF-8">
      <title>Joueur 1 / Joueur 2</title>
  </head>
  <body>
    <h1>Connexion aux rôles</h1>
    
    <?php if ($error): ?>
      <p style="color: red; font-weight: bold;"><?= $error ?></p>
    <?php endif; ?>
    
    <h2>Votre rôle actuel : <strong><?= $role ?></strong></h2>
    <p>
      Joueur 1 : <?= $etat["j1"] ? "🔴 Occupé" : "🟢 Libre" ?><br>
      Joueur 2 : <?= $etat["j2"] ? "🔴 Occupé" : "🟢 Libre" ?>
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
    </form>
  </body>
</html>