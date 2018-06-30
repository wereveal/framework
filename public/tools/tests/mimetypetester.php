<?php /** @noinspection ForgottenDebugOutputInspection */

use Ritc\Library\Helper\MimeTypeHelper;

include $_SERVER['DOCUMENT_ROOT'] . '/setup.php';
include BASE_PATH . '/src/apps/Ritc/Library/Helper/MimeTypeHelper.php';

$a_mime_types = MimeTypeHelper::mapMimeToExtension();
print '<pre>';
print_r($a_mime_types);
print '</pre>';
print '<br>';
$mime_type = MimeTypeHelper::getMimeFromFile(PUBLIC_PATH . '/tools/tests/table.html');
print $mime_type . '<br>';
$a_mime_ext = MimeTypeHelper::getExtensionFromMime($mime_type);
print_r($a_mime_ext);
print '<br>';
$gotten_mime = MimeTypeHelper::getMimeFromExtension($a_mime_ext[0]);
print $gotten_mime . "\n";
print MimeTypeHelper::isExtensionForMime($gotten_mime, $a_mime_ext[0]);
print '<br>';
print MimeTypeHelper::isMimeForExtension($gotten_mime, $a_mime_ext[0]);
print '<br>';