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
//colonnes par lignes
$colsPerRow = 10;

//victoire
$sql_victory = new SqlConnect();
//compte cases touchées joueur 2 sur joueur 1
$query1 = "SELECT COUNT(*) as touches FROM joueur2 WHERE checked = 1 AND boat > 0";
$req1 = $sql_victory->db->query($query1);
$touches_j1 = $req1->fetch(PDO::FETCH_ASSOC)['touches'];
//inversement
$query2 = "SELECT COUNT(*) as touches FROM joueur1 WHERE checked = 1 AND boat > 0";
$req2 = $sql_victory->db->query($query2);
$touches_j2 = $req2->fetch(PDO::FETCH_ASSOC)['touches'];


// Vérifier si c'est le tour du joueur actuel
$is_my_turn = ($_SESSION["role"] === $current_turn);


//condition victoire
if ($touches_j1 >= 6) {
  echo '<div class="victory">
        VICTOIRE DU JOUEUR 1
        </div>';
}

if ($touches_j2 >= 6) {
  echo '<div class="victory">
        VICTOIRE DU JOUEUR 2
        </div>';
}
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

    <div class="turn-indicator <?php echo $is_my_turn ? 'my-turn' : 'not-my-turn'; ?>">
      <?php if ($is_my_turn): ?>
        🎯 C'est votre tour ! (<?php echo $_SESSION["role"]; ?>)
      <?php else: ?>
        ⏳ En attente du tour de <?php echo $current_turn; ?>
      <?php endif; ?>
    </div>

    <div class="d-flex justify-content-center gap-5">

      <div>
        <h3>Grille adverse</h3>
        
        <?php
        if (isset($_SESSION['message'])) {
            echo "<p class='message'>" . $_SESSION['message'] . "</p>";
            unset($_SESSION['message']); // pour afficher qu’une seule fois
        }

        for ($i = 0; $i < count($rows); $i += $colsPerRow) {
        //crée lignes
          echo '<div class="row">';
          for ($j = 0; $j < $colsPerRow; $j++) {
          //crée colonnes dans lignes
            if (isset($rows[$i + $j])) {
              $case = $rows[$i + $j];
              //case entre dans BDD
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
    </div>

  </div>
  <form method="post" action="../scripts/reset_total.php">
    <button type="submit" name="reset_total" class="button">
      ❌ RESET
    </button>
  </form>
</body>

</html>