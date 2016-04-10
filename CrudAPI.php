<?php
// 09/04/2016 - File created.

abstract class CCrudAPI
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
	
	// This is the function called by the non-abstracted classes. 
	public function FireAPI() {
        if ( method_exists( $this, $this->gModel ) ) {   
			return $this->_response( $this->{$this->gModel }( $this->argArray ));
        }
    }
	
	// The response class that takes the header info and moves the process forward.
    private function _response( $data, $status = 200 ) {
        header( "HTTP/1.1 " . $status . " " . $this->_requestStatus( $status ) );
        return json_encode( $data );
    }
	
	// Using the status code, return the status.  A perfect implementation would have a more diverse range of codes
	// 	however, this would take too much time given the current scope.
	private function _GetStatus( $sCode ) {
            if ( $sCode !== NULL ) {

                switch ( $sCode ) {
					case 200: 
						$text = 'OK'; 
					break;
					
					case 505: 
						$text = 'HTTP Version not supported'; 
					break;
				}
			}
		
        return ( $text ); 
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
				_DoInputSanitization( $_POST );
			break;
					
			// Read
			case 'GET':
				echo "get request";
				_DoInputSanitization( $_GET );	
			break;
				
			// Update
			case 'PUT':
				echo "put request";
				// Come back to this, the implemtation I've taken elsewhere won't work (design flaw?).
				
			break;
				
			// Delete (unsuprisingly...)
			case 'DELETE':
				echo "delete request";
			break;
				
			default:
				// Do nothing.
				// I'll want an error message of some kind here, time willing.
			break;
        }
    }
	
	// Simply skirts aroung the array to keep the infotmation we are working with relevant.
	private function _DoInputSanitization( $reqMethod ) {
			
		$input = Array();		
		if ( is_array( reqMethod ) ) {
			foreach( reqMethod as $arraykey => $arrayVal ) {
				$input[ $arraykey ] = $this->_DoInputSanitization( $arrayVal );
			}
			
		} else {
			$input = trim( strip_tags( reqMethod ) );
		}	
				
        return $input;
    }
} 