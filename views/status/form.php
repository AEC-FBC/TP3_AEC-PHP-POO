{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ pageTitle }}</h1>
        <p>Définissez le nom, la couleur et l'ordre du statut.</p>
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
        <h2 class="card__title">Informations du statut</h2>

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
                        maxlength="100"
                        required>

                    {% if errors.name is defined %}
                    <p>{{ errors.name }}</p>
                    {% endif %}
                </div>
            </div>

            <div>
                <div class="form-field">
                    <label class="form-label" for="color">
                        Couleur - obligatoire
                    </label>

                    <input
                        id="color"
                        name="color"
                        type="color"
                        value="{{ errors.color is defined ? '#667579' : values.color }}"
                        required>

                    {% if errors.color is defined %}
                    <p>{{ errors.color }}</p>
                    {% endif %}
                </div>

                <div class="form-field">
                    <label class="form-label" for="display_order">
                        Ordre d'affichage - obligatoire
                    </label>

                    <input
                        class="form-input"
                        id="display_order"
                        name="display_order"
                        type="number"
                        value="{{ values.display_order }}"
                        min="0"
                        max="2147483647"
                        step="1"
                        required>
                    <p>
                        Les nombres les plus petits apparaissent en premier.
                    </p>

                    {% if errors.display_order is defined %}
                    <p>{{ errors.display_order }}</p>
                    {% endif %}
                </div>
            </div>
        </div>
    </section>

    <div class="data-table__actions">
        <button class="button button--primary" type="submit">
            {{ submitLabel }}
        </button>

        <a class="button button--secondary" href="{{ base }}/statuses">
            Annuler
        </a>
    </div>
</form>

{% if statusId %}
<section class="main-container">
    <h2 class="card__title">Supprimer le statut</h2>

    <p>
        La suppression est définitive. Un statut utilisé par un projet
        ou une tâche ne peut pas être supprimé.
    </p>

    <form method="post" action="{{ base }}/statuses/delete">
        <input type="hidden" name="id" value="{{ statusId }}">

        <button class="button button--secondary" type="submit">
            Supprimer définitivement le statut
        </button>
    </form>
</section>
{% endif %}
{% endblock %}