<?php
/*
Plugin Name: WP-Widget Gallery
Plugin URI: http://scoopdesign.com.au
Description: This WordPress plugin allows user to create a gallery for widgets. This plugin also has the ability to display it on page of your choice. 
Version: 1.3
Author: eyouth { rob.panes } | scoopdesign.com.au
Author URI: http://scoodpesign.com.au

Copyright 2013  Rob Panes | scoopdesign.com.au  (email : robpane126@gmail.com)

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


class wpwidget_media_gallery extends WP_Widget { 
	
	// Widget Settings
	function wpwidget_media_gallery() {
            //echo $this->widget($args, $instance);
            $widget_ops = array('description' => __('Creates gallery on your sidebar and has ability to display on page of your choice.') );
            $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wpwidget_media_gallery' );
            $this->WP_Widget( 'wpwidget_media_gallery', __('WP Widget Gallery'), $widget_ops, $control_ops );                                                
	}
        
	// Widget Output
	function widget($args, $instance) {
		extract($args);    
                global $post;
		$title = apply_filters('widget_title', $instance['title']);               
                $wpwidgetpage = $instance['wpwidgetpage'];
                $wpwidgetsize = $instance['wpwidgetsize'];
                $images = explode(',',$instance['wpwidget_thumbnail_image']);
                $showtitle = !empty($instance['wpwidget_showtitle'])?true:false;
                $showdesc = !empty($instance['wpwidget_showdesc'])?true:false;
                $page_object = get_queried_object();
                $wtheID     = get_queried_object_id();
                $styleadditions = "margin:0 auto 3px;";
                if ($instance['wpwidgetsize'] == "small-thumb"){
                        $smallThumb = array(50, 50);
                        $wpwidgetsize = $smallThumb;
                        // Width and Height added to style as IE was ignoring the default width and height set.
                        //$styleadditions = "float: left; display: block;margin:3px; width: 50px; height: 50px;";
                }
                                
                if( !empty($wpwidgetpage)){                    
                if ( in_array($wtheID, $wpwidgetpage) ):
                    // ------                 
                    echo $before_widget;
                    echo $before_title . $title . $after_title;   
                    if (is_array($images)):
                    echo '<ul id="widget-media-container">';                       
                    foreach( $images as $image){ 
                            $attachment = get_post( $image );
                            $obj = array(
                                    'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                                    'caption' => $attachment->post_excerpt,
                                    'description' => $attachment->post_content,
                                    'href' => get_permalink( $attachment->ID ),
                                    'src' => $attachment->guid,
                                    'title' => $attachment->post_title
                            );
                            $url   = wp_get_attachment_image($image, $wpwidgetsize, false);
                            $src   = wp_get_attachment_image_src( $image, 'full' );
                            if ( $url ):
                                $out   = '<li class="item"><a href="'.$src[0].'" data-lightbox="'.$obj['title'].'" title="'.$obj['title'].'">'.$url.'</a>';
                                if ( $showtitle )
                                $out  .= '<p style="text-align:center;text-transform:uppercase;font-size:.9em;">'.$obj['title'].'</p>';                            
                                if ( $showdesc )
                                $out  .= '<p style="text-align:center;font-size:.9em;">'.$obj['description'].'</p>';                                          
                                $out  .= '</li>';              
                                echo $out;
                            endif;
                    }
                    echo '</ul>';
                    endif;
                    echo $after_widget;		                                	                                
		// ------
                endif;    
           }else{
               // ------                 
                    echo $before_widget;
                    echo $before_title . $title . $after_title;   
                    if (is_array($images)):
                    echo '<ul id="widget-media-container">';                       
                    foreach( $images as $image){ 
                            $attachment = get_post( $image );
                            $obj = array(
                                    'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
                                    'caption' => $attachment->post_excerpt,
                                    'description' => $attachment->post_content,
                                    'href' => get_permalink( $attachment->ID ),
                                    'src' => $attachment->guid,
                                    'title' => $attachment->post_title
                            );
                            $url   = wp_get_attachment_image($image, $wpwidgetsize, false);
                            $src   = wp_get_attachment_image_src( $image, 'full' );
                            if ( $url ):
                                $out   = '<li class="item"><a href="'.$src[0].'" data-lightbox="'.$obj['title'].'" title="'.$obj['title'].'">'.$url.'</a>';
                                if ( $showtitle )
                                $out  .= '<p style="text-align:center;text-transform:uppercase;font-size:.9em;">'.$obj['title'].'</p>';                            
                                if ( $showdesc )
                                $out  .= '<p style="text-align:center;font-size:.9em;">'.$obj['description'].'</p>';                                          
                                $out  .= '</li>';              
                                echo $out;
                            endif;
                    }
                    echo '</ul>';
                    endif;
                    echo $after_widget;		                                
		// ------
           }    
	}

// Update
	function update( $new_instance, $old_instance ) {  
		$instance = $old_instance; 
                $instance['title'] = strip_tags( $new_instance['title'] );
                $instance['wpwidgetpage'] = $new_instance['wpwidgetpage'];
                $instance['wpwidgetsize'] = strip_tags($new_instance['wpwidgetsize']);
                $instance['wpwidget_showtitle'] = isset($new_instance['wpwidget_showtitle']);
                $instance['wpwidget_showdesc'] = isset($new_instance['wpwidget_showdesc']);
                $instance['wpwidget_thumbnail_image'] = $new_instance['wpwidget_thumbnail_image'];
                
		return $instance;
	}
	
	// Backend Form
	function form($instance) {
		
		$defaults = array( 'title' => 'Widget Image' ); // Default Values
		$instance = wp_parse_args( (array) $instance, $defaults ); 
                
        ?>        
                <p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>
                <p>
                <label for="<?php echo $this->get_field_id( 'wpwidgetpage' ); ?>">Show in Pages:<br /></label>			
                <?php  
                    $args = array(
                            'sort_order' => 'ASC',
                            'sort_column' => 'post_title',
                            'hierarchical' => 1,
                            'exclude' => '',
                            'include' => '',
                            'meta_key' => '',
                            'meta_value' => '',
                            'authors' => '',
                            'child_of' => 0,
                            'parent' => -1,
                            'exclude_tree' => '',
                            'number' => '',
                            'offset' => 0,
                            'post_type' => 'page',
                            'post_status' => 'publish'
                    ); 
                    $pages = get_pages($args); 
                    ?>

                    <select multiple='multiple' name="<?php echo $this->get_field_name('wpwidgetpage'); ?>[]" style='width:100%;'>
                    <?php

                    foreach ($pages as $page):
                            if (is_array($instance['wpwidgetpage']) ):
                    ?>            
                                <option value="<?php echo $page->ID ?>" <?php selected(in_array($page->ID, $instance["wpwidgetpage"]))?>><?php echo trim($page->post_title)?></options>
                    <?php   else: ?>
                                <option value="<?php echo $page->ID ?>"><?php echo trim($page->post_title) ?></options>
                    <?php   endif; 

                    endforeach;
                    ?>
                    </select>   
                    <small>Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.</small>                            
        </p>        
        <p>
                    <label for="<?php echo $this->get_field_id( 'wpwidgetsize' ); ?>">Image Size:<br /></label>
                    <select name="<?php echo $this->get_field_name('wpwidgetsize'); ?>" style='width:100%;'> 
                        <?php $selected = 'selected=selected'; echo $instance['wpwidgetsize']; ?>
                        <option value="small-thumb" <?php if (trim($instance['wpwidgetsize']) == 'small-thumb')echo $selected; else ''; ?>>Small Thumb</options>
                        <option value="thumbnail" <?php if (trim($instance['wpwidgetsize']) == 'thumbnail')echo $selected; else ''; ?>>Thumbnail</options>
                         <option value="medium" <?php if (trim($instance['wpwidgetsize']) == 'medium')echo $selected; else ''; ?>>Medium</options>
                         <option value="full" <?php if (trim($instance['wpwidgetsize']) == 'full')echo $selected; else ''; ?>>Full</options>    
                    </select>
        </p>
        <p>                
		<input type="checkbox" class="" id="<?php echo $this->get_field_id( 'wpwidget_showtitle' ); ?>" name="<?php echo $this->get_field_name( 'wpwidget_showtitle' ); ?>" <?php checked(isset($instance['wpwidget_showtitle']) ? $instance['wpwidget_showtitle'] : 0); ?>/>
                <label for="<?php echo $this->get_field_id( 'wpwidget_showtitle' ); ?>">Display image title:</label>
        </p>
        <p>                
		<input type="checkbox" class="" id="<?php echo $this->get_field_id( 'wpwidget_showdesc' ); ?>" name="<?php echo $this->get_field_name( 'wpwidget_showdesc' ); ?>" <?php checked(isset($instance['wpwidget_showdesc']) ? $instance['wpwidget_showdesc'] : 0); ?> />
                <label for="<?php echo $this->get_field_id( 'wpwidget_showdesc' ); ?>">Display image description:</label>
        </p>
        <p class="wp-widget-gal">
                <input type="button" value="<?php _e( 'Upload Image', 'wpwidget_media_gallery' ); ?>" class="button wpwidget_media_upload" id="wpwidget_media_upload"/>                  	
                <input type="hidden" value="<?php echo $instance['wpwidget_thumbnail_image'] ?>" name="<?php echo $this->get_field_name('wpwidget_thumbnail_image'); ?>" class="wpwidget_arr" id="<?php echo $this->get_field_id( 'wpwidget_thumbnail_image' ); ?>">
                <?php if (!empty($instance['wpwidget_thumbnail_image'])){ ?>
        	<ul class="wpwidgetgallery">
                    <?php       
                        /*
                        if (is_admin() && !isset($_COOKIE['image_array'])) {                                                    
                            unset($_COOKIE['image_array']);
                            setcookie('image_array', '', time() - 3600);
                            setcookie("image_array", $instance['wpwidget_thumbnail_image']);
                        }else{
                            unset($_COOKIE['image_array']);
                            setcookie('image_array', '', time() - 3600);
                            setcookie("image_array", $instance['wpwidget_thumbnail_image']);
                        } 
                         * 
                         */       
                        $images = explode(',',$instance['wpwidget_thumbnail_image']);
                        $cnt = 0;
                        
                        foreach( $images as $image):
                            $url = wp_get_attachment_image($image, array(80,80),false, array('data-attachment_id' => $image ));
                            if ($url):
                            $out  = '<li style="display:inline-block;padding:5px;">'.$url;
                            $out .= '<div class="wpwidgetoverlay"><a href="#" data-attachment_id ="'.$image.'" id="'.$cnt.'" class="wpwidget_rem_img">remove</a> | <a href="post.php?post='.$image.'&action=edit" id="'. $image .'" class="wpwidget_edit_img" >edit</a></div>';
                            $out .= '</li>';        
                            $cnt++;        
                            echo $out;
                            endif;
                        endforeach;     
                    ?>
                </ul> 
                <?php } ?>
        </p>
                
    <?php }        
        
}        

// Add Widget
function wpwidget_media_gallery_init() {
	register_widget('wpwidget_media_gallery');	
}
add_action('widgets_init', 'wpwidget_media_gallery_init');

if( is_active_widget(  false, false, 'wpwidget_media_gallery', true )) {
//Media upload
add_action('init', 'widget_media_gallery_upload');
function widget_media_gallery_upload(){         
    if(function_exists( 'wp_enqueue_media' )){ 	
            wp_register_script( 'wpwidget-mediaupload', plugins_url() . '/wp-widget-gallery/js/mediaupload.js', array('jquery') );
            wp_enqueue_script ( 'wpwidget-mediaupload' );
            wp_register_script( 'wpwidget-masonry', plugins_url() . '/wp-widget-gallery/js/jquery.masonry.min.js', array('jquery') );
            wp_enqueue_script ( 'wpwidget-masonry' );            
            wp_register_script( 'wpwidget-modernizer', plugins_url() . '/wp-widget-gallery/js/modernizr-2.5.3.min.js', array('jquery') );
            wp_enqueue_script ( 'wpwidget-modernizer' );  
            wp_register_script( 'wpwidget-lightbox', plugins_url() . '/wp-widget-gallery/js/lightbox-2.6.min.js', array('jquery') );
            wp_enqueue_script ( 'wpwidget-lightbox' );  
            
            wp_enqueue_media();
    }else{
            wp_enqueue_style('thickbox');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
            wp_enqueue_script( 'wpwidget-mediaupload', plugins_url() . '/wp-widget-gallery/js/mediaupload.js', array('jquery') );
            wp_enqueue_script( 'wpwidget-masonry', plugins_url() . '/wp-widget-gallery/js/jquery.masonry.min.js', array('jquery') );         
            wp_enqueue_script( 'wpwidget-modernizer', plugins_url() . '/wp-widget-gallery/js/modernizr-2.5.3.min.js', array('jquery') );
            }	          
}

function widget_media_css(){
?>
        <style type="text/css">
            #widget-media-container {
                clear:both;      
                display:inline-table;
            }
            .widget ul li.item, div.item {
                display: block;
                float: left;
                width: auto;
                margin: 3px;
                list-style: none;
                -webkit-transition: left .4s ease-in-out, top .4s ease-in-out .4s;
                -moz-transition: left .4s ease-in-out, top .4s ease-in-out .4s;
                -ms-transition: left .4s ease-in-out, top .4s ease-in-out .4s;
                -o-transition: left .4s ease-in-out, top .4s ease-in-out .4s;
                transition: left .4s ease-in-out, top .4s ease-in-out .4s;
            }

            .widget ul li.item img {
                margin:0 auto;
                display:block;
                position:relative
            }
        </style>
<?php   
}
add_action('wp_head','widget_media_css');

function wpwidget_lightbox(){
    wp_register_style('wpwidget-lightbox', plugins_url('wp-widget-gallery/css/lightbox.css',__DIR__));
    wp_enqueue_style('wpwidget-lightbox');
}
add_action('wp_enqueue_scripts','wpwidget_lightbox');
}
?>        