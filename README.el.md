# XML Feed Manager

**Συγγραφέας:** [DamiansLab](https://damianslab.gr) · Έκδοση 2.1.0 · PrestaShop 1.7 / 8 · Γλώσσες: Ελληνικά, English

# 📘 Manual Χρήσης – XML Feed Manager

## 🔍 Τι είναι το XML Feed Manager

Το **XML Feed Manager** είναι ένα module για PrestaShop που δημιουργεί αυτόματα αρχεία XML συμβατά με τις απαιτήσεις των πλατφορμών **Skroutz** και **Google Merchant**.  
Στόχος του είναι να εξάγει **μόνο τα ενεργά και διαθέσιμα προϊόντα** του καταστήματος με τα απαραίτητα εμπορικά και τεχνικά στοιχεία.

---

## ⚙️ Λειτουργίες & Χαρακτηριστικά

### Υποστηριζόμενα XML Feeds:
- 🟦 Skroutz Feed (`generate-skroutz-feed.php`)
- 🟥 Google Merchant Feed (`generate-google-feed.php`)

### Περιλαμβανόμενα πεδία για κάθε προϊόν:
- Όνομα προϊόντος
- Σύντομη & Πλήρης περιγραφή (με δυνατότητα καθαρισμού HTML)
- Τιμή (με ή χωρίς ΦΠΑ)
- Διαθεσιμότητα (stock & λεκτική)
- Κατηγορία (με πλήρη ιεραρχία)
- Εικόνα προϊόντος
- EAN
- MPN / Reference
- Βάρος
- Ποσότητα αποθέματος
- Σύνδεσμος προς σελίδα προϊόντος

---

## 🧠 Πώς Λειτουργεί

Κατά την εκτέλεση (χειροκίνητα ή με cron), το module:

- Φιλτράρει προϊόντα βάσει ρυθμίσεων:
  - Κατηγορίες
  - Εξαιρούμενα προϊόντα (με dropdown επιλογή)
- Ελέγχει ενεργά & διαθέσιμα προϊόντα
- Καθαρίζει HTML/SVG tags από τις περιγραφές (αν έχει ενεργοποιηθεί)
- Δημιουργεί XML με σωστή κωδικοποίηση και format
- Αποθηκεύει τα αρχεία XML τοπικά στον server

### Ονόματα functions:
- `xmlfeed_generate_xml()` → για Skroutz
- `googlefeed_generate_xml()` → για Google Merchant

---

## 🧪 Οδηγίες Χρήσης (για Διαχειριστές)

### Πρόσβαση στο Admin Panel:
- Modules > XML Feed Manager > Configure

### Ρυθμίσεις:
- ✅ Επιλογή Κατηγοριών (multi-select tree)
- ❌ Εξαίρεση Προϊόντων:
  - μέσω **dropdown με αναζήτηση & πολλαπλή επιλογή**
  - ξεχωριστά για **Skroutz** και **Google**
- ✅ Επιλογή πεδίων εξαγωγής
- ✅ Καθαρισμός HTML περιγραφών
- ✅ Επιλογή τύπου Feed

### Κουμπιά:
- ▶ Δημιουργία Skroutz XML
- ▶ Δημιουργία Google XML
- 🕒 Εμφάνιση ημερομηνίας & μεγέθους τελευταίας δημιουργίας

---

✅ Τεχνικές Λεπτομέρειες
Απαιτεί PHP CLI

Αποθηκεύει τα αρχεία XML στο:

/modules/xmlfeedmanager/skroutz_export/

/modules/xmlfeedmanager/google_export/

Encoding: UTF-8

Το Google XML χρησιμοποιεί namespaces g: κατάλληλα για το Merchant Center

🧼 Καθαρισμός HTML Περιγραφών
Αν η επιλογή είναι ενεργή, η συνάρτηση cleanHtmlToSingleLine():

Αφαιρεί εικόνες (<img>), SVG, icons, tags

Επιστρέφει καθαρό κείμενο σε 1 γραμμή για τις περιγραφές

---

## ⏱️ Ρύθμιση Cron Job

Για αυτόματη δημιουργία XML αρχείων μέσω cron:

```bash
php /path/to/prestashop/modules/xmlfeedmanager/generate-skroutz-feed.php
php /path/to/prestashop/modules/xmlfeedmanager/generate-google-feed.php

---

## Δομή αρχείων

```
xmlfeedmanager/
├── xmlfeedmanager.php
├── config.xml
├── license.txt
├── README.md / README.el.md
├── CHANGELOG.md
├── logo.png
├── generate-skroutz-feed.php
├── generate-google-feed.php
├── controllers/   views/   translations/
```

## Credits και άδεια χρήσης

Με άδεια χρήσης, όχι πώληση, βάσει της DamiansLab End User License Agreement: δείτε το [license.txt](license.txt). Μία άδεια καλύπτει ένα παραγωγικό κατάστημα· η αντιγραφή, η αναδιανομή και η τροποποίηση δεν επιτρέπονται.

Select2 4.0.13 (Kevin Brown, Igor Vaynberg και συντελεστές), άδεια MIT, φορτώνεται από το cdnjs και παραμένει υπό τη δική του άδεια.

## Υποστήριξη και επικοινωνία

**DamiansLab &mdash; Web Development & 3D Printing**
Ηλίας Δαμιανίδης · Full stack developer

- Website: <https://damianslab.gr>
- Contact: <https://damianslab.gr/contact>
- Email: <iliaskdamianidis@gmail.com>
- Instagram: <https://instagram.com/damianslab>
- GitHub: <https://github.com/DamiansLab>
