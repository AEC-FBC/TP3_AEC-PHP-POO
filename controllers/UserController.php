<?php

namespace App\Controllers;

use App\Models\User;
use App\Providers\View;
use PDOException;

class UserController
{
    private function requireAdmin(): void
    {
        if (($_SESSION["user"]["role"] ?? null) !== "admin") {
            View::redirect("login");
        }
    }

    public function index(array $query = []): void
    {
        $this->requireAdmin();

        $users = (new User())->select();

        // Ne jamais transmettre les mots de passe aux vues.
        foreach ($users as &$user) {
            unset($user["password"]);
        }
        unset($user);

        View::render("admin/users/index", [
            "pageTitle" => "Gestion des utilisateurs",
            "users" => $users,
        ]);
    }

    public function create(array $query = []): void
    {
        $this->requireAdmin();

        View::render("admin/users/form", [
            "pageTitle" => "Ajouter un utilisateur",
            "user" => null,
            "error" => null,
        ]);
    }

    public function store(array $query = []): void
    {
        $this->requireAdmin();

        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $role = $_POST["role"] ?? "";

        if (
            $name === "" ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            strlen($password) < 8 ||
            !in_array($role, ["admin", "client"], true)
        ) {
            $this->showForm(null, "Vérifiez les informations. Le mot de passe doit contenir au moins 8 caractères.");
            return;
        }

        $model = new User();

        if ($model->findByEmail($email)) {
            $this->showForm(null, "Cette adresse courriel est déjà utilisée.");
            return;
        }

        $model->insert([
            "name" => $name,
            "email" => $email,
            "password" => password_hash($password, PASSWORD_DEFAULT),
            "role" => $role,
        ]);

        View::redirect("admin/users");
    }

    public function edit(array $query = []): void
    {
        $this->requireAdmin();

        $id = filter_var($query["id"] ?? null, FILTER_VALIDATE_INT);
        $user = $id ? (new User())->selectId($id) : false;

        if (!$user) {
            http_response_code(404);
            echo "Utilisateur introuvable.";
            return;
        }

        unset($user["password"]);

        $this->showForm($user);
    }

    public function update(array $query = []): void
    {
        $this->requireAdmin();

        $id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);
        $model = new User();
        $existing = $id ? $model->selectId($id) : false;

        if (!$existing) {
            http_response_code(404);
            echo "Utilisateur introuvable.";
            return;
        }

        $name = trim($_POST["name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $password = $_POST["password"] ?? "";
        $role = $_POST["role"] ?? "";

        $formUser = [
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "role" => $role,
        ];

        if (
            $name === "" ||
            !filter_var($email, FILTER_VALIDATE_EMAIL) ||
            !in_array($role, ["admin", "client"], true) ||
            ($password !== "" && strlen($password) < 8)
        ) {
            $this->showForm($formUser, "Vérifiez les informations saisies.");
            return;
        }

        $other = $model->findByEmail($email);

        if ($other && (int) $other["id"] !== $id) {
            $this->showForm($formUser, "Cette adresse courriel est déjà utilisée.");
            return;
        }

        // Empêcher un administrateur de retirer son propre rôle.
        if (
            $id === (int) $_SESSION["user"]["id"] &&
            $role !== "admin"
        ) {
            $this->showForm($formUser, "Vous ne pouvez pas retirer votre propre rôle administrateur.");
            return;
        }

        $data = [
            "name" => $name,
            "email" => $email,
            "role" => $role,
        ];

        // Un mot de passe vide conserve le mot de passe actuel.
        if ($password !== "") {
            $data["password"] = password_hash($password, PASSWORD_DEFAULT);
        }

        $model->update($data, $id);

        // Actualiser la session si l'administrateur modifie son compte.
        if ($id === (int) $_SESSION["user"]["id"]) {
            $_SESSION["user"]["name"] = $name;
            $_SESSION["user"]["email"] = $email;
        }

        View::redirect("admin/users");
    }

    public function delete(array $query = []): void
    {
        $this->requireAdmin();

        $id = filter_var($_POST["id"] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            View::redirect("admin/users");
        }

        // Empêcher la suppression de son propre compte.
        if ($id === (int) $_SESSION["user"]["id"]) {
            View::redirect("admin/users");
        }

        (new User())->delete($id);

        View::redirect("admin/users");
    }

    private function showForm(?array $user, ?string $error = null): void
    {
        View::render("admin/users/form", [
            "pageTitle" => $user ? "Modifier un utilisateur" : "Ajouter un utilisateur",
            "user" => $user,
            "error" => $error,
        ]);
    }
}
