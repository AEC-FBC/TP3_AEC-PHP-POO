{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Services</h1>
        <p>Gérez les services proposés par l'agence.</p>
    </div>

    <a class="button button--primary" href="{{ base }}/services/create">
        Nouveau service
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
                <th>Service</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            {% for service in services %}
            <tr>
                <td>{{ service.name }}</td>
                <td>{{ service.description|default('-', true) }}</td>
                <td>
                    <a
                        class="button button--secondary"
                        href="{{ base }}/services/edit?id={{ service.id }}">
                        Modifier
                    </a>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="3" class="data-table__empty">
                    Aucun service pour le moment.
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
{% endblock %}