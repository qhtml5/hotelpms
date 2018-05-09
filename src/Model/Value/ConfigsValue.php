<?php

namespace App\Model\Value;

class ConfigsValue
{

    // sorting
    /** @var string sorting delimiter */
    const SORT_DELIMITER = '_';
    /** @var string ascending sort */
    const SORT_ASC = 'ASC';
    /** @var string descending sort */
    const SORT_DESC = 'DESC';
    /** @var string param delimiter */
    const PARAM_DELIMITER = '_';

    // Gender
    /** @var int male */
    const GDR_MALE = 1;
    /** @var int female */
    const GDR_FEMALE = 2;
    /** @var int unknown */
    const GDR_VOID = 0;

    // Employee kind
    /** @var int regular employee */
    const EPK_PROPER = 1;
    /** @var int non-regular employee (full-time) */
    const EPK_FULLTIME = 2;
    /** @var int non-regular employee (part-time) */
    const EPK_PARTTIME = 3;
    /** @var int other kind */
    const EPK_OTHER = 4;

    // Flag value
    const FLV_TRUE = 1;
    const FLV_FALSE = 0;

    // Cleaning state
    /** @var int cleaning complete */
    const CLS_CLEAN = 1;
    /** @var int dirty now */
    const CLS_DIRTY = 2;
    /** @var int inspected */
    const CLS_INSPECTED = 3;
    /** @var int pickup */
    const CLS_PICKUP = 4;

    // Occupied state
    /** @var int vacant */
    const USS_VACANT = 0;
    /** @var int occupied */
    const USS_OCCUPIED = 1;
    /** @var int inspect */
    const USS_INSPECT = 2;

    // Additional info 1 of equipment
    /** @var int north wing 0x1 */
    const EQA_NORTH_WING = 1;
    /** @var int east wing 0x2 */
    const EQA_EAST_WING = 2;
    /** @var int west wing 0x4 */
    const EQA_WEST_WING = 4;
    /** @var int south wing 0x8 */
    const EQA_SOUTH_WING = 8;
    /** @var int north direction 0x10 */
    const EQA_NORTH_DIRECTION = 16;
    /** @var int east direction 0x20 */
    const EQA_EAST_DIRECTION = 32;
    /** @var int west direction 0x40 */
    const EQA_WEST_DIRECTION = 64;
    /** @var int south direction 0x80 */
    const EQA_SOUTH_DIRECTION = 128;
    /** @var int ocean view 0x100 */
    const EQA_OCEAN_VIEW = 256;
    /** @var int mountain view 0x200 */
    const EQA_MOUNTAIN_VIEW = 512;
    /** @var int large size 0x1000 */
    const EQA_LARGE_SIZE = 4096;
    /** @var int normal size 0x2000 */
    const EQA_NORMAL_SIZE = 8192;
    /** @var int small size 0x4000 */
    const EQA_SMALL_SIZE = 16384;

    // Additional info 2 of equipment
    /** @var int smoking 0x1 */
    const EQA_SMOKING = 1;
    /** @var int no smoking 0x2 */
    const EQA_NOSMOKING = 2;

    // Additional info 3 of equipment

    // Equipment kind
    /** @var int room */
    const EQK_ROOM = 1;
    /** @var int locker */
    const EQK_LOCKER = 2;
    /** @var int massage */
    const EQK_MASSAGE = 10;
    /** @var int golf */
    const EQK_GOLF = 20;

    // AGENT
    /** @var int agent */
    const VIA_AGENT = 1;
    /** @var int website */
    const VIA_WEBSITE = 2;
    /** @var int telephone */
    const VIA_TELEPHONE = 4;
    /** @var int fax */
    const VIA_FAX = 8;
    /** @var int email */
    const VIA_EMAIL = 16;
    /** @var int direct */
    const VIA_DIRECT = 28;
    /** @var int walkin */
    const VIA_WALKIN = 32;

     // Client Kind
    /** @var int person */
    const CLK_PERSONAL = 1;
    /** @var int group */
    const CLK_GROUP = 2;

    // Client Rank
    /** @var int Very Important Person */
    const CLL_VIP = 1;
    /** @var int Dominant Person */
    const CLL_DOMINANT = 2;
    /** @var int General Person */
    const CLL_GENERAL = 3;
    /** @var int Undesirable Guest */
    const CLL_UG = 4;

    // Description kind
    /** @var int merchandise */
    const DRK_MERCHANDISE = 1;
    /** @var int service */
    const DRK_SERVICE = 2;
    /** @var int tax */
    const DRK_TAX = 3;
    /** @var int additional */
    const DRK_ADDITIONAL = 4;
    /** @var int deposit */
    const DRK_DEPOSIT = 5;
    /** @var int discount */
    const DRK_DISCOUNT = 6;
    /** @var int extra service */
    const DRK_EXTRA_SERVICE = 7;
    /** @var int transfer */
    const DRK_TRANSFER = 9;

    // Price kind
    /** @var int amount */
    const PRK_AMOUNT = 1;
    /** @var int rate */
    const PRK_RATE = 2;
    /** @var int discount amount */
    const PRK_DISC_AMOUNT = 3;
    /** @var int discount rate */
    const PRK_DISC_RATE = 4;

    // Payment method
    /** @var int cash */
    const PAYMENT_CASH = 1;
    /** @var int credit card */
    const PAYMENT_CREDITCARD = 2;
    /** @var int coupon */
    const PAYMENT_COUPON = 3;
    /** @var int other */
    const PAYMENT_OTHER = 99;

    // payment gate way domestic or international
    /** @var int domestic */
    const PGW_DOMESTIC = 1;
    /** @var int international */
    const PGW_INTERNATIONAL = 2;

    // charge kind
    /** @var int once day */
    const CGK_ONCE = 1;
    /** @var int once hour */
    const CGK_TIME = 2;
    /** @var int extended time (hours) */
    const CGK_EXTEND_TIME = 3;
    /** @var int time and extended time use for remove fee charge */
    const CGK_TIME_AND_EXTEND = -1;


    // Extend time kind
    /** @var int extend time kind early check in */
    const ETK_EARLY_CHECK_IN = 1;
    /** @var int extend time kind late check out */
    const ETK_LATE_CHECK_OUT = 2;

    // Calendar kind
    /** @var int business day */
    const CDK_BUSINESS = 1;
    /** @var int holiday day */
    const CDK_HOLIDAY = 2;
    /** @var int before holiday day */
    const CDK_PRE_HOLIDAY = 3;

    // Kind of Date
    /** @var int sunday 0x1 */
    const DKD_SUNDAY = 1;
    /** @var int monday 0x2 */
    const DKD_MONDAY = 2;
    /** @var int tuesday 0x4 */
    const DKD_TUESDAY = 4;
    /** @var int wednesday 0x8 */
    const DKD_WEDNESDAY = 8;
    /** @var int thursday 0x10 */
    const DKD_THURSDAY = 16;
    /** @var int friday 0x20 */
    const DKD_FRIDAY = 32;
    /** @var int saturday 0x40 */
    const DKD_SATURDAY = 64;
    /** @var int holiday 0x80 */
    const DKD_HOLIDAY = 128;
    /** @var int pre holiday 0x100 */
    const DKD_PRE_HOLIDAY = 256;

    // check_in_status
    /** @var int before checkin */
    const CIS_BEFORE_CHECKIN = 0;
    /** @var int inhouse */
    const CIS_INHOUSE = 1;
    /** @var int after checkout */
    const CIS_AFTER_CHECKOUT = 2;

    // transaction status by int
    /** @var int failed */
    const TXN_FAILED = 0;
    /** @var int success */
    const TXN_SUCCESS = 1;
    /** @var int invalid hash */
    const TXN_INVALID_HASH = 2;

    // subscription plans
    /** @var int hostel 0x01 */
    const ALF_HOTEL = 1;
    /** @var int restaurant 0x02 */
    const ALF_RESTAURANT = 2;
    /** @var int golf 0x04 */
    const ALF_GOLF = 4;
    /** @var int sports 0x08 */
    const ALF_SPORTS = 8;
    /** @var int spa 0x10 */
    const ALF_SPA = 16;

    // options function
    /** @var int register 0x01 */
    const OPF_REGISTER = 1;
    /** @var int transfer hotel 0x02 */
    const OPF_TRANSFER_HOTEL = 2;

    // price composition
    /** @var int room 0x01 */
    const PCS_ROOM = 1;
    /** @var int facility 0x02 */
    const PCS_FACILITY = 2;
    /** @var int branch 0x04 */
    const PCS_BRANCH = 4;
    /** @var int operator 0x1000 */
    const PCS_OPERATOR = 4096;

    // payment status
    /** @var int not paid 0x0 */
    const PMS_NOT_PAID = 0;
    /** @var int paid 0x01 */
    const PMS_PAID = 1;

    const FOLIO_LANG_DEFAULT = 'vi';

    // Event for payment
    /** @var string */
    const EVT_UPDATE_SUBSCRIPTION_AFTER_PAYMENT = 'OnepayTransaction.afterReturn.updateSubscriptionInfo';

    // configuration_code
    /** @var int Number of possible check-in failures */
    const CCC_LOGIN_FAILURES_TIMES = 1;
    /** @var int Automatic logout time */
    const CCC_AUTO_LOGOUT_MINUTES = 2;

    /** @var int Suzutekメール送信サーバー情報 */
    const CCC_MAIL_SERVER_INFORMATION = 100;

    function __construct()
    {
    }

    /**
     * エイリアスメールアドレスを取得する
     * @return エイリアスメールアドレス
     */
    public static function getAlias() {
        $APP_ENV = env('APP_ENV');
        if (empty($APP_ENV)) {
            // 本番環境
            $alias = 'info@gaiheki.yeay.jp';
        } else {
            if ($APP_ENV === 'staging') {
                // 検証環境
                if ($_SERVER['REMOTE_ADDR'] === '183.77.127.125') {
                    // 鈴木商店からアクセスした場合は、検証環境でもこのメールアドレス
                    $alias = 'q-gaiheki-test@suzukishouten.co.jp';
                } else {
                    $alias = 'test@gaiheki.yeay.jp';
                }
            } else if ($APP_ENV === 'development_local') {
                // 開発環境（ローカル）
                $alias = 'q-gaiheki-test@suzukishouten.co.jp';
            }
        }
        return $alias;
    }
}