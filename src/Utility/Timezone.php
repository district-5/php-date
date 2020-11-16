<?php

/**
 * District5 - Date
 *
 * @copyright District5
 *
 * @author Mark Morgan <mark.morgan@district5.co.uk>
 * @link https://www.district5.co.uk
 *
 * @license This software and associated documentation (the "Software") may not be
 * used, copied, modified, distributed, published or licensed to any 3rd party
 * without the written permission of District5 or its author.
 *
 * The above copyright notice and this permission notice shall be included in
 * all licensed copies of the Software.
 *
 */
namespace District5\Utility;

/**
 * Timezone
 *
 * A utility for working with Timezones
 *
 * @author Mark Morgan
 *
 */
class Timezone
{

    protected static $_options = array(
        '1' => array('id' => '1', 'utcOffset' => -12, 'label' => '(GMT -12:00) International Date Line West'),
        '2' => array('id' => '2', 'utcOffset' => -11, 'label' => '(GMT -11:00) Midway Island, Samoa'),
        '3' => array('id' => '3', 'utcOffset' => -10, 'label' => '(GMT -10:00) Hawaii'),
        '4' => array('id' => '4', 'utcOffset' => -9, 'label' => '(GMT -09:00) Alaska'),
        '5' => array('id' => '5', 'utcOffset' => -8, 'label' => '(GMT -08:00) Pacific Time (US & Canada)'),
        '6' => array('id' => '6', 'utcOffset' => -8, 'label' => '(GMT -08:00) Tijuana, Baja California'),
        '7' => array('id' => '7', 'utcOffset' => -7, 'label' => '(GMT -07:00) Arizona'),
        '8' => array('id' => '8', 'utcOffset' => -7, 'label' => '(GMT -07:00) Chihuahua, La Paz, Mazatlan'),
        '9' => array('id' => '9', 'utcOffset' => -7, 'label' => '(GMT -07:00) Mountain Time (US & Canada)'),
        '10' => array('id' => '10', 'utcOffset' => -6, 'label' => '(GMT -06:00) Central America'),
        '11' => array('id' => '11', 'utcOffset' => -6, 'label' => '(GMT -06:00) Central Time (US & Canada)'),
        '12' => array('id' => '12', 'utcOffset' => -6, 'label' => '(GMT -06:00) Guadalajara, Mexico City, Monterrey'),
        '13' => array('id' => '13', 'utcOffset' => -6, 'label' => '(GMT -06:00) Saskatchewan'),
        '14' => array('id' => '14', 'utcOffset' => -5, 'label' => '(GMT -05:00) Bogota, Lima, Quito, Rio Branco'),
        '15' => array('id' => '15', 'utcOffset' => -5, 'label' => '(GMT -05:00) Eastern Time (US & Canada)'),
        '16' => array('id' => '16', 'utcOffset' => -5, 'label' => '(GMT -05:00) Indiana (East)'),
        '17' => array('id' => '17', 'utcOffset' => -4, 'label' => '(GMT -04:00) Atlantic Time (Canada)'),
        '18' => array('id' => '18', 'utcOffset' => -4, 'label' => '(GMT -04:00) Caracas, La Paz'),
        '19' => array('id' => '19', 'utcOffset' => -4, 'label' => '(GMT -04:00) Manaus'),
        '20' => array('id' => '20', 'utcOffset' => -4, 'label' => '(GMT -04:00) Santiago'),
        '21' => array('id' => '21', 'utcOffset' => -3.5, 'label' => '(GMT -03:30) Newfoundland'),
        '22' => array('id' => '22', 'utcOffset' => -3, 'label' => '(GMT -03:00) Brasilia'),
        '23' => array('id' => '23', 'utcOffset' => -3, 'label' => '(GMT -03:00) Buenos Aires, Georgetown'),
        '24' => array('id' => '24', 'utcOffset' => -3, 'label' => '(GMT -03:00) Greenland'),
        '25' => array('id' => '25', 'utcOffset' => -3, 'label' => '(GMT -03:00) Montevideo'),
        '26' => array('id' => '26', 'utcOffset' => -2, 'label' => '(GMT -02:00) Mid-Atlantic'),
        '27' => array('id' => '27', 'utcOffset' => -1, 'label' => '(GMT -01:00) Cape Verde Is.'),
        '28' => array('id' => '28', 'utcOffset' => -1, 'label' => '(GMT -01:00) Azores'),
        '29' => array('id' => '29', 'utcOffset' => 0, 'label' => '(GMT +00:00) Casablanca, Monrovia, Reykjavik'),
        '30' => array('id' => '30', 'utcOffset' => 0, 'label' => '(GMT +00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London'),
        '31' => array('id' => '31', 'utcOffset' => 1, 'label' => '(GMT +01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna'),
        '32' => array('id' => '32', 'utcOffset' => 1, 'label' => '(GMT +01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague'),
        '33' => array('id' => '33', 'utcOffset' => 1, 'label' => '(GMT +01:00) Brussels, Copenhagen, Madrid, Paris'),
        '34' => array('id' => '34', 'utcOffset' => 1, 'label' => '(GMT +01:00) Sarajevo, Skopje, Warsaw, Zagreb'),
        '35' => array('id' => '35', 'utcOffset' => 1, 'label' => '(GMT +01:00) West Central Africa'),
        '36' => array('id' => '36', 'utcOffset' => 2, 'label' => '(GMT +02:00) Amman'),
        '37' => array('id' => '37', 'utcOffset' => 2, 'label' => '(GMT +02:00) Athens, Bucharest, Istanbul'),
        '38' => array('id' => '38', 'utcOffset' => 2, 'label' => '(GMT +02:00) Beirut'),
        '39' => array('id' => '39', 'utcOffset' => 2, 'label' => '(GMT +02:00) Cairo'),
        '40' => array('id' => '40', 'utcOffset' => 2, 'label' => '(GMT +02:00) Harare, Pretoria'),
        '41' => array('id' => '41', 'utcOffset' => 2, 'label' => '(GMT +02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius'),
        '42' => array('id' => '42', 'utcOffset' => 2, 'label' => '(GMT +02:00) Jerusalem'),
        '43' => array('id' => '43', 'utcOffset' => 2, 'label' => '(GMT +02:00) Minsk'),
        '44' => array('id' => '44', 'utcOffset' => 2, 'label' => '(GMT +02:00) Windhoek'),
        '45' => array('id' => '45', 'utcOffset' => 3, 'label' => '(GMT +03:00) Kuwait, Riyadh, Baghdad'),
        '46' => array('id' => '46', 'utcOffset' => 3, 'label' => '(GMT +03:00) Moscow, St. Petersburg, Volgograd'),
        '47' => array('id' => '47', 'utcOffset' => 3, 'label' => '(GMT +03:00) Nairobi'),
        '48' => array('id' => '48', 'utcOffset' => 3, 'label' => '(GMT +03:00) Tbilisi'),
        '49' => array('id' => '49', 'utcOffset' => 3.5, 'label' => '(GMT +03:30) Tehran'),
        '50' => array('id' => '50', 'utcOffset' => 4, 'label' => '(GMT +04:00) Abu Dhabi, Muscat'),
        '51' => array('id' => '51', 'utcOffset' => 4, 'label' => '(GMT +04:00) Baku'),
        '52' => array('id' => '52', 'utcOffset' => 4, 'label' => '(GMT +04:00) Yerevan'),
        '53' => array('id' => '53', 'utcOffset' => 4.5, 'label' => '(GMT +04:30) Kabul'),
        '54' => array('id' => '54', 'utcOffset' => 5, 'label' => '(GMT +05:00) Yekaterinburg'),
        '55' => array('id' => '55', 'utcOffset' => 5, 'label' => '(GMT +05:00) Islamabad, Karachi, Tashkent'),
        '56' => array('id' => '56', 'utcOffset' => 5.5, 'label' => '(GMT +05:30) Sri Jayawardenapura'),
        '57' => array('id' => '57', 'utcOffset' => 5.5, 'label' => '(GMT +05:30) Chennai, Kolkata, Mumbai, New Delhi'),
        '58' => array('id' => '58', 'utcOffset' => 5.75, 'label' => '(GMT +05:45) Kathmandu'),
        '59' => array('id' => '59', 'utcOffset' => 6, 'label' => '(GMT +06:00) Almaty, Novosibirsk'),
        '60' => array('id' => '60', 'utcOffset' => 6, 'label' => '(GMT +06:00) Astana, Dhaka'),
        '61' => array('id' => '61', 'utcOffset' => 6.5, 'label' => '(GMT +06:30) Yangon (Rangoon)'),
        '62' => array('id' => '62', 'utcOffset' => 7, 'label' => '(GMT +07:00) Bangkok, Hanoi, Jakarta'),
        '63' => array('id' => '63', 'utcOffset' => 7, 'label' => '(GMT +07:00) Krasnoyarsk'),
        '64' => array('id' => '64', 'utcOffset' => 8, 'label' => '(GMT +08:00) Beijing, Chongqing, Hong Kong, Urumqi'),
        '65' => array('id' => '65', 'utcOffset' => 8, 'label' => '(GMT +08:00) Kuala Lumpur, Singapore'),
        '66' => array('id' => '66', 'utcOffset' => 8, 'label' => '(GMT +08:00) Irkutsk, Ulaan Bataar'),
        '67' => array('id' => '67', 'utcOffset' => 8, 'label' => '(GMT +08:00) Perth'),
        '68' => array('id' => '68', 'utcOffset' => 8, 'label' => '(GMT +08:00) Taipei'),
        '69' => array('id' => '69', 'utcOffset' => 9, 'label' => '(GMT +09:00) Osaka, Sapporo, Tokyo'),
        '70' => array('id' => '70', 'utcOffset' => 9, 'label' => '(GMT +09:00) Seoul'),
        '71' => array('id' => '71', 'utcOffset' => 9, 'label' => '(GMT +09:00) Yakutsk'),
        '72' => array('id' => '72', 'utcOffset' => 9.5, 'label' => '(GMT +09:30) Adelaide'),
        '73' => array('id' => '73', 'utcOffset' => 9.5, 'label' => '(GMT +09:30) Darwin'),
        '74' => array('id' => '74', 'utcOffset' => 10, 'label' => '(GMT +10:00) Brisbane'),
        '75' => array('id' => '75', 'utcOffset' => 10, 'label' => '(GMT +10:00) Canberra, Melbourne, Sydney'),
        '76' => array('id' => '76', 'utcOffset' => 10, 'label' => '(GMT +10:00) Hobart'),
        '77' => array('id' => '77', 'utcOffset' => 10, 'label' => '(GMT +10:00) Guam, Port Moresby'),
        '78' => array('id' => '78', 'utcOffset' => 10, 'label' => '(GMT +10:00) Vladivostok'),
        '79' => array('id' => '79', 'utcOffset' => 11, 'label' => '(GMT +11:00) Magadan, Solomon Is., New Caledonia'),
        '80' => array('id' => '80', 'utcOffset' => 12, 'label' => '(GMT +12:00) Auckland, Wellington'),
        '81' => array('id' => '81', 'utcOffset' => 12, 'label' => '(GMT +12:00) Fiji, Kamchatka, Marshall Is.'),
        '82' => array('id' => '82', 'utcOffset' => 13, 'label' => '(GMT +13:00) Nuku\'alofa')
    );

    /**
     * Gets a specific timezone given the timezone id
     *
     * @param string $timezoneId The timezone id
     *
     * @return array The details of the timezone with the given id in an array
     * of $timezoneId => array(id, utcOffset, label), null if no timezone details found
     */
    public static function GetTimezone($timezoneId)
    {
        if (!array_key_exists($timezoneId, static::$_options))
        {
            return null;
        }

        return static::$_options[$timezoneId];
    }

    /**
     * Gets all supported timezones
     *
     * @return array All the timezones in an array of $timezoneId => array(id, utcOffset, label)
     */
    public static function GetTimezones()
    {
        return static::$_options;
    }

    /**
     * Checks whether timezone id is valid
     *
     * @param string $id The timezone id
     *
     * @return bool True if the id is a valid timezone id, false otherwise
     */
    public static function IsValidTimezoneId($id)
    {
        if (!array_key_exists($id, static::$_options))
        {
            return false;
        }

        return true;
    }
}