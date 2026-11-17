{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Établissements</h1>
        <p>Gérez les établissements des entreprises.</p>
    </div>

    <a class="button button--primary" href="{{ base }}/locations/create">
        Nouvel établissement
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
                <th>Établissement</th>
                <th>Entreprise</th>
                <th>Ville</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            {% for location in locations %}
            <tr>
                <td>{{ location.name }}</td>
                <td>{{ location.company_name }}</td>
                <td>{{ location.city|default('-', true) }}</td>
                <td>{{ location.phone|default('-', true) }}</td>
                <td>
                    <a
                        class="button button--secondary"
                        href="{{ base }}/locations/edit?id={{ location.id }}">
                        Modifier
                    </a>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="5" class="data-table__empty">
                    Aucun établissement pour le moment.
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
{% endblock %}