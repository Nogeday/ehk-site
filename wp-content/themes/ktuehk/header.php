<?php
/**
 * Site header.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main"><?php esc_html_e( 'İçeriğe geç', 'ktuehk' ); ?></a>

<header class="site-header" data-site-header>
	<div class="container site-header__bar">
		<?php ktuehk_brand( 'header' ); ?>

		<nav class="nav" id="site-nav" aria-label="<?php esc_attr_e( 'Ana menü', 'ktuehk' ); ?>" data-nav>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'nav__list',
					'depth'          => 2,
					'fallback_cb'    => 'ktuehk_menu_fallback',
				)
			);
			?>
			<form class="nav__search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<label class="screen-reader-text" for="nav-search-input"><?php esc_html_e( 'Sitede ara', 'ktuehk' ); ?></label>
				<?php echo ktuehk_icon( 'search', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<input id="nav-search-input" class="nav__search-input" type="search" name="s" placeholder="<?php esc_attr_e( 'Yazı, proje veya etkinlik ara…', 'ktuehk' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
			</form>
		</nav>

		<div class="site-header__actions">
			<button type="button" class="icon-btn search-toggle" aria-expanded="false" aria-controls="site-search" data-search-toggle>
				<?php echo ktuehk_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<span class="screen-reader-text"><?php esc_html_e( 'Sitede ara', 'ktuehk' ); ?></span>
			</button>
			<button type="button" class="icon-btn nav-toggle" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
				<span class="nav-toggle__open"><?php echo ktuehk_icon( 'menu', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="nav-toggle__close"><?php echo ktuehk_icon( 'x', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
				<span class="screen-reader-text" data-nav-toggle-label><?php esc_html_e( 'Menüyü aç', 'ktuehk' ); ?></span>
			</button>
		</div>
	</div>

	<div class="search-panel" id="site-search" hidden data-search-panel>
		<div class="container">
			<?php get_search_form( array( 'aria_label' => __( 'Site araması', 'ktuehk' ) ) ); ?>
			<p class="search-panel__hint"><?php esc_html_e( 'Yazılar, projeler ve etkinlikler içinde arar.', 'ktuehk' ); ?></p>
		</div>
	</div>
</header>

<main id="main" class="site-main" tabindex="-1">
