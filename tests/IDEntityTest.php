<?php

namespace AvtoDev\IDEntity\Tests;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\IDEntityInterface;
use AvtoDev\IDEntity\Tests\Mocks\IDEntityMock;
use AvtoDev\IDEntity\Types\IDEntityUnknown;
use Exception;

/**
 * Class IDEntityTest.
 */
class IDEntityTest extends AbstractTestCase
{
    /**
     * @var IDEntityMock
     */
    protected $instance;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->instance = new IDEntityMock;
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        unset($this->instance);

        parent::tearDown();
    }

    /**
     * Тест констант.
     *
     * @return void
     */
    public function testConstants()
    {
        $this->assertEquals('AUTODETECT', IDEntity::ID_TYPE_AUTO);
        $this->assertEquals('UNKNOWN', IDEntity::ID_TYPE_UNKNOWN);
        $this->assertEquals('VIN', IDEntity::ID_TYPE_VIN);
        $this->assertEquals('GRZ', IDEntity::ID_TYPE_GRZ);
        $this->assertEquals('STS', IDEntity::ID_TYPE_STS);
        $this->assertEquals('PTS', IDEntity::ID_TYPE_PTS);
        $this->assertEquals('CHASSIS', IDEntity::ID_TYPE_CHASSIS);
        $this->assertEquals('BODY', IDEntity::ID_TYPE_BODY);
    }

    /**
     * Тест реализации необходимых интерфейсов.
     *
     * @return void
     */
    public function testImplements()
    {
        foreach ([IDEntityInterface::class] as $class_name) {
            $this->assertInstanceOf($class_name, $this->instance);
        }
    }

    /**
     * Убеждаемся в том, что конструктор нельзя использовать.
     *
     * @return void
     */
    public function testConstructorException()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessageRegExp('~use method.+::make~i');

        new IDEntity;
    }

    /**
     * Тест метода 'getSupportedTypes'.
     *
     * @return void
     */
    public function testGetSupportedTypes()
    {
        $expects = [
            IDEntity::ID_TYPE_VIN,
            IDEntity::ID_TYPE_GRZ,
            IDEntity::ID_TYPE_STS,
            IDEntity::ID_TYPE_PTS,
            IDEntity::ID_TYPE_CHASSIS,
            IDEntity::ID_TYPE_BODY,
        ];

        foreach ($expects as $type) {
            $this->assertContains($type, IDEntity::getSupportedTypes());
        }

        foreach (['foo', null, 123, new Exception] as $type) {
            $this->assertNotContains($type, IDEntity::getSupportedTypes());
        }
    }

    /**
     * Тест метода 'typeIsSupported'.
     *
     * @return void
     */
    public function testTypeIsSupported()
    {
        $expects = [
            IDEntity::ID_TYPE_VIN,
            IDEntity::ID_TYPE_GRZ,
            IDEntity::ID_TYPE_STS,
            IDEntity::ID_TYPE_PTS,
            IDEntity::ID_TYPE_CHASSIS,
            IDEntity::ID_TYPE_BODY,
        ];

        foreach ($expects as $type) {
            $this->assertTrue(IDEntity::typeIsSupported($type));
        }

        foreach (['foo', null, 123, new Exception] as $type) {
            $this->assertFalse(IDEntity::typeIsSupported($type));
        }
    }

    /**
     * Тест метода 'make' с передачей конкретного типа.
     *
     * @return void
     */
    public function testMakeWithPassedType()
    {
        $instance = IDEntity::make('JF1SJ5LC5DG048667', $type = IDEntity::ID_TYPE_VIN);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('А123АА177', $type = IDEntity::ID_TYPE_GRZ);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('11АА112233', $type = IDEntity::ID_TYPE_STS);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('11АА112233', $type = IDEntity::ID_TYPE_PTS);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('FN15-002153', $type = IDEntity::ID_TYPE_BODY);
        $this->assertEquals($type, $instance->getType());

        $instance = IDEntity::make('FN15-002153', $type = IDEntity::ID_TYPE_CHASSIS);
        $this->assertEquals($type, $instance->getType());
    }

    /**
     * Тест метода 'make' с типом "авто-определение типа".
     *
     * @return void
     */
    public function testMakeWithAutoType()
    {
        $instance = IDEntity::make($value = 'JF1SJ5LC5DG048667');
        $this->assertEquals(IDEntity::ID_TYPE_VIN, $instance->getType());
        $this->assertEquals($value, $instance->getValue());

        $instance = IDEntity::make($value = 'А123АА177');
        $this->assertEquals(IDEntity::ID_TYPE_GRZ, $instance->getType());
        $this->assertEquals($value, $instance->getValue());

        $instance = IDEntity::make($value = '11АА112233');
        $this->assertEquals(IDEntity::ID_TYPE_STS, $instance->getType());
        $this->assertEquals($value, $instance->getValue());

        // Тип "номер ПТС" автоматически отдетектить невозможно, так как правила проверки птс и стс идентичны

        $instance = IDEntity::make($value = 'FN15-002153');
        $this->assertEquals(IDEntity::ID_TYPE_BODY, $instance->getType());
        $this->assertEquals($value, $instance->getValue());

        // Тип "номер ШАССИ" автоматически отдетектить невозможно, так как правила проверки шасси и кузова идентичны
    }

    /**
     * Тест метода 'make' с передачей неизвестного типа.
     *
     * @return void
     */
    public function testMakeWithUnknownType()
    {
        $instance = IDEntity::make('foo');
        $this->assertEquals(IDEntity::ID_TYPE_UNKNOWN, $instance->getType());
        $this->assertInstanceOf(IDEntityUnknown::class, $instance);

        $instance = IDEntity::make('foo', 'bar');
        $this->assertEquals(IDEntity::ID_TYPE_UNKNOWN, $instance->getType());
        $this->assertInstanceOf(IDEntityUnknown::class, $instance);

        $instance = IDEntity::make('foo', IDEntity::ID_TYPE_AUTO);
        $this->assertEquals(IDEntity::ID_TYPE_UNKNOWN, $instance->getType());
        $this->assertInstanceOf(IDEntityUnknown::class, $instance);
    }

    /**
     * Тест метода 'is'.
     *
     * @return void
     */
    public function testIsMethod()
    {
        $this->assertTrue(IDEntity::is($value = 'JF1SJ5LC5DG048667', IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_STS));

        $this->assertTrue(IDEntity::is($value = 'А123АА177', IDEntity::ID_TYPE_GRZ));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_STS));

        $this->assertTrue(IDEntity::is($value = '11АА112233', IDEntity::ID_TYPE_STS));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));

        $this->assertTrue(IDEntity::is($value = '11АА332211', IDEntity::ID_TYPE_PTS));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));

        $this->assertTrue(IDEntity::is($value = 'FN15-002153', IDEntity::ID_TYPE_BODY));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));

        $this->assertTrue(IDEntity::is($value = 'FN15-102153', IDEntity::ID_TYPE_CHASSIS));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_VIN));
        $this->assertFalse(IDEntity::is($value, IDEntity::ID_TYPE_GRZ));
    }
}
