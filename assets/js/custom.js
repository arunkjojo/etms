function htmlbodyHeightUpdate(){
	var height3 = $( window ).height()
	var height1 = $('.nav').height()+50
	height2 = $('.main').height()
	if(height2 > height3){
		$('html').height(Math.max(height1,height3,height2)+10);
		$('body').height(Math.max(height1,height3,height2)+10);
	}
	else
	{
		$('html').height(Math.max(height1,height3,height2));
		$('body').height(Math.max(height1,height3,height2));
	}
	
}
$(document).ready(function () {
	htmlbodyHeightUpdate()
	$( window ).resize(function() {
		htmlbodyHeightUpdate()
	});
	$( window ).scroll(function() {
		height2 = $('.main').height()
		htmlbodyHeightUpdate()
	});
});

// Get the <datalist> and <input> elements.
var dataList = document.getElementById('json-datalist');
var input = document.getElementById('ajax');

// Create a new XMLHttpRequest.
var request = new XMLHttpRequest();

// Handle state changes for the request.
request.onreadystatechange = function(response) {
  if (request.readyState === 4) {
    if (request.status === 200) {
      // Parse the JSON
      var jsonOptions = JSON.parse(request.responseText);
  
      // Loop over the JSON array.
      jsonOptions.forEach(function(item) {
        // Create a new <option> element.
        var option = document.createElement('option');
        // Set the value using the item in the JSON array.
        option.value = item;
        // Add the <option> element to the <datalist>.
        dataList.appendChild(option);
      });
      
      // Update the placeholder text.
      input.placeholder = "e.g. datalist";
    } else {
      // An error occured :(
      input.placeholder = "Couldn't load datalist options :(";
    }
  }
};

$( document ).ready(function() {
  if (getCookie("siteWillOpen") === null || getCookie("siteWillOpen") === "") {
    setCookie("siteWillOpen", null, 5);
  }
  function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + exdays * 60 * 1000);
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(";");
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == " ") {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  // let details = navigator.userAgent;

  // let regexp = /android|iphone|kindle|ipad/i;

  // let isMobileDevice = regexp.test(details);

  // if (isMobileDevice) {
  // 	var preSite=getCookie('siteWillOpen');
  // 	setCookie("siteWillOpen", "notOpen", 10);
  // 	// if(confirm("Are you open this ETMS Application in desktop/laptop")){
  // 	// 	// preSite == "open" &&
  // 	// 	window.location.reload();
  // 	// }
  // } else {
  // 	var preSite=getCookie('siteWillOpen');
  // 	setCookie("siteWillOpen", "open", 10);
  // 	// if(preSite == "notOpen" && confirm("Are you open this ETMS Application in desktop/laptop")){
  // 	// 	window.location.reload();
  // 	// }
  // }
});
