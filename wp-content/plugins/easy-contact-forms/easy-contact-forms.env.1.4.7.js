ufoForms.docReady(function(){
	try{
		jqv = jQuery.fn.jquery;
		document.getElementById('support-data-query-version').value = jqv; 
		document.getElementById('support-data-table-query-version').innerHTML = jqv; 
	
	} catch (e) {}
	document.getElementById('support-data-js-errors').value = 'No'; 
	document.getElementById('support-data-table-js-errors').innerHTML = 'No'; 
});