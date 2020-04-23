<?php
/**
 * Copyright (c) 2019. Stratusolve (Pty) Ltd, South Africa
 * This file is the property of Stratusolve (Pty) Ltd.
 * This file may not be used or included in any project prior to the signing of the divblox software license agreement.
 * By using this file or including it in your project you agree to the terms and conditions stipulated by the divblox software license agreement.
 * This file may not be copied or modified in any way without prior written permission from Stratusolve (Pty) Ltd
 * THIS FILE SHOULD NOT BE EDITED. divblox assumes the integrity of this file. If you edit this file, it could be overridden by a future divblox update
 * For queries please send an email to support@divblox.com
 */

require_once(FRAMEWORK_ROOT_STR.'/assets/php/third_party/htmlpurifier-4.10.0/library/HTMLPurifier.auto.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require(FRAMEWORK_ROOT_STR.'/assets/php/third_party/vendor/autoload.php');

//region Core framework classes
abstract class dxBaseClass {
    /**
     * Override method to perform a property "Get"
     * This will get the value of $strName
     * All inhereted objects that call __get() should always fall through
     * to calling parent::__get() in a try/catch statement catching
     * for CallerExceptions.
     *
     * @param string $strName Name of the property to get
     *
     * @throws dxUndefinedPropertyException
     * @return mixed the returned property
     */
    public function __get($strName) {
        $objReflection = new ReflectionClass($this);
        throw new dxUndefinedPropertyException("GET", $objReflection->getName(), $strName);
    }

    /**
     * Override method to perform a property "Set"
     * This will set the property $strName to be $mixValue
     * All inhereted objects that call __set() should always fall through
     * to calling parent::__set() in a try/catch statement catching
     * for CallerExceptions.
     *
     * @param string $strName  Name of the property to set
     * @param string $mixValue New value of the property
     *
     * @throws dxUndefinedPropertyException
     * @return mixed the property that was set
     */
    public function __set($strName, $mixValue) {
        $objReflection = new ReflectionClass($this);
        throw new dxUndefinedPropertyException("SET", $objReflection->getName(), $strName);
    }

    /**
     * This allows you to set any properties, given by a name-value pair list
     * in mixOverrideArray.
     * Each item in mixOverrideArray needs to be either a string in the format
     * of Property=Value or an array in the format of array(Property => Value).
     * OverrideAttributes() will basically call
     * $this->Property = Value for each string element in the array.
     * Value can be surrounded by quotes... but this is optional.
     *
     * @param $mixOverrideArray
     *
     * @throws dxCallerException
     * @throws Exception|dxCallerException
     * @internal param \mixed[] $objOverrideArray the array of name-value pair items of properties/attributes to override
     * @return void
     */
    public final function OverrideAttributes($mixOverrideArray) {
        // Iterate through the OverrideAttribute Array
        if ($mixOverrideArray) foreach ($mixOverrideArray as $mixOverrideItem) {
            if (is_array($mixOverrideItem)) {
                foreach ($mixOverrideItem as $strKey=>$mixValue)
                    // Apply the override
                    try {
                        $this->__set($strKey, $mixValue);
                    } catch (dxCallerException $objExc) {
                        $objExc->IncrementOffset();
                        throw $objExc;
                    }
            } else {
                // Extract the Key and Value for this OverrideAttribute
                $intPosition = strpos($mixOverrideItem, "=");
                if ($intPosition === false)
                    throw new dxCallerException(sprintf("Improperly formatted OverrideAttribute: %s", $mixOverrideItem));
                $strKey = substr($mixOverrideItem, 0, $intPosition);
                $mixValue = substr($mixOverrideItem, $intPosition + 1);

                // Ensure that the Value is properly formatted (unquoted, single-quoted, or double-quoted)
                if (substr($mixValue, 0, 1) == "'") {
                    if (substr($mixValue, strlen($mixValue) - 1) != "'")
                        throw new dxCallerException(sprintf("Improperly formatted OverrideAttribute: %s", $mixOverrideItem));
                    $mixValue = substr($mixValue, 1, strlen($mixValue) - 2);
                } else if (substr($mixValue, 0, 1) == '"') {
                    if (substr($mixValue, strlen($mixValue) - 1) != '"')
                        throw new dxCallerException(sprintf("Improperly formatted OverrideAttribute: %s", $mixOverrideItem));
                    $mixValue = substr($mixValue, 1, strlen($mixValue) - 2);
                }

                // Apply the override
                try {
                    $this->__set($strKey, $mixValue);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
            }
        }
    }
}
class dxErrorAttribute {
    public $Label;
    public $Contents;
    public $MultiLine;

    public function __construct($strLabel, $strContents, $blnMultiLine) {
        $this->Label = $strLabel;
        $this->Contents = $strContents;
        $this->MultiLine = $blnMultiLine;
    }
}
/**
 * @property-read integer $Offset
 * @property-read mixed $BackTrace
 * @property-read string $Query
 */
class dxDataBindException extends Exception {
    private $intOffset;
    private $strTraceArray;
    private $strQuery;

    public function __construct(dxCallerException $objExc) {
        parent::__construct($objExc->getMessage(), $objExc->getCode());
        $this->intOffset = $objExc->Offset;
        $this->strTraceArray = $objExc->TraceArray;

        if ($objExc instanceof dxDatabaseExceptionBase)
            $this->strQuery = $objExc->Query;

        $this->file = $this->strTraceArray[$this->intOffset]['file'];
        $this->line = $this->strTraceArray[$this->intOffset]['line'];
    }

    public function __get($strName) {
        switch($strName) {
            case "Offset":
                return $this->intOffset;

            case "BackTrace":
                $objTraceArray = debug_backtrace();
                return (var_export($objTraceArray, true));

            case "Query":
                return $this->strQuery;
        }
    }
}
/**
 * This is the main exception to be thrown by any
 * method to indicate that the CALLER is responsible for
 * causing the exception.  This works in conjunction with divblox's
 * error handling/reporting, so that the correct file/line-number is
 * displayed to the user.
 *
 * So for example, for a class that contains the method GetItemAtIndex($intIndex),
 * it is conceivable that the caller could call GetItemAtIndex(15), where 15 does not exist.
 * GetItemAtIndex would then thrown an IndexOutOfRangeException (which extends CallerException).
 * If the CallerException is not caught, then the Exception will be reported to the user.  The CALLER
 * (the script who CALLED GetItemAtIndex) would have that line highlighted as being responsible
 * for calling the error.
 *
 * The PHP default for exception reporting would normally say that the "throw Exception" line in GetItemAtIndex
 * is responsible for throwing the exception.  While this is technically true, in reality, it was the line that
 * CALLED GetItemAtIndex which is responsible.  In short, this allows for much cleaner exception reporting.
 *
 * On a more in-depth note, in general, suppose a method OuterMethod takes in parameters, and ends up passing those
 * paremeters into ANOTHER method InnerMethod which could throw a CallerException.  OuterMethod is responsible
 * for catching and rethrowing the caller exception.  And when this is done, IncrementOffset() MUST be called on
 * the exception object, to indicate that OuterMethod's CALLER is responsible for the exception.
 *
 * So the code snippet to call InnerMethod by OuterMethod should look like:
 *	function OuterMethod($mixValue) {
 *		try {
 *			InnerMethod($mixValue);
 *		} catch (CallerException $objExc) {
 *			$objExc->IncrementOffset();
 *			throw $objExc;
 *		}
 *		// Do Other Stuff
 *	}
 * Again, this will assure the user that the line of code responsible for the exception is properly being reported
 * by the divblox error reporting/handler.
 * @property-read int $Offset The exception offset.
 * @property-read string $BackTrace The exception backtrace.
 * @property-read string $TraceArray The exception backtrace in a form of an array.
 */
class dxCallerException extends Exception {
    private $intOffset;
    private $strTraceArray;

    public function setMessage($strMessage) {
        $this->message = $strMessage;
    }

    /**
     * The constructor of CallerExceptions.  Takes in a message string
     * as well as an optional Offset parameter (defaults to 0).
     * The Offset specificies how many calls up the call stack is responsible
     * for the exception.
     *
     * Normally, the Offset would be altered by calls to IncrementOffset
     * at every step the CallerException is caught/rethrown up the call stack.
     * @param string $strMessage the Message of the exception
     * @param integer $intOffset the optional Offset value (currently defaulted to 0)
     * @return dxCallerException the new exception
     */
    public function __construct($strMessage, $intOffset = 1) {
        parent::__construct($strMessage);
        $this->intOffset = $intOffset;
        $this->strTraceArray = debug_backtrace();

        $this->file = $this->strTraceArray[$this->intOffset]['file'];
        $this->line = $this->strTraceArray[$this->intOffset]['line'];
    }

    public function IncrementOffset() {
        $this->intOffset++;
        if (array_key_exists('file', $this->strTraceArray[$this->intOffset]))
            $this->file = $this->strTraceArray[$this->intOffset]['file'];
        else
            $this->file = '';
        if (array_key_exists('line', $this->strTraceArray[$this->intOffset]))
            $this->line = $this->strTraceArray[$this->intOffset]['line'];
        else
            $this->line = '';
    }

    public function DecrementOffset() {
        $this->intOffset--;
        if (array_key_exists('file', $this->strTraceArray[$this->intOffset]))
            $this->file = $this->strTraceArray[$this->intOffset]['file'];
        else
            $this->file = '';
        if (array_key_exists('line', $this->strTraceArray[$this->intOffset]))
            $this->line = $this->strTraceArray[$this->intOffset]['line'];
        else
            $this->line = '';
    }

    public function __get($strName) {
        if ($strName == "Offset")
            return $this->intOffset;
        else if ($strName == "BackTrace") {
            $objTraceArray = debug_backtrace();
            return (var_export($objTraceArray, true));
        } else if ($strName == "TraceArray") {
            return $this->strTraceArray;
        }
    }
}

class dxUndefinedPrimaryKeyException extends dxCallerException {
    /**
     * dxUndefinedPrimaryKeyException constructor.
     * @param $strMessage
     */
    public function __construct($strMessage) {
        parent::__construct($strMessage, 2);
    }
}

class dxIndexOutOfRangeException extends dxCallerException {
    /**
     * dxIndexOutOfRangeException constructor.
     * @param $intIndex
     * @param $strMessage
     */
    public function __construct($intIndex, $strMessage) {
        if ($strMessage)
            $strMessage = ": " . $strMessage;
        parent::__construct(sprintf("Index (%s) is out of range%s", $intIndex, $strMessage), 2);
    }
}

class dxUndefinedPropertyException extends dxCallerException {
    /**
     * dxUndefinedPropertyException constructor.
     * @param $strType
     * @param $strClass
     * @param $strProperty
     */
    public function __construct($strType, $strClass, $strProperty) {
        parent::__construct(sprintf("Undefined %s property or variable in '%s' class: %s", $strType, $strClass, $strProperty), 2);
    }
}

class dxOptimisticLockingException extends dxCallerException {
    /**
     * dxOptimisticLockingException constructor.
     * @param $strClass
     */
    public function __construct($strClass) {
        parent::__construct(sprintf('Optimistic Locking constraint when trying to update %s object.  To update anyway, call ->Save() with $blnForceUpdate set to true', $strClass, 2));
    }
}

class dxRemoteAdminDeniedException extends dxCallerException {
    /**
     * dxRemoteAdminDeniedException constructor.
     */
    public function __construct() {
        parent::__construct('Remote access to "' . FrameworkFunctions::$RequestUri . '" has been disabled.' .
            "\r\nTo allow remote access to this script, set the ALLOW_REMOTE_ADMIN constant to TRUE\r\nor to \"" . $_SERVER['REMOTE_ADDR'] . '" in "configuration.inc.php".', 2);
    }
}

class dxInvalidFormStateException extends dxCallerException {
    /**
     * dxInvalidFormStateException constructor.
     * @param $strFormId
     */
    public function __construct($strFormId) {
        parent::__construct(sprintf('Invalid Form State Data for "%s" object (session may have been lost)', $strFormId), 2);
    }
}

/**
 * The exception that is thrown by dxType::Cast
 * if an invalid cast is performed.  InvalidCastException
 * derives from CallerException, and therefore should be handled
 * similar to how CallerExceptions are handled (e.g. IncrementOffset should
 * be called whenever an InvalidCastException is caught and rethrown).
 */
class dxInvalidCastException extends dxCallerException {
    /**
     * Constructor
     * @param string $strMessage
     * @param int    $intOffset
     */
    public function __construct($strMessage, $intOffset = 2) {
        parent::__construct($strMessage, $intOffset);
    }
}

/**
 * Type Library to add some support for strongly named types.
 *
 * PHP does not support strongly named types.  The divblox type library
 * and divblox typing in general attempts to bring some structure to types
 * when passing in values, properties, parameters to/from divblox framework objects
 * and methods.
 *
 * The Type library attempts to allow as much flexibility as possible to
 * set and cast variables to other types, similar to how PHP does it natively,
 * but simply adds a big more structure to it.
 *
 * For example, regardless if a variable is an integer, boolean, or string,
 * dxType::Cast will allow the flexibility of those values to interchange with
 * each other with little to no issue.
 *
 * In addition to value objects (ints, bools, floats, strings), the Type library
 * also supports object casting.  While technically casting one object to another
 * is not a true cast, dxType::Cast does at least ensure that the tap being "casted"
 * to is a legitamate subclass of the object being "cast".  So if you have ParentClass,
 * and you have a ChildClass that extends ParentClass,
 *		$objChildClass = new ChildClass();
 *		$objParentClass = new ParentClass();
 *		Type::Cast($objChildClass, 'ParentClass'); // is a legal cast
 *		Type::Cast($objParentClass, 'ChildClass'); // will throw an InvalidCastException
 *
 * For values, specifically int to string conversion, one different between
 * dxType::Cast and PHP (in order to add structure) is that if an integer contains
 * alpha characters, PHP would normally allow that through w/o complaint, simply
 * ignoring any numeric characters past the first alpha character.  dxType::Cast
 * would instead throw an InvalidCastException to let the developer immedaitely
 * know that something doesn't look right.
 *
 * In theory, the type library should maintain the same level of flexibility
 * PHP developers are accostomed to, while providing a mechanism to limit
 * careless coding errors and tough to figure out mistakes due to PHP's sometimes
 * overly laxed type conversions.
 */
abstract class dxType {
    /**
     * This faux constructor method throws a caller exception.
     * The Type object should never be instantiated, and this constructor
     * override simply guarantees it.
     *
     * @throws dxCallerException
     * @return \dxType
     */
    public final function __construct() {
        throw new dxCallerException('Type should never be instantiated.  All methods and variables are publically statically accessible.');
    }

    /**
     *
     */
    const String = 'string';
    /**
     *
     */
    const Integer = 'integer';
    /**
     *
     */
    const Float = 'double';
    /**
     *
     */
    const Boolean = 'boolean';
    /**
     *
     */
    const Object = 'object';
    /**
     *
     */
    const ArrayType = 'array';

    /**
     *
     */
    const DateTime = 'dxDateTime';

    /**
     *
     */
    const Resource = 'resource';

    /**
     *
     */
    const NoOp = 1;
    /**
     *
     */
    const CheckOnly = 2;
    /**
     *
     */
    const CastOnly = 3;
    /**
     *
     */
    const CheckAndCast = 4;
    /**
     * @var int
     */
    private static $intBehaviour = dxType::CheckAndCast;

    /**
     * @param $objItem
     * @param $strType
     * @return bool|mixed|string
     * @throws dxInvalidCastException
     */
    private static function CastObjectTo($objItem, $strType) {
        try {
            $objReflection = new ReflectionClass($objItem);
            if ($objReflection->getName() == 'SimpleXMLElement') {
                switch ($strType) {
                    case dxType::String:
                        return (string) $objItem;
                    case dxType::Integer:
                        try {
                            return dxType::Cast((string) $objItem, dxType::Integer);
                        } catch (dxCallerException $objExc) {
                            $objExc->IncrementOffset();
                            throw $objExc;
                        }
                    case dxType::Boolean:
                        $strItem = strtolower(trim((string) $objItem));
                        if (($strItem == 'false') ||
                            (!$strItem))
                            return false;
                        else
                            return true;
                }
            }

            if ($objItem instanceof $strType)
                return $objItem;
        } catch (Exception $objExc) {
        }

        throw new dxInvalidCastException(sprintf('Unable to cast %s object to %s', $objReflection->getName(), $strType));
    }

    /**
     * @param $mixItem
     * @param $strNewType
     * @param float $precision
     * @return mixed
     * @throws dxInvalidCastException
     */
    private static function CastValueTo($mixItem, $strNewType, $precision = 1.0e-7) {
        // Set precision to be smaller when working with greater accuracy. As default, we are happy with 7 decimals
        $strOriginalType = gettype($mixItem);

        switch (dxType::TypeFromDoc($strNewType)) {
            case dxType::Boolean:
                if ($strOriginalType == dxType::Boolean)
                    return $mixItem;
                if (is_null($mixItem))
                    return false;
                if (strlen($mixItem) == 0)
                    return false;
                if (strtolower($mixItem) == 'false')
                    return false;
                settype($mixItem, $strNewType);
                return $mixItem;

            case dxType::Integer:
                if($strOriginalType == dxType::Boolean)
                    throw new dxInvalidCastException(sprintf('Unable to cast %s value to %s: %s', $strOriginalType, $strNewType, $mixItem));
                if (strlen($mixItem) == 0)
                    return null;
                if ($strOriginalType == dxType::Integer)
                    return $mixItem;

                // Check to make sure the value hasn't changed significantly
                $intItem = $mixItem;
                settype($intItem, $strNewType);
                $mixTest = $intItem;
                settype($mixTest, $strOriginalType);

                // If the value hasn't changed, it's safe to return the casted value
                if ((string)$mixTest === (string)$mixItem)
                    return $intItem;

                // if casting changed the value, but we have a valid integer, return with a string cast
                if (preg_match('/^-?\d+$/',$mixItem) === 1)
                    return (string)$mixItem;

                // any other scenarios is an invalid cast
                throw new dxInvalidCastException(sprintf('Unable to cast %s value to %s: %s', $strOriginalType, $strNewType, $mixItem));
            case dxType::Float:
                if($strOriginalType == dxType::Boolean)
                    throw new dxInvalidCastException(sprintf('Unable to cast %s value to %s: %s', $strOriginalType, $strNewType, $mixItem));
                if (strlen($mixItem) == 0)
                    return null;
                if ($strOriginalType == dxType::Float)
                    return $mixItem;

                if (!is_numeric($mixItem))
                    throw new dxInvalidCastException(sprintf('Invalid float: %s', $mixItem));

                // Check to make sure the value hasn't changed significantly
                $fltItem = $mixItem;
                settype($fltItem, $strNewType);
                $mixTest = $fltItem;
                settype($mixTest, $strOriginalType);

                //account for any scientific notation that results
                //find out what notation is currently being used
                $i = strpos($mixItem, '.');
                $precision = ($i === false) ? 0 : strlen($mixItem) - $i - 1;
                //and represent the casted value the same way
                $strTest = sprintf('%.' . $precision . 'f', $fltItem);

                // If the value hasn't changed, it's safe to return the casted value
                if ((string)$strTest === (string)$mixItem)
                    return $fltItem;

                // the changed value could be the result of loosing precision. Return the original value with no cast
                return $mixItem;

            case dxType::String:
                if ($strOriginalType == dxType::String)
                    return $mixItem;

                // Check to make sure the value hasn't changed significantly
                $strItem = $mixItem;
                settype($strItem, $strNewType);
                $mixTest = $strItem;
                settype($mixTest, $strOriginalType);

                // Has it?
                $blnSame = true;
                if ($strOriginalType == dxType::Float) {
                    // type conversion from float to string affects precision and can throw off the comparison
                    // so we need to use a comparison check using an epsilon value instead
                    $epsilon = $precision;
                    $diff = abs($mixItem - $mixTest);
                    if ($diff > $epsilon) {
                        $blnSame = false;
                    }
                }
                else {
                    if ($mixTest != $mixItem)
                        $blnSame = false;
                }
                if (!$blnSame)
                    //This is an invalid cast
                    throw new dxInvalidCastException(sprintf('Unable to cast %s value to %s: %s', $strOriginalType, $strNewType, $mixItem));

                return $strItem;

            default:
                throw new dxInvalidCastException(sprintf('Unable to cast %s value to unknown type %s', $strOriginalType, $strNewType));
        }
    }

    /**
     * @param $arrItem
     * @param $strType
     * @return mixed
     * @throws dxInvalidCastException
     */
    private static function CastArrayTo($arrItem, $strType) {
        if ($strType == dxType::ArrayType)
            return $arrItem;
        else
            throw new dxInvalidCastException(sprintf('Unable to cast Array to %s', $strType));
    }

    /**
     * This method can be used to change the casting behaviour of dxType::Cast().
     * By default dxType::Cast() does lots of validation and type casting (using settype()).
     * Depending on your application you may or may not need validation or casting or both.
     * In these situations you can set the necessary behaviour by passing the appropriate constant to this function.
     *
     * @static
     * @param int $intBehaviour one of the 4 constants dxType::NoOp, dxType::CastOnly, dxType::CheckOnly, dxType::CheckAndCast
     * @return int the previous setting
     */
    public static function SetBehaviour($intBehaviour) {
        $oldBehaviour = dxType::$intBehaviour;
        dxType::$intBehaviour = $intBehaviour;
        return $oldBehaviour;
    }

    /**
     * Used to cast a variable to another type.  Allows for moderate
     * support of strongly-named types.
     *
     * Will throw an exception if the cast fails, causes unexpected side effects,
     * if attempting to cast an object to a value (or vice versa), or if an object
     * is being cast to a class that isn't a subclass (e.g. parent).  The exception
     * thrown will be an InvalidCastException, which extends CallerException.
     *
     * @param mixed $mixItem the value, array or object that you want to cast
     * @param string $strType the type to cast to.  Can be a dxType::XXX constant (e.g. dxType::Integer), or the name of a Class
     * @return mixed the passed in value/array/object that has been cast to strType
     */
    public final static function Cast($mixItem, $strType) {
        switch (dxType::$intBehaviour) {
            case dxType::NoOp:
                return $mixItem;
            case dxType::CastOnly:
                throw new dxCallerException("dxType::CastOnly handling not yet implemented");
                break;
            case dxType::CheckOnly:
                throw new dxCallerException("dxType::CheckOnly handling not yet implemented");
                break;
            case dxType::CheckAndCast:
                break;
            default:
                throw new InvalidArgumentException();
                break;
        }
        // Automatically Return NULLs
        if (is_null($mixItem))
            return null;

        // Figure out what PHP thinks the type is
        $strPhpType = gettype($mixItem);

        switch ($strPhpType) {
            case dxType::Object:
                try {
                    return dxType::CastObjectTo($mixItem, $strType);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case dxType::String:
            case dxType::Integer:
            case dxType::Float:
            case dxType::Boolean:
                try {
                    return dxType::CastValueTo($mixItem, $strType);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case dxType::ArrayType:
                try {
                    return dxType::CastArrayTo($mixItem, $strType);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case dxType::Resource:
                // Cannot Cast Resources
                throw new dxInvalidCastException('Resources cannot be cast');

            default:
                // Could not determine type
                throw new dxInvalidCastException(sprintf('Unable to determine type of item to be cast: %s', $mixItem));
        }
    }

    /**
     * Used by the divblox Code Generator to allow for the code generation of
     * the actual "Type::Xxx" constant, instead of the text of the constant,
     * in generated code.
     *
     * It is rare for Constant to be used manually outside of Code Generation.
     *
     * @param string $strType the type to convert to 'constant' form
     * @return string the text of the Text:Xxx Constant
     */
    public final static function Constant($strType) {
        switch ($strType) {
            case dxType::Object: return 'dxType::Object';
            case dxType::String: return 'dxType::String';
            case dxType::Integer: return 'dxType::Integer';
            case dxType::Float: return 'dxType::Float';
            case dxType::Boolean: return 'dxType::Boolean';
            case dxType::ArrayType: return 'dxType::ArrayType';
            case dxType::Resource: return 'dxType::Resource';
            case dxType::DateTime: return 'dxType::DateTime';

            default:
                // Could not determine type
                throw new dxInvalidCastException(sprintf('Unable to determine type of item to lookup its constant: %s', $strType));
        }
    }

    /**
     * @param $strType
     * @return string
     * @throws dxInvalidCastException
     */
    public final static function TypeFromDoc($strType) {
        switch (strtolower($strType)) {
            case 'string':
            case 'str':
                return dxType::String;

            case 'integer':
            case 'int':
                return dxType::Integer;

            case 'float':
            case 'flt':
            case 'double':
            case 'dbl':
            case 'single':
            case 'decimal':
                return dxType::Float;

            case 'bool':
            case 'boolean':
            case 'bit':
                return dxType::Boolean;

            case 'datetime':
            case 'date':
            case 'time':
            case 'qdatetime':
                return dxType::DateTime;

            case 'null':
            case 'void':
                return 'void';

            default:
                try {
                    $objReflection = new ReflectionClass($strType);
                    return $strType;
                } catch (ReflectionException $objExc) {
                    throw new dxInvalidCastException(sprintf('Unable to determine type of item from PHPDoc Comment to lookup its dxType or Class: %s', $strType));
                }
        }
    }

    /**
     * Used by the divblox Code Generator and QSoapService class to allow for the xml generation of
     * the actual "s:type" Soap Variable types.
     *
     * @param string $strType the type to convert to 'constant' form
     * @return string the text of the SOAP standard s:type variable type
     */
    public final static function SoapType($strType) {
        switch ($strType) {
            case dxType::String: return 'string';
            case dxType::Integer: return 'int';
            case dxType::Float: return 'float';
            case dxType::Boolean: return 'boolean';
            case dxType::DateTime: return 'dateTime';

            case dxType::ArrayType:
            case dxType::Object:
            case dxType::Resource:
            default:
                // Could not determine type
                throw new dxInvalidCastException(sprintf('Unable to determine type of item to lookup its constant: %s', $strType));
        }
    }
    /*
        final public static function SoapArrayType($strType) {
            try {
                return sprintf('ArrayOf%s', ucfirst(dxType::SoapType($strType)));
            } catch (dxInvalidCastException $objExc) {}
                $objExc->IncrementOffset();
                throw $objExc;
            }
        }

        final public static function AlterSoapComplexTypeArray(&$strComplexTypeArray, $strType) {
            switch ($strType) {
                case dxType::String:
                    $strItemName = 'string';
                    break;
                case dxType::Integer:
                    $strItemName = 'int';
                    break;
                case dxType::Float:
                    $strItemName = 'float';
                    break;
                case dxType::Boolean:
                    $strItemName = 'boolean';
                    break;
                case dxType::DateTime:
                    $strItemName = 'dateTime';
                    break;

                case dxType::ArrayType:
                case dxType::Object:
                case dxType::Resource:
                default:
                    // Could not determine type
                    throw new dxInvalidCastException(sprintf('Unable to determine type of item to lookup its constant: %s', $strType));
            }

            $strArrayName = dxType::SoapArrayType($strType);

            if (!array_key_exists($strArrayName, $strComplexTypeArray))
                $strComplexTypeArray[$strArrayName] = sprintf(
                    '<s:complexType name="%s"><s:sequence>' .
                    '<s:element minOccurs="0" maxOccurs="unbounded" name="%s" type="%s"/>' .
                    '</s:sequence></s:complexType>',
                    dxType::SoapArrayType($strType),
                    $strItemName,
                    dxType::SoapType($strType));
        }*/
}

/**
 * An abstract utility class to handle string manipulation.  All methods
 * are statically available.
 */
abstract class dxString {
    /**
     * This faux constructor method throws a caller exception.
     * The String object should never be instantiated, and this constructor
     * override simply guarantees it.
     *
     * @return void
     */
    public final function __construct() {
        throw new dxCallerException('String should never be instantiated.  All methods and variables are publically statically accessible.');
    }

    /**
     * Returns the first character of a given string, or null if the given
     * string is null.
     * @param string $strString
     * @return string the first character, or null
     */
    public final static function FirstCharacter($strString) {
        if (mb_strlen($strString, APP_ENCODING_TYPE_STR) > 0)
            return mb_substr($strString, 0 , 1, APP_ENCODING_TYPE_STR);
        else
            return null;
    }

    /**
     * Returns the last character of a given string, or null if the given
     * string is null.
     * @param string $strString
     * @return string the last character, or null
     */
    public final static function LastCharacter($strString) {
        $intLength = mb_strlen($strString, APP_ENCODING_TYPE_STR);
        if ($intLength > 0)
            return mb_substr($strString, $intLength - 1, 1, APP_ENCODING_TYPE_STR);
        else
            return null;
    }

    /**
     * Truncates the string to a given length, adding elipses (if needed).
     * @param string $strString string to truncate
     * @param integer $intMaxLength the maximum possible length of the string to return (including length of the elipse)
     * @return string the full string or the truncated string with eplise
     */
    public final static function Truncate($strText, $intMaxLength) {
        if (mb_strlen($strText, APP_ENCODING_TYPE_STR) > $intMaxLength)
            return mb_substr($strText, 0, $intMaxLength - 3, APP_ENCODING_TYPE_STR) . "...";
        else
            return $strText;
    }

    /**
     * Escapes the string so that it can be safely used in as an Xml Node (basically, adding CDATA if needed)
     * @param string $strString string to escape
     * @return string the XML Node-safe String
     */
    public final static function XmlEscape($strString) {
        if ((mb_strpos($strString, '<', 0, APP_ENCODING_TYPE_STR) !== false) ||
            (mb_strpos($strString, '&', 0, APP_ENCODING_TYPE_STR) !== false)) {
            $strString = str_replace(']]>', ']]]]><![CDATA[>', $strString);
            $strString = sprintf('<![CDATA[%s]]>', $strString);
        }

        return $strString;
    }

    // Implementation from http://en.wikibooks.org/wiki/Algorithm_Implementation/Strings/Longest_common_substring

    /**
     * @param $str1
     * @param $str2
     * @return mixed|string
     */
    public final static function LongestCommonSubsequence($str1, $str2) {
        $str1Len = mb_strlen($str1, APP_ENCODING_TYPE_STR);
        $str2Len = mb_strlen($str2, APP_ENCODING_TYPE_STR);

        if($str1Len == 0 || $str2Len == 0)
            return '';

        $CSL = array(); //Common Sequence Length array
        $intLargestSize = 0;
        $ret = array();

        //initialize the CSL array to assume there are no similarities
        for($i=0; $i<$str1Len; $i++){
            $CSL[$i] = array();
            for($j=0; $j<$str2Len; $j++){
                $CSL[$i][$j] = 0;
            }
        }

        for($i=0; $i<$str1Len; $i++){
            for($j=0; $j<$str2Len; $j++){
                //check every combination of characters
                if( $str1[$i] == $str2[$j] ){
                    //these are the same in both strings
                    if($i == 0 || $j == 0)
                        //it's the first character, so it's clearly only 1 character long
                        $CSL[$i][$j] = 1;
                    else
                        //it's one character longer than the string from the previous character
                        $CSL[$i][$j] = $CSL[$i-1][$j-1] + 1;

                    if( $CSL[$i][$j] > $intLargestSize ){
                        //remember this as the largest
                        $intLargestSize = $CSL[$i][$j];
                        //wipe any previous results
                        $ret = array();
                        //and then fall through to remember this new value
                    }
                    if( $CSL[$i][$j] == $intLargestSize )
                        //remember the largest string(s)
                        $ret[] = substr($str1, $i-$intLargestSize+1, $intLargestSize);
                }
                //else, $CSL should be set to 0, which it was already initialized to
            }
        }
        //return the first match
        if(FrameworkFunctions::getDataSetSize($ret) > 0)
            return $ret[0];
        else
            return ''; //no matches
    }
}
//endregion

//region Database related classes
/**
 * Every database adapter must implement the following 5 classes (all which are abstract):
 * * DatabaseBase
 * * DatabaseFieldBase
 * * DatabaseResultBase
 * * DatabaseRowBase
 * * DatabaseExceptionBase
 *
 * This Database library also has the following classes already defined, and
 * Database adapters are assumed to use them internally:
 * * DatabaseIndex
 * * DatabaseForeignKey
 * * DatabaseFieldType (which is an abstract class that solely contains constants)
 *
 * @property-read string $EscapeIdentifierBegin
 * @property-read string $EscapeIdentifierEnd
 * @property-read boolean $EnableProfiling
 * @property-read int $AffectedRows
 * @property-read string $Profile
 * @property-read int $DatabaseIndex
 * @property-read int $Adapter
 * @property-read string $Server
 * @property-read string $Port
 * @property-read string $Database
 * @property-read string $Service
 * @property-read string $Protocol
 * @property-read string $Host
 * @property-read string $Username
 * @property-read string $Password
 * @property-read string $cert_path
 * @property-read boolean $Caching if true objects loaded from this database will be kept in cache (assuming a cache provider is also configured)
 * @property-read string $DateFormat
 * @property-read boolean $OnlyFullGroupBy database adapter sub-classes can override and set this property to true
 *      to prevent the behavior of automatically adding all the columns to the select clause when the query has
 *      an aggregation clause.
 * @package DatabaseAdapters
 */
abstract class dxDatabaseBase extends dxBaseClass {
    // Must be updated for all Adapters
    /** Adapter name */
    const Adapter = 'Generic Database Adapter (Abstract)';

    // Protected Member Variables for ALL Database Adapters
    /** @var int Database Index according to the configuration file */
    protected $intDatabaseIndex;
    /** @var bool Has the profiling been enabled? */
    protected $blnEnableProfiling;
    /**
     * @var array
     */
    protected $strProfileArray;

    /**
     * @var string[]
     */
    protected $objConfigArray;
    /**
     * @var bool
     */
    protected $blnConnectedFlag = false;

    /**
     * @var string
     */
    protected $strEscapeIdentifierBegin = '"';
    /**
     * @var string
     */
    protected $strEscapeIdentifierEnd = '"';
    /**
     * @var bool
     */
    protected $blnOnlyFullGroupBy = false; // should be set in sub-classes as appropriate

    /**
     * @var int The transaction depth value.
     * It is incremented on a transaction begin,
     * decremented on a transaction commit, and reset to zero on a roll back.
     * It is used to implement the recursive transaction functionality.
     */
    protected $intTransactionDepth = 0;

    // Abstract Methods that ALL Database Adapters MUST implement

    /**
     * @return mixed
     */
    abstract public function Connect();
    // these are protected - externally, the "Query/NonQuery" wrappers are meant to be called

    /**
     * @param $strQuery
     * @return mixed
     */
    abstract protected function ExecuteQuery($strQuery);

    /**
     * @param $strNonQuery
     * @return mixed
     */
    abstract protected function ExecuteNonQuery($strNonQuery);

    /**
     * @return mixed
     */
    abstract public function GetTables();

    /**
     * @param null $strTableName
     * @param null $strColumnName
     * @return mixed
     */
    abstract public function InsertId($strTableName = null, $strColumnName = null);

    /**
     * @param $strTableName
     * @return mixed
     */
    abstract public function GetFieldsForTable($strTableName);

    /**
     * @param $strTableName
     * @return mixed
     */
    abstract public function GetIndexesForTable($strTableName);

    /**
     * @param $strTableName
     * @return mixed
     */
    abstract public function GetForeignKeysForTable($strTableName);

    /**
     * This function actually begins the database transaction.
     * Must be implemented in all subclasses.
     * The "TransactionBegin" wrapper are meant to be called by end-user code
     * @return void Nothing
     */
    abstract protected function ExecuteTransactionBegin();
    /**
     * This function actually commits the database transaction.
     * Must be implemented in all subclasses.
     * The "TransactionCommit" wrapper are meant to be called by end-user code
     * @return void Nothing
     */
    abstract protected function ExecuteTransactionCommit();
    /**
     * This function actually rolls back the database transaction.
     * Must be implemented in all subclasses.
     * The "TransactionRollBack" wrapper are meant to be called by end-user code
     * @return void Nothing
     */
    abstract protected function ExecuteTransactionRollBack();

    /**
     * This function begins the database transaction.
     * @return void Nothing
     */
    public final function TransactionBegin() {
        if (0 == $this->intTransactionDepth) {
            $this->ExecuteTransactionBegin();
        }
        $this->intTransactionDepth++;
    }
    /**
     * This function commits the database transaction.
     * @return void Nothing
     */
    public final function TransactionCommit() {
        if (1 == $this->intTransactionDepth) {
            $this->ExecuteTransactionCommit();
        }
        if ($this->intTransactionDepth <= 0) {
            throw new dxCallerException("The transaction commit call is called before the transaction begin was called.");
        }
        $this->intTransactionDepth--;
    }
    /**
     * This function rolls back the database transaction.
     * @return void Nothing
     */
    public final function TransactionRollBack() {
        $this->ExecuteTransactionRollBack();
        $this->intTransactionDepth = 0;
    }

    /**
     * @param $strLimitInfo
     * @return mixed
     */
    abstract public function SqlLimitVariablePrefix($strLimitInfo);

    /**
     * @param $strLimitInfo
     * @return mixed
     */
    abstract public function SqlLimitVariableSuffix($strLimitInfo);

    /**
     * @param $strSortByInfo
     * @return mixed
     */
    abstract public function SqlSortByVariable($strSortByInfo);

    /**
     * @return mixed
     */
    abstract public function Close();

    /**
     * @param $strIdentifier
     * @return string
     */
    public function EscapeIdentifier($strIdentifier) {
        return $this->strEscapeIdentifierBegin . $strIdentifier . $this->strEscapeIdentifierEnd;
    }

    /**
     * @param $mixIdentifiers
     * @return array|string
     */
    public function EscapeIdentifiers($mixIdentifiers) {
        if (is_array($mixIdentifiers)) {
            return array_map(array($this, 'EscapeIdentifier'), $mixIdentifiers);
        } else {
            return $this->EscapeIdentifier($mixIdentifiers);
        }
    }

    /**
     * @param $mixValues
     * @return array|string
     */
    public function EscapeValues($mixValues) {
        if (is_array($mixValues)) {
            return array_map(array($this, 'SqlVariable'), $mixValues);
        } else {
            return $this->SqlVariable($mixValues);
        }
    }

    /**
     * @param $mixColumnsAndValuesArray
     * @return array
     */
    public function EscapeIdentifiersAndValues($mixColumnsAndValuesArray) {
        $result = array();
        foreach ($mixColumnsAndValuesArray as $strColumn => $mixValue) {
            $result[$this->EscapeIdentifier($strColumn)] = $this->SqlVariable($mixValue);
        }
        return $result;
    }

    /**
     * @param $strTable
     * @param $mixColumnsAndValuesArray
     * @param null $strPKNames
     */
    public function InsertOrUpdate($strTable, $mixColumnsAndValuesArray, $strPKNames = null) {
        $strEscapedArray = $this->EscapeIdentifiersAndValues($mixColumnsAndValuesArray);
        $strColumns = array_keys($strEscapedArray);
        $strUpdateStatement = '';
        foreach ($strEscapedArray as $strColumn => $strValue) {
            if ($strUpdateStatement) $strUpdateStatement .= ', ';
            $strUpdateStatement .= $strColumn . ' = ' . $strValue;
        }
        if (is_null($strPKNames)) {
            $strMatchCondition = 'target_.'.$strColumns[0].' = source_.'.$strColumns[0];
        } else if (is_array($strPKNames)) {
            $strMatchCondition = '';
            foreach ($strPKNames as $strPKName) {
                if ($strMatchCondition) $strMatchCondition .= ' AND ';
                $strMatchCondition .= 'target_.'.$this->EscapeIdentifier($strPKName).' = source_.'.$this->EscapeIdentifier($strPKName);
            }
        } else {
            $strMatchCondition = 'target_.'.$this->EscapeIdentifier($strPKNames).' = source_.'.$this->EscapeIdentifier($strPKNames);
        }
        $strTable = $this->EscapeIdentifierBegin . $strTable . $this->EscapeIdentifierEnd;
        $strSql = sprintf('MERGE INTO %s AS target_ USING %s AS source_ ON %s WHEN MATCHED THEN UPDATE SET %s WHEN NOT MATCHED THEN INSERT (%s) VALUES (%s)',
            $strTable, $strTable,
            $strMatchCondition, $strUpdateStatement,
            implode(', ', $strColumns),
            implode(', ', array_values($strEscapedArray))
        );
        $this->ExecuteNonQuery($strSql);
    }

    /**
     * @param string $strQuery query string
     * @return dxDatabaseResultBase
     */
    public final function Query($strQuery) {
        $timerName = null;
        if (!$this->blnConnectedFlag) {
            $this->Connect();
        }


        if ($this->blnEnableProfiling) {
            $timerName = 'queryExec' . mt_rand() ;
            QTimer::Start($timerName);
        }

        $result = $this->ExecuteQuery($strQuery);

        if ($this->blnEnableProfiling) {
            $dblQueryTime = QTimer::Stop($timerName);
            QTimer::Reset($timerName);

            // Log Query (for Profiling, if applicable)
            $this->LogQuery($strQuery, $dblQueryTime);
        }

        return $result;
    }

    /**
     * @param $strNonQuery
     * @return mixed
     */
    public final function NonQuery($strNonQuery) {
        if (!$this->blnConnectedFlag) {
            $this->Connect();
        }
        $timerName = '';
        if ($this->blnEnableProfiling) {
            $timerName = 'queryExec' . mt_rand() ;
            QTimer::Start($timerName);
        }

        $result = $this->ExecuteNonQuery($strNonQuery);

        if ($this->blnEnableProfiling) {
            $dblQueryTime = QTimer::Stop($timerName);
            QTimer::Reset($timerName);

            // Log Query (for Profiling, if applicable)
            $this->LogQuery($strNonQuery, $dblQueryTime);
        }

        return $result;
    }

    /**
     * @param string $strName
     * @return bool|mixed|string
     * @throws dxCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case 'EscapeIdentifierBegin':
                return $this->strEscapeIdentifierBegin;
            case 'EscapeIdentifierEnd':
                return $this->strEscapeIdentifierEnd;
            case 'EnableProfiling':
                return $this->blnEnableProfiling;
            case 'AffectedRows':
                return -1;
            case 'Profile':
                return $this->strProfileArray;
            case 'DatabaseIndex':
                return $this->intDatabaseIndex;
            case 'Adapter':
                $strConstantName = get_class($this) . '::Adapter';
                return constant($strConstantName) . ' (' . $this->objConfigArray['adapter'] . ')';
            case 'Server':
            case 'Port':
            case 'Database':
                // Informix naming
            case 'Service':
            case 'Protocol':
            case 'Host':
            case 'cert_path':
            case 'Username':
            case 'Password':
                return $this->objConfigArray[strtolower($strName)];
            case 'Caching':
                return $this->objConfigArray['caching'];
            case 'DateFormat':
                return (is_null($this->objConfigArray[strtolower($strName)])) ? (dxDateTime::FormatIso) : ($this->objConfigArray[strtolower($strName)]);
            case 'OnlyFullGroupBy':
                return $this->blnOnlyFullGroupBy;

            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * Constructs a Database Adapter based on the database index and the configuration array of properties for this particular adapter
     * Sets up the base-level configuration properties for this database,
     * namely DB Profiling and Database Index
     *
     * @param integer $intDatabaseIndex
     * @param string[] $objConfigArray configuration array as passed in to the constructor by QApplicationBase::InitializeDatabaseConnections();
     * @return void
     */
    public function __construct($intDatabaseIndex, $objConfigArray) {
        // Setup DatabaseIndex
        $this->intDatabaseIndex = $intDatabaseIndex;

        // Save the ConfigArray
        $this->objConfigArray = $objConfigArray;

        // Setup Profiling Array (if applicable)
        $this->blnEnableProfiling = dxType::Cast($objConfigArray['profiling'], dxType::Boolean);
        if ($this->blnEnableProfiling)
            $this->strProfileArray = array();
    }


    /**
     * Allows for the enabling of DB profiling while in middle of the script
     *
     * @return void
     */
    public function EnableProfiling() {
        // Only perform profiling initialization if profiling is not yet enabled
        if (!$this->blnEnableProfiling) {
            $this->blnEnableProfiling = true;
            $this->strProfileArray = array();
        }
    }

    /**
     * If EnableProfiling is on, then log the query to the profile array
     *
     * @param string $strQuery
     * @param double $dblQueryTime query execution time in milliseconds
     * @return void
     */
    private function LogQuery($strQuery, $dblQueryTime) {
        if ($this->blnEnableProfiling) {
            // Dereference-ize Backtrace Information
            $objDebugBacktrace = debug_backtrace();

            // get rid of unnecessary backtrace info in case of:
            // query
            if ((FrameworkFunctions::getDataSetSize($objDebugBacktrace) > 3) &&
                (array_key_exists('function', $objDebugBacktrace[2])) &&
                (($objDebugBacktrace[2]['function'] == 'QueryArray') ||
                    ($objDebugBacktrace[2]['function'] == 'QuerySingle') ||
                    ($objDebugBacktrace[2]['function'] == 'QueryCount')))
                $objBacktrace = $objDebugBacktrace[3];
            else
                if (isset($objDebugBacktrace[2]))
                    // non query
                    $objBacktrace = $objDebugBacktrace[2];
                else
                    // ad hoc query
                    $objBacktrace = $objDebugBacktrace[1];

            // get rid of reference to current object in backtrace array
            if( isset($objBacktrace['object']))
                $objBacktrace['object'] = null;

            for ($intIndex = 0, $intMax = FrameworkFunctions::getDataSetSize($objBacktrace['args']); $intIndex < $intMax; $intIndex++) {
                $obj = $objBacktrace['args'][$intIndex];
                if (($obj instanceof dxQueryClause) || ($obj instanceof dxQueryCondition))
                    $obj = sprintf("[%s]", $obj->__toString());
                else if (is_null($obj))
                    $obj = 'null';
                else if (gettype($obj) == 'integer') {}
                else if (gettype($obj) == 'object')
                    $obj = 'Object';
                else if (is_array($obj))
                    $obj = 'Array';
                else
                    $obj = sprintf("'%s'", $obj);
                $objBacktrace['args'][$intIndex] = $obj;
            }

            // Push it onto the profiling information array
            $arrProfile = array(
                'objBacktrace' 	=> $objBacktrace,
                'strQuery'			=> $strQuery,
                'dblTimeInfo'		=> $dblQueryTime);

            array_push( $this->strProfileArray, $arrProfile);
        }
    }

    /**
     * Properly escapes $mixData to be used as a SQL query parameter.
     * If IncludeEquality is set (usually not), then include an equality operator.
     * So for most data, it would just be "=".  But, for example,
     * if $mixData is NULL, then most RDBMS's require the use of "IS".
     *
     * @param mixed $mixData
     * @param boolean $blnIncludeEquality whether or not to include an equality operator
     * @param boolean $blnReverseEquality whether the included equality operator should be a "NOT EQUAL", e.g. "!="
     * @return string the properly formatted SQL variable
     */
    public function SqlVariable($mixData, $blnIncludeEquality = false, $blnReverseEquality = false) {
        // Are we SqlVariabling a BOOLEAN value?
        if (is_bool($mixData)) {
            // Yes
            if ($blnIncludeEquality) {
                // We must include the inequality

                if ($blnReverseEquality) {
                    // Do a "Reverse Equality"

                    // Check against NULL, True then False
                    if (is_null($mixData))
                        return 'IS NOT NULL';
                    else if ($mixData)
                        return '= 0';
                    else
                        return '!= 0';
                } else {
                    // Check against NULL, True then False
                    if (is_null($mixData))
                        return 'IS NULL';
                    else if ($mixData)
                        return '!= 0';
                    else
                        return '= 0';
                }
            } else {
                // Check against NULL, True then False
                if (is_null($mixData))
                    return 'NULL';
                else if ($mixData)
                    return '1';
                else
                    return '0';
            }
        }

        // Check for Equality Inclusion
        if ($blnIncludeEquality) {
            if ($blnReverseEquality) {
                if (is_null($mixData))
                    $strToReturn = 'IS NOT ';
                else
                    $strToReturn = '!= ';
            } else {
                if (is_null($mixData))
                    $strToReturn = 'IS ';
                else
                    $strToReturn = '= ';
            }
        } else
            $strToReturn = '';

        // Check for NULL Value
        if (is_null($mixData))
            return $strToReturn . 'NULL';

        // Check for NUMERIC Value
        if (is_integer($mixData) || is_float($mixData))
            return $strToReturn . sprintf('%s', $mixData);

        // Check for DATE Value
        if ($mixData instanceof dxDateTime) {
            /** @var dxDateTime $mixData */
            if ($mixData->IsTimeNull())
                return $strToReturn . sprintf("'%s'", $mixData->qFormat('YYYY-MM-DD'));
            else
                return $strToReturn . sprintf("'%s'", $mixData->qFormat(dxDateTime::FormatIso));
        }

        // Assume it's some kind of string value
        return $strToReturn . sprintf("'%s'", addslashes($mixData));
    }

    /**
     * @param $strQuery
     * @param $mixParameterArray
     * @return mixed
     */
    public function PrepareStatement($strQuery, $mixParameterArray) {
        foreach ($mixParameterArray as $strKey => $mixValue) {
            if (is_array($mixValue)) {
                $strParameters = array();
                foreach ($mixValue as $mixParameter)
                    array_push($strParameters, $this->SqlVariable($mixParameter));
                $strQuery = str_replace(chr(dxQueryNamedValue::DelimiterCode) . '{' . $strKey . '}', implode(',', $strParameters) . ')', $strQuery);
            } else {
                $strQuery = str_replace(chr(dxQueryNamedValue::DelimiterCode) . '{=' . $strKey . '=}', $this->SqlVariable($mixValue, true, false), $strQuery);
                $strQuery = str_replace(chr(dxQueryNamedValue::DelimiterCode) . '{!' . $strKey . '!}', $this->SqlVariable($mixValue, true, true), $strQuery);
                $strQuery = str_replace(chr(dxQueryNamedValue::DelimiterCode) . '{' . $strKey . '}', $this->SqlVariable($mixValue), $strQuery);
            }
        }

        return $strQuery;
    }

    /**
     * Displays the OutputProfiling results, plus a link which will popup the details of the profiling.
     *
     * @return void
     */
    public function OutputProfiling() {
        if ($this->blnEnableProfiling) {
            printf('<form method="post" id="frmDbProfile%s" action="%s/profile.php"><div>',
                $this->intDatabaseIndex, __VIRTUAL_DIRECTORY__ . __PHP_ASSETS__);
            printf('<input type="hidden" name="strProfileData" value="%s" />',
                base64_encode(serialize($this->strProfileArray)));
            printf('<input type="hidden" name="intDatabaseIndex" value="%s" />', $this->intDatabaseIndex);
            printf('<input type="hidden" name="strReferrer" value="%s" /></div></form>', FrameworkFunctions::HtmlEntities(FrameworkFunctions::$RequestUri));

            $intCount = round(FrameworkFunctions::getDataSetSize($this->strProfileArray));
            if ($intCount == 0)
                printf('<b>PROFILING INFORMATION FOR DATABASE CONNECTION #%s</b>: No queries performed.  Please <a href="#" onclick="var frmDbProfile = document.getElementById(\'frmDbProfile%s\'); frmDbProfile.target = \'_blank\'; frmDbProfile.submit(); return false;">click here to view profiling detail</a><br />',
                    $this->intDatabaseIndex, $this->intDatabaseIndex);
            else if ($intCount == 1)
                printf('<b>PROFILING INFORMATION FOR DATABASE CONNECTION #%s</b>: 1 query performed.  Please <a href="#" onclick="var frmDbProfile = document.getElementById(\'frmDbProfile%s\'); frmDbProfile.target = \'_blank\'; frmDbProfile.submit(); return false;">click here to view profiling detail</a><br />',
                    $this->intDatabaseIndex, $this->intDatabaseIndex);
            else
                printf('<b>PROFILING INFORMATION FOR DATABASE CONNECTION #%s</b>: %s queries performed.  Please <a href="#" onclick="var frmDbProfile = document.getElementById(\'frmDbProfile%s\'); frmDbProfile.target = \'_blank\'; frmDbProfile.submit(); return false;">click here to view profiling detail</a><br />',
                    $this->intDatabaseIndex, $intCount, $this->intDatabaseIndex);
        } else {
            _p('<form></form><b>Profiling was not enabled for this database connection (#' . $this->intDatabaseIndex . ').</b>  To enable, ensure that ENABLE_PROFILING is set to TRUE.', false);
        }
    }

    /**
     * Executes the explain statement for a given query and returns the output without any transformation.
     * If the database adapter does not support EXPLAIN statements, returns null.
     *
     * @param $strSql
     */
    public function ExplainStatement($sql) {
        return null;
    }
}

abstract class dxDatabaseFieldBase extends dxBaseClass {
    /**
     * @var
     */
    protected $strName;
    /**
     * @var
     */
    protected $strOriginalName;
    /**
     * @var
     */
    protected $strTable;
    /**
     * @var
     */
    protected $strOriginalTable;
    /**
     * @var
     */
    protected $strDefault;
    /**
     * @var
     */
    protected $intMaxLength;
    /**
     * @var
     */
    protected $strComment;

    // Bool
    /**
     * @var
     */
    protected $blnIdentity;
    /**
     * @var
     */
    protected $blnNotNull;
    /**
     * @var
     */
    protected $blnPrimaryKey;
    /**
     * @var
     */
    protected $blnUnique;
    /**
     * @var
     */
    protected $blnTimestamp;

    /**
     * @var
     */
    protected $strType;

    /**
     * @param string $strName
     * @return mixed
     * @throws dxCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case "Name":
                return $this->strName;
            case "OriginalName":
                return $this->strOriginalName;
            case "Table":
                return $this->strTable;
            case "OriginalTable":
                return $this->strOriginalTable;
            case "Default":
                return $this->strDefault;
            case "MaxLength":
                return $this->intMaxLength;
            case "Identity":
                return $this->blnIdentity;
            case "NotNull":
                return $this->blnNotNull;
            case "PrimaryKey":
                return $this->blnPrimaryKey;
            case "Unique":
                return $this->blnUnique;
            case "Timestamp":
                return $this->blnTimestamp;
            case "Type":
                return $this->strType;
            case "Comment":
                return $this->strComment;
            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
}

/**
 * @property dxQueryBuilder $QueryBuilder
 *
 */
abstract class dxDatabaseResultBase extends dxBaseClass {
    // Allow to attach a dxQueryBuilder object to use the result object as cursor resource for cursor queries.
    /**
     * @var
     */
    protected $objQueryBuilder;

    /**
     * @return mixed
     */
    abstract public function FetchArray();

    /**
     * @return mixed
     */
    abstract public function FetchRow();

    /**
     * @return mixed
     */
    abstract public function FetchField();

    /**
     * @return mixed
     */
    abstract public function FetchFields();

    /**
     * @return mixed
     */
    abstract public function CountRows();

    /**
     * @return mixed
     */
    abstract public function CountFields();

    /**
     * @return mixed
     */
    abstract public function GetNextRow();

    /**
     * @return mixed
     */
    abstract public function GetRows();

    /**
     * @return mixed
     */
    abstract public function Close();

    /**
     * @param string $strName
     * @return mixed
     * @throws dxCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case 'QueryBuilder':
                return $this->objQueryBuilder;
            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * @param string $strName
     * @param string $mixValue
     * @return mixed
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __set($strName, $mixValue) {
        switch ($strName) {
            case 'QueryBuilder':
                try {
                    return ($this->objQueryBuilder = dxType::Cast($mixValue, 'dxQueryBuilder'));
                } catch (dxInvalidCastException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
            default:
                try {
                    return parent::__set($strName, $mixValue);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
}

/**
 *
 * @package DatabaseAdapters
 */
abstract class dxDatabaseRowBase extends dxBaseClass {
    /**
     * @param $strColumnName
     * @param null $strColumnType
     * @return mixed
     */
    abstract public function GetColumn($strColumnName, $strColumnType = null);

    /**
     * @param $strColumnName
     * @return mixed
     */
    abstract public function ColumnExists($strColumnName);

    /**
     * @return mixed
     */
    abstract public function GetColumnNameArray();
}

/**
 *
 * @package DatabaseAdapters
 */
abstract class dxDatabaseExceptionBase extends dxCallerException {
    /**
     * @var
     */
    protected $intErrorNumber;
    /**
     * @var
     */
    protected $strQuery;

    /**
     * @param $strName
     * @return array|int|mixed
     */
    public function __get($strName) {
        switch ($strName) {
            case "ErrorNumber":
                return $this->intErrorNumber;
            case "Query";
                return $this->strQuery;
            default:
                return parent::__get($strName);
        }
    }
}

/**
 *
 * @package DatabaseAdapters
 */
class dxDatabaseForeignKey extends dxBaseClass {
    /**
     * @var
     */
    protected $strKeyName;
    /**
     * @var
     */
    protected $strColumnNameArray;
    /**
     * @var
     */
    protected $strReferenceTableName;
    /**
     * @var
     */
    protected $strReferenceColumnNameArray;

    /**
     * dxDatabaseForeignKey constructor.
     * @param $strKeyName
     * @param $strColumnNameArray
     * @param $strReferenceTableName
     * @param $strReferenceColumnNameArray
     */
    public function __construct($strKeyName, $strColumnNameArray, $strReferenceTableName, $strReferenceColumnNameArray) {
        $this->strKeyName = $strKeyName;
        $this->strColumnNameArray = $strColumnNameArray;
        $this->strReferenceTableName = $strReferenceTableName;
        $this->strReferenceColumnNameArray = $strReferenceColumnNameArray;
    }

    /**
     * @param string $strName
     * @return mixed
     * @throws dxCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case "KeyName":
                return $this->strKeyName;
            case "ColumnNameArray":
                return $this->strColumnNameArray;
            case "ReferenceTableName":
                return $this->strReferenceTableName;
            case "ReferenceColumnNameArray":
                return $this->strReferenceColumnNameArray;
            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
}

/**
 *
 * @package DatabaseAdapters
 */
class dxDatabaseIndex extends dxBaseClass {
    /**
     * @var
     */
    protected $strKeyName;
    /**
     * @var
     */
    protected $blnPrimaryKey;
    /**
     * @var
     */
    protected $blnUnique;
    /**
     * @var
     */
    protected $strColumnNameArray;

    /**
     * dxDatabaseIndex constructor.
     * @param $strKeyName
     * @param $blnPrimaryKey
     * @param $blnUnique
     * @param $strColumnNameArray
     */
    public function __construct($strKeyName, $blnPrimaryKey, $blnUnique, $strColumnNameArray) {
        $this->strKeyName = $strKeyName;
        $this->blnPrimaryKey = $blnPrimaryKey;
        $this->blnUnique = $blnUnique;
        $this->strColumnNameArray = $strColumnNameArray;
    }

    /**
     * @param string $strName
     * @return mixed
     * @throws dxCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case "KeyName":
                return $this->strKeyName;
            case "PrimaryKey":
                return $this->blnPrimaryKey;
            case "Unique":
                return $this->blnUnique;
            case "ColumnNameArray":
                return $this->strColumnNameArray;
            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
}

/**
 *
 * @package DatabaseAdapters
 */
abstract class dxDatabaseFieldType {
    /**
     *
     */
    const Blob = "Blob";
    /**
     *
     */
    const VarChar = "VarChar";
    /**
     *
     */
    const Char = "Char";
    /**
     *
     */
    const Integer = "Integer";
    /**
     *
     */
    const DateTime = "DateTime";
    /**
     *
     */
    const Date = "Date";
    /**
     *
     */
    const Time = "Time";
    /**
     *
     */
    const Float = "Float";
    /**
     *
     */
    const Bit = "Bit";
}

class dxMySqliDatabase extends dxDatabaseBase {
    /**
     *
     */
    const Adapter = 'MySql Improved Database Adapter for MySQL 4';

    /**
     * @var
     */
    protected $objMySqli;

    /**
     * @var string
     */
    protected $strEscapeIdentifierBegin = '`';
    /**
     * @var string
     */
    protected $strEscapeIdentifierEnd = '`';

    /**
     * @param $strLimitInfo
     * @return mixed|null|string
     */
    public function SqlLimitVariablePrefix($strLimitInfo) {
        // MySQL uses Limit by Suffixes (via a LIMIT clause)

        // If requested, use SQL_CALC_FOUND_ROWS directive to utilize GetFoundRows() method
        if (array_key_exists('usefoundrows', $this->objConfigArray) && $this->objConfigArray['usefoundrows'])
            return 'SQL_CALC_FOUND_ROWS';

        return null;
    }

    /**
     * @param $strLimitInfo
     * @return mixed|null|string
     * @throws Exception
     */
    public function SqlLimitVariableSuffix($strLimitInfo) {
        // Setup limit suffix (if applicable) via a LIMIT clause
        if (strlen($strLimitInfo)) {
            if (strpos($strLimitInfo, ';') !== false)
                throw new Exception('Invalid Semicolon in LIMIT Info');
            if (strpos($strLimitInfo, '`') !== false)
                throw new Exception('Invalid Backtick in LIMIT Info');
            return "LIMIT $strLimitInfo";
        }

        return null;
    }

    /**
     * @param $strSortByInfo
     * @return mixed|null|string
     * @throws Exception
     */
    public function SqlSortByVariable($strSortByInfo) {
        // Setup sorting information (if applicable) via a ORDER BY clause
        if (strlen($strSortByInfo)) {
            if (strpos($strSortByInfo, ';') !== false)
                throw new Exception('Invalid Semicolon in ORDER BY Info');
            if (strpos($strSortByInfo, '`') !== false)
                throw new Exception('Invalid Backtick in ORDER BY Info');

            return "ORDER BY $strSortByInfo";
        }

        return null;
    }

    /**
     * @param $strTable
     * @param $mixColumnsAndValuesArray
     * @param null $strPKNames
     * @throws dxMySqliDatabaseException
     */
    public function InsertOrUpdate($strTable, $mixColumnsAndValuesArray, $strPKNames = null) {
        $strEscapedArray = $this->EscapeIdentifiersAndValues($mixColumnsAndValuesArray);
        $strUpdateStatement = '';
        foreach ($strEscapedArray as $strColumn => $strValue) {
            if ($strUpdateStatement) $strUpdateStatement .= ', ';
            $strUpdateStatement .= $strColumn . ' = ' . $strValue;
        }
        $strSql = sprintf('INSERT INTO %s%s%s (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s',
            $this->EscapeIdentifierBegin, $strTable, $this->EscapeIdentifierEnd,
            implode(', ', array_keys($strEscapedArray)),
            implode(', ', array_values($strEscapedArray)),
            $strUpdateStatement
        );
        $this->ExecuteNonQuery($strSql);
    }

    /**
     * @return mixed|void
     * @throws dxMySqliDatabaseException
     */
    public function Connect() {
        // Connect to the Database Server
        $this->objMySqli = mysqli_init();
        mysqli_ssl_set($this->objMySqli, NULL, NULL, $this->cert_path, NULL, NULL);
        mysqli_real_connect($this->objMySqli, $this->Server, $this->Username, $this->Password, $this->Database, $this->Port);
        //$this->objMySqli = new MySqli($this->Server, $this->Username, $this->Password, $this->Database, $this->Port);

        if (!$this->objMySqli)
            throw new dxMySqliDatabaseException("Unable to connect to Database", -1, null);

        if ($this->objMySqli->error)
            throw new dxMySqliDatabaseException($this->objMySqli->error, $this->objMySqli->errno, null);

        if (!isset($_SESSION['DB_Connected'])) {
            // TODO: Here we can implement a runtime license check
            $_SESSION['DB_Connected'] = 1;
        }

        // Update "Connected" Flag
        $this->blnConnectedFlag = true;

        // Set to AutoCommit
        $this->NonQuery('SET AUTOCOMMIT=1;');

        // Set NAMES (if applicable)
        if (array_key_exists('encoding', $this->objConfigArray))
            $this->NonQuery('SET NAMES ' . $this->objConfigArray['encoding'] . ';');
    }

    /**
     * @return bool
     */
    public function checkTableNamesCase() {
        $strQuery = "SHOW VARIABLES LIKE 'lower_case_table_names'";
        $objResult = $this->objMySqli->query($strQuery);
        while ($row=mysqli_fetch_row($objResult)) {
            if ($row[1] == 2) {
                return true; //All good
            }
        }
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            while ($row=mysqli_fetch_row($objResult)) {
                if ($row[1] == 2) {
                    return true; //All good
                }
            }
        } else {
            // We assume it is fine, since it is not Windows
            return true;
        }
        return false;
    }

    /**
     * @param string $strName
     * @return bool|mixed|string
     * @throws dxCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case 'AffectedRows':
                return $this->objMySqli->affected_rows;
            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * @param $strQuery
     * @return dxMySqliDatabaseResult|mixed
     * @throws dxMySqliDatabaseException
     */
    protected function ExecuteQuery($strQuery) {
        // Perform the Query
        $objResult = $this->objMySqli->query($strQuery);
        if ($this->objMySqli->error)
            throw new dxMySqliDatabaseException($this->objMySqli->error, $this->objMySqli->errno, $strQuery);

        // Return the Result
        $objMySqliDatabaseResult = new dxMySqliDatabaseResult($objResult, $this);
        return $objMySqliDatabaseResult;
    }

    /**
     * @param $strNonQuery
     * @return mixed|void
     * @throws dxMySqliDatabaseException
     */
    protected function ExecuteNonQuery($strNonQuery) {
        // Perform the Query
        $this->objMySqli->query($strNonQuery);
        if ($this->objMySqli->error)
            throw new dxMySqliDatabaseException($this->objMySqli->error, $this->objMySqli->errno, $strNonQuery);
    }

    /**
     * @return array|mixed
     */
    public function GetTables() {
        // Use the MySQL "SHOW TABLES" functionality to get a list of all the tables in this database
        $objResult = $this->Query("SHOW TABLES");
        $strToReturn = array();
        while ($strRowArray = $objResult->FetchRow())
            array_push($strToReturn, $strRowArray[0]);
        return $strToReturn;
    }

    /**
     * @param $strTableName
     * @return mixed
     */
    public function GetFieldsForTable($strTableName) {
        $objResult = $this->Query(sprintf('SELECT * FROM %s%s%s LIMIT 1', $this->strEscapeIdentifierBegin, $strTableName, $this->strEscapeIdentifierEnd));
        return $objResult->FetchFields();
    }

    /**
     * @param null $strTableName
     * @param null $strColumnName
     * @return mixed
     */
    public function InsertId($strTableName = null, $strColumnName = null) {
        return $this->objMySqli->insert_id;
    }

    /**
     * @return mixed|void
     */
    public function Close() {
        $this->objMySqli->close();

        // Update Connected Flag
        $this->blnConnectedFlag = false;
    }

    /**
     *
     */
    protected function ExecuteTransactionBegin() {
        // Set to AutoCommit
        $this->NonQuery('SET AUTOCOMMIT=0;');
    }

    /**
     *
     */
    protected function ExecuteTransactionCommit() {
        $this->NonQuery('COMMIT;');
        // Set to AutoCommit
        $this->NonQuery('SET AUTOCOMMIT=1;');
    }

    /**
     *
     */
    protected function ExecuteTransactionRollBack() {
        $this->NonQuery('ROLLBACK;');
        // Set to AutoCommit
        $this->NonQuery('SET AUTOCOMMIT=1;');
    }

    /**
     * @return mixed
     * @throws dxCallerException
     */
    public function GetFoundRows() {
        if (array_key_exists('usefoundrows', $this->objConfigArray) && $this->objConfigArray['usefoundrows']) {
            $objResult = $this->Query('SELECT FOUND_ROWS();');
            $strRow = $objResult->FetchArray();
            return $strRow[0];
        } else
            throw new dxCallerException('Cannot call GetFoundRows() on the database when "usefoundrows" configuration was not set to true.');
    }

    /**
     * @param $strTableName
     * @return array|mixed
     * @throws Exception
     */
    public function GetIndexesForTable($strTableName) {
        // Figure out the Table Type (InnoDB, MyISAM, etc.) by parsing the Create Table description
        $strCreateStatement = $this->GetCreateStatementForTable($strTableName);
        $strTableType = $this->GetTableTypeForCreateStatement($strCreateStatement);

        switch (true) {
            case substr($strTableType, 0, 6) == 'MYISAM':
                return $this->ParseForIndexes($strCreateStatement);

            case substr($strTableType, 0, 6) == 'INNODB':
                return $this->ParseForIndexes($strCreateStatement);

            case substr($strTableType, 0, 6) == 'MEMORY':
            case substr($strTableType, 0, 4) == 'HEAP':
                return $this->ParseForIndexes($strCreateStatement);

            default:
                throw new Exception("Table Type is not supported: $strTableType");
        }
    }

    /**
     * @param $strTableName
     * @return array|mixed
     * @throws Exception
     */
    public function GetForeignKeysForTable($strTableName) {
        // Figure out the Table Type (InnoDB, MyISAM, etc.) by parsing the Create Table description
        $strCreateStatement = $this->GetCreateStatementForTable($strTableName);
        $strTableType = $this->GetTableTypeForCreateStatement($strCreateStatement);

        switch (true) {
            case substr($strTableType, 0, 6) == 'MYISAM':
                $objForeignKeyArray = array();
                break;

            case substr($strTableType, 0, 6) == 'MEMORY':
            case substr($strTableType, 0, 4) == 'HEAP':
                $objForeignKeyArray = array();
                break;

            case substr($strTableType, 0, 6) == 'INNODB':
                $objForeignKeyArray = $this->ParseForInnoDbForeignKeys($strCreateStatement);
                break;

            default:
                throw new Exception("Table Type is not supported: $strTableType");
        }

        return $objForeignKeyArray;
    }

    // MySql defines KeyDefinition to be [OPTIONAL_NAME] ([COL], ...)
    // If the key name exists, this will parse it out and return it
    /**
     * @param $strKeyDefinition
     * @return bool|null|string
     * @throws Exception
     */
    private function ParseNameFromKeyDefinition($strKeyDefinition) {
        $strKeyDefinition = trim($strKeyDefinition);

        $intPosition = strpos($strKeyDefinition, '(');

        if ($intPosition === false)
            throw new Exception("Invalid Key Definition: $strKeyDefinition");
        else if ($intPosition == 0)
            // No Key Name Defined
            return null;

        // If we're here, then we have a key name defined
        $strName = trim(substr($strKeyDefinition, 0, $intPosition));

        // Rip Out leading and trailing "`" character (if applicable)
        if (substr($strName, 0, 1) == '`')
            return substr($strName, 1, strlen($strName) - 2);
        else
            return $strName;
    }

    // MySql defines KeyDefinition to be [OPTIONAL_NAME] ([COL], ...)
    // This will return an array of strings that are the names [COL], etc.
    /**
     * @param $strKeyDefinition
     * @return array
     * @throws Exception
     */
    private function ParseColumnNameArrayFromKeyDefinition($strKeyDefinition) {
        $strKeyDefinition = trim($strKeyDefinition);

        // Get rid of the opening "(" and the closing ")"
        $intPosition = strpos($strKeyDefinition, '(');
        if ($intPosition === false)
            throw new Exception("Invalid Key Definition: $strKeyDefinition");
        $strKeyDefinition = trim(substr($strKeyDefinition, $intPosition + 1));

        $intPosition = strpos($strKeyDefinition, ')');
        if ($intPosition === false)
            throw new Exception("Invalid Key Definition: $strKeyDefinition");
        $strKeyDefinition = trim(substr($strKeyDefinition, 0, $intPosition));

        // Create the Array
        $strToReturn = explode(',', $strKeyDefinition);

        // Take out trailing and leading "`" character in each name (if applicable)
        for ($intIndex = 0; $intIndex < FrameworkFunctions::getDataSetSize($strToReturn); $intIndex++) {
            $strColumn = $strToReturn[$intIndex];

            if (substr($strColumn, 0, 1) == '`')
                $strColumn = substr($strColumn, 1, strpos($strColumn, '`', 1) - 1);

            $strToReturn[$intIndex] = $strColumn;
        }

        return $strToReturn;
    }

    /**
     * @param $strCreateStatement
     * @return array
     * @throws Exception
     */
    private function ParseForIndexes($strCreateStatement) {
        // MySql nicely splits each object in a table into it's own line
        // Split the create statement into lines, and then pull out anything
        // that says "PRIMARY KEY", "UNIQUE KEY", or just plain ol' "KEY"
        $strLineArray = explode("\n", $strCreateStatement);

        $objIndexArray = array();

        // We don't care about the first line or the last line
        for ($intIndex = 1; $intIndex < (FrameworkFunctions::getDataSetSize($strLineArray) - 1); $intIndex++) {
            $strLine = $strLineArray[$intIndex];

            // Each object has a two-space indent
            // So this is a key object if any of those key-related words exist at position 2
            switch (2) {
                case (strpos($strLine, 'PRIMARY KEY')):
                    $strKeyDefinition = substr($strLine, strlen('  PRIMARY KEY '));

                    $strKeyName = $this->ParseNameFromKeyDefinition($strKeyDefinition);
                    $strColumnNameArray = $this->ParseColumnNameArrayFromKeyDefinition($strKeyDefinition);

                    $objIndex = new dxDatabaseIndex($strKeyName, $blnPrimaryKey = true, $blnUnique = true, $strColumnNameArray);
                    array_push($objIndexArray, $objIndex);
                    break;

                case (strpos($strLine, 'UNIQUE KEY')):
                    $strKeyDefinition = substr($strLine, strlen('  UNIQUE KEY '));

                    $strKeyName = $this->ParseNameFromKeyDefinition($strKeyDefinition);
                    $strColumnNameArray = $this->ParseColumnNameArrayFromKeyDefinition($strKeyDefinition);

                    $objIndex = new dxDatabaseIndex($strKeyName, $blnPrimaryKey = false, $blnUnique = true, $strColumnNameArray);
                    array_push($objIndexArray, $objIndex);
                    break;

                case (strpos($strLine, 'KEY')):
                    $strKeyDefinition = substr($strLine, strlen('  KEY '));

                    $strKeyName = $this->ParseNameFromKeyDefinition($strKeyDefinition);
                    $strColumnNameArray = $this->ParseColumnNameArrayFromKeyDefinition($strKeyDefinition);

                    $objIndex = new dxDatabaseIndex($strKeyName, $blnPrimaryKey = false, $blnUnique = false, $strColumnNameArray);
                    array_push($objIndexArray, $objIndex);
                    break;
            }
        }

        return $objIndexArray;
    }

    /**
     * @param $strCreateStatement
     * @return array
     * @throws Exception
     */
    private function ParseForInnoDbForeignKeys($strCreateStatement) {
        // MySql nicely splits each object in a table into it's own line
        // Split the create statement into lines, and then pull out anything
        // that starts with "CONSTRAINT" and contains "FOREIGN KEY"
        $strLineArray = explode("\n", $strCreateStatement);

        $objForeignKeyArray = array();

        // We don't care about the first line or the last line
        for ($intIndex = 1; $intIndex < (FrameworkFunctions::getDataSetSize($strLineArray) - 1); $intIndex++) {
            $strLine = $strLineArray[$intIndex];

            // Check to see if the line:
            // * Starts with "CONSTRAINT" at position 2 AND
            // * contains "FOREIGN KEY"
            if ((strpos($strLine, "CONSTRAINT") == 2) &&
                (strpos($strLine, "FOREIGN KEY") !== false)) {
                $strLine = substr($strLine, strlen('  CONSTRAINT '));

                // By the end of the following lines, we will end up with a strTokenArray
                // Index 0: the FK name
                // Index 1: the list of columns that are the foreign key
                // Index 2: the table which this FK references
                // Index 3: the list of columns which this FK references
                $strTokenArray = explode(' FOREIGN KEY ', $strLine);
                $strTokenArray[1] = explode(' REFERENCES ', $strTokenArray[1]);
                $strTokenArray[2] = $strTokenArray[1][1];
                $strTokenArray[1] = $strTokenArray[1][0];
                $strTokenArray[2] = explode(' ', $strTokenArray[2]);
                $strTokenArray[3] = $strTokenArray[2][1];
                $strTokenArray[2] = $strTokenArray[2][0];

                // Cleanup, and change Index 1 and Index 3 to be an array based on the
                // parsed column name list
                if (substr($strTokenArray[0], 0, 1) == '`')
                    $strTokenArray[0] = substr($strTokenArray[0], 1, strlen($strTokenArray[0]) - 2);
                $strTokenArray[1] = $this->ParseColumnNameArrayFromKeyDefinition($strTokenArray[1]);
                if (substr($strTokenArray[2], 0, 1) == '`')
                    $strTokenArray[2] = substr($strTokenArray[2], 1, strlen($strTokenArray[2]) - 2);
                $strTokenArray[3] = $this->ParseColumnNameArrayFromKeyDefinition($strTokenArray[3]);

                // Create the FK object and add it to the return array
                $objForeignKey = new dxDatabaseForeignKey($strTokenArray[0], $strTokenArray[1], $strTokenArray[2], $strTokenArray[3]);
                array_push($objForeignKeyArray, $objForeignKey);

                // Ensure the FK object has matching column numbers (or else, throw)
                if ((FrameworkFunctions::getDataSetSize($objForeignKey->ColumnNameArray) == 0) ||
                    (FrameworkFunctions::getDataSetSize($objForeignKey->ColumnNameArray) != FrameworkFunctions::getDataSetSize($objForeignKey->ReferenceColumnNameArray)))
                    throw new Exception("Invalid Foreign Key definition: $strLine");
            }
        }
        return $objForeignKeyArray;
    }

    /**
     * @param $strTableName
     * @return mixed
     */
    private function GetCreateStatementForTable($strTableName) {
        // Use the MySQL "SHOW CREATE TABLE" functionality to get the table's Create statement
        $objResult = $this->Query(sprintf('SHOW CREATE TABLE `%s`', $strTableName));
        $objRow = $objResult->FetchRow();
        $strCreateTable = $objRow[1];
        $strCreateTable = str_replace("\r", "", $strCreateTable);
        return $strCreateTable;
    }

    /**
     * @param $strCreateStatement
     * @return string
     * @throws Exception
     */
    private function GetTableTypeForCreateStatement($strCreateStatement) {
        // Table Type is in the last line of the Create Statement, "TYPE=DbTableType"
        $strLineArray = explode("\n", $strCreateStatement);
        $strFinalLine = strtoupper($strLineArray[FrameworkFunctions::getDataSetSize($strLineArray) - 1]);

        if (substr($strFinalLine, 0, 7) == ') TYPE=') {
            return trim(substr($strFinalLine, 7));
        } else if (substr($strFinalLine, 0, 9) == ') ENGINE=') {
            return trim(substr($strFinalLine, 9));
        } else
            throw new Exception("Invalid Table Description");
    }

    /**
     *
     * @param string $sql
     * @return dxMySqliDatabaseResult
     */
    public function ExplainStatement($sql) {
        // As of MySQL 5.6.3, EXPLAIN provides information about
        // SELECT, DELETE, INSERT, REPLACE, and UPDATE statements.
        // Before MySQL 5.6.3, EXPLAIN provides information only about SELECT statements.

        $objDbResult = $this->Query("select version()");
        $strDbRow = $objDbResult->FetchRow();
        $strVersion = dxType::Cast($strDbRow[0], dxType::String);
        $strVersionArray = explode('.', $strVersion);
        $strMajorVersion = null;
        if (FrameworkFunctions::getDataSetSize($strVersionArray) > 0) {
            $strMajorVersion = $strVersionArray[0];
        }
        if (null === $strMajorVersion) {
            return null;
        }
        if (intval($strMajorVersion) > 5) {
            return $this->Query("EXPLAIN " . $sql);
        } else if (5 == intval($strMajorVersion)) {
            $strMinorVersion = null;
            if (FrameworkFunctions::getDataSetSize($strVersionArray) > 1) {
                $strMinorVersion = $strVersionArray[1];
            }
            if (null === $strMinorVersion) {
                return null;
            }
            if (intval($strMinorVersion) > 6) {
                return $this->Query("EXPLAIN " . $sql);
            } else if (6 == intval($strMinorVersion)) {
                $strSubMinorVersion = null;
                if (FrameworkFunctions::getDataSetSize($strVersionArray) > 2) {
                    $strSubMinorVersion = $strVersionArray[2];
                }
                if (null === $strSubMinorVersion) {
                    return null;
                }
                if (!is_integer($strSubMinorVersion)) {
                    $strSubMinorVersionArray = explode("-", $strSubMinorVersion);
                    if (FrameworkFunctions::getDataSetSize($strSubMinorVersionArray) > 1) {
                        $strSubMinorVersion = $strSubMinorVersionArray[0];
                        if (!is_integer($strSubMinorVersion)) {
                            // Failed to determine the sub-minor version.
                            return null;
                        }
                    } else {
                        // Failed to determine the sub-minor version.
                        return null;
                    }
                }
                if (intval($strSubMinorVersion) > 2) {
                    return $this->Query("EXPLAIN " . $sql);
                } else {
                    // We have the version before 5.6.3
                    // let's check if it is SELECT-only request
                    if (0 == substr_QBaseClass::getDataSetSize($sql, "DELETE") &&
                        0 == substr_QBaseClass::getDataSetSize($sql, "INSERT") &&
                        0 == substr_QBaseClass::getDataSetSize($sql, "REPLACE") &&
                        0 == substr_QBaseClass::getDataSetSize($sql, "UPDATE")
                    ) {
                        return $this->Query("EXPLAIN " . $sql);
                    }
                }
            }
        }
        // Return null by default
        return null;
    }
}

/**
 *
 * @package DatabaseAdapters
 */
class dxMySqliDatabaseException extends dxDatabaseExceptionBase {
    /**
     * dxMySqliDatabaseException constructor.
     * @param $strMessage
     * @param $intNumber
     * @param $strQuery
     */
    public function __construct($strMessage, $intNumber, $strQuery) {
        parent::__construct(sprintf("MySqli Error: %s", $strMessage), 2);
        $this->intErrorNumber = $intNumber;
        $this->strQuery = $strQuery;
    }
}

/**
 *
 * @package DatabaseAdapters
 */
class dxMySqliDatabaseResult extends dxDatabaseResultBase {
    /**
     * @var mysqli_result
     */
    protected $objMySqliResult;
    /**
     * @var dxMySqliDatabase
     */
    protected $objDb;

    /**
     * dxMySqliDatabaseResult constructor.
     * @param mysqli_result $objResult
     * @param dxMySqliDatabase $objDb
     */
    public function __construct(mysqli_result $objResult, dxMySqliDatabase $objDb) {
        $this->objMySqliResult = $objResult;
        $this->objDb = $objDb;
    }

    /**
     * @return mixed
     */
    public function FetchArray() {
        return $this->objMySqliResult->fetch_array();
    }

    /**
     * @return array|mixed
     * @throws Exception
     */
    public function FetchFields() {
        $objArrayToReturn = array();
        while ($objField = $this->objMySqliResult->fetch_field())
            array_push($objArrayToReturn, new dxMySqliDatabaseField($objField, $this->objDb));
        return $objArrayToReturn;
    }

    /**
     * @return dxMySqliDatabaseField|mixed
     * @throws Exception
     */
    public function FetchField() {
        if ($objField = $this->objMySqliResult->fetch_field())
            return new dxMySqliDatabaseField($objField, $this->objDb);
    }

    /**
     * @return mixed
     */
    public function FetchRow() {
        return $this->objMySqliResult->fetch_row();
    }

    /**
     * @return object
     */
    public function MySqlFetchField() {
        return $this->objMySqliResult->fetch_field();
    }

    /**
     * @return int|mixed
     */
    public function CountRows() {
        return $this->objMySqliResult->num_rows;
    }

    /**
     * @return mixed
     */
    public function CountFields() {
        return $this->objMySqliResult->num_fields();
    }

    /**
     * @return mixed|void
     */
    public function Close() {
        $this->objMySqliResult->free();
    }

    /**
     * @return dxMySqliDatabaseRow|mixed|null
     */
    public function GetNextRow() {
        $strColumnArray = $this->FetchArray();

        if ($strColumnArray)
            return new dxMySqliDatabaseRow($strColumnArray);
        else
            return null;
    }

    /**
     * @return array|mixed
     */
    public function GetRows() {
        $objDbRowArray = array();
        while ($objDbRow = $this->GetNextRow())
            array_push($objDbRowArray, $objDbRow);
        return $objDbRowArray;
    }
}

/**
 *
 * @package DatabaseAdapters
 */
class dxMySqliDatabaseRow extends dxDatabaseRowBase {
    /**
     * @var
     */
    protected $strColumnArray;

    /**
     * dxMySqliDatabaseRow constructor.
     * @param $strColumnArray
     */
    public function __construct($strColumnArray) {
        $this->strColumnArray = $strColumnArray;
    }

    /**
     * @param $strColumnName
     * @param null $strColumnType
     * @return dxDateTime|mixed|null
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function GetColumn($strColumnName, $strColumnType = null) {
        if (array_key_exists($strColumnName, $this->strColumnArray)) {
            $strColumnValue = $this->strColumnArray[$strColumnName];
            if (is_null($strColumnValue))
                return null;

            switch ($strColumnType) {
                case dxDatabaseFieldType::Bit:
                    // Account for single bit value
                    $chrBit = $strColumnValue;
                    if ((strlen($chrBit) == 1) && (ord($chrBit) == 0))
                        return false;

                    // Otherwise, use PHP conditional to determine true or false
                    return ($strColumnValue) ? true : false;

                case dxDatabaseFieldType::Blob:
                case dxDatabaseFieldType::Char:
                case dxDatabaseFieldType::VarChar:
                    return dxType::Cast($strColumnValue, dxType::String);

                case dxDatabaseFieldType::Date:
                    return new dxDateTime($strColumnValue, null, dxDateTime::DateOnlyType);
                case dxDatabaseFieldType::DateTime:
                    return new dxDateTime($strColumnValue, null, dxDateTime::DateAndTimeType);
                case dxDatabaseFieldType::Time:
                    return new dxDateTime($strColumnValue, null, dxDateTime::TimeOnlyType);

                case dxDatabaseFieldType::Float:
                    return dxType::Cast($strColumnValue, dxType::Float);

                case dxDatabaseFieldType::Integer:
                    return dxType::Cast($strColumnValue, dxType::Integer);

                default:
                    return $strColumnValue;
            }
        } else
            return null;
    }

    /**
     * @param $strColumnName
     * @return bool|mixed
     */
    public function ColumnExists($strColumnName) {
        return array_key_exists($strColumnName, $this->strColumnArray);
    }

    /**
     * @return mixed
     */
    public function GetColumnNameArray() {
        return $this->strColumnArray;
    }
}

/**
 *
 * @package DatabaseAdapters
 */
class dxMySqliDatabaseField extends dxDatabaseFieldBase {
    /**
     * dxMySqliDatabaseField constructor.
     * @param $mixFieldData
     * @param null $objDb
     * @throws Exception
     */
    public function __construct($mixFieldData, $objDb = null) {
        $this->strName = $mixFieldData->name;
        $this->strOriginalName = $mixFieldData->orgname;
        $this->strTable = $mixFieldData->table;
        $this->strOriginalTable = $mixFieldData->orgtable;
        $this->strDefault = $mixFieldData->def;
        $this->intMaxLength = null;
        $this->strComment = null;

        // Set strOriginalName to Name if it isn't set
        if (!$this->strOriginalName)
            $this->strOriginalName = $this->strName;

        if($this->strOriginalTable)
        {
            $objDescriptionResult = $objDb->Query(sprintf("SHOW FULL FIELDS FROM `%s`", $this->strOriginalTable));
            while (($objRow = $objDescriptionResult->FetchArray())) {
                if ($objRow["Field"] == $this->strOriginalName) {

                    // Calculate MaxLength of this column (e.g. if it's a varchar, calculate length of varchar
                    // NOTE: $mixFieldData->max_length in the MySQL spec is **DIFFERENT**
                    $strLengthArray = explode("(", $objRow["Type"]);
                    if ((FrameworkFunctions::getDataSetSize($strLengthArray) > 1) &&
                        (strtolower($strLengthArray[0]) != 'enum') &&
                        (strtolower($strLengthArray[0]) != 'set')) {
                        $strLengthArray = explode(")", $strLengthArray[1]);
                        $this->intMaxLength = $strLengthArray[0];

                        // If the length is something like (7,2), then let's pull out just the "7"
                        $intCommaPosition = strpos($this->intMaxLength, ',');
                        if ($intCommaPosition !== false) {
                            $this->intMaxLength = substr($this->intMaxLength, 0, $intCommaPosition);
                        }

                        if (!is_numeric($this->intMaxLength)) {
                            throw new Exception("Not a valid Column Length: " . $objRow["Type"]);
                        }
                    }

                    // Get the field comment
                    $this->strComment = $objRow["Comment"];
                }
            }
        }

        $this->blnIdentity = ($mixFieldData->flags & MYSQLI_AUTO_INCREMENT_FLAG) ? true: false;
        $this->blnNotNull = ($mixFieldData->flags & MYSQLI_NOT_NULL_FLAG) ? true : false;
        $this->blnPrimaryKey = ($mixFieldData->flags & MYSQLI_PRI_KEY_FLAG) ? true : false;
        $this->blnUnique = ($mixFieldData->flags & MYSQLI_UNIQUE_KEY_FLAG) ? true : false;

        $this->SetFieldType($mixFieldData->type);
    }

    /**
     * @param $intMySqlFieldType
     * @throws Exception
     */
    protected function SetFieldType($intMySqlFieldType) {
        switch ($intMySqlFieldType) {
            case MYSQLI_TYPE_TINY:
                if ($this->intMaxLength == 1)
                    $this->strType = dxDatabaseFieldType::Bit;
                else
                    $this->strType = dxDatabaseFieldType::Integer;
                break;
            case MYSQLI_TYPE_SHORT:
            case MYSQLI_TYPE_LONG:
            case MYSQLI_TYPE_LONGLONG:
            case MYSQLI_TYPE_INT24:
                $this->strType = dxDatabaseFieldType::Integer;
                break;
            case MYSQLI_TYPE_NEWDECIMAL:
            case MYSQLI_TYPE_DECIMAL:
            case MYSQLI_TYPE_FLOAT:
                $this->strType = dxDatabaseFieldType::Float;
                break;
            case MYSQLI_TYPE_DOUBLE:
                // NOTE: PHP does not offer full support of double-precision floats.
                // Value will be set as a VarChar which will guarantee that the precision will be maintained.
                //    However, you will not be able to support full typing control (e.g. you would
                //    not be able to use a QFloatTextBox -- only a regular QTextBox)
                $this->strType = dxDatabaseFieldType::VarChar;
                break;
            case MYSQLI_TYPE_TIMESTAMP:
                // System-generated Timestamp values need to be treated as plain text
                $this->strType = dxDatabaseFieldType::VarChar;
                $this->blnTimestamp = true;
                break;
            case MYSQLI_TYPE_DATE:
                $this->strType = dxDatabaseFieldType::Date;
                break;
            case MYSQLI_TYPE_TIME:
                $this->strType = dxDatabaseFieldType::Time;
                break;
            case MYSQLI_TYPE_DATETIME:
                $this->strType = dxDatabaseFieldType::DateTime;
                break;
            case MYSQLI_TYPE_TINY_BLOB:
            case MYSQLI_TYPE_MEDIUM_BLOB:
            case MYSQLI_TYPE_LONG_BLOB:
            case MYSQLI_TYPE_BLOB:
                $this->strType = dxDatabaseFieldType::Blob;
                break;
            case MYSQLI_TYPE_STRING:
            case MYSQLI_TYPE_VAR_STRING:
                $this->strType = dxDatabaseFieldType::VarChar;
                break;
            case MYSQLI_TYPE_CHAR:
                $this->strType = dxDatabaseFieldType::Char;
                break;
            case MYSQLI_TYPE_INTERVAL:
                throw new Exception("divblox MySqliDatabase library: MYSQLI_TYPE_INTERVAL is not supported");
                break;
            case MYSQLI_TYPE_NULL:
                throw new Exception("divblox MySqliDatabase library: MYSQLI_TYPE_NULL is not supported");
                break;
            case MYSQLI_TYPE_YEAR:
                $this->strType = dxDatabaseFieldType::Integer;
                break;
            case MYSQLI_TYPE_NEWDATE:
                throw new Exception("divblox MySqliDatabase library: MYSQLI_TYPE_NEWDATE is not supported");
                break;
            case MYSQLI_TYPE_ENUM:
                throw new Exception("divblox MySqliDatabase library: MYSQLI_TYPE_ENUM is not supported.  Use TypeTables instead.");
                break;
            case MYSQLI_TYPE_SET:
                throw new Exception("divblox MySqliDatabase library: MYSQLI_TYPE_SET is not supported.  Use TypeTables instead.");
                break;
            case MYSQLI_TYPE_GEOMETRY:
                throw new Exception("divblox MySqliDatabase library: MYSQLI_TYPE_GEOMETRY is not supported");
                break;
            default:
                throw new Exception("Unable to determine MySqli Database Field Type: " . $intMySqlFieldType);
                break;
        }
    }
}

// New MySQL 5 constanst not yet in PHP (as of PHP 5.1.2)
if (!defined('MYSQLI_TYPE_NEWDECIMAL'))
    /**
     *
     */
    define('MYSQLI_TYPE_NEWDECIMAL', 246);
if (!defined('MYSQLI_TYPE_BIT'))
    /**
     *
     */
    define('MYSQLI_TYPE_BIT', 16);

/**
 *
 * @package DatabaseAdapters
 */
class dxMySqli5Database extends dxMySqliDatabase {
    /**
     *
     */
    const Adapter = 'MySql Improved Database Adapter for MySQL 5';

    /**
     * @return array|mixed
     * @throws dxMySqliDatabaseException
     */
    public function GetTables() {
        // Connect if Applicable
        if (!$this->blnConnectedFlag) $this->Connect();

        // Use the MySQL5 Information Schema to get a list of all the tables in this database
        // (excluding views, etc.)
        $strDatabaseName = $this->Database;

        $objResult = $this->Query("
				SELECT
					table_name
				FROM
					information_schema.tables
				WHERE
					table_type <> 'VIEW' AND
					table_schema = '$strDatabaseName';
			");

        $strToReturn = array();
        while ($strRowArray = $objResult->FetchRow())
            array_push($strToReturn, $strRowArray[0]);
        return $strToReturn;
    }

    /**
     * @param $strQuery
     * @return dxMySqli5DatabaseResult|dxMySqliDatabaseResult|mixed
     * @throws dxMySqliDatabaseException
     */
    protected function ExecuteQuery($strQuery) {
        // Perform the Query
        $objResult = $this->objMySqli->query($strQuery);
        if ($this->objMySqli->error)
            throw new dxMySqliDatabaseException($this->objMySqli->error, $this->objMySqli->errno, $strQuery);

        // Return the Result
        $objMySqliDatabaseResult = new dxMySqli5DatabaseResult($objResult, $this);
        return $objMySqliDatabaseResult;
    }

    /**
     * Performs a Multi Result-Set Query, which is available with Stored Procs in MySQL 5
     * Written by Mike Hostetler
     *
     * @param string $strQuery
     * @return dxMySqli5DatabaseResult[] array of results
     */
    public function MultiQuery($strQuery) {
        // Connect if Applicable
        if (!$this->blnConnectedFlag) $this->Connect();

        // Perform the Query
        $this->objMySqli->multi_query($strQuery);
        if ($this->objMySqli->error)
            throw new dxMySqliDatabaseException($this->objMySqli->error, $this->objMySqli->errno, $strQuery);

        $objResultSets = array();
        do {
            if ($objResult = $this->objMySqli->store_result()) {
                array_push($objResultSets,new dxMySqli5DatabaseResult($objResult, $this));
            }
        } while ($this->objMySqli->more_results() && $this->objMySqli->next_result());

        return $objResultSets;
    }
}

/**
 *
 * @package DatabaseAdapters
 */
class dxMySqli5DatabaseResult extends dxMySqliDatabaseResult {
    /**
     * @return array|mixed
     * @throws Exception
     */
    public function FetchFields() {
        $objArrayToReturn = array();
        while ($objField = $this->objMySqliResult->fetch_field())
            array_push($objArrayToReturn, new dxMySqli5DatabaseField($objField, $this->objDb));
        return $objArrayToReturn;
    }

    /**
     * @return dxMySqli5DatabaseField|dxMySqliDatabaseField|mixed
     * @throws Exception
     */
    public function FetchField() {
        if ($objField = $this->objMySqliResult->fetch_field())
            return new dxMySqli5DatabaseField($objField, $this->objDb);
    }
}

/**
 *
 * @package DatabaseAdapters
 */
class dxMySqli5DatabaseField extends dxMySqliDatabaseField {
    /**
     * @param $intMySqlFieldType
     * @throws Exception
     */
    protected function SetFieldType($intMySqlFieldType) {
        switch ($intMySqlFieldType) {
            case MYSQLI_TYPE_NEWDECIMAL:
                $this->strType = dxDatabaseFieldType::Float;
                break;

            case MYSQLI_TYPE_BIT:
                $this->strType = dxDatabaseFieldType::Bit;
                break;

            default:
                parent::SetFieldType($intMySqlFieldType);
        }
    }
}

// NOTATIONS: http://www.cob.sjsu.edu/johnson_f/notation.htm
abstract class dxConvertNotationBase {
    /**
     * @param $strType
     * @return string
     */
    public static function PrefixFromType($strType) {
        switch ($strType) {
            case dxType::ArrayType:
                return "obj";
            case dxType::Boolean:
                return "bln";
            case dxType::DateTime:
                return "dtt";
            case dxType::Float:
                return "flt";
            case dxType::Integer:
                return "int";
            case dxType::Object:
                return "obj";
            case dxType::String:
                return "str";
        }
    }

    /**
     * @param $strName
     * @return string
     */
    public static function WordsFromUnderscore($strName) {
        $strToReturn = trim(str_replace('_', ' ', $strName));
        if (strtolower($strToReturn) == $strToReturn)
            return ucwords($strToReturn);
        return $strToReturn;
    }

    /**
     * @param $strName
     * @return string
     */
    public static function CamelCaseFromUnderscore($strName) {
        $strToReturn = '';

        // If entire underscore string is all uppercase, force to all lowercase
        // (mixed case and all lowercase can remain as is)
        if ($strName == strtoupper($strName))
            $strName = strtolower($strName);

        while (($intPosition = strpos($strName, "_")) !== false) {
            // Use 'ucfirst' to create camelcasing
            $strName = ucfirst($strName);
            if ($intPosition == 0) {
                $strName = substr($strName, 1);
            } else {
                $strToReturn .= substr($strName, 0, $intPosition);
                $strName = substr($strName, $intPosition + 1);
            }
        }

        $strToReturn .= ucfirst($strName);
        return $strToReturn;
    }

    /**
     * @param $strName
     * @return string
     */
    public static function WordsFromCamelCase($strName) {
        if (strlen($strName) == 0)
            return '';

        $strToReturn = dxString::FirstCharacter($strName);

        for ($intIndex = 1; $intIndex < strlen($strName); $intIndex++) {
            // Get the current character we're examining
            $strChar = substr($strName, $intIndex, 1);

            // Get the character previous to this
            $strPrevChar = substr($strName, $intIndex - 1, 1);

            // If an upper case letter
            if ((ord($strChar) >= ord('A')) &&
                (ord($strChar) <= ord('Z')))
                // Add a Space
                $strToReturn .= ' ' . $strChar;

            // If a digit, and the previous character is NOT a digit
            else if ((ord($strChar) >= ord('0')) &&
                (ord($strChar) <= ord('9')) &&
                ((ord($strPrevChar) < ord('0')) ||
                    (ord($strPrevChar) > ord('9'))))
                // Add a space
                $strToReturn .= ' ' . $strChar;

            // If a letter, and the previous character is a digit
            else if ((ord(strtolower($strChar)) >= ord('a')) &&
                (ord(strtolower($strChar)) <= ord('z')) &&
                (ord($strPrevChar) >= ord('0')) &&
                (ord($strPrevChar) <= ord('9')))
                // Add a space
                $strToReturn .= ' ' . $strChar;

            // Otherwise
            else
                // Don't add a space
                $strToReturn .= $strChar;
        }

        return $strToReturn;
    }

    /**
     * @param $strName
     * @return string
     */
    public static function UnderscoreFromCamelCase($strName) {
        if (strlen($strName) == 0)
            return '';

        $strToReturn = dxString::FirstCharacter($strName);

        for ($intIndex = 1; $intIndex < strlen($strName); $intIndex++) {
            $strChar = substr($strName, $intIndex, 1);
            if (strtoupper($strChar) == $strChar)
                $strToReturn .= '_' . $strChar;
            else
                $strToReturn .= $strChar;
        }

        return strtolower($strToReturn);
    }

    /**
     * @param $strName
     * @return string
     */
    public static function JavaCaseFromUnderscore($strName) {
        $strToReturn = dxConvertNotation::CamelCaseFromUnderscore($strName);
        return strtolower(substr($strToReturn, 0, 1)) . substr($strToReturn, 1);
    }
}

/**
 * dxConvertNotation
 *
 * Feel free to override any core dxConvertNotationBase methods here
 *
 * @category divblox
 * @package Codegen
 * @author divblox
 * @copyright
 * @access public
 */
abstract class dxConvertNotation extends dxConvertNotationBase {
}

/**
 * abstract cache provider
 */
abstract class dxAbstractCacheProvider {
    /**
     * Get the object that has the given key from the cache
     * @param string $strKey the key of the object in the cache
     * @return object
     */
    abstract public function Get($strKey);

    /**
     * Set the object into the cache with the given key
     * @param string $strKey the key to use for the object
     * @param object $objValue the object to put in the cache
     * @return void
     */
    abstract public function Set($strKey, $objValue);

    /**
     * Delete the object that has the given key from the cache
     * @param string $strKey the key of the object in the cache
     * @return void
     */
    abstract public function Delete($strKey);

    /**
     * Invalidate all the objects in the cache
     * @return void
     */
    abstract public function DeleteAll();

    /**
     * Create a key appropriate for this cache provider
     * @return string the key
     */
    public function CreateKey(/* ... */) {
        // @hack for php version < 5.4
        $objArgsArray = array();
        $arg_list = func_get_args();
        $numargs = func_num_args();
        for ($i = 0; $i < $numargs; $i++) {
            $arg = $arg_list[$i];
            if (is_array($arg)) {
                foreach ($arg as $a) {
                    $objArgsArray[] = $a;
                }
            } else {
                $objArgsArray[] = $arg;
            }
        }

        return implode(":", $objArgsArray);
        //return implode(":", func_get_args());
    }
}

/**
 * No-op cache provider: No caching at all.
 * Use it to disable caching support.
 */
class dxCacheProviderNoCache extends dxAbstractCacheProvider {
    /**
     * Get the object that has the given key from the cache
     * @param string $strKey the key of the object in the cache
     * @return object
     */
    public function Get($strKey) {
        return false;
    }

    /**
     * Set the object into the cache with the given key
     * @param string $strKey the key to use for the object
     * @param object $objValue the object to put in the cache
     * @return void
     */
    public function Set($strKey, $objValue) {
        // do nothing
    }

    /**
     * Delete the object that has the given key from the cache
     * @param string $strKey the key of the object in the cache
     * @return void
     */
    public function Delete($strKey) {
        // do nothing
    }

    /**
     * Invalidate all the objects in the cache
     * @return void
     */
    public function DeleteAll() {
        // do nothing
    }
}
//endregion

//region ORM Query classes
/**
 * The abstract dxQueryBaseNode class
 * @property-read dxQueryBaseNode $_ParentNode
 * @property-read string $_Name
 * @property-read string $_Alias
 * @property-write string $Alias
 * @property-read string $_PropertyName
 * @property-read string $_Type
 * @property-read string $_RootTableName
 * @property-read string $_TableName
 * @property-read string $_PrimaryKey
 * @property-read string $_ClassName
 * @property-read dxQueryBaseNode $_PrimaryKeyNode
 */
abstract class dxQueryBaseNode extends dxBaseClass {
    /**
     * @var
     */
    protected $objParentNode;
    /**
     * @var
     */
    protected $strType;
    /**
     * @var
     */
    protected $strName;
    /**
     * @var
     */
    protected $strAlias;
    /**
     * @var
     */
    protected $strPropertyName;
    /**
     * @var
     */
    protected $strRootTableName;

    /**
     * @var
     */
    protected $strTableName;
    /**
     * @var
     */
    protected $strPrimaryKey;
    /**
     * @var
     */
    protected $strClassName;

    // used by expansion nodes
    /**
     * @var
     */
    protected $blnExpandAsArray;
    /**
     * @var
     */
    protected $objChildNodeArray;

    /**
     * @param string $strName
     * @return mixed
     * @throws dxCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case '_ParentNode':
                return $this->objParentNode;
            case '_Name':
                return $this->strName;
            case '_Alias':
                return $this->strAlias;
            case '_PropertyName':
                return $this->strPropertyName;
            case '_Type':
                return $this->strType;
            case '_RootTableName':
                return $this->strRootTableName;
            case '_TableName':
                return $this->strTableName;
            case '_PrimaryKey':
                return $this->strPrimaryKey;
            case '_ClassName':
                return $this->strClassName;
            case '_PrimaryKeyNode':
                return null;

            case 'ExpandAsArray':
                return $this->blnExpandAsArray;
            case 'ChildNodeArray':
                return $this->objChildNodeArray;

            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * @param string $strName
     * @param string $mixValue
     * @return mixed
     * @throws dxCallerException
     */
    public function __set($strName, $mixValue) {
        switch ($strName) {
            case 'Alias':
                /**
                 * Sets the value for strAlias
                 * @param string $mixValue
                 * @return string
                 */
                try {
                    return ($this->strAlias= dxType::Cast($mixValue, dxType::String));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            case 'ExpandAsArray':
                try {
                    return ($this->blnExpandAsArray = dxType::Cast($mixValue, dxType::Boolean));
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }

            default:
                try {
                    return parent::__set($strName, $mixValue);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param $blnExpandSelection
     * @param dxQuerySelect|null $objSelect
     * @return mixed
     */
    abstract public function GetColumnAliasHelper(dxQueryBuilder $objBuilder, $blnExpandSelection, dxQuerySelect $objSelect = null);

    /**
     * @param dxQueryBuilder $objBuilder
     * @param bool $blnExpandSelection
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @return mixed
     */
    abstract public function GetColumnAlias(dxQueryBuilder $objBuilder, $blnExpandSelection = false, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect = null);

    /**
     * Merges a node tree into this node, building the child nodes. The node being received
     * is assumed to be specially built node such that only one child node exists, if any,
     * and the last node in the chain is designated as array expansion. The goal of all of this
     * is to set up a node chain where intermediate nodes can be designated as being array
     * expansion nodes, as well as the leaf nodes.
     *
     * @param dxQueryBaseNode $objNewNode
     */
    public function _MergeExpansionNode (dxQueryBaseNode $objNewNode) {
        if (!$objNewNode || empty($objNewNode->objChildNodeArray)) {
            return;
        }
        if ($objNewNode->strName != $this->strName) {
            throw new dxCallerException('Expansion node tables must match.');
        }

        if (!$this->objChildNodeArray) {
            $this->objChildNodeArray = $objNewNode->objChildNodeArray;
        } else {
            $objChildNode = reset($objNewNode->objChildNodeArray);
            if (isset ($this->objChildNodeArray[$objChildNode->strName])) {
                if ($objChildNode->blnExpandAsArray) {
                    $this->objChildNodeArray[$objChildNode->strName]->blnExpandAsArray = true;
                    // assume this is a leaf node, so don't follow any more.
                }
                else {
                    $this->objChildNodeArray[$objChildNode->strName]->_MergeExpansionNode ($objChildNode);
                }
            } else {
                $this->objChildNodeArray[$objChildNode->strName] = $objChildNode;
            }
        }
    }

    /**
     * @return string
     */
    public function ExtendedAlias () {
        $strExtendedAlias = $this->strAlias;
        $objNode = $this;
        while ($objNode->objParentNode) {
            $objNode = $objNode->objParentNode;
            $strExtendedAlias = $objNode->strAlias . '__' . $strExtendedAlias;
        }
        return $strExtendedAlias;
    }

    /**
     * @return mixed|null
     */
    public function FirstChild() {
        $a = $this->objChildNodeArray;
        if ($a) {
            return reset ($a);
        } else {
            return null;
        }
    }

}

class dxQueryNode extends dxQueryBaseNode {
    /**
     * dxQueryNode constructor.
     * @param $strName
     * @param $strPropertyName
     * @param $strType
     * @param dxQueryBaseNode|null $objParentNode
     * @throws dxCallerException
     */
    public function __construct($strName, $strPropertyName, $strType, dxQueryBaseNode $objParentNode = null) {
        $this->objParentNode = $objParentNode;
        $this->strName = $strName;
        if ($objParentNode) $objParentNode->objChildNodeArray[$strName] = $this;

        $this->strAlias = $strName;
        $this->strPropertyName = $strPropertyName;
        $this->strType = $strType;
        if ($objParentNode) {
            if (version_compare(PHP_VERSION, '5.1.0') == -1)
                $this->strRootTableName = $objParentNode->__get('_RootTableName');
            else
                $this->strRootTableName = $objParentNode->_RootTableName;
        } else
            $this->strRootTableName = $strName;
    }

    /**
     * @param mixed $mixValue
     * @param dxQueryBuilder $objBuilder
     * @param boolean $blnEqualityType can be null (for no equality), true (to add a standard "equal to") or false (to add a standard "not equal to")
     * @return string
     */
    public function GetValue($mixValue, dxQueryBuilder $objBuilder, $blnEqualityType = null) {
        if ($mixValue instanceof dxQueryNamedValue) {
            /** @var dxQueryNamedValue $mixValue */
            return $mixValue->Parameter($blnEqualityType);
        }

        if ($mixValue instanceof dxQueryNode) {
            /** @var dxQueryNode $mixValue */
            if (is_null($blnEqualityType))
                $strToReturn = '';
            else if ($blnEqualityType)
                $strToReturn = '= ';
            else
                $strToReturn = '!= ';

            try {
                return $strToReturn . $mixValue->GetColumnAlias($objBuilder);
            } catch (dxCallerException $objExc) {
                $objExc->IncrementOffset();
                throw $objExc;
            }
        } else {
            if (is_null($blnEqualityType)) {
                $blnIncludeEquality = false;
                $blnReverseEquality = false;
            } else {
                $blnIncludeEquality = true;
                if ($blnEqualityType)
                    $blnReverseEquality = false;
                else
                    $blnReverseEquality = true;
            }

//				try {
//					return $objBuilder->Database->SqlVariable(dxType::Cast($mixValue, $this->_Type), $blnIncludeEquality, $blnReverseEquality);
//				} catch (dxCallerException $objExc) {
//					$objExc->IncrementOffset();
//					$objExc->IncrementOffset();
//					throw $objExc;
//				}
            return $objBuilder->Database->SqlVariable($mixValue, $blnIncludeEquality, $blnReverseEquality);
        }
    }

    /**
     * @return string
     */
    public function GetAsManualSqlColumn() {
        if ($this->strTableName)
            return $this->strTableName . '.' . $this->strName;
        else if (($this->objParentNode) && ($this->objParentNode->strTableName))
            return $this->objParentNode->strTableName . '.' . $this->strName;
        else
            return $this->strName;
    }

    /**
     * @return bool
     */
    public function isTopLevelLeafNode() {
        return (get_class($this) == 'dxQueryNode') && (is_null($this->objParentNode->_Type));
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param bool $blnExpandSelection
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @return mixed|string
     * @throws dxCallerException
     */
    public function GetTable(dxQueryBuilder $objBuilder, $blnExpandSelection = false, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect = null) {
        // Make sure our Root Tables Match
        if ($this->_RootTableName != $objBuilder->RootTableName)
            throw new dxCallerException('Cannot use dxQueryNode for "' . $this->_RootTableName . '" when querying against the "' . $objBuilder->RootTableName . '" table', 3);

        // If we are a standard dxQueryNode at the top level column, simply return the column name
        if ($this->isTopLevelLeafNode()) {
            return $this->objParentNode->_Name;
        } else {
            // Use the Helper to Iterate Through the Parent Chain and get the Parent Alias
            try {
                $strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $blnExpandSelection, $objSelect ? dxQuery::Select() : null);

                if ($this->strTableName) {
                    $strJoinTableAlias = $strParentAlias . '__' . $this->strName;
                    // Next, Join the Appropriate Table
                    $this->addJoinTable($objBuilder, $strJoinTableAlias, $strParentAlias, $objJoinCondition);

                    if ($blnExpandSelection)
                        call_user_func(array($this->strClassName, 'GetSelectFields'), $objBuilder, $strJoinTableAlias, $objSelect);
                }
            } catch (dxCallerException $objExc) {
                $objExc->IncrementOffset();
                throw $objExc;
            }

            return $strParentAlias;
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param bool $blnExpandSelection
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @return mixed|string
     * @throws dxCallerException
     */
    public function GetTableAlias(dxQueryBuilder $objBuilder, $blnExpandSelection = false, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect = null) {
        $strTable = $this->GetTable($objBuilder, $blnExpandSelection, $objJoinCondition, $objSelect);
        return $objBuilder->GetTableAlias($strTable);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param $strTableAlias
     * @return string
     */
    public function MakeColumnAlias(dxQueryBuilder $objBuilder, $strTableAlias) {
        $strBegin = $objBuilder->Database->EscapeIdentifierBegin;
        $strEnd = $objBuilder->Database->EscapeIdentifierEnd;

        return sprintf('%s%s%s.%s%s%s',
            $strBegin, $strTableAlias, $strEnd,
            $strBegin, $this->strName, $strEnd);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param bool $blnExpandSelection
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @return mixed|string
     * @throws dxCallerException
     */
    public function GetColumnAlias(dxQueryBuilder $objBuilder, $blnExpandSelection = false, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect = null) {
        $strTableAlias = $this->GetTableAlias($objBuilder, $blnExpandSelection, $objJoinCondition, $objSelect);
        // Pull the Begin and End Escape Identifiers from the Database Adapter
        return $this->MakeColumnAlias($objBuilder, $strTableAlias);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param $strJoinTableAlias
     * @param $strParentAlias
     * @param dxQueryCondition|null $objJoinCondition
     * @throws dxCallerException
     */
    protected function addJoinTable(dxQueryBuilder $objBuilder, $strJoinTableAlias, $strParentAlias, dxQueryCondition $objJoinCondition = null) {
        $objBuilder->AddJoinItem($this->strTableName, $strJoinTableAlias,
            $strParentAlias, $this->strName, $this->strPrimaryKey, $objJoinCondition);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param $blnExpandSelection
     * @param dxQuerySelect|null $objSelect
     * @return mixed|string
     * @throws dxCallerException
     */
    public function GetColumnAliasHelper(dxQueryBuilder $objBuilder, $blnExpandSelection, dxQuerySelect $objSelect = null) {
        // Are we at the Parent Node?
        if (is_null($this->objParentNode))
            // Yep -- Simply return the Parent Node Name
            return $this->strName;
        else {
            try {
                // No -- First get the Parent Alias
                $strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $blnExpandSelection, $objSelect ? dxQuery::Select() : null);

                $strJoinTableAlias = $strParentAlias . '__' . $this->strAlias;
                // Next, Join the Appropriate Table
                $this->addJoinTable($objBuilder, $strJoinTableAlias, $strParentAlias);
            } catch (dxCallerException $objExc) {
                $objExc->IncrementOffset();
                throw $objExc;
            }

            // Next, Expand the Selection Fields for this Table (if applicable)
            if ($blnExpandSelection) {
                call_user_func(array($this->strClassName, 'GetSelectFields'), $objBuilder, $strJoinTableAlias, $objSelect);
            }

            // Return the Parent Alias
            return $strParentAlias . '__' . $this->strAlias;
        }
    }


    //////////////////
    // Helpers for Orm-generated DataGrids
    //////////////////
    /**
     * @param $strNodeLabelArray
     * @param $intIndex
     * @return string
     */
    protected function GetDataGridHtmlHelper($strNodeLabelArray, $intIndex) {
        if (($intIndex + 1) == FrameworkFunctions::getDataSetSize($strNodeLabelArray))
            return $strNodeLabelArray[$intIndex];
        else
            return sprintf('(%s ? %s : null)', $strNodeLabelArray[$intIndex], $this->GetDataGridHtmlHelper($strNodeLabelArray, $intIndex + 1));
    }

    /**
     * @return string
     * @throws Exception
     */
    public function GetDataGridHtml() {
        // Array-ify Node Hierarchy
        $objNodeArray = array();

        $objNodeArray[] = $this;
        while ($objNodeArray[FrameworkFunctions::getDataSetSize($objNodeArray) - 1]->objParentNode)
            $objNodeArray[] = $objNodeArray[FrameworkFunctions::getDataSetSize($objNodeArray) - 1]->objParentNode;

        $objNodeArray = array_reverse($objNodeArray, false);

        // Go through the objNodeArray to build out the DataGridHtml

        // Error Behavior
        if (FrameworkFunctions::getDataSetSize($objNodeArray) < 2)
            throw new Exception('Invalid dxQueryNode to GetDataGridHtml on');

        // Simple Two-Step Node
        else if (FrameworkFunctions::getDataSetSize($objNodeArray) == 2)
            $strToReturn = '$_ITEM->' . $objNodeArray[1]->strPropertyName;

        // Complex N-Step Node
        else {
            $strNodeLabelArray[0] = '$_ITEM->' . $objNodeArray[1]->strPropertyName;
            for ($intIndex = 2; $intIndex < FrameworkFunctions::getDataSetSize($objNodeArray); $intIndex++) {
                $strNodeLabelArray[$intIndex - 1] = $strNodeLabelArray[$intIndex - 2] . '->' . $objNodeArray[$intIndex]->strPropertyName;
            }

            $strToReturn = $this->GetDataGridHtmlHelper($strNodeLabelArray, 0);
        }

        if($this->strType == dxDatabaseFieldType::Time)
            return sprintf('(%s) ? %s->qFormat(dxDateTime::$DefaultTimeFormat) : null', $strToReturn, $strToReturn);

        if ($this->strType == dxDatabaseFieldType::Bit)
            return sprintf('(null === %s)? "" : ((%s)? "%s" : "%s")', $strToReturn, $strToReturn, FrameworkFunctions::Translate('True'), FrameworkFunctions::Translate('False'));

        if (class_exists($this->strClassName))
            return sprintf('(%s) ? %s->__toString() : null;', $strToReturn, $strToReturn);

        return $strToReturn;
    }

    /**
     * @return $this|dxQueryBaseNode
     */
    public function GetDataGridOrderByNode() {
        if ($this instanceof dxQueryReverseReferenceNode)
            return $this->_PrimaryKeyNode;
        else
            return $this;
    }

    /**
     * @param QDataGridColumn $col
     */
    public function SetFilteredDataGridColumnFilter(QDataGridColumn $col)
    {
        switch($this->strType)
        {
            case dxDatabaseFieldType::Bit:
                //List of true / false / any
                $col->FilterType = QFilterType::ListFilter;
                $col->FilterAddListItem("True", dxQuery::Equal($this, true));
                $col->FilterAddListItem("False", dxQuery::Equal($this, false));
                $col->FilterAddListItem("Set", dxQuery::IsNotNull($this));
                $col->FilterAddListItem("Unset", dxQuery::IsNull($this));
                break;
            case dxDatabaseFieldType::Blob:
            case dxDatabaseFieldType::Char:
            case dxDatabaseFieldType::Time:
            case dxDatabaseFieldType::VarChar:
            case dxDatabaseFieldType::Date:
            case dxDatabaseFieldType::DateTime:
                //LIKE
                $col->FilterType = QFilterType::TextFilter;
                $col->FilterPrefix = '%';
                $col->FilterPostfix = '%';
                $col->Filter = dxQuery::Like($this, null);
                break;
            case dxDatabaseFieldType::Float:
            case dxDatabaseFieldType::Integer:
                //EQUAL
                $col->FilterType = QFilterType::TextFilter;
                $col->Filter = dxQuery::Equal($this, null);
                break;
            case dxType::Object:
            case dxType::Resource:
            default:
                //this node points to a class, there's no way to know what to filter on
                $col->FilterType = QFilterType::None;
                $col->ClearFilter();
                break;
        }
    }
}

class dxQueryReverseReferenceNode extends dxQueryNode {
    /**
     * @var
     */
    protected $strForeignKey;

    /**
     * dxQueryReverseReferenceNode constructor.
     * @param dxQueryBaseNode $objParentNode
     * @param $strName
     * @param $strType
     * @param $strForeignKey
     * @param null $strPropertyName
     * @throws dxCallerException
     */
    public function __construct(dxQueryBaseNode $objParentNode, $strName, $strType, $strForeignKey, $strPropertyName = null) {
        $this->objParentNode = $objParentNode;
        if ($objParentNode) {
            if (version_compare(PHP_VERSION, '5.1.0') == -1)
                $this->strRootTableName = $objParentNode->__get('_RootTableName');
            else
                $this->strRootTableName = $objParentNode->_RootTableName;
        } else
            throw new dxCallerException('ReverseReferenceNodes must have a Parent Node');
        $this->strName = $strName;
        $objParentNode->objChildNodeArray[$strName] = $this;
        $this->strAlias = $strName;
        $this->strType = $strType;
        $this->strForeignKey = $strForeignKey;
        $this->strPropertyName = $strPropertyName;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param $strJoinTableAlias
     * @param $strParentAlias
     * @param dxQueryCondition|null $objJoinCondition
     * @throws dxCallerException
     */
    protected function addJoinTable(dxQueryBuilder $objBuilder, $strJoinTableAlias, $strParentAlias, dxQueryCondition $objJoinCondition = null) {
        $objBuilder->AddJoinItem($this->strTableName, $strJoinTableAlias,
            $strParentAlias, $this->objParentNode->_PrimaryKey, $this->strForeignKey, $objJoinCondition);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param bool $blnExpandSelection
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @return mixed|null|string
     * @throws dxCallerException
     */
    public function GetColumnAlias(dxQueryBuilder $objBuilder, $blnExpandSelection = false, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect = null) {
        $this->GetTableAlias($objBuilder, $blnExpandSelection, $objJoinCondition, $objSelect);
        return null;
    }

    /**
     * @return string
     */
    public function GetExpandArrayAlias() {
//			$objNode = $this;
//			$objChildTableNode = $this->_ChildTableNode;
//			$strToReturn = $objChildTableNode->_Name . '__' . $objChildTableNode->_PrimaryKey;
        $strToReturn = $this->strName . '__' . $this->_PrimaryKey;

        $objNode = $this->_ParentNode;
        while ($objNode) {
            $strToReturn = $objNode->_Name . '__' . $strToReturn;
            $objNode = $objNode->_ParentNode;
        }

        return $strToReturn;
    }
}

/**
 * @property-read dxQueryNode $_ChildTableNode
 */
class dxQueryAssociationNode extends dxQueryBaseNode {
    /**
     * dxQueryAssociationNode constructor.
     * @param dxQueryBaseNode $objParentNode
     * @throws dxCallerException
     */
    public function __construct(dxQueryBaseNode $objParentNode) {
        $this->objParentNode = $objParentNode;
        if ($objParentNode) {
            if (version_compare(PHP_VERSION, '5.1.0') == -1)
                $this->strRootTableName = $objParentNode->__get('_RootTableName');
            else
                $this->strRootTableName = $objParentNode->_RootTableName;

            $objParentNode->objChildNodeArray[$this->strName] = $this;
        } else
            $this->strRootTableName = $this->strName;
        $this->strAlias = $this->strName;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param bool $blnExpandSelection
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @return mixed|string
     * @throws dxCallerException
     */
    public function GetColumnAlias(dxQueryBuilder $objBuilder, $blnExpandSelection = false, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect = null) {
        // Make sure our Root Tables Match
        if ($this->_RootTableName != $objBuilder->RootTableName)
            throw new dxCallerException('Cannot use dxQueryNode for "' . $this->_RootTableName . '" when querying against the "' . $objBuilder->RootTableName . '" table', 3);

        // Pull the Begin and End Escape Identifiers from the Database Adapter
        $strBegin = $objBuilder->Database->EscapeIdentifierBegin;
        $strEnd = $objBuilder->Database->EscapeIdentifierEnd;

        // If we are a standard dxQueryNode at the top level column, simply return the column name
        if ((get_class($this) == 'dxQueryNode') && (is_null($this->objParentNode->_Type)))
            return sprintf('%s%s%s.%s%s%s',
                $strBegin, $this->objParentNode->_Name, $strEnd,
                $strBegin, $this->strName, $strEnd);
        else {
            // Use the Helper to Iterate Through the Parent Chain and get the Parent Alias
            $strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $blnExpandSelection, $objSelect ? dxQuery::Select() : null);

            if ($this->strTableName) {
                // Next, Join the Appropriate Table
                $objBuilder->AddJoinItem($this->strTableName, $strParentAlias . '__' . $this->strName,
                    $strParentAlias, $this->strName, $this->strPrimaryKey);

                if ($blnExpandSelection)
                    call_user_func(array($this->strClassName, 'GetSelectFields'), $objBuilder, $strParentAlias . '__' . $this->strName, $objSelect);
            }

            // Finally, return the final column alias name (Parent Prefix with Current Node Name)
            return sprintf('%s%s%s.%s%s%s',
                $strBegin, $strParentAlias, $strEnd,
                $strBegin, $this->strName, $strEnd);
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param $blnExpandSelection
     * @param dxQuerySelect|null $objSelect
     * @return mixed|string
     * @throws dxCallerException
     */
    public function GetColumnAliasHelper(dxQueryBuilder $objBuilder, $blnExpandSelection, dxQuerySelect $objSelect = null) {
        // Are we at the Parent Node?
        if (is_null($this->objParentNode))
            // Yep -- Simply return the Parent Node Name
            return $this->strName;
        else {
            // No -- First get the Parent Alias
            $strParentAlias = $this->objParentNode->GetColumnAliasHelper($objBuilder, $blnExpandSelection, $objSelect ? dxQuery::Select() : null);

            // Next, Join the Appropriate Table
            $objBuilder->AddJoinItem($this->strTableName, $strParentAlias . '__' . $this->strAlias,
                $strParentAlias, $this->objParentNode->_PrimaryKey, $this->strPrimaryKey);

            // Return the Parent Alias
            return $strParentAlias . '__' . $this->strAlias;
        }
    }

    /**
     * @return string
     */
    public function GetExpandArrayAlias() {
        $objNode = $this;
        $objChildTableNode = $this->_ChildTableNode;
        $strToReturn = $objChildTableNode->_Name . '__' . $objChildTableNode->_PrimaryKey;
        while ($objNode) {
            $strToReturn = $objNode->_Name . '__' . $strToReturn;
            $objNode = $objNode->_ParentNode;
        }

        return $strToReturn;
    }
}

class dxQueryNamedValue extends dxQueryNode {
    /**
     *
     */
    const DelimiterCode = 3;

    /**
     * dxQueryNamedValue constructor.
     * @param $strName
     */
    public function __construct($strName) {
        $this->strName = $strName;
    }

    /**
     * @param null $blnEqualityType
     * @return string
     */
    public function Parameter($blnEqualityType = null) {
        if (is_null($blnEqualityType))
            return chr(dxQueryNamedValue::DelimiterCode) . '{' . $this->strName . '}';
        else if ($blnEqualityType)
            return chr(dxQueryNamedValue::DelimiterCode) . '{=' . $this->strName . '=}';
        else
            return chr(dxQueryNamedValue::DelimiterCode) . '{!' . $this->strName . '!}';
    }
}

abstract class dxQueryCondition extends dxBaseClass {
    /**
     * @var
     */
    protected $strOperator;

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed
     */
    abstract public function UpdateQueryBuilder(dxQueryBuilder $objBuilder);

    /**
     * @return string
     */
    public function __toString() {
        return 'dxQueryCondition Object';
    }

    /**
     * @var
     */
    protected $blnProcessed;

    /**
     * Used internally by divblox Query to get an individual where clause for a given condition
     * Mostly used for conditional joins.
     *
     * @param dxDatabaseBase $objDatabase
     * @param string $strRootTableName
     * @param boolean $blnProcessOnce
     */
    public function GetWhereClause(dxQueryBuilder $objBuilder, $blnProcessOnce = false) {
        if ($blnProcessOnce && $this->blnProcessed)
            return null;

        $this->blnProcessed = true;

        try {
            $objConditionBuilder = new dxQueryPartialQueryBuilder($objBuilder);
            $this->UpdateQueryBuilder($objConditionBuilder);
            return $objConditionBuilder->GetWhereStatement();
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }
}

class dxQueryConditionAll extends dxQueryCondition {
    /**
     * dxQueryConditionAll constructor.
     * @param $mixParameterArray
     * @throws dxCallerException
     */
    public function __construct($mixParameterArray) {
        if (FrameworkFunctions::getDataSetSize($mixParameterArray))
            throw new dxCallerException('All clause takes in no parameters', 3);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddWhereItem('1=1');
    }
}

class dxQueryConditionNone extends dxQueryCondition {
    /**
     * dxQueryConditionNone constructor.
     * @param $mixParameterArray
     * @throws dxCallerException
     */
    public function __construct($mixParameterArray) {
        if (FrameworkFunctions::getDataSetSize($mixParameterArray))
            throw new dxCallerException('None clause takes in no parameters', 3);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddWhereItem('1=0');
    }
}

abstract class dxQueryConditionLogical extends dxQueryCondition {
    /** @var dxQueryCondition[] */
    protected $objConditionArray;

    /**
     * @param $mixParameterArray
     * @return array
     * @throws dxCallerException
     */
    protected function CollapseConditions($mixParameterArray) {
        $objConditionArray = array();
        foreach ($mixParameterArray as $mixParameter) {
            if (is_array($mixParameter))
                $objConditionArray = array_merge($objConditionArray, $mixParameter);
            else
                array_push($objConditionArray, $mixParameter);
        }

        foreach ($objConditionArray as $objCondition)
            if (!($objCondition instanceof dxQueryCondition))
                throw new dxCallerException('Logical Or/And clause parameters must all be dxQueryCondition objects', 3);

        if (FrameworkFunctions::getDataSetSize($objConditionArray))
            return $objConditionArray;
        else
            throw new dxCallerException('No parameters passed in to logical Or/And clause', 3);
    }

    /**
     * dxQueryConditionLogical constructor.
     * @param $mixParameterArray
     * @throws dxCallerException
     */
    public function __construct($mixParameterArray) {
        $objConditionArray = $this->CollapseConditions($mixParameterArray);
        try {
            $this->objConditionArray = dxType::Cast($objConditionArray, dxType::ArrayType);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $intLength = FrameworkFunctions::getDataSetSize($this->objConditionArray);
        if ($intLength) {
            $objBuilder->AddWhereItem('(');
            for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
                if (!($this->objConditionArray[$intIndex] instanceof dxQueryCondition))
                    throw new dxCallerException($this->strOperator . ' clause has elements that are not Conditions');
                try {
                    $this->objConditionArray[$intIndex]->UpdateQueryBuilder($objBuilder);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
                if (($intIndex + 1) != $intLength)
                    $objBuilder->AddWhereItem($this->strOperator);
            }
            $objBuilder->AddWhereItem(')');
        }
    }
}

class dxQueryConditionOr extends dxQueryConditionLogical {
    /**
     * @var string
     */
    protected $strOperator = 'OR';
}

class dxQueryConditionAnd extends dxQueryConditionLogical {
    /**
     * @var string
     */
    protected $strOperator = 'AND';
}

class dxQueryConditionNot extends dxQueryCondition {
    /**
     * @var dxQueryCondition
     */
    protected $objCondition;

    /**
     * dxQueryConditionNot constructor.
     * @param dxQueryCondition $objCondition
     */
    public function __construct(dxQueryCondition $objCondition) {
        $this->objCondition = $objCondition;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddWhereItem('(NOT');
        try {
            $this->objCondition->UpdateQueryBuilder($objBuilder);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
        $objBuilder->AddWhereItem(')');
    }
}

abstract class dxQueryConditionComparison extends dxQueryCondition {
    /**
     * @var dxQueryNode
     */
    public $objQueryNode;
    /**
     * @var dxQueryNode
     */
    public $mixOperand;

    /**
     * dxQueryConditionComparison constructor.
     * @param dxQueryNode $objQueryNode
     * @param $mixOperand
     * @throws dxInvalidCastException
     */
    public function __construct(dxQueryNode $objQueryNode, $mixOperand) {
        $this->objQueryNode = $objQueryNode;
        if (!$objQueryNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based dxQueryNode', 3);

        if ($mixOperand instanceof dxQueryNamedValue)
            $this->mixOperand = $mixOperand;
        else if ($mixOperand instanceof dxQueryAssociationNode)
            throw new dxInvalidCastException('Comparison operand cannot be an Association-based dxQueryNode', 3);
        else if ($mixOperand instanceof dxQueryCondition)
            throw new dxInvalidCastException('Comparison operand cannot be a dxQueryCondition', 3);
        else if ($mixOperand instanceof dxQueryClause)
            throw new dxInvalidCastException('Comparison operand cannot be a dxQueryClause', 3);
        else if (!($mixOperand instanceof dxQueryNode)) {
//				try {
//					$this->mixOperand = dxType::Cast($mixOperand, $objQueryNode->_Type);
//				} catch (dxCallerException $objExc) {
//					$objExc->IncrementOffset();
//					$objExc->IncrementOffset();
//					throw $objExc;
//				}
            $this->mixOperand = $mixOperand;
        } else {
            if (!$mixOperand->_ParentNode)
                throw new dxInvalidCastException('Unable to cast "' . $mixOperand->_Name . '" table to Column-based dxQueryNode', 3);
            $this->mixOperand = $mixOperand;
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . $this->strOperator . $this->objQueryNode->GetValue($this->mixOperand, $objBuilder));
    }
}

class dxQueryConditionIsNull extends dxQueryConditionComparison {
    /**
     * dxQueryConditionIsNull constructor.
     * @param dxQueryNode $objQueryNode
     * @throws dxInvalidCastException
     */
    public function __construct(dxQueryNode $objQueryNode) {
        $this->objQueryNode = $objQueryNode;
        if (!$objQueryNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based dxQueryNode', 3);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IS NULL');
    }
}

class dxQueryConditionIsNotNull extends dxQueryConditionComparison {
    /**
     * dxQueryConditionIsNotNull constructor.
     * @param dxQueryNode $objQueryNode
     * @throws dxInvalidCastException
     */
    public function __construct(dxQueryNode $objQueryNode) {
        $this->objQueryNode = $objQueryNode;
        if (!$objQueryNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based dxQueryNode', 3);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IS NOT NULL');
    }
}

class dxQueryConditionIn extends dxQueryConditionComparison {
    /**
     * dxQueryConditionIn constructor.
     * @param dxQueryNode $objQueryNode
     * @param $mixValuesArray
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct(dxQueryNode $objQueryNode, $mixValuesArray) {
        $this->objQueryNode = $objQueryNode;
        if (!$objQueryNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based dxQueryNode', 3);

        if ($mixValuesArray instanceof dxQueryNamedValue)
            $this->mixOperand = $mixValuesArray;
        else if ($mixValuesArray instanceof dxQuerySubQueryNode)
            $this->mixOperand = $mixValuesArray;
        else {
            try {
                $this->mixOperand = dxType::Cast($mixValuesArray, dxType::ArrayType);
            } catch (dxCallerException $objExc) {
                $objExc->IncrementOffset();
                $objExc->IncrementOffset();
                throw $objExc;
            }
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $mixOperand = $this->mixOperand;
        if ($mixOperand instanceof dxQueryNamedValue) {
            /** @var dxQueryNamedValue $mixOperand */
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IN (' . $mixOperand->Parameter() . ')');
        } else if ($mixOperand instanceof dxQuerySubQueryNode) {
            /** @var dxQuerySubQueryNode $mixOperand */
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IN ' . $mixOperand->GetColumnAlias($objBuilder));
        } else {
            $strParameters = array();
            foreach ($mixOperand as $mixParameter) {
                array_push($strParameters, $objBuilder->Database->SqlVariable($mixParameter));
            }
            if (FrameworkFunctions::getDataSetSize($strParameters))
                $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' IN (' . implode(',', $strParameters) . ')');
            else
                $objBuilder->AddWhereItem('1=0');
        }
    }
}

class dxQueryConditionNotIn extends dxQueryConditionComparison {
    /**
     * dxQueryConditionNotIn constructor.
     * @param dxQueryNode $objQueryNode
     * @param $mixValuesArray
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct(dxQueryNode $objQueryNode, $mixValuesArray) {
        $this->objQueryNode = $objQueryNode;
        if (!$objQueryNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based dxQueryNode', 3);

        if ($mixValuesArray instanceof dxQueryNamedValue)
            $this->mixOperand = $mixValuesArray;
        else if ($mixValuesArray instanceof dxQuerySubQueryNode)
            $this->mixOperand = $mixValuesArray;
        else {
            try {
                $this->mixOperand = dxType::Cast($mixValuesArray, dxType::ArrayType);
            } catch (dxCallerException $objExc) {
                $objExc->IncrementOffset();
                $objExc->IncrementOffset();
                throw $objExc;
            }
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $mixOperand = $this->mixOperand;
        if ($mixOperand instanceof dxQueryNamedValue) {
            /** @var dxQueryNamedValue $mixOperand */
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT IN (' . $mixOperand->Parameter() . ')');
        } else if ($mixOperand instanceof dxQuerySubQueryNode) {
            /** @var dxQuerySubQueryNode $mixOperand */
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT IN ' . $mixOperand->GetColumnAlias($objBuilder));
        } else {
            $strParameters = array();
            foreach ($mixOperand as $mixParameter) {
                array_push($strParameters, $objBuilder->Database->SqlVariable($mixParameter));
            }
            if (FrameworkFunctions::getDataSetSize($strParameters))
                $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT IN (' . implode(',', $strParameters) . ')');
            else
                $objBuilder->AddWhereItem('1=1');
        }
    }
}

class dxQueryConditionLike extends dxQueryConditionComparison {
    /**
     * dxQueryConditionLike constructor.
     * @param dxQueryNode $objQueryNode
     * @param $strValue
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct(dxQueryNode $objQueryNode, $strValue) {
        $this->objQueryNode = $objQueryNode;
        if (!$objQueryNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based dxQueryNode', 3);

        if ($strValue instanceof dxQueryNamedValue)
            $this->mixOperand = $strValue;
        else {
            try {
                $this->mixOperand = dxType::Cast($strValue, dxType::String);
            } catch (dxCallerException $objExc) {
                $objExc->IncrementOffset();
                $objExc->IncrementOffset();
                throw $objExc;
            }
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $mixOperand = $this->mixOperand;
        if ($mixOperand instanceof dxQueryNamedValue) {
            /** @var dxQueryNamedValue $mixOperand */
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' LIKE ' . $mixOperand->Parameter());
        } else {
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' LIKE ' . $objBuilder->Database->SqlVariable($mixOperand));
        }
    }
}

class dxQueryConditionNotLike extends dxQueryConditionComparison {
    /**
     * dxQueryConditionNotLike constructor.
     * @param dxQueryNode $objQueryNode
     * @param $strValue
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct(dxQueryNode $objQueryNode, $strValue) {
        $this->objQueryNode = $objQueryNode;
        if (!$objQueryNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based dxQueryNode', 3);

        if ($strValue instanceof dxQueryNamedValue)
            $this->mixOperand = $strValue;
        else {
            try {
                $this->mixOperand = dxType::Cast($strValue, dxType::String);
            } catch (dxCallerException $objExc) {
                $objExc->IncrementOffset();
                $objExc->IncrementOffset();
                throw $objExc;
            }
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $mixOperand = $this->mixOperand;
        if ($mixOperand instanceof dxQueryNamedValue) {
            /** @var dxQueryNamedValue $mixOperand */
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT LIKE ' . $mixOperand->Parameter());
        } else {
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT LIKE ' . $objBuilder->Database->SqlVariable($mixOperand));
        }
    }
}

class dxQueryConditionBetween extends dxQueryConditionComparison {
    /**
     * @var dxQueryNamedValue
     */
    protected $mixOperandTwo;

    /**
     * dxQueryConditionBetween constructor.
     * @param dxQueryNode $objQueryNode
     * @param $strMinValue
     * @param $strMaxValue
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct(dxQueryNode $objQueryNode, $strMinValue, $strMaxValue) {
        $this->objQueryNode = $objQueryNode;
        if (!$objQueryNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based dxQueryNode', 3);

        try {
            $this->mixOperand = dxType::Cast($strMinValue, dxType::String);
            $this->mixOperandTwo = dxType::Cast($strMaxValue, dxType::String);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            $objExc->IncrementOffset();
            throw $objExc;
        }

        if ($strMinValue instanceof dxQueryNamedValue)
            $this->mixOperand = $strMinValue;
        if ($strMaxValue instanceof dxQueryNamedValue)
            $this->mixOperandTwo = $strMaxValue;

    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $mixOperand = $this->mixOperand;
        $mixOperandTwo = $this->mixOperandTwo;
        if ($mixOperand instanceof dxQueryNamedValue) {
            /** @var dxQueryNamedValue $mixOperand */
            /** @var dxQueryNamedValue $mixOperandTwo */
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' BETWEEN ' . $mixOperand->Parameter() . ' AND ' . $mixOperandTwo->Parameter());
        } else {
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' BETWEEN ' . $objBuilder->Database->SqlVariable($mixOperand) . ' AND ' . $objBuilder->Database->SqlVariable($mixOperandTwo));
        }
    }
}

class dxQueryConditionNotBetween extends dxQueryConditionComparison {
    /**
     * @var dxQueryNamedValue
     */
    protected $mixOperandTwo;

    /**
     * dxQueryConditionNotBetween constructor.
     * @param dxQueryNode $objQueryNode
     * @param $strMinValue
     * @param $strMaxValue
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct(dxQueryNode $objQueryNode, $strMinValue, $strMaxValue) {
        $this->objQueryNode = $objQueryNode;
        if (!$objQueryNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objQueryNode->_Name . '" table to Column-based dxQueryNode', 3);

        try {
            $this->mixOperand = dxType::Cast($strMinValue, dxType::String);
            $this->mixOperandTwo = dxType::Cast($strMaxValue, dxType::String);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            $objExc->IncrementOffset();
            throw $objExc;
        }

        if ($strMinValue instanceof dxQueryNamedValue)
            $this->mixOperand = $strMinValue;
        if ($strMaxValue instanceof dxQueryNamedValue)
            $this->mixOperandTwo = $strMaxValue;

    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $mixOperand = $this->mixOperand;
        $mixOperandTwo = $this->mixOperandTwo;
        if ($mixOperand instanceof dxQueryNamedValue) {
            /** @var dxQueryNamedValue $mixOperand */
            /** @var dxQueryNamedValue $mixOperandTwo */
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT BETWEEN ' . $mixOperand->Parameter() . ' AND ' . $mixOperandTwo->Parameter());
        } else {
            $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' NOT BETWEEN ' . $objBuilder->Database->SqlVariable($mixOperand) . ' AND ' . $objBuilder->Database->SqlVariable($mixOperandTwo));
        }
    }
}

class dxQueryConditionEqual extends dxQueryConditionComparison {
    /**
     * @var string
     */
    protected $strOperator = ' = ';

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' ' . $this->objQueryNode->GetValue($this->mixOperand, $objBuilder, true));
    }
}

class dxQueryConditionNotEqual extends dxQueryConditionComparison {
    /**
     * @var string
     */
    protected $strOperator = ' != ';

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddWhereItem($this->objQueryNode->GetColumnAlias($objBuilder) . ' ' . $this->objQueryNode->GetValue($this->mixOperand, $objBuilder, false));
    }
}

class dxQueryConditionGreaterThan extends dxQueryConditionComparison {
    /**
     * @var string
     */
    protected $strOperator = ' > ';
}

class dxQueryConditionLessThan extends dxQueryConditionComparison {
    /**
     * @var string
     */
    protected $strOperator = ' < ';
}

class dxQueryConditionGreaterOrEqual extends dxQueryConditionComparison {
    /**
     * @var string
     */
    protected $strOperator = ' >= ';
}

class dxQueryConditionLessOrEqual extends dxQueryConditionComparison {
    /**
     * @var string
     */
    protected $strOperator = ' <= ';
}

class dxQuery {
    /////////////////////////
    // dxQueryCondition Factories
    /////////////////////////

    /**
     * @return dxQueryConditionAll
     * @throws dxCallerException
     */
    static public function All() {
        return new dxQueryConditionAll(func_get_args());
    }

    /**
     * @return dxQueryConditionNone
     * @throws dxCallerException
     */
    static public function None() {
        return new dxQueryConditionNone(func_get_args());
    }

    /**
     * @return dxQueryConditionOr
     * @throws dxCallerException
     */
    static public function OrCondition(/* array and/or parameterized list of QLoad objects*/) {
        return new dxQueryConditionOr(func_get_args());
    }

    /**
     * @return dxQueryConditionAnd
     * @throws dxCallerException
     */
    static public function AndCondition(/* array and/or parameterized list of QLoad objects*/) {
        return new dxQueryConditionAnd(func_get_args());
    }

    /**
     * @param dxQueryCondition $objCondition
     * @return dxQueryConditionNot
     */
    static public function Not(dxQueryCondition $objCondition) {
        return new dxQueryConditionNot($objCondition);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $mixValue
     * @return dxQueryConditionEqual
     * @throws dxInvalidCastException
     */
    static public function Equal(dxQueryNode $objQueryNode, $mixValue) {
        return new dxQueryConditionEqual($objQueryNode, $mixValue);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $mixValue
     * @return dxQueryConditionNotEqual
     * @throws dxInvalidCastException
     */
    static public function NotEqual(dxQueryNode $objQueryNode, $mixValue) {
        return new dxQueryConditionNotEqual($objQueryNode, $mixValue);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $mixValue
     * @return dxQueryConditionGreaterThan
     * @throws dxInvalidCastException
     */
    static public function GreaterThan(dxQueryNode $objQueryNode, $mixValue) {
        return new dxQueryConditionGreaterThan($objQueryNode, $mixValue);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $mixValue
     * @return dxQueryConditionGreaterOrEqual
     * @throws dxInvalidCastException
     */
    static public function GreaterOrEqual(dxQueryNode $objQueryNode, $mixValue) {
        return new dxQueryConditionGreaterOrEqual($objQueryNode, $mixValue);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $mixValue
     * @return dxQueryConditionLessThan
     * @throws dxInvalidCastException
     */
    static public function LessThan(dxQueryNode $objQueryNode, $mixValue) {
        return new dxQueryConditionLessThan($objQueryNode, $mixValue);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $mixValue
     * @return dxQueryConditionLessOrEqual
     * @throws dxInvalidCastException
     */
    static public function LessOrEqual(dxQueryNode $objQueryNode, $mixValue) {
        return new dxQueryConditionLessOrEqual($objQueryNode, $mixValue);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @return dxQueryConditionIsNull
     * @throws dxInvalidCastException
     */
    static public function IsNull(dxQueryNode $objQueryNode) {
        return new dxQueryConditionIsNull($objQueryNode);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @return dxQueryConditionIsNotNull
     * @throws dxInvalidCastException
     */
    static public function IsNotNull(dxQueryNode $objQueryNode) {
        return new dxQueryConditionIsNotNull($objQueryNode);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $mixValuesArray
     * @return dxQueryConditionIn
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function In(dxQueryNode $objQueryNode, $mixValuesArray) {
        return new dxQueryConditionIn($objQueryNode, $mixValuesArray);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $mixValuesArray
     * @return dxQueryConditionNotIn
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function NotIn(dxQueryNode $objQueryNode, $mixValuesArray) {
        return new dxQueryConditionNotIn($objQueryNode, $mixValuesArray);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $strValue
     * @return dxQueryConditionLike
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function Like(dxQueryNode $objQueryNode, $strValue) {
        return new dxQueryConditionLike($objQueryNode, $strValue);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $strValue
     * @return dxQueryConditionNotLike
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function NotLike(dxQueryNode $objQueryNode, $strValue) {
        return new dxQueryConditionNotLike($objQueryNode, $strValue);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $strMinValue
     * @param $strMaxValue
     * @return dxQueryConditionBetween
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function Between(dxQueryNode $objQueryNode, $strMinValue, $strMaxValue) {
        return new dxQueryConditionBetween($objQueryNode, $strMinValue, $strMaxValue);
    }

    /**
     * @param dxQueryNode $objQueryNode
     * @param $strMinValue
     * @param $strMaxValue
     * @return dxQueryConditionNotBetween
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function NotBetween(dxQueryNode $objQueryNode, $strMinValue, $strMaxValue) {
        return new dxQueryConditionNotBetween($objQueryNode, $strMinValue, $strMaxValue);
    }

    ////////////////////////
    // dxQueryCondition Shortcuts
    ////////////////////////
    /**
     * @param dxQueryNode $objQueryNode
     * @param $strSymbol
     * @param $mixValue
     * @param null $mixValueTwo
     * @return dxQueryConditionBetween|dxQueryConditionNotBetween
     * @throws dxCallerException
     */
    static public function _(dxQueryNode $objQueryNode, $strSymbol, $mixValue, $mixValueTwo = null) {
        try {
            switch(strtolower(trim($strSymbol))) {
                case '=': return dxQuery::Equal($objQueryNode, $mixValue);
                case '!=': return dxQuery::NotEqual($objQueryNode, $mixValue);
                case '>': return dxQuery::GreaterThan($objQueryNode, $mixValue);
                case '<': return dxQuery::LessThan($objQueryNode, $mixValue);
                case '>=': return dxQuery::GreaterOrEqual($objQueryNode, $mixValue);
                case '<=': return dxQuery::LessOrEqual($objQueryNode, $mixValue);
                case 'in': return dxQuery::In($objQueryNode, $mixValue);
                case 'not in': return dxQuery::NotIn($objQueryNode, $mixValue);
                case 'like': return dxQuery::Like($objQueryNode, $mixValue);
                case 'not like': return dxQuery::NotLike($objQueryNode, $mixValue);
                case 'is null': return dxQuery::IsNull($objQueryNode, $mixValue);
                case 'is not null': return dxQuery::IsNotNull($objQueryNode, $mixValue);
                case 'between': return dxQuery::Between($objQueryNode, $mixValue, $mixValueTwo);
                case 'not between': return dxQuery::NotBetween($objQueryNode, $mixValue, $mixValueTwo);
                default:
                    throw new dxCallerException('Unknown Query Comparison Operation: ' . $strSymbol, 0);
            }
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /////////////////////////
    // dxQuerySubQuery Factories
    /////////////////////////

    /**
     * @param $strSql
     * @param null $objParentQueryNodes
     * @return dxQuerySubQuerySqlNode
     * @throws dxCallerException
     */
    static public function SubSql($strSql, $objParentQueryNodes = null) {
        $objParentQueryNodeArray = func_get_args();
        return new dxQuerySubQuerySqlNode($strSql, $objParentQueryNodeArray);
    }

    /**
     * @param $strName
     * @param dxQuerySubQueryNode|null $objSubQueryDefinition
     * @return dxQueryVirtualNode
     * @throws dxCallerException
     */
    static public function Virtual($strName, dxQuerySubQueryNode $objSubQueryDefinition = null) {
        return new dxQueryVirtualNode($strName, $objSubQueryDefinition);
    }

    /////////////////////////
    // dxQueryClause Factories
    /////////////////////////

    /**
     * @return array
     * @throws dxCallerException
     */
    static public function Clause(/* parameterized list of dxQueryClause objects */) {
        $objClauseArray = array();

        foreach (func_get_args() as $objClause)
            if ($objClause) {
                if (!($objClause instanceof dxQueryClause))
                    throw new dxCallerException('Non-dxQueryClause object was passed in to dxQuery::Clause');
                else
                    array_push($objClauseArray, $objClause);
            }

        return $objClauseArray;
    }

    /**
     * @return dxQueryOrderBy
     */
    static public function OrderBy(/* array and/or parameterized list of dxQueryNode objects*/) {
        return new dxQueryOrderBy(func_get_args());
    }

    /**
     * @return dxQueryGroupBy
     */
    static public function GroupBy(/* array and/or parameterized list of dxQueryNode objects*/) {
        return new dxQueryGroupBy(func_get_args());
    }

    /**
     * @param dxQuerySubQuerySqlNode $objNode
     * @return dxQueryHavingClause
     */
    static public function Having(dxQuerySubQuerySqlNode $objNode) {
        return new dxQueryHavingClause($objNode);
    }

    /**
     * @param $objNode
     * @param $strAttributeName
     * @return dxQueryCount
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function Count($objNode, $strAttributeName) {
        return new dxQueryCount($objNode, $strAttributeName);
    }

    /**
     * @param $objNode
     * @param $strAttributeName
     * @return dxQuerySum
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function Sum($objNode, $strAttributeName) {
        return new dxQuerySum($objNode, $strAttributeName);
    }

    /**
     * @param $objNode
     * @param $strAttributeName
     * @return dxQueryMinimum
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function Minimum($objNode, $strAttributeName) {
        return new dxQueryMinimum($objNode, $strAttributeName);
    }

    /**
     * @param $objNode
     * @param $strAttributeName
     * @return dxQueryMaximum
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function Maximum($objNode, $strAttributeName) {
        return new dxQueryMaximum($objNode, $strAttributeName);
    }

    /**
     * @param $objNode
     * @param $strAttributeName
     * @return dxQueryAverage
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function Average($objNode, $strAttributeName) {
        return new dxQueryAverage($objNode, $strAttributeName);
    }

    /**
     * @param $objNode
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @return dxQueryExpand|dxQueryExpandVirtualNode
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    static public function Expand($objNode, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect = null) {
//			if (gettype($objNode) == 'string')
//				return new dxQueryExpandVirtualNode(new dxQueryVirtualNode($objNode));

        if ($objNode instanceof dxQueryVirtualNode)
            return new dxQueryExpandVirtualNode($objNode);
        else
            return new dxQueryExpand($objNode, $objJoinCondition, $objSelect);
    }

    /**
     * @param $objNode
     * @param dxQuerySelect|null $objSelect
     * @return dxQueryExpandAsArray
     * @throws dxCallerException
     */
    static public function ExpandAsArray($objNode, dxQuerySelect $objSelect = null) {
        return new dxQueryExpandAsArray($objNode, $objSelect);
    }

    /**
     * @return dxQuerySelect
     */
    static public function Select(/* array and/or parameterized list of dxQueryNode objects*/) {
        return new dxQuerySelect(func_get_args());
    }

    /**
     * @param $intMaxRowCount
     * @param int $intOffset
     * @return dxQueryLimitInfo
     * @throws dxCallerException
     */
    static public function LimitInfo($intMaxRowCount, $intOffset = 0) {
        return new dxQueryLimitInfo($intMaxRowCount, $intOffset);
    }

    /**
     * @return dxQueryDistinct
     */
    static public function Distinct() {
        return new dxQueryDistinct();
    }

    /**
     * @param dxQueryClause[]|dxQueryClause|null $objClauses dxQueryClause object or array of dxQueryClause objects
     * @return dxQuerySelect dxQuerySelect clause containing all the nodes from all the dxQuerySelect clauses from $objClauses,
     * or null if $objClauses contains no dxQuerySelect clauses
     */
    public static function extractSelectClause($objClauses) {
        if ($objClauses instanceof dxQuerySelect)
            return $objClauses;

        if (is_array($objClauses)) {
            $hasSelects = false;
            $objSelect = dxQuery::Select();
            foreach ($objClauses as $objClause) {
                if ($objClause instanceof dxQuerySelect) {
                    $hasSelects = true;
                    $objSelect->Merge($objClause);
                }
            }
            if (!$hasSelects)
                return null;
            return $objSelect;
        }
        return null;
    }

    /////////////////////////
    // Aliased dxQuery Node
    /////////////////////////
    /**
     * Returns the supplied node object, after seting its alias to the value supplied
     *
     * @param dxQueryBaseNode $objNode The node object to set alias on
     * @param string $strAlias The alias to set
     * @return mixed The same node that was passed in, but with the alias set
     *
     */
    static public function Alias(dxQueryBaseNode $objNode, $strAlias)
    {
        $objNode->Alias = $strAlias;
        return $objNode;
    }

    /////////////////////////
    // NamedValue dxQuery Node
    /////////////////////////
    /**
     * @param $strName
     * @return dxQueryNamedValue
     */
    static public function NamedValue($strName) {
        return new dxQueryNamedValue($strName);
    }
}

abstract class dxQuerySubQueryNode extends dxQueryNode {
    /*
            protected $strFunctionName;
            protected $objNode;
            protected $objCondition;

            public function __construct(dxQueryNode $objNode, dxQueryCondition $objCondition = null) {
                $this->objParentNode = $objNode->objParentNode;
                $this->strName = 'NAMEFOO';
                $this->strPropertyName = 'PROPERTYFOO';
                $this->strType = 'integer';
                if ($this->objParentNode) {
                    if (version_compare(PHP_VERSION, '5.1.0') == -1)
                        $this->strRootTableName = $objNode->objParentNode->__get('_RootTableName');
                    else
                        $this->strRootTableName = $objNode->objParentNode->_RootTableName;
                } else
                    $this->strRootTableName = 'ROOTFOO';

                $this->objNode = $objNode;
                $this->objCondition = $objCondition;
            }
            public function GetColumnAlias($objBuilder) {
                if ($this->objCondition) {
                    $strConditionClause = $this->objCondition->GetWhereClause($objBuilder->Database, $this->strRootTableName, true);
                    $strFromClause = $this->objCondition->GetFromClause($objBuilder->Database, $this->strRootTableName);
                    return sprintf('(SELECT %s(%s) FROM %s WHERE %s)',
                        $this->strFunctionName,
                        $this->objNode->GetColumnAlias($this->objCondition->GetPartialBuilder($objBuilder->Database, $this->strRootTableName)),
                        $strFromClause,
                        $strConditionClause);
                } else {
                    return 'HERE';
                }
            }*/
}

class dxQuerySubQueryCountNode extends dxQuerySubQueryNode {
    /**
     * @var string
     */
    protected $strFunctionName = 'COUNT';
}

class dxQuerySubQuerySqlNode extends dxQuerySubQueryNode {
    /**
     * @var
     */
    protected $strSql;
    /** @var dxQueryNode[] */
    protected $objParentQueryNodes;

    /**
     * dxQuerySubQuerySqlNode constructor.
     * @param $strSql
     * @param null $objParentQueryNodes
     */
    public function __construct($strSql, $objParentQueryNodes = null) {
        $this->objParentNode = true;
        $this->objParentQueryNodes = $objParentQueryNodes;
        $this->strSql = $strSql;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param bool $blnExpandSelection
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @return mixed|string
     * @throws dxCallerException
     */
    public function GetColumnAlias(dxQueryBuilder $objBuilder, $blnExpandSelection = false, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect = null) {
        $strSql = $this->strSql;
        for ($intIndex = 1; $intIndex < FrameworkFunctions::getDataSetSize($this->objParentQueryNodes); $intIndex++) {
            if (!is_null($this->objParentQueryNodes[$intIndex]))
                $strSql = str_replace('{' . $intIndex . '}', $this->objParentQueryNodes[$intIndex]->GetColumnAlias($objBuilder), $strSql);
        }
        return '(' . $strSql . ')';
    }
}

class dxQueryVirtualNode extends dxQueryNode {
    /**
     * @var dxQuerySubQueryNode
     */
    protected $objSubQueryDefinition;

    /**
     * dxQueryVirtualNode constructor.
     * @param $strName
     * @param dxQuerySubQueryNode|null $objSubQueryDefinition
     */
    public function __construct($strName, dxQuerySubQueryNode $objSubQueryDefinition = null) {
        $this->objParentNode = true;
        $this->strName = trim(strtolower($strName));
        $this->objSubQueryDefinition = $objSubQueryDefinition;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param bool $blnExpandSelection
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @return mixed|string
     * @throws dxCallerException
     */
    public function GetColumnAlias(dxQueryBuilder $objBuilder, $blnExpandSelection = false, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect = null) {
        if ($this->objSubQueryDefinition) {
            $objBuilder->SetVirtualNode($this->strName, $this->objSubQueryDefinition);
            return $this->objSubQueryDefinition->GetColumnAlias($objBuilder);
        } else {
            try {
                return $objBuilder->GetVirtualNode($this->strName)->GetColumnAlias($objBuilder);
            } catch (dxCallerException $objExc) {
                $objExc->IncrementOffset();
                $objExc->IncrementOffset();
                throw $objExc;
            }
        }
    }

    /**
     * @return string
     */
    public function GetAttributeName() {
        return $this->strName;
    }
}

abstract class dxQueryClause extends dxBaseClass {
    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed
     */
    abstract public function UpdateQueryBuilder(dxQueryBuilder $objBuilder);

    /**
     * @return mixed
     */
    abstract public function __toString();
}

class dxQueryOrderBy extends dxQueryClause {
    /** @var dxQueryNode[]  */
    protected $objNodeArray;

    /**
     * @param $mixParameterArray
     * @return array|dxQueryNode[]
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    protected function CollapseNodes($mixParameterArray) {
        /** @var dxQueryNode[] $objNodeArray */
        $objNodeArray = array();
        foreach ($mixParameterArray as $mixParameter) {
            if (is_array($mixParameter))
                $objNodeArray = array_merge($objNodeArray, $mixParameter);
            else
                array_push($objNodeArray, $mixParameter);
        }

        $blnPreviousIsNode = false;
        foreach ($objNodeArray as $objNode)
            if (!($objNode instanceof dxQueryNode || $objNode instanceof dxQueryCondition) ) {
                if (!$blnPreviousIsNode)
                    throw new dxCallerException('OrderBy clause parameters must all be dxQueryNode or dxQueryCondition objects followed by an optional true/false "Ascending Order" option', 3);
                $blnPreviousIsNode = false;
            } else {
                if ($objNode instanceof dxQueryReverseReferenceNode)
                    throw new dxInvalidCastException('Cannot order by a ReverseReferenceNode: ' . $objNode->_Name, 4);
                if ($objNode instanceof dxQueryNode && !$objNode->_ParentNode)
                    throw new dxInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based dxQueryNode', 4);
                $blnPreviousIsNode = true;
            }

        if (FrameworkFunctions::getDataSetSize($objNodeArray))
            return $objNodeArray;
        else
            throw new dxCallerException('No parameters passed in to OrderBy clause', 3);
    }

    /**
     * dxQueryOrderBy constructor.
     * @param $mixParameterArray
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct($mixParameterArray) {
        $this->objNodeArray = $this->CollapseNodes($mixParameterArray);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $intLength = FrameworkFunctions::getDataSetSize($this->objNodeArray);
        for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
            $objNode = $this->objNodeArray[$intIndex];
            if ($objNode instanceof dxQueryNode) {
                /** @var dxQueryNode $objNode */
                $strOrderByCommand = $objNode->GetColumnAlias($objBuilder);
            } else if ($objNode instanceof dxQueryCondition) {
                /** @var dxQueryCondition $objNode */
                $strOrderByCommand = $objNode->GetWhereClause($objBuilder);
            } else {
                $strOrderByCommand = '';
            }

            // Check to see if they want a ASC/DESC declarator
            if ((($intIndex + 1) < $intLength) &&
                !($this->objNodeArray[$intIndex + 1] instanceof dxQueryNode)) {
                if ((!$this->objNodeArray[$intIndex + 1]) ||
                    (trim(strtolower($this->objNodeArray[$intIndex + 1])) == 'desc'))
                    $strOrderByCommand .= ' DESC';
                else
                    $strOrderByCommand .= ' ASC';
                $intIndex++;
            }

            $objBuilder->AddOrderByItem($strOrderByCommand);
        }
    }

    /**
     * This is used primarly by datagrids wanting to use the "old Beta 2" style of
     * Manual Queries.  This allows a datagrid to use dxQuery::OrderBy even though
     * the manually-written Load method takes in Beta 2 string-based SortByCommand information.
     *
     * @return string
     */
    public function GetAsManualSql() {
        $strOrderByArray = array();
        $intLength = FrameworkFunctions::getDataSetSize($this->objNodeArray);
        for ($intIndex = 0; $intIndex < $intLength; $intIndex++) {
            $strOrderByCommand = $this->objNodeArray[$intIndex]->GetAsManualSqlColumn();

            // Check to see if they want a ASC/DESC declarator
            if ((($intIndex + 1) < $intLength) &&
                !($this->objNodeArray[$intIndex + 1] instanceof dxQueryNode)) {
                if ((!$this->objNodeArray[$intIndex + 1]) ||
                    (trim(strtolower($this->objNodeArray[$intIndex + 1])) == 'desc'))
                    $strOrderByCommand .= ' DESC';
                else
                    $strOrderByCommand .= ' ASC';
                $intIndex++;
            }

            array_push($strOrderByArray, $strOrderByCommand);
        }

        return implode(',', $strOrderByArray);
    }

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryOrderBy Clause';
    }
}

class dxQueryDistinct extends dxQueryClause {
    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->SetDistinctFlag();
    }

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryDistinct Clause';
    }
}

class dxQueryLimitInfo extends dxQueryClause {
    /**
     * @var mixed
     */
    protected $intMaxRowCount;
    /**
     * @var mixed
     */
    protected $intOffset;

    /**
     * dxQueryLimitInfo constructor.
     * @param $intMaxRowCount
     * @param int $intOffset
     * @throws dxCallerException
     */
    public function __construct($intMaxRowCount, $intOffset = 0) {
        try {
            $this->intMaxRowCount = dxType::Cast($intMaxRowCount, dxType::Integer);
            $this->intOffset = dxType::Cast($intOffset, dxType::Integer);
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        if ($this->intOffset)
            $objBuilder->SetLimitInfo($this->intOffset . ',' . $this->intMaxRowCount);
        else
            $objBuilder->SetLimitInfo($this->intMaxRowCount);
    }

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryLimitInfo Clause';
    }

    /**
     * @param string $strName
     * @return mixed
     * @throws dxCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case 'MaxRowCount':
                return $this->intMaxRowCount;
            case 'Offset':
                return $this->intOffset;
            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
}

class dxQueryExpandVirtualNode extends dxQueryClause {
    /**
     * @var dxQueryVirtualNode
     */
    protected $objNode;

    /**
     * dxQueryExpandVirtualNode constructor.
     * @param dxQueryVirtualNode $objNode
     */
    public function __construct(dxQueryVirtualNode $objNode) {
        $this->objNode = $objNode;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        try {
            $objBuilder->AddSelectFunction(null, $this->objNode->GetColumnAlias($objBuilder), $this->objNode->GetAttributeName());
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryExpandVirtualNode Clause';
    }
}

class dxQueryExpand extends dxQueryClause {
    /** @var dxQueryNode */
    protected $objNode;
    /**
     * @var dxQueryCondition
     */
    protected $objJoinCondition;
    /**
     * @var dxQuerySelect
     */
    protected $objSelect;

    /**
     * dxQueryExpand constructor.
     * @param $objNode
     * @param dxQueryCondition|null $objJoinCondition
     * @param dxQuerySelect|null $objSelect
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct($objNode, dxQueryCondition $objJoinCondition = null, dxQuerySelect $objSelect  = null) {
        // Check against root and table dxQueryNodes
        if ($objNode instanceof dxQueryAssociationNode)
            throw new dxCallerException('Expand clause parameter cannot be the association table\'s dxQueryNode, itself', 2);
        else if (!($objNode instanceof dxQueryNode))
            throw new dxCallerException('Expand clause parameter must be a dxQueryNode object', 2);
        else if (!$objNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based dxQueryNode', 3);

        $this->objNode = $objNode;
        $this->objJoinCondition = $objJoinCondition;
        $this->objSelect = $objSelect;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $this->objNode->GetColumnAlias($objBuilder, true, $this->objJoinCondition, $this->objSelect);
    }

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryExpand Clause';
    }
}

/*
 * Allows a custom sql injection as a having clause. Its up to you to make sure its correct, but you can use subquery placeholders
 * to expand column names. Standard SQL has limited Having capabilities, but many SQL engines have useful extensions.
 */
class dxQueryHavingClause extends dxQueryClause {
    /**
     * @var dxQuerySubQueryNode
     */
    protected $objNode;

    /**
     * dxQueryHavingClause constructor.
     * @param dxQuerySubQueryNode $objSubQueryDefinition
     */
    public function __construct(dxQuerySubQueryNode $objSubQueryDefinition) {
        $this->objNode = $objSubQueryDefinition;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddHavingItem (
            $this->objNode->GetColumnAlias($objBuilder)
        );
    }

    /**
     * @return mixed
     */
    public function GetAttributeName() {
        return $this->strName;
    }

    /**
     * @return mixed|string
     */
    public function __toString() {
        return "Having Clause";
    }

}

abstract class dxQueryAggregationClause extends dxQueryClause {
    /** @var dxQueryNode */
    protected $objNode;
    /**
     * @var
     */
    protected $strAttributeName;
    /**
     * @var
     */
    protected $strFunctionName;

    /**
     * dxQueryAggregationClause constructor.
     * @param $objNode
     * @param $strAttributeName
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct($objNode, $strAttributeName) {
        // Check against root and table dxQueryNodes
        if ($objNode instanceof dxQueryAssociationNode)
            throw new dxCallerException('Expand clause parameter cannot be the association table\'s dxQueryNode, itself', 2);
        else if (!($objNode instanceof dxQueryNode))
            throw new dxCallerException('Expand clause parameter must be a dxQueryNode object', 2);
        else if (!$objNode->_ParentNode)
            throw new dxInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based dxQueryNode', 3);

        $this->objNode = $objNode;
        $this->strAttributeName = $strAttributeName;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $objBuilder->AddSelectFunction($this->strFunctionName, $this->objNode->GetColumnAlias($objBuilder), $this->strAttributeName);
    }
}

class dxQueryCount extends dxQueryAggregationClause {
    /**
     * @var string
     */
    protected $strFunctionName = 'COUNT';

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryCount Clause';
    }
}

class dxQuerySum extends dxQueryAggregationClause {
    /**
     * @var string
     */
    protected $strFunctionName = 'SUM';

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQuerySum Clause';
    }
}

class dxQueryMinimum extends dxQueryAggregationClause {
    /**
     * @var string
     */
    protected $strFunctionName = 'MIN';

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryMinimum Clause';
    }
}

class dxQueryMaximum extends dxQueryAggregationClause {
    /**
     * @var string
     */
    protected $strFunctionName = 'MAX';

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryMaximum Clause';
    }
}

class dxQueryAverage extends dxQueryAggregationClause {
    /**
     * @var string
     */
    protected $strFunctionName = 'AVG';

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryAverage Clause';
    }
}

class dxQueryExpandAsArray extends dxQueryClause {
    /** @var dxQueryNode|dxQueryAssociationNode */
    protected $objNode;
    /**
     * @var dxQuerySelect
     */
    protected $objSelect;

    /**
     * dxQueryExpandAsArray constructor.
     * @param $objNode
     * @param dxQuerySelect|null $objSelect
     * @throws dxCallerException
     */
    public function __construct($objNode, dxQuerySelect $objSelect = null) {
        // Ensure that this is an dxQueryAssociationNode
        if ((!($objNode instanceof dxQueryAssociationNode)) && (!($objNode instanceof dxQueryReverseReferenceNode)))
            throw new dxCallerException('ExpandAsArray clause parameter must be an Association Table-based dxQueryNode', 2);

        $this->objNode = $objNode;
        $this->objSelect = $objSelect;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     * @throws dxCallerException
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        if ($this->objNode instanceof dxQueryAssociationNode)
            $this->objNode->_ChildTableNode->GetColumnAlias($objBuilder, true, null, $this->objSelect);
        else
            $this->objNode->GetColumnAlias($objBuilder, true, null, $this->objSelect);
        $objBuilder->AddExpandAsArrayNode($this->objNode);
    }

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryExpandAsArray Clause';
    }
}

class dxQueryGroupBy extends dxQueryClause {
    /** @var dxQueryBaseNode[] */
    protected $objNodeArray;

    /**
     * @param $mixParameterArray
     * @return array
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    protected function CollapseNodes($mixParameterArray) {
        $objNodeArray = array();
        foreach ($mixParameterArray as $mixParameter) {
            if (is_array($mixParameter))
                $objNodeArray = array_merge($objNodeArray, $mixParameter);
            else
                array_push($objNodeArray, $mixParameter);
        }

        $objFinalNodeArray = array();
        foreach ($objNodeArray as $objNode) {
            /** @var dxQueryBaseNode $objNode */
            if ($objNode instanceof dxQueryAssociationNode)
                throw new dxCallerException('GroupBy clause parameter cannot be an association table\'s dxQueryNode, itself', 3);
            else if (!($objNode instanceof dxQueryNode))
                throw new dxCallerException('GroupBy clause parameters must all be dxQueryNode objects', 3);
            if (!$objNode->_ParentNode)
                throw new dxInvalidCastException('Unable to cast "' . $objNode->_Name . '" table to Column-based dxQueryNode', 4);
            if ($objNode->_PrimaryKeyNode) {
                array_push($objFinalNodeArray, $objNode->_PrimaryKeyNode);
            } else
                array_push($objFinalNodeArray, $objNode);
        }

        if (FrameworkFunctions::getDataSetSize($objFinalNodeArray))
            return $objFinalNodeArray;
        else
            throw new dxCallerException('No parameters passed in to Expand clause', 3);
    }

    /**
     * dxQueryGroupBy constructor.
     * @param $mixParameterArray
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function __construct($mixParameterArray) {
        $this->objNodeArray = $this->CollapseNodes($mixParameterArray);
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
        $intLength = FrameworkFunctions::getDataSetSize($this->objNodeArray);
        for ($intIndex = 0; $intIndex < $intLength; $intIndex++)
            $objBuilder->AddGroupByItem($this->objNodeArray[$intIndex]->GetColumnAlias($objBuilder));
    }

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQueryGroupBy Clause';
    }
}

class dxQuerySelect extends dxQueryClause {
    /**
     * @var array
     */
    protected $arrNodeObj = array();

    /**
     * dxQuerySelect constructor.
     * @param $arrNodeObj
     */
    public function __construct($arrNodeObj) {
        $this->arrNodeObj = $arrNodeObj;
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @return mixed|void
     */
    public function UpdateQueryBuilder(dxQueryBuilder $objBuilder) {
    }

    /**
     * @param dxQueryBuilder $objBuilder
     * @param $strTableName
     * @param $strAliasPrefix
     */
    public function AddSelectItems(dxQueryBuilder $objBuilder, $strTableName, $strAliasPrefix) {
        foreach ($this->arrNodeObj as $objNode) {
            $objBuilder->AddSelectItem($strTableName, $objNode->_Name, $strAliasPrefix . $objNode->_Name);
        }
    }

    /**
     * @param dxQuerySelect|null $objSelect
     */
    public function Merge(dxQuerySelect $objSelect = null) {
        if ($objSelect) foreach ($objSelect->arrNodeObj as $objNode)
            array_push($this->arrNodeObj, $objNode);
    }

    /**
     * @return mixed|string
     */
    public function __toString() {
        return 'dxQuerySelectColumn Clause';
    }
}

/**
 * dxQueryBuilder class
 * @property dxDatabaseBase $Database
 * @property string $RootTableName
 * @property string[] $ColumnAliasArray
 * @property dxQueryBaseNode $ExpandAsArrayNode
 */
class dxQueryBuilder extends dxBaseClass {
    /**
     * @var array
     */
    protected $strSelectArray;
    /**
     * @var array
     */
    protected $strColumnAliasArray;
    /**
     * @var int
     */
    protected $intColumnAliasCount = 0;
    /**
     * @var array
     */
    protected $strTableAliasArray;
    /**
     * @var int
     */
    protected $intTableAliasCount = 0;
    /**
     * @var array
     */
    protected $strFromArray;
    /**
     * @var array
     */
    protected $strJoinArray;
    /**
     * @var array
     */
    protected $strJoinConditionArray;
    /**
     * @var array
     */
    protected $strWhereArray;
    /**
     * @var array
     */
    protected $strOrderByArray;
    /**
     * @var array
     */
    protected $strGroupByArray;
    /**
     * @var array
     */
    protected $strHavingArray;
    /** @var dxQueryVirtualNode[] */
    protected $objVirtualNodeArray;
    /**
     * @var
     */
    protected $strLimitInfo;
    /**
     * @var
     */
    protected $blnDistinctFlag;
    /**
     * @var
     */
    protected $objExpandAsArrayNode;

    /**
     * @var
     */
    protected $blnCountOnlyFlag;

    /**
     * @var dxDatabaseBase
     */
    protected $objDatabase;
    /**
     * @var
     */
    protected $strRootTableName;

    /**
     * @var string
     */
    protected $strEscapeIdentifierBegin;
    /**
     * @var string
     */
    protected $strEscapeIdentifierEnd;

    /**
     * @param $strTableName
     * @param $strColumnName
     * @param $strFullAlias
     */
    public function AddSelectItem($strTableName, $strColumnName, $strFullAlias) {
        $strTableAlias = $this->GetTableAlias($strTableName);

        if (!array_key_exists($strFullAlias, $this->strColumnAliasArray)) {
            $strColumnAlias = 'a' . $this->intColumnAliasCount++;
            $this->strColumnAliasArray[$strFullAlias] = $strColumnAlias;
        } else {
            $strColumnAlias = $this->strColumnAliasArray[$strFullAlias];
        }

        $this->strSelectArray[$strFullAlias] = sprintf('%s%s%s.%s%s%s AS %s%s%s',
            $this->strEscapeIdentifierBegin, $strTableAlias, $this->strEscapeIdentifierEnd,
            $this->strEscapeIdentifierBegin, $strColumnName, $this->strEscapeIdentifierEnd,
            $this->strEscapeIdentifierBegin, $strColumnAlias, $this->strEscapeIdentifierEnd);
    }

    /**
     * @param $strFunctionName
     * @param $strColumnName
     * @param $strFullAlias
     */
    public function AddSelectFunction($strFunctionName, $strColumnName, $strFullAlias) {
        $this->strSelectArray[$strFullAlias] = sprintf('%s(%s) AS %s__%s%s',
            $strFunctionName, $strColumnName,
            $this->strEscapeIdentifierBegin, $strFullAlias, $this->strEscapeIdentifierEnd);
    }

    /**
     * @param $strTableName
     */
    public function AddFromItem($strTableName) {
        $strTableAlias = $this->GetTableAlias($strTableName);

        $this->strFromArray[$strTableName] = sprintf('%s%s%s AS %s%s%s',
            $this->strEscapeIdentifierBegin, $strTableName, $this->strEscapeIdentifierEnd,
            $this->strEscapeIdentifierBegin, $strTableAlias, $this->strEscapeIdentifierEnd);
    }

    /**
     * @param $strTableName
     * @return mixed|string
     */
    public function GetTableAlias($strTableName) {
        if (!array_key_exists($strTableName, $this->strTableAliasArray)) {
            $strTableAlias = 't' . $this->intTableAliasCount++;
            $this->strTableAliasArray[$strTableName] = $strTableAlias;
            return $strTableAlias;
        } else {
            return $this->strTableAliasArray[$strTableName];
        }
    }

    /**
     * @param $strJoinTableName
     * @param $strJoinTableAlias
     * @param $strTableName
     * @param $strColumnName
     * @param $strLinkedColumnName
     * @param dxQueryCondition|null $objJoinCondition
     * @throws dxCallerException
     */
    public function AddJoinItem($strJoinTableName, $strJoinTableAlias, $strTableName, $strColumnName, $strLinkedColumnName, dxQueryCondition $objJoinCondition = null) {
        $strJoinItem = sprintf('LEFT JOIN %s%s%s AS %s%s%s ON %s%s%s.%s%s%s = %s%s%s.%s%s%s',
            $this->strEscapeIdentifierBegin, $strJoinTableName, $this->strEscapeIdentifierEnd,
            $this->strEscapeIdentifierBegin, $this->GetTableAlias($strJoinTableAlias), $this->strEscapeIdentifierEnd,

            $this->strEscapeIdentifierBegin, $this->GetTableAlias($strTableName), $this->strEscapeIdentifierEnd,
            $this->strEscapeIdentifierBegin, $strColumnName, $this->strEscapeIdentifierEnd,

            $this->strEscapeIdentifierBegin, $this->GetTableAlias($strJoinTableAlias), $this->strEscapeIdentifierEnd,
            $this->strEscapeIdentifierBegin, $strLinkedColumnName, $this->strEscapeIdentifierEnd);

        $strJoinIndex = $strJoinItem;
        try {
            $strConditionClause = null;
            if ($objJoinCondition &&
                ($strConditionClause = $objJoinCondition->GetWhereClause($this, false)))
                $strJoinItem .= ' AND ' . $strConditionClause;
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        /* If this table has already been joined, then we need to check for the following:
            1. Condition wasn't specified before and we aren't specifying one now
                Do Nothing --b/c nothing was changed or updated
            2. Condition wasn't specified before but we ARE specifying one now
                Update the indexed item in the joinArray with the new JoinItem WITH Condition
            3. Condition WAS specified before but we aren't specifying one now
                Do Nothing -- we need to keep the old condition intact
            4. Condition WAS specified before and we are specifying the SAME one now
                Do Nothing --b/c nothing was changed or updated
            5. Condition WAS specified before and we are specifying a DIFFERENT one now
                Do Nothing -- we need to keep the old condition intact
                intensive from a code processing standpoint
        */
        if (array_key_exists($strJoinIndex, $this->strJoinArray)) {
            // Case 1 and 2
            if (!array_key_exists($strJoinIndex, $this->strJoinConditionArray)) {

                // Case 1
                if (!$strConditionClause) {
                    return;

                    // Case 2
                } else {
                    $this->strJoinArray[$strJoinIndex] = $strJoinItem;
                    $this->strJoinConditionArray[$strJoinIndex] = $strConditionClause;
                    return;
                }
            }

            // Case 3
            if (!$strConditionClause)
                return;

            // Case 4
            if ($strConditionClause == $this->strJoinConditionArray[$strJoinIndex])
                return;

            // Case 5
            throw new dxCallerException('You have two different Join Conditions on the same Expanded Table: ' . $strJoinIndex . "\r\n[" . $this->strJoinConditionArray[$strJoinIndex] . ']   vs.   [' . $strConditionClause . ']');
        }

        // Create the new JoinItem in teh JoinArray
        $this->strJoinArray[$strJoinIndex] = $strJoinItem;

        // If there is a condition, record that condition against this JoinIndex
        if ($strConditionClause)
            $this->strJoinConditionArray[$strJoinIndex] = $strConditionClause;
    }

    /**
     * @param $strJoinTableName
     * @param $strJoinTableAlias
     * @param dxQueryCondition $objJoinCondition
     * @throws dxCallerException
     */
    public function AddJoinCustomItem($strJoinTableName, $strJoinTableAlias, dxQueryCondition $objJoinCondition) {
        $strJoinItem = sprintf('LEFT JOIN %s%s%s AS %s%s%s ON ',
            $this->strEscapeIdentifierBegin, $strJoinTableName, $this->strEscapeIdentifierEnd,
            $this->strEscapeIdentifierBegin, $this->GetTableAlias($strJoinTableAlias), $this->strEscapeIdentifierEnd
        );

        try {
            if (($strConditionClause = $objJoinCondition->GetWhereClause($this->objDatabase, $this->strRootTableName, true)))
                $strJoinItem .= ' AND ' . $strConditionClause;
        } catch (dxCallerException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }

        $this->strJoinArray[$strJoinItem] = $strJoinItem;
    }

    /**
     * @param $strSql
     */
    public function AddJoinCustomSqlItem($strSql) {
        $this->strJoinArray[$strSql] = $strSql;
    }

    /**
     * @param $strItem
     */
    public function AddWhereItem($strItem) {
        array_push($this->strWhereArray, $strItem);
    }

    /**
     * @param $strItem
     */
    public function AddOrderByItem($strItem) {
        array_push($this->strOrderByArray, $strItem);
    }

    /**
     * @param $strItem
     */
    public function AddGroupByItem($strItem) {
        array_push($this->strGroupByArray, $strItem);
    }

    /**
     * @param $strItem
     */
    public function AddHavingItem ($strItem) {
        array_push($this->strHavingArray, $strItem);
    }


    /**
     * @param $strLimitInfo
     */
    public function SetLimitInfo($strLimitInfo) {
        $this->strLimitInfo = $strLimitInfo;
    }

    /**
     *
     */
    public function SetDistinctFlag() {
        $this->blnDistinctFlag = true;
    }

    /**
     *
     */
    public function SetCountOnlyFlag() {
        $this->blnCountOnlyFlag = true;
    }

    /**
     * @param $strName
     * @param dxQuerySubQueryNode $objNode
     */
    public function SetVirtualNode($strName, dxQuerySubQueryNode $objNode) {
        $this->objVirtualNodeArray[trim(strtolower($strName))] = $objNode;
    }

    /**
     * @param $strName
     * @return dxQueryVirtualNode|mixed
     * @throws dxCallerException
     */
    public function GetVirtualNode($strName) {
        $strName = trim(strtolower($strName));
        if (array_key_exists($strName, $this->objVirtualNodeArray))
            return $this->objVirtualNodeArray[$strName];
        else throw new dxCallerException('Undefined Virtual Node: ' . $strName);
    }

    /**
     * @param $objNode
     */
    public function AddExpandAsArrayNode($objNode) {
        /** @var dxQueryReverseReferenceNode|dxQueryAssociationNode $objNode */
        // build child nodes and find top node of given node
        $objNode->ExpandAsArray = true;
        while ($objNode->_ParentNode) {
            $objNode = $objNode->_ParentNode;
        }

        if (!$this->objExpandAsArrayNode) {
            $this->objExpandAsArrayNode = $objNode;
        }
        else {
            // integrate the information into current nodes
            $this->objExpandAsArrayNode->_MergeExpansionNode ($objNode);
        }
    }

    /**
     * dxQueryBuilder constructor.
     * @param dxDatabaseBase $objDatabase
     * @param $strRootTableName
     */
    public function __construct(dxDatabaseBase $objDatabase, $strRootTableName) {
        $this->objDatabase = $objDatabase;
        $this->strEscapeIdentifierBegin = $objDatabase->EscapeIdentifierBegin;
        $this->strEscapeIdentifierEnd = $objDatabase->EscapeIdentifierEnd;
        $this->strRootTableName = $strRootTableName;

        $this->strSelectArray = array();
        $this->strColumnAliasArray = array();
        $this->strTableAliasArray = array();
        $this->strFromArray = array();
        $this->strJoinArray = array();
        $this->strJoinConditionArray = array();
        $this->strWhereArray = array();
        $this->strOrderByArray = array();
        $this->strGroupByArray = array();
        $this->strHavingArray = array();
        $this->objVirtualNodeArray = array();
    }

    /**
     * @return string
     */
    public function GetStatement() {
        // SELECT Clause
        if ($this->blnCountOnlyFlag) {
            if ($this->blnDistinctFlag) {
                $strSql = "SELECT\r\n    COUNT(*) AS q_row_count\r\n" .
                    "FROM    (SELECT DISTINCT ";
                $strSql .= "    " . implode(",\r\n    ", $this->strSelectArray);
            } else
                $strSql = "SELECT\r\n    COUNT(*) AS q_row_count\r\n";
        } else {
            if ($this->blnDistinctFlag)
                $strSql = "SELECT DISTINCT\r\n";
            else
                $strSql = "SELECT\r\n";
            if ($this->strLimitInfo)
                $strSql .= $this->objDatabase->SqlLimitVariablePrefix($this->strLimitInfo) . "\r\n";
            $strSql .= "    " . implode(",\r\n    ", $this->strSelectArray);
        }

        // FROM and JOIN Clauses
        $strSql .= sprintf("\r\nFROM\r\n    %s\r\n    %s",
            implode(",\r\n    ", $this->strFromArray),
            implode("\r\n    ", $this->strJoinArray));

        // WHERE Clause
        if (FrameworkFunctions::getDataSetSize($this->strWhereArray)) {
            $strWhere = implode("\r\n    ", $this->strWhereArray);
            if (trim($strWhere) != '1=1')
                $strSql .= "\r\nWHERE\r\n    " . $strWhere;
        }

        // Additional Ordering/Grouping/Having clauses
        if (FrameworkFunctions::getDataSetSize($this->strGroupByArray))
            $strSql .= "\r\nGROUP BY\r\n    " . implode(",\r\n    ", $this->strGroupByArray);
        if (FrameworkFunctions::getDataSetSize($this->strHavingArray)) {
            $strHaving = implode("\r\n    ", $this->strHavingArray);
            $strSql .= "\r\nHaving\r\n    " . $strHaving;
        }
        if (FrameworkFunctions::getDataSetSize($this->strOrderByArray))
            $strSql .= "\r\nORDER BY\r\n    " . implode(",\r\n    ", $this->strOrderByArray);

        // Limit Suffix (if applicable)
        if ($this->strLimitInfo)
            $strSql .= "\r\n" . $this->objDatabase->SqlLimitVariableSuffix($this->strLimitInfo);

        // For Distinct Count Queries
        if ($this->blnCountOnlyFlag && $this->blnDistinctFlag)
            $strSql .= "\r\n) as q_count_table";

        return $strSql;
    }


    /**
     * @param string $strName
     * @return array|dxDatabaseBase|mixed
     * @throws dxCallerException
     */
    public function __get($strName) {
        switch ($strName) {
            case 'Database':
                return $this->objDatabase;
            case 'RootTableName':
                return $this->strRootTableName;
            case 'ColumnAliasArray':
                return $this->strColumnAliasArray;
            case 'ExpandAsArrayNode':
                return $this->objExpandAsArrayNode;

            default:
                try {
                    return parent::__get($strName);
                } catch (dxCallerException $objExc) {
                    $objExc->IncrementOffset();
                    throw $objExc;
                }
        }
    }
}

/**
 * 	Subclasses dxQueryBuilder to handle the building of conditions for conditional expansions, subqueries, etc.
 * 	Since regular queries use WhereClauses for conditions, we just use the where clause portion, and
 * 	only build a condition clause appropriate for a conditional expansion.
 */
class dxQueryPartialQueryBuilder extends dxQueryBuilder {
    /**
     * @var dxQueryBuilder
     */
    protected $objParentBuilder;

    /**
     * dxQueryPartialQueryBuilder constructor.
     * @param dxQueryBuilder $objBuilder
     */
    public function __construct(dxQueryBuilder $objBuilder) {
        parent::__construct($objBuilder->objDatabase, $objBuilder->strRootTableName);
        $this->objParentBuilder = $objBuilder;
        $this->strColumnAliasArray = &$objBuilder->strColumnAliasArray;
        $this->strTableAliasArray = &$objBuilder->strTableAliasArray;
    }

    /**
     * @return string
     */
    public function GetWhereStatement() {
        return implode(' ', $this->strWhereArray);
    }

    /**
     * @return string
     */
    public function GetFromStatement() {
        return implode(' ', $this->strFromArray) . ' ' . implode(' ', $this->strJoinArray);
    }
}

// Shorthand for dxQuery
class dxQ extends dxQuery {}
//endregion

//region Date and time helper classes
// These Aid with the PHP 5.2 DateTime error handling
class dxDateTimeNullException extends dxCallerException {}

function dxDateTimeErrorHandler() {}

/**
 * dxDateTime (Standard)
 * REQUIRES: PHP >= 5.2.0
 *
 * This DateTime class manages datetimes for the entire system.  It basically
 * provides a nice wrapper around the PHP DateTime class, which is included with
 * all versions of PHP >= 5.2.0.
 *
 * For legacy PHP users (PHP < 5.2.0), please refer to dxDateTime.legacy
 */
class dxDateTime extends DateTime {
    /**
     *
     */
    const Now = 'now';
    /**
     *
     */
    const FormatIso = 'YYYY-MM-DD hhhh:mm:ss';
    /**
     *
     */
    const FormatIsoCompressed = 'YYYYMMDDhhhhmmss';
    /**
     *
     */
    const FormatDisplayDate = 'MMM DD YYYY';
    /**
     *
     */
    const FormatDisplayDateFull = 'DDD, MMMM D, YYYY';
    /**
     *
     */
    const FormatDisplayDateTime = 'MMM DD YYYY hh:mm zz';
    /**
     *
     */
    const FormatDisplayDateTimeFull = 'DDDD, MMMM D, YYYY, h:mm:ss zz';
    /**
     *
     */
    const FormatDisplayTime = 'hh:mm:ss zz';
    /**
     *
     */
    const FormatRfc822 = 'DDD, DD MMM YYYY hhhh:mm:ss ttt';

    /**
     *
     */
    const FormatSoap = 'YYYY-MM-DDThhhh:mm:ss';

    /**
     *
     */
    const UnknownType = 0;
    /**
     *
     */
    const DateOnlyType = 1;
    /**
     *
     */
    const TimeOnlyType = 2;
    /**
     *
     */
    const DateAndTimeType = 3;

    /**
     * The "Default" Display Format
     * @var string $DefaultFormat
     */

    public static $DefaultFormat = DATE_TIME_FORMAT_PHP_STR;

    /**
     * The "Default" Display Format for Times
     * @var string $DefaultTimeFormat
     */
    public static $DefaultTimeFormat = dxDateTime::FormatDisplayTime;

    /**
     * Returns a new dxDateTime object that's set to "Now"
     * Set blnTimeValue to true (default) for a DateTime, and set blnTimeValue to false for just a Date
     *
     * @param boolean $blnTimeValue whether or not to include the time value
     * @return dxDateTime the current date and/or time
     */
    public static function Now($blnTimeValue = true) {
        $dttToReturn = new dxDateTime(dxDateTime::Now);
        if (!$blnTimeValue) {
            $dttToReturn->blnTimeNull = true;
            $dttToReturn->ReinforceNullProperties();
        }
        return $dttToReturn;
    }

    /**
     * @var bool
     */
    protected $blnDateNull = true;
    /**
     * @var bool
     */
    protected $blnTimeNull = true;

    /**
     * @param null $strFormat
     * @return string
     * @throws dxCallerException
     */
    public static function NowToString($strFormat = null) {
        $dttNow = new dxDateTime(dxDateTime::Now);
        return $dttNow->qFormat($strFormat);
    }

    /**
     * @return bool
     */
    public function IsDateNull() {
        return $this->blnDateNull;
    }

    /**
     * @return bool
     */
    public function IsNull() {
        return ($this->blnDateNull && $this->blnTimeNull);
    }

    /**
     * @return bool
     */
    public function IsTimeNull() {
        return $this->blnTimeNull;
    }

    /**
     * @param $strFormat
     * @return string
     */
    public function PhpDate($strFormat) {
        // This just makes a call to format
        return parent::format($strFormat);
    }

    /**
     * @param $dttArray
     * @return array|null
     */
    public function GetSoapDateTimeArray($dttArray) {
        if (!$dttArray)
            return null;

        $strArrayToReturn = array();
        foreach ($dttArray as $dttItem)
            array_push($strArrayToReturn, $dttItem->qFormat(dxDateTime::FormatSoap));
        return $strArrayToReturn;
    }

    /**
     * @param integer $intTimestamp
     * @param DateTimeZone $objTimeZone
     * @return dxDateTime
     */
    public static function FromTimestamp($intTimestamp, DateTimeZone $objTimeZone = null) {
        return new dxDateTime(date('Y-m-d H:i:s', $intTimestamp), $objTimeZone);
    }

    /**
     * dxDateTime constructor.
     * @param null $mixValue
     * @param DateTimeZone|null $objTimeZone
     * @param int $intType
     * @throws dxCallerException
     */
    public function __construct($mixValue = null, DateTimeZone $objTimeZone = null, $intType = dxDateTime::UnknownType) {
        switch ($intType) {
            case dxDateTime::DateOnlyType:
                if ($objTimeZone) {
                    parent::__construct($mixValue, $objTimeZone);
                } else {
                    parent::__construct($mixValue);
                }
                $this->blnTimeNull = true;
                $this->blnDateNull = false;
                $this->ReinforceNullProperties();
                return;
            case dxDateTime::TimeOnlyType:
                if ($objTimeZone) {
                    parent::__construct($mixValue, $objTimeZone);
                } else {
                    parent::__construct($mixValue);
                }
                $this->blnTimeNull = false;
                $this->blnDateNull = true;
                $this->ReinforceNullProperties();
                return;
            case dxDateTime::DateAndTimeType:
                if ($objTimeZone) {
                    parent::__construct($mixValue, $objTimeZone);
                } else {
                    parent::__construct($mixValue);
                }
                $this->blnTimeNull = false;
                $this->blnDateNull = false;
                return;
            default:
                break;
        }
        // Cloning from another dxDateTime object
        if ($mixValue instanceof dxDateTime) {
            if ($objTimeZone)
                throw new dxCallerException('dxDateTime cloning cannot take in a DateTimeZone parameter');
            if ($mixValue->GetTimeZone()->GetName() == date_default_timezone_get())
                parent::__construct($mixValue->format('Y-m-d H:i:s'));
            else
                parent::__construct($mixValue->format(DateTime::ISO8601));
            $this->blnDateNull = $mixValue->IsDateNull();
            $this->blnTimeNull = $mixValue->IsTimeNull();

            // Subclassing from a PHP DateTime object
        } else if ($mixValue instanceof DateTime) {
            if ($objTimeZone)
                throw new dxCallerException('dxDateTime subclassing of a DateTime object cannot take in a DateTimeZone parameter');
            parent::__construct($mixValue->format(DateTime::ISO8601));

            // By definition, a DateTime object doesn't have anything nulled
            $this->blnDateNull = false;
            $this->blnTimeNull = false;

            // Using "Now" constant
        } else if (strtolower($mixValue) == dxDateTime::Now) {
            if ($objTimeZone)
                parent::__construct('now', $objTimeZone);
            else
                parent::__construct('now');
            $this->blnDateNull = false;
            $this->blnTimeNull = false;

            // Null or No Value
        } else if (!$mixValue) {
            // Set to "null date"
            // And Do Nothing Else -- Default Values are already set to Nulled out
            if ($objTimeZone)
                parent::__construct('2000-01-01 00:00:00', $objTimeZone);
            else
                parent::__construct('2000-01-01 00:00:00');

            // Parse the Value string
        } else {
            $strTimeISO8601 = null;
            $blnValid = false;
            //FrameworkFunctions::SetErrorHandler('dxDateTimeErrorHandler');
            try {
                if ($objTimeZone)
                    $blnValid = parent::__construct($mixValue, $objTimeZone);
                else
                    $blnValid = parent::__construct($mixValue);
            } catch (Exception $objExc) {}
            if ($blnValid !== false)
                $strTimeISO8601 = parent::format(DateTime::ISO8601);
            //FrameworkFunctions::RestoreErrorHandler();

            // Valid Value String
            if ($strTimeISO8601) {
                // To deal with "Tues" and date skipping bug in PHP 5.2
                if ($strTimeISO8601 != $mixValue)
                    parent::__construct($strTimeISO8601);

                // Set DateNull and TimeNull according to the value of $mixValue
                $objDateTime = (object)date_parse($mixValue);
                $this->blnDateNull = !$objDateTime->year && !$objDateTime->month && !$objDateTime->day;
                $this->blnTimeNull = ($objDateTime->hour === false) || ($objDateTime->minute === false) || ($objDateTime->second === false);

                // Timestamp-based Value string
            } else if (is_numeric($mixValue)) {
                if ($objTimeZone)
                    parent::__construct(date('Y-m-d H:i:s', $mixValue), $objTimeZone);
                else
                    parent::__construct(date('Y-m-d H:i:s', $mixValue));

                $this->blnTimeNull = false;
                $this->blnDateNull = false;

                // Null Date
            } else {
                // Set to "null date"
                // And Do Nothing Else -- Default Values are already set to Nulled out
                if ($objTimeZone)
                    parent::__construct('2000-01-01 00:00:00', $objTimeZone);
                else
                    parent::__construct('2000-01-01 00:00:00');
            }
        }
        $this->ReinforceNullProperties();
    }

    /**
     * Returns a new dxDateTime object set to the last day of the specified month.
     *
     * @param int month
     * @param int year
     * @return dxDateTime the last day to a month in a year
     */
    public static function LastDayOfTheMonth($intMonth, $intYear) {
        $temp = date('Y-m-t',mktime(0,0,0,$intMonth,1,$intYear));
        return new dxDateTime($temp);
    }

    /**
     * Returns a new dxDateTime object set to the first day of the specified month.
     *
     * @param int month
     * @param int year
     * @return dxDateTime the first day of the month
     */
    public static function FirstDayOfTheMonth($intMonth, $intYear) {
        $temp = date('Y-m-d',mktime(0,0,0,$intMonth,1,$intYear));
        return new dxDateTime($temp);
    }

    /* The Following Methods are in place because of a bug in PHP 5.2.0 */
    /**
     * @var
     */
    protected $strSerializedData;

    /**
     * @return array
     */
    public function __sleep() {
        $this->strSerializedData = parent::format(DateTime::ISO8601);
        return array('blnDateNull', 'blnTimeNull', 'strSerializedData');
    }

    /**
     *
     */
    public function __wakeup() {
        parent::__construct($this->strSerializedData);
    }

    /**
     * @deprecated since PHP 5.3
     * DEPRECATED - DO NOT USE. For PHP 5.3 compatability, this method should not be called with parameters.
     * In previous versions of divblox, the way to format a date was to call
     * __toString(), passing the format as a parameter. PHP 5.3 no longer supports this construct.
     * To format a date, call $myDateTimeObject->qFormat($strParameters).
     *
     * For compatibility with old apps, this method is preserved - and passing parameters to it is
     * allowed, through a horrible hack. Please DO NOT use in applications that were written past the
     * release of PHP 5.3.
     */
    public function __toString() {
        $strArgumentArray = func_get_args();

        if (FrameworkFunctions::getDataSetSize($strArgumentArray) >= 1) {
            $strFormat = $strArgumentArray[0];
        } else {
            $strFormat = null;
        }
        return $this->qFormat($strFormat);
    }

    /**
     * Outputs the date as a string given the format strFormat.  By default,
     * it will return as dxDateTime::FormatDisplayDate "MMM DD YYYY", e.g. Mar 20 1977.
     *
     * Properties of strFormat are (using Sunday, March 2, 1977 at 1:15:35 pm
     * in the following examples):
     *
     *	M - Month as an integer (e.g., 3)
     *	MM - Month as an integer with leading zero (e.g., 03)
     *	MMM - Month as three-letters (e.g., Mar)
     *	MMMM - Month as full name (e.g., March)
     *
     *	D - Day as an integer (e.g., 2)
     *	DD - Day as an integer with leading zero (e.g., 02)
     *	DDD - Day of week as three-letters (e.g., Wed)
     *	DDDD - Day of week as full name (e.g., Wednesday)
     *
     *	YY - Year as a two-digit integer (e.g., 77)
     *	YYYY - Year as a four-digit integer (e.g., 1977)
     *
     *	h - Hour as an integer in 12-hour format (e.g., 1)
     *	hh - Hour as an integer in 12-hour format with leading zero (e.g., 01)
     *	hhh - Hour as an integer in 24-hour format (e.g., 13)
     *	hhhh - Hour as an integer in 24-hour format with leading zero (e.g., 13)
     *
     *	mm - Minute as a two-digit integer
     *
     *	ss - Second as a two-digit integer
     *
     *	z - "pm" or "am"
     *	zz - "PM" or "AM"
     *	zzz - "p.m." or "a.m."
     *	zzzz - "P.M." or "A.M."
     *
     *  ttt - Timezone Abbreviation as a three-letter code (e.g. PDT, GMT)
     *  tttt - Timezone Identifier (e.g. America/Los_Angeles)
     *
     * @param string $strFormat the format of the date
     * @return string the formatted date as a string
     */
    public function qFormat($strFormat = null) {
        if (is_null($strFormat))
            $strFormat = dxDateTime::$DefaultFormat;

        /*
            (?(?=D)([D]+)|
                (?(?=M)([M]+)|
                    (?(?=Y)([Y]+)|
                        (?(?=h)([h]+)|
                            (?(?=m)([m]+)|
                                (?(?=s)([s]+)|
                                    (?(?=z)([z]+)|
                                        (?(?=t)([t]+)|
            ))))))))
        */

//			$strArray = preg_split('/([^D^M^Y^h^m^s^z^t])+/', $strFormat);
        preg_match_all('/(?(?=D)([D]+)|(?(?=M)([M]+)|(?(?=Y)([Y]+)|(?(?=h)([h]+)|(?(?=m)([m]+)|(?(?=s)([s]+)|(?(?=z)([z]+)|(?(?=t)([t]+)|))))))))/', $strFormat, $strArray);
        $strArray = $strArray[0];
        $strToReturn = '';

        $intStartPosition = 0;
        for ($intIndex = 0; $intIndex < FrameworkFunctions::getDataSetSize($strArray); $intIndex++) {
            $strToken = trim($strArray[$intIndex]);
            if ($strToken) {
                $intEndPosition = strpos($strFormat, $strArray[$intIndex], $intStartPosition);
                $strToReturn .= substr($strFormat, $intStartPosition, $intEndPosition - $intStartPosition);
                $intStartPosition = $intEndPosition + strlen($strArray[$intIndex]);

                switch ($strArray[$intIndex]) {
                    case 'M':
                        $strToReturn .= parent::format('n');
                        break;
                    case 'MM':
                        $strToReturn .= parent::format('m');
                        break;
                    case 'MMM':
                        $strToReturn .= parent::format('M');
                        break;
                    case 'MMMM':
                        $strToReturn .= parent::format('F');
                        break;

                    case 'D':
                        $strToReturn .= parent::format('j');
                        break;
                    case 'DD':
                        $strToReturn .= parent::format('d');
                        break;
                    case 'DDD':
                        $strToReturn .= parent::format('D');
                        break;
                    case 'DDDD':
                        $strToReturn .= parent::format('l');
                        break;

                    case 'YY':
                        $strToReturn .= parent::format('y');
                        break;
                    case 'YYYY':
                        $strToReturn .= parent::format('Y');
                        break;

                    case 'h':
                        $strToReturn .= parent::format('g');
                        break;
                    case 'hh':
                        $strToReturn .= parent::format('h');
                        break;
                    case 'hhh':
                        $strToReturn .= parent::format('G');
                        break;
                    case 'hhhh':
                        $strToReturn .= parent::format('H');
                        break;

                    case 'mm':
                        $strToReturn .= parent::format('i');
                        break;

                    case 'ss':
                        $strToReturn .= parent::format('s');
                        break;

                    case 'z':
                        $strToReturn .= parent::format('a');
                        break;
                    case 'zz':
                        $strToReturn .= parent::format('A');
                        break;
                    case 'zzz':
                        $strToReturn .= sprintf('%s.m.', substr(parent::format('a'), 0, 1));
                        break;
                    case 'zzzz':
                        $strToReturn .= sprintf('%s.M.', substr(parent::format('A'), 0, 1));
                        break;

                    case 'ttt':
                        $strToReturn .= parent::format('T');
                        break;
                    case 'tttt':
                        $strToReturn .= parent::format('e');
                        break;

                    default:
                        $strToReturn .= $strArray[$intIndex];
                }
            }
        }

        if ($intStartPosition < strlen($strFormat))
            $strToReturn .= substr($strFormat, $intStartPosition);

        return $strToReturn;
    }

    /**
     * @param int $intHour
     * @param null $intMinute
     * @param null $intSecond
     * @param null $intMicroSecond
     * @return $this|DateTime|false
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function setTime($intHour, $intMinute = null, $intSecond = null, $intMicroSecond = null) {
        // If HOUR or MINUTE is NULL...
        if (is_null($intHour) || is_null($intMinute)) {
            //TODO: JGL: When we get php 7.1, need to add MicroSeconds. Currently Hetzner servers only support 7.0
            parent::setTime($intHour, $intMinute, $intSecond/*, $intMicroSecond*/);
            $this->blnTimeNull = true;
            $this->ReinforceNullProperties();
            return $this;
        }

        $intHour = dxType::Cast($intHour, dxType::Integer);
        $intMinute = dxType::Cast($intMinute, dxType::Integer);
        $intSecond = dxType::Cast($intSecond, dxType::Integer);
        $intMicroSecond = dxType::Cast($intMicroSecond, dxType::Integer);
        $this->blnTimeNull = false;
        //TODO: JGL: When we get php 7.1, need to add MicroSeconds. Currently Hetzner servers only support 7.0
        parent::setTime($intHour, $intMinute, $intSecond/*, $intMicroSecond*/);
        return $this;
    }

    /**
     * @param int $intYear
     * @param int $intMonth
     * @param int $intDay
     * @return $this|DateTime
     * @throws dxCallerException
     * @throws dxInvalidCastException
     */
    public function setDate($intYear, $intMonth, $intDay) {
        $intYear = dxType::Cast($intYear, dxType::Integer);
        $intMonth = dxType::Cast($intMonth, dxType::Integer);
        $intDay = dxType::Cast($intDay, dxType::Integer);
        $this->blnDateNull = false;
        parent::setDate($intYear, $intMonth, $intDay);
        return $this;
    }

    /**
     *
     */
    protected function ReinforceNullProperties() {
        if ($this->blnDateNull)
            parent::setDate(2000, 1, 1);
        if ($this->blnTimeNull)
            parent::setTime(0, 0, 0);
    }

    /**
     * Converts the current dxDateTime object to a different TimeZone.
     *
     * TimeZone should be passed in as a string-based identifier.
     *
     * Note that this is different than the built-in DateTime::SetTimezone() method which expicitly
     * takes in a DateTimeZone object.  dxDateTime::ConvertToTimezone allows you to specify any
     * string-based Timezone identifier.  If none is specified and/or if the specified timezone
     * is not a valid identifier, it will simply remain unchanged as opposed to throwing an exeception
     * or error.
     *
     * @param string $strTimezoneIdentifier a string-based parameter specifying a timezone identifier (e.g. America/Los_Angeles)
     * @return void
     */
    public function ConvertToTimezone($strTimezoneIdentifier) {
        try {
            $dtzNewTimezone = new DateTimeZone($strTimezoneIdentifier);
            $this->SetTimezone($dtzNewTimezone);
        } catch (Exception $objExc) {}
    }

    /**
     * @param dxDateTime $dttCompare
     * @return bool
     * @throws dxCallerException
     */
    public function IsEqualTo(dxDateTime $dttCompare) {
        // All comparison operations MUST have operands with matching Date Nullstates
        if ($this->blnDateNull != $dttCompare->blnDateNull)
            return false;

        // If mismatched Time nullstates, then only compare the Date portions
        if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
            // Let's "Null Out" the Time
            $dttThis = new dxDateTime($this);
            $dttThat = new dxDateTime($dttCompare);
            $dttThis->Hour = null;
            $dttThat->Hour = null;

            // Return the Result
            return ($dttThis->Timestamp == $dttThat->Timestamp);
        } else {
            // Return the Result for the both Date and Time components
            return ($this->Timestamp == $dttCompare->Timestamp);
        }
    }

    /**
     * @param dxDateTime $dttCompare
     * @return bool
     * @throws dxCallerException
     */
    public function IsEarlierThan(dxDateTime $dttCompare) {
        // All comparison operations MUST have operands with matching Date Nullstates
        if ($this->blnDateNull != $dttCompare->blnDateNull)
            return false;

        // If mismatched Time nullstates, then only compare the Date portions
        if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
            // Let's "Null Out" the Time
            $dttThis = new dxDateTime($this);
            $dttThat = new dxDateTime($dttCompare);
            $dttThis->Hour = null;
            $dttThat->Hour = null;

            // Return the Result
            return ($dttThis->Timestamp < $dttThat->Timestamp);
        } else {
            // Return the Result for the both Date and Time components
            return ($this->Timestamp < $dttCompare->Timestamp);
        }
    }

    /**
     * @param dxDateTime $dttCompare
     * @return bool
     * @throws dxCallerException
     */
    public function IsEarlierOrEqualTo(dxDateTime $dttCompare) {
        // All comparison operations MUST have operands with matching Date Nullstates
        if ($this->blnDateNull != $dttCompare->blnDateNull)
            return false;

        // If mismatched Time nullstates, then only compare the Date portions
        if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
            // Let's "Null Out" the Time
            $dttThis = new dxDateTime($this);
            $dttThat = new dxDateTime($dttCompare);
            $dttThis->Hour = null;
            $dttThat->Hour = null;

            // Return the Result
            return ($dttThis->Timestamp <= $dttThat->Timestamp);
        } else {
            // Return the Result for the both Date and Time components
            return ($this->Timestamp <= $dttCompare->Timestamp);
        }
    }

    /**
     * @param dxDateTime $dttCompare
     * @return bool
     * @throws dxCallerException
     */
    public function IsLaterThan(dxDateTime $dttCompare) {
        // All comparison operations MUST have operands with matching Date Nullstates
        if ($this->blnDateNull != $dttCompare->blnDateNull)
            return false;

        // If mismatched Time nullstates, then only compare the Date portions
        if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
            // Let's "Null Out" the Time
            $dttThis = new dxDateTime($this);
            $dttThat = new dxDateTime($dttCompare);
            $dttThis->Hour = null;
            $dttThat->Hour = null;

            // Return the Result
            return ($dttThis->Timestamp > $dttThat->Timestamp);
        } else {
            // Return the Result for the both Date and Time components
            return ($this->Timestamp > $dttCompare->Timestamp);
        }
    }

    /**
     * @param dxDateTime $dttCompare
     * @return bool
     * @throws dxCallerException
     */
    public function IsLaterOrEqualTo(dxDateTime $dttCompare) {
        // All comparison operations MUST have operands with matching Date Nullstates
        if ($this->blnDateNull != $dttCompare->blnDateNull)
            return false;

        // If mismatched Time nullstates, then only compare the Date portions
        if ($this->blnTimeNull != $dttCompare->blnTimeNull) {
            // Let's "Null Out" the Time
            $dttThis = new dxDateTime($this);
            $dttThat = new dxDateTime($dttCompare);
            $dttThis->Hour = null;
            $dttThat->Hour = null;

            // Return the Result
            return ($dttThis->Timestamp >= $dttThat->Timestamp);
        } else {
            // Return the Result for the both Date and Time components
            return ($this->Timestamp >= $dttCompare->Timestamp);
        }
    }

    /**
     * @param dxDateTime $dttDateTime
     * @return QDateTimeSpan
     */
    public function Difference(dxDateTime $dttDateTime) {
        $intDifference = $this->Timestamp - $dttDateTime->Timestamp;
        return new QDateTimeSpan($intDifference);
    }

    /**
     * @param DateInterval $dtsSpan
     * @return $this|DateTime
     * @throws dxCallerException
     */
    public function Add($dtsSpan){
        if (!$dtsSpan instanceof QDateTimeSpan) {
            throw new dxCallerException("Can only add QDateTimeSpan objects");
        }
        // Get this DateTime timestamp
        $intTimestamp = $this->Timestamp;

        // And add the Span Second count to it
        $this->Timestamp = $this->Timestamp + $dtsSpan->Seconds;
        return $this;
    }

    /**
     * @param $intSeconds
     * @return $this
     */
    public function AddSeconds($intSeconds){
        $this->Second += $intSeconds;
        return $this;
    }

    /**
     * @param $intMinutes
     * @return $this
     */
    public function AddMinutes($intMinutes){
        $this->Minute += $intMinutes;
        return $this;
    }

    /**
     * @param $intHours
     * @return $this
     */
    public function AddHours($intHours){
        $this->Hour += $intHours;
        return $this;
    }

    /**
     * @param $intDays
     * @return $this
     */
    public function AddDays($intDays){
        $this->Day += $intDays;
        return $this;
    }

    /**
     * @param $intMonths
     * @return $this
     */
    public function AddMonths($intMonths){
        $this->Month += $intMonths;
        return $this;
    }

    /**
     * @param $intYears
     * @return $this
     */
    public function AddYears($intYears){
        $this->Year += $intYears;
        return $this;
    }

    /**
     * @param string $mixValue
     * @return $this|DateTime
     */
    public function Modify($mixValue) {
        parent::modify($mixValue);
        return $this;
    }

    /**
     * @param $strName
     * @return dxDateTime|string
     * @throws dxUndefinedPropertyException
     */
    public function __get($strName) {
        switch ($strName) {
            case 'Month':
                if ($this->blnDateNull)
                    return null;
                else
                    return (int) parent::format('m');

            case 'Day':
                if ($this->blnDateNull)
                    return null;
                else
                    return (int) parent::format('d');

            case 'Year':
                if ($this->blnDateNull)
                    return null;
                else
                    return (int) parent::format('Y');

            case 'Hour':
                if ($this->blnTimeNull)
                    return null;
                else
                    return (int) parent::format('H');

            case 'Minute':
                if ($this->blnTimeNull)
                    return null;
                else
                    return (int) parent::format('i');

            case 'Second':
                if ($this->blnTimeNull)
                    return null;
                else
                    return (int) parent::format('s');

            case 'Timestamp':
                // Until PHP fixes a bug where lowest int is int(-2147483648) but lowest float/double is (-2147529600)
                // We return as a "double"
                return (double) parent::format('U');

            case 'Age':
                // Figure out the Difference from "Now"
                $dtsFromCurrent = $this->Difference(dxDateTime::Now());

                // It's in the future ('about 2 hours from now')
                if ($dtsFromCurrent->IsPositive())
                    return $dtsFromCurrent->SimpleDisplay() . ' from now';

                // It's in the past ('about 5 hours ago')
                else if ($dtsFromCurrent->IsNegative()) {
                    $dtsFromCurrent->Seconds = abs($dtsFromCurrent->Seconds);
                    return $dtsFromCurrent->SimpleDisplay() . ' ago';

                    // It's current
                } else
                    return 'right now';

            case 'LastDayOfTheMonth':
                return self::LastDayOfTheMonth($this->Month, $this->Year);
            case 'FirstDayOfTheMonth':
                return self::FirstDayOfTheMonth($this->Month, $this->Year);
            default:
                throw new dxUndefinedPropertyException('GET', 'dxDateTime', $strName);
        }
    }

    /**
     * @param $strName
     * @param $mixValue
     * @return mixed
     * @throws dxCallerException
     * @throws dxDateTimeNullException
     * @throws dxInvalidCastException
     * @throws dxUndefinedPropertyException
     */
    public function __set($strName, $mixValue) {
        try {
            switch ($strName) {
                case 'Month':
                    if ($this->blnDateNull && (!is_null($mixValue)))
                        throw new dxDateTimeNullException('Cannot set the Month property on a null date.  Use SetDate().');
                    if (is_null($mixValue)) {
                        $this->blnDateNull = true;
                        $this->ReinforceNullProperties();
                        return null;
                    }
                    $mixValue = dxType::Cast($mixValue, dxType::Integer);
                    parent::setDate(parent::format('Y'), $mixValue, parent::format('d'));
                    return $mixValue;

                case 'Day':
                    if ($this->blnDateNull && (!is_null($mixValue)))
                        throw new dxDateTimeNullException('Cannot set the Day property on a null date.  Use SetDate().');
                    if (is_null($mixValue)) {
                        $this->blnDateNull = true;
                        $this->ReinforceNullProperties();
                        return null;
                    }
                    $mixValue = dxType::Cast($mixValue, dxType::Integer);
                    parent::setDate(parent::format('Y'), parent::format('m'), $mixValue);
                    return $mixValue;

                case 'Year':
                    if ($this->blnDateNull && (!is_null($mixValue)))
                        throw new dxDateTimeNullException('Cannot set the Year property on a null date.  Use SetDate().');
                    if (is_null($mixValue)) {
                        $this->blnDateNull = true;
                        $this->ReinforceNullProperties();
                        return null;
                    }
                    $mixValue = dxType::Cast($mixValue, dxType::Integer);
                    parent::setDate($mixValue, parent::format('m'), parent::format('d'));
                    return $mixValue;

                case 'Hour':
                    if ($this->blnTimeNull && (!is_null($mixValue)))
                        throw new dxDateTimeNullException('Cannot set the Hour property on a null time.  Use SetTime().');
                    if (is_null($mixValue)) {
                        $this->blnTimeNull = true;
                        $this->ReinforceNullProperties();
                        return null;
                    }
                    $mixValue = dxType::Cast($mixValue, dxType::Integer);
                    parent::setTime($mixValue, parent::format('i'), parent::format('s'));
                    return $mixValue;

                case 'Minute':
                    if ($this->blnTimeNull && (!is_null($mixValue)))
                        throw new dxDateTimeNullException('Cannot set the Minute property on a null time.  Use SetTime().');
                    if (is_null($mixValue)) {
                        $this->blnTimeNull = true;
                        $this->ReinforceNullProperties();
                        return null;
                    }
                    $mixValue = dxType::Cast($mixValue, dxType::Integer);
                    parent::setTime(parent::format('H'), $mixValue, parent::format('s'));
                    return $mixValue;

                case 'Second':
                    if ($this->blnTimeNull && (!is_null($mixValue)))
                        throw new dxDateTimeNullException('Cannot set the Second property on a null time.  Use SetTime().');
                    if (is_null($mixValue)) {
                        $this->blnTimeNull = true;
                        $this->ReinforceNullProperties();
                        return null;
                    }
                    $mixValue = dxType::Cast($mixValue, dxType::Integer);
                    parent::setTime(parent::format('H'), parent::format('i'), $mixValue);
                    return $mixValue;

                case 'Timestamp':
                    $mixValue = dxType::Cast($mixValue, dxType::Integer);
                    $this->blnDateNull = false;
                    $this->blnTimeNull = false;

                    $this->SetDate(date('Y', $mixValue), date('m', $mixValue), date('d', $mixValue));
                    $this->SetTime(date('H', $mixValue), date('i', $mixValue), date('s', $mixValue));
                    return $mixValue;

                default:
                    throw new dxUndefinedPropertyException('SET', 'dxDateTime', $strName);
            }
        } catch (dxInvalidCastException $objExc) {
            $objExc->IncrementOffset();
            throw $objExc;
        }
    }
}
//endregion

//region Component controller related
/**
 * Class ProjectComponentController
 * Responsible for managing the base-level behaviour of all server-side component scripts
 */
class ComponentController_base {
    /**
     * @var string
     */
    protected $ComponentNameStr;
    /**
     * @var array
     */
    protected $ResultArray;
    /**
     * @var array
     */
    protected $InputParameterArray = [];
    /**
     * @var bool
     */
    protected $RequireCleanInputBool = true;
    /**
     * @var
     */
    protected $CurrentClientAuthenticationToken;
    /**
     * @var string
     */
    protected $CurrentUserAgentStr = '';

    /**
     * ComponentController_base constructor.
     * @param string $ComponentNameStr
     * @param bool $RequireCleanInputBool
     */
    public function __construct($ComponentNameStr = 'Component', $RequireCleanInputBool = true) {
        $this->ComponentNameStr = $ComponentNameStr;
        $this->ResultArray = array();
        $this->setReturnValue("Result","Failed");
        $this->setReturnValue("Message","Unknown error");
        $HttpUserAgent = 'None';
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $HttpUserAgent = $_SERVER["HTTP_USER_AGENT"];
        }
        $HttpOrigin = 'None';
        if (isset($_SERVER["HTTP_ORIGIN"])) {
            $HttpOrigin = $_SERVER["HTTP_ORIGIN"];
        }
        $this->CurrentUserAgentStr = $HttpUserAgent.';'.$HttpOrigin;
        $this->processInput();
        $this->processAuthenticationToken();
        $this->checkComponentAccess();
        $this->RequireCleanInputBool = $RequireCleanInputBool;
        $this->executeComponentFunction();
    }

    /**
     * @param null $Key
     * @param string $Value
     * @return bool
     */
    protected function setReturnValue($Key = null, $Value = "") {
        if (is_null($Key)) {
            return false;
        }
        $this->ResultArray[$Key] = $Value;
        return true;
    }

    /**
     *
     */
    protected function processAuthenticationToken() {
        $this->CurrentClientAuthenticationToken = $this->getInputValue("AuthenticationToken");
        if (is_null($this->CurrentClientAuthenticationToken)) {
            $this->initializeNewAuthenticationToken();
            return;
        }
        $ClientAuthenticationTokenObj = FrameworkFunctions::getCurrentAuthTokenObject($this->CurrentClientAuthenticationToken);
        if (is_null($ClientAuthenticationTokenObj)) {
            $this->initializeNewAuthenticationToken();
        } else {
            $this->checkValidAuthenticationToken($ClientAuthenticationTokenObj);
        }
    }

    /**
     * @return bool
     */
    protected function checkIsNative() {
        return !is_null($this->getInputValue("is_native"));
    }

    /**
     * @param string $Token
     */
    protected function registerAuthenticationTokenInSession($Token = "") {
        $this->CurrentClientAuthenticationToken = $Token;
        $this->setReturnValue("AuthenticationToken",$Token);
        $_SESSION["AuthenticationToken"] = $Token;
    }

    /**
     * @return null
     */
    protected function checkAuthenticationTokenInSession() {
        if (isset($_SESSION["AuthenticationToken"])) {
            return $_SESSION["AuthenticationToken"];
        }
        return null;
    }

    /**
     * @param ClientAuthenticationToken|null $ClientAuthenticationTokenObj
     * @throws dxUndefinedPrimaryKeyException
     */
    protected function checkValidAuthenticationToken(ClientAuthenticationToken $ClientAuthenticationTokenObj = null) {
        if (is_null($ClientAuthenticationTokenObj)) {
            $this->initializeNewAuthenticationToken();
            return;
        }
        $ClientConnectionObj = $ClientAuthenticationTokenObj->ClientConnectionObject;
        if (is_null($ClientConnectionObj)) {
            $ClientAuthenticationTokenObj->Delete();
            $this->initializeNewAuthenticationToken();
            return;
        }
        if (!$ClientAuthenticationTokenObj->IsNative) {
            if ($ClientConnectionObj->ClientUserAgent != $this->CurrentUserAgentStr) {
                // JGL: This token could have been stolen since it is being used on another device. Let's create a new token
                $ClientAuthenticationTokenObj->Delete();
                $this->initializeNewAuthenticationToken();
                return;
            }
            if ($ClientAuthenticationTokenObj->UpdateDateTime < dxDateTime::Now()->AddMinutes(-AUTHENTICATION_REGENERATION_INT)) {
                // JGL: The authentication should be regenerated
                $this->regenerateAuthenticationToken($ClientAuthenticationTokenObj);
                return;
            }
        }
        $this->updateAuthenticationToken($ClientAuthenticationTokenObj);
    }

    /**
     * @param ClientAuthenticationToken|null $ClientAuthenticationTokenObj
     */
    protected function updateAuthenticationToken(ClientAuthenticationToken $ClientAuthenticationTokenObj = null) {
        if (is_null($ClientAuthenticationTokenObj)) {
            $this->initializeNewAuthenticationToken();
            return;
        }
        $ClientAuthenticationTokenObj->UpdateDateTime = dxDateTime::Now();
        $ClientConnectionObj = $ClientAuthenticationTokenObj->ClientConnectionObject;
        $ClientConnectionObj->UpdateDateTime = dxDateTime::Now();
        try {
            $ClientAuthenticationTokenObj->Save();
            $ClientConnectionObj->Save();
            $this->registerAuthenticationTokenInSession($ClientAuthenticationTokenObj->Token);
        } catch (dxCallerException $e) {

        }
    }

    /**
     * @param ClientAuthenticationToken|null $ClientAuthenticationTokenObj
     */
    protected function regenerateAuthenticationToken(ClientAuthenticationToken $ClientAuthenticationTokenObj = null) {
        if (is_null($ClientAuthenticationTokenObj)) {
            $this->initializeNewAuthenticationToken();
            return;
        }
        if ($ClientAuthenticationTokenObj->IsNative) {
            $this->registerAuthenticationTokenInSession($ClientAuthenticationTokenObj->Token);
            return;
        }
        $ClientAuthenticationTokenObj->ExpiredToken = $ClientAuthenticationTokenObj->Token;
        $ClientAuthenticationTokenObj->Token = FrameworkFunctions::generateUniqueClientAuthenticationToken();
        $ClientAuthenticationTokenObj->UpdateDateTime = dxDateTime::Now();
        $ClientConnectionObj = $ClientAuthenticationTokenObj->ClientConnectionObject;
        $ClientConnectionObj->UpdateDateTime = dxDateTime::Now();
        try {
            $ClientAuthenticationTokenObj->Save();
            $ClientConnectionObj->Save();
            $this->registerAuthenticationTokenInSession($ClientAuthenticationTokenObj->Token);
        } catch (dxCallerException $e) {

        }
    }

    /**
     * @return bool
     */
    protected function initializeNewAuthenticationToken() {
        // JGL: We need to create a new auth token that will initially not be linked to an account
        $ClientAuthenticationTokenObj = new ClientAuthenticationToken();
        $ClientAuthenticationTokenObj->Token = FrameworkFunctions::generateUniqueClientAuthenticationToken();
        $ClientAuthenticationTokenObj->UpdateDateTime = dxDateTime::Now();
        $ClientConnectionObj = new ClientConnection();
        $RemoteAddress = 'Unknown';
        if (isset($_SERVER["REMOTE_ADDR"])) {
            $RemoteAddress = $_SERVER["REMOTE_ADDR"];
        }
        $ClientConnectionObj->ClientIpAddress = $RemoteAddress;
        $HttpUserAgent = 'None';
        if (isset($_SERVER["HTTP_USER_AGENT"])) {
            $HttpUserAgent = $_SERVER["HTTP_USER_AGENT"];
        }
        $HttpOrigin = 'None';
        if (isset($_SERVER["HTTP_ORIGIN"])) {
            $HttpOrigin = $_SERVER["HTTP_ORIGIN"];
        }
        $ClientConnectionObj->ClientUserAgent = $HttpUserAgent.';'.$HttpOrigin;
        $ClientConnectionObj->UpdateDateTime = dxDateTime::Now();
        try {
            $ClientConnectionObj->Save();
            $ClientAuthenticationTokenObj->ClientConnectionObject = $ClientConnectionObj;
            $ClientAuthenticationTokenObj->Save();
            $this->registerAuthenticationTokenInSession($ClientAuthenticationTokenObj->Token);
            return true;
        } catch (dxCallerException $e) {
            return false;
        }
    }

    /**
     *
     */
    public function checkComponentAccess() {
        $this->setReturnValue("Result","Success");
        $this->setReturnValue("Message","Component ready");
        if (!ProjectAccessManager::getComponentAccess(FrameworkFunctions::getCurrentAccountId(),$this->ComponentNameStr)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("ForceLogout",true);
            $this->setReturnValue("Message","Access is denied for component '".$this->ComponentNameStr."'");
            $this->presentOutput();
        }
    }

    /**
     *
     */
    public function processInput() {
        if (!$this->RequireCleanInputBool) {
            $this->InputParameterArray = $_POST;
            return;
        }
        $PurifierConfig = HTMLPurifier_Config::createDefault();
        $HTMLPurifierObj = new HTMLPurifier($PurifierConfig);
        foreach($_POST as $key => $value) {
            $this->InputParameterArray[$key] = $HTMLPurifierObj->purify($value);
        }
    }

    /**
     * @param null $Value
     * @param bool $ForceNumeric
     * @return int|mixed|null
     */
    public function getInputValue($Value = null, $ForceNumeric = false) {
        if (is_null($Value)) {
            if ($ForceNumeric) {
                return -1;
            }
            return null;
        }
        if (!isset($this->InputParameterArray[$Value])) {
            if ($ForceNumeric) {
                return -1;
            }
            return null;
        }
        return $this->InputParameterArray[$Value];
    }

    /**
     *
     */
    public function executeComponentFunction() {
        $FunctionName = $this->getInputValue("f");
        if (is_null($FunctionName)) {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Invalid function");
            $this->presentOutput();
        }
        if (method_exists($this,$FunctionName)) {
            call_user_func(array($this, $FunctionName));
        } else {
            $this->setReturnValue("Result","Failed");
            $this->setReturnValue("Message","Invalid function");
            $this->presentOutput();
        }
    }

    /**
     *
     */
    public function presentOutput() {
        die(json_encode($this->ResultArray));
    }
}
//endregion

//region Security and Authentication related
abstract class AccessManager_Base {

    /**
     * @param int $AccountId: The Id of the account to check (Typically the current account)
     * @param null $ObjectType: The type of object to query (Must be defined in the data model)
     * @param int $ObjectId: The Id of the object to check
     * @return array: Will be an array of allowable operations as per ObjectAccessOperation
     * The idea is to override this function in framework_classes.php and project_classes.php
     */
    public static function getObjectAccess($AccountId = -1, $ObjectType = null, $ObjectId = -1) {
        // JGL: If $AccountId == -1, the user is anonymous
        // JGL: Logic is that we retrieve the object and check the ObjectOwner and then decide what to do
        // JGL: By default, let's only allow full CRUD for object owners and Create & Read only for others
        if (is_null($ObjectType)) {
            return [];
        }
        if (!class_exists($ObjectType)) {
            return [];
        }
        if (isset($_SESSION["divblox_admin_access"])) {
            return [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR];
            // JGL: By default, when logged in as divblox admin, you can access everything
        }
        $AllAccessObjectArray = [
            "PushRegistration"
        ];
        if (in_array($ObjectType, $AllAccessObjectArray)) {
            return [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR];
        }
        
        if (FrameworkFunctions::getCurrentUserRole() == "Administrator") {
            return [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR];
        }
        
        $CurrentObject = $ObjectType::Load($ObjectId);
        if (is_null($CurrentObject)) {
            return [AccessOperation::CREATE_STR,AccessOperation::READ_STR];
        }
        if ($AccountId == $CurrentObject->ObjectOwner) {
            return [AccessOperation::CREATE_STR,AccessOperation::READ_STR,AccessOperation::UPDATE_STR,AccessOperation::DELETE_STR];
        }

        return [AccessOperation::CREATE_STR,AccessOperation::READ_STR];
    }

    /**
     * @param int $AccountId: The Id of the account to check (Typically the current account)
     * @param string $ComponentName: The name of the component for which access is checked. This must align with the filename of
     * the component's html file
     * @return bool: TRUE if access is allowed, FALSE otherwise
     * The idea is to override this function in framework_classes.php and project_classes.php
     */
    public static function getComponentAccess($AccountId = -1, $ComponentName = '') {
        // JGL: If $AccountId == -1, the user is anonymous
        // JGL: Let's get the user role associated with the account provided and then decide if component access is allowed
        // JGL: This function can be overridden to check a lookup table for specific accounts when more granular
        // permissions are required
        if (isset($_SESSION["divblox_admin_access"])) {
            return true; // JGL: By default, when logged in as divblox admin, you can access everything
        }
        $AccountObj = Account::Load($AccountId);
        if (is_null($AccountObj)) {
            // JGL: By default, let's not allow ANY anonymous access
            return false;
        }
        $UserRoleObj = $AccountObj->UserRoleObject;
        if (is_null($UserRoleObj)) {
            // JGL: By default, let's not allow ANY anonymous access
            return false;
        }
        // JGL: By default, let's say that an Administrator can access all components
        if ($UserRoleObj->Role == "Administrator") {
            return true;
        }
        // JGL: By default, let's say that any other user role cannot access any component
        // JGL: This must be overridden in class ProjectAccessManager
        return false;
    }
}
abstract class AccessOperation {
    /**
     *
     */
    const CREATE_STR = 'CREATE';
    /**
     *
     */
    const READ_STR = 'READ';
    /**
     *
     */
    const UPDATE_STR = 'UPDATE';
    /**
     *
     */
    const DELETE_STR = 'DELETE';
}
//endregion

//region Native support related
abstract class NativeDevicePlatform {
    /**
     *
     */
    const IOS_STR = 'IOS';
    /**
     *
     */
    const ANDROID_STR = 'ANDROID';
}
abstract class NativeDevicePushRegistrationStatus {
    /**
     *
     */
    const ACTIVE_STR = 'ACTIVE';
    /**
     *
     */
    const INACTIVE_STR = 'INACTIVE';
}
abstract class NativePushPriority {
    /**
     *
     */
    const NORMAL_STR = 'normal';
    /**
     *
     */
    const HIGH_STR = 'high';
}
//endregion
//region Email Related

/**
 * Class EmailManager_Base The base email sending class. This class wraps PHPMailer and creates an entry of EmailMessage
 * in the database upon attempting to send
 * Typical usage:
 * 1. Set the following before sending:
 *  EmailSettings::$SMTPServer = 'smtp.yourserver.com';
    EmailSettings::$SMTPUsername = 'user@yourserver.com';
    EmailSettings::$SMTPPassword = 'YourPassword';
    EmailSettings::$SMTPForceSecurityProtocol = true; // Needed for TLS
 * 2. Call the prepareEmail function to set the subject and message content
    EmailManager::prepareEmail("Test subject","A test message");
 * 3. Add recipients as needed:
    EmailManager::addRecipientAddress("john@doe.com",'John Doe');
 * 4. Send the email:
    if (EmailManager::sendEmail($ErrorInfo)) {
        echo "Email sent!<br>".json_encode($ErrorInfo);
    } else {
        echo "Email NOT sent: <br>".json_encode($ErrorInfo);
    }
 */
abstract class EmailManager_Base {
    /**
     * Used to indicate the DEFAULT state of the Email Manager
     */
    const READY_STATE_NOT_READY_STR = 'NOT READY';
    /**
     * Used to indicate that the email server and message content has been configured
     */
    const READY_STATE_INITIATED_STR = 'INITIATED';
    /**
     * Used to indicate that at least 1 recipient email address has been added
     */
    const READY_STATE_READY_STR = 'READY';

    /**
     * @var The PHPMailer instance
     */
    protected static $PHPMailerObj;
    /**
     * @var string The Email Manager ready state that control whether emails can be sent
     */
    protected static $ReadyState = self::READY_STATE_NOT_READY_STR;

    /**
     * @param string $SubjectStr Your email's subject
     * @param string $MessageHTMLStr The HTML message of your email
     * @param null $MessagePlainTextStr The plaintext message of your email (optional)
     * @param null $FromDetailsArray OPTIONAL Can be specified as array("email" => 'john@do.com',"name" => 'John Doe');
     * @param null $ReplyDetailsArray OPTIONAL Can be specified as array("email" => 'john@do.com',"name" => 'John Doe');
     * @param bool $isHTMLBool Indicates whether we will send the email as HTML or plaintext
     * @throws Exception
     */
    public static function prepareEmail($SubjectStr = 'Default subject',
                                        $MessageHTMLStr = '',
                                        $MessagePlainTextStr = null,
                                        $FromDetailsArray = null,
                                        $ReplyDetailsArray = null,
                                        $isHTMLBool = true) {
        self::$PHPMailerObj = new PHPMailer(true);
        //Server settings
        self::$PHPMailerObj->isSMTP();                                            // Send using SMTP
        self::$PHPMailerObj->SMTPAuth    = true;                                   // Enable SMTP authentication
        self::$PHPMailerObj->SMTPDebug   = EmailSettings::$SMTPDebugMode;
        self::$PHPMailerObj->Host        = EmailSettings::$SMTPServer;
        self::$PHPMailerObj->Username    = EmailSettings::$SMTPUsername;
        self::$PHPMailerObj->Password    = EmailSettings::$SMTPPassword;
        self::$PHPMailerObj->Port        = EmailSettings::$SMTPPort;
        self::$PHPMailerObj->SMTPAutoTLS = EmailSettings::$SMTPAutoTLS;
        if (EmailSettings::$SMTPForceSecurityProtocol) {
            self::$PHPMailerObj->SMTPSecure = EmailSettings::$SMTPSecure;
        }
        self::$PHPMailerObj->isHTML($isHTMLBool);                                  // Set email format to HTML/Plaintext

        if (is_null($FromDetailsArray)) {
            $FromDetailsArray = array("email" => self::$PHPMailerObj->Username,"name" => self::$PHPMailerObj->Username);
        }
        if (is_null($ReplyDetailsArray)) {
            $ReplyDetailsArray = array("email" => self::$PHPMailerObj->Username,"name" => self::$PHPMailerObj->Username);
        }

        if (!isset($FromDetailsArray['email'])) {
            $FromDetailsArray['email'] = self::$PHPMailerObj->Username;
        }
        if (!isset($FromDetailsArray['name'])) {
            $FromDetailsArray['name'] = '';
        }

        if (!isset($ReplyDetailsArray['email'])) {
            $ReplyDetailsArray['email'] = self::$PHPMailerObj->Username;
        }
        if (!isset($ReplyDetailsArray['name'])) {
            $ReplyDetailsArray['name'] = '';
        }

        self::$PHPMailerObj->setFrom($FromDetailsArray['email'], $FromDetailsArray["name"]);
        self::$PHPMailerObj->addReplyTo($ReplyDetailsArray['email'], $ReplyDetailsArray["name"]);

        self::$PHPMailerObj->Subject = $SubjectStr;
        self::$PHPMailerObj->Body    = $MessageHTMLStr;
        if (is_null($MessagePlainTextStr)) {
            $MessagePlainTextStr = $MessageHTMLStr;
        }
        self::$PHPMailerObj->AltBody = $MessagePlainTextStr;

        self::$ReadyState = self::READY_STATE_INITIATED_STR;
    }

    /**
     * In order for an email to be ready to sent, it needs at least 1 recipient address.
     * @param null $EmailAddressStr The email address to send to
     * @param null $FullName The optional name of the recipient
     * @throws \Exception
     */
    public static function addRecipientAddress($EmailAddressStr = null, $FullName = null) {
        if (!is_null($FullName)) {
            self::$PHPMailerObj->addAddress($EmailAddressStr, $FullName);     // Add a recipient
        } else {
            self::$PHPMailerObj->addAddress($EmailAddressStr);     // Add a recipient
        }
        self::$ReadyState = self::READY_STATE_READY_STR;
    }

    /**
     * @param null $EmailAddressStr The email address to cc
     * @throws \Exception
     */
    public static function addCCAddress($EmailAddressStr = null) {
        if (self::$ReadyState != self::READY_STATE_READY_STR) {
            throw new \Exception("Trying to add CC address: $EmailAddressStr. Ready state must be ".self::READY_STATE_READY_STR."; Current state: ".self::$ReadyState);
        }
        self::$PHPMailerObj->addCC($EmailAddressStr);     // Add a recipient
    }

    /**
     * @param null $EmailAddressStr The email address to bcc
     * @throws \Exception
     */
    public static function addBCCAddress($EmailAddressStr = null) {
        if (self::$ReadyState != self::READY_STATE_READY_STR) {
            throw new \Exception("Trying to add BCC address: $EmailAddressStr. Ready state must be ".self::READY_STATE_READY_STR."; Current state: ".self::$ReadyState);
        }
        self::$PHPMailerObj->addBCC($EmailAddressStr);     // Add a recipient
    }

    /**
     * @param null $FilePath The path, from document root, of the file to add as an attachment
     * @param null $FileName The OPTIONAL name of the file
     * @throws \Exception
     */
    public static function addAttachment($FilePath = null, $FileName = null) {
        if (self::$ReadyState != self::READY_STATE_READY_STR) {
            throw new \Exception("Trying to add attachment for PHPMailer. Ready state must be ".self::READY_STATE_READY_STR."; Current state: ".self::$ReadyState);
        }
        if (!file_exists($FilePath)) {
            throw new \Exception("Trying to add attachment for PHPMailer. No file exists at $FilePath");
        } else {
            if (!is_null($FileName)) {
                self::$PHPMailerObj->addAttachment($FilePath, $FileName);
            } else {
                self::$PHPMailerObj->addAttachment($FilePath);
            }
        }
    }

    /**
     * @param $ErrorInfo Will be an array containing information about what happened
     * @return bool True when an email was successfully sent, false when not.
     * @throws dxCallerException
     */
    public static function sendEmail(&$ErrorInfo) {
        if (self::$ReadyState != self::READY_STATE_READY_STR) {
            throw new \Exception("Trying to send email with PHPMailer. Ready state must be ".self::READY_STATE_READY_STR."; Current state: ".self::$ReadyState);
        }
        $ErrorInfo = [];
        if (class_exists("EmailMessage")) {
            $EmailMessageObj = new EmailMessage();
            $EmailMessageObj->SentDate = dxDateTime::Now();
            $EmailMessageObj->FromAddress = self::$PHPMailerObj->From;
            $EmailMessageObj->ReplyEmail = json_encode(self::$PHPMailerObj->getReplyToAddresses());
            $EmailMessageObj->Recipients = json_encode(self::$PHPMailerObj->getToAddresses());
            $EmailMessageObj->Cc = json_encode(self::$PHPMailerObj->getCcAddresses());
            $EmailMessageObj->Bcc = json_encode(self::$PHPMailerObj->getBccAddresses());
            $EmailMessageObj->Subject = self::$PHPMailerObj->Subject;
            $EmailMessageObj->EmailMessage = self::$PHPMailerObj->Body;
            $EmailMessageObj->ErrorInfo = "Pre send";
            $EmailMessageObj->Save();
            $ErrorInfo["EmailMessageId"] = $EmailMessageObj->Id;
        }
        set_exception_handler('handleEmailException');
        $EmailSendSuccessBool = false;
        $ErrorInfo["Status"] = 'NOT SENT';
        try {
            self::$PHPMailerObj->send();
            if (class_exists("EmailMessage")) {
                $EmailMessageObj->ErrorInfo = 'Message sent';
            }
            $EmailSendSuccessBool = true;
            $ErrorInfo["Status"] = 'SENT';
        } catch (Exception $e) {
            if (class_exists("EmailMessage")) {
                $EmailMessageObj->ErrorInfo = self::$PHPMailerObj->ErrorInfo;
            }
            $ErrorInfo["Details"] = self::$PHPMailerObj->ErrorInfo;
        }
        set_exception_handler('divbloxHandleException');
        $EmailMessageObj->Save();
        return $EmailSendSuccessBool;
    }

}
abstract class EmailSettings_Base {
    /**
     * @var string
     */
    public static $SMTPServer = 'smtp1.example.com';
    /**
     * @var string
     */
    public static $SMTPUsername = 'user@example.com';
    /**
     * @var string
     */
    public static $SMTPPassword = 'secret';
    /**
     * @var int
     */
    public static $SMTPPort = 587;
    /**
     * @var int
     */
    public static $SMTPDebugMode = SMTP::DEBUG_OFF;// To enable verbose debug output, use DEBUG_SERVER
    /**
     * @var string
     */
    public static $SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;// Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
    /**
     * @var bool
     */
    public static $SMTPForceSecurityProtocol = false;
    /**
     * @var bool
     */
    public static $SMTPAutoTLS = false;

}
//endregion
?>