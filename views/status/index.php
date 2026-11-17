{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Statuts</h1>
        <p>Gérez les statuts disponibles pour les projets et les tâches.</p>
    </div>

    <a class="button button--primary" href="{{ base }}/statuses/create">
        Nouveau statut
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
                <th>Nom</th>
                <th>Couleur</th>
                <th>Ordre d'affichage</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            {% for status in statuses %}
            <tr>
                <td>{{ status.name }}</td>
                <td>{{ status.color }}</td>
                <td>{{ status.display_order }}</td>
                <td>
                    <a
                        class="button button--secondary"
                        href="{{ base }}/statuses/edit?id={{ status.id }}">
                        Modifier
                    </a>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="4" class="data-table__empty">
                    Aucun statut pour le moment.
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
{% endblock %}