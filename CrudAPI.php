<?php
// 09/04/2016 - File created.

abstract class CCrud
{
	// Global variable for the HTTP CRUD state.
    protected $gHttpReqMethod = '';
	
	// Global variable for the model in the url.
	protected $gModel = '';
	
	// Global variable for expanded data on the model in the url. 
	protected $gExpModel = '';

	// Global variable for an array of arguements in the url.
	protected $argArray = Array();
	
	// A constructor that enables cross-origin sharing and houses the abstract functionality of the CRUD.
    public function __construct( $urlReq ) {
		
		// Research following a bug suggests that adding this code allows for "cross-origin" sharing.
		header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");
		
		$this->_ReasignURL( $urlReq );
		$this->_SelectCRUDState();
		$this->_DoURLSanitization( $urlReq );
    }

	// Take the URL, break it down and reasign to variables as required.
	private function _ReasignURL( $urlReq ) {
		
        $this->argArray = explode('/', rtrim( $urlReq, '/' ) );
        $this->gModel = array_shift( $this->argArray );
		
        if ( array_key_exists( 0, $this->argArray) && !is_numeric( $this->argArray[ 0 ] ) ) {
            $this->gExpModel = array_shift( $this->argArray );
        }
	}
	
	// Work out the correct CRUD state from the http request method.
	private function _SelectCRUDState() {
	    $this->gHttpReqMethod = $_SERVER[ 'REQUEST_METHOD' ];
        if ( $this->gHttpReqMethod == 'POST' ) {	

            if ( $_SERVER[ 'HTTP_X_HTTP_METHOD' ] == 'PUT' ) {
                $this->gHttpReqMethod = 'PUT';
				
            } else if ( $_SERVER[ 'HTTP_X_HTTP_METHOD' ] == 'DELETE' ) {
				$this->gHttpReqMethod = 'DELETE';
			}
        }	
	}
	
	// Take the URL and sanitize it for a smoother process going forward. 
    private function _DoURLSanitization( $urlReq ) {

        switch( this->gHttpReqMethod ) {
				
		// Create
		case 'POST':
			echo "Post request";	
			$input = Array();
			
			if ( is_array( $_POST ) ) {
				foreach( $_POST as $arraykey => $arrayVal ) {
					// Not 100% sure about how to achieve what I want here.  I want to sanatize but how?
					// 	will need to go away and ponder or do some research.
				}
			} else {
				$input = trim( strip_tags( $_POST ) );
			}
			
			break;
				
		// Read
		case 'GET':
			echo "get request";
			break;
			
		// Update
		case 'PUT':
			echo "put request";
			break;
			
		// Delete
		case 'DELETE':
			echo "delete request";
			break;
			
		default:
			// Do nothing.
			// Eventually I'll want an error message of some kind here.
			break;
        }
    }
}