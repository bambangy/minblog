$('#navbarNav > ul > li > a').each(function () {
	var link = $(this);
	if ($(link).attr('href') == location.href) {
		$(link).addClass('active');
		$(link).attr('aria-current', 'page');
		return false;
	}
});
