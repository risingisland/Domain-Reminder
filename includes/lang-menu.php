<?php
/**
 * lang-menu.php
 * Dynamically builds language options from all lang.*.php files in /langs/
 * Each lang file must define $lang['LANG_CODE'] and $lang['LANG_LONG']
 */

$langs_dir = __DIR__ . '/../langs/';
$lang_files = glob($langs_dir . 'lang.*.php');

if ($lang_files) {
    sort($lang_files); // alphabetical order
    foreach ($lang_files as $file) {
        // Load file in isolated scope to extract LANG_CODE and LANG_LONG
        $tmp = (function() use ($file) {
            $lang = [];
            include $file;
            return $lang;
        })();

        if (!empty($tmp['LANG_CODE']) && !empty($tmp['LANG_LONG'])) {
            $code  = htmlspecialchars($tmp['LANG_CODE']);
            $label = htmlspecialchars($tmp['LANG_LONG']);
            echo "\t<option value=\"settings.php?lang={$code}\">{$label}</option>\n";
        }
    }
}
?>