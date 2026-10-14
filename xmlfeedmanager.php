<?php
/**
 * XML Feed Manager - Generates XML product feeds for Skroutz and Google Merchant for PrestaShop.
 *
 * @author    DamiansLab <https://damianslab.gr>
 * @copyright 2025 DamiansLab (Ηλίας Δαμιανίδης)
 * @license   Proprietary, see license.txt (DamiansLab End User License Agreement)
 */
// Απαγορεύει την άμεση πρόσβαση στο αρχείο
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Κύρια κλάση του module XMLFEED
 */
class xmlfeedmanager extends Module
{
    /** Year of the first release, printed in the footer. */
    const COPYRIGHT_YEAR = '2025';

    public function __construct()
    {
        $this->name = 'xmlfeedmanager';
        $this->tab = 'seo';
        $this->version = '2.1.0';
        $this->author = 'DamiansLab';
        $this->author_uri = 'https://damianslab.gr';
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

    /**
     * Shared DamiansLab header ('header') and footer ('footer') of every back office page of the module.
     * See DAMIANSLAB-MODULE-STANDARD.md.
     */
    public function renderChrome($part, array $vars = array())
    {
        $this->context->smarty->assign(array_merge(array(
            'dl_logo' => $this->_path . 'logo.png',
            'dl_name' => $this->displayName,
            'dl_version' => $this->version,
            'dl_year' => self::COPYRIGHT_YEAR,
            'dl_tagline' => '',
            'dl_button' => null,
            'dl_links' => array(),
            'dl_disclaimer' => '',
        ), $vars));

        return $this->display(__FILE__, 'module_' . $part . '.tpl');
    }
}
