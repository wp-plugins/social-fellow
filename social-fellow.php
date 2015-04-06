<?php
/*
Plugin Name: Social Fellow
Description: List how many times your content is shared in different social networks. Also Allows to shared it from an internal panel.
Version: 1.0 
Author: Pedro Escudero
Author URI: http://es.linkedin.com/in/pedroescuderozumel/es
Plugin URI: http://github.com/social-fellow
License: GPL2
*/

// Check for existing class
if ( ! class_exists( 'social_fellow' ) ) {
/**
	 * Main Class
	 */
	class social_fellow  {

		/**
		 * Class constructor: initializes class variables and adds actions and filters.
		 */
		public function __construct() {
			$this->social_fellow();
		}

		public function social_fellow() {
			register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );
			register_deactivation_hook( __FILE__, array( __CLASS__, 'deactivation' ) );

			// Register admin only hooks
			if(is_admin()) {
				$this->register_admin_hooks();
			}
                        
                        // Register global hooks
			$this->register_global_hooks();
		}
                /**
		 * Registers global hooks.
		 */
		public function register_global_hooks() {
			add_action('admin_enqueue_scripts', array($this,'add_css'));
		} 
                /**
		 * Add CSS needed for the plugin
		 */
		public function add_css() {
		    wp_register_style('social_fellow', plugins_url('style.css', __FILE__));
                    wp_enqueue_style( 'social_fellow' );
		}  
		
		/**
		 * Handles activation tasks, such as registering the uninstall hook.
		 */
		public function activation() {
			register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );
		}

		/**
		 * Handles deactivation tasks, such as deleting plugin options.
		 */
		public function deactivation() {

		}

		/**
		 * Handles uninstallation tasks, such as deleting plugin options.
		 */
		public function uninstall() {
			
		}

		/**
		 * Registers admin only hooks.
		 */
		public function register_admin_hooks() {
			
			// Add Settings Link
			add_action('admin_menu', array($this, 'admin_menu'));

			// Add settings link to plugins listing page
			add_filter('plugin_action_links', array($this, 'plugin_settings_link'), 2, 2);

			
		}

		/**
		 * Admin: add settings link to plugin management page
		 */
		public function plugin_settings_link($actions, $file) {
			if(false !== strpos($file, 'social-fellow')) {
				$actions['settings'] = '<a href="options-general.php?page=socialfellow">Settings</a>';
			}
			return $actions;
		}

		/**
		 * Admin: add Link to sidebar admin menu
		 */
		public function admin_menu() {
			
			add_options_page('Social Fellow Options', 'Social Fellow', 'manage_options', 'socialfellow', array($this, 'settings_page'));
		}

		/**
		 * Admin: settings page
		 */
		public function settings_page() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			} ?>
                            <div id="fb-root"></div>
                            <script>(function(d, s, id) {
                              var js, fjs = d.getElementsByTagName(s)[0];
                              if (d.getElementById(id)) return;
                              js = d.createElement(s); js.id = id;
                              js.src = "//connect.facebook.net/es_ES/sdk.js#xfbml=1&appId=448388558529572&version=v2.3";
                              fjs.parentNode.insertBefore(js, fjs);
                            }(document, 'script', 'facebook-jssdk'));</script>
                            
                            <script src="//platform.linkedin.com/in.js" type="text/javascript"> lang: en_US</script>
                            
                            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                            
                            <script src="https://apis.google.com/js/platform.js" async defer>
                                {lang: 'en'}
                            </script>
                            
			<div class="wrap">

				<?php screen_icon(); ?>
				<h2>Social Fellow</h2>

				<hr/>

				<div style="float:right; margin-left:20px;">
					
				</div>
                                
				<h2>Description</h2>
				<p>
				 This plugin provides you a list of your current entries  & pages and the number of times that each one of them have been shared in 
                                 Twitter, Facebook, Google+ and Linkedin.
				</p>
                                <p>
                                    If this plugin has been useful, you may see my professional profile in <a href="http://es.linkedin.com/in/pedroescuderozumel/es" target="_blank">Linkedin</a> or follow me work at <a target="_blank" href="https://github.com/PedroEscudero">github</a>. Do you have any suggestion about this plugin? Please <a href="mailto:pedroescudero@gmail.com">write me</a>.
                                </p>
                                <?php
                                    global $wpdb;
                                    $pages_entries_counter = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' && (post_type ='post' || post_type='page')" );
                                   
                                   
                                ?>
				<h3>Entries & Pages (<?php echo $pages_entries_counter; ?>)</h3>
                                   
				
                               <?php
                               
                                    $number_of_pages = (round( $pages_entries_counter /10 ))-1;
                                    if($_GET['pa']){
                                        $current_page = $_GET['pa'];
                                    }else{
                                        $current_page = 1;
                                    }
                                    $current_entries = $current_page * 10;
                                    global $table_prefix;
                                    $table = $table_prefix . "posts";
                                   
                                    $consulta = "SELECT post_title, ID, post_name, post_type, post_author FROM $table WHERE post_status = 'publish' && (post_type ='post' || post_type='page') ORDER BY ID ASC LIMIT 10 OFFSET {$current_entries}";
                                    $resultado = $wpdb->get_results( $consulta );
                                    echo "<table><tr><th>Name</th><th>Author</th><th>Facebook</th><th>Twitter</th><th>Google+</th><th>Linkedin</th><tr>";
                                    foreach ( $resultado as $fila ):
                                       
                                       $permalink = get_permalink( $fila->ID );
                                       $entry_author = $this -> get_entry_author( $fila->post_author );
                                       echo "<tr><td><a target='_blank' href='{$permalink}'>". $fila->post_title . "</a></td>";
                                       echo "<td>". $entry_author . "</td>";
                                       echo "<td><div class='fb-like' data-href='{$permalink}' data-layout='button_count' data-action='like' data-show-faces='false' data-share='false'></div></td>";
                                       echo "<td> <a href='{$permalink}' data-href='https://twitter.com/share' data-url='{$permalink}' class='twitter-share-button'>Tweet</a>  </td> ";
                                       echo "<td><div class='g-plusone' data-size='small' data-href='{$permalink}'></div></td>";
                                       echo "<td><script type='IN/Share' data-url='{$permalink}' data-counter='right'></script></td></tr>";
                                    
                                    endforeach;
                                    echo "</table><hr></hr>";
                                   
                                    if ( $number_of_pages > 1 )  {
                                   
                                        echo paginate_links(array(
                                             'base' => '/wp-admin/options-general.php?page=socialfellow'.'%_%',
                                             'format' => '&pa=%#%',
                                             'current' => $current_page,
                                             'prev_next' => True,
                                             'prev_text' => __('&laquo; Previous'),
                                             'next_text' => __('Next &raquo;'),
                                             'total' => $number_of_pages,
                                             'mid_size' => 4,
                                             'type' => 'list'
                                        ));
                                   }

                               ?>
                                
			 	<hr/>
                                
			</div>
			<?php
		}
                public function get_entry_author( $author_id ) {
                    global $table_prefix;
                    global $wpdb;
                    $table = $table_prefix . "users";
                    $consulta = "SELECT display_name FROM $table WHERE ID ='{$author_id}' ";
                    $resultado = $wpdb->get_results( $consulta );
                    return $resultado[0] ->display_name;
                }
                  
	} // End social_fellow class

	// Init Class
	new social_fellow();
}

?>
