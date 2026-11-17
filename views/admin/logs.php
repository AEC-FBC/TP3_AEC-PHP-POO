{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Journal de bord</h1>
        <p>Consultez les 100 dernières visites du site.</p>
    </div>
</div>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Date et heure</th>
                <th>Utilisateur</th>
                <th>Adresse IP</th>
                <th>Page visitée</th>
            </tr>
        </thead>

        <tbody>
            {% for visit in visits %}
            <tr>
                <td>{{ visit.visited_at }}</td>
                <td>{{ visit.username }}</td>
                <td>{{ visit.ip_address }}</td>
                <td>{{ visit.page_url }}</td>
            </tr>
            {% else %}
            <tr>
                <td colspan="4">Aucune visite enregistrée.</td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
</div>
{% endblock %}