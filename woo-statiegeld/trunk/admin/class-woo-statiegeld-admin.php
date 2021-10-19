<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://www.thijsotter.nl
 * @since      1.0.0
 *
 * @package    Woo_Statiegeld
 * @subpackage Woo_Statiegeld/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Woo_Statiegeld
 * @subpackage Woo_Statiegeld/admin
 * @author     Thijs Otter <info@thijsotter.nl>
 */
class Woo_Statiegeld_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $woo_statiegeld    The ID of this plugin.
	 */
	private $woo_statiegeld;

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
	 * @var      string    $woo_statiegeld       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $woo_statiegeld, $version ) {

		$this->woo_statiegeld = $woo_statiegeld;
        $this->version = $version;

        add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 ); 
        add_action( 'woocommerce_settings_tabs_deposit', array( $this, 'settings_tab' ) ); 
        add_action( 'woocommerce_update_options_deposit', array( $this, 'update_settings' ) );

        add_filter( 'manage_edit-product_deptype_columns', array( $this, 'deptype_columns' ) ); 

    }

    /** 
     * Adds a new settings tab to the WooCommerce settings tabs array. 
     *
     * @since   2.0.0
     */ 
    function add_settings_tab( $settings_tabs ) { 
        $settings_tabs['deposit'] = __( 'Statiegeld', $this->woo_statiegeld ); 
        return $settings_tabs; 
    } 
 
    /** 
     * Uses the WooCommerce admin fields API to output settings via the woocommerce_admin_fields() function. 
     *
     * @since   2.0.0
     */ 
    function settings_tab() { 
        woocommerce_admin_fields( $this->get_settings() ); 
    } 
 
    /** 
     * Uses the WooCommerce options API to save settings via the woocommerce_update_options() function. 
     *
     * @since   2.0.0
     */ 
    function update_settings() { 
        woocommerce_update_options( $this->get_settings() ); 
    } 
 
    /** 
     * Get all the settings for this plugin for woocommerce_admin_fields() function. 
     * 
     * @return array Array of settings for woocommerce_admin_fields() function. 
     * @since   2.0.0
     */ 
    function get_settings() { 

        // Start Section
        $settings = array( 
            'section_title' => array( 
                'name'     => __( 'Statiegeld', $this->woo_statiegeld ), 
                'type'     => 'title', 
                'desc'     => '', 
                'id'       => 'wc_deposit_section_title' 
            ),
            'hide_in_loop' => array( 
                'name' => __( 'Verbergen op archief/productpagina', $this->woo_statiegeld ), 
                'type' => 'checkbox', 
                'desc' => __( 'Indien aangevinkt, wordt statiegeld niet getoond op archief of enkele productpagina\'s', $this->woo_statiegeld ), 
                'id'   => 'wc_deposit_hide_in_loop' 
            ), 
            'hide_in_cart' => array( 
                'name' => __( 'Verbergen in winkelwagen/betaling', $this->woo_statiegeld ), 
                'type' => 'checkbox', 
                'desc' => __( 'Indien aangevinkt, wordt statiegeld niet getoond naast de productprijzen op de winkelwagen pagina of tijdens het afrekenen.', $this->woo_statiegeld ), 
                'id'   => 'wc_deposit_hide_in_cart' 
            )
        ); 
        
        $settings = apply_filters( 'wc_deposit_settings', $settings );


        // End Section
        $settings['section_end'] = array( 
             'type' => 'sectionend', 
             'id' => 'wc_deposit_section_end' 
        );
 
        return $settings; 
    } 

    /**
     * Register custom taxonomy
     *
     * @since   1.0.0
     */
    public function statiegeld_taxonomy() {

        $labels = array( 
            'name' => _x( 'Soorten statiegeld', 'taxonomy general name', $this->woo_statiegeld ),
            'singular_name' => _x( 'Soort statiegeld', 'taxonomy singular name', $this->woo_statiegeld ),
            'menu_name' => _x( 'Statiegeld', 'admin menu', $this->woo_statiegeld ),
            'name_admin_bar' => _x( 'Nieuwe soort statiegeld toevoegen', 'add new on admin bar', $this->woo_statiegeld ),
            'add_new_item' => _x( 'Nieuwe soort statiegeld toevoegen', 'add new on admin menu', $this->woo_statiegeld ),
            'search_items' => __( 'Zoek soorten statiegeld', $this->woo_statiegeld ),
            'popular_items' => __( 'Populaire soorten statiegeld', $this->woo_statiegeld ),
            'all_items' => __( 'Alle soorten statiegeld', $this->woo_statiegeld ),
            'parent_item' => __( 'Bovenliggende soort Statiegeld', $this->woo_statiegeld ),
            'parent_item_colon' => __( 'Bovenliggende soort Statiegeld:', $this->woo_statiegeld ),
            'edit_item' => __( 'Wijzig soort statiegeld', $this->woo_statiegeld ),
            'update_item' => __( 'Update soort statiegeld', $this->woo_statiegeld ),
            'new_item_name' => __( 'Nieuwe soort statiegeld', $this->woo_statiegeld ),
            'separate_items_with_commas' => __( 'Scheid soorten statiegeld met komma\'s', $this->woo_statiegeld ),
            'add_or_remove_items' => __( 'Soorten Statiegeld toevoegen of verwijderen', $this->woo_statiegeld ),
            'choose_from_most_used' => __( 'Kies uit meest gebruikte soorten statiegeld', $this->woo_statiegeld ),
        );

        $args = array( 
            'labels' => $labels,
            'public' => true,
            'show_in_nav_menus' => true,
            'show_ui' => true,
            'show_tagcloud' => false,
            'show_admin_column' => false,
            'hierarchical' => false,
            'rewrite' => false,
            'query_var' => true
        );

        register_taxonomy( 'product_deptype', array( 'product' ), $args );
    }

    /**
     * Adjust columns in Admin UI
     *
     * @since   1.0.0
     */
    function deptype_columns( $columns ) {
        $new_columns = array(
            'cb' => '<input type="checkbox" />',
            'name' => __( 'Soort statiegeld', $this->woo_statiegeld ),
            'description' => __( 'Beschrijving', $this->woo_statiegeld ),
            'posts' => __( 'Aantal', $this->woo_statiegeld )
        );
        return $new_columns;
    }

}