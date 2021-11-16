$(document).ready(function () {
    
    //attiva l'evento di evidenza delle tab dopo il validate del form
    FormActions.afterValidate();

    //di default, forza la visualizzazione della modalità semplice
    AreeInteresse.showSemplice();

    $('#bk-avanzata').click(function () {
        AreeInteresse.showAvanzata();
    });

    $('#bk-semplice').click(function () {
        AreeInteresse.showSemplice();
    });

    //cicla gli alberi delle aree di interesse per applicare gli eventi
    $.each(AreeInteresseVars.data_trees, function(ref_tree, data_trees){
        $.each(data_trees, function(index, data_tree){
            var id_tree = data_tree['id'];
            var limit_tree = data_tree['limit'];
            var tags_selected = data_tree['tags_selected'];

            //metodo comune che avvia la renderizzazione della preview dell'albero ed applica gli eventi
            AreeInteresse.initTree(tags_selected, id_tree, limit_tree, data_trees, ref_tree);
        });
    });
    if ($.isFunction(onlyLeavesSelectable())) {
        onlyLeavesSelectable();
    }
});

var AreeInteresse = [];
AreeInteresse.showAvanzata = function(){
    $('#vista-avanzata').show();
    $('#vista-semplice').hide();
    $('#bk-semplice').show();
    $('#bk-avanzata').hide();
};
AreeInteresse.showSemplice = function(){
    $('#vista-semplice').show();
    $('#vista-avanzata').hide();
    $('#bk-avanzata').show();
    $('#bk-semplice').hide();
};
AreeInteresse.initTree = function(tags_selected, id_tree, limit_tree, data_trees, ref_tree){
    //Enable advance tree copy
    var enableAdvance = false;


    //renderizza la preview ed aggiorna il totale dei nodi selezionati
    AreeInteresse.renderPreview(tags_selected, id_tree, limit_tree, ref_tree);

    //identifica l'id dell'albero, utile per gli eventi
    var id_input_tree = AreeInteresse.getIdTree(id_tree, ref_tree);

    //identifica l'id della preview dell'albero
    var id_preview_tree = AreeInteresse.getIdPreviewTree(id_tree, ref_tree);

    /* applica gli eventi sugli alberi */

    $("#"+id_input_tree).on('treeview.checked', function(event, key) {
        if(limit_tree >= 1){
            var selectedNodes = $(this).val();
            if(selectedNodes){
                selectedNodes = selectedNodes.split(',');
                if(
                    selectedNodes
                    &&
                    selectedNodes.length
                    &&
                    selectedNodes.length >= limit_tree
                ){
                    $(this).treeview('uncheckNode', key);
                    alert(AreeInteresseVars.error_limit_tags);
                }
            }
        }
        else{
            AreeInteresse.updateTotale(id_tree, limit_tree, ref_tree);
        }
    });

    $("#"+id_input_tree).on('treeview.change',  function(event, key, name) {
        var key = key || '';
        var name = name || '';

        var tagsData = new Array();
        var keyNodes = new Array();
        var nameNodes = new Array();

        if(key && name){
            keyNodes = key.split(',');
            nameNodes = name.split('||');

            $.each(keyNodes, function( index, keyNode ) {
                if(!keyNode){
                    return;
                }

                if(nameNodes[index]){
                    tagsData.push({
                        id : keyNode ,
                        label : nameNodes[index],
                    });
                }
            });
        }

        //metodo comune per aggiornamento della preview e del totale
        AreeInteresse.renderPreview(tagsData, id_tree, limit_tree, ref_tree);

        //se è un albero semplificato, replica le modifiche a quelli avanzati
        if(ref_tree == "simple-choice" && enableAdvance){
            //identifica l'id dell'albero di riferimento
            var tree_id_to_change = $(this).attr('data-tree-attach');

            $("input[id^=advanced-choice-treeview-" + tree_id_to_change + "-]").each(function (index, tree) {
                //oggetto dell'albero
                var tree_view_input = $(tree);

                //identifica i nodi dell'albero
                var tree_view_ids = tree_view_input.val();
                if(tree_view_ids && tree_view_ids.length > 0){
                    //rimuove i nodi vecchi
                    tree_view_ids = tree_view_ids.split(",");
                    $.each(tree_view_ids, function(index_id, id){
                        tree_view_input.treeview("uncheckNode", id);
                    });
                }

                //seleziona quelli nuovi
                var tree_view_new_ids = key.split(',');
                $.each(tree_view_new_ids, function(index_id, id){
                    tree_view_input.treeview("checkNode", id);
                });
            });
        }
    });

    $("#"+id_preview_tree).on("click", ".tag_selected_remove", function(event){
        event.stopImmediatePropagation();

        //recupera la preview del relativo tag
        var tag_selected = $(this).parents(".tag_selected").first();
        if(tag_selected.length){
            //recupera i dati di tag e albero
            var id_tag = tag_selected.attr("data-tagid");
            var id_tag_tree = tag_selected.attr("data-tagtree");
            var ref_tree = tag_selected.attr("data-reftree");

            //se ci sono tutti i dati
            if(id_tag && id_tag_tree && ref_tree){
                //identifica l'id dell'albero
                var id_input_tree = AreeInteresse.getIdTree(id_tag_tree, ref_tree);

                //rimuove il nodo
                $("#"+id_input_tree).treeview('uncheckNode' , id_tag);

                //identifica il limite di tag selezionabili per l'albero in esame, serve per
                //l'aggiornamento del numero di selezioni rimaste
                var limit_tag_tree = false;
                $.each(data_trees, function(index, data_tree){
                    var id_tree = data_tree['id'];
                    var limit_tree = data_tree['limit'];

                    if(id_tree == id_tag_tree){
                        limit_tag_tree = limit_tree;
                    }
                });

                //metodo comune per l'aggiornamento del totale delle selezioni
                AreeInteresse.updateTotale(id_tag_tree, limit_tag_tree, ref_tree);
            }
        }
    });

    $("#"+id_preview_tree).on("click", ".tag_selected", function(event){
        event.stopImmediatePropagation();

        //identifica il tag preview
        var tag_selected = $(this);

        //recupera i dati di tag e albero
        var id_tag = tag_selected.attr("data-tagid");
        var id_tag_tree = tag_selected.attr("data-tagtree");
        var ref_tree = tag_selected.attr("data-reftree");

        //se ci sono tutti i dati
        if(id_tag && id_tag_tree && ref_tree){
            //identifica l'id dell'albero
            var id_input_tree = AreeInteresse.getIdTree(id_tag_tree, ref_tree);

            //identifica il tag a cui scrollare
            var tagScrollTo = $("#"+id_input_tree+"-tree").find('[data-key="'+id_tag+'"]');
            if(tagScrollTo.length){
                //lancia la funzione ricorsiva che apre tutti i parent
                AreeInteresse.openNode(tagScrollTo);

                //altezza dell'header
                var headerHeight = $("#"+id_input_tree+"-wrapper").find('.kv-header-container').height();

                $("#"+id_input_tree+"-tree").animate({
                    scrollTop: (tagScrollTo[0].offsetTop - headerHeight)
                },'slow');
            }
        }
    });
};
AreeInteresse.getIdTree = function(id_tree, ref_tree){
    //identifica l'id dell'albero
    var id_input_tree = (ref_tree == "simple-choice" ? "simple-choice-treeview-"+id_tree : "advanced-choice-treeview-"+id_tree+"-"+ref_tree);

    return id_input_tree;
};
AreeInteresse.getIdPreviewTree = function(id_tree, ref_tree){
    return "preview_tag_tree_"+ref_tree+"_"+id_tree;
};
AreeInteresse.renderPreview = function(tagsData, id_tree, limit_tree, ref_tree){
    //identifica l'id della preview dell'albero
    var id_preview_tree = AreeInteresse.getIdPreviewTree(id_tree, ref_tree);

    //identifica il blocco di preview
    var preview_block = $('#'+id_preview_tree);

    //lo svuota
    preview_block.empty();

    //se ci sono elementi, li renderizza
    if(tagsData.length > 0){
        $.each(tagsData, function(index, tagData){
            var id_tag = tagData.id;
            var tag_tmp = "";
            tag_tmp += "<div class='tag_selected col-xs-12' data-tagid='"+id_tag+"' data-tagtree='"+id_tree+"' data-reftree='"+ref_tree+"'>";
            tag_tmp += "    <div class='tag_selected_part tag_selected_remove_container'>";
            tag_tmp += "        <div class='tag_selected_remove'>"+AreeInteresseVars.icon_remove_tag+"</div>";
            tag_tmp += "    </div>";
            tag_tmp += "    <div class='tag_selected_part'>";
            tag_tmp += "        <div class='tag_selected_label'>";
            tag_tmp += "            "+tagData.label;
            tag_tmp += "        </div>";
            tag_tmp += "        <div class='tag_selected_parents'>";
            tag_tmp += "            ";+AreeInteresse.getParentsString(id_tag, id_tree, ref_tree);
            tag_tmp += "        </div>";
            tag_tmp += "    </div>";
            tag_tmp += "</div>";

            //lo inserisce nella preview
            preview_block.append(tag_tmp);
        });
    }
    //altrimenti inserisce una label generale
    else{
        var label_no_tag = "";
        label_no_tag += "<span class='tree_no_tag'>";
        label_no_tag += "   "+AreeInteresseVars.no_tags_selected;
        label_no_tag += "</span>";
        preview_block.append(label_no_tag);
    }

    //metodo comune per l'aggiornamento del contatore dei nodi selezionati
    AreeInteresse.updateTotale(id_tree, limit_tree, ref_tree);
};
AreeInteresse.getParentsString = function(id_tag, id_tag_tree, ref_tree){
    //identifica l'id dell'albero
    var id_input_tree = AreeInteresse.getIdTree(id_tag_tree, ref_tree);

    //identifica il tag
    var currentTag = $("#"+id_input_tree+"-tree").find('[data-key="' + id_tag + '"]');

    //recupera ricorsivamente la label dei tag padri
    var tag_parents =  AreeInteresse.getParentsStringFromTag(currentTag);

    return tag_parents.join(" / ");
};
AreeInteresse.updateTotale = function(id_tree, limit_tree, ref_tree){
    //identifica il blocco che contiene il contatore
    var counter_block = $('#remaining_tag_tree_'+ref_tree+'_'+id_tree).find('.tree-remaining-tag-number');

    //se il limite non è infinito
    if(limit_tree){
        //identifica l'id dell'albero
        var id_input_tree = AreeInteresse.getIdTree(id_tree, ref_tree);

        //calcola il totale attuale
        var selectedNodes = $("#"+id_input_tree).val();

        //recupera il conteggio degli elementi
        var count_selected = 0;
        if(selectedNodes != ''){
            selectedNodes = selectedNodes.split(',');
            count_selected = selectedNodes.length;
        }

        counter_block.html((limit_tree - count_selected)+"/"+limit_tree);
    }
    else{
        counter_block.html(AreeInteresseVars.tags_unlimited);
    }
};
AreeInteresse.openNode = function(node){
    //recupera il nodo parent e se chiuso lo apre
    var parentNode = node.parents(".kv-parent").first();

    //procede solo se ha identificato il nodo padre
    if(parentNode.length){
        //se il nodo è chiuso
        if(parentNode.hasClass('kv-collapsed')){
            parentNode.find('.kv-node-toggle').first().trigger('click');
        }

        AreeInteresse.openNode(parentNode);
    }
};
AreeInteresse.getParentsStringFromTag = function(node){
    //array con i parents
    var parents = new Array();

    //recupera il nodo parent
    var parentNode = node.parents(".kv-parent").first();

    //procede solo se ha identificato il nodo padre
    if(parentNode.length){
        //salva la label
        parents.push(parentNode.find(".kv-node-label").html());

        //lancia ricorsivamente per identificare i nodi padre del padre
        var parent_parents = AreeInteresse.getParentsStringFromTag(parentNode);
        if(parent_parents.length){
            $.each(parent_parents, function(index, parent_parent){
                parents.unshift(parent_parent);
            });
        }
    }

    return parents;
};


