<?php
namespace pagebuilders\divi;
class AMP_Enhancer_Divi_Pagebuidler {

    public function __construct()
    {
        $this->en_load_dependencies();
        //$this->define_public_hooks();
    }
    
    private function en_load_dependencies(){
		add_action( 'et_builder_ready', [$this,'amp_enhancer_divi_modules_file_override'] );
    }
  

   public function amp_enhancer_divi_modules_file_override(){

                $filesArray = array('Accordion.php','Toggle.php');//'MapItem.php',
                foreach ($filesArray as $key => $value) {
                    if(!empty($value)){
                        if(file_exists(AMP_ENHANCER_PAGEBUILDER_DIR."/divi/modules/".$value)){
                            require_once AMP_ENHANCER_PAGEBUILDER_DIR."/divi/modules/".$value;
                        }
                    }
                }

    }

}