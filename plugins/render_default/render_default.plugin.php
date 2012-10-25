<?php


class render_default extends orbiter {

	function render_default() {
		orbiter::add_filter( 'parse_document', array( $this, 'article_setup' ), 50 );
		orbiter::add_filter( 'render', array( $this, 'render_view' ), 50 );
	}

	function article_setup( $article ) {

		// Add permalink
		$article['permalink'] = trim( $article['uri'] . '/' . $article['slug'], '/' );

		return $article;
	}

	function render_view( $articles ) {

		// Get the request relative to this folder
		$request_uri = str_replace( dirname( $_SERVER['PHP_SELF'] ), '/', $_SERVER['REQUEST_URI'] );

		// Remove query args and opening/trailing slashes
		$request_uri = trim( array_shift( explode( '?', $request_uri ) ) , '/' );

		// Remove index.php
		$request_uri = str_replace( basename( $_SERVER['SCRIPT_NAME'] ), '', $request_uri );

		foreach ( $articles as $article )
			if ( $article['permalink'] == $request_uri )
				die( orbiter::filter( 'render_article_html', array( 'article' => $article, 'articles' => $articles, 'config' => orbiter::$config ), orbiter::$template[ $article['template'] ], $request_uri ) );
		
		header('HTTP/1.0 404 Not Found');

		foreach ( $articles as $article )
			if ( $article['permalink'] == '404' )
				die( orbiter::filter( 'render_article_html', array( 'article' => $article, 'articles' => $articles, 'config' => orbiter::$config ), orbiter::$template[ $article['template'] ], $request_uri ) );

		die( orbiter::filter( 'render_article_html', array( 'article' => array( 'content' => 'Page Not Found' ), 'articles' => $articles, 'config' => orbiter::$config ), orbiter::$template[ 'template.html' ], $request_uri ) );
	}

}