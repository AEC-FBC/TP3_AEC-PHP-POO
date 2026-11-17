{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Connexion</h1>
        <p>Connectez-vous à votre espace Craft Industria.</p>
    </div>
</div>

<section class="main-container">
    <h2 class="card__title">Accéder à mon compte</h2>

    {% if error is defined and error %}
    <p class="form-error">{{ error }}</p>
    {% endif %}

    <form action="{{ base }}/login" method="POST">
        <div class="form-group">
            <label for="email">Adresse courriel</label>
            <input
                type="email"
                id="email"
                name="email"
                autocomplete="username"
                required>
        </div>

        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required>
        </div>

        <button type="submit" class="button button--primary">
            Se connecter
        </button>
    </form>
</section>
{% endblock %}