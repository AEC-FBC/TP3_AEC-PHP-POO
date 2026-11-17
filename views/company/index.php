{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Entreprises</h1>
        <p>Consultez les entreprises, leurs établissements et leurs projets.</p>
    </div>
    {% if currentUser.role == 'admin' %}
    <a class="button button--primary" href="{{ base }}/companies/create">
        Nouvelle entreprise
    </a>
    {% endif %}
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
                <th>Entreprise</th>
                <th>Site Web</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            {% for company in companies %}
            <tr>
                <td>{{ company.name }}</td>
                <td>{{ company.website|default('-', true) }}</td>
                <td>
                    <div class="data-table__actions">
                        <a
                            class="button button--secondary"
                            href="{{ base }}/companies/show?id={{ company.id }}">
                            Voir
                        </a>

                        <a
                            class="button button--secondary"
                            href="{{ base }}/companies/edit?id={{ company.id }}">
                            Modifier
                        </a>
                    </div>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="3" class="data-table__empty">
                    Aucune entreprise pour le moment.
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
{% endblock %}