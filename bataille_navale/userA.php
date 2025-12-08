<?php
$fichier = "etat_joueurs.json";

$etat = json_decode(file_get_contents($fichier), true);

function save_state($file, $data)
{
    file_put_contents($file, json_encode($data));
}

session_start();
if ($_SESSION["role"] !== "Joueur 1") {
    header("Location: index.php");
    exit;
}

if (isset($_POST["disconnect"])) {
    $etat = ["j1" => null, "j2" => null];
    save_state($GLOBALS['fichier'], $etat);

    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html>

<head>
    <title>Joueur 1</title>
</head>

<body>
    <h1>Bienvenue Joueur 1</h1>
    <form method="post">
        <button type="submit" name="disconnect" class="disconnect-btn">
            ❌ Arrêter la partie
        </button>
    </form>
</body>

</html>