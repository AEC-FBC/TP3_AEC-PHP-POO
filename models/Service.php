<?php

namespace App\Models;

class Service extends CRUD
{
    protected string $table = "service";

    protected array $fillable = [
        "name",
        "description",
    ];
}
