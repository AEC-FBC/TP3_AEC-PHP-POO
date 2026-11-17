{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ pageTitle }}</h1>
        <p>Renseignez les informations et les services du projet.</p>
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

<form method="post" action="{{ formAction }}" enctype="multipart/form-data">
    <section class="main-container">
        <h2 class="card__title">Informations du projet</h2>

        <div class="form-grid">
            <div>
                <div class="form-field">
                    <label class="form-label" for="title">
                        Titre - obligatoire
                    </label>

                    <input
                        class="form-input"
                        id="title"
                        name="title"
                        type="text"
                        value="{{ values.title }}"
                        minlength="3"
                        maxlength="150"
                        required>

                    {% if errors.title is defined %}
                    <p>{{ errors.title }}</p>
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

                <div class="form-field">
                    <label class="form-label" for="planned_start_date">
                        Début prévu
                    </label>

                    <input
                        class="form-input"
                        id="planned_start_date"
                        name="planned_start_date"
                        type="date"
                        value="{{ values.planned_start_date }}">

                    {% if errors.planned_start_date is defined %}
                    <p>{{ errors.planned_start_date }}</p>
                    {% endif %}
                </div>

                <div class="form-field">
                    <label class="form-label" for="planned_end_date">
                        Fin prévue
                    </label>

                    <input
                        class="form-input"
                        id="planned_end_date"
                        name="planned_end_date"
                        type="date"
                        value="{{ values.planned_end_date }}">

                    {% if errors.planned_end_date is defined %}
                    <p>{{ errors.planned_end_date }}</p>
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

                    {% if errors.company_location_id is defined %}
                    <p>{{ errors.company_location_id }}</p>
                    {% endif %}
                </div>

                <div class="form-field">
                    <label class="form-label" for="primary_contact_id">
                        Contact principal - obligatoire
                    </label>

                    <select
                        class="form-select"
                        id="primary_contact_id"
                        name="primary_contact_id"
                        required>
                        <option value="">Choisir un contact</option>

                        {% for contact in contacts %}
                        <option
                            value="{{ contact.id }}"
                            {% if values.primary_contact_id == contact.id %}selected{% endif %}>
                            {{ companyNames[contact.company_id]|default('') }}
                            - {{ contact.first_name }} {{ contact.last_name }}
                        </option>
                        {% endfor %}
                    </select>

                    <p>
                        Choisissez un contact de l'entreprise sélectionnée.
                    </p>

                    {% if errors.primary_contact_id is defined %}
                    <p>{{ errors.primary_contact_id }}</p>
                    {% endif %}
                </div>

                <div class="form-field">
                    <label class="form-label" for="status_id">
                        Statut - obligatoire
                    </label>

                    <select
                        class="form-select"
                        id="status_id"
                        name="status_id"
                        required>
                        <option value="">Choisir un statut</option>

                        {% for status in statuses %}
                        <option
                            value="{{ status.id }}"
                            {% if values.status_id == status.id %}selected{% endif %}>
                            {{ status.name }}
                        </option>
                        {% endfor %}
                    </select>

                    {% if errors.status_id is defined %}
                    <p>{{ errors.status_id }}</p>
                    {% endif %}
                </div>
            </div>
        </div>
    </section>

    <section class="main-container">
        <h2 class="card__title">Services du projet</h2>
        <p>Cochez les services associés à ce projet.</p>

        <div class="form-field">
            {% for service in services %}
            <div>
                <label for="service_{{ service.id }}">
                    <input
                        id="service_{{ service.id }}"
                        type="checkbox"
                        name="service_ids[]"
                        value="{{ service.id }}"
                        {% if service.id in values.service_ids %}checked{% endif %}>
                    {{ service.name }}
                </label>
            </div>
            {% else %}
            <p>Aucun service disponible.</p>
            {% endfor %}

            {% if errors.service_ids is defined %}
            <p>{{ errors.service_ids }}</p>
            {% endif %}
        </div>
    </section>
    <section class="main-container">
        <h2 class="card__title">Image du projet</h2>

        <div class="form-field">
            <label class="form-label" for="project_image">
                Sélectionner une image
            </label>

            <input
                class="form-input"
                type="file"
                id="project_image"
                name="project_image"
                accept="image/jpeg,image/png,image/webp">

            <p>Formats acceptés : JPEG, PNG et WebP. Maximum 2 Mo.</p>

            {% if errors.project_image is defined %}
            <p>{{ errors.project_image }}</p>
            {% endif %}
        </div>
    </section>
    <div class="data-table__actions">
        <button type="submit" class="button button--primary">
            {{ submitLabel }}
        </button>

        {% if fromCompany %}
        <a class="button button--secondary" href="{{ base }}/companies/show?id={{ values.company_id }}">
            Retour à l'entreprise
        </a>
        {% else %}
        <a class="button button--secondary" href="{{ base }}/projects">
            Annuler
        </a>
        {% endif %}
    </div>
</form>
{% endblock %}