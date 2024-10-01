
/**
 *
 *
 *
 */

zaa.directive("zaaTinymce", function () {
    return {
        restrict: "E",
        scope: {
            "model": "=",
            "options": "=",
            "label": "@label",
            "i18n": "@i18n",
            "id": "@fieldid",
            "placeholder": "@placeholder"
        },
        controller: ['$scope', '$timeout', function ($scope, $timeout) {
            $scope.$watch("model", function (newValue, oldValue) {
                value = angular.element('textarea#' + $scope.id).val();
                if (newValue === value) {
                    return;
                }
                //console.log(newValue);
                editor = tinymce.get($scope.id);
                if (editor) {
                    editor.setContent(newValue);
                }
            });
            $scope.init = function () {
                tinymce.init({
                    forced_root_block: "",
                    branding: false,
                    hidden_input: false,
                    selector: 'textarea#' + $scope.id,
                    height: 200,
                    theme: 'modern',
                    convert_urls: false,
                    language : 'it',
                    language_url : '/tinymce/langs/it.js',
                    plugins: 'print searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools  contextmenu colorpicker textpattern help',
                    toolbar1: 'formatselect | bold italic strikethrough | link unlink image | alignleft aligncenter alignright | numlist bullist outdent indent  | removeformat',
                    image_advtab: true,
                    templates: [
                        {title: 'Test template 1', content: 'Test 1'},
                        {title: 'Test template 2', content: 'Test 2'}
                    ],
                    content_css: [
                        '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                        '//www.tinymce.com/css/codepen.min.css'
                    ],
                    content_style: "body{background-color: #e2e4e7 !important;}",
                    setup: function (editor) {
                        editor.on('keydown change', function () {
                            $('textarea#' + $scope.id).val(editor.getContent());
                            angular.element('textarea#' + $scope.id).val(editor.getContent());
                            $scope.lastKeyStroke = Date.now();
                            $scope.$apply(function () {
                                $scope.model = angular.element('textarea#' + $scope.id).val();
                            });
                        });
                    }
                });
            };
            $timeout(function () {
                tinymce.remove("#" + $scope.id);
                $scope.init();
            });
        }],
        template: function () {
            return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><textarea id="{{id}}" ng-model="model" type="text" class="form-control"></textarea></div></div>';
        }
    }
});



zaa.directive("zaaWysiwyg", ['$compile', function ($compile) {
    return {
        terminal: true,
        priority: 1000000,
        replace: true,
        restrict: "E",
        scope: false,
        template: "<div></div>",
        link: function (scope, element, attrs) {
            for (var attr in attrs.$attr) {
                element.removeAttr(attr);
            }
            //console.log(scope);
            $newDirective = '<zaa-tinymce options="options" initvalue="' + scope.initvalue + '" i18n="" label="' + scope.label + '" autocomplete="" fieldid="' + scope.$id + '" model="model" placeholder="' + scope.placeholder + '"></zaa-tinymce>';
            var el = $compile($newDirective)(scope);
            //console.log(el);
            element.html(el);

        }
    }
}]);


zaa.directive("zaaDbfield", function () {
    return {
        restrict: "E",
        scope: {
            "model": "=",
            "options": "=",
            "label": "@label",
            "i18n": "@i18n",
            "id": "@fieldid",
            "placeholder": "@placeholder",
            "autocomplete": "@autocomplete"
        },
        link:function(scope){

            scope.onBlur = function(ev){
                var el = angular.element(ev.target);
                el.val(el.val().toString().toLowerCase().replace(/[^a-zA-Z0-9]+/g, "_"));
                this.model = el.val();
            }

            scope.onFocus = function(ev){
                var el = angular.element(ev.target);
                el.val(el.val().toString().toLowerCase().replace(/[^a-zA-Z0-9]+/g, "_"));
                this.model = el.val();
            }

        },
        template: function () {
            return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label"><label for="{{id}}">{{label}}</label></div><div class="form-side"><input id="{{id}}" insert-paste-listener ng-model="model" ng-blur="onBlur($event)" ng-focus="onFocus($event)" type="text" class="form-control" autocomplete="{{autocomplete}}" placeholder="{{placeholder}}" /></div></div>';
        }
    }
});

/**
 * <zaa-radio model="model" options="[{label:'foo', value: 'bar', image: '/img/image.png'}, {...}]"></zaa-radio>
 */
zaa.directive("zaaRadioImage", function () {
    return {
        restrict: "E",
        scope: {
            "model": "=",
            "options": "=",
            "label": "@label",
            "i18n": "@i18n",
            "id": "@fieldid",
            "initvalue": "@initvalue"
        },
        controller: ['$scope', '$timeout', function ($scope, $timeout) {
            $scope.setModelValue = function (value) {
                $scope.model = value;
            };

            $scope.init = function () {
                if ($scope.model == undefined || $scope.model == null) {
                    $scope.model = typeCastValue($scope.initvalue);
                }
            };
            $timeout(function () {
                $scope.init();
            });
        }],
        template: function () {
            return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}">' +
                '<div class="form-side form-side-label">' +
                '<label for="{{id}}">{{label}}</label>' +
                '</div>' +
                '<div class="form-side">' +
                '<div ng-repeat="(key, item) in options" class="form-check">' +
                '<input value="{{item.value}}" type="radio" ng-click="setModelValue(item.value)" ng-checked="item.value == model" name="{{id}}_{{key}}" class="form-check-input" id="{{id}}_{{key}}">' +
                '<label class="form-check-label" for="{{id}}_{{key}}">' +
                '<img ng-if="item.image" src="{{item.image}}" alt=""/>' +
                '{{item.label}}' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>';
        }
    };
});

/**
 * <zaa-text model="itemCopy.title" label="<?= Module::t('view_index_page_title'); ?>"></zaa-text>
 */
zaa.directive("zaaTextImage", function () {
    return {
        restrict: "E",
        scope: {
            "model": "=",
            "options": "=",
            "label": "@label",
            "i18n": "@i18n",
            "id": "@fieldid",
            "placeholder": "@placeholder",
            "autocomplete": "@autocomplete"
        },
        template: function () {
            return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}"><div class="form-side form-side-label">'+
                '<label for="{{id}}">' +
                '<img ng-if="options[0].image" src="{{options[0].image}}" alt=""/>' +
                '{{label}}' +
                '</label></div><div class="form-side"><input id="{{id}}" insert-paste-listener ng-model="model" type="text" class="form-control" autocomplete="{{autocomplete}}" placeholder="{{placeholder}}" /></div></div>';
        }
    }
});

/**
 * options arg object:
 *
 * options.items[] = [{"value" : 1, "label" => 'Label for Value 1' }]
 *
 * @param preselect boolean if enable all models will be selected by default.
 */
zaa.directive("zaaSocialSearch", function () {
    return {
        restrict: "E",
        scope: {
            "model": "=",
            "options": "=",
            "i18n": "@i18n",
            "id": "@fieldid",
            "label": "@label",
            "preselect": "@preselect"
        },
        controller: ['$scope', '$filter', '$http', function ($scope, $filter, $http) {

            if ($scope.model == undefined) {
                $scope.model = [];
            }

            $scope.social = [];
            $scope.selectedSocial = [];
            $scope.listSocial = [];

            $scope.searching = 'Cerca';
            $scope.searchHistory = [];

            $http.post('admin/api-cms-admin/get-social').then(function(response) {
                var data = response.data;
                $scope.social = data;
            });

            $scope.preselectOptionValuesToModel = function (options) {
                angular.forEach(options, function (value) {
                    $scope.model.push({
                        'id': value.id,
                        'social': value.social,
                        'value': value.value,
                        'image': value.image,
                        'user': value.user,
                        'user_icon': value.user_icon,
                        'date': value.date,
                        'link': value.link,
                        'user_link': value.user_link,
                        'tot_share': value.tot_share,
                        'tot_comments': value.tot_comments,
                        'tot_like': value.tot_like,
                    });
                });
            };

            $scope.searchString = '';

            $scope.$watch('options.items', function (n, o) {

                if (n != undefined && n.length != 0) {
                    $scope.optionitems = $filter('orderBy')(n, 'label');
                    if ($scope.preselect) {
                        $scope.preselectOptionValuesToModel(n);
                    }
                }
            });

            $scope.filtering = function () {
                $scope.optionitems = $filter('filter')($scope.options.items, {$: $scope.searchString});
            }

            $scope.toggleSelection = function (value) {

                console.log($scope.model);
                if ($scope.model == undefined) {
                    $scope.model = [];
                }

                for (var i in $scope.model) {
                    if ($scope.model[i]["value"] == value.value) {
                        $scope.model.splice(i, 1);
                        return;
                    }
                }
                $scope.model.push({
                    'id': value.id,
                    'social': value.social,
                    'value': value.value,
                    'image': value.image,
                    'user': value.user,
                    'user_icon': value.user_icon,
                    'date': value.date,
                    'link': value.link,
                    'user_link': value.user_link,
                    'tot_share': value.tot_share,
                    'tot_comments': value.tot_comments,
                    'tot_like': value.tot_like,
                });
            }

            $scope.isChecked = function (item) {
                for (var i in $scope.model) {
                    if ($scope.model[i]["value"] == item.value) {
                        return true;
                    }
                }
                return false;
            }

            $scope.search = function (input) {

                $scope.searching = 'Ricerca in corso...';
                var keys = angular.element('#key_social').val();
                if(input != undefined){
                    keys = input;
                    angular.element('#key_social').val(keys);
                }
                if(!$scope.searchHistory.includes(keys) && keys != '')
                    $scope.searchHistory.push(keys);

                console.log($scope.listSocial);
                var tokens = $scope.listSocial.join(", ");
                var url = 'socialwall/socialwall/preview-socialwall?socialwallTokensIds='+tokens+'&keywords='+keys+'&render=false';
                $http.post(url).then(function(response) {

                    var posts = [];
                    angular.forEach(response.data, function (value) {
                        console.log(value);
                        posts.push({
                            'id': value.id,
                            'social': value.type,
                            'value': value.post_text,
                            'image': value.post_image_url,
                            'user': value.name,
                            'user_icon': value.profile_picture_url,
                            'date': value.post_nice_datetime,
                            'link': value.permalink,
                            'user_link': value.profile_url,
                            'tot_share': 0,
                            'tot_comments': 0,
                            'tot_like': 0,
                        });
                    });

                    $scope.model = posts;

                    angular.forEach($scope.model, function (value) {
                        posts.push({
                            'id': value.id,
                            'social': value.social,
                            'value': value.value,
                            'image': value.image,
                            'user': value.user,
                            'user_icon': value.user_icon,
                            'date': value.date,
                            'link': value.link,
                            'user_link': value.user_link,
                            'tot_share': value.tot_share,
                            'tot_comments': value.tot_comments,
                            'tot_like': value.tot_like,
                        });
                    });

                    $scope.options.items = posts.filter((obj, pos, arr) => {
                        return arr.map(mapObj => mapObj['id']).indexOf(obj['id']) === pos;
                    });

                    $scope.searching = 'Cerca';

                });

            }

            $scope.onSocialChange = function (selectedSocial,id) {
                if (selectedSocial) {
                    // il valore dell'elemento di input è stato selezionato
                    $scope.listSocial.push(id);
                    // esegui altre azioni necessarie qui
                } else {
                    // il valore dell'elemento di input è stato deselezionato
                    const index = $scope.listSocial.indexOf(id);
                    if (index >= 0) {
                        $scope.listSocial.splice(index, 1);
                    }
                    // esegui altre azioni necessarie qui
                }
                console.log(selectedSocial);
                console.log(id);
            }
        }],
        link: function (scope) {
            scope.random = Math.random().toString(36).substring(7);
        },
        template: function () {
            return '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}">' +
                '<div class="form-side form-side-label">' +
                '<label for="social">Social configurati</label>' +
                '</div>' +
                '<div class="form-side">' +
                '<div style="display:flex">' +
                '<div ng-repeat="(k,item) in social track by k">' +
                '<input id="{{random}}_{{k}}" type="checkbox" class="form-control" value="{{item.id}}" ng-model="selectedSocial" ng-change="onSocialChange(selectedSocial,{{item.id}})" />' +
                '<label for="{{random}}_{{k}}">{{item.value}}</label>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}">' +
                '<div class="form-side form-side-label">' +
                '<label for="key_social">Inserisci chiave di ricerca contenuti social</label>' +
                '</div>' +
                '<div class="form-side">' +
                '<div style="display:flex">' +
                '<input id="key_social" type="text" class="form-control" autocomplete="{{autocomplete}}" placeholder="{{placeholder}}" />' +
                '<a class="btn btn-save" ng-click="search()" style="width:23%">{{searching}}</a>' +
                '</div>' +
                '<div class="search-history" ng-if="searchHistory.length > 0" style="display:flex">' +
                '<p style="padding-right:15px">Ultime ricerce:</p>' +
                '<div class="socialwall-search-history" style="display:flex">' +
                '<p ng-repeat="(k, tag) in searchHistory track by k"><a ng-click="search(tag)" style="padding-right:15px">{{tag}}</a></p>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +

                '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}">' +
                '<div class="form-side form-side-label">' +
                '<label for="{{id}}">{{label}}</label>' +
                '</div>' +
                '<div class="form-side">' +

                '<div class="position-relative mb-3">' +
                '<div class="input-group">' +
                '<div class="input-group-prepend">' +
                '<div class="input-group-text">' +
                '<i class="material-icons">search</i>' +
                '</div>' +
                '</div>' +
                '<input class="form-control" type="text" ng-change="filtering()" ng-model="searchString" placeholder="' + i18n['ngrest_crud_search_text'] + '">' +
                '</div>' +
                '<span class="zaa-checkbox-array-counter badge badge-secondary">{{options.items.length}}</span>' +
                '</div>' +

                '<div class="form-check" ng-repeat="(k, item) in optionitems track by k">' +
                '<input ng-if="false" type="checkbox" class="form-check-input" ng-checked="isChecked(item)" id="{{random}}_{{k}}" ng-click="toggleSelection(item)" />' +
                '<label for="{{random}}_{{k}}">' +
                '<span class="nome_social" ng-if="item.social">{{item.social}}</span>' +
                '<img class="{{item.social}}" ng-if="item.image" src="{{item.image}}" alt=""/>' +
                '{{item.value}}' +
                '</label>' +
                '</div>' +
                '</div>' +
                '</div>';
        }
    }
});

zaa.directive("zaaFileArrayUploadV2", function () {
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
                '<div class="form-group form-side-by-side" ng-class="{\'input--hide-label\': i18n}">' +
                '<div class="form-side form-side-label">' +
                '<label>{{label}}</label>' +
                '</div>' +
                '<div class="form-side">' +
                '<div class="list zaa-file-array-upload-v2">' +
                '<p class="alert alert-info" ng-hide="model.length > 0">' +
                i18n.js_dir_no_selection +
                '</p>' +
                '<div ng-repeat="(key,file) in model track by key" class="list-item">' +
                '<div class="list-section" ng-if="file.hiddenStorageUploadSource">' +
                '<a ng-href="{{file.hiddenStorageUploadSource}}" target="_blank" class="btn btn-primary">{{file.hiddenStorageUploadName}}</a>' +
                '</div>' +
                '<div class="list-section" ng-if="!file.hiddenStorageUploadSource">' +
                '<div class="list-left">' +
                '<storage-file-upload ng-model="file.fileId"></storage-file-upload>' +
                '</div>' +
                '<div class="list-right">' +
                '<div class="form-group">' +
                '<zaa-text model="file.sostitutivo" label="' + i18n.js_dir_alt_name + '" id="sostitutivo_label_{{file.id}}"></zaa-text>' +
                '<div type="input-hint" class="ng-binding">' +
                '<i class="material-icons">info</i>' +
                i18n.js_dir_alt_name_hint +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label for="{{file.id}}">' +
                i18n.js_dir_image_description +
                '</label>' +
                '<textarea ng-model="file.caption" id="{{file.id}}" class="zaa-file-array-upload-description form-control" auto-grow></textarea>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="list-buttons"  ng-if="!file.hiddenStorageUploadSource">' +
                '<div class="btn-group" role="group">' +
                '<button type="button" class="btn btn-sm btn-outline-info" ng-click="moveUp(key)" ng-if="key > 0">' +
                '<i class="material-icons">keyboard_arrow_up</i>' +
                '</button>' +
                '<button type="button" class="btn btn-sm btn-outline-info" ng-click="moveDown(key)" ng-if="showDownButton(key)">' +
                '<i class="material-icons">keyboard_arrow_down</i>' +
                '</button>' +
                '<button type="button" class="btn btn-sm btn-outline-danger" ng-click="remove(key)">' +
                '<i class="material-icons">remove</i>' +
                '</button>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<button ng-click="add()" type="button" class="btn btn-sm btn-success list-add-button">' +
                '<i class="material-icons">add</i>' +
                '</button>' +
                '</div>' +
                '</div>'
            );
        },
    };
});