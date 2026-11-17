{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <h1>{{ pageTitle|default('Erreur') }}</h1>
</div>

<section class="alert alert--error">
    <p class="alert__text">{{ msg }}</p>
</section>

<p>
    {% if currentUser %}
    <a class="button button--secondary" href="{{ base }}/tasks">
        Retour aux tâches
    </a>
    {% else %}
    <a class="button button--secondary" href="{{ base }}/">
        Retour à l'accueil
    </a>
    {% endif %}
</p>
{% endblock %}