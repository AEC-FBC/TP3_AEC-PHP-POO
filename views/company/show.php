{% extends "layouts/base.php" %}

{% block content %}
<div class="page-heading">
    <div>
        <h1>{{ company.name }}</h1>
        <p>{{ company.website|default('Aucun site Web renseigné.', true) }}</p>
    </div>

    <div class="data-table__actions">
        <a
            class="button button--primary"
            href="{{ base }}/companies/edit?id={{ company.id }}">
            Modifier l'entreprise
        </a>

        <a class="button button--secondary" href="{{ base }}/companies">
            Retour aux entreprises
        </a>
    </div>
</div>

{% if notice %}
<div class="alert alert--success">
    <p class="alert__text">{{ notice }}</p>
</div>
{% endif %}

<section class="main-container">
    <div class="page-heading">
        <div>
            <h2 class="card__title">Établissements</h2>
            <p>Les établissements de cette entreprise.</p>
        </div>

        <a
            class="button button--primary"
            href="{{ base }}/locations/create?company_id={{ company.id }}">
            Ajouter un établissement
        </a>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                {% for location in locations %}
                <tr>
                    <td>{{ location.name }}</td>
                    <td>{{ location.address|default('-', true) }}</td>
                    <td>{{ location.city|default('-', true) }}</td>
                    <td>{{ location.phone|default('-', true) }}</td>
                    <td>
                        <a
                            class="button button--secondary"
                            href="{{ base }}/locations/edit?id={{ location.id }}">
                            Modifier
                        </a>
                    </td>
                </tr>
                {% else %}
                <tr>
                    <td colspan="5" class="data-table__empty">
                        Aucun établissement pour cette entreprise.
                    </td>
                </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
</section>

<section class="main-container">
    <h2 class="card__title">Contacts</h2>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Contact</th>
                    <th>Courriel</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                {% for contact in contacts %}
                <tr>
                    <td>{{ contact.first_name }} {{ contact.last_name }}</td>
                    <td>{{ contact.email|default('-', true) }}</td>
                    <td>{{ contact.phone|default('-', true) }}</td>
                    <td>
                        <a
                            class="button button--secondary"
                            href="{{ base }}/contacts/edit?id={{ contact.id }}">
                            Modifier
                        </a>
                    </td>
                </tr>
                {% else %}
                <tr>
                    <td colspan="4" class="data-table__empty">
                        Aucun contact pour cette entreprise.
                    </td>
                </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
</section>

<section class="main-container">
    <h2 class="card__title">Projets</h2>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Projet</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                {% for project in projects %}
                <tr>
                    <td>{{ project.title }}</td>
                    <td>{{ project.status_name }}</td>
                    <td>
                        <a
                            class="button button--secondary"
                            href="{{ base }}/projects/show?id={{ project.id }}">
                            Voir
                        </a>
                    </td>
                </tr>
                {% else %}
                <tr>
                    <td colspan="3" class="data-table__empty">
                        Aucun projet pour cette entreprise.
                    </td>
                </tr>
                {% endfor %}
            </tbody>
        </table>
    </div>
</section>

<section class="main-container">
    <h2 class="card__title">Supprimer l'entreprise</h2>

    {% if locations or contacts or projects %}
    <p>
        Cette entreprise possède encore des établissements, des contacts
        ou des projets. Elle ne peut pas être supprimée.
    </p>
    {% else %}
    <p>Attention : cette suppression est définitive.</p>

    <form method="post" action="{{ base }}/companies/delete">
        <input type="hidden" name="id" value="{{ company.id }}">

        <button class="button button--secondary" type="submit">
            Supprimer définitivement l'entreprise
        </button>
    </form>
    {% endif %}
</section>
{% endblock %}