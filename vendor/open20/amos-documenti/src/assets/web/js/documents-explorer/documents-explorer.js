function documentsExplorer() {
    var templateNavbar = $("#documents-explorer-navbar").html();
    var templateBreadcrumb = $("#documents-explorer-breadcrumb").html();
    var templateFolders = $("#documents-explorer-folders").html();
    var templateFiles = $("#documents-explorer-files").html();
    var templateNewFolderModal = $("#documents-explorer-new-folder-modal").html();
    var templateDeleteFileModal = $("#documents-explorer-delete-file-modal").html();
    var templateDeleteFolderModal = $("#documents-explorer-delete-folder-modal").html();
    var templateDeleteAreaModal = $("#documents-explorer-delete-area-modal").html();
    var templateDeleteStanzaModal = $("#documents-explorer-delete-stanza-modal").html();

    var currentParentId = null;

    var currentScope = null;
    var currentScopeName = '';

    var currentTranslationsAndButtons = null;

    var translatedStrings = [];
    var dataNavbar = [];
    var dataBreadcrumb = [];
    
    dataBreadcrumb['links'] = [];

    /**
     * Structure for this array:
     * [
     *      [
     *           'name' : 'stanza_area_name',
     *           'scope_id' : 'scope_id',
     *           'is_area' : true/false
     *      ]
     * ]
     * @type {Array}
     */
    var routeStanze = [];

    /**
     * GET BUTTONS AND TRANSLATIONS
     * (FOR CURRENT SCOPE)
     */

    /**
     * START DOCUMENTS EXPLORER FUNCTIONS
     */

    function startDocumentsExplorer() {
        setBreadcrumb();
        setNavbar();
        reloadExplorer().done(function () {
            // PCD-507 : All'apertura dell'explorer (la prima volta che viene inizializzato, quindi all'apertura della pagina),
            // entrare automaticamente in un'area se un utente può visualizzare (e accedere) solo a quella.
            // Per fare ciò viene controllato se non sono settati scope (quindi se l'utente non è già presente all'interno
            // di un'area e di una stanza), viene controllato se nella lista è presente una sola area, e solamente in questo
            // caso si entra all'interno dell'unica stanza visualizzata.
            if(currentScope === null) {
                if($("#stanze-list").children().length == 1) {
                    $($("#stanze-list").children()[0]).find('.stanze-list-item').click();
                    if(document.referrer.includes("insert-auth-data")) {
                        reloadExplorer();
                    }
                }
            }
        });
    }

    function reloadExplorer() {
        var d = $.Deferred();
        getTranslationsAndButtons().done(function (resp) {
            getAree().done(function () {
                d.resolve();
                setNewFolderBehavior();
            });
        });
        return d.promise();
    }

    function setAreeBehaviors() {
        $("#stanze-list div.stanze-list-item").click(function () {
            currentParentId = null;
            currentScope = $(this).attr('data-stanza-id');

            resetBreadcrumb($(this).attr('data-stanza-name'));

            setBreadcrumb();
            refreshExplorer(currentParentId, currentScope).done(function () {
            });
            getStanze();
        });
    }

    /**
     * END START DOCUMENTS EXPLORER FUNCTIONS
     */

    /**
     * NAVBAR SECTION
     */

    function setNavbar() {
        $("#content-explorer-navbar").empty();
        var htmlNavbar = Mustache.render(templateNavbar, dataNavbar);
        $('#content-explorer-navbar').append(htmlNavbar);
    }

    function reloadNavbar() {

    }

    /**
     * NEW FOLDER SECTION
     */

    function setNewFolderBehavior() {

    }

    /**
     * END NEW FOLDER SECTION
     */

    /**
     * END NAVBAR SECTION
     */

    /**
     * ###########################################################################
     * ###########################################################################
     */

    /**
     * BREADCRUMB SECTION
     */

    /**
     * Reload all breadcrumb section
     */
    function setBreadcrumb() {
        $('#content-explorer-breadcrumb').empty();
        var htmlBreadcrumb = Mustache.render(templateBreadcrumb, dataBreadcrumb);
        $('#content-explorer-breadcrumb').append(htmlBreadcrumb);
        setBreadcrumbBehavior();
    }

    /**
     * Set a new breadcrumb
     * @param parentId
     * @param folderName
     */
    function setNewBreadcrumb(parentId, folderName, scopeId) {
        if (!parentId) parentId = null;
        if (!folderName) folderName = null;
        if (!scopeId) scopeId = null;
        $(dataBreadcrumb['links']).each(function () {
            if (this.classes.indexOf('link') == -1) {
                this.classes += " link";
                this.isNotLast = true;
            }
        });

        dataBreadcrumb['links'].push({
            'classes': '',
            'model-id': parentId,
            'scope-id': scopeId,
            'name': folderName,
        });

        currentParentId = parentId;

        $('#content-explorer-breadcrumb').empty();
        setBreadcrumb();
    }

    /**
     * Remove a breadcrumb (and all the following ones) from breadcrumb section
     * @param parentId
     */
    function removeBreadcrumb(parentId) {
        breadcrumbToRemove = dataBreadcrumb['links'].length;

        $(dataBreadcrumb['links']).each(function (index) {
            if (this['model-id'] === parentId) {
                breadcrumbToRemove = dataBreadcrumb['links'].length - index;
                return false;
            }
        });

        dataBreadcrumb['links'][dataBreadcrumb['links'].length - breadcrumbToRemove].classes = dataBreadcrumb['links'][dataBreadcrumb['links'].length - breadcrumbToRemove].classes.replace("link", "");

        currentParentId = dataBreadcrumb['links'][dataBreadcrumb['links'].length - breadcrumbToRemove]['model-id'];

        delete dataBreadcrumb['links'][dataBreadcrumb['links'].length - breadcrumbToRemove].isNotLast;
        dataBreadcrumb['links'] = dataBreadcrumb['links'].slice(0, -breadcrumbToRemove + 1);
        $('#content-explorer-breadcrumb').empty();
        setBreadcrumb();
    }

    /**
     * Set breadcrumb behaviors
     */
    function setBreadcrumbBehavior() {
        /**
         * Clicking a breadcrumb with "link" css class, will remove that breadcrumb
         * and all the following ones (see removeBreadcrumb() function)
         */
        $("#content-explorer-breadcrumb .link").click(function () {
            removeBreadcrumb($(this).attr('data-parent-id'));
            refreshExplorer($(this).attr('data-parent-id'), currentScope);
        });
    }

    /**
     * END BREADCRUMB SECTION
     */

    /**
     * ###########################################################################
     * ###########################################################################
     */

    /**
     * FOLDERS SECTION
     */

    /**
     * CONTEXT MENU SECTION
     */

// DEPRECATO
    function setContextMenusFolder() {
        $('#documents-explorer').append(Mustache.render(templateDeleteFolderModal));

        $.contextMenu({
            selector: '.context-menu-folder',
            trigger: 'left',
            callback: function (key, options) {
                setContextMenusFoldersBehaviors(key, options, $(this).attr('data-model-id'), $(this));
            },
            items: currentTranslationsAndButtons['foldersOptions'],
        });

        $.contextMenu({
            selector: '.folder-name',
            trigger: 'right',
            callback: function (key, options) {
                setContextMenusFoldersBehaviors(key, options, $(this).attr('data-model-id'), $(this));
            },
            items: currentTranslationsAndButtons['foldersOptions'],
        });
    }

    function setContextMenuFolder(folders) {
        $(folders).each(function () {
            $.contextMenu({
                selector: '.context-menu-folder[data-model-id=' + this['model-id'] + ']',
                trigger: 'left',
                callback: function (key, options) {
                    setContextMenusFoldersBehaviors(key, options, $(this).attr('data-model-id'), $(this));
                },
                items: this.permissions,
            });
            $.contextMenu({
                selector: '.context-menu-folder[data-model-id=' + this['model-id'] + ']',
                trigger: 'right',
                callback: function (key, options) {
                    setContextMenusFoldersBehaviors(key, options, $(this).attr('data-model-id'), $(this));
                },
                items: this.permissions,
            });

            $.contextMenu({
                selector: '.folder-name[data-model-id=' + this['model-id'] + ']',
                trigger: 'right',
                callback: function (key, options) {
                    setContextMenusFoldersBehaviors(key, options, $(this).attr('data-model-id'), $(this));
                },
                items: this.permissions,
            });
        });
    }

    /**
     * END CONTEXT MENU SECTION
     */

    /**
     * Get folders filtered by Documenti plugin
     * @param parentId
     */
    function getFolders(parentId) {
        var d = $.Deferred();
        if (parentId !== undefined && currentScope !== undefined && currentScope !== null) {
            $.ajax({
                method: "post",
                url: '/documenti/documenti-ajax/get-folders',
                data: {
                    'parent-id': parentId,
                    'scope-id': currentScope,
                    'foldersPath': JSON.stringify(dataBreadcrumb),
                },
                cache: false
            }).done(function (resp) {
                resp = $.parseJSON(resp);
                var htmlFolders = Mustache.render(templateFolders, resp);
                $('#content-explorer-folders').append(htmlFolders);
                setContextMenuFolder(resp.folders);
                setFoldersBehaviors();
                d.resolve();
            });
        } else {
            d.resolve();
        }
        return d.promise();
    }

    /**
     * Set folders behaviors
     */
    function setFoldersBehaviors() {
        $("#content-explorer-folders .folder-container .folder div.folder-container-single").each(function () {
            $(this).click(function () {
                setNewBreadcrumb($(this).attr('data-parent-id'), $(this).find('.folder-name .link-name').text());
                refreshExplorer($(this).attr('data-parent-id'),currentScope);
            })
        });
    }

    function reloadFolders(setCreateNewFolderBehavior) {
        if (!setCreateNewFolderBehavior) setCreateNewFolderBehavior = false;
        var d = $.Deferred();
        $('#content-explorer-folders').empty();
        getFolders(currentParentId).done(function () {
            if(setCreateNewFolderBehavior) {
                createNewFolderModalBehavior();
            }
            d.resolve();
        });
        return d.promise();
    }

    /**
     * Set behaviors for the context menus of folder
     * @param className
     */
    function setContextMenusFoldersBehaviors(key, options, modelId, currentObject) {
        switch (key) {
            case 'open':
                window.open('/documenti/documenti/go-to-view-folder?id=' + modelId, '_self');
                return false;
            case 'edit':
                window.open('/documenti/documenti/go-to-update-folder?id=' + modelId, '_self');
                return false;
            // case 'download':
            //     window.open('/attachments/file/download?id='+$(currentObject).attr('data-file-id')+'&hash='+$(currentObject).attr('data-model-hash'), '_blank', 'location=no,height=50,width=50,scrollbars=no,status=no');
            //     return false;
            case 'delete':
                $('#documents-explorer').append(Mustache.render(templateDeleteFolderModal));

                $("#documents-explorer-delete-folder-modal-content").on($.modal.OPEN, function () {
                    $("#documents-explorer-delete-folder-modal-yes").bind("click", function (e) {
                        removeModel(modelId, $("#documents-explorer-delete-folder-modal-no"), $("#documents-explorer-delete-folder-modal-content"), "folders");
                        $("#documents-explorer-delete-folder-modal-yes").unbind();
                    });
                });
                //$(modal).find(".errors").empty();
                $("#documents-explorer-delete-folder-modal-content").modal().ready(function () {
                    $("#documents-explorer-delete-folder-modal-content").on($.modal.AFTER_CLOSE, function () {
                        $("#documents-explorer-delete-folder-modal-content").remove();
                    });
                });
                return false;
            // case "rename":
            //     $("#documents-explorer").append("<div id=\"documents-explorer-rename-folder-modal-content\" class=\"modal\">\n" +
            //         "        <div class=\"row\">\n" +
            //         "            <div class=\"col-xs-12\">\n" +
            //         "                <h2>Rinomina Cartella</h2>\n" +
            //         "                <input id=\"documents-explorer-rename-folder-name\" class=\"form-control\" maxlength=\"255\" type=\"text\">\n" +
            //         "                <div id=\"form-actions\" class=\"bk-btnFormContainer\">\n" +
            //         "                    <button class=\"btn btn-navigation-primary\" id=\"documents-explorer-rename-folder-modal-save\">Ok</button>\n" +
            //         "                    <a class=\"btn btn-secondary undo-edit\" id=\"documents-explorer-rename-folder-modal-close\" rel=\"modal:close\">Annulla</a>\n" +
            //         "                </div>\n" +
            //         "                <div class=\"errors\">\n" +
            //         "                </div>\n" +
            //         "            </div>\n" +
            //         "        </div>\n" +
            //         "    </div>");
            //     $("#documents-explorer-rename-folder-modal-content").find(".errors").empty();
            //     $("#documents-explorer-rename-folder-modal-content").modal();
            //     // TODO rimozione contenuto modale appesa
            //     $("#documents-explorer-rename-folder-modal-content").on('modal:close', function () {
            //         $(this).unbind();
            //         $(this).remove();
            //     });
            default:
                return false;
        }
    }

    /**
     * END FOLDERS SECTION
     */

    /**
     * ###########################################################################
     * ###########################################################################
     */

    /**
     * DOCUMENTS SECTION
     */

    /**
     * CONTEXT MENU SECTION
     */

// DEPRECATO
    function setContextMenusDocuments() {
        $('#documents-explorer').append(Mustache.render(templateDeleteFileModal));
        $.contextMenu({
            selector: '.context-menu-documents',
            trigger: 'left',
            callback: function (key, options) {
                setContextMenusDocumentsBehaviors(key, options, $(this).attr('data-model-id'), $(this));
            },
            items: currentTranslationsAndButtons['documentsOptions'],
        });

        $.contextMenu({
            selector: '.file-preview',
            trigger: 'right',
            callback: function (key, options) {
                setContextMenusDocumentsBehaviors(key, options, $(this).attr('data-model-id'), $(this));
            },
            items: currentTranslationsAndButtons['documentsOptions'],
        });

    }

    function setContextMenuDocuments(documents) {
        $('#documents-explorer').append(Mustache.render(templateDeleteFileModal));
        $(documents).each(function () {
            $.contextMenu({
                selector: '.context-menu-documents[data-model-id=' + this['model-id'] + ']',
                trigger: 'left',
                callback: function (key, options) {
                    setContextMenusDocumentsBehaviors(key, options, $(this).attr('data-model-id'), $(this));
                },
                items: this.permissions,
            });
            $.contextMenu({
                selector: '.context-menu-documents[data-model-id=' + this['model-id'] + ']',
                trigger: 'right',
                callback: function (key, options) {
                    setContextMenusDocumentsBehaviors(key, options, $(this).attr('data-model-id'), $(this));
                },
                items: this.permissions,
            });

            $.contextMenu({
                selector: '.file-preview[data-model-id=' + this['model-id'] + ']',
                trigger: 'right',
                callback: function (key, options) {
                    setContextMenusDocumentsBehaviors(key, options, $(this).attr('data-model-id'), $(this));
                },
                items: this.permissions,
            });
        });

    }

    /**
     * END CONTEXT MENU SECTION
     */

    /**
     * Set behaviors for the context menus of documents
     * @param className
     */
    function setContextMenusDocumentsBehaviors(key, options, modelId, currentObject) {
        switch (key) {
            case 'open':
                window.open('/documenti/documenti/view?id=' + modelId, '_self');
                return false;
            case 'edit':
                window.open('/documenti/documenti/update?id=' + modelId + '&from=dashboard', '_self');
                return false;
            case 'new-version':
                var newVersionModal = "<div id=\"documents-explorer-create-new-version-document-modal-content\" class=\"modal modal-document-explorer\">\n" +
                    "        <div class=\"row\">\n" +
                    "            <div class=\"col-xs-12\">\n" +
                    "                <h2>"+lajax.t("Stai per creare una nuova versione del documento. Procedere?")+"</h2>\n" +
                    "                <div id=\"form-actions\" class=\"bk-btnFormContainer\">\n" +
                    "                    <button class=\"btn btn-navigation-primary\" id=\"documents-explorer-create-new-version-document-modal-yes\">"+lajax.t("Conferma")+"</button>\n" +
                    "                    <a class=\"btn btn-secondary undo-edit\" id=\"documents-explorer-create-new-version-document-modal-no\" rel=\"modal:close\">"+lajax.t("Annulla")+"</a>\n" +
                    "                </div>\n" +
                    "            </div>\n" +
                    "        </div>\n" +
                    "    </div>";
                $("#documents-explorer-files").append(newVersionModal);
                $("#documents-explorer-create-new-version-document-modal-yes").click(function () {
                    window.open('/documenti/documenti/new-document-version?id=' + modelId + '&from=dashboard', '_self');
                    $("#documents-explorer-create-new-version-document-modal-yes").prop('disabled', true);
                    return false;
                });
                $("#documents-explorer-create-new-version-document-modal-content").on($.modal.AFTER_CLOSE, function () {
                    $("#documents-explorer-create-new-version-document-modal-content").remove();
                });
                $("#documents-explorer-create-new-version-document-modal-content").modal();
                return false;
            case 'download':
                download($(currentObject).attr('data-file-id'), $(currentObject).attr('data-model-hash'));
                return false;
                
                
                
//                window.open('/attachments/file/download?id=' + $(currentObject).attr('data-file-id') + '&hash=' + $(currentObject).attr('data-model-hash'), '_blank', 'location=no,height=50,width=50,scrollbars=no,status=no');
//                return false;
            case 'delete':
                $("#documents-explorer-delete-file-modal-yes").click(function (e) {
                    removeModel(
                        modelId, 
                        $("#documents-explorer-delete-file-modal-no"), 
                        $("#documents-explorer-delete-file-modal-content"), 
                        "documents"
                    );
                    $("#documents-explorer-delete-file-modal-yes").unbind();
                });
                $("#documents-explorer-delete-file-modal-content").find(".errors").empty();
                $("#documents-explorer-delete-file-modal-content").modal();
                return false;
            // case "rename":
            //     $("#documents-explorer").append("<div id=\"documents-explorer-rename-document-modal-content\" class=\"modal\">\n" +
            //         "        <div class=\"row\">\n" +
            //         "            <div class=\"col-xs-12\">\n" +
            //         "                <h2>Rinomina File</h2>\n" +
            //         "                <input id=\"documents-explorer-rename-document-name\" class=\"form-control\" maxlength=\"255\" type=\"text\">\n" +
            //         "                <div id=\"form-actions\" class=\"bk-btnFormContainer\">\n" +
            //         "                    <button class=\"btn btn-navigation-primary\" id=\"documents-explorer-rename-document-modal-save\">Ok</button>\n" +
            //         "                    <a class=\"btn btn-secondary undo-edit\" id=\"documents-explorer-rename-document-modal-close\" rel=\"modal:close\">Annulla</a>\n" +
            //         "                </div>\n" +
            //         "                <div class=\"errors\">\n" +
            //         "                </div>\n" +
            //         "            </div>\n" +
            //         "        </div>\n" +
            //         "    </div>");
            //     $("#documents-explorer-rename-document-modal-content").find(".errors").empty();
            //     $("#documents-explorer-rename-document-modal-content").modal();
            //     // TODO rimozione contenuto modale appesa
            //     $("#documents-explorer-rename-document-modal-content").on('modal:close', function () {
            //         $(this).unbind();
            //         $(this).remove();
            //     });
            // $("#documents-explorer-rename-document-modal-save").click(function () {
            //
            //     $(this).unbind();
            // });
            default:
                return false;
        }
    }

    /**
     * Get documents from Documenti plugin via AJAX
     * @param parentId
     */
    function getDocuments(parentId) {
        var d = $.Deferred();
        if (parentId !== undefined && currentScope !== undefined && currentScope !== null) {
            $.ajax(
                {
                    method: "post",
                    url: '/documenti/documenti-ajax/get-documents',
                    data: {
                        'parent-id': parentId,
                        'scope-id': currentScope
                    },
                    cache: false
                }).done(function (resp) {
                resp = $.parseJSON(resp);
                //console.log(resp);
                var htmlDocuments = Mustache.render(templateFiles, resp);
                $('#content-explorer-files').append(htmlDocuments);
                //setContextMenusDocuments();
                setContextMenuDocuments(resp.files);
                d.resolve();
            });
        } else {
            d.resolve();
        }
        return d.promise();
    }

    /**
     * Reload all documents
     */
    function reloadDocuments() {
        var d = $.Deferred();
        $('#content-explorer-files').empty();
        getDocuments(currentParentId).done(function () {
            d.resolve();
        });
        return d.promise();
    }

    /**
     * END DOCUMENTS SECTION
     */

    /**
     * ###########################################################################
     * ###########################################################################
     */

    /**
     * COMMON FUNCTIONS SECTION
     */

    function refreshExplorer(parentId, communityId) {
        if (!parentId) parentId = null;
        d = $.Deferred();
        r = Mustache.render(templateNewFolderModal);
        $.when(reloadFolders(), reloadDocuments()).done(function () {
            createNewFolderModalBehavior();
            
            $("#create-new-folder-modal").click(function () {
                parentIdInUrl = '?';
                if (parentId !== null) {
                    parentIdInUrl += 'parentId=' + parentId + '&';
                }
                window.open('/documenti/documenti/create' + parentIdInUrl + 'isFolder=1&from=dashboard', '_self');
            });
            
            $("#upload-new-files").click(function () {
                parentIdInUrl = '?';
                if (parentId !== null) {
                    parentIdInUrl += 'parentId=' + parentId + '&';
                }
                window.open('/documenti/documenti/create' + parentIdInUrl + 'from=dashboard', '_self');
            });
            $("#upload-multi-files").click(function () {
                parentIdInUrl = '?';
                if (parentId !== null && typeof parentId != 'undefined') {
                    parentIdInUrl += 'parentId=' + parentId + '&';
                }
                if (communityId !== null && typeof communityId != 'undefined') {
                    parentIdInUrl += 'communityId=' + communityId + '&';
                }
                window.open('/documenti/documenti/create-multiple'+ parentIdInUrl + 'from=dashboard', '_self');
            });
            d.resolve();
        });
        return d.promise();
    }

    function createNewFolderModalBehavior() {
//        $("#create-new-folder-modal").click(function () {
//            $("#documents-explorer-new-folder-modal-content").find(".errors").empty();
//            //$("#documents-explorer-new-folder-modal-create-new-folder").find(".errors").empty();
//            $("#documents-explorer-new-folder-name").val("");
//            $("#content-explorer-folders").append(Mustache.render(templateNewFolderModal));
//            $("#documents-explorer-new-folder-modal-content").on($.modal.OPEN, function () {
//                $("#documents-explorer-new-folder-modal-create-new-folder").click(function () {
//                    //return false;
//                    $("#documents-explorer-new-folder-modal-content").find(".errors").empty();
//                    if ($("#documents-explorer-new-folder-name").val().trim() != "") {
//                        $("#documents-explorer-new-folder-modal-create-new-folder").prop("disabled", true);
//                        $.ajax({
//                            method: "post",
//                            url: '/documenti/documenti-ajax/create-folder',
//                            data: {
//                                'parent-id': currentParentId,
//                                'scope': currentScope,
//                                'folder-name': $("#documents-explorer-new-folder-name").val().trim(),
//                            },
//                            cache: false
//                        }).done(function (resp) {
//                            resp = $.parseJSON(resp);
//                            if (resp.success) {
//                                $("#documents-explorer-new-folder-modal-close").click();
//                                $("#documents-explorer-new-folder-modal-create-new-folder").prop("disabled", false);
//                                $("#documents-explorer-new-folder-modal-create-new-folder").unbind();
//                                reloadFolders(true);
//                            } else {
//                                $("#documents-explorer-new-folder-modal-create-new-folder").prop("disabled", false);
//                                $("#documents-explorer-new-folder-modal-content").find(".errors").append('<span>' + resp.message + '</span>');
//                            }
//                        });
//                    } else {
//                        $("#documents-explorer-new-folder-modal-content").find(".errors").append('<span>' + translatedStrings['ERROR--NOME-CARTELLA-NON-VUOTO'] + '</span>');
//                    }
//                });
//            });
//            
//            $("#documents-explorer-new-folder-modal-content").modal().ready(function () {
//                $("#documents-explorer-new-folder-modal-content").on($.modal.AFTER_CLOSE, function () {
//                    $("#documents-explorer-new-folder-modal-content").remove();
//                });
//            });
//        });
    }

    function removeModel(modelId, modalCloseButton, modal, functionReload) {
        $(modal).find(".errors").empty();
        if (modelId !== undefined) {
            $("#documents-explorer-delete-folder-modal-yes").prop("disabled", true);
            $("#documents-explorer-delete-file-modal-yes").prop("disabled", true);
            $.ajax(
                {
                    method: "post",
                    url: '/documenti/documenti-ajax/delete-model',
                    data: {
                        'model-id': modelId
                    },
                    cache: false,
                }).done(function (resp) {
                resp = $.parseJSON(resp);
                //console.log(resp);
                if (resp.success) {
                    $(modalCloseButton).click();
                    switch (functionReload) {
                        case "folders":
                            //reloadFolders();
                            refreshExplorer(currentParentId, currentScope);
                            break;
                        case "documents":
                            //reloadDocuments();
                            refreshExplorer(currentParentId, currentScope);
                            break;
                        default:
                            break;
                    }
                    $("#documents-explorer-delete-folder-modal-yes").prop("disabled", false);
                    $("#documents-explorer-delete-file-modal-yes").prop("disabled", false);
                } else {
                    $(modal).find(".errors").append('<span>' + resp.message + '</span>');
                    $(modal).find(".bk-btnFormContainer").hide();
                    $(modal).find("h2").hide();
                    $("#documents-explorer-delete-folder-modal-yes").prop("disabled", false);
                    $("#documents-explorer-delete-file-modal-yes").prop("disabled", false);
                }
            });
        }
    }

    /**
     * GET TRANSLATIONS AND BUTTONS
     */

    function getTranslationsAndButtons() {
        return $.ajax(
            {
                method: "post",
                url: '/documenti/documenti-ajax/get-translations-and-options',
                data: {
                },
                cache: false
            }).done(function (resp) {
            resp = $.parseJSON(resp);
            currentTranslationsAndButtons = resp;
            translatedStrings = resp['translations'];

            dataNavbar = {
                'NAME--CREATE-NEW-FOLDER': resp['translations']['LABEL--CREATE-NEW-FOLDER'],
                'NAME--UPLOAD-NEW-FILES': resp['translations']['LABEL--UPLOAD-NEW-FILES'],
                'NAME--UPLOAD-MULTI-FILES': resp['translations']['LABEL--UPLOAD-MULTI-FILES'],
            };
        });
    }

    function resetBreadcrumb(scopeName) {
        dataBreadcrumb = {
            "links": [{
                'classes': '',
                'model-id': null,
                'name': scopeName, //lajax.t('Condivisi con me'),
                //'isNotLast' : true
            }],
        }
    }

    /**
     * END COMMON FUNCTIONS SECTION
     */

    /**
     * ###########################################################################
     * ###########################################################################
     */

    function getAree(resetScope) {
        if (!resetScope) resetScope = null;
        return $.ajax({
            method: "get",
            url: '/documenti/documenti-ajax/get-aree',
            data: {
                resetScope: resetScope,
            },
            cache: false
        }).done(function (resp) {
            resp = $.parseJSON(resp);

            if(resp.forceReset !== undefined && resp.forceReset) {
                getAree(true);
                $("#content-explorer-folders").empty();
                $("#content-explorer-files").empty()
                dataBreadcrumb['links'] = [];
                setBreadcrumb();
                return true;
            }

            if(resp.insideSubcommunity !== undefined && resp.insideSubcommunity) {
                currentScope = resp.scope;
                currentParentId = resp.parentId;
                routeStanze = resp.routeStanze;
                // Se parto da una subcommunity (ho uno scope settato), inserisco il flag
                // 'isRootPathStanze' = true alla posizione 0 dell'array routeStanze in modo
                // tale da non permettere di accedere alla lista di tutte le community accessibili dall'utente
                // (viene quindi contestualizzata la root di partenza di navigazione)
                if(routeStanze[0] !== undefined && routeStanze[0] !== null) {
                    routeStanze[0]['isRootPathStanze'] = true;
                }
                dataBreadcrumb = resp.breadcrumbFolders;
                if(!dataBreadcrumb.hasOwnProperty('links')) {
                    dataBreadcrumb = dataBreadcrumb[0];
                }

                $.when(getStanze(true), refreshExplorer(currentParentId,currentScope)).done(function () {
                    dataBreadcrumb = resp.breadcrumbFolders;
                    if(!dataBreadcrumb.hasOwnProperty('links')) {
                        dataBreadcrumb = dataBreadcrumb[0];
                    }
                    setBreadcrumb();
                });
                return true;
            }

            var htmlModal = Mustache.render(templateDeleteAreaModal);
            $('#content-explorer-sidebar').append(htmlModal);

            $("#location-title").empty();
            $("#location-title").append("<h2>" + lajax.t('Aree di condivisione') + "</h2>");
            $("#documents-explorer-create-new-area").remove();
            if (resp.canCreate) {
                $("#content-explorer-sidebar").prepend("<div class='col-xs-12 add-area' id=\"documents-explorer-create-new-area\">" +
                    "<a href=\"/community/community/create\" title='" + lajax.t('Nuova Area Condivisa') + "' class=\"btn btn-navigation-primary\">" +
                    "<span class=\"am am-plus\"></span>" +
                    lajax.t('Nuova Area Condivisa') +
                    "</a>" +
                    "</div>"
                );
            }
            $("#stanze-list").empty();
            $(resp.areas).each(function () {
                $("#stanze-list").append(
                    "<div class='col-md-12 col-sm-4 col-xs-12 room-name-container'>" +
                    "<div data-stanza-id=\"" + this.id + "\" data-stanza-name=\"" + this.name + "\" class='stanze-list-item col-xs-12 nop'>" +
                    "<div class='room-name'>" +
                    "<span class='dash dash-group'></span>" +
                    "<div class='col-xs-12 nop'>" +
                    "<span class='link-name' title='" + this.name + "'>" + this.name + "</span>" +
                    "<span class='description' title='" + this.description + "'>" + this.description + "</span>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "<span class='am am-menu context-menu-stanze' data-stanza-id='" + this.id + "'></span>" +
                    "</div>"
                );
                setContextMenusStanza(this.permissions, this.id, true);
            });
            setAreeBehaviors();
            setContextMenusStanzeBehaviors();
        });
    }

    function getStanze(backToStanza) {
        if (!backToStanza) backToStanza = null;
        return $.ajax({
            method: "get",
            url: '/documenti/documenti-ajax/get-subcommunities',
            data: {
                'idArea': currentScope,
                'routeStanze': JSON.stringify(routeStanze),
                'removeStanza': backToStanza,
            },
            cache: false
        }).done(function (resp) {
            resp = $.parseJSON(resp);

            var htmlModal = Mustache.render(templateDeleteStanzaModal);
            $('#content-explorer-sidebar').append(htmlModal);

            $("#location-title").empty();

            if (!backToStanza) {
                routeStanze.push({
                    'name': resp['current-community-name'],
                    'scope_id': currentScope,
                    'isArea': (routeStanze.length === 0),
                });
            }

            if(routeStanze.length === 1 && routeStanze[0].isRootPathStanze) {
                // if sono nella stanza isRootPathStanza
                $("#location-title").append('<h2>' + resp['current-community-name'] + '</h2>');
            } else {
                // else
                $("#location-title").append('<span id="documents-explorer-back-stanza" class="am am-arrow-left" title="Torna indietro"> <!--TODO add class hidden if first layer-->\n' +
                    '                            <span class="sr-only">Indietro</span>\n' +
                    '                        </span><h2>' + resp['current-community-name'] + '</h2>');
            }
            resetBreadcrumb(resp['current-community-name']);

            goBackStanzaBehavior();

            $("#documents-explorer-create-new-area").remove();
            if (resp.canCreate) {
                $("#content-explorer-sidebar").prepend("<div class='col-xs-12 add-room' id=\"documents-explorer-create-new-area\">" +
                    "<a href=\"/community/community/create?parentId=" + currentScope + "\" class=\"btn btn-navigation-primary\" title='" + lajax.t('Crea nuova sottocommunity') + "'>" +
                    "<span class=\"am am-plus\"></span>" +
                    lajax.t('Crea nuova sottocommunity') +
                    "</a>" +
                    "</div>"
                );
            }
            $("#stanze-list").empty();

            $(resp.subcommunities).each(function () {
                $("#stanze-list").append(
                    "<div class='col-md-12 col-sm-4 col-xs-12 room-name-container'>" +
                    "<div data-stanza-id=\"" + this.id + "\" data-stanza-name=\"" + this.name + "\" class='stanze-list-item col-xs-12 nop'>" +
                    "<div class='room-name'>" +
                    "<span class='am am-view-module'></span>" +
                    "<div class='col-xs-12 nop'>" +
                    "<span class='link-name' title='" + this.name + "'>" + this.name + "</span>" +
                    "<span class='description' title='" + this.description + "'>" + this.description + "</span>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "<span class='am am-menu context-menu-stanze' data-stanza-id='" + this.id + "'></span>" +
                    "</div>"
                );

                setContextMenusStanza(this.permissions, this.id, false);
            });

            $("#stanze-list div.stanze-list-item").click(function () {
                currentParentId = null;
                currentScope = $(this).attr('data-stanza-id');

                resetBreadcrumb($(this).attr('data-stanza-name'));

                setBreadcrumb();

                refreshExplorer(currentParentId,currentScope).done(function () {
                    setNewFolderBehavior();
                });
                getStanze();
            });

        });
    }

    function goBackStanzaBehavior() {
        $("#documents-explorer-back-stanza").click(function () {
            goBackStanza();
            $("#documents-explorer-back-stanza").unbind();
        });
    }

    function goBackStanza() {
        routeStanze.pop();
        currentParentId = null;
        if (routeStanze.length === 0) {
            currentScope = null;
        } else {
            currentScope = routeStanze[routeStanze.length - 1].scope_id;
            resetBreadcrumb(routeStanze[routeStanze.length - 1].name);
        }
        setBreadcrumb();
        if (routeStanze.length === 0) {
            getAree(true);
            $("#content-explorer-folders").empty();
            $("#content-explorer-files").empty()
            dataBreadcrumb['links'] = [];
            setBreadcrumb();
        } else {
            refreshExplorer(currentParentId,currentScope).done(function () {
                setNewFolderBehavior();
            });
            getStanze(true);
        }
    }

    function setContextMenusStanza(arrayPermissions, stanzaId, isArea) {
        $.contextMenu({
            selector: '.context-menu-stanze[data-stanza-id=' + stanzaId + ']',
            trigger: 'left',
            callback: function (key, options) {
                setContextMenusStanzeBehaviors(key, options, $(this).attr('data-stanza-id'), $(this), isArea);
            },
            items: arrayPermissions,
        });
        $.contextMenu({
            selector: '.context-menu-stanze[data-stanza-id=' + stanzaId + ']',
            trigger: 'right',
            callback: function (key, options) {
                setContextMenusStanzeBehaviors(key, options, $(this).attr('data-stanza-id'), $(this), isArea);
            },
            items: arrayPermissions,
        });

        $.contextMenu({
            selector: '.stanze-list-item[data-stanza-id=' + stanzaId + ']',
            trigger: 'right',
            callback: function (key, options) {
                setContextMenusStanzeBehaviors(key, options, $(this).attr('data-stanza-id'), $(this), isArea);
            },
            items: arrayPermissions,
        });

    }

    /**
     * Set behaviors for the context menus of areas and rooms
     * @param className
     */
    function setContextMenusStanzeBehaviors(key, options, modelId, currentObject, isArea) {
        if (!isArea) isArea = false;
        switch (key) {
            case 'open':
                window.open('/documenti/documenti/go-to-view?openScheda=1&id=' + modelId, '_self');
                return false;
            case 'edit':
                window.open('/documenti/documenti/go-to-update?id=' + modelId, '_self');
                return false;
            case 'import':
                var importAction = '/import/default/import?communityId=' + modelId;
                window.open('/uploader/upload/index?callbackUrl=' + encodeURIComponent(importAction), '_self');
                return false;
            case 'participants':
                window.open('/documenti/documenti/go-to-participants-tab?id=' + modelId, '_self');
                return false;
            case 'sharingGroups':
                window.open('/documenti/documenti/go-to-groups?id=' + modelId, '_self');
                return false;
            case 'cooperation':
                window.open('/documenti/documenti/go-to-join?id=' + modelId, '_self');
                return false;
            // case 'download':
            //     window.open('/attachments/file/download?id='+$(currentObject).attr('data-file-id')+'&hash='+$(currentObject).attr('data-model-hash'), '_blank', 'location=no,height=50,width=50,scrollbars=no,status=no');
            //     return false;
            case 'delete':
                if (isArea) {
                    $("#documents-explorer-delete-area-modal-content").find(".errors").empty();
                    $("#documents-explorer-delete-area-modal-yes").bind("click", function (e) {
                        removeCommunity(modelId, $("#documents-explorer-delete-area-modal-no"), $("#documents-explorer-delete-area-modal-content"), 'area');
                        $("#documents-explorer-delete-area-modal-yes").unbind();
                    });
                    $("#documents-explorer-delete-area-modal-content").on($.modal.AFTER_CLOSE, function () {
                        $("#documents-explorer-delete-area-modal-yes").unbind();
                    });
                    $("#documents-explorer-delete-area-modal-content").modal();
                } else {
                    $("#documents-explorer-delete-stanza-modal-content").find(".errors").empty();
                    $("#documents-explorer-delete-stanza-modal-yes").bind("click", function (e) {
                        removeCommunity(modelId, $("#documents-explorer-delete-stanza-modal-no"), $("#documents-explorer-delete-stanza-modal-content"), 'stanza', true);
                        $("#documents-explorer-delete-stanza-modal-yes").unbind();
                    });
                    $("#documents-explorer-delete-stanza-modal-content").on($.modal.AFTER_CLOSE, function () {
                        $("#documents-explorer-delete-stanza-modal-yes").unbind();
                    });
                    $("#documents-explorer-delete-stanza-modal-content").modal();
                }
                return false;
            // case "rename":
            //     $("#documents-explorer").append("<div id=\"documents-explorer-rename-folder-modal-content\" class=\"modal\">\n" +
            //         "        <div class=\"row\">\n" +
            //         "            <div class=\"col-xs-12\">\n" +
            //         "                <h2>Rinomina Cartella</h2>\n" +
            //         "                <input id=\"documents-explorer-rename-folder-name\" class=\"form-control\" maxlength=\"255\" type=\"text\">\n" +
            //         "                <div id=\"form-actions\" class=\"bk-btnFormContainer\">\n" +
            //         "                    <button class=\"btn btn-navigation-primary\" id=\"documents-explorer-rename-folder-modal-save\">Ok</button>\n" +
            //         "                    <a class=\"btn btn-secondary undo-edit\" id=\"documents-explorer-rename-folder-modal-close\" rel=\"modal:close\">Annulla</a>\n" +
            //         "                </div>\n" +
            //         "                <div class=\"errors\">\n" +
            //         "                </div>\n" +
            //         "            </div>\n" +
            //         "        </div>\n" +
            //         "    </div>");
            //     $("#documents-explorer-rename-folder-modal-content").find(".errors").empty();
            //     $("#documents-explorer-rename-folder-modal-content").modal();
            //     // TODO rimozione contenuto modale appesa
            //     $("#documents-explorer-rename-folder-modal-content").on('modal:close', function () {
            //         $(this).unbind();
            //         $(this).remove();
            //     });
            default:
                return false;
        }
    }

    function removeCommunity(modelId, modalCloseButton, modal, functionReload, stayInStanza) {
        if (!stayInStanza) stayInStanza = false;
        $(modal).find(".errors").empty();
        if (modelId !== undefined) {
            $("#documents-explorer-delete-area-modal-yes").prop("disabled", true);
            $("#documents-explorer-delete-stanza-modal-yes").prop("disabled", true);
            $.ajax(
                {
                    method: "POST",
                    url: '/documenti/documenti-ajax/delete-community',
                    data: {
                        'model-id': modelId
                    },
                    cache: false,
                }).done(function (resp) {
                resp = $.parseJSON(resp);
                if (resp.success) {
                    $(modalCloseButton).click();
                    switch (functionReload) {
                        case "area":
                            refreshExplorer().done(function () {
                                setNewFolderBehavior();
                            });
                            getAree(true);
                            break;
                        case "stanza":
                            if (stayInStanza) {
                                refreshExplorer().done(function () {
                                    setNewFolderBehavior();
                                });
                                getStanze(true);
                            } else {
                                routeStanze.pop();
                                if (routeStanze.length === 0) {
                                    currentScope = null;
                                } else {
                                    currentScope = routeStanze[routeStanze.length - 1].scope_id;
                                    resetBreadcrumb(routeStanze[routeStanze.length - 1].name);
                                }
                                setBreadcrumb();
                                if (routeStanze.length === 0) {
                                    getAree(true);
                                    $("#content-explorer-folders").empty();
                                    $("#content-explorer-files").empty()
                                    dataBreadcrumb['links'][0]['name'] = '';
                                    setBreadcrumb();
                                } else {
                                    refreshExplorer(null,currentScope).done(function () {
                                        setNewFolderBehavior();
                                    });
                                    getStanze(true);
                                }
                                $("#documents-explorer-back-stanza").unbind();
                            }
                            break;
                        default:
                            break;
                    }
                    $("#documents-explorer-delete-area-modal-yes").prop("disabled", false);
                    $("#documents-explorer-delete-stanza-modal-yes").prop("disabled", false);
                } else {
                    $(modal).find(".errors").append('<span>' + resp.message + '</span>');
                    $("#documents-explorer-delete-area-modal-yes").prop("disabled", false);
                    $("#documents-explorer-delete-stanza-modal-yes").prop("disabled", false);
                }
            });
        }
    }

    $(document).on('ajaxStart', function () {
        $('#loader').show();
    });

    $(document).on('ajaxStop', function () {
        $('#loader').hide();
    });


    /**
     * ###########################################################################
     * ###########################################################################
     */

    /**
     * START DOCUMENTS EXPLORER (SECTION)
     * (THIS MUST BE AT THE END OF THE JS FILE)
     */

    startDocumentsExplorer();

    /**
     * END START DOCUMENTS EXPLORER SECTION
     */
}

documentsExplorer();