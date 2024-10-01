<?php

use open20\amos\admin\models\UserProfile;
use luya\admin\models\ApiUser;
use open20\design\Module;
use yii\web\View;

$this->registerJs(
    "
                $(function() {
    $('a[data-toggle=\"tab\"]').on('shown.bs.tab', function (e) {
        $('#tabid').val($(this).attr('id'));
    });
});",
    View::POS_END
);

$nome = "";
if (!empty(\Yii::$app->request->queryParams["User"]["nome"])) {
    $nome = \Yii::$app->request->queryParams["User"]["nome"];
}
$cognome = "";
if (!empty(\Yii::$app->request->queryParams["User"]["cognome"])) {
    $cognome = \Yii::$app->request->queryParams["User"]["cognome"];
}
$email = "";
if (!empty(\Yii::$app->request->queryParams["User"]["email"])) {
    $email = \Yii::$app->request->queryParams["User"]["email"];
}

$codice_fiscale = "";
if (!empty(\Yii::$app->request->queryParams["User"]["codice_fiscale"])) {
    $codice_fiscale = \Yii::$app->request->queryParams["User"]["codice_fiscale"];
}
$numeroMatricola = "";
if (!empty(\Yii::$app->request->queryParams["User"]["numeroMatricola"])) {
    $numeroMatricola = \Yii::$app->request->queryParams["User"]["numeroMatricola"];
}

$NavItem_title = "";
if (!empty(\Yii::$app->request->queryParams["NavItem"]["title"])) {
    $NavItem_title = \Yii::$app->request->queryParams["NavItem"]["title"];
}
$NavItem_elasticSourceText = "";
if (!empty(\Yii::$app->request->queryParams["NavItem"]["elasticSourceText"])) {
    $NavItem_elasticSourceText = \Yii::$app->request->queryParams["NavItem"]["elasticSourceText"];
}

$NavItem_status = "";
if (!empty(\Yii::$app->request->queryParams["NavItem"]["status"])) {
    $NavItem_status = \Yii::$app->request->queryParams["NavItem"]["status"];
}

$News_title = "";
if (!empty(\Yii::$app->request->queryParams["News"]["title"])) {
    $News_title = \Yii::$app->request->queryParams["News"]["title"];
}
$News_descrizione_breve = "";
if (!empty(\Yii::$app->request->queryParams["News"]["descrizione_breve"])) {
    $News_descrizione_breve = \Yii::$app->request->queryParams["News"]["descrizione_breve"];
}
$News_descrizione = "";
if (!empty(\Yii::$app->request->queryParams["News"]["descrizione"])) {
    $News_descrizione = \Yii::$app->request->queryParams["News"]["descrizione"];
}
$News_start_publication = "";
if (!empty(\Yii::$app->request->queryParams["News"]["start_publication"])) {
    $News_start_publication = \Yii::$app->request->queryParams["News"]["start_publication"];
}

$Image_filename = "";
if (!empty(\Yii::$app->request->queryParams["Image"]["filename"])) {
    $Image_filename = \Yii::$app->request->queryParams["Image"]["filename"];
}
$Image_type = "";
if (!empty(\Yii::$app->request->queryParams["Image"]["type"])) {
    $Image_type = \Yii::$app->request->queryParams["Image"]["type"];
}
$Attach_filename = "";
if (!empty(\Yii::$app->request->queryParams["Attach"]["filename"])) {
    $Attach_filename = \Yii::$app->request->queryParams["Attach"]["filename"];
}
$Attach_type = "";
if (!empty(\Yii::$app->request->queryParams["Attach"]["type"])) {
    $Attach_type = \Yii::$app->request->queryParams["Attach"]["type"];
}

$Tab_id = "navSearch_Libera-tab";
if (!empty(\Yii::$app->request->queryParams["Tab"]["id"])) {
    $Tab_id = \Yii::$app->request->queryParams["Tab"]["id"];
}

$users = ApiUser::find()->all();
$options =  "";
$user_id = !empty(\Yii::$app->request->queryParams["NavItem"]["user"]) ? \Yii::$app->request->queryParams["NavItem"]["user"] : "";
foreach ($users as $user) {
    $options .=  "<option value=\"" . $user->id . "\" ". ($user_id == $user->id ? "selected" : "") ." >" . $user->firstname . " " . $user->lastname . "</option>";
}

$users = UserProfile::find()->all();
$optionsUsersAmos =  "";
$user_id = !empty(\Yii::$app->request->queryParams["News"]["user"]) ? \Yii::$app->request->queryParams["News"]["user"] : "";
foreach ($users as $user) {
    /*@var $user UserProfile*/
    $optionsUsersAmos .=  "<option value=\"" . $user->user_id . "\" ". ($user_id == $user->user_id ? "selected" : "") .">" . $user->nome . " " . $user->cognome . "</option>";
}

?>



<div class="row">
    <div class="col-12">
        <div class="nav nav-tabs nav-tabs-icon-text" id="navSearch" role="tablist" aria-orientation="vertical">
            <a class="nav-link <?= $Tab_id == "navSearch_Libera-tab" ? "active" : "" ?>" id="navSearch_Libera-tab" data-toggle="tab" href="#navSearch_Libera" role="tab" aria-controls="navSearch_Libera" aria-selected="<?= $Tab_id == "navSearch_Libera-tab" ? "true" : "false" ?>"><span class="mr-2 mdi mdi-magnify"></span><?= Module::t('amosdesign', 'Ricerca libera') ?></a>
            <a class="nav-link <?= $Tab_id == "navSearch_Operatori-tab" ? "active" : "" ?>" id="navSearch_Operatori-tab" data-toggle="tab" href="#navSearch_Operatori" role="tab" aria-controls="navSearch_Operatori" aria-selected="<?= $Tab_id == "navSearch_Operatori-tab" ? "true" : "false" ?>"><span class="mr-2 mdi mdi-account-group"></span><?= Module::t('amosdesign', 'Operatori') ?></a>
            <a class="nav-link <?= $Tab_id == "navSearch_Pagine-tab" ? "active" : "" ?>" id="navSearch_Pagine-tab" data-toggle="tab" href="#navSearch_Pagine" role="tab" aria-controls="navSearch_Pagine" aria-selected="<?= $Tab_id == "navSearch_Pagine-tab" ? "true" : "false" ?>"><span class="mr-2 mdi mdi-file-document"></span><?= Module::t('amosdesign', 'Pagine') ?></a>
            <a class="nav-link <?= $Tab_id == "navSearch_Notizie-tab" ? "active" : "" ?>" id="navSearch_Notizie-tab" data-toggle="tab" href="#navSearch_Notizie" role="tab" aria-controls="navSearch_Notizie" aria-selected="<?= $Tab_id == "navSearch_Notizie-tab" ? "true" : "false" ?>"><span class="mr-2 mdi mdi-newspaper"></span><?= Module::t('amosdesign', 'Notizie') ?></a>
            <a class="nav-link <?= $Tab_id == "navSearch_Immagini-tab" ? "active" : "" ?>" id="navSearch_Immagini-tab" data-toggle="tab" href="#navSearch_Immagini" role="tab" aria-controls="navSearch_Immagini" aria-selected="<?= $Tab_id == "navSearch_Immagini-tab" ? "true" : "false" ?>"><span class="mr-2 mdi mdi-image"></span><?= Module::t('amosdesign', 'Immagini') ?></a>
            <a class="nav-link <?= $Tab_id == "navSearch_Allegati-tab" ? "active" : "" ?>" id="navSearch_Allegati-tab" data-toggle="tab" href="#navSearch_Allegati" role="tab" aria-controls="navSearch_Allegati" aria-selected="<?= $Tab_id == "navSearch_Allegati-tab" ? "true" : "false" ?>"><span class="mr-2 mdi mdi-paperclip"></span><?= Module::t('amosdesign', 'Allegati') ?></a>
            <input type="hidden" class="form-control" id="tabid" name="Tab[id]" value="<?= $Tab_id ?>">
        </div>
    </div>
    <div class="col-12">
        <div class="tab-content" id="navSearchContent">
            <div class="tab-pane py-3 fade <?= $Tab_id == "navSearch_Libera-tab" ? "show active" : "" ?>" id="navSearch_Libera" role="tabpanel" aria-labelledby="navSearch_Libera-tab">
                <div class="row">
                    <div class="col-12">
                        <?=
                        $this->render(
                            '@vendor/open20/design/src/components/bootstrapitalia/views/bi-form-input-search',
                            [
                                'name' => 'searchtext',
                                'inputId' => 'SearchDesign',
                                'type' => 'search',
                                'value' => \app\modules\cms\helpers\CmsHelper::itemQueryStringValue('searchtext')
                            ]
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane pt-3 fade <?= $Tab_id == "navSearch_Operatori-tab" ? "show active" : "" ?>" id="navSearch_Operatori" role="tabpanel" aria-labelledby="navSearch_Operatori-tab">
                <div class="row pt-4">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="exampleInputText">Nome</label>
                            <input type="text" class="form-control" id="exampleInputText" name="User[nome]" value="<?= $nome ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="exampleInputText">Cognome</label>
                            <input type="text" class="form-control" id="exampleInputText" name="User[cognome]" value="<?= $cognome ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="exampleInputText">Email</label>
                            <input type="text" class="form-control" id="exampleInputText" name="User[email]" value="<?= $email ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="exampleInputText">Codice Fiscale</label>
                            <input type="text" class="form-control" id="exampleInputText" name="User[codice_fiscale]" value="<?= $codice_fiscale ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label for="exampleInputText">Numero Matricola</label>
                            <input type="text" class="form-control" id="exampleInputText" name="User[numeroMatricola]" value="<?= $numeroMatricola ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="active" for="dateStandard">Data inizio</label>
                            <input type="date" id="dateStandard" name="dateStandard">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="active" for="dateStandard">Data fine</label>
                            <input type="date" id="dateStandard" name="dateStandard">
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button class="btn btn-outline-primary" onclick="window.location.href = (window.location.href.split('?')[0]);return false;">Annulla</button>
                        <button class="btn btn-primary">Cerca</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane pt-3 fade <?= $Tab_id == "navSearch_Pagine-tab" ? "show active" : "" ?>" id="navSearch_Pagine" role="tabpanel" aria-labelledby="navSearch_Pagine-tab">
                <div class="row pt-4">
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="exampleInputText">Titolo</label>
                            <input type="text" class="form-control" id="exampleInputText" name="NavItem[title]" value="<?= $NavItem_title ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="bootstrap-select-wrapper">
                            <label for="defaultSelect">Stato</label>
                            <select id="defaultSelect" name="NavItem[status]">
                                <option selected="" value="">Scegli un'opzione</option>
                                <option value="1">Bozza</option>
                                <option value="0">Pubblicato</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label for="exampleInputText">Contenuto interno</label>
                            <input type="text" class="form-control" id="exampleInputText" name="NavItem[elasticSourceText]" value="<?= $NavItem_elasticSourceText ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="active" for="dateStandard">Pubblicato il</label>
                            <input type="date" id="dateStandard" name="dateStandard">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="active" for="dateStandard">Modificato il</label>
                            <input type="date" id="dateStandard" name="dateStandard">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="bootstrap-select-wrapper">
                            <label for="selectExample4">Autore</label>
                            <select id="selectExample4" title="Scegli un'opzione" data-live-search="true" data-live-search-placeholder="Cerca opzioni" name="NavItem[user]">
                                <?= $options; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Tag liberi</label>
                            <textarea id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button class="btn btn-outline-primary" onclick="window.location.href = (window.location.href.split('?')[0]);return false;">Annulla</button>
                        <button class="btn btn-primary">Cerca</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane pt-3 fade <?= $Tab_id == "navSearch_Notizie-tab" ? "show active" : "" ?>" id="navSearch_Notizie" role="tabpanel" aria-labelledby="navSearch_Notizie-tab">
                <div class="row pt-4">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="exampleInputText">Titolo</label>
                            <input type="text" class="form-control" id="exampleInputText" name="News[title]" value="<?= $News_title ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="exampleInputText">Sottotitolo</label>
                            <input type="text" class="form-control" id="exampleInputText" name="News[descrizione_breve]" value="<?= $News_descrizione_breve ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label for="exampleInputText">Testo della notizia</label>
                            <input type="text" class="form-control" id="exampleInputText" name="News[descrizione]" value="<?= $News_descrizione ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-group">
                            <label class="active" for="dateStandard">Pubblicato il</label>
                            <input type="date" id="dateStandard" name="dateStandard" name="News[start_publication]" value="<?= $News_start_publication ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="bootstrap-select-wrapper">
                            <label for="selectExample4">Creato da</label>
                            <select id="selectExample4" title="Scegli un'opzione" data-live-search="true" data-live-search-placeholder="Cerca opzioni" name="News[user]">
                                <?= $optionsUsersAmos; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Tag liberi</label>
                            <textarea id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button class="btn btn-outline-primary" onclick="window.location.href = (window.location.href.split('?')[0]);return false;">Annulla</button>
                        <button class="btn btn-primary">Cerca</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane pt-3 fade <?= $Tab_id == "navSearch_Immagini-tab" ? "show active" : "" ?>" id="navSearch_Immagini" role="tabpanel" aria-labelledby="navSearch_Immagini-tab">
                <div class="row pt-4">
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="exampleInputText">Nome del file</label>
                            <input type="text" class="form-control" id="exampleInputText" name="Image[filename]" value="<?= $Image_filename ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="bootstrap-select-wrapper">
                            <label for="selectExample4">Estensione del file</label>
                            <select id="selectExample4" title="Scegli un'opzione" data-live-search="true" data-live-search-placeholder="Cerca opzioni" name="Image[type]">
                                <option value="png" <?= $Image_type == "png" ? "selected" : "" ?>>PNG</option>
                                <option value="jpg" <?= $Image_type == "jpg" ? "selected" : "" ?>>JPG</option>
                                <option value="jpeg" <?= $Image_type == "jpeg" ? "selected" : "" ?>>JPEG</option>
                                <option value="svg" <?= $Image_type == "svg" ? "selected" : "" ?>>SVG</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label for="exampleInputText">Testo alternativo</label>
                            <input type="text" class="form-control" id="exampleInputText">
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Tag liberi</label>
                            <textarea id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button class="btn btn-outline-primary" onclick="window.location.href = (window.location.href.split('?')[0]);return false;">Annulla</button>
                        <button class="btn btn-primary">Cerca</button>
                    </div>
                </div>
            </div>
            <div class="tab-pane pt-3 fade <?= $Tab_id == "navSearch_Allegati-tab" ? "show active" : "" ?>" id="navSearch_Allegati" role="tabpanel" aria-labelledby="navSearch_Allegati-tab">
                <div class="row pt-4">
                    <div class="col-12 col-md-8">
                        <div class="form-group">
                            <label for="exampleInputText">Nome del file</label>
                            <input type="text" class="form-control" id="exampleInputText" name="Attach[filename]" value="<?= $Attach_filename ?>">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="bootstrap-select-wrapper">
                            <label for="selectExample4">Estensione del file</label>
                            <select id="selectExample4" title="Scegli un'opzione" data-live-search="true" data-live-search-placeholder="Cerca opzioni" name="Attach[type]">
                                <option value="doc" <?= $Attach_type == "doc" ? "selected" : "" ?>>DOC</option>
                                <option value="docx" <?= $Attach_type == "docx" ? "selected" : "" ?>>DOCX</option>
                                <option value="pdf" <?= $Attach_type == "pdf" ? "selected" : "" ?>>PDF</option>
                                <option value="xls" <?= $Attach_type == "xls" ? "selected" : "" ?>>XLS</option>
                                <option value="xlsx" <?= $Attach_type == "xlsx" ? "selected" : "" ?>>XLSX</option>
                                <option value="txt" <?= $Attach_type == "txt" ? "selected" : "" ?>>TXT</option>
                                <option value="zip" <?= $Attach_type == "zip" ? "selected" : "" ?>>ZIP</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-md-12">
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Tag liberi</label>
                            <textarea id="exampleFormControlTextarea1" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="col-12 text-right">
                        <button class="btn btn-outline-primary" onclick="window.location.href = (window.location.href.split('?')[0]);return false;">Annulla</button>
                        <button class="btn btn-primary">Cerca</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>