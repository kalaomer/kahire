<?php namespace Kahire\Serializers\Fields;

use Carbon\Carbon;
use DateTime;
use InvalidArgumentException;
use Kahire\Serializers\Fields\Attributes\AfterAttribute;
use Kahire\Serializers\Fields\Attributes\BeforeAttribute;
use Kahire\Serializers\Fields\Exceptions\ValidationError;

/**
 * Class DateTimeField
 * @package Kahire\Serializers\Fields
 */
class DateTimeField extends Field {

    use AfterAttribute, BeforeAttribute;

    /**
     * @var array
     */
    protected $validationRules = [ "date" ];

    /**
     * @var array
     */
    protected $inputFormats = [
        'Y-m-d H:i:s',          // '2006-10-25 14:30:59'
        'Y-m-d H:i:s.u',        // '2006-10-25 14:30:59.000200'
        'Y-m-d H:i',            // '2006-10-25 14:30'
        'Y-m-d',                // '2006-10-25'
        'm/d/Y H:i:s',          // '10/25/2006 14:30:59'
        'm/d/Y H:i:s.u',        // '10/25/2006 14:30:59.000200'
        'm/d/Y H:i',            // '10/25/2006 14:30'
        'm/d/Y',                // '10/25/2006'
        'm/d/y H:i:s',          // '10/25/06 14:30:59'
        'm/d/y H:i:s.u',        // '10/25/06 14:30:59.000200'
        'm/d/y H:i',            // '10/25/06 14:30'
        'm/d/y',                // '10/25/06'
    ];

    /**
     * @var string
     */
    protected $outputFormat = "Y-m-d H:i:s";


    /**
     * @param $dateString string
     *
     * @return Carbon
     */
    protected function createDateTimeFromString($dateString)
    {
        foreach ($this->inputFormats as $inputFormat)
        {
            try {
                return Carbon::createFromFormat($inputFormat, $dateString);
            } catch (InvalidArgumentException $e) {
            }
        }

        return false;
    }


    /**
     * @param $value
     *
     * @return Carbon
     * @throws ValidationError
     */
    public function toInternalValue($value)
    {
        if ( $value instanceof DateTime )
        {
            return $value;
        }

        $date = $this->createDateTimeFromString($value);

        if ( $date === false )
        {
            throw new ValidationError("value is not matched with datetime formats.");
        }

        return $date;
    }


    /**
     * @param $value
     *
     * @return null|string
     */
    public function toRepresentation($value)
    {
        if ( $value instanceof DateTime )
        {
            return $value->format($this->outputFormat);
        }

        $date = $this->createDateTimeFromString($value);

        if ( $date === false )
        {
            return null;
        }

        return $date->format($this->outputFormat);
    }


    /**
     * @param $data DateTime
     *
     * @throws ValidationError
     */
    public function runValidationClause($data)
    {
        $data = $data->format($this->outputFormat);

        return parent::runValidationClause($data);
    }

    protected function getDateFormatValidationRule()
    {
        return ["date_format" => $this->outputFormat];
    }

}