
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
                        plugins: 'print fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools  contextmenu colorpicker textpattern help',
                        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
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