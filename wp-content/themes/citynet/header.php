<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php
    wp_body_open();
    
    $template = (string) apply_filters( 'citynet_site_header_template', citynet_get_device() );
    $args = (array) apply_filters( 'citynet_site_header_template_args', array(), $template );
    citynet_get_site_template( "header/$template" , $args ); ?>
    <main>