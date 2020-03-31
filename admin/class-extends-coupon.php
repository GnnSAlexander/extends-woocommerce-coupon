<?php

class ExtendsCoupon
{

    const PAGE_TITLE = 'Extends Coupon Page';
    const MENU_TITLE = 'Extends Coupon';
    const MENU_SLUG = 'extends-coupon';
    const SUBMENU_TITLE = 'Coupons';

    private static $db;

    public function __construct( $db )
    {
        self::$db = $db;
    }

    public function index()
    {
        $coupons = self::$db->getCoupons();
        $form = array(
            "create" => "create",
            "update" => "update",
            "page" => self::MENU_SLUG,
        );

        return self::view('views/index.php', compact('coupons','form'));
    }

    public function create()
    {
        
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'asc',
            'post_type'        => 'shop_coupon',
            'post_status'      => 'publish',
        );

        $form = array(
            "action" => "save_coupon",
            "page" => self::MENU_SLUG,
        );
            
        $coupons = get_posts( $args );
        
        return self::view('views/create.php', compact('coupons', 'form' ));
    }

    public function update()
    {
        if( isset($_GET['post_id']) )
        {
            $products = self::$db->getCouponToProductById( $_GET['post_id'] );
        }
        return self::view('views/update.php', compact('products'));
    }

    public function route()
    {
        //I need to change this
        echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">';

        switch( $_GET['action'] )
        {
            case 'create':
                self::create();
                break;
            case 'update':
                self::update();
                break;
            case 'json':
                self::getCoupon($_GET['id']);
                break;
            default:
                self::index();

        }
    }

    public function view( $name , array $args = array() ){
       
        foreach ( $args AS $key => $val ) {
            $$key = $val;
        }

        include_once($name);
    }
     
    function getCoupon( $request ) {
        $params = $request->get_params();
        $data = new WC_Coupon($params['id']);
        return ( $data->code != "null" ) ?  new WP_REST_Response( json_decode($data), 200) : new WP_REST_Response(array(), 200);
    }

    function getProductIDs( $request ) {
        $params = $request->get_params();
        $products = array();
        foreach(json_decode($params['product_ids']) as $id )
        {
            $product = new WC_Product( $id );
            //print($product->id);
            if( $product->id == $id ){
                $products[] = json_decode($product);
            }
        }
        return new WP_REST_Response($products, 200);
        //$data = new WC_Product($params['product_ids']);
        //return ( $data->code != "null" ) ?  new WP_REST_Response( json_decode($data), 200) : new WP_REST_Response(array(), 200);
    }

    function alter_shop_coupon_data( $round, $discounting_amount, $cart_item, $single, $coupon ){
  
        $result = self::$db->getCoupon($coupon->get_code());

        if( $result->post_title == $coupon->get_code()){
            $item = self::$db->getCouponToProduct($result->post_id, $cart_item['product_id']);
            if( isset($item) ){
            
                $discount = (float) $item->coupon_amount * ( $discounting_amount / 100 );
                $round = round( min( $discount, $discounting_amount ), wc_get_rounding_precision() );
            }
            
        }
        //if($coupon->is_type('percent') && ){}
        return $round;
    }

    private function log( $file, $data)
    {
        $log = new WC_Logger();
        $data = (is_array($data)) ? print_r($data, true) : $data;
        $log->add($file ,$data);
    }
}

?>