{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Craft Industria</h1>
        <p>Le suivi des tâches de vos projets, au même endroit.</p>
    </div>
</div>

<section class="main-container">
    <h2 class="card__title">Gestion de projets - Pixel & Craft</h2>

    <p>
        Craft Industria centralise les informations nécessaires
        au suivi des mandats de l'agence.
    </p>

    <p>
        Le module de tâches permet de consulter les travaux prévus,
        de les associer à un projet, de suivre leur statut
        et de préciser leurs dates.
    </p>

    <a class="button button--primary" href="{{ base }}/tasks">
        Consulter les tâches
    </a>
</section>
{% endblock %}