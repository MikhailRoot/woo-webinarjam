# woo-webinarjam
Woocommerce plugin to sell webinarjam webinars

My friend needed to sell webinars with webinarjam and i've created plugin for woocomerce for it. 

## Updated on March 2020:
Code is updated with current [API documentation](https://documentation.webinarjam.com/category/webinarjam-api/).

##Important
Because Webinarjam changed api and now use Schedule variable, which was previously unused, i've updated code to make it working, right now it registers user to first schedule in webinar.
Sorry for incovenience but if we want to sell same webinar with different schedules it will require big change in how product is sold (e.g. we'll have to display schedule selector for customer).
So right now (March 13, 2020) for case where you have a webinar it works, but supports in fact single schedule per webinar.

## NOTE

You are free to use this plugin without any charge.

You can say "thank you" to me via registration to webinarjam via my [registration  affiliate link](http://dealguardian.net/product/470/?hop=mikhailRoot&page=default&trackingID1=github) . 

It does not increase price for you but I recieve in this case some money from them.

Note all other links on this page are not affiliate links.

Thank you!

## How it works?
 You create webinars and products for them to sell on your wordpress+woocommerce website and sell them as usuall virtual products.
 
 Client will recieve webinarjam  access links as he pays  for it via email. 
 
 And of course payment methods could be all that woocommerce supports.
 
 You as admin will recieve same links to your admin email to make sure you'll not lose this sensitive information due to technical problems and email delivery issues.


## Usage
1. First of all you need to register on WebinarJam.
2. Then you need to create your first webinar in [webinarjam control panel](https://app.webinarjam.com/members/login)
3. install plugin  into your Wordpress+woocommerce website as usuall by uploading zip archive to Wordpress plugins installation dialog, and activate it.
4. go to **Wordpress Dashboard** ->  **Settings** -> **WebinarJam Settings**
5. paste you API key from [webinarjam](https://app.webinarjam.com/members/login) and save settings (you need this to be done only once, as API key is not changes).
 * you'll recieve API key when you create your first Webinarjam webinar in **Integrations** step -> **Api Custom Integrations** section.
 * save your webinarjam webinar and you'll be able to select it in Woocommerce product creation dialog further.
6. Go to Products page in Woocommerce and click **add Product**.
7. in Product type selection dropdown list will be a  new item - **Webinarjam** - just select it
8. there will be product settings tab **Select webinar** and dropdown list to select your already created in [webinarjam.com panel](https://app.webinarjam.com/members/login) webinars. Choose which you want to sell.
9. Set price for this webinar.
10. add description, photo or anything you need to specify like for usuall woocommerce product and click **Publish**.

**Happy webinars selling!**

## Features
1. added metabox within order screen in woocommerce to show webinarjam registration result's data to admin
2. it supports multiple webinarjam products bought via single order.
3. `webinarjam-list` shortcode to output all bought by user webinars from all orders (see example below). 
4. `webinarjam` shortcode to echo latest or specified by order_id  webinarjam registration result's data:
 - webinar_id, webinar_name, user_id, name, email, schedule, date, timezone, live_room_url , replay_room_url, thank_you_url all those are webinarjam's data for example user_id and webinar_id are id's in webinarjam system not your Wordpress|Woocommerce
 - you can use {parameter_name} placeholders to replace them in side shortcode's content see example below. 
 - You can also specify css classes passed in as class attribute as string
 example usage 
 
 `[webinarjam  class="sample_class" param="replay_room_url" ] {webinar_name} - for replay visit: {replay_room_url} [/webinarjam]` - without order_id specified it will get last webinar bought by current user.
 
 or 
 
 `[webinarjam  class=sample_class param=live_room_url ] {webinar_name} - for replay visit: {replay_room_url} [/webinarjam]`

  Example of `[webinarjam-list]` shortcode usage, it should have inside it a `[webinarjam]` shortcode instance which 
  will be used as a template to output all each bought by user webinar item. As explained above `{param_name}` are inner content placeholders which can be used to display webinar's data,
  all param_names corresponds to labels in webinarjam's order's screen metabox.
  
 ` [webinarjam-list]      
        [webinarjam class="block" param="replay_room_url" order_id ] 
              {webinar_name} - {replay_room_url} 
        [/webinarjam]
    [/webinarjam-list]
 `
 
 
## New Features:
 + support for multiple webinars bought in single order.
 + showing access links for webinars per client - accomplished via  `[webinarjam-list]` shortcode.
 + added shortcode inner content placeholders
 + it now uses woocommerce's standard `general` pricing tab so it has regular and sale prices.
 + added support for guest orders (idea and code from Sam Krieg).
    
## Plans

- create a better email templates
- think about better UI for selecting webinar to sell.

If you have any issues or improvements ideas contact [me here on github](https://github.com/MikhailRoot) or via skype Mikhail.root
