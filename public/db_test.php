<?php
echo "Teste de conexão com o banco de dados<br>";

$envFile = file_get_contents(dirname(__DIR__) . '/.env');
$lines = explode("\n", $envFile);
$dbUrl = '';

foreach ($lines as $line) {
    if (strpos($line, 'DATABASE_URL') === 0) {
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $dbUrl = trim($parts[1]);
            $dbUrl = str_replace('"', '', $dbUrl);
        }
    }
}

echo "URL do banco: " . htmlspecialchars($dbUrl) . "<br>";

// Tentar fazer conexão
if (strpos($dbUrl, 'sqlite') === 0) {
    // Conexão SQLite
    $dbPath = str_replace('sqlite:///%kernel.project_dir%/', dirname(__DIR__) . '/', $dbUrl);
    echo "Caminho do SQLite: " . htmlspecialchars($dbPath) . "<br>";
    
    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        echo "Conexão SQLite estabelecida com sucesso!<br>";
        
        // Verificar tabelas
        $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
        echo "Tabelas encontradas: " . implode(', ', $tables) . "<br>";
        
    } catch (PDOException $e) {
        echo "Erro ao conectar no SQLite: " . $e->getMessage() . "<br>";
    }
} elseif (strpos($dbUrl, 'mysql') === 0) {
    // Conexão MySQL
    $pattern = '/mysql:\/\/([^:]+):([^@]*)@([^:]+):(\d+)\/([^?]+)/';
    preg_match($pattern, $dbUrl, $matches);
    
    if (count($matches) >= 6) {
        $user = $matches[1];
        $pass = $matches[2];
        $host = $matches[3];
        $port = $matches[4];
        $dbname = $matches[5];
        
        echo "Tentando conectar ao MySQL: host=$host, port=$port, dbname=$dbname, user=$user<br>";
        
        try {
            $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
            echo "Conexão MySQL estabelecida com sucesso!<br>";
            
            // Listar tabelas
            $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
            echo "Tabelas encontradas: " . implode(', ', $tables) . "<br>";
            
        } catch (PDOException $e) {
            echo "Erro ao conectar no MySQL: " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Formato da URL do MySQL inválido<br>";
    }
} else {
    echo "Tipo de banco de dados não suportado neste teste<br>";
}
?>