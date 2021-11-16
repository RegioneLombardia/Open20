<?php

namespace app\modules\cmsapi\cart\positions;

use yii\helpers\Html;

class RoomPosition implements \open20\amos\shop\CartPositionInterface
{
    /**
     * @var integer Identiffier of this room
     */
    protected $_id          = null;
    protected $_id_potenziale = null;
    protected $_check_in    = '';
    protected $_check_out   = '';
    protected $_name        = '';
    protected $_description = '';
    protected $_quantity    = 0;
    protected $_price       = 0;
    protected $_roomData    = [];
    protected $_currency    = 'EUR';

    public function getId()
    {
        $datas = [
            $this->_id_potenziale,
            $this->_check_in,
            $this->_check_out
        ];

        $id = hash( 'crc32', implode('_', $datas));

        return $id;
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    public function getIdPotenziale()
    {
        return $this->_id_potenziale;
    }

    public function setIdPotenziale($id)
    {
        $this->_id_potenziale = $id;
    }

    public function getIcon()
    {
        return 'room';
    }

    public function getCheckIn()
    {
        return $this->_check_in;
    }

    public function setCheckIn($checkin)
    {
        $this->_check_in = $checkin;
    }

    public function getCheckOut()
    {
        return $this->_check_out;
    }

    public function setCheckOut($checkout)
    {
        $this->_check_out = $checkout;
    }

    public function getName()
    {
        return $this->_name;
    }

    public function setName($name)
    {
        $this->_name = $name;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
    }

    public function getQuantityOptions()
    {
        return [];
    }

    public function getPrice()
    {
        return $this->_price;
    }

    public function setPrice($price)
    {
        $this->_price = $price;
    }

    public function getCost($withDiscount = true)
    {
        return $this->_price * $this->_quantity;
    }


    public function getQuantity()
    {
        return $this->_quantity;
    }

    public function setQuantity($quantity)
    {
        $this->_quantity = $quantity;
    }

    /**
     * @return array
     */
    public function getRoomData()
    {
        return $this->_roomData;
    }

    /**
     * @param array $roomData
     */
    public function setRoomData($roomData = [])
    {
        $this->_roomData = $roomData;
    }

    public function load($roomArray = [])
    {
        foreach ($roomArray as $key => $val) {
            switch ($key) {
                case 'id_camera_potenziale':
                    $this->setIdPotenziale($val);
                    break;
                case 'nome_vp':
                    $this->setName($val);
                    break;
                case 'check_in':
                    $this->setCheckIn($val);
                    break;
                case 'check_out':
                    $this->setCheckOut($val);
                    break;
                case 'nome_struttura':
                    $this->setDescription($val);
                    break;
                case 'posti_letto':
                    break;
                case 'prezzo_consigliato':
                    $this->setPrice($val);
                    break;
                case 'id_tariffa':
                    break;
                case 'id_configurazione':
                    break;
            }
        }

        $this->setRoomData($roomArray);
    }

    /**
     *
     * @return string
     */
    public function renderDescription()
    {
        return  'Struttura:' . Html::tag('span', $this->getDescription()
            . Html::tag('br')
            . 'Alloggio:' . $this->getName()
            . Html::tag('br')
            . ' ' . Html::tag('span', 'CheckIn:' . $this->getCheckIn())
            . Html::tag('br')
            . ' ' . Html::tag('span', 'CheckOut:' . $this->getCheckOut())
            , ['class' => 'text-muted']);
    }

    /**
     *
     * @return string
     */
    public function renderPaymentDescription()
    {
        return  'Struttura: ' .  $this->getDescription() . PHP_EOL
            . 'Alloggio: ' . $this->getName() . PHP_EOL
            . 'CheckIn: ' . $this->getCheckIn() . PHP_EOL
            .  'CheckOut: ' . $this->getCheckOut() ;
    }

    /**
     * This closure will be called in [[GridView::rowOptions]].
     *
     * @param integer $key the key value associated with the current data model
     * @param integer $index the zero-based index of the data model in the model array returned by [[dataProvider]]
     * @param GridView $grid the GridView object
     * @return array
     */
    public function getRowOptions($key, $index, $grid)
    {
        return [];
    }

    /**
     *
     * @return array
     */
    public function getAdditionalLinks(): array
    {
        return [];
    }

    public function getCurrency()
    {
        return $this->_currency;
    }

    public function setCurrency($currency)
    {
        $this->_currency = $currency;
    }
}