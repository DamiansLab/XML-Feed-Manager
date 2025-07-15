<?php
/**
 * Admin Controller για τη διαχείριση του XML Feed Manager
 */
class AdminXmlFeedManagerConfigureController extends ModuleAdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->bootstrap = true;
    }

    public function postProcess()
    {
        // Δημιουργία Google XML feed
        if (Tools::isSubmit('generate_google')) {
            include_once(_PS_MODULE_DIR_.'xmlfeedmanager/generate-google-feed.php');
            googlefeed_generate_xml();
            $this->confirmations[] = 'Το Google XML αρχείο δημιουργήθηκε επιτυχώς.';
        }

        // Αποθήκευση ρυθμίσεων module
        if (Tools::isSubmit('submitXmlFeedManagerSettings')) {
            Configuration::updateValue('XMLFEED_CATEGORY_IDS', implode(',', Tools::getValue('categories-tree', [])));
            Configuration::updateValue('XMLFEED_EXCLUDE_IDS', Tools::getValue('XMLFEED_EXCLUDE_IDS'));
            Configuration::updateValue('XMLFEED_CLEAN_HTML', (int)Tools::getValue('XMLFEED_CLEAN_HTML'));
            Configuration::updateValue('XMLFEED_FEED_FIELDS', implode(',', Tools::getValue('XMLFEED_FEED_FIELDS', [])));
			Configuration::updateValue(
				'XMLFEEDMANAGER_EXCLUDED_IDS_SKROUTZ',
				implode(',', Tools::getValue('excluded_ids_skroutz', []))
			);

			Configuration::updateValue(
				'XMLFEEDMANAGER_EXCLUDED_IDS_GOOGLE',
				implode(',', Tools::getValue('excluded_ids_google', []))
			);
						$this->confirmations[] = 'Οι ρυθμίσεις αποθηκεύτηκαν.';
					}

					// Δημιουργία XML feed
					if (Tools::isSubmit('generate_now')) {
						include_once(_PS_MODULE_DIR_.'xmlfeedmanager/generate-skroutz-feed.php');
						xmlfeed_generate_xml();
						$this->confirmations[] = 'Το XML αρχείο δημιουργήθηκε επιτυχώς.';
					}
				}

    public function initContent()
    {
        parent::initContent();

        // Λήψη επιλεγμένων κατηγοριών
        $selected_categories_raw = Configuration::get('XMLFEED_CATEGORY_IDS');
        $selected_categories = $selected_categories_raw ? explode(',', $selected_categories_raw) : [];

        // Εμφάνιση κατηγοριών με δενδροειδή μορφή
        $tree = new HelperTreeCategories('categories-tree');
        $tree->setUseCheckBox(true)
             ->setFullTree(true)
             ->setInputName('categories-tree')
             ->setRootCategory((int)Configuration::get('PS_ROOT_CATEGORY'))
             ->setSelectedCategories($selected_categories);

        // Πληροφορίες Skroutz XML
        $feed_path = _PS_MODULE_DIR_.'xmlfeedmanager/skroutz_export/skroutz.xml';
        $last_generated = file_exists($feed_path) ? date("Y-m-d H:i:s", filemtime($feed_path)) : '';
        $last_size = file_exists($feed_path) ? round(filesize($feed_path) / 1024, 2) : 0;
        $feed_url = file_exists($feed_path)
            ? $this->context->shop->getBaseURL(true).'modules/xmlfeedmanager/skroutz_export/skroutz.xml'
            : '';

        // Πληροφορίες Google XML
        $google_feed_path = _PS_MODULE_DIR_.'xmlfeedmanager/google_export/google.xml';
        $google_last_generated = file_exists($google_feed_path) ? date("Y-m-d H:i:s", filemtime($google_feed_path)) : '';
        $google_last_size = file_exists($google_feed_path) ? round(filesize($google_feed_path) / 1024, 2) : 0;
        $google_feed_url = file_exists($google_feed_path)
            ? $this->context->shop->getBaseURL(true).'modules/xmlfeedmanager/google_export/google.xml'
            : '';

        // Λήψη διαθέσιμων και επιλεγμένων πεδίων για XML feed
        $all_fields = $this->getAvailableExportFields();
        $selected_fields = explode(',', Configuration::get('XMLFEED_FEED_FIELDS'));

		// Λήψη αποθηκευμένων εξαιρούμενων IDs
		$excluded_ids_skroutz = explode(',', Configuration::get('XMLFEEDMANAGER_EXCLUDED_IDS_SKROUTZ'));
		$excluded_ids_google = explode(',', Configuration::get('XMLFEEDMANAGER_EXCLUDED_IDS_GOOGLE'));

		// Λήψη προϊόντων για τα dropdowns
		$product_list = Product::getProducts(
			$this->context->language->id,
			0, 0, 'name', 'asc', false, true
		);

		// Προετοιμασία επιλογών για select
		$product_options = [];
		foreach ($product_list as $product) {
			$product_options[] = [
				'id_product' => $product['id_product'],
				'name' => $product['name']
			];
		}

        // Ανάθεση μεταβλητών στο Smarty για το template
        $this->context->smarty->assign([
            'categories_tree' => $tree->render(),
            'excluded_ids' => Configuration::get('XMLFEED_EXCLUDE_IDS'),
            'available_fields' => $all_fields,
            'selected_fields' => $selected_fields,
            'last_generated' => $last_generated,
            'last_size' => $last_size,
            'feed_url' => $feed_url,
            'google_feed_url' => $google_feed_url,
            'google_last_generated' => $google_last_generated,
            'google_last_size' => $google_last_size,
			'product_options' => $product_options,
			'excluded_ids_skroutz' => $excluded_ids_skroutz,
			'excluded_ids_google' => $excluded_ids_google,
            'clean_html' => (int)Configuration::get('XMLFEED_CLEAN_HTML'),
        ]);

        // Απόδοση template
        $this->content = $this->context->smarty->fetch(_PS_MODULE_DIR_.'xmlfeedmanager/views/templates/admin/configure.tpl');
        $this->context->smarty->assign('content', $this->content);
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia();
        $this->addJS(_PS_JS_DIR_.'jquery/plugins/treeview-categories/jquery.treeview-categories.js');
        $this->addCSS(_PS_JS_DIR_.'jquery/plugins/treeview-categories/jquery.treeview-categories.css');
    }

    /**
     * Ορισμός των διαθέσιμων πεδίων εξαγωγής
     */
    private function getAvailableExportFields()
    {
        return [
            'id' => 'ID Προϊόντος',
            'name' => 'Όνομα Προϊόντος',
            'description_short' => 'Σύντομη Περιγραφή',
            'description' => 'Πλήρης Περιγραφή',
            'price' => 'Τιμή με ΦΠΑ',
            'price_without_vat' => 'Τιμή χωρίς ΦΠΑ',
            'ean13' => 'EAN13',
            'reference' => 'MPN / Κωδικός Κατασκευαστή',
            'weight' => 'Βάρος',
            'quantity' => 'Διαθεσιμότητα',
            'manufacturer_name' => 'Κατασκευαστής',
            'category_name' => 'Κατηγορία',
            'image' => 'Εικόνα Προϊόντος',
            'dimensions' => 'Διαστάσεις (ΠxΥxΒ)',
            'availability' => 'Διαθεσιμότητα (Λεκτική)',
        ];
    }
}
