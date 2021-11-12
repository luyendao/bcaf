jQuery(document).ready(function($){

if (typeof gform !== 'undefined') { 
	gform.addFilter('gform_file_upload_markup', function (html, file, up, strings, imagesUrl) {
  		var formId = up.settings.multipart_params.form_id,
  		fieldId = up.settings.multipart_params.field_id;
  		html = "<img style='margin-right:5px;' class='gform_delete' "
  		+ "src='" + my_unique_name.stylesheet_directory + "/images/checkmark.png' "
  		+ "onclick='gformDeleteUploadedFile(" + formId + "," + fieldId + ", this);' "
  		+ "alt='" + strings.delete_file + "' title='" + strings.delete_file + "' /><strong>" + file.name +"</strong>";
 
	return html;
	
	});

	}
});
