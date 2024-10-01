<?php

namespace open20\gamification\models;

use open20\amos\admin\models\UserContact;
use open20\amos\admin\models\UserProfile;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\community\models\Community;
use open20\amos\community\models\CommunityUserMm;
use open20\amos\core\user\User;
use open20\amos\discussioni\models\DiscussioniTopic;
use open20\amos\documenti\models\Documenti;
use open20\amos\news\models\News;
use open20\amos\seo\behaviors\SeoContentBehavior;
use open20\amos\seo\interfaces\SeoModelInterface;
use open20\amos\sondaggi\models\SondaggiRisposte;
use open20\amos\sondaggi\models\SondaggiRisposteSessioni;
use open20\gamification\Module;
use Yii;
use open20\amos\comments\models\Comment;
use open20\amos\comments\models\CommentReply;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class Gamification
 */
class Gamification extends base\Gamification implements SeoModelInterface
{
    /**
     * Adding the file behavior
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
            'SeoContentBehavior' => [
                'class' => SeoContentBehavior::class,
                'titleAttribute' => 'nome',
                'descriptionAttribute' => 'descrizione',
                'imageAttribute' => 'copertina',
                'defaultOgType' => 'article',
                'schema' => 'NewsArticle'
            ]
        ]);
    }

    public function getSchema()
    {
        return null;
    }

    public function representingColumn()
    {
        return [
            //inserire il campo o i campi rappresentativi del modulo
        ];
    }

    public function attributeHints()
    {
        return [
        ];
    }

    /**
     * Returns the text hint for the specified attribute.
     * @param string $attribute the attribute name
     * @return string the attribute hint
     */
    public function getAttributeHint($attribute)
    {
        $hints = $this->attributeHints();
        return isset($hints[$attribute]) ? $hints[$attribute] : null;
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
        ]);
    }

    public function attributeLabels()
    {
        return
            ArrayHelper::merge(
                parent::attributeLabels(),
                [
                ]);
    }

    public static function getEditFields()
    {
        $labels = self::attributeLabels();

        return [
            [
                'slug' => 'titolo',
                'label' => $labels['Titolo'],
                'type' => 'string'
            ],
            [
                'slug' => 'descrizione',
                'label' => $labels['Descrizione'],
                'type' => 'string'
            ],
            [
                'slug' => 'starting_date',
                'label' => $labels["Data d'inizio"],
                'type' => 'integer'
            ],
            [
                'slug' => 'finish_date',
                'label' => $labels['Data di fine'],
                'type' => 'integer'
            ],
            [
                'slug' => 'utente_iscrizione',
                'label' => $labels['Punti assegnati all’iscrizione alla piattaforma'],
                'type' => 'integer'
            ],
            [
                'slug' => 'utente_foto',
                'label' => $labels['Punti assegnati al caricamento di un’immagine nel profilo'],
                'type' => 'integer'
            ],
            [
                'slug' => 'utente_colleg',
                'label' => $labels['Punti assegnati al collegamento con utente'],
                'type' => 'integer'
            ],
            [
                'slug' => 'community_creazione',
                'label' => $labels['Punti assegnati alla creazione di una community'],
                'type' => 'integer'
            ],
            [
                'slug' => 'community_iscrizione',
                'label' => $labels['Punti assegnati all’iscrizione di una community'],
                'type' => 'integer'
            ],
            [
                'slug' => 'notizie_creazione',
                'label' => $labels['Punti assegnati alla creazione di una notizia in piattaforma o community'],
                'type' => 'integer'
            ],
            [
                'slug' => 'notizie_commento',
                'label' => $labels['Punti assegnati per ogni commento ad una notizia in piattaforma o community'],
                'type' => 'integer'
            ],
            [
                'slug' => 'notizie_like',
                'label' => $labels['Punti assegnati per ogni Like ad una notizia in piattaforma o community'],
                'type' => 'integer'
            ],
            [
                'slug' => 'documenti_creazione',
                'label' => $labels['Punti assegnati alla creazione di un documento in piattaforma o community'],
                'type' => 'integer'
            ],
            [
                'slug' => 'discussioni_creazione',
                'label' => $labels['Punti assegnati alla creazione di una discussione in piattaforma o community'],
                'type' => 'integer'
            ],
            [
                'slug' => 'discussioni_commento',
                'label' => $labels['Punti assegnati per ogni commento ad una discussione in piattaforma o community'],
                'type' => 'integer'
            ],
            [
                'slug' => 'discussioni_like',
                'label' => $labels['Punti assegnati per ogni Like ad una discussione in piattaforma o community'],
                'type' => 'integer'
            ],
            [
                'slug' => 'sondaggi_partec',
                'label' => $labels['Punti assegnati per ogni partecipazione ad un sondaggio'],
                'type' => 'integer'
            ],
        ];
    }

    public function getCreatedBy($id)
    {
        $video = Gamification::findOne(['id' => $id]);
        $user = UserProfile::findOne($video->created_by);
        return $user->nomeCognome;
    }

    public function getUpdatedBy($id)
    {
        $video = Gamification::findOne(['id' => $id]);
        $user = UserProfile::findOne($video->updated_by);
        return $user->nomeCognome;
    }

    public function getDeletedBy($id)
    {
        $video = Gamification::findOne(['id' => $id]);
        $user = UserProfile::findOne($video->deleted_by);
        return $user->nomeCognome;
    }

    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        if (!empty($this->usePrettyUrl) && ($this->usePrettyUrl == true) && $this->hasMethod('getPrettyUrl')) {
            return 'gamification/gamification';
        }
        return "gamification/gamification/view";
    }

    /**
     * @return string The url to view of this model
     */
    public function getFullViewUrl()
    {
        if (!empty($this->usePrettyUrl) && ($this->usePrettyUrl == true)) {
            return Url::toRoute(["/" . $this->getViewUrl() . "/" . $this->id . "/".$this->getPrettyUrl()]);
        } else if (!empty($this->useFrontendView) && ($this->useFrontendView == true) && method_exists($this,
                'getBackendobjectsUrl')) {
            return $this->getBackendobjectsUrl();
        } else {
            return Url::toRoute(["/" . $this->getViewUrl(), "id" => $this->id]);
        }
    }

    public function aggiornaOpeningToConcludedPerData($id)
    {
        $model = Gamification::findOne(['id' => $id]);

        if ($model->status == Gamification::GAMIFICATION_WORKFLOW_STATUS_OPENING) {
            if (strtotime($model->finish_date) <= strtotime("today")) {
                $model->status = Gamification::GAMIFICATION_WORKFLOW_STATUS_CONCLUDED;
            }
        }

        $model->save(false);
    }

    public function aggiornaTabellaPunteggiTotUsers($id){
        $allPunteggi = PunteggiUtenti::find()->select(['id', 'content_id', 'user_profile_id', 'attivita', 'nome', 'cognome', 'punteggio', 'email'])
            ->andWhere(['gamification_id' => $id])
            ->asArray()->all();

        $arrayTabella = [];

        foreach ($allPunteggi as $punteggio) {
            $key = $punteggio['content_id'] . '-' . $punteggio['attivita'] . '-' . $punteggio['id'];
            if(!array_key_exists($key, $arrayTabella)){
                $arrayTabella[$key] = [
                    'user_profile_id' => $punteggio['user_profile_id'],
                    'nome_cognome' => $punteggio['nome'] . ' ' . $punteggio['cognome'],
                    'totale' => 0
                ];

            }
            $arrayTabella[$key]['totale'] += $punteggio['punteggio'];
        }
        PunteggiTotUtenti::deleteAll(['gamification_id' => $id]);

        $models = [];
        foreach ($arrayTabella as $key => $dati) {
            $keyModels = $dati['user_profile_id'];
            $user_id = UserProfile::findOne(['id' => $dati['user_profile_id']]);
            list($content_id, $attivita, $keyId) = explode('-', $key);
            if(!array_key_exists($keyModels, $models)) {
                $model = new PunteggiTotUtenti;
                $model->nome_cognome = $dati['nome_cognome'];
                $models[$keyModels] = $model;
            }
            $models[$keyModels]->gamification_id = $id;
            $nomeCampo = PunteggiUtenti::ARRAY_CAMPI[$attivita][1];
            $models[$keyModels]->$nomeCampo += $dati['totale'];
            $models[$keyModels]->user_id = $user_id->user_id;
            $models[$keyModels]->totale += $dati['totale'];
        }

        foreach ($models as $model) {
            $model->save();
        }
    }

    public function punteggioIscizioneEFotoUtente($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = UserProfile::find()
            ->andWhere(['status' => 'UserProfileWorkflow/VALIDATED']);

        if (isset($user_id)) {
            $query->andWhere(['user_id' => $user_id]);
        }

        $allUserProfiles = $query->all();

        foreach ($allUserProfiles as $userProfile) {
            $user = User::findOne(['id' => $userProfile->user_id]);

            
            if (self::excludeUserFromGamification($user) ||  ($userProfile->isDeactivated() )/* || !(($model->starting_date < $userProfile->created_at) && ($userProfile->created_at < $model->finish_date))*/) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $query1 = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $userProfile->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 1]);

            $count1 = $query1->count();

            if ($count1 == 0) {
                $recordPunteggio1 = new PunteggiUtenti();
            } else {
                $recordPunteggio1 = $query1->one();
            }

            $recordPunteggio1->gamification_id = $id;
            $recordPunteggio1->content_id = $userProfile->id;
            $recordPunteggio1->datetime_attivita = $user->created_at;
            $recordPunteggio1->user_profile_id = $userProfile->id;
            $recordPunteggio1->username = $user->username;
            $recordPunteggio1->email = $user->email;
            $recordPunteggio1->nome = $userProfile->nome;
            $recordPunteggio1->cognome = $userProfile->cognome;
            $recordPunteggio1->attivita = 1;
            $recordPunteggio1->punteggio = $model->utente_iscrizione;
            $recordPunteggio1->save(false);
        }

        foreach ($allUserProfiles as $userProfile) {
            $user = User::findOne(['id' => $userProfile->user_id]);

            
            if (self::excludeUserFromGamification($user) || ($userProfile->isDeactivated())){
                continue;
            }

            if (!empty($userProfile->userProfileImage) && $userProfile->userProfileImage->name != 'defaultProfilo') {
                $query2 = PunteggiUtenti::find()
                    ->andWhere(['gamification_id' => $id])
                    ->andWhere(['content_id' => $userProfile->id])
                    ->andWhere(['email' => $user->email])
                    ->andWhere(['username' => $user->username])
                    ->andWhere(['attivita' => 2]);

                $count2 = $query2->count();

                if ($count2 == 0) {
                    $recordPunteggio2 = new PunteggiUtenti();
                } else {
                    $recordPunteggio2 = $query2->one();
                }

                $recordPunteggio2->gamification_id = $id;
                $recordPunteggio2->content_id = $userProfile->id;
                $recordPunteggio2->datetime_attivita = $user->created_at;
                $recordPunteggio2->user_profile_id = $userProfile->id;
                $recordPunteggio2->username = $user->username;
                $recordPunteggio2->email = $user->email;
                $recordPunteggio2->nome = $userProfile->nome;
                $recordPunteggio2->cognome = $userProfile->cognome;
                $recordPunteggio2->attivita = 2;
                $recordPunteggio2->punteggio = $model->utente_foto;
                $recordPunteggio2->save(false);
            }
        }
    }

    public function punteggioCollegamentoUtente($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = UserContact::find()
            ->andWhere(['status' => 'ACCEPTED']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }
        $allUserProfilesContact = $query->all();

        foreach ($allUserProfilesContact as $userProfileContact) {
            $user1 = User::findOne(['id' => $userProfileContact->user_id]);
            $user2 = User::findOne(['id' => $userProfileContact->contact_id]);

            if (!self::excludeUserFromGamification($user1)) {
                if (($model->starting_date < $userProfileContact->accepted_at) && ($userProfileContact->accepted_at < $model->finish_date)) {
                    $userProfile1 = UserProfile::findOne(['user_id' => $user1->id]);
                    var_dump($user1->id);
                    $query1 = PunteggiUtenti::find()
                        ->andWhere(['gamification_id' => $id])
                        ->andWhere(['content_id' => $userProfile1->id])
                        ->andWhere(['email' => $user1->email])
                        ->andWhere(['username' => $user1->username])
                        ->andWhere(['attivita' => 3]);
                    $count1 = $query1->count();
                    if ($count1 == 0) {
                        $recordPunteggio1 = new PunteggiUtenti();
                    } else {
                        $recordPunteggio1 = $query1->one();
                    }
                    $recordPunteggio1->gamification_id = $id;
                    $recordPunteggio1->content_id = $userProfile1->id;
                    $recordPunteggio1->datetime_attivita = $userProfileContact->created_at;
                    $recordPunteggio1->user_profile_id = $userProfile1->id;
                    $recordPunteggio1->username = $user1->username;
                    $recordPunteggio1->email = $user1->email;
                    $recordPunteggio1->nome = $userProfile1->nome;
                    $recordPunteggio1->cognome = $userProfile1->cognome;
                    $recordPunteggio1->attivita = 3;
                    $recordPunteggio1->punteggio = $model->utente_colleg;
                    $recordPunteggio1->save(false);
                }
            }
            if (!self::excludeUserFromGamification($user2)) {
                if (($model->starting_date < $userProfileContact->accepted_at) && ($userProfileContact->accepted_at < $model->finish_date)) {
                    $userProfile2 = UserProfile::findOne(['user_id' => $user2->id]);
                    var_dump($user2->id);
                    $query2 = PunteggiUtenti::find()
                        ->andWhere(['gamification_id' => $id])
                        ->andWhere(['content_id' => $userProfile2->id])
                        ->andWhere(['email' => $user2->email])
                        ->andWhere(['username' => $user2->username])
                        ->andWhere(['attivita' => 3])
                        ->andWhere(['datetime_attivita' => $userProfileContact->accepted_at]);

                    $count2 = $query2->count();

                    if ($count2 == 0) {
                        $recordPunteggio2 = new PunteggiUtenti();
                    } else {
                        $recordPunteggio2 = $query2->one();
                    }

                    $recordPunteggio2->gamification_id = $id;
                    $recordPunteggio2->content_id = $userProfile2->id;
                    $recordPunteggio2->datetime_attivita = $userProfileContact->created_at;
                    $recordPunteggio2->user_profile_id = $userProfile2->id;
                    $recordPunteggio2->username = $user2->username;
                    $recordPunteggio2->email = $user2->email;
                    $recordPunteggio2->nome = $userProfile2->nome;
                    $recordPunteggio2->cognome = $userProfile2->cognome;
                    $recordPunteggio2->attivita = 3;
                    $recordPunteggio2->punteggio = $model->utente_colleg;
                    $recordPunteggio2->save(false);
                }
            }
        }
    }

    public function punteggioCreazioneCommunity($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = Community::find()
            ->andWhere(['status' => 'CommunityWorkflow/VALIDATED'])
            ->orderBy(['created_by' => 'DESC']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allCommunity = $query->all();

        foreach ($allCommunity as $community) {
            $user = User::findOne(['id' => $community->created_by]);

            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $community->created_at) && ($community->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $community->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 4]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $community->id;
                $recordPunteggio->datetime_attivita = $community->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 4;
            }

            $recordPunteggio->punteggio = $model->community_creazione;
            $recordPunteggio->save(false);
        }
    }

    public function punteggioIscrizioneCommunity($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = CommunityUserMm::find()
            ->andWhere(['status' => 'ACTIVE'])
            ->andWhere([
                'OR',
                ['role' => 'COMMUNITY_MANAGER'],
                ['role' => 'PARTICIPANT'],
                ['role' => 'EVENT_MANAGER'],
            ])
            ->orderBy(['created_by' => 'DESC']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allCommunityUserMm = $query->all();

        foreach ($allCommunityUserMm as $community) {
            $user = User::findOne(['id' => $community->created_by]);

            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $community->created_at) && ($community->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $community->community_id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 5]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $community->community_id;
                $recordPunteggio->datetime_attivita = $community->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 5;
            }

            $recordPunteggio->punteggio = $model->community_iscrizione;
            $recordPunteggio->save(false);
        }
    }

    public function punteggioCreazioneNews($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = News::find()
            ->andWhere(['status' => 'NewsWorkflow/VALIDATO'])
            ->orderBy(['created_by' => 'DESC']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allNews = $query->all();

        foreach ($allNews as $news) {
            $user = User::findOne(['id' => $news->created_by]);

            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $news->created_at) && ($news->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $news->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 6]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $news->id;
                $recordPunteggio->datetime_attivita = $news->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 6;
            }
            $recordPunteggio->punteggio = $model->notizie_creazione;
            $recordPunteggio->save(false);
        }
    }

    public function punteggioCommentiNews($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = Comment::find()
            ->andWhere(['context' => 'open20\amos\news\models\News'])
            ->orderBy(['created_by' => 'DESC']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allCommenti = $query->all();

        foreach ($allCommenti as $commento) {
            $user = User::findOne(['id' => $commento->created_by]);

            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $commento->created_at) && ($commento->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $commento->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 7]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $commento->id;
                $recordPunteggio->datetime_attivita = $commento->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 7;
            }
            $recordPunteggio->punteggio = $model->notizie_commento;
            $recordPunteggio->save(false);
        }

        $query = CommentReply::find()
            ->orderBy(['created_by' => 'DESC']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allCommentiReply = $query->all();

        foreach ($allCommentiReply as $commento) {
            $user = User::findOne(['id' => $commento->created_by]);

            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $commento->created_at) && ($commento->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $commentContext = Comment::findOne(['id' => $commento->comment_id]);
            if(!($commentContext->context == 'open20\amos\news\models\News')){
                continue;
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $commento->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 7]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $commento->id;
                $recordPunteggio->datetime_attivita = $commento->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 7;
            }
            $recordPunteggio->punteggio = $model->notizie_commento;
            $recordPunteggio->save(false);
        }
    }

    public function punteggioLikeNotizie($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = \open20\amos\core\models\ContentLikes::find()
            ->andWhere(['models_classname_id' => '1']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allLike = $query->all();

        foreach ($allLike as $like) {
            $user = User::findOne(['id' => $like->created_by]);

            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $like->created_at) && ($like->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $like->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 8]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $like->id;
                $recordPunteggio->datetime_attivita = $like->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 8;
            }

            $recordPunteggio->punteggio = $model->notizie_like;
            $recordPunteggio->save(false);
        }
    }

    public function punteggioCreazioneDocumenti($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = Documenti::find()
            ->andWhere(['status' => 'DocumentiWorkflow/VALIDATO']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allDocumenti = $query->all();

        foreach ($allDocumenti as $documenti) {
            $user = User::findOne(['id' => $documenti->created_by]);
            
            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $documenti->created_at) && ($documenti->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $documenti->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 9]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $documenti->id;
                $recordPunteggio->datetime_attivita = $documenti->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 9;
            }

            $recordPunteggio->punteggio = $model->documenti_creazione;
            $recordPunteggio->save(false);
        }
    }

    public function punteggioCreazioneDiscussioni($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = DiscussioniTopic::find()
            ->andWhere(['status' => 'DiscussioniTopicWorkflow/ATTIVA']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allDiscussioni = $query->all();

        foreach ($allDiscussioni as $discussione) {
            $user = User::findOne(['id' => $discussione->created_by]);
            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $discussione->created_at) && ($discussione->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }



            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $discussione->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 10]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $discussione->id;
                $recordPunteggio->datetime_attivita = $discussione->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 10;
            }

            $recordPunteggio->punteggio = $model->discussioni_creazione;
            $recordPunteggio->save(false);
        }
    }

    public function punteggioCommentiDiscussioni($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = Comment::find()
            ->andWhere(['context' => 'open20\amos\discussioni\models\DiscussioniTopic'])
            ->orderBy(['created_by' => 'DESC']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allCommenti = $query->all();

        foreach ($allCommenti as $commento) {
            $user = User::findOne(['id' => $commento->created_by]);

            $authManager = Yii::$app->authManager;
            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $commento->created_at) && ($commento->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $commento->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 11]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $commento->id;
                $recordPunteggio->datetime_attivita = $commento->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 11;
            }
            $recordPunteggio->punteggio = $model->discussioni_commento;
            $recordPunteggio->save(false);
        }

        $query = CommentReply::find()
            ->orderBy(['created_by' => 'DESC']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allCommentiReply = $query->all();

        foreach ($allCommentiReply as $commento) {
            $user = User::findOne(['id' => $commento->created_by]);

            
            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $commento->created_at) && ($commento->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $commentContext = Comment::findOne(['id' => $commento->comment_id]);
            if (!($commentContext->context == 'open20\amos\discussioni\models\DiscussioniTopic')) {
                continue;
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $commento->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 11]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $commento->id;
                $recordPunteggio->datetime_attivita = $commento->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 11;
            }
            $recordPunteggio->punteggio = $model->discussioni_commento;
            $recordPunteggio->save(false);
        }
    }

    public function punteggioLikeDiscussioni($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = \open20\amos\core\models\ContentLikes::find()
            ->andWhere(['models_classname_id' => '4']);

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allLike = $query->all();

        foreach ($allLike as $like) {
            $user = User::findOne(['id' => $like->created_by]);

            
            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $like->created_at) && ($like->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $like->id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 12]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $like->id;
                $recordPunteggio->datetime_attivita = $like->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 12;
            }

            $recordPunteggio->punteggio = $model->discussioni_like;
            $recordPunteggio->save(false);
        }
    }

    public function punteggioCreazioneSondaggi($id, $user_id)
    {
        $model = Gamification::findOne(['id' => $id]);

        $query = SondaggiRisposte::find();

        if (isset($user_id)) {
            $query->andWhere(['created_by' => $user_id]);
        }

        $allSondaggi = $query->all();

        foreach ($allSondaggi as $sondaggio) {
            $user = User::findOne(['id' => $sondaggio->created_by]);

           
            $userProfile = UserProfile::findOne(['user_id' => $user->id]);
            if (self::excludeUserFromGamification($user)
                || !(($model->starting_date < $sondaggio->created_at) && ($sondaggio->created_at < $model->finish_date))
                || ($userProfile->isDeactivated())) {
                continue; // Salta questo elemento e passa alla prossima iterazione
            }
            
            $sondaggioid = SondaggiRisposteSessioni::findOne($sondaggio->sondaggi_risposte_sessioni_id);

            $query = PunteggiUtenti::find()
                ->andWhere(['gamification_id' => $id])
                ->andWhere(['content_id' => $sondaggioid->sondaggi_id])
                ->andWhere(['email' => $user->email])
                ->andWhere(['username' => $user->username])
                ->andWhere(['attivita' => 13]);

            $recordPunteggio = $query->one();

            if ($recordPunteggio === null) {
                $recordPunteggio = new PunteggiUtenti;
                $recordPunteggio->gamification_id = $id;
                $recordPunteggio->content_id = $sondaggioid->sondaggi_id;
                $recordPunteggio->datetime_attivita = $sondaggio->created_at;
                $recordPunteggio->user_profile_id = $userProfile->id;
                $recordPunteggio->username = $user->username;
                $recordPunteggio->email = $user->email;
                $recordPunteggio->nome = $userProfile->nome;
                $recordPunteggio->cognome = $userProfile->cognome;
                $recordPunteggio->attivita = 13;
            }

            $recordPunteggio->punteggio = $model->sondaggi_partec;
            $recordPunteggio->save(false);
        }
    }

    /**
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
    }

    public function getStatus($id) {
        $gamification = Gamification::findOne(['id' => $id]);

        $inProgress = (strtotime($gamification->starting_date) <= strtotime("now") &&
                       strtotime($gamification->finish_date) >= strtotime("now"));

        $inChiusura = strtotime($gamification->finish_date) <= strtotime("now");

        if ($gamification->status == 'GamificationWorkflow/CONCLUDED') {
            return 'Concluso';
        } else if ($gamification->status == 'GamificationWorkflow/DRAFT') {
            return 'In Bozza';
        } else if ($gamification->status == 'GamificationWorkflow/OPENING') {
            if ($inProgress) {
                return 'In Corso';
            } elseif ($inChiusura) {
                return 'In Chiusura';
            } else {
                return 'In Apertura';
            }
        } else {
            return 'Assente';
        }
    }

    /**
     * @return array
     */
    public function getStatusToRenderToHide()
    {
        $statusToRender = [
            Gamification::GAMIFICATION_WORKFLOW_STATUS_DRAFT => Module::t('gamification', 'Modifica in corso'),
        ];

        $statusToRender = ArrayHelper::merge(
            $statusToRender,
            [
                Gamification::GAMIFICATION_WORKFLOW_STATUS_OPENING => Module::t('gamification', 'In corso'),
            ]
        );

        $hideDraftStatus = [
            Gamification::GAMIFICATION_WORKFLOW_STATUS_CONCLUDED,
            //Gamification::GAMIFICATION_WORKFLOW_STATUS_INPROGRESS
        ];

        return [
            'statusToRender' => $statusToRender,
            'hideDraftStatus' => $hideDraftStatus
        ];
    }

    /**
     * @return string marker path
     */
    public function getIconMarker()
    {
        return null; //TODO
    }

    /**
     * If events are more than one, set 'array' => true in the calendarView in the index.
     * @return array events
     */
    public function getEvents()
    {
        return NULL; //TODO
    }

    /**
     * @return url event (calendar of activities)
     */
    public function getUrlEvent()
    {
        return NULL; //TODO e.g. Yii::$app->urlManager->createUrl([]);
    }

    /**
     * @return color event
     */
    public function getColorEvent()
    {
        return NULL; //TODO
    }

    /**
     * @return title event
     */
    public function getTitleEvent()
    {
        return NULL; //TODO
    }
    
    
    public function excludeUserFromGamification($user){
        $authManager = Yii::$app->authManager;
        if (($authManager->checkAccess($user->id, 'SUPERUSER')) || ($authManager->checkAccess($user->id, 'ADMIN')) || ($authManager->checkAccess($user->id, 'CAPOREDATTORECMS'))
            ) {
            return true;
        }
        return false;
    }
}
