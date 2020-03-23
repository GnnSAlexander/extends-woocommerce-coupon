<?php


Class ExtendsCouponAdmin{

    private $page;

    public function __construct( $page ){
        $this->page = $page;
    }

    public function init(){
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
        add_action( 'rest_api_init', array($this, 'coupon_endpoint') );
        add_action( 'rest_api_init', array($this, 'products_endpoint') );
        add_filter( 'woocommerce_coupon_get_discount_amount',  array($this,'alter_shop_coupon_data'), 20, 5 );
    }
 
    /**
     * Creates the submenu item and calls on the Submenu Page object to render
     * the actual contents of the page.
     */
    public function add_menu_page() {
 
        add_menu_page(
            $this->page::PAGE_TITLE,
            $this->page::MENU_TITLE,
            'manage_options',
            $this->page::MENU_SLUG,
            array( $this->page, 'route' )
        );

    }

    public function add_submenu_page(){

        add_submenu_page(
            $this->page::MENU_SLUG, 
            $this->page::PAGE_TITLE, 
            $this->page::SUBMENU_TITLE, 
            'manage_options',
            $this->page::MENU_SLUG,
            array( $this->page, 'route')
        );

    }

    public function coupon_endpoint() {
        register_rest_route( 'extends_coupon/', 'getCoupon', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array( $this->page, 'getCoupon'),
        ) );
    }

    public function products_endpoint() {
        register_rest_route( 'extends_coupon/', 'getProductIDs', array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => array( $this->page, 'getProductIDs'),
        ) );
    }

    
    function alter_shop_coupon_data( $round, $discounting_amount, $cart_item, $single, $coupon ){
        $db = new ModelCoupon();
        $log = new WC_Logger();

        $log->add('new-woocommerce-log-name',$coupon->get_code());
        $result = $db->getCoupon($coupon->get_code());

        $log_entry = print_r( $single, true );
        $log->add( 'new-woocommerce-log-name', $log_entry );
        $log->add( 'new-woocommerce-log-name', $cart_item['product_id'] );

        if( $result->post_title == $coupon->get_code()){
            $item = $db->getCouponToProduct($result->id, $cart_item['product_id']);
                
            if( isset($item) ){
                $log_entry = print_r( $item, true );
                $log->add( 'new-woocommerce-log-name', "product: " );
                $log->add( 'new-woocommerce-log-name', $log_entry );
                
                $discount = (float) $item->coupon_amount * ( $discounting_amount / 100 );
                $round = round( min( $discount, $discounting_amount ), wc_get_rounding_precision() );
            }
            
        }
        //if($coupon->is_type('percent') && ){}
        return $round;
    }
    
}
?>