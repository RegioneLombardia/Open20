<tr data-key="${id}">
    <td class="" title="${labelPublicationDate}" data-col-seq="0" data-toggle="modal" data-target="#modal-post-${id}">${publication_date}</td>
    <td class="" title="${labelText}" data-col-seq="1" data-toggle="modal" data-target="#modal-post-${id}">${text}</td>
    <td class="" title="${labelType}" data-col-seq="2" data-toggle="modal" data-target="#modal-post-${id}">${typeIcon}</td>
    <td class="" title="" data-col-seq="3" data-toggle="modal" data-target="#modal-post-${id}">${image}</td>
    <td class="action-buttons" title="" data-col-seq="4">
        <button type="button" class="btn btn-danger" onclick="removeSocialwallPostAction(${id})">x</button>
    </td>
</tr>