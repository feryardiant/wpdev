<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package  Blank
 * @since    0.2.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php blank( 'template' )->skip_link(); ?>

<div id="page" <?php blank( 'template' )->wrapper_attr(); ?>>

	<header id="masthead" <?php blank( 'template' )->header_attr(); ?>>

		<?php blank( 'template' )->header(); ?>

	</header> <!-- #masthead -->

	<section id="content" <?php blank( 'template' )->content_attr(); ?>>

		<?php do_action( 'blank_before_main' ); ?>
