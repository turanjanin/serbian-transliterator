# Serbian Transliterator - PHP library

This library converts text between Serbian Latin and Cyrillic scripts. 

It comes with an extensive list of exceptions in order to properly handle digraphs when transliterating 
from Latin to Cyrillic (*injekcija* becomes *инјекција* instead of *ињекција*).

Additionally, the most common foreign words will be detected and left in their original form (*Facebook* won't be transliterated to *Фацебоок*).


## Installation

You can install the package via composer:

```bash
composer require turanjanin/serbian-transliterator
```

## Usage

```php
use Turanjanin\SerbianTransliterator\Transliterator;


echo Transliterator::toLatin('Брза вижљаста лија хоће да ђипи преко лењог флегматичног џукца.');
// Brza vižljasta lija hoće da đipi preko lenjog flegmatičnog džukca.

echo Transliterator::toAsciiLatin('Fijuče vetar u šiblju, ledi pasaže i kuće iza njih i gunđa u odžacima.');
// Fijuce vetar u siblju, ledi pasaze i kuce iza njih i gundja u odzacima.

echo Transliterator::toCyrillic('Odjednom Tanjug reče da će nadživeti injekciju. Dodjavola, džangrizava njuška je bila u pravu.');
// Одједном Танјуг рече да ће надживети инјекцију. Дођавола, џангризава њушка је била у праву.
```


## Author

- [Jovan Turanjanin](https://github.com/turanjanin)

The list of exceptions for transliteration is taken from [turanjanin/cirilizator](https://github.com/turanjanin/cirilizator) project.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
