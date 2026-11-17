{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ pageTitle }}</h1>
        <p>Renseignez les informations de l'entreprise.</p>
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
        <h2 class="card__title">Informations de l'entreprise</h2>

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
            <label class="form-label" for="website">
                Site Web
            </label>

            <input
                class="form-input"
                id="website"
                name="website"
                type="url"
                value="{{ values.website }}"
                placeholder="https://exemple.com"
                maxlength="255">

            {% if errors.website is defined %}
            <p>{{ errors.website }}</p>
            {% endif %}
        </div>
    </section>

    <div class="data-table__actions">
        <button class="button button--primary" type="submit">
            {{ submitLabel }}
        </button>

        <a class="button button--secondary" href="{{ base }}/companies">
            Annuler
        </a>
    </div>
</form>
{% endblock %}