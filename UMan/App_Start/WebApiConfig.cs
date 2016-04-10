// AKG: Standard VS code, I need to look into what I can and cnnot do here - the HTTP access looks interesting.

using System;
using System.Collections.Generic;
using System.Linq;
using System.Web.Http;

namespace UMan {
	public static class WebApiConfig {
		public static void Register( HttpConfiguration config ) {
			config.Routes.MapHttpRoute(
					name: "DefaultApi",
					routeTemplate: "api/{controller}/{id}",
					defaults: new {
						id = RouteParameter.Optional
					}
			);
		}
	}
}
