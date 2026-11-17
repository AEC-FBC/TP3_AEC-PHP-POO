<?php

namespace App\Models;

use PDO;
use LogicException;
use InvalidArgumentException;

abstract class CRUD extends PDO
{
    protected string $table;
    protected ?string $primaryKey = "id";
    protected array $fillable = [];

    public function __construct()
    {
        $dsn = "mysql:host=" . DB_HOST
            . ";port=" . DB_PORT
            . ";dbname=" . DB_NAME
            . ";charset=utf8mb4";

        parent::__construct(
            $dsn,
            DB_USER,
            DB_PASSWORD,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    public function select(): array
    {
        $stmt = $this->query("SELECT * FROM {$this->table}");

        return $stmt->fetchAll();
    }

    public function selectId(int $id): array|false
    {
        $this->checkPrimaryKey();

        $sql = "SELECT * FROM {$this->table}
                WHERE {$this->primaryKey} = :id";

        $stmt = $this->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function insert(array $data): string|false
    {
        $data = $this->filterData($data);

        $columns = implode(", ", array_keys($data));
        $parameters = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO {$this->table} ($columns)
                VALUES ($parameters)";

        $stmt = $this->prepare($sql);

        if (!$stmt->execute($data)) {
            return false;
        }

        return $this->lastInsertId();
    }

    public function update(array $data, int $id): bool
    {
        $this->checkPrimaryKey();

        $data = $this->filterData($data);
        $assignments = [];

        foreach ($data as $field => $value) {
            $assignments[] = "$field = :$field";
        }

        $sql = "UPDATE {$this->table} SET "
            . implode(", ", $assignments)
            . " WHERE {$this->primaryKey} = :where_id";

        $stmt = $this->prepare($sql);

        foreach ($data as $field => $value) {
            $type = $value === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR;

            $stmt->bindValue(":$field", $value, $type);
        }

        $stmt->bindValue(":where_id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete(int $id): bool
    {
        $this->checkPrimaryKey();

        $sql = "DELETE FROM {$this->table}
                WHERE {$this->primaryKey} = :id";

        $stmt = $this->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    private function filterData(array $data): array
    {
        $allowed = array_fill_keys($this->fillable, true);
        $data = array_intersect_key($data, $allowed);

        if ($data === []) {
            throw new InvalidArgumentException(
                "Aucun champ autorisé à enregistrer."
            );
        }

        return $data;
    }

    private function checkPrimaryKey(): void
    {
        if ($this->primaryKey === null) {
            throw new LogicException(
                "Cette table ne possède pas de clé primaire simple."
            );
        }
    }
}
