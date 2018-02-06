<?php

namespace AvtoDev\IDEntity\Types;

use Exception;
use AvtoDev\IDEntity\Helpers\Normalizer;
use AvtoDev\IDEntity\Helpers\Transliterator;
use Illuminate\Support\Str;

/**
 * Class IDEntityBody.
 *
 * Идентификатор - номер кузова.
 */
class IDEntityBody extends AbstractTypedIDEntity
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return static::ID_TYPE_BODY;
    }

    /**
     * {@inheritdoc}
     */
    public static function normalize($value)
    {
        try {
            // Заменяем множественные пробелы - одиночными
            $value = preg_replace('~\s+~u', ' ', trim((string) $value));

            // Заменяем пробелы - дефисами
            $value = preg_replace('~[[:space:]]+~', '-', $value);

            // Номализуем символы дефиса
            $value = Normalizer::normalizeDashChar($value);

            // Заменяем множественные дефисы - одиночными
            $value = preg_replace('~\-+~', '-', $value);

            // Производим замену кириллических символов на латинские аналоги
            $value = Transliterator::transliterateString(Str::upper($value), true);

            // Удаляем все символы, кроме разрешенных
            $value = preg_replace('~[^A-Z0-9\-]~u', '', $value);

            return $value;
        } catch (Exception $e) {
            // Do nothing
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidateCallbacks()
    {
        return [
            function ($value) {
                return $this->validateWithValidatorRule($value, 'required|string|body_code');
            },
        ];
    }
}
