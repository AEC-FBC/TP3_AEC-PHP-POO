<?php

namespace App\Models;

use PDO;

class Task extends CRUD
{
    protected string $table = "task";

    protected array $fillable = [
        "project_id",
        "status_id",
        "title",
        "description",
        "planned_start_date",
        "planned_end_date",
    ];

    public function selectWithRelations(): array
    {
        $sql = "SELECT
                    task.*,
                    project.title AS project_title,
                    status.name AS status_name
                FROM task
                INNER JOIN project ON project.id = task.project_id
                INNER JOIN status ON status.id = task.status_id
                ORDER BY task.id DESC";

        return $this->query($sql)->fetchAll();
    }

    public function selectIdWithRelations(int $id): array|false
    {
        $sql = "SELECT
                    task.*,
                    project.title AS project_title,
                    status.name AS status_name
                FROM task
                INNER JOIN project ON project.id = task.project_id
                INNER JOIN status ON status.id = task.status_id
                WHERE task.id = :id";

        $stmt = $this->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }
}
