<?php
/*
Plugin Name: Thesis Plugin
Plugin URI: 
Description: Database for WPForms Submissions and export to PDF
Author: Chris Kolonas
Author URI: http://https://github.com/criskolonas
Version: 0.1
*/


/*
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require(ABSPATH . 'wp-content/plugins/thesis-plugin/libs/fpdf184/fpdf.php');
require(ABSPATH . 'wp-content/plugins/thesis-plugin/includes/class-pdf.php');
require(ABSPATH . 'wp-content/plugins/thesis-plugin/includes/form-submission.php');

if(is_admin()){
	require(ABSPATH . 'wp-content/plugins/thesis-plugin/includes/admin.php');
}
*/

function testfunc($sections,$form_data){
    $sections['pdf_settings'] = esc_html__( 'PDF Settings', 'wpforms-lite' );
    return $sections;

}
add_filter( 'wpforms_builder_settings_sections','testfunc',3, 2);


function wpforms_pdfsettings_section_content($instance){
    wpforms_panel_field(
         'text',
         'pdf_settings',
         'header_photo',
         $instance->form_data,
         esc_html__( 'Header Image URL', 'wpforms-lite' ),
         [
            'smarttags' => [
                'type '=> 'all',
            ],
            'parent' => 'pdf_settings'
         ]


    );
    wpforms_panel_field(
        'text',
        'pdf_settings',
        'footer_photo',
        $instance->form_data,
        esc_html__( 'Footer Image URL', 'wpforms-lite' )

   );
}
add_action('wpforms_form_settings_panel_content','wpforms_pdfsettings_section_content',10);