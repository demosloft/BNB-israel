<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
wp_enqueue_style('envato-toolkit-admin');
?>
<div class="envato-toolkit-wrapper">
<h1>Envato Toolkit - Search Results</h1>
<div class="content">
    <?php if ($errorMessage != ""): ?>
        <div class="info-message error-message"><?=$errorMessage;?></div>
    <?php elseif ($okayMessage != ""): ?>
        <div class="info-message okay-message"><?=$okayMessage;?></div>
    <?php endif; ?>
    <div class="results-box">
        <h2>Details about you</h2>
        <p>
            <strong>List of all different plugins you bought:</strong><br />
            <?php foreach($plugins AS $pluginId => $plugin): ?>
                <?='Plugin Id: '.$pluginId.', Name: '.$plugin['name'];?>, Licenses:<br />
                <?php foreach($plugin['licenses'] AS $license): ?>
                    <em>
                    Code: <?=$license['purchase_code'];?>,
                    License: <?=$license['license'];?>,
                    Purchased: <?=$license['license_purchase_date'];?> <?=$license['license_purchase_time'];?>,
                    Expires: <?=$license['support_expiration_date'];?> <?=$license['support_expiration_time'];?>,
                    Status: <?=$license['support_active'] == 1 ? "Supported" : "Support Expired";?>
                    </em>
                    <br />
                <?php endforeach; ?>
                <br />
            <?php endforeach; ?>
        </p>
        <p>
            <strong>List of all different themes you bought:</strong><br />
            <?php foreach($themes AS $themeId => $theme): ?>
                <?='Theme Id: '.$themeId.', Name: '.$theme['name'];?>, Licenses:<br />
                <?php foreach($theme['licenses'] AS $license): ?>
                    <em>
                    Code: <?=$license['purchase_code'];?>,
                    License: <?=$license['license'];?>,
                    Purchased: <?=$license['license_purchase_date'];?> <?=$license['license_purchase_time'];?>,
                    Expires: <?=$license['support_expiration_date'];?> <?=$license['support_expiration_time'];?>,
                    Status: <?=$license['support_active'] == 1 ? "Supported" : "Support Expired";?>
                    </em>
                    <br />
                <?php endforeach; ?>
                <br />
            <?php endforeach; ?>
        </p>
        <p>
            <strong>Your summary:</strong><br />
            Your location is <strong><?=$authorCity;?></strong>, <strong><?=$authorCountry;?></strong>.
            You&#39;ve sold your items <?=$authorSales;?> times
            and you have <?=$authorFollowers;?> followers on Envato.
        </p>
        <div class="clear">&nbsp;</div>

        <!-- ---------------------------------------------------------- -->
        <?php if($targetPurchaseCode != ''): ?>
            <h2>1. Your Customer&#39;s License Details</h2>
            <?php if($showLicenseDetails): ?>
                <ul>
                    <li>Purchase Code: <?=$targetPurchaseCode;?></li>
                    <li>Is Valid License: <?=$isValidTargetLicense ? 'Yes' : 'No';?></li>
                    <li>Buyer Username: <?=$targetLicenseBuyer;?></li>
                    <li>License: <?=$targetLicense;?></li>
                    <li>Purchased At: <?=$targetLicensePurchasedAt;?></li>
                    <li>Supported Until: <?=$targetLicenseSupportedUntil;?></li>
                    <li>Status: <?=$targetLicenseSupportActive == 1 ? "Supported" : "Support Expired";?></li>
                </ul>
            <?php endif; ?>
            <div class="clear">&nbsp;</div>
        <?php endif; ?>

        <!-- ---------------------------------------------------------- -->
        <?php if($targetUsername != ''): ?>
            <h2>2. Details About Target Envato User - <?=$targetUsername;?></h2>
            <p>
                <strong><?=$targetUsername;?></strong> is located in <strong><?=$targetUserCity;?></strong>,
                <strong><?=$targetUserCountry;?></strong>. He sold his items <?=$targetUserSales;?> times
                and has <?=$targetUserFollowers;?> followers on Envato.
            </p>
            <div class="clear">&nbsp;</div>
        <?php endif; ?>

        <!-- ---------------------------------------------------------- -->
        <?php if($targetPluginId > 0): ?>
            <h2>3. Status of Purchased Plugin ID - <?=$targetPluginId;?></h2>
            <ul>
                <li>Plugin Name: <?=$nameOfTargetPluginId;?></li>
                <li>Plugin Update Available: <?=$pluginUpdateAvailable ? 'Yes' : 'No';?></li>
                <li>Installed Plugin Version: <?=$installedPluginVersion;?></li>
                <li>Available Plugin Version: <?=$availablePluginVersion;?></li>
                <?php if($pluginUpdateDownloadUrl != ''): ?>
                    <li>Plugin Update Download URL:
                        <a href="<?=$pluginUpdateDownloadUrl;?>" target="_blank" title="Download newest version">Download newest version</a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="clear">&nbsp;</div>
        <?php endif; ?>

        <!-- ---------------------------------------------------------- -->
        <?php if($targetThemeId > 0): ?>
            <h2>4. Status of Purchased Theme ID - <?=$targetThemeId;?></h2>
            <ul>
                <li>Theme Name: <?=$nameOfTargetThemeId;?></li>
                <li>Theme Update Available: <?=$themeUpdateAvailable ? 'Yes' : 'No';?></li>
                <li>Installed Theme Version: <?=$installedThemeVersion;?></li>
                <li>Available Theme Version: <?=$availableThemeVersion;?></li>
                <?php if($themeUpdateDownloadUrl != ''): ?>
                    <li>Theme Update Download URL:
                        <a href="<?=$themeUpdateDownloadUrl;?>" target="_blank" title="Download newest version">Download newest version</a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="clear">&nbsp;</div>
        <?php endif; ?>

        <!-- ---------------------------------------------------------- -->
        <?php if($targetPluginName != '' && $targetPluginAuthor != ''): ?>
            <h2>5. Envato Item Id of Purchased Plugin</h2>
            <ul>
                <li>Searched for Name: <?=$targetPluginName;?></li>
                <li>Searched for Author: <?=$targetPluginAuthor;?></li>
                <li>Found Plugin Id: <?=$foundPluginId;?></li>
            </ul>
            <div class="clear">&nbsp;</div>
        <?php endif; ?>

        <!-- ---------------------------------------------------------- -->
        <?php if($targetThemeName != '' && $targetThemeAuthor != ''): ?>
            <h2>6. Envato Item Id of Purchased Theme</h2>
            <ul>
                <li>Searched for Name: <?=$targetThemeName;?></li>
                <li>Searched for Author: <?=$targetThemeAuthor;?></li>
                <li>Found Theme Id: <?=$foundThemeId;?></li>
            </ul>
        <?php endif; ?>

        <div class="action-buttons">
            <button type="submit" class="back-button" onclick="window.location.href='<?=$goBackUrl;?>'">Back</button>
        </div>
    </div>
</div>
</div>