<?php
# Not a valid entry point, skip unless MEDIAWIKI is defined
if (!defined('MEDIAWIKI')) {
	echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "$IP/extensions/CreatePage/CreatePage.php" );
EOT;
	exit( 1 );
}

$wgAutoloadClasses['CreatePage'] = dirname(__FILE__) . '/SpecialCreatePage.php';
$wgSpecialPages[ 'CreatePage' ] = 'CreatePage';
$wgHooks['LoadAllMessages'][] = 'CreatePage::loadMessages';
$wgHooks['LangugeGetSpecialPageAliases'][] = 'CreatePage_LocalizedPageName';

$wgExtensionCredits['specialpage'][] = array(
	'name' => 'CreatePage',
	'description' => 'This special page allows you to easily create new pages which follow our naming-convention',
	'version' => '0.8.1-1.11.0',
	'author' => 'Mathias Ertl',
	'url' => 'http://pluto.htu.tuwien.ac.at/devel_wiki/index.php/CreatePage',
);

function CreatePage_LocalizedPageName( &$specialPageArray, $code) {
	CreatePage::loadMessages();
	$text = wfMsg('createpage');

	# Convert from title in text form to DBKey and put it into the alias array:
	$title = Title::newFromText( $text );
	$specialPageArray['CreatePage'][] = $title->getDBKey();

	return true;
}

?>
