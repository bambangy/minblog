<?php

namespace bin;

use PDO;
use PDOException;
use Exception;

class Database
{
    private $pdo;
    private $stmt;
    private $error;

    public function __construct($config)
    {
        $dbConfig = $config;

        $dsn = $this->buildDsn($dbConfig);
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    private function buildDsn($dbConfig)
    {
        switch ($dbConfig['driver']) {
            case 'mysql':
            case 'mariadb':
                return "mysql:host={$dbConfig['server']};dbname={$dbConfig['database']};charset={$dbConfig['charset']}";
            case 'pgsql':
                return "pgsql:host={$dbConfig['server']};dbname={$dbConfig['database']};options='--client_encoding={$dbConfig['charset']}'";
            case 'sqlsrv':
                return "sqlsrv:Server={$dbConfig['server']};Database={$dbConfig['database']}";
            case 'oci':
                return "oci:dbname=//{$dbConfig['server']}/{$dbConfig['database']};charset={$dbConfig['charset']}";
            default:
                throw new Exception("Unsupported database driver: {$dbConfig['driver']}");
        }
    }

    public function query(string $sql): void
    {
        $this->stmt = $this->pdo->prepare($sql);
    }

    public function bind(string $param, $value, ?int $type = null): void
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute(): bool
    {
        return $this->stmt->execute();
    }

    public function resultSet(): array
    {
        $this->execute();
        return $this->stmt->fetchAll();
    }

    public function single(): ?array
    {
        $this->execute();
        return $this->stmt->fetch() ?: null;
    }

    public function rowCount(): int
    {
        return $this->stmt->rowCount();
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function endTransaction(): bool
    {
        return $this->pdo->commit();
    }

    public function cancelTransaction(): bool
    {
        return $this->pdo->rollBack();
    }
}
