{**
 * XML Feed Manager - Generates XML product feeds for Skroutz and Google Merchant for PrestaShop.
 *
 * @author    DamiansLab <https://damianslab.gr>
 * @copyright 2025 DamiansLab (Ηλίας Δαμιανίδης)
 * @license   Proprietary, see license.txt (DamiansLab End User License Agreement)
 *}
<div class="dl-header">
  <div class="dl-header__title">
    <img class="dl-header__logo" src="{$dl_logo|escape:'html':'UTF-8'}" alt="{$dl_name|escape:'html':'UTF-8'}" width="56" height="56">
    <div>
      <h2>{$dl_name|escape:'html':'UTF-8'} <small>v{$dl_version|escape:'html':'UTF-8'}</small></h2>
      {if $dl_tagline}<p>{$dl_tagline|escape:'html':'UTF-8'}</p>{/if}
    </div>
  </div>
  {if $dl_button}
    <a class="btn btn-default dl-header__button" href="{$dl_button.url|escape:'html':'UTF-8'}">
      <i class="{$dl_button.icon|escape:'html':'UTF-8'}"></i> {$dl_button.label|escape:'html':'UTF-8'}
    </a>
  {/if}
</div>
