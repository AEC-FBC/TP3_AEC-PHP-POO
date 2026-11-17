<?php

namespace App\Models;

use PDO;

class Project extends CRUD
{
    protected string $table = "project";

    protected array $fillable = [
        "company_id",
        "company_location_id",
        "primary_contact_id",
        "status_id",
        "title",
        "description",
        "planned_start_date",
        "planned_end_date",
        "image_path",
    ];

    public function selectWithRelations(): array
    {
        $sql = "
            SELECT project.*,
                   company.name AS company_name,
                   status.name AS status_name
            FROM project
            INNER JOIN company ON company.id = project.company_id
            INNER JOIN status ON status.id = project.status_id
            ORDER BY project.id DESC
        ";

        return $this->query($sql)->fetchAll();
    }

    public function getServices(int $projectId): array
    {
        $sql = "
            SELECT service.*
            FROM service
            INNER JOIN project_service
                ON project_service.service_id = service.id
            WHERE project_service.project_id = :project_id
            ORDER BY service.name
        ";

        $statement = $this->prepare($sql);
        $statement->execute(["project_id" => $projectId]);

        return $statement->fetchAll();
    }

    public function getTasks(int $projectId): array
    {
        $sql = "
            SELECT task.*, status.name AS status_name
            FROM task
            INNER JOIN status ON status.id = task.status_id
            WHERE task.project_id = :project_id
            ORDER BY task.id DESC
        ";

        $statement = $this->prepare($sql);
        $statement->execute(["project_id" => $projectId]);

        return $statement->fetchAll();
    }

    public function syncServices(int $projectId, array $serviceIds): void
    {
        $statement = $this->prepare(
            "DELETE FROM project_service WHERE project_id = :project_id"
        );

        $statement->execute(["project_id" => $projectId]);

        $statement = $this->prepare(
            "INSERT INTO project_service (project_id, service_id)
             VALUES (:project_id, :service_id)"
        );

        foreach ($serviceIds as $serviceId) {
            $statement->execute([
                "project_id" => $projectId,
                "service_id" => $serviceId,
            ]);
        }
    }

    public function saveWithServices(
        array $data,
        array $serviceIds,
        ?int $id = null
    ): int {
        $this->beginTransaction();

        try {
            if ($id === null) {
                $newId = $this->insert($data);

                if ($newId === false || (int) $newId < 1) {
                    throw new \RuntimeException("Création du projet impossible.");
                }

                $id = (int) $newId;
            } else {
                if (!$this->update($data, $id)) {
                    throw new \RuntimeException("Modification du projet impossible.");
                }
            }

            $this->syncServices($id, $serviceIds);

            $this->commit();

            return $id;
        } catch (\Throwable $exception) {
            if ($this->inTransaction()) {
                $this->rollBack();
            }

            throw $exception;
        }
    }

    public function deleteWithServices(int $id): void
    {
        $this->beginTransaction();

        try {
            $this->syncServices($id, []);

            if (!$this->delete($id)) {
                throw new \RuntimeException("Suppression du projet impossible.");
            }

            $this->commit();
        } catch (\Throwable $exception) {
            if ($this->inTransaction()) {
                $this->rollBack();
            }

            throw $exception;
        }
    }
}
