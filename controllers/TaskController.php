<?php

namespace App\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\Status;
use App\Providers\Validator;
use App\Providers\View;

class TaskController
{
    public function index(array $query = []): void
    {
        $model = new Task();

        View::render("task/index", [
            "pageTitle" => "Tâches",
            "tasks" => $model->selectWithRelations(),
            "notice" => $this->getNotice($query),
        ]);
    }

    public function show(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Task();
        $task = $model->selectIdWithRelations($id);

        if ($task === false) {
            $this->error(404, "La tâche est introuvable.");

            return;
        }

        View::render("task/show", [
            "fromProject" => ($query["from"] ?? "") === "project",
            "fromCompany" => ($query["parent"] ?? "") === "company",
            "pageTitle" => "Fiche de la tâche",
            "task" => $task,
            "notice" => $this->getNotice($query),
        ]);
    }

    public function create(array $query = []): void
    {
        $this->renderForm([
            "project_id" => "",
            "status_id" => "",
            "title" => "",
            "description" => "",
            "planned_start_date" => "",
            "planned_end_date" => "",
        ]);
    }

    public function store(
        array $input,
        array $query = []
    ): void {
        $values = $this->prepareValues($input);
        $errors = $this->validate($values);

        if ($errors !== []) {
            http_response_code(422);
            $this->renderForm($values, $errors);

            return;
        }

        $model = new Task();
        $id = $model->insert($this->prepareDatabaseValues($values));

        if ($id === false || $id === "0") {
            throw new \RuntimeException(
                "Impossible de récupérer la tâche créée."
            );
        }

        View::redirect("tasks/show?id=" . $id . "&notice=created");
    }

    public function edit(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Task();
        $task = $model->selectId($id);

        if ($task === false) {
            $this->error(404, "La tâche est introuvable.");

            return;
        }

        $this->renderForm(
            $this->prepareValues($task),
            [],
            $id,
            ($query["from"] ?? "") === "project",
            ($query["parent"] ?? "") === "company"
        );
    }

    public function update(
        array $input,
        array $query = []
    ): void {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Task();

        if ($model->selectId($id) === false) {
            $this->error(404, "La tâche est introuvable.");

            return;
        }

        $values = $this->prepareValues($input);
        $errors = $this->validate($values);

        if ($errors !== []) {
            http_response_code(422);
            $this->renderForm(
                $values,
                $errors,
                $id,
                ($query["from"] ?? "") === "project",
                ($query["parent"] ?? "") === "company"
            );

            return;
        }

        $success = $model->update(
            $this->prepareDatabaseValues($values),
            $id
        );

        if (!$success) {
            throw new \RuntimeException(
                "Impossible de modifier la tâche."
            );
        }

        View::redirect(
            "tasks/show?id=" . $id . "&notice=updated"
                . (($query["from"] ?? "") === "project" ? "&from=project" : "")
                . (($query["parent"] ?? "") === "company" ? "&parent=company" : "")
        );
    }

    public function delete(
        array $input,
        array $query = []
    ): void {
        $id = $this->getId($input["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Task();

        if ($model->selectId($id) === false) {
            $this->error(404, "La tâche est introuvable.");

            return;
        }

        if (!$model->delete($id)) {
            throw new \RuntimeException(
                "Impossible de supprimer la tâche."
            );
        }

        View::redirect("tasks?notice=deleted");
    }

    private function prepareValues(array $input): array
    {
        $fields = [
            "project_id",
            "status_id",
            "title",
            "description",
            "planned_start_date",
            "planned_end_date",
        ];

        $values = [];

        foreach ($fields as $field) {
            $value = $input[$field] ?? "";

            if (is_string($value)) {
                $values[$field] = trim($value);
            } elseif (is_int($value)) {
                $values[$field] = (string) $value;
            } else {
                $values[$field] = $value;
            }
        }

        return $values;
    }

    private function validate(array $values): array
    {
        $validator = new Validator();

        $validator
            ->field("title", $values["title"], "Le titre")
            ->required()
            ->min(3)
            ->max(255);

        $validator
            ->field("description", $values["description"], "La description")
            ->max(5000);

        $validator
            ->field("project_id", $values["project_id"], "Le projet")
            ->required()
            ->int();

        $validator
            ->field("status_id", $values["status_id"], "Le statut")
            ->required()
            ->int();

        $validator
            ->field(
                "planned_start_date",
                $values["planned_start_date"],
                "La date de début"
            )
            ->date();

        $validator
            ->field(
                "planned_end_date",
                $values["planned_end_date"],
                "La date de fin"
            )
            ->date();

        $errors = $validator->getErrors();

        if (!isset($errors["project_id"])) {
            $project = new Project();

            if ($project->selectId((int) $values["project_id"]) === false) {
                $errors["project_id"] = "Le projet sélectionné n'existe pas.";
            }
        }

        if (!isset($errors["status_id"])) {
            $status = new Status();

            if ($status->selectId((int) $values["status_id"]) === false) {
                $errors["status_id"] = "Le statut sélectionné n'existe pas.";
            }
        }

        if (
            !isset($errors["planned_start_date"])
            && !isset($errors["planned_end_date"])
            && $values["planned_start_date"] !== ""
            && $values["planned_end_date"] !== ""
            && $values["planned_end_date"] < $values["planned_start_date"]
        ) {
            $errors["planned_end_date"] =
                "La fin prévue ne peut pas précéder le début prévu.";
        }

        return $errors;
    }

    private function prepareDatabaseValues(array $values): array
    {
        return [
            "project_id" => $values["project_id"],
            "status_id" => $values["status_id"],
            "title" => $values["title"],
            "description" => $values["description"] === ""
                ? null
                : $values["description"],
            "planned_start_date" => $values["planned_start_date"] === ""
                ? null
                : $values["planned_start_date"],
            "planned_end_date" => $values["planned_end_date"] === ""
                ? null
                : $values["planned_end_date"],
        ];
    }

    private function renderForm(
        array $values,
        array $errors = [],
        ?int $id = null,
        bool $fromProject = false,
        bool $fromCompany = false
    ): void {
        $projects = new Project();
        $statuses = new Status();

        foreach ($values as $field => $value) {
            if (!is_string($value)) {
                $values[$field] = "";
            }
        }

        $editing = $id !== null;

        View::render($editing ? "task/edit" : "task/create", [
            "fromProject" => $fromProject,
            "fromCompany" => $fromCompany,
            "pageTitle" => $editing ? "Modifier une tâche" : "Nouvelle tâche",
            "values" => $values,
            "errors" => $errors,
            "projects" => $projects->select(),
            "statuses" => $statuses->select(),
            "formAction" => BASE . (
                $editing
                ? "/tasks/edit?id=" . $id
                . ($fromProject ? "&from=project" : "")
                . ($fromCompany ? "&parent=company" : "")
                : "/tasks/create"
            ),
            "submitLabel" => $editing ? "Enregistrer les modifications" : "Créer la tâche",
        ]);
    }

    private function getId(mixed $value): ?int
    {
        if (!is_string($value)) {
            $this->error(400, "Identifiant invalide.");

            return null;
        }

        $id = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        );

        if ($id === false) {
            $this->error(400, "Identifiant invalide.");

            return null;
        }

        return $id;
    }

    private function error(int $status, string $message): void
    {
        http_response_code($status);

        View::render("error", [
            "pageTitle" => "Erreur",
            "msg" => $message,
        ]);
    }

    private function getNotice(array $query): string
    {
        return match ($query["notice"] ?? "") {
            "created" => "La tâche a été créée.",
            "updated" => "La tâche a été modifiée.",
            "deleted" => "La tâche a été supprimée.",
            default => "",
        };
    }
}
