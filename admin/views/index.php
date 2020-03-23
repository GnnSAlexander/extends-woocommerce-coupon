<div class="wrap">
    <h1>Extends Coupon </h1>
     <a href="<?php echo admin_url( "admin.php?page={$form['page']}&action={$form['action']}" ) ?>" class="page-title-action">Extender un Cupon</a>

     <div class="wrap container-md">
        <?php if( !empty($coupons) ): ?>
            <ul class="list-group">
                <?php foreach($coupons as $coupon ): ?>
                <li class="list-group-item" >
                    <div class="row">
                        <div class="col-md-9">
                            <h2>
                                <?php echo $coupon->post_title;?>
                            </h2>
                        </div>
                        
                        <div class="col-md-3 text-right">
                            <button type="button" class="btn btn-primary">Editar</button>
                            <button type="button" class="btn btn-primary">
                                <svg class="bi bi-trash-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.5 1a1 1 0 00-1 1v1a1 1 0 001 1H3v9a2 2 0 002 2h6a2 2 0 002-2V4h.5a1 1 0 001-1V2a1 1 0 00-1-1H10a1 1 0 00-1-1H7a1 1 0 00-1 1H2.5zm3 4a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7a.5.5 0 01.5-.5zM8 5a.5.5 0 01.5.5v7a.5.5 0 01-1 0v-7A.5.5 0 018 5zm3 .5a.5.5 0 00-1 0v7a.5.5 0 001 0v-7z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h1>No hay cupones extendidos</h1>
        <?php endif; ?>
     </div>
</div>