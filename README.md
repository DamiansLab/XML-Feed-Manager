📘 Manual Χρήσης – XML Feed Manager
🔍 Τι είναι το XML Feed Manager
Το XML Feed Manager είναι ένα module για PrestaShop που δημιουργεί αυτόματα αρχεία XML συμβατά με τις απαιτήσεις των πλατφορμών Skroutz και Google Merchant. Στόχος του είναι να εξάγει μόνο τα ενεργά και διαθέσιμα προϊόντα του καταστήματος με τα βασικά εμπορικά και τεχνικά στοιχεία.
⚙️ Λειτουργίες & Χαρακτηριστικά
Υποστηριζόμενα XML Feeds:
- Skroutz Feed (generate-skroutz-feed.php)
- Google Merchant Feed (generate-google-feed.php)
Περιλαμβανόμενα πεδία για κάθε προϊόν:
- Όνομα προϊόντος
- Περιγραφή (καθαρισμένη από HTML/SVG)
- Τιμή (με φόρους)
- Διαθεσιμότητα (stock)
- Κατηγορία προϊόντος
- Εικόνα
- EAN
- MPN (από το reference)
- Βάρος
- Ποσότητα αποθέματος
- Σύνδεσμος προς σελίδα προϊόντος
Δομή αρχείων:
- /modules/xmlfeedmanager/generate-skroutz-feed.php → Δημιουργία Skroutz XML
- /modules/xmlfeedmanager/generate-google-feed.php → Δημιουργία Google Merchant 
🧠 Πώς λειτουργεί
Κατά την εκτέλεση της λειτουργίας (χειροκίνητα ή μέσω cron), το module:
- Φιλτράρει προϊόντα βάσει ρυθμίσεων (κατηγορίες, εξαιρέσεις)
- Ελέγχει ενεργότητα και διαθεσιμότητα προϊόντων
- Καθαρίζει HTML/SVG tags από τις περιγραφές
- Δημιουργεί XML με σωστό encoding και tags
- Αποθηκεύει το αρχείο XML τοπικά στον server
Ονόματα functions:
- skroutzfeed_generate_xml() → για Skroutz
- xmlfeedmanager_generate_xml() → για Google Merchant
🧪 Οδηγίες Χρήσης (για Διαχειριστές)
Πρόσβαση στο Admin Panel:
1. Modules > XML Feed Manager > Configure
Ρυθμίσεις:
- Επιλογή Κατηγοριών (multi-select)
- Εξαίρεση Προϊόντων (IDs, με κόμμα)
- Επιλογή πεδίων εξαγωγής
- Καθαρισμός HTML περιγραφών
- Επιλογή τύπου Feed (Skroutz ή Google Merchant)
Κουμπιά στο Admin:
- Δημιουργία Skroutz XML
- Δημιουργία Google XML
- Εμφάνιση τελευταίας δημιουργίας και μεγέθους αρχείου
⏱️ Ρύθμιση Cron Job
Για αυτόματη δημιουργία XML αρχείων με cron job:
php /path/to/prestashop/modules/xmlfeedmanager/generate-skroutz-feed.php
php /path/to/prestashop/modules/xmlfeedmanager/generate-google-feed.php
Ενδεικτική ρύθμιση κάθε 2 λεπτά:
*/2 * * * * php /var/www/html/modules/xmlfeedmanager/generate-skroutz-feed.php
*/2 * * * * php /var/www/html/modules/xmlfeedmanager/generate-google-feed.php
✅ Τεχνικές Λεπτομέρειες
- Απαιτεί PHP CLI
- Αποθήκευση XML στο /feeds/
- Encoding UTF-8
- Google XML χρησιμοποιεί namespaces g:
🧼 Καθαρισμός HTML Περιγραφών
Η συνάρτηση cleanHtmlToSingleLine():
- Αφαιρεί <img>, <svg>, <i>, εικονίδια
- Διατηρεί καθαρό κείμενο σε μία γραμμή
