<?php

namespace App\Controllers;

use App\Models\Company;
use App\Providers\Validator;
use App\Providers\View;
use PDOException;

class CompanyController
{
    public function index(array $query = []): void
    {
        View::render("company/index", [
            "pageTitle" => "Entreprises",
            "companies" => (new Company())->select(),
            "notice" => match ($query["notice"] ?? "") {
                "deleted" => "L'entreprise a été supprimée.",
                default => "",
            },
        ]);
    }

    public function show(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Company();
        $company = $model->selectId($id);

        if (!$company) {
            $this->error(404, "Entreprise introuvable.");
            return;
        }

        View::render("company/show", [
            "pageTitle" => $company["name"],
            "company" => $company,
            "locations" => $model->getLocations($id),
            "contacts" => $model->getContacts($id),
            "projects" => $model->getProjects($id),
            "notice" => match ($query["notice"] ?? "") {
                "created" => "L'entreprise a été créée.",
                "updated" => "L'entreprise a été modifiée.",
                default => "",
            },
        ]);
    }

    public function create(array $query = []): void
    {
        $this->renderForm([
            "name" => "",
            "website" => "",
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

        $id = (new Company())->insert($this->databaseValues($values));

        if ($id === false || (int) $id < 1) {
            throw new \RuntimeException("Création de l'entreprise impossible.");
        }

        View::redirect("companies/show?id=" . $id . "&notice=created");
    }

    public function edit(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $company = (new Company())->selectId($id);

        if (!$company) {
            $this->error(404, "Entreprise introuvable.");
            return;
        }

        $this->renderForm($this->prepareValues($company), [], $id);
    }

    public function update(array $input, array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Company();

        if (!$model->selectId($id)) {
            $this->error(404, "Entreprise introuvable.");
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
            throw new \RuntimeException(
                "Modification de l'entreprise impossible."
            );
        }

        View::redirect("companies/show?id=" . $id . "&notice=updated");
    }

    public function delete(array $input, array $query = []): void
    {
        $id = $this->getId($input["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Company();

        if (!$model->selectId($id)) {
            $this->error(404, "Entreprise introuvable.");
            return;
        }

        try {
            if (!$model->delete($id)) {
                throw new \RuntimeException(
                    "Suppression de l'entreprise impossible."
                );
            }
        } catch (PDOException $exception) {
            if ($exception->getCode() !== "23000") {
                throw $exception;
            }

            $this->error(
                409,
                "Cette entreprise possède encore des établissements, des contacts ou des projets."
            );
            return;
        }

        View::redirect("companies?notice=deleted");
    }

    private function prepareValues(array $input): array
    {
        $values = [];

        foreach (["name", "website"] as $field) {
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
            "website",
            $values["website"],
            "Le site Web"
        )->max(255);

        $errors = $validator->getErrors();

        if (
            !isset($errors["website"])
            && $values["website"] !== ""
        ) {
            $validUrl = filter_var(
                $values["website"],
                FILTER_VALIDATE_URL
            );

            $scheme = strtolower(
                (string) parse_url($values["website"], PHP_URL_SCHEME)
            );

            if (
                $validUrl === false
                || !in_array($scheme, ["http", "https"], true)
            ) {
                $errors["website"] =
                    "Saisis une adresse complète commençant par https:// ou http://.";
            }
        }

        return $errors;
    }

    private function databaseValues(array $values): array
    {
        if ($values["website"] === "") {
            $values["website"] = null;
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

        View::render("company/form", [
            "pageTitle" => $id === null
                ? "Créer une entreprise"
                : "Modifier l'entreprise",
            "values" => $values,
            "errors" => $errors,
            "companyId" => $id,
            "formAction" => BASE . (
                $id === null
                ? "/companies/create"
                : "/companies/edit?id=" . $id
            ),
            "submitLabel" => $id === null
                ? "Créer l'entreprise"
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
