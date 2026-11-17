<?php

namespace App\Controllers;

use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Contact;
use App\Providers\Validator;
use App\Providers\View;
use PDOException;

class ContactController
{
    public function index(array $query = []): void
    {
        View::render("contact/index", [
            "pageTitle" => "Contacts",
            "contacts" => (new Contact())->selectWithRelations(),
            "notice" => match ($query["notice"] ?? "") {
                "created" => "Le contact a été créé.",
                "updated" => "Le contact a été modifié.",
                "deleted" => "Le contact a été supprimé.",
                default => "",
            },
        ]);
    }

    public function create(array $query = []): void
    {
        $this->renderForm($this->prepareValues([]));
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

        $id = (new Contact())->insert($this->databaseValues($values));

        if ($id === false || (int) $id < 1) {
            throw new \RuntimeException("Création du contact impossible.");
        }

        View::redirect("companies/show?id=" . (int) $values["company_id"]);
    }

    public function edit(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $contact = (new Contact())->selectId($id);

        if (!$contact) {
            $this->error(404, "Contact introuvable.");
            return;
        }

        $this->renderForm($this->prepareValues($contact), [], $id);
    }

    public function update(array $input, array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Contact();

        if (!$model->selectId($id)) {
            $this->error(404, "Contact introuvable.");
            return;
        }

        $values = $this->prepareValues($input);
        $errors = $this->validate($values);

        // Éviter de déplacer un contact vers une autre entreprise
        // s'il est déjà le contact principal d'un projet existant.
        if (
            !isset($errors["company_id"])
            && $model->hasProjectCompanyConflict(
                $id,
                (int) $values["company_id"]
            )
        ) {
            $errors["company_id"] =
                "Ce contact est utilisé par un projet d'une autre entreprise.";
        }

        if ($errors !== []) {
            http_response_code(422);
            $this->renderForm($values, $errors, $id);
            return;
        }

        if (!$model->update($this->databaseValues($values), $id)) {
            throw new \RuntimeException("Modification du contact impossible.");
        }

        View::redirect("companies/show?id=" . (int) $values["company_id"]);
    }

    public function delete(array $input, array $query = []): void
    {
        $id = $this->getId($input["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Contact();
        $contact = $model->selectId($id);

        if (!$contact) {
            $this->error(404, "Contact introuvable.");
            return;
        }

        $companyId = (int) $contact["company_id"];

        try {
            if (!$model->delete($id)) {
                throw new \RuntimeException("Suppression du contact impossible.");
            }
        } catch (PDOException $exception) {
            if ($exception->getCode() !== "23000") {
                throw $exception;
            }

            $this->error(
                409,
                "Ce contact est utilisé par un projet et ne peut pas être supprimé."
            );
            return;
        }

        View::redirect("companies/show?id=" . $companyId);
    }

    private function prepareValues(array $input): array
    {
        $values = [];

        foreach (
            [
                "company_id",
                "company_location_id",
                "first_name",
                "last_name",
                "job_title",
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
            "first_name",
            $values["first_name"],
            "Le prénom"
        )->required()->max(150);

        $validator->field(
            "last_name",
            $values["last_name"],
            "Le nom"
        )->required()->max(150);

        $validator->field(
            "company_id",
            $values["company_id"],
            "L'entreprise"
        )->required()->int();

        $validator->field(
            "company_location_id",
            $values["company_location_id"],
            "L'établissement"
        )->int();

        $validator->field(
            "job_title",
            $values["job_title"],
            "Le poste"
        )->max(100);

        $validator->field(
            "phone",
            $values["phone"],
            "Le téléphone"
        )->max(30);

        $validator->field(
            "email",
            $values["email"],
            "Le courriel"
        )->max(255);

        $errors = $validator->getErrors();

        if (
            !isset($errors["email"])
            && $values["email"] !== ""
            && filter_var($values["email"], FILTER_VALIDATE_EMAIL) === false
        ) {
            $errors["email"] = "Saisis une adresse courriel valide.";
        }

        if (!isset($errors["company_id"])) {
            if (!(new Company())->selectId((int) $values["company_id"])) {
                $errors["company_id"] = "Cette entreprise n'existe pas.";
            }
        }

        if (
            !isset($errors["company_location_id"])
            && $values["company_location_id"] !== ""
        ) {
            $location = (new CompanyLocation())->selectId(
                (int) $values["company_location_id"]
            );

            if (!$location) {
                $errors["company_location_id"] =
                    "Cet établissement n'existe pas.";
            } elseif (
                !isset($errors["company_id"])
                && (int) $location["company_id"] !== (int) $values["company_id"]
            ) {
                $errors["company_location_id"] =
                    "Choisis un établissement de l'entreprise sélectionnée.";
            }
        }

        return $errors;
    }

    private function databaseValues(array $values): array
    {
        foreach (
            [
                "company_location_id",
                "job_title",
                "phone",
                "email",
            ] as $field
        ) {
            if ($values[$field] === "") {
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

        $companies = (new Company())->select();
        $companyNames = [];

        foreach ($companies as $company) {
            $companyNames[$company["id"]] = $company["name"];
        }

        View::render("contact/form", [
            "pageTitle" => $id === null
                ? "Créer un contact"
                : "Modifier le contact",
            "values" => $values,
            "errors" => $errors,
            "companies" => $companies,
            "companyNames" => $companyNames,
            "locations" => (new CompanyLocation())->select(),
            "contactId" => $id,
            "formAction" => BASE . (
                $id === null
                ? "/contacts/create"
                : "/contacts/edit?id=" . $id
            ),
            "submitLabel" => $id === null
                ? "Créer le contact"
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
