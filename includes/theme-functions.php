<?php

 	/*	
	*	get background 
	*	---------------------------------------------------------------------
	*/
function torch_get_background($args){
$background = "";
 if (is_array($args)) {
	if (isset($args['image']) && $args['image']!="") {
	$background =  "background:url(".esc_url( $args['image'] ). ")  ".$args['repeat']." ".$args['position']." ".$args['attachment'].";";
	}
	else
	{
	if(isset($args['color']) && $args['color'] !=""){
	$background = "background:".$args['color'].";";
	}
	}
	}
return $background;
}


	
	// get breadcrumbs
 function torch_get_breadcrumb(){
   global $post,$wp_query ;
    $postid = isset($post->ID)?$post->ID:"";
	
   $show_breadcrumb = "";
   if ( 'page' == get_option( 'show_on_front' ) && ( '' != get_option( 'page_for_posts' ) ) && $wp_query->get_queried_object_id() == get_option( 'page_for_posts' ) ) { 
    $postid = $wp_query->get_queried_object_id();
   }
  
   if(isset($postid) && is_numeric($postid)){
    $show_breadcrumb = get_post_meta( $postid, '_torch_show_breadcrumb', true );
	}
	if($show_breadcrumb == 'yes' || $show_breadcrumb==""){

  echo '<div class="container">';
  if ( is_singular() ) {
	
  echo '<div class="breadcrumb-title">'.$post->post_title.'</div>';
  }
  echo '<div class="breadcrumb-nav">'; 
               breadcrumb_trail(array("before"=>"","show_browse"=>false));
      echo '</div>    
                <div class="clearfix"></div>            
            </div>';
           
	}
	   
	}
	
	
/*
*  page navigation
*
*/
function torch_native_pagenavi($echo,$wp_query){
    if(!$wp_query){global $wp_query;}
    global $wp_rewrite;      
    $wp_query->query_vars['paged'] > 1 ? $current = $wp_query->query_vars['paged'] : $current = 1;
    $pagination = array(
    'base' => @add_query_arg('paged','%#%'),
    'format' => '',
    'total' => $wp_query->max_num_pages,
    'current' => $current,
    'prev_text' => '&laquo; ',
    'next_text' => ' &raquo;'
    );
 
    if( $wp_rewrite->using_permalinks() )
        $pagination['base'] = user_trailingslashit( trailingslashit( remove_query_arg('s',get_pagenum_link(1) ) ) . 'page/%#%/', 'paged');
 
    if( !empty($wp_query->query_vars['s']) )
        $pagination['add_args'] = array('s'=>get_query_var('s'));
    if($echo == "echo"){
    echo '<div class="page_navi">'.paginate_links($pagination).'</div>'; 
	}else
	{
	
	return '<div class="page_navi">'.paginate_links($pagination).'</div>';
	}
}
   
    //// Custom comments list
   
   function torch_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment; ?>
   
   <li  <?php comment_class("comment"); ?> id="comment-<?php comment_ID() ;?>">
                                	<article class="comment-body">
                                    	<div class="comment-avatar"><?php echo get_avatar($comment,'52','' ); ?></div>
                                        <div class="comment-box">
                                            <div class="comment-info"><?php printf(__('%s <span class="says">says:</span>','torch'), get_comment_author_link()) ;?> <a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ;?>">
<?php printf(__('%1$s at %2$s','torch'), get_comment_date(), get_comment_time()) ;?></a>  <?php edit_comment_link(__('(Edit)','torch'),'  ','') ;?></div>

 <?php if ($comment->comment_approved == '0') : ?>
         <em><?php _e('Your comment is awaiting moderation.','torch') ;?></em>
         <br />
      <?php endif; ?>
     <div class="comment-content"><?php comment_text() ;?>
      <div class="reply-quote">
             <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ;?>
			</div>
       </div>
    </div></article>

<?php
        }
		
 function torch_get_default_slider(){
	global $allowedposttags ;
	$controller   = '';
	$slideContent = '';
	
	$slide_time       = torch_options_array("slide_time");
	$slide_height     = torch_options_array("slide_height");
	$slide_height     = $slide_height==""?"":"height:".$slide_height.";";
	$slide_time       = is_numeric($slide_time)?$slide_time:"5000";
		  
	$return = '<section class="homepage-slider"><div id="carousel-torch-generic" style="'.esc_attr( $slide_height ).'" class="carousel slide" data-interval="'.absint( $slide_time ).'" data-ride="carousel">';
	 for($i=1;$i<=5;$i++){
	$active = '';
	// $title = torch_options_array('torch_slide_title_'.$i);
	 $text     = torch_options_array('torch_slide_text_'.$i);
	 $image    = torch_options_array('torch_slide_image_'.$i);
	 $link     = torch_options_array('torch_slide_link_'.$i);
	 $btn_text = torch_options_array('torch_slide_btn_text_'.$i);
	
		   
	 if($i==1) $active     = 'active';

	 
	 if(isset($image) && strlen($image)>10){
		 $controller   .= '<li data-target="#carousel-torch-generic" data-slide-to="'.($i-1).'" class="'.$active.'"><span>'.$i.'</span></li>';
			
		 $slideContent .= '<div class="item '.$active.'">';
         $slideContent .= '<img src="'.esc_url($image).'" alt="" />';
         $slideContent .= '<div class="carousel-caption">';
		 $slideContent .= '<div class="caption-text">';
		 $slideContent .= wp_kses( $text, $allowedposttags  );
		 $slideContent .= '</div>';
		 if($link != "")
		 $slideContent .= '<a href="'.esc_url($link).'"><button>'.esc_html($btn_text).'</button></a>';
		 $slideContent .= '</div>';
		 $slideContent .= '</div>';
		 }
		
	}
	     $return .= '<ol class="carousel-indicators">'. $controller .'</ol>';
		 $return .= '<div class="carousel-inner">'. $slideContent .'</div>';
		 
		 $return .= '<a class="left carousel-control" href="#carousel-torch-generic" data-slide="prev">
						<span class="fa fa-angle-left"></span>
					</a>
					<a class="right carousel-control" href="#carousel-torch-generic" data-slide="next">
						<span class="fa fa-angle-right"></span>
					</a>';
		$return .= '</div></section>';

        return $return;
   }
   

 
  function torch_enqueue_less_styles($tag, $handle) {
		global $wp_styles;
		$match_pattern = '/\.less$/U';
		if ( preg_match( $match_pattern, $wp_styles->registered[$handle]->src ) ) {
			$handle = $wp_styles->registered[$handle]->handle;
			$media = $wp_styles->registered[$handle]->args;
			$href = $wp_styles->registered[$handle]->src . '?ver=' . $wp_styles->registered[$handle]->ver;
			$rel = isset($wp_styles->registered[$handle]->extra['alt']) && $wp_styles->registered[$handle]->extra['alt'] ? 'alternate stylesheet' : 'stylesheet';
			$title = isset($wp_styles->registered[$handle]->extra['title']) ? "title='" . esc_attr( $wp_styles->registered[$handle]->extra['title'] ) . "'" : '';
	
			$tag = "<link rel='stylesheet' id='".esc_attr($handle)."' $title href='".esc_attr($href)."' type='text/less' media='".esc_attr($media)."' />";
		}
		return $tag;
	}
	add_filter( 'style_loader_tag', 'torch_enqueue_less_styles', 5, 2);
	
	
add_action( 'optionsframework_sidebar','torch_options_panel_sidebar' );

/**
 * Torch widget area generator
 */

function torch_widget_area_generator($args = array(),$echo = true){
	
	$column            = isset($_POST['column'])?$_POST['column']:1;
	$num               = isset($_POST['num'])?$_POST['num']:0;
	$areaname          = isset($_POST['areaname'])?$_POST['areaname']:0;
	$column_items      = array();
	for($i=0; $i<$column; $i++){
		$column_items[] = 12/$column; 
		}
	$defaults = array("areaname" => $areaname,
							 "color" => '',
							 "image" => '',
							 "repeat" => '',
							 "position" => '',
							 "attachment" => '',
							 "layout" => '',
							 "column" => $column,
							 "columns" => $column_items,
							 "num"     => $num,
							 "padding" => 50
							 );

	$args = wp_parse_args( $args, $defaults );
	$sanitize_areaname = sanitize_title($args['areaname']);

	       $image_show = $args['image']==''?'':'<img src="'.$args['image'].'"><a class="remove-image">'.__("Remove","torch").'</a>';
		   if($args['image']==''){
			   $button = '<input type="button" value="Upload" class="upload-button button" id="upload-list-item-image-'.$args['num'].'">';
		   }else{
			   $button = '<input type="button" value="Remove" class="remove-file  button" id="upload-list-item-image-'.$args['num'].'">';
			   }
		   
		   
	// Background Color
	            $output  = '<div class="list-item ">';
				$output .= '<div class="section-widget-area-name"><span class="widget-area-name">'.$args['areaname'].'</span><span><a href="javascript:;" class="edit-section">'.__("Edit","torch").'</a> | <a href="javascript:;" data-href="javascript:;" data-toggle="confirmation" class="remove-section ">'.__("Remove","torch").'</a></span></div>';
				$output .= '<input type="hidden" name="widget-area[section-widget-area-name][]" class="section-widget-area-name-item" id="section-widget-area-name-'.$args['num'].'" value="'.$args['areaname'].'" />';
				$output .= '<input type="hidden" class="section-widget-sanitize-areaname" value="'.$sanitize_areaname.'" />';
				
				$output .= '<div class="section-widget-area-wrapper">';
				$output .= '<div class="section section-color section-widget-area-background" id="section-widget-area-background-'.$args['num'].'"><label>'. __("Background","torch").':</label>
  <div class="wp-picker-container"><span class="wp-picker-input-wrap">
    <input type="text" value="'.$args['color'].'" class="of-color of-background-color wp-color-picker" id="list-item-color-'.$args['num'].'"  name="widget-area[list-item-color][]" style="display: none;">
    <input type="button" class="button button-small hidden wp-picker-clear" value="Clear">
    </span>
    <div class="wp-picker-holder">
      <div class="iris-picker iris-mozilla iris-border" style="display: none; width: 255px; height: 202.125px; padding-bottom: 23.2209px;">
        <div class="iris-picker-inner">
          <div class="iris-square" style="width: 182.125px; height: 182.125px;"><a href="#" class="iris-square-value ui-draggable" style="left: 0px; top: 182.133px;"><span class="iris-square-handle ui-slider-handle"></span></a>
            <div class="iris-square-inner iris-square-horiz" style="background-image: -moz-linear-gradient(left center , rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255), rgb(255, 255, 255));"></div>
            <div class="iris-square-inner iris-square-vert" style="background-image: -moz-linear-gradient(center top , transparent, rgb(0, 0, 0));"></div>
          </div>
          <div class="iris-slider iris-strip" style="width: 28.2px; height: 205.346px; background-image: -moz-linear-gradient(center top , rgb(0, 0, 0), rgb(0, 0, 0));">
            <div class="iris-slider-offset ui-slider ui-slider-vertical ui-widget ui-widget-content ui-corner-all" aria-disabled="false"><a href="#" class="ui-slider-handle ui-state-default ui-corner-all" style="bottom: 0%;"></a></div>
          </div>
        </div>
        <div class="iris-palette-container"><a tabindex="0" class="iris-palette" style="background-color: rgb(0, 0, 0); width: 19.5784px; height: 19.5784px; margin-left: 0px;"></a><a tabindex="0" class="iris-palette" style="background-color: rgb(255, 255, 255); width: 19.5784px; height: 19.5784px; margin-left: 3.6425px;"></a><a tabindex="0" class="iris-palette" style="background-color: rgb(221, 51, 51); width: 19.5784px; height: 19.5784px; margin-left: 3.6425px;"></a><a tabindex="0" class="iris-palette" style="background-color: rgb(221, 153, 51); width: 19.5784px; height: 19.5784px; margin-left: 3.6425px;"></a><a tabindex="0" class="iris-palette" style="background-color: rgb(238, 238, 34); width: 19.5784px; height: 19.5784px; margin-left: 3.6425px;"></a><a tabindex="0" class="iris-palette" style="background-color: rgb(129, 215, 66); width: 19.5784px; height: 19.5784px; margin-left: 3.6425px;"></a><a tabindex="0" class="iris-palette" style="background-color: rgb(30, 115, 190); width: 19.5784px; height: 19.5784px; margin-left: 3.6425px;"></a><a tabindex="0" class="iris-palette" style="background-color: rgb(130, 36, 227); width: 19.5784px; height: 19.5784px; margin-left: 3.6425px;"></a></div>
      </div>
    </div>
  </div>
  <input type="text" placeholder="'. __("No file chosen","torch").'" value="'.$args['image'].'" name="widget-area[list-item-image][]" class="upload" id="list-item-image-'.$args['num'].'">
  '.$button.'
  <div id="list-item-image-'.$args['num'].'-image" class="screenshot">'.$image_show.'</div>
  <div class="of-background-properties">
    <select id="list-item-repeat-'.$args['num'].'" name="widget-area[list-item-repeat][]" class="of-background of-background-repeat">
      <option '.($args['repeat'] == 'no-repeat'?'selected="selected"':'').' value="no-repeat">No Repeat</option>
      <option '.($args['repeat'] == 'repeat-x'?'selected="selected"':'').' value="repeat-x">Repeat Horizontally</option>
      <option '.($args['repeat'] == 'repeat-y'?'selected="selected"':'').' value="repeat-y">Repeat Vertically</option>
      <option '.($args['repeat'] == 'repeat'?'selected="selected"':'').' value="repeat">Repeat All</option>
    </select>
    <select id="list-item-position-'.$args['num'].'" name="widget-area[list-item-position][]" class="of-background of-background-position">
      <option '.($args['position'] == 'top left'?'selected="selected"':'').' value="top left">Top Left</option>
      <option '.($args['position'] == 'top center'?'selected="selected"':'').' value="top center">Top Center</option>
      <option '.($args['position'] == 'top right'?'selected="selected"':'').' value="top right">Top Right</option>
      <option '.($args['position'] == 'center left'?'selected="selected"':'').' value="center left">Middle Left</option>
      <option '.($args['position'] == 'center center'?'selected="selected"':'').' value="center center">Middle Center</option>
      <option '.($args['position'] == 'center right'?'selected="selected"':'').' value="center right">Middle Right</option>
      <option '.($args['position'] == 'bottom left'?'selected="selected"':'').' value="bottom left">Bottom Left</option>
      <option '.($args['position'] == 'bottom center'?'selected="selected"':'').' value="bottom center">Bottom Center</option>
      <option '.($args['position'] == 'bottom right'?'selected="selected"':'').' value="bottom right">Bottom Right</option>
    </select>
    <select id="list-item-attachment-'.$args['num'].'" name="widget-area[list-item-attachment][]" class="of-background of-background-attachment">
      <option  '.($args['attachment'] == 'scroll'?'selected="selected"':'').'value="scroll">Scroll Normally</option>
      <option '.($args['attachment'] == 'fixed'?'selected="selected"':'').' value="fixed">Fixed in Place</option>
    </select>
  </div>
</div>';

				
				/////widget secton layout
		$output .= '<div id="section-widget-area-layout-'.$args['num'].'" class="section section-layout">';
		$output .= '<label> '.__("Layout","torch").' :</label><select name="widget-area[widget-area-layout][]" id="widget-area-layout-'.$args['num'].'">
			    	<option '.($args['layout'] == 'boxed'?'selected="selected"':'').' value="boxed">'.__("boxed","torch").'</option>
				    <option '.($args['layout'] == 'full'?'selected="selected"':'').' value="full">'.__("full width","torch").'</option></select>';
				
		$output .= '</div>';
		
		$output .= '<div id="section-widget-area-padding-'.$args['num'].'" class="section section-padding">';
		$output .= '<label> '.__("Padding top & bottom","torch").' :</label>';
		$output .= '<input style=" width:50%;" type="text" value="'.$args['padding'].'" name="widget-area[widget-area-padding][]" id="widget-area-padding-'.$args['num'].'"> px';
		$output .= '</div>';
		
				/////widget secton column
		$output .= '<div id="section-widget-area-column-'.$args['num'].'" class="section section-column">';
		$output .= '<label> '.__("Column","torch").' :</label><select class="widget-area-column" name="widget-area[widget-area-column][]" id="widget-area-column-'.$args['num'].'">
			        <option value="1">'.__("choose column","torch").'</option>';
					for($j=1;$j<=4;$j++){
						$selected   = "";
						$column_n   = __("columns","torch");
						if($j == $args['column']){$selected = " selected='selected' ";}
						if($j == 1){$column_n = __("column","torch");}
						
			    	    $output .= '<option value="'.$j.'" '.$selected.'>'.$j.' '.$column_n.'</option>';
				   
					}
					
		$output .= '</select>';
				/////widget secton column items
		$output .= '<div class="widget-secton-column">';
				if(count($args['columns']) > 1){
					$j = 1 ;
					foreach($args['columns'] as $c){
						
			        $output .= '<label> '.sprintf(__("Column %s width","torch"),$j).' :</label><select class="widget-area-column-item" name="widget-area[widget-area-column-item]['.$sanitize_areaname.'][]" id="widget-area-column-item-'.$j.'">';
			        
					for($k=1;$k<=12;$k++){
					$selected   = "";
					if($c == $k){$selected = ' selected="selected" ';}
			    	$output .= '<option value="'.$k.'" '.$selected.'>'.$k.'/12</option>';
				   
					}
					
		$output .= '</select>';
		$j++;
					  }
					}
		$output .= '</div>';
				/////
		$output .= '</div>';
				//				
		$output .= '</div>';
		$output .= '</div>';
				if($echo == true){
				    echo $output ;
				    exit(0);
				}else{
					return $output ;
					}
	
	}
    add_action('wp_ajax_torch_widget_area_generator', 'torch_widget_area_generator');
	add_action('wp_ajax_nopriv_torch_widget_area_generator', 'torch_widget_area_generator');

/**
 * torch admin sidebar
 */
function torch_options_panel_sidebar() { ?>
	<div id="optionsframework-sidebar">
		<div class="metabox-holder">
	    	<div class="postbox">
	    		<h3><?php esc_attr_e( 'Quick Links', 'torch' ); ?></h3>
      			<div class="inside"> 
		          <ul>
                   <li><a href="<?php echo esc_url( 'http://www.mageewp.com/torch-theme.html' ); ?>" target="_blank"><?php _e('Upgrade to Pro','torch');?></a></li>
                  <li><a href="<?php echo esc_url( 'http://www.mageewp.com/themes/' ); ?>" target="_blank"><?php _e('MageeWP Themes','torch');?></a></li>
                  <li><a href="<?php echo esc_url( 'http://www.mageewp.com/documents/tutorials' ); ?>" target="_blank"><?php _e('Tutorials','torch');?></a></li>
                  <li><a href="<?php echo esc_url( 'http://www.mageewp.com/documents/faq/' ); ?>" target="_blank"><?php _e('FAQ','torch');?></a></li>
                  <li><a href="<?php echo esc_url( 'http://www.mageewp.com/knowledges/' ); ?>" target="_blank"><?php _e('Knowledge','torch');?></a></li>
                  <li><a href="<?php echo esc_url( 'http://www.mageewp.com/forums/torch-theme/' ); ?>" target="_blank"><?php _e('Support Forums','torch');?></a></li>
                  </ul>
      			</div>
	    	</div>
	  	</div>
	</div>
    <div class="clear"></div>
<?php
} 
 
 function torch_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 )
		$title = "$title $sep " . sprintf( __( ' Page %s ', 'torch' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'torch_wp_title', 10, 2 );

if ( ! function_exists( '_wp_render_title_tag' ) ) {
	function torch_slug_render_title() {
?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php
	}
	add_action( 'wp_head', 'torch_slug_render_title' );
}



  function torch_title( $title ) {
  if ( $title == '' ) {
  return 'Untitled';
  } else {
  return $title;
  }
  }
  add_filter( 'the_title', 'torch_title' );


	function torch_favicon()
	{
	    $url =  torch_options_array('favicon');
	
		$icon_link = "";
		if($url)
		{
			$type = "image/x-icon";
			if(strpos($url,'.png' )) $type = "image/png";
			if(strpos($url,'.gif' )) $type = "image/gif";
		
			$icon_link = '<link rel="icon" href="'.esc_url($url).'" type="'.$type.'">';
		}
		
		echo $icon_link;
	}
	add_action( 'wp_head', 'torch_favicon' );
