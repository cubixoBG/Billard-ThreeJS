<?php get_header(); ?>

<main class="flexcenter">
    <?php if (is_user_logged_in()):
        $user = wp_get_current_user();
        ?>
        <div class="welcome-banner">
            <h2>Prêt à jouer, <?php echo esc_html($user->display_name); ?></h2>
            <p>Le billard 3D arrive bientôt...</p>
            <div class="welcome-actions">
                <a href="#" class="btn-play">Jouer →</a>
                <a href="<?php echo wp_logout_url(home_url('/login')); ?>" class="btn-logout-index">
                    Déconnexion
                </a>
            </div>
        </div>

    <?php else: ?>

        <div class="welcome-banner">
            <h2>Bienvenue sur Billard 3D 🎱</h2>
            <p>Connectez-vous ou créez un compte pour accéder au jeu et suivre vos stats.</p>
            <div class="welcome-actions">
                <a href="<?php echo home_url('/login'); ?>" class="btn-play">Se connecter</a>
                <a href="<?php echo home_url('/register'); ?>" class="btn-secondary">S'inscrire</a>
            </div>
        </div>

    <?php endif; ?>
</main>
<?php get_footer(); ?>