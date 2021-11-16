<?php

use yii\db\Migration;

class m170728_110411_create_intro_slideshow extends Migration
{
    public function safeUp()
    {
        if (class_exists('\open20\amos\slideshow\models\Slideshow')) {
            if (($this->db->schema->getTableSchema('slideshow', true) != null) &&
                ($this->db->schema->getTableSchema('slideshow_route', true) != null) &&
                ($this->db->schema->getTableSchema('slideshow_pages', true) != null)) {

                $arraySlideshow = ['name' => 'Title My Activities', 'label' => 'LABEL', 'description' => 'DESCRIPTION'];
                $this->insert('slideshow', $arraySlideshow);
                $slideShow = \open20\amos\slideshow\models\Slideshow::find()->andWhere($arraySlideshow)->one();
                if (!empty($slideShow)) {
                    $this->insert('slideshow_route', [
                        'route' => '/myactivities/my-activities/index',
                        'already_view' => 1,
                        'slideshow_id' => $slideShow->id,
                        'role' => 'BASIC_USER']);
                    $this->insert('slideshow_pages', [
                        'name' => 'PAGE 1',
                        'ordinal' => 1,
                        'slideshow_id' => $slideShow->id,
                        'pageContent' => '<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa.</p>
                            <p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Donec quam felis, ultricies nec, pellentesque eu, pretium quis, sem.</p>
                            <p>Nulla consequat massa quis enim. Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu.</p>
                            <p>In enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede mollis pretium. Integer tincidunt. Cras dapibus.</p>
                            <p>Vivamus elementum semper nisi. Aenean vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend ac, enim.</p>
                            <p>Aliquam lorem ante, dapibus in, viverra quis, feugiat a, tellus. Phasellus viverra nulla ut metus varius laoreet. Quisque rutrum. Aenean imperdiet.</p>
                            <p>Etiam ultricies nisi vel augue. Curabitur ullamcorper ultricies nisi. Nam eget dui. Etiam rhoncus.</p>
                            <p>Maecenas tempus, tellus eget condimentum rhoncus, sem quam semper libero, sit amet adipiscing sem neque sed ipsum. Nam quam nunc, blandit vel, luctus pulvinar, hendrerit id, lorem.</p>
                            <p>Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis faucibus. Nullam quis ante. Etiam sit amet orci eget eros faucibus tincidunt. Duis leo. Sed fringilla mauris sit amet nibh. Donec sodales sagittis magna. Sed consequat, leo eget bibendum sodales, augue velit cursus nunc,</p>
                            ']);

                }
            }
        }
    }

    public function safeDown()
    {
        if (class_exists('\open20\amos\slideshow\models\Slideshow')) {
            if (($this->db->schema->getTableSchema('slideshow', true) != null) &&
                ($this->db->schema->getTableSchema('slideshow_route', true) != null) &&
                ($this->db->schema->getTableSchema('slideshow_pages', true) != null)) {
                $arraySlideshow = ['name' => 'Title My Activities', 'label' => 'LABEL', 'description' => 'DESCRIPTION'];
                $slideShow = \open20\amos\slideshow\models\Slideshow::find()->andWhere($arraySlideshow)->one();
                if (!empty($slideShow)) {
                    $this->delete('slideshow_route', ['slideshow_id' => $slideShow->id]);
                    $this->delete('slideshow_pages', ['slideshow_id' => $slideShow->id]);
                    $this->delete('slideshow', $arraySlideshow);
                }

            }
        }
    }

}
