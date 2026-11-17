<?php

namespace App\Controllers;

use App\Models\VisitLog;
use App\Providers\View;

class VisitLogController
{
    public function index(): void
    {
        View::render("admin/logs", [
            "pageTitle" => "Journal de bord",
            "visits" => (new VisitLog())->getRecentVisits(),
        ]);
    }
}
