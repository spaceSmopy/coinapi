<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 */
?>

<div class="mynewsdesk-settings">
    <h1><?= __('CoinAPI settings','coinapi_integration'); ?></h1>
    <form method="post" action="">
        <h4><?= __('CoinGecko API key:','coinapi_integration'); ?></h4>
        <?php $api_key = get_option('coingecko_key');
        if(!empty($api_key)) {
            echo '<input type="text" name="api_key" placeholder="Enter API Key" value="'. $api_key .'">';
        } else {
            echo '<input type="text" name="api_key" placeholder="Enter API Key">';
        }?>

        <div class="submit">
            <input type="hidden" name="action" value="process_form">
            <input type="submit" name="submit" id="submit" class="update-button button button-primary" value="Save"  />
        </div>
    </form>
</div>