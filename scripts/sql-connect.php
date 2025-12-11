<?php

class SqlConnect {
  public object $db;
  private string $host;
  private string $port;
  private string $dbname;
  private string $password;
  private string $user;

  public function __construct() {
    // set your config here
    $this->host = '127.0.0.1';
    $this->port = '8889';   // MAMP default on some installs; verify in MAMP prefs
    $this->dbname = 'bataille_navale';
    $this->user = 'root';
    $this->password = 'root';

    $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->dbname};charset=utf8mb4";

    try {
      $this->db = new PDO($dsn, $this->user, $this->password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
      ]);
      return;
    } catch (PDOException $e) {
      // try a common fallback (localhost:3306)
      $fallbackDsn = "mysql:host=localhost;port=3306;dbname={$this->dbname};charset=utf8mb4";
      try {
        $this->db = new PDO($fallbackDsn, $this->user, $this->password, [
          PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
          PDO::ATTR_PERSISTENT => false,
        ]);
        return;
      } catch (PDOException $e2) {
        // Throw a clearer error for debugging
        throw new PDOException(
          "Database connection failed.\nTried DSNs:\n  $dsn\n  $fallbackDsn\n\n"
          . "Error 1: " . $e->getMessage() . "\n"
          . "Error 2: " . $e2->getMessage()
        );
      }
    }
  }

  public function transformDataInDot($data) {
    $dataFormated = [];

    foreach ($data as $key => $value) {
      $dataFormated[':' . $key] = $value;
    }

    return $dataFormated;
  }
}