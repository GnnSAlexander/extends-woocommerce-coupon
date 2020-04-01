<div class="wrap">
    <h1>Actualizar cupon</h1>
    
    <div class="wrap">
        <div class="ec-product container-md">
            <div class="form-row">
                <?php if( !empty($products) ): ?>
                    <?php foreach( $products as $product ): ?>
                        <div class="form-row product<?php echo $product->product_id; ?>">
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
                                <button onclick="update( document.querySelector('.product<?php echo $product->product_id;?>') );" class="save btn btn-primary">Update</button>
                            </div>
                        </div>
                    <?php endforeach;?>
                    <?php wp_nonce_field("extends_coupon_{$form['action']}", 'extends_coupon'); ?>
                    <input type="hidden" name="action" value="<?php echo $form['action']; ?>">
                    <input type="hidden" name="coupon_id" value="<?php echo $coupon_id; ?>">
                <?php else: ?>
                    No se has extendido un cupon correctamente!!
                <?php endif;?>
            </div>
        </div>  
    </div>
</div>

<script>
async function update( parent )
         {
            console.log(parent)
            const formData = new FormData()
            formData.append('coupon_id', document.querySelector("input[name='coupon_id']").value)
            formData.append('product_id', parent.querySelector("input[name='product_id']").value)
            formData.append('product_name', parent.querySelector("input[name='product_name']").value)
            formData.append('discount_type', parent.querySelector("input[name='discount_type']").value)
            formData.append('coupon_amount', parent.querySelector("input[name='coupon_amount']").value)
            formData.append('extends_coupon', document.querySelector("input[name='extends_coupon']").value)
            formData.append('action', document.querySelector("input[name='action']").value)

            const response =  await fetch("/wp-admin/admin-post.php", {
                method: 'POST', // or 'PUT'
                body: formData
            })

            if( response.status == 200 ){
                const data =  await response.json()
                if(data.status == "OK") {
                   alert("se update");
                   //window.location.href = "/wp-admin/admin.php?page=extends-coupon&action=create"
                }
                    
            }else{
                error = await response.json()
                console.log(error)
                alert(JSON.stringify(error.error))
            }
            
         }
</script>