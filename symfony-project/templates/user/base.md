<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <title>Inscription - AdopteUnDev</title>
</head>

<body>

  <div class="register-container d-flex justify-content-center align-items-center">
    <div class="register-card shadow p-4 rounded">
      <h2 class="text-center mb-4">Créer un profil</h2>
      <form action="/register" method="POST" enctype="multipart/form-data">
        <!-- Choix du type d'utilisateur -->
        {# <div class="form-group">
          <label for="userType">Vous êtes :</label>
          <select id="userType" name="userType" class="form-control" required>
            <option value="developer">Développeur</option>
            <option value="company">Entreprise</option>
          </select>
        </div> #}

        <!-- Nom et prénom -->
        <div class="form-group">
          <label for="firstName">Prénom</label>
          <input type="text" id="firstName" name="firstName" class="form-control" placeholder="Votre prénom" required>
        </div>
        <div class="form-group">
          <label for="lastName">Nom</label>
          <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Votre nom" required>
        </div>

        <!-- Adresse e-mail -->
        <div class="form-group">
          <label for="email">Adresse e-mail</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Votre e-mail" required>
        </div>

        <!-- Mot de passe -->
        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Votre mot de passe"
            required>
        </div>
        <div class="form-group">
          <label for="confirmPassword">Confirmez le mot de passe</label>
          <input type="password" id="confirmPassword" name="confirmPassword" class="form-control"
            placeholder="Confirmez votre mot de passe" required>
        </div>

        <!-- Localisation -->
        <div class="form-group">
          <label for="location">Localisation</label>
          <input type="text" id="location" name="location" class="form-control" placeholder="Votre localisation">
        </div>

        <!-- Langages de programmation -->
        <div class="form-group">
          <label for="languages">Langages de programmation (développeurs uniquement) :</label>
          <select id="languages" name="languages[]" class="form-control" multiple>
            <option value="python">Python</option>
            <option value="javascript">JavaScript</option>
            <option value="java">Java</option>
            <option value="php">PHP</option>
            <option value="csharp">C#</option>
          </select>
          <small class="form-text text-muted">Maintenez la touche Ctrl (ou Command sur Mac) pour sélectionner
            plusieurs.</small>
        </div>

        <!-- Niveau d'expérience -->
        <div class="form-group">
          <label for="experience">Niveau d'expérience :</label>
          <input type="number" id="experience" name="experience" class="form-control" placeholder="Ex : 3 (sur 5)"
            min="1" max="5">
        </div>

        <!-- Salaire minimum souhaité -->
        <div class="form-group">
          <label for="salary">Salaire minimum souhaité (en €)</label>
          <input type="number" id="salary" name="salary" class="form-control" placeholder="Ex : 40000">
        </div>

        <!-- Courte biographie -->
        <div class="form-group">
          <label for="bio">Courte biographie :</label>
          <textarea id="bio" name="bio" class="form-control" rows="3" placeholder="Décrivez-vous brièvement"></textarea>
        </div>

        <!-- Avatar -->
        <div class="form-group">
          <label for="avatar">Avatar (photo de profil) :</label>
          <input type="file" id="avatar" name="avatar" class="form-control-file">
        </div>

        <!-- CGU -->
        <div class="form-check mb-3">
          <input type="checkbox" id="cgu" name="cgu" class="form-check-input" required>
          <label class="form-check-label" for="cgu">J'accepte les <a href="/cgu" target="_blank">conditions générales
              d'utilisation</a>.</label>
        </div>

        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        <p class="text-center mt-3">
          Déjà un compte ? <a href="{{path('app_inscription')}}">Se connecter</a>
        </p>
      </form>
    </div>
  </div>
</body>

</html>