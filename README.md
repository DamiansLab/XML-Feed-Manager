Τρέχουσα έκδοση: 2.1.0

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

## ⏱️ Ρύθμιση Cron Job

Για αυτόματη δημιουργία XML αρχείων μέσω cron:

```bash
php /path/to/prestashop/modules/xmlfeedmanager/generate-skroutz-feed.php
php /path/to/prestashop/modules/xmlfeedmanager/generate-google-feed.php


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
