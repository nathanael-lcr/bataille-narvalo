<?php
session_start();

$fichier = "etat_joueurs.json";
$fichier_grilles = "grilles_joueurs.json";

if (!file_exists($fichier)) {
    file_put_contents($fichier, json_encode(["j1" => null, "j2" => null]));
}

if (!file_exists($fichier_grilles)) {
    $grilles_initiales = [
        "j1" => [
            "grille" => [
                [3, 0, 0, 0, 0, 0, 0, 2, 2, 0],
                [3, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [3, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 2, 2, 0, 0, 0],
                [0, 0, 0, 0, 0, 5, 0, 0, 0, 4],
                [0, 0, 0, 0, 0, 5, 0, 0, 0, 4],
                [0, 0, 0, 0, 0, 5, 0, 0, 0, 4],
                [3, 3, 3, 0, 0, 5, 0, 0, 0, 4],
                [0, 0, 0, 0, 0, 5, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
            ],
            "tirs" => array_fill(0, 10, array_fill(0, 10, null))
        ],
        "j2" => [
            "grille" => [
                [2, 2, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 3, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 3, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 3, 0],
                [0, 0, 5, 5, 5, 5, 5, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 4],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 4],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 4],
                [0, 0, 0, 0, 0, 0, 0, 0, 0, 4]
            ],
            "tirs" => array_fill(0, 10, array_fill(0, 10, null))
        ]
    ];
    file_put_contents($fichier_grilles, json_encode($grilles_initiales));
}

$etat = json_decode(file_get_contents($fichier), true);
$grilles = json_decode(file_get_contents($fichier_grilles), true);

function save_state($file, $data) {
    file_put_contents($file, json_encode($data));
}

// Vérifier que l'utilisateur a un rôle
if (!isset($_SESSION["role"])) {
    header("Location: index.php");
    exit;
}

$mySession = session_id();
$myRole = $_SESSION["role"];
$myPlayer = ($myRole === "Joueur 1") ? "j1" : "j2";
$oppPlayer = ($myRole === "Joueur 1") ? "j2" : "j1";

// Vérifier l'état actuel
if ($etat['j1'] === null && $etat['j2'] === null) {
    // Les deux joueurs sont déconnectés
    header('Location: index.php');
    exit();
}

// Vérifier si le joueur actuel a été remplacé
if ($myRole === 'Joueur 1' && $etat['j1'] !== $mySession) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

if ($myRole === 'Joueur 2' && $etat['j2'] !== $mySession) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
}

// Gestion de la déconnexion (reset total)
if (isset($_POST["disconnect"])) {
    $etat = ["j1" => null, "j2" => null];
    save_state($fichier, $etat);

    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

// Gestion des tirs (du moins un début)
if (isset($_POST["tirer"])) {
    $row = (int)$_POST["row"];
    $col = (int)$_POST["col"];
    
    if ($row >= 0 && $row < 10 && $col >= 0 && $col < 10) {
        // Vérifier que la case n'a pas déjà été tirée
        if ($grilles[$myPlayer]["tirs"][$row][$col] === null) {
            // Récupérer la valeur de la grille adverse
            $valeur = $grilles[$oppPlayer]["grille"][$row][$col];
            // 1 si touché (valeur > 0), 0 si raté (valeur == 0)
            $grilles[$myPlayer]["tirs"][$row][$col] = ($valeur > 0) ? 1 : 0;
            save_state($fichier_grilles, $grilles);
        }
    }
}

header('refresh:5');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bataille Navale - <?= htmlspecialchars($myRole) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        .info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }

        .disconnect-btn {
            padding: 10px 20px;
            background-color: #ff6b6b;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .disconnect-btn:hover {
            background-color: #ff5252;
        }

        .grilles-container {
            display: flex;
            gap: 40px;
            flex-wrap: wrap;
        }

        .grille-section {
            flex: 1;
            min-width: 400px;
        }

        .grille-section h3 {
            margin-top: 0;
        }

        table {
            border-collapse: collapse;
            margin-top: 10px;
        }

        table,
        thead,
        tbody,
        tr,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            text-align: center;
            padding: 10px;
            width: 30px;
            height: 30px;
        }

        th {
            background-color: rgb(200, 200, 200);
        }

        /* Couleurs pour ma grille */
        td.navire {
            background-color: #ffa500;
            font-weight: bold;
        }

        /* Couleurs pour les tirs */
        td.touche {
            background-color: #ff0000;
            color: white;
            font-weight: bold;
        }

        td.rate {
            background-color: #87ceeb;
        }

        td.clickable {
            cursor: pointer;
        }

        td.clickable:hover {
            background-color: #ddd;
        }

        form {
            display: inline;
        }

        input[type="hidden"] {
            display: none;
        }
    </style>
</head>

<body>
    <h1>Bataille Navale</h1>

    <div class="info">
        <p><strong>Vous êtes : <?= htmlspecialchars($myRole) ?></strong></p>
        <p>
            Joueur 1 : <?= $etat["j1"] ? "🔴 Connecté" : "🟢 En attente" ?><br>
            Joueur 2 : <?= $etat["j2"] ? "🔴 Connecté" : "🟢 En attente" ?>
        </p>
    </div>

    <form method="post">
        <button type="submit" name="disconnect" class="disconnect-btn">
            ❌ Arrêter la partie
        </button>
    </form>

    <?php if ($etat["j1"] !== null && $etat["j2"] !== null): ?>
        <div class="grilles-container">
            <!-- Ma grille -->
            <div class="grille-section">
                <h3>Ma grille (<?= htmlspecialchars($myRole) ?>)</h3>
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
                                    <td class="<?= $grilles[$myPlayer]["grille"][$i][$j] > 0 ? 'navire' : '' ?>"></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Grille adverse (tirs) -->
            <div class="grille-section">
                <h3>Grille adverse (<?= htmlspecialchars($myRole === "Joueur 1" ? "Joueur 2" : "Joueur 1") ?>)</h3>
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
                                    <td class="clickable <?= 
                                        $grilles[$myPlayer]["tirs"][$i][$j] === 1 ? 'touche' : 
                                        ($grilles[$myPlayer]["tirs"][$i][$j] === 0 ? 'rate' : '')
                                    ?>" onclick="tirer(<?php echo $i; ?>, <?php echo $j; ?>)"></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <form method="post" id="tirer-form" style="display:none;">
                    <input type="hidden" id="tirer-row" name="row">
                    <input type="hidden" id="tirer-col" name="col">
                    <button type="submit" name="tirer">Tirer</button>
                </form>
            </div>
        </div>
    <?php else: ?>
        <p style="color: orange;"><strong>⏳ En attente du deuxième joueur...</strong></p>
    <?php endif; ?>

    <script>
        function tirer(row, col) {
            document.getElementById('tirer-row').value = row;
            document.getElementById('tirer-col').value = col;
            document.getElementById('tirer-form').submit();
        }

        setInterval(() => location.reload(), 5000);
    </script>
</body>

</html>