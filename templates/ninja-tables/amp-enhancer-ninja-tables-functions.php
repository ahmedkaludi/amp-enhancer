<?php 

function amp_enhancer_render_ninja_table_shortcode($atts, $content = ''){
        $shortCodeDefaults = array(
            'id'               => false,
            'filter'           => false,
            'use_parent_width' => false,
            'info'             => ''
        );

        $shortCodeDefaults = apply_filters('ninja_tables_shortcode_defaults', $shortCodeDefaults);
        $shortCodeData = shortcode_atts($shortCodeDefaults, $atts);
        $shortCodeData = apply_filters('ninja_tables_shortcode_data', $shortCodeData);

        $tableArray = amp_enhancer_ninja_table_getTableArray($shortCodeData, $content);



        if (NinjaTables\Classes\ArrayHelper::get($tableArray, 'settings.formula_support') == 'yes') {
            do_action('ninja_tables_require_formulajs', $tableArray);
        }

        $tableArray = apply_filters('ninja_table_js_config', $tableArray, $shortCodeData['filter']);

        ob_start();
        do_action('ninja_tables-render-table-' . NinjaTables\Classes\ArrayHelper::get($tableArray, 'settings.library'), $tableArray);
        $table_data = ob_get_contents();
         ob_get_clean();
        $tableId = $tableArray['table_id'];
        $table_body_data = amp_enhancer_ninja_tables_getAllData($tableId);
        $table_data  = preg_replace('/<div(.*?)class=(.*?)ninja_table_wrapper(.*?)<table(.*?)>(.*?)<\/table>/s', '<div$1class=$2ninja_table_wrapper$3<table$4>'.$table_body_data.'$5</table>', $table_data);
        return $table_data;
    }



    function amp_enhancer_ninja_tables_getAllData($tableId){
        //$tableId = intval(NinjaTables\Classes\ArrayHelper::get($_REQUEST, 'table_id'));
        do_action('ninja_table_doing_ajax_table_data', $tableId);
        $defaultSorting = sanitize_text_field(NinjaTables\Classes\ArrayHelper::get($_REQUEST, 'default_sorting'));
       
        $tableSettings = ninja_table_get_table_settings($tableId, 'public');
        $is_ajax_table = true;
        if (NinjaTables\Classes\ArrayHelper::get($tableSettings, 'render_type') == 'legacy_table') {
            $is_ajax_table = false;
        }
        $is_ajax_table = apply_filters('ninja_table_is_public_ajax_table', $is_ajax_table, $tableId);

        if (!$tableSettings || !$is_ajax_table) {
            wp_send_json_success([], 200);
        }

        $skip = NinjaTables\Classes\ArrayHelper::get($_REQUEST, 'skip_rows', 0);
        $limit = NinjaTables\Classes\ArrayHelper::get($_REQUEST, 'limit_rows', false);

        if (!$limit && !$skip && isset($_REQUEST['chunk_number'])) {
            $chunkNumber = NinjaTables\Classes\ArrayHelper::get($_REQUEST, 'chunk_number', 0);
            $perChunk = ninjaTablePerChunk($tableId);
            $skip = $chunkNumber * $perChunk;
            $limit = $perChunk;
        }

        $ownOnly = false;
        if (isset($_REQUEST['own_only']) && $_REQUEST['own_only'] == 'yes') {
            $ownOnly = true;
        }

        $tableColumns = ninja_table_get_table_columns($tableId);
        $formatted_data = ninjaTablesGetTablesDataByID($tableId, $tableColumns, $defaultSorting, false, $limit, $skip, $ownOnly);

        $formatted_data = apply_filters('ninja_tables_get_public_data', $formatted_data, $tableId);
        // return $formatted_data;

        $dataProvider = ninja_table_get_data_provider($tableId);
        $thead = '<thead><tr class="footable-header">';
        $thead_cont = $tbody_cont =  '';
        $i = 0; 
        
        if ($dataProvider == 'default') {
            $newStyledData = array();
            $tbody_content = $tbody_cont = '';
            $counter = $skip;
           foreach ($formatted_data as $index => $datum) {
                $datum = array_map(function ($value) {
                    if (is_string($value)) {
                        return do_shortcode($value);
                    }
                    return $value;
                }, $datum);

                unset($datum['___id___']);
                $newStyledData[] = array(
                    'options' => array(
                        'classes' => (isset($datum['___id___'])) ? 'ninja_table_row_' . $counter . ' nt_row_id_' . $datum['___id___'] : 'ninja_table_row_' . $counter,
                    ),
                    'value'   => $datum
                );
                $counter = $counter + 1;

                $tbody_cont .= '<tr class="ninja_table_row_'.$counter.' nt_row_id_'.$counter.'">';

                    foreach ($datum as $key => $datum_value) {

                           $key_data = str_replace('_', ' ', $key);
                           $key_data = ucwords($key_data);
                            if($i == 0){

                                $thead_cont .= 
                                                '<th class="ninja_column_' . $counter . '  ninja_clmn_nm_s_no footable-sortable footable-first-visible" scope="col" style="display: table-cell;">'.$key_data.'<span class="fooicon fooicon-sort"></span>
                                                  </th>';
                                              
                             }

                            if(isset($formatted_data[$index][$key]) && !empty($formatted_data[$index][$key])){
                                $tbody_cont .= '<td class="ninja_column_' . $counter . ' ninja_clmn_nm_s_no footable-first-visible" style="display: table-cell;">'.$datum_value.'</td>';
                                }  

                    }

               $tbody_cont .=  '</tr>';

              $i++;
          }

            $formatted_data = $newStyledData;

        }

        $thead_cl = '</tr></thead>';
        $thead_content = '<thead>
                            <tr class="footable-header">
                              '.$thead_cont.'
                            </tr>
                          </thead>';
        $tbody_content = '<tbody>'.$tbody_cont.'</tbody>';  
        $table_content = $thead_content.$tbody_content; 

       return $table_content;
    }




    function amp_enhancer_ninja_table_getTableArray($shortCodeData, $content = ''){
        extract($shortCodeData);

        $table_id = $shortCodeData['id'];

        if (!$table_id) {
            return;
        }

        $table = get_post($table_id);

        if (!$table || $table->post_type != 'ninja-table') {
            return;
        }

        $tableSettings = ninja_table_get_table_settings($table_id, 'public');

        $tableSettings = apply_filters(
            'ninja_tables_rendering_table_settings', $tableSettings, $shortCodeData, $table
        );

        $tableColumns = ninja_table_get_table_columns($table_id, 'public');

        if (!$tableSettings || !$tableColumns) {
            return;
        }

        $tableSettings['use_parent_width'] = $use_parent_width;

        if (isset($tableSettings['columns_only']) && is_array($tableSettings['columns_only'])) {
            $showingColumns = $tableSettings['columns_only'];
            $formattedColumns = array();
            foreach ($tableColumns as $columnIndex => $table_column) {
                if (isset($showingColumns[$table_column['key']])) {
                    $formattedColumns[] = $table_column;
                }
            }
            $tableColumns = $formattedColumns;
        }

        return array(
            'table_id'      => $table_id,
            'columns'       => $tableColumns,
            'settings'      => $tableSettings,
            'table'         => $table,
            'content'       => $content,
            'shortCodeData' => $shortCodeData
        );
    }


