<?php
  include('./scripts/sql-connect.php');

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  $sql = new SqlConnect();
  $player = $_SESSION["role"] === 'joueur1' ?  'joueur2' : 'joueur1';
  $query = 'SELECT * FROM '.$player;

  $req = $sql->db->prepare($query);
  $req->execute();
  $rows = $req->fetchAll(PDO::FETCH_ASSOC);
  
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/views/style.css" />
  </head>
  <body>
    <div class="container text-center">
      <?php
        for ($i = 0; $i < count($rows); $i += $colsPerRow) {
          echo '<div class="row">';
          for ($j = 0; $j < $colsPerRow; $j++) {
              if (isset($rows[$i + $j])) {
                  $case = $rows[$i + $j];
                  $color = $case['checked'] == 1 ? 'blue' : 'white';
                  if ($case['checked'] == 1 && $case['boat'] > 0) {
                    $color = 'red';
                  }            

                  $idgrid = $case['idgrid'];

                  echo '<div class="col">';
                  echo '<form method="post" action="../scripts/click_case.php" class=form>';
                  echo '<button type="submit" name="cell" value="'.$idgrid.'" class="cell" style="background-color:'.$color.';"></button>';
                  echo '</form>';
                  echo '</div>';
              }
          }
          echo '</div>';
      }
    ?>
    </div>
    <form method="post" action="../scripts/reset_total.php">
      <button type="submit" name="reset_total" class="button">
        ❌ Fin de partie (RESET)
      </button>
    </form>
  </body>
</html>