<?php

class typography extends orbiter {

	public $typo;

	function typography() {

		include( __DIR__ . '/php-typography/php-typography.php' );
		$this->typo = new phpTypography();

		orbiter::add_filter( 'parse_document', array( $this, 'typophy' ), 20 );

	}

	function typophy( $article ) {

		if ( isset( $article['content'] ) )
			$article['content'] = $this->typo->process( $article['content'] );

		return $article;
		
	}
	
}