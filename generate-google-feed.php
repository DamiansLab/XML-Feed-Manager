<?php
/**
 * XML Feed Manager - Generates XML product feeds for Skroutz and Google Merchant for PrestaShop.
 *
 * @author    DamiansLab <https://damianslab.gr>
 * @copyright 2025 DamiansLab (Ηλίας Δαμιανίδης)
 * @license   Proprietary, see license.txt (DamiansLab End User License Agreement)
 */

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');

date_default_timezone_set('Europe/Athens');

function googlefeed_generate_xml() {
    $context = Context::getContext();
    $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
    $id_shop = (int)Configuration::get('PS_SHOP_DEFAULT');

    $selected_category_ids = explode(',', Configuration::get('XMLFEED_CATEGORY_IDS'));
	$excluded_ids = array_map('intval', explode(',', Configuration::get('XMLFEEDMANAGER_EXCLUDED_IDS_GOOGLE')));

    $products = Db::getInstance()->executeS('
        SELECT p.id_product, pl.name, pl.link_rewrite, pl.description_short,
               p.price, m.name AS manufacturer_name, sa.quantity,
               i.id_image, p.reference, p.ean13
        FROM '._DB_PREFIX_.'product p
        JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.(int)$id_lang.')
        JOIN '._DB_PREFIX_.'stock_available sa ON (p.id_product = sa.id_product AND sa.id_shop = '.(int)$id_shop.')
        LEFT JOIN '._DB_PREFIX_.'manufacturer m ON (p.id_manufacturer = m.id_manufacturer)
        LEFT JOIN '._DB_PREFIX_.'image i ON (i.id_product = p.id_product AND i.cover = 1)
        WHERE p.active = 1
    ');

    $xml = new DOMDocument('1.0', 'UTF-8');
    $rss = $xml->createElement('rss');
    $rss->setAttribute('version', '2.0');
    $rss->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:g', 'http://base.google.com/ns/1.0');
    $xml->appendChild($rss);

    $channel = $xml->createElement('channel');
    $rss->appendChild($channel);

    $channel->appendChild($xml->createElement('title', Configuration::get('PS_SHOP_NAME')));
    $channel->appendChild($xml->createElement('link', $context->shop->getBaseURL(true)));
    $channel->appendChild($xml->createElement('description', 'Product feed for Google Merchant'));

    foreach ($products as $product) {
        if (in_array($product['id_product'], $excluded_ids)) continue;

        $item = $xml->createElement('item');

        $item->appendChild($xml->createElement('g:id', $product['id_product']));
        $item->appendChild($xml->createElement('title', htmlspecialchars($product['name'])));
        $item->appendChild($xml->createElement('description', strip_tags($product['description_short'])));

        $productObj = new Product($product['id_product'], false, $id_lang);
        $product_link = $context->link->getProductLink($productObj);
        $item->appendChild($xml->createElement('link', $product_link));

        if ($product['id_image']) {
            $image_url = $context->link->getImageLink($product['link_rewrite'], $product['id_product'].'-'.$product['id_image'], 'large_default');
            $item->appendChild($xml->createElement('g:image_link', $image_url));
        }

        $item->appendChild($xml->createElement('g:brand', $product['manufacturer_name']));
        if ($product['ean13']) {
            $item->appendChild($xml->createElement('g:gtin', $product['ean13']));
        }
        if ($product['reference']) {
            $item->appendChild($xml->createElement('g:mpn', $product['reference']));
        }

        $item->appendChild($xml->createElement('g:condition', 'new'));
        $item->appendChild($xml->createElement('g:availability', $product['quantity'] > 0 ? 'in stock' : 'out of stock'));

        $price = number_format(Product::getPriceStatic($product['id_product'], true), 2);
        $item->appendChild($xml->createElement('g:price', $price.' EUR'));

		if ($productObj->weight > 0) {
			$shippingWeight = number_format($productObj->weight, 2, '.', '') . ' kg';
			$item->appendChild($xml->createElement('g:shipping_weight', $shippingWeight));
}

        // Προσθήκη πλήρους ιεραρχίας κατηγορίας στο product_type
        $productCategories = Product::getProductCategories($product['id_product']);
        $category_name = '';
        if (!empty($productCategories)) {
            $category = new Category((int)$productCategories[0], $id_lang);
            if (Validate::isLoadedObject($category)) {
                $categoryPath = $category->getParentsCategories($id_lang);
                $pathParts = [];
                foreach (array_reverse($categoryPath) as $cat) {
                    if ($cat['name'] !== 'Root') {
                        $pathParts[] = $cat['name'];
                    }
                }
                $category_name = implode(' > ', $pathParts);
            }
        }

        if ($category_name) {
            $item->appendChild($xml->createElement('g:product_type', htmlspecialchars($category_name)));
        }

        $channel->appendChild($item);
    }

    $xml->formatOutput = true;

    $export_dir = _PS_MODULE_DIR_.'xmlfeedmanager/google_export';
    if (!file_exists($export_dir)) {
        mkdir($export_dir, 0755, true);
    }

    $xml->save($export_dir.'/google.xml');
}

if (php_sapi_name() === 'cli' || isset($_GET['run'])) {
    googlefeed_generate_xml();
    echo "Google XML feed generated.";
}