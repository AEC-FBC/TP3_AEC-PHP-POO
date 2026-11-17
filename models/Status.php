<?php

namespace App\Models;

class Status extends CRUD
{
    protected string $table = "status";

    protected array $fillable = [
        "name",
        "color",
        "display_order",
    ];

    public function select(): array
    {
        return $this->query(
            "SELECT * FROM status ORDER BY display_order, name"
        )->fetchAll();
    }
}
