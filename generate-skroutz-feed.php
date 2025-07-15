<?php

require_once(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
date_default_timezone_set('Europe/Athens');

/**
 * Καθαρίζει HTML, εικόνες, SVGs, icons και κρατά απλό κείμενο σε μία γραμμή
 */
function cleanHtmlToSingleLine($html) {
    $html = preg_replace('/<img[^>]*>/i', '', $html);
    $html = preg_replace('/<svg.*?<\/svg>/is', '', $html);
    $html = preg_replace('/<i[^>]*>.*?<\/i>/is', '', $html);
    $html = preg_replace('/<[^>]*class="[^"]*(icon|fa)[^"]*"[^>]*>.*?<\/[^>]*>/is', '', $html);
    $text = strip_tags($html);
    $text = preg_replace('/\s+/u', ' ', $text);
    return trim($text);
}

/**
 * Δημιουργεί XML αρχείο feed και το αποθηκεύει τοπικά
 */
function xmlfeed_generate_xml() {
    $context = Context::getContext();
    $id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
    $id_shop = (int)Configuration::get('PS_SHOP_DEFAULT');

    // Παίρνουμε τις ρυθμίσεις
    $selected_category_ids = explode(',', Configuration::get('XMLFEED_CATEGORY_IDS'));
	$excluded_ids = array_map('intval', explode(',', Configuration::get('XMLFEEDMANAGER_EXCLUDED_IDS_SKROUTZ')));
    $included_fields = explode(',', Configuration::get('XMLFEED_FEED_FIELDS'));
    $clean_html = Configuration::get('XMLFEED_CLEAN_HTML');

    // Λήψη προϊόντων
    $sql = 'SELECT p.id_product, pl.name, pl.link_rewrite, pl.description_short, p.price,
                   m.name AS manufacturer_name, sa.quantity
            FROM '._DB_PREFIX_.'product p
            JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product AND pl.id_lang = '.$id_lang.')
            JOIN '._DB_PREFIX_.'stock_available sa ON (p.id_product = sa.id_product AND sa.id_product_attribute = 0 AND sa.id_shop = '.$id_shop.')
            LEFT JOIN '._DB_PREFIX_.'manufacturer m ON (p.id_manufacturer = m.id_manufacturer)
            WHERE p.active = 1 AND sa.quantity > 0';

    $products = Db::getInstance()->executeS($sql);

    $xml = new DOMDocument('1.0', 'UTF-8');
    $xml->formatOutput = true;
    $root = $xml->createElement('products');
    $root->appendChild($xml->createElement('created_at', date('c')));

    foreach ($products as $product) {
        if (in_array($product['id_product'], $excluded_ids)) {
            continue;
        }

        $productObj = new Product((int)$product['id_product'], false, $id_lang);
        $productCats = $productObj->getCategories();

        // Έλεγχος κατηγοριών
        if (!empty($selected_category_ids)) {
            $common = array_intersect($productCats, $selected_category_ids);
            if (empty($common)) {
                continue;
            }
        }

        $productNode = $xml->createElement('product');
        $productNode->appendChild($xml->createElement('UniqueID', $product['id_product']));

        // Όνομα
        if (in_array('name', $included_fields)) {
            $productNode->appendChild($xml->createElement('name', htmlspecialchars($product['name'])));
        }

        // Τιμή με ΦΠΑ
        if (in_array('price', $included_fields)) {
            $productPrice = Product::getPriceStatic($product['id_product'], true);
            $productNode->appendChild($xml->createElement('PriceVat', number_format($productPrice, 2, '.', '')));
        }

        // Τιμή χωρίς ΦΠΑ
        if (in_array('price_without_vat', $included_fields)) {
            $price_no_vat = Product::getPriceStatic($product['id_product'], false);
            $productNode->appendChild($xml->createElement('price_without_vat', number_format($price_no_vat, 2, '.', '')));
        }

        // Σύνδεσμος
        $productNode->appendChild($xml->createElement('link', $context->link->getProductLink($product['id_product'])));

        // Σύντομη Περιγραφή
        if (in_array('description_short', $included_fields) && !empty($product['description_short'])) {
            $desc_short = $clean_html ? cleanHtmlToSingleLine($product['description_short']) : $product['description_short'];
            $node = $xml->createElement('short_description');
            $node->appendChild($xml->createCDATASection($desc_short));
            $productNode->appendChild($node);
        }

        // Πλήρης Περιγραφή
        if (in_array('description', $included_fields) && !empty($productObj->description)) {
            $desc_full = $clean_html ? cleanHtmlToSingleLine($productObj->description) : $productObj->description;
            $node = $xml->createElement('description');
            $node->appendChild($xml->createCDATASection($desc_full));
            $productNode->appendChild($node);
        }

        // Κατηγορία
        if (in_array('category_name', $included_fields) && $productObj->id_category_default) {
            $category = new Category((int)$productObj->id_category_default, $id_lang);
            $categoryPath = $category->getParentsCategories($id_lang);
            $pathParts = [];

            foreach (array_reverse($categoryPath) as $cat) {
                if ($cat['name'] !== 'Root') {
                    $pathParts[] = $cat['name'];
                }
            }

            $categoryName = implode(' > ', $pathParts);
            if (!empty($categoryName)) {
                $productNode->appendChild($xml->createElement('category', htmlspecialchars($categoryName)));
            }
        }

        // Κατασκευαστής
        if (in_array('manufacturer_name', $included_fields) && !empty($product['manufacturer_name'])) {
            $productNode->appendChild($xml->createElement('manufacturer', htmlspecialchars($product['manufacturer_name'])));
        }

        // EAN
        if (in_array('ean13', $included_fields) && !empty($productObj->ean13)) {
            $productNode->appendChild($xml->createElement('ean', $productObj->ean13));
        }

        // MPN / Reference
        if (in_array('reference', $included_fields)) {
            $mpn = $productObj->mpn ?: $productObj->reference;
            if (!empty($mpn)) {
                $productNode->appendChild($xml->createElement('mpn', htmlspecialchars($mpn)));
            }
        }

        // Διαστάσεις
        if (in_array('dimensions', $included_fields)) {
            $dimensions = number_format($productObj->width, 2, '.', '').','.
                          number_format($productObj->height, 2, '.', '').','.
                          number_format($productObj->depth, 2, '.', '').' cm';
            $productNode->appendChild($xml->createElement('dimensions', $dimensions));
        }

        // Βάρος
        if (in_array('weight', $included_fields)) {
            $productNode->appendChild($xml->createElement('weight', number_format($productObj->weight, 2, '.', '') . ' kg'));
        }

        // Ποσότητα
        if (in_array('quantity', $included_fields)) {
            $productNode->appendChild($xml->createElement('quantity', (int)$product['quantity']));
        }

        // Εικόνα
        if (in_array('image', $included_fields)) {
            $cover = Product::getCover($product['id_product']);
            if ($cover) {
                $imageUrl = $context->link->getImageLink($product['link_rewrite'], $cover['id_image'], 'large_default');
                $productNode->appendChild($xml->createElement('image', $imageUrl));
            }
        }

        // Διαθεσιμότητα (λεκτική)
        if (in_array('availability', $included_fields)) {
            $productNode->appendChild($xml->createElement('availability', 'Άμεσα διαθέσιμο'));
        }

        $root->appendChild($productNode);
    }

    $xml->appendChild($root);

    // Αποθήκευση XML & GZ
    $feed_dir = _PS_MODULE_DIR_.'xmlfeedmanager/skroutz_export/';
    if (!is_dir($feed_dir)) {
        mkdir($feed_dir, 0755, true);
    }

    $xml_file_path = $feed_dir.'skroutz.xml';
    $gz_file_path = $feed_dir.'skroutz.xml.gz';

    $xml->save($xml_file_path);

    if (function_exists('gzencode')) {
        $xml_content = file_get_contents($xml_file_path);
        if ($xml_content !== false) {
            $gz_content = gzencode($xml_content, 9);
            if ($gz_content !== false) {
                file_put_contents($gz_file_path, $gz_content);
            } else {
                error_log('Failed to gzip content for feed.');
            }
        } else {
            error_log('Failed to read XML content for feed compression.');
        }
    } else {
        error_log('gzencode function is not available. Cannot create gzipped XML feed.');
    }
}

xmlfeed_generate_xml();
