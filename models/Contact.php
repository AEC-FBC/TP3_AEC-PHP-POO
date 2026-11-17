<?php

namespace App\Models;

class Contact extends CRUD
{
    protected string $table = "contact";

    protected array $fillable = [
        "company_id",
        "company_location_id",
        "first_name",
        "last_name",
        "job_title",
        "phone",
        "email",
    ];

    public function selectWithRelations(): array
    {
        $sql = "
            SELECT contact.*,
                   company.name AS company_name,
                   company_locations.name AS location_name
            FROM contact
            INNER JOIN company
                ON company.id = contact.company_id
            LEFT JOIN company_locations
                ON company_locations.id = contact.company_location_id
            ORDER BY contact.last_name, contact.first_name
        ";

        return $this->query($sql)->fetchAll();
    }
}
