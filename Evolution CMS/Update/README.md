# Script Updater for 1.4 to 2 or 3 version for Evolution CMS

## Reason
1. The current site on Evolution CMS 1.4 cannot be normally upgraded to versions 2 and 3 using a standard update.
2. The main developer of Evolution CMS was a non-advocate "Velikoukr" (Ukranian) Dmitry "Khohol" Lukyanenko, so the 
   community switched to the fork of Evolution CMS - Community Edition - 
   `https://github.com/evocms-community/evolution`.

## Changes
- Changed redirect after download to `install/index.php` for sites on nginx + php-fpm.
- Updated url for downloading update cms on fork of Evolution CMS - Community Edition.
- Removed the language "Velikoukrov" (Ukranian) from the script updater.

## Usage
Just put the `updater.php` script in the root of the site and run `https://example.com/updater.php` in the browser and 
follow the instructions.

## Original
Original taken from `https://extras.evo.im/packages/core/migrate-1.4.12-and-2.0.4-to-3.0.html`