<?php
/*
Plugin Name: Social Fellow
Description: Measure the social metrics of your publications. List how many times your content is shared in different social networks. Also Allows to shared it from an internal panel on the page edit panel.
Version: 2.1.0
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
			add_action('admin_enqueue_scripts', array($this,'add_css') );
      add_action( 'edit_form_after_title', array($this,'add_social_after_title') );
      add_action ('admin_enqueue_scripts', array($this, 'add_js') );

		} 
                /**
		 * Add CSS needed for the plugin
		 */
		public function add_css() {
		    wp_register_style('social_fellow', plugins_url('style.css', __FILE__));
        wp_enqueue_style( 'social_fellow' );
		}  

    /**
     * Admin: add javascript to handle options
     */

    public function add_js() {
      wp_register_script('js_social_fellow', WP_PLUGIN_URL . '/social-fellow/js/social-fellow.js', array('jquery') );
      wp_enqueue_script('js_social_fellow');
    }
                
    function add_social_after_title() {
                    ?>
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
                    <?php
                    $id_post = get_the_id();
                    $permalink = get_permalink($id_post);
                    global $table_prefix;
                    global $wpdb;
                    $table = $table_prefix . "posts";
                    $consulta = "SELECT post_author FROM $table WHERE ID='$id_post'";
                    $resultado = $wpdb->get_results( $consulta );
                    $id_entry_author = $resultado[0]-> post_author;
                    $entry_author = $this -> get_entry_author($id_entry_author);
                    echo "<br/><div class='quicktags-toolbar wp-editor-container'>";
                    echo "<table><tr><th>Author</th><th>Facebook</th><th>Twitter</th><th>Google+</th><th>Linkedin</th></tr>";
                    echo "<td>". $entry_author . "</td>";
                    echo "<td><div class='fb-like' data-href='{$permalink}' data-layout='button_count' data-action='like' data-show-faces='false' data-share='false'></div></td>";
                    echo "<td> <a href='{$permalink}' data-href='https://twitter.com/share' data-url='{$permalink}' class='twitter-share-button'>Tweet</a>  </td> ";
                    echo "<td><div class='g-plusone' data-size='small' data-href='{$permalink}'></div></td>";
                    echo "<td><script type='IN/Share' data-url='{$permalink}' data-counter='right'></script></td></tr></table></div><br/>";
                                    
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
				 This plugin provides you a list of your current active entries & pages and the number of times that each one of them have been shared in 
                                 Twitter, Facebook, Google+ and Linkedin.
				</p>
                                <p>
                                    If this plugin has been useful, you may see my professional profile in <a href="http://es.linkedin.com/in/pedroescuderozumel/es" target="_blank">Linkedin</a> or follow me work at <a target="_blank" href="https://github.com/PedroEscudero">github</a>. Do you have any suggestion about this plugin? Please <a href="mailto:pedroescudero@gmail.com">write me</a>.
                                </p>
                                <?php 
                                  if($_GET['limit']){
                                    $number_of_entries = $_GET['limit'];
                                  }else{
                                    $number_of_entries = 10;
                                  }

                                  switch ($number_of_entries){
                                    case 10:
                                      $selected_limit_10 = "selected";
                                    break;
                                    case 20:
                                      $selected_limit_20 = "selected";
                                    break;
                                    case 50:
                                      $selected_limit_50 = "selected";
                                    break;
                                    case 100:
                                      $selected_limit_100 = "selected";
                                    break;
                                    default:
                                      $selected_limit_all = "selected";
                                    break;
                                  }

                                ?>
                                <div id = 'social-fellow-menu-bar'>
                                    <strong>Number of post and entries to show: </strong>
                                    <select id = 'social-fellow-select-limit' name = 'social-fellow-select-limit'>
                                        <option value='10' <?php echo $selected_limit_10; ?>>10</option>
                                        <option value='20' <?php echo $selected_limit_20; ?>>20</option>
                                        <option value='50' <?php echo $selected_limit_50; ?>>50</option>
                                        <option value='100' <?php echo $selected_limit_100; ?>>100</option>
                                        <option value='' <?php echo $selected_limit_all; ?>>All</option>
                                    </select>
                                </div>
                               
                                <?php
                                    global $wpdb;
                                    $pages_entries_counter = $wpdb->get_var( "SELECT count(*) FROM $wpdb->posts WHERE post_status = 'publish' && (post_type ='post' || post_type='page')" );
                                   
                                   
                                ?>
				<h3>Entries & Pages (<?php echo $pages_entries_counter; ?>)</h3>
                                   
				
                               <?php
                                    
                                    $number_of_pages = ceil( $pages_entries_counter / $number_of_entries );

                                    if($_GET['pa']){
                                        $current_page = $_GET['pa'];
                                    }else{
                                        $current_page = 1;
                                    }
                                    if ($pages_entries_counter > $number_of_entries && $current_page >1){
                                        $current_entries = ($current_page -1) * $number_of_entries;
                                    }else{
                                        $current_entries = 0; //$pages_entries_counter;
                                    }
                                    
                                    global $table_prefix;
                                    $table = $table_prefix . "posts";
                                   
                                    $consulta = "SELECT post_title, ID, post_name, post_type, post_author FROM $table WHERE post_status = 'publish' && (post_type ='post' || post_type='page') ORDER BY ID ASC LIMIT $number_of_entries OFFSET {$current_entries}";
                                    $resultado = $wpdb->get_results( $consulta );
                                    echo "<table><tr><th>Name</th><th>Author</th><th>Facebook</th><th>Twitter</th><th>Google+</th><th>Linkedin</th></tr>";
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
                                             'base' => '/wp-admin/options-general.php?page=socialfellow&limit='.$number_of_entries.'%_%',
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
