{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>Modifier une tâche</h1>
        <p>Mets à jour les informations et les dates prévues.</p>
    </div>
</div>

{% include "task/form.php" %}
{% endblock %}