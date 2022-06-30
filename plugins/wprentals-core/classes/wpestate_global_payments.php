<?php
/* Copyright (C) Wpestate/Sc Intenet Viboo SRL, Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by wpestate.org, March 2019
 */


class Wpestate_Global_Payments {

    public $stripe_payments;
    public $is_woo;
    public $userID;
    public $user_email;

    function __construct() {

        $this->is_woo   =   wprentals_get_option('wp_estate_enable_woo','') ;
        $current_user   =   wp_get_current_user();

        $this->userID                  =    $current_user->ID;
        $this->user_email              =    $current_user->user_email;

        if(  $this->is_woo =='yes' ) {
            add_filter( 'woocommerce_cart_item_permalink','__return_false');
            add_action( 'wp_ajax_wpestate_woo_pay',                 array($this, 'wpestate_woo_pay') );
            add_action( 'wp_ajax_mopriv_wpestate_woo_pay',          array($this, 'wpestate_woo_pay') );
            add_filter( 'woocommerce_thankyou_order_received_text', array($this, 'wpestate_woocommerce_thankyou_order_received_text'),10,2 );
            add_action( 'woocommerce_before_single_product',        array($this, 'wpestate_product_redirect') );
            add_action( 'woocommerce_product_query',                array($this, 'wpestate_custom_pre_get_posts_query' ));
            add_action( 'woocommerce_order_status_completed',       array($this, 'wpestate_payment_complete') );
            add_action( 'woocommerce_order_status_processing',      array($this, 'wpestate_payment_complete_for_processing') );
            add_action( 'woocommerce_thankyou',                     array($this, 'order_attach') );
            add_filter( 'woocommerce_checkout_fields' ,             array($this, 'custom_override_checkout_fields') );
            add_filter( 'woocommerce_create_account_default_checked', '__return_true');
            add_filter( 'woocommerce_checkout_get_value',array($this,'wpestate_checkout_get_value'),10,2);
            add_action('woocommerce_created_customer'   ,array($this,'wpestate_woocommerce_created_customer'), 10 , 1);
        }



        require_once WPESTATE_PLUGIN_PATH.'classes/wpestate_stripe_payments.php';
        $this->stripe_payments=new Wpestate_stripe_payments();

    }

    
    
    function wpestate_woocommerce_created_customer( $customer_id) {
        update_user_meta($customer_id, 'user_type', 1);
    }
    

    function wpestate_checkout_get_value($input,$key){
        global $current_user;
        switch ($key) :
            case 'billing_first_name':
            case 'shipping_first_name':
                return $current_user->first_name;
            break;

            case 'billing_last_name':
            case 'shipping_last_name':
                return $current_user->last_name;
            break;
            case 'billing_email':
                return $current_user->user_email;
            break;
            case 'billing_phone':
                return $current_user->phone;
            break;
        endswitch;

    }

    /**
    * woo checkout
    *
    *
    * @since    2.7
    * @access   private
    */

    function custom_override_checkout_fields( $fields ) {

        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['billing']['billing_address_2']);
        unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_postcode']);
        unset($fields['billing']['billing_country']);
        unset($fields['billing']['billing_state']);

         return $fields;
    }


    /**
    * order received &user non registred
    *
    *
    * @since    2.7
    * @access   private
    */

    function order_attach( $order_id ) {
        $current_user   =   wp_get_current_user();
        $user_id        =   $current_user->ID;

        $order      =   new WC_Order($order_id);
        $products   =   $order->get_items();

        foreach($products as $prod){
            $product_id         =   $prod['product_id'];

            $wpestate_propid    =  intval(  get_post_meta( $product_id, '_prop_id', true) );
            $bookid             =  intval(  get_post_meta( $product_id, '_booking_id', true) );
            $invoice_no         =  intval( get_post_meta( $product_id, '_invoice_id', true) );
            $depozit            =  floatval ( get_post_meta( $product_id, '_price', true) );


            $arg = array(
                'ID' => $bookid,
                'post_author' => $user_id,
            );
            wp_update_post( $arg );

            update_post_meta($invoice_no, 'buyer_id',    $order->get_user_id());

            $owner_id          =   wpsestate_get_author($wpestate_propid);
            $receiver          =   get_userdata($user_id);
            $receiver_email    =   $receiver->user_email;
            $receiver_login    =   $receiver->user_login;
            $from              =   $owner_id;
            $to                =   $user_id;
            $subject           =   esc_html__( 'New Invoice','wprentals');
            $description       =   esc_html__( 'A new invoice was generated for your booking request','wprentals');
            wpestate_add_to_inbox($user_id,$user_id,$to,$subject,$description,1);
          //  wpestate_send_booking_email('newinvoice',$receiver_email);
        }

    }






    /**
    * order received txt
    *
    *
    * @since    2.7
    * @access   private
    */


    function wpestate_woocommerce_thankyou_order_received_text ( $thank_you_msg,$order_id ) {

        $order = wc_get_order( $order_id );
        $products   =   $order->get_items();

        foreach($products as $prod){
            $product_id         =   $prod['product_id'];
            $product_bought     =   wc_get_product( $product_id );
            $is_submit          =   get_post_meta( $product_id, '_is_submit', true );
            $listing_id         =   get_post_meta( $product_id, '_prop_id', true );

            if($is_submit==1){
                $url= wpestate_get_template_link('user_dashboard.php') ;
                $thank_you_msg.='</br><a href="'.$url.'" class="return_woo_button  " >'.esc_html__('Return to Dashboard','wprentals-core').'</a>';

            }else{
                $url = wpestate_get_template_link('user_dashboard_my_reservations.php');
                $thank_you_msg.='</br><a href="'.$url.'" class="return_woo_button  " >'.esc_html__('Return to My Reservations','wprentals-core').'</a>';
            }
            
            $post = array( 'ID' => $product_id, 'post_status' => 'draft' );
            wp_update_post($post);

        }



    return $thank_you_msg;
}

    /**
    * woo pre query
    *
    *
    * @since    2.7
    * @access   private
    */

    function wpestate_custom_pre_get_posts_query($q){
        $meta_query = (array) $q->get( 'meta_query' );
        $meta_query[]=array(
                'meta_key'      => '_prop_id',
                'meta_compare'  => 'NOT EXISTS',
            'value' => ''
               );
        $q->set( 'meta_query', $meta_query );


    }



     /**
    * woo rodduct except
    *
    *
    * @since    2.7
    * @access   private
    */

    function wpestate_product_redirect(){
        $product_id =   get_the_ID();
        $propid     =   get_post_meta( $product_id, '_prop_id',true);

        if(intval($propid)!==0){
            wp_redirect( home_url(), 301 );
          exit;
        }

    }


    /**
    * woo show_cart thank you page
    *
    *
    * @since    2.7
    * @access   private
    */
    function thankyou_redirect($order_id ){
        $order = wc_get_order( $order_id );
        $products   =   $order->get_items();

        foreach($products as $prod){
            $product_id         =   $prod['product_id'];
            $product_bought     =   wc_get_product( $product_id );
            $is_submit          =   get_post_meta( $product_id, '_is_submit', true );
            $listing_id         =   get_post_meta( $product_id, '_prop_id', true );

            if($is_submit==1){
                $url=
                wp_safe_redirect( wpestate_get_template_link('user_dashboard.php') );
                exit;
            }else{
                wp_safe_redirect(  wpestate_get_template_link('user_dashboard_my_reservations.php') );
                exit;
            }

        }

    }


     /**
    * woo show cart icon
    *
    *
    * @since    2.7
    * @access   private
    */
    function show_cart_icon_mobile(){
        if( $this->is_woo=="no"){
            return;
        }
        print '<li id="shopping-cart-mobile" class="wpestate_header_shoping_cart_icon_mobile">
        <a href="'.wc_get_cart_url().'">'.esc_html__('Your Cart','wprentals-core').'<span class="wpestream_cart_counter_header_mobile">'.WC()->cart->get_cart_contents_count().'</span></a>';
        print '</li>';
    }


     /**
    * woo show cart icon
    *
    *
    * @since    2.7
    * @access   private
    */
    function show_cart_icon(){
        if( $this->is_woo=="no"){
            return;
        }
        print '<div id="shopping-cart" class="wpestate_header_shoping_cart_icon">
        <svg id="shopping-cart_icon" width="23" height="21" viewBox="0 0 23 21" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M18.5444 21H4.56587C4.11918 21.0009 3.68506 20.8508 3.33278 20.5738C2.98049 20.2968 2.73033 19.9087 2.62221 19.4715L0.0518138 9.06855C-0.0161936 8.77314 -0.017268 8.46605 0.0486706 8.17016C0.114609 7.87427 0.245863 7.59719 0.43266 7.35954C0.619457 7.1219 0.856988 6.92982 1.12757 6.79759C1.39815 6.66537 1.69481 6.59641 1.99547 6.59584H21.1148C21.4188 6.59524 21.719 6.66461 21.9925 6.79866C22.266 6.93272 22.5055 7.12793 22.6929 7.36945C22.8804 7.61096 23.0107 7.89242 23.074 8.1924C23.1374 8.49238 23.132 8.80298 23.0584 9.10056L20.488 19.5035C20.3739 19.9348 20.1212 20.3156 19.7694 20.5864C19.4177 20.8572 18.9868 21.0027 18.5444 21V21ZM1.99547 8.1963C1.93208 8.1955 1.86936 8.20945 1.81217 8.23706C1.75499 8.26467 1.70488 8.30521 1.66575 8.35552C1.62661 8.40584 1.5995 8.46457 1.58651 8.52717C1.57353 8.58976 1.57502 8.65453 1.59088 8.71645L4.16127 19.1194C4.18282 19.2111 4.23458 19.2927 4.30808 19.3508C4.38158 19.409 4.47246 19.4403 4.56587 19.4395H18.5444C18.6353 19.4389 18.7236 19.408 18.7953 19.3515C18.8671 19.2951 18.9183 19.2163 18.941 19.1274L21.5114 8.72445C21.5273 8.66254 21.5288 8.59776 21.5158 8.53517C21.5028 8.47257 21.4757 8.41384 21.4365 8.36353C21.3974 8.31321 21.3473 8.27268 21.2901 8.24506C21.2329 8.21745 21.1702 8.2035 21.1068 8.20431L1.99547 8.1963Z" fill="black"/>
        <path d="M7.34949 10.9391C7.2432 10.5104 6.81245 10.2497 6.3874 10.3569C5.96234 10.4642 5.70394 10.8986 5.81023 11.3274L7.12859 16.6452C7.23488 17.074 7.66563 17.3346 8.09068 17.2274C8.51574 17.1202 8.77415 16.6857 8.66785 16.2569L7.34949 10.9391Z" fill="black"/>
        <path d="M15.7647 10.9418L14.4454 16.2594C14.3391 16.6881 14.5974 17.1226 15.0225 17.2299C15.4475 17.3372 15.8783 17.0766 15.9846 16.6479L17.3039 11.3303C17.4103 10.9016 17.152 10.4671 16.7269 10.3598C16.3019 10.2525 15.8711 10.5131 15.7647 10.9418Z" fill="black"/>
        <path d="M5.29573 7.88422L3.93913 7.08399L7.90579 0.442086C8.00504 0.255689 8.17344 0.116528 8.37415 0.0550426C8.57485 -0.00644271 8.79153 0.0147524 8.97679 0.113992C9.15637 0.22357 9.2856 0.400468 9.33615 0.605949C9.38671 0.81143 9.35448 1.02875 9.24652 1.21031L5.29573 7.88422Z" fill="black"/>
        <path d="M17.8145 7.88421L13.8478 1.2423C13.79 1.15019 13.7514 1.04711 13.7344 0.939408C13.7175 0.831702 13.7225 0.721639 13.7493 0.615975C13.776 0.51031 13.8239 0.411275 13.89 0.32495C13.956 0.238624 14.0389 0.166831 14.1334 0.113976C14.3134 0.00507863 14.5289 -0.0274323 14.7326 0.0235629C14.9363 0.0745582 15.1117 0.204903 15.2203 0.386054L19.187 7.02796L17.8145 7.88421Z" fill="black"/>
        </svg>
        <span class="wpestream_cart_counter_header">'.WC()->cart->get_cart_contents_count().'</span></div>';

    }

     /**
    * woo show cart
    *
    *
    * @since    2.7
    * @access   private
    */

    function show_cart(){
        $return_string='';
        print '<div class="wpestate_header_shoping_cart" id="wpestate_header_shoping_cart">';
        print '<div class="wpestate_header_shoping_cart_container ">';
            $cart_content =  WC()->cart->get_cart_contents();
            foreach ($cart_content as $key => $product) {
                $product_id =   $product['product_id'];
                $quantity   =   $product['quantity'];
                $price      =   $product['line_total'];
                $product    =   wc_get_product( $product_id );
                $link       =   get_permalink($product_id);
                $title      =   get_the_title($product_id);

                $wpestate_propid  =    get_post_meta( $product_id, '_prop_id', 'true' );
                $thumb            =   wp_get_attachment_image_src(get_post_thumbnail_id($wpestate_propid),'wpestate_user_thumb');
                $return_string .=   '<div class="wpestate_in_cart_item">';
                $return_string .=   '<div class="wpestate_in_cart_image"><a href="'.$link.'" target="_blank"><img src="'.$thumb[0].'" alt="'.$title.'"/></a></div>';
                $return_string .=   '<div class="wpestate_in_cart_title"><a href="'.$link.'" target="_blank">'.$title.'</a></div>';
                $return_string .=   '<div class="wpestate_in_cart_price">'.esc_html__('Price','wprentals-core').': '.wc_price($price).'</div>';
               // $return_string .=   '<div class="wpestate_in_cart_remove" data-productid="'.$product_id.'"><i class="far fa-times-circle"></i></div>';
                $return_string .=   '</div>';

        }

        $return_string .=  '<div class="wpestate_header_shoping_cart_total">'.esc_html__('Total:','wprentals-core').' '. WC()->cart->get_cart_total().'</div>';

        $return_string .=   '<a class="wpestate_header_view_cart " href="'.wc_get_cart_url().'">'.esc_html__('View Cart','wprentals-core').'</a>';
        $return_string .=   '<a class="wpestate_header_view_checkout" href="'.wc_get_checkout_url().'">'.esc_html__('Checkout','wprentals-core').'</a>';
        print $return_string;
        print '</div>';
        print'</div>';
    }



    /**
    * woo
    *
    *
    * @since    2.7
    * @access   private
    */

    function wpestate_payment_complete_for_processing($order_id){
        $order      =   wc_get_order( $order_id );
        $user       =   $order->get_user();
        $user_id    =   $order->get_user_id();
        $products   =   $order->get_items();
        $user_data  =   get_userdata($user_id);
        foreach($products as $prod){
            $product_id         =   $prod['product_id'];
            $product_bought     =   wc_get_product( $product_id );
            $is_submit          =   intval( get_post_meta( $product_id, '_is_submit', true ) );
            $listing_id         =   intval( get_post_meta( $product_id, '_prop_id', true ) );

            if($is_submit==1){
                $is_upgrade         =   intval(  get_post_meta( $product_id, '_is_featured', true) );
                $listing_id         =   intval(  get_post_meta( $product_id, '_prop_id', true) );
                $this->wpestate_process_payment_submission($listing_id, $is_upgrade,$is_featured);
            }else{
                $wpestate_propid    =  intval(  get_post_meta( $product_id, '_prop_id', true) );
                $bookid             =  intval(  get_post_meta( $product_id, '_booking_id', true) );
                $invoice_no         =  intval( get_post_meta( $product_id, '_invoice_id', true) );
                $depozit            =  floatval ( get_post_meta( $product_id, '_price', true) );
                wpestate_booking_mark_confirmed($bookid,$invoice_no, $this->userID,$depozit,$user_data->user_email ,0);

            }


        }

    }

    function wpestate_payment_complete($order_id){
        $order      =   wc_get_order( $order_id );
        $user       =   $order->get_user();
        $user_id    =   $order->get_user_id();
        $products   =   $order->get_items();
        $user_data  =   get_userdata($user_id);
        foreach($products as $prod){
            $product_id         =   $prod['product_id'];
            $product_bought     =   wc_get_product( $product_id );
            $is_submit          =   intval( get_post_meta( $product_id, '_is_submit', true ) );
            $listing_id         =   intval( get_post_meta( $product_id, '_prop_id', true ) );

            if($is_submit==1){
                $is_upgrade         =   intval(  get_post_meta( $product_id, '_is_featured', true) );
                $listing_id         =   intval(  get_post_meta( $product_id, '_prop_id', true) );
                $this->wpestate_process_payment_submission($listing_id, $is_upgrade,$is_featured);
            }else{
                $wpestate_propid    =  intval(  get_post_meta( $product_id, '_prop_id', true) );
                $bookid             =  intval(  get_post_meta( $product_id, '_booking_id', true) );
                $invoice_no         =  intval( get_post_meta( $product_id, '_invoice_id', true) );
                $depozit            =  floatval ( get_post_meta( $product_id, '_price', true) );
                wpestate_booking_mark_confirmed($bookid,$invoice_no, $this->userID,$depozit,$user_data->user_email ,0);

            }


        }

    }



     /**
    * woo
    *
    *
    * @since    2.7
    * @access   private
    */

    function wpestate_process_payment_submission($listing_id, $is_upgrade,$is_featured){

            $time = time();
            $date = date('Y-m-d H:i:s',$time);

            if($is_upgrade==1){
                update_post_meta($listing_id, 'prop_featured', 1);
                $invoice_id = wpestate_insert_invoice('Upgrade to Featured','One Time',$listing_id,$date, $this->userID,0,1,'' );
                update_post_meta($invoice_id, 'invoice_status', 'confirmed');
                wpestate_email_to_admin(1);
            }else{
                update_post_meta($listing_id, 'pay_status', 'paid');
                $admin_submission_status = esc_html ( wprentals_get_option('wp_estate_admin_submission','') );
                $paid_submission_status  = esc_html ( wprentals_get_option('wp_estate_paid_submission','') );

                if($admin_submission_status=='no'  && $paid_submission_status=='per listing' ){
                    $post = array(
                        'ID'            => $listing_id,
                        'post_status'   => 'publish'
                        );
                    $post_id =  wp_update_post($post );
                }


                $invoice_id = wpestate_insert_invoice('Listing','One Time',$listing_id,$date,$this->userID,0,0,'' );
                update_post_meta($invoice_id, 'invoice_status', 'confirmed');

                wpestate_email_to_admin(0);
            }
    }






     /**
    * woo show pay button
    *
    *
    * @since    2.7
    * @access   private
    */

    function show_button_pay($property_id,$bookid,$invoice_id,$depozit,$type){
        if(floatval($type)==2){
            update_post_meta($property_id,'woo_pay_submit_depozit',$depozit);
            print '<div class="woo_pay_submit"  data-is_featured="0" data-propid="'.esc_attr($property_id).'"  data-deposit="'.esc_attr($depozit).'">'.esc_html__('Pay Now','wprentals-core').'</div>';
        } else if(floatval($type)==3){
            update_post_meta($property_id,'woo_pay_submit_depozit',$depozit);
            print '<div class="woo_pay_submit" data-is_featured="1"  data-propid="'.esc_attr($property_id).'"  data-deposit="'.esc_attr($depozit).'">'.esc_html__('Upgrade to Featured','wprentals-core').'</div>';
        }else{
            update_post_meta(intval($invoice_id),'woo_pay_depozit',$depozit);
            print '<div class="woo_pay" data-deposit="'.esc_attr($depozit).'"  data-propid="'.esc_attr($property_id).'" data-bookid="'.esc_attr($bookid).'" data-invoiceid="'.esc_attr($invoice_id).'">'.esc_html__('Pay Now','wprentals-core').'</div>';
        }
    }


     /**
    * woo
    *
    *
    * @since    2.7
    * @access   private
    */

    function wpestate_woo_pay(){
        $current_user       =   wp_get_current_user();
        $userID             =   $current_user->ID;
        $user_email         =   $current_user->user_email ;
        $wpestate_propid    =   floatval($_POST['propid']);
        $invoice_no         =   esc_html($_POST['invoiceid']);
        $bookid             =   floatval($_POST['book_id']);

        $is_submit          =   floatval($_POST['is_submit']);
        $is_featured        =   floatval($_POST['is_featured']);



        if(isset($_POST['invoiceid'])){
            $depozit            =   floatval( get_post_meta(floatval($invoice_no),'woo_pay_depozit',true) );
            $product_id         =   $this->wpestate_fa2_woo($invoice_no);
        }else{
            $depozit            =   floatval ( get_post_meta($wpestate_propid,'woo_pay_submit_depozit',true) );
            if($is_featured==1){
                $product_id         =   $this->wpestate_fa2_woo($wpestate_propid.'f');
            }else{
                $product_id         =   $this->wpestate_fa2_woo($wpestate_propid);
            }
        }



        if( $product_id == 0 ){
           $product_id = $this->wpestate_fa_woo($wpestate_propid,$invoice_no,$bookid,$depozit,$is_submit,$is_featured);
        }
        $cart = WC()->cart->add_to_cart( $product_id, 1, '', [], [ '__booking_data' => '' ] );
        return $cart;
    }



     /**
    * woo add to cart ning log
    *
    *
    * @since    2.7
    * @access   private
    */

    function wpestate_woo_pay_non_logged($wpestate_propid,$invoice_no,$bookid,$depozit){

        $wpestate_propid    =   floatval($wpestate_propid);
        $invoice_no         =   esc_html($invoice_no);
        $bookid             =   floatval($bookid);
        $depozit            =   floatval($depozit);

        $product_id         =   $this->wpestate_fa2_woo($invoice_no);

        if( $product_id == 0 ){
           $product_id = $this->wpestate_fa_woo($wpestate_propid,$invoice_no,$bookid,$depozit,0,0);
        }
        $cart = WC()->cart->add_to_cart( $product_id, 1, '', [], [ '__booking_data' => '' ] );
        return $cart;
    }



     /**
    * woo shw pay button fa
    *
    *
    * @since    2.7
    * @access   private
    */

    function wpestate_fa_woo($wpestate_propid,$invoice_no,$bookid,$depozit,$is_submit,$is_featured){
        if($is_submit==1){
            if($is_featured==1){
                $title= sprintf( esc_html__('Upgrade to "Featured" for Listing "%s" with id %s', 'wprentals-core'), get_the_title($wpestate_propid),$wpestate_propid);
                $invoice_no=$wpestate_propid.'f';
            }else{
                $title= sprintf( esc_html__('Payment for Listing "%s" with id %s', 'wprentals-core'), get_the_title($wpestate_propid),$wpestate_propid);
                $invoice_no=$wpestate_propid;
            }
            $post = array(
                'post_content'   => '',
                'post_status'    => "publish",
                'post_title'     => $title,
                'post_parent'    => '',
                'post_type'      => "product",
                'comment_status' => 'closed'
            );



        }else{
            $booking_from_date  =  wpestate_convert_dateformat_reverse_wordpress(get_post_meta($bookid, 'booking_from_date_unix', true));
            $booking_to_date    =  wpestate_convert_dateformat_reverse_wordpress(get_post_meta($bookid, 'booking_to_date_unix', true));
            $title = sprintf( esc_html__("Payment for Invoice %s , Booking %s, Name: %s, Period: %s ", 'wprentals-core'), floatval($invoice_no) ,$bookid, get_the_title($wpestate_propid), esc_html__( 'from','wprentals-core').' '.esc_html($booking_from_date).' '.esc_html__( 'to','wprentals-core').' '. esc_html($booking_to_date));
            $post = array(
                'post_content'   => '',
                'post_status'    => "publish",
                'post_title'     => $title,
                'post_parent'    => '',
                'post_type'      => "product",
                'comment_status' => 'closed'
            );
        }
        $product_id = wp_insert_post( $post );
        
        wp_set_post_terms($product_id,'product_visibility','exclude-from-catalog');
         wp_set_post_terms($product_id,'product_visibility','exclude-from-search');

        update_post_meta( $product_id, '_stock_status', 'instock' );
        update_post_meta( $product_id, '_visibility', 'visible' );
        update_post_meta( $product_id, '_downloadable', 'no' );
        update_post_meta( $product_id, '_virtual', 'yes' );
        update_post_meta( $product_id, '_featured', 'no' );
        update_post_meta( $product_id, '_sold_individually', 'yes' );
        update_post_meta( $product_id, '_manage_stock', 'no' );
        update_post_meta( $product_id, '_backorders', 'no' );
        update_post_meta( $product_id, '_price', $depozit );
        update_post_meta( $product_id, '_booking_id', $bookid );
        update_post_meta( $product_id, '_invoice_id', $invoice_no );
        update_post_meta( $product_id, '_prop_id', $wpestate_propid );
        update_post_meta( $product_id, '_is_submit', $is_submit );
        update_post_meta( $product_id, '_is_featured', $is_featured );

        $booking_guests     =   floatval(get_post_meta($bookid, 'booking_guests', true));
        update_post_meta( $product_id, '_booking_guests', $booking_guests );

        update_post_meta( $product_id, '_wc_min_qty_product', 1 );
        update_post_meta( $product_id, '_wc_max_qty_product', 1 );
        $data_variation = [
            'types' => [
                'name'         => 'types',
                'value'        => 'service',
                'position'     => 0,
                'is_visible'   => 1,
                'is_variation' => 1,
                'is_taxonomy'  => 1
            ]
        ];
        update_post_meta( $product_id, '_product_attributes', $data_variation );
        update_post_meta( $product_id, '_product_version', '3.0.1' );

        return $product_id;

    }


     function wpestate_fa2_woo($invoice_no){
           $args = [
                'post_type'      => 'product',
                'meta_key'       => '_invoice_id',
                'meta_value'     => $invoice_no,
                'posts_per_page' => 1
            ];


            $query = new WP_Query( $args );


            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    return get_the_ID();
                }
            }
            return 0;
     }




    /**
    * Create a Post Call
    *
    *
    * @since    2.7
    * @access   private
    */
    public function wpestate_make_post_call($url, $postdata,$token) {

        $args=array(
                'method' => 'POST',
                'timeout' => 45,
                'redirection' => 5,
                'httpversion' => '1.0',
                'sslverify' => false,
                'blocking' => true,
                'body' =>  $postdata,
                'headers' => [
                        'Authorization' =>'Bearer '. $this->stripe_secret_key ,
                        'Accept'        =>'application/json',
                        'Content-Type'  =>'application/json'
                ],

        );



        $response = wp_remote_post( $url, $args );


	if ( is_wp_error( $response ) ) {
	    $error_message = $response->get_error_message();
            return $error_message;
            die();
	} else {

            $body = wp_remote_retrieve_body( $response );
            $jsonResponse = json_decode( $body, true );


	}

	return $jsonResponse;
    }





    public function show_checkout() {

        print '<div class="wprentals_sidebar_cart">';
            foreach( WC()->cart->get_cart() as $cart_item ){
                print '<div class="wprentals_sidebar_cart_unit">';
                $product_id         =   $cart_item['product_id'];
                $product            =   wc_get_product( $product_id );
                $wpestate_propid    =   get_post_meta( $product_id, '_prop_id', true );
                $bookid             =   get_post_meta( $product_id, '_booking_id', true );
                $invoice_no         =   get_post_meta( $product_id, '_invoice_id', true );
                $is_submit          =   get_post_meta( $product_id, '_is_submit', true );
                $booking_guests     =   get_post_meta( $product_id, '_booking_guests',true );
                $preview            =   wp_get_attachment_image_src(get_post_thumbnail_id($wpestate_propid), 'wpestate_property_listings');
                $booking_from_date  =  wpestate_convert_dateformat_reverse_wordpress(get_post_meta($bookid, 'booking_from_date_unix', true));
                $booking_to_date    =  wpestate_convert_dateformat_reverse_wordpress(get_post_meta($bookid, 'booking_to_date_unix', true));

                print '<img src="'.$preview[0].'" >';
                print '<h3>'.$product->get_title().'</h3>';

                print '<div class="wpestate_cart_item"><span>'.esc_html__('Price','wprentals-core').':</span> '.wc_price($product->get_price()).'</div>';
                if($is_submit==1){

                }else{
                    print '<div class="wpestate_cart_item"><span>'.esc_html__('Property Name','wprentals-core').':</span> '.get_the_title($wpestate_propid).'</div>';
                    print '<div class="wpestate_cart_item"><span>'.esc_html__('Period','wprentals-core').':</span> '. esc_html__( 'from','wprentals-core').' '.esc_html($booking_from_date).' '.esc_html__( 'to','wprentals-core').' '. esc_html($booking_to_date).'</div>';
                    print '<div class="wpestate_cart_item"><span>'.esc_html__('Invoice no','wprentals-core').':</span> '.floatval($invoice_no).'</div>';
                    print '<div class="wpestate_cart_item"><span>'.esc_html__('Booking no','wprentals-core').':</span> '.$bookid.'</div>';
                    print '<div class="wpestate_cart_item"><span>'.esc_html__('Guests no','wprentals-core').':</span> '.$booking_guests.'</div>';
                }
                print '</div>';
            }


            print '<h4>'.esc_html__('Total','wprentals-core').': '.wc_price(WC()->cart->cart_contents_total).'</h4>';

        print '</div>';
    }




}
