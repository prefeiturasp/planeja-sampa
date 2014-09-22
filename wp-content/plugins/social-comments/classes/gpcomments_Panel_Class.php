<?php
/**
 * socialCommentspanel Extention class to SimplePanel
 *
 * @version 0.1
 * @author Ohad Raz <admin@bainternet.info>
 * @copyright 2013 Ohad Raz
 * 
 */
if (!class_exists('socialCommentspanel')){
	/**
	* socialCommentspanel
	*/
	class socialCommentspanel extends SimplePanel{

		public function admin_menu(){
			$this->slug = add_comments_page(
				$this->title, 
				$this->name, 
				$this->capability,
				get_class(), 
				array($this,'show_page')
			);

			//help tabs
			add_action('load-'.$this->slug, array($this,'_help_tab'));
			add_action( get_class().'add_meta_boxes', array($this,'add_meta_boxes' ));
		}

		/**
		 * add_meta_boxes to page
		 */
		public function add_meta_boxes(){
			add_meta_box( 'gplus_comments', __('Save Settings'), array($this,'savec'), get_class(), 'side','low');
			add_meta_box( 'Credit_sidebar', __('Credits'), array($this,'credits'), get_class(), 'side','low');
			add_meta_box( 'News', __('Latest From Bainternet'), array($this,'news'), get_class(), 'side','low');
			foreach ($this->sections as $s) {
				add_meta_box( $s['id'], $s['title'], array($this,'main_settings'), get_class(), 'normal','low',$s);
			}
		}

		/**
		 * news metabox
		 * @return [type] [description]
		 */
		public function news(){
			$news = get_transient( 'bainternetNews' );
			if ( !$news ) {
				if (!function_exists('fetch_feed'))
					include_once(ABSPATH . WPINC . '/feed.php');
				// Get a SimplePie feed object from the specified feed source.
				$rss = fetch_feed('http://en.bainternet.info/feed');
				ob_start();
				$maxitems = 0;

				if (!is_wp_error( $rss ) ) {
				    $maxitems = $rss->get_item_quantity(5); 
				    $rss_items = $rss->get_items(0, $maxitems); 
				}
				?>

				<ul>
				    <?php if ($maxitems == 0) echo '<li>No items.</li>';
				    else
				    // Loop through each feed item and display each item as a hyperlink.
				    foreach ( $rss_items as $item ) : ?>
				    <li>
				    	<span><?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?></span><br/>
				        <a target="_blank" href='<?php echo esc_url( $item->get_permalink() ); ?>'
				        title='<?php echo 'Posted '.$item->get_date('j F Y | g:i a'); ?>'>
				        <?php echo esc_html( $item->get_title() ); ?></a>
				    </li>
				    <?php endforeach; ?>
				</ul>
				<?php
				$news = ob_get_clean();
				set_transient( 'bainternetNews', $news, 60 * 60 * 24 * 3 );
			}
			echo $news;
		}

		/**
		 * generate plugin button metabox
		 * @return [type] [description]
		 */
		public function savec(){
			echo '<span class="working" style="display:none;"><img src="images/wpspin_light.gif"></span>';
			submit_button('Save Changes');
		}

		/**
		 * main settings metaboxs
		 * @return [type] [description]
		 */
		function main_settings($args,$s = null){
        	
				echo '<table class="form-table">';
        		do_settings_fields(get_class(),$s['id']);
        		echo '</table>';
		}

		function credits(){
			?>
			<p><strong>
				<?php echo __( 'Want to help make this plugin even better? All donations are used to improve and support, so donate $20, $50 or $100 now!' ); ?></strong></p>
			<a class="" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K4MMGF5X3TM5L" target="_blank"><img type="image" src="https://www.paypalobjects.com/<?php echo get_locale(); ?>/i/btn/btn_donate_LG.gif" border="0" alt="PayPal Ñ The safer, easier way to pay online."></a>
            <p><?php _e( 'Or you could:', 'socialComments' ); ?></p>
            <ul>
                    <li><a href="http://wordpress.org/support/view/plugin-reviews/social-comments#postform"><?php _e( 'Rate the plugin 5&#9733; on WordPress.org' ); ?></a></li>
                    <li><a href="http://wordpress.org/extend/plugins/social-comments/"><?php _e( 'Blog about it &amp; link to the plugin page'); ?></a></li>
            </ul>
            <?php
		}

		public function show_page(){
			wp_enqueue_script('post');
			do_action(get_class().'add_meta_boxes');
			if(isset($this->inject['before_wrap']))
				echo $this->inject['before_wrap'];
			?>
		    <div class="wrap">
		    	<?php screen_icon('plugins'); ?>
		        <h2><?php echo $this->name; ?></h2>
		        <div id="message" class="below-h2"></div>
		        <?php settings_errors(); ?>
		        <?php do_action($this->slug.'_before_Form',$this); ?>
		         <form id="BPM_FORM" action="options.php" method="POST">
		         	<div id="poststuff" class="metabox-holder has-right-sidebar">
					    <div class="inner-sidebar">
					    	<!-- SIDEBAR BOXES -->
					    	<?php do_action($this->slug.'_before_sidebar',$this); ?>
					    	<?php do_meta_boxes( get_class(), 'side',$this ); ?>
					    	<?php do_action($this->slug.'_after_sidebar',$this); ?>
					    </div>
					    <div id="post-body" style="background-color: transparent;">
					        <div id="post-body-content">
					            <div id="titlediv"></div>
					            <div id="postdivrich" class="postarea"></div>
					            <div id="normal-sortables" class="meta-box-sortables ui-sortable">
					                <!-- BOXES -->
					                <?php do_action($this->slug.'_before_metaboxes',$this); ?>
									<?php
					                	foreach ($this->sections as $s) {
						        			settings_fields($s['option_group']);
						        		}
					                	do_meta_boxes( get_class(), 'normal',$this ); 
					                ?>
					                <?php do_action($this->slug.'_after_metaboxes',$this); ?>
					            </div>
					        </div>
					    </div>
					    <br class="clear">
					</div>
		            <?php do_action($this->slug.'_after_Fields',$this); ?>
		        </form>
		        <?php do_action($this->slug.'_after_Form',$this); ?>
		    </div>
		    <?php
		    if(isset($this->inject['after_wrap']))
				echo $this->inject['after_wrap'];
			?>
		    <style>
		    .error{ background-color: #FFEBE8;border-color: #C00;}
		    .error input, .error textarea{ border-color: #C00;}
		    </style>
		    <?php
		}

		
		function _setting_radioImage($args) {
			$std   = isset($args['std'])? $args['std'] : '';
			$name  = esc_attr( $args['name'] );
			$value = esc_attr( $this->get_value($args['id'],$std));
			$items = $args['options'];
			$uri = $args['uri'];
			foreach($items as  $v) {
				$checked = ($value==$v) ? ' checked="checked" ' : '';
				echo "<label><input ".$checked." value='$v' name='$name' type='radio' /><img src='{$uri}{$v}/facebook.png'><img src='{$uri}{$v}/gplus.png'><img src='{$uri}{$v}/wp.png'><img src='{$uri}{$v}/disqus.png'></label><br />";
			}
		}


		public function register_settings(){
			foreach ($this->sections as $s) {
				add_settings_section( $s['id'], $s['title'], array($this,'section_callback') , get_class() );
				register_setting( $s['option_group'], $this->option, array($this,'sanitize_callback') );
				
			}
			foreach ($this->fields as $f) {
				add_settings_field( $f['id'], $f['label'], array($this,'show_field'), get_class(), $f['section'], $f ); 
			}
		}

	}//end class

	$p = new socialCommentspanel(
		array(
			'title'      => __('Social Comments'),
			'name'       => __('Social Comments'),
			'capability' => 'edit_plugins',
			'option'     => 'social_comments'
		)
	);
	
	
	
	//main plugin fields
	include_once(dirname(__FILE__).'/../config/main_plugin_fields.php');
	
	$p->add_help_tab(array(
		'id'      => 'GPlus_Comments',
		'title'   => 'Social Comments',
		'content' => '<div style="min-height: 350px">
                <h2 style="text-align: center;">'.__('Social Comments Plugin').'</h2>
                <div>
                		<p>'.__('If you have any questions or problems head over to').' <a href="http://wordpress.org/support/plugin/social-comments">' . __('Plugin Support') . '</a></p>
                        <p>' .__('If you like my wrok then please ') .'<a class="button button-primary" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=K4MMGF5X3TM5L" target="_blank">' . __('Donate') . '</a>
                </div>
        </div>
        '
        )
	);
	$GLOBALS['socialComments_pannel'] = $p;
}//end if