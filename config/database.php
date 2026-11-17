<?php

class Database
{
    private string $host = '127.0.0.1';
    private string $dbName = 'db_portal_sdm';
    private string $username = 'root';
    private string $password = '';
    private ?PDO $pdo = null;

    /**
     * Membuka dan mengembalikan instance koneksi PDO.
     * @return PDO
     */
    public function getConnection(): PDO
    {
        if ($this->pdo === null) {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
        }

        return $this->pdo;
    }
}
