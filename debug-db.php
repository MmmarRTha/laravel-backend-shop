<?php
// This script helps debug database connection issues on Render

echo "Starting database connection test...\n";

// Output environment variables (without showing passwords)
echo "\nEnvironment Variables:\n";
echo "DB_CONNECTION: " . getenv('DB_CONNECTION') . "\n";
echo "DB_HOST: " . getenv('DB_HOST') . "\n";
echo "DB_PORT: " . getenv('DB_PORT') . "\n";
echo "DB_DATABASE: " . getenv('DB_DATABASE') . "\n";
echo "DB_USERNAME: " . getenv('DB_USERNAME') . "\n";
echo "DB_PASSWORD: " . (getenv('DB_PASSWORD') ? '[SET]' : '[NOT SET]') . "\n";

// Attempt to connect using PDO directly
try {
    echo "\nAttempting direct PDO connection...\n";
    
    $dsn = getenv('DB_CONNECTION') . ':host=' . getenv('DB_HOST') . 
           ';port=' . getenv('DB_PORT') . 
           ';dbname=' . getenv('DB_DATABASE');
    
    $pdo = new PDO(
        $dsn,
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD')
    );
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "PDO connection successful!\n";
    
    // List tables
    echo "\nExisting tables:\n";
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (count($tables) > 0) {
        foreach ($tables as $table) {
            echo "- " . $table . "\n";
        }
    } else {
        echo "No tables found in the database.\n";
    }
    
} catch (PDOException $e) {
    echo "PDO connection failed: " . $e->getMessage() . "\n";
}

echo "\nTest complete.\n";
