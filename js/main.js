/**

	*Increase & Decrease Font on website


**/

function increaseFontSize(objId) {
obj = document.getElementById(objId);
//get current font size of obj
currentSize = parseFloat(obj.style.fontSize); //parseFloat gives you just the numerical value, i.e. strips the 'em' bit away
obj.style.fontSize = (currentSize + .1) + "em";
}

function decreaseFontSize(objId) {
obj = document.getElementById(objId);
//get current font size of obj
currentSize = parseFloat(obj.style.fontSize); //parseFloat gives you just the numerical value, i.e. strips the 'em' bit away
obj.style.fontSize = (currentSize - .1) + "em";
}




$(document).ready(function(){


/* 
 * Przewijanie do wybranego miejsca (ID) na stronie - menu1 pozycje 2-1  do 2-4

 */


	$(function () {
		$('.link1').click(function () {
			$("html, body").animate({ scrollTop: $('#target1').offset().top -100 }, 1000);
			return false;
		});
				$('.link2').click(function () {
			$("html, body").animate({ scrollTop: $('#target2').offset().top - 100 }, 1000);
			return false;
		});
				$('.link3').click(function () {
			$("html, body").animate({ scrollTop: $('#target3').offset().top  - 100 }, 1000);
			return false;
		});
				$('.link4').click(function () {
			//$("html, body").animate({ scrollTop: $('#target4').offset().top }, 1000);
			$("html, body").delay(2000).animate({scrollTop: $('#target4').offset().top }, 2000);
			return false;
		});
	});















/* 
 * Przewijanie strony na gore + pokazywanie / ukrywanie przycisku

 */


	// hide #back-top first
	$(".back-top").hide();
	
	// fade in #back-top
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 200) {
				$('.back-top').fadeIn();
			} else {
				$('.back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		$('.back-top').click(function () {
			//alert('Your book is overdue');
			$('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});





