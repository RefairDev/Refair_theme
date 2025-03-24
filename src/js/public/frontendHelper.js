window.addEventListener("load", function(event) {

	let manualToggle = document.querySelector(".manual-close");
	if (manualToggle){
		manualToggle.addEventListener("click", function(e){
			var content = document.querySelector(".manual-inner");
			var wrapper = document.querySelector(".manual");
			e.target.classList.toggle("collapsed");
			
			wrapper.classList.toggle("collapsed");
			if (content.style.maxHeight){
				content.style.maxHeight = null;
				if ( ! e.target.classList.has("user-action-link")){
					e.target.innerText=`>`;
				}
			} else {
				content.style.maxHeight = content.scrollHeight + "px";
				if ( ! e.target.classList.has("user-action-link")){
					e.target.innerText=`×`;
				}
			} 
		});
	}

});

