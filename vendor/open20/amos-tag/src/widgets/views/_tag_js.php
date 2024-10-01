<?php
/**
 * @var $options array
 */

$idTree = $options['data_trees']['id'];
$this->registerJsVar('tagFormVar_'.$idTree ,\yii\helpers\Json::htmlEncode($options));
$this->registerJsVar('limit_tree_'.$idTree ,$options['data_trees']['limit']);
$this->registerJsVar('tags_selected_'.$idTree ,$options['data_trees']['tags_selected']);
$this->registerJsVar('tags_unlimited_'.$idTree ,$options['tags_unlimited']);
$this->registerJsVar('no_tags_selected_'.$idTree ,$options['no_tags_selected']);
$this->registerJsVar('icon_remove_tag_'.$idTree ,$options['icon_remove_tag']);
$this->registerJsVar('error_limit_tags_'.$idTree ,$options['error_limit_tags']);

$js= <<<JS

  
 //avoid send malformed string as selected nodes on submit - eg. string will be directly used in search query
 // end check on submit
  $(document).on('submit', 'form', function (e) {
        var values = $("#tree_obj_$idTree").val();

        if (values) {
            if (values.charAt(0) == ',') {
                values = values.substr(1);
            }
            if (values.charAt(values.length - 1) == ',') {
                values = values.substr(0, values.length - 1);
            }
            $("#tree_obj_$idTree").val(values);
        }
  });

 $(document).on('treeview.checked', "#tree_obj_$idTree", function (event, key) {
        if (limit_tree_$idTree >= 1) {
            var selectedNodes = $(this).val();
            if (selectedNodes) {
                selectedNodes = selectedNodes.split(',');
                if (
                    selectedNodes
                    &&
                    selectedNodes.length
                    &&
                    selectedNodes.length >= limit_tree_$idTree
                ) {
                    $(this).treeview('uncheckNode', key);
                    alert(error_limit_tags_$idTree);
                }
            }
        } else {
            updateTotale($idTree, limit_tree_$idTree);
        }
  });
 
 $(document).on('treeview.change', "#tree_obj_$idTree", function (event, key, name) {
        var key = key || '';
        var name = name || '';

        var tagsData = new Array();
        var keyNodes = new Array();
        var nameNodes = new Array();

        if (key && name) {
            keyNodes = key.split(',');
            nameNodes = name.split('||');

            $.each(keyNodes, function (index, keyNode) {
                if (!keyNode) {
                    return;
                }

                if (nameNodes[index]) {
                    tagsData.push({
                        id: keyNode,
                        label: nameNodes[index],
                    });
                }
            });
        }

        //metodo comune per aggiornamento della preview e del totale
        renderPreview(tagsData, $idTree, limit_tree_$idTree);
    });
 
    $(document).on("click", "#preview_tag_tree_$idTree .tag_selected_remove", function (event) {
        event.stopImmediatePropagation();

        //recupera la preview del relativo tag
        var tag_selected = $(this).parents(".tag_selected").first();
        if (tag_selected.length) {
            //recupera i dati di tag e albero
            var id_tag = tag_selected.attr("data-tagid");
            var id_tag_tree = tag_selected.attr("data-tagtree");

            //se ci sono tutti i dati
            if (id_tag && id_tag_tree) {
                //rimuove il nodo
                $("#tree_obj_" + id_tag_tree).treeview('uncheckNode', id_tag);

                //identifica il limite di tag selezionabili per l'albero in esame, serve per
                //l'aggiornamento del numero di selezioni rimaste
                var limit_tag_tree = false;
                
                var id_tree = $idTree;
                var limit_tree = limit_tree_$idTree;
                if (id_tree == id_tag_tree) {
                    limit_tag_tree = limit_tree;
                }
                // $.each(data_trees, function (index, data_tree) {
                //     var id_tree = data_tree['id'];
                //     var limit_tree = data_tree['limit'];
                //
                //     if (id_tree == id_tag_tree) {
                //         limit_tag_tree = limit_tree;
                //     }
                // });

                //metodo comune per l'aggiornamento del totale delle selezioni
                updateTotale(id_tag_tree, limit_tag_tree);
            }
        }
    });
    
       $(document).on("click", "#preview_tag_tree_$idTree .tag_selected", function (event) {
        event.stopImmediatePropagation();

        //identifica il tag preview
        var tag_selected = $(this);

        //recupera i dati di tag e albero
        var id_tag = tag_selected.attr("data-tagid");
        var id_tag_tree = tag_selected.attr("data-tagtree");

        //se ci sono tutti i dati
        if (id_tag && id_tag_tree) {
            //identifica il tag a cui scrollare
            var tagScrollTo = $("#tree_obj_" + id_tag_tree + "-tree").find('[data-key="' + id_tag + '"]');
            if (tagScrollTo.length) {
                //lancia la funzione ricorsiva che apre tutti i parent
                openNode(tagScrollTo);

                //altezza dell'header
                var headerHeight = $("#tree_obj_" + id_tag_tree + "-wrapper").find('.kv-header-container').height();

                $("#tree_obj_" + id_tag_tree + "-tree").animate({
                    scrollTop: (tagScrollTo[0].offsetTop - headerHeight)
                }, 'slow');
            }
        }
    });
 
       
  renderPreview = function (tagsData, id_tree, limit_tree) {
    //identifica il blocco di preview
    var preview_block = $('#preview_tag_tree_' + id_tree);

    //lo svuota
    preview_block.empty();

    //se ci sono elementi, li renderizza
    if (tagsData.length > 0) {
        $.each(tagsData, function (index, tagData) {
            var id_tag = tagData.id;
            var tag_tmp = "";
            tag_tmp += "<div class='tag_selected col-xs-12' data-tagid='" + id_tag + "' data-tagtree='" + id_tree + "'>";
            tag_tmp += "    <div class='tag_selected_part tag_selected_remove_container'>";
            tag_tmp += "        <div class='tag_selected_remove'>" + icon_remove_tag_$idTree + "</div>";
            tag_tmp += "    </div>";
            tag_tmp += "    <div class='tag_selected_part'>";
            tag_tmp += "        <div class='tag_selected_label'>";
            tag_tmp += "            " + tagData.label;
            tag_tmp += "        </div>";
            tag_tmp += "        <div class='tag_selected_parents'>";
            tag_tmp += "            " + getParentsString(id_tag, id_tree);
            tag_tmp += "        </div>";
            tag_tmp += "    </div>";
            tag_tmp += "</div>";

            //lo inserisce nella preview
            preview_block.append(tag_tmp);
        });
    }
    //altrimenti inserisce una label generale
    else {
        var label_no_tag = "";
        label_no_tag += "<span class='tree_no_tag'>";
        label_no_tag += "   " + no_tags_selected_$idTree;
        label_no_tag += "</span>";
        preview_block.append(label_no_tag);
    }
    //metodo comune per l'aggiornamento del contatore dei nodi selezionati
    updateTotale(id_tree, limit_tree);
};

getParentsString = function (id_tag, id_tag_tree) {
    //identifica il tag
    var currentTag = $("#tree_obj_" + id_tag_tree + "-tree").find('[data-key="' + id_tag + '"]');

    //recupera ricorsivamente la label dei tag padri
    var tag_parents = getParentsStringFromTag(currentTag);

    return tag_parents.join(" / ");
};

updateTotale = function (id_tree, limit_tree) {
    //identifica il blocco che contiene il contatore
    var counter_block = $('#remaining_tag_tree_' + id_tree).find('.tree-remaining-tag-number');

    //se il limite non è infinito
    if (limit_tree) {
        //calcola il totale attuale
        var selectedNodes = $("#tree_obj_" + id_tree).val();

        //recupera il conteggio degli elementi
        var count_selected = 0;
        if (selectedNodes && selectedNodes != '') {
            selectedNodes = selectedNodes.split(',');
            count_selected = selectedNodes.length;
        }

        counter_block.html((limit_tree - count_selected) + "/" + limit_tree);
    } else {
        counter_block.html(tags_unlimited_$idTree);
    }
};

openNode = function (node) {
    //recupera il nodo parent e se chiuso lo apre
    var parentNode = node.parents(".kv-parent").first();

    //procede solo se ha identificato il nodo padre
    if (parentNode.length) {
        //se il nodo è chiuso
        if (parentNode.hasClass('kv-collapsed')) {
            parentNode.find('.kv-node-toggle').first().trigger('click');
        }

        openNode(parentNode);
    }
};

getParentsStringFromTag = function (node) {
    //array con i parents
    var parents = new Array();

    //recupera il nodo parent
    var parentNode = node.parents(".kv-parent").first();

    //procede solo se ha identificato il nodo padre
    if (parentNode.length) {
        //salva la label
        parents.push(parentNode.find(".kv-node-label").html());

        //lancia ricorsivamente per identificare i nodi padre del padre
        var parent_parents = getParentsStringFromTag(parentNode);
        if (parent_parents.length) {
            $.each(parent_parents, function (index, parent_parent) {
                parents.unshift(parent_parent);
            });
        }
    }

    return parents;
};

// dynamically populate the dropdown options by selected tags in the tree specified
function populateDropdownWithSelectedTags(tagTreeId, dropdownId) {
    var amosTagWidget = $('#' + tagTreeId);
    if (amosTagWidget.length) {
        var treeId = '#' + amosTagWidget.find('input.hide').attr('id');
        if ($(treeId).length) {
            $(treeId).on('treeview.checked', function (event, key) {
                var label = amosTagWidget.find('li[data-key=' + key + '] span.kv-node-label').text();
                $('#' + dropdownId).append('<option value=' + key + '>' + label + '</option>');
            });
            $(treeId).on('treeview.unchecked', function (event, key) {
                var option = $('#' + dropdownId).find('option[value=' + key + ']');
                if ($('#' + dropdownId).val() == key) {
                    $('#' + dropdownId).val(null).trigger('change');
                }
                option.remove();
            });
        }
    }
}

function onlyLeavesSelectable(selectSonsOnly) {
    // only leaf nodes are selectable
    var parents = $('li.kv-parent');
    parents.each(function (index, node) {
        if (selectSonsOnly == true) {
            $('.kv-node-checkbox:first', this).remove();
        }
        //The folder node - remove checkbox
        var detail = $('.kv-node-detail:first', this);
        detail.addClass('kv-node-detail-parent').removeClass('kv-node-detail');
        // on click open/close folder instead of select the folder and nodes inside it
        detail.on('click', function (e) {
            e.preventDefault();
            $(this).parent().find('.kv-node-toggle:first').click();
        });
    });
}

//$(document).ready(function () {
//    renderPreview(tags_selected_$idTree, $idTree, limit_tree_$idTree);
//    onlyLeavesSelectable(tagFormVar_$idTree.selectSonsOnly);
//});


JS;


$this->registerJs($js);