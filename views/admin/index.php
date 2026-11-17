{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Administration</h1>
        <p>Bienvenue, {{ user.name }} !</p>
    </div>
</div>

<section class="main-container">
    <h2 class="card__title">Tableau de bord</h2>

    <p>
        Gérez les informations et les utilisateurs
        de Craft Industria à partir de cet espace.
    </p>

    <div class="dashboard-actions">
        <a class="button button--primary" href="{{ base }}/projects">
            Gérer les projets
        </a>

        <a class="button" href="{{ base }}/tasks">
            Gérer les tâches
        </a>

        <a class="button" href="{{ base }}/admin/users">
            Gérer les utilisateurs
        </a>

        <a class="button" href="{{ base }}/admin/logs">
            Journal des visites
        </a>
    </div>
</section>
{% endblock %}