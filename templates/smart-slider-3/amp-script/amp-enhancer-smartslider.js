var slide = document.getElementsByClassName('n2-ss-slide');
var previous = document.getElementsByClassName('nextend-arrow-previous');
var next = document.getElementsByClassName('nextend-arrow-next');

if(previous){
	previous[0].addEventListener( 'click', function(e) {

		amp_enhancer_initialize_slider('prev')
	});
}

if(next){
	next[0].addEventListener( 'click', function(e) {

		amp_enhancer_initialize_slider('next');
	});
}

function amp_enhancer_initialize_slider(direction){

	var active_slide = document.getElementsByClassName('active-slide');
	var active_data_id = active_slide[0].getAttribute('data-id');
	var data_id = slide[slide.length-1].getAttribute('data-id');
	var initial_data_id = slide[0].getAttribute('data-id');
	var set_data_id;

	  if(direction == 'next'){

			if(active_data_id == data_id){
	           set_data_id = initial_data_id;
			}else{
				set_data_id = ++active_data_id;
			}
	  }
      
      if(direction == 'prev'){
			if(active_data_id == initial_data_id){
	           set_data_id = data_id;
			}else{
				set_data_id = --active_data_id;
			}
	  }


	   active_slide[0].classList.remove('active-slide');
		for (var i = 0; i <slide.length; i++) {
		 var current_data_id = slide[i].getAttribute('data-id');
		  if(set_data_id == current_data_id){
		  	slide[i].classList.add('active-slide')
		  }
		}
}