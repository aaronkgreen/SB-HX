// AKG: Standard VS code, best leave alone.

using System.Web;
using System.Web.Mvc;

namespace UMan {
	public class FilterConfig {
		public static void RegisterGlobalFilters( GlobalFilterCollection filters ) {
			filters.Add( new HandleErrorAttribute() );
		}
	}
}