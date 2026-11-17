{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Nouvelle tâche</h1>
        <p>Associe une tâche à un projet et à un statut.</p>
    </div>
</div>

{% include "task/form.php" %}
{% endblock %}