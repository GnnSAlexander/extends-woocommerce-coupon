<div class="wrap">
    <h1>Actualizar cupon</h1>
    
    <div class="wrap">
        <div class="ec-product container-md">
            <div class="form-row">
                <?php foreach( $products as $product ): ?>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="product_id">Product</label>
                            <input type="text" class="form-control" name="product_id" value="<?php echo $product->product_id; ?>" readonly>
                        </div>
                        <div class="form-group col">
                            <label for="">Discount type</label>
                            <input type="text"  class="form-control" name="discount_type" value="<?php echo $product->discount_type; ?>" readonly>
                        </div>
                        <div class="form-group col">
                            <label for="">Amount</label>
                            <input type="text" class="form-control" name="coupon_amount" value="<?php echo $product->coupon_amount; ?>">
                        </div>
                        <div class="form-inline">
                            <button class="save btn btn-primary">Update</button>
                        </div>
                    </div>
                <?php endforeach;?>
            </div>
        </div>  
    </div>
</div>