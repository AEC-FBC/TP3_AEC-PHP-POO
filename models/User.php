<?php

namespace App\Models;

class User extends CRUD
{
    protected string $table = "users";

    protected array $fillable = [
        "name",
        "email",
        "password",
        "role",
    ];

    public function findByEmail(string $email): array|false
    {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";

        $stmt = $this->prepare($sql);
        $stmt->execute([
            "email" => $email,
        ]);

        return $stmt->fetch();
    }
}
