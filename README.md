Dokuwiki AdSense plugin
=======================

Don't use. Pending work.

Install
-------

1. `cd dokuwiki/lib/plugins`
2. `git clone https://github.com/dmeziere/dokuwiki-adsense-plugin adsense`

Use
---

First indicates your Google AdSense client ID in Admin / Configuration Settings / Plugin / Adsense. A checkbox allows you to restrict ads displaying to anonymous users. It allows you to filter registered users page views from the statistics.

If you want to use the plugin from within a page, simply put the tag `{ADSENSE 123456789}`, replacing the numeric part with the ad slot ID that you wish to display. It allows you to use different ads slots at different places of your website.

If you want to use the plugin from within a template, and display the same slots on every page, add the following to your template's `main.php` or whether better suits your needs :

```php
if (!$conf['plugin']['adsense']['adsense_restrict'] || !isset($_SERVER['REMOTE_USER'])) {
  require_once DOKU_INC . '/lib/plugins/adsense/syntax.php';
  echo syntax_plugin_adsense::_adsense($conf['plugin']['adsense']['adsense_client'], '123456789');
}
```
