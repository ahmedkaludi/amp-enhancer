var swiper = document.getElementsByClassName("swiper-pagination-bullet");
var swiper_next = document.getElementsByClassName("elementor-swiper-button-next");
var swiper_prev = document.getElementsByClassName("elementor-swiper-button-prev");
var bullet_wrapper = document.getElementsByClassName("swiper-pagination-bullets");
var viewport   = document.defaultView.innerWidth;


if(swiper){

  for(var i=0; i<swiper.length;i++ ){

    swiper[i].addEventListener( 'click', function(e) {
            if(viewport <= 1024 && bullet_wrapper){
              	amp_enhancer_add_bullet_points();
				amp_enhancer_mobile_dots_functionality(e);
            }else{
		    	var transform = e.target.getAttribute('data-transform');
		    	var bullet = e.target.getAttribute('data-bullet');
		    	var data_id = e.target.parentNode.getAttribute('data-id');
		    	var swiper_cls = 'swiper-wrapper'+data_id;
		    	var swiper_wrapper = document.getElementsByClassName(swiper_cls);
				var swiper_active = e.target.parentNode.getElementsByClassName("swiper-pagination-bullet-active");
		    	swiper_active[0].classList.remove("swiper-pagination-bullet-active");
		    	e.target.classList.add("swiper-pagination-bullet-active");
		    	swiper_wrapper[0].style.transform = 'translate3d(-'+transform+'%, 0px, 0px)';
		    	swiper_wrapper[0].setAttribute('data-transform-px',transform);
		    	swiper_wrapper[0].setAttribute('data-frame',bullet);
	       }
     });

	}
}


if(swiper_next){
	for(var i=0; i<swiper_next.length;i++ ){
    swiper_next[i].addEventListener( 'click', function(e) {
         amp_enhancer_image_carousel_arrows(e,'nextclicks');
     });	
    }
}

if(swiper_prev){
	for(var i=0; i<swiper_prev.length;i++ ){
    swiper_prev[i].addEventListener( 'click', function(e) {
		amp_enhancer_image_carousel_arrows(e,'prevclicks');
     });	
    }

}


function amp_enhancer_image_carousel_arrows(e,clicked_button){
	
	var arrow_slide = e.currentTarget.getAttribute('data-slide');
	var total_slide = e.currentTarget.getAttribute('data-total-slides');
	var data_id 	= e.currentTarget.getAttribute('data-id');
	var swiper_cls  = 'swiper-wrapper'+data_id;
    var swiper_wrapper = document.getElementsByClassName(swiper_cls);
	var data_frame = parseInt(swiper_wrapper[0].getAttribute('data-frame'));
	var swiper_active = swiper_wrapper[0].parentNode.getElementsByClassName("swiper-pagination-bullet-active");

        if(viewport <= 1024 && bullet_wrapper){
	        amp_enhancer_add_bullet_points();
		}

		if(clicked_button == 'prevclicks'){
			 data_frame = data_frame-1;
		}
		if(clicked_button == 'nextclicks'){
			 data_frame += 1;
		}
		if(viewport <= 1024){
	  		arrow_slide = total_slide;
	  	}

	var bullet = swiper_wrapper[0].parentNode.getElementsByClassName("bullet"+data_frame);
	if((data_frame >= 0 && clicked_button == 'prevclicks') || (data_frame < arrow_slide && clicked_button == 'nextclicks')){

	  	var arrow_width = e.currentTarget.getAttribute('data-width');

	  	var arrow_scroll = e.currentTarget.getAttribute('data-to-scroll');
	  	if(viewport <= 1024 ){
	  		arrow_scroll = 1;
		    arrow_width = amp_enhancer_viewport_transform_css();
		    arrow_width = 100;
	  	}
	  	var arrowtransform = (arrow_width*arrow_scroll)*data_frame;
	  	swiper_wrapper[0].style.transform = 'translate3d(-'+arrowtransform+'%, 0px, 0px)';
	   	if(bullet.length > 0){
		   	swiper_active[0].classList.remove("swiper-pagination-bullet-active");
		   	bullet[0].classList.add("swiper-pagination-bullet-active");
		}
	   swiper_wrapper[0].setAttribute('data-transform-px',arrowtransform);
	   swiper_wrapper[0].setAttribute('data-frame',data_frame);

	}
}


function amp_enhancer_add_bullet_points(){

	 for(var i=0; i<bullet_wrapper.length;i++ ){
         var wrapper_length = bullet_wrapper[i].children.length;
         var wrapper_slides = bullet_wrapper[i].getAttribute('data-total-slides');
         if(wrapper_slides != wrapper_length){
          var child_length = wrapper_slides - wrapper_length;
	           for (var j = wrapper_length; j < wrapper_slides; j++) {
		           var span  = document.createElement("span");
		            span.setAttribute('class','swiper-pagination-bullet  bullet'+j+'');
		            span.setAttribute('tabindex','0');
		            span.setAttribute('role','button');
		            span.setAttribute('data-bullet',j);
		            span.setAttribute('aria-label','Go to slide '+(j+1));
		            bullet_wrapper[i].appendChild(span);
					var newbullets = bullet_wrapper[i].getElementsByClassName("bullet"+j);
					newbullets[0].addEventListener( 'click', function(e) {
							amp_enhancer_mobile_dots_functionality(e);
					});
	           }
         }
	 }
}

function amp_enhancer_mobile_dots_functionality(e){

	var bullet = e.target.getAttribute('data-bullet');
	var data_id = e.target.parentNode.getAttribute('data-id');
	var swiper_cls = 'swiper-wrapper'+data_id;
   	var transform = e.target.parentNode.getAttribute('data-slide-width');
	var swiper_wrapper = document.getElementsByClassName(swiper_cls);
	var swiper_active = e.target.parentNode.getElementsByClassName("swiper-pagination-bullet-active");
	transform = 100;
	swiper_active[0].classList.remove("swiper-pagination-bullet-active");
	e.target.classList.add("swiper-pagination-bullet-active");
	transform = parseInt(transform)*parseInt(bullet);
	swiper_wrapper[0].style.transform = 'translate3d(-'+transform+'%, 0px, 0px)';
	swiper_wrapper[0].setAttribute('data-transform-px',transform);
	swiper_wrapper[0].setAttribute('data-frame',bullet);
}

function amp_enhancer_viewport_transform_css(){
   var transform_css = '';
	switch (viewport) {
	  case 320:
	    transform_css = 258;
	    break; 
	  case 375:
	    transform_css = 313;
	    break; 
	  case 425:
	    transform_css = 363;
	    break; 
	  case 768:
	    transform_css = 324.5;
	    break;
	  case 1024:
	    transform_css = 298.5;
	    break;    
	  default:
	  transform_css = 298.5;
	}
   return transform_css;
}

