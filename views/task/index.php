{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Tâches</h1>
        <p>Consultez les travaux associés aux projets de l'agence.</p>
    </div>

    <a class="button button--primary" href="{{ base }}/tasks/create">
        Nouvelle tâche
    </a>
</div>

{% if notice %}
<div class="alert alert--success">
    <p class="alert__text">{{ notice }}</p>
</div>
{% endif %}

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Tâche</th>
                <th>Projet</th>
                <th>Statut</th>
                <th>Début prévu</th>
                <th>Fin prévue</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            {% for task in tasks %}
            <tr>
                <td>{{ task.title }}</td>
                <td>{{ task.project_title }}</td>
                <td>{{ task.status_name }}</td>
                <td>{{ task.planned_start_date|default('-', true) }}</td>
                <td>{{ task.planned_end_date|default('-', true) }}</td>
                <td>
                    <div class="data-table__actions">
                        <a
                            class="button button--secondary"
                            href="{{ base }}/tasks/show?id={{ task.id }}">
                            Voir
                        </a>

                        <a
                            class="button button--secondary"
                            href="{{ base }}/tasks/edit?id={{ task.id }}">
                            Modifier
                        </a>
                    </div>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="6" class="data-table__empty">
                    Aucune tâche pour le moment.
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
{% endblock %}