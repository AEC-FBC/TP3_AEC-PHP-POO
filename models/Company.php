<?php

namespace App\Models;

class Company extends CRUD
{
    protected string $table = "company";

    protected array $fillable = [
        "name",
        "website",
    ];

    public function getLocations(int $companyId): array
    {
        $statement = $this->prepare(
            "SELECT *
             FROM company_locations
             WHERE company_id = :company_id
             ORDER BY name"
        );

        $statement->execute(["company_id" => $companyId]);

        return $statement->fetchAll();
    }

    public function getContacts(int $companyId): array
    {
        $statement = $this->prepare(
            "SELECT *
             FROM contact
             WHERE company_id = :company_id
             ORDER BY last_name, first_name"
        );

        $statement->execute(["company_id" => $companyId]);

        return $statement->fetchAll();
    }

    public function getProjects(int $companyId): array
    {
        $statement = $this->prepare(
            "SELECT project.*, status.name AS status_name
             FROM project
             INNER JOIN status ON status.id = project.status_id
             WHERE project.company_id = :company_id
             ORDER BY project.id DESC"
        );

        $statement->execute(["company_id" => $companyId]);

        return $statement->fetchAll();
    }
}
