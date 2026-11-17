{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ pageTitle }}</h1>
        <p>Renseignez le nom et la description du service.</p>
    </div>
</div>

{% if errors is not empty %}
<section class="alert alert--error">
    <h2 class="alert__title">
        Vérifie les informations saisies
    </h2>

    <ul>
        {% for message in errors %}
        <li>{{ message }}</li>
        {% endfor %}
    </ul>
</section>
{% endif %}

<form method="post" action="{{ formAction }}">
    <section class="main-container">
        <h2 class="card__title">Informations du service</h2>

        <div class="form-field">
            <label class="form-label" for="name">
                Nom - obligatoire
            </label>

            <input
                class="form-input"
                id="name"
                name="name"
                type="text"
                value="{{ values.name }}"
                maxlength="150"
                required>

            {% if errors.name is defined %}
            <p>{{ errors.name }}</p>
            {% endif %}
        </div>

        <div class="form-field">
            <label class="form-label" for="description">
                Description
            </label>

            <textarea
                class="form-textarea"
                id="description"
                name="description"
                rows="6"
                maxlength="10000">{{ values.description }}</textarea>

            {% if errors.description is defined %}
            <p>{{ errors.description }}</p>
            {% endif %}
        </div>
    </section>

    <div class="data-table__actions">
        <button class="button button--primary" type="submit">
            {{ submitLabel }}
        </button>

        <a class="button button--secondary" href="{{ base }}/services">
            Annuler
        </a>
    </div>
</form>

{% if serviceId %}
<section class="main-container">
    <h2 class="card__title">Supprimer le service</h2>

    <p>
        La suppression est définitive. Un service associé à un projet
        ne peut pas être supprimé.
    </p>

    <form method="post" action="{{ base }}/services/delete">
        <input type="hidden" name="id" value="{{ serviceId }}">

        <button class="button button--secondary" type="submit">
            Supprimer définitivement le service
        </button>
    </form>
</section>
{% endif %}
{% endblock %}