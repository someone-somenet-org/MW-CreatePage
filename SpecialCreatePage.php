<?php

/**
 * Entry point
 */
function wfSpecialCreatePage ($par) {
	global $wgOut;
	$page = new CreatePage();
	$page->execute($par);
}

/**
 * actual class...
 */
class CreatePage extends SpecialPage
{
	/**
	 * constructor, only does the basic stuff...
	 */
	function CreatePage() {
		self::loadMessages();
		SpecialPage::SpecialPage( wfMsg('createpage') );
	}

	function execute( $par ) {
		global $wgOut;
		$this->setHeaders();

		$wgOut->addWikiText('Stub');
	}

	/* internationalization stuff */
	function loadMessages() {
		static $messagesLoaded = false;
		global $wgMessageCache;
		if ( $messagesLoaded )
			return true;
		$messagesLoaded = true;

		require( dirname( __FILE__ ) . '/CreatePage.i18n.php' );
		foreach ( $allMessages as $lang => $langMessages ) {
			$wgMessageCache->addMessages( $langMessages, $lang );
		}
		return true;
	}
}

?>
