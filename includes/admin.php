<?php 
/*
    Adds load_entries.js script to the footer of the page and localizes it for AJAX
*/
function enqueue_my_custom_script() {
    wp_enqueue_script( 'load_entries',WP_CONTENT_URL.'/plugins/thesis-plugin/scripts/load_entries_from_db.js',array('jquery'),false,true);
    wp_localize_script( 'load_entries', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
}

add_action( 'admin_enqueue_scripts', 'enqueue_my_custom_script' );

/*
    Creates Admin Dashboard Menu
*/
 
function thesis_plugin_menu(){
    add_menu_page( 'Dashboard', 'Thesis Plugin', 'manage_options', 'thesis-plugin', 'form_entries' );
    add_submenu_page( 'thesis-plugin', 'Entries','Entries', 'manage_options', 'thesis-plugin-entries', 'form_entries' );
}

add_action('admin_menu', 'thesis_plugin_menu');


function load_entries(){
	global $wpdb;
    $target = $_POST['table'];
    $data = $wpdb->get_results("SELECT * FROM {$target}");
    echo var_dump($data);
}
//Its wp_ajax{name used in jquery}
add_action( 'wp_ajax_load_entries_ajax', 'load_entries' );

function form_entries(){
    global $wpdb;
    //$action = WP_CONTENT_URL.'/plugins/thesis-plugin/includes/entries.php';
    //WARNING LOCAL
    $tables = $wpdb -> get_results("SHOW TABLES FROM local LIKE '".$wpdb->prefix."submissions_table_%'");
    echo '<form method="POST" action="#" id="table_form">
    <label for="tables">Select Entries Table:</label>
    <select name="tables" id="tables">';
    foreach ($tables as $key=>$value )
    {
        foreach($value as $table_name)
       echo '<option value="'.$table_name.'">'.$table_name.'</option>';

    }
    echo 
    '</select>
    <br><br>
    <input type="submit" value="Submit">
  </form>
    ';

    echo '<div id="output-area"></div>';

    
}
