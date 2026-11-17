<?php

namespace App\Controllers;

use App\Models\Service;
use App\Providers\Validator;
use App\Providers\View;
use PDOException;

class ServiceController
{
    public function index(array $query = []): void
    {
        View::render("service/index", [
            "pageTitle" => "Services",
            "services" => (new Service())->select(),
            "notice" => match ($query["notice"] ?? "") {
                "created" => "Le service a été créé.",
                "updated" => "Le service a été modifié.",
                "deleted" => "Le service a été supprimé.",
                default => "",
            },
        ]);
    }

    public function create(array $query = []): void
    {
        $this->renderForm([
            "name" => "",
            "description" => "",
        ]);
    }

    public function store(array $input, array $query = []): void
    {
        $values = $this->prepareValues($input);
        $errors = $this->validate($values);

        if ($errors !== []) {
            http_response_code(422);
            $this->renderForm($values, $errors);
            return;
        }

        $id = (new Service())->insert($this->databaseValues($values));

        if ($id === false || (int) $id < 1) {
            throw new \RuntimeException("Création du service impossible.");
        }

        View::redirect("services?notice=created");
    }

    public function edit(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $service = (new Service())->selectId($id);

        if (!$service) {
            $this->error(404, "Service introuvable.");
            return;
        }

        $this->renderForm($this->prepareValues($service), [], $id);
    }

    public function update(array $input, array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Service();

        if (!$model->selectId($id)) {
            $this->error(404, "Service introuvable.");
            return;
        }

        $values = $this->prepareValues($input);
        $errors = $this->validate($values);

        if ($errors !== []) {
            http_response_code(422);
            $this->renderForm($values, $errors, $id);
            return;
        }

        if (!$model->update($this->databaseValues($values), $id)) {
            throw new \RuntimeException("Modification du service impossible.");
        }

        View::redirect("services?notice=updated");
    }

    public function delete(array $input, array $query = []): void
    {
        $id = $this->getId($input["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Service();

        if (!$model->selectId($id)) {
            $this->error(404, "Service introuvable.");
            return;
        }

        try {
            if (!$model->delete($id)) {
                throw new \RuntimeException("Suppression du service impossible.");
            }
        } catch (PDOException $exception) {
            if ($exception->getCode() !== "23000") {
                throw $exception;
            }

            $this->error(
                409,
                "Ce service est associé à un projet. Retirez son association avant de le supprimer."
            );
            return;
        }

        View::redirect("services?notice=deleted");
    }

    private function prepareValues(array $input): array
    {
        $values = [];

        foreach (["name", "description"] as $field) {
            $value = $input[$field] ?? "";

            $values[$field] = is_string($value) || is_int($value)
                ? trim((string) $value)
                : $value;
        }

        return $values;
    }

    private function validate(array $values): array
    {
        $validator = new Validator();

        $validator->field(
            "name",
            $values["name"],
            "Le nom"
        )->required()->max(150);

        $validator->field(
            "description",
            $values["description"],
            "La description"
        )->max(10000);

        return $validator->getErrors();
    }

    private function databaseValues(array $values): array
    {
        if ($values["description"] === "") {
            $values["description"] = null;
        }

        return $values;
    }

    private function renderForm(
        array $values,
        array $errors = [],
        ?int $id = null
    ): void {
        foreach ($values as $field => $value) {
            if (!is_string($value)) {
                $values[$field] = "";
            }
        }

        View::render("service/form", [
            "pageTitle" => $id === null
                ? "Créer un service"
                : "Modifier le service",
            "values" => $values,
            "errors" => $errors,
            "serviceId" => $id,
            "formAction" => BASE . (
                $id === null
                ? "/services/create"
                : "/services/edit?id=" . $id
            ),
            "submitLabel" => $id === null
                ? "Créer le service"
                : "Enregistrer les modifications",
        ]);
    }

    private function getId(mixed $value): ?int
    {
        if (
            !is_string($value)
            || filter_var(
                $value,
                FILTER_VALIDATE_INT,
                ["options" => ["min_range" => 1]]
            ) === false
        ) {
            $this->error(400, "Identifiant invalide.");
            return null;
        }

        return (int) $value;
    }

    private function error(int $status, string $message): void
    {
        http_response_code($status);

        View::render("error", [
            "pageTitle" => "Erreur",
            "msg" => $message,
        ]);
    }
}
