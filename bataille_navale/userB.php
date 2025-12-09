<?php
$fichier = "etat_joueurs.json";

$etat = json_decode(file_get_contents($fichier), true);

function save_state($file, $data)
{
    file_put_contents($file, json_encode($data));
}

session_start();
if ($_SESSION["role"] !== "Joueur 2") {
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

$mySession = session_id();
$myRole = $_SESSION["role"] ?? "";

// Fonction pour vérifier l'état
function checkPlayerState($mySession, $myRole)
{
    // Lire le fichier JSON
    $jsonFile = 'etat_joueurs.json';

    if (!file_exists($jsonFile)) {
        return false;
    }

    $data = json_decode(file_get_contents($jsonFile), true);

    if ($data === null) {
        return false;
    }

    // Vérifier si les deux joueurs sont déconnectés
    if ($data['j1'] === null && $data['j2'] === null) {
        header('Location: index.php');
        exit();
    }

    // Vérifier si le Joueur 1 a été remplacé
    if ($myRole === 'Joueur 1' && $data['j1'] !== $mySession) {
        header('Location: index.php');
        exit();
    }

    // Vérifier si le Joueur 2 a été remplacé
    if ($myRole === 'Joueur 2' && $data['j2'] !== $mySession) {
        header('Location: index.php');
        exit();
    }

    return true;
}

// Appeler la fonction
checkPlayerState($mySession, $myRole);
header('refresh:5');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Joueur 2</title>
    <style>
        table {
            border-collapse: collapse;
        }
        table, thead, tbody, tr, th, td {
            border: 1px solid black;
        }
        th, td {
            text-align: center;
            padding: 10px;
        }
        th {
            background-color: rgb(200, 200, 200);
        }
    </style>
</head>

<body>
    <h1>Bienvenue Joueur 2</h1>
    <form method="post">
        <button type="submit" name="disconnect" class="disconnect-btn">
            ❌ Arrêter la partie
        </button>
    </form>



    <table>
        <thead>
            <tr>
                <th scope="col"></th>
                <?php for ($j = 0; $j < 10; $j++) { ?>
                    <th scope="col"><?php echo $j + 1 ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $i < 10; $i++) { ?>
                <tr>
                    <th scope="row"><?php echo chr(65 + $i) ?></th>
                    <?php for ($j = 0; $j < 10; $j++) { ?>
                        <td></td>
                    <?php } ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</body>

</html>