function guid() {
    function e() {
        return Math.floor(65536 * (1 + Math.random()))
            .toString(16)
            .substring(1);
    }
    return e() + e() + "-" + e() + "-" + e() + "-" + e() + "-" + e() + e() + e();
}
function i18nParam(e, t) {
    var n = i18n[e];
    return (
        angular.forEach(t, function (e, t) {
            n = n.replace("%" + t + "%", e);
        }),
            n
    );
}
function typeCastValue(e) {
    return angular.isNumber(e) ? parseInt(e) : e;
}
angular
    .module("dnd", [])
    .factory("dndFactory", function () {
        return {
            data: { content: null, pos: null, element: null },
            getElement: function () {
                return this.data.element;
            },
            setElement: function (e) {
                this.data.element = e;
            },
            setContent: function (e) {
                this.data.content = e;
            },
            getContent: function () {
                return this.data.content;
            },
            setPos: function (e) {
                this.data.pos = e;
            },
            getPos: function () {
                return this.data.pos;
            },
        };
    })
    .directive("dnd", [
        "dndFactory",
        "AdminClassService",
        function (r, e) {
            return {
                restrict: "A",
                transclude: !1,
                replace: !1,
                template: !1,
                templateURL: !1,
                scope: { dndModel: "=", dndCss: "=", dndOndrop: "&", dndIsvalid: "&" },
                link: function (i, a, e) {
                    var o = !0,
                        l = e.hasOwnProperty("dndDisableDragMiddle");
                    e.hasOwnProperty("dndDragDisabled") || a.attr("draggable", "true"),
                        (i.addClass = function (e) {
                            a.addClass(e);
                        }),
                        (i.removeClass = function (e, t) {
                            a.removeClass(e);
                        }),
                        a.on("dragstart", function (e) {
                            if (((e = e.originalEvent || e).stopPropagation(), "false" == a.attr("draggable"))) return !0;
                            (o = !0), r.setContent(i.dndModel), r.setElement(a[0]), i.addClass(i.dndCss.onDrag);
                            try {
                                e.dataTransfer.setData("text", "1");
                            } catch (e) {
                                try {
                                    e.dataTransfer.setData("application/json", "1");
                                } catch (e) {
                                    e.dataTransfer.setData("Text", "1");
                                }
                            }
                        }),
                        a.on("dragend", function (e) {
                            (e = e.originalEvent || e), i.removeClass(i.dndCss.onDrag), e.stopPropagation();
                        }),
                        a.on("dragover", function (e) {
                            e = e.originalEvent || e;
                            try {
                                e.dataTransfer.dropEffect = "move";
                            } catch (e) {}
                            if ((e.preventDefault(), e.stopPropagation(), !i.dndIsvalid({ hover: i.dndModel, dragged: r.getContent() }))) return (o = !1);
                            var t = a[0].getBoundingClientRect(),
                                n = (100 / t.height) * (e.clientY - t.top);
                            return (
                                l
                                    ? n <= 50
                                        ? (i.addClass(i.dndCss.onHoverTop), i.removeClass(i.dndCss.onHoverMiddle), i.removeClass(i.dndCss.onHoverBottom), r.setPos("top"))
                                        : (i.removeClass(i.dndCss.onHoverTop), i.removeClass(i.dndCss.onHoverMiddle), i.addClass(i.dndCss.onHoverBottom), r.setPos("bottom"))
                                    : n <= 25
                                        ? (i.addClass(i.dndCss.onHoverTop), i.removeClass(i.dndCss.onHoverMiddle), i.removeClass(i.dndCss.onHoverBottom), r.setPos("top"))
                                        : 65 <= n
                                            ? (i.removeClass(i.dndCss.onHoverTop), i.removeClass(i.dndCss.onHoverMiddle), i.addClass(i.dndCss.onHoverBottom), r.setPos("bottom"))
                                            : (i.removeClass(i.dndCss.onHoverTop), i.addClass(i.dndCss.onHoverMiddle), i.removeClass(i.dndCss.onHoverBottom), r.setPos("middle")),
                                    i.addClass(i.dndCss.onHover),
                                    !1
                            );
                        }),
                        a.on("dragenter", function (e) {
                            (e = e.originalEvent || e), i.addClass(i.dndCss.onHover), e.preventDefault();
                        }),
                        a.on("dragleave", function (e) {
                            i.removeClass(i.dndCss.onHover, !0), i.removeClass(i.dndCss.onHoverTop, !0), i.removeClass(i.dndCss.onHoverMiddle, !0), i.removeClass(i.dndCss.onHoverBottom, !0);
                        }),
                    e.hasOwnProperty("dndDropDisabled") ||
                    a.on("drop", function (e) {
                        return (
                            (e = e.originalEvent || e).preventDefault(),
                                e.stopPropagation(),
                                i.removeClass(i.dndCss.onHover, !0),
                                i.removeClass(i.dndCss.onHoverTop, !0),
                                i.removeClass(i.dndCss.onHoverMiddle, !0),
                                i.removeClass(i.dndCss.onHoverBottom, !0),
                            !!o &&
                            (i.$apply(function () {
                                i.dndOndrop({ dragged: r.getContent(), dropped: i.dndModel, position: r.getPos(), element: r.getElement() });
                            }),
                                !0)
                        );
                    });
                },
            };
        },
    ]);
var zaa = angular.module("zaa", ["ui.router", "dnd", "angular-loading-bar", "ngFileUpload", "ngWig", "flow", "angular.filter", "720kb.datepicker", "directive.ngColorwheel", "uiCropper"]);
zaa.config([
    "$httpProvider",
    "$stateProvider",
    "$controllerProvider",
    "$urlMatcherFactoryProvider",
    function (e, t, n, i) {
        e.interceptors.push("authInterceptor"),
            (zaa.bootstrap = n),
            i.strictMode(!1),
            t
                .state("default", {
                    url: "/default/:moduleId",
                    templateUrl: function (e) {
                        return "admin/template/default";
                    },
                })
                .state("default.route", {
                    url: "/:moduleRouteId/:controllerId/:actionId",
                    templateUrl: function (e) {
                        return e.moduleRouteId + "/" + e.controllerId + "/" + e.actionId;
                    },
                    parent: "default",
                    resolve: { adminServiceResolver: adminServiceResolver },
                })
                .state("custom", {
                    url: "/template/:templateId",
                    templateUrl: function (e) {
                        return e.templateId;
                    },
                    resolve: {
                        adminServiceResolver: adminServiceResolver,
                        resolverProvider: [
                            "resolver",
                            function (e) {
                                return e.then;
                            },
                        ],
                    },
                })
                .state("home", {
                    url: "",
                    templateUrl: "admin/default/dashboard",
                    controller: [
                        "$scope",
                        function (e) {
                            e.$parent.currentItem = { icon: "home", alias: i18n.menu_dashboard };
                        },
                    ],
                })
                .state("default.route.detail", {
                    url: "/:id",
                    parent: "default.route",
                    template: "<ui-view/>",
                    controller: [
                        "$scope",
                        "$stateParams",
                        function (e, t) {
                            (e.crud = e.$parent),
                                (e.init = function () {
                                    e.crud.config.inline || (e.crud.data.updateId != t.id && e.crud.toggleUpdate(t.id));
                                }),
                                e.init();
                        },
                    ],
                });
    },
]),
    zaa.provider("resolver", [
        function () {
            var a = [];
            (this.addCallback = function (e) {
                a.push(e);
            }),
                (this.$get = [
                    "$injector",
                    "$q",
                    "$state",
                    function (i, e, t) {
                        return e(function (e, t) {
                            for (var n in a) i.invoke(a[n]);
                        });
                    },
                ]);
        },
    ]),
    zaa.factory("LuyaLoading", [
        "$timeout",
        function (t) {
            var n = !1,
                i = null,
                a = null;
            return {
                start: function (e) {
                    (i = null == e ? i18n.js_zaa_server_proccess : e),
                        t.cancel(a),
                        (a = t(function () {
                            n = !0;
                        }, 2e3));
                },
                stop: function () {
                    t.cancel(a), (n = !1);
                },
                getStateMessage: function () {
                    return i;
                },
                getState: function () {
                    return n;
                },
            };
        },
    ]),
    zaa.factory("AdminClassService", function () {
        var n = [];
        return (
            (n.vars = {}),
                (n.getClassSpace = function (e) {
                    if (n.vars.hasOwnProperty(e)) return n.vars[e];
                }),
                (n.hasClassSpace = function (e) {
                    return !!n.vars.hasOwnProperty(e);
                }),
                (n.setClassSpace = function (e, t) {
                    n.vars[e] = t;
                }),
                (n.clearSpace = function (e) {
                    n.vars.hasOwnProperty(e) && (n.vars[e] = null);
                }),
                (n.removeSpace = function (e) {
                    n.hasClassSpace(e) && delete n.vars[e];
                }),
                (n.stack = 0),
                (n.modalStackPush = function () {
                    n.stack += 1;
                }),
                (n.modalStackRemove = function () {
                    n.stack <= 1 ? (n.stack = 0) : (n.stack -= 1);
                }),
                (n.modalStackRemoveAll = function () {
                    n.stack = 0;
                }),
                (n.modalStackIsEmpty = function () {
                    return 0 == n.stack;
                }),
                n
        );
    }),
    zaa.factory("CacheReloadService", [
        "$http",
        "$window",
        function (e, t) {
            var n = [];
            return (
                (n.reload = function () {
                    e.get("admin/api-admin-common/cache").then(function (e) {
                        t.location.reload();
                    });
                }),
                    n
            );
        },
    ]),
    zaa.factory("authInterceptor", [
        "$rootScope",
        "$q",
        "AdminToastService",
        "AdminDebugBar",
        function (i, a, t, o) {
            return {
                request: function (e) {
                    if ((e.hasOwnProperty("ignoreLoadingBar") || (e.debugId = o.pushRequest(e)), e.hasOwnProperty("authToken"))) var t = e.authToken;
                    else t = i.luyacfg.authToken;
                    (e.headers = e.headers || {}), (e.headers.Authorization = "Bearer " + t);
                    var n = document.head.querySelector("[name=csrf-token]");
                    return null !== n && (e.headers["X-CSRF-Token"] = n.content), e || a.when(e);
                },
                response: function (e) {
                    return e.hasOwnProperty("ignoreLoadingBar") || o.pushResponse(e), e || a.when(e);
                },
                responseError: function (e) {
                    if (401 == e.status || 403 == e.status || 405 == e.status) e.config.hasOwnProperty("authToken") || (window.location = "admin/default/logout?autologout=1");
                    else if (404 == e.status) {
                        e.data.hasOwnProperty("message") ? t.info(e.data.message, 1e4) : t.info("Response Error: " + e.status + " " + e.statusText, 1e4);
                    } else if (422 != e.status) {
                        e.data.hasOwnProperty("message") ? t.error(e.data.message, 1e4) : t.error("Response Error: " + e.status + " " + e.statusText, 1e4);
                    }
                    return a.reject(e);
                },
            };
        },
    ]),
    (adminServiceResolver = [
        "ServiceFoldersData",
        "ServiceFiltersData",
        "ServiceLanguagesData",
        "ServicePropertiesData",
        "AdminLangService",
        "ServiceFoldersDirecotryId",
        function (e, t, n, i, a, o) {
            t.load(), e.load(), n.load(), i.load(), a.load(), o.load();
        },
    ]),
    zaa.factory("ServiceAdminTags", [
        "$http",
        "$q",
        "$rootScope",
        function (i, e, a) {
            var o = {
                data: null,
                load: function (n) {
                    return e(function (t, e) {
                        null !== o.data && !0 !== n
                            ? t(o.data)
                            : i.get("admin/api-admin-common/tags").then(function (e) {
                                (o.data = e.data), a.$broadcast("service:AdminTags", o.data), t(o.data);
                            });
                    });
                },
            };
            return o;
        },
    ]),
    zaa.factory("ServiceFoldersData", [
        "$http",
        "$q",
        "$rootScope",
        function (i, e, a) {
            var o = [];
            return (
                (o.data = null),
                    (o.load = function (n) {
                        return e(function (t, e) {
                            null !== o.data && !0 !== n
                                ? t(o.data)
                                : i.get("admin/api-admin-storage/data-folders").then(function (e) {
                                    (o.data = e.data), a.$broadcast("service:FoldersData", o.data), t(o.data);
                                });
                        });
                    }),
                    o
            );
        },
    ]),
    zaa.factory("ServiceFoldersDirecotryId", [
        "$http",
        "$q",
        "$rootScope",
        function (i, e, a) {
            var o = [];
            return (
                (o.folderId = !1),
                    (o.load = function (n) {
                        return e(function (t, e) {
                            !1 !== o.folderId && !0 !== n
                                ? t(o.folderId)
                                : i.get("admin/api-admin-common/get-filemanager-folder-state").then(function (e) {
                                    (o.folderId = e.data), a.$broadcast("service:FoldersDirectoryId", o.folderId), t(o.folderId);
                                });
                        });
                    }),
                    o
            );
        },
    ]),
    zaa.factory("ServiceImagesData", [
        "$http",
        "$q",
        "$rootScope",
        "$log",
        function (a, e, t, n) {
            var o = [];
            return (
                (o.data = {}),
                    (o.getImage = function (t, i) {
                        return e(function (n, e) {
                            return 0 == t
                                ? e(t)
                                : o.data.hasOwnProperty(t) && !0 !== i
                                    ? n(o.data[t])
                                    : void a.get("admin/api-admin-storage/image-info?id=" + t).then(function (e) {
                                        var t = e.data;
                                        return (o.data[t.id] = t), n(t);
                                    });
                        });
                    }),
                    (o.loadImages = function (n) {
                        return e(function (t, e) {
                            if (0 == n.length) return t();
                            a.post("admin/api-admin-storage/images-info?expand=source,tinyCropImage", { ids: n }).then(function (e) {
                                return (
                                    angular.forEach(e.data, function (e) {
                                        o.data[e.id] = e;
                                    }),
                                        t()
                                );
                            });
                        });
                    }),
                    o
            );
        },
    ]),
    zaa.factory("ServiceFilesData", [
        "$http",
        "$q",
        "$rootScope",
        "$log",
        function (a, e, t, n) {
            var o = [];
            return (
                (o.data = {}),
                    (o._promises = {}),
                    (o.getFile = function (e, t) {
                        if (o._promises.hasOwnProperty(e)) return o._promises[e];
                        var n = o.newPromise(e, t);
                        return (o._promises[e] = n);
                    }),
                    (o.newPromise = function (i, t) {
                        return e(function (n, e) {
                            return 0 == i
                                ? e(i)
                                : o.data.hasOwnProperty(i) && !0 !== t
                                    ? n(o.data[i])
                                    : void a.get("admin/api-admin-storage/file-info?id=" + i).then(function (e) {
                                        var t = e.data;
                                        return (o.data[t.id] = t), delete o._promises[i], n(t);
                                    });
                        });
                    }),
                    o
            );
        },
    ]),
    zaa.factory("ServiceFiltersData", [
        "$http",
        "$q",
        "$rootScope",
        function (i, e, a) {
            var o = [];
            return (
                (o.data = null),
                    (o.load = function (n) {
                        return e(function (t, e) {
                            null !== o.data && !0 !== n
                                ? t(o.data)
                                : i.get("admin/api-admin-storage/data-filters").then(function (e) {
                                    (o.data = e.data), a.$broadcast("service:FiltersData", o.data), t(o.data);
                                });
                        });
                    }),
                    o
            );
        },
    ]),
    zaa.factory("ServiceLanguagesData", [
        "$http",
        "$q",
        "$rootScope",
        function (i, e, a) {
            var o = [];
            return (
                (o.data = []),
                    (o.load = function (n) {
                        return e(function (t, e) {
                            0 < o.data.length && !0 !== n
                                ? t(o.data)
                                : i.get("admin/api-admin-common/data-languages").then(function (e) {
                                    (o.data = e.data), a.$broadcast("service:LanguagesData", o.data), t(o.data);
                                });
                        });
                    }),
                    o
            );
        },
    ]),
    zaa.factory("ServicePropertiesData", [
        "$http",
        "$q",
        "$rootScope",
        function (i, e, a) {
            var o = [];
            return (
                (o.data = null),
                    (o.load = function (n) {
                        return e(function (t, e) {
                            null !== o.data && !0 !== n
                                ? t(o.data)
                                : i.get("admin/api-admin-common/data-properties").then(function (e) {
                                    (o.data = e.data), a.$broadcast("service:PropertiesData", o.data), t(o.data);
                                });
                        });
                    }),
                    o
            );
        },
    ]),
    zaa.factory("CrudTabService", function () {
        var l = [];
        return (
            (l.tabs = []),
                (l.remove = function (e, t) {
                    (l.tabs.splice(e, 1), 0 < l.tabs.length) ? (l.tabs.slice(-1)[0].active = !0) : t.switchTo(0);
                }),
                (l.addTab = function (e, t, n, i, a) {
                    var o = { id: e, api: t, arrayIndex: n, active: !0, name: i, modelClass: a };
                    angular.forEach(l.tabs, function (e) {
                        e.active = !1;
                    }),
                        l.tabs.push(o);
                }),
                (l.clear = function () {
                    l.tabs = [];
                }),
                l
        );
    }),
    zaa.factory("AdminLangService", [
        "ServiceLanguagesData",
        "$rootScope",
        function (e, n) {
            var i = [];
            return (
                (i.data = []),
                    (i.selection = []),
                    (i.toggleSelection = function (e) {
                        var t = i.selection.indexOf(e.short_code);
                        -1 == t ? (i.selection.push(e.short_code), n.$broadcast("service:LoadLanguage", e)) : 1 < i.selection.length && i.selection.splice(t, 1);
                    }),
                    (i.isInSelection = function (e) {
                        return -1 != i.selection.indexOf(e);
                    }),
                    (i.resetDefault = function () {
                        (i.selection = []),
                            angular.forEach(e.data, function (e, t) {
                                1 == e.is_default && (i.isInSelection(e.short_code) || i.toggleSelection(e));
                            });
                    }),
                    (i.load = function () {
                        e.load().then(function (e) {
                            (i.data = e),
                                angular.forEach(e, function (e) {
                                    1 == e.is_default && (i.isInSelection(e.short_code) || i.toggleSelection(e));
                                });
                        });
                    }),
                    i
            );
        },
    ]),
    zaa.factory("AdminDebugBar", function () {
        var i = [];
        return (
            (i.data = []),
                (i.clear = function () {
                    i.data = [];
                }),
                (i.pushRequest = function (e) {
                    return i.data.push({ url: e.url, requestData: e.data, responseData: null, responseStatus: null, start: new Date(), end: null, parseTime: null });
                }),
                (i.pushResponse = function (e) {
                    var t = e,
                        n = i.data[t.config.debugId];
                    return n && ((n.responseData = t.data), (n.responseStatus = t.status), (n.end = new Date()), (n.parseTime = new Date() - n.start)), e;
                }),
                i
        );
    }),
    zaa.factory("AdminToastService", [
        "$q",
        "$timeout",
        "$injector",
        function (e, a, o) {
            var l = [];
            return (
                (l.notify = function (e, t, n) {
                    null == t && (t = 6e3);
                    var i = guid();
                    (l.queue[i] = {
                        message: e,
                        timeout: t,
                        uuid: i,
                        type: n,
                        close: function () {
                            delete l.queue[this.uuid];
                        },
                    }),
                        a(function () {
                            delete l.queue[i];
                        }, t);
                }),
                    (l.success = function (e, t) {
                        l.notify(e, t, "success");
                    }),
                    (l.info = function (e, t) {
                        l.notify(e, t, "info");
                    }),
                    (l.warning = function (e, t) {
                        l.notify(e, t, "warning");
                    }),
                    (l.error = function (e, t) {
                        l.notify(e, t, "error");
                    }),
                    (l.errorArray = function (e, n) {
                        angular.forEach(e, function (e, t) {
                            l.error(e.message, n);
                        });
                    }),
                    (l.confirm = function (e, t, n) {
                        var i = guid();
                        l.queue[i] = {
                            message: e,
                            title: t,
                            click: function () {
                                var t = this,
                                    e = o.invoke(this.callback, this, { $toast: this });
                                void 0 !== e &&
                                e.then(
                                    function (e) {
                                        t.close();
                                    },
                                    function (e) {
                                        t.close();
                                    },
                                    function (e) {}
                                );
                            },
                            uuid: i,
                            callback: n,
                            type: "confirm",
                            close: function () {
                                delete l.queue[this.uuid];
                            },
                        };
                    }),
                    (l.queue = {}),
                    l
            );
        },
    ]),
    zaa.factory("HtmlStorage", function () {
        var n = {
            data: {},
            isLoaded: !1,
            loadData: function () {
                if (!n.isLoaded && localStorage.getItem("HtmlStorage")) {
                    var e = angular.fromJson(localStorage.getItem("HtmlStorage"));
                    n.data = e;
                }
            },
            saveData: function () {
                localStorage.removeItem("HtmlStorage"), localStorage.setItem("HtmlStorage", angular.toJson(n.data));
            },
            getValue: function (e, t) {
                return n.loadData(), n.data.hasOwnProperty(e) ? n.data[e] : t;
            },
            setValue: function (e, t) {
                n.loadData(), (n.data[e] = t), n.saveData();
            },
        };
        return n;
    }),
    zaa.filter("filemanagerdirsfilter", function () {
        return function (e, n) {
            var i = [];
            return (
                angular.forEach(e, function (e, t) {
                    e.parentId == n && i.push(e);
                }),
                    i.sort(function (e, t) {
                        return e.name.toLowerCase() > t.name.toLowerCase() ? 1 : -1;
                    }),
                    i
            );
        };
    }),
    zaa.filter("findthumbnail", function () {
        return function (e, n, i) {
            var a = !1;
            return (
                angular.forEach(e, function (e, t) {
                    a || (e.fileId == n && e.filterId == i && (a = e));
                }),
                    a
            );
        };
    }),
    zaa.filter("findidfilter", function () {
        return function (e, n) {
            var i = !1;
            return (
                angular.forEach(e, function (e, t) {
                    e.id == n && (i = e);
                }),
                    i
            );
        };
    }),
    zaa.filter("filemanagerfilesfilter", function () {
        return function (e, t, n) {
            var i = [];
            return (
                angular.forEach(e, function (e) {
                    n ? e.folderId == t && 1 == e.isImage && i.push(e) : e.folderId == t && i.push(e);
                }),
                    i
            );
        };
    }),
    zaa.filter("trustAsUnsafe", [
        "$sce",
        function (n) {
            return function (e, t) {
                return n.trustAsHtml(e);
            };
        },
    ]),
    zaa.filter("srcbox", function () {
        return function (e, t) {
            if (!e) return e;
            if (!t) return e;
            var a = ("" + t).toLowerCase(),
                o = {};
            return (
                angular.forEach(e, function (n, i) {
                    angular.forEach(n, function (e, t) {
                        -1 !== ("" + e).toLowerCase().indexOf(a) && (o[i] = n);
                    });
                }),
                    o
            );
        };
    }),
    zaa.filter("trustAsResourceUrl", [
        "$sce",
        function (n) {
            return function (e, t) {
                return t ? n.trustAsResourceUrl(e) : null;
            };
        },
    ]),
    zaa.filter("truncateMiddle", function () {
        return function (e, t, n) {
            if (((t = t || 30), (n = n || "..."), e.length <= t)) return e;
            var i = (t - n.length) / 2;
            return e.substring(0, i) + n + e.substring(e.length - i, e.length);
        };
    }),
    zaa.directive("echarts", [
        function () {
            return {
                scope: { id: "@", theme: "@", data: "=" },
                restrict: "E",
                template: '<div style="min-height:300px;height:auto;width:100%;"></div>',
                replace: !0,
                controller: [
                    "$scope",
                    function (e) {
                        e.theme && (e.theme = "macarons");
                    },
                ],
                link: function (e) {
                    var t = echarts.init(document.getElementById(e.id), e.theme);
                    e.$watch("data", function (e) {
                        e && null != e && t.setOption(angular.fromJson(e));
                    }),
                        angular.element(window).bind("resize", function () {
                            t.resize();
                        });
                },
            };
        },
    ]),
    zaa.directive("compileHtml", [
        "$compile",
        "$parse",
        function (a, o) {
            return {
                restrict: "A",
                link: function (e, t, n) {
                    var i = o(n.ngBindHtml);
                    e.$watch(
                        function () {
                            return (i(e) || "").toString();
                        },
                        function () {
                            a(t, null, -9999)(e);
                        }
                    );
                },
            };
        },
    ]),
    zaa.directive("selectOnClick", function () {
        return function (e, t, n) {
            t.bind("click", function () {
                this.select();
            });
        };
    }),
    zaa.directive("zaaEsc", [
        "$document",
        function (i) {
            return function (t, e, n) {
                i.on("keyup", function (e) {
                    27 == e.keyCode &&
                    t.$apply(function () {
                        t.$eval(n.zaaEsc);
                    });
                });
            };
        },
    ]),
    zaa.directive("linkObjectToString", function () {
        return {
            restrict: "E",
            relace: !0,
            scope: { link: "=" },
            template: function () {
                return '<span><span ng-if="link.type==1"><show-internal-redirection nav-id="link.value" /></span><span ng-if="link.type==2">{{link.value}}</span><span ng-if="link.type==3"><storage-file-display file-id="{{link.value}}"></storage-file-display></span><span ng-if="link.type==4">{{link.value}}</span><span ng-if="link.type==5">{{link.value}}</span></span>';
            },
        };
    }),
    zaa.directive("tooltip", [
        "$document",
        "$http",
        function (r, s) {
            return {
                restrict: "A",
                scope: { tooltipText: "@", tooltipExpression: "=", tooltipPosition: "@", tooltipOffsetTop: "@", tooltipOffsetLeft: "@", tooltipImageUrl: "@", tooltipPreviewUrl: "@", tooltipDisabled: "=" },
                link: function (i, a, e) {
                    function o() {
                        var e = {};
                        e = "function" == typeof l[i.tooltipPosition] ? l[i.tooltipPosition]() : l.right();
                        var t = parseInt(i.tooltipOffsetTop);
                        t && (e.top = e.top + t);
                        var n = parseInt(i.tooltipOffsetLeft);
                        n && (e.left = e.left + n), i.pop.css(e);
                    }
                    var l = {
                        top: function () {
                            var e = a[0].getBoundingClientRect();
                            return { top: e.top - i.pop.outerHeight(), left: e.left + e.width / 2 - i.pop.outerWidth() / 2 };
                        },
                        bottom: function () {
                            var e = a[0].getBoundingClientRect();
                            return { top: e.top + e.height, left: e.left + e.width / 2 - i.pop.outerWidth() / 2 };
                        },
                        right: function () {
                            var e = a[0].getBoundingClientRect();
                            return { top: e.top + e.height / 2 - i.pop.outerHeight() / 2, left: e.left + e.width };
                        },
                        left: function () {
                            var e = a[0].getBoundingClientRect();
                            return { top: e.top + e.height / 2 - i.pop.outerHeight() / 2, left: e.left - i.pop.outerWidth() };
                        },
                    };
                    a.on("mouseenter", function () {
                        if (!i.pop && (void 0 === i.tooltipDisabled || !1 === i.tooltipDisabled)) {
                            i.tooltipExpression && (i.tooltipText = i.tooltipExpression);
                            var e =
                                    '<div class="tooltip tooltip-' +
                                    (i.tooltipPosition || "right") +
                                    (i.tooltipImageUrl ? " tooltip-image" : "") +
                                    '" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner">' +
                                    (i.tooltipText ? '<span class="tooltip-text">' + i.tooltipText + "</span>" : "") +
                                    "</div></div>",
                                t = $(e);
                            if (i.tooltipImageUrl) {
                                var n = new Image();
                                (n.onload = function () {
                                    o();
                                }),
                                    (n.src = i.tooltipImageUrl),
                                    t.find(".tooltip-inner").append(n);
                            }
                            i.tooltipPreviewUrl &&
                            s.get(i.tooltipPreviewUrl).then(function (e) {
                                t.find(".tooltip-inner").append('<div class="tooltip-preview">' + e.data + "</div>");
                            }),
                                (i.pop = t),
                                r.find("body").append(i.pop),
                                i.pop.hide();
                        }
                        !i.pop || (void 0 !== i.tooltipDisabled && !1 !== i.tooltipDisabled) || (o(), a.parents().on("scroll", o), i.pop.show());
                    }),
                        a.on("mouseleave", function () {
                            a.parents().off("scroll", o), i.pop && i.pop.hide();
                        }),
                        i.$on("$destroy", function () {
                            i.pop && i.pop.remove();
                        });
                },
            };
        },
    ]),
    zaa.directive("convertToNumber", function () {
        return {
            require: "ngModel",
            link: function (e, t, n, i) {
                i.$parsers.push(function (e) {
                    return null != e ? parseInt(e, 10) : null;
                }),
                    i.$formatters.push(function (e) {
                        return null != e ? "" + e : null;
                    });
            },
        };
    }),
    zaa.directive("autoGrow", function () {
        return function (e, t, n) {
            function i() {
                null == a && ((a = angular.element("<div></div>").css({ position: "absolute", top: -1e4, left: -1e4, resize: "none" })), angular.element(document.body).append(a)),
                    a.css({ fontSize: t.css("font-size"), fontFamily: t.css("font-family"), lineHeight: t.css("line-height"), width: t.width(), paddingTop: t.css("padding-top"), paddingBottom: t.css("padding-bottom") });
                var e = t
                    .val()
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/&/g, "&amp;")
                    .replace(/\n$/, "<br/>&nbsp;")
                    .replace(/\n/g, "<br/>")
                    .replace(/\s{2,}/g, function (e) {
                        return (
                            (function (e, t) {
                                for (var n = 0, i = ""; n < t; n++) i += e;
                                return i;
                            })("&nbsp;", e.length - 1) + " "
                        );
                    });
                a.html(e), t.css("height", a.outerHeight() + 10 + "px");
            }
            var a = null;
            t.bind("keyup keydown keypress change click", i),
                t.bind("blur", function () {
                    null != a && (a.remove(), (a = null));
                }),
                i();
        };
    }),
    zaa.directive("resizer", [
        "$document",
        function (o) {
            return {
                scope: { trigger: "@" },
                link: function (e, t, i) {
                    function n(e) {
                        $(i.resizerCover).show();
                        var t = e.pageX,
                            n = window.innerWidth;
                        t < 600 && (t = 600), n - 400 < t && (t = n - 400);
                        $(i.resizerLeft).width(), $(i.resizerRight).width();
                        $(i.resizerLeft).css({ width: t + "px" }), $(i.resizerRight).css({ width: n - t + "px" });
                    }
                    function a() {
                        $(i.resizerCover).hide(), o.unbind("mousemove", n), o.unbind("mouseup", a);
                    }
                    e.$watch("trigger", function (e, t) {
                        0 == e && ($(i.resizerLeft).removeAttr("style"), $(i.resizerRight).removeAttr("style"));
                    }),
                        t.on("mousedown", function (e) {
                            e.preventDefault(), o.on("mousemove", n), o.on("mouseup", a);
                        });
                },
            };
        },
    ]),
    zaa.directive("ngConfirmClick", function () {
        return {
            link: function (t, e, n) {
                var i = n.ngConfirmClick || "Are you sure?",
                    a = n.confirmedClick;
                e.bind("click", function (e) {
                    window.confirm(i) && t.$eval(a);
                });
            },
        };
    }),
    zaa.directive("focusMe", [
        "$timeout",
        "$parse",
        function (a, o) {
            return {
                link: function (e, t, n) {
                    var i = o(n.focusMe);
                    e.$watch(i, function (e) {
                        !0 === e &&
                        a(function () {
                            t[0].focus();
                        });
                    });
                },
            };
        },
    ]),
    zaa.directive("clickPastePusher", [
        "$rootScope",
        "$compile",
        function (i, e) {
            return {
                restrict: "A",
                replace: !1,
                link: function (e, t, n) {
                    t.bind("click", function () {
                        i.$broadcast("insertPasteListener", n.clickPastePusher);
                    });
                },
            };
        },
    ]),
    zaa.directive("insertPasteListener", [
        "$rootScope",
        function (r) {
            return {
                restrict: "A",
                link: function (e, l, t) {
                    l.bind("focus", function () {
                        r.lastElement = l[0];
                        r.$on("insertPasteListener", function (e, t) {
                            var n = r.lastElement;
                            if (n != l[0] || !n) return !1;
                            if (((r.$$listeners.insertPasteListener = []), document.selection)) n.focus(), (document.selection.createRange().text = t), n.focus();
                            else if (n.selectionStart || 0 === n.selectionStart) {
                                var i = n.selectionStart,
                                    a = n.selectionEnd,
                                    o = n.scrollTop;
                                (n.value = n.value.substring(0, i) + t + n.value.substring(a, n.value.length)), n.focus(), (n.selectionStart = i + t.length), (n.selectionEnd = i + t.length), (n.scrollTop = o);
                            } else (n.value += t), n.focus();
                        });
                    });
                },
            };
        },
    ]),
    zaa.directive("modal", [
        "$timeout",
        function (e) {
            return {
                restrict: "E",
                scope: { isModalHidden: "=", title: "@modalTitle" },
                replace: !0,
                transclude: !0,
                templateUrl: "modal",
                controller: [
                    "$scope",
                    "AdminClassService",
                    function (e, n) {
                        e.$watch("isModalHidden", function (e, t) {
                            e !== t && (e ? n.modalStackRemove() : n.modalStackPush());
                        }),
                            (e.escModal = function () {
                                (e.isModalHidden = !0), n.modalStackRemoveAll();
                            });
                    },
                ],
                link: function (e, t) {
                    e.$on("$destroy", function () {
                        t.remove();
                    }),
                        angular.element(document.body).append(t);
                },
            };
        },
    ]),
    zaa.directive("collapseContainer", [
        function () {
            return {
                restrict: "E",
                scope: { title: "@", icon: "@" },
                replace: !0,
                transclude: !0,
                controller: [
                    "$scope",
                    function (e) {
                        (e.visible = !1),
                            (e.toggleVisibility = function () {
                                e.visible = !e.visible;
                            });
                    },
                ],
                template: function () {
                    return '<div class="card" ng-class="{\'card-closed\': !visible}"><div class="card-header" ng-click="toggleVisibility()"><span class="material-icons card-toggle-indicator">keyboard_arrow_down</span><i class="material-icons" ng-show="icon">{{icon}}</i><span>{{title}}</span></div><div class="card-body" ng-transclude></div></div>';
                },
            };
        },
    ]),
    zaa.directive("crudLoader", [
        "$http",
        "$sce",
        function (t, i) {
            return {
                restrict: "E",
                replace: !0,
                transclude: !1,
                scope: { api: "@", alias: "@", modelSelection: "@", modelSetter: "=" },
                controller: [
                    "$scope",
                    function (n) {
                        (n.input = { showWindow: !0 }),
                            (n.content = null),
                            (n.toggleWindow = function () {
                                if (n.input.showWindow) {
                                    if (-1 < n.api.indexOf("?")) var e = n.api + "&inline=1";
                                    else e = n.api + "?inline=1";
                                    parseInt(n.modelSelection) && (e = e + "&modelSelection=" + n.modelSetter),
                                        t.get(e).then(function (e) {
                                            (n.content = i.trustAsHtml(e.data)), (n.input.showWindow = !1);
                                        });
                                } else "function" == typeof n.$parent.loadService && n.$parent.loadService(), (n.input.showWindow = !0);
                            }),
                            n.$watch("input.showWindow", function (e, t) {
                                e !== t && 1 == e && "function" == typeof n.$parent.loadService && n.$parent.loadService();
                            }),
                            (n.setModelValue = function (e, t) {
                                (n.modelSetter = e), n.toggleWindow();
                            });
                    },
                ],
                template: function () {
                    return '<div class="crud-loader-tag"><button ng-click="toggleWindow()" type="button" class="btn btn-info btn-icon"><i class="material-icons">playlist_add</i></button><modal is-modal-hidden="input.showWindow" modal-title="{{alias}}"><div class="modal-body" compile-html ng-bind-html="content"></modal></div>';
                },
            };
        },
    ]),
    zaa.directive("crudRelationLoader", [
        "$http",
        "$sce",
        function (n, i) {
            return {
                restrict: "E",
                replace: !0,
                transclude: !1,
                scope: { api: "@api", arrayIndex: "@arrayIndex", modelClass: "@modelClass", id: "@id" },
                controller: [
                    "$scope",
                    function (t) {
                        if (((t.content = null), -1 < t.api.indexOf("?"))) var e = t.api + "&inline=1";
                        else e = t.api + "?inline=1";
                        n.get(e + "&relation=" + t.id + "&arrayIndex=" + t.arrayIndex + "&modelClass=" + t.modelClass).then(function (e) {
                            t.content = i.trustAsHtml(e.data);
                        });
                    },
                ],
                template: function () {
                    return '<div compile-html ng-bind-html="content"></div>';
                },
            };
        },
    ]),
    zaa.directive("zaaInjector", [
        "$compile",
        function (i) {
            return {
                restrict: "E",
                replace: !0,
                transclude: !1,
                scope: { dir: "=", model: "=", options: "=", label: "@label", grid: "@grid", fieldid: "@fieldid", placeholder: "@placeholder", initvalue: "@initvalue", autocomplete: "@autocomplete" },
                link: function (e, t) {
                    var n = i(
                        angular.element("<" + e.dir + ' options="options" initvalue="{{initvalue}}" fieldid="{{fieldid}}" placeholder="{{placeholder}}" autocomplete="{{autocomplete}}" model="model" label="{{label}}" i18n="{{grid}}" />')
                    )(e);
                    t.replaceWith(n);
                },
            };
        },
    ]),
    zaa.directive("zaaSortRelationArray", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                "$filter",
                function (a, e) {
                    a.searchString,
                        (a.sourceData = []),
                        (a.dropdownOpen = !1),
                        a.$watch(
                            function () {
                                return a.model;
                            },
                            function (e, t) {
                                null == e && (a.model = []);
                            }
                        ),
                        a.$watch(
                            function () {
                                return a.options;
                            },
                            function (e, t) {
                                null != e && (a.sourceData = e.sourceData);
                            }
                        ),
                        (a.getSourceOptions = function () {
                            return a.sourceData;
                        }),
                        (a.getModelItems = function () {
                            return a.model;
                        }),
                        (a.addToModel = function (n) {
                            var i = !1;
                            angular.forEach(a.model, function (e, t) {
                                e.value == n.value && (i = !0);
                            }),
                            i || a.model.push({ value: n.value, label: n.label });
                        }),
                        (a.removeFromModel = function (e) {
                            a.model.splice(e, 1);
                        }),
                        (a.moveUp = function (e) {
                            e = parseInt(e);
                            var t = a.model[e];
                            (a.model[e] = a.model[e - 1]), (a.model[e - 1] = t);
                        }),
                        (a.moveDown = function (e) {
                            e = parseInt(e);
                            var t = a.model[e];
                            (a.model[e] = a.model[e + 1]), (a.model[e + 1] = t);
                        }),
                        (a.elementInModel = function (n) {
                            var i = !1;
                            return (
                                angular.forEach(a.model, function (e, t) {
                                    e.value == n.value && (i = !0);
                                }),
                                    !i
                            );
                        });
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><div class="list"><div class="list-item" ng-repeat="(key, item) in getModelItems() track by key"><div class="list-buttons"><i ng-show="!$first" ng-click="moveUp(key)" class="material-icons" style="transform: rotate(270deg);">play_arrow</i><i ng-show="!$last" ng-click="moveDown(key)" class="material-icons" style="transform: rotate(90deg);">play_arrow</i></div><span>{{item.label}}</span><div class="float-right"><i ng-click="removeFromModel(key)" class="material-icons">delete</i></div></div><div class="list-item" ng-show="sourceData.length != model.length"><input class="form-control" type="search" ng-model="searchString" ng-focus="dropdownOpen = true" /><ul class="list-group"><li class="list-group-item list-group-item-action" ng-repeat="option in getSourceOptions() | filter:searchString" ng-show="dropdownOpen && elementInModel(option)" ng-click="addToModel(option)"><i class="material-icons">add_circle</i><span>{{ option.label }}</span></li></ul><div class="list-chevron"><i ng-click="dropdownOpen=!dropdownOpen" class="material-icons" ng-show="dropdownOpen">arrow_drop_up</i><i ng-click="dropdownOpen=!dropdownOpen" class="material-icons" ng-show="!dropdownOpen">arrow_drop_down</i></div></div></div></div>';
            },
        };
    }),
    zaa.directive("zaaTagArray", function () {
        return {
            restrict: "E",
            scope: { model: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                "$http",
                function (n, e) {
                    (n.tags = []),
                        e.get("admin/api-admin-common/tags").then(function (e) {
                            angular.forEach(e.data, function (e) {
                                (e.id = parseInt(e.id)), n.tags.push(e);
                            });
                        }),
                        null == n.model
                            ? (n.model = [])
                            : angular.forEach(n.model, function (e, t) {
                                n.model[t] = parseInt(e);
                            }),
                        (n.isInSelection = function (e) {
                            return (e = parseInt(e)), -1 != n.model.indexOf(e);
                        }),
                        (n.toggleSelection = function (e) {
                            var t = n.model.indexOf(e);
                            -1 < t ? n.model.splice(t, 1) : n.model.push(e);
                        });
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><span ng-click="toggleSelection(tag.id)" ng-repeat="tag in tags" ng-class="{\'badge-primary\' : isInSelection(tag.id), \'badge-secondary\' : !isInSelection(tag.id)}" class="badge badge-pill mx-1 mb-2">{{tag.name}}</span></div></div>';
            },
        };
    }),
    zaa.directive("zaaLink", [
        "$filter",
        function (t) {
            return {
                restrict: "E",
                scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
                controller: [
                    "$scope",
                    function (n) {
                        (n.unset = function () {
                            (n.model = !1), (n.data.model = null);
                        }),
                            (n.data = { modalState: 1, model: null }),
                            n.$watch(
                                "model",
                                function (e, t) {
                                    e && (n.data.model = e);
                                },
                                !0
                            ),
                            n.$watch(
                                "data.model",
                                function (e, t) {
                                    e && (n.model = e);
                                },
                                !0
                            ),
                            (n.isEmpty = function (e) {
                                return !e || t("isEmpty")(e);
                            });
                    },
                ],
                template: function () {
                    return (
                        '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><labelfor="{{id}}">{{label}}</label></div><div class="form-side"><div ng-if="!isEmpty(data.model)"><div class="link-selector"><div class="link-selector-actions"><div class="link-selector-btn btn btn-secondary" ng-click="data.modalState=0"><i class="material-icons left">insert_link</i><span>' +
                        i18n.js_link_change_value +
                        '</span></div><span ng-hide="model | isEmpty" class="link-selector-reset" ng-click="unset()"><i class="material-icons">remove_circle</i></span></div><link-object-to-string class="ml-2" link="model"></link-object-to-string></div></div><div ng-if="isEmpty(data.model)"><div class="link-selector"><div class="link-selector-actions"><div class="link-selector__btn btn btn-secondary" ng-click="data.modalState=0"><i class="material-icons left">insert_link</i><span>' +
                        i18n.js_link_set_value +
                        '</span></div><span style="margin-left:10px;">' +
                        i18n.js_link_not_set +
                        '</span></div></div></div><modal is-modal-hidden="data.modalState" modal-title="{{label}}"><form ng-submit="data.modalState=1"><zaa-link-options data="data.model" uid="id" ng-if="!data.modalState"></zaa-link-options><button ng-click="data.modalState=1" class="btn btn-icon btn-save" type="submit">' +
                        i18n.js_link_set_value +
                        "</button></form></modal></div></div>"
                    );
                },
            };
        },
    ]),
    zaa.directive("zaaLinkOptions", function () {
        return {
            restrict: "EA",
            scope: { data: "=", uid: "=" },
            templateUrl: "linkoptions.html",
            controller: [
                "$scope",
                function (n) {
                    n.$watch(
                        function () {
                            return n.data;
                        },
                        function (e, t) {
                            angular.isArray(e) && (n.data = {});
                        }
                    );
                },
            ],
        };
    }),
    zaa.directive("zaaSlug", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", listener: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                "$filter",
                function (n, i) {
                    n.$watch("listener", function (e, t) {
                        void 0 !== e && (n.model = i("slugify")(e));
                    }),
                        n.$watch("model", function (e, t) {
                            e != t && (n.model = i("slugify")(e));
                        });
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><input id="{{id}}" insert-paste-listener ng-model="model" type="text" class="form-control" placeholder="{{placeholder}}" /></div></div>';
            },
        };
    }),
    zaa.directive("zaaColor", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                function (n) {
                    function i() {
                        if (void 0 === n.model || !n.model) return "#000";
                        var e = n.model;
                        if (("string" == typeof n.model && (e = e.substr(1)), 3 === e.length)) {
                            e = e.replace(/^#?([a-f\d])([a-f\d])([a-f\d])$/i, function (e, t, n, i) {
                                return t + t + n + n + i + i;
                            });
                        }
                        return 6 !== e.length ? "#000" : 128 <= (299 * parseInt(e.substr(0, 2), 16) + 587 * parseInt(e.substr(2, 2), 16) + 114 * parseInt(e.substr(4, 2), 16)) / 1e3 ? "#000" : "#fff";
                    }
                    (n.textColor = i()),
                        n.$watch(
                            function () {
                                return n.model;
                            },
                            function (e, t) {
                                n.textColor = i();
                            }
                        );
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><div class="colorwheel"><div class="colorwheel-background" style="background-color: {{model}};"><input class="colorwheel-input" type="text" ng-model="model" style="color: {{textColor}}; border-color: {{textColor}};" maxlength="7" /></div><div class="colorwheel-wheel"><div ng-colorwheel="{ size: 150, segments: 120 }" ng-model="model"></div></div></div></div></div>';
            },
        };
    }),
    zaa.directive("zaaWysiwyg", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><ng-wig ng-disabled="false" ng-model="model" buttons="bold, italic, link, list1, list2" source-mode-allowed></ng-wig></div></div>';
            },
        };
    }),
    zaa.directive("zaaNumber", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid", placeholder: "@placeholder", initvalue: "@initvalue" },
            link: function (n) {
                n.$watch(
                    function () {
                        return n.model;
                    },
                    function (e, t) {
                        null == e && (n.model = parseInt(n.initvalue)), angular.isNumber(n.model) ? (n.isValid = !0) : (n.isValid = !1);
                    }
                );
            },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><input id="{{id}}" ng-model="model" type="number" min="0" class="form-control" ng-class="{\'invalid\' : !isValid }" placeholder="{{placeholder}}" /></div></div>';
            },
        };
    }),
    zaa.directive("zaaDecimal", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid", placeholder: "@placeholder" },
            controller: [
                "$scope",
                function (e) {
                    null === e.options ? (e.steps = 0.01) : (e.steps = e.options.steps);
                },
            ],
            link: function (n) {
                n.$watch(
                    function () {
                        return n.model;
                    },
                    function (e, t) {
                        angular.isNumber(n.model) ? (n.isValid = !0) : (n.isValid = !1);
                    }
                );
            },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><input id="{{id}}" ng-model="model" type="number" min="0" step="{{steps}}" class="form-control" ng-class="{\'invalid\' : !isValid }" placeholder="{{placeholder}}" /></div></div>';
            },
        };
    }),
    zaa.directive("zaaText", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid", placeholder: "@placeholder", autocomplete: "@autocomplete" },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><input id="{{id}}" insert-paste-listener ng-model="model" type="text" class="form-control" autocomplete="{{autocomplete}}" placeholder="{{placeholder}}" /></div></div>';
            },
        };
    }),
    zaa.directive("zaaReadonly", function () {
        return {
            restrict: "E",
            scope: { model: "=", label: "@label", i18n: "@i18n" },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side"><span class="text-muted">{{model}}</span></div></div>';
            },
        };
    }),
    zaa.directive("zaaAsyncValue", function () {
        return {
            restrict: "E",
            scope: { model: "=", api: "@", fields: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                "$timeout",
                "$http",
                function (e, t, n) {
                    e.resetValue = function () {
                        (e.model = 0), (e.value = null);
                    };
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><async-value model="model" api="{{api}}" fields="fields"  ng-show="model"></async-value><button type="button" class="btn btn-icon btn-cancel" ng-click="resetValue()" ng-show="model"></button></div></div>';
            },
        };
    }),
    zaa.directive("asyncValue", function () {
        return {
            restrict: "E",
            scope: { model: "=", api: "@", fields: "=" },
            controller: [
                "$scope",
                "$timeout",
                "$http",
                function (n, e, i) {
                    e(function () {
                        n.$watch("model", function (e, t) {
                            e &&
                            ((n.value = ""),
                                i.get(n.api + "/" + e + "?fields=" + n.fields.join()).then(function (e) {
                                    n.value,
                                        angular.forEach(e.data, function (e) {
                                            e && (n.value = n.value + e + " ");
                                        });
                                }));
                        });
                    });
                },
            ],
            template: function () {
                return '<span ng-bind="value"></span>';
            },
        };
    }),
    zaa.directive("zaaTextarea", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid", placeholder: "@placeholder" },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><textarea id="{{id}}" insert-paste-listener ng-model="model" type="text" class="form-control" auto-grow placeholder="{{placeholder}}"></textarea></div></div>';
            },
        };
    }),
    zaa.directive("zaaPassword", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid", autocomplete: "@autocomplete" },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><input id="{{id}}" ng-model="model" type="password" class="form-control" autocomplete="{{autocomplete}}" placeholder="{{placeholder}}" /></div></div>';
            },
        };
    }),
    zaa.directive("zaaRadio", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid", initvalue: "@initvalue" },
            controller: [
                "$scope",
                "$timeout",
                function (t, e) {
                    (t.setModelValue = function (e) {
                        t.model = e;
                    }),
                        (t.init = function () {
                            (null != t.model && null != t.model) || (t.model = typeCastValue(t.initvalue));
                        }),
                        e(function () {
                            t.init();
                        });
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><div ng-repeat="(key, item) in options" class="form-check"><input value="{{item.value}}" type="radio" ng-click="setModelValue(item.value)" ng-checked="item.value == model" name="{{id}}_{{key}}" class="form-check-input" id="{{id}}_{{key}}"><label class="form-check-label" for="{{id}}_{{key}}">{{item.label}}</label></div></div></div>';
            },
        };
    }),
    zaa.directive("zaaSelect", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", optionsvalue: "@optionsvalue", optionslabel: "@optionslabel", label: "@label", i18n: "@i18n", id: "@fieldid", initvalue: "@initvalue" },
            controller: [
                "$scope",
                "$timeout",
                "$rootScope",
                function (e, t, n) {
                    null == e.optionsvalue && (e.optionsvalue = "value"), null == e.optionslabel && (e.optionslabel = "label");
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><luya-select ng-model="model" options="options" id="{{id}}" optionsvalue="{{optionsvalue}}" optionslabel="{{optionslabel}}" initvalue="{{initvalue}}"></luya-select></div></div>';
            },
        };
    }),
    zaa.directive("zaaSelectCrud", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", api: "@api", label: "@label", i18n: "@i18n", id: "@fieldid", initvalue: "@initvalue" },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><async-value model="model" api="{{options.api}}" fields="options.fields"></async-value><crud-loader api="{{options.route}}" model-setter="model" model-selection="1" alias="{{label}}"></crud-loader></div></div>';
            },
        };
    }),
    zaa.directive("luyaSelect", function () {
        return {
            restrict: "E",
            scope: { model: "=ngModel", options: "=", optionsvalue: "@optionsvalue", optionslabel: "@optionslabel", id: "@fieldid", initvalue: "@initvalue", ngChange: "&" },
            controller: [
                "$scope",
                "$timeout",
                "$rootScope",
                function (i, t, e) {
                    (i.isOpen = 0),
                    (null != i.optionsvalue && "" != i.optionsvalue) || (i.optionsvalue = "value"),
                    (null != i.optionslabel && "" != i.optionslabel) || (i.optionslabel = "label"),
                    angular.isNumber(i.model) && (i.model = typeCastValue(i.model)),
                        i.$on("closeAllSelects", function () {
                            i.isOpen && i.closeSelect();
                        }),
                        t(function () {
                            i.$watch(
                                function () {
                                    return i.model;
                                },
                                function (e, t) {
                                    (null != e && null != e && "" != e) || (angular.isNumber(i.initvalue) && (i.initvalue = typeCastValue(i.initvalue)), i.valueExistsInOptions(e) || (i.model = i.initvalue));
                                }
                            );
                        }),
                        (i.valueExistsInOptions = function (t) {
                            var n = !1;
                            return (
                                angular.forEach(i.options, function (e) {
                                    t == e[i.optionsvalue] && (n = !0);
                                }),
                                    n
                            );
                        }),
                        (i.toggleIsOpen = function () {
                            i.isOpen || e.$broadcast("closeAllSelects"), (i.isOpen = !i.isOpen);
                        }),
                        (i.closeSelect = function () {
                            i.isOpen = 0;
                        }),
                        (i.setModelValue = function (e) {
                            (i.model = angular.isObject(e) ? e[i.optionsvalue] : e), t(i.ngChange, 0), i.closeSelect();
                        }),
                        (i.getSelectedLabel = function () {
                            var t = i18n.ngrest_select_no_selection;
                            return (
                                angular.forEach(i.options, function (e) {
                                    i.model == e[i.optionsvalue] && (t = e[i.optionslabel]);
                                }),
                                    t
                            );
                        }),
                        (i.hasSelectedValue = function () {
                            var e = i.model;
                            return !(!i.valueExistsInOptions(e) || e == i.initvalue);
                        });
                },
            ],
            template: function () {
                return '<div class="zaaselect" ng-class="{\'open\':isOpen, \'selected\':hasSelectedValue()}"><select class="zaaselect-select" ng-model="model"><option ng-repeat="opt in options" ng-value="opt[optionsvalue]">{{opt[optionslabel]}}</option></select><div class="zaaselect-selected"><span class="zaaselect-selected-text" ng-click="toggleIsOpen()">{{getSelectedLabel()}}</span><i class="material-icons zaaselect-clear-icon" ng-click="setModelValue(initvalue)">clear</i><i class="material-icons zaaselect-dropdown-icon" ng-click="toggleIsOpen()">keyboard_arrow_down</i></div><div class="zaaselect-dropdown"><div class="zaaselect-search"><input class="zaaselect-search-input" type="search" focus-me="isOpen" ng-model="searchQuery" /></div><div class="zaaselect-overflow" ng-if="isOpen"><div class="zaaselect-item" ng-repeat="opt in options | filter:searchQuery"><span class="zaaselect-label" ng-class="{\'zaaselect-label-active\': opt[optionsvalue] == model}" ng-click="opt[optionsvalue] == model ? false : setModelValue(opt)">{{opt[optionslabel]}}</span></div></div></div></div>';
            },
        };
    }),
    zaa.directive("zaaCheckbox", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", i18n: "@i18n", id: "@fieldid", label: "@label", initvalue: "@initvalue" },
            controller: [
                "$scope",
                "$timeout",
                function (e, t) {
                    null === e.options || void 0 === e.options ? ((e.valueTrue = 1), (e.valueFalse = 0)) : ((e.valueTrue = e.options["true-value"]), (e.valueFalse = e.options["false-value"])),
                        (e.init = function () {
                            (null != e.model && null != e.model) || (e.model = typeCastValue(e.initvalue));
                        }),
                        t(function () {
                            e.init();
                        }),
                        (e.clicker = function () {
                            e.model == e.valueTrue ? (e.model = e.valueFalse) : (e.model = e.valueTrue);
                        });
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><div class="form-check"><input id="{{id}}" ng-true-value="{{valueTrue}}" ng-change="change()" ng-click="clicker()" ng-false-value="{{valueFalse}}" ng-model="model" type="checkbox" class="form-check-input-standalone" ng-checked="model == valueTrue" /><label for="{{id}}"></label></div></div></div>';
            },
        };
    }),
    zaa.directive("zaaCheckboxArray", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", i18n: "@i18n", id: "@fieldid", label: "@label", preselect: "@preselect" },
            controller: [
                "$scope",
                "$filter",
                function (n, i) {
                    null == n.model && (n.model = []),
                        (n.preselectOptionValuesToModel = function (e) {
                            angular.forEach(e, function (e) {
                                n.model.push({ value: e.value });
                            });
                        }),
                        (n.searchString = ""),
                        n.$watch("options", function (e, t) {
                            null != e && e.hasOwnProperty("items") && ((n.optionitems = i("orderBy")(e.items, "label")), n.preselect && n.preselectOptionValuesToModel(e.items));
                        }),
                        (n.filtering = function () {
                            n.optionitems = i("filter")(n.options.items, n.searchString);
                        }),
                        (n.toggleSelection = function (e) {
                            for (var t in (null == n.model && (n.model = []), n.model)) if (n.model[t].value == e.value) return void n.model.splice(t, 1);
                            n.model.push({ value: e.value });
                        }),
                        (n.isChecked = function (e) {
                            for (var t in n.model) if (n.model[t].value == e.value) return !0;
                            return !1;
                        });
                },
            ],
            link: function (e) {
                e.random = Math.random().toString(36).substring(7);
            },
            template: function () {
                return (
                    '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><div class="position-relative mb-3"><div class="input-group"><div class="input-group-prepend"><div class="input-group-text"><i class="material-icons">search</i></div></div><input class="form-control" type="text" ng-change="filtering()" ng-model="searchString" placeholder="' +
                    i18n.ngrest_crud_search_text +
                    '"></div><span class="zaa-checkbox-array-counter badge badge-secondary">{{optionitems.length}} ' +
                    i18n.js_dir_till +
                    ' {{options.items.length}}</span></div><div class="form-check" ng-repeat="(k, item) in optionitems track by k"><input type="checkbox" class="form-check-input" ng-checked="isChecked(item)" id="{{random}}_{{k}}" ng-click="toggleSelection(item)" /><label for="{{random}}_{{k}}">{{item.label}}</label></div></div></div>'
                );
            },
        };
    }),
    zaa.directive("zaaDatetime", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", id: "@fieldid", i18n: "@i18n", resetable: "@resetable" },
            controller: [
                "$scope",
                "$filter",
                function (i, a) {
                    (i.isNumeric = function (e) {
                        return !isNaN(e);
                    }),
                        i.$watch(
                            function () {
                                return i.model;
                            },
                            function (e, t) {
                                if (null != e && null != e) {
                                    var n = new Date(1e3 * e);
                                    (i.pickerPreselect = n), (i.date = a("date")(n, "dd.MM.yyyy")), (i.hour = a("date")(n, "HH")), (i.min = a("date")(n, "mm"));
                                } else (i.date = null), (i.model = null);
                            }
                        ),
                        (i.refactor = function (e) {
                            if (((i.isNumeric(i.hour) && "" != i.hour) || (i.hour = "0"), (i.isNumeric(i.min) && "" != i.min) || (i.min = "0"), "Invalid Date" == e || "" == e || "NaN" == e)) (i.date = null), (i.model = null);
                            else {
                                var t = e.split(".");
                                if (3 == t.length && 4 == t[2].length) {
                                    23 < parseInt(i.hour) && (i.hour = 23), 59 < parseInt(i.min) && (i.min = 59);
                                    var n = t[1] + "/" + t[0] + "/" + t[2] + " " + i.hour + ":" + i.min;
                                    (i.model = Date.parse(n) / 1e3), (i.datePickerToggler = !1);
                                }
                            }
                        }),
                        i.$watch(
                            function () {
                                return i.date;
                            },
                            function (e, t) {
                                e != t && null != e && null != e && i.refactor(e);
                            }
                        ),
                        (i.autoRefactor = function () {
                            i.refactor(i.date);
                        }),
                        (i.datePickerToggler = !1),
                        (i.toggleDatePicker = function () {
                            i.datePickerToggler = !i.datePickerToggler;
                        }),
                        (i.openDatePicker = function () {
                            i.datePickerToggler = !0;
                        }),
                        (i.closeDatePicker = function () {
                            i.datePickerToggler = !1;
                        }),
                        (i.hour = "0"),
                        (i.min = "0"),
                        (i.reset = function () {
                            i.model = null;
                        }),
                        (i.getIsResetable = function () {
                            return !i.resetable || parseInt(i.resetable);
                        });
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side zaa-datetime" ng-class="{\'input--hide-label\': i18n, \'input--with-time\': model!=null && date!=null}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side form-inline datepicker-wrapper"><datepicker class="input-group input-group--append-clickable" date-set="{{pickerPreselect.toString()}}" date-week-start-day="1" datepicker-toggle="false" datepicker-show="{{datePickerToggler}}" date-format="dd.MM.yyyy"><input class="form-control datepicker-date-input" ng-model="date" type="text" ng-focus="openDatePicker()" /><div class="input-group-append" ng-click="toggleDatePicker()"><div class="input-group-text"><i class="material-icons" ng-hide="datePickerToggler">date_range</i><i class="material-icons" ng-show="datePickerToggler">close</i></div></div></datepicker><div ng-show="model!=null && date!=null" class="hour-selection"><div class="input-group"><input class="form-control zaa-datetime-hour-input" type="text" ng-model="hour" ng-change="autoRefactor()" /></div><div class="input-group"><div class="input-group-prepend zaa-datetime-time-colon"><div class="input-group-text">:</div></div><input class="form-control form-control--force-border zaa-datetime-minute-input" type="text" ng-model="min" ng-change="autoRefactor()" /></div></div><div ng-show="model && getIsResetable()"><button type="button" ng-click="reset()" class="ml-2 btn btn-icon btn-cancel"></nutton></div></div></div>';
            },
        };
    }),
    zaa.directive("zaaDate", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", id: "@fieldid", i18n: "@i18n", resetable: "@resetable" },
            controller: [
                "$scope",
                "$filter",
                function (i, a) {
                    i.$watch(
                        function () {
                            return i.model;
                        },
                        function (e, t) {
                            if (null != e && null != e) {
                                var n = new Date(1e3 * e);
                                (i.pickerPreselect = n), (i.date = a("date")(n, "dd.MM.yyyy"));
                            } else (i.date = null), (i.model = null);
                        }
                    ),
                        (i.refactor = function (e) {
                            if ("Invalid Date" == e || "" == e) (i.date = null), (i.model = null);
                            else {
                                var t = e.split(".");
                                if (3 == t.length && 4 == t[2].length) {
                                    var n = t[1] + "/" + t[0] + "/" + t[2];
                                    (i.model = Date.parse(n) / 1e3), (i.datePickerToggler = !1);
                                }
                            }
                        }),
                        i.$watch(
                            function () {
                                return i.date;
                            },
                            function (e, t) {
                                e != t && null != e && null != e && i.refactor(e);
                            }
                        ),
                        (i.autoRefactor = function () {
                            i.refactor(i.date);
                        }),
                        (i.datePickerToggler = !1),
                        (i.toggleDatePicker = function () {
                            i.datePickerToggler = !i.datePickerToggler;
                        }),
                        (i.openDatePicker = function () {
                            i.datePickerToggler = !0;
                        }),
                        (i.closeDatePicker = function () {
                            i.datePickerToggler = !1;
                        }),
                        (i.reset = function () {
                            i.model = null;
                        }),
                        (i.getIsResetable = function () {
                            return !i.resetable || parseInt(i.resetable);
                        });
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side zaa-date" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side form-inline datepicker-wrapper"><datepicker class="input-group input-group--append-clickable" date-set="{{pickerPreselect.toString()}}" date-week-start-day="1" datepicker-toggle="false" datepicker-show="{{datePickerToggler}}" date-format="dd.MM.yyyy"><input class="form-control datepicker-date-input" ng-model="date" type="text" ng-focus="openDatePicker()" /><div class="input-group-append" ng-click="toggleDatePicker()"><div class="input-group-text"><i class="material-icons" ng-hide="datePickerToggler">date_range</i><i class="material-icons" ng-show="datePickerToggler">close</i></div></div></datepicker><div ng-show="model && getIsResetable()"><button type="button" ng-click="reset()" class="ml-2 btn btn-icon btn-cancel"></nutton></div></div></div>';
            },
        };
    }),
    zaa.directive("zaaTable", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                function (i) {
                    null == i.model && (i.model = [{ 0: "" }]),
                        (i.addColumn = function () {
                            var e = 0;
                            for (var t in i.model[0]) e++;
                            for (var n in i.model) i.model[n][e] = "";
                        }),
                        (i.addRow = function () {
                            var e = i.model[0],
                                t = {};
                            for (var n in e) t[n] = "";
                            i.model.push(t);
                        }),
                        (i.removeColumn = function (e) {
                            for (var t in i.model) {
                                var n = i.model[t];
                                n instanceof Array ? n.splice(e, 1) : delete n[e];
                            }
                        }),
                        (i.moveLeft = function (e) {
                            for (var t in ((e = parseInt(e)), i.model)) {
                                var n = i.model[t][e];
                                (i.model[t][e] = i.model[t][e - 1]), (i.model[t][e - 1] = n);
                            }
                        }),
                        (i.moveRight = function (e) {
                            for (var t in ((e = parseInt(e)), i.model)) {
                                var n = i.model[t][e];
                                (i.model[t][e] = i.model[t][e + 1]), (i.model[t][e + 1] = n);
                            }
                        }),
                        (i.moveUp = function (e) {
                            e = parseInt(e);
                            var t = i.model[e];
                            (i.model[e] = i.model[e - 1]), (i.model[e - 1] = t);
                        }),
                        (i.moveDown = function (e) {
                            e = parseInt(e);
                            var t = i.model[e];
                            (i.model[e] = i.model[e + 1]), (i.model[e + 1] = t);
                        }),
                        (i.removeRow = function (e) {
                            i.model.splice(e, 1);
                        }),
                        (i.showRightButton = function (e) {
                            return parseInt(e) < Object.keys(i.model[0]).length - 1;
                        }),
                        (i.showDownButton = function (e) {
                            return parseInt(e) < Object.keys(i.model).length - 1;
                        });
                },
            ],
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label ng-if="label">{{label}}</label><label ng-if="!label">Table</label></div><div class="form-side"><div class="zaa-table-wrapper"><table class="zaa-table table table-bordered"><tbody><tr><th scope="col" width="35px"></th><th scope="col" data-ng-repeat="(hk, hr) in model[0] track by hk" class="zaa-table-buttons"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveLeft(hk)" ng-if="hk > 0"><i class="material-icons">keyboard_arrow_left</i></button><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveRight(hk)" ng-if="showRightButton(hk)"><i class="material-icons">keyboard_arrow_right</i></button><button type="button" class="btn btn-sm btn-outline-danger" ng-click="removeColumn(hk)"><i class="material-icons">remove</i></button></div></th></tr><tr data-ng-repeat="(key, row) in model track by key"><td width="35px" scope="row" class="zaa-table-buttons"><div class="btn-group-vertical" role="group"><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveUp(key)" ng-if="key > 0"><i class="material-icons">keyboard_arrow_up</i></button><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveDown(key)" ng-if="showDownButton(key)"><i class="material-icons">keyboard_arrow_down</i></button><button type="button" class="btn btn-sm btn-outline-danger" ng-click="removeRow(key)"><i class="material-icons">remove</i></button></div></td><td data-ng-repeat="(field,value) in row track by field"><textarea ng-model="model[key][field]" class="zaa-table__textarea"></textarea></td></tr></tbody></table><button ng-click="addRow()" type="button" class="zaa-table-add-row btn btn-sm btn-success"><i class="material-icons">add</i></button><button ng-click="addColumn()" type="button" class="zaa-table-add-column btn btn-sm btn-success"><i class="material-icons">add</i></button></div></div></div>';
            },
        };
    }),
    zaa.directive("zaaFileUpload", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side"><storage-file-upload ng-model="model"></storage-file-upload></div></div>';
            },
        };
    }),
    zaa.directive("zaaImageUpload", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            template: function () {
                return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side"><storage-image-upload options="options" ng-model="model"></storage-image-upload></div></div>';
            },
        };
    }),
    zaa.directive("zaaImageArrayUpload", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            link: function (e, n, t) {
                e.$watch(
                    "model",
                    function (e, t) {
                        1 <= e.length ? $(n).removeClass("is-empty").addClass("is-not-empty") : $(n).removeClass("is-not-empty").addClass("is-empty");
                    },
                    !0
                );
            },
            controller: [
                "$scope",
                function (n) {
                    null == n.model && (n.model = []),
                        (n.add = function () {
                            (null != n.model && "" != n.model && null != n.model) || (n.model = []), n.model.push({ imageId: 0, caption: "" });
                        }),
                        (n.remove = function (e) {
                            n.model.splice(e, 1);
                        }),
                        (n.moveUp = function (e) {
                            e = parseInt(e);
                            var t = n.model[e];
                            (n.model[e] = n.model[e - 1]), (n.model[e - 1] = t);
                        }),
                        (n.moveDown = function (e) {
                            e = parseInt(e);
                            var t = n.model[e];
                            (n.model[e] = n.model[e + 1]), (n.model[e + 1] = t);
                        }),
                        (n.showDownButton = function (e) {
                            return parseInt(e) < Object.keys(n.model).length - 1;
                        });
                },
            ],
            template: function () {
                return (
                    '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side"><div class="list zaa-file-array-upload"><p class="alert alert-info" ng-hide="model.length > 0">' +
                    i18n.js_dir_no_selection +
                    '</p><div ng-repeat="(key,image) in model track by key" class="list-item"><div class="list-section"><div class="list-left"><storage-image-upload ng-model="image.imageId" options="options"></storage-image-upload></div><div class="list-right"><div class="form-group"><label for="{{image.id}}">' +
                    i18n.js_dir_image_description +
                    '</label><textarea ng-model="image.caption" id="{{image.id}}" class="zaa-file-array-upload-description form-control" auto-grow></textarea></div></div></div><div class="list-buttons"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveUp(key)" ng-if="key > 0"><i class="material-icons">keyboard_arrow_up</i></button><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveDown(key)" ng-if="showDownButton(key)"><i class="material-icons">keyboard_arrow_down</i></button><button type="button" class="btn btn-sm btn-outline-danger" ng-click="remove(key)"><i class="material-icons">remove</i></button></div></div></div><button ng-click="add()" type="button" class="btn btn-sm btn-success list-add-button"><i class="material-icons">add</i></button></div></div></div>'
                );
            },
        };
    }),
    zaa.directive("zaaFileArrayUpload", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                "$element",
                "$timeout",
                function (n, e, t) {
                    null == n.model && (n.model = []),
                        (n.add = function () {
                            (null != n.model && "" != n.model && null != n.model) || (n.model = []), n.model.push({ fileId: 0, caption: "" });
                        }),
                        (n.remove = function (e) {
                            n.model.splice(e, 1);
                        }),
                        (n.moveUp = function (e) {
                            e = parseInt(e);
                            var t = n.model[e];
                            (n.model[e] = n.model[e - 1]), (n.model[e - 1] = t);
                        }),
                        (n.moveDown = function (e) {
                            e = parseInt(e);
                            var t = n.model[e];
                            (n.model[e] = n.model[e + 1]), (n.model[e + 1] = t);
                        }),
                        (n.showDownButton = function (e) {
                            return parseInt(e) < Object.keys(n.model).length - 1;
                        });
                },
            ],
            template: function () {
                return (
                    '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side"><div class="list zaa-file-array-upload"><p class="alert alert-info" ng-hide="model.length > 0">' +
                    i18n.js_dir_no_selection +
                    '</p><div ng-repeat="(key,file) in model track by key" class="list-item"><div class="list-section" ng-if="file.hiddenStorageUploadSource"><a ng-href="{{file.hiddenStorageUploadSource}}" target="_blank" class="btn btn-primary">{{file.hiddenStorageUploadName}}</a></div><div class="list-section" ng-if="!file.hiddenStorageUploadSource"><div class="list-left"><storage-file-upload ng-model="file.fileId"></storage-file-upload></div><div class="list-right"><div class="form-group"><label for="{{file.id}}">' +
                    i18n.js_dir_image_description +
                    '</label><textarea ng-model="file.caption" id="{{file.id}}" class="zaa-file-array-upload-description form-control" auto-grow></textarea></div></div></div><div class="list-buttons"  ng-if="!file.hiddenStorageUploadSource"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveUp(key)" ng-if="key > 0"><i class="material-icons">keyboard_arrow_up</i></button><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveDown(key)" ng-if="showDownButton(key)"><i class="material-icons">keyboard_arrow_down</i></button><button type="button" class="btn btn-sm btn-outline-danger" ng-click="remove(key)"><i class="material-icons">remove</i></button></div></div></div></div><button ng-click="add()" type="button" class="btn btn-sm btn-success list-add-button"><i class="material-icons">add</i></button></div></div>'
                );
            },
        };
    }),
    zaa.directive("zaaMultipleInputs", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                "$timeout",
                function (n, e) {
                    (n.init = function () {
                        null == n.model || null == n.model
                            ? (n.model = [])
                            : angular.forEach(n.model, function (e, t) {
                                0 == Object.keys(e).length && (n.model[t] = {});
                            });
                    }),
                        (n.add = function () {
                            (null != n.model && "" != n.model && null != n.model) || (n.model = []), n.model.push({});
                        }),
                        (n.remove = function (e) {
                            n.model.splice(e, 1);
                        }),
                        (n.moveUp = function (e) {
                            e = parseInt(e);
                            var t = n.model[e];
                            (n.model[e] = n.model[e - 1]), (n.model[e - 1] = t);
                        }),
                        (n.moveDown = function (e) {
                            e = parseInt(e);
                            var t = n.model[e];
                            (n.model[e] = n.model[e + 1]), (n.model[e + 1] = t);
                        }),
                        (n.showDownButton = function (e) {
                            return parseInt(e) < Object.keys(n.model).length - 1;
                        }),
                        e(function () {
                            n.init();
                        });
                },
            ],
            template: function () {
                return (
                    '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side"><div class="list zaa-multiple-inputs"><p class="alert alert-info" ng-hide="model.length > 0">' +
                    i18n.js_dir_no_selection +
                    '</p><div ng-repeat="(msortKey,row) in model track by msortKey" class="list-item" ng-init="ensureRow(row)"><div ng-repeat="(mutliOptKey,opt) in options track by mutliOptKey"><zaa-injector dir="opt.type" options="opt.options" fieldid="id-{{msortKey}}-{{mutliOptKey}}" initvalue="{{opt.initvalue}}" label="{{opt.label}}" model="row[opt.var]"></zaa-injector></div><div class="list-buttons"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveUp(msortKey)" ng-if="msortKey > 0"><i class="material-icons">keyboard_arrow_up</i></button><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveDown(msortKey)" ng-if="showDownButton(msortKey)"><i class="material-icons">keyboard_arrow_down</i></button><button type="button" class="btn btn-sm btn-outline-danger" ng-click="remove(msortKey)"><i class="material-icons">remove</i></button></div></div></div><button ng-click="add()" type="button" class="btn btn-sm btn-success list-add-button"><i class="material-icons">add</i></button></div></div></div>'
                );
            },
        };
    }),
    zaa.directive("zaaJsonObject", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                function (t) {
                    t.$watch("model", function (e) {
                        angular.isArray(e) && (t.model = {}), null == e && (t.model = {});
                    }),
                        (t.add = function (e) {
                            t.model[e] = "";
                        }),
                        (t.remove = function (e) {
                            delete t.model[e];
                        });
                },
            ],
            template: function () {
                return (
                    '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side"><div class="list zaa-json-array"><div ng-repeat="(key,value) in model" class="list-item"><div class="input-group"><div class="input-group-prepend border-right"><div class="input-group-text text-muted">{{key}}</div></div><input class="form-control" type="text" ng-model="model[key]" /></div><div class="list-buttons"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-danger" ng-click="remove(key)"><i class="material-icons">remove</i></button></div></div></div><div class="input-group input-group--append-clickable"><input type="text" class="form-control" placeholder="' +
                    i18n.js_jsonobject_newkey +
                    '" aria-label="' +
                    i18n.js_jsonobject_newkey +
                    '" ng-model="newKey"><div class="input-group-append"><div class="input-group-text" ng-click="add(newKey);newKey=null;"><i class="material-icons">add</i></div></div></div></div></div></div>'
                );
            },
        };
    }),
    zaa.directive("zaaListArray", function () {
        return {
            restrict: "E",
            scope: { model: "=", options: "=", label: "@label", i18n: "@i18n", id: "@fieldid" },
            controller: [
                "$scope",
                "$element",
                "$timeout",
                function (n, t, e) {
                    (n.init = function () {
                        (null != n.model && null != n.model) || (n.model = []);
                    }),
                        (n.add = function () {
                            (null != n.model && "" != n.model && null != n.model) || (n.model = []), n.model.push({ value: "" }), n.setFocus();
                        }),
                        (n.remove = function (e) {
                            n.model.splice(e, 1);
                        }),
                        (n.refactor = function (e, t) {
                            e !== n.model.length - 1 && "" == t.value && n.remove(e);
                        }),
                        (n.setFocus = function () {
                            e(function () {
                                var e = t.children(".list").children(".list__item:last-of-type").children(".list__left").children("input");
                                1 == e.length && e[0].focus();
                            }, 50);
                        }),
                        (n.moveUp = function (e) {
                            e = parseInt(e);
                            var t = n.model[e];
                            (n.model[e] = n.model[e - 1]), (n.model[e - 1] = t);
                        }),
                        (n.moveDown = function (e) {
                            e = parseInt(e);
                            var t = n.model[e];
                            (n.model[e] = n.model[e + 1]), (n.model[e + 1] = t);
                        }),
                        (n.showDownButton = function (e) {
                            return parseInt(e) < Object.keys(n.model).length - 1;
                        }),
                        n.init();
                },
            ],
            template: function () {
                return (
                    '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label>{{label}}</label></div><div class="form-side"><div class="list zaa-file-array-upload"><p class="alert alert-info" ng-hide="model.length > 0">' +
                    i18n.js_dir_no_selection +
                    '</p><div ng-repeat="(key,row) in model track by key" class="list-item"><input class="form-control list-input" type="text" ng-model="row.value" /><div class="list-buttons"><div class="btn-group" role="group"><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveUp(key)" ng-if="key > 0"><i class="material-icons">keyboard_arrow_up</i></button><button type="button" class="btn btn-sm btn-outline-info" ng-click="moveDown(key)" ng-if="showDownButton(key)"><i class="material-icons">keyboard_arrow_down</i></button><button type="button" class="btn btn-sm btn-outline-danger" ng-click="remove(key)"><i class="material-icons">remove</i></button></div></div></div><button ng-click="add()" type="button" class="btn btn-sm btn-success list-add-button"><i class="material-icons">add</i></button></div></div></div>'
                );
            },
        };
    }),
    zaa.directive("storageFileDisplay", function () {
        return {
            restrict: "E",
            scope: { fileId: "@fileId" },
            controller: [
                "$scope",
                "$filter",
                "ServiceFilesData",
                function (n, e, i) {
                    (n.fileId = 0),
                        (n.fileinfo = null),
                        n.$watch("fileId", function (e, t) {
                            0 != e &&
                            null != e &&
                            null != e &&
                            i.getFile(e).then(
                                function (e) {
                                    n.fileinfo = e;
                                },
                                function () {
                                    n.fileinfo = null;
                                }
                            );
                        });
                },
            ],
            template: function () {
                return '<a ng-show="fileinfo" href="{{ fileinfo.source }}" target="_blank">{{ fileinfo.name_original }}</a>';
            },
        };
    }),
    zaa.directive("storageImageCrudList", function () {
        return {
            restrict: "E",
            scope: { imageId: "@imageId" },
            controller: [
                "$scope",
                "ServiceImagesData",
                function (n, t) {
                    (n.imageSrc = null),
                        n.$watch("imageId", function (e, t) {
                            e != t && (n.imageSrc = null), e && n.evaluateImages();
                        }),
                        n.$on("requestImageSourceReady", function () {
                            n.evaluateImages();
                        }),
                        (n.evaluateImages = function () {
                            0 == n.imageId ||
                            n.imageSrc ||
                            t.getImage(n.imageId).then(function (e) {
                                e.tinyCropImage
                                    ? (n.imageSrc = e.tinyCropImage.source)
                                    : t.getImage(n.imageId, !0).then(function (e) {
                                        e.tinyCropImage && (n.imageSrc = e.tinyCropImage.source);
                                    });
                            });
                        });
                },
            ],
            template: function () {
                return '<img ng-show="imageSrc" ng-src="{{imageSrc}}" alt="{{imageSrc}}" class="img-fluid rounded border" />';
            },
        };
    }),
    zaa.directive("storageImageThumbnailDisplay", function () {
        return {
            restrict: "E",
            scope: { imageId: "@imageId" },
            controller: [
                "$scope",
                "$filter",
                "ServiceImagesData",
                "ServiceFilesData",
                function (n, e, i, t) {
                    n.$watch("imageId", function (e, t) {
                        e != t && (n.imageSrc = null);
                    }),
                        n.$watch(
                            function () {
                                return n.imageId;
                            },
                            function (e, t) {
                                (null == e && null == e) ||
                                i.getImage(e).then(
                                    function (e) {
                                        n.imageSrc = e.tinyCropImage.source;
                                    },
                                    function () {
                                        n.imageSrc = null;
                                    }
                                );
                            }
                        ),
                        (n.imageSrc = null);
                },
            ],
            template: function () {
                return '<div ng-show="imageSrc"><img ng-src="{{imageSrc}}" alt="{{imageSrc}}" class="img-fluid" /></div>';
            },
        };
    }),
    zaa.directive("storageFileUpload", function () {
        return {
            restrict: "E",
            scope: { ngModel: "=", onlyImagesC: "@onlyImagesC" },
            controller: [
                "$scope",
                "$filter",
                "ServiceFilesData",
                function (t, e, n) {
                    (t.modal = { state: 1 }),
                        (t.modalContainer = !1),
                        (t.fileinfo = null),
                        (t.select = function (e) {
                            t.toggleModal(), (t.ngModel = e);
                        }),
                        (t.reset = function () {
                            (t.ngModel = 0), (t.fileinfo = null);
                        }),
                        (t.toggleModal = function () {
                            (t.modalContainer = !t.modalContainer), (t.modal.state = !t.modal.state);
                        }),
                        t.$watch(
                            function () {
                                return t.ngModel;
                            },
                            function (e) {
                                if (null == e || null == e || !angular.isNumber(e)) return null;
                                n.getFile(e).then(
                                    function (e) {
                                        t.fileinfo = e;
                                    },
                                    function () {
                                        t.fileinfo = null;
                                    }
                                );
                            }
                        );
                },
            ],
            templateUrl: "storageFileUpload",
        };
    }),
    zaa.directive("storageImageUpload", function () {
        return {
            restrict: "E",
            scope: { ngModel: "=", options: "=" },
            controller: [
                "$scope",
                "$http",
                "$filter",
                "ServiceFiltersData",
                "ServiceImagesData",
                "AdminToastService",
                "ServiceFilesData",
                function (n, i, a, e, o, l, t) {
                    (n.filtersData = e.data),
                        n.$on("service:FiltersData", function (e, t) {
                            n.filtersData = t;
                        }),
                        (n.noFilters = function () {
                            if (n.options) return n.options.no_filter;
                        }),
                        (n.thumbnailfilter = null),
                        (n.imageLoading = !1),
                        (n.fileId = 0),
                        (n.filterId = 0),
                        (n.imageinfo = null),
                        (n.imageNotFoundError = !1),
                        (n.thumb = !1),
                        (n.filterApply = function () {
                            (n.imageLoading = !0),
                                t.getFile(n.fileId).then(
                                    function (e) {
                                        var t = a("filter")(e.images, { filter_id: n.filterId });
                                        0 == t.length
                                            ? i.post("admin/api-admin-storage/image-filter", { fileId: n.fileId, filterId: n.filterId }).then(
                                                function (e) {
                                                    (n.ngModel = e.data.id), l.success(i18n.js_dir_image_upload_ok), (n.imageLoading = !1);
                                                },
                                                function (e) {
                                                    l.error(i18n.js_dir_image_filter_error), (n.imageLoading = !1);
                                                }
                                            )
                                            : ((n.ngModel = t[0].id), (n.imageLoading = !1));
                                    },
                                    function () {
                                        (n.imageinfo = null), (n.thumb = !1), (n.ngModel = 0);
                                    }
                                );
                        }),
                        (n.changeFilter = function () {
                            n.filterApply();
                        }),
                        n.$watch(
                            function () {
                                return n.fileId;
                            },
                            function (e, t) {
                                null != e && null != e && n.filterApply();
                            }
                        ),
                        n.$watch(
                            function () {
                                return n.ngModel;
                            },
                            function (e, t) {
                                null != e &&
                                null != e &&
                                0 != e &&
                                o.getImage(e).then(
                                    function (e) {
                                        n.applyImageDetails(e), (n.fileId = e.file_id), (n.filterId = e.filter_id);
                                    },
                                    function () {
                                        (n.fileId = 0), (n.filterId = 0), (n.imageinfo = null), (n.thumb = !1);
                                    }
                                );
                            }
                        ),
                        (n.applyImageDetails = function (e) {
                            (n.imageinfo = e), (n.thumb = e);
                        });
                },
            ],
            templateUrl: "storageImageUpload",
        };
    }),
    zaa.directive("storageFileManager", function () {
        return {
            restrict: "E",
            transclude: !1,
            scope: { allowSelection: "@selection", onlyImages: "@onlyImages", onlyImagesC: "@onlyImagesC" },
            controller: [
                "$scope",
                "$http",
                "$filter",
                "$timeout",
                "$rootScope",
                "$q",
                "HtmlStorage",
                "cfpLoadingBar",
                "Upload",
                "ServiceFoldersData",
                "ServiceFilesData",
                "LuyaLoading",
                "AdminToastService",
                "ServiceFoldersDirecotryId",
                "ServiceAdminTags",
                function (a, o, t, n, e, l, r, i, s, c, d, u, p, f, m) {
                    (a.foldersData = c.data),
                        a.$on("service:FoldersData", function (e, t) {
                            a.foldersData = t;
                        }),
                        (a.foldersDataReload = function () {
                            return c.load(!0);
                        }),
                        (a.tags = []),
                        m.load().then(function (e) {
                            a.tags = e;
                        }),
                        (a.filesData = []),
                        (a.totalFiles = 0),
                        (a.pageCount = 0),
                        (a.currentPageId = parseInt(r.getValue("filemanager.pageId", 1))),
                        a.$watch(
                            "currentPageId",
                            function (e, t) {
                                void 0 !== e && e != t && a.getFilesForCurrentPage();
                            },
                            !0
                        ),
                        a.$watch(
                            "currentFolderId",
                            function (e, t) {
                                void 0 !== e && (a.generateFolderInheritance(e), a.getFilesForPageAndFolder(e, 1));
                            },
                            !0
                        ),
                        (a.folderInheritance = []),
                        (a.generateFolderInheritance = function (e) {
                            (a.folderInheritance = []), a.findFolderInheritance(e);
                        }),
                        (a.findFolderInheritance = function (e) {
                            if (a.foldersData && a.foldersData.hasOwnProperty(e)) {
                                var t = a.foldersData[e];
                                a.folderInheritance.push(t), t && t.parentId && a.findFolderInheritance(t.parentId);
                            }
                        }),
                        (a.hasFolderActiveChild = function (t) {
                            var n = !1;
                            return (
                                angular.forEach(a.folderInheritance, function (e) {
                                    e.id == t && (n = !0);
                                }),
                                    n
                            );
                        }),
                        (a.getFilesForPageAndFolder = function (n, i) {
                            return l(function (t, e) {
                                o.get(a.createUrl(n, i, a.sortField, a.searchQuery)).then(function (e) {
                                    return r.setValue("filemanager.sortField", a.sortField), r.setValue("filemanager.pageId", parseInt(i)), a.filesResponseToVars(e), t(!0);
                                });
                            });
                        }),
                        (a.createUrl = function (e, t, n, i) {
                            if ("true" != a.onlyImagesC) {
                                return "admin/api-admin-storage/data-files?folderId=" + e + "&page=" + t + "&expand=createThumbnail,createThumbnailMedium,isImage,sizeReadable&sort=" + n + "&search=" + i;   
                            }
                            else {
                                return "admin/api-admin-storage/data-files?folderId=" + e + "&page=" + t + "&expand=createThumbnail,createThumbnailMedium,isImage,sizeReadable&sort=" + n + "&search=" + i + "&onlyImages=1";
                            }
                        }),
                        (a.filesResponseToVars = function (e) {
                            (a.filesData = e.data), (a.pageCount = e.headers("X-Pagination-Page-Count")), (a.currentPageId = parseInt(e.headers("X-Pagination-Current-Page"))), (a.totalFiles = e.headers("X-Pagination-Total-Count"));
                        }),
                        (a.filesMetaToPagination = function (e) {
                            a.pageCount = e.totalPages;
                        }),
                        (a.getFilesForCurrentPage = function () {
                            return a.getFilesForPageAndFolder(a.currentFolderId, a.currentPageId);
                        }),
                        (a.currentFolderId = f.folderId),
                        (a.foldersDirecotryIdReload = function () {
                            return f.load(!0);
                        }),
                        (a.folderCountMessage = function (e) {
                            return i18nParam("js_filemanager_count_files_overlay", { count: e.filesCount });
                        }),
                        (a.errorMsg = null),
                        (a.replaceFile = function (e, t) {
                            (a.replaceFiled = e) &&
                            (u.start(),
                                s.upload({ url: "admin/api-admin-storage/file-replace", data: { file: e, fileId: a.fileDetail.id, pageId: a.currentPageId } }).then(
                                    function (e) {
                                        u.stop(),
                                        200 == e.status &&
                                        (a.getFilesForCurrentPage().then(function () {
                                            p.success(i18n.js_dir_manager_file_replace_ok);
                                        }),
                                            a.openFileDetail(a.fileDetail, !0));
                                    },
                                    function () {
                                        u.stop();
                                    }
                                ));
                        }),
                        a.$watch("uploadingfiles", function (e) {
                            if (null != e) {
                                (a.uploadResults = 0), u.start(i18n.js_dir_upload_wait);
                                for (var t = 0; t < e.length; t++) (a.errorMsg = null), (n = e[t]), a.uploadUsingUpload(n);
                            }
                            var n;
                        }),
                        a.$watch("uploadResults", function (e, t) {
                            null != a.uploadingfiles &&
                            e == a.uploadingfiles.length &&
                            null == a.errorMsg &&
                            a.getFilesForCurrentPage().then(function () {
                                p.success(i18n.js_dir_manager_upload_image_ok), u.stop();
                            });
                        }),
                        (a.pasteUpload = function (e) {
                            for (var t = 0; t < e.originalEvent.clipboardData.items.length; t++) {
                                var n = e.originalEvent.clipboardData.items[t];
                                "file" == n.kind &&
                                (u.start(i18n.js_dir_upload_wait),
                                    s.upload({ url: "admin/api-admin-storage/files-upload", fields: { folderId: a.currentFolderId }, file: n.getAsFile() }).then(
                                        function (e) {
                                            e.data.upload
                                                ? a.getFilesForCurrentPage().then(function () {
                                                    p.success(i18n.js_dir_manager_upload_image_ok), u.stop();
                                                })
                                                : (p.error(e.data.message), u.stop());
                                        },
                                        function (e) {
                                            alert('errore 2');

                                            p.error(e.data.message), u.stop();
                                        }
                                    ));
                            }
                        }),
                        (a.uploadUsingUpload = function (t) {
                            (t.upload = s.upload({ url: "admin/api-admin-storage/files-upload", fields: { folderId: a.currentFolderId }, file: t })),
                                t.upload.then(
                                    function (e) {
                                        n(function () {
                                            a.erroreUpload = '';
                                            a.uploadResults++, (t.processed = !0), (t.result = e.data), t.result.upload || (p.error(t.result.message), u.stop(), (a.errorMsg = !0));
                                        });
                                    },
                                    function (e) {
                                        a.erroreUpload = '';
                                        if(e.data.message){
                                            a.erroreUpload = e.data.message;
                                            t.message = a.erroreUpload;
                                        }
                                        (t = e.data), p.error(t.message), u.stop(), (a.errorMsg = !0);
                                    }
                                ),
                                t.upload.progress(function (e) {
                                    (t.processed = !1), (t.progress = Math.min(100, parseInt((100 * e.loaded) / e.total)));
                                });
                        }),
                        (a.selectedFiles = []),
                        (a.toggleSelectionAll = function () {
                            a.filesData.forEach(function (e, t) {
                                a.toggleSelection(e);
                            });
                        }),
                        (a.toggleSelection = function (e) {
                            if ("true" != a.allowSelection) {
                                var t = a.selectedFiles.indexOf(e.id);
                                -1 < t ? a.selectedFiles.splice(t, 1) : a.selectedFiles.push(e.id);
                            } else a.$parent.select(e.id);
                        }),
                        (a.inSelection = function (e) {
                            return -1 != a.selectedFiles.indexOf(e.id);
                        }),
                        (a.showFolderForm = !1),
                        (a.createNewFolder = function (e) {
                            e &&
                            o.post("admin/api-admin-storage/folder-create", { folderName: e, parentFolderId: a.currentFolderId }).then(function (e) {
                                var t = e.data;
                                a.foldersDataReload().then(function (e) {
                                    a.folderFormToggler(), (a.newFolderName = null), a.changeCurrentFolderId(t);
                                });
                            });
                        }),
                        (a.folderFormToggler = function () {
                            a.showFolderForm = !a.showFolderForm;
                        }),
                        (a.searchQuery = ""),
                        (a.searchPromise = null),
                        (a.searchLoading = !1),
                        (a.runSearch = function () {
                            0 < a.searchQuery.length
                                ? ((a.searchLoading = !0),
                                    i.start(),
                                    n.cancel(a.searchPromise),
                                    (a.searchPromise = n(function () {
                                        a.getFilesForCurrentPage().then(function () {
                                            a.searchLoading = !1;
                                        });
                                    }, 1e3)))
                                : a.getFilesForCurrentPage().then(function () {
                                    a.searchLoading = !1;
                                });
                        }),
                        (a.sortField = r.getValue("filemanager.sortField", "name_original")),
                        (a.changeSortField = function (e) {
                            (a.sortField = e), a.getFilesForCurrentPage();
                        }),
                        (a.changeCurrentFolderId = function (e, t) {
                            a.searchQuery = "";
                            var n = a.currentFolderId;
                            (a.currentFolderId = e),
                                (a.currentPageId = 1),
                                (a.selectedFiles = []),
                            !0 !== t && n != e && ((f.folderId = e), o.post("admin/api-admin-common/save-filemanager-folder-state", { folderId: e }, { ignoreLoadingBar: !0 }));
                        }),
                        (a.toggleFolderItem = function (e) {
                            null == e.toggle_open ? (e.toggle_open = 1) : (e.toggle_open = !e.toggle_open), o.post("admin/api-admin-common/filemanager-foldertree-history", { data: e }, { ignoreLoadingBar: !0 });
                        }),
                        (a.folderUpdateForm = !1),
                        (a.folderDeleteForm = !1),
                        (a.folderDeleteConfirmForm = !1),
                        (a.updateFolder = function (e) {
                            o.post("admin/api-admin-storage/folder-update?folderId=" + e.id, { name: e.name }).then(function (e) {
                                p.success(i18n.js_dir_manager_rename_success);
                            });
                        }),
                        (a.cancelFolderEdit = function (e, t) {
                            e.name = t;
                        }),
                        (a.deleteFolder = function (i) {
                            o.post("admin/api-admin-storage/is-folder-empty?folderId=" + i.id, { name: i.name }).then(function (e) {
                                var t = e.data.empty,
                                    n = e.data.count;
                                t
                                    ? o.post("admin/api-admin-storage/folder-delete?folderId=" + i.id, { name: i.name }).then(function (e) {
                                        a.foldersDataReload().then(function () {
                                            a.currentFolderId = 0;
                                        });
                                    })
                                    : p.confirm(i18nParam("layout_filemanager_remove_dir_not_empty", { folderName: i.name, count: n }), i18n.js_dir_manager_rm_folder_confirm_title, [
                                        "$timeout",
                                        "$toast",
                                        function (e, t) {
                                            o.post("admin/api-admin-storage/folder-delete?folderId=" + i.id, { name: i.name }).then(function () {
                                                a.foldersDataReload().then(function () {
                                                    (a.currentFolderId = 0), t.close();
                                                });
                                            });
                                        },
                                    ]);
                            });
                        }),
                        (a.removeFiles = function () {
                            p.confirm(i18n.js_dir_manager_rm_file_confirm, i18n.js_dir_manager_rm_file_confirm_title, [
                                "$timeout",
                                "$toast",
                                function (e, t) {
                                    o.post("admin/api-admin-storage/filemanager-remove-files", { ids: a.selectedFiles, pageId: a.currentPageId, folderId: a.currentFolderId }).then(function (e) {
                                        a.getFilesForCurrentPage().then(function () {
                                            t.close(), p.success(i18n.js_dir_manager_rm_file_ok), (a.selectedFiles = []), a.closeFileDetail();
                                        });
                                    });
                                },
                            ]);
                        }),
                        (a.moveFilesTo = function (e) {
                            o.post("admin/api-admin-storage/filemanager-move-files", { fileIds: a.selectedFiles, toFolderId: e, currentPageId: a.currentPageId, currentFolderId: a.currentFolderId }).then(function (e) {
                                a.getFilesForCurrentPage().then(function () {
                                    (a.selectedFiles = []), (a.showFoldersToMove = !1);
                                });
                            });
                        }),
                        (a.getFolderData = function (e) {
                            return t("filemanagerdirsfilter")(a.foldersData, e);
                        }),
                        a.getFilesForCurrentPage(),
                        (a.fileDetail = !1),
                        (a.showFoldersToMove = !1),
                        (a.largeImagePreviewState = !0),
                        (a.fileDetailFull = !1),
                        (a.nameEditMode = !1),
                        (a.fileDetailFolder = !1),
                        (a.openFileDetail = function (e, t) {
                            a.fileDetail.id == e.id && !0 !== t
                                ? a.closeFileDetail()
                                : (d.getFile(e.id, t).then(
                                    function (e) {
                                        (a.fileDetailFull = e), (a.fileDetailFolder = a.foldersData[e.folder_id]);
                                    },
                                    function () {}
                                ),
                                    (a.fileDetail = e));
                        }),
                        (a.saveTagRelation = function (e, t) {
                            o.post("admin/api-admin-storage/toggle-file-tag", { tagId: e.id, fileId: t.id }).then(function (e) {
                                a.fileDetailFull.tags = e.data;
                            });
                        }),
                        (a.fileHasTag = function (t) {
                            var n = !1;
                            return (
                                angular.forEach(a.fileDetailFull.tags, function (e) {
                                    e.id == t.id && (n = !0);
                                }),
                                    n
                            );
                        }),
                        (a.updateFileData = function () {
                            o.put("admin/api-admin-storage/file-update?id=" + a.fileDetailFull.id + "&pageId=" + a.currentPageId, a.fileDetailFull).then(function (e) {
                                (t("findidfilter")(a.filesData, a.fileDetail.id, !0).name = e.data.name_original), (a.nameEditMode = !1);
                            });
                        }),
                        (a.closeFileDetail = function () {
                            (a.fileDetail = !1), (a.fileDetailFull = !1), (a.nameEditMode = !1);
                        }),
                        (a.removeFile = function (e) {
                            (a.selectedFiles = []), a.toggleSelection(e), a.removeFiles();
                        }),
                        (a.isFileEditHidden = !0),
                        (a.editFile = function (e) {
                            a.isFileEditHidden = !a.isFileEditHidden;
                        }),
                        (a.cropSuccess = function () {
                            (a.isFileEditHidden = !0),
                                a.getFilesForCurrentPage().then(function () {
                                    p.success(i18n.crop_success);
                                }),
                                a.openFileDetail(a.fileDetail, !0);
                        }),
                        (a.storeFileCaption = function (e) {
                            o.post("admin/api-admin-storage/filemanager-update-caption", { id: e.id, captionsText: e.captionArray, pageId: a.currentPageId }).then(function (e) {
                                p.success(i18n.file_caption_success);
                            });
                        }),
                        (a.selectedFileFromParent = null),
                        (a.init = function () {
                            a.$parent.fileinfo && ((a.selectedFileFromParent = a.$parent.fileinfo), a.changeCurrentFolderId(a.selectedFileFromParent.folder_id, !0));
                        }),
                        a.init();
                },
            ],
            templateUrl: "storageFileManager",
        };
    }),
    zaa.directive("hasEnoughSpace", [
        "$window",
        "$timeout",
        function (a, o) {
            return {
                restrict: "A",
                scope: { loadingCondition: "=", isFlexBox: "=" },
                link: function (n, i, e) {
                    n.elementWidth = 0;
                    function t() {
                        o(function () {
                            n.elementOriginalWidth ||
                            (n.elementOriginalWidth = (function () {
                                var e = i.clone().insertAfter(i);
                                e.css({ position: "fixed", top: 0, left: 0, visibility: "hidden" }), "none" === e.css("display") && e.css("display", n.isFlexBox ? "flex" : "block");
                                var t = e.outerWidth();
                                return e.remove(), t;
                            })()),
                            i.hasClass("not-enough-space") && (i.removeClass("not-enough-space"), i.addClass("has-enough-space")),
                                i.parent().outerWidth() < n.elementOriginalWidth ? i.removeClass("has-enough-space").addClass("not-enough-space") : i.removeClass("not-enough-space").addClass("has-enough-space");
                        });
                    }
                    angular.element(a).on("resize", function () {
                        t();
                    }),
                        n.$watch("loadingCondition", function (e) {
                            1 == e && t();
                        });
                },
            };
        },
    ]),
    zaa.directive("activeClass", function () {
        return {
            restrict: "A",
            scope: { activeClass: "@" },
            link: function (e, t) {
                t.on("mouseenter", function () {
                    t.addClass(e.activeClass);
                }),
                    t.on("mouseleave", function () {
                        t.removeClass(e.activeClass);
                    }),
                    t.on("click", function () {
                        t.toggleClass(e.activeClass);
                    });
            },
        };
    }),
    zaa.directive("imageEdit", function () {
        return {
            restrict: "E",
            scope: { fileId: "=", onSuccess: "&" },
            controller: [
                "$scope",
                "$http",
                "$timeout",
                function (t, e, n) {
                    t.file,
                        t.cropperImage,
                        (t.cropperConfig = { distUrl: "", areaType: "rectangle", ratio: null, resultImageSize: "max", resultImageFormat: "image/jpeg", resultImageQuality: 1, areaInitSize: 200, canvasScalemode: "full-width" }),
                        (t.changeQuality = function (e) {
                            t.cropperConfig.resultImageQuality = e;
                        }),
                        (t.isCurrentQuality = function (e) {
                            return t.cropperConfig.resultImageQuality == e;
                        }),
                        e.get("/admin/api-admin-storage/file-info?id=" + t.fileId).then(function (e) {
                            (t.file = e.data), (t.cropperConfig.resultImageFormat = t.file.mime_type), (t.cropperImage = t.file.file.href);
                        }),
                        (t.saveAsCopy = !0),
                        (t.isCurrentRatio = function (e) {
                            return t.cropperConfig.ratio == e;
                        }),
                        (t.changeRatio = function (e) {
                            (t.cropperImage = !1),
                                (t.cropperConfig.ratio = e),
                                n(function () {
                                    t.cropperImage = t.file.source;
                                });
                        }),
                        (t.save = function () {
                            e.post("/admin/api-admin-storage/file-crop", { distImage: t.cropperConfig.distUrl, fileName: t.file.name_new_compound, extension: t.file.extension, saveAsCopy: t.saveAsCopy, fileId: t.file.id }).then(function (
                                e
                            ) {
                                t.onSuccess();
                            });
                        });
                },
            ],
            template:
                '\n    <div class="row">\n        <div class="col-md-8">\n            <p class="lead">' +
                i18n.crop_source_image +
                '</p>\n            <div class="bg-light rounded pt-3 pl-3 pr-3 pb-2">\n            <ui-cropper\n                ng-if="cropperImage" \n                image="cropperImage" \n                result-image="cropperConfig.distUrl"\n                result-image-format="{{cropperConfig.resultImageFormat}}"\n                result-image-quality="cropperConfig.resultImageQuality"\n                result-image-size="cropperConfig.resultImageSize"\n                area-type="{{cropperConfig.areaType}}" \n                area-init-size="cropperConfig.areaInitSize"\n                chargement="\'Loading\'"\n                canvas-scalemode="{{cropperConfig.canvasScalemode}}"\n                aspect-ratio="cropperConfig.ratio"\n            ></ui-cropper>\n            </div>\n            <ul class="list-group list-group-horizontal justify-content-center mt-3">\n                <li class="list-group-item text-center" ng-class="{\'active\':isCurrentRatio(null)}" ng-click="changeRatio(null)"><i class="material-icons">crop_free</i><br /><small>' +
                i18n.crop_size_free +
                '</small></li>\n                <li class="list-group-item text-center" ng-class="{\'active\':isCurrentRatio(\'1\')}" ng-click="changeRatio(\'1\')"><i class="material-icons">crop_square</i><br /><small>' +
                i18n.crop_size_1to1 +
                '</small></li>\n                <li class="list-group-item text-center" ng-class="{\'active\':isCurrentRatio(\'1.7\')}" ng-click="changeRatio(\'1.7\')"><i class="material-icons">crop_16_9</i><br /><small>' +
                i18n.crop_size_desktop +
                '</small></li>\n                <li class="list-group-item text-center" ng-class="{\'active\':isCurrentRatio(\'0.5\')}" ng-click="changeRatio(\'0.5\')"><i class="material-icons">crop_portrait</i><br /><small>' +
                i18n.crop_size_mobile +
                '</small></li>\n            </ul>\n        </div>\n        <div class="col-md-4" ng-show="cropperImage">\n            <p class="lead">' +
                i18n.crop_preview +
                '</p>\n            <img ng-src="{{cropperConfig.distUrl}}" ng-show="cropperConfig.distUrl" class="img-fluid border" />\n\n            <ul class="list-group list-group-horizontal justify-content-center mt-3">\n                <li class="list-group-item text-center" ng-class="{\'active\':isCurrentQuality(1.0)}" ng-click="changeQuality(1.0)"><i class="material-icons">looks_one</i><br /><small>' +
                i18n.crop_quality_high +
                '</small></li>\n                <li class="list-group-item text-center" ng-class="{\'active\':isCurrentQuality(0.8)}" ng-click="changeQuality(0.8)"><i class="material-icons">looks_two</i><br /><small>' +
                i18n.crop_quality_medium +
                '</small></li>\n                <li class="list-group-item text-center" ng-class="{\'active\':isCurrentQuality(0.5)}" ng-click="changeQuality(0.5)"><i class="material-icons">looks_3</i><br /><small>' +
                i18n.crop_quality_low +
                '</small></li>\n            </ul>\n\n            <div class="form-check mt-3 rounded border p-2" ng-click="saveAsCopy=!saveAsCopy" ng-class="{\'bg-light\':saveAsCopy}">\n                <input class="form-check-input" type="checkbox" ng-model="saveAsCopy">\n                <label class="form-check-label">\n                ' +
                i18n.crop_btn_as_copy +
                '\n                </label>\n                <small class="text-muted">' +
                i18n.crop_btn_as_copy_hint +
                '</small>\n            </div>\n\n            <button type="button" ng-show="saveAsCopy" class="mt-3 btn btn-lg btn-icon btn-save" ng-click="save()">' +
                i18n.crop_btn_save_copy +
                '</button>\n            <button type="button" ng-show="!saveAsCopy" class="mt-3 btn btn-lg btn-icon btn-save" ng-click="save()">' +
                i18n.crop_btn_save_replace +
                "</button>\n        </div>\n    </div>\n        ",
        };
    }),
    zaa.directive("pagination", function () {
        return {
            restrict: "E",
            scope: { currentPage: "=", pageCount: "=" },
            controller: [
                "$scope",
                "$timeout",
                function (t, e) {
                    (t.pageNumberInputVal = t.currentPage),
                        t.$watch("currentPage", function (e) {
                            t.pageNumberInputVal = e;
                        }),
                        t.$watch("pageNumberInputVal", function () {
                            t.inputWidth = 25 + 10 * (t.pageNumberInputVal.toString().length <= 0 ? 1 : t.pageNumberInputVal.toString().length);
                        });
                    var n = null;
                    (t.pageNumberInputChange = function () {
                        n && e.cancel(n),
                            (n = e(function () {
                                isNaN(t.pageNumberInputVal)
                                    ? (t.pageNumberInputVal = t.currentPage)
                                    : parseInt(t.pageNumberInputVal) > parseInt(t.pageCount) || parseInt(t.pageNumberInputVal) <= 0
                                        ? (t.pageNumberInputVal = t.currentPage)
                                        : (t.currentPage = t.pageNumberInputVal);
                            }, 500));
                    }),
                        (t.next = function () {
                            t.currentPage < t.pageCount && (t.currentPage += 1);
                        }),
                        (t.prev = function () {
                            1 < t.currentPage && (t.currentPage -= 1);
                        }),
                        (t.first = function () {
                            t.currentPage = 1;
                        }),
                        (t.last = function () {
                            t.currentPage = t.pageCount;
                        });
                },
            ],
            template:
                '\n            <div class="pagination" ng-show="pageCount > 1">\n                <button class="pagination-btn pagination-btn-first btn btn-icon btn-first-page" ng-click="first()" ng-disabled="currentPage == 1"></button>\n                <button class="pagination-btn pagination-btn-prev btn btn-icon btn-prev" ng-click="prev()" ng-disabled="currentPage == 1"></button>\n                <div class="pagination-page">\n                    <input ng-style="{\'max-width\': inputWidth + \'px\'}" class="form-control pagination-input" type="text" ng-model="pageNumberInputVal" ng-change="pageNumberInputChange()" />\n                    <span class="pagination-delimiter">/</span>\n                    <span class="pagination-number-of-pages">{{pageCount}}</span>\n                </div>\n                <button class="pagination-btn pagination-btn-next btn btn-icon btn-next" ng-click="next()" ng-disabled="currentPage == pageCount"></button>\n                <button class="pagination-btn pagination-btn-last btn btn-icon btn-last-page" ng-click="last()" ng-disabled="currentPage == pageCount"></button>\n            </div>\n        ',
        };
    }),
    zaa.controller("DefaultDashboardObjectController", [
        "$scope",
        "$http",
        "$sce",
        function (t, n, e) {
            t.data,
                (t.loadData = function (e) {
                    n.get(e).then(function (e) {
                        t.data = e.data;
                    });
                });
        },
    ]),
    zaa.controller("CrudController", [
        "cfpLoadingBar",
        "$scope",
        "$rootScope",
        "$filter",
        "$http",
        "$sce",
        "$state",
        "$timeout",
        "$injector",
        "$q",
        "AdminLangService",
        "AdminToastService",
        "CrudTabService",
        "ServiceImagesData",
        function (t, r, e, n, s, i, a, o, l, c, d, u, p, f) {
            (r.toast = u),
                (r.AdminLangService = d),
                (r.tabService = p),
                (r.clearData = function () {
                    u.confirm(i18n.ngrest_delete_all_button_confirm_message, i18n.ngrest_delete_all_button_label, function () {
                        var e = this;
                        s.get(r.config.apiEndpoint + "/truncate").then(function () {
                            e.close(), r.loadList();
                        });
                    });
                }),
                (r.crudSwitchType = 0),
                (r.switchToTab = function (e) {
                    angular.forEach(r.tabService.tabs, function (e) {
                        e.active = !1;
                    }),
                        (e.active = !0),
                        r.switchTo(4);
                }),
                (r.addAndswitchToTab = function (e, t, n, i, a) {
                    r.tabService.addTab(e, t, n, i, a), r.switchTo(4);
                }),
                (r.closeTab = function (e, t) {
                    r.tabService.remove(t, r);
                }),
                (r.switchTo = function (e, t) {
                    r.config.relationCall
                        ? (r.crudSwitchType = e)
                        : (t && r.resetData(),
                        0 == e && s.get(r.config.apiEndpoint + "/unlock", { ignoreLoadingBar: !0 }),
                        (0 != e && 1 != e) || r.config.inline || a.go("default.route"),
                        4 === (r.crudSwitchType = e) ||
                        r.config.inline ||
                        angular.forEach(r.tabService.tabs, function (e) {
                            e.active = !1;
                        }));
                }),
                (r.closeUpdate = function () {
                    r.switchTo(0, !0);
                }),
                (r.closeCreate = function () {
                    r.switchTo(0, !0);
                }),
                (r.activeWindowModal = !0),
                (r.openActiveWindow = function () {
                    r.activeWindowModal = !1;
                }),
                (r.closeActiveWindow = function () {
                    r.activeWindowModal = !0;
                }),
                (r.changeGroupByField = function () {
                    0 == r.config.groupByField ? (r.config.groupBy = 0) : (r.config.groupBy = 1);
                }),
                (r.isSettingsVisible = !1),
                (r.toggleSettingsMenu = function () {
                    r.isSettingsVisible = !r.isSettingsVisible;
                }),
                (r.hiddeSettingsMenu = function () {
                    r.isSettingsVisible = !1;
                }),
                (r.isExportModalHidden = !0),
                (r.exportdata = { header: 1, type: "xlsx" }),
                (r.toggleExportModal = function () {
                    (r.exportdata.filter = r.config.filter), (r.isExportModalHidden = !r.isExportModalHidden);
                }),
                (r.exportResponse = !1),
                (r.generateExport = function () {
                    s.post(r.config.apiEndpoint + "/export?" + r.config.apiExportQueryString, r.exportdata).then(function (e) {
                        r.exportResponse = e.data;
                    });
                }),
                (r.downloadExport = function () {
                    var e = r.exportResponse.url;
                    return (r.exportResponse = !1), window.open(e), !1;
                }),
                (r.applySaveCallback = function () {
                    r.config.saveCallback && l.invoke(r.config.saveCallback, this);
                }),
                (r.isOrderBy = function (e) {
                    return e == r.config.orderBy;
                }),
                (r.changeOrder = function (e, t) {
                    (r.config.orderBy = t + e), s.post("admin/api-admin-common/ngrest-order", { apiEndpoint: r.config.apiEndpoint, sort: t, field: e }, { ignoreLoadingBar: !0 }), r.loadList();
                }),
                (r.callActiveButton = function (e, t, n) {
                    var i = angular.element(n.currentTarget);
                    i.addClass("crud-buttons-button-loading"),
                        s.get(r.config.apiEndpoint + "/active-button?hash=" + e + "&id=" + t.join()).then(
                            function (e) {
                                i.removeClass("crud-buttons-button-loading"),
                                    i.addClass("crud-buttons-button-success"),
                                    o(function () {
                                        i.removeClass("crud-buttons-button-success");
                                    }, 5e3),
                                    angular.forEach(e.data.events, function (e) {
                                        "loadList" == e && r.loadList();
                                    }),
                                    u.success(e.data.message);
                            },
                            function (e) {
                                i.removeClass("crud-buttons-button-loading"),
                                    i.addClass("crud-buttons-button-danger"),
                                    o(function () {
                                        i.removeClass("crud-buttons-button-danger");
                                    }, 5e3),
                                    u.error(e.data.message);
                            }
                        );
                }),
                (r.reloadActiveWindow = function () {
                    r.getActiveWindow(r.data.aw.hash, r.data.aw.itemId);
                }),
                (r.getActiveWindow = function (t, n, e) {
                    s.post(r.config.activeWindowRenderUrl, { itemId: n, activeWindowHash: t, ngrestConfigHash: r.config.ngrestConfigHash }).then(function (e) {
                        r.openActiveWindow(),
                            (r.data.aw.itemId = n),
                            (r.data.aw.configCallbackUrl = r.config.activeWindowCallbackUrl),
                            (r.data.aw.configHash = r.config.ngrestConfigHash),
                            (r.data.aw.hash = t),
                            (r.data.aw.content = i.trustAsHtml(e.data.content)),
                            (r.data.aw.title = e.data.title),
                            r.$broadcast("awloaded", { id: t });
                    });
                }),
                (r.getActiveWindowCallbackUrl = function (e) {
                    return r.data.aw.configCallbackUrl + "?activeWindowCallback=" + e + "&ngrestConfigHash=" + r.data.aw.configHash + "&activeWindowHash=" + r.data.aw.hash;
                }),
                (r.sendActiveWindowCallback = function (e, t) {
                    t = t || {};
                    return s.post(r.getActiveWindowCallbackUrl(e), $.param(t), { headers: { "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8" } });
                }),
                (r.searchPromise = null),
                r.$watch("config.searchQuery", function (e, t) {
                    e != t && null != e && null != e && r.applySearchQuery(e);
                }),
                (r.applySearchQuery = function (e) {
                    null != e &&
                    null != e &&
                    (o.cancel(r.searchPromise),
                        0 == e.length
                            ? r.loadList(1)
                            : (t.start(),
                                (r.searchPromise = o(function () {
                                    r.reloadCrudList(1);
                                }, 700))));
                }),
                (r.generateSearchPromise = function (e, t) {
                    return s.post(r.generateUrlWithParams("search", t), { query: e }).then(function (e) {
                        r.parseResponseQueryToListArray(e);
                    });
                }),
                (r.parentSelectInline = function (e) {
                    r.$parent.$parent.$parent.setModelValue(r.getRowPrimaryValue(e), e);
                }),
                (r.checkIfFieldExistsInPopulateCondition = function (e) {
                    var t = r.config.pools;
                    if (t.hasOwnProperty(e)) return t[e];
                    if (r.config.relationCall) {
                        var n = r.$parent.$parent.config.relations[parseInt(r.config.relationCall.arrayIndex)].relationLink;
                        if (null !== n && n.hasOwnProperty(e)) return parseInt(r.config.relationCall.id);
                    }
                    return !1;
                }),
                (r.relationItems = []),
                (r.deleteItem = function (e, t) {
                    u.confirm(i18n.js_ngrest_rm_page, i18n.ngrest_button_delete, [
                        "$toast",
                        function (t) {
                            s.delete(r.config.apiEndpoint + "/" + e).then(
                                function (e) {
                                    r.loadList(), t.close(), u.success(i18n.js_ngrest_rm_confirm);
                                },
                                function (e) {
                                    r.printErrors(e);
                                }
                            );
                        },
                    ]);
                }),
                (r.toggleUpdate = function (n) {
                    r.resetData(),
                        s.get(r.config.apiEndpoint + "/" + n + "?" + r.config.apiUpdateQueryString).then(
                            function (e) {
                                var t = e.data;
                                (r.data.update = t), r.config.relationCall ? (r.crudSwitchType = 2) : r.switchTo(2), r.config.inline || a.go("default.route.detail", { id: n }), (r.data.updateId = n);
                            },
                            function (e) {
                                u.error(i18n.js_ngrest_error);
                            }
                        );
                }),
                (r.highlightPkValue = null),
                (r.highlightTimeout = 5e3),
                (r.isRowHighlighted = function (e) {
                    return r.getRowPrimaryValue(e) == r.highlightPkValue;
                }),
                (r.submitUpdate = function () {
                    s.put(r.config.apiEndpoint + "/" + r.data.updateId, angular.toJson(r.data.update, !0)).then(
                        function (e) {
                            u.success(i18n.js_ngrest_rm_update),
                                r.loadList(r.pager.currentPage).then(function () {
                                    r.applySaveCallback(),
                                        r.switchTo(0, !0),
                                        (r.highlightPkValue = r.getRowPrimaryValue(e.data)),
                                        o(function () {
                                            r.highlightPkValue = null;
                                        }, r.highlightTimeout);
                                });
                        },
                        function (e) {
                            r.printErrors(e.data);
                        }
                    );
                }),
                (r.submitCreate = function () {
                    s.post(r.config.apiEndpoint, angular.toJson(r.data.create, !0)).then(
                        function (e) {
                            u.success(i18n.js_ngrest_rm_success),
                                r.loadList().then(function () {
                                    r.applySaveCallback(),
                                        r.switchTo(0, !0),
                                        r.resetData(),
                                        (r.highlightPkValue = r.getRowPrimaryValue(e.data)),
                                        o(function () {
                                            r.highlightPkValue = null;
                                        }, r.highlightTimeout);
                                });
                        },
                        function (e) {
                            r.printErrors(e.data);
                        }
                    );
                }),
                (r.printErrors = function (e) {
                    angular.forEach(e, function (e, t) {
                        u.error(e.message);
                    });
                }),
                (r.resetData = function () {
                    (r.data.create = angular.copy({})), (r.data.update = angular.copy({}));
                }),
                (r.changeNgRestFilter = function () {
                    s.post("admin/api-admin-common/ngrest-filter", { apiEndpoint: r.config.apiEndpoint, filterName: r.config.filter }, { ignoreLoadingBar: !0 }), r.loadList(1);
                }),
                (r.pager = { currentPage: 1, pageCount: 1, perPage: 0, totalItems: 0 }),
                r.$watch(
                    "pager.currentPage",
                    function (e, t) {
                        e === t || null == e || null == e || r.loadList(e);
                    },
                    !0
                ),
                (r.setPagination = function (e, t, n, i) {
                    (r.totalRows = i), (r.pager = { currentPage: parseInt(e), pageCount: t, perPage: n, totalItems: i });
                }),
                (r.toggleStatus = function (t, n, i, e) {
                    var a = !e ? 1 : 0,
                        o = t[r.config.pk],
                        l = {};
                    (l[n] = a),
                        s.put(r.config.apiEndpoint + "/" + o + "?ngrestCallType=update&fields=" + n, angular.toJson(l, !0)).then(
                            function (e) {
                                (t[n] = a), u.success(i18nParam("js_ngrest_toggler_success", { field: i }));
                            },
                            function (e) {
                                r.printErrors(e);
                            }
                        );
                }),
                (r.sortableUp = function (e, t, n) {
                    var i = r.data.listArray[e - 1];
                    (r.data.listArray[e - 1] = t), (r.data.listArray[e] = i), r.updateSortableIndexPositions(n);
                }),
                (r.sortableDown = function (e, t, n) {
                    var i = r.data.listArray[e + 1];
                    (r.data.listArray[e + 1] = t), (r.data.listArray[e] = i), r.updateSortableIndexPositions(n);
                }),
                (r.updateSortableIndexPositions = function (a) {
                    angular.forEach(r.data.listArray, function (e, t) {
                        var n = {};
                        n[a] = t;
                        var i = r.getRowPrimaryValue(e);
                        s.put(r.config.apiEndpoint + "/" + i + "?ngrestCallType=update&fields=" + a, angular.toJson(n, !0), { ignoreLoadingBar: !0 });
                    });
                }),
                (r.loadService = function () {
                    r.initServiceAndConfig();
                }),
                (r.evalSettings = function (e) {
                    e.hasOwnProperty("order") && (r.config.orderBy = e.order), e.hasOwnProperty("filterName") && (r.config.filter = e.filterName);
                }),
                (r.getRowPrimaryValue = function (t) {
                    var e = r.config.pk;
                    if (angular.isArray(e)) {
                        var n = [];
                        return (
                            angular.forEach(e, function (e) {
                                n.push(t[e]);
                            }),
                                n.join()
                        );
                    }
                    return t[r.config.pk];
                }),
                (r.tagsFilterIds = []),
                (r.isTagFilterActive = function (e) {
                    return -1 != r.tagsFilterIds.indexOf(e);
                }),
                (r.toggleTagFilter = function (e) {
                    var t = r.tagsFilterIds.indexOf(e);
                    -1 == t ? r.tagsFilterIds.push(e) : r.tagsFilterIds.splice(t, 1), r.loadList();
                }),
                (r.initServiceAndConfig = function () {
                    var t = c.defer();
                    return (
                        s.get(r.config.apiEndpoint + "/services?" + r.config.apiServicesQueryString).then(function (e) {
                            (r.service = e.data.service),
                                (r.serviceResponse = e.data),
                                r.evalSettings(e.data._settings),
                            r.$parent.notifications && r.$parent.notifications.hasOwnProperty(r.serviceResponse._authId) && delete r.$parent.notifications[r.serviceResponse._authId],
                                t.resolve();
                        }),
                            t.promise
                    );
                }),
                (r.toggleNotificationMute = function () {
                    s.post(r.config.apiEndpoint + "/toggle-notification", { mute: !r.serviceResponse._notifcation_mute_state }).then(function (e) {
                        r.initServiceAndConfig();
                    });
                }),
                (r.getFieldHelp = function (e) {
                    return !!(r.serviceResponse && r.serviceResponse._hints && r.serviceResponse._hints.hasOwnProperty(e)) && r.serviceResponse._hints[e];
                }),
                (r.loadList = function (e) {
                    return null == e && r.pager ? r.reloadCrudList(r.pager.currentPage) : r.reloadCrudList(e);
                }),
                (r.totalRows = 0),
                (r.requestedImages = []),
                (r.parseResponseQueryToListArray = function (n) {
                    r.setPagination(n.headers("X-Pagination-Current-Page"), n.headers("X-Pagination-Page-Count"), n.headers("X-Pagination-Per-Page"), n.headers("X-Pagination-Total-Count")),
                        (r.data.listArray = n.data),
                        (r.requestedImages = []),
                        angular.forEach(r.service, function (e, t) {
                            e.hasOwnProperty("lazyload_images") &&
                            angular.forEach(n.data, function (e) {
                                r.requestedImages.push(e[t]);
                            });
                        }),
                        o(function () {
                            f.loadImages(r.requestedImages).then(function () {
                                r.$broadcast("requestImageSourceReady"), (r.requestedImages = []);
                            });
                        });
                }),
                (r.generateUrlWithParams = function (e, t) {
                    var n = r.config.apiEndpoint + "/" + e + "?" + r.config.apiListQueryString;
                    r.config.orderBy && (n = n + "&sort=" + r.config.orderBy.replace("+", "")), void 0 !== t && (n = n + "&page=" + t);
                    var i = r.config.searchQuery;
                    i && (n = n + "&query=" + i);
                    var a = r.tagsFilterIds.join(",");
                    return a && (n = n + "&tags=" + a), n;
                }),
                (r.reloadCrudList = function (e) {
                    var t = c.defer();
                    if (0 == parseInt(r.config.filter) || null === r.config.filter) {
                        if (r.config.relationCall) {
                            n = (n = r.generateUrlWithParams("relation-call", e)) + "&arrayIndex=" + r.config.relationCall.arrayIndex + "&id=" + r.config.relationCall.id + "&modelClass=" + r.config.relationCall.modelClass;
                        } else {
                            if (r.config.searchQuery) return r.generateSearchPromise(r.config.searchQuery, e);
                            var n = r.generateUrlWithParams("list", e);
                        }
                        s.get(n).then(function (e) {
                            t.resolve(e), r.parseResponseQueryToListArray(e);
                        });
                    } else {
                        (n = (n = r.generateUrlWithParams("filter", e)) + "&filterName=" + r.config.filter),
                            s.get(n).then(function (e) {
                                r.parseResponseQueryToListArray(e), t.resolve(e);
                            });
                    }
                    return t.promise;
                }),
                (r.service = !1),
                r.$on("secondMenuClick", function () {
                    r.isInitalized && (r.loadList(), r.switchTo(0, !0));
                }),
                (r.data = { create: {}, update: {}, aw: {}, list: {}, updateId: 0 }),
                (r.isInitalized = !1),
                r.$watch("config", function (e, t) {
                    o(function () {
                        r.initServiceAndConfig().then(function () {
                            (r.isInitalized = !0), r.loadList();
                        });
                    });
                });
        },
    ]),
    zaa.controller("ActiveWindowTagController", [
        "$scope",
        "$http",
        "AdminToastService",
        function (n, e, i) {
            (n.crud = n.$parent),
                (n.tags = []),
                (n.relation = {}),
                (n.newTagName = null),
                (n.loadTags = function () {
                    e.get(n.crud.getActiveWindowCallbackUrl("LoadTags")).then(function (e) {
                        n.tags = e.data;
                    });
                }),
                (n.loadRelations = function () {
                    e.get(n.crud.getActiveWindowCallbackUrl("LoadRelations")).then(function (e) {
                        (n.relation = {}),
                            e.data.forEach(function (e, t) {
                                n.relation[e.tag_id] = 1;
                            });
                    });
                }),
                (n.saveTag = function () {
                    var t = n.newTagName;
                    "" !== t &&
                    n.crud.sendActiveWindowCallback("SaveTag", { tagName: t }).then(function (e) {
                        e.data ? (n.tags.push({ id: e.data, name: t }), i.success(t + " wurde gespeichert.")) : i.error(t + " " + i18n.js_tag_exists), (n.newTagName = null);
                    });
                }),
                (n.saveRelation = function (t, e) {
                    n.crud.sendActiveWindowCallback("SaveRelation", { tagId: t.id, value: e }).then(function (e) {
                        (n.relation[t.id] = e.data), i.success(i18n.js_tag_success);
                    });
                }),
                n.$watch(
                    function () {
                        return n.data.aw.itemId;
                    },
                    function (e, t) {
                        n.loadRelations();
                    }
                ),
                n.loadTags();
        },
    ]),
    zaa.controller("ActiveWindowGalleryController", [
        "$scope",
        "$http",
        "$filter",
        function (o, e, t) {
            (o.crud = o.$parent),
                (o.files = []),
                (o.select = function (e) {
                    0 == t("filter")(o.files, { fileId: e }, !0).length &&
                    o.crud.sendActiveWindowCallback("AddImageToIndex", { fileId: e }).then(function (e) {
                        var t = e.data;
                        o.files.push(t);
                    });
                }),
                (o.loadImages = function () {
                    e.get(o.crud.getActiveWindowCallbackUrl("loadAllImages")).then(function (e) {
                        o.files = e.data;
                    });
                }),
                (o.changePosition = function (e, t, n) {
                    t = parseInt(t);
                    var i = o.files[t];
                    "up" == n ? ((o.files[t] = o.files[t - 1]), (o.files[t - 1] = i)) : "down" == n && ((o.files[t] = o.files[t + 1]), (o.files[t + 1] = i));
                    var a = o.files[t];
                    o.crud.sendActiveWindowCallback("ChangeSortIndex", { new: a, old: i });
                }),
                (o.moveUp = function (e, t) {
                    o.changePosition(e, t, "up");
                }),
                (o.moveDown = function (e, t) {
                    o.changePosition(e, t, "down");
                }),
                (o.remove = function (e, t) {
                    o.crud.sendActiveWindowCallback("RemoveFromIndex", { imageId: e.originalImageId }).then(function (e) {
                        o.files.splice(t, 1);
                    });
                }),
                o.$watch(
                    function () {
                        return o.data.aw.itemId;
                    },
                    function (e, t) {
                        o.loadImages();
                    }
                );
        },
    ]),
    zaa.controller("ActiveWindowGroupAuth", [
        "$scope",
        "$http",
        "CacheReloadService",
        function (n, e, t) {
            (n.crud = n.$parent),
                (n.reload = function () {
                    t.reload();
                }),
                (n.rights = []),
                (n.auths = []),
                (n.save = function (e) {
                    n.crud.sendActiveWindowCallback("saveRights", { data: e }).then(function (e) {
                        n.getRights(), n.reload();
                    });
                }),
                (n.clearModule = function (e) {
                    angular.forEach(e, function (e) {
                        n.rights[e.id] = { base: 0, create: 0, update: 0, delete: 0 };
                    });
                }),
                (n.toggleModule = function (e) {
                    angular.forEach(e, function (e) {
                        n.rights[e.id] = { base: 1, create: 1, update: 1, delete: 1 };
                    });
                }),
                (n.toggleGroup = function (e) {
                    (objectGroup = n.rights[e]),
                        1 == objectGroup.base ? ((objectGroup.create = 1), (objectGroup.update = 1), (objectGroup.delete = 1)) : 0 == objectGroup.base && ((objectGroup.create = 0), (objectGroup.update = 0), (objectGroup.delete = 0));
                }),
                (n.toggleAll = function () {
                    angular.forEach(n.auths, function (e) {
                        angular.forEach(e, function (e) {
                            n.rights[e.id] = { base: 1, create: 1, update: 1, delete: 1 };
                        });
                    });
                }),
                (n.untoggleAll = function () {
                    angular.forEach(n.auths, function (e) {
                        angular.forEach(e, function (e) {
                            n.rights[e.id] = { base: 0, create: 0, update: 0, delete: 0 };
                        });
                    });
                }),
                (n.getRights = function () {
                    e.get(n.crud.getActiveWindowCallbackUrl("getRights")).then(function (e) {
                        (n.rights = e.data.rights), (n.auths = e.data.auths);
                    });
                }),
                n.$on("awloaded", function (e, t) {
                    n.getRights();
                }),
                n.$watch(
                    function () {
                        return n.data.aw.itemId;
                    },
                    function (e, t) {
                        n.getRights();
                    }
                );
        },
    ]),
    zaa.controller("DefaultController", [
        "$scope",
        "$http",
        "$state",
        "$stateParams",
        "CrudTabService",
        function (a, t, n, i, o) {
            (a.moduleId = n.params.moduleId),
                (a.loadDashboard = function () {
                    return (a.currentItem = null), a.getDashboard(a.moduleId), n.go("default", { moduleId: a.moduleId });
                }),
                (a.isOpenModulenav = !1),
                (a.items = []),
                (a.itemRoutes = []),
                (a.currentItem = null),
                (a.dashboard = []),
                (a.itemAdd = function (e, t) {
                    for (var n in (a.items.push({ name: e, items: t }), t)) {
                        var i = t[n];
                        a.itemRoutes[i.route] = { alias: i.alias, icon: i.icon };
                    }
                }),
                (a.getDashboard = function (e) {
                    t.get("admin/api-admin-menu/dashboard", { params: { nodeId: e } }).then(function (e) {
                        a.dashboard = e.data;
                    });
                }),
                (a.init = function () {
                    a.get(), a.getDashboard(a.moduleId);
                }),
                (a.resolveCurrentItem = function () {
                    if (!a.currentItem && ("default.route" == n.current.name || "default.route.detail" == n.current.name)) {
                        var e = [i.moduleRouteId, i.controllerId, i.actionId].join("/");
                        a.itemRoutes.indexOf(e) && ((a.currentItem = a.itemRoutes[e]), (a.currentItem.route = e));
                    }
                }),
                (a.click = function (e) {
                    a.isOpenModulenav = !1;
                    var t = (a.currentItem = e).route.split("/");
                    o.clear(), a.$broadcast("secondMenuClick", { item: e }), n.go("default.route", { moduleRouteId: t[0], controllerId: t[1], actionId: t[2] });
                }),
                (a.get = function () {
                    t.get("admin/api-admin-menu/items", { params: { nodeId: a.moduleId } }).then(function (e) {
                        var t = e.data;
                        for (var n in t.groups) {
                            var i = t.groups[n];
                            a.itemAdd(i.name, i.items);
                        }
                        a.resolveCurrentItem();
                    });
                }),
                (a.hasSubUnreadNotificaton = function (e) {
                    return a.$parent.notifications && a.$parent.notifications.hasOwnProperty(e.authId) ? a.$parent.notifications[e.authId] : 0;
                }),
                a.$on("topMenuClick", function (e) {
                    a.currentItem = null;
                }),
                a.init();
        },
    ]),
    zaa.controller("DashboardController", [
        "$scope",
        function (e) {
            e.logItemOpen = !1;
        },
    ]),
    zaa.filter("lockFilter", function () {
        return function (e, t, n) {
            var i = !1;
            return (
                angular.forEach(e, function (e) {
                    e.lock_table == t && e.lock_pk == n && (i = e);
                }),
                    i
            );
        };
    }),
    zaa.controller("LayoutMenuController", [
        "$scope",
        "$document",
        "$http",
        "$state",
        "$timeout",
        "$window",
        "$filter",
        "HtmlStorage",
        "CacheReloadService",
        "AdminDebugBar",
        "LuyaLoading",
        "AdminToastService",
        "AdminClassService",
        function (a, e, n, o, i, t, l, r, s, c, d, u, p) {
            (a.AdminClassService = p),
                (a.AdminDebugBar = c),
                (a.LuyaLoading = d),
                (a.toastQueue = u.queue),
                (a.reload = function () {
                    s.reload();
                }),
                (a.reload = function (e) {
                    0 == e ? t.location.reload() : s.reload();
                }),
                (a.reloadButtonCall = function (e) {
                    n.get("admin/api-admin-common/reload-button-call?key=" + e).then(function (e) {
                        u.success(e.data.message);
                    });
                }),
                (a.isHover = r.getValue("sidebarToggleState", !1)),
                (a.toggleMainNavSize = function () {
                    (a.isHover = !a.isHover), r.setValue("sidebarToggleState", a.isHover);
                }),
                (a.profile = {}),
                (a.settings = {}),
                (a.packages = []),
                (a.getProfileAndSettings = function () {
                    n.get("admin/api-admin-user/session").then(function (e) {
                        (a.profile = e.data.user), (a.settings = e.data.settings), (a.packages = e.data.packages);
                    });
                }),
                (a.browser = null),
                (a.detectBrowser = function () {
                    a.browser = [bowser.name.replace(" ", "-").toLowerCase() + "-" + bowser.version, bowser.mac ? "mac-os-" + (bowser.osversion ? bowser.osversion : "") : "windows-" + (bowser.osversion ? bowser.osversion : "")].join(" ");
                }),
                a.detectBrowser(),
                a.getProfileAndSettings(),
                (a.debugDetail = null),
                (a.debugDetailKey = null),
                (a.loadDebugDetail = function (e, t) {
                    (a.debugDetail = e), (a.debugDetailKey = t);
                }),
                (a.closeDebugDetail = function () {
                    (a.debugDetail = null), (a.debugDetailKey = null);
                }),
                (a.notify = null),
                (a.forceReload = 0),
                (a.showOnlineContainer = !1),
                (a.searchDetailClick = function (t, i) {
                    "custom" == t.type
                        ? a.click(t.menuItem).then(function () {
                            if (t.stateProvider) {
                                var n = {};
                                angular.forEach(t.stateProvider.params, function (e, t) {
                                    n[t] = i[e];
                                }),
                                    o.go(t.stateProvider.state, n).then(function () {
                                        a.closeSearchInput();
                                    });
                            } else a.closeSearchInput();
                        })
                        : a.click(t.menuItem.module).then(function () {
                            var e = t.menuItem.route.split("/");
                            o.go("default.route", { moduleRouteId: e[0], controllerId: e[1], actionId: e[2] }).then(function () {
                                if (t.stateProvider) {
                                    var n = {};
                                    angular.forEach(t.stateProvider.params, function (e, t) {
                                        n[t] = i[e];
                                    }),
                                        o.go(t.stateProvider.state, n).then(function () {
                                            a.closeSearchInput();
                                        });
                                } else a.closeSearchInput();
                            });
                        });
                }),
                (a.visibleAdminReloadDialog = !1),
                (a.lastKeyStroke = Date.now()),
                e.bind("keyup", function (e) {
                    a.lastKeyStroke = Date.now();
                }),
                (a.notifications = []),
                (function t() {
                    n.post("admin/api-admin-timestamp", { lastKeyStroke: a.lastKeyStroke }, { ignoreLoadingBar: !0 }).then(function (e) {
                        (a.forceReload = e.data.forceReload),
                            (a.notifications = e.data.notifications),
                        a.forceReload &&
                        !a.visibleAdminReloadDialog &&
                        ((a.visibleAdminReloadDialog = !0),
                            u.confirm(i18n.js_admin_reload, i18n.layout_btn_reload, function () {
                                a.reload(), (a.visibleAdminReloadDialog = !1);
                            })),
                            (a.locked = e.data.locked),
                            (a.notify = e.data.useronline),
                            (a.idleStrokeDashoffset = e.data.idleStrokeDashoffset),
                            (a.idleTimeRelative = e.data.idleTimeRelative),
                            i(t, 2e4);
                    });
                })(),
                (a.isLocked = function (e, t) {
                    return l("lockFilter")(a.locked, e, t);
                }),
                (a.getLockedName = function (e, t) {
                    var n = a.isLocked(e, t);
                    return n.firstname + " " + n.lastname;
                }),
                (a.searchQuery = null),
                (a.searchInputOpen = !1),
                (a.escapeSearchInput = function () {
                    a.searchInputOpen && a.closeSearchInput();
                }),
                (a.toggleSearchInput = function () {
                    a.searchInputOpen = !a.searchInputOpen;
                }),
                (a.openSearchInput = function () {
                    a.searchInputOpen = !0;
                }),
                (a.closeSearchInput = function () {
                    a.searchInputOpen = !1;
                }),
                (a.searchResponse = null),
                (a.hasUnreadNotificaton = function (e) {
                    var t = e.authIds,
                        n = 0;
                    return (
                        angular.forEach(t, function (e) {
                            e && a.notifications.hasOwnProperty(e) && (n += parseInt(a.notifications[e]));
                        }),
                            n
                    );
                }),
                a.$watch(
                    function () {
                        return a.searchQuery;
                    },
                    function (e, t) {
                        e !== t &&
                        (2 < e.length
                            ? n.get("admin/api-admin-search", { params: { query: e } }).then(function (e) {
                                a.searchResponse = e.data;
                            })
                            : (a.searchResponse = null));
                    }
                ),
                (a.items = []),
                (a.currentItem = {}),
                (a.isOpen = !1),
                (a.click = function (e) {
                    return (a.isOpen = !1), a.$broadcast("topMenuClick", { menuItem: e }), e.template ? o.go("custom", { templateId: e.template }) : o.go("default", { moduleId: e.id });
                }),
                (a.isActive = function (e) {
                    if (e.template) {
                        if (o.params.templateId == e.template) return (a.currentItem = e), !0;
                    } else if (o.params.moduleId == e.id) return (a.currentItem = e), !0;
                }),
                (a.get = function () {
                    n.get("admin/api-admin-menu").then(function (e) {
                        a.items = e.data;
                    });
                }),
                a.get();
        },
    ]),
    zaa.controller("AccountController", [
        "$scope",
        "$http",
        "$window",
        "AdminToastService",
        function (t, n, i, a) {
            (t.pass = {}),
                (t.changePassword = function () {
                    n.post("admin/api-admin-user/change-password", t.pass).then(
                        function (e) {
                            a.success(i18n.aws_changepassword_succes), (t.pass = {});
                        },
                        function (e) {
                            a.errorArray(e.data), (t.pass = {});
                        }
                    );
                }),
                (t.changeSettings = function (e) {
                    n.post("admin/api-admin-user/change-settings", e).then(function (e) {
                        i.location.reload();
                    });
                }),
                (t.removeDevice = function (e) {
                    n.post("admin/api-admin-user/remove-device", { deviceId: e.id }).then(function () {
                        a.success(i18n.js_account_update_profile_success), t.getProfile();
                    });
                }),
                (t.profile = {}),
                (t.settings = {}),
                (t.activities = {}),
                (t.email = {}),
                (t.devices = []),
                (t.twoFa = {}),
                (t.twoFaBackupCode = !1),
                (t.getProfile = function () {
                    n.get("admin/api-admin-user/session").then(function (e) {
                        (t.profile = e.data.user), (t.settings = e.data.settings), (t.activities = e.data.activities), (t.devices = e.data.devices), (t.twoFa = e.data.twoFa);
                    });
                }),
                (t.changePersonData = function (e) {
                    n.put("admin/api-admin-user/session-update", e).then(
                        function (e) {
                            a.success(i18n.js_account_update_profile_success), t.getProfile();
                        },
                        function (e) {
                            a.errorArray(e.data);
                        }
                    );
                }),
                (t.changeEmail = function () {
                    n.put("admin/api-admin-user/change-email", { token: t.email.token }).then(
                        function (e) {
                            a.success(i18n.js_account_update_profile_success), t.getProfile();
                        },
                        function (e) {
                            a.errorArray(e.data);
                        }
                    );
                }),
                (t.registerTwoFa = function () {
                    n.post("admin/api-admin-user/register-twofa", { secret: t.twoFa.secret, verification: t.twoFa.verification }).then(
                        function (e) {
                            (t.twoFaBackupCode = e.data.backupCode), a.success(i18n.js_account_update_profile_success), t.getProfile();
                        },
                        function (e) {
                            a.errorArray(e.data);
                        }
                    );
                }),
                (t.disableTwoFa = function () {
                    n.post("admin/api-admin-user/disable-twofa").then(function () {
                        a.success(i18n.js_account_update_profile_success), t.getProfile();
                    });
                }),
                t.getProfile();
        },
    ]),
    zaa.directive("luyaSchedule", function () {
        return {
            restrict: "E",
            relace: !0,
            scope: { value: "=", attributeValues: "=", primaryKeyValue: "=", modelClass: "@", attributeName: "@", onlyIcon: "@", title: "@" },
            controller: [
                "$scope",
                "$http",
                "$timeout",
                "AdminToastService",
                function (i, t, n, a) {
                    (i.getFirstAttributeKeyAsDefaultValue = function () {
                        return i.attributeValues[0].value;
                    }),
                        (i.newvalue = i.getFirstAttributeKeyAsDefaultValue()),
                        (i.isVisible = !1),
                        (i.upcomingAccordionOpen = !0),
                        (i.archiveAccordionOpen = !1),
                        (i.showDatepicker = !1),
                        (i.modalPositionClass = ""),
                        i.$watch("showDatepicker", function (e) {
                            if (0 === e) {
                                var t = new Date();
                                i.timestamp = t.getTime() / 1e3;
                            }
                        }),
                        (i.toggleWindow = function () {
                            (i.isVisible = !i.isVisible), i.isVisible ? i.getLogTable() : i.hideInlineModal();
                        }),
                        (i.escModal = function () {
                            i.isVisible && ((i.isVisible = !1), i.hideInlineModal());
                        }),
                        (i.getUniqueFormId = function (e) {
                            return e + i.primaryKeyValue + "_" + i.attributeName;
                        }),
                        (i.logs = { upcoming: [], archived: [] }),
                        (i.getLogTable = function (e) {
                            t.get("admin/api-admin-common/scheduler-log?model=" + i.modelClass + "&pk=" + i.primaryKeyValue).then(function (e) {
                                (i.logs.archived = []),
                                    (i.logs.upcoming = []),
                                    e.data.forEach(function (e) {
                                        e.is_done ? i.logs.archived.push(e) : i.logs.upcoming.push(e);
                                    }),
                                    angular.forEach(i.logs.archived, function (e, t) {
                                        e.id == i.latestId && (i.value = e.new_attribute_value);
                                    }),
                                    n(function () {
                                        i.showInlineModal();
                                    });
                            });
                        }),
                        (i.valueToLabel = function (t) {
                            var n;
                            return (
                                angular.forEach(i.attributeValues, function (e) {
                                    e.value == t && (n = e.label);
                                }),
                                    n
                            );
                        });
                    var e = new Date().getTime() / 1e3;
                    i.latestId,
                        (i.timestamp = parseInt(e)),
                        (i.saveNewJob = function () {
                            t.post("admin/api-admin-common/scheduler-add", {
                                model_class: i.modelClass,
                                primary_key: i.primaryKeyValue,
                                target_attribute_name: i.attributeName,
                                new_attribute_value: i.newvalue,
                                schedule_timestamp: i.timestamp,
                            }).then(
                                function (e) {
                                    (i.latestId = e.data.id), i.getLogTable();
                                },
                                function (e) {
                                    a.errorArray(e.data);
                                }
                            );
                        }),
                        (i.deleteJob = function (e) {
                            t.delete("admin/api-admin-common/scheduler-delete?id=" + e.id).then(function (e) {
                                i.getLogTable();
                            });
                        });
                },
            ],
            link: function (l, r, e) {
                var s = r.find(".inlinemodal"),
                    o = r.find(".inlinemodal-arrow"),
                    c = r.find(".scheduler-btn");
                (l.alignModal = function () {
                    var e = $(document).width(),
                        t = (r.parents(".luya-content")[0].scrollHeight || $(document).height(), c[0].getBoundingClientRect()),
                        n = e - (t.left + t.width + 30),
                        i = t.left - 30,
                        a = 500 <= n || i <= n,
                        o = i < 300 && n < 300;
                    s.removeClass("inlinemodal--left inlinemodal--right inlinemodal--full"),
                        o
                            ? (s.addClass("inlinemodal--full"), s.css({ display: "block", left: 15, right: 15, top: 15, bottom: 15 }))
                            : a
                                ? (s.addClass("inlinemodal--right"), s.css({ display: "block", left: t.left + t.width + 15, right: 15, width: "auto" }))
                                : (s.addClass("inlinemodal--left"), s.css({ display: "block", left: 1100 < t.left ? "auto" : 15, right: e + 15 - t.left, width: 1100 < t.left ? "100%" : "auto" })),
                        l.alignModalArrow();
                }),
                    (l.alignModalArrow = function () {
                        var e = s[0].getBoundingClientRect(),
                            t = c[0].getBoundingClientRect(),
                            n = o.outerHeight(),
                            i = t.top - 15 - n / 2,
                            a = e.height - 15 - n / 2 - 10;
                        i <= 10 ? (i = 10) : (0 < a ? a : 5e3) <= i && (i = a), o.css({ top: i });
                    }),
                    (l.showInlineModal = function () {
                        l.alignModal();
                    }),
                    (l.hideInlineModal = function () {
                        s.css({ display: "none" });
                    }),
                    angular.element(window).bind("resize", function () {
                        l.isVisible && l.alignModal();
                    }),
                    $(window).on("scroll", function () {
                        l.isVisible && l.alignModalArrow();
                    }),
                    r.parents().on("scroll", function () {
                        l.isVisible && l.alignModalArrow();
                    });
            },
            template: function () {
                return (
                    '<div class="scheduler" ng-class="{\'inlinemodal--open\' : isVisible}"><button ng-click="toggleWindow()" type="button" class="scheduler-btn btn btn-link"><i class="material-icons">timelapse</i><span ng-hide="onlyIcon">{{valueToLabel(value)}}</span></button><div class="inlinemodal" style="display: none;" ng-class="modalPositionClass" zaa-esc="escModal()"><div class="inlinemodal-inner"><div class="inlinemodal-head clearfix"><div class="modal-header"><h5 class="modal-title">{{title}}</h5><div class="modal-close"><button type="button" class="close" aria-label="Close" ng-click="toggleWindow()"><span aria-hidden="true"><span class="modal-esc">ESC</span> &times;</span></button></div></div></div><div class="inlinemodal-content"><div class="clearfix"><zaa-select model="newvalue" options="attributeValues" label="' +
                    i18n.js_scheduler_new_value +
                    '"></zaa-select><zaa-checkbox model="showDatepicker" fieldid="{{getUniqueFormId(\'datepicker\')}}" label="' +
                    i18n.js_scheduler_show_datepicker +
                    '"></zaa-checkbox><zaa-datetime ng-show="showDatepicker" model="timestamp" label="' +
                    i18n.js_scheduler_time +
                    '"></zaa-datetime><button type="button" class="btn btn-save btn-icon float-right" ng-click="saveNewJob()">' +
                    i18n.js_scheduler_save +
                    '</button></div><div class="card mt-4" ng-class="{\'card-closed\': !upcomingAccordionOpen}" ng-hide="logs.upcoming.length <= 0"><div class="card-header" ng-click="upcomingAccordionOpen=!upcomingAccordionOpen"><span class="material-icons card-toggle-indicator">keyboard_arrow_down</span><i class="material-icons">alarm</i>&nbsp;<span> ' +
                    i18n.js_scheduler_title_upcoming +
                    '</span><span class="badge badge-secondary float-right">{{logs.upcoming.length}}</span></div><div class="card-body p-2"><div class="table-responsive"><table class="table table-hover table-align-middle"><thead><tr><th>' +
                    i18n.js_scheduler_table_newvalue +
                    "</th><th>" +
                    i18n.js_scheduler_table_timestamp +
                    '</th><th></th></tr></thead><tbody><tr ng-repeat="log in logs.upcoming"><td>{{valueToLabel(log.new_attribute_value)}}</td><td>{{log.schedule_timestamp*1000 | date:\'short\'}}</td><td style="width: 60px;"><button type="button" class="btn btn-delete btn-icon" ng-click="deleteJob(log)"></button></td></tr></tbody></table></div></div></div><div class="card mt-3" ng-class="{\'card-closed\': !archiveAccordionOpen}" ng-hide="logs.archived.length <= 0"><div class="card-header" ng-click="archiveAccordionOpen=!archiveAccordionOpen"><span class="material-icons card-toggle-indicator">keyboard_arrow_down</span><i class="material-icons">alarm_on</i>&nbsp;<span> ' +
                    i18n.js_scheduler_title_completed +
                    '</span><span class="badge badge-secondary float-right">{{logs.archived.length}}</span></div><div class="card-body p-2"><div class="table-responsive"><table class="table table-hover table-align-middle"><thead><tr><th>' +
                    i18n.js_scheduler_table_newvalue +
                    "</th><th>" +
                    i18n.js_scheduler_table_timestamp +
                    '</th></tr></thead><tbody><tr ng-repeat="log in logs.archived"><td>{{valueToLabel(log.new_attribute_value)}}</td><td>{{log.schedule_timestamp*1000 | date:\'short\'}}</td></tr></tbody></table></div></div></div></div></div><div class="inlinemodal-arrow"></div></div></div>'
                );
            },
        };
    });
