<?php
/*
    Plugin Name: Gravity Forms Autocomplete
    Plugin URI: https://carlymho.com/gf-autocomplete
    Description: Adds options to Gravity Forms fields to turn on autocomplete and select a fill type (e.g. "email" or "cc-name") for accesibility purposes.
    Version: 1.0
    Author: Carly Ho
    Author URI: https://carlymho.com/
    License: GPLv2
*/

function activate_tasks() {
    return;
}

function deactivate_tasks() {
    return;
}

function uninstall_tasks() {
    return;
}

function register_hooks() {
    register_activation_hook(__FILE__, 'activate_tasks');
	register_deactivation_hook(__FILE__, 'deactivate_tasks');
	register_uninstall_hook(__FILE__, 'uninstall_tasks');
}
add_action('plugins_loaded', 'register_hooks');

function autocomplete_settings($position, $form_id) {
    if ($position == 50) {
    ?>
    <li class="autocomplete_type_setting field_setting" style="display: list-item !important;">
        <label for="field_autocomplete_type_setting">
            <?php _e("Autocomplete Type", "gravityforms"); ?>
            <?php gform_tooltip("form_field_autocomplete_type_value") ?>
        </label>
        <select id="field_autocomplete_type_value">
            <option value="off">Off</option>
            <option value="on">On</option>
            <option value="name">Name</option>
            <option value="honorific-prefix">Honorific Prefix</option>
            <option value="given-name">Given Name</option>
            <option value="additional-name">Additional Name</option>
            <option value="family-name">Family Name</option>
            <option value="honorific-suffix">Honorific Suffix</option>
            <option value="nickname">Nickname</option>
            <option value="email">Email</option>
            <option value="username">Username</option>
            <option value="new-password">New Password</option>
            <option value="current-password">Current Password</option>
            <option value="organization-title">Organization Title</option>
            <option value="organization">Organization</option>
            <option value="street-address">Street Address</option>
            <option value="address-line1">Address Line 1</option>
            <option value="address-line2">Address Line 2</option>
            <option value="address-line3">Address Line 3</option>
            <option value="address-level4">Address Level 4</option>
            <option value="address-level3">Address Level 3</option>
            <option value="address-level2">Address Level 2</option>
            <option value="address-level1">Address Level 1</option>
            <option value="country">Country</option>
            <option value="country-name">Country Name</option>
            <option value="postal-code">Postal Code</option>
            <option value="cc-name">Credit Card Name</option>
            <option value="cc-given-name">Credit Card Given Name</option>
            <option value="cc-additional-name">Credit Card Aditional Name</option>
            <option value="cc-family-name">Credit Card Family Name</option>
            <option value="cc-number">Credit Card Number</option>
            <option value="cc-exp">Credit Card Expiration</option>
            <option value="cc-exp-month">Credit Card Expiration Month</option>
            <option value="cc-exp-year">Credit Card Expiration Year</option>
            <option value="cc-csc">Credit Card Security Code</option>
            <option value="cc-type">Credit Card Type</option>
            <option value="transaction-currency">Transaction Currency</option>
            <option value="transaction-amount">Transaction Amount</option>
            <option value="language">Language</option>
            <option value="bday">Birthday</option>
            <option value="bday-day">Birthday Day of Month</option>
            <option value="bday-month">Birthday Month</option>
            <option value="bday-year">Birthday Year</option>
            <option value="sex">Gender Identity</option>
            <option value="tel">Phone Number</option>
            <option value="tel-country-code">Phone Country Code</option>
            <option value="tel-national">Phone Number Without Country Code</option>
            <option value="tel-area-code">Phone Area Code</option>
            <option value="tel-local">Phone Number W/o Country or Area Code</option>
            <option value="tel-extension">Phone Extension Code</option>
            <option value="impp">Instant Messaging Protocol URL</option>
            <option value="url">URL</option>
            <option value="photo">Photo</option>
        </select>
    </li>
    <?php
    }
}
add_action('gform_field_advanced_settings', 'autocomplete_settings', 10, 2);

function editor_script(){
    ?>
    <script type='text/javascript'>
        //adding setting to fields of type "text"
        fieldSettings.text += ", .autocomplete_type_setting";
 
        //binding to the load field settings event to initialize the checkbox
        jQuery(document).on("gform_load_field_settings", function(event, field, form){
            jQuery("#field_autocomplete_type_value option").each(function() {
                if (field["autocompleteType"] && jQuery(this).val() == field["autocompleteType"]) {
                    jQuery(this).prop("selected", "selected");
                }
            });
            jQuery('.autocomplete_type_setting').css("display", "list-item");
        });

        jQuery('#field_autocomplete_type_value').on('change', function() {
            var selected = jQuery(this).find('option:selected').val();
            SetFieldProperty('autocompleteType', selected);
        });
    </script>
    <?php
}
add_action('gform_editor_js', 'editor_script');

function add_autocomplete_attribute($input, $field, $value, $entry_id, $form_id) {
    $in = $input;
    if ($field->autocompleteType && $field->autocompleteType != "off") {
        $in = preg_replace('/<([a-z]+)/i', '<$1 autocomplete="'.$field->autocompleteType.'"', $in);
    }
    return $in;
}
add_filter('gform_field_content', 'add_autocomplete_attribute', 10, 5);