<?php
  if (isset($_POST["reset_total"])) {
    include('./destory_session.php');

    header("Location: ../index.php");
    exit;
  }