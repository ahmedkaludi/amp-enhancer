// Slider Functionality
var amp_slide = document.getElementsByClassName('amp-slide');

if(amp_slide){

  for(var i=0; i<amp_slide.length;i++ ){
    
    var next_click = amp_slide[i].getElementsByClassName('next');
    var prev_click = amp_slide[i].getElementsByClassName('previous');

    var flickity_slider = amp_slide[i].getElementsByClassName('flickity-slider');

    if(flickity_slider[0]){
    	var banner = flickity_slider[0].getElementsByClassName('ux-amp-cont');
    }

  	 next_click[0].addEventListener( 'click', function(e) {
   	   var active_slider = flickity_slider[0].getElementsByClassName('active-slider');
   	   if(!active_slider[0]){
   	   	banner[0].classList.add('active-slider');
   	   	active_slider = flickity_slider[0].getElementsByClassName('active-slider');
   	   }
   	   var slide_number  = parseInt(active_slider[0].getAttribute('data-slide'))+1;
   	   if(slide_number == banner.length){
   	   		slide_number =0;
   	   }
   	   var transform = slide_number*100;
       flickity_slider[0].style.transform = 'translateX(-'+transform+'%)';
   	    for (var j = 0; j<banner.length; j++) {
   	    	var data_slide = parseInt(banner[j].getAttribute('data-slide'));
   	    	if(slide_number == data_slide){
   	    		banner[j].classList.add('active-slider');
   	    	}else{
   	    		banner[j].classList.remove('active-slider');
   	    	}
   	    }

        console.log(slide_number);
      

     });

     prev_click[0].addEventListener( 'click', function(e) {

	 var active_slider = flickity_slider[0].getElementsByClassName('active-slider');
	 if(!active_slider[0]){
   	   	banner[0].classList.add('active-slider');
   	   	active_slider = flickity_slider[0].getElementsByClassName('active-slider');
   	   }
	   	   var slide_number  = parseInt(active_slider[0].getAttribute('data-slide'))-1;
	   	   if(slide_number == -1){
   	   		slide_number = banner.length-1;
   	  	 }
	   	   var transform = slide_number*100;
	       flickity_slider[0].style.transform = 'translateX(-'+transform+'%)';
	   	    for (var j = 0; j<banner.length; j++) {
	   	    	var data_slide = parseInt(banner[j].getAttribute('data-slide'));
	   	    	if(slide_number == data_slide){
	   	    		banner[j].classList.add('active-slider');
	   	    	}else{
	   	    		banner[j].classList.remove('active-slider');
	   	    	}
	   	    }


     });

  }
}