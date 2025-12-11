<?php
include('./scripts/sql-connect.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sql = new SqlConnect();
$player = $_SESSION["role"] === 'joueur1' ? 'joueur2' : 'joueur1';
$query = 'SELECT * FROM ' . $player;

$req = $sql->db->prepare($query);
$req->execute();
$rows = $req->fetchAll(PDO::FETCH_ASSOC);

$currentPlayer = $_SESSION["role"];
$query2 = 'SELECT * FROM ' . $currentPlayer;

$req2 = $sql->db->prepare($query2);
$req2->execute();
$myRows = $req2->fetchAll(PDO::FETCH_ASSOC);


// Vérifier si c'est le tour du joueur actuel
$isMyTurn = ($_SESSION["role"] === $currentTurn);

$colsPerRow = 10;
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Game</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link
    href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Sekuya&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" type="text/css" href="/views/style.css" />
</head>

<body>
  <div class="container text-center">

    <div class="turn-indicator <?php echo $isMyTurn ? 'my-turn' : 'not-my-turn'; ?>">
      <?php if ($isMyTurn): ?>
        🎯 C'est votre tour ! (<?php echo $_SESSION["role"]; ?>)
      <?php else: ?>
        ⏳ En attente du tour de <?php echo $currentTurn; ?>
      <?php endif; ?>
    </div>

    <div class="d-flex justify-content-center gap-5">

      <!-- ====== GRILLE DE TIR (adversaire) ====== -->
      <div>
        <h3>Grille adverse</h3>

        <?php
        for ($i = 0; $i < count($rows); $i += $colsPerRow) {
          echo '<div class="row">';
          for ($j = 0; $j < $colsPerRow; $j++) {
            if (isset($rows[$i + $j])) {
              $case = $rows[$i + $j];
              $color = $case['checked'] == 1 ? '#2C38B8' : '#F2EFEB';
              if ($case['checked'] == 1 && $case['boat'] > 0) {
                $color = '#B82C2C';
              }
              echo '<div class="col">';
              echo '<form method="post" action="../scripts/click_case.php">';
              echo '<button type="submit" name="cell" value="' . $case['idgrid'] . '" class="cell" style="background-color:' . $color . ';"></button>';
              echo '</form>';
              echo '</div>';
            }
          }
          echo '</div>';
        }
        ?>
      </div>

      <!-- ====== MA PROPRE GRILLE (bateaux) ====== -->
      <div>
        <h3>Vos bateaux</h3>

        <?php
        for ($i = 0; $i < count($myRows); $i += $colsPerRow) {
          echo '<div class="row">';
          for ($j = 0; $j < $colsPerRow; $j++) {
            if (isset($myRows[$i + $j])) {
              $cell = $myRows[$i + $j];

              // Couleur : bateaux visibles
              $color = $cell['boat'] > 0 ? '#6060FF' : '#F2EFEB'; // bleu clair = bateau
        
              // si touché : rouge
              if ($cell['checked'] == 1 && $cell['boat'] > 0) {
                $color = '#B82C2C';
              }

              echo '<div class="col">';
              echo '<button class="cell" style="background-color:' . $color . ';" disabled></button>';
              echo '</div>';
            }
          }
          echo '</div>';
        }
        ?>

      </div>
    </div>

  </div>
  <form method="post" action="../scripts/reset_total.php">
    <button type="submit" name="reset_total" class="button">
      ❌ RESET
    </button>
  </form>
</body>

</html>