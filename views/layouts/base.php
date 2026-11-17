<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ pageTitle|default('Accueil') }} - Craft Industria</title>

    <link rel="stylesheet" href="{{ asset }}/css/style.css">
</head>

<body>
    <aside class="sidebar">
        <a class="sidebar__brand" href="{{ base }}/">
            <span class="sidebar__logo">CI</span>

            <span>
                <strong class="sidebar__name">Craft Industria</strong>
                <span class="sidebar__subtitle">Gestion de projets</span>
            </span>
        </a>

        <nav class="sidebar__nav">
            <a class="sidebar__link" href="{{ base }}/">
                Accueil
            </a>
            {% if currentUser %}
            <a class="sidebar__link" href="{{ base }}/tasks">
                <img class="icon" src="{{ asset }}/icons/folder.svg" alt="">
                Tâches
            </a>

            <a class="sidebar__link" href="{{ base }}/projects">
                <img class="icon" src="{{ asset }}/icons/building.svg" alt="">
                Projets
            </a>

            <a class="sidebar__link" href="{{ base }}/companies">
                <img class="icon" src="{{ asset }}/icons/building.svg" alt="">
                Entreprises
            </a>

            {% if currentUser.role == 'admin' %}

            <a class="sidebar__link" href="{{ base }}/statuses">
                Statuts
            </a>

            <a class="sidebar__link" href="{{ base }}/services">
                Services
            </a>

            <a class="sidebar__link" href="{{ base }}/admin">
                Administration
            </a>
            <a class="sidebar__link" href="{{ base }}/admin/logs">
                Journal de bord
            </a>
            {% endif %}
            {% endif %}
        </nav>
    </aside>

    <header class="topbar">
        <span class="topbar__brand">Pixel & Craft</span>
        <span class="topbar__page">{{ pageTitle|default('Accueil') }}</span>
        <div class="topbar__auth">
            {% if currentUser is defined and currentUser %}
            <span class="topbar__username">
                {{ currentUser.name }}
            </span>

            <form action="{{ base }}/logout" method="post">
                <button class="topbar__auth-button" type="submit">
                    Déconnexion
                </button>
            </form>
            {% else %}
            <a class="topbar__auth-button" href="{{ base }}/login">
                Connexion
            </a>
            {% endif %}
        </div>
    </header>

    <div class="app-layout">
        <main class="main-content">
            <div class="page-body">
                {% block content %}{% endblock %}
            </div>
        </main>
    </div>
</body>

</html>