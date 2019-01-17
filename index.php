<?php 
/*
 Plugin Name: VW Lawyer pro Post Types
 Plugin URI: https://www.vwthemes.com/
 Description: Creating new post type for VW Lawyer pro Pro Theme
 Author: VW Themes
 Version: 1.0
 Author URI: https://www.vwthemes.com/
*/

define( 'VW_LAWYER_PRO_POSTTYPE_VERSION', '1.0' );

add_action( 'init', 'vw_lawyer_pro_posttype_create_post_type' );

function vw_lawyer_pro_posttype_create_post_type() {
	register_post_type( 'services',
    array(
        'labels' => array(
            'name' => __( 'Services','vw-lawyer-pro-posttype' ),
            'singular_name' => __( 'Services','vw-lawyer-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-tag',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
	);
  register_post_type( 'attorney',
    array(
        'labels' => array(
            'name' => __( 'Attorney','vw-lawyer-pro-posttype' ),
            'singular_name' => __( 'Attorney','vw-lawyer-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );
  register_post_type( 'testimonials',
	array(
		'labels' => array(
			'name' => __( 'Testimonials','vw-lawyer-pro-posttype-pro' ),
			'singular_name' => __( 'Testimonials','vw-lawyer-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-businessman',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
  register_post_type( 'faq',
	array(
		'labels' => array(
			'name' => __( 'Faq','vw-lawyer-pro-posttype-pro' ),
			'singular_name' => __( 'Faq','vw-lawyer-pro-posttype-pro' )
			),
		'capability_type' => 'post',
		'menu_icon'  => 'dashicons-media-spreadsheet',
		'public' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail'
			)
		)
	);
}
// Serives section
function vw_lawyer_pro_posttype_images_metabox_enqueue($hook) {
	if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
		wp_enqueue_script('vw_lawyer-pro-posttype-images-metabox', plugin_dir_url( __FILE__ ) . '/js/img-metabox.js', array('jquery', 'jquery-ui-sortable'));

		global $post;
		if ( $post ) {
			wp_enqueue_media( array(
					'post' => $post->ID,
				)
			);
		}

	}
}
add_action('admin_enqueue_scripts', 'vw_lawyer_pro_posttype_images_metabox_enqueue');
// Services Meta
function vw_lawyer_pro_posttype_bn_custom_meta_services() {

    add_meta_box( 'bn_meta', __( 'Services Meta', 'vw-lawyer-pro-posttype' ), 'vw_lawyer_pro_posttype_bn_meta_callback_services', 'services', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
	add_action('admin_menu', 'vw_lawyer_pro_posttype_bn_custom_meta_services');
}

function vw_lawyer_pro_posttype_bn_meta_callback_services( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
	<div id="property_stuff">
		<table id="list-table">			
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<p>
						<label for="meta-image"><?php echo esc_html('Icon Image'); ?></label><br>
						<input type="text" name="meta-image" id="meta-image" class="meta-image regular-text" value="<?php echo $bn_stored_meta['meta-image'][0]; ?>">
						<input type="button" class="button image-upload" value="Browse">
					</p>
					<div class="image-preview"><img src="<?php echo $bn_stored_meta['meta-image'][0]; ?>" style="max-width: 250px;"></div>
				</tr>
        <tr id="meta-2">
          <p>
            <label for="meta-url"><?php echo esc_html('Want to link with custom URL'); ?></label><br>
            <input type="url" name="meta-url" id="meta-url" class="meta-url regular-text" value="<?php echo $bn_stored_meta['meta-url'][0]; ?>">
          </p>
        </tr>
			</tbody>
		</table>
	</div>
	<?php
}

function vw_lawyer_pro_posttype_bn_meta_save_services( $post_id ) {

	if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	// Save Image
	if( isset( $_POST[ 'meta-image' ] ) ) {
	    update_post_meta( $post_id, 'meta-image', esc_url_raw($_POST[ 'meta-image' ]) );
	}
  if( isset( $_POST[ 'meta-url' ] ) ) {
      update_post_meta( $post_id, 'meta-url', esc_url_raw($_POST[ 'meta-url' ]) );
  }
}
add_action( 'save_post', 'vw_lawyer_pro_posttype_bn_meta_save_services' );

/* Attorney */
function vw_lawyer_pro_posttype_bn_designation_meta() {
    add_meta_box( 'vw_lawyer_pro_posttype_bn_meta', __( 'Enter Designation','vw-lawyer-pro-posttype' ), 'vw_lawyer_pro_posttype_bn_meta_callback', 'attorney', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_lawyer_pro_posttype_bn_designation_meta');
}
/* Adds a meta box for custom post */
function vw_lawyer_pro_posttype_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'vw_lawyer_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    ?>
    <div id="attorney_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                    <td class="left">
                        <?php esc_html_e( 'Email', 'vw-lawyer-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-desig" id="meta-desig" value="<?php echo esc_html($bn_stored_meta['meta-desig'][0]); ?>" />
                    </td>
                </tr>
                <tr id="meta-2">
                    <td class="left">
                        <?php esc_html_e( 'Phone Number', 'vw-lawyer-pro-posttype' )?>
                    </td>
                    <td class="left" >
                        <input type="text" name="meta-call" id="meta-call" value="<?php echo esc_html($bn_stored_meta['meta-call'][0]); ?>" />
                    </td>
                </tr>
                <tr id="meta-3">
                  <td class="left">
                    <?php esc_html_e( 'Facebook Url', 'vw-lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-facebookurl" id="meta-facebookurl" value="<?php echo esc_url($bn_stored_meta['meta-facebookurl'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-4">
                  <td class="left">
                    <?php esc_html_e( 'Linkedin URL', 'vw-lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-linkdenurl" id="meta-linkdenurl" value="<?php echo esc_url($bn_stored_meta['meta-linkdenurl'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php esc_html_e( 'Twitter Url', 'vw-lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-twitterurl" id="meta-twitterurl" value="<?php echo esc_url( $bn_stored_meta['meta-twitterurl'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php esc_html_e( 'GooglePlus URL', 'vw-lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-googleplusurl" id="meta-googleplusurl" value="<?php echo esc_url($bn_stored_meta['meta-googleplusurl'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-7">
                  <td class="left">
                    <?php esc_html_e( 'Designation', 'vw-lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_html($bn_stored_meta['meta-designation'][0]); ?>" />
                  </td>
                </tr>
                <tr id="meta-8">
                  <td class="left">
                    <?php esc_html_e( 'Want to link with custom URL', 'vw-lawyer-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-attorney-url" id="meta-attorney-url" class="meta-attorney-url regular-text" value="<?php echo $bn_stored_meta['meta-attorney-url'][0]; ?>">
                  </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom fields meta input */
function vw_lawyer_pro_posttype_bn_metadesig_attorney_save( $post_id ) {
    if( isset( $_POST[ 'meta-desig' ] ) ) {
        update_post_meta( $post_id, 'meta-desig', sanitize_text_field($_POST[ 'meta-desig' ]) );
    }
    if( isset( $_POST[ 'meta-call' ] ) ) {
        update_post_meta( $post_id, 'meta-call', sanitize_text_field($_POST[ 'meta-call' ]) );
    }
    // Save facebookurl
    if( isset( $_POST[ 'meta-facebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-facebookurl', esc_url_raw($_POST[ 'meta-facebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-linkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-linkdenurl', esc_url_raw($_POST[ 'meta-linkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-twitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-twitterurl', esc_url_raw($_POST[ 'meta-twitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-googleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-googleplusurl', esc_url_raw($_POST[ 'meta-googleplusurl' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', sanitize_text_field($_POST[ 'meta-designation' ]) );
    }
    if( isset( $_POST[ 'meta-attorney-url' ] ) ) {
        update_post_meta( $post_id, 'meta-attorney-url', esc_url_raw($_POST[ 'meta-attorney-url' ]) );
    }
}
add_action( 'save_post', 'vw_lawyer_pro_posttype_bn_metadesig_attorney_save' );

/* Attorney shorthcode */
function vw_lawyer_pro_posttype_attorney_func( $atts ) {
    $attorney = ''; 
    $custom_url ='';
    $attorney = '<div class="row">';
    $query = new WP_Query( array( 'post_type' => 'attorney' ) );
    if ( $query->have_posts() ) :
    $k=1;
    $new = new WP_Query('post_type=attorney'); 
    while ($new->have_posts()) : $new->the_post();
    	$post_id = get_the_ID();
    	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
      if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
		  $url = $thumb['0'];
      $excerpt = vw_lawyer_pro_string_limit_words(get_the_excerpt(),20);
      $designation= get_post_meta($post_id,'meta-designation',true);
      $call= get_post_meta($post_id,'meta-call',true);
      $facebookurl= get_post_meta($post_id,'meta-facebookurl',true);
      $linkedin=get_post_meta($post_id,'meta-linkdenurl',true);
      $twitter=get_post_meta($post_id,'meta-twitterurl',true);
      $googleplus=get_post_meta($post_id,'meta-googleplusurl',true);
      if(get_post_meta($post_id,'meta-attorney-url',true !='')){$custom_url =get_post_meta($post_id,'meta-attorney-url',true); } else{ $custom_url = get_permalink(); }
      $attorney .= '
                <div class="attorneys_box col-lg-4 col-md-6 col-sm-6">
                  <?php if (has_post_thumbnail()){ ?>
                    <div class="image-box ">
                      <img class="client-img" src="'.esc_url($thumb_url).'" alt="attorney-thumbnail" />
                      <div class="attorneys-box w-100 float-left">
                        <h4 class="attorney_name"><a href="'.esc_url($custom_url).'">'.get_the_title().'</a></h4>
                        <p>'.esc_html($designation).'</p>
                      </div>
                    </div>
                  <?php } ?>
                  <div class="content_box w-100 float-left">
                    <div class="short_text pt-3">'.$excerpt.'</div>
                    <div class="about-socialbox pt-3">
                      <p>'.$call.'</p>
                      <div class="att_socialbox">';
                        if($facebookurl != ''){
                          $attorney .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                        } if($twitter != ''){
                          $attorney .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                        } if($googleplus != ''){
                          $attorney .= '<a class="" href="'.esc_url($googleplus).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                        } if($linkedin != ''){
                          $attorney .= '<a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                        }
                      $attorney .= '</div>
                    </div>
                  </div>
                </div>';
      if($k%2 == 0){
          $attorney.= '<div class="clearfix"></div>'; 
      } 
      $k++;         
  endwhile; 
  wp_reset_postdata();
  $attorney.= '</div>';
  else :
    $attorney = '<h2 class="center">'.esc_html_e('Not Found','vw-lawyer-pro-posttype').'</h2>';
  endif;
  return $attorney;
}
add_shortcode( 'attorney', 'vw_lawyer_pro_posttype_attorney_func' );

/* Testimonial section */
/* Adds a meta box to the Testimonial editing screen */
function vw_lawyer_pro_posttype_bn_testimonial_meta_box() {
	add_meta_box( 'vw-lawyer-pro-posttype-pro-testimonial-meta', __( 'Enter Designation', 'vw-lawyer-pro-posttype-pro' ), 'vw_lawyer_pro_posttype_bn_testimonial_meta_callback', 'testimonials', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_lawyer_pro_posttype_bn_testimonial_meta_box');
}

/* Adds a meta box for custom post */
function vw_lawyer_pro_posttype_bn_testimonial_meta_callback( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'vw_lawyer_pro_posttype_posttype_testimonial_meta_nonce' );
  $bn_stored_meta = get_post_meta( $post->ID );
	$desigstory = get_post_meta( $post->ID, 'vw_lawyer_pro_posttype_posttype_testimonial_desigstory', true );
	?>
	<div id="testimonials_custom_stuff">
		<table id="list">
			<tbody id="the-list" data-wp-lists="list:meta">
				<tr id="meta-1">
					<td class="left">
						<?php esc_html_e( 'Designation', 'vw-lawyer-pro-posttype-pro' )?>
					</td>
					<td class="left" >
						<input type="text" name="vw_lawyer_pro_posttype_posttype_testimonial_desigstory" id="vw_lawyer_pro_posttype_posttype_testimonial_desigstory" value="<?php echo esc_attr( $desigstory ); ?>" />
					</td>
				</tr>
        <tr id="meta-2">
          <td class="left">
            <?php esc_html_e( 'Want to link with custom URL', 'vw-lawyer-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="url" name="meta-testimonial-url" id="meta-testimonial-url" class="meta-testimonial-url regular-text" value="<?php echo $bn_stored_meta['meta-testimonial-url'][0]; ?>">
          </td>
        </tr>
			</tbody>
		</table>
	</div>
	<?php
}

/* Saves the custom meta input */
function vw_lawyer_pro_posttype_bn_metadesig_save( $post_id ) {
	if (!isset($_POST['vw_lawyer_pro_posttype_posttype_testimonial_meta_nonce']) || !wp_verify_nonce($_POST['vw_lawyer_pro_posttype_posttype_testimonial_meta_nonce'], basename(__FILE__))) {
		return;
	}

	if (!current_user_can('edit_post', $post_id)) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}

	// Save desig.
	if( isset( $_POST[ 'vw_lawyer_pro_posttype_posttype_testimonial_desigstory' ] ) ) {
		update_post_meta( $post_id, 'vw_lawyer_pro_posttype_posttype_testimonial_desigstory', sanitize_text_field($_POST[ 'vw_lawyer_pro_posttype_posttype_testimonial_desigstory']) );
	}
  if( isset( $_POST[ 'meta-testimonial-url' ] ) ) {
    update_post_meta( $post_id, 'meta-testimonial-url', esc_url($_POST[ 'meta-testimonial-url']) );
  }

}

add_action( 'save_post', 'vw_lawyer_pro_posttype_bn_metadesig_save' );

/* Testimonials shortcode */
function vw_lawyer_pro_posttype_testimonial_func( $atts ) {
	$testimonial = '';
	$testimonial = '<div class="row">';
	$query = new WP_Query( array( 'post_type' => 'testimonials') );

    if ( $query->have_posts() ) :

	$k=1;
	$new = new WP_Query('post_type=testimonials');

	while ($new->have_posts()) : $new->the_post();
        $custom_url = '';
      	$post_id = get_the_ID();
      	$excerpt = wp_trim_words(get_the_excerpt(),25);
      	$thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
		    if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
      	$desigstory= get_post_meta($post_id,'vw_lawyer_pro_posttype_posttype_testimonial_desigstory',true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $testimonial .= '
          <div class="col-md-6 col-sm-12">
            <div class=" testimonial_box w-100 mb-3">
              <div class="content_box w-100">
                <div class="short_text pt-3"><p>'.$excerpt.'</p></div>
              </div>
              <div class="image-box media">
                <img class="testi-img w-100 d-flex align-self-center mr-3" src="'.esc_url($thumb_url).'" alt="testimonial-thumbnail" />
                <div class="testimonial-box media-body">
                  <h4 class="testimonial_name mt-0"><a href="'.esc_url($custom_url).'">'.esc_html(get_the_title()) .'</a></h4>
                  <p>'.esc_html($desigstory).'</p>
                </div>
              </div>
            </div>
          </div>';
		if($k%3 == 0){
			$testimonial.= '<div class="clearfix"></div>';
		}
      $k++;
  endwhile;
  else :
  	$testimonial = '<h2 class="center">'.esc_html__('Post Not Found','vw-lawyer-pro-posttype-pro').'</h2>';
  endif;
  $testimonial .= '</div>';
  return $testimonial;
}

add_shortcode( 'testimonials', 'vw_lawyer_pro_posttype_testimonial_func' );

/* Services shortcode */
function vw_lawyer_pro_posttype_services_func( $atts ) {
  $services = '';
  $services = '<div class="row">';
  $query = new WP_Query( array( 'post_type' => 'services') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=services');

  while ($new->have_posts()) : $new->the_post();
        $custom_url ='';
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $services_image= get_post_meta(get_the_ID(), 'meta-image', true);
        if(get_post_meta($post_id,'meta-testimonial-url',true !='')){$custom_url =get_post_meta($post_id,'meta-testimonial-url',true); } else{ $custom_url = get_permalink(); }
        $services .= '
            <div class="our_services_outer col-md-6 col-sm-6">
              <div class="services_inner">
                <a href="'.esc_url($custom_url).'">
                <div class="row hover_border">
                  <div class="col-md-3">
                    <img class="" src="'.esc_url($services_image).'">
                  </div>
                  <div class="col-md-9">
                    <h4 class="mt-0">'.esc_html(get_the_title()) .'</h4>
                    <div class="short_text pt-3">'.$excerpt.'</div>
                  </div>
                </div>
               </a>
              </div>
            </div>';
    if($k%2 == 0){
      $services.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $services = '<h2 class="center">'.esc_html__('Post Not Found','vw-lawyer-pro-posttype-pro').'</h2>';
  endif;
  $services .= '</div>';
  return $services;
}

add_shortcode( 'list-services', 'vw_lawyer_pro_posttype_services_func' );

/* Faq shortcode */
function vw_lawyer_pro_posttype_faq_func( $atts ) {
  $faq = '';
  $faq = '<div id="accordion" class="row">';
  $query = new WP_Query( array( 'post_type' => 'faq') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=faq');

  while ($new->have_posts()) : $new->the_post();
        $post_id = get_the_ID();
        $excerpt = wp_trim_words(get_the_excerpt(),25);
        $desigstory= get_post_meta($post_id,'vw_lawyer_pro_posttype_posttype_testimonial_desigstory',true);
        $faq .= '
        <div class="panel-group col-md-6 w-100 mb-3">
          <div class="panel">
            <div class="panel-heading">
            <h4 class="panel-title">
              <a href="#panelBody'.esc_attr($k).'" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"><i class="fas fa-plus"></i>'.get_the_title().' </a>
              </h4>
            </div>
            <div id="panelBody'.esc_attr($k).'" class="panel-collapse collapse in">
            <div class="panel-body">
                <p>'.get_the_content().'</p>
              </div>
            </div>
          </div>
          </div>';
    if($k%2 == 0){
      $faq.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $faq = '<h2 class="center">'.esc_html__('Post Not Found','vw-lawyer-pro-posttype-pro').'</h2>';
  endif;
  $faq .= '</div>';
  return $faq;
}
add_shortcode( 'list-faq', 'vw_lawyer_pro_posttype_faq_func' );