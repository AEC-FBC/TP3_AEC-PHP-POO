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
}
