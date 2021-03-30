<?php

function amp_enhancer_foo_gal_reader_css(){ ?>

  .foogallery .fg-item .fg-item-inner{visibility: visible;opacity: 1;}
 .fg-item amp-img.amp-wp-enforced-sizes[layout="intrinsic"] > img {object-fit: cover;}
 .fiv-inner{width:90%}body .fg-image-viewer .fiv-inner .fiv-ctrls{display:none;}

  <?php 
}

function amp_enhancer_foogallery_load_gallery_template($boolean, $gallery, $template_location){

    if(strpos($template_location, 'image-viewer/gallery-image-viewer.php')){
      	$amp_template = AMP_ENHANCER_TEMPLATE_DIR.'foogallery/default-templates/gallery-image-viewer.php';
      	if(file_exists($amp_template)){
      	  load_template( $amp_template, false );
        }
     	return true;
    }else{
    	return false;
    }

}