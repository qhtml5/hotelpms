<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;

use App\Exception\NotAllowedPaymentException;
use App\Exception\NotCorrectPaymentAmountException;
use App\Exception\NotCorrectSalesInfoAndReservationException;
use App\Exception\SalesInfoPaidException;
use App\Model\Entity\CurrencyConversion;
use App\Model\Value\ConfigsValue;
use App\Model\Value\PaymentMethodValue;
use Cake\Network\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Controller\Component\SalesInfosTrait;

/**
 * Class PaymentDetailsTable
 * @package App\Model\Table
 * @author vo.chau
 * @date 2017-Jan-18
 */
class PaymentDetailsTable extends BaseTable
{
    /**
     * initialize repository
     *
     * @param array $config override configurations
     */
    public function initialize(array $config)
    {
        // initialize for parent
        parent::initialize($config);

        /*
         * configure mapping for Payment Details
         * 1. belongs to sales infos
         * 2. belongs to sales details
         * 3. belongs to currency conversions
         *
         * @author vo.chau
         */
        $this->addAssociations([
            'belongsTo' => [
                'SalesInfos' => [
                    'foreignKey' => 'sales_info_id',
                    'className' => 'App\Model\Table\SalesInfosTable'
                ],
                'SalesDetails' => [
                    'foreignKey' => 'sales_detail_id',
                    'className' => 'App\Model\Table\SalesDetailsTable'
                ],
                'CurrencyConversions' => [
                    'foreignKey' => 'currency_conversion_id',
                    'className' => 'App\Model\Table\CurrencyConversionsTable'
                ]
            ]
        ]);
    }
}