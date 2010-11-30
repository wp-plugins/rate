"use strict";

/*globals $, jQuery, window, document */

(function ($) {
	var ratings;
	
	function doHover(e) {
		$(this).prevAll().andSelf()[(e.type === 'mouseenter' ? 'add' : 'remove') + 'Class']('rover');
	}
	
	function doSuccess(data) {
		console.log(data);
	}
	
	function doError(e) {
		console.log(e);
	}
	
	function doRating() {
		var elem = $(this), indx, ctx;
		
		ctx = elem.parent();
		indx = ctx.find('li').index(this) + 1;
		
		elem.prevAll().andSelf().addClass('whole').removeClass('empty half rover');
		elem.nextAll().addClass('empty').removeClass('whole half rover');		

		$.ajax({
			type   : 'post',
			url    : 'http://' + window.location.host + '/wp-admin/admin-ajax.php',
			data   : {
				action: 'rate_item',
				rating: indx,
				'comment_post_ID' : ctx.attr('data-id'),
				'comment_ID' : ctx.attr('data-comment-id')
			},
			success: doSuccess,
			error  : doError
		});	
	
		return false;
	}
	
	$(document).ready(function () {
		nratings = $('.needs-rating li');
		nratings.bind('mouseenter mouseleave', doHover).click(doRating);
	});
}(jQuery));