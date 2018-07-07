<?php

namespace AvtoDev\IDEntity\Tests\Types;

use AvtoDev\IDEntity\IDEntity;
use AvtoDev\IDEntity\Types\IDEntityGrz;
use Illuminate\Support\Str;

/**
 * Class IDEntityGrzTest.
 */
class IDEntityGrzTest extends AbstractIDEntityTestCase
{
    /**
     * @var IDEntityGrz
     */
    protected $instance;

    /**
     * Test class constants.
     *
     * @return void
     */
    public function testConstants()
    {
        $instance = $this->instance; // PHP 5.6

        $this->assertEquals('X000XX77_OR_X000XX777', $instance::FORMAT_PATTERN_1);
        $this->assertEquals('X000XX', $instance::FORMAT_PATTERN_2);
        $this->assertEquals('XX00077', $instance::FORMAT_PATTERN_3);
        $this->assertEquals('0000XX77', $instance::FORMAT_PATTERN_4);
        $this->assertEquals('XX000077', $instance::FORMAT_PATTERN_5);
        $this->assertEquals('X000077', $instance::FORMAT_PATTERN_6);
        $this->assertEquals('000X77', $instance::FORMAT_PATTERN_7);
        $this->assertEquals('0000X77', $instance::FORMAT_PATTERN_8);

        $this->assertEquals('TYPE_1', $instance::GOST_TYPE_1);
        $this->assertEquals('TYPE_1A', $instance::GOST_TYPE_1A);
        $this->assertEquals('TYPE_1B', $instance::GOST_TYPE_1B);
        $this->assertEquals('TYPE_2', $instance::GOST_TYPE_2);
        $this->assertEquals('TYPE_3', $instance::GOST_TYPE_3);
        $this->assertEquals('TYPE_4', $instance::GOST_TYPE_4);
        $this->assertEquals('TYPE_5', $instance::GOST_TYPE_5);
        $this->assertEquals('TYPE_6', $instance::GOST_TYPE_6);
        $this->assertEquals('TYPE_7', $instance::GOST_TYPE_7);
        $this->assertEquals('TYPE_8', $instance::GOST_TYPE_8);
        $this->assertEquals('TYPE_20', $instance::GOST_TYPE_20);
        $this->assertEquals('TYPE_21', $instance::GOST_TYPE_21);
        $this->assertEquals('TYPE_22', $instance::GOST_TYPE_22);

        $this->assertInternalType('array', $instance::PATTERNS_AND_TYPES_MAP);
        $this->assertNotEmpty($instance::PATTERNS_AND_TYPES_MAP);
    }

    /**
     * Test method `gostTypeToPattern()`.
     *
     * @return void
     */
    public function testGostTypeToPattern()
    {
        $instance = $this->instance; // PHP 5.6

        $this->assertEquals($instance::FORMAT_PATTERN_1, $instance::getFormatPatternByGostType($instance::GOST_TYPE_1));

        $this->assertEquals($instance::FORMAT_PATTERN_2, $instance::getFormatPatternByGostType($instance::GOST_TYPE_1A));

        $this->assertEquals($instance::FORMAT_PATTERN_3, $instance::getFormatPatternByGostType($instance::GOST_TYPE_1B));
        $this->assertEquals($instance::FORMAT_PATTERN_3, $instance::getFormatPatternByGostType($instance::GOST_TYPE_2));

        $this->assertEquals($instance::FORMAT_PATTERN_4, $instance::getFormatPatternByGostType($instance::GOST_TYPE_3));
        $this->assertEquals($instance::FORMAT_PATTERN_4, $instance::getFormatPatternByGostType($instance::GOST_TYPE_4));
        $this->assertEquals($instance::FORMAT_PATTERN_4, $instance::getFormatPatternByGostType($instance::GOST_TYPE_5));
        $this->assertEquals($instance::FORMAT_PATTERN_4, $instance::getFormatPatternByGostType($instance::GOST_TYPE_7));
        $this->assertEquals($instance::FORMAT_PATTERN_4, $instance::getFormatPatternByGostType($instance::GOST_TYPE_8));

        $this->assertEquals($instance::FORMAT_PATTERN_5, $instance::getFormatPatternByGostType($instance::GOST_TYPE_6));

        $this->assertEquals($instance::FORMAT_PATTERN_6, $instance::getFormatPatternByGostType($instance::GOST_TYPE_20));

        $this->assertEquals($instance::FORMAT_PATTERN_7, $instance::getFormatPatternByGostType($instance::GOST_TYPE_21));

        $this->assertEquals($instance::FORMAT_PATTERN_8, $instance::getFormatPatternByGostType($instance::GOST_TYPE_22));

        $this->assertNull($instance::getFormatPatternByGostType('foo bar'));
        $this->assertNull($instance::getFormatPatternByGostType(123));
        $this->assertNull($instance::getFormatPatternByGostType(null));
    }

    public function testPatternToGostTypes()
    {
        $instance = $this->instance; // PHP 5.6

        $this->assertEquals(
            $instance::getGostTypesByPattern($instance::FORMAT_PATTERN_1),
            $instance::PATTERNS_AND_TYPES_MAP[$instance::FORMAT_PATTERN_1]
        );

        $this->assertEquals(
            $instance::getGostTypesByPattern($instance::FORMAT_PATTERN_2),
            $instance::PATTERNS_AND_TYPES_MAP[$instance::FORMAT_PATTERN_2]
        );

        $this->assertEquals(
            $instance::getGostTypesByPattern($instance::FORMAT_PATTERN_3),
            $instance::PATTERNS_AND_TYPES_MAP[$instance::FORMAT_PATTERN_3]
        );

        $this->assertEquals(
            $instance::getGostTypesByPattern($instance::FORMAT_PATTERN_4),
            $instance::PATTERNS_AND_TYPES_MAP[$instance::FORMAT_PATTERN_4]
        );

        $this->assertEquals(
            $instance::getGostTypesByPattern($instance::FORMAT_PATTERN_5),
            $instance::PATTERNS_AND_TYPES_MAP[$instance::FORMAT_PATTERN_5]
        );

        $this->assertEquals(
            $instance::getGostTypesByPattern($instance::FORMAT_PATTERN_6),
            $instance::PATTERNS_AND_TYPES_MAP[$instance::FORMAT_PATTERN_6]
        );

        $this->assertEquals(
            $instance::getGostTypesByPattern($instance::FORMAT_PATTERN_7),
            $instance::PATTERNS_AND_TYPES_MAP[$instance::FORMAT_PATTERN_7]
        );

        $this->assertEquals(
            $instance::getGostTypesByPattern($instance::FORMAT_PATTERN_8),
            $instance::PATTERNS_AND_TYPES_MAP[$instance::FORMAT_PATTERN_8]
        );


        $this->assertNull($instance::getGostTypesByPattern('foo bar'));
        $this->assertNull($instance::getGostTypesByPattern(123));
        $this->assertNull($instance::getGostTypesByPattern(null));
    }

    /**
     * {@inheritdoc}
     */
    public function testGetType()
    {
        $this->assertEquals(IDEntity::ID_TYPE_GRZ, $this->instance->getType());
    }

    /**
     * {@inheritdoc}
     */
    public function testIsValid()
    {
        $valid = [
            // М000ММ77 или М000ММ777 (тип 1 - Для легковых, грузовых, грузопассажирских ТС и автобусов)
            'М000ММ77',
            'М000ММ777',
            'А825МС716',
            'Р392КК190',
            'С731НХ197',
            'Е750МО750',
            'М396СХ46',
            'А137НО89',
            'К898КМ40',
            'О772ТХ197',
            'В771ЕК126',
            'Х894СВ59',
            'Е373ТА73',
            'А777АА77',
            'О704КО190',
            'У868УК26',
            'М824РН78',
            'Т149ОЕ190',
            'Т293ТА178',
            'О476ЕЕ750',
            'В168ТС190',
            'У460УА77',
            'Т258СА77',
            'С475РУ777',
            'Р295ЕЕ178',
            'Х918УУ116',
            'Х116РЕ96',
            'У888ЕК99',
            'О292ОМ77',
            'С989ЕР72',
            'К324МУ750',
            'Е228РХ33',
            'О166РУ174',
            'Н492ТН197',
            'К206МХ32',
            'Р515ЕР19',
            'Н416ТЕ161',
            'У477ЕМ178',
            'Н090РН777',
            'В399УН777',
            'Е986НХ199',
            'М441ЕЕ73',
            'Р842СН777',
            'У914ВХ123',
            'Р181СК161',
            'У371ВН142',
            'У752НХ178',
            'А548ВР750',
            'Н580ХС38',
            'Е427ЕВ190',
            'О386АА40',
            'С061ОУ777',
            'Р295КА102',
            'Р239УЕ777',
            'О461ОВ750',
            'К005АВ77',
            'Е029ХВ70',
            'У956УС777',
            'А528КТ37',
            'Р602ВС86',
            'Р048ОА750',
            'Е251ВК82',
            'Е966РА777',
            'Н340АХ199',
            'Т555СН42',
            'К052ОУ178',
            'М333МВ161',
            'А028ЕУ178',
            'С326ХО199',
            'С976РТ98',
            'Н388ЕУ750',
            'М770РВ161',
            'М828МР02',
            'О377ЕТ750',
            'Е697ХС163',
            'Т612ХХ47',
            'В750КО777',
            'Т085КР123',
            'У700КХ61',
            'К988СС82',
            'Т039КР60',
            'Е751УХ197',
            'С572ЕУ777',
            'Е393МН33',
            'С552ВХ102',
            'Н327СМ777',
            'А284АР777',
            'У606КЕ33',
            'у828хк47',
            'о590тт98',

            // М000ММ (тип 1А - Для легковых ТС должностных лиц)
            'М000ММ',
            'О772ТХ',
            'В771ЕК',
            'Х894СВ',
            'Е373ТА',
            'А777АА',
            'О704КО',
            'У868УК',
            'М824РН',
            'Т149ОЕ',
            'Т293ТА',
            'О476ЕЕ',
            'В168ТС',
            'У460УА',
            'Т258СА',
            'С475РУ',
            'Р295ЕЕ',
            'Х918УУ',
            'Х116РЕ',
            'У888ЕК',
            'О292ОМ',
            'С989ЕР',
            'К324МУ',
            'Е228РХ',
            'О166РУ',
            'Н492ТН',
            'К206МХ',
            'Р515ЕР',
            'Н416ТЕ',
            'У477ЕМ',
            'Н090РН',
            'В399УН',
            'Е986НХ',
            'М441ЕЕ',
            'Р842СН',
            'о292ом',
            'с989ер',

            // ММ00077 (тип 1Б - Для легковых ТС, исп. для перевозки людей на коммерческой основе, автобусов)
            // ММ00077 (тип 2 - Для автомобильных прицепов и полуприцепов)
            'ММ00077',
            'СХ39646',
            'НО13789',
            'КМ89840',
            'СВ89459',
            'ТА37373',
            'АА77777',
            'УК86826',
            'РН82478',
            'УА46077',
            'СА25877',
            'РЕ11696',
            'ЕК88899',
            'ОМ29277',
            'ЕР98972',
            'РХ22833',
            'МХ20632',
            'ЕР51519',
            'ЕЕ44173',
            'ХС58038',
            'са25877',
            'ре11696',

            // 0000ММ77 (тип 3 - Для тракторов, самоходных дорожно-строительных машин и иных машин и прицепов)
            // 0000ММ77 (тип 4 - Для мотоциклов, мотороллеров, мопедов)
            // 0000ММ77 (тип 5 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов)
            // 0000ММ77 (тип 7 - Для тракторов, самоходных дорожно-строительных машин и иных машин и прицепов)
            // 0000ММ77 (тип 8 - Для мотоциклов, мотороллеров, мопедов)
            '0000ММ77',
            '6868УК26',
            '2824РН78',
            '6460УА77',
            '5258СА77',
            '1116РЕ96',
            '8888ЕК99',
            '9292ОМ77',
            '8989ЕР72',
            '2228РХ33',
            '0206МХ32',
            '1515ЕР19',
            '4441ЕЕ73',
            '8580ХС38',
            '8386АА40',
            '0005АВ77',
            '2029ХВ70',
            '2528КТ37',
            '0602ВС86',
            '5251ВК82',
            '5555СН42',
            '7976РТ98',
            '2828МР02',
            '1612ХХ47',
            '0700КХ61',
            '8988СС82',
            '3039КР60',
            '9393МН33',
            '0606КЕ33',
            '2029хв70',
            '2528кт37',

            // ММ000077 (тип 6 - Для автомобильных прицепов и полуприцепов)
            'ММ000077',
            'УК868626',
            'МН824278',
            'УА460677',
            'ТА258577',
            'ХЕ116196',
            'УК888899',
            'ОМ292977',
            'СР989872',
            'ЕХ228233',
            'КХ206032',
            'РР515119',
            'МЕ441473',
            'НС580838',
            'ОА386840',
            'КВ005077',
            'ЕВ029270',
            'АТ528237',
            'РС602086',
            'ЕК251582',
            'ТН555542',
            'СТ976798',
            'МР828202',
            'ТХ612147',
            'УХ700061',
            'КС988882',
            'ТР039360',
            'ЕН393933',
            'УЕ606033',
            'кв005077',
            'ев029270',

            // М000077 (тип 20 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов)
            'М000077',
            'К868626',
            'Н824278',
            'А460677',
            'А258577',
            'Е116196',
            'К888899',
            'М292977',
            'Р989872',
            'Х228233',
            'Х206032',
            'Р515119',
            'Е441473',
            'С580838',
            'А386840',
            'В005077',
            'В029270',
            'Т528237',
            'С602086',
            'К251582',
            'Н555542',
            'Т976798',
            'Р828202',
            'Х612147',
            'Х700061',
            'С988882',
            'Р039360',
            'Н393933',
            'Е606033',
            'в005077',
            'в029270',

            // 000М77 (тип 21 - Для автомобильных прицепов и полуприцепов)
            '000М77',
            '866К26',
            '822Н78',
            '466А77',
            '255А77',
            '111Е96',
            '888К99',
            '299М77',
            '988Р72',
            '222Х33',
            '200Х32',
            '511Р19',
            '444Е73',
            '588С38',
            '388А40',
            '000В77',
            '022В70',
            '522Т37',
            '600С86',
            '255К82',
            '555Н42',
            '977Т98',
            '822Р02',
            '611Х47',
            '700Х61',
            '988С82',
            '033Р60',
            '399Н33',
            '600Е33',
            '000в77',
            '022в70',
            '522т37',

            // 0000М77 (тип 22 - Для мотоциклов)
            '0000М77',
            '6868У26',
            '2824Р78',
            '6460У77',
            '5258С77',
            '1116Р96',
            '8888Е99',
            '9292О77',
            '8989Е72',
            '2228Р33',
            '0206М32',
            '1515Е19',
            '4441Е73',
            '8580Х38',
            '8386А40',
            '0005А77',
            '2029Х70',
            '2528К37',
            '0602В86',
            '5251В82',
            '5555С42',
            '7976Р98',
            '2828М02',
            '1612Х47',
            '0700К61',
            '8988С82',
            '3039К60',
            '9393М33',
            '0606К33',
            '2029х70',
            '2528к37',
        ];

        foreach ($valid as $value) {
            $this->assertTrue($this->instance->setValue($value)->isValid(), sprintf('GRZ: "%s"', $value));
        }

        $invalid = [
            // Слишком длинные
            'Р394242КК190',
            'С73321НХ197',
            'Е7350МО750',
            'М396СРХ46',
            'А137НОО89',
            'К898КМ4054',
            'К8948КМ404',

            // Слишком короткие
            'У752НХ1',
            'А548ВР7',
            'Н58ХС38',
            'Е47ЕВ190',
            'О386А40',
            'С06ОУ777',
            'Р295К102',

            // Содержащие запрещенные символы
            'Н416ТЯ161',
            'Ю477ЕМ178',
            'Н090ЫН777',
            'Ф399УН777',
            'Е986НЪ199',
            'М441ЕЦ73',
            'Р842ЭН777',
        ];

        foreach ($invalid as $value) {
            $this->assertFalse($this->instance->setValue($value)->isValid(), sprintf('GRZ: "%s"', $value));
        }

        $this->assertFalse($this->instance->setValue('TSMEYB21S00610448')->isValid());
        $this->assertFalse($this->instance->setValue('LN130-0128818')->isValid());
    }

    /**
     * Test pattern format getter.
     *
     * @return void
     */
    public function testGetFormatPattern()
    {
        $instance = $this->instance; // PHP 5.6

        $asserts = [
            $instance::FORMAT_PATTERN_1 => [
                'М000ММ77',
                'М000ММ777',
                'А825МС716',
                'Р392КК190',
                'С731НХ197',
                'Е750МО750',
                'М396СХ46',
                'А137НО89',
                'К898КМ40',
                'О772ТХ197',
                'В771ЕК126',
                'Х894СВ59',
            ],

            $instance::FORMAT_PATTERN_2 => [
                'М000ММ',
                'О772ТХ',
                'В771ЕК',
                'Х894СВ',
                'Е373ТА',
                'А777АА',
                'О704КО',
                'У868УК',
            ],

            $instance::FORMAT_PATTERN_3 => [
                'ММ00077',
                'СХ39646',
                'НО13789',
                'КМ89840',
                'СВ89459',
                'ТА37373',
            ],

            $instance::FORMAT_PATTERN_4 => [
                '0000ММ77',
                '6868УК26',
                '2824РН78',
                '6460УА77',
                '5258СА77',
                '1116РЕ96',
                '8888ЕК99',
                '9292ОМ77',
                '8989ЕР72',
            ],

            $instance::FORMAT_PATTERN_5 => [
                'ММ000077',
                'УК868626',
                'МН824278',
                'УА460677',
                'ТА258577',
                'ХЕ116196',
                'УК888899',
                'ОМ292977',
                'СР989872',
            ],

            $instance::FORMAT_PATTERN_6 => [
                'М000077',
                'К868626',
                'Н824278',
                'А460677',
                'А258577',
                'Е116196',
                'К888899',
                'М292977',
                'Р989872',
                'Х228233',
                'Х206032',
                'Р515119',
            ],

            $instance::FORMAT_PATTERN_7 => [
                '000М77',
                '866К26',
                '822Н78',
                '466А77',
                '255А77',
                '111Е96',
                '888К99',
                '299М77',
                '988Р72',
                '222Х33',
            ],

            $instance::FORMAT_PATTERN_8 => [
                '0000М77',
                '6868У26',
                '2824Р78',
                '6460У77',
                '5258С77',
                '1116Р96',
                '8888Е99',
                '9292О77',
                '8989Е72',
            ],
        ];

        foreach ($asserts as $pattern => $cases) {
            foreach ($cases as $case) {
                $this->assertEquals(
                    $pattern,
                    $this->instance->setValue($case)->getFormatPattern(), sprintf('GRZ "%s" != "%s"', $case, $pattern)
                );
            }
        }
    }

    /**
     * Тест метода получения кода региона ГРЗ номера.
     *
     * @return void
     */
    public function testGetRegionCode()
    {
        $expects = [
            // М000ММ77 или М000ММ777 (тип 1 - Для легковых, грузовых, грузопассажирских ТС и автобусов)
            'М000ММ777' => 777,
            'А825МС716' => 716,
            'Р392КК190' => 190,
            'С731НХ197' => 197,
            'Е750МО750' => 750,
            'О772ТХ197' => 197,
            'В771ЕК126' => 126,
            'О704КО190' => 190,
            'Т149ОЕ190' => 190,
            'Т293ТА178' => 178,
            'О476ЕЕ750' => 750,
            'В168ТС190' => 190,
            'С475РУ777' => 777,
            'Р295ЕЕ178' => 178,
            'Х918УУ116' => 116,
            'К324МУ750' => 750,
            'О166РУ174' => 174,
            'Н492ТН197' => 197,
            'Н416ТЕ161' => 161,
            'У477ЕМ178' => 178,
            'Н090РН777' => 777,
            'В399УН777' => 777,
            'Е986НХ199' => 199,
            'Р842СН777' => 777,
            'У914ВХ123' => 123,
            'Р181СК161' => 161,
            'У371ВН142' => 142,
            'У752НХ178' => 178,
            'А548ВР750' => 750,
            'Е427ЕВ190' => 190,
            'С061ОУ777' => 777,
            'Р295КА102' => 102,
            'Р239УЕ777' => 777,
            'О461ОВ750' => 750,
            'У956УС777' => 777,
            'Р048ОА750' => 750,
            'Е966РА777' => 777,
            'Н340АХ199' => 199,
            'К052ОУ178' => 178,
            'М333МВ161' => 161,
            'А028ЕУ178' => 178,
            'С326ХО199' => 199,
            'М770РВ161' => 161,
            'Н388ЕУ750' => 750,
            'О377ЕТ750' => 750,
            'Е697ХС163' => 163,
            'В750КО777' => 777,
            'Т085КР123' => 123,
            'Е751УХ197' => 197,
            'С572ЕУ777' => 777,
            'С552ВХ102' => 102,
            'Н327СМ777' => 777,
            'А284АР777' => 777,
            'М441ЕЕ73'  => 73,
            'О386АА40'  => 40,
            'Н580ХС38'  => 38,
            'К005АВ77'  => 77,
            'Е029ХВ70'  => 70,
            'А528КТ37'  => 37,
            'Р602ВС86'  => 86,
            'Е251ВК82'  => 82,
            'М396СХ46'  => 46,
            'М000ММ77'  => 77,
            'А137НО89'  => 89,
            'К898КМ40'  => 40,
            'Х894СВ59'  => 59,
            'Е373ТА73'  => 73,
            'А777АА77'  => 77,
            'У868УК26'  => 26,
            'М824РН78'  => 78,
            'У460УА77'  => 77,
            'Т258СА77'  => 77,
            'Х116РЕ96'  => 96,
            'Х116РЕ01'  => 1,
            'У888ЕК99'  => 99,
            'О292ОМ77'  => 77,
            'С989ЕР72'  => 72,
            'Е228РХ33'  => 33,
            'К206МХ32'  => 32,
            'Р515ЕР19'  => 19,
            'Т555СН42'  => 42,
            'С976РТ98'  => 98,
            'М828МР02'  => 2,
            'Т612ХХ47'  => 47,
            'У700КХ61'  => 61,
            'К988СС82'  => 82,
            'Т039КР60'  => 60,
            'Е393МН33'  => 33,
            'У606КЕ33'  => 33,
            'у828хк47'  => 47,
            'о590тт98'  => 98,

            // М000ММ (тип 1А - Для легковых ТС должностных лиц)
            'М000ММ'    => null,
            'О772ТХ'    => null,
            'В771ЕК'    => null,
            'Х894СВ'    => null,
            'Е373ТА'    => null,
            'А777АА'    => null,
            'О704КО'    => null,
            'У868УК'    => null,
            'М824РН'    => null,
            'Т149ОЕ'    => null,
            'Т293ТА'    => null,
            'О476ЕЕ'    => null,
            'В168ТС'    => null,
            'У460УА'    => null,
            'Т258СА'    => null,
            'С475РУ'    => null,
            'Р295ЕЕ'    => null,
            'Х918УУ'    => null,
            'Х116РЕ'    => null,
            'У888ЕК'    => null,
            'О292ОМ'    => null,
            'С989ЕР'    => null,
            'К324МУ'    => null,
            'Е228РХ'    => null,
            'О166РУ'    => null,
            'Н492ТН'    => null,
            'К206МХ'    => null,
            'Р515ЕР'    => null,
            'Н416ТЕ'    => null,
            'У477ЕМ'    => null,
            'Н090РН'    => null,
            'В399УН'    => null,
            'Е986НХ'    => null,
            'М441ЕЕ'    => null,
            'Р842СН'    => null,
            'о292ом'    => null,
            'с989ер'    => null,

            // ММ00077 (тип 1Б - Для легковых ТС, исп. для перевозки людей на коммерческой основе, автобусов)
            // ММ00077 (тип 2 - Для автомобильных прицепов и полуприцепов)
            'ММ00077'   => 77,
            'СХ39646'   => 46,
            'НО13789'   => 89,
            'КМ89840'   => 40,
            'СВ89459'   => 59,
            'ТА37373'   => 73,
            'АА77777'   => 77,
            'УК86826'   => 26,
            'РН82478'   => 78,
            'УА46077'   => 77,
            'УА46001'   => 1,
            'СА25877'   => 77,
            'РЕ11696'   => 96,
            'ЕК88899'   => 99,
            'ОМ29277'   => 77,
            'ЕР98972'   => 72,
            'РХ22833'   => 33,
            'МХ20632'   => 32,
            'ЕР51519'   => 19,
            'ЕЕ44173'   => 73,
            'ХС58038'   => 38,
            'са25877'   => 77,
            'ре11696'   => 96,

            // 0000ММ77 (тип 3 - Для тракторов, самоходных дорожно-строительных машин и иных машин и прицепов)
            // 0000ММ77 (тип 4 - Для мотоциклов, мотороллеров, мопедов)
            // 0000ММ77 (тип 5 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов)
            // 0000ММ77 (тип 7 - Для тракторов, самоходных дорожно-строительных машин и иных машин и прицепов)
            // 0000ММ77 (тип 8 - Для мотоциклов, мотороллеров, мопедов)
            '0000ММ77'  => 77,
            '6868УК26'  => 26,
            '2824РН78'  => 78,
            '6460УА77'  => 77,
            '5258СА77'  => 77,
            '1116РЕ96'  => 96,
            '8888ЕК99'  => 99,
            '9292ОМ77'  => 77,
            '8989ЕР72'  => 72,
            '2228РХ33'  => 33,
            '0206МХ32'  => 32,
            '1515ЕР19'  => 19,
            '4441ЕЕ73'  => 73,
            '8580ХС38'  => 38,
            '8386АА40'  => 40,
            '0005АВ77'  => 77,
            '2029ХВ70'  => 70,
            '2528КТ37'  => 37,
            '0602ВС86'  => 86,
            '5251ВК82'  => 82,
            '5555СН42'  => 42,
            '7976РТ98'  => 98,
            '2828МР02'  => 2,
            '1612ХХ47'  => 47,
            '0700КХ61'  => 61,
            '8988СС82'  => 82,
            '3039КР60'  => 60,
            '9393МН33'  => 33,
            '0606КЕ33'  => 33,
            '2029хв70'  => 70,
            '2528кт37'  => 37,
            '2528кт372' => null,
            '0005АВ777' => null,

            // ММ000077 (тип 6 - Для автомобильных прицепов и полуприцепов)
            'ММ000077'  => 77,
            'УК868626'  => 26,
            'МН824278'  => 78,
            'УА460677'  => 77,
            'ТА258577'  => 77,
            'ХЕ116196'  => 96,
            'УК888899'  => 99,
            'ОМ292977'  => 77,
            'СР989872'  => 72,
            'ЕХ228233'  => 33,
            'КХ206032'  => 32,
            'РР515119'  => 19,
            'МЕ441473'  => 73,
            'НС580838'  => 38,
            'ОА386840'  => 40,
            'КВ005077'  => 77,
            'ЕВ029270'  => 70,
            'АТ528237'  => 37,
            'РС602086'  => 86,
            'ЕК251582'  => 82,
            'ТН555542'  => 42,
            'СТ976798'  => 98,
            'МР828202'  => 2,
            'ТХ612147'  => 47,
            'УХ700061'  => 61,
            'КС988882'  => 82,
            'ТР039360'  => 60,
            'ЕН393933'  => 33,
            'УЕ606033'  => 33,
            'кв005077'  => 77,
            'ев029270'  => 70,
            'кв0050777' => null,
            'ев0292197' => null,

            // М000077 (тип 20 - Для легковых, грузовых, грузопассажирских автомобилей и автобусов)
            'М000077'   => 77,
            'К868626'   => 26,
            'Н824278'   => 78,
            'А460677'   => 77,
            'А258577'   => 77,
            'Е116196'   => 96,
            'К888899'   => 99,
            'М292977'   => 77,
            'Р989872'   => 72,
            'Х228233'   => 33,
            'Х206032'   => 32,
            'Р515119'   => 19,
            'Е441473'   => 73,
            'С580838'   => 38,
            'А386840'   => 40,
            'В005077'   => 77,
            'В029270'   => 70,
            'Т528237'   => 37,
            'С602086'   => 86,
            'К251582'   => 82,
            'Н555542'   => 42,
            'Т976798'   => 98,
            'Р828202'   => 2,
            'Х612147'   => 47,
            'Х700061'   => 61,
            'С988882'   => 82,
            'Р039360'   => 60,
            'Н393933'   => 33,
            'Е606033'   => 33,
            'в005077'   => 77,
            'в029270'   => 70,
            'в0050777'  => null,
            'в0292190'  => null,

            // 000М77 (тип 21 - Для автомобильных прицепов и полуприцепов)
            '000М77'    => 77,
            '866К26'    => 26,
            '822Н78'    => 78,
            '466А77'    => 77,
            '255А77'    => 77,
            '111Е96'    => 96,
            '888К99'    => 99,
            '299М77'    => 77,
            '988Р72'    => 72,
            '222Х33'    => 33,
            '200Х32'    => 32,
            '511Р19'    => 19,
            '444Е73'    => 73,
            '588С38'    => 38,
            '388А40'    => 40,
            '000В77'    => 77,
            '022В70'    => 70,
            '522Т37'    => 37,
            '600С86'    => 86,
            '255К82'    => 82,
            '555Н42'    => 42,
            '977Т98'    => 98,
            '822Р02'    => 2,
            '611Х47'    => 47,
            '700Х61'    => 61,
            '988С82'    => 82,
            '033Р60'    => 60,
            '399Н33'    => 33,
            '600Е33'    => 33,
            '000в77'    => 77,
            '022в70'    => 70,
            '522т37'    => 37,
            '022в777'   => null,
            '522т197'   => null,

            // 0000М77 (тип 22 - Для мотоциклов)
            '0000М77'   => 77,
            '6868У26'   => 26,
            '2824Р78'   => 78,
            '6460У77'   => 77,
            '5258С77'   => 77,
            '1116Р96'   => 96,
            '8888Е99'   => 99,
            '9292О77'   => 77,
            '8989Е72'   => 72,
            '2228Р33'   => 33,
            '0206М32'   => 32,
            '1515Е19'   => 19,
            '4441Е73'   => 73,
            '8580Х38'   => 38,
            '8386А40'   => 40,
            '0005А77'   => 77,
            '2029Х70'   => 70,
            '2528К37'   => 37,
            '0602В86'   => 86,
            '5251В82'   => 82,
            '5555С42'   => 42,
            '7976Р98'   => 98,
            '2828М02'   => 2,
            '1612Х47'   => 47,
            '0700К61'   => 61,
            '8988С82'   => 82,
            '3039К60'   => 60,
            '9393М33'   => 33,
            '0606К33'   => 33,
            '9393М777'  => null,
            '0606К197'  => null,

            '123А098АА' => null,
            'foo bar'   => null,
        ];

        /** @var IDEntityGrz $instance */
        $instance = $this->instance;

        foreach ($expects as $what => $with) {
            $this->assertEquals(
                $with, $instance->setValue($what)->getRegionCode(), sprintf('"%s" !== "%s"', $what, $with)
            );
        }
    }

    /**
     * Тест метода, возвращающего данные о регионе ГРЗ номера.
     *
     * @return void
     */
    public function testGetRegionData()
    {
        $expects = [
            'С552ВХ102' => 'RU-BA',
            'Н327СМ777' => 'RU-MOW',
            'АА000177'  => 'RU-MOW',
            'У606КЕ33'  => 'RU-VLA',
            'У828ХК47'  => 'RU-LEN',
            'О590ТТ98'  => 'RU-SPE',
            'О168РЕ197' => 'RU-MOW',
            'Т900ММ77'  => 'RU-MOW',
            'Т462КО750' => 'RU-MOS',
            'Р012МА34'  => 'RU-VGG',
            'У188РУ174' => 'RU-CHE',
            'В164ОЕ190' => 'RU-MOS',
            'О832ВТ31'  => 'RU-BEL',
            'А098АА99'  => 'RU-MOW',
            'А825МС716' => 'RU-TA',

            '022В77' => 'RU-MOW',
            '522Т02' => 'RU-BA',
        ];

        /** @var IDEntityGrz $instance */
        $instance = $this->instance;

        foreach ($expects as $what => $with) {
            $this->assertEquals(
                $with, $instance->setValue($what)->getRegionData()->getIso31662(),
                sprintf('"%s" !== "%s"', $what, $with)
            );
        }

        $fails = [
            'А098А',
            '123А098АА',
            'foo bar',
        ];

        foreach ($fails as $fail) {
            $this->assertNull($instance->setValue($fail)->getRegionData());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function testNormalize()
    {
        $instance = $this->instance;

        // Из нижнего регистра переведёт в верхний
        $this->assertEquals($valid = $this->getValidValue(), $instance::normalize(Str::lower($this->getValidValue())));

        // Пробелы - успешно триммит
        $this->assertEquals($valid, $instance::normalize(' ' . $this->getValidValue() . ' '));

        // Латиницу заменяет на кириллицу
        $this->assertEquals($valid, $instance::normalize('X123YO96'));

        // Некорректные символы - удаляет
        $this->assertEquals($valid, $instance::normalize('X123 #$^&&&% YO96 '));

        $asserts = [
            'Х123АВ96' => ['Х123АВ96', 'Х123AB96'],
            'Х123ЕК96' => ['Х123ЕК96', 'Х123EK96'],
            'Х123МН96' => ['Х123МН96', 'Х123MH96'],
            'Х123ОР96' => ['Х123ОР96', 'Х123OP96'],
            'Х123СТ96' => ['Х123СТ96', 'Х123CT96'],
            'Х123УХ96' => ['Х123УХ96', 'Х123YX96'],
        ];

        foreach ($asserts as $with => $what) {
            foreach ($what as $item) {
                $this->assertEquals($with, $instance::normalize($item));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getClassName()
    {
        return IDEntityGrz::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidValue()
    {
        return 'Х123УО96';
    }
}
