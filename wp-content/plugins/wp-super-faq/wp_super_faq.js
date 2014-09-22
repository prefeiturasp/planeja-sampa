jQuery(document).ready(function() {
	// Initialize to closed
	jQuery('.wp-super-faq-answer').hide();

	// If a closed question is clicked
	jQuery('.wp-super-faq-question-closed').live('click', function(event) {
		event.preventDefault();
		var wp_super_faq_id = '#' + jQuery(this).attr('id') + '-answer';
		jQuery(this).removeClass().addClass('wp-super-faq-question-open');
		jQuery(wp_super_faq_id + ' .wp-super-faq-triangle').html('&#9660;');
		jQuery('.wp-super-faq-answer' + wp_super_faq_id).slideDown();
		
	});
	
	// If an open question is clicked
	jQuery('.wp-super-faq-question-open').live('click', function(event) {
		event.preventDefault();
		var wp_super_faq_id = '#' + jQuery(this).attr('id') + '-answer';
		jQuery(this).removeClass().addClass('wp-super-faq-question-closed');
		jQuery(wp_super_faq_id + ' .wp-super-faq-triangle').html('&#9654;');
		jQuery('.wp-super-faq-answer' + wp_super_faq_id).slideUp();
	});
});