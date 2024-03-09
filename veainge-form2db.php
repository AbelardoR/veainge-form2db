<?php

/*
Plugin Name: Form2db
Description: Stores form data in the database and displays a table.
Version: 1.0
Author: Abelardo Recalde
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

function tableName($prefix="") {
    return  $prefix . "veainge_formdata";
}

function make_table()
{
    global $wpdb;
    $table = tableName($wpdb->prefix);
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table (
        id BIGINT AUTO_INCREMENT,
        content TEXT,
        created_at TIMESTAMP DEFAULT now() NULL,
        PRIMARY KEY(id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'make_table');

// Acción para eliminar la tabla cuando se desactive el plugin
function remove_table()
{
    global $wpdb;
    $tabla = tableName($wpdb->prefix); // Cambia 'mis_formularios' al nombre que desees

    $wpdb->query("DROP TABLE IF EXISTS $tabla");
}
register_deactivation_hook(__FILE__, 'remove_table');


// Acción para guardar datos del formulario en la base de datos
function save_data_form()
{
    $refer = check_ajax_referer( 'save-form-info', 'nonce' );  // Check the nonce.
	$formData = $_POST['formData'];
    $data = [];
    foreach ($formData as $getData) {
        $data[$getData['name']] = $getData['value'];
    }

    $saveData = json_encode($data);
    global $wpdb;
    $table = tableName($wpdb->prefix);

    $getFirstKey = key($data);
    // Get the content based on a unique identifier (for example, a title or an ID)
    // $queryCompre = "SELECT * FROM $table  WHERE content = %s" ;
    $queryCompre = "SELECT * FROM wp_veainge_formdata WHERE JSON_VALUE(content, '$.$getFirstKey') = '%s' LIMIT 100" ;
    
    $existing_content = $wpdb->get_results( $wpdb->prepare( $queryCompre, current($data) ) );

    if ($existing_content) {
        $wpdb->update($table, array(
            'content' => $saveData
        ),  array( 'id' => current($existing_content)->id ));
        $response = array('status' => 'success' ,'message' => 'Data updated correctly', 'data' => $saveData);
    } else {
        $wpdb->insert($table, array(
            'content' => $saveData
        ));
        $response = array('status' => 'success' ,'message' => 'Data stored correctly', 'data' => $saveData);
    }
    
    
    // Send a response (can be a success message or any other data).
    wp_send_json($response);
    
    // Don't forget to stop execution afterward
    wp_die();
}

add_action('wp_ajax_nopriv_save_data_form', 'save_data_form');
add_action('wp_ajax_save_data_form', 'save_data_form');


function load_custom_script()
{
    wp_register_script('frontend-ajax', plugins_url('veainge-form2db.js', __FILE__), array('jquery'), '1.0', true);
    wp_register_script('data-tables', "https://cdn.datatables.net/2.0.2/js/dataTables.min.js", array('jquery'), '2.0.2', true);

    wp_localize_script('frontend-ajax', 'frontend_ajax_object',
		array(
			'ajaxurl' => esc_url(admin_url( 'admin-ajax.php' )),
            'nonce'    => wp_create_nonce( 'save-form-info' ),
            'locale' => get_locale()
		)
	);
    wp_enqueue_script('data-tables');
    wp_enqueue_script('frontend-ajax');
}

add_action('wp_enqueue_scripts', 'load_custom_script');


function load_custom_style()
{
    wp_enqueue_style('frontend-form', plugins_url('style.css', __FILE__));
    wp_enqueue_style('data-tables', "https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css");

}

add_action('wp_enqueue_scripts', 'load_custom_style');

include_once( plugin_dir_path( __FILE__ ) . 'src/form_shortcode.php' );
include_once( plugin_dir_path( __FILE__ ) . 'src/table_shortcode.php' );
?>