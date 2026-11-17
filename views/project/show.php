{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ project.title }}</h1>
        <p>Informations, services et tâches du projet.</p>
    </div>

    <div class="data-table__actions">
        <a
            class="button button--primary"
            href="{{ base }}/projects/edit?id={{ project.id }}">
            Modifier le projet
        </a>

        <a class="button button--secondary" href="{{ base }}/projects">
            Retour aux projets
        </a>
    </div>
</div>

{% if notice %}
<div class="alert alert--success">
    <p class="alert__text">{{ notice }}</p>
</div>
{% endif %}

<section class="main-container">
    <h2 class="card__title">Informations du projet</h2>

    <div class="form-grid">
        <div>
            <div class="form-field">
                <strong class="form-label">Entreprise</strong>
                <p>{{ company.name }}</p>
            </div>

            <div class="form-field">
                <strong class="form-label">Établissement</strong>
                <p>{{ location.name|default('Non précisé', true) }}</p>
            </div>

            <div class="form-field">
                <strong class="form-label">Contact principal</strong>
                <p>{{ contact.first_name }} {{ contact.last_name }}</p>
            </div>
        </div>

        <div>
            <div class="form-field">
                <strong class="form-label">Statut</strong>
                <p>{{ status.name }}</p>
            </div>

            <div class="form-field">
                <strong class="form-label">Début prévu</strong>
                <p>
                    {{ project.planned_start_date|default('Non précisé', true) }}
                </p>
            </div>

            <div class="form-field">
                <strong class="form-label">Fin prévue</strong>
                <p>
                    {{ project.planned_end_date|default('Non précisée', true) }}
                </p>
            </div>
        </div>
    </div>

    <h2 class="card__title">Description</h2>
    <p>{{ project.description|default('Aucune description.', true)|nl2br }}</p>
</section>

<section class="main-container">
    <h2 class="card__title">Services associés</h2>

    {% if services %}
    <ul>
        {% for service in services %}
        <li>{{ service.name }}</li>
        {% endfor %}
    </ul>
    {% else %}
    <p>Aucun service associé.</p>
    {% endif %}
</section>

<section class="main-container">
    <div class="page-heading">
        <div>
            <h2 class="card__title">Tâches du projet</h2>
            <p>Consultez les travaux associés à ce projet.</p>
        </div>

        <a class="button button--primary" href="{{ base }}/tasks/create">
            Nouvelle tâche
        </a>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tâche</th>
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
                    <td colspan="5" class="data-table__empty">
                        Aucune tâche associée à ce projet.
                    </td>
                </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
</section>

<section class="main-container">
    <h2 class="card__title">Supprimer le projet</h2>

    {% if tasks %}
    <p>Supprimez les tâches associées avant de supprimer ce projet.</p>
    {% else %}
    <div class="alert alert--error">
        <p class="alert__text">
            Attention : la suppression de ce projet est définitive.
        </p>
    </div>

    <form method="post" action="{{ base }}/projects/delete">
        <input type="hidden" name="id" value="{{ project.id }}">

        <button class="button button--secondary" type="submit">
            Supprimer définitivement le projet
        </button>
    </form>
    {% endif %}
</section>
{% endblock %}