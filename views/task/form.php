{% if errors is not empty %}
<section class="alert alert--error">
    <h2 class="alert__title">
        Vérifie les informations saisies
    </h2>

    <ul>
        {% for field, message in errors %}
        <li>{{ message }}</li>
        {% endfor %}
    </ul>
</section>
{% endif %}

<form method="post" action="{{ formAction }}">
    <section class="main-container">
        <h2 class="card__title">Informations de la tâche</h2>

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
                        required
                        minlength="3"
                        maxlength="255">

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
                        maxlength="5000">{{ values.description }}</textarea>

                    {% if errors.description is defined %}
                    <p>{{ errors.description }}</p>
                    {% endif %}
                </div>
            </div>

            <div>
                <div class="form-field">
                    <label class="form-label" for="project_id">
                        Projet - obligatoire
                    </label>

                    <select
                        class="form-select"
                        id="project_id"
                        name="project_id"
                        required>
                        <option value="">Choisir un projet</option>

                        {% for project in projects %}
                        <option
                            value="{{ project.id }}"
                            {% if values.project_id == project.id %}
                            selected
                            {% endif %}>
                            {{ project.title }}
                        </option>
                        {% endfor %}
                    </select>

                    {% if errors.project_id is defined %}
                    <p>{{ errors.project_id }}</p>
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
                            {% if values.status_id == status.id %}
                            selected
                            {% endif %}>
                            {{ status.name }}
                        </option>
                        {% endfor %}
                    </select>

                    {% if errors.status_id is defined %}
                    <p>{{ errors.status_id }}</p>
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
        </div>
    </section>

    <div class="data-table__actions">
        <button type="submit" class="button button--primary">
            {{ submitLabel }}
        </button>

        {% if fromProject %}
        <a class="button button--secondary"
            href="{{ base }}/projects/show?id={{ values.project_id }}{% if fromCompany %}&from=company{% endif %}">
            Retour au projet
        </a>
        {% else %}
        <a class="button button--secondary" href="{{ base }}/tasks">
            Annuler
        </a>
        {% endif %}
    </div>
</form>