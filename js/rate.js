"use strict";

/*globals $, jQuery, window, document */

(function ($) {
	var nratings, fratings;
	
	function doHover(e) {
		$(this).prevAll().andSelf()[(e.type === 'mouseenter' ? 'add' : 'remove') + 'Class']('rover');
	}
	
	function doSuccess(data) {
		//console.log(data);
	}
	
	function doError(e) {
		//console.log(e);
	}
	
	function setUI(elem) {
		elem.prevAll().andSelf().addClass('whole').removeClass('empty half rover');
		elem.nextAll().addClass('empty').removeClass('whole half rover');	
	}
	
	function doFormRating() {
		var elem = $(this), indx, ctx, field;
		
		setUI(elem);	
		
		ctx = elem.parent();
		indx = ctx.find('li').index(this) + 1;
		
		if (!$('#comment_karma').length) {
			field = $('<input />').attr({
				name : 'comment_karma',
				id   : 'comment_karma',
				value: indx,
				type : 'hidden'
			});
			ctx.after(field);
		} else {
			$('#comment_karma').val(indx);
		}
	}
	
	function doRating() {
		var elem = $(this), indx, ctx;
		
		setUI(elem);		
		
		ctx = elem.parent();
		indx = ctx.find('li').index(this) + 1;

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
		fratings = $('.form-rating li');
		fratings.bind('mouseenter mouseleave', doHover).click(doFormRating);
		nratings.bind('mouseenter mouseleave', doHover).click(doRating);
	});
}(jQuery));