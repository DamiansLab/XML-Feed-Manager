<?php
/**
 * XML Feed Manager - Generates XML product feeds for Skroutz and Google Merchant for PrestaShop.
 *
 * @author    DamiansLab <https://damianslab.gr>
 * @copyright 2025 DamiansLab (Ηλίας Δαμιανίδης)
 * @license   Proprietary, see license.txt (DamiansLab End User License Agreement)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Frontend controller που εκθέτει το XML Feed
 */
class xmlfeedmanagerModuleFrontController extends ModuleFrontController {
    public function initContent() {
        parent::initContent();

        // Ορισμός Content-Type για XML
        header('Content-Type: text/xml; charset=utf-8');

        // Πλήρης διαδρομή προς το αρχείο XML: ?type=google για Google, αλλιώς Skroutz
        if (Tools::getValue('type') === 'google') {
            $xml_path = _PS_MODULE_DIR_ . 'xmlfeedmanager/google_export/google.xml';
        } else {
            $xml_path = _PS_MODULE_DIR_ . 'xmlfeedmanager/skroutz_export/skroutz.xml';
        }

        // Αν υπάρχει, εμφανίζεται το περιεχόμενο του αρχείου XML
        if (file_exists($xml_path)) {
            readfile($xml_path);
        } else {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<products></products>'; // Κενό XML
        }

        // Τερματίζει την εκτέλεση για να μη φορτωθεί theme/template
        exit;
    }
}
