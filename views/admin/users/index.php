{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Gestion des utilisateurs</h1>
        <p>Consultez et gérez les comptes de Craft Industria.</p>
    </div>

    <a class="button button--primary" href="{{ base }}/admin/users/create">
        Ajouter un utilisateur
    </a>
</div>

<section class="main-container">
    <h2 class="card__title">Liste des utilisateurs</h2>

    {% if users is empty %}
    <p>Aucun utilisateur trouvé.</p>
    {% else %}
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Courriel</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            {% for user in users %}
            <tr>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td>
                    {{ user.role == "admin" ? "Administrateur" : "Client" }}
                </td>
                <td>
                    <a
                        class="button"
                        href="{{ base }}/admin/users/edit?id={{ user.id }}">
                        Modifier
                    </a>

                    <form
                        action="{{ base }}/admin/users/delete"
                        method="POST"
                        style="display: inline;"
                        onsubmit="return confirm('Supprimer cet utilisateur ?');">
                        <input type="hidden" name="id" value="{{ user.id }}">

                        <button type="submit" class="button">
                            Supprimer
                        </button>
                    </form>
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
    {% endif %}
</section>
{% endblock %}