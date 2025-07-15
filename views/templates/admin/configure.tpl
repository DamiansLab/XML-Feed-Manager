<!-- Πάνελ διαχείρισης ρυθμίσεων XML Feed Manager -->
<div class="panel">
  <h3><i class="icon icon-list"></i> Ρυθμίσεις XML Feed Manager</h3>

  <form method="post">
    <!-- Πεδίο εξαιρούμενων προϊόντων -->
    <!-- Νέα Πεδία Εξαιρέσεων με Select -->
<div class="form-group">
  <label for="excluded_ids_skroutz">Εξαιρούμενα Προϊόντα για Skroutz</label>
  <select name="excluded_ids_skroutz[]" id="excluded_ids_skroutz" multiple class="select2 form-control">
    {foreach from=$product_options item=product}
      <option value="{$product.id_product}" {if in_array($product.id_product, $excluded_ids_skroutz)}selected{/if}>
        [{$product.id_product}] {$product.name|escape:'html':'UTF-8'}
      </option>
    {/foreach}
  </select>
</div>

<div class="form-group">
  <label for="excluded_ids_google">Εξαιρούμενα Προϊόντα για Google</label>
  <select name="excluded_ids_google[]" id="excluded_ids_google" multiple class="select2 form-control">
    {foreach from=$product_options item=product}
      <option value="{$product.id_product}" {if in_array($product.id_product, $excluded_ids_google)}selected{/if}>
        [{$product.id_product}] {$product.name|escape:'html':'UTF-8'}
      </option>
    {/foreach}
  </select>
</div>

    <!-- Δέντρο επιλογής κατηγοριών -->
    <div class="form-group">
      <label>Επιλογή Κατηγοριών για εξαγωγή</label>
      {$categories_tree nofilter}
    </div>
	
	<!-- Επιλογή πεδίων προς εξαγωγή -->
    <div class="form-group">
      <label>Επιλέξτε ποια πεδία θέλετε να περιλαμβάνονται στο XML</label>
      <div class="checkbox">
        {foreach from=$available_fields key=field_key item=field_label}
          <label>
            <input type="checkbox" name="XMLFEED_FEED_FIELDS[]" value="{$field_key}" 
                   {if in_array($field_key, $selected_fields)}checked="checked"{/if} />
            {$field_label}
          </label><br>
        {/foreach}
      </div>
    </div>

    <!-- Ρύθμιση καθαρισμού HTML -->
    <div class="form-group">
        <label class="control-label col-lg-3">Καθαρισμός HTML από περιγραφές;</label>
        <div class="col-lg-9">
            <span class="switch prestashop-switch fixed-width-lg">
                <input type="radio" name="XMLFEED_CLEAN_HTML" id="XMLFEED_CLEAN_HTML_on" value="1" {if $clean_html}checked="checked"{/if} />
                <label for="XMLFEED_CLEAN_HTML_on">Ναι</label>
                <input type="radio" name="XMLFEED_CLEAN_HTML" id="XMLFEED_CLEAN_HTML_off" value="0" {if !$clean_html}checked="checked"{/if} />
                <label for="XMLFEED_CLEAN_HTML_off">Όχι</label>
                <a class="slide-button btn"></a>
            </span>
            <p class="help-block">Αν είναι ενεργό, η περιγραφή θα μετατραπεί σε καθαρό κείμενο (χωρίς εικόνες, icons και HTML).</p>
        </div>
    </div>

    <!-- Κουμπιά ενεργειών -->
    <button type="submit" name="submitXmlFeedManagerSettings" class="btn btn-primary">
      <i class="process-icon-save"></i> Αποθήκευση Ρυθμίσεων
    </button>

    <button type="submit" name="generate_now" class="btn btn-primary">
        Δημιουργία Skroutz XML
    </button>
	&nbsp;
    <button type="submit" name="generate_google" class="btn btn-success">
        Δημιουργία Google XML
    </button>
  </form>
  
<!-- autocomplete -->
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Ενεργοποίηση Select2 -->
<script>
  $(document).ready(function () {
    $('#excluded_ids_skroutz, #excluded_ids_google').select2({
      width: '100%',
      placeholder: 'Επιλέξτε προϊόντα...',
      allowClear: true
    });
  });
</script>


  <hr />

  <!-- Πληροφορίες XML αρχείων -->
  <div class="row">
    <div class="col-md-6">
      <p>
        <strong>🔗 Skroutz XML:</strong> <a href="{$feed_url}" target="_blank">{$feed_url}</a><br>
        <strong>📅 Τελευταία δημιουργία:</strong> {$last_generated}<br>
        <strong>📦 Μέγεθος αρχείου:</strong> {$last_size} KB
      </p>
    </div>
    <div class="col-md-6">
      <p>
        <strong>🔗 Google XML:</strong> <a href="{$google_feed_url}" target="_blank">{$google_feed_url}</a><br>
        <strong>📅 Τελευταία δημιουργία:</strong> {$google_last_generated}<br>
        <strong>📦 Μέγεθος αρχείου:</strong> {$google_last_size} KB
      </p>
    </div>
  </div>
</div>

<div class="panel">
  <h3><i class="icon-book"></i> Οδηγίες Χρήσης</h3>
  <p><strong>Τι κάνει το module:</strong> Δημιουργεί XML feed για συγχρονισμό με διάφορες online Πλατφόρμες.</p>
  <p><strong>Πώς λειτουργεί:</strong> Επιλέγεις κατηγορίες & εξαιρέσεις, και με ένα κλικ δημιουργείται το XML.</p>
  <p><strong>Αρχεία εξόδου:</strong>
    <code>/modules/xmlfeedmanager/skroutz_export/skroutz.xml</code> και <code>/modules/xmlfeedmanager/google_export/google.xml</code></p>
  <p><strong>Αυτοματοποίηση:</strong> Cron job: 
    <code>php /path_to_prestashop/modules/xmlfeedmanager/generate-skroutz-feed.php</code> και 
    <code>php /path_to_prestashop/modules/xmlfeedmanager/generate-google-feed.php</code>
  </p>
  <p><strong>Περισσότερα:</strong> <a href="https://damianslab.gr" target="_blank">damianslab.gr</a></p>
</div>
