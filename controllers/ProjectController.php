<?php

namespace App\Controllers;

use App\Models\Company;
use App\Models\CompanyLocation;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Service;
use App\Models\Status;
use App\Providers\Validator;
use App\Providers\View;
use PDOException;

class ProjectController
{
    public function index(array $query = []): void
    {
        $notice = match ($query["notice"] ?? "") {
            "deleted" => "Le projet a été supprimé.",
            default => "",
        };

        View::render("project/index", [
            "pageTitle" => "Projets",
            "projects" => (new Project())->selectWithRelations(),
            "notice" => $notice,
        ]);
    }

    public function show(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Project();
        $project = $model->selectId($id);

        if (!$project) {
            $this->error(404, "Projet introuvable.");
            return;
        }

        $notice = match ($query["notice"] ?? "") {
            "created" => "Le projet a été créé.",
            "updated" => "Le projet a été modifié.",
            default => "",
        };

        View::render("project/show", [
            "fromCompany" => ($query["from"] ?? "") === "company",
            "pageTitle" => $project["title"],
            "project" => $project,
            "company" => (new Company())->selectId((int) $project["company_id"]),
            "location" => $project["company_location_id"]
                ? (new CompanyLocation())->selectId(
                    (int) $project["company_location_id"]
                )
                : null,
            "contact" => (new Contact())->selectId(
                (int) $project["primary_contact_id"]
            ),
            "status" => (new Status())->selectId((int) $project["status_id"]),
            "services" => $model->getServices($id),
            "tasks" => $model->getTasks($id),
            "notice" => $notice,
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

        $data = $this->databaseValues($values);
        $data["image_path"] = $this->uploadImage();

        $id = (new Project())->saveWithServices(
            $data,
            $values["service_ids"]
        );

        View::redirect("projects/show?id=" . $id . "&notice=created");
    }

    public function edit(array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Project();
        $project = $model->selectId($id);

        if (!$project) {
            $this->error(404, "Projet introuvable.");
            return;
        }

        $project["service_ids"] = array_map(
            "strval",
            array_column($model->getServices($id), "id")
        );

        $this->renderForm(
            $this->prepareValues($project),
            [],
            $id,
            ($query["from"] ?? "") === "company"
        );
    }

    public function update(array $input, array $query = []): void
    {
        $id = $this->getId($query["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Project();

        $project = $model->selectId($id);

        if (!$project) {
            $this->error(404, "Projet introuvable.");
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
                ($query["from"] ?? "") === "company"
            );
            return;
        }

        $data = $this->databaseValues($values);

        $oldImagePath = $project["image_path"] ?? null;
        $newImagePath = $this->uploadImage();

        if ($newImagePath !== null) {
            $data["image_path"] = $newImagePath;
        }

        try {
            $model->saveWithServices(
                $data,
                $values["service_ids"],
                $id
            );
        } catch (\Throwable $e) {
            // Supprimer la nouvelle image si l'enregistrement échoue.
            if ($newImagePath !== null) {
                $this->deleteImage($newImagePath);
            }

            throw $e;
        }

        // Supprimer l'ancienne image seulement après la sauvegarde.
        if ($newImagePath !== null) {
            $this->deleteImage($oldImagePath);
        }

        View::redirect(
            "projects/show?id=" . $id . "&notice=updated"
                . (($query["from"] ?? "") === "company" ? "&from=company" : "")
        );
    }

    public function delete(array $input, array $query = []): void
    {
        $id = $this->getId($input["id"] ?? null);

        if ($id === null) {
            return;
        }

        $model = new Project();

        $project = $model->selectId($id);

        if (!$project) {
            $this->error(404, "Projet introuvable.");
            return;
        }

        if ($model->getTasks($id) !== []) {
            $this->error(
                409,
                "Supprime les tâches de ce projet avant de supprimer le projet."
            );
            return;
        }

        try {
            $model->deleteWithServices($id);
        } catch (PDOException $exception) {
            if ($exception->getCode() !== "23000") {
                throw $exception;
            }

            $this->error(
                409,
                "Ce projet est encore utilisé par d'autres données."
            );
            return;
        }
        // Supprimer l'image après la suppression réussie du projet.
        $this->deleteImage($project["image_path"] ?? null);
        View::redirect("projects?notice=deleted");
    }

    private function prepareValues(array $input): array
    {
        $values = [];

        foreach (
            [
                "company_id",
                "company_location_id",
                "primary_contact_id",
                "status_id",
                "title",
                "description",
                "planned_start_date",
                "planned_end_date",
            ] as $field
        ) {
            $value = $input[$field] ?? "";

            $values[$field] = is_string($value) || is_int($value)
                ? trim((string) $value)
                : $value;
        }

        $values["service_ids"] = $input["service_ids"] ?? [];

        return $values;
    }

    private function validate(array $values): array
    {
        $validator = new Validator();

        $validator->field("title", $values["title"], "Le titre")
            ->required()->min(3)->max(150);

        $validator->field(
            "description",
            $values["description"],
            "La description"
        )->max(10000);

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
            "primary_contact_id",
            $values["primary_contact_id"],
            "Le contact"
        )->required()->int();

        $validator->field(
            "status_id",
            $values["status_id"],
            "Le statut"
        )->required()->int();

        $validator->field(
            "planned_start_date",
            $values["planned_start_date"],
            "La date de début"
        )->date();

        $validator->field(
            "planned_end_date",
            $values["planned_end_date"],
            "La date de fin"
        )->date();

        $errors = $validator->getErrors();

        if (!isset($errors["company_id"])) {
            if (!(new Company())->selectId((int) $values["company_id"])) {
                $errors["company_id"] = "Cette entreprise n'existe pas.";
            }
        }

        if (!isset($errors["status_id"])) {
            if (!(new Status())->selectId((int) $values["status_id"])) {
                $errors["status_id"] = "Ce statut n'existe pas.";
            }
        }

        if (!isset($errors["primary_contact_id"])) {
            $contact = (new Contact())->selectId(
                (int) $values["primary_contact_id"]
            );

            if (!$contact) {
                $errors["primary_contact_id"] = "Ce contact n'existe pas.";
            } elseif (
                !isset($errors["company_id"])
                && (int) $contact["company_id"] !== (int) $values["company_id"]
            ) {
                $errors["primary_contact_id"] =
                    "Choisis un contact de l'entreprise sélectionnée.";
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

        if (
            !isset($errors["planned_start_date"])
            && !isset($errors["planned_end_date"])
            && $values["planned_start_date"] !== ""
            && $values["planned_end_date"] !== ""
            && $values["planned_end_date"] < $values["planned_start_date"]
        ) {
            $errors["planned_end_date"] =
                "La date de fin doit être égale ou postérieure à la date de début.";
        }

        if (!is_array($values["service_ids"])) {
            $errors["service_ids"] = "La sélection des services est invalide.";
        } else {
            $serviceModel = new Service();

            foreach ($values["service_ids"] as $serviceId) {
                if (
                    !is_string($serviceId)
                    || filter_var(
                        $serviceId,
                        FILTER_VALIDATE_INT,
                        ["options" => ["min_range" => 1]]
                    ) === false
                    || !$serviceModel->selectId((int) $serviceId)
                ) {
                    $errors["service_ids"] =
                        "Un des services sélectionnés est invalide.";
                    break;
                }
            }
        }

        return $errors;
    }

    private function databaseValues(array $values): array
    {
        unset($values["service_ids"]);

        foreach (
            [
                "company_location_id",
                "description",
                "planned_start_date",
                "planned_end_date",
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
        ?int $id = null,
        bool $fromCompany = false
    ): void {
        foreach ($values as $key => $value) {
            if ($key !== "service_ids" && !is_string($value)) {
                $values[$key] = "";
            }
        }

        $values["service_ids"] = is_array($values["service_ids"])
            ? array_values(array_unique(array_filter(
                $values["service_ids"],
                "is_string"
            )))
            : [];

        $companies = (new Company())->select();
        $companyNames = [];

        foreach ($companies as $company) {
            $companyNames[$company["id"]] = $company["name"];
        }

        View::render("project/form", [
            "pageTitle" => $id === null ? "Créer un projet" : "Modifier le projet",
            "fromCompany" => $fromCompany,
            "values" => $values,
            "errors" => $errors,
            "companies" => $companies,
            "companyNames" => $companyNames,
            "locations" => (new CompanyLocation())->select(),
            "contacts" => (new Contact())->select(),
            "statuses" => (new Status())->select(),
            "services" => (new Service())->select(),
            "formAction" => BASE . (
                $id === null
                ? "/projects/create"
                : "/projects/edit?id=" . $id
                . ($fromCompany ? "&from=company" : "")
            ),
            "submitLabel" => $id === null
                ? "Créer le projet"
                : "Enregistrer les modifications",
        ]);
    }

    private function uploadImage(): ?string
    {
        $file = $_FILES["project_image"] ?? null;

        if (!$file || $file["error"] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($file["error"] !== UPLOAD_ERR_OK) {
            throw new \RuntimeException("Erreur lors du téléversement de l'image.");
        }

        if ($file["size"] > 2 * 1024 * 1024) {
            throw new \RuntimeException("L'image ne doit pas dépasser 2 Mo.");
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file["tmp_name"]);

        $extensions = [
            "image/jpeg" => "jpg",
            "image/png" => "png",
            "image/webp" => "webp",
        ];

        if (!isset($extensions[$mime])) {
            throw new \RuntimeException("Format d'image non accepté.");
        }

        $filename = bin2hex(random_bytes(16)) . "." . $extensions[$mime];

        $directory = dirname(__DIR__) . "/public/uploads/projects";

        if (!is_dir($directory) || !is_writable($directory)) {
            throw new \RuntimeException("Le dossier des images est inaccessible.");
        }

        if (!move_uploaded_file($file["tmp_name"], $directory . "/" . $filename)) {
            throw new \RuntimeException("Impossible d'enregistrer l'image.");
        }

        return "uploads/projects/" . $filename;
    }

    private function deleteImage(?string $imagePath): void
    {
        if (empty($imagePath)) {
            return;
        }

        $directory = realpath(__DIR__ . "/../public/uploads/projects");

        if ($directory === false) {
            return;
        }

        $file = realpath(__DIR__ . "/../public/" . $imagePath);

        if (
            $file !== false
            && dirname($file) === $directory
            && is_file($file)
        ) {
            unlink($file);
        }
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
