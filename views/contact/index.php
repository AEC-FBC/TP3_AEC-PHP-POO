{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Contacts</h1>
        <p>Consultez les contacts des entreprises.</p>
    </div>

    <a class="button button--primary" href="{{ base }}/contacts/create">
        Nouveau contact
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
                <th>Contact</th>
                <th>Entreprise</th>
                <th>Poste</th>
                <th>Courriel</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            {% for contact in contacts %}
            <tr>
                <td>{{ contact.first_name }} {{ contact.last_name }}</td>
                <td>{{ contact.company_name }}</td>
                <td>{{ contact.job_title|default('-', true) }}</td>
                <td>{{ contact.email|default('-', true) }}</td>
                <td>{{ contact.phone|default('-', true) }}</td>
                <td>
                    <a
                        class="button button--secondary"
                        href="{{ base }}/contacts/edit?id={{ contact.id }}">
                        Modifier
                    </a>
                </td>
            </tr>
            {% else %}
            <tr>
                <td colspan="6" class="data-table__empty">
                    Aucun contact pour le moment.
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
{% endblock %}