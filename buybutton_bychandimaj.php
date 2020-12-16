<?php
//------
//------ SHOPPIFY BUY SHORTCODE
//------
//------ By ChandimaJ

function shopify_button_scripts($atts){
?>
	<div id="product_div_placeholder" bb_added="true"></div>
	<div id="product_div_placeholder2" style="display:none !important"></div>

	<script type="text/javascript">
	console.log(' Dual Product Script...');


	function on_addToCart(ui){ // Calling custom function()
		console.log('pixel...');

		let content_type = "product_group";
		let content_id = parseInt(ui.components.product[0].id);
		let value = parseFloat(ui.components.product[0].selectedVariant.price);
		let num_items = parseInt(ui.components.product[0].selectedQuantity);
		let product_name = ui.components.product[0].handle;

		//If attributes are passed with the shortcode, override...
<?php
		if (isset($atts['content_type'])){ 	
			$content_type = $atts['content_type'];
			print_r ("content_type = '$content_type'; ");	
		}
		if (isset($atts['content_id'])){ 	
			$content_id = $atts['content_id'];
			print_r ("content_id = $content_id; ");
		}
		if (isset($atts['content_name'])){ 	
			$content_name = $atts['content_name'];
			print_r ("content_name = '$content_name'; ");
		}
?>
		fbq('track', 'AddToCart', {
			content_type: 'product_group',
			content_ids: [content_id],
			value: value,
			num_items: num_items,
			content_name: product_name,
			currency: 'EUR',
			});
		}

	// Pixel add to cart


	

	/*<![CDATA[*/  
		/**
		 * Ref:https://github.com/Shopify/buy-button-js/blob/master/docs/assets/scripts/addToCart.js
		 * */
	(function($) { // jQuery  
		function add_letter_product(ui){

			console.log('Adding letter product to cart...');
			let letter_product_index = 999;
			for (i=0; i<ui.components.cart[0].lineItemCache.length; i++){
				if(ui.components.cart[0].lineItemCache[i].title=="Day One Edition - Dankeschön"){
					letter_product_index = i;
					break;
				}
			}
			console.log('letter product index: '+letter_product_index);

			if(letter_product_index==999){
				setTimeout(function(){ 
					$('#product_div_placeholder2 .shopify-buy__btn').unbind('click').trigger('click');
				 }, 4000);
				
			}


		}

	  console.log("BEGIN: Shopify button related scripts by ChandimaJ");
	  

	var configs = {
	  //--
	  //--
	  //------------- SET YOUR BUTTON CONFIGURATION PARAMETERS HERE

		//--- Shopify Store Connect for  (Used in ShopifyBuyInit())
		//---
		domain: 'shop.overdeliver.gg',
		storefrontAccessToken: '7cff65ea0f72ec3176241ebe7dbfa4a2',
		

		
		// DIV Id (Placeholder id) for the product to be created in within the HTML of the page
		// Replace the id. if not the default will be used.
		product_div_id: 'product_div_placeholder',

		//--- Shopify Admin Connect to get product information
		//---
		//--- Get from shopify custom apps page. set permissions only to products with read only access

<?php
		//---
		//--- Product Related Configurations
		//---
		$products_api_url = 'https://3afeff826ad07b1db6216996603e85e8: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@overdeliver-gmbh.myshopify.com/admin/api/2020-10/products';
		//Ex: https://{client id}:{client secret}@overdeliver-gmbh.myshopify.com/admin/api/2020-10/products
	
		$product_id ='5751875076251'; //OVERDELIVER® - Flow State Booster
		if (isset($atts['product_id'])){
			$product_id = $atts['product_id'];
		}
?>
		
		//DO NOT CHANGE
		//Updating Configs object from PHP variables
		product_id:'<?= $product_id ?>', // Do not change
		day_one_purchased:false,
	}

	// Get product quantities for a product
	// 
	//
function ajax_get_product_quantities(configs){
	console.log("--FETCH: Product details from admin/products api endpoint");
		
	var catch_php_errors = `
<?php
//--- POSTMAN PHP CURL FOR PRODUCTS 			  
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "$products_api_url/$product_id.json", // BUILD URL FROM PARAMETERS
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
   // "Authorization: Basic M2FmZWZmODI2YWQwN2IxZGI2MjE2OTk2NjAzZTg1ZTg6c2hwcGFfYzAxMWNhYWY3OTljNDExZDQwZTk5Y2U5OGYzZWIwMjM=",
  ),
));
$response = curl_exec($curl);
?>
	`;
		console.log(catch_php_errors);
		
		var product = <?= $response ?>;
		
		if (product){
			console.log ('----SUCCESS: results fetched for Product:"'+product.title);
		}else{
			console.log ('----FAIL: results fetched failed for Product ID:"'+configs.product_id);
			console.log (product);
		}
		
		var product = product.product;
		
		configs.qty_variant1 = parseInt(product.variants[0].inventory_quantity);
		//configs.qty_variant1 = 0; //Test variant qty
		configs.qty_variant2 = parseInt(product.variants[1].inventory_quantity);
		configs.variant1_id = product.variants[0].id;
		configs.variant2_id = product.variants[1].id;
		
		
		console.log ('------RESULT: '+configs.qty_variant1+' Remains for '+product.variants[0].title);
		console.log ('------RESULT: '+configs.qty_variant2+' Remains for '+product.variants[1].title);
		console.log ('--END: FETCH');
		
		set_variant_id(configs);
	}  

		
		
	//Ser variant id based on quantity
	function set_variant_id(configs){
		console.log ('--BEGIN: setting variant ID');
		//configs.qty_variant1 = 2; //Testing the script
		//configs.qty_variant2 = 3; //Testing the script
		if (configs.qty_variant1 >= 1){
			configs.variant_id = configs.variant1_id;
			configs.variant_qty = configs.qty_variant1;
			console.log('----SELECT: Variant 1 - in stock  ('+configs.qty_variant1+')');
		}else{
			configs.variant_id = configs.variant2_id;
			configs.variant_qty = configs.qty_variant2;
			console.log('----SELECT: Variant 2 - ( Variant 1 is out of stock )');
		}
		console.log ('--END: setting variant ID');
		
		load_shopify_button(configs);
	}
		
		
		
		
	//Custom function to fire on AddToCart Event.
		
		
		
	function load_shopify_button(configs){

	  var buttonTemplate = `
	  <div class=" shopify-buy__quantity-with-btns">

<div class="qtyblurbtn qtydecrement" id="qtyd_decrement">
				<button class="  " type="button">
					<span class=""></span>
					<span>-</span>
					<span class=""></span>
				</button>
</div>


				<div class="great-input">
				  <input class="" id="qtyd_value" type="number" value="1" aria-label="Quantity">
				</div>


<div class="qtyblurbtn qtyincrement" id="qtyd_increment">
				<button class=" gradientbtn blurbtn" type="button">
					<span class=""></span>
					<span>+</span>
					<span class=""></span>
				</button>  
</div>

 <div class=" gradientbtn" style="cursor:'pointer'" id="qtyd_beforeaddtocart" >
		  <div class=" elementor-button" id="qtyd_addtocart">
			<span class="elementor-button-content-wrapper">
							<span class="elementor-button-text"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{data.buttonText}}</font></font></span>
			</span>
		</div>
</div>
			</div>`;

		//Create Component
		var product_options =  {
			events: {
				'beforeUpdateConfig': function(cart) {
					console.log('Upadate cart..');
				}
			},
			
			product: {
				iframe:false,

				contents:{
					options:false,
					quantity:false,
					price:false,
					title:false,
					img:false,
					button:true
				},
				templates:{
					button:buttonTemplate
				},
				DOMEvents: {

					'click #qtyd_decrement':function(){
						let current_qty = parseInt($('#qtyd_value').val());
						let new_qty = 1;
						if (current_qty>1){
							new_qty = current_qty - 1;
						}
						ui.components.product[0].selectedQuantity = new_qty;
						$('#qtyd_value').val(new_qty);
					},

					'click #qtyd_increment':function(){
						let current_qty = parseInt($('#qtyd_value').val());
						let new_qty = current_qty + 1;	
						ui.components.product[0].selectedQuantity = new_qty;
						$('#qtyd_value').val(new_qty);
					},

					'click #qtyd_beforeaddtocart':function(event, target){
						event.stopPropagation();
						let current_qty = parseInt($('#qtyd_value').val());
						console.log(ui);
						on_addToCart(ui); // Calling custom function

						let day_ones = 0;
						let day_id = 2;
						let standard_id = 2;
						let standard_ones = 0;
						let line_items = ui.components.cart[0].lineItemCache.length;

						for (let i=0; i<line_items; i++){
							if( (typeof ui.components.cart[0].lineItemCache[i] !== 'undefined') && (ui.components.cart[0].lineItemCache[i].variant.title == "Standard Edition")){
								standard_ones = ui.components.cart[0].lineItemCache[i].quantity;
								standard_id = i;
							}else if( (typeof ui.components.cart[0].lineItemCache[i] !== 'undefined') && (ui.components.cart[0].lineItemCache[i].variant.title == "Day One Edition")){
								day_ones = ui.components.cart[0].lineItemCache[i].quantity;
								day_id = i;
							}
						}
							
						
						day_ones = parseInt(day_ones);
						standard_ones = parseInt(standard_ones);
						let totals = day_ones + standard_ones;

						console.log('day_ones:'+day_ones+" index: "+day_id);
						console.log('standard_ones:'+standard_ones+" index: "+standard_id);
						console.log('totals: '+totals);

						// No saved cart items && has day one products
						if(  totals==0  &&  ( parseInt(configs.variant1_id) == parseInt(configs.variant_id) )  ){ 
							function xxx(){
									ui.components.product[0].updateVariant('Titel',"Day One Edition");
									ui.components.product[0].selectedQuantity = 1;
									ui.components.cart[0].addVariantToCart(ui.components.product[0].selectedVariant,1);
									add_letter_product(ui);

								setTimeout(function() {   //calls click event after a certain time
									ui.components.product[0].updateVariant('Titel',"Standard Edition");
									let x =current_qty-1;
									ui.components.cart[0].addVariantToCart(ui.components.product[0].selectedVariant,x);
									
									ui.openCart();
								}, 1000);
							}
							xxx();
							
						// No existing cart items and no day one products available
						}else if(totals==0){
							ui.components.cart[0].addVariantToCart(ui.components.product[0].selectedVariant,current_qty);
							ui.openCart();

						// Day one products exists && no standard products
						}else if (day_ones>0 && standard_ones==0){
							//Making sure day one has only one
							ui.components.cart[0].lineItemCache[day_id].quantity = 1;
							//ui.components.cart[0].updateCache(ui.components.cart[0].lineItemCache);
							//ui.components.cart[0].updateCacheItem(ui.components.cart[0].lineItemCache[day_id].id, ui.components.cart[0].lineItemCache[day_id].quantity);
							ui.components.cart[0].updateItem(ui.components.cart[0].lineItemCache[day_id].id, ui.components.cart[0].lineItemCache[day_id].quantity);
							
							//Adding standard variant with updated figures
							let x = current_qty + totals - 1;
							ui.components.product[0].updateVariant('Titel',"Standard Edition");
							ui.components.cart[0].addVariantToCart(ui.components.product[0].selectedVariant,x);
							ui.openCart();

						// Saved cart items, dayones morethan zero and standard ones morethan zero
						}else if(day_ones>0 && standard_ones>0){ 
							ui.components.cart[0].lineItemCache[day_id].quantity = 1;
							ui.components.cart[0].lineItemCache[standard_id].quantity = totals + current_qty -1;
							//ui.components.cart[0].updateCache(ui.components.cart[0].lineItemCache);
							//ui.components.cart[0].updateCacheItem(ui.components.cart[0].lineItemCache[day_id].id, ui.components.cart[0].lineItemCache[day_id].quantity);
							ui.components.cart[0].updateItem(ui.components.cart[0].lineItemCache[day_id].id, ui.components.cart[0].lineItemCache[day_id].quantity);
							//ui.components.cart[0].updateCacheItem(ui.components.cart[0].lineItemCache[standard_id].id, ui.components.cart[0].lineItemCache[standard_id].quantity);
							ui.components.cart[0].updateItem(ui.components.cart[0].lineItemCache[standard_id].id, ui.components.cart[0].lineItemCache[standard_id].quantity);
							ui.openCart();
						// Saved cart items, Only standard ones exists in line
						}else if(standard_ones>0 ){ 
							ui.components.cart[0].lineItemCache[standard_id].quantity = standard_ones + current_qty;
							//ui.components.cart[0].updateCache(ui.components.cart[0].lineItemCache);
							//ui.components.cart[0].updateCacheItem(ui.components.cart[0].lineItemCache[standard_id].id, ui.components.cart[0].lineItemCache[standard_id].quantity);
							ui.components.cart[0].updateItem(ui.components.cart[0].lineItemCache[standard_id].id, ui.components.cart[0].lineItemCache[standard_id].quantity);
							ui.openCart();
						}
						
					}
				},
				events : {
					 addVariantToCart: function (cart,) {
						 console.log('add variant to cart...');
					 }
				}
			} // END Products

		}// Product options			
		
		var ui = window.ui;

		//Initializing the library
		ui.createComponent('product', {
			id: configs.product_id,
			variantId: configs.variant_id,
			moneyFormat: '€ {{amount}} ',
			node: document.getElementById(configs.product_div_id),
			toggles:false,
			cart:false,
			//toggles: [{node: document.getElementById('toggle_shopify_cart')}],
			options: product_options,
		});
		
		ui.createComponent('product', {
				id: '<?= $atts["letter_product_id"] ?>',
				cart: false,
				toggles: false,
				node: document.getElementById("product_div_placeholder2"),
				options:{
					product:{
						iframe: false,
						events : {
					 	addVariantToCart: function (cart) {
							$('.shopify-buy__cart-item__price').each(function(){
								if($(this).text()=="€ 0.00 "){
									$(this).addClass('xxx');
								}
							});
					 	}
					}
				}
				}
			});
		

	}// End of load_shopify_button();

	//load_shopify_button(configs);
	  console.log('END: Shopify Related Scripts by ChandimaJ');
		
		ajax_get_product_quantities(configs);
	})( jQuery );
	/*]]>*/
	</script>
<?php //-----------------------------
}

add_shortcode('shopify-buy', 'shopify_button_scripts');
//------------
//------------ END SHOPIFY BUY SHORTCODE
//------------




































































/**
 * 
 * Shopify General Shortcode
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */
function shopify_general_scripts($atts){
?>
<!-- BuyButton Placeholder Div -->	
<div id='product_div_placeholder' class="shopify-product current" bb_added="true"></div>

<!-- BuyButton Javascripts -->
<script type="text/javascript">
console.log(' Single Product Script...');
/** 
*----------- Pixel addToCart Event Script
*/ 
function on_addToCart(ui){
	console.log('pixel script...');

	let content_type = "product_group";
	let content_id = parseInt(ui.components.product[0].id);
	let value = parseFloat(ui.components.product[0].selectedVariant.price);
	let num_items = parseInt(ui.components.product[0].selectedQuantity);
	let product_name = ui.components.product[0].handle;

<?php
		if (isset($atts['content_type'])){ 	
			$content_type = $atts['content_type'];
			print_r ("content_type = '$content_type'; ");	
		}
		if (isset($atts['content_id'])){ 	
			$content_id = $atts['content_id'];
			print_r ("content_id = $content_id; ");
		}
		if (isset($atts['content_name'])){ 	
			$content_name = $atts['content_name'];
			print_r ("content_name = '$content_name'; ");
		}
?>		
	fbq('track', 'AddToCart', {
		content_type: 'product_group',
		content_ids: [content_id],
		value: value,
		num_items: num_items,
		content_name: product_name,
		currency: 'EUR',
		});
	}
/**
*--------/Pixel
 */

(function($) { // jQuery  
		  console.log("BEGIN: Shopify button related scripts by ChandimaJ");
		  $('#toggle_shopify_cart').html();
		  $('.shopify-buy__cart-toggle').unbind('click');
	
		var configs = {
		  //--
		  //--
		  //------------- SET YOUR BUTTON CONFIGURATION PARAMETERS HERE
	
			//--- Shopify Store Connect for  (Used in ShopifyBuyInit())
			//---
			domain: 'shop.overdeliver.gg',
			storefrontAccessToken: '7cff65ea0f72ec3176241ebe7dbfa4a2',
	
			
			// DIV Id (Placeholder id) for the product to be created in within the HTML of the page
			// Replace the id. if not the default will be used.
			product_div_id: 'product_div_placeholder',
	
			//--- Shopify Admin Connect to get product information
			//---
			//--- Get from shopify custom apps page. set permissions only to products with read only access
	

<?php //##########	PHP	###############
			//---
			//--- Product Related Configurations
			//---
			$products_api_url = 'https://3afeff826ad07b1db6216996603e85e8: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@overdeliver-gmbh.myshopify.com/admin/api/2020-10/products';
			//Ex: https://{client id}:{client secret}@overdeliver-gmbh.myshopify.com/admin/api/2020-10/products
		
			$product_id ='5751875076251'; //OVERDELIVER® - Flow State Booster
			if (isset($atts['product_id'])){
				$product_id = $atts['product_id'];
			}
	////##########	/PHP	###############
?>

			
			//DO NOT CHANGE
			//Updating Configs object from PHP variables
			product_id:'<?= $product_id ?>', // Do not change
			day_one_purchased:false,
		}
load_shopify_button(configs);
	
			
function load_shopify_button(configs){
	
		  var buttonTemplate = `
<div class=" shopify-buy__quantity-with-btns {{data.classes.product.quantity}} {{data.quantityClass}}" data-element="product.quantity">
	
	<div class="qtyblurbtn qtydecrement" id="qtyd_decrement">
					<button class="{{data.classes.product.quantityButton}} {{data.classes.product.quantityDecrement}}" type="button" data-element="product.quantityDecrement">
						<span class=""></span>
						<span>-</span>
						<span class=""></span>
					</button>
	</div>
	
	
					<div class="great-input">
					  <input class="{{data.classes.product.quantityInput}}" id="qtyd_value" type="number" aria-label="Quantity" min="1" value="{{data.selectedQuantity}}" data-element="product.quantityInput">
					</div>
	
	
	<div class="qtyblurbtn qtyincrement" id="qtyd_increment">
					<button class="{{data.classes.product.quantityButton}} {{data.classes.product.quantityIncrement}}" type="button" data-element="product.quantityIncrement" style="padding-left:0 !important">
						<span class=""></span>
						<span>+</span>
						<span class=""></span>
					</button>  
	</div>
	
	 <div class=" gradientbtn {{data.classes.product.buttonWrapper}} buybutton_default" data-element="product.buttonWrapper" style="cursor:'pointer'" id="qtyd_beforeaddtocart" >
			  <div class=" elementor-button {{data.classes.product.button}} {{data.buttonClass}}" id="qtyd_addtocart" data-element="product.button">
				<span class="elementor-button-content-wrapper">
								<span class="elementor-button-text"><font style="vertical-align: inherit;"><font style="vertical-align: inherit;">{{data.buttonText}}</font></font></span>
				</span>
			</div>
	</div>
	
</div>`;
	
	
			//Create Component
			var product_options =  {
				events: {
					'beforeUpdateConfig': function(cart) {
						console.log('Upadate cart..');
					}
				},
				
				product: {
					iframe:false,
	
					contents:{
						options:false,
						quantity:false,
						price:false,
						title:false,
						img:false,
						button:true
					},
					templates:{
						button:buttonTemplate
					},
					events : {
						 addVariantToCart: function (cart) {
							 console.log('add variant to cart...');
							 on_addToCart(ui);
						 }
					}
				}, // END Products
	
			}// Product options
	
						
			
			//Creating shop client
			var client = ShopifyBuy.buildClient({
				domain: configs.domain,
				storefrontAccessToken: configs.storefrontAccessToken, // previously apiKey, now deprecated
			});

			//Initializing the library
			var ui = ShopifyBuy.UI.init(client);
			ui.createComponent('product', {
				id: configs.product_id,
				moneyFormat: '€ {{amount}} ',
				node: document.getElementById(configs.product_div_id),
				toggles: false,
				toggles: [{node: document.getElementById('toggle_shopify_cart')}],
				options: product_options,
			});
			
	
		}// End of load_shopify_button();
	
		//load_shopify_button(configs);
		  console.log('END: Shopify Related Scripts by ChandimaJ');
			
		})( jQuery );
		/*]]>*/
		</script>
	<?php //-----------------------------
	}

	add_shortcode('shopify-default', 'shopify_general_scripts');





















































	/**
 * 
 * Shopify Cart Shortcode
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 * 
 */
function shopify_cart_toggle_scripts($atts){
	?>
	<!-- BuyButton Placeholder Div -->	
	<div id='toggle_shopify_cart' class="shopify-toggle_alone"></div>
	<a id="buybutton_checkout_url" href="#" style="display:none !important">Checkout</a>
	
	<!-- BuyButton Javascripts -->
	<script src="https://sdks.shopifycdn.com/buy-button/1.0.0/buybutton.js"></script>
	<script type="text/javascript">
	console.log(' Single Product Script...');
	/** 
	*----------- Pixel addToCart Event Script
	*/ 
	function on_addToCart(ui){
		console.log('pixel script...');
	
		let content_type = "product_group";
		let content_id = parseInt(ui.components.product[0].id);
		let value = parseFloat(ui.components.product[0].selectedVariant.price);
		let num_items = parseInt(ui.components.product[0].selectedQuantity);
		let product_name = ui.components.product[0].handle;
	
	<?php
			if (isset($atts['content_type'])){ 	
				$content_type = $atts['content_type'];
				print_r ("content_type = '$content_type'; ");	
			}
			if (isset($atts['content_id'])){ 	
				$content_id = $atts['content_id'];
				print_r ("content_id = $content_id; ");
			}
			if (isset($atts['content_name'])){ 	
				$content_name = $atts['content_name'];
				print_r ("content_name = '$content_name'; ");
			}
	?>		
		fbq('track', 'AddToCart', {
			content_type: 'product_group',
			content_ids: [content_id],
			value: value,
			num_items: num_items,
			content_name: product_name,
			currency: 'EUR',
			});
	}
	/**
	*--------/Pixel
	 */
	
	(function($) { // jQuery  
			  console.log("BEGIN: Shopify button related scripts by ChandimaJ");
		
			var configs = {
			  //--
			  //--
			  //------------- SET YOUR BUTTON CONFIGURATION PARAMETERS HERE
		
				//--- Shopify Store Connect for  (Used in ShopifyBuyInit())
				//---
				domain: 'shop.overdeliver.gg',
				storefrontAccessToken: '7cff65ea0f72ec3176241ebe7dbfa4a2',
		
			}

		load_shopify_buttonx(configs);

	
		
				
	function load_shopify_buttonx(configs){
		console.log('cart toggle loaded...');
		
			  var toggleTemplates = {
			  //title: '<h5 class="{{data.classes.toggle.title}}" data-element="toggle.title">{{data.text.title}}</h5>',
			  icon: `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="35px" viewBox="0 0 52 32" version="1.1">
		<defs>
		<linearGradient id="linear0" gradientUnits="userSpaceOnUse" x1="0" y1="304.02" x2="867.18" y2="304.02" gradientTransform="matrix(0.0518923,0,0,0.0526207,0,0.0022687)">
		<stop offset="0.41" style="stop-color:rgb(20%,66.27451%,99.215686%);stop-opacity:1;"/>
		<stop offset="1" style="stop-color:rgb(13.333333%,38.039216%,90.196078%);stop-opacity:1;"/>
		</linearGradient>
		</defs>
		<g id="surface1">
		<path style=" stroke:none;fill-rule:nonzero;fill:url(#linear0);" d="M 12.480469 5.191406 L 10.527344 0.00390625 L 0 0.00390625 L 8.308594 3.269531 L 9.472656 6.359375 L 9.550781 6.570312 L 9.6875 6.9375 L 13.714844 8.460938 L 40.320312 8.460938 L 36.253906 19.234375 L 17.78125 19.234375 L 15.160156 12.328125 L 13.730469 11.769531 L 11.136719 10.761719 L 15.566406 22.496094 L 38.464844 22.496094 L 45 5.191406 Z M 19.09375 31.996094 C 16.980469 31.996094 15.265625 30.257812 15.265625 28.113281 C 15.265625 25.972656 16.976562 24.234375 19.09375 24.234375 C 21.207031 24.234375 22.921875 25.972656 22.917969 28.113281 C 22.917969 30.257812 21.207031 31.992188 19.09375 31.996094 Z M 19.09375 26.59375 C 18.261719 26.59375 17.589844 27.273438 17.589844 28.113281 C 17.589844 28.957031 18.261719 29.636719 19.09375 29.636719 C 19.921875 29.636719 20.59375 28.957031 20.59375 28.113281 C 20.589844 27.277344 19.921875 26.597656 19.09375 26.59375 Z M 35.007812 31.996094 C 32.894531 31.996094 31.179688 30.257812 31.179688 28.113281 C 31.179688 25.96875 32.894531 24.234375 35.007812 24.234375 C 37.121094 24.234375 38.835938 25.972656 38.835938 28.113281 C 38.832031 30.257812 37.121094 31.992188 35.007812 31.996094 Z M 35.007812 26.59375 C 34.179688 26.59375 33.503906 27.273438 33.503906 28.113281 C 33.503906 28.957031 34.179688 29.636719 35.007812 29.636719 C 35.835938 29.636719 36.507812 28.957031 36.507812 28.113281 C 36.503906 27.277344 35.835938 26.597656 35.007812 26.59375 Z M 35.007812 26.59375 "/>
		</g>
		</svg>`,
			  //icon: '<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M10.663 3H20.495L23.4892 7.74084H28.8395L32 10.605V16.6297V25.0248L28.8395 27.889H20.642L18.9136 26.3087H15.6049H12.2963L10.5679 27.889H2.37037L0 25.0248V16.6297V10.605L2.37037 7.74084H8.16784L10.663 3ZM19.4063 4.97531L21.257 7.90562L18.9136 10.1112H15.6049H12.2963L10.1939 8.13249L11.8556 4.97531H19.4063ZM8.79261 9.71609L10.6576 11.6217H20.7085L22.5734 9.71609H27.0935L29.6297 12.0187V23.611L27.0935 25.9136H22.8856L20.7085 23.8531H10.6576L8.48046 25.9136H4.27257L2.37042 23.611V12.0187L4.27257 9.71609H8.79261ZM21.2069 12.8069L23.0719 10.9013H26.6357L28.4445 12.5434V23.0863L26.6357 24.7284H23.3575L21.1804 22.6679H10.1856L8.00853 24.7284H4.83078L3.55561 23.1848V12.4449L4.83078 10.9013H8.29419L10.1592 12.8069H21.2069Z" fill="white"></path> <rect x="10.8643" y="20.5803" width="5.92593" height="1.58025" transform="rotate(-90 10.8643 20.5803)" fill="black"></rect> <rect x="14.8149" y="20.5803" width="5.92593" height="1.58025" transform="rotate(-90 14.8149 20.5803)" fill="black"></rect> <rect x="18.7656" y="20.5803" width="5.92593" height="1.58025" transform="rotate(-90 18.7656 20.5803)" fill="black"></rect> </svg>',
			  count: '<div class="{{data.classes.toggle.count}}" data-element="toggle.count">{{data.count}}</div>',
			};
		
		var cartTemplates = {
		  title: `<div class="{{data.classes.cart.header}}" data-element="cart.header" test="tester">
					<h2 class="{{data.classes.cart.title}}" data-element="cart.title">{{data.text.title}}</h2>
					<button class="{{data.classes.cart.close}}" data-element="cart.close">
					  <span aria-hidden="true">&times;</span>
					  <span class="visuallyhidden">{{data.text.closeAccessibilityLabel}}</span>
					 </button>
				  </div>`,
		  lineItems: `<div class="{{data.classes.cart.cartScroll}}{{#data.contents.note}} {{data.classes.cart.cartScrollWithCartNote}}{{/data.contents.note}}{{#data.discounts}} {{data.classes.cart.cartScrollWithDiscounts}}{{/data.discounts}}" data-element="cart.cartScroll">
						{{#data.isEmpty}}<p class="{{data.classes.cart.empty}} {{data.classes.cart.emptyCart}}" data-element="cart.empty">{{data.text.empty}}</p>{{/data.isEmpty}}
						<ul role="list" class="{{data.classes.cart.lineItems}}" data-element="cart.lineItems">{{{data.lineItemsHtml}}}</ul>
					  </div>`,
		  footer: `{{^data.isEmpty}}
					<div class="{{data.classes.cart.footer}}" data-element="cart.footer">
					  {{#data.discounts}}
						<div class="{{data.classes.cart.discount}}" data-element="cart.discount">
						  <span class="{{data.classes.cart.discountText}}" data-element="cart.discountText">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" class="{{data.classes.cart.discountIcon}}" data-element="cart.discountIcon" aria-hidden="true">
							  <path d="M10.001 2.99856C9.80327 2.99856 9.61002 2.93994 9.44565 2.83011C9.28128 2.72029 9.15317 2.56418 9.07752 2.38155C9.00187 2.19891 8.98207 1.99794 9.02064 1.80405C9.05921 1.61016 9.1544 1.43207 9.29419 1.29228C9.43397 1.1525 9.61207 1.0573 9.80596 1.01874C9.99984 0.980171 10.2008 0.999965 10.3834 1.07562C10.5661 1.15127 10.7222 1.27938 10.832 1.44375C10.9418 1.60812 11.0005 1.80136 11.0005 1.99905C11.0005 2.26414 10.8952 2.51837 10.7077 2.70581C10.5203 2.89326 10.266 2.99856 10.001 2.99856ZM10.001 1.67062e-05H7.0024C6.87086 -0.000743818 6.74046 0.024469 6.61868 0.0742095C6.49691 0.12395 6.38614 0.19724 6.29275 0.289876L0.295655 6.28697C0.201972 6.37989 0.127614 6.49044 0.0768697 6.61224C0.0261256 6.73404 0 6.86468 0 6.99663C0 7.12857 0.0261256 7.25922 0.0768697 7.38102C0.127614 7.50282 0.201972 7.61336 0.295655 7.70628L4.29372 11.7043C4.38664 11.798 4.49718 11.8724 4.61898 11.9231C4.74078 11.9739 4.87143 12 5.00337 12C5.13532 12 5.26596 11.9739 5.38776 11.9231C5.50956 11.8724 5.62011 11.798 5.71303 11.7043C5.90294 11.5044 11.5102 5.89716 11.7101 5.70725C11.8028 5.61386 11.876 5.50309 11.9258 5.38132C11.9755 5.25954 12.0007 5.12914 12 4.99759V1.99905C12 1.46887 11.7894 0.96041 11.4145 0.585519C11.0396 0.210628 10.5311 1.67062e-05 10.001 1.67062e-05Z" />
							</svg>
							<span class="visuallyhidden">Discount:</span>
							{{text}}
						  </span>
						  <span class="{{data.classes.cart.discountAmount}}" data-element="cart.discountAmount">{{amount}}</span>
						</div>
					  {{/data.discounts}}
					  <p class="{{data.classes.cart.subtotalText}}" data-element="cart.total">{{data.text.total}}</p>
					  <p class="{{data.classes.cart.subtotal}}" data-element="cart.subtotal">{{data.formattedTotal}}</p>
					  {{#data.contents.note}}
						<div class="{{data.classes.cart.note}}" data-element="cart.note">
						  <label for="{{data.cartNoteId}}" class="{{data.classes.cart.noteDescription}}" data-element="cart.noteDescription">{{data.text.noteDescription}}</label>
						  <textarea id="{{data.cartNoteId}}" class="{{data.classes.cart.noteTextArea}}" data-element="cart.noteTextArea" rows="3"/>{{data.cartNote}}</textarea>
						</div>
					  {{/data.contents.note}}
					  <p class="{{data.classes.cart.notice}}" data-element="cart.notice">{{data.text.notice}}</p>
					  <button class="{{data.classes.cart.button}}" type="button" data-element="cart.button">{{data.text.button}}</button>
					</div>
				   {{/data.isEmpty}}`,
		};
		
		var lineItemTemplates = {
		  image: '  <div class="{{data.classes.lineItem.image}}" style="background-image: url({{data.lineItemImage}})" data-element="lineItem.image" test="tester"></div>',
		  variantTitle: '<div class=" {{data.classes.lineItem.variantTitle}}" data-element="lineItem.variantTitle">{{data.variantTitle}}<span style="color:transparent; opacity:0 !important;">|</span></div>',
		
		  title: '<div class="close_lineitemx">&times;</div> <span class="{{data.classes.lineItem.itemTitle}}" data-element="lineItem.itemTitle">{{data.title}}</span>',
		  price: '<span class="{{data.classes.lineItem.price}}" data-element="lineItem.price">{{data.formattedPrice}}</span>',
		  priceWithDiscounts: `<div class="{{data.classes.lineItem.priceWithDiscounts}}" data-element="lineItem.price">
								{{#data.formattedFullPrice}}
								  <span class="visuallyhidden">Regular price</span>
								  <del class="{{data.classes.lineItem.fullPrice}}" data-element="lineItem.fullPrice">{{data.formattedFullPrice}}</del>
								  <span class="visuallyhidden">Sale price</span>
								{{/data.formattedFullPrice}}
								<div class="{{data.classes.lineItem.price}}" data-element="lineItem.price">{{data.formattedActualPrice}}</div>
								{{#data.discounts}}
								  <div class="{{data.classes.lineItem.discount}}" data-element="lineItem.discount">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 12 12" class="{{data.classes.lineItem.discountIcon}}" data-element="lineItem.discountIcon" aria-hidden="true">
									  <path d="M10.001 2.99856C9.80327 2.99856 9.61002 2.93994 9.44565 2.83011C9.28128 2.72029 9.15317 2.56418 9.07752 2.38155C9.00187 2.19891 8.98207 1.99794 9.02064 1.80405C9.05921 1.61016 9.1544 1.43207 9.29419 1.29228C9.43397 1.1525 9.61207 1.0573 9.80596 1.01874C9.99984 0.980171 10.2008 0.999965 10.3834 1.07562C10.5661 1.15127 10.7222 1.27938 10.832 1.44375C10.9418 1.60812 11.0005 1.80136 11.0005 1.99905C11.0005 2.26414 10.8952 2.51837 10.7077 2.70581C10.5203 2.89326 10.266 2.99856 10.001 2.99856ZM10.001 1.67062e-05H7.0024C6.87086 -0.000743818 6.74046 0.024469 6.61868 0.0742095C6.49691 0.12395 6.38614 0.19724 6.29275 0.289876L0.295655 6.28697C0.201972 6.37989 0.127614 6.49044 0.0768697 6.61224C0.0261256 6.73404 0 6.86468 0 6.99663C0 7.12857 0.0261256 7.25922 0.0768697 7.38102C0.127614 7.50282 0.201972 7.61336 0.295655 7.70628L4.29372 11.7043C4.38664 11.798 4.49718 11.8724 4.61898 11.9231C4.74078 11.9739 4.87143 12 5.00337 12C5.13532 12 5.26596 11.9739 5.38776 11.9231C5.50956 11.8724 5.62011 11.798 5.71303 11.7043C5.90294 11.5044 11.5102 5.89716 11.7101 5.70725C11.8028 5.61386 11.876 5.50309 11.9258 5.38132C11.9755 5.25954 12.0007 5.12914 12 4.99759V1.99905C12 1.46887 11.7894 0.96041 11.4145 0.585519C11.0396 0.210628 10.5311 1.67062e-05 10.001 1.67062e-05Z" />
									</svg>
									<span class="visuallyhidden">Discount:</span>
									{{discount}}
								  </div>
								{{/data.discounts}}
							  </div>`,
		  quantity: `<div class="{{data.classes.lineItem.quantity}}" data-element="lineItem.quantity">
		  
		  <div class="qtyblurbtn qtydecrement {{data.classes.lineItem.quantityButton}} {{data.classes.lineItem.quantityDecrement}}" id="qtyd_decrement" data-line-item-id="{{data.id}}" data-element="lineItem.quantityDecrement">
						<button style="display:none !important" class="hiddenrealbtn {{data.classes.lineItem.quantityButton}} {{data.classes.lineItem.quantityDecrement}}" data-line-item-id="{{data.id}}" data-element="lineItem.quantityDecrement"></button>
						<button class="lineitm_val_decrement" type="button">
							<span class=""></span>
							<span>-</span>
							<span class=""></span>
						</button>
		</div>
		
		
						<div class="great-input" style="">
							<input class="lineitm_val {{data.classes.lineItem.quantityInput}}" type="number" min="0" aria-label="{{data.text.quantityInputAccessibilityLabel}}" data-line-item-id="{{data.id}}" value="{{data.quantity}}" data-element="lineItem.quantityInput">
						</div>
		
		
		<div class="qtyblurbtn qtyincrement" id="qtyd_increment">
						<button style="display:none !important" class="hiddenrealbtn {{data.classes.lineItem.quantityButton}} {{data.classes.lineItem.quantityIncrement}}" data-line-item-id="{{data.id}}" data-element="lineItem.quantityIncrement"></button>
						<button class="lineitm_val_increment" type="button">
							<span class=""></span>
							<span>+</span>
							<span class=""></span>
						</button>  
		</div>
					</div>`,
		};
		
				//Create Component
				var product_options =  {
					
					
			cart:{
				iframe:false,
				contents:{
					lineItems:true
				},
				templates:cartTemplates,
				popup:false,
				styles:{
					
	'background': 'rgb(4,11,21)',
	'background': '-moz-linear-gradient(180deg, rgba(5, 11, 21, 0.75) 0%, rgba(4, 11, 21, 0.63) 100%)',
	'background': '-webkit-linear-gradient(180deg, rgba(5, 11, 21, 0.75) 0%, rgba(4, 11, 21, 0.63) 100%)',
	'background': 'linear-gradient(180deg, rgba(5, 11, 21, 0.75) 0%, rgba(4, 11, 21, 0.63) 100%)',
	'filter': 'progid:DXImageTransform.Microsoft.gradient(startColorstr="#040b15",endColorstr="#040b15",GradientType=1)',
	'-webkit-backdrop-filter': 'blur(15px)',
	'backdrop-filter': 'blur(15px)',
	'border-width': '0 0 0 1px',
	'border-style': 'solid',
	'border-image': 'linear-gradient(0deg, transparent 10%, #33A9FD 50%, transparent 90%) 15',
				},
				DOMEvents:{
					'click .lineitm_val_increment':function(event,target){
						console.log('Increment Clicked...');
						let current_qty = $(target.parentElement.parentElement.parentElement).find('.lineitm_val').val();
						let line_item = $(target.parentElement.parentElement.parentElement).find('.shopify-buy__cart-item__variant-title').text();
						console.log('line_item: '+line_item);
						console.log('current_qty: '+current_qty);
						
						if(line_item=='Standard Edition|' ){
							let standard_id = 2;
							let standard_ones = 0;
							let line_items = ui.components.cart[0].lineItemCache.length;

							for (let i=0; i<line_items; i++){
								if( (typeof ui.components.cart[0].lineItemCache[i] !== 'undefined') && (ui.components.cart[0].lineItemCache[i].variant.title == "Standard Edition")){
									standard_ones = ui.components.cart[0].lineItemCache[i].quantity;
									standard_id = i;
								}
							}
		
							standard_ones = parseInt(standard_ones);
							console.log('standard_ones:'+standard_ones+" index: "+standard_id);
							
							let new_qty = standard_ones+1;
							ui.components.cart[0].lineItemCache[standard_id].quantity = new_qty;
							ui.components.cart[0].updateItem(ui.components.cart[0].lineItemCache[standard_id].id, ui.components.cart[0].lineItemCache[standard_id].quantity);
							$(target.parentElement.parentElement.parentElement).find('.lineitm_val').val(new_qty);

						}else if(line_item=='Day One Edition|'){
							event.preventDefault();
							event.stopPropagation();
							$(target.parentElement.parentElement).find('.qtyblurbtn').css('pointer-events','none');
							$(target.parentElement.parentElement).find('.qtyblurbtn').css('opacity','.4');

							$('#qtyd_beforeaddtocart').trigger('click');
						}else{
							$(target).find('.hiddenrealbtn').trigger('click');
						}


					},

					'click .lineitm_val_decrement':function(event,target){
						console.log('Decrement Clicked...');
						let current_qty = $(target.parentElement.parentElement.parentElement).find('.lineitm_val').val();
						let line_item = $(target.parentElement.parentElement.parentElement).find('.shopify-buy__cart-item__variant-title').text();
						console.log(line_item);
						console.log(current_qty);
						
						if(line_item=='Standard Edition|'){
							let standard_id = 2;
							let standard_ones = 0;
							let line_items = ui.components.cart[0].lineItemCache.length;

							for (let i=0; i<line_items; i++){
								if( (typeof ui.components.cart[0].lineItemCache[i] !== 'undefined') && (ui.components.cart[0].lineItemCache[i].variant.title == "Standard Edition")){
									standard_ones = ui.components.cart[0].lineItemCache[i].quantity;
									standard_id = i;
								}
							}
		
							standard_ones = parseInt(standard_ones);
							console.log('standard_ones:'+standard_ones+" index: "+standard_id);

							let new_qty = standard_ones-1;
							ui.components.cart[0].lineItemCache[standard_id].quantity = new_qty;
							ui.components.cart[0].updateItem(ui.components.cart[0].lineItemCache[standard_id].id, ui.components.cart[0].lineItemCache[standard_id].quantity);
							$(target.parentElement.parentElement.parentElement).find('.lineitm_val').val(new_qty);

							if(typeof parseInt(ui.components.cart[0].lineItemCache[standard_id].quantity) == 'undefined' || parseInt(ui.components.cart[0].lineItemCache[standard_id].quantity)<=0){
								console.log('standard qty is 0');
								ui.closeCart();
								setTimeout(function() {
									ui.openCart();
								}, 2000);
							}


						}else if(line_item=='Day One Edition|'){
							event.preventDefault();
							event.stopPropagation();
							$(target.parentElement.parentElement).find('.qtyblurbtn').css('pointer-events','none');
							$(target.parentElement.parentElement).find('.qtyblurbtn').css('opacity','.4');

							let dayone_id = 2;
							let dayone_ones = 0;
							let line_items = ui.components.cart[0].lineItemCache.length;

							for (let i=0; i<line_items; i++){
								if( (typeof ui.components.cart[0].lineItemCache[i] !== 'undefined') && (ui.components.cart[0].lineItemCache[i].variant.title == "Day One Edition")){
									dayone_ones = ui.components.cart[0].lineItemCache[i].quantity;
									dayone_id = i;
								}
							}
		

							dayone_ones = parseInt(dayone_ones);
							console.log('dayone_ones:'+dayone_ones+" index: "+dayone_id);
							let new_qty = 0;
							ui.components.cart[0].lineItemCache[dayone_id].quantity = new_qty;
							ui.components.cart[0].updateItem(ui.components.cart[0].lineItemCache[dayone_id].id, ui.components.cart[0].lineItemCache[dayone_id].quantity);
							$(target.parentElement.parentElement.parentElement).find('.lineitm_val').val(new_qty);

							if(typeof parseInt(ui.components.cart[0].lineItemCache[dayone_id].quantity) == 'undefined' || parseInt(ui.components.cart[0].lineItemCache[dayone_id].quantity)<=0){
								console.log('Dayone qty is 0');
								ui.closeCart();
								setTimeout(function() {
									ui.openCart();
								}, 2000);
							}
						}else{
							$(target).find('.hiddenrealbtn').trigger('click');

						}
						
					},

					'click .close_lineitemx':function(event,target){
						var element_id = $(target.parentElement).attr("id");
						console.log('close item clicked..');
						ui.components.cart[0].updateItem(element_id, 0);
						ui.closeCart();
								setTimeout(function() {
									ui.openCart();
								}, 2000);
					}
				},
				events: {
					beforeInit: function(cart){
						var actualOpen = cart.checkout.open;
						cart.checkout.open = function (url) {
							//url = url + "?utm_source=testsource&utm_medium=testmedium&utm_campaign=testcampaign&utm_content=testcontent&utm_term=testterm&ref=testref";
							//actualOpen.call(this, url);
							$('#buybutton_checkout_url').attr('href',url);
							//$('#buybutton_checkout_url').trigger('click');
							window.location.href=url;
						};
					}
				}
	
				
			}, // END Cart
		
				lineItem:{
					iframe:false,
					templates: lineItemTemplates
				}, // END Line Items
				

				  toggle:{
					iframe:false,
					sticky: false,
					templates:toggleTemplates,
					contents: {
						title:false
					},
					text:{
						title: ''
					}
				  },
		
				}// Product options
		
							
				
				//Creating shop client
				var client = ShopifyBuy.buildClient({
					domain: configs.domain,
					storefrontAccessToken: configs.storefrontAccessToken, // previously apiKey, now deprecated
				});

				
				//Initializing the library
				var ui = ShopifyBuy.UI.init(client);
				
				//Getting global ui
				window.ui = ui;
					console.log('ui is set. ui:');
					console.log(window.ui);
		
				ui.createComponent('cart', {
					toggles: [{node: document.getElementById('toggle_shopify_cart')}],
					options: product_options,
					moneyFormat: '€ {{amount}} ',
				});
				
		
			}// End of load_shopify_button();
		
			//load_shopify_button(configs);
			  console.log('END: Shopify Related Scripts by ChandimaJ');
				
			})( jQuery );
			/*]]>*/
			</script>
		<?php //-----------------------------
		}
	
		add_shortcode('shopify-cart', 'shopify_cart_toggle_scripts');