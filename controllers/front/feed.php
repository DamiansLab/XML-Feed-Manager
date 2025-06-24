<?php
/**
 * Frontend controller που εκθέτει το XML Feed
 */
class xmlfeedmanagerModuleFrontController extends ModuleFrontController {
    public function initContent() {
        parent::initContent();

        // Ορισμός Content-Type για XML
        header('Content-Type: text/xml; charset=utf-8');

        // Πλήρης διαδρομή προς το αρχείο XML
        $xml_path = _PS_MODULE_DIR_ . 'xmlfeedmanager/skoutz_export/skroutz.xml';
		$xml_path = _PS_MODULE_DIR_ . 'xmlfeedmanager/google_export/google.xml';

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
