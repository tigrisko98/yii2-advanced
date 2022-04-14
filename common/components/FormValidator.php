<?php
namespace common\components;

class FormValidator
{
    public static function validateFormData(array &$formData, array $formAttributes): array
    {
        $diff = array_diff_assoc($formData, $formAttributes);
        $formData = $diff;

        return $diff;
    }
}