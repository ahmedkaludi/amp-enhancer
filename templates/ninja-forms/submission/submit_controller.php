<?php if ( ! defined( 'ABSPATH' ) ) exit;

abstract class AMP_Enhancer_NF_Abstracts_Controller
{
    /**
     * Data (Misc.) passed back to the client in the Response.
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Errors passed back to the client in the Response.
     *
     * @var array
     */
    protected $_errors = array();

    /**
     * Debug Messages passed back to the client in the Response.
     *
     * @var array
     */
    protected $_debug = array();

    /*
     * PUBLIC METHODS
     */

    /**
     * NF_Abstracts_Controller constructor.
     */
    public function __construct()
    {
        //This section intentionally left blank.
    }


    /*
     * PROTECTED METHODS
     */

    /**
     * Respond
     *
     * A wrapper for the WordPress AJAX response pattern.
     */
    protected function _respond( $data = array() )
    {
        if( empty( $data ) ){
            $data = $this->_data;
        }

        if( isset( $this->_data['debug'] ) ) {
            $this->_debug = array_merge( $this->_debug, $this->_data[ 'debug' ] );
        }

        if( isset( $this->_data['errors'] ) && $this->_data[ 'errors' ] ) {
            $this->_errors = array_merge( $this->_errors, $this->_data[ 'errors' ] );
        }

        // allow for accessing and acting on $data before responding
        do_action( 'ninja_forms_before_response', $data );

        $data['actions']['success_message'] = str_replace(array("<p>",'</p></p>','</p>'), array('<span>','<br>','</span>'), $data['actions']['success_message']);
        $errorMsgs = array();
        if(count($this->_errors)>0){
            foreach ($this->_errors['fields'] as $key => $value) {
                if(is_array($value)){
                    foreach ($value as $val) {
                        
                $errorMsgs[] = array('error_detail'=>$val);
                    }
                }else{
                    $errorMsgs[] = array('error_detail'=>$value);
                }
            }
        }

        $response = array( 'data' => $data, 'errors' => $errorMsgs, 'debug' => $this->_debug );
        if(count($this->_errors)>0){
            header('HTTP/1.1 500 FORBIDDEN');
        }

        echo wp_json_encode( $response );
        $siteUrl = parse_url( get_site_url());
        $redirect_url = '';
        if( isset($data['actions']['redirect']) && $data['actions']['redirect']!=""){
            $redirect_url = ampforwp_url_controller($data['actions']['redirect']);
            header("Content-type: application/json");
            header("Access-Control-Allow-Credentials: true");
            header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
            header("AMP-Access-Control-Allow-Source-Origin: ".esc_attr($siteUrl['scheme']).'://'.esc_attr($siteUrl['host']));
            header("AMP-Redirect-To: ".esc_raw_url($redirect_url));
            header("Access-Control-Expose-Headers: AMP-Redirect-To, AMP-Access-Control-Allow-Source-Origin");   
        }else{
            header("access-control-allow-credentials:true");
            header("access-control-allow-headers:Content-Type, Content-Length, Accept-Encoding, X-CSRF-Token");
            header("Access-Control-Allow-Origin:".esc_attr($_SERVER['HTTP_ORIGIN']));
            
            header("AMP-Access-Control-Allow-Source-Origin:".esc_attr($siteUrl['scheme']).'://'.esc_attr($siteUrl['host']));
            header("access-control-expose-headers:AMP-Access-Control-Allow-Source-Origin");
            header("Content-Type:application/json");
        }
        die(); // this is required to terminate immediately and return a proper response
    }
}