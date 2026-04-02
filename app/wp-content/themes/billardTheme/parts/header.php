<?php
/**
 * Header du thème Billard
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php wp_title('|', true, 'right'); ?><?php bloginfo('name'); ?></title>
    <?php wp_head(); ?>
</head>
<body>
    <?php wp_body_open(); ?>
    <header class="flexcenter">
        <h1><a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a></h1>
    </header>