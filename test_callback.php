<?php
// Mock script to see how the system handles malformed `$user['Processing_value_one']` without validation
$loc = "invalidformat";
$custompricevalue = 1000;
$customtimevalueprice = 2000;
$textbotlang['users']['customsellvolume']['title'] = "test title";
$parts = explode("_", $loc);

if (($parts[0] ?? '') === "customvolume") {
    $info_product['Volume_constraint'] = $parts[2] ?? 0;
    $info_product['name_product'] = $textbotlang['users']['customsellvolume']['title'];
    $info_product['code_product'] = $textbotlang['users']['customsellvolume']['title'];
    $info_product['Service_time'] = $parts[1] ?? 0;
    $info_product['price_product'] = (($parts[2] ?? 0) * $custompricevalue) + (($parts[1] ?? 0) * $customtimevalueprice);
    echo "Custom Volume processed.\n";
} else {
    echo "Regular product processed.\n";
}

// Another scenario
$user = ['Processing_value_one' => 'customvolume'];
$parts2 = explode("_", $user['Processing_value_one']);

if (($parts2[0] ?? '') === "customvolume") {
    $info_product['Volume_constraint'] = $parts2[2] ?? 0;
    $info_product['name_product'] = $textbotlang['users']['customsellvolume']['title'];
    $info_product['code_product'] = $textbotlang['users']['customsellvolume']['title'];
    $info_product['Service_time'] = $parts2[1] ?? 0;
    $info_product['price_product'] = (($parts2[2] ?? 0) * $custompricevalue) + (($parts2[1] ?? 0) * $customtimevalueprice);
    echo "Custom Volume processed 2 with safe defaults.\n";
    print_r($info_product);
}

?>
