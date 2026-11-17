{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ user ? "Modifier un utilisateur" : "Ajouter un utilisateur" }}</h1>
        <p>Gestion des comptes Craft Industria.</p>
    </div>
</div>

<section class="main-container">
    <h2 class="card__title">
        {{ user ? "Informations de l'utilisateur" : "Nouvel utilisateur" }}
    </h2>

    {% if error %}
    <p class="form-error">{{ error }}</p>
    {% endif %}

    <form
        method="POST"
        action="{{ base }}/admin/users/{{ user ? 'edit' : 'create' }}">
        {% if user %}
        <input type="hidden" name="id" value="{{ user.id }}">
        {% endif %}

        <div class="form-group">
            <label for="name">Nom</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ user.name|default('') }}"
                maxlength="100"
                required>
        </div>

        <div class="form-group">
            <label for="email">Adresse courriel</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ user.email|default('') }}"
                maxlength="150"
                required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                autocomplete="new-password"
                {% if not user %}required{% endif %}>

            {% if user %}
            <small>Laissez vide pour conserver le mot de passe actuel.</small>
            {% else %}
            <small>Minimum 8 caractères.</small>
            {% endif %}
        </div>

        <div class="form-group">
            <label for="role">Rôle</label>
            <select id="role" name="role" required>
                <option
                    value="client"
                    {% if user.role|default('client') == 'client' %}selected{% endif %}>
                    Client
                </option>
                <option
                    value="admin"
                    {% if user.role|default('client') == 'admin' %}selected{% endif %}>
                    Administrateur
                </option>
            </select>
        </div>

        <button type="submit" class="button button--primary">
            {{ user ? "Enregistrer les modifications" : "Créer l'utilisateur" }}
        </button>

        <a class="button" href="{{ base }}/admin/users">
            Annuler
        </a>
    </form>
</section>
{% endblock %}