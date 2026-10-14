# Changelog

Όλες οι σημαντικές αλλαγές για το module **XML Feed Manager** καταγράφονται εδώ.

## [Unreleased]

### Πρότυπο DamiansLab
- Εφαρμογή του DamiansLab Module Standard: headers αρχείων, `index.php` guards, `author`/`author_uri`, `license.txt`, κοινό header/footer στη σελίδα διαχείρισης, `config.xml` από την κλάση.
- Νέο `README.md` (English) και `README.el.md` (ελληνικό manual).
- Τα `error_log()` αντικαταστάθηκαν από `PrestaShopLogger::addLog()`.

---

## [2.1.0] - 2025-07-15

### ✨ Νέα Χαρακτηριστικά
- Προστέθηκε υποστήριξη για εξαίρεση προϊόντων από το XML μέσω του διαχειριστικού:
  - Νέο πεδίο **"Εξαιρούμενα προϊόντα για Skroutz"** με δυνατότητα πολλαπλής επιλογής προϊόντων.
  - Νέο πεδίο **"Εξαιρούμενα προϊόντα για Google"** με παρόμοια λειτουργία.
  - Και τα δύο πεδία χρησιμοποιούν dropdown με **autocomplete (Select2)** για εύκολη χρήση.

### ⚙ Εσωτερικές Τεχνικές Αλλαγές
- Νέα Configuration keys:
  - `XMLFEEDMANAGER_EXCLUDED_IDS_SKROUTZ`
  - `XMLFEEDMANAGER_EXCLUDED_IDS_GOOGLE`
- Ανανεώθηκαν τα παρακάτω αρχεία:
  - `configure.tpl`: προστέθηκαν τα νέα πεδία με πολλαπλή επιλογή
  - `AdminXmlFeedManagerConfigureController.php`: ανάκτηση και αποθήκευση επιλογών
  - `generate-skroutz-feed.php`: εφαρμογή φίλτρου εξαιρέσεων για Skroutz feed
  - `generate-google-feed.php`: εφαρμογή φίλτρου εξαιρέσεων για Google feed

### 📅 Συντάκτης
Damian (https://damianslab.gr)

---

## [2.0.1] - Προηγούμενη σταθερή έκδοση

- Πρώτη λειτουργική έκδοση με υποστήριξη για:
  - Επιλογή πεδίων για εξαγωγή
  - Επιλογή κατηγοριών
  - Καθαρισμό HTML
  - Δημιουργία Skroutz και Google XML
