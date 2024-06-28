<?php

function executeSQLFiles($pdo, $directory)
{
    // Get all .sql files in the directory
    $files = glob($directory . '/*.sql');

    // Sort files by name
    sort($files);

    foreach ($files as $file) {
        $sql = file_get_contents($file);
        try {
            $pdo->exec($sql);
            echo "Executed: $file\n";
        } catch (PDOException $e) {
            echo "Error executing $file: " . $e->getMessage() . "\n";
        }
    }
}

function getExecutedSeeders($file)
{
    if (!file_exists($file)) {
        return [];
    }
    return file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

function recordExecutedSeeder($file, $seeder)
{
    file_put_contents($file, $seeder . PHP_EOL, FILE_APPEND);
}

function getDSN($config)
{
    switch ($config['driver']) {
        case 'mysql':
        case 'mariadb':
            return "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
        case 'pgsql':
            return "pgsql:host={$config['host']};dbname={$config['database']};options='--client_encoding={$config['charset']}'";
        case 'sqlsrv':
            return "sqlsrv:Server={$config['host']};Database={$config['database']}";
        case 'oci':
            return "oci:dbname=//{$config['host']}/{$config['database']};charset={$config['charset']}";
        default:
            throw new Exception("Unsupported database driver: {$config['driver']}");
    }
}

require_once __DIR__ . '/autoload.php';
$config = new bin\Config(__DIR__ . '/.env');

$config = [
    "driver" => getenv("db_driver"),
    "host" => getenv("db_server"),
    "username" => getenv("db_username"),
    "password" => getenv("db_password"),
    "database" => getenv("db_name"),
    "charset" => getenv("db_charset")
];

try {
    $dsn = getDSN($config);
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    // Execute DDL files
    executeSQLFiles($pdo, './sql/tables');
    executeSQLFiles($pdo, './sql/views');
    executeSQLFiles($pdo, './sql/procedures');
    executeSQLFiles($pdo, './sql/functions');

    // Execute DML files in seeders
    $executedSeeders = getExecutedSeeders('./sql/migrations.txt');
    $seederFiles = glob('./sql/seeders/*.sql');
    sort($seederFiles);

    foreach ($seederFiles as $seederFile) {
        if (!in_array(basename($seederFile), $executedSeeders)) {
            $sql = file_get_contents($seederFile);
            try {
                $pdo->exec($sql);
                echo "Executed seeder: $seederFile\n";
                recordExecutedSeeder('./sql/migrations.txt', basename($seederFile));
            } catch (PDOException $e) {
                echo "Error executing seeder $seederFile: " . $e->getMessage() . "\n";
            }
        } else {
            echo "Skipping already executed seeder: $seederFile\n";
        }
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
