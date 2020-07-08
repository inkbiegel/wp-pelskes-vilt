<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Pelske
 */

?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=2bBMJvmO0w">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=2bBMJvmO0w">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=2bBMJvmO0w">
	<link rel="manifest" href="/site.webmanifest?v=2bBMJvmO0w">
	<link rel="mask-icon" href="/safari-pinned-tab.svg?v=2bBMJvmO0w" color="#d074fe">
	<link rel="shortcut icon" href="/favicon.ico?v=2bBMJvmO0w">
	<meta name="msapplication-TileColor" content="#9f00a7">
	<meta name="theme-color" content="#fffdee">
	<?php wp_head(); ?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-9535157-2"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());
	  gtag('config', 'UA-9535157-2', { 'anonymize_ip': true });
	</script>
</head>
<body <?php body_class(); ?>>
	<?php get_template_part( 'template-parts/symbol-defs.svg' ); ?>
	<a class="skip-link screen-reader-text focusable" href="#main"><?php esc_html_e( 'Skip to content', 'pelske' ); ?></a>
	<header id="masthead" class="site-header">
		<img class="site-header-img" src="<?php header_image(); ?>" alt="">
		<a href="/" class="site-logo"><?php get_template_part( 'template-parts/logo-pelskes-vilt_circle.svg' ); ?></a>
		<nav id="site-nav" class="main-navigation">
			<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e( 'Menu', 'pelske' ); ?><span class="icon-burger"></span></button>
			<?php
				wp_nav_menu( array(
					'theme_location' => 'menu-1',
					'menu_id'        => 'primary-menu',
				) );
			?>
		</nav>
	</header>