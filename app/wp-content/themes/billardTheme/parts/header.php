<?php
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo home_url('/'); ?>" class="site-title">🎱 Billard 3D</a>

        <div class="user-area">
            <?php if (is_user_logged_in()):
                $current_user = wp_get_current_user();
            ?>
                <span class="user-greeting">
                    Bienvenue, <strong><?php echo esc_html($current_user->display_name); ?></strong>
                </span>
                <a href="<?php echo wp_logout_url(home_url('/login')); ?>" class="btn-logout">
                    Déconnexion
                </a>
            <?php else: ?>
                <a href="<?php echo home_url('/login'); ?>" class="btn-login-header">
                    Se connecter
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>