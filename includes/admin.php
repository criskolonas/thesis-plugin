<?php 
add_action('admin_menu', 'thesis_plugin_menu');
 
function thesis_plugin_menu(){
    add_menu_page( 'Dashboard', 'Thesis Plugin', 'manage_options', 'thesis-plugin', 'form_entries' );
    add_submenu_page( 'thesis-plugin', 'Entries','Entries', 'manage_options', 'thesis-plugin-entries', 'form_entries' );

}
 

function form_entries(){
    global $wpdb;
    $tables = $wpdb -> get_results("SHOW TABLES FROM local LIKE '".$wpdb->prefix."submissions_table_%'");
    echo '<form action="">
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

    
}

function load_entries($table){

}

