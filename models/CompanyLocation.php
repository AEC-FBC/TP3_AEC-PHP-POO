<?php

namespace App\Models;

class CompanyLocation extends CRUD
{
    protected string $table = "company_locations";

    protected array $fillable = [
        "company_id",
        "name",
        "address",
        "unit",
        "postal_code",
        "country",
        "region",
        "city",
        "phone",
        "email",
    ];

    public function selectWithRelations(): array
    {
        $sql = "
            SELECT company_locations.*,
                   company.name AS company_name
            FROM company_locations
            INNER JOIN company
                ON company.id = company_locations.company_id
            ORDER BY company.name, company_locations.name
        ";

        return $this->query($sql)->fetchAll();
    }
    public function hasDependencies(int $id): bool
    {
        $sql = "
        SELECT
            (SELECT COUNT(*)
             FROM contact
             WHERE company_location_id = :contact_location)
            +
            (SELECT COUNT(*)
             FROM project
             WHERE company_location_id = :project_location)
    ";

        $statement = $this->prepare($sql);

        $statement->execute([
            "contact_location" => $id,
            "project_location" => $id,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }
}
