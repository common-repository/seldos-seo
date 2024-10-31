<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.hayatikodla.com
 * @since      1.0.0
 *
 * @package    Seldos_Seo
 * @subpackage Seldos_Seo/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Seldos_Seo
 * @subpackage Seldos_Seo/public
 * @author     Hasan Yüksektepe <hasanhasokeyk@hotmail.com>
 */
class Seldos_Seo_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name; 
		$this->version = $version;
        
        if(!empty(get_option( 'googleAnalyticCode' )) and strlen(get_option( 'googleAnalyticCode' )) >= 10){
            add_action('wp_footer', [$this,'googleAnalyticCode']);
        }
        
        if(!empty(get_option( 'googleSCCode' )) and strlen(get_option( 'googleSCCode' )) >= 10){
            add_action('wp_head', [$this,'googleSCCode']);
        }
        if(!empty(get_option( 'yandexMetrica' )) and strlen(get_option( 'yandexMetrica' )) >= 6){
            add_action('wp_footer', [$this,'yandexMetrica']);
        }
        
        add_action('wp_title', array(&$this,'titleChange'));
        add_action( 'wp_head', array(&$this, 'descChange' ) );
        //add_filter('the_title', array(&$this,'titleChange'));
		
		if(!empty(get_option( 'autoAlt' )) and get_option( 'autoAlt' ) == 1){
			add_filter('image_send_to_editor', array( $this, 'seldos_seo_image_alt_public'), 10, 2);
			add_filter('wp_get_attachment_image_attributes', array( $this, 'seldos_seo_image_alt'), 10, 2);
		}
        
		if(!empty(get_option( 'autoSefName' )) and get_option( 'autoSefName' ) == 1){
			add_filter( 'sanitize_file_name', 'seldos_seo_filename', 10 );
			function seldos_seo_filename( $filename ){
				$info = pathinfo( $filename );
				$ext  = empty( $info['extension'] ) ? '' : '.' . $info['extension'];
				$name = basename( $filename, $ext );
				return sanitize_title($name).$ext;
			}
		}
		
		if(!empty(get_option( 'wpjson' )) and get_option( 'wpjson' ) == 1){
			if ( version_compare( get_bloginfo( 'version' ), '4.7', '>=' ) ) {
				require_once( plugin_dir_path( __FILE__ ) . '../includes/class-seldos-seo-disable-rest-api.php' );
				new Disable_REST_API( __FILE__ );
			} else {
				remove_action( 'wp_head','rest_output_link_wp_head');
				remove_action( 'wp_head', 'wp_oembed_add_discovery_links');
				remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
				remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
				add_filter( 'json_enabled', '__return_false' );
				add_filter( 'json_jsonp_enabled', '__return_false' );
			}
		}
    
    
        //404 detect
        //add_action( 'wp_loaded', array($this,'seldos_seo_404_detect'));
        add_action( 'wp_enqueue_scripts', array($this,'seldos_seo_404_detect'));
        //404 detect
        
        add_action('init', array($this,'do_output_buffer'));        
	}
    
    function do_output_buffer() {
        ob_start();
    }
    
    function seldos_seo_404_detect(){
        if(is_404()) {
            global $wpdb;
            if(isset($_SERVER['REQUEST_URI']) and !empty($_SERVER['REQUEST_URI'])){
                $url = trim(strip_tags($_SERVER['REQUEST_URI']));
                $setUrl = get_bloginfo('url');
                
                $control = $wpdb->get_results("SELECT * FROM seldos_seo_404 WHERE link='".$url."'");
                if($wpdb->num_rows == 0){
                    $add = $wpdb->query("INSERT INTO seldos_seo_404 SET link='".$url."'");
                }else{
                    wp_redirect( rtrim($setUrl,'/').$control[0]->new_link, 301 );
                    exit;
                }
            }
        }
    }

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/seldos-seo-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/seldos-seo-public.js', array( 'jquery' ), $this->version, false );

	}
    
    public function googleAnalyticCode(){
    ?>
    <!-- SELDOS SEO-->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-22483688-6"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', '<?=get_option( 'googleAnalyticCode' )?>');
    </script>
    <!-- SELDOS SEO-->
    <?php
    }
    
    public function googleSCCode(){
    ?>
    <!-- SELDOS SEO-->
    <meta name="google-site-verification" content="<?=get_option( 'googleSCCode' )?>" />
    <!-- SELDOS SEO-->
    <?php
    }
    
    public function yandexMetrica(){
    ?>
    <!-- SELDOS SEO-->
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" > (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter<?=get_option( 'yandexMetrica' )?> = new Ya.Metrika({ id:<?=get_option( 'yandexMetrica' )?>, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/<?=get_option( 'yandexMetrica' )?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <!-- SELDOS SEO-->
    <?php
    }
    
    public function titleChange($title){
       
        if(is_single()){
            $t = get_option( 'post_title' );
            return trim(str_replace(['%postTitle%','%sep%','%sitename%','&raquo;'],[$title,'-',get_bloginfo('name'),''],$t));
        }else if(is_page()){
            $t = get_option( 'page_title' );
            return trim(str_replace(['%postTitle%','%sep%','%sitename%','&raquo;'],[$title,'-',get_bloginfo('name'),''],$t));
        }
        
        return $title;
    }
    
    public function descChange($desc){
        global $post;
        
		if(isset($desc,$post->ID)){
			//$desc = wp_trim_words($post->post_content,40,'');
			$desc = get_post_meta($post->ID,'seldos_seo_description',true);
		}
			
        echo '<!-- SELDOS SEO -->'."\n";
        if(is_single()){
            $t = get_option( 'post_desc' );
            $t = trim(str_replace(['%postDesc%','%postTitle%','%sep%','%sitename%','&raquo;'],[$desc,$post->post_title,'-',get_bloginfo('name'),''],$t));
            echo '<meta name="description"  content="'.$t.'" />';
        }else if(is_page()){
            $t = get_option( 'page_desc' );
            $t = trim(str_replace(['%postDesc%','%postTitle%','%sep%','%sitename%','&raquo;'],[$desc,$post->post_title,'-',get_bloginfo('name'),''],$t));
            echo '<meta name="description"  content="'.$t.'" />';
        }
        echo "\n".'<!-- SELDOS SEO -->'."\n";
        
    }
    
	public function seldos_seo_image_alt($attributes, $attachment){
		if ( !isset( $attributes['alt'] ) || '' === $attributes['alt'] ) {
			$attributes['alt']=get_the_title($attachment->ID);
		}
		return $attributes;
	}
	public function seldos_seo_image_alt_public($html, $id){
		return str_replace('alt=""','alt="'.get_the_title($id).'"',$html);
	}
}
