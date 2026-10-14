# XML Feed Manager

**Author:** [DamiansLab](https://damianslab.gr) · Version 2.1.0 · PrestaShop 1.7 / 8 · Languages: Greek, English

Generates XML product feeds for **Skroutz** and **Google Merchant** from the active, available products of your shop. The full Greek manual is in [README.el.md](README.el.md).

## Features

- Skroutz feed (`generate-skroutz-feed.php`) and Google Merchant feed (`generate-google-feed.php`)
- Category selection and separate product exclusion lists for Skroutz and Google
- Selectable export fields and optional HTML cleaning of descriptions
- Manual generation from the back office or automatic generation by cron

## Usage

Back office: Modules > XML Feed Manager > Configure. Output files:

- `modules/xmlfeedmanager/skroutz_export/skroutz.xml`
- `modules/xmlfeedmanager/google_export/google.xml`

Cron:

```bash
php /path/to/prestashop/modules/xmlfeedmanager/generate-skroutz-feed.php
php /path/to/prestashop/modules/xmlfeedmanager/generate-google-feed.php
```

## Files

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

## Credits and license

Licensed, not sold, under the DamiansLab End User License Agreement: see [license.txt](license.txt). One license covers one production shop; copying, redistribution and modification are not allowed.

Select2 4.0.13 (Kevin Brown, Igor Vaynberg and contributors), MIT license, loaded from cdnjs and kept under its own license.

## Support and contact

**DamiansLab &mdash; Web Development & 3D Printing**
Ηλίας Δαμιανίδης · Full stack developer

- Website: <https://damianslab.gr>
- Contact: <https://damianslab.gr/contact>
- Email: <iliaskdamianidis@gmail.com>
- Instagram: <https://instagram.com/damianslab>
- GitHub: <https://github.com/DamiansLab>
