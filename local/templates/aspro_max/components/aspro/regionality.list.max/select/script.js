$(document).ready(function(){
	$('.confirm_region .aprove').on('click', function(e){
		var _this = $(this);
		$.removeCookie('current_region');

		setCookieOnDomains('current_region', _this.data('id'));
		
		$('.confirm_region').remove();
		if(typeof _this.data('href') !== 'undefined')
			location.href = _this.data('href');
	})

	$(document).on('click', '.confirm_region .close', function(){
		var _this = $(this);
		$.removeCookie('current_region');

		setCookieOnDomains('current_region', _this.data('id'));

		$('.confirm_region').remove();
		$('.top_mobile_region .confirm_wrapper').remove();
	});
	
	$('.js_city_change').on('click', function(){
		var _this = $(this);
		$('.region_wrapper .dropdown').fadeIn(100);
		if(_this.closest('.top_mobile_region').length)
		{
			$('.burger').click();

			$('.mobile_regions > ul > li > a').click()
		}
		$('.confirm_region').remove();
	})
	$(document).on('click', '.js_city_chooser', function(){
		var _this = $(this);
		$('.confirm_region').remove();
		_this.closest('.region_wrapper').find('.dropdown').fadeToggle(100);
	})
	/* close search block */
	$("html, body").on('mousedown', function(e){
		e.stopPropagation();
		if(!$(e.target).hasClass('dropdown'))
		{
			$('.region_wrapper .dropdown').fadeOut(100);
		}

	});
	$('.region_wrapper').find('*').on('mousedown', function(e){
		e.stopPropagation();
	});
	$('.region_wrapper .more_item:not(.current) span').on('click', function(e){
		$.removeCookie('current_region');
		setCookieOnDomains('current_region', $(this).data('region_id'));

		location.href = $(this).data('href');
	});
});