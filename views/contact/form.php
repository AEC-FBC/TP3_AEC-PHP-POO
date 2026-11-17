{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ pageTitle }}</h1>
        <p>Renseignez les coordonnées du contact.</p>
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
        <h2 class="card__title">Informations du contact</h2>

        <div class="form-grid">
            <div>
                <div class="form-field">
                    <label class="form-label" for="first_name">
                        Prénom - obligatoire
                    </label>

                    <input
                        class="form-input"
                        id="first_name"
                        name="first_name"
                        type="text"
                        value="{{ values.first_name }}"
                        maxlength="150"
                        required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="last_name">
                        Nom - obligatoire
                    </label>

                    <input
                        class="form-input"
                        id="last_name"
                        name="last_name"
                        type="text"
                        value="{{ values.last_name }}"
                        maxlength="150"
                        required>
                </div>

                <div class="form-field">
                    <label class="form-label" for="job_title">
                        Poste
                    </label>

                    <input
                        class="form-input"
                        id="job_title"
                        name="job_title"
                        type="text"
                        value="{{ values.job_title }}"
                        maxlength="100">
                </div>

                <div class="form-field">
                    <label class="form-label" for="email">
                        Courriel
                    </label>

                    <input
                        class="form-input"
                        id="email"
                        name="email"
                        type="email"
                        value="{{ values.email }}"
                        maxlength="255">
                </div>

                <div class="form-field">
                    <label class="form-label" for="phone">
                        Téléphone
                    </label>

                    <input
                        class="form-input"
                        id="phone"
                        name="phone"
                        type="tel"
                        value="{{ values.phone }}"
                        maxlength="30">
                </div>
            </div>

            <div>
                <div class="form-field">
                    <label class="form-label" for="company_id">
                        Entreprise - obligatoire
                    </label>

                    <select
                        class="form-select"
                        id="company_id"
                        name="company_id"
                        required>
                        <option value="">Choisir une entreprise</option>

                        {% for company in companies %}
                        <option
                            value="{{ company.id }}"
                            {% if values.company_id == company.id %}selected{% endif %}>
                            {{ company.name }}
                        </option>
                        {% endfor %}
                    </select>
                </div>

                <div class="form-field">
                    <label class="form-label" for="company_location_id">
                        Établissement
                    </label>

                    <select
                        class="form-select"
                        id="company_location_id"
                        name="company_location_id">
                        <option value="">Aucun établissement</option>

                        {% for location in locations %}
                        <option
                            value="{{ location.id }}"
                            {% if values.company_location_id == location.id %}selected{% endif %}>
                            {{ companyNames[location.company_id]|default('') }}
                            - {{ location.name }}
                        </option>
                        {% endfor %}
                    </select>

                    <p>
                        Choisissez un établissement de l'entreprise sélectionnée.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="data-table__actions">
        <button class="button button--primary" type="submit">
            {{ submitLabel }}
        </button>

        <a class="button button--secondary" href="{{ base }}/contacts">
            Retour aux contacts
        </a>

        <a class="button button--secondary" href="{{ base }}/projects/create">
            Créer un projet
        </a>
    </div>
</form>

{% if contactId %}
<section class="main-container">
    <h2 class="card__title">Supprimer le contact</h2>

    <p>
        La suppression est définitive. Un contact utilisé par un projet
        ne peut pas être supprimé.
    </p>

    <form method="post" action="{{ base }}/contacts/delete">
        <input type="hidden" name="id" value="{{ contactId }}">

        <button class="button button--secondary" type="submit">
            Supprimer définitivement le contact
        </button>
    </form>
</section>
{% endif %}
{% endblock %}