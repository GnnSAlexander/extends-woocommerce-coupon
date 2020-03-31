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
            <input type="type" class="coupon_name" name="coupon_name" value="">
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
                    const coupon_name = document.querySelector('.coupon_name')
                    coupon_name.value = data['code']
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
            if(products){
                products.forEach(element => {

                    const col_parent = divElement("form-row")
                    const col_1 = divElement( "form-group col" )
                    const col_2 = divElement( "form-group col" )
                    const col_3 = divElement( "form-group col" )
                    const col_4 = divElement( "form-inline" )

                    col_1.append(
                        labelElement( 'Product' ) ,
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

                    col_4.append( buttonElement( "Guardar", "save btn btn-primary" ) )

                    col_parent.append( 
                        col_1, 
                        col_2, 
                        col_3,
                        col_4
                        ) 
                    ec_product.append( col_parent )
                    });

                    const buttons =  document.querySelectorAll(".save")
                    buttons.forEach(button => {
                        button.addEventListener("click", function(event){
                            event.preventDefault()
                            const parent = button.parentElement.parentElement
                            save( parent )
                        })
                    })
                    
            }else{
                const h1 = document.createElement("h1")
                h1.innerText = "No hay productos relacionado al cupon."
                ec_product.append( h1 )
            }
            
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

         async function save( parent )
         {

            const formData = new FormData()
            formData.append('coupon_id', document.querySelector("#coupon").value)
            formData.append('coupon_name', document.querySelector("input[name='coupon_name']").value)
            formData.append('product_id', parent.querySelector("input[name='product_id']").value)
            formData.append('product_name', parent.querySelector("input[name='product_name']").value)
            formData.append('discount_type', parent.querySelector("input[name='discount_type']").value)
            formData.append('coupon_amount', parent.querySelector("input[name='coupon_amount']").value)
            formData.append('extends_coupon', document.querySelector("input[name='extends_coupon']").value)
            formData.append('action', document.querySelector("input[name='action']").value)

            console.log(formData)
            const response =  await fetch("/wp-admin/admin-post.php", {
                method: 'POST', // or 'PUT'
                body: formData
            })

            if( response.status == 200 ){
                const data =  await response.json()
                if(data.status == "OK") {
                   alert("se extendio el cupon");
                   //window.location.href = "/wp-admin/admin.php?page=extends-coupon&action=create"
                }
                    
            }else{
                error = await response.json()
                alert(error['message'])
            }
            
         }


     </script>
</div>