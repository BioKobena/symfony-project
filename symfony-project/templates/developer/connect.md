{% extends 'base.html.twig' %}

{% block body %}
<h1>Connexion développeur</h1>

{% for message in app.flashes('error') %}
    <div class="alert alert-danger">{{ message }}</div>
{% endfor %}

<form method="post">
    <label for="email">Email :</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>
{% endblock %}
