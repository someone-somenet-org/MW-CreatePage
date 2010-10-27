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
	var $myParser;

	/**
	 * constructor, only does the basic stuff...
	 */
	function CreatePage() {
		SpecialPage::SpecialPage( 'CreatePage' );
		wfLoadExtensionMessages( 'CreatePage' );

		$this->myParser = new Parser();
		$this->mIncludable = true;
	}

	function execute( $par ) {
		global $wgOut, $wgRequest;
		global $wgCreatePageNamespaces, $wgCreatePageTypes;

		// assemble types and namespaces
		foreach( $wgCreatePageNamespaces as $key => $value ) {
			if ( $value ) 
				$namespaces .= '<option selected>' . $key;
			else
				$namespaces .= '<option>' . $key;
		}
		foreach( $wgCreatePageTypes as $key => $value ) {
			if ( $value )
				$types .= '<option selected>' . $key;
			else
				$types .= '<option>' . $key;
		}

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
		$wgOut->setPagetitle( wfMsg('emCreatePagePageTitle') );

		global $wgScript;
		$handler = $wgScript . '/' . MWNamespace::getCanonicalName(NS_SPECIAL) . ":" . SpecialPage::getLocalName( 'Create page' );

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
						<td><select name=namespace>' . $namespaces . '</select></td>
						<td><input size=40 type=\'text\' name=\'newtitle\' value=\'' . $newtitle . '\'></td>
						<td><select name=type>' . $types . '</select></td>
						<td><input size=15 type=\'text\' name=\'suffix1\' value=\'' . $suffix1 . '\'></td>
						<td><input size=22 type=\'text\' name=\'suffix2\' value=\'' . $suffix2 . '\'></td>
						<td><input type=\'submit\' value=\'' . wfMsg('button')  . '\'></td>
					</tr>
					<tr>
						<td></td>
						<td>' . wfMsg( 'newtitle_desc' ) . '</td>
						<td></td>
						<td>' . wfMsg( 'suffix1_desc' ) . '</td>
						<td></td>
					</tr>
				</table></form>');
	}

	/**
	 * parse the text because we can't add WikiText. see here:
	 *  http://bugzilla.wikimedia.org/show_bug.cgi?id=9762
	 */
	function addText( $text ) {
		global $wgTitle, $wgOut;
		$po = $this->myParser->parse( $text, $wgTitle, new ParserOptions(), false, true );
		$wgOut->addHTML( $po->getText() );
	}
}

?>
