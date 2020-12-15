# Shopify buybutton.js Integration on wordpress website
by Chandima Jayasiri (chandimaj@icloud.com)
<br>
<br>

## Installation
<br>
<br>

### 1. Copy `buybutton_bychandima.php` into themes / child theme folder
<br />

The following file __buybutton_bychandima.php__ should be placed in the theme folder / child themes folder.

(ex: overdeliver.gg/wp-content/themes/hello-child/) is the main script file responsible for all the Shopify Buy Button related functions.
<br />
<br />
<br />

### 2. Including `buybutton_bychandima.php` into wordpress
<br />

This file should he included in the theme's main `function.php` file which will be adding the _buybutton_bychandima.php_ to the wordpress site, when generating the page.

The file is included in the fuctions.php by the following code snippet.

```
include 'buybutton_bychandimaj.php';
```
<br />
<br />

### 3. Styling
<br />

All the styles used are saved in the `buybutton_styles_bychandimaj.css` file.<br>
But the theme (Hello) is overriding all the external css linked to the site and therefore a non invasive implementation of the styling had to be used.
<br />
<br />

So the css styles in the _buybutton_styles_bychandimaj.css_ is directly added to the site via __Theme Customize__ options / __Additional CSS__ section.

<br />
<br />
<br />
<br />



## Using the __Buy Button__ in a page
<br>
<br>

The buy button is added to a page via __wordpress shortcodes__.
<br />

### __There are two types of buy buttons__. ###
<br>
<br>

### 1. Default Buy Button Shortcode `[shopify-default]`
<br>

- Used for products with only __one product variant__
<br>
<br>
<br>

### 2. Buy Button with two variants Shortcode `[shopify-buy]`
<br>

- Used for special product using  __two product variants (Day One Edition and Standard Edition)__
<br>
<br>
<br>
<br>
<br>

### Adding product __buybutton shortcode__  to a page
<br>


- Product Id must be specified to load product into the page. If not the demo product will be loaded.

- Use the below code to pass the __Product Id__ as parameter to the shortcode. 
<br>
<br>

__For Default Product:__

```
[shopify-default product_id="<product id>"]
```

__For a product having two variants:__

```
[shopify-buy product_id="<product id>"]
```
<br>
<br>
<br>

___Note:___ _Replace `<product id>` with the actual product id of your product_ 

Ex:
```
[shopify-default product_id="5751875076251"]
```
<br>
<br>
<br>
<br>
<br>

### Linking a `Letter` Product with a `Day One Edition` product 
<br>
<br>

__For a product having two variants:__

```
[shopify-buy product_id="<product id>" letter_product_id="<letter product id>"]
```
<br>
<br>

___Note:___ _Replace `<product id>` with the actual product id of your product and `<letter product id>` with the product id of the additional product to be linked with the __day one edition__ product_ 

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

### Adding pixel event parameters to __buybutton shortcode__ 
<br>


The following parameters can be passed to override Facebook Pixel Event parameters for `addToCart` pixel event.
<br>

- content_id <br>
_Override event product id._<br>
_Product id used in the BuyButton is used as default if not specified_

- content_type<br>
_Value `product` is used as default if not specified_<br>

- content_name <br>
_Product handle is used as default if not specified_
<br>
<br>
<br>

__Passing Pixel Parameters For a Default Product:__

```
[shopify-default product_id="<product id>" content_id="<product content id>" content_type="<product content type>" content_name="<product name>"]
```

__For a product having two variants:__

```
[shopify-buy product_id="<product id>" content_id="<product content id>" content_type="<product content type>" content_name="<product name>"]
```
<br>
<br>
<br>

___Note:___ _Replace the pixel parameters with the custom parameters you want to use. If not default parameters will be used._ 

Ex:
```
[shopify-default product_id="5751875076251" content_id="5751875076251" content_type="product_group" content_name="Flow State Booster"]
```


