{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Projets</h1>
        <p>Consultez et gérez les projets des entreprises.</p>
    </div>
    {% if currentUser.role == 'admin' %}
    <a class="button button--primary" href="{{ base }}/projects/create">
        Nouveau projet
    </a>
</div>
{% endif %}
{% if notice %}
<div class="alert alert--success">
    <p class="alert__text">{{ notice }}</p>
</div>
{% endif %}
</div>
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Projet</th>
                <th>Entreprise</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            {% for project in projects %}
            <tr>
                <td>{{ project.title }}</td>
                <td>{{ project.company_name }}</td>
                <td>{{ project.status_name }}</td>
                <td>
                    <div class="data-table__actions">
                        <a
                            class="button button--secondary"
                            href="{{ base }}/projects/show?id={{ project.id }}">
                            Voir
                        </a>

                        <a
                            class="button button--secondary"
                            href="{{ base }}/projects/edit?id={{ project.id }}">
                            Modifier
                        </a>
                    </div>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="4" class="data-table__empty">
                    Aucun projet pour le moment.
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
{% endblock %}