<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
// WIDGET 
class Webcam_Viewer_Widget extends WP_Widget {

	// Main constructor

	public function __construct() {

		$widget_ops = array(

		"classname" => "webcam_viewer_widget",

		"description" => "A plugin for Webcam by Antonio Germani.",

		"customize_selective_refresh" => true);

		parent::__construct( "webcam_viewer_widget", "Webcam_Viewer_Widget", $widget_ops );

	}

	//defining the frontend part

	public function widget($args, $instance) {
		// $instance è l'array dei dati impostati nel backend del widget

		if ( ! isset( $args['widget_id'] ) ) {

			$args['widget_id'] = $this->id;

		}

		//getting the title from the widget backend

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Webcam viewer' );

		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		
		$url = ( ! empty( $instance['url'] ) ) ? $instance['url'] : __( 'Webcam viewer' );

		$url = apply_filters( 'widget_url', $url, $instance, $this->id_base );
		
		$singola = ( ! empty( $instance['singola'] ) ) ? $instance['singola'] : __( 'Webcam viewer' );

		$singola = apply_filters( 'widget_singola', $singola, $instance, $this->id_base );
		$number = (isset($number))?$number:1;
		
		//setting up the loop arguments

		$args = array(

		'post_type' => 'post',

		'post_status' => 'publish',

		'posts_per_page' => $number);

		$query1 = new WP_Query($args); ?>

		<div class="container-fr">

		<?php /* printing the widget title, if set */	
		$args['before_title']=(isset($args['before_title']))?$args['before_title']:'';
		$args['after_title']=(isset($args['after_title']))?$args['after_title']:'';
		if ( $title ) {

			echo '<h3 style="text-align: center;">'.$args['before_title'] . $title . $args['after_title'].'</h3>';
		}
		
		if ($_SERVER['SERVER_NAME']!="localhost"){
			$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off') ? 'https://' : 'http://';
			$base_with_domain=$base_url.$_SERVER['SERVER_NAME']."/";
			$base_domain="";
		} else {			
			$base_with_domain="";
			$base_domain="http://localhost/wordpress/wordpress/";
		}
		
		// prendo i dati del settings
		$settings = get_option( 'webcam_viewer_settings_option_name' ); // Array of All Options
		
		//$url= $base_with_domain.$settings['url_img_webcam_10'];	
		
		$imgcss="wimg".$settings['stylecss_14'];		
		$caption="caption".$settings['stylecss_14'];
		$stylecss="style".$settings['stylecss_14'];
		
		// trovo l'ID numerico del widget
		$idarray=explode("-",$this->id);
		$id = $idarray[count($idarray)-1];
			
		if (isset($settings['reverse_8']) && $settings['reverse_8']){
			$caption=$caption."w";
		}
		if (isset($settings['formato_7']) && $settings['formato_7'] == 0){
			$date="%e-%m-%y";
		}
		if (isset($settings['formato_7']) && $settings['formato_7'] == 1){
			$date="%d %B %Y";
		}
		if (isset($settings['formato_7']) && $settings['formato_7'] == 2){
			$date="%A %d %B %Y";
		}
		if (isset($settings['formato_7']) && $settings['formato_7'] == 3){
			$date="%Y-%e-%m";
		}
		if (isset($settings['refresh_15']) && $settings['refresh_15']>0 && $singola!=2) { 
			?>
			<script type="text/javascript">
				function timedRefresh(timeoutPeriod) {
					var timer = setInterval(function() {
						if (timeoutPeriod > 0) {
							timeoutPeriod -= 1;
							document.getElementById("ticker").innerHTML = timeoutPeriod + ".." + "<br />";
						} else {
						clearInterval(timer);
						window.location.reload()
						};
					}, 1000);
				};
				timedRefresh(<?php echo $settings['refresh_15']?>);
			</script>	
			<div id="ticker" ></div>
			<?php
		}
		if ($singola==1) { // singola immagine
			?> 
			<div class="immagine" id="<?php echo $stylecss; ?>" >
			<?php $url=$url."?".rand()."\n";	
				?>
				<img id="<?php echo $imgcss; ?>" class="myimage" src="<?php echo $base_with_domain,$url; ?>" alt="Webcam img missing" title="webcam"  />
				<?php				
			echo '</div>';
		} else {
			echo "PRO edition needing";
		}			
		?>
		</div>
		<?php
	}
	
	// admin view
	public function form( $instance ) {
		// input dei dati impostabili nel widget
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$url = isset( $instance['url'] ) ? esc_attr( $instance['url'] ) : '';
		$singola = isset( $instance['singola'] ) ? esc_attr( $instance['singola'] ) : '';
		?>

		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>

		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id( 'url' ); ?>"><?php _e( 'Url:' ); ?></label>

		<input class="widefat" id="<?php echo $this->get_field_id( 'url' ); ?>" name="<?php echo $this->get_field_name( 'url' ); ?>" type="text" value="<?php echo $url; ?>" /></p>
		
		<p><?php $checked = ( isset( $singola ) && $singola === '0' ) ? 'checked' : '' ; ?>
		<label for="<?php echo $this->get_field_id( 'singola' ); ?>"><input type="radio" name="<?php echo $this->get_field_name( 'singola' ); ?>" id="<?php echo $this->get_field_id( 'singola-0' ); ?>" value="0" <?php echo $checked; ?>disabled> <?php echo __("Multiple", 'Webcam-viewer')," ->PRO edition"; ?></label><br>
		<?php $checked = ( isset( $singola ) && $singola === '1' ) ? 'checked' : '' ; ?>
		<label for="singola-1"><input type="radio" name="<?php echo $this->get_field_name( 'singola' ); ?>" id="<?php echo $this->get_field_id( 'singola-1' ); ?>" value="1" checked="checked"<?php echo $checked; ?>> <?php echo __("Single", 'Webcam-viewer'); ?></label><br>
		<?php $checked = ( isset( $singola ) && $singola === '2' ) ? 'checked' : '' ; ?>
		<label for="singola-2"><input type="radio" name="<?php echo $this->get_field_name( 'singola' ); ?>" id="<?php echo $this->get_field_id( 'singola-2' ); ?>" value="2" <?php echo $checked; ?>disabled> <?php echo __("Rapid", 'Webcam-viewer')," ->PRO edition"; ?></label><br>
		<?php $checked = ( isset( $singola ) && $singola === '3' ) ? 'checked' : '' ; ?>
		<label for="singola-3"><input type="radio" name="<?php echo $this->get_field_name( 'singola' ); ?>" id="<?php echo $this->get_field_id( 'singola-3' ); ?>" value="3" <?php echo $checked; ?>disabled> <?php echo __("Bootstrap multiple", 'Webcam-viewer')," ->PRO edition"; ?>
		</label></p> 
		<?php
		echo __("Select how your webcam works for uploading the images. <br>SINGLE: set it if there is a single image with the same name always. <br>MULTIPLE: set it if there are multiple images into a folder. <br>RAPID: set it if there are multiple immages into a folder but you want to show only the last one with a rapid refresh. With this mode you can publish more modules with different webcams. <br>BOOTSTRAP: if there are multiple immages into a folder and you want to show them with a nice fade-in/out effect by Bootstrap."
		, 'Webcam-viewer');
	}	

	// save backend options

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['url'] = sanitize_text_field( $new_instance['url'] );
		$instance['singola'] = sanitize_text_field( $new_instance['singola'] );

		return $instance; // restituisce un array con i dati impostati nel backend
	}
}
?>