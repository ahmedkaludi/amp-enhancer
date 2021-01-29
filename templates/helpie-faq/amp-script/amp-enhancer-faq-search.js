var search_input = document.getElementsByClassName("search__input");
var accordion_header = document.getElementsByClassName("accordion__header");

if(accordion_header){
	for(var j=0; j<accordion_header.length;j++ ){
		accordion_header[j].addEventListener( 'click', function(e) {
			var parentnode = e.currentTarget.parentNode;
			var accordion_body = parentnode.getElementsByClassName("accordion__body");
			accordion_body[0].style.display = 'block';
			var test =  accordion_body[0].getAttribute('hidden');
			if(test == null){
				accordion_body[0].setAttribute("hidden",true);
				e.currentTarget.classList.remove("active");
			}else{
				accordion_body[0].removeAttribute("hidden");
				e.currentTarget.classList.add("active");
			}
			//e.currentTarget.classList.remove("accordion__header");
			//e.currentTarget.classList.add("accordion__header_active");
		});
	}
}

	        
if(search_input){
	search_input[0].addEventListener( 'input', function(e) {
		  var value = e.currentTarget.value;
	      amp_enhancer_faq_search_functionality(value);

	    });
}

function  amp_enhancer_faq_search_functionality(value){

  var item = document.getElementsByClassName("accordion__item");
  for(var i=0; i<item.length;i++ ){
  	var accrdn_title = item[i].getElementsByClassName('accordion__title');
  	var accrdn_body = item[i].getElementsByClassName('accordion__body');
  	var accrdn_html = accrdn_title[0].innerHTML+'  '+accrdn_body[0].innerHTML;
  	accrdn_html = accrdn_html.toString().toLowerCase();
  	value = value.toLowerCase();

  	var index = accrdn_html.indexOf(value);

	  	if(index > -1){
			item[i].style.display = 'block';
	  	}else{
	       item[i].style.display = 'none';
	  	}
   }
}
