<?php 

function amp_enhancer_helpie_faq($atts, $content = null){
            
            $faq = new \HelpieFaq\Features\Faq\Faq();
            $faq_model = new \HelpieFaq\Features\Faq\Faq_Model();
            $defaults = $faq_model->get_default_args();
            $args = shortcode_atts($defaults, $atts);

            /**
             * Check the shorcode is faq_group shortcode or not. 
             * If it's faq_shortcode then set default props value in $args.
             */
            if( isset($atts['group_id']) && !empty($atts['group_id']) && intval($atts['group_id'])){
                
                $faq_groups = new \HelpieFaq\Features\Faq\Faq_Groups\Faq_Groups();
                $faq_groups_args = $faq_groups->get_default_args($atts);
                $args = array_merge($args,$faq_groups_args);
            }
            return amp_enhancer_helpie_get_view($args);
}


function amp_enhancer_helpie_get_view($args){
            $faq = new \HelpieFaq\Features\Faq\Faq();
            $html = '';

            $style = array();

            if( isset($args['style']) ){
                $style = $args['style']; 
            }
            
            $viewProps = $faq->model->get_viewProps($args);
            
            if (isset($viewProps['items']) && !empty($viewProps['items'])) {
                //$html = $faq->view->get($viewProps, $style);
                $html = amp_enhancer_helpie_get_html($viewProps, $style);
            }
            /** use this below filter for generating faq-schema snippet */
            apply_filters( 'helpie_faq_schema_generator', $viewProps);
            
            return $html;
        }


function  amp_enhancer_helpie_get_html( $viewProps ){
            $faq_view = new \HelpieFaq\Features\Faq\Faq_View();
            require_once HELPIE_FAQ_PATH . 'lib/stylus/stylus.php';
            $stylus = new \Stylus\Stylus();
            //$viewProps['collection'] = $faq_view->boolean_conversion( $viewProps['collection'] );
            $additional_classes = $faq_view->get_additional_classes( $viewProps );
            $id = '';
            if ( isset( $viewProps['collection']['id'] ) ) {
                $id .= $viewProps['collection']['id'];
            }
            $script_url = str_replace('http:','https:',AMP_ENHANCER_PLUGIN_URI).'templates/helpie-faq/amp-script/amp-enhancer-faq-search.js?ver='.AMP_ENHANCER_VERSION;
            $html = '<amp-script src="'.esc_url_raw($script_url).'"><section id="' . esc_attr($id) . '" class="helpie-faq accordions ' . esc_attr($additional_classes) . '">';
            if ( isset( $viewProps['collection']['title'] ) && $viewProps['collection']['title'] != '' ) {
                $html .= '<h3 class="collection-title">' . esc_html($viewProps['collection']['title']) . '</h3>';
            }
            // TODO check FAQ searchbar is enable or not
            $is_faq_search_enabled = amp_enhancer_is_faq_search_enabled( $viewProps );
            if ( $is_faq_search_enabled ) {
             $html .= $stylus->search->get_view( $viewProps['collection'] );
            }
            $html .= amp_enhancer_get_view($viewProps );
            $html .= '</section></amp-script>';
            return $html;
        }

   function amp_enhancer_get_view($viewProps){
           $accordion = new \Stylus\Components\Accordion();
            // error_log('$viewProps : ' . print_r($viewProps, true));
            $html = isset($viewProps['collection']['title'])?$viewProps['collection']['title']:"";
            $collectionProps = $viewProps['collection'];
            $top_level = $viewProps['collection']['display_mode'];
            if( $top_level == 'simple_accordion_category' || $top_level == 'faq_list'){
                $html = $accordion->get_titled_view($viewProps['items'], $collectionProps );
            } else{
                $html = amp_enhancer_search_get_accordion($viewProps['items'], $collectionProps,$accordion );
            }
            /*else{
                $html = amp_enhancer_get_accordion($viewProps['items'], $collectionProps,$accordion );
            }*/
            

            return $html;
        }



   function amp_enhancer_get_accordion($props,$collectionProps,$accordion){

            $faq_list_class = '';
            if(isset($collectionProps['display_mode']) &&  $collectionProps['display_mode'] == 'faq_list'){
                $faq_list_class = 'faq_list';
            }

             $expand = '';
              if(isset($collectionProps['toggle']) && $collectionProps['toggle'] = true){
                $expand = 'expand-single-section';
              }

            $html = '<amp-accordion expand-single-section class="accordion '.esc_attr($faq_list_class).'">';

            for ($ii = 0; $ii < sizeof($props); $ii++) {
                $html .= amp_enhancer_get_single_item($props[$ii],$collectionProps,$accordion);
            }

            $html .= '</amp-accordion>';

            return $html;
        }

    function amp_enhancer_search_get_accordion($props,$collectionProps,$accordion){

            $faq_list_class = '';
            if(isset($collectionProps['display_mode']) &&  $collectionProps['display_mode'] == 'faq_list'){
                $faq_list_class = 'faq_list';
            }
         
            $html = '<article class="accordion '.$faq_list_class.'">';

            for ($ii = 0; $ii < sizeof($props); $ii++) {
                $html .= amp_enhancer_get_single_item_accordion($props[$ii],$collectionProps,$accordion);
            }

            $html .= '</article>';

            return $html;

        }

        function amp_enhancer_get_single_item_accordion($props,$collectionProps,$accordion){
            $id = isset($props['post_id']) ? "post-".$props['post_id'] : "term-".$props['term_id'];
            
            $faq_url_data_item = '';
            if(isset($collectionProps['faq_url_attribute']) && $collectionProps['faq_url_attribute'] == 1){
                $faq_url_data_item = 'hfaq-'.$id;
            }

            $accordion__header_classes = '';

            $show_accordion_body = '';
            if(isset($collectionProps['open_by_default']) && $collectionProps['open_by_default'] == 'open_all_faqs'){
                $show_accordion_body = 'style="display: block;"';
                $accordion__header_classes .= ' active'; 
            }

            $custom_toggle_icon_content = $accordion->get_custom_toggle_icon($collectionProps);

            if(!empty($custom_toggle_icon_content)){
                $accordion__header_classes .= ' custom-icon';
            }

            $html = '<li class="accordion__item">';
            $html .= '<div class="accordion__header '.$accordion__header_classes.'" data-id="'.$id.'" data-item="'.$faq_url_data_item.'">';
            $html .= '<div class="accordion__title">'.$props['title'].'</div>';
            // $html .= '<a href="#hfaq-'.esc_attr($id).'">'.$props['title'].'</a>';
            $html .= $custom_toggle_icon_content;
            $html .= '</div>';
            $html .= '<div hidden class="accordion__body" '.$show_accordion_body.'>';
           
            if(is_plugin_active('elementor/elementor.php')){
                $html .= '<p>' . apply_filters('elementor/frontend/the_content',$props['content']) . '</p>';
            }else{
                $html .= '<p>' . apply_filters('the_content',$props['content']) . '</p>';
            }

            if( isset($props['children'])){
                $html .= $accordion->get_accordion($props['children'],$collectionProps);
            }
            
            $html .= '</div>';
            $html .= '</li>';

            return $html;
        }

  function amp_enhancer_get_single_item($props,$collectionProps,$accordion){
            $id = isset($props['post_id']) ? "post-".$props['post_id'] : "term-".$props['term_id'];
            
            $faq_url_data_item = '';
            if(isset($collectionProps['faq_url_attribute']) && $collectionProps['faq_url_attribute'] == 1){
                $faq_url_data_item = 'hfaq-'.$id;
            }

            $accordion__header_classes = '';

            $show_accordion_body = '';
            if(isset($collectionProps['open_by_default']) && $collectionProps['open_by_default'] == 'open_all_faqs'){
                $show_accordion_body = 'style="display: block;"';
                $accordion__header_classes .= ' active'; 
            }

            $custom_toggle_icon_content = $accordion->get_custom_toggle_icon($collectionProps);

            if(!empty($custom_toggle_icon_content)){
                $accordion__header_classes .= ' custom-icon';
            }

            $html = '<section class="accordion__item">';
            $html .= '<h4 class="accordion__header '.esc_attr($accordion__header_classes).'" data-id="'.esc_attr($id).'" data-item="'.esc_attr($faq_url_data_item).'">';
            $html .= '<div class="accordion__title">'.esc_html($props['title']).'</div>';
            // $html .= '<a href="#hfaq-'.esc_attr($id).'">'.$props['title'].'</a>';
            $html .= $custom_toggle_icon_content;
            $html .= '</h4>';
            $html .= '<div class="accordion__body" '.$show_accordion_body.'>';
           
            if(is_plugin_active('elementor/elementor.php')){
                $html .= '<p>' . apply_filters('elementor/frontend/the_content',$props['content']) . '</p>';
            }else{
                $html .= '<p>' . apply_filters('the_content',$props['content']) . '</p>';
            }

            if( isset($props['children'])){
                $html .= $accordion->get_accordion($props['children'],$collectionProps);
            }
            
            $html .= '</div>';
            $html .= '</section>';

            return $html;
    }

  function amp_enhancer_is_faq_search_enabled( $viewProps ){
            
            if ( is_singular( 'product' ) ) {
                if ( isset( $viewProps['collection']['woo_search_show'] ) && $viewProps['collection']['woo_search_show'] ) {
                    return true;
                }
            } else {
                if ( isset( $viewProps['collection']['show_search'] ) && $viewProps['collection']['show_search'] ) {
                    return true;
                }
            }
            
            return false;
    }  