<div class="link-list-wrapper preference-user-menu">
    <ul class="link-list">
        <li class="myprofile-menu">
            <a class="list-item p-0" href="/preferenceuser/preference/settings" title="Gestione preferenze">
                <span>Gestione preferenze</span>
            </a>
        </li>        
        <li class="myprofile-menu">
            <a class="list-item p-0" href="/preferenceuser/preference/user-profile" title="Il mio profilo">
                <span>Il mio profilo</span>
            </a>
        </li>
        
        <li class="exit-menu">
            <a class="list-item p-0" href="/site/logout" title="Esci">
                <span>Esci
                <svg class="icon icon-tertiary right">
                    <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#exit-to-app"></use>
                </svg></span>
            </a>
        </li>
        <li class="delete-menu lightgrey-bg-c1">
            <a class="list-item p-0" href="javascript::void(0)" title="Elimina profilo" id="anchor-elimina-profilo-id" data-toggle="modal" data-target="#profile-delete-id">
                <span>Elimina profilo
                <svg class="icon icon-tertiary right">
                    <use xlink:href="<?= $currentAsset->baseUrl ?>/sprite/material-sprite.svg#trash-can-outline"></use>
                </svg></span>
            </a>
        </li>
    </ul>
</div>
