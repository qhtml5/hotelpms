<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;

use Cake\Validation\Validator;
use Cake\I18n\Date;

/**
 * Class ClientInfosTable
 * @package App\Model\Table
 * @author chau.vo <vo.thi.trang.chau@suzukishouten.co.jp>
 */
class ClientInfosTable extends BaseTable
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
         * configure mapping for Client Infos
         * 1. belong to CurrencyConversions
         * 2. has many reservation infos
         *
         * @author vo.chau
         */
        $this->addAssociations([
            'belongsTo' => [
                'CurrencyConversions' => [
                    'foreignKey' => 'currency_conversion_id',
                    'className' => 'App\Model\Table\CurrencyConversionsTable'
                ],
                'CreditcardCompanies' => [
                    'foreignKey' => 'creditcard_company_id',
                    'className' => 'App\Model\Table\CreditcardCompaniesTable'
                ]
            ],
            'hasMany' => [
                'ReservationInfos' => [
                    'foreignKey' => 'client_info_id',
                    'className' => 'App\Model\Table\ReservationInfosTable'
                ]
            ]
        ]);
    }

	/**
     * Validate data add new client
     *
     * @author chau.vo <vo.thi.trang.chau@suzukishouten.co.jp>
     * @return \Cake\Validation\Validator
     */

    public function getValidationCreateClientInfoStandard()
    {
        $validator = new Validator();
        $validator

  			/** client_kind */
            // Presence in post data
            ->requirePresence('client_kind', 'create', 'Client Kind must be entered.')
            // Empty checking
            ->notEmpty('client_kind', 'Client Kind must be entered.')
            // Check is integer or not
            ->integer('client_kind', 'Client Kind must be integer.')
            /** first_name */
            // Presence in post data
            ->requirePresence('first_name', 'create', 'Name must be entered.')
            // Empty checking
            ->notEmpty('first_name', 'Name must be entered.')

            /** gender */
            // Check is integer or not
            ->integer('gender', 'gender must be integer.')
            ->allowEmpty('gender')
            /** date_of_birth */
            // Check valid date
            ->date('date_of_birth', ['ymd'], 'Date is not valid format.')
            ->allowEmpty('date_of_birth')

            /** date_of_issue_of_id */
            // Check valid date
            ->date('date_of_issue_of_id', ['ymd'], 'Date is not valid format.')
            ->allowEmpty('date_of_issue_of_id')

            /** expiration_date_of_id */
            // Check valid date
            ->date('expiration_date_of_id', ['ymd'], 'Date is not valid format.')
            ->allowEmpty('expiration_date_of_id')
            // compare expiration_date_of_id
            ->add('expiration_date_of_id', 'custom', [
                'rule' => function ($value, $context) {
                    $date_of_issue_of_id = new Date($context['data']['date_of_issue_of_id']);
                    $expiration_date_of_id = new Date($value);
                    return $expiration_date_of_id > $date_of_issue_of_id;
                },
                'message' => "Date of issue end must be greater than Date of Expire"
            ])

            /** expiration_date_of_creditcard */
            // Check valid date
            ->date('expiration_date_of_creditcard', ['ymd'], 'Date is not valid format.')
            ->allowEmpty('expiration_date_of_creditcard')

            /** expiration_date_of_visa */
            // Check valid date
            ->date('expiration_date_of_visa', ['ymd'], 'Date is not valid format.')
            ->allowEmpty('expiration_date_of_visa')


            /** email_address1 */
            // Check valid email
            ->add('email_address1', 'validFormat', [
                'rule' => 'email',
                'message' => 'E-mail 1 must be valid'
            ])
            ->allowEmpty('email_address1')

            /** email_address2 */
            // Check valid email
            ->add('email_address2', 'validFormat', [
                'rule' => 'email',
                'message' => 'E-mail 2 must be valid'
            ])
            ->allowEmpty('email_address2')
            /** client_rank */
            // Presence in post data
            ->requirePresence('client_rank', 'create', 'client rank must be entered.')
            // Empty checking
            ->notEmpty('client_rank', 'client rank must be entered.')
            // Check is integer or not
            ->integer('client_rank', 'client rank must be integer.')

            /** married */
            // Check is integer or not
            ->integer('married', 'Married must be integer.')
            ->allowEmpty('married')

            /** favorite_equipment1 */
            // Check is integer or not
            ->integer('favorite_equipment1', 'favorite_equipment1  must be integer.')
            ->allowEmpty('favorite_equipment1')

            /** favorite_equipment2 */
            // Check is integer or not
            ->integer('favorite_equipment2', 'favorite_equipment1  must be integer.')
            ->allowEmpty('favorite_equipment2')

            /** usage_count */
            // Check is integer or not
            ->integer('usage_count', 'usage_count  must be integer.')
            ->allowEmpty('usage_count')

            /** telephone_number1 */
            // Check is integer or not
            ->add('telephone_number1', array(
                'custom' => [
                    'rule' =>  array('custom', '/^[0-9+\-\s.]+$/i'),
                    'message' => "Telephone number is not valid."
                ]
            ))
            ->allowEmpty('telephone_number1')
            /** telephone_number2 */
            // Check is integer or not
            ->add('telephone_number2', array(
                'custom' => [
                    'rule' =>  array('custom', '/^[0-9+\-\s.]+$/i'),
                    'message' => "Telephone number is not valid."
                ]
            ))
            ->allowEmpty('telephone_number2');

        return $validator;
    }

}