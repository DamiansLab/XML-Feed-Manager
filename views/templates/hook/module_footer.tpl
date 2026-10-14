{**
 * XML Feed Manager - Generates XML product feeds for Skroutz and Google Merchant for PrestaShop.
 *
 * @author    DamiansLab <https://damianslab.gr>
 * @copyright 2025 DamiansLab (Ηλίας Δαμιανίδης)
 * @license   Proprietary, see license.txt (DamiansLab End User License Agreement)
 *}
<div class="dl-footer">
  <div class="dl-footer__main">
    <strong>{$dl_name|escape:'html':'UTF-8'}</strong> v{$dl_version|escape:'html':'UTF-8'}
    &middot; {l s='by' mod='xmlfeedmanager'} <a href="https://damianslab.gr" target="_blank" rel="noopener">DamiansLab</a>
    &mdash; Web Development &amp; 3D Printing
  </div>
  <div>Ηλίας Δαμιανίδης &middot; Full stack developer</div>
  <div class="dl-footer__links">
    <a href="https://damianslab.gr" target="_blank" rel="noopener">{l s='Website' mod='xmlfeedmanager'}</a>
    <a href="https://damianslab.gr/contact" target="_blank" rel="noopener">{l s='Contact' mod='xmlfeedmanager'}</a>
    <a href="mailto:iliaskdamianidis@gmail.com">iliaskdamianidis@gmail.com</a>
    <a href="https://instagram.com/damianslab" target="_blank" rel="noopener">Instagram</a>
    <a href="https://github.com/DamiansLab" target="_blank" rel="noopener">GitHub</a>
    {foreach from=$dl_links item=link}
      <a href="{$link.url|escape:'html':'UTF-8'}" target="_blank" rel="noopener">{$link.label|escape:'html':'UTF-8'}</a>
    {/foreach}
  </div>
  <div class="dl-footer__legal">
    &copy; {$dl_year|escape:'html':'UTF-8'} DamiansLab (Ηλίας Δαμιανίδης){if $dl_disclaimer} &middot; {$dl_disclaimer|escape:'html':'UTF-8'}{/if}
  </div>
</div>
