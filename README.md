# postnet-shipping
=== PostNet Shipping ===
Contributors: postnet-shipping
Tags: PostNet, PostNet Shipping, PostNet Plugin, PEP, PEP CELL, PEP HOME, ShoeCity, Tellie Town, PEPKOR, SKYNET, RAM, WooCommerce, WooCommerse, PARGO, PostNet, POPI, POPIA
Requires at least: 5.4
Tested up to: 6.0.3
Requires PHP: 7.3
WC requires at least: 7.0 and WC tested up to: 7.0.0
Stable tag: 1.0.0
License: License: GPLv2 or later

Auto setup of PostNet Shipping Method with a required field on checkout to indicate what PostNet Branch to be used complete with error handeling and help.


== Description ==
** NOTE!!! This plugin will only work if you are in South Africa. In order to test this plugin form any country other than South Africa, you will have to use a PROXY with South Africa as location.

By simply adding the PostNet plugin to your site, you can offer your customers the convenience of collecting their online orders at a PostNet Points.
This plugin is the only one that display and enforces the addition of a PostNet Branch field on checkout with WooCommerce 7 and up.
The plugin integrates seamlessly with all WooCommerce platforms and requires no special programming or development.


== 3rd Party and External Services ==
Firstly you need to have WooCommerce version 7+ installed in your website before installing the PostNet Plugin.
Further we send out analitics info via our plugin from your site, to our server to communicate with your plugin. This 3rd party service together with the circumstances under which we make calls are documented herein:

We make calls to our server, using our plugin from your WordPress website. This enables us to to collect some important information in order to make contact with you should it me required from our side, as well as to send you notifications on your admin portal, and to do certain checks so you get the best we have to offer. For your security we have codded the calls in such a manner, to only allow transmission between sites that contain our plugin and our server with a specific URL only. Each call is signed with your API Key and therefore no unauthorised calls can be made to our server. We add a single cronjob via our plugin to run data analytics every hour so we can see how we can improve our plugin.

Link to service: https://postnetplugin.co.za/
Link to service Privacy Policy: https://bepopiacompliant.co.za/#/privacy/postnetplugin.co.za

https://analytics.ppp.web-x.co.za/api/newusercreated
- we collect your domain name, user ID and email address of admin users and send it to our database to log an entry so that we can collect further data about the use of uour plugin on your site.


== Installation ==
Install from WordPress Library

Log in to the admin panel of your WordPress website, click on "plugins", then again at the top left on "Add New".
Now type "PostNet" into the search bar and wait for the plugins to filter. Now click on "Install Now" on the PostNet Plugin. After installation, activate the plugin. Thereafter you are good to go, and PostNet will be displayed as a shipping option on checkout.

Install From Website
The link on our website will take you to our official Plugin Page on WordPress.
Alternatively, you can go straight there by visiting: https://wordpress.org/plugins/postnet-shipping/

Click on the download button to download the latest official zipped file of our plugin. Now log in to the admin panel of your WordPress website, click on "plugins", then again at the top left on "Add New". Thereafter click on "Upload Plugin" and select or drag and drop the file you just downloaded.
After installation, activate the plugin. Thereafter you are good to go, and PostNet will be displayed as a shipping option on checkout.


== Frequently Asked Questions ==
Q1: What does PostNet Plugin do?
A1: This plugin enables visitors to your store to select PostNet as a shipping method.
This plugin will then apply a flat rate of R59.95 to the order as shipping cost and request a PostNet Branch where the order need to be sent to. This also assumes a delivery time of 7-9 working days which is indicated on the checkout page.

Q2: What happens if the user select PostNet but does not enter an PostNet Branch?
A2: This Plugin is smart enough to see if a blank field is provided, and will show an error that the PostNet Branch was not supplied if this is the case.

Q3: Can a client enter a wrong PostNet Branch?
A3: It is possible yes. We read the data entered and check if it consists of 5 characters. If it does, the plugin assume that the number entered is correct.

Q4: What happens if a user enters more or less than 5 characters?
A4: When they try to submit the order, another error will be displayed indicating that the PostNet Branch does not seem to be correct.

Q5: How will a client know what the code of their nearest PostNet Branch is?
A5: Both by clicking on the Text "Locate your nearest PostNet Branch" and in the link provided in the error, they can open the PostNet Points lookup in a new window to determine the code of their nearest or most-convenient PostNet Branch.

Q6: Is PostNet Plugin affiliated with PostNet at all?
A6: No, Web-X.co.za a third party realised that shortcoming of this feature when developing a WP WooCommerce shop for a client, and decided to build the solution as a plugin rather than adding the code to the site alone.


== Screenshots ==
1. Settings | Click on WooCommerce settings(1), then  on Shipping (2) and lastly on PostNet (3) to access our settings (WooCommerce -> Settings -> Shipping -> PostNet)
2. Checkout with PostNet | When PostNet is selected at checkout,a  Input field for PostNet Branch will be displayed (1), To locate the nearest point the user can click on "Locate nearest PostNet Branch". In the event where the PostNet Branch was not spesified and error will be displayed for the user, with instructions (3).
3. When a user click on the "Locate nearest PostNet Branch" link, they will be presented with this official page from PostNet where they can enter their location/area (1), click or select their nearest PostNet Branch (2), and Copy the PostNet Branch Number (3)

== Changelog ==
= 1.0.0 =
* Initial PostNet Plugin release