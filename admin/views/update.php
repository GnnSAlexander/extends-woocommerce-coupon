<div class="wrap">
    <h1>Actualizar cupon</h1>
    
    <div class="wrap">
        <div class="ec-product container-md">
            <div class="form-row">
                <?php if( !empty($products) ): ?>
                    <?php foreach( $products as $product ): ?>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="product_id">Product</label>
                                <input type="hidden" class="form-control" name="product_id" value="<?php echo $product->product_id; ?>" readonly>
                                <input type="type" class="form-control" name="product_name" value="<?php echo $product->product_name; ?>" readonly>
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
                <?php else: ?>
                    No se has extendido un cupon correctamente!!
                <?php endif;?>
            </div>
        </div>  
    </div>
</div>