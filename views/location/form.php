{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ pageTitle }}</h1>
        <p>Renseignez les informations et les coordonnées de l'établissement.</p>
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
        <h2 class="card__title">Informations de l'établissement</h2>

        <div class="form-grid">
            <div>
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

                    {% if errors.company_id is defined %}
                    <p>{{ errors.company_id }}</p>
                    {% endif %}
                </div>
            </div>
        </div>
    </section>

    <section class="main-container">
        <h2 class="card__title">Adresse et coordonnées</h2>

        <div class="form-grid">
            {% set fields = {
                address: {
                    label: 'Adresse',
                    type: 'text',
                    max: 255
                },
                unit: {
                    label: 'Numéro de local',
                    type: 'text',
                    max: 50
                },
                postal_code: {
                    label: 'Code postal',
                    type: 'text',
                    max: 30
                },
                city: {
                    label: 'Ville',
                    type: 'text',
                    max: 100
                },
                region: {
                    label: 'Province ou région',
                    type: 'text',
                    max: 100
                },
                country: {
                    label: 'Pays',
                    type: 'text',
                    max: 100
                },
                phone: {
                    label: 'Téléphone',
                    type: 'tel',
                    max: 30
                },
                email: {
                    label: 'Courriel',
                    type: 'email',
                    max: 255
                }
            } %}

            {% for field, settings in fields %}
            <div class="form-field">
                <label class="form-label" for="{{ field }}">
                    {{ settings.label }}
                </label>

                <input
                    class="form-input"
                    id="{{ field }}"
                    name="{{ field }}"
                    type="{{ settings.type }}"
                    value="{{ values[field] }}"
                    maxlength="{{ settings.max }}">

                {% if errors[field] is defined %}
                <p>{{ errors[field] }}</p>
                {% endif %}
            </div>
            {% endfor %}
        </div>
    </section>

    <div class="data-table__actions">
        <button class="button button--primary" type="submit">
            {{ submitLabel }}
        </button>

        <a class="button button--secondary" href="{{ base }}/locations">
            Annuler
        </a>
    </div>
</form>

{% if locationId %}
<section class="main-container">
    <h2 class="card__title">Supprimer l'établissement</h2>

    <p>
        La suppression est définitive. Un établissement utilisé par
        un contact ou un projet ne peut pas être supprimé.
    </p>

    <form method="post" action="{{ base }}/locations/delete">
        <input type="hidden" name="id" value="{{ locationId }}">

        <button class="button button--secondary" type="submit">
            Supprimer définitivement l'établissement
        </button>
    </form>
</section>
{% endif %}
{% endblock %}