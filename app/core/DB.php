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
    private static ?self $instance = null;

    public function __construct()
    {
        $dsn = new Installer();

        try {
            $this->pdo = new PDO($dsn->getDsnFromDbType(DB_TYPE), DB_USER, DB_PASSWORD);
        } catch (PDOException $e) {
            echo "Erreur SQL : " . $e->getMessage();
            die();
        }
    }

    public function exec(string $query): bool
    {
        if ($this->pdo) {
            try {
                $statement = $this->pdo->prepare($query);
                return $statement->execute();
            } catch (PDOException $e) {
                echo "Erreur SQL : " . $e->getMessage();
                return false;
            }
        }
        else {
            $db = $this->getInstance();
            try {
                $statement = $db->pdo->prepare($query);
                return $statement->execute();
            } catch (PDOException $e) {
                echo "Erreur SQL : " . $e->getMessage();
                return false;
            }
        }
    }

    public function save() : void
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
        } 
        else {
            if (empty($this->getId())) {
                $sql = 'INSERT INTO ' . "" . $tableName . ' (' . implode(',', array_keys($data)) . ') VALUES (:' . implode(',:', array_keys($data)) . ');';
            } else {
                $sql = "UPDATE " . "" . $tableName . " SET ";
                foreach ($data as $column => $value) {
                    $sql .= $column . "=:" . $column . ",";
                }
                $sql = substr($sql, 0, -1);
                $sql .= " WHERE id = " . $this->getId() . ";";
            }
            $this->exec($sql);
        }
    }

    public static function getInstance(): self  
    {
        if (self::$instance === null) { //si l'instance n'existe pas
            include '../app/config/config.php'; //on inclut le fichier de configuration
            self::$instance = new self();
        }
        return self::$instance;
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
            echo "Erreur SQL : " . $e->getMessage();
            return false;
        }
    }

    private function getDataObject()
    {
        $data = get_object_vars($this);
        unset($data['pdo']);
        unset($data['table']);
        unset($data['tableMapping']);
        return $data;
    }
}

