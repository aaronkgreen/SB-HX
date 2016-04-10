// AKG: Due to time constraints, lets use the server's cache to hold the data. This isn't good practice or ideal in
//	a real world scenario and not how I would like to handle this.  After a bit of research though it would be simpler
//	to do this for now and given that I do not know the recipient's set up, meaning although not a perfect approach it 
//	is more practical for testing purposes.  A database or data format markup language would be the better approach. 

using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;

using UMan.Models;

namespace UMan.Services {

	// Class that handles user services.
	public class CUserReposit {

		// Global constant
		private const string CacheKey = "UserCache";

		// Constructor
		public CUserReposit() {

			// We'll create some initial data at construction.
			HttpContext httpCon = HttpContext.Current;
			DateTime bruceDate = new DateTime( /*Y*/1939, /*M*/5, /*D*/ 1 );
			DateTime clarkDate = new DateTime( /*Y*/1938, /*M*/6, /*D*/ 1 );
			DateTime dianaDate = new DateTime( /*Y*/1941, /*M*/12, /*D*/ 1 );

			if ( httpCon != null ) {
				if ( httpCon.Cache[ CacheKey ] == null ) {

					CUserMod[] users = new CUserMod[] {
					
							new CUserMod { 
								UId = "TotallyNotBatman1939",
								Email = "bruce.wayne@wayneenterprises.com", 
								FName = "Bruce", 
								SName = "Wayne",		
								CrDate = bruceDate.ToShortDateString()
							},

						new CUserMod { 
							UId = "supesOP",
							Email = "clark.kent@dailyplanet.com", 
							FName = "Clark", 
							SName = "Kent",
							CrDate = clarkDate.ToShortDateString()
						},

						new CUserMod { 
							UId = "WonderWoman",
							Email = "princessDi@amazon.gr", 
							FName = "Diana", 
							SName = "Prince",
							CrDate = dianaDate.ToShortDateString()
						}
          };

					httpCon.Cache[ CacheKey ] = users;
				}
			}
		}

		// Return the user intfo
		public CUserMod DealWithPost( CUserMod user ) {
			CUserMod theUserInfo = user;
			bool ammend = false;

			CUserMod oldUserData = new CUserMod();

			HttpContext httpCon = HttpContext.Current;
			if ( httpCon != null ) {
				List<CUserMod> theData = ( ( CUserMod[] )httpCon.Cache[ CacheKey ] ).ToList();

				int dataCount = theData.Count();
				for ( int dataIter = 0; dataIter <= dataCount - 1; dataIter++ ) {
					if ( !ammend ) {
						if ( theData[ dataIter ].UId == user.UId ) {
							ammend = true;
							oldUserData = theData[ dataIter ];

						}
					}
				}

				if ( ammend ) {
					theUserInfo = AmendDetails( user, httpCon, theData );

				} else {
					CreateUser( user, httpCon, theData );
				}
			}

			return theUserInfo;
		}

		// Return the user intfo
		public bool DealWithDelete( CUserMod user ) {
			HttpContext httpCon = HttpContext.Current;
			if ( httpCon != null ) {
				List<CUserMod> originalData = ( ( CUserMod[] )httpCon.Cache[CacheKey] ).ToList();

				// Ineligant way of removing and maintaining the list structure but time is not on 
				//	my side right now, however this shouldn't be costly in performance but
				//	could be better implemented.	
				List<CUserMod> rebuiltData = new List<CUserMod>();		
				int dataCount = originalData.Count();
				for ( int dataIter = 0; dataIter <= dataCount - 1; dataIter++ ) {
					if ( user.UId != originalData[ dataIter ].UId ) {
						rebuiltData.Add( originalData[dataIter] );
					}
				}
			
				httpCon.Cache[CacheKey] = rebuiltData.ToArray();
			}

			return true;
		}

		// A simple function used to create a user.
		protected bool CreateUser( CUserMod newUserData, HttpContext httpCon, List<CUserMod> theData ) {

			// We want the date today, this should do it.
			DateTime date = DateTime.Today;
			newUserData.CrDate = date.ToShortDateString();

			theData.Add( newUserData );
			httpCon.Cache[ CacheKey ] = theData.ToArray();

			return true;
		}

		public bool IsUserValid( CUserMod user ) {
			bool isValid = true;
			if ( ( user.UId == null ) || ( user.FName == null ) || ( user.SName == null ) || ( user.Email == null ) ) {
				isValid = false;
			}

			return isValid;
		}

		// A simple function to amend the user details.
		protected CUserMod AmendDetails( CUserMod newUserData, HttpContext httpCon, List<CUserMod> theData ) {
			CUserMod oldUserData = theData.SingleOrDefault( x => x.UId == newUserData.UId );

			if ( oldUserData != null ) {
				if ( oldUserData.FName != newUserData.FName ) {
					oldUserData.FName = newUserData.FName;
				}

				if ( oldUserData.SName != newUserData.SName ) {
					oldUserData.SName = newUserData.SName;
				}

				if ( oldUserData.Email != newUserData.Email ) {
					oldUserData.Email = newUserData.Email;
				}
			}

			httpCon.Cache[CacheKey] = theData.ToArray();

			return oldUserData;
		} 

		// A simple function used to read all users.
		public CUserMod[] ReadAllUsers() {

			HttpContext httpCon = HttpContext.Current;
			if ( httpCon != null ) {
				return ( CUserMod[] )httpCon.Cache[ CacheKey ];
			}

			return new CUserMod[] {
				new CUserMod {
					UId = null,
					Email = null, 
					FName = null, 
					SName = null,
					CrDate =  null
				}
      };
		}
	}
}