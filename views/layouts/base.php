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

            <a class="sidebar__link" href="{{ base }}/contacts">
                Contacts
            </a>


            <a class="sidebar__link" href="{{ base }}/statuses">
                Statuts
            </a>

            <a class="sidebar__link" href="{{ base }}/services">
                Services
            </a>
        </nav>
    </aside>

    <header class="topbar">
        <span class="topbar__brand">Pixel & Craft</span>
        <span class="topbar__page">{{ pageTitle|default('Accueil') }}</span>
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