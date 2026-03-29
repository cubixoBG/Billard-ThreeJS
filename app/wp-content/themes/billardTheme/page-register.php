<?php
/*
 * Template Name: Page Register
 */

// if (is_user_logged_in()) {
//     wp_redirect(home_url('/'));
//     exit;
// }
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription — Billard 3D</title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/login.css}">
</head>
<body>

<div class="auth-container flexcenter">

    <div class="auth-header">
        <div class="ball-icon"></div>
        <h2>Billard 3D</h2>
        <p>Créez votre compte</p>
    </div>

    <section class="auth-card">
        <h2>Inscription</h2>

        <div class="alert error"   id="alert-error"></div>
        <div class="alert success" id="alert-success"></div>

        <div class="field">
            <label for="username">Nom</label>
            <input type="text" id="username" placeholder="Votre nom" autocomplete="username">
        </div>

        <div class="field">
            <label for="email">Adresse email</label>
            <input type="email" id="email" placeholder="email@example.com" autocomplete="email">
        </div>

        <div class="field">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" placeholder="********" autocomplete="new-password">
            <div class="password-strength">
                <div class="password-strength-bar" id="strength-bar"></div>
            </div>
            <div class="strength-text" id="strength-text"></div>
        </div>

        <div class="field">
            <label for="password2">Confirmer le mot de passe</label>
            <input type="password" id="password2" placeholder="********" autocomplete="new-password">
        </div>

        <button class="btn-submit" id="btn-register" style="margin-top: 8px;">
            <span class="btn-text">Créer mon compte</span>
            <span class="spinner" id="spinner"></span>
        </button>

        <div class="auth-footer">
            Déjà un compte ?
            <a href="<?php echo home_url('/login'); ?>">Se connecter</a>
        </div>
    </div>
</section>
<?php wp_footer(); ?>
</body>
</html>