<?php

namespace App\Controllers;

use App\Models\Status;
use App\Providers\Validator;
use App\Providers\View;
use PDOException;

class StatusController
{
    public function index(array $query = []): void
    {
        View::render("status/index", [
            "pageTitle" => "Statuts",
            "statuses" => (new Status())->select(),
            "notice" => match ($query["notice"] ?? "") {
                "created" => "Le statut a été créé.",
                "updated" => "Le statut a été modifié.",
                "deleted" => "Le statut a été supprimé.",
                default => "",
            },
        ]);
    }

    public function create(array $query = []): void
    {
        $this->renderForm([
            "name" => "",
            "color" => "#667579",
            "display_order" => "0",
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

        $id = (new Status())->insert($values);

        if ($id === false || (int) $id < 1) {
            throw new \RuntimeException("Création du statut impossible.");
        }

        View::redirect("statuses?notice=created");
    }

    public function edit(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $status = (new Status())->selectId($id);

        if (!$status) {
            $this->error(404, "Statut introuvable.");
            return;
        }

        $this->renderForm($this->prepareValues($status), [], $id);
    }

    public function update(array $input, array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Status();

        if (!$model->selectId($id)) {
            $this->error(404, "Statut introuvable.");
            return;
        }

        $values = $this->prepareValues($input);
        $errors = $this->validate($values);

        if ($errors !== []) {
            http_response_code(422);
            $this->renderForm($values, $errors, $id);
            return;
        }

        if (!$model->update($values, $id)) {
            throw new \RuntimeException("Modification du statut impossible.");
        }

        View::redirect("statuses?notice=updated");
    }

    public function delete(array $input, array $query = []): void
    {
        $id = $this->getId($input["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Status();

        if (!$model->selectId($id)) {
            $this->error(404, "Statut introuvable.");
            return;
        }

        try {
            if (!$model->delete($id)) {
                throw new \RuntimeException("Suppression du statut impossible.");
            }
        } catch (PDOException $exception) {
            if ($exception->getCode() !== "23000") {
                throw $exception;
            }

            $this->error(
                409,
                "Ce statut est utilisé par un projet ou une tâche."
            );
            return;
        }

        View::redirect("statuses?notice=deleted");
    }

    private function prepareValues(array $input): array
    {
        $values = [];

        foreach (["name", "color", "display_order"] as $field) {
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
        )->required()->max(100);

        $validator->field(
            "color",
            $values["color"],
            "La couleur"
        )->required();

        $validator->field(
            "display_order",
            $values["display_order"],
            "L'ordre d'affichage"
        )->required();

        $errors = $validator->getErrors();

        if (
            !isset($errors["color"])
            && !preg_match('/\A#[0-9a-fA-F]{6}\z/', $values["color"])
        ) {
            $errors["color"] =
                "La couleur doit respecter le format #667579.";
        }

        if (
            !isset($errors["display_order"])
            && filter_var(
                $values["display_order"],
                FILTER_VALIDATE_INT,
                ["options" => [
                    "min_range" => 0,
                    "max_range" => 2147483647,
                ]]
            ) === false
        ) {
            $errors["display_order"] =
                "L'ordre doit être un entier positif ou égal à zéro.";
        }

        return $errors;
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

        View::render("status/form", [
            "pageTitle" => $id === null
                ? "Créer un statut"
                : "Modifier le statut",
            "values" => $values,
            "errors" => $errors,
            "statusId" => $id,
            "formAction" => BASE . (
                $id === null
                ? "/statuses/create"
                : "/statuses/edit?id=" . $id
            ),
            "submitLabel" => $id === null
                ? "Créer le statut"
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
