{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ task.title }}</h1>
        <p>Projet : {{ task.project_title }}</p>
    </div>

    <a class="button button--secondary" href="{{ base }}/tasks">
        Retour aux tâches
    </a>
</div>

{% if notice %}
<div class="alert alert--success">
    <p class="alert__text">{{ notice }}</p>
</div>
{% endif %}

<section class="main-container">
    <h2 class="card__title">Informations de la tâche</h2>

    <dl>
        <dt>Statut</dt>
        <dd>{{ task.status_name }}</dd>

        <dt>Description</dt>
        <dd>{{ task.description|default('Aucune description.') }}</dd>

        <dt>Début prévu</dt>
        <dd>{{ task.planned_start_date|default('Non précisé') }}</dd>

        <dt>Fin prévue</dt>
        <dd>{{ task.planned_end_date|default('Non précisée') }}</dd>
    </dl>

    <a
        class="button button--primary"
        href="{{ base }}/tasks/edit?id={{ task.id }}">
        Modifier
    </a>
</section>

<section class="main-container">
    <h2 class="card__title">Supprimer cette tâche</h2>
    <p>La suppression est définitive.</p>

    <form method="post" action="{{ base }}/tasks/delete">
        <input type="hidden" name="id" value="{{ task.id }}">

        <button type="submit" class="button button--danger">
            Supprimer la tâche
        </button>
    </form>
</section>
{% endblock %}