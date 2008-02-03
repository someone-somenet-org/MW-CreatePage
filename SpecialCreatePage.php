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
		$this->mIncludable = true;
	}

	function execute( $par ) {
		global $wgOut, $wgRequest;
		global $wgCreatePageNamespaces, $createPageTypes;

		$wgCreatePageNamespaces = '<option>Uni Wien<option selected>TU Wien<option>MU Wien<option>Sonstige';
		$wgCreatePageTypes = '<option>AG<option>AU<option>Ex<option selected>LU<option>PR<option>PS<option>SE<option>UE<option>VD<option>VO<option>VL<option>VU';

		$this->setHeaders();

		if ( $wgRequest->getBool('was_submitted', false ) ) {
			$ns = $wgRequest->getVal( 'namespace' );
			$newtitle = $wgRequest->getVal( 'newtitle' );
			$type = $wgRequest->getVal( 'type' );
			$suffix1 = $wgRequest->getVal( 'suffix1' );
			$suffix2 = $wgRequest->getVal( 'suffix2' );

			if ( $ns != '' && $newtitle != '' && $type != '' && $suffix1 != '' ) {

				// assemble title:
				$completeTitle = $ns . ':' . $newtitle . ' ' . $type . ' ' . '(' . $suffix1;
				if ( $suffix2 != '' ) {
					$completeTitle .= ', ' . $suffix2;
				}
				$completeTitle .= ')';


				$redir = Title::newFromText( $completeTitle );
				if ( $redir->exists() )
					$wgOut->redirect($redir->getFullURL() );
				else
					$wgOut->redirect($redir->getFullURL() . '?action=edit' );
			} else {
				$this->addText( wfMsg('missing_input') );
			}
		}

		$this->addText( wfMsg('introduction') );

// caused problems 2007-12-09
//		$wgOut->setPagetitle( wfMsg('pagetitle') );

		global $wgScript;
		$handler = $wgScript . '/' . Namespace::getCanonicalName(NS_SPECIAL) . ":" . SpecialPage::getLocalName( 'Create page' );

		$wgOut->addHTML('<form name=\'new_page\' method=\'get\' action=\'' . $handler . '\'>
				<input type="hidden" name="was_submitted" value="true">
				<table>
					<tr>
						<th>' . wfMsg('namespace_header') . '</th>
						<th>' . wfMsg('newpage_title') . '</th>
						<th>' . wfMsg('newpage_type') . '</th>
						<th>' . wfMsg('newpage_suffix1') . '</th>
						<th>' . wfMsg('newpage_suffix2') . '</th>
					<tr>
						<td><select name=namespace>' . $wgCreatePageNamespaces . '</select></td>
						<td><input size=45 type=\'text\' name=\'newtitle\' value=\'' . $newtitle . '\'></td>
						<td><select name=type>' . $wgCreatePageTypes . '</select></td>
						<td><input size=15 type=\'text\' name=\'suffix1\' value=\'' . $suffix1 . '\'></td>
						<td><input size=15 type=\'text\' name=\'suffix2\' value=\'' . $suffix2 . '\'></td>
						<td><input type=\'submit\' value=\'' . wfMsg('button')  . '\'></td>
					</tr>
					<tr>
						<td></td>
						<td>' . wfMsg( 'newtitle_desc' ) . '</td>
						<td></td>
						<td>' . wfMsg( 'suffix1_desc' ) . '</td>
						<td>' . wfMsg( 'suffix2_desc' ) . '</td>
					</tr>
				</table></form>');
	}

	/**
	 * parse the text because we can't add WikiText. see here:
	 *  http://bugzilla.wikimedia.org/show_bug.cgi?id=9762
	 */
	function addText( $text ) {
		global $wgTitle, $wgOut, $wgParser;
		$po = $wgParser->parse( $text, $wgTitle, $wgParser->mOptions, false, false );
		$wgOut->addHTML( $po->getText() );
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
