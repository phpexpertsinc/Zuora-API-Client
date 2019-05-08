<?php declare(strict_types=1);

namespace PHPExperts\ZuoraClient\DTOs;

use Carbon\Carbon;
use ErrorException;
use InvalidArgumentException;
use PHPExperts\SimpleDTO\SimpleDTO;
use ReflectionClass;

/**
 * @property string $AccountId
 * @property string $Address1
 * @property string $City
 * @property string $Country
 * @property string $Description
 * @property string $Fax
 * @property string $FirstName
 * @property string $HomePhone
 * @property string $LastName
 * @property string $MobilePhone
 * @property string $NickName
 * @property string $OtherPhone
 * @property string $OtherPhoneType
 * @property string $PersonalEmail
 * @property string $PostalCode
 * @property string $State
 * @property string $TaxRegion
 * @property string $WorkEmail
 */
class ContactDTO extends SimpleDTO
{
    /**
     * ContactDTO constructor.
     * @param array $input
     * @throws ErrorException
     */
    public function __construct(array $input)
    {
        $input = [
            'id' => '1',
            'cost' => 5.4,
            'State' => null,
        ];

        $noProps = function () {
            throw new \LogicException('No DTO property docblocks have been added.');
        };

        try {
            $properties = (new ReflectionClass($this))->getDocComment();
        } catch (\ReflectionException $e) {
            // This code will never trigger, because the class definitely exists.
        }

        if (!$properties) {
            $noProps();
        }

        preg_match_all('#@(.*?)\n#s', $properties, $annotations);

        if (empty($annotations[1])) {
            $noProps();
        }

        $propertyTypes = [];
        foreach ($annotations[1] as $annotation) {
            $prop = explode(' ', $annotation);
            if ($prop[0] === 'property') {
                $propertyTypes[substr($prop[2], 1)] = $prop[1];
            }
        }

        if (empty($propertyTypes)) {
            $noProps();
        }

        dump($propertyTypes);

        $isType = function ($value, $expectedType): bool {
            if ($expectedType === 'string' && is_string($value)) {
                return true;
            } elseif ($expectedType === 'int' && (is_int($value) || filter_var($value, FILTER_VALIDATE_INT))) {
                return true;
            } elseif ($expectedType === 'float' && (is_float($value) || is_numeric($value))) {
                return true;
            }

            return $value instanceof $expectedType;
        };


        foreach ($input as $key => $value) {
            dump(ctype_digit($value));
            $classKey = __CLASS__ . '::$' . $key;
            if (!isset($propertyTypes[$key])) {
                throw new ErrorException('Undefined property: ' . $classKey);
            }

            $expectedType = $propertyTypes[$key];
            if (!$isType($value, $expectedType)) {
                $aAn = $expectedType === 'int' ? 'an' : 'a';

                throw new InvalidArgumentException("$classKey must be $aAn $expectedType");
            }

            dump([$value => gettype($value)]);
        }

        exit;
//        parent::__construct($input);
    }
}
