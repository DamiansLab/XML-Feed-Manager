<?php
// Απαγορεύει την άμεση πρόσβαση στο αρχείο
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Κύρια κλάση του module XMLFEED
 */
class xmlfeedmanager extends Module
{
    public function __construct()
    {
        $this->name = 'xmlfeedmanager';
        $this->tab = 'seo';
        $this->version = '2.1.0';
        $this->author = 'Damian';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('XML Feed Manager');
        $this->description = $this->l('Δημιουργεί XML feed για συγχρονισμό με διάφορες online Πλατφόρμες');
    }

    /**
     * Εκτελείται κατά την εγκατάσταση του module
     */
    public function install()
    {
        return parent::install()
            && $this->registerHook('displayBackOfficeHeader')
            && Configuration::updateValue('XMLFEED_EXCLUDE_IDS', '')
            && Configuration::updateValue('XMLFEED_CATEGORY_IDS', '');
    }
	public function hookDisplayBackOfficeHeader($params)
    {
        // Προαιρετικά: μπορείς να φορτώσεις custom CSS/JS αν χρειαστεί
    }
    /**
     * Εκτελείται κατά την απεγκατάσταση του module
     */
    public function uninstall()
    {
        return parent::uninstall()
            && Configuration::deleteByName('XMLFEED_EXCLUDE_IDS')
            && Configuration::deleteByName('XMLFEED_CATEGORY_IDS');
    }

    /**
     * Ανακατεύθυνση στη διαχείριση ρυθμίσεων του module
     */
    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminXmlFeedManagerConfigure'));
    }
}
