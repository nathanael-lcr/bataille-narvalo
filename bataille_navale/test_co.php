<?php
// Teste TOUS les ports possibles
$ports = [3306, 8889, 3307];
$password = 'root';

foreach ($ports as $port) {
    echo "Test du port $port... ";
    try {
        $pdo = new PDO(
            "mysql:host=127.0.0.1;port=$port;dbname=bnavale",
            'root',
            $password
        );
        echo "✅ CONNEXION RÉUSSIE sur le port $port !<br>";
        exit; // On arrête dès que ça marche
    } catch (PDOException $e) {
        echo "❌ Échec<br>";
    }
}

echo "<br>Aucun port ne fonctionne. Vérifie dans phpMyAdmin :<br>";
echo "1. Clique sur l'onglet 'Variables'<br>";
echo "2. Cherche 'port'<br>";
echo "3. Note le numéro affiché<br>";
?>