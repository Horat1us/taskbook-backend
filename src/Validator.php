<?php
/**
 * Created by PhpStorm.
 * User: horat1us
 * Date: 8/19/17
 * Time: 1:05 AM
 */

namespace Horat1us\TaskBook;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;

class Validator
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array[]
     */
    protected $rules;

    /**
     * Validator constructor.
     * @param array $rules
     * @param Request $request
     */
    public function __construct(array $rules, Request $request)
    {
        $this->rules = $rules;
        $this->request = $request;
    }

    /**
     * @return array
     */
    public function validate(): array
    {
        $validator = Validation::createValidator();
        $errors = [];

        foreach ($this->rules as $field => $rules) {

            /** @var ConstraintViolation[] $violations */
            $violations = $validator->validate(
                $this->request->get($field),
                $rules
            );

            foreach ($violations as $violation) {
                $errors[] = [
                    'details' => $violation->getMessage(),
                    'code' => $violation->getCode(),
                    'attribute' => $field,
                ];
            }
        }
        return $errors;
    }

    /**
     * @return array[]
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param array[] $rules
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;
    }
}