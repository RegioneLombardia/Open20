<?php

namespace common\modules\transformermanagers;

use open20\amos\admin\models\UserProfile;
use open20\amos\socialauth\models\SocialIdmUser;
use open20\elasticsearch\transformer\modeltransformers\BaseModelToElasticTransformer;

class UserMtoETransformer extends BaseModelToElasticTransformer {
    /* @var $model UserProfile */

    public function transform() {
        $model = $this->getModel();

        $values = [];
        /* @var $userProfile UserProfile */
        $userProfile = $model;
        $values['title'] = $this->filterString($userProfile->nome . " " . $userProfile->cognome);
        $values['titolo'] = $this->filterString($userProfile->nome . " " . $userProfile->cognome);
        $values['nome'] = $this->filterString($userProfile->nome);
        $values['cognome'] = $this->filterString($userProfile->cognome);
        $values['codice_fiscale'] = $this->filterString($userProfile->codice_fiscale);
        $socialIdmUser = SocialIdmUser::findOne([
            'user_id' => $userProfile->user_id,
        ]);
        $values['numeroMatricola'] = !is_null($socialIdmUser) ? $this->filterString($socialIdmUser->numeroMatricola): "";
        $values['email'] = $this->filterString($model->user->email);
        $values['descrizione'] = $this->filterString($userProfile->presentazione_breve);
        $values['public'] = 0;
        $values['start_publication'] = date("c", strtotime($model->created_at));
        $values['end_publication'] = date("c", strtotime($model->deleted_at));

        return $values;
    }

}
