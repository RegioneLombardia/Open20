/**
 * @param obj DomObj della select2/dropdown kartik
 * @param set_enable Bool se true riabilita la tendina indicata con DomObj
 * Svuota la tendina ricevuta (figlia popolata in base al valore di quella padre)
 * ne salva l'eventuale valore selezionato ed imposta l'eventuale option default nell'html (tipo la x per cancellare il contenuto)
 *
 * NDR: Il comportamento del widget di kartik, in caso di nessun risultato popolante la tendina figlia,
 * è di disabilitare la tendina, lasciando però un valore indicato.
 */
function clearValueIfParentEmpty( obj, set_enable ){
    var placeholder_html = '';

    //ricavo l'option eventualmente selezionata
    //var selected_option = $(obj).find(':selected');
    var selected_default_option = $(obj).find('option[value=""]');
    //se ho un opzione default, imposto il suo contenuto html alla select
    if( selected_default_option.length > 0 ){
        placeholder_html = selected_default_option.html();
        $(obj).val('');
    }

    if(set_enable){
        $(obj).removeAttr('disabled');
    }
    //il campo contentente la label nel formato select2-#id_tendina#-container
    var obj_lbl_displaied = $('#select2-'+$(obj).attr('id')+'-container');
    var lbl_displaied = obj_lbl_displaied.html();
    //siccome la label è contenuta in un tag <span> che contiene un altro <span>,
    //salvo quest'ultimo per reimpostarlo successivamente
    var myregexp = /<.*?>.*?<\/.*?>/;
    var match = lbl_displaied.match(myregexp);

    /*console.log(match, "match");
    console.log(placeholder_html, "placeholder_html");*/
    if (match != null) {
        /*var old_content = match[0];

        if( placeholder_html.indexOf(old_content) > 0 ){
            old_content = '';
        }*/
        //console.log(old_content, "old_content");
        //cancello il contenuto
        obj_lbl_displaied.html('');
        //ripristino il tag <span> contenente la x per cancellare il contenuto + eventuale valore option selezionata
        obj_lbl_displaied.html(/*old_content+*/placeholder_html);
    }
}

/**
 * @param nazione_id ID record nazione
 * @param provincia_dom_id DOM id del campo provincia
 * @param comune_dom_id DOM id del campo comune
 * @param cap_dom_id DOM id del campo cap
 * Se la nazione è Italia (ID nazione 1) abilita eventuali select come provincia, comune e cap
 * altrimenti le disabilita
 */
function cleanSelectByNazione(nazione_id, provincia_dom_id, comune_dom_id, cap_dom_id ){
    if(nazione_id==1){
        $('#'+provincia_dom_id).removeAttr('disabled');

        removeHiddenObj(provincia_dom_id);
        removeHiddenObj(comune_dom_id);
        removeHiddenObj(cap_dom_id);

    }else{
        $('#'+provincia_dom_id).attr('disabled', true);
        //per refreshare in cascata i campi che dipendono dalla provincia
        $('#'+provincia_dom_id).select2('val', '');
        $('#'+provincia_dom_id).trigger('select2:select');
        //per refreshare il cap
        //$('#'+comune_dom_id).trigger('depdrop:change');

        createHiddenObj($('#'+provincia_dom_id).attr('name'), provincia_dom_id);
        createHiddenObj($('#'+comune_dom_id).attr('name'), comune_dom_id);
        createHiddenObj($('#'+cap_dom_id).attr('name'), cap_dom_id);
    }
}

/**
 * @param obj_name NAME del campo input di origine
 * @param obj_id ID del campo input di origine
 * @returns {boolean}
 * Dato l'attributo NAME e ID del campo di origine, genera un input hidden e lo accoda nel DOM DOPO il campo esistente
 */
function createHiddenObj(obj_name, obj_id){
    if(obj_name && obj_name.length > 0 && obj_id && obj_id.length > 0 ) {
        //genero un id per l'input hidden
        var obj_hidden_id = generatehiddenId(obj_id);

        var html_input = '<input type="hidden" id="'+obj_hidden_id+'" name="'+obj_name+'" value="">';

        $('#'+obj_id).after( html_input );
        return true;
    }

    return false;
}

/**
 * @param obj_id ID del campo d'origine da cui è stato creato quello hidden
 * @returns {boolean}
 * dato l'ID del campo di origine: elimina dal DOM il campo hidden
 */
function removeHiddenObj(obj_id){
    if( obj_id && obj_id.length > 0 ) {
        //genero un id per l'input hidden
        var obj_hidden_id = generatehiddenId(obj_id);

        $('#'+obj_hidden_id).remove();
    }

    return false;
}

/**
 * @param input_id ID del campo input d'origine
 * @returns String/false se striga generata o meno
 * dato l'ID del campo di origine: genera e ritorna un nuovo ID aggiungendo il suffisso '_hidden'
 */
function generatehiddenId(input_id){
    if(input_id && input_id.length > 0){
        return input_id+'_hidden';
    }

    return false;
}