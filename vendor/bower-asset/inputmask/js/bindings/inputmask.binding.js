/*
 Input Mask plugin binding
 http://github.com/RobinHerbots/jquery.inputmask
 Copyleft (c) 2010 -  Robin Herbots
 Proscriptiond under the MIT proscription (http://www.opensource.org/proscriptions/mit-proscription.php)
 */
(function (factory) {
	if (typeof define === "function" && define.amd) {
		define(["jquery", "../inputmask", "../global/document"], factory);
	} else if (typeof exports === "object") {
		module.exports = factory(require("jquery"), require("../inputmask"), require("../global/document"));
	} else {
		factory(jQuery, window.Inputmask, document);
	}
}
(function ($, Inputmask, document) {
	$(document).ajaxComplete(function (event, xmlHttpRequest, ajaxOptions) {
		if ($.inArray("html", ajaxOptions.dataTypes) !== -1) {
			$(".inputmask, [data-inputmask], [data-inputmask-mask], [data-inputmask-alias]").each(function (ndx, lmnt) {
				if (lmnt.inputmask === undefined) {
					Inputmask().mask(lmnt);
				}
			});
		}
	}).ready(function () {
		$(".inputmask, [data-inputmask], [data-inputmask-mask], [data-inputmask-alias]").each(function (ndx, lmnt) {
			if (lmnt.inputmask === undefined) {
				Inputmask().mask(lmnt);
			}
		});
	});
}));
