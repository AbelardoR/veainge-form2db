<?php

// Agregamos el shortcode para mostrar los registros almacenados
function show_records_shortcode()
{
    // Obtenemos todos los registros de la tabla
    global $wpdb;
    $table_name = tableName($wpdb->prefix);
    $results = $wpdb->get_results("SELECT * FROM $table_name");

    ob_start();
    $html = '';
    $html .= '<h2 class="table-title">Records</h2>';
    $html .= '<table border="1" id="table-from-plugin">';
    $html .= '<thead><tr>';
    $html .= "<th>ID</th>";
    foreach (json_decode(end($results)->content, true) as $key => $value) {
        $html .= "<th> $key </th>";
    }
    $html .= '</tr></thead>';
    $html .= '<tbody>';
    foreach ($results as $row) {
        $html .= '<tr>';
        $html .= "<td> $row->id </td>";
        foreach (json_decode($row->content, true) as $key => $value) {
            $html .= "<td> $value </td>";
        }
        $html .= '</tr>';
    }
    $html .= '</tbody>';
    $html .= '</table>';
    ob_get_clean();
    return $html;
}
add_shortcode('show_records', 'show_records_shortcode');