<?php

class ModelCoupon
{
    public function init()
    {
        add_action( 'admin_post_save_coupon', array( $this, 'save' ) );
        add_action( 'admin_post_update_coupon', array( $this, 'update' ) );
    }

    public function save()
    {   
        
        if ( !( $this->has_valid_nonce() && current_user_can( 'manage_options' ) ) ) {
            // TODO: Display an error message.

            if(!$this->has_valid_nonce()){
                $error['nonce'] = "es invalido"; 
            }
             return wp_send_json(array("error" => $error), 400);
            
        }

        $error = $this->validate($_POST);
        if( !empty($error) ){
            return wp_send_json(array("error" => $error), 400);
        }

        $item = $this->getCouponToProduct($_POST['coupon_id'], $_POST['product_id'],$_POST['coupon_amount']);
        if( empty($item) )
        {
            //Verificar que consecuencia trae el intentar guardar un cupon registrado
            $result = $this->insertCoupon($_POST['coupon_id'], $_POST['coupon_name']);
            $result = $this->insertCouponToProduct($_POST['coupon_id'], $_POST['product_id'], $_POST['product_name'], $_POST['discount_type'],$_POST['coupon_amount'] );
        }else{
            //echo "test";
            //print_r($wpdb);
            $this->updatetCouponToProduct( $_POST['coupon_id'], $_POST['product_id'], $_POST['product_name'],$_POST['discount_type'],$_POST['coupon_amount'] );
        }
        return wp_send_json(array( "status" => "OK", "message" => "success"));
    }

    public function update()
    {
        if ( !( $this->has_valid_nonce() && current_user_can( 'manage_options' ) ) ) {
            // TODO: Display an error message.

            if(!$this->has_valid_nonce()){
                $error['nonce'] = "es invalido"; 
            }
             return wp_send_json(array("error" => $error), 400);
            
        }

        $error = $this->validate($_POST);
        if( !empty($error) ){
            return wp_send_json(array("error" => $error), 400);
        }

        $this->updatetCouponToProduct( $_POST['coupon_id'], $_POST['product_id'], $_POST['product_name'],$_POST['discount_type'],$_POST['coupon_amount'] );
        return wp_send_json(array( "status" => "OK", "message" => "success"));
    }

    private function validate( $data )
    {
        $error = array();
        if( !is_numeric( $data['coupon_amount']) )
        {
            $error['error_amount'] = "No puede estar vacio, valor debe ser numerico";
        }
        return $error;
    }

    private function has_valid_nonce(){
        if ( ! isset( $_POST['extends_coupon'] ) ) { // Input var okay.
            return false;
        }
        if ( ! isset( $_POST['action'] ) ) { // Input var okay.
            return false;
        }

        $field  = wp_unslash( $_POST['extends_coupon'] );
        $action = "extends_coupon_{$_POST['action']}";
        return wp_verify_nonce( $field, $action );
    }
    //Get all coupons
    public function getCoupons()
    {
        global $wpdb;

        $results = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ec_coupon")
        );
        return $results;
    }
    //Get a coupon with coupon name
    public function getCoupon($name)
    {
        global $wpdb;

        $result = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ec_coupon WHERE post_title='{$name}'")
        );
        return $result;
    }

    //insert a Coupon
    public function insertCoupon( $post_id , $post_title)
    {
        global $wpdb;

        $result = $wpdb->insert("{$wpdb->prefix}ec_coupon",
            array(
                'post_id' => $post_id,
                'post_title' => $post_title
            )
        );
        return $wpdb;
    }
    //Insert a Product
    public function insertCouponToProduct( $post_id , $product_id, $product_name, $discount_type, $coupon_amount)
    {
        global $wpdb;

        $wpdb->insert("{$wpdb->prefix}ec_coupon_to_product",
            array(
                'coupon_id' => $post_id,
                'product_id' => $product_id,
                'product_name' => $product_name,
                'discount_type' => $discount_type,
                'coupon_amount' => $coupon_amount
            )
        );
        return $wpdb;
    }

    //Update a product
    public function updatetCouponToProduct($post_id , $product_id, $product_name, $discount_type, $coupon_amount)
    {
        global $wpdb;

        $where = [ 'coupon_id' => $post_id ];
        $wpdb->update("{$wpdb->prefix}ec_coupon_to_product",
            array(
                'coupon_id' => $post_id,
                'product_id' => $product_id,
                'product_name' => $product_name,
                'discount_type' => $discount_type,
                'coupon_amount' => $coupon_amount
            ),
            $where
        );
    }

    //Get products with coupon_id
    public function getCouponToProductById( $id )
    {
        global $wpdb;
        $sql = "SELECT * FROM {$wpdb->prefix}ec_coupon_to_product WHERE coupon_id = {$id}";
        $results =  $wpdb->get_results(
            $wpdb->prepare( $sql )
        );

        return $results;
    }

    //Find if the code and the product are related
    public function getCouponToProduct($id, $product_id )
    {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}ec_coupon c 
        LEFT JOIN {$wpdb->prefix}ec_coupon_to_product cp ON cp.product_id={$product_id}
        WHERE c.post_id={$id} AND cp.coupon_id ={$id}";
        $result = $wpdb->get_row(
            $wpdb->prepare($sql)
        );
        return $result;
    }


}
?>