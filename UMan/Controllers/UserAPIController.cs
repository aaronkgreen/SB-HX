// AKG - This should be self-explanitory but the name prior to the Contoller sufix is the API end-point e.g. localhost/api/UserAPI to access this data.

using System.Net;
using System.Net.Http;
using System.Web.Http;

using UMan.Models;
using UMan.Services;

namespace UMan.Controllers {

	public class UserAPIController : ApiController {

		// Global value
		private CUserReposit gUserRepository;

		// Constructor
		public UserAPIController() {
			gUserRepository = new CUserReposit();
		}

		// A simple GET on the user reposit.
		public CUserMod[] Get() {
			return gUserRepository.ReadAllUsers();
		}

		// Let's get our POST on...
		public HttpResponseMessage Post( CUserMod user ) {

			// This seems like an odd way of doing this I am sure, my plan was to take the CUserMod
			//	and use it to fill in vital JS/JSon data in the test dashboard.
			CUserMod theUserReq = gUserRepository.DealWithPost( user );

			HttpResponseMessage response;

			if ( gUserRepository.IsUserValid( theUserReq ) ) {
				response = Request.CreateResponse( System.Net.HttpStatusCode.OK, "OK" );

			} else {
				// My implementation doesn't act on it at all but here we give out an error 500.
				response = Request.CreateErrorResponse( HttpStatusCode.InternalServerError, "Invalid input" );  
			}

			return response;
		}

		// Removal
		public HttpResponseMessage Delete( CUserMod user ) {
			HttpResponseMessage response;

			gUserRepository.DealWithDelete( user );

			// A little redundant (and by a little I mean completely) right now, need to finish.
			response = Request.CreateResponse( System.Net.HttpStatusCode.OK, "OK" );

			return response;
		}
	}
}
