<?
# Make html/form helpers available globally, outside of facades

if(!function_exists('backlink')) {
	function backlink($title=null,$action=null,$params=[],$attrs=[])
	{
		return app('html')->backlink($title,$action,$params,$attrs);
	}
}

if(!function_exists('addlink')) {
	function addlink($title=null,$action=null,$params=[],$attrs=[])
	{
		return app('html')->addlink($title,$action,$params,$attrs);
	}
}
if(!function_exists('deletelink')) {
	function deletelink($title=null,$action=null,$params=[],$attrs=[])
	{
		return app('html')->deletelink($title,$action,$params,$attrs);
	}
}
if(!function_exists('editlink')) {
	function editlink($title=null,$action=null,$params=[],$attrs=[])
	{
		return app('html')->editlink($title,$action,$params,$attrs);
	}
}
if(!function_exists('blink')) {
	function blink($label,$url,$options,$type='default')
	{
		return app('html')->blink($label,$url,$options,$type);
	}
}
if(!function_exists('gblink')) {
	function gblink($glyph,$label,$url,$options,$type='default')
	{
		return app('html')->gblink($glyph,$label,$url,$options,$type);
	}
}
if(!function_exists('glink')) {
	function glink($glyph,$label,$url,$options=[])
	{
		return app('html')->glink($glyph,$label,$url,$options);
	}
}
if(!function_exists('alink')) { # reverse parameters. label, url NOT url, label
	function alink($title=null,$url=null,$attributes=[], $secure=null,$escape=false) 
	{
		return app('html')->alink($title,$url,$attributes,$secure,$escape);
	}
}
if(!function_exists('button')) { # with confirm support
	function button($value=null,$options=[])
	{
		return app('html')->button($value,$options);
	}
}


if(!function_exists('thing')) {
	function thing()
	{
		return app('html')->thing();
	}
}
if(!function_exists('ucThing')) {
	function ucThing()
	{
		return app('html')->ucThing();
	}
}
if(!function_exists('humanize')) {
	function humanize($className)
	{
		return app('html')->humanize($className);
	}
}
if(!function_exists('controllerAction')) {
	function controllerAction()
	{
		return app('html')->controllerAction();
	}
}
if(!function_exists('currentController')) {
	function currentController()
	{
		return app('html')->currentController();
	}
}
if(!function_exists('currentAction')) {
	function currentAction()
	{
		return app('html')->currentAction();
	}
}
if(!function_exists('fa')) {
	function fa($glyph)
	{
		return app('html')->fa($glyph);
	}
}
if(!function_exists('g')) {
	function g($glyph)
	{
		return app('html')->g($glyph);
	}
}

if(!function_exists('loadPartial')) {
	function loadPartial($viewFile,$vars=[])
	{
		return app('html')->loadPartial($viewFile,$vars);
	}
}

if(!function_exists('yieldSection')) {
	function yieldSection($section)
	{
		return app('html')->yieldSection($section);
	}
}



###############################################
# Form helpers
###############################################

# If we separate the form into several sections, we still need to assign the model each time...
if(!function_exists('form_set_model')) { 
	function form_set_model($model=null)
	{
		#$modelClass = !empty($Model) ? snake_case(class_basename($Model)) : null;

		return app('form')->setModel($model); 
	}
}

if(!function_exists('url_to_action'))
{
	function url_to_action($url=[],$stringonly=false)
	{
		return app('html')->urlToAction($url,$stringonly);
	}
}

if(!function_exists('url_to_route'))
{
	function url_to_route($url=[],$stringonly=false)
	{
		return app('html')->urlToRoute($url,$stringonly);
	}
}

if(!function_exists('form_open')) { 
	function form_open($model=null,$options=[])
	{
		# Always use this and pass null if needed.
		if(!empty($model))
		{
			form_set_model($model);
		}

		#error_log("FORM_OP=".print_r($options,true));

		# Automatically determine URL if model passed and not explicit.
		if(!isset($options['url']) && !isset($options['route']) && !isset($options['action']) && !empty($model)) # update or store
		{
			#error_log("MODURL");
			if($model->getKey())
			{
				$options['url'] = ['action'=>'update',$model->getKey()];
				# Need to specify PUT/PATCH!!!!
				$options['method'] = 'PUT';
			} else {
				$options['url'] = ['action'=>'store'];
			}
		}

		if(!empty($options['url']) && is_array($options['url'])) # Convert ['action'=>'foobar',1234]
		{
			$options['action'] = url_to_action($options['url']); # users.foobar,1234
		#error_log("URL=".print_r($options['url'],true));
		#error_log("ACT=".print_r($options['action'],true));
			unset($options['url']);
		}

		return app('form')->open($options); # BootstrapForm ignores url/route
		# Use original/underlying form  handler
	}
}

# Use form_open if specifying url/route paramter
/*if(!function_exists('form_open')) { 
	function form_open($model=null,$options=[])
	{
		app('bootstrap_form')->form->model = $model; # Still load model data
		return app('bootstrap_form')->open($options);
	}
}*/

if(!function_exists('form_inline')) { 
	function form_inline($model=null,$options=[])
	{
		$options['model'] = $model;
		return app('bootstrap_form')->inline($options);
	}
}
if(!function_exists('form_horizontal')) { 
	function form_horizontal($model=null,$options=[])
	{
		$options['model'] = $model;
		return app('bootstrap_form')->horizontal($options);
	}
}
if(!function_exists('form_close')) {
	function form_close()
	{
		return app('bootstrap_form')->close();
	}
}
if(!function_exists('form_field_name')) # Properly inserts model name so form fields can be isolated in request hash
{
	function form_field_name($name) # Allow model.field syntax, also ignore existing ones set.
	{
		$Model = app('form')->getModel(); # Hacked into a macro....
		# This might break above if we put in namespace...

		$model_var = !empty($Model) ? $Model->model_var() : null;

		if(preg_match("/.+\[.*\].*/", $name) || preg_match("/^_.+/", $name))
		{
			return $name; # As-is, already in brackets or internal with _underscore
		}
		# What if we want LITERAL string, not part of model?
		# rarely but possible.... put dot at beginning! (namespaced)
		# password => user[password]
		# user.first_name => user[first_name]
		# ._token => _token
		$field_parts = explode(".", $name);
		if(empty($field_parts[0])) { array_shift($field_parts); } # explicit namespace.
		else if(count($field_parts) == 1 && !empty($model_var)) {
			array_unshift($field_parts,$model_var);
		} # Else, already has parts, was explicit with model. Leave alone.

		# Now convert foo.bar => foo[bar]
		# A dot at the end will create a narray, ie foo.bar. => foo[bar][]
		$field_name = array_shift($field_parts);
		while($key = array_shift($field_parts))
		{
			$field_name .= "[$key]";
		}
		return $field_name;
	}
}
if(!function_exists('label')) { 
	function label($name, $value=null,$options=[],$escape=false)
	{
		return app('bootstrap_form')->label($name, $value,$options, $escape);
	}
}
if(!function_exists('input_text')) { 
	function input_text($name,$options=[])
	{
		$options = default_input_options($name,$options);
		return app('bootstrap_form')->text(form_field_name($name),$options['label'],null,$options);
	}
}
if(!function_exists('input_hidden')) { 
	function input_hidden($name, $value=null,$options=[])
	{
		$options = default_input_options($name,$options);
		return app('bootstrap_form')->hidden(form_field_name($name),$value,$options);
	}
}
if(!function_exists('input_password')) { 
	function input_password($name, $options=[])
	{
		$options = default_input_options($name,$options);
		return app('bootstrap_form')->password(form_field_name($name), $options['label'],$options);
	}
}
if(!function_exists('input_email')) { 
	function input_email($name, $options=[])
	{
		$options = default_input_options($name,$options);
		return app('bootstrap_form')->email(form_field_name($name),$options['label'], null,$options);
	}
}
if(!function_exists('input_number')) { 
	function input_number($name, $options=[])
	{
		$options = default_input_options($name,$options);
		return app('bootstrap_form')->number(form_field_name($name),$options['label'], null,$options);
	}
}
if(!function_exists('input_date')) { 
	function input_date($name, $options=[])
	{
		$options = default_input_options($name,$options);
		return app('bootstrap_form')->date(form_field_name($name),$options['label'], null,$options);
	}
}

if(!function_exists('input_file')) { 
	function input_file($name, $options=[])
	{
		$options = default_input_options($name,$options);
		return app('bootstrap_form')->file(form_field_name($name),$options['label'], $options);
	}
}

# HMMMMM TODO? right now we set the value of the checkbox via 'value' and whether to check as 'checked'
if(!function_exists('input_checkbox')) { 
	function input_checkbox($name,$options=[])
	{
		$options = default_input_options($name,$options);
		if(isset($options['value']) && is_array($options['value'])) # Multiple
		{
			if(!isset($options['inline'])) { $options['inline'] = false; }
			return app('bootstrap_form')->checkboxes(form_field_name($name),$options['label'],null,null,$options['inline'],$options);
		} else {
			return # Needs hidden value with 0 so checkboxes can be UNchecked
				app('form')->hidden(form_field_name($name),0) . 
				app('bootstrap_form')->checkbox(form_field_name($name),$options['label'],1,null,$options);
		}
	}
}
if(!function_exists('input_radio')) { 
	function input_radio($name,$options=[])
	{
		$options = default_input_options($name,$options);

		if(isset($options['value']) && is_array($options['value'])) # Multiple
		{
			if(!isset($options['inline'])) { $options['inline'] = false; }
			return app('bootstrap_form')->radios(form_field_name($name),$options['label'],$options['value'],null,$options['inline'],$options);
		} else {
			return app('bootstrap_form')->radio(form_field_name($name),$options['label'], $options['value'],null,$options);
		}
	}
}
if(!function_exists('select')) { 
	function select($name, $list=[],$attrs=[])
	{
		$selected = isset($attrs['value']) ? $attrs['value']  : null; unset($attrs['value']);
		$attrs = default_input_options($name,$attrs);
		return app('bootstrap_form')->select(form_field_name($name),$attrs['label'], $list,$selected,$attrs);
	}
}
if(!function_exists('submit')) { 
	function submit($name, $options=[])
	{
		return app('bootstrap_form')->submit($name, $options);
	}
}

if(!function_exists('default_input_options'))
{
	function default_input_options($name,$options)
	{
		if(!isset($options['label'])) { $options['label'] = title_case(preg_replace("/_/", " ", snake_case($name))); } # Must be explicit, it doesnt insert spaces
		#if(!isset($options['value'])) { $options['value'] = ''; }
		if(!isset($options['id']))
		{
			$options['id'] = studly_case(preg_replace("/\W+/","_", $name));
		}
		return $options;
	}
}
