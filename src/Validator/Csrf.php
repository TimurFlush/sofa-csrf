<?php

namespace TimurFlush\SofaCsrf\Validator;

use Phalcon\Validation\Message;
use Phalcon\Validation\Validator;
use Phalcon\Validation\ValidatorInterface;

/**
 * Class Csrf
 * @package TimurFlush\SofaCsrf\Validator
 * @version 2.0.0
 * @author Timur Flush
 */
class Csrf extends Validator implements ValidatorInterface
{
    public function validate(\Phalcon\Validation $validation, $attribute)
    {
        if (!$validation->getDI()->has('csrf') || !($validation->getDI()->getShared('csrf') instanceof \TimurFlush\SofaCsrf\Protection))
            trigger_error('Csrf service is not registered.', E_USER_ERROR);

        $this->setOption('cancelOnFail', true);
        $value = $validation->getValue($attribute) ?? '';

        if ($validation->getDI()->getShared('csrf')->checkToken($value))
            return true;

        $label = $this->prepareLabel($validation, $attribute);
        $message = $this->prepareMessage($validation, $attribute, 'Captcha');
        $code = $this->prepareCode($attribute);
        $replacePairs = [':field' => $label];

        $validation->appendMessage(
            new Message(
                strtr($message, $replacePairs),
                $attribute,
                'Csrf',
                $code
            )
        );
        return false;
    }
}