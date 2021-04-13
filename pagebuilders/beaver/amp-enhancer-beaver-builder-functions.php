<?php
function amp_enhancer_fl_builder_module_frontend_file($module_dir,$module){
  
    if(isset($module->name) && $module->name == 'Accordion'){
      $module_dir = AMP_ENHANCER_PAGEBUILDER_DIR.'beaver/accordion/frontend.php';
    }
     if(isset($module->name) && $module->name == 'Tabs'){
      $module_dir = AMP_ENHANCER_PAGEBUILDER_DIR.'beaver/tabs/frontend.php';
    }
  return $module_dir;
}