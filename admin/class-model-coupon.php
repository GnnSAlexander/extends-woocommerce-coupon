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
        $error = $this->validate($_POST);
        if ( ! ( $this->has_valid_nonce() && current_user_can( 'manage_options' ) ) && !empty($error) ) {
            // TODO: Display an error message.
             return wp_send_json($error);
            
        }

        $item = $this->getCouponToProduct($_POST['coupon_id'], $_POST['product_id'],$_POST['coupon_amount']);
        if( empty($item) )
        {
            $this->insertCouponToProduct($_POST);
        }else{
            $this->updatetCouponToProduct($_POST);
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

    public function insertCouponToProduct($data)
    {
        global $wpdb;

        $wpdb->insert("{$wpdb->prefix}ec_coupon",
            array(
                'coupon_id' => $data['coupon'],
                'product_id' => $data['id'],
                'discount_type' => $data['discount_type'],
                'coupon_amount' => $data['coupon_amount']
            )
        );
    }

    public function updatetCouponToProduct($data)
    {
        global $wpdb;

        $where = [ 'id' => NULL ];
        $wpdb->update("{$wpdb->prefix}ec_coupon",
            array(
                'coupon_id' => $data['coupon'],
                'product_id' => $data['id'],
                'discount_type' => $data['discount_type'],
                'coupon_amount' => $data['coupon_amount']
            ),
            $where
        );
    }

    public function getCouponToProduct($id, $product_id, $amount =  null )
    {
        global $wpdb;

        //If exists $amount
        $sql = "SELECT * FROM {$wpdb->prefix}ec_coupon_to_product WHERE coupon_id={$id} AND product_id={$product_id} ";
        //$sql .= ( $amount != null ) ? "AND coupon_amount={$amount}" : "";
        $result = $wpdb->get_row(
            $wpdb->prepare($sql)
        );
        print_r($result);
        return $result;
    }


}
?>