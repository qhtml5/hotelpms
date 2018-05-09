<?php
/**
 * Hotel PMS Project
 * @copyright Copyright © 2016 株式会社鈴木商店, All rights reserved. (https://www.suzukishouten.co.jp/)
 */
namespace App\Model\Table;

use App\Context\ContextTrait;
use App\Model\Entity\Action;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Date;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

/**
 * Should be extended by all new tables which want to apply the `SoftDelete` and `Timestamp`
 *
 * ### NOTE when initialize child class
 *
 * ```
 *  public function initialize(array $config)
 *  {
 *      parent::initialize($config);
 *      // your customization code here
 *  }
 *
 * ```
 *
 * Class BaseTable
 *
 * @package App\Model\Table
 * @author vo.chau
 */
class BaseTable extends Table
{

    /**
     * Custom deleted field with the same column in table (if this field is NOT NULL means record was deleted)
     *
     * @see \SoftDelete\Model\Table\SoftDeleteTrait
     * @var string
     */
    protected $softDeleteField = 'deleted_date';

    /**
     * Some case need to by pass check operator code => set this value to false
     * @var bool
     */
    public $checkOperator = true;

    /**
     * Using to select the deleted record
     *
     * ### Example: suppose $user #1 is soft deleted.
     *
     * ```
     * $user = $this->Users->find('all', ['withDeleted'])->where('id', 1)->first();
     * $this->restore($user); // $user #1 is now restored.
     * ```
     * @var string
     */
    const WITH_DELETE_FIELD = 'withDeleted';

    /**
     * Initialize Table
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        // set default mapping key in case of we have more than one primary key
        // Todo: override if child class need other mapping
        $this->primaryKey('id');
        // add timestamp behavior
        $this->addBehavior('Timestamp',
            [
                'events' => [
                    'Model.beforeSave' => [
                        'created_date' => 'new',
                        'modified_date' => 'always',
                    ],
                ]
            ]
        );
    }

}