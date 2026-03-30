<?php get_header(); ?>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/home.css">

<main class="flexcenter">
    <?php if (is_user_logged_in()):
        $user = wp_get_current_user();
        ?>
        <section class="welcome-banner">
            <h2>Prêt à jouer, <?php echo esc_html($user->display_name); ?></h2>
            <p>Le billard 3D arrive bientôt...</p>
            <div class="welcome-actions">
                <a href="<?php echo home_url('/billard'); ?>" class="btn-play">Jouer →</a>
                <a href="<?php echo wp_logout_url(home_url('/login')); ?>" class="btn-logout-index">
                    Déconnexion
                </a>
            </div>
        </section>

    <?php else: ?>

        <div class="infoHome">
            <section class="hero">
                <div class="hero-orbs">
                    <div class="orb orb--1"></div>
                    <div class="orb orb--2"></div>
                    <div class="orb orb--3"></div>
                </div>

                <div class="hero-content">
                    <span class="hero-badge">🎱 Billard 3D en ligne</span>
                    <h1 class="hero-title">
                        Jouez au billard<br>
                        <span class="hero-accent">comme jamais auparavant</span>
                    </h1>
                    <p class="hero-subtitle">
                        Une expérience immersive en 3D directement dans votre navigateur.
                        Affrontez d'autres joueurs et grimpez dans le classement.
                    </p>
                    <div class="hero-actions">
                        <a href="<?php echo home_url('/register'); ?>" class="btn-primary">Créer un compte</a>
                        <a href="<?php echo home_url('/login'); ?>" class="btn-ghost">Se connecter</a>
                    </div>
                </div>

                <div class="hero-balls">
                    <div class="deco-ball deco-ball--red"></div>
                    <div class="deco-ball deco-ball--yellow"></div>
                    <div class="deco-ball deco-ball--blue"></div>
                    <div class="deco-ball deco-ball--white"></div>
                </div>
            </section>

            <section class="features">
                <div class="feature-card">
                    <div class="feature-icon">🎮</div>
                    <h3>Physique réaliste</h3>
                    <p>Moteur de physique 3D avec rebonds et effets de masse fidèles au vrai billard.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🏆</div>
                    <h3>Classement global</h3>
                    <p>Chaque partie compte. Accumulez des points et grimpez dans le leaderboard.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Vos statistiques</h3>
                    <p>Suivez vos performances : taux de victoire, meilleure série, points marqués.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>100% navigateur</h3>
                    <p>Aucune installation requise. Jouez depuis n'importe quel appareil.</p>
                </div>
            </section>

            <section class="cta-section">
                <h2>Prêt à jouer ?</h2>
                <p>Rejoignez la communauté et commencez à jouer gratuitement dès maintenant.</p>
                <a href="<?php echo home_url('/register'); ?>" class="btn-primary">Commencer →</a>
            </section>
        </div>

    <?php endif; ?>
</main>
<?php get_footer(); ?>