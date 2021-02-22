<?php declare(strict_types=1);

namespace Mediagone\Common\Types\System;

use InvalidArgumentException;
use Mediagone\Common\Types\ValueObject;
use function is_string;


/**
 * Represents a Country (supports Alpha2 and Alpha3 country codes)
 */
final class Country implements ValueObject
{
    //========================================================================================================
    // Properties
    //========================================================================================================
    
    private string $name;
    
    private string $alpha2;
    
    private string $alpha3;
    
    
    
    //========================================================================================================
    // Constructors
    //========================================================================================================
    
    private function __construct(object $country)
    {
        $this->name = $country->name;
        $this->alpha2 = $country->alpha2;
        $this->alpha3 = $country->alpha3;
    }
    
    
    public static function fromName(string $name) : self
    {
        return self::getCacheValue('name', $name);
    }
    
    
    public static function fromAlpha2(string $alpha2) : self
    {
        return self::getCacheValue('alpha2', $alpha2);
    }
    
    
    public static function fromAlpha3(string $alpha3) : self
    {
        return self::getCacheValue('alpha3', $alpha3);
    }
    
    
    
    //========================================================================================================
    // Static methods
    //========================================================================================================
    
    public static function isValueValid($alpha3) : bool
    {
        if (! is_string($alpha3)) {
            return false;
        }
        
        try {
            self::getCacheValue('alpha3', $alpha3);
            
            return true;
        }
        catch (InvalidArgumentException $ex) {
            return false;
        }
    }
    
    
    
    //========================================================================================================
    // Methods
    //========================================================================================================
    
    public function jsonSerialize()
    {
        return $this->alpha3;
    }
    
    
    public function __toString() : string
    {
        return $this->alpha3;
    }
    
    
    public function getName() : string
    {
        return $this->name;
    }
    
    
    public function getAlpha2() : string
    {
        return $this->alpha2;
    }
    
    
    public function getAlpha3() : string
    {
        return $this->alpha3;
    }
    
    
    
    //========================================================================================================
    // Helpers
    //========================================================================================================
    
    private static function getCacheValue(string $cacheKey, string $countryKey) : self
    {
        if (! isset(self::$cache[$cacheKey])) {
            self::initCache($cacheKey);
        }
        
        if (isset(self::$cache[$cacheKey][$countryKey])) {
            return self::getCountry(self::$cache[$cacheKey][$countryKey]);
        }
        
        throw new InvalidArgumentException("Invalid country \"$countryKey\" ($cacheKey)");
    }
    
    
    private static function initCache(string $cacheKey) : void
    {
        self::$cache[$cacheKey] = [];
        foreach (self::$COUNTRIES as $country) {
            self::$cache[$cacheKey][$country[$cacheKey]] = (object)$country;
        }
    }
    
    
    private static function getCountry(object $country) : self
    {
        if ($country->c === null) {
            $country->c = new self($country);
        }
        
        return $country->c;
    }
    
    
    
    //========================================================================================================
    // Static properties
    //========================================================================================================
    
    private static array $cache = [];
    
    private static array $COUNTRIES = [
        ['name' => 'Afghanistan', 'alpha2' => 'AF', 'alpha3' => 'AFG', 'number' => '004' , 'c' => null],
        ['name' => 'Åland Islands', 'alpha2' => 'AX', 'alpha3' => 'ALA', 'number' => '248' , 'c' => null],
        ['name' => 'Albania', 'alpha2' => 'AL', 'alpha3' => 'ALB', 'number' => '008' , 'c' => null],
        ['name' => 'Algeria', 'alpha2' => 'DZ', 'alpha3' => 'DZA', 'number' => '012' , 'c' => null],
        ['name' => 'American Samoa', 'alpha2' => 'AS', 'alpha3' => 'ASM', 'number' => '016' , 'c' => null],
        ['name' => 'Andorra', 'alpha2' => 'AD', 'alpha3' => 'AND', 'number' => '020' , 'c' => null],
        ['name' => 'Angola', 'alpha2' => 'AO', 'alpha3' => 'AGO', 'number' => '024' , 'c' => null],
        ['name' => 'Anguilla', 'alpha2' => 'AI', 'alpha3' => 'AIA', 'number' => '660' , 'c' => null],
        ['name' => 'Antarctica', 'alpha2' => 'AQ', 'alpha3' => 'ATA', 'number' => '010' , 'c' => null],
        ['name' => 'Antigua and Barbuda', 'alpha2' => 'AG', 'alpha3' => 'ATG', 'number' => '028' , 'c' => null],
        ['name' => 'Argentina', 'alpha2' => 'AR', 'alpha3' => 'ARG', 'number' => '032' , 'c' => null],
        ['name' => 'Armenia', 'alpha2' => 'AM', 'alpha3' => 'ARM', 'number' => '051' , 'c' => null],
        ['name' => 'Aruba', 'alpha2' => 'AW', 'alpha3' => 'ABW', 'number' => '533' , 'c' => null],
        ['name' => 'Australia', 'alpha2' => 'AU', 'alpha3' => 'AUS', 'number' => '036' , 'c' => null],
        ['name' => 'Austria', 'alpha2' => 'AT', 'alpha3' => 'AUT', 'number' => '040' , 'c' => null],
        ['name' => 'Azerbaijan', 'alpha2' => 'AZ', 'alpha3' => 'AZE', 'number' => '031' , 'c' => null],
        ['name' => 'Bahamas', 'alpha2' => 'BS', 'alpha3' => 'BHS', 'number' => '044' , 'c' => null],
        ['name' => 'Bahrain', 'alpha2' => 'BH', 'alpha3' => 'BHR', 'number' => '048' , 'c' => null],
        ['name' => 'Bangladesh', 'alpha2' => 'BD', 'alpha3' => 'BGD', 'number' => '050' , 'c' => null],
        ['name' => 'Barbados', 'alpha2' => 'BB', 'alpha3' => 'BRB', 'number' => '052' , 'c' => null],
        ['name' => 'Belarus', 'alpha2' => 'BY', 'alpha3' => 'BLR', 'number' => '112' , 'c' => null],
        ['name' => 'Belgium', 'alpha2' => 'BE', 'alpha3' => 'BEL', 'number' => '056' , 'c' => null],
        ['name' => 'Belize', 'alpha2' => 'BZ', 'alpha3' => 'BLZ', 'number' => '084' , 'c' => null],
        ['name' => 'Benin', 'alpha2' => 'BJ', 'alpha3' => 'BEN', 'number' => '204' , 'c' => null],
        ['name' => 'Bermuda', 'alpha2' => 'BM', 'alpha3' => 'BMU', 'number' => '060' , 'c' => null],
        ['name' => 'Bhutan', 'alpha2' => 'BT', 'alpha3' => 'BTN', 'number' => '064' , 'c' => null],
        ['name' => 'Bolivia (Plurinational State of)', 'alpha2' => 'BO', 'alpha3' => 'BOL', 'number' => '068' , 'c' => null],
        ['name' => 'Bonaire, Sint Eustatius and Saba', 'alpha2' => 'BQ', 'alpha3' => 'BES', 'number' => '535' , 'c' => null],
        ['name' => 'Bosnia and Herzegovina', 'alpha2' => 'BA', 'alpha3' => 'BIH', 'number' => '070' , 'c' => null],
        ['name' => 'Botswana', 'alpha2' => 'BW', 'alpha3' => 'BWA', 'number' => '072' , 'c' => null],
        ['name' => 'Bouvet Island', 'alpha2' => 'BV', 'alpha3' => 'BVT', 'number' => '074' , 'c' => null],
        ['name' => 'Brazil', 'alpha2' => 'BR', 'alpha3' => 'BRA', 'number' => '076' , 'c' => null],
        ['name' => 'British Indian Ocean Territory', 'alpha2' => 'IO', 'alpha3' => 'IOT', 'number' => '086' , 'c' => null],
        ['name' => 'Brunei Darussalam', 'alpha2' => 'BN', 'alpha3' => 'BRN', 'number' => '096' , 'c' => null],
        ['name' => 'Bulgaria', 'alpha2' => 'BG', 'alpha3' => 'BGR', 'number' => '100' , 'c' => null],
        ['name' => 'Burkina Faso', 'alpha2' => 'BF', 'alpha3' => 'BFA', 'number' => '854' , 'c' => null],
        ['name' => 'Burundi', 'alpha2' => 'BI', 'alpha3' => 'BDI', 'number' => '108' , 'c' => null],
        ['name' => 'Cabo Verde', 'alpha2' => 'CV', 'alpha3' => 'CPV', 'number' => '132' , 'c' => null],
        ['name' => 'Cambodia', 'alpha2' => 'KH', 'alpha3' => 'KHM', 'number' => '116' , 'c' => null],
        ['name' => 'Cameroon', 'alpha2' => 'CM', 'alpha3' => 'CMR', 'number' => '120' , 'c' => null],
        ['name' => 'Canada', 'alpha2' => 'CA', 'alpha3' => 'CAN', 'number' => '124' , 'c' => null],
        ['name' => 'Cayman Islands', 'alpha2' => 'KY', 'alpha3' => 'CYM', 'number' => '136' , 'c' => null],
        ['name' => 'Central African Republic', 'alpha2' => 'CF', 'alpha3' => 'CAF', 'number' => '140' , 'c' => null],
        ['name' => 'Chad', 'alpha2' => 'TD', 'alpha3' => 'TCD', 'number' => '148' , 'c' => null],
        ['name' => 'Chile', 'alpha2' => 'CL', 'alpha3' => 'CHL', 'number' => '152' , 'c' => null],
        ['name' => 'China', 'alpha2' => 'CN', 'alpha3' => 'CHN', 'number' => '156' , 'c' => null],
        ['name' => 'Christmas Island', 'alpha2' => 'CX', 'alpha3' => 'CXR', 'number' => '162' , 'c' => null],
        ['name' => 'Cocos (Keeling) Islands', 'alpha2' => 'CC', 'alpha3' => 'CCK', 'number' => '166' , 'c' => null],
        ['name' => 'Colombia', 'alpha2' => 'CO', 'alpha3' => 'COL', 'number' => '170' , 'c' => null],
        ['name' => 'Comoros', 'alpha2' => 'KM', 'alpha3' => 'COM', 'number' => '174' , 'c' => null],
        ['name' => 'Congo', 'alpha2' => 'CG', 'alpha3' => 'COG', 'number' => '178' , 'c' => null],
        ['name' => 'Congo (Democratic Republic of the)', 'alpha2' => 'CD', 'alpha3' => 'COD', 'number' => '180' , 'c' => null],
        ['name' => 'Cook Islands', 'alpha2' => 'CK', 'alpha3' => 'COK', 'number' => '184' , 'c' => null],
        ['name' => 'Costa Rica', 'alpha2' => 'CR', 'alpha3' => 'CRI', 'number' => '188' , 'c' => null],
        ['name' => 'Côte d\'Ivoire', 'alpha2' => 'CI', 'alpha3' => 'CIV', 'number' => '384' , 'c' => null],
        ['name' => 'Croatia', 'alpha2' => 'HR', 'alpha3' => 'HRV', 'number' => '191' , 'c' => null],
        ['name' => 'Cuba', 'alpha2' => 'CU', 'alpha3' => 'CUB', 'number' => '192' , 'c' => null],
        ['name' => 'Curaçao', 'alpha2' => 'CW', 'alpha3' => 'CUW', 'number' => '531' , 'c' => null],
        ['name' => 'Cyprus', 'alpha2' => 'CY', 'alpha3' => 'CYP', 'number' => '196' , 'c' => null],
        ['name' => 'Czechia', 'alpha2' => 'CZ', 'alpha3' => 'CZE', 'number' => '203' , 'c' => null],
        ['name' => 'Denmark', 'alpha2' => 'DK', 'alpha3' => 'DNK', 'number' => '208' , 'c' => null],
        ['name' => 'Djibouti', 'alpha2' => 'DJ', 'alpha3' => 'DJI', 'number' => '262' , 'c' => null],
        ['name' => 'Dominica', 'alpha2' => 'DM', 'alpha3' => 'DMA', 'number' => '212' , 'c' => null],
        ['name' => 'Dominican Republic', 'alpha2' => 'DO', 'alpha3' => 'DOM', 'number' => '214' , 'c' => null],
        ['name' => 'Ecuador', 'alpha2' => 'EC', 'alpha3' => 'ECU', 'number' => '218' , 'c' => null],
        ['name' => 'Egypt', 'alpha2' => 'EG', 'alpha3' => 'EGY', 'number' => '818' , 'c' => null],
        ['name' => 'El Salvador', 'alpha2' => 'SV', 'alpha3' => 'SLV', 'number' => '222' , 'c' => null],
        ['name' => 'Equatorial Guinea', 'alpha2' => 'GQ', 'alpha3' => 'GNQ', 'number' => '226' , 'c' => null],
        ['name' => 'Eritrea', 'alpha2' => 'ER', 'alpha3' => 'ERI', 'number' => '232' , 'c' => null],
        ['name' => 'Estonia', 'alpha2' => 'EE', 'alpha3' => 'EST', 'number' => '233' , 'c' => null],
        ['name' => 'Ethiopia', 'alpha2' => 'ET', 'alpha3' => 'ETH', 'number' => '231' , 'c' => null],
        ['name' => 'Eswatini', 'alpha2' => 'SZ', 'alpha3' => 'SWZ', 'number' => '748' , 'c' => null],
        ['name' => 'Falkland Islands (Malvinas)', 'alpha2' => 'FK', 'alpha3' => 'FLK', 'number' => '238' , 'c' => null],
        ['name' => 'Faroe Islands', 'alpha2' => 'FO', 'alpha3' => 'FRO', 'number' => '234' , 'c' => null],
        ['name' => 'Fiji', 'alpha2' => 'FJ', 'alpha3' => 'FJI', 'number' => '242' , 'c' => null],
        ['name' => 'Finland', 'alpha2' => 'FI', 'alpha3' => 'FIN', 'number' => '246' , 'c' => null],
        ['name' => 'France', 'alpha2' => 'FR', 'alpha3' => 'FRA', 'number' => '250' , 'c' => null],
        ['name' => 'French Guiana', 'alpha2' => 'GF', 'alpha3' => 'GUF', 'number' => '254' , 'c' => null],
        ['name' => 'French Polynesia', 'alpha2' => 'PF', 'alpha3' => 'PYF', 'number' => '258' , 'c' => null],
        ['name' => 'French Southern Territories', 'alpha2' => 'TF', 'alpha3' => 'ATF', 'number' => '260' , 'c' => null],
        ['name' => 'Gabon', 'alpha2' => 'GA', 'alpha3' => 'GAB', 'number' => '266' , 'c' => null],
        ['name' => 'Gambia', 'alpha2' => 'GM', 'alpha3' => 'GMB', 'number' => '270' , 'c' => null],
        ['name' => 'Georgia', 'alpha2' => 'GE', 'alpha3' => 'GEO', 'number' => '268' , 'c' => null],
        ['name' => 'Germany', 'alpha2' => 'DE', 'alpha3' => 'DEU', 'number' => '276' , 'c' => null],
        ['name' => 'Ghana', 'alpha2' => 'GH', 'alpha3' => 'GHA', 'number' => '288' , 'c' => null],
        ['name' => 'Gibraltar', 'alpha2' => 'GI', 'alpha3' => 'GIB', 'number' => '292' , 'c' => null],
        ['name' => 'Greece', 'alpha2' => 'GR', 'alpha3' => 'GRC', 'number' => '300' , 'c' => null],
        ['name' => 'Greenland', 'alpha2' => 'GL', 'alpha3' => 'GRL', 'number' => '304' , 'c' => null],
        ['name' => 'Grenada', 'alpha2' => 'GD', 'alpha3' => 'GRD', 'number' => '308' , 'c' => null],
        ['name' => 'Guadeloupe', 'alpha2' => 'GP', 'alpha3' => 'GLP', 'number' => '312' , 'c' => null],
        ['name' => 'Guam', 'alpha2' => 'GU', 'alpha3' => 'GUM', 'number' => '316' , 'c' => null],
        ['name' => 'Guatemala', 'alpha2' => 'GT', 'alpha3' => 'GTM', 'number' => '320' , 'c' => null],
        ['name' => 'Guernsey', 'alpha2' => 'GG', 'alpha3' => 'GGY', 'number' => '831' , 'c' => null],
        ['name' => 'Guinea', 'alpha2' => 'GN', 'alpha3' => 'GIN', 'number' => '324' , 'c' => null],
        ['name' => 'Guinea-Bissau', 'alpha2' => 'GW', 'alpha3' => 'GNB', 'number' => '624' , 'c' => null],
        ['name' => 'Guyana', 'alpha2' => 'GY', 'alpha3' => 'GUY', 'number' => '328' , 'c' => null],
        ['name' => 'Haiti', 'alpha2' => 'HT', 'alpha3' => 'HTI', 'number' => '332' , 'c' => null],
        ['name' => 'Heard Island and McDonald Islands', 'alpha2' => 'HM', 'alpha3' => 'HMD', 'number' => '334' , 'c' => null],
        ['name' => 'Holy See', 'alpha2' => 'VA', 'alpha3' => 'VAT', 'number' => '336' , 'c' => null],
        ['name' => 'Honduras', 'alpha2' => 'HN', 'alpha3' => 'HND', 'number' => '340' , 'c' => null],
        ['name' => 'Hong Kong', 'alpha2' => 'HK', 'alpha3' => 'HKG', 'number' => '344' , 'c' => null],
        ['name' => 'Hungary', 'alpha2' => 'HU', 'alpha3' => 'HUN', 'number' => '348' , 'c' => null],
        ['name' => 'Iceland', 'alpha2' => 'IS', 'alpha3' => 'ISL', 'number' => '352' , 'c' => null],
        ['name' => 'India', 'alpha2' => 'IN', 'alpha3' => 'IND', 'number' => '356' , 'c' => null],
        ['name' => 'Indonesia', 'alpha2' => 'ID', 'alpha3' => 'IDN', 'number' => '360' , 'c' => null],
        ['name' => 'Iran (Islamic Republic of)', 'alpha2' => 'IR', 'alpha3' => 'IRN', 'number' => '364' , 'c' => null],
        ['name' => 'Iraq', 'alpha2' => 'IQ', 'alpha3' => 'IRQ', 'number' => '368' , 'c' => null],
        ['name' => 'Ireland', 'alpha2' => 'IE', 'alpha3' => 'IRL', 'number' => '372' , 'c' => null],
        ['name' => 'Isle of Man', 'alpha2' => 'IM', 'alpha3' => 'IMN', 'number' => '833' , 'c' => null],
        ['name' => 'Israel', 'alpha2' => 'IL', 'alpha3' => 'ISR', 'number' => '376' , 'c' => null],
        ['name' => 'Italy', 'alpha2' => 'IT', 'alpha3' => 'ITA', 'number' => '380' , 'c' => null],
        ['name' => 'Jamaica', 'alpha2' => 'JM', 'alpha3' => 'JAM', 'number' => '388' , 'c' => null],
        ['name' => 'Japan', 'alpha2' => 'JP', 'alpha3' => 'JPN', 'number' => '392' , 'c' => null],
        ['name' => 'Jersey', 'alpha2' => 'JE', 'alpha3' => 'JEY', 'number' => '832' , 'c' => null],
        ['name' => 'Jordan', 'alpha2' => 'JO', 'alpha3' => 'JOR', 'number' => '400' , 'c' => null],
        ['name' => 'Kazakhstan', 'alpha2' => 'KZ', 'alpha3' => 'KAZ', 'number' => '398' , 'c' => null],
        ['name' => 'Kenya', 'alpha2' => 'KE', 'alpha3' => 'KEN', 'number' => '404' , 'c' => null],
        ['name' => 'Kiribati', 'alpha2' => 'KI', 'alpha3' => 'KIR', 'number' => '296' , 'c' => null],
        ['name' => 'Korea (Democratic People\'s Republic of)', 'alpha2' => 'KP', 'alpha3' => 'PRK', 'number' => '408' , 'c' => null],
        ['name' => 'Korea (Republic of)', 'alpha2' => 'KR', 'alpha3' => 'KOR', 'number' => '410' , 'c' => null],
        ['name' => 'Kuwait', 'alpha2' => 'KW', 'alpha3' => 'KWT', 'number' => '414' , 'c' => null],
        ['name' => 'Kyrgyzstan', 'alpha2' => 'KG', 'alpha3' => 'KGZ', 'number' => '417' , 'c' => null],
        ['name' => 'Lao People\'s Democratic Republic', 'alpha2' => 'LA', 'alpha3' => 'LAO', 'number' => '418' , 'c' => null],
        ['name' => 'Latvia', 'alpha2' => 'LV', 'alpha3' => 'LVA', 'number' => '428' , 'c' => null],
        ['name' => 'Lebanon', 'alpha2' => 'LB', 'alpha3' => 'LBN', 'number' => '422' , 'c' => null],
        ['name' => 'Lesotho', 'alpha2' => 'LS', 'alpha3' => 'LSO', 'number' => '426' , 'c' => null],
        ['name' => 'Liberia', 'alpha2' => 'LR', 'alpha3' => 'LBR', 'number' => '430' , 'c' => null],
        ['name' => 'Libya', 'alpha2' => 'LY', 'alpha3' => 'LBY', 'number' => '434' , 'c' => null],
        ['name' => 'Liechtenstein', 'alpha2' => 'LI', 'alpha3' => 'LIE', 'number' => '438' , 'c' => null],
        ['name' => 'Lithuania', 'alpha2' => 'LT', 'alpha3' => 'LTU', 'number' => '440' , 'c' => null],
        ['name' => 'Luxembourg', 'alpha2' => 'LU', 'alpha3' => 'LUX', 'number' => '442' , 'c' => null],
        ['name' => 'Macao', 'alpha2' => 'MO', 'alpha3' => 'MAC', 'number' => '446' , 'c' => null],
        ['name' => 'North Macedonia', 'alpha2' => 'MK', 'alpha3' => 'MKD', 'number' => '807' , 'c' => null],
        ['name' => 'Madagascar', 'alpha2' => 'MG', 'alpha3' => 'MDG', 'number' => '450' , 'c' => null],
        ['name' => 'Malawi', 'alpha2' => 'MW', 'alpha3' => 'MWI', 'number' => '454' , 'c' => null],
        ['name' => 'Malaysia', 'alpha2' => 'MY', 'alpha3' => 'MYS', 'number' => '458' , 'c' => null],
        ['name' => 'Maldives', 'alpha2' => 'MV', 'alpha3' => 'MDV', 'number' => '462' , 'c' => null],
        ['name' => 'Mali', 'alpha2' => 'ML', 'alpha3' => 'MLI', 'number' => '466' , 'c' => null],
        ['name' => 'Malta', 'alpha2' => 'MT', 'alpha3' => 'MLT', 'number' => '470' , 'c' => null],
        ['name' => 'Marshall Islands', 'alpha2' => 'MH', 'alpha3' => 'MHL', 'number' => '584' , 'c' => null],
        ['name' => 'Martinique', 'alpha2' => 'MQ', 'alpha3' => 'MTQ', 'number' => '474' , 'c' => null],
        ['name' => 'Mauritania', 'alpha2' => 'MR', 'alpha3' => 'MRT', 'number' => '478' , 'c' => null],
        ['name' => 'Mauritius', 'alpha2' => 'MU', 'alpha3' => 'MUS', 'number' => '480' , 'c' => null],
        ['name' => 'Mayotte', 'alpha2' => 'YT', 'alpha3' => 'MYT', 'number' => '175' , 'c' => null],
        ['name' => 'Mexico', 'alpha2' => 'MX', 'alpha3' => 'MEX', 'number' => '484' , 'c' => null],
        ['name' => 'Micronesia (Federated States of)', 'alpha2' => 'FM', 'alpha3' => 'FSM', 'number' => '583' , 'c' => null],
        ['name' => 'Moldova (Republic of)', 'alpha2' => 'MD', 'alpha3' => 'MDA', 'number' => '498' , 'c' => null],
        ['name' => 'Monaco', 'alpha2' => 'MC', 'alpha3' => 'MCO', 'number' => '492' , 'c' => null],
        ['name' => 'Mongolia', 'alpha2' => 'MN', 'alpha3' => 'MNG', 'number' => '496' , 'c' => null],
        ['name' => 'Montenegro', 'alpha2' => 'ME', 'alpha3' => 'MNE', 'number' => '499' , 'c' => null],
        ['name' => 'Montserrat', 'alpha2' => 'MS', 'alpha3' => 'MSR', 'number' => '500' , 'c' => null],
        ['name' => 'Morocco', 'alpha2' => 'MA', 'alpha3' => 'MAR', 'number' => '504' , 'c' => null],
        ['name' => 'Mozambique', 'alpha2' => 'MZ', 'alpha3' => 'MOZ', 'number' => '508' , 'c' => null],
        ['name' => 'Myanmar', 'alpha2' => 'MM', 'alpha3' => 'MMR', 'number' => '104' , 'c' => null],
        ['name' => 'Namibia', 'alpha2' => 'NA', 'alpha3' => 'NAM', 'number' => '516' , 'c' => null],
        ['name' => 'Nauru', 'alpha2' => 'NR', 'alpha3' => 'NRU', 'number' => '520' , 'c' => null],
        ['name' => 'Nepal', 'alpha2' => 'NP', 'alpha3' => 'NPL', 'number' => '524' , 'c' => null],
        ['name' => 'Netherlands', 'alpha2' => 'NL', 'alpha3' => 'NLD', 'number' => '528' , 'c' => null],
        ['name' => 'New Caledonia', 'alpha2' => 'NC', 'alpha3' => 'NCL', 'number' => '540' , 'c' => null],
        ['name' => 'New Zealand', 'alpha2' => 'NZ', 'alpha3' => 'NZL', 'number' => '554' , 'c' => null],
        ['name' => 'Nicaragua', 'alpha2' => 'NI', 'alpha3' => 'NIC', 'number' => '558' , 'c' => null],
        ['name' => 'Niger', 'alpha2' => 'NE', 'alpha3' => 'NER', 'number' => '562' , 'c' => null],
        ['name' => 'Nigeria', 'alpha2' => 'NG', 'alpha3' => 'NGA', 'number' => '566' , 'c' => null],
        ['name' => 'Niue', 'alpha2' => 'NU', 'alpha3' => 'NIU', 'number' => '570' , 'c' => null],
        ['name' => 'Norfolk Island', 'alpha2' => 'NF', 'alpha3' => 'NFK', 'number' => '574' , 'c' => null],
        ['name' => 'Northern Mariana Islands', 'alpha2' => 'MP', 'alpha3' => 'MNP', 'number' => '580' , 'c' => null],
        ['name' => 'Norway', 'alpha2' => 'NO', 'alpha3' => 'NOR', 'number' => '578' , 'c' => null],
        ['name' => 'Oman', 'alpha2' => 'OM', 'alpha3' => 'OMN', 'number' => '512' , 'c' => null],
        ['name' => 'Pakistan', 'alpha2' => 'PK', 'alpha3' => 'PAK', 'number' => '586' , 'c' => null],
        ['name' => 'Palau', 'alpha2' => 'PW', 'alpha3' => 'PLW', 'number' => '585' , 'c' => null],
        ['name' => 'Palestine, State of', 'alpha2' => 'PS', 'alpha3' => 'PSE', 'number' => '275' , 'c' => null],
        ['name' => 'Panama', 'alpha2' => 'PA', 'alpha3' => 'PAN', 'number' => '591' , 'c' => null],
        ['name' => 'Papua New Guinea', 'alpha2' => 'PG', 'alpha3' => 'PNG', 'number' => '598' , 'c' => null],
        ['name' => 'Paraguay', 'alpha2' => 'PY', 'alpha3' => 'PRY', 'number' => '600' , 'c' => null],
        ['name' => 'Peru', 'alpha2' => 'PE', 'alpha3' => 'PER', 'number' => '604' , 'c' => null],
        ['name' => 'Philippines', 'alpha2' => 'PH', 'alpha3' => 'PHL', 'number' => '608' , 'c' => null],
        ['name' => 'Pitcairn', 'alpha2' => 'PN', 'alpha3' => 'PCN', 'number' => '612' , 'c' => null],
        ['name' => 'Poland', 'alpha2' => 'PL', 'alpha3' => 'POL', 'number' => '616' , 'c' => null],
        ['name' => 'Portugal', 'alpha2' => 'PT', 'alpha3' => 'PRT', 'number' => '620' , 'c' => null],
        ['name' => 'Puerto Rico', 'alpha2' => 'PR', 'alpha3' => 'PRI', 'number' => '630' , 'c' => null],
        ['name' => 'Qatar', 'alpha2' => 'QA', 'alpha3' => 'QAT', 'number' => '634' , 'c' => null],
        ['name' => 'Réunion', 'alpha2' => 'RE', 'alpha3' => 'REU', 'number' => '638' , 'c' => null],
        ['name' => 'Romania', 'alpha2' => 'RO', 'alpha3' => 'ROU', 'number' => '642' , 'c' => null],
        ['name' => 'Russian Federation', 'alpha2' => 'RU', 'alpha3' => 'RUS', 'number' => '643' , 'c' => null],
        ['name' => 'Rwanda', 'alpha2' => 'RW', 'alpha3' => 'RWA', 'number' => '646' , 'c' => null],
        ['name' => 'Saint Barthélemy', 'alpha2' => 'BL', 'alpha3' => 'BLM', 'number' => '652' , 'c' => null],
        ['name' => 'Saint Helena, Ascension and Tristan da Cunha', 'alpha2' => 'SH', 'alpha3' => 'SHN', 'number' => '654' , 'c' => null],
        ['name' => 'Saint Kitts and Nevis', 'alpha2' => 'KN', 'alpha3' => 'KNA', 'number' => '659' , 'c' => null],
        ['name' => 'Saint Lucia', 'alpha2' => 'LC', 'alpha3' => 'LCA', 'number' => '662' , 'c' => null],
        ['name' => 'Saint Martin (French part)', 'alpha2' => 'MF', 'alpha3' => 'MAF', 'number' => '663' , 'c' => null],
        ['name' => 'Saint Pierre and Miquelon', 'alpha2' => 'PM', 'alpha3' => 'SPM', 'number' => '666' , 'c' => null],
        ['name' => 'Saint Vincent and the Grenadines', 'alpha2' => 'VC', 'alpha3' => 'VCT', 'number' => '670' , 'c' => null],
        ['name' => 'Samoa', 'alpha2' => 'WS', 'alpha3' => 'WSM', 'number' => '882' , 'c' => null],
        ['name' => 'San Marino', 'alpha2' => 'SM', 'alpha3' => 'SMR', 'number' => '674' , 'c' => null],
        ['name' => 'Sao Tome and Principe', 'alpha2' => 'ST', 'alpha3' => 'STP', 'number' => '678' , 'c' => null],
        ['name' => 'Saudi Arabia', 'alpha2' => 'SA', 'alpha3' => 'SAU', 'number' => '682' , 'c' => null],
        ['name' => 'Senegal', 'alpha2' => 'SN', 'alpha3' => 'SEN', 'number' => '686' , 'c' => null],
        ['name' => 'Serbia', 'alpha2' => 'RS', 'alpha3' => 'SRB', 'number' => '688' , 'c' => null],
        ['name' => 'Seychelles', 'alpha2' => 'SC', 'alpha3' => 'SYC', 'number' => '690' , 'c' => null],
        ['name' => 'Sierra Leone', 'alpha2' => 'SL', 'alpha3' => 'SLE', 'number' => '694' , 'c' => null],
        ['name' => 'Singapore', 'alpha2' => 'SG', 'alpha3' => 'SGP', 'number' => '702' , 'c' => null],
        ['name' => 'Sint Maarten (Dutch part)', 'alpha2' => 'SX', 'alpha3' => 'SXM', 'number' => '534' , 'c' => null],
        ['name' => 'Slovakia', 'alpha2' => 'SK', 'alpha3' => 'SVK', 'number' => '703' , 'c' => null],
        ['name' => 'Slovenia', 'alpha2' => 'SI', 'alpha3' => 'SVN', 'number' => '705' , 'c' => null],
        ['name' => 'Solomon Islands', 'alpha2' => 'SB', 'alpha3' => 'SLB', 'number' => '090' , 'c' => null],
        ['name' => 'Somalia', 'alpha2' => 'SO', 'alpha3' => 'SOM', 'number' => '706' , 'c' => null],
        ['name' => 'South Africa', 'alpha2' => 'ZA', 'alpha3' => 'ZAF', 'number' => '710' , 'c' => null],
        ['name' => 'South Georgia and the South Sandwich Islands', 'alpha2' => 'GS', 'alpha3' => 'SGS', 'number' => '239' , 'c' => null],
        ['name' => 'South Sudan', 'alpha2' => 'SS', 'alpha3' => 'SSD', 'number' => '728' , 'c' => null],
        ['name' => 'Spain', 'alpha2' => 'ES', 'alpha3' => 'ESP', 'number' => '724' , 'c' => null],
        ['name' => 'Sri Lanka', 'alpha2' => 'LK', 'alpha3' => 'LKA', 'number' => '144' , 'c' => null],
        ['name' => 'Sudan', 'alpha2' => 'SD', 'alpha3' => 'SDN', 'number' => '729' , 'c' => null],
        ['name' => 'Suriname', 'alpha2' => 'SR', 'alpha3' => 'SUR', 'number' => '740' , 'c' => null],
        ['name' => 'Svalbard and Jan Mayen', 'alpha2' => 'SJ', 'alpha3' => 'SJM', 'number' => '744' , 'c' => null],
        ['name' => 'Sweden', 'alpha2' => 'SE', 'alpha3' => 'SWE', 'number' => '752' , 'c' => null],
        ['name' => 'Switzerland', 'alpha2' => 'CH', 'alpha3' => 'CHE', 'number' => '756' , 'c' => null],
        ['name' => 'Syrian Arab Republic', 'alpha2' => 'SY', 'alpha3' => 'SYR', 'number' => '760' , 'c' => null],
        ['name' => 'Taiwan (Province of China)', 'alpha2' => 'TW', 'alpha3' => 'TWN', 'number' => '158' , 'c' => null],
        ['name' => 'Tajikistan', 'alpha2' => 'TJ', 'alpha3' => 'TJK', 'number' => '762' , 'c' => null],
        ['name' => 'Tanzania, United Republic of', 'alpha2' => 'TZ', 'alpha3' => 'TZA', 'number' => '834' , 'c' => null],
        ['name' => 'Thailand', 'alpha2' => 'TH', 'alpha3' => 'THA', 'number' => '764' , 'c' => null],
        ['name' => 'Timor-Leste', 'alpha2' => 'TL', 'alpha3' => 'TLS', 'number' => '626' , 'c' => null],
        ['name' => 'Togo', 'alpha2' => 'TG', 'alpha3' => 'TGO', 'number' => '768' , 'c' => null],
        ['name' => 'Tokelau', 'alpha2' => 'TK', 'alpha3' => 'TKL', 'number' => '772' , 'c' => null],
        ['name' => 'Tonga', 'alpha2' => 'TO', 'alpha3' => 'TON', 'number' => '776' , 'c' => null],
        ['name' => 'Trinidad and Tobago', 'alpha2' => 'TT', 'alpha3' => 'TTO', 'number' => '780' , 'c' => null],
        ['name' => 'Tunisia', 'alpha2' => 'TN', 'alpha3' => 'TUN', 'number' => '788' , 'c' => null],
        ['name' => 'Turkey', 'alpha2' => 'TR', 'alpha3' => 'TUR', 'number' => '792' , 'c' => null],
        ['name' => 'Turkmenistan', 'alpha2' => 'TM', 'alpha3' => 'TKM', 'number' => '795' , 'c' => null],
        ['name' => 'Turks and Caicos Islands', 'alpha2' => 'TC', 'alpha3' => 'TCA', 'number' => '796' , 'c' => null],
        ['name' => 'Tuvalu', 'alpha2' => 'TV', 'alpha3' => 'TUV', 'number' => '798' , 'c' => null],
        ['name' => 'Uganda', 'alpha2' => 'UG', 'alpha3' => 'UGA', 'number' => '800' , 'c' => null],
        ['name' => 'Ukraine', 'alpha2' => 'UA', 'alpha3' => 'UKR', 'number' => '804' , 'c' => null],
        ['name' => 'United Arab Emirates', 'alpha2' => 'AE', 'alpha3' => 'ARE', 'number' => '784' , 'c' => null],
        ['name' => 'United Kingdom of Great Britain and Northern Ireland', 'alpha2' => 'GB', 'alpha3' => 'GBR', 'number' => '826' , 'c' => null],
        ['name' => 'United States of America', 'alpha2' => 'US', 'alpha3' => 'USA', 'number' => '840' , 'c' => null],
        ['name' => 'United States Minor Outlying Islands', 'alpha2' => 'UM', 'alpha3' => 'UMI', 'number' => '581' , 'c' => null],
        ['name' => 'Uruguay', 'alpha2' => 'UY', 'alpha3' => 'URY', 'number' => '858' , 'c' => null],
        ['name' => 'Uzbekistan', 'alpha2' => 'UZ', 'alpha3' => 'UZB', 'number' => '860' , 'c' => null],
        ['name' => 'Vanuatu', 'alpha2' => 'VU', 'alpha3' => 'VUT', 'number' => '548' , 'c' => null],
        ['name' => 'Venezuela (Bolivarian Republic of)', 'alpha2' => 'VE', 'alpha3' => 'VEN', 'number' => '862' , 'c' => null],
        ['name' => 'Viet Nam', 'alpha2' => 'VN', 'alpha3' => 'VNM', 'number' => '704' , 'c' => null],
        ['name' => 'Virgin Islands (British)', 'alpha2' => 'VG', 'alpha3' => 'VGB', 'number' => '092' , 'c' => null],
        ['name' => 'Virgin Islands (U.S.)', 'alpha2' => 'VI', 'alpha3' => 'VIR', 'number' => '850' , 'c' => null],
        ['name' => 'Wallis and Futuna', 'alpha2' => 'WF', 'alpha3' => 'WLF', 'number' => '876' , 'c' => null],
        ['name' => 'Western Sahara', 'alpha2' => 'EH', 'alpha3' => 'ESH', 'number' => '732' , 'c' => null],
        ['name' => 'Yemen', 'alpha2' => 'YE', 'alpha3' => 'YEM', 'number' => '887' , 'c' => null],
        ['name' => 'Zambia', 'alpha2' => 'ZM', 'alpha3' => 'ZMB', 'number' => '894' , 'c' => null],
        ['name' => 'Zimbabwe', 'alpha2' => 'ZW', 'alpha3' => 'ZWE', 'number' => '716' , 'c' => null],
    ];
    
    
    
}
