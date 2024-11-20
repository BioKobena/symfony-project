# AdopteUnDev

Bienvenue sur le projet **AdopteUnDev** ! Ce projet a pour objectif de créer une application web permettant aux développeurs et aux entreprises de créer des profils ou des fiches de postes et de les faire correspondre, à la manière d'un site de rencontre. L'objectif est d'optimiser la mise en relation entre développeurs et recruteurs via des fonctionnalités conviviales et innovantes.

![Logo AdopteUnDev](./docs/images/logo.png)

## Table des matières
1. [Description du projet](#description-du-projet)
2. [Fonctionnalités](#fonctionnalités)
3. [Installation et configuration](#installation-et-configuration)
4. [Structure du projet](#structure-du-projet)
5. [Technologies utilisées](#technologies-utilisées)
6. [Contributions](#contributions)
7. [Démonstration vidéo](#démonstration-vidéo)
8. [Licences et mentions légales](#licences-et-mentions-légales)

---

## Description du projet

**AdopteUnDev** est une application web qui aide les développeurs à trouver des opportunités en fonction de leur profil, tout en permettant aux entreprises de recruter des talents correspondant à leurs besoins. Inspirée des sites de rencontres, cette plateforme facilite la mise en relation en fonction de critères spécifiques.

### Objectifs
- Optimiser la mise en relation entre développeurs et recruteurs.
- Fournir une interface simple et intuitive.
- Proposer des fonctionnalités sociales et d'évaluation pour améliorer la transparence.

---

## Fonctionnalités

### 1. **Gestion des Utilisateurs**
- **Inscription distincte** : Choisissez entre développeur ou entreprise.
- **Profil public/privé** : Masquez vos informations sensibles (contact, salaire).
- **Rôles** : Gestion des droits utilisateurs via `ROLE_DEV` et `ROLE_COMPANY`.

### 2. **Création et Gestion des Profils/Fiches de Postes**
#### Pour les Développeurs :
- Nom, prénom, localisation.
- Langages de programmation (choix dans une liste).
- Niveau d'expérience.
- Salaire minimum souhaité.
- Avatar.

#### Pour les Entreprises :
- Titre du poste.
- Technologies recherchées.
- Niveau d'expérience requis.
- Salaire proposé.

### 3. **Système de Matching**
- Suggestions dynamiques de profils ou postes selon des critères (langages, salaire, localisation, etc.).

### 4. **Pages d'Accueil Dynamiques**
- Pour les entreprises : Profils les plus consultés, derniers profils créés.
- Pour les développeurs : Postes populaires, dernières offres publiées.

### 5. **Recherche Avancée**
- Filtre par critères : Langages, technologies, localisation, salaire, etc.

### 6. **Évaluations et Feedbacks**
- Évaluation des développeurs sur leur expérience.

### 7. **Fonctionnalités Sociales**
- Ajouter des profils ou fiches de postes à une liste de favoris.
- Messagerie intégrée (optionnelle).

### 8. **Suivi et Analyses**
- Statistiques utilisateurs : Nombre de vues, top profils/postes.

---

## Installation et configuration

### 1. **Prérequis**
- Docker
- Composer
- Symfony CLI

### 2. **Étapes d'installation**
Clonez ce dépôt sur votre machine locale.

```bash
git clone https://github.com/BioKobena/symfony-project.git .

