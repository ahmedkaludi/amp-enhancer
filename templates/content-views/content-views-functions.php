<?php

function amp_enhancer_cv_template_override($file_path){

   if(strpos($file_path, 'templates/collapsible/html/main.php') > -1){
    $file_path = AMP_ENHANCER_TEMPLATE_DIR.'content-views/collapsible/main.php';
   }
   return $file_path;
}

function amp_enhancer_cv_collapsible_filters($result,$content_array){

 if(preg_match('/<div\s+class="(.*?)pt-cv-content-item(.*?)">(.*)<\/div>/s', $result)){
   $result = preg_replace('/<div\s+class="(.*?)pt-cv-content-item(.*?)">(.*)<\/div>/s', '<section class="$1pt-cv-content-item$2">$3</section>', $result);
 }

return $result;
}

function amp_enhancer_cv_collapsible_wrapper($output,$content_array){

  if(preg_match('/<div\s+class="panel-group"(.*?)>(.*?)<section(.*?)>(.*)<\/div>/s', $output)){
    $output = preg_replace('/<div\s+class="panel-group"(.*?)>(.*?)<section(.*?)>(.*)<\/div>/s', '<amp-accordion class="panel-group"$1 expand-single-section>$2<section$3 expanded>$4<\/amp-accordion>', $output);
  }

return $output;
}

function amp_enhancer_cv_view_all_output($combined_output,$before_output,$output){

  if(preg_match('/<div\s+class="carousel-inner">(.*)<\/div>/s', $output)){ 
      $output = preg_replace('/<div\s+class="carousel-inner">(.*)<\/div>/s', '<amp-carousel id="carouselWithPreviewtest" class="carousel-inner" width="400" height="250" layout="responsive" type="slides" on="slideChange:carouselWithPreviewSelectoretest.toggle(index=event.index, value=true)">$1</amp-carousel>', $output); 
  }
 return $output;
}