<?php
/**
 * SimplePanel is a class to be used in WordPress to create 
 * option panels for themes and plugins using the native settings api.
 *
 * @version 0.2.1
 * @author Ohad Raz <admin@bainternet.info>
 * @copyright 2013 Ohad Raz
 * 
 */
if (!class_exists('SimplePanel')){
	/**
	* SimplePanel
	*/
	class SimplePanel{
		/**
		 * $title 
		 *
		 * Holds page title
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @var string
		 */
		public $title = '';
		/**
		 * $name 
		 *
		 * Holds page name
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @var string
		 */
		public $name = '';
		/**
		 * $capability 
		 *
		 * Capability needed to access the page
		 *
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @var string
		 */
		public $capability = 'manage_options';
		/**
		 * $option 
		 *
		 * Option name
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @var string
		 */
		public $option = '';
		/**
		 * $fields 
		 *
		 * Holds page fields and settings
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @var array
		 */
		public $fields = array();
		/**
		 * $sections 
		 *
		 * Holds page sections
		 *
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @var array
		 */
		public $sections = array();
		/**
		 * $has_errors 
		 *
		 * flag if page holds validation errors
		 *
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @var boolean
		 */
		public $has_errors = false;
		/**
		 * $slug 
		 *
		 * Holds page hook/slug
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @var string
		 */
		public $slug = '';
		/**
		 * $help_tabs 
		 *
		 * Holds help tabs Info
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @var array
		 */
		public $help_tabs = array();
		/**
		 * $upload_JS_Done 
		 *
		 * A flag if upload js as been included.
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.2
		 * @access public
		 * @var boolean
		 */
		public static $upload_JS_Done = false;
		/**
		 * $inject
		 *
		 * Used to inject css and JS
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.3
		 * @access public
		 * @var array
		 */
		public static $inject = array();
		/**
		 * __construct 
		 * 
		 * Class constructor
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @param array $args 
		 * @return void
		 */
		function __construct($args=array()){
			$this->setProperties($args);
			$this->hooks();
		}
		/**
		 * setProperties
		 *
		 * sets defult properties combained with user defined values
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @param array   $args       args to set
		 * @param boolean $properties  properties to set
		 * @return void
		 */
		public function setProperties($args = array(), $properties = false){
			if (!is_array($properties))
				$properties = array_keys(get_object_vars($this));

			foreach ($properties as $key ) {
			  $this->$key = (isset($args[$key]) ? $args[$key] : $this->$key);
			}
		}

		/**
		 * hooks 
		 * 
		 * function that hooks all needed action and filter hooks
		 *
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @return void
		 */
		public function hooks(){
			//admin page
			add_action('admin_menu', array($this,'admin_menu'));

			//register settings
			add_action( 'admin_init', array($this,'register_settings') );

			//limit File type at upload
			add_filter('wp_handle_upload_prefilter', array($this,'Validate_upload_file_type'));

			do_action('SimplePanel_after_hooks',$this);
			$this->extra_hooks();
		}

		/**
		 * extra_hooks 
		 *
		 * Allows Extending classes to add action and filter hook
		 *
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.2
		 * @access public
		 * @return void
		 */
		public function extra_hooks(){return;}

		/**
		 * _help_tab 
		 *
		 * this function adds the help tabs to the page screen 
		 * should not be called directly
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @uses get_current_screen()
		 * @uses add_help_tab()
		 * @return void
		 */
		public function _help_tab(){
			$screen = get_current_screen();

		    /*
		     * Check if current screen is My Admin Page
		     * Don't add help tab if it's not
		     */
		    if ( $screen->id != $this->slug )
		        return;

		    // Add my_help_tab if current screen is My Admin Page
		    foreach ($this->help_tabs as $value) {
		    	$screen->add_help_tab($value);	
		    }
		}

		/**
		 * register_settings
		 *
		 * Adds sections Registers the settings and adds the settings field
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @uses add_settings_section()
		 * @uses register_setting()
		 * @uses add_settings_field()
		 * 
		 * @return [type] [description]
		 */
		public function register_settings(){
			foreach ($this->sections as $s) {
				add_settings_section( $s['id'], $s['title'], array($this,'section_callback') , get_class($this) );
				register_setting( $s['option_group'], $this->option, array($this,'sanitize_callback') );
				
			}
			foreach ($this->fields as $f) {
				add_settings_field( $f['id'], $f['label'], array($this,'show_field'), get_class($this), $f['section'], $f ); 
			}
		}

		/**
		 * admin_menu
		 *
		 * Adds admin menu page
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @uses add_options_page()
		 * @access public
		 * @return void
		 */
		public function admin_menu(){
			$this->slug = add_options_page(
				$this->title, 
				$this->name, 
				$this->capability,
				get_class($this), 
				array($this,'show_page')
			);

			//help tabs
			add_action('load-'.$this->slug, array($this,'_help_tab'));
			do_action('SimplePanel_after_admin_menu',$this);
		}

		/**
		 * show_page 
		 *
		 * this method displays the page itself
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @uses do_settings_sections()
		 * @uses settings_fields()
		 * @uses submit_button()
		 * @return void
		 */
		public function show_page(){
			?>
		    <div class="wrap">
		        <h2><?php echo $this->name; ?></h2>
		         <form action="options.php" method="POST">
		            <?php 
		            	do_settings_sections(get_class($this) );
		            	foreach ($this->sections as $s) {
		            		settings_fields($s['option_group']);
		            	}
		            	submit_button(); 
		            ?>
		        </form>
		    </div>
		    <?php
		}

		/**
		 * sanitize_callback 
		 *
		 * This function lets you sanitize the data before saving
		 * 
		 * @uses apply_filters('SimplePanel_sanitize'
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @param  mixed $input form data
		 * @return mixed sanitized data
		 */
		public function sanitize_callback($input){
			//sanitize
			$input = apply_filters('SimplePanel_sanitize',$input,$this->option,$this);
			
			//get all options
		    //$options = get_option($this->option);

		    //update only the neede options
		    foreach ($input as $key => $value){
		        $options[$key] = $value;
		    }
		    //return all options
		    return $options;
		}

		/**
		 * show_field 
		 *
		 * a magic function to load the right field type
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @param  array $args field arguments
		 * @return void
		 */
		public function show_field($args){
			if (method_exists($this, '_setting_'.$args['type'])){
				call_user_func( array( $this, '_setting_'.$args['type']),$args);
				$this->_settings_field_desc($args);
			}
		}

		/**
		 * get_value 
		 *
		 * function to get a value of the page fields
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @uses get_option()
		 * @param  string $key id of the field to get
		 * @param  string $def default value to return if not found
		 * @return mixed
		 */
		public function get_value($key = '',$def = ''){
			$options = get_option($this->option);
			if (isset($options[$key]))
				return $options[$key];
			return $def;
		}

		/**
		 * _settings_field_desc 
		 *
		 * Used to show field description
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access private
		 * @param  array $args field arguments
		 * @return void
		 */
		function _settings_field_desc($args){
			if (isset($args['desc']))
				echo '<p class="description">'.$args['desc'].'</p>';
		}

		/**
		 * _setting_editor 
		 *
		 * Used to show editor [wysiwyg] field 
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access private
		 * @uses wp_editor()
		 * @param  array $args field arguments
		 * @return void
		 */
		function _setting_editor( $args ) {
			$std   = isset($args['std'])? $args['std'] : '';
			$name  = esc_attr( $args['name'] );
			$value = esc_attr( $this->get_value($args['id'],$std));
		    wp_editor( $value, $name);
		}

		/**
		 * _setting_text
		 *
		 * Used to show a text field 
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access private
		 * @param  array $args field arguments
		 * @return void
		 */
		function _setting_text( $args ) {
			$std   = isset($args['std'])? $args['std'] : '';
			$name  = esc_attr( $args['name'] );
			$value = esc_attr( $this->get_value($args['id'],$std));
		    echo "<input type='text' name='$name' value='$value' />";
		}

		/**
		 * _setting_select 
		 *
		 * 
		 * Used to show a select field 
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access private
		 * @param  array $args field arguments
		 * @return void
		 */
		function  _setting_select($args) {
			$std   = isset($args['std'])? $args['std'] : '';
			$name  = esc_attr( $args['name'] );
			$value = esc_attr( $this->get_value($args['id'],$std));
			$items = $args['options'];
			echo "<select name='$name'>";
			foreach($items as $l => $v) {
				$selected = ($value == $v) ? 'selected="selected"' : '';
				echo "<option value='$v' $selected>$l</option>";
			}
			echo "</select>";
		}
		/**
		 * _setting_textarea 
		 * 
		 * Used to show a textarea field 
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access private
		 * @param  array $args field arguments
		 * @return void
		 */
		function _setting_textarea($args) {
			$std   = isset($args['std'])? $args['std'] : '';
			$name  = esc_attr( $args['name'] );
			$value = esc_attr( $this->get_value($args['id'],$std));
			echo "<textarea  name='$name' rows='7' cols='50' type='textarea'>$value</textarea>";
		}
		/**
		 * _setting_sortable 
		 * 
		 * Used to show a sortable field 
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.3
		 * @access private
		 * @param  array $args field arguments
		 * @return void
		 */
		function _setting_sortable($args) {
			wp_enqueue_script( 'jquery');
			wp_enqueue_script( 'jquery-ui-sortable');
			add_filter('admin_footer',array($this,'_sortable_js'));
			$std   = isset($args['std'])? $args['std'] : '';
			$name  = esc_attr( $args['name'] );
			$items = $this->get_value($args['id'],$args['options']);
			echo "<ul name='$name' class='sortable'>";
			foreach($items as $l => $v) {
				echo "<li id='{$args['id']}{$v}'><input type='hidden' name='{$name}[{$l}]' value='$v'>$l</option>";
			}
			echo "</ul>";
		}

		/**
		 * _setting_checkbox 
		 * 
		 * Used to show a checkbox field 
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access private
		 * @param  array $args field arguments
		 * @return void
		 */
		function _setting_checkbox($args) {
			$std     = isset($args['std'])? $args['std'] : false;
			$name    = esc_attr( $args['name'] );
			$value   = esc_attr( $this->get_value($args['id'],$std));
			$checked = ($value!= false)? ' checked="checked" ' : '';
			echo "<input ".$checked." name='$name' type='checkbox' value='1' />";
		}

		/**
		 * _setting_radio 
		 * 
		 * Used to show a radio field 
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access private
		 * @param  array $args field arguments
		 * @return void
		 */
		function _setting_radio($args) {
			$std   = isset($args['std'])? $args['std'] : '';
			$name  = esc_attr( $args['name'] );
			$value = esc_attr( $this->get_value($args['id'],$std));
			$items = $args['options'];
			foreach($items as $l => $v) {
				$checked = ($value==$v) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$v' name='$name' type='radio' /> $l</label><br />";
			}
		}

		/**
		 * _setting_image
		 * 
		 * Used to show a image upload field 
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.2
		 * @access private
		 * @param  array $args field arguments
		 * @return void
		 */
		function _setting_image($args) {
			wp_enqueue_media();
			$std   = isset($args['std'])? $args['std'] : array('id' => '', 'url' => '');
			$name  = esc_attr( $args['name'] );
			$value = $this->get_value($args['id'],$std);
			$has_image = (empty($value['url']))? false : true;
			$w = isset($args['width'])? $args['width'] : 'auto';
			$h = isset($args['height'])? $args['height'] : 'auto';
			$PreviewStyle = "style='width: $w; height: $h;". ( (!$has_image)? "display: none;'": "'");
			$id = str_replace(array(" ","[","]"),array("","",""),$name);
			$multiple = isset($args['multi'])? $args['multi'] : false;
			$multiple = ($multiple)? "multiFile '" : "";

			echo "<span class='simplePanelImagePreview'><img {$PreviewStyle} src='{$value['url']}'><br/></span>";
			echo "<input type='hidden' name='{$name}[id]' value='{$value['id']}'/>";
			echo "<input type='hidden' name='{$name}[url]' value='{$value['url']}'/>";
			if ($has_image)
				echo "<input class='{$multiple} button  simplePanelimageUploadclear' id='{$id}' value='Remove Image' />";
			else
				echo "<input class='{$multiple} button simplePanelimageUpload' id='{$id}' value='Upload Image' />";
			
			$this->_upload_js();
		}

		/**
		 * _setting_file
		 * 
		 * Used to show a file upload field 
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.2
		 * @access private
		 * @param  array $args field arguments
		 * @return void
		 */
		function _setting_file($args) {
			$std   = isset($args['std'])? $args['std'] : array('id' => '', 'url' => '');
			$multiple = isset($args['multi'])? $args['multi'] : false;
			$multiple = ($multiple)? "multiFile '" : "";
			$name  = esc_attr( $args['name'] );
			$value = $this->get_value($args['id'],$std);
			$has_file = (empty($value['url']))? false : true;
			$type = isset($args['mime_type'])? $args['mime_type'] : '';
			$ext = isset($args['ext'])? $args['ext'] : '';
			$type = (is_array($type)? implode("|",$type) : $type);
			$ext = (is_array($ext)? implode("|",$ext) : $ext);
			$id = str_replace(array(" ","[","]"),array("","",""),$name);
			$li = ($has_file)? "<li><a href='{$value['url']}' target='_blank'></a></li>": "";

			echo "<span class='simplePanelfilePreview'><ul>{$li}</ul></span>";
			echo "<input type='hidden' name='{$name}[id]' value='{$value['id']}'/>";
			echo "<input type='hidden' name='{$name}[url]' value='{$value['url']}'/>";
			if ($has_file)
				echo "<input class='{$multiple} button simplePanelfileUploadclear' id='{$id}' value='Remove File' data-mime_type='{$type}' data-ext='{$ext}'/>";
			else
				echo "<input class='{$multiple} button simplePanelfileUpload' id='{$id}' value='Upload File' data-mime_type='{$type}' data-ext='{$ext}'/>";

			$this->_upload_js();
		}


		/**
		 * section_callback
		 * @todo do something wi this function, maybe a hook?
		 * @return [type] [description]
		 */
		function section_callback() {
		    
		    //todo
		}

		/**
		 * add_field 
		 * 
		 * Used to add a field to the page
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @param  array $f field arguments
		 * @return void
		 */
		public function add_field($f){
			$f['name'] = $this->option .'[' .$f['id'].']';
			$this->fields[] = $f;
		}

		/**
		 * add_section 
		 * 
		 * Used to add a section to the page
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @param  array $f section arguments
		 * @return string section id
		 */
		public function add_section($f){
			$this->sections[] = $f;
			return $f['id'];
		}

		/**
		 * Validate_upload_file_type 
		 *
		 * Checks if the uploaded file is of the expected format
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.2
		 * @access public
		 * @uses get_allowed_mime_types() to check allowed types
		 * @param array $file uploaded file
		 * @return array file with error on mismatch
		 */
		function Validate_upload_file_type($file) {
		  	if (isset($_POST['uploadeType']) && !empty($_POST['uploadeType'])){
		  		$allowed = explode("|", $_POST['uploadeType']);
		  		$ext =  substr(strrchr($file['name'],'.'),1);

		  		if (!in_array($ext, (array)$allowed)){
		  			$file['error'] = __("Sorry, you cannot upload this file type for this field.");
		  			return $file;
		  		}

		  		foreach (get_allowed_mime_types() as $key => $value) {
		  			if (strpos($key, $ext) || $key == $ext)
		  				return $file;
		  		}
		  		$file['error'] = __("Sorry, you cannot upload this file type for this field.");
		  	}
		  	return $file;
		}

		/**
		 * addError 
		 * 
		 * Used to add a field validation error
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 * @uses add_settings_error()
		 * @param  array $e error arguments
		 * @return void
		 */
		public function addError($e){
			$setting = get_class($this) . esc_attr($this->option);
			$code    = $e['code']; 
			$message = $e['message']; 
			$type    = isset($e['type'])? $e['type']: 'error';
			add_settings_error( $setting, $code, $message, $type );
			$this->has_errors = true;
		}

		/**
		 * add_help_tab
		 * 
		 * Used to add an help tab to the page
		 * 
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.1
		 * @access public
		 *  
		 * @param array $args 
		 *      'id'	=> 'my_help_tab',
		 *      'title'	=> __('My Help Tab'),
		 *      'content'	=> '<p>' . __( 'Descriptive content that will show in My Help Tab-body goes here.' ) . '</p>',
		 *      callback' => callback function
		 * @return void
		 */
		public function add_help_tab($args = array()){

			$this->help_tabs[] = $args;
		}
		
		/**
		 * _sortable_js 
		 *
		 * This function prints the needed Javascript to use sortable
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.3
		 * @access public
		 * @return Void
		 */
		function _sortable_js(){
			static $done = false;
			if ($done) return;
			$done = true;
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($){
					$( ".sortable" ).sortable();
    				$( ".sortable" ).disableSelection();
				});
			</script>
			<?php
		}
		
		/**
		 * _upload_js 
		 *
		 * This function prints the needed Javascript to use upload fileds fiele and image
		 * @author Ohad Raz <admin@bainternet.info>
		 * @since 0.2
		 * @access public
		 * @return Void
		 */
		function _upload_js(){
			if (self::$upload_JS_Done)
				return;
			wp_enqueue_media();
			self::$upload_JS_Done = true;
			// Uploading files
			?>
			<script type="text/javascript">
			var simplePanelmedia;
			jQuery(document).ready(function($){
				var simplePanelupload =(function(){
					var inited;
					var file_id;
					var file_url;
					var file_type;
					function init (){
						
						return {
							image_frame: new Array(),
							file_frame: new Array(),
							hooks:function(){
								$('.simplePanelimageUpload,.simplePanelfileUpload').live('click', function( event ){
									event.preventDefault();
									if ($(this).hasClass('simplePanelfileUpload'))
										inited.upload($(this),'file');
									else
										inited.upload($(this),'image');
								});

								$('.simplePanelimageUploadclear,.simplePanelfileUploadclear').live('click', function( event ){
									event.preventDefault();
									inited.set_fields($(this));
									$(inited.file_url).val("");
									$(inited.file_id).val("");
									if ($(this).hasClass('simplePanelimageUploadclear')){
										inited.set_preview('image',false);
										inited.replaceImageUploadClass($(this));
									}else{
										inited.set_preview('file',false);
										inited.replaceFileUploadClass($(this));
									}
								});	
							},
							set_fields: function (el){
								inited.file_url = $(el).prev();
								inited.file_id = $(inited.file_url).prev();
							},
							upload:function(el,utype){
								inited.set_fields(el)
								if (utype == 'image')
									inited.upload_Image($(el));
								else
									inited.upload_File($(el));
							},
							upload_File: function(el){
								// If the media frame already exists, reopen it.
								var mime = $(el).attr('data-mime_type') || '';
								var ext = $(el).attr("data-ext") || 'file';
								var name = $(el).attr('id');
								var multi = ($(el).hasClass("multiFile")? true: false);
								
								if ( typeof inited.file_frame[name] !== "undefined")  {
								  	inited.file_frame[name].uploader.uploader.param( 'uploadeType', ext);
								  	inited.file_frame[name].open();

								  	return;
								}
								// Create the media frame.
								
								inited.file_frame[name] = wp.media({
									library: {
						                type: mime
						            },
									title: jQuery( this ).data( 'uploader_title' ),
								  	button: {
								    	text: jQuery( this ).data( 'uploader_button_text' ),
								  	},
								  	multiple: multi  // Set to true to allow multiple files to be selected
								});
								

								// When an image is selected, run a callback.
								inited.file_frame[name].on( 'select', function() {
								  // We set multiple to false so only get one image from the uploader
									attachment = inited.file_frame[name].state().get('selection').first().toJSON();
								  // Do something with attachment.id and/or attachment.url here
								  	$(inited.file_id).val(attachment.id);
								  	$(inited.file_url).val(attachment.url);
								  	inited.replaceFileUploadClass(el);
								  	inited.set_preview('file',true);
								});
								// Finally, open the modal
								
								inited.file_frame[name].open();
								inited.file_frame[name].uploader.uploader.param( 'uploadeType', ext );
							},
							upload_Image:function(el){
								var name = $(el).attr('id');
								var multi = ($(el).hasClass("multiFile")? true: false);
								// If the media frame already exists, reopen it.
								if ( typeof inited.image_frame[name] !== "undefined")  {
								  	inited.image_frame[name].open();
								  	return;
								}
								// Create the media frame.
								inited.image_frame[name] =  wp.media({
									library: {
						                type: 'image'
						            },
									title: jQuery( this ).data( 'uploader_title' ),
								  	button: {
								    	text: jQuery( this ).data( 'uploader_button_text' ),
								  	},
								  	multiple: multi  // Set to true to allow multiple files to be selected
								});

								// When an image is selected, run a callback.
								inited.image_frame[name].on( 'select', function() {
									// We set multiple to false so only get one image from the uploader
									attachment = inited.image_frame[name].state().get('selection').first().toJSON();
								  	// Do something with attachment.id and/or attachment.url here
								  	$(inited.file_id).val(attachment.id);
								  	$(inited.file_url).val(attachment.url);
								  	inited.replaceImageUploadClass(el);
								  	inited.set_preview('image',true);
								});
								// Finally, open the modal
								inited.image_frame[name].open();
							},
							replaceImageUploadClass: function(el){
								if ($(el).hasClass("simplePanelimageUpload")){
									$(el).removeClass("simplePanelimageUpload").addClass('simplePanelimageUploadclear').val('Remove Image');
								}else{
									$(el).removeClass("simplePanelimageUploadclear").addClass('simplePanelimageUpload').val('Upload Image');
								}
							},
							replaceFileUploadClass: function(el){
								if ($(el).hasClass("simplePanelfileUpload")){
									$(el).removeClass("simplePanelfileUpload").addClass('simplePanelfileUploadclear').val('Remove File');
								}else{
									$(el).removeClass("simplePanelfileUploadclear").addClass('simplePanelfileUpload').val('Upload File');
								}
							},
							set_preview: function(stype,ShowFlag){
								ShowFlag = ShowFlag || false;
								var fileuri = $(inited.file_url).val();
								if (stype == 'image'){
									if (ShowFlag)
										$(inited.file_id).prev().find('img').attr('src',fileuri).show();
									else
										$(inited.file_id).prev().find('img').attr('src','').hide();
								}else{
									if (ShowFlag)
										$(inited.file_id).prev().find('ul').append('<li><a href="' + fileuri + '" target="_blank">'+fileuri+'</a></li>');
									else
										$(inited.file_id).prev().find('ul').children().remove();
								}
							}
						}
					}
				 
					return {
						getInstance :function(){
							if (!inited){
								inited = init();
							}
							return inited; 
						}
					}
				})()
				simplePanelmedia = simplePanelupload.getInstance();
				simplePanelmedia.hooks();
			});
			</script>
			<?php
		}

	}//end class
}//end if