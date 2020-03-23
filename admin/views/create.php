<div class="wrap">
    <h1>Crear Extension de un cupon </h1>
    
     <div class="wrap">
        <form method="POST" action="<?php echo esc_html( admin_url( 'admin-post.php' ) ); ?>" class="container-md">
        <input type="hidden" name="action" value="<?php echo $form['action']; ?>">
        <?php wp_nonce_field("extends_coupon_{$form['action']}", 'extends_coupon'); ?>
        <div class="form-group">
            <label for="coupon">Coupon</label>
            <select name="coupon_id" id="coupon" class="form-control" onchange="getCoupon()">
                <option value="null"> ---- </option>
                <?php foreach( $coupons as $coupon ): ?>
                    <option value="<?php echo $coupon->ID; ?>"><?php echo $coupon->post_title; ?></option>
                <?php endforeach;?>
            </select>
        </div>
            <div class="ec-product form-row">

            </div>
        </form>
     </div>

     <script>
         
       async function getCoupon()
         {
            const ec_product = document.querySelector('.ec-product')
            ec_product.innerHTML = ""
            const id = document.getElementById('coupon').value
            const response = await fetch("/wp-json/extends_coupon/getCoupon?id="+id)
            if( response.status == 200 ){
                const data =  await response.json()
                if(data.length != 0) {
                    console.log(data)
                    renderProduct(data)
                }
                    
            }else{
                error = await response.json()
                alert(error['message'])
            }
        
         }

         async function getProducts( data )
         {
            const product_ids = JSON.stringify(data)
            const response = await fetch("/wp-json/extends_coupon/getProductIDs?product_ids="+product_ids)
            if( response.status == 200 ){
                const data =  await response.json()
                if(data.length != 0) {
                    return data
                    //renderProduct(data)
                }
                    
            }else{
                error = await response.json()
                alert(error['message'])
            }
         }

         //
         async function renderProduct( data )
         {
            const ec_product = document.querySelector('.ec-product')

            const products = await getProducts(data.product_ids)
            products.forEach(element => {

                const col_parent = divElement("form-row")
                const col_1 = divElement( "form-group col" )
                const col_2 = divElement( "form-group col" )
                const col_3 = divElement( "form-group col" )
                const col_4 = divElement( "form-inline" )

                col_1.append(
                    labelElement( 'product' ) ,
                    inputElement( 'hidden', 'product_id' , element['id'], 'form-control'),
                    inputElement( 'text', 'product_name' , element['name'], 'form-control', true)
                )

                col_2.append( 
                    labelElement( 'Discount type' ),
                    inputElement( 'text', 'discount_type' , data['discount_type'], 'form-control', true) 
                )
                col_3.append( 
                    labelElement( 'Amount' ),
                    inputElement( 'text', 'coupon_amount' , data['amount'], 'form-control')
                )

                col_4.append( buttonElement( "Guardar", "btn btn-primary" ) )
                
                col_parent.append( 
                    col_1, 
                    col_2, 
                    col_3,
                    col_4
                    ) 
                ec_product.append( col_parent )
            });
            
         }
         //Create a label Element
         function labelElement( name )
         {
             const label = document.createElement("label")
             label.innerHTML = name
             return label
         }
        //Create a div
         function divElement( className )
         {
            const div = document.createElement("div")
            div.className = className
            
            return div
         }
         //Create a Input element
         function inputElement( type, name, value, className, redadOnly = false )
         {
                const input = document.createElement("input")
                input.name = name
                input.readOnly = redadOnly
                input.value = value
                input.type = type
                input.className = className
                return input
         }

         function buttonElement( name, className )
         {
            const button = document.createElement("button")
            button.className =  className
            button.innerHTML = name
            return button
         }

         function save()
         {

         }


     </script>
</div>