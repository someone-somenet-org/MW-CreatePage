<?php
# Not a valid entry point, skip unless MEDIAWIKI is defined
if (!defined('MEDIAWIKI')) {
	echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "$IP/extensions/CreatePage/CreatePage.php" );
EOT;
	exit(1);
}

$wgAutoloadClasses['SpecialCreatePage'] = __DIR__ . '/SpecialCreatePage.php';
$wgExtensionMessagesFiles['CreatePage'] = __DIR__ . '/CreatePage.i18n.php';
$wgExtensionMessagesFiles['CreatePageAlias'] = __DIR__ . '/CreatePage.alias.php';
$wgSpecialPages['CreatePage'] = 'SpecialCreatePage';


$wgExtensionCredits['specialpage'][] = array(
	'name' => 'CreatePage',
	'description' => 'This special page allows you to easily create new pages which follow our naming-convention',
	'version' => '1.0.4-1.21.0',
	'author' => 'Mathias Ertl',
	'url' => 'https://fs.fsinf.at/wiki/CreatePage',
);

?>
