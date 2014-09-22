<?php 
/* section main */
if ( !defined('ABSPATH')) exit;
$setting = $p->add_section(array(
	'option_group'      =>  'social_comments',
	'sanitize_callback' => null,
	'id'                => 'social_comments', 
	'title'             => __('Social Comments')
	)
);
//select field
$p->add_field(array(
	'label'   => __('How To display Social Comments'),
	'std'     => 'tabbed',
	'id'      => 'how',
	'type'    => 'select',
	'section' => $setting,
	'options' => array(
		'Stacked' => 'Stacked',
		'Tabbed'  => 'Tabbed',
	),
	'desc'    => __('<strong>Stacked</strong> will place the each comment system under another based on the order below,<br/><strong>Tabbed</strong> will palce each comment system in its own tab based on the oder bellow')
	)
);
//select field
$p->add_field(array(
	'label'   => __('How To Trigger tabs?'),
	'std'     => 'Click',
	'id'      => 'tabsTrigger',
	'type'    => 'select',
	'section' => $setting,
	'options' => array(
		'Click' => 'Click',
		'Hover'  => 'Hover',
	),
	'desc'    => __('<strong>Click</strong> will switch between Tabs on label click,<br/><strong>Hover</strong> will switch between Tabs on label hover<br /> Only used in tabbbed view.')
	)
);
//sortable field
$p->add_field(array(
	'label'   => __('Social Comments display order'),
	'std'     => 'tabbed',
	'id'      => 'order',
	'type'    => 'sortable',
	'section' => $setting,
	'options' => array(
		'WordPress Comments'   => 'wp',
		'Disqus comments'      => 'disqus',
		'Google plus comments' => 'gplus',
		'Facebook comments'    => 'facebook',
	),
	'desc'    => __('Drag and drop to set the order of the comment systems.')
	)
);
//text field
$p->add_field(array(
	'section' => $setting,
	'label'   => __('Label Before tabs'),
	'std'     => NULL,
	'id'      => 'pre_tabs_label',
	'type'    => 'text',
	'desc'    => __('Enter a label to show above the tabs something like <em>"&lt;h2&gt;Comment Here&lt;/h2&gt;"</em> (optional) leave lank for none.')
	)
);

//WordPress
$setting4 = $p->add_section(array(
	'option_group'      =>  'social_comments',
	'sanitize_callback' => null,
	'id'                => 'wp_social_comments', 
	'title'             => __('WordPress Comments settings')
	)
);
//checkbox field
$saved = get_option('social_comments',array());
if (count($saved) > 0 && !isset($saved['wp_comments_enabled']) )
	$saved = FALSE;
else
	$saved = TRUE;
$p->add_field(array(
	'section' => $setting4,
	'label'   => __('Enable native WordPress comments'),
	'std'     => $saved,
	'id'      => 'wp_comments_enabled',
	'type'    => 'checkbox',
	'desc'    => __('UnCheck to disable the Native Comments')
	)
);
//text field
$p->add_field(array(
	'section' => $setting4,
	'label'   => __('WordPress Comments tab label'),
	'std'     => __('WordPress'),
	'id'      => 'wp_comments_label',
	'type'    => 'text',
	'desc'    => __('Enter WordPress Comments Tab label')
	)
);
//image field
$p->add_field(array(
	'section' => $setting4,
	'label'   => __('WordPress Comments tab icon'),
	'id'      => 'wp_comments_img',
	'type'    => 'image',
	'desc'    => __('You can upload a custom WordPress Tab icon')
	)
);

//Disqus
$setting5 = $p->add_section(array(
	'option_group'      =>  'social_comments',
	'sanitize_callback' => null,
	'id'                => 'disqus_social_comments', 
	'title'             => __('Disqus Comments settings')
	)
);
//checkbox field
$p->add_field(array(
	'section' => $setting5,
	'label'   => __('Enable Disqus comments'),
	'std'     => false,
	'id'      => 'disqus_comments_enabled',
	'type'    => 'checkbox',
	'desc'    => __('Check to enabled Disqus Comments')
	)
);

//text field
$p->add_field(array(
	'section' => $setting5,
	'label'   => __('Disqus Shortname'),
	'std'     => '',
	'id'      => 'disqus_shortname',
	'type'    => 'text',
	'desc'    => __('Enter Disqus short name')
	)
);
//text field
$p->add_field(array(
	'section' => $setting5,
	'label'   => __('Disqus Comments tab label'),
	'std'     => __('Disqus'),
	'id'      => 'disqus_comments_label',
	'type'    => 'text',
	'desc'    => __('Enter Disqus Comments Tab label')
	)
);
//image field
$p->add_field(array(
	'section' => $setting5,
	'label'   => __('Disqus Comments tab icon'),
	'id'      => 'disqus_comments_img',
	'type'    => 'image',
	'desc'    => __('You can upload a custom Disqus Tab icon')
	)
);

//google plus
$setting2 = $p->add_section(array(
	'option_group'      =>  'social_comments',
	'sanitize_callback' => null,
	'id'                => 'google_social_comments', 
	'title'             => __('Google Plus Comments settings')
	)
);
//checkbox field
$p->add_field(array(
	'section' => $setting2,
	'label'   => __('Enable Google Plus comments'),
	'std'     => FALSE,
	'id'      => 'gplus_comments_enabled',
	'type'    => 'checkbox',
	'desc'    => __('Check to enable Google Plus Comments')
	)
);
//text field
$p->add_field(array(
	'section' => $setting2,
	'label'   => __('Google Plus tab label'),
	'std'     => __('Google + '),
	'id'      => 'gplus_comments_label',
	'type'    => 'text',
	'desc'    => __('Enter Google Plus Tab label')
	)
);
//image field
$p->add_field(array(
	'section' => $setting2,
	'label'   => __('Google Plus tab icon'),
	'id'      => 'gplus_comments_img',
	'type'    => 'image',
	'desc'    => __('You can upload a custom Google Plus Tab icon')
	)
);

//facebook commnets
$setting3 = $p->add_section(array(
	'option_group'      =>  'social_comments',
	'sanitize_callback' => null,
	'id'                => 'facebook_social_comments', 
	'title'             => __('Facebook Comments settings')
	)
);
//checkbox field
$p->add_field(array(
	'section' => $setting3,
	'label'   => __('Enable Facebook comments'),
	'std'     => FALSE,
	'id'      => 'facebook_comments_enabled',
	'type'    => 'checkbox',
	'desc'    => __('Check to enable Facebook Comments')
	)
);
//text field
$p->add_field(array(
	'section' => $setting3,
	'label'   => __('Facebook App ID'),
	'std'     => FALSE,
	'id'      => 'facebook_appID',
	'type'    => 'text',
	'desc'    => __('Enter Your Facebook App ID')
	)
);
//select field
$p->add_field(array(
	'section' => $setting3,
	'label'   => __('Facebook Color Scheme'),
	'std'     => 'light',
	'id'      => 'facebook_colorScheme',
	'type'    => 'select',
	'options'  => array(
		'Light' => 'light',
		'Dark'  => 'dark'
	),
	'desc'    => __('Select Facebook Comments color scheme')
	)
);
//text field
$p->add_field(array(
	'section' => $setting3,
	'label'   => __('Facebook label'),
	'std'     => __('Facebook'),
	'id'      => 'facebook_comments_label',
	'type'    => 'text',
	'desc'    => __('Enter Facebook Tab label')
	)
);
//image field
$p->add_field(array(
	'section' => $setting3,
	'label'   => __('Facebook tab icon'),
	'id'      => 'facebook_comments_img',
	'type'    => 'image',
	'desc'    => __('You can upload a custom Facebook Tab icon')
	)
);

//iconset
$setting6 = $p->add_section(array(
	'option_group'      =>  'social_comments',
	'sanitize_callback' => null,
	'id'                => 'icon_social_comments', 
	'title'             => __('Tabs Icon set')
	)
);
//checkboxImages field
$p->add_field(array(
	'section' => $setting6,
	'label'   => __('Select Icon Set for tabs'),
	'std'     => 'cleanlight',
	'id'      => 'iconset',
	'uri'	  => plugins_url('assets/images/icons/', dirname(__FILE__) ),
	'type'    => 'radioImage',
	'options' => array('apricum' , 'cleandark', 'cleanlight','creamycolor', 'creamysilver', 'denimdark', 'denimlight' , 'glossydark', 'glossylight', 'labels','neon', 'retro','retrobadge','somicro'),
	'desc'    => __('Select the Icon set you want to use when using tabs, as you can see not all sets have disqus icon so you can use the upload option at the disqus settings bellow')
	)
);

//custom css
$p->inject['after_wrap'] = <<<CSS
<style type="text/css">
	li[id=ordergplus]{
		padding: 10px;
  		background-color: Red;
  		cursor: move;
  		color: #fff;
  		text-align: center;
  		width: 50%;
  		font-weight: bolder;
	}
	li[id=orderdisqus]{
		padding: 10px;
  		background-color: #2e9fff;
  		cursor: move;
  		color: #fff;
  		text-align: center;
  		width: 50%;
  		font-weight: bolder;
	}
	li[id=orderwp]{ 
		padding: 10px;
  		background-color: black;
  		font-weight: bolder;
  		cursor: move;
  		text-align: center;
  		width: 50%;
  		color: #fff;
	}
	li[id=orderfacebook]{ 
		padding: 10px;
		font-weight: bolder;
  		background-color: blue;
  		cursor: move;
  		text-align: center;
  		width: 50%;
  		color: #fff;
	}
</style>
CSS
;