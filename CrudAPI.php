<?php
// 09/04/2016 - File created.

class CCrud
{
	// We need to know which CRUD state we're getting from the HTTP request.
    private $gHttpReqMethod = '';
	
	// A simple constructor, I will need to develop this further for security reasons.
    public function __construct( $urlReq ) {
		$this->UpdateHTTPReqMethod();
		$this->CRUDTest( $urlReq );
    }
	
	// Work out the CRUD state from the http request method.
	private function UpdateHTTPReqMethod() {
	    $this->gHttpReqMethod = $_SERVER[ 'REQUEST_METHOD' ];
        if ( $this->gHttpReqMethod == 'POST' ) {	

            if ( $_SERVER[ 'HTTP_X_HTTP_METHOD' ] == 'PUT' ) {
                $this->gHttpReqMethod = 'PUT';
				
            } else if ( $_SERVER[ 'HTTP_X_HTTP_METHOD' ] == 'DELETE' ) {
				$this->gHttpReqMethod = 'DELETE';
			}
        }	
	}
	
	// Test that the Create, Read, Update and Delete requests pick up. 
    private function CRUDTest( $urlReq ) {

        switch( gHttpReqMethod ) {
				
		// Create
		case 'POST':
			echo "Post request";
			break;
				
		// Read
		case 'GET':
			echo "get request";
			break;
			
		// Update
		case 'PUT':
			echo "put request";
			break;
			
		// See below...
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