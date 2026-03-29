<?php
/*
 * Template Name: Page Login
 */

if (is_user_logged_in()) {
    wp_redirect(home_url('/'));
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion — Billard 3D</title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/login.css">
</head>
<body>

<div class="auth-container flexcenter">

    <div class="auth-header">
        <div class="ball-icon"></div>
        <h2>Billard 3D</h2>
        <p>Connectez-vous pour jouer</p>
    </div>

    <div class="auth-card">
        <h2>Connexion</h2>

        <div class="alert error" id="alert-error"></div>
        <div class="alert success" id="alert-success"></div>

        <div class="field">
            <label for="username">Identifiant ou email</label>
            <input type="text" id="username" placeholder="Votre nom ou email" autocomplete="username">
        </div>

        <div class="field">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" placeholder="••••••••" autocomplete="current-password">
        </div>

        <button class="btn-submit" id="btn-login">
            <span class="btn-text">Jouer →</span>
            <span class="spinner" id="spinner"></span>
        </button>

        <div class="auth-footer">
            Pas encore de compte ?
            <a href="<?php echo home_url('/register'); ?>">S'inscrire gratuitement</a>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</body>
</html>