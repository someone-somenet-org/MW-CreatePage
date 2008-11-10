<?php
# Not a valid entry point, skip unless MEDIAWIKI is defined
if (!defined('MEDIAWIKI')) {
	echo <<<EOT
To install my extension, put the following line in LocalSettings.php:
require_once( "$IP/extensions/CreatePage/CreatePage.php" );
EOT;
	exit( 1 );
}

$dir = dirname(__FILE__);

$wgAutoloadClasses['CreatePage'] = dirname(__FILE__) . '/SpecialCreatePage.php';
$wgExtensionMessagesFiles['CreatePage'] = $dir . '/CreatePage.i18n.php';
$wgSpecialPages[ 'CreatePage' ] = 'CreatePage';
$wgHooks['LanguageGetSpecialPageAliases'][] = 'efCreatePageLocalizedPageName';


$wgExtensionCredits['specialpage'][] = array(
	'name' => 'CreatePage',
	'description' => 'This special page allows you to easily create new pages which follow our naming-convention',
	'version' => '1.0.1-1.12.0',
	'author' => 'Mathias Ertl',
	'url' => 'http://pluto.htu.tuwien.ac.at/devel_wiki/CreatePage',
);

function efCreatePageLocalizedPageName( &$specialPageArray, $code) {
	wfLoadExtensionMessages('CreatePage');
	$textMain = wfMsgForContent( 'createpage' );
	$textUser = wfMsg('createpage');

	# Convert from title in text form to DBKey and put it into the alias array:
	$titleMain = Title::newFromText( $textMain );
	$titleUser = Title::newFromText( $textUser );
	$specialPageArray['CreatePage'][] = $titleMain->getDBKey();
	$specialPageArray['CreatePage'][] = $titleUser->getDBKey();

	return true;
}

?>
