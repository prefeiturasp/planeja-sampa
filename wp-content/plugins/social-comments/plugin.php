<?php
/*
Plugin Name: Social Comments
Plugin URI: http://en.bainternet.info 
Description: This plugin adds Google Plus Comments , facebook comments, Disqus comments and the native comments system to your blog 
Version: 0.1.2
Author: Bainternet 
Author Email: admin@bainternet.info 
License:

  Copyright  Bainternet 2013 (admin@bainternet.info)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/
if (!class_exists('SocialComments')){

    class SocialComments {
		/****************
		 *  Public Vars *
		 ***************/
		 
        /**
         * $dir 
         * 
         * olds plugin directory
         * @var string
         */
		public $dir = '';
		/**
		 * $url 
		 * 
		 * holds assets url
		 * @var string
		 */
        public $url = '';
        /**
         * $txdomain 
         *
         * holds plugin textDomain
         * @var string
         */
        public $txdomain = 'GPComments';
		/**
         * $options
         *
         * holds plugin settings options
         * @var array
         */
        public $options = array();
		/****************
		 *    Methods   *
		 ***************/ 

        /**
         * Plugin class Constructor
         */
        function __construct() {
			$this->setProperties();
        	$this->dir = plugin_dir_path(__FILE__);
        	//Respects SSL
        	$this->url = plugins_url('assets/', __FILE__);
			$this->hooks();
        }
        
        /**
         * hooks 
         *
         * function used to add action and filter hooks 
         * Used with `adminHooks` and `clientHokks`
         *
         * hooks for both admin and client sides should be added at the buttom
         * 
         * @return void
         */
		public function hooks(){
			if(is_admin())
				$this->adminHooks();
			else
				$this->clientHooks();
			
			/**
			 * hooks for both admin and client sides
			 * hooks should be here
			 */
		}
		
		/**
		 * adminHooks 
		 * 
		 * Admin side hooks should go here
		 * @return void
		 */
		function adminHooks(){
			//add admin panel
			if (!class_exists('SimplePanel'))
				require_once(plugin_dir_path(__FILE__).'classes/Simple_Panel_Class.php');
				
			require_once(plugin_dir_path(__FILE__).'classes/gpcomments_Panel_Class.php');
		}

		/**
		 * clientHooks
		 *
		 *  client side hooks should go here
		 * @return void
		 */
		function clientHooks(){
			$options = $this->getOptions();
			if ($options['gplus_comments_enabled'] || $options['facebook_comments_enabled'] || $options['disqus_comments_enabled'] ){
				//chatch comment template hook for replacement
				add_filter('comments_template',array($this, 'add_socialcomments_tmplate'));
				//add css
				add_action( 'wp_enqueue_scripts', array($this,'enqueue_style' ));
			}
				
		}
		
		/**
 		 * setProperties 
 		 *
 		 * function to set class Properties
 		 * @param array   $args       array of arguments
 		 * @param boolean $properties arguments to set
 		 */
 		public function setProperties($args = array(), $properties = false){
			if (!is_array($properties))
				$properties = array_keys(get_object_vars($this));
 
			foreach ($properties as $key ) {
			  $this->$key = (isset($args[$key]) ? $args[$key] : $this->$key);
			}
		}

		/**
		 * createNewView 
		 *
		 * This create a new EasyView instance
		 * @param  array  $args [description]
		 * @return object EasyView instance
		 */
		public function createNewView($args = array('vars' => array())){
			if(!class_exists('EasyView'))
				require_once($this->dir.'/classes/EasyView.php');
			return new EasyView('',$args);
		} 
		
		/**
		 * createViewGet 
		 *
		 * This is a shorthand function for creating a new EasyView object 
		 * and geting the rendered template.
		 *
		 * 
		 * @param  string $template    Template File
		 * @param  string $templatedir templates directory
		 * @param  array  $args        view args
		 * @return string rendered view as astring
		 */
		public function createViewGet($template = '', $templatedir = '', $args = array('vars' => array())){
			$v = $this->createNewView($templatedir, $args);
			return $v->getRender($template);
		}
		
		/**
		 * getOptions 
		 *
		 * gets the saved options and sets the defaults
		 * @since 0.1
         * @access public
		 * @return array
		 */
		public function getOptions(){
			if($this->options == null){
				$def = array(
					'how'                       => 'Tabbed',
					'order'                     => array('wp', 'gplus', 'facebook','disqus'),
					'iconset'                   => 'cleanlight',
					'wp_comments_enabled'       => TRUE,
					'wp_comments_label'         => __('WordPress'),
					'wp_comments_img'           => '',
					'gplus_comments_enabled'    => FALSE,
					'gplus_comments_label'      => __('Google + '),
					'gplus_comments_img'        => '',
					'disqus_comments_enabled'   => FALSE,
					'disqus_comments_label'     => __('Disqus'),
					'disqus_comments_img'       => '',
					'disqus_shortname'          => '',
					'facebook_comments_enabled' => FALSE,
					'facebook_appID'            => 'null',
					'facebook_colorScheme'      => 'light',
					'facebook_comments_label'   => __('Facebook'),
					'facebook_comments_img'     => '',
					'tabsTrigger'               => 'Click',
					'pre_tabs_label'			=> NULL
				);
				$tmp = get_option('social_comments',array());
				//fix reverse boolean
				if (count($tmp) > 0 && !isset($tmp['wp_comments_enabled'])){
					$tmp['wp_comments_enabled'] = false;
				}
				$this->options = array_merge($def,$tmp);
			}
			return $this->options;
		}
		
		public function add_plusonecomments( $file ){
			return $this->dir .'views/Google.comments.markup.php';
		}
		
		public function getGoocleComments(){
			$options = $this->getOptions();
			$view = $this->createViewGet($this->dir.'/views/Google.comments.markup.php');
			return $view;
		}

		public function getFacebookComments(){
			$options = $this->getOptions();
			$v = $this->createNewView();
			$v->Color_Scheme = $options['facebook_colorScheme'];
			$v->appID = $options['facebook_appID'];
			$this->modLink = '';
			if (current_user_can( 'moderate_comments' ))
				$this->modLink = '<a href="http://developers.facebook.com/tools/comments?id='.$options['facebook_appID'].'" target="_blank">'.__('Moderate Facebook Comments').'</a>';
			return $v->getRender($this->dir.'/views/Facebook.comments.markup.php');
		}

		public function getDisqusComments(){
			$options = $this->getOptions();
			$v = $this->createNewView();
			$v->shortname = $options['disqus_shortname'];
			return $v->getRender($this->dir.'/views/disqus.comments.markup.php');
		}

		public function getWordPressComments($file){
			ob_start();
			include($file);
			return ob_get_clean();
		}

		function add_socialcomments_tmplate($file){
			if ((is_single() || is_page() || is_singular()) && comments_open()){
				$options = $this->getOptions();
				$comm ='';
				$style ='';

				//exit if password protected and no cookie is found
				if (post_password_required()){
					echo '<p><em>'.__('This is password protected.').'</em></p>';
					return $this->dir.'/views/comments.php';
				}
				//loop over the systems
				foreach ($options['order'] as $key => $value) {
					//skip if disabled
					if (!$options[$value.'_comments_enabled']) 
						continue;

					
					$comm .= "<div id='{$value}_comments' style='{$style}'>";
					//$style = 'display:none;';
					switch ($value) {
						case 'wp':
							$comm .= $this->getWordPressComments($file);
							break;
						case 'gplus':
							$comm .= $this->getGoocleComments();
							break;
						case 'facebook':
							$comm .= $this->getFacebookComments();
							break;
						case 'disqus':
							$comm .= $this->getDisqusComments();
							break;
					}
					$comm .= "</div><!-- #{$value} -->";
					if ($options['how'] == 'Tabbed'){
						if (isset($options[$value.'_comments_img']['url']) && !empty($options[$value.'_comments_img']['url']))
							$img = $options[$value.'_comments_img']['url'];
						else{
							$img = $this->url .'images/icons/'.$options['iconset'].'/'.$value.'.png';
						}
						$img = "<img src='{$img}'>";
						$ul[] = "<li><a href='#{$value}_comments'>{$img}".$options[$value.'_comments_label']."</a></li>";
					}
				}
				if ($options['how'] == 'Tabbed'){
					$pre_tabs = isset($options['pre_tabs_label'])? $options['pre_tabs_label'] : '';
					$comm = "<div id='social_comments_control'>{$pre_tabs}<ul id='social_comments_nav'>".implode("",$ul). "</ul>". $comm ."</div>";
					//add jQuery tabs
					wp_enqueue_script( 'jquery');
					wp_enqueue_script( 'jquery-ui-tabs');
					add_filter('wp_footer',array($this,'tabs_JS'),1000);
				}
				echo $comm;
				//dirty dirty hack to include an empty file since this filter will include the native comments otherwise
				return $this->dir.'/views/comments.php';
			}else{
				return $file;
			}
		}

		function enqueue_style(){
			$suffix = ( is_rtl() ) ? '.rtl': '';
			wp_register_style( 'social_comments_rtl', $this->url.'css/social_comments'.$suffix.'.css');
			wp_enqueue_style( 'social_comments_rtl' );
			
		}

		function tabs_JS(){
			$options = $this->getOptions();
			if ($options['tabsTrigger'] == "Click"){
				?><script type="text/javascript">jQuery(document).ready(function($){$("#social_comments_control").tabs();});</script><?php
			}else{
				?><script type="text/javascript">jQuery(document).ready(function($){$("#social_comments_control").tabs({ event: "mouseover" });});</script><?php
			}
		}
		
    } // end class
}//end if
$GLOBALS['SocialComments'] = new SocialComments();