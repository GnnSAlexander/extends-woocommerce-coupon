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
        global $wpdb;
        $error = $this->validate($_POST);
        if ( ! ( $this->has_valid_nonce() && current_user_can( 'manage_options' ) ) && !empty($error) ) {
            // TODO: Display an error message.
             return wp_send_json($error);
            
        }

        $item = $this->getCouponToProduct($_POST['coupon_id'], $_POST['product_id'],$_POST['coupon_amount']);
        if( empty($item) )
        {
            //Verificar que consecuencia trae el intentar guardar un cupon registrado
            $result = $this->insertCoupon($_POST['coupon_id'], $_POST['coupon_name']);
            $result = $this->insertCouponToProduct($_POST['coupon_id'], $_POST['product_id'],$_POST['discount_type'],$_POST['coupon_amount'] );
        }else{
            //echo "test";
            //print_r($wpdb);
            $this->updatetCouponToProduct( $_POST['coupon_id'], $_POST['product_id'],$_POST['discount_type'],$_POST['coupon_amount'] );
        }
        return wp_send_json($_POST);
    }

    //private function get

    private function validate( $data )
    {
        $error = array();
        if( !is_numeric($data['coupon_amount']) )
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
        $action = "extends_coupon_q{$_POST['action']}";
 
        return wp_verify_nonce( $field, $action );
    }

    public function getCoupons()
    {
        global $wpdb;

        $results = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ec_coupon")
        );
        return $results;
    }

    public function getCoupon($name)
    {
        global $wpdb;

        $result = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}ec_coupon WHERE post_title='{$name}'")
        );
        return $result;
    }

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

    public function insertCouponToProduct( $post_id , $product_id, $discount_type, $coupon_amount)
    {
        global $wpdb;

        $wpdb->insert("{$wpdb->prefix}ec_coupon_to_product",
            array(
                'coupon_id' => $post_id,
                'product_id' => $product_id,
                'discount_type' => $discount_type,
                'coupon_amount' => $coupon_amount
            )
        );
        return $wpdb;
    }

    public function updatetCouponToProduct($post_id , $product_id, $discount_type, $coupon_amount)
    {
        global $wpdb;

        $where = [ 'coupon_id' => $post_id ];
        $wpdb->update("{$wpdb->prefix}ec_coupon_to_product",
            array(
                'coupon_id' => $post_id,
                'product_id' => $product_id,
                'discount_type' => $discount_type,
                'coupon_amount' => $coupon_amount
            ),
            $where
        );
    }

    public function getCouponToProduct($id, $product_id )
    {
        global $wpdb;

        //If exists $amount
        $sql = "SELECT * FROM {$wpdb->prefix}ec_coupon c 
        LEFT JOIN {$wpdb->prefix}ec_coupon_to_product cp ON cp.product_id={$product_id}
        WHERE c.post_id={$id} AND cp.coupon_id ={$id}";
        //$sql .= ( $amount != null ) ? "AND coupon_amount={$amount}" : "";
        $result = $wpdb->get_row(
            $wpdb->prepare($sql)
        );
        return $result;
    }


}
?>