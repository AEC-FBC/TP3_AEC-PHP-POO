{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <h1>{{ pageTitle|default('Erreur') }}</h1>
</div>

<section class="alert alert--error">
    <p class="alert__text">{{ msg }}</p>
</section>

<p>
    <a class="button button--secondary" href="{{ base }}/tasks">
        Retour aux tâches
    </a>
</p>
{% endblock %}