<?php

namespace App\Models;

class VisitLog extends CRUD
{
    protected string $table = "visit_log";

    protected array $fillable = [
        "ip_address",
        "username",
        "page_url",
    ];
    public function getRecentVisits(): array
    {
        return $this->query(
            "SELECT * FROM visit_log ORDER BY visited_at DESC, id DESC LIMIT 100"
        )->fetchAll();
    }
}
