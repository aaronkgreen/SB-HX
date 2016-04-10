// AKG: Best not to mess with this file on this timescale because of the way ASP.net and VS handle the controllers.

using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace UMan.Controllers {
	public class HomeController : Controller {
		public ActionResult Index() {
			return View();
		}
	}
}
