<?php
/**
 * Plugin Name: WordPress HTTP API Test
 * Plugin URI: 
 * Description: Example plugin showing how to make web requests using WordPress HTTP API
 * Author: ericjuden
 * Author URI: http://ericjuden.com
 * Version: 1.0
 */
 
class WP_HTTP_API_Tester {
	function __construct(){
		add_action( 'admin_menu' , array( $this , 'admin_menu' ) );
	}
	
	function admin_menu(){
		add_management_page( __( 'HTTP API Tester' ) , __( 'HTTP API Tester' ) , 'manage_options' , 'wp-http-api-tester' , array( $this , 'tester_page' ) );
	}
	
	function tester_page(){
		// Security check
		if( !current_user_can('manage_options') ){
			wp_die( __( 'You do not have permission to view this page.' ) );
		}
?>
		<div class="wrap">
			<?php screen_icon( 'tools' ); ?>
			<h2><?php _e( 'HTTP API Tester' ); ?></h2>
			
			<form method="post">
				<label for="url"><?php _e( 'Enter a website URL to retrieve' ); ?></label><br />
				<input type="url" name="url" id="url" class="regular-text" />
				<input type="submit" name="submit" class="button-primary" value="<?php _e( 'Retrieve URL' ); ?>" />
			</form>
			
			
			<?php if( isset( $_POST['submit'] ) ){	// Check if we have posted yet. If yes, display results ?>
				<?php $response = wp_remote_get( $_POST['url'] ); ?>
				
				<?php $response_code = wp_remote_retrieve_response_code( $response ); ?>
				<?php $response_message = wp_remote_retrieve_response_message( $response ); ?>
				<?php 
					switch( $response_code ){
						case "200":
?>
			<div class="updated"><strong><?php _e( 'Response Code' ); ?>: </strong> <?php echo $response_code; ?> <?php echo $response_message; ?></div>
<?php
						break;
						
						default:
?>
			<div class="error"><strong><?php _e( 'Response Code' ); ?>: </strong> <?php echo $response_code; ?> <?php echo $response_message; ?></div>
<?php						
						break;
					} 
				?>
				
				<h3><?php _e( 'Headers' ); ?></h3>
				<table class="widefat">
					<?php $headers = wp_remote_retrieve_headers( $response ); ?>
					<?php foreach( $headers as $key => $value ){ ?>
					<tr>
						<th><strong><?php echo $key; ?></strong></th>
						<td><?php echo $value; ?></td>						
					</tr>
					<?php } ?>	
				</table>
				
				<h3><?php _e( 'Body' ); ?></h3>
				<textarea name="body" id="body" cols="80" rows="50" class="large-text"><?php echo wp_remote_retrieve_body( $response ); ?></textarea>
			
				<h3><?php _e( 'Cookies' ); ?></h3>
				<textarea name="body" id="body" cols="80" rows="10" class="large-text"><?php print_r( $response['cookies'] ); ?></textarea>
			<?php } ?>
		</div>
<?php
	}
}
$wp_http_api_tester = new WP_HTTP_API_Tester();
?>
