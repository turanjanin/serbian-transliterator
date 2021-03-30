<?php

declare(strict_types=1);

namespace Turanjanin\SerbianTransliterator\Tests;

use PHPUnit\Framework\TestCase;
use Turanjanin\SerbianTransliterator\Transliterator;

class TransliteratorTest extends TestCase
{
    /** @test */
    public function it_can_transliterate_cyrillic_to_latin()
    {
        $lowercaseCyrillic = 'брза вижљаста лија хоће да ђипи преко лењог флегматичног џукца.';
        $lowercaseLatin = 'brza vižljasta lija hoće da đipi preko lenjog flegmatičnog džukca.';

        $this->assertSame($lowercaseLatin, Transliterator::toLatin($lowercaseCyrillic));

        $uppercaseCyrillic = 'ЉУДИ, ЈАЗАВАЦ ЏЕФ ТРЧИ ПО ШУМИ ГЛОЂУЋИ НЕКО СУХО ЖБУЊЕ.';
        $uppercaseLatin = 'LJUDI, JAZAVAC DŽEF TRČI PO ŠUMI GLOĐUĆI NEKO SUHO ŽBUNJE.';

        $this->assertSame($uppercaseLatin, Transliterator::toLatin($uppercaseCyrillic));
    }

    /** @test */
    public function it_can_transliterate_latin_to_latin()
    {
        $latin = 'Ljubičasti jež iz fioke hoće da pecne rđavog miša džonjala.';

        $this->assertSame($latin, Transliterator::toLatin($latin));
    }

    /** @test */
    public function it_will_properly_transliterate_case_of_latin_digraphs()
    {
        $this->assertSame('Ljubičasta LJOVISNA je ljankase', Transliterator::toLatin('Љубичаста ЉОВИСНА је љанкасе'));

        $this->assertSame('Njiše se njopajuće NJANJAVO', Transliterator::toLatin('Њише се њопајуће ЊАЊАВО'));

        $this->assertSame('Džangrizavi DŽUDISTA džemper odžakom Džodi daje.', Transliterator::toLatin('Џангризави ЏУДИСТА џемпер оџаком Џоди даје.'));
    }

    /** @test */
    public function it_can_transliterate_latin_to_ascii_latin()
    {
        $latin = 'Šefe, čiji je dođavola ovaj žuti džemper iz Ćuprije?';
        $ascii = 'Sefe, ciji je dodjavola ovaj zuti dzemper iz Cuprije?';

        $this->assertSame($ascii, Transliterator::toAsciiLatin($latin));


        // Mix of Unicode characters, combined characters and icelandic DJ
        $latin = 'Ðavo je u detaǉima, nĳe da ti Čika Džoš nije rekao.';
        $ascii = 'Djavo je u detaljima, nije da ti Cika Dzos nije rekao.';

        $this->assertSame($ascii, Transliterator::toAsciiLatin($latin));
    }

    /** @test */
    public function it_can_transliterate_cyrillic_to_ascii_latin()
    {
        $cyrillic = 'Фијуче ветар у шибљу, леди пасаже и куће иза њих и гунђа у оџацима.';
        $ascii = 'Fijuce vetar u siblju, ledi pasaze i kuce iza njih i gundja u odzacima.';

        $this->assertSame($ascii, Transliterator::toAsciiLatin($cyrillic));
    }

    /** @test */
    public function it_can_transliterate_latin_to_cyrillic()
    {
        $lowercaseLatin = 'brza vižljasta lija hoće da đipi preko lenjog flegmatičnog džukca.';
        $lowercaseCyrillic = 'брза вижљаста лија хоће да ђипи преко лењог флегматичног џукца.';

        $this->assertSame($lowercaseCyrillic, Transliterator::toCyrillic($lowercaseLatin));

        $uppercaseLatin = 'LJUDI, JAZAVAC DŽEF TRČI PO ŠUMI GLOĐUĆI NEKO SUHO ŽBUNJE.';
        $uppercaseCyrillic = 'ЉУДИ, ЈАЗАВАЦ ЏЕФ ТРЧИ ПО ШУМИ ГЛОЂУЋИ НЕКО СУХО ЖБУЊЕ.';

        $this->assertSame($uppercaseCyrillic, Transliterator::toCyrillic($uppercaseLatin));

        // Mix of Unicode characters, combined characters and icelandic DJ
        $latin = 'Ðavo je u detaǉima, nĳe da ti Čika Džoš nije rekao.';
        $cyrillic = 'Ђаво је у детаљима, није да ти Чика Џош није рекао.';

        $this->assertSame($cyrillic, Transliterator::toCyrillic($latin));
    }

    /** @test */
    public function it_will_correctly_handle_transliteration_of_latin_digraphs()
    {
        $latin = 'Odjednom Tanjug reče da će nadživeti injekciju. Dodjavola, džangrizava njuška je bila u pravu.';
        $cyrillic = 'Одједном Танјуг рече да ће надживети инјекцију. Дођавола, џангризава њушка је била у праву.';

        $this->assertSame($cyrillic, Transliterator::toCyrillic($latin));
    }

    /** @test */
    public function it_wont_transliterate_to_cyrillic_words_with_foreign_characters()
    {
        $latin = 'Biografiju pošaljite kao Word dokument u docx formatu za Über Yahu.';
        $cyrillic = 'Биографију пошаљите као Word документ у docx формату за Über Yahu.';

        $this->assertSame($cyrillic, Transliterator::toCyrillic($latin));
    }

    /** @test */
    public function it_wont_transliterate_to_cyrillic_some_of_the_most_common_foreign_words()
    {
        $latin = 'Moj DJ username Adobe Dacia po defaultu sve PDF dokumente šalje mailom Google developerima.';
        $cyrillic = 'Мој DJ username Adobe Dacia по defaultu све PDF документе шаље mailom Google developerima.';

        $this->assertSame($cyrillic, Transliterator::toCyrillic($latin));
    }

    /** @test */
    public function it_wont_transliterate_to_cyrillic_words_with_foreign_character_combination()
    {
        $latin = 'Naša pevačica, Jellena sa dva l, učestvovala u prethodnoj VII sezoni Big Brother muzzičkog festivalа na sajtu domen.com';
        $cyrillic = 'Наша певачица, Jellena са два л, учествовала у претходној VII сезони Биг Brother muzzičkog фестивала на сајту domen.com';

        $this->assertSame($cyrillic, Transliterator::toCyrillic($latin));
    }
}
