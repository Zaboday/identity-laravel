<?php

namespace AvtoDev\IDEntity;

use AvtoDev\IDEntity\Types\IDEntityBody;
use AvtoDev\IDEntity\Types\IDEntityChassis;
use AvtoDev\IDEntity\Types\IDEntityGrz;
use AvtoDev\IDEntity\Types\IDEntityPts;
use AvtoDev\IDEntity\Types\IDEntitySts;
use AvtoDev\IDEntity\Types\IDEntityUnknown;
use AvtoDev\IDEntity\Types\IDEntityVin;
use AvtoDev\IDEntity\Types\TypedIDEntityInterface;
use LogicException;

/**
 * Class IDEntity.
 *
 * Объект идентификационной сущности.
 */
class IDEntity implements IDEntityInterface
{
    /**
     * Обозначает необходимость в автоматическом определении типа.
     *
     * @var string
     */
    const ID_TYPE_AUTO = 'AUTODETECT';

    /**
     * Неизвестный тип идентификатора.
     *
     * @var string
     */
    const ID_TYPE_UNKNOWN = 'UNKNOWN';

    /**
     * Тип - VIN-код.
     *
     * @var string
     */
    const ID_TYPE_VIN = 'VIN';

    /**
     * Тип - регистрационный (ГРЗ) знак.
     *
     * @var string
     */
    const ID_TYPE_GRZ = 'GRZ';

    /**
     * Тип - код СТС (Номер свидетельства о регистрации ТС).
     *
     * @var string
     */
    const ID_TYPE_STS = 'STS';

    /**
     * Тип - код ПТС (паспорт транспортного средства).
     *
     * @var string
     */
    const ID_TYPE_PTS = 'PTS';

    /**
     * Тип - номер шасси (встречается редко, но всё же встречается).
     *
     * @var string
     */
    const ID_TYPE_CHASSIS = 'CHASSIS';

    /**
     * Тип - номер кузова.
     *
     * @var string
     */
    const ID_TYPE_BODY = 'BODY';

    /**
     * IDEntity constructor.
     *
     * Запрещаем использование конструктора в пользу фабричного метода.
     *
     * @throws LogicException
     */
    public function __construct()
    {
        throw new LogicException(
            sprintf('Constructor for this object is unsupported. Use method "::%s" instead', 'make')
        );
    }

    /**
     * Возвращает массив поддерживаемых типов идентификаторов.
     *
     * @return string[]
     */
    public static function getSupportedTypes()
    {
        return array_keys(static::getTypesMap());
    }

    /**
     * Проверяет наличие поддержки переданного типа идентификатора.
     *
     * @param string $type
     *
     * @return bool
     */
    public static function typeIsSupported($type)
    {
        return is_string($type) && in_array($type, static::getSupportedTypes());
    }

    /**
     * {@inheritdoc}
     */
    public static function make($value, $type = self::ID_TYPE_AUTO)
    {
        // Если указанный тип идентификатора нам известен - то его и создаём
        if (class_exists($class_name = static::getEntityClassByType($type))) {
            return new $class_name($value);
        }

        // Если указан тип "авто-определение" - то поочерёдно создаем каждый тип, и проверяем соответствие методом
        // валидации
        if ($type === self::ID_TYPE_AUTO) {
            foreach (static::getTypesMap() as $class_name) {
                /** @var TypedIDEntityInterface $instance */
                if (($instance = new $class_name($value)) && $instance->isValid()) {
                    return $instance;
                }
            }
        }

        return new IDEntityUnknown($value);
    }

    /**
     * {@inheritdoc}
     */
    public static function is($value, $type)
    {
        return self::make($value, $type)->isValid();
    }

    /**
     * Метод, возвращающий массив связок "%тип_идентификатора% => %класс_его_обслуживающий%".
     *
     * Порядок элементов важен для механизма автоматического определения типа.
     *
     * @return string[]
     */
    protected static function getTypesMap()
    {
        return [
            self::ID_TYPE_VIN     => IDEntityVin::class,
            self::ID_TYPE_GRZ     => IDEntityGrz::class,
            self::ID_TYPE_STS     => IDEntitySts::class,
            self::ID_TYPE_PTS     => IDEntityPts::class,
            self::ID_TYPE_BODY    => IDEntityBody::class,
            self::ID_TYPE_CHASSIS => IDEntityChassis::class,
        ];
    }

    /**
     * Возвращает имя класса, который обслуживает идентификатор по его типу. В случае ошибки или не обнаружения - вернет
     * null.
     *
     * @param string $type
     *
     * @return string|null
     */
    protected static function getEntityClassByType($type)
    {
        return static::typeIsSupported($type)
            ? static::getTypesMap()[$type]
            : null;
    }
}
