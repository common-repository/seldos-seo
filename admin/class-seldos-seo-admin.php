<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.hayatikodla.com
 * @since      1.0.0
 *
 * @package    Seldos_Seo
 * @subpackage Seldos_Seo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Seldos_Seo
 * @subpackage Seldos_Seo/admin
 * @author     Hasan Yüksektepe <hasanhasokeyk@hotmail.com>
 */
class Seldos_Seo_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name; 
		$this->version = $version;
        
        add_action( 'admin_menu', array(&$this, 'register_seldos_seo_menu'));
		remove_action('template_redirect', 'redirect_canonical');
		add_action( 'template_redirect', array(&$this, 'seldos_sslRedirect'));
        add_action( 'add_meta_boxes', array(&$this, 'seldos_seo_add_meta_box') );
        add_action( 'save_post', array(&$this, 'seldos_seo_save') );
        add_action( 'wp_ajax_seldos_seo_ajax_post', array($this, 'seldos_seo_ajax_post'));
        
        //INDEXED COLUMNS
        add_filter( 'manage_pages_columns', array(&$this, 'seldos_seo_index_columns') );
        add_filter( 'manage_posts_columns', array(&$this, 'seldos_seo_index_columns') );
        //INDEXED COLUMNS
        
        /*
        global $notice_text,$notice_type;
        $notice_text = 'test';
        $notice_type = 'error';
        add_action( 'admin_notices',  array($this, 'seldos_seo_notice'));
        */
        
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "DROP TABLE IF EXISTS `seldos_seo_404`;
        CREATE TABLE IF NOT EXISTS `seldos_seo_404` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `link` varchar(255) NOT NULL,
          `new_link` varchar(255) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;
        COMMIT;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
        
	}
    
    function seldos_seo_index_columns( $columns ) {

        return array_merge( $columns, array('post_desc' => __('Indexed','seldos-seo')) );
        
    }
    
    public function seldos_seo_ajax_post() {
        global $wpdb;
        
        $post = $this->seldos_postSecurty($_POST);
        extract($post);
        
        switch($_POST['type']){
            case 'error-link-save':
                
                $postName = ['errorID','errorNewLink'];
                if($this->seldos_postControl($postName)){
                    $save = $wpdb->query("UPDATE seldos_seo_404 SET new_link = '".$errorNewLink."' WHERE id='".$errorID."'");
                    if($save){
                        _e('Success','seldos-seo');
                    }else{
                        _e('Error','seldos-seo');
                    }
                }else{
                    wp_die(__('Error Save Error','seldos-seo'));
                }
            break;
            
            case 'error-link-delete':
                
                $postName = ['errorID'];
                if($this->seldos_postControl($postName)){
                    $save = $wpdb->query("DELETE FROM seldos_seo_404 WHERE id='".$errorID."'");
                    if($save){
                        _e('Success','seldos-seo');
                    }else{
                        _e('Error','seldos-seo');
                    }
                }else{
                    wp_die(__('Error Save Error','seldos-seo'));
                }
            break;
            
            default:
                _e('hahahaha :)','seldos-seo');
            break;
        }
        exit;
    }
    
    function seldos_seo_notice(){
        global $notice_text,$notice_type;
        ?>
        <div class="notice notice-<?=$notice_type?> is-dismissible">
            <p><?=__( $notice_text, 'seldos-seo' );?></p>
        </div>
        <?php
    }
    
    function register_seldos_seo_menu(){
        add_menu_page(__('Seldos SEO','seldos'), __('Seldos SEO','seldos-seo'), 'manage_options', 'seldos-seo-settings',array(&$this, 'seldos_seo_settings'),plugins_url( '/img/icon.png' , __FILE__),2);
        add_submenu_page( 'seldos-seo-settings', __('Settings','seldos-seo'), __('Settings','seldos-seo'), 'manage_options', 'seldos-seo-settings');
        //add_submenu_page( 'seldos-seo-settings', __('SEO Appearance','seldos'), __('SEO Appearance','seldos'), 'manage_options', 'seldos-seo-apperance',array(&$this, 'seldos_seo_apperance'));
        add_submenu_page( 'seldos-seo-settings', __('SEO Error Page','seldos'), __('SEO Error Page','seldos-seo'), 'manage_options', 'seldos-seo-error-page',array(&$this, 'seldos_seo_error_page'));
        //add_submenu_page( 'seldos-seo-settings', __('SEO Redirects','seldos'), __('SEO Redirects','seldos'), 'manage_options', 'seldos-seo-redirects',array(&$this, 'seldos_seo_redirects'));
    }
    
    function seldos_seo_settings(){
        require plugin_dir_path(__FILE__).'partials/seldos-seo-settings.php';
    }
    
    function seldos_seo_apperance(){
        require plugin_dir_path(__FILE__).'partials/seldos-seo-apperance.php';
    }
    
    function seldos_seo_error_page(){
        require plugin_dir_path(__FILE__).'partials/seldos-seo-error-page.php';
    }
    function seldos_seo_redirects(){
        require plugin_dir_path(__FILE__).'partials/seldos-seo-redirects.php';
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Seldos_Seo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Seldos_Seo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/seldos-seo-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Seldos_Seo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Seldos_Seo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/seldos-seo-admin.js', array( 'jquery' ), $this->version, false );
	}
    
    function seldos_postSecurty($post){
		global $mysqli;
		$degerler = array();
		foreach($post as $p => $d){
			if(is_string($_POST[$p]) === true){
				$degerler[$p] = addslashes(trim(strip_tags(($d))));
			}
		}
		return $degerler;
	}
	
	function seldos_postControl($post){
		
		$kontrol = 0;
		foreach($post as $parametre){
			if(isset($_POST[$parametre]) and !empty($_POST[$parametre])){
				$kontrol ++;
			}else{
				return false;
				break;
			}
		}
		
		if(count($post)==$kontrol){
			return true;
		}else{
			return false;
		}
		
	}
	
	function seldos_sslRedirect(){
		
		$wordpressSsl = empty(get_option( 'wordpressSsl' ))?false:get_option( 'wordpressSsl' );
		
		if($wordpressSsl != false and isset($_SERVER['SCRIPT_URI'])){
            
			$getUrl = parse_url($_SERVER['SCRIPT_URI']);
			$setUrl = parse_url(get_bloginfo('url'));
			
			if(strstr($getUrl['path'],'index.php')){
				wp_redirect( str_replace([$getUrl['scheme'],$getUrl['host'],'index.php'],[$setUrl['scheme'],$setUrl['host'],''],$_SERVER['SCRIPT_URI']), 301 );
                exit;
			}else if ( !is_ssl() and get_option( 'sslActive' ) == 'true') {
				
				if(strstr($getUrl['scheme'],$setUrl['scheme']) === false){
					wp_redirect( str_replace([$getUrl['scheme'],$getUrl['host']],[$setUrl['scheme'],$setUrl['host']],$_SERVER['SCRIPT_URI']), 301 );
                    exit;
				}
				
			}else if(strstr($getUrl['host'],$setUrl['host']) === false){
				wp_redirect( str_replace([$getUrl['scheme'],$getUrl['host']],[$setUrl['scheme'],$setUrl['host']],$_SERVER['SCRIPT_URI']), 301 );
                exit;
			}
		}
	}
    
    function seldos_seo_get_meta( $value ) {
        global $post;

        $field = get_post_meta( $post->ID, $value, true );
        if ( ! empty( $field ) ) {
            return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
        } else {
            return false;
        }
    }

    function seldos_seo_add_meta_box() {
        
        $post_types = get_post_types('','names');
        unset($post_types['attachment']);
        unset($post_types['revision']);
        unset($post_types['nav_menu_item']);
        unset($post_types['custom_css']);
        unset($post_types['customize_changeset']);
        unset($post_types['oembed_cache']);
        foreach ( $post_types as $post_type ) {
            
            add_meta_box(
                'seldos_seo-seldos-seo',
                __( 'Seldos SEO Settings', 'seldos-seo' ),
                array(&$this,'seldos_seo_html'),
                $post_type,
                'advanced',
                'core'
            );
           
        }        
    }

    function seldos_seo_html( $post) {
        wp_nonce_field( '_seldos_seo_nonce', 'seldos_seo_nonce' ); 
        require (__DIR__).'/partials/seldos-seo-post-type-template.php';
    }

    function seldos_seo_save( $post_id ) {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
        if ( ! isset( $_POST['seldos_seo_nonce'] ) || ! wp_verify_nonce( $_POST['seldos_seo_nonce'], '_seldos_seo_nonce' ) ) return;
        if ( ! current_user_can( 'edit_post', $post_id ) ) return;

        if ( isset( $_POST['seldos_seo_title'] ) )
            update_post_meta( $post_id, 'seldos_seo_title', esc_attr( $_POST['seldos_seo_title'] ) );
        if ( isset( $_POST['seldos_seo_description'] ) )
            update_post_meta( $post_id, 'seldos_seo_description', esc_attr( $_POST['seldos_seo_description'] ) );
    }
    

    /*
        Usage: seldos_seo_get_meta( 'seldos_seo_title' )
        Usage: seldos_seo_get_meta( 'seldos_seo_description' )
    */

    
}
