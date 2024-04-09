<?php

namespace App\Core;

use PDO;
use PDOException;

use App\Controllers\Installer;
use App\Controllers\Error;

class DB
{
    private ?object $pdo = null;
    private array $tableMapping = [
        // 'class_name' => 'table_name'
        'User' => 'users',
        'Page' => 'pages',
        'Setting' => 'settings',
        'Comment' => 'comments',
        'Article' => 'articles',
        'Category' => 'categories',
    ];
    private string $tableName = '';
    private static ?self $instance = null;

    public function __construct()
    {
        $dsn = new Installer();

        // Vérifie si la connexion est établie
        try {
            include_once '../app/config/config.php'; //on inclut le fichier de configuration
            $this->pdo = new PDO($dsn->getDsnFromDbType(DB_TYPE), DB_USER, DB_PASSWORD);
            
        } catch (PDOException $e) {
            $error = "Connexion échouée";
            header('Location: /installer');
            die();
        }
    }

    // Singleton : évite d'instancier la classe plusieurs fois
    public static function getInstance(): self
    {
        if (self::$instance === null) { //si l'instance n'existe pas
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Permet d'effectuer une sauvegarde ou une mise à jour
    public function save(): void
    {
        $data = $this->getDataObject();

        // Convertit les booléens en chaînes de caractères
        foreach ($data as $key => $value) {
            if (is_bool($value)) {
                $data[$key] = $value ? 'true' : 'false';
            }
        }

        // Récupère le nom de la classe et le nom de la table
        $className = basename(str_replace('\\', '/', get_class($this)));
        $tableName = $this->getTableNameByClassName($className);

        if (empty($tableName)) {
            throw new \Exception('Table name is not defined');
        } else {
            $params = [];
            // Si l'id n'est pas défini, on insère une nouvelle ligne
            if (empty($this->getId())) {
                $sql = 'INSERT INTO ' . "" . $tableName . ' (' . implode(',', array_keys($data)) . ') VALUES (:' . implode(',:', array_keys($data)) . ');';
                foreach ($data as $key => $value) {
                    $params[":$key"] = $value;
                }
            } else {
                // Sinon, on met à jour la ligne existante
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

    // Récupère un enregistrement en fonction de certains critères
    public function getOneBy(array $conditions)
    {
        // Récupère le nom de la classe et le nom de la table
        $className = basename(str_replace('\\', '/', get_class($this)));
        $tableName = $this->getTableNameByClassName($className);

        $sql = "SELECT * FROM $tableName WHERE ";
        $params = [];

        // Parcours les conditions
        foreach ($conditions as $column => $value) {
            if (is_array($value) && isset($value['operator'])) { //ne pas oublier de vérifier si l'opérateur est défini et doit etre un tableau
                $sql .= "$column {$value['operator']} AND ";
                if ($value['operator'] != 'IS NULL' && $value['operator'] != 'IS NOT NULL') {
                    $params[":$column"] = $value['value']; // Si l'opérateur nécessite une valeur (ex : '=', '!='), ajoutez-la aux paramètres

                }
            } else {
                // Traitement normal
                $sql .= "$column = :$column AND ";
                $params[":$column"] = $value;
            }
        }
        $sql = rtrim($sql, 'AND ');

        $data = $this->exec($sql, $params);

        if ($data) {
            return $data;
        } else {
            return false;
        }
    }

    // Récupère un enregistrement en fonction de son id
    public function populate(array $data): void
    {
        // Parcours les données et les attribue aux propriétés de l'objet
        for ($i = 0; $i < count($data); $i++) {
            foreach ($data[$i] as $key => $value) {
                $methodName = "set" . ucfirst($key);
                if (method_exists($this, $methodName)) {
                    $this->$methodName($value);
                }
            }
        }
    }

    // Récupère tous les enregistrements de la table associée à la classe
    public function getAll(): array
    {
        // Récupère le nom de la classe et le nom de la table
        $className = basename(str_replace('\\', '/', get_class($this)));
        $tableName = $this->getTableNameByClassName($className);

        // Récupère tous les enregistrements
        $data = $this->exec("SELECT * FROM $tableName;");
        if ($data) {
            return $data;
        } else {
            return [];
        }
    }

    // Récupère tous les enregistrements en fonction de certains critères
    public function getAllBy(array $conditions): array
    {
        $data = $this->getOneBy($conditions);
        if (!empty($data)) {
            return $data;
        } else {
            return [];
        }
    }

    // exécute une requête SQL en fonction des paramètres passés
    public function exec(string $query, array $params = [], string $returnType = "array")
    {
        // Vérifie si la connexion est établie
        if ($this->pdo) {
            $statement = $this->pdo->prepare($query);

            // Parcours les paramètres et les attribue à la requête
            foreach ($params as $param => $value) {
                $statement->bindValue($param, $value);
            }

        } else {
            // Si la connexion n'est pas établie, on la crée
            $db = $this->getInstance();

            // Prépare la requête
            $statement = $db->pdo->prepare($query);
            foreach ($params as $param => $value) {
                $statement->bindValue($param, $value);
            }

        }
        try {
            // Exécute la requête
            $statement->execute();
            if ($returnType === "array") {
                return $statement->fetchAll(PDO::FETCH_ASSOC);
            }
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Supprime un enregistrement en fonction de son id
    public function delete(int $id): void
    {
        // Récupère le nom de la classe et le nom de la table
        $className = basename(str_replace('\\', '/', get_class($this)));
        $tableName = $this->getTableNameByClassName($className);
        // Exécute la requête
        $this->exec("DELETE FROM $tableName WHERE id = $id;");
    }

    public function deleteAllBy(array $conditions): void
    {
        // Récupère le nom de la classe et le nom de la table
        $className = basename(str_replace('\\', '/', get_class($this)));
        $tableName = $this->getTableNameByClassName($className);

        $sql = "DELETE FROM $tableName WHERE ";
        $params = [];

        // Parcours les conditions
        foreach ($conditions as $column => $value) {
            if (is_array($value) && isset($value['operator'])) {
                $sql .= "$column {$value['operator']} AND ";
                if ($value['operator'] != 'IS NULL' && $value['operator'] != 'IS NOT NULL') {
                    $params[":$column"] = $value['value'];
                }
            } else {
                $sql .= "$column = :$column AND ";
                $params[":$column"] = $value;
            }
        }
        $sql = rtrim($sql, 'AND ');

        $this->exec($sql, $params);
    }

    // Supprime les enregistrements marqués comme supprimés
    public function softDelete(): void
    {
        // Récupère le nom de la classe et le nom de la table
        $className = basename(str_replace('\\', '/', get_class($this)));
        $tableName = $this->getTableNameByClassName($className);
        // Exécute la requête
        $this->exec("DELETE FROM $tableName WHERE deleted_at IS NOT NULL AND deleted_at < NOW()");
    }

    // ---------------------------------------------------------------

    // Récupère le nom de la table en fonction de la classe passée en paramètre
    private function getTableNameByClassName(string $className): string
    {
        return $this->tableMapping[$className];
    }

    // test la connexion
    public function testConnection(): bool
    {
        // Vérifie si la connexion est établie
        try {
            $this->pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupère les données de l'objet
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

