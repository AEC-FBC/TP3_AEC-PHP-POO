<?php

namespace App\Controllers;

use App\Models\Company;
use App\Models\CompanyLocation;
use App\Providers\Validator;
use App\Providers\View;
use PDOException;

class CompanyLocationController
{
    public function index(array $query = []): void
    {
        View::render("location/index", [
            "pageTitle" => "Établissements",
            "locations" => (new CompanyLocation())->selectWithRelations(),
            "notice" => match ($query["notice"] ?? "") {
                "created" => "L'établissement a été créé.",
                "updated" => "L'établissement a été modifié.",
                "deleted" => "L'établissement a été supprimé.",
                default => "",
            },
        ]);
    }

    public function create(array $query = []): void
    {
        $values = $this->prepareValues([]);

        if (isset($query["company_id"])) {
            $companyId = $this->getId($query["company_id"]);

            if ($companyId === null) {
                return;
            }

            if (!(new Company())->selectId($companyId)) {
                $this->error(404, "Entreprise introuvable.");
                return;
            }

            $values["company_id"] = (string) $companyId;
        }

        $this->renderForm($values);
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

        $id = (new CompanyLocation())->insert(
            $this->databaseValues($values)
        );

        if ($id === false || (int) $id < 1) {
            throw new \RuntimeException("Création de l'établissement impossible.");
        }

        View::redirect("locations?notice=created");
    }

    public function edit(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $location = (new CompanyLocation())->selectId($id);

        if (!$location) {
            $this->error(404, "Établissement introuvable.");
            return;
        }

        $this->renderForm($this->prepareValues($location), [], $id);
    }

    public function update(array $input, array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new CompanyLocation();
        $location = $model->selectId($id);

        if (!$location) {
            $this->error(404, "Établissement introuvable.");
            return;
        }

        $values = $this->prepareValues($input);
        $errors = $this->validate($values);

        // Un établissement déjà utilisé doit rester dans
        // la même entreprise que ses contacts et ses projets.
        if (
            !isset($errors["company_id"])
            && (int) $values["company_id"] !== (int) $location["company_id"]
        ) {
            $statement = $model->prepare(
                "SELECT
                    (SELECT COUNT(*) FROM contact
                     WHERE company_location_id = :contact_location)
                    +
                    (SELECT COUNT(*) FROM project
                     WHERE company_location_id = :project_location)"
            );

            $statement->execute([
                "contact_location" => $id,
                "project_location" => $id,
            ]);

            if ((int) $statement->fetchColumn() > 0) {
                $errors["company_id"] =
                    "Cet établissement est utilisé. Il doit conserver son entreprise actuelle.";
            }
        }

        if ($errors !== []) {
            http_response_code(422);
            $this->renderForm($values, $errors, $id);
            return;
        }

        if (!$model->update($this->databaseValues($values), $id)) {
            throw new \RuntimeException(
                "Modification de l'établissement impossible."
            );
        }

        View::redirect("locations?notice=updated");
    }

    public function delete(array $input, array $query = []): void
    {
        $id = $this->getId($input["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new CompanyLocation();

        if (!$model->selectId($id)) {
            $this->error(404, "Établissement introuvable.");
            return;
        }

        try {
            if (!$model->delete($id)) {
                throw new \RuntimeException(
                    "Suppression de l'établissement impossible."
                );
            }
        } catch (PDOException $exception) {
            if ($exception->getCode() !== "23000") {
                throw $exception;
            }

            $this->error(
                409,
                "Cet établissement est utilisé par un contact ou un projet."
            );
            return;
        }

        View::redirect("locations?notice=deleted");
    }

    private function prepareValues(array $input): array
    {
        $values = [];

        foreach (
            [
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
            ] as $field
        ) {
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
            "company_id",
            $values["company_id"],
            "L'entreprise"
        )->required()->int();

        $validator->field(
            "name",
            $values["name"],
            "Le nom"
        )->required()->max(150);

        $fields = [
            "address" => ["L'adresse", 255],
            "unit" => ["Le numéro de local", 50],
            "postal_code" => ["Le code postal", 30],
            "country" => ["Le pays", 100],
            "region" => ["La région ou province", 100],
            "city" => ["La ville", 100],
            "phone" => ["Le téléphone", 30],
            "email" => ["Le courriel", 255],
        ];

        foreach ($fields as $field => [$label, $length]) {
            $validator->field($field, $values[$field], $label)->max($length);
        }

        $errors = $validator->getErrors();

        if (!isset($errors["company_id"])) {
            if (!(new Company())->selectId((int) $values["company_id"])) {
                $errors["company_id"] = "Cette entreprise n'existe pas.";
            }
        }

        if (
            !isset($errors["email"])
            && $values["email"] !== ""
            && filter_var($values["email"], FILTER_VALIDATE_EMAIL) === false
        ) {
            $errors["email"] = "Saisis une adresse courriel valide.";
        }

        return $errors;
    }

    private function databaseValues(array $values): array
    {
        foreach ($values as $field => $value) {
            if (
                !in_array($field, ["company_id", "name"], true)
                && $value === ""
            ) {
                $values[$field] = null;
            }
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

        View::render("location/form", [
            "pageTitle" => $id === null
                ? "Créer un établissement"
                : "Modifier l'établissement",
            "values" => $values,
            "errors" => $errors,
            "companies" => (new Company())->select(),
            "locationId" => $id,
            "formAction" => BASE . (
                $id === null
                ? "/locations/create"
                : "/locations/edit?id=" . $id
            ),
            "submitLabel" => $id === null
                ? "Créer l'établissement"
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
