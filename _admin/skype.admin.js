jQuery(document).ready(function(){
	jQuery("#skype_settings #skype_save").click(function(e){
		e.preventDefault();
		var set_name = e.target.className.split(" ")[1];
		var _data = {};
		_data[set_name] = {};
		var form = jQuery("form#skype_settings table tbody");
		jQuery.each(jQuery(form)[0].children, function(a, b){
			var value = false, name = false;
			field = b.children[1].children[0];
			if(field.type === 'text' && field.value.length < 1){
				value = "false";
			}
			if(field.type === 'checkbox'){
				value = (field.checked) ? '1' : '0';
			}
			if(field.tagName.toLowerCase() === 'select'){
				value = field.options[field.selectedIndex].value;
			}
			name = (name) ? name : field.name;
			value = (value) ? value : field.value;
			_data[set_name][name] = value;
			name = value = null;
		});
		do_ajax(_data);
	});
});

function do_ajax(values){
	jQuery.ajax({
		type:"POST",
		url:"/wp-content/plugins/WPSkypeStatus/_admin/skype.ajax.php",
		data:{skype_set:null, data:values},
		error:function(req, status, err){
			console.log(req, status, err);
		},
		success:function(data, status, req){
			console.log(data, status, req);
			//<div id='message' class='updated fade'><p></p></div>
		}
	});
}