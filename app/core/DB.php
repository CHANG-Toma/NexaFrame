<?php

namespace App\Core;

use PDO;
use PDOException;

use App\Controllers\Installer;

class DB
{
    private ?object $pdo = null;
    private array $tableMapping = [
        // 'class_name' => 'table_name'
        'User' => 'users',
        'Page' => 'pages',
        'Setting' => 'settings',
    ];
    private string $tableName = '';
    private static ?self $instance = null;

    public function __construct()
    {
        $dsn = new Installer();

        try {
            include_once '../app/config/config.php'; //on inclut le fichier de configuration
            $this->pdo = new PDO($dsn->getDsnFromDbType(DB_TYPE), DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            die();
        }
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) { //si l'instance n'existe pas
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function save(): void
    {
        $data = $this->getDataObject();

        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $data[$key] = $value ? 'true' : 'false';    //si c'est un booléen, on le transforme en string
            }
        }

        $className = basename(str_replace('\\', '/', get_class($this)));    //basename retourne le nom du fichier sans l'extension
        $tableName = $this->getTableNameByClassName($className);

        if (empty($tableName)) {
            throw new \Exception('Table name is not defined');
        } else {
            $params = [];
            if (empty($this->getId())) {
                $sql = 'INSERT INTO ' . "" . $tableName . ' (' . implode(',', array_keys($data)) . ') VALUES (:' . implode(',:', array_keys($data)) . ');';
                foreach ($data as $key => $value) {
                    $params[":$key"] = $value;
                }
            } else {
                $sql = "UPDATE " . "" . $tableName . " SET ";
                foreach ($data as $column => $value) {
                    $sql .= $column . "=:" . $column . ",";
                    $params[":$column"] = $value;
                }
                $sql = substr($sql, 0, -1);
                $sql .= " WHERE id = " . $this->getId() . ";";
            }
            $this->exec($sql, $params);
        }
    }

    public function getOneBy(array $conditions)
    {
        $className = basename(str_replace('\\', '/', get_class($this)));
        $tableName = $this->getTableNameByClassName($className);

        $sql = "SELECT * FROM $tableName WHERE ";
        $params = [];

        foreach ($conditions as $column => $value) {
            $sql .= "$column = :$column AND ";
            $params[":$column"] = $value;
        }
        $sql = rtrim($sql, 'AND ');

        $data = $this->exec($sql, $params);

        if ($data) {
            return $data;
        } else {
            return false;
        }
    }

    public function populate(array $data): void
    {
        for ($i = 0; $i < count($data); $i++) {
            foreach ($data[$i] as $key => $value) {
                $methodName = "set" . ucfirst($key); 
                if (method_exists($this, $methodName)) {
                    $this->$methodName($value);
                }
            }
        }
    }


    public function exec(string $query, array $params = [], string $returnType = "array")
    {
        if ($this->pdo) {
            $statement = $this->pdo->prepare($query);

            foreach ($params as $param => $value) {
                $statement->bindValue($param, $value);
            }

        } else {
            $db = $this->getInstance();

            $statement = $db->pdo->prepare($query);
            foreach ($params as $param => $value) {
                $statement->bindValue($param, $value);
            }

        }
        try {
            $statement->execute();
            if ($returnType === "array") {
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // ---------------------------------------------------------------

    private function getTableNameByClassName(string $className): string
    {
        return $this->tableMapping[$className];
    }

    public function testConnection(): bool
    {
        try {
            $this->pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    private function getDataObject(): array
    {
        $data = get_object_vars($this);
        unset($data['pdo']);
        unset($data['table']);
        unset($data['tableMapping']);
        unset($data['tableName']);
        return $data;
    }
}

