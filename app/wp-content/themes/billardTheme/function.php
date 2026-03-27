<?php

function billard_theme_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'billard_theme_setup');

function billard_enqueue_styles()
{
    wp_enqueue_style('main-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'billard_enqueue_styles');

// Désactiver la barre admin WordPress en front
add_filter('show_admin_bar', '__return_false');

// Redirection après logout vers /login
function billard_logout_redirect()
{
    wp_redirect(home_url('/login'));
    exit;
}
add_action('wp_logout', 'billard_logout_redirect');

// Bloquer l'accès à wp-login.php et rediriger vers notre page
function billard_block_wp_login() {
    if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false
        && !is_admin()) {
        wp_redirect(home_url('/login'));
        exit;
    }
}
add_action('init', 'billard_block_wp_login');

// Handler AJAX : Connexion
function billard_ajax_login()
{
    check_ajax_referer('billard_nonce', 'nonce');

    $username = sanitize_text_field($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) && $_POST['remember'] === 'true';

    $user = wp_signon([
        'user_login' => $username,
        'user_password' => $password,
        'remember' => $remember,
    ], false);

    if (is_wp_error($user)) {
        wp_send_json_error(['message' => 'Identifiants incorrects.']);
    }

    wp_send_json_success(['redirect' => home_url('/')]);
}
add_action('wp_ajax_nopriv_billard_login', 'billard_ajax_login');

// Handler AJAX : Inscription
function billard_ajax_register()
{
    check_ajax_referer('billard_nonce', 'nonce');

    if (!get_option('users_can_register')) {
        wp_send_json_error(['message' => 'Les inscriptions sont désactivées.']);
    }

    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    // Validations
    if (empty($username) || empty($email) || empty($password)) {
        wp_send_json_error(['message' => 'Tous les champs sont obligatoires.']);
    }
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Adresse email invalide.']);
    }
    if ($password !== $password2) {
        wp_send_json_error(['message' => 'Les mots de passe ne correspondent pas.']);
    }
    if (strlen($password) < 8) {
        wp_send_json_error(['message' => 'Le mot de passe doit faire au moins 8 caractères.']);
    }
    if (username_exists($username)) {
        wp_send_json_error(['message' => 'Ce pseudo est déjà pris.']);
    }
    if (email_exists($email)) {
        wp_send_json_error(['message' => 'Cet email est déjà utilisé.']);
    }

    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => 'Erreur lors de la création du compte.']);
    }

    // Connexion automatique après inscription
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, false);

    wp_send_json_success(['redirect' => home_url('/')]);
}
add_action('wp_ajax_nopriv_billard_register', 'billard_ajax_register');

// Enregistrement des menus de navigation
function mytheme_register_nav_menu()
{
    register_nav_menus(array(
        'primary_menu' => __('Primary Menu', 'text_domain'),
        'footer_menu' => __('Footer Menu', 'text_domain'),
    ));
}
add_action('after_setup_theme', 'mytheme_register_nav_menu');