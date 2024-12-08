<?php

namespace App\Helpers;

use InvalidArgumentException;

class RegionConverter
{
    /**
     * Массив стран и их свойств.
     * Каждая страна имеет код, имя на двух языках и континент.
     *
     * @var array
     */
    private static $countries = [
        "ad" => ["name" => ["ru" => "Андорра", "en" => "Andorra"], "continent" => "EU"],
        "ae" => ["name" => ["ru" => "ОАЭ", "en" => "United Arab Emirates"], "continent" => "AS"],
        "af" => ["name" => ["ru" => "Афганистан", "en" => "Afghanistan"], "continent" => "AS"],
        "ag" => ["name" => ["ru" => "Антигуа и Барбуда", "en" => "Antigua and Barbuda"], "continent" => "NA"],
        "ai" => ["name" => ["ru" => "Ангилья", "en" => "Anguilla"], "continent" => "NA"],
        "al" => ["name" => ["ru" => "Албания", "en" => "Albania"], "continent" => "EU"],
        "am" => ["name" => ["ru" => "Армения", "en" => "Armenia"], "continent" => "AS"],
        "an" => ["name" => ["ru" => "Нид. Антильские о-ва", "en" => "Netherlands Antilles"], "continent" => "north_america"],
        "ao" => ["name" => ["ru" => "Ангола", "en" => "Angola"], "continent" => "AF"],
        "aq" => ["name" => ["ru" => "Антарктида", "en" => "Antarctica"], "continent" => "AN"],
        "ar" => ["name" => ["ru" => "Аргентина", "en" => "Argentina"], "continent" => "SA"],
        "as" => ["name" => ["ru" => "Американское Самоа", "en" => "American Samoa"], "continent" => "OC"],
        "at" => ["name" => ["ru" => "Австрия", "en" => "Austria"], "continent" => "EU"],
        "au" => ["name" => ["ru" => "Австралия", "en" => "Australia"], "continent" => "OC"],
        "aw" => ["name" => ["ru" => "Аруба", "en" => "Aruba"], "continent" => "NA"],
        "ax" => ["name" => ["ru" => "Эландские острова", "en" => "Aland Islands"], "continent" => "EU"],
        "az" => ["name" => ["ru" => "Азербайджан", "en" => "Azerbaijan"], "continent" => "AS"],
        "ba" => ["name" => ["ru" => "Босния и Герцеговина", "en" => "Bosnia and Herzegovina"], "continent" => "EU"],
        "bb" => ["name" => ["ru" => "Барбадос", "en" => "Barbados"], "continent" => "NA"],
        "bd" => ["name" => ["ru" => "Бангладеш", "en" => "Bangladesh"], "continent" => "AS"],
        "be" => ["name" => ["ru" => "Бельгия", "en" => "Belgium"], "continent" => "EU"],
        "bf" => ["name" => ["ru" => "Буркина-Фасо", "en" => "Burkina Faso"], "continent" => "AF"],
        "bg" => ["name" => ["ru" => "Болгария", "en" => "Bulgaria"], "continent" => "EU"],
        "bh" => ["name" => ["ru" => "Бахрейн", "en" => "Bahrain"], "continent" => "AS"],
        "bi" => ["name" => ["ru" => "Бурунди", "en" => "Burundi"], "continent" => "AF"],
        "bj" => ["name" => ["ru" => "Бенин", "en" => "Benin"], "continent" => "AF"],
        "bl" => ["name" => ["ru" => "Сен-Бартельми", "en" => "Saint Barthelemy"], "continent" => "NA"],
        "bm" => ["name" => ["ru" => "Бермуды", "en" => "Bermuda"], "continent" => "NA"],
        "bn" => ["name" => ["ru" => "Бруней-Даруссалам", "en" => "Brunei"], "continent" => "AS"],
        "bo" => ["name" => ["ru" => "Боливия", "en" => "Bolivia"], "continent" => "SA"],
        "bq" => ["name" => ["ru" => "Бонайре, Саба и С.Э.", "en" => "Bonaire, Saint Eustatius and Saba"], "continent" => "NA"],
        "br" => ["name" => ["ru" => "Бразилия", "en" => "Brazil"], "continent" => "SA"],
        "bs" => ["name" => ["ru" => "Багамы", "en" => "Bahamas"], "continent" => "NA"],
        "bt" => ["name" => ["ru" => "Бутан", "en" => "Bhutan"], "continent" => "AS"],
        "bv" => ["name" => ["ru" => "Остров Буве", "en" => "Bouvet Island"], "continent" => "AN"],
        "bw" => ["name" => ["ru" => "Ботсвана", "en" => "Botswana"], "continent" => "AF"],
        "by" => ["name" => ["ru" => "Беларусь", "en" => "Belarus"], "continent" => "EU"],
        "bz" => ["name" => ["ru" => "Белиз", "en" => "Belize"], "continent" => "NA"],
        "ca" => ["name" => ["ru" => "Канада", "en" => "Canada"], "continent" => "NA"],
        "cc" => ["name" => ["ru" => "Кокосовые (Килинг) о-ва", "en" => "Cocos Islands"], "continent" => "AS"],
        "cd" => ["name" => ["ru" => "Конго, Дем. Рес-ка", "en" => "Democratic Republic of the Congo"], "continent" => "AF"],
        "cf" => ["name" => ["ru" => "Цент.-Афр. Республика", "en" => "Central African Republic"], "continent" => "AF"],
        "cg" => ["name" => ["ru" => "Конго", "en" => "Republic of the Congo"], "continent" => "AF"],
        "ch" => ["name" => ["ru" => "Швейцария", "en" => "Switzerland"], "continent" => "EU"],
        "ci" => ["name" => ["ru" => "Кот д'Ивуар", "en" => "Ivory Coast"], "continent" => "AF"],
        "ck" => ["name" => ["ru" => "Острова Кука", "en" => "Cook Islands"], "continent" => "OC"],
        "cl" => ["name" => ["ru" => "Чили", "en" => "Chile"], "continent" => "SA"],
        "cm" => ["name" => ["ru" => "Камерун", "en" => "Cameroon"], "continent" => "AF"],
        "cn" => ["name" => ["ru" => "Китай", "en" => "China"], "continent" => "AS"],
        "co" => ["name" => ["ru" => "Колумбия", "en" => "Colombia"], "continent" => "SA"],
        "cr" => ["name" => ["ru" => "Коста-Рика", "en" => "Costa Rica"], "continent" => "NA"],
        "cu" => ["name" => ["ru" => "Куба", "en" => "Cuba"], "continent" => "NA"],
        "cv" => ["name" => ["ru" => "Кабо-Верде", "en" => "Cape Verde"], "continent" => "AF"],
        "cw" => ["name" => ["ru" => "Кюрасао", "en" => "Curacao"], "continent" => "NA"],
        "cx" => ["name" => ["ru" => "Остров Рождества", "en" => "Christmas Island"], "continent" => "AS"],
        "cy" => ["name" => ["ru" => "Кипр", "en" => "Cyprus"], "continent" => "EU"],
        "cz" => ["name" => ["ru" => "Чехия", "en" => "Czech Republic"], "continent" => "EU"],
        "de" => ["name" => ["ru" => "Германия", "en" => "Germany"], "continent" => "EU"],
        "dj" => ["name" => ["ru" => "Джибути", "en" => "Djibouti"], "continent" => "AF"],
        "dk" => ["name" => ["ru" => "Дания", "en" => "Denmark"], "continent" => "EU"],
        "dm" => ["name" => ["ru" => "Доминика", "en" => "Dominica"], "continent" => "NA"],
        "do" => ["name" => ["ru" => "Доминиканская Респ.", "en" => "Dominican Republic"], "continent" => "NA"],
        "dz" => ["name" => ["ru" => "Алжир", "en" => "Algeria"], "continent" => "AF"],
        "ec" => ["name" => ["ru" => "Эквадор", "en" => "Ecuador"], "continent" => "SA"],
        "ee" => ["name" => ["ru" => "Эстония", "en" => "Estonia"], "continent" => "EU"],
        "eg" => ["name" => ["ru" => "Египет", "en" => "Egypt"], "continent" => "AF"],
        "eh" => ["name" => ["ru" => "Западная Сахара", "en" => "Western Sahara"], "continent" => "AF"],
        "er" => ["name" => ["ru" => "Эритрея", "en" => "Eritrea"], "continent" => "AF"],
        "es" => ["name" => ["ru" => "Испания", "en" => "Spain"], "continent" => "EU"],
        "et" => ["name" => ["ru" => "Эфиопия", "en" => "Ethiopia"], "continent" => "AF"],
        "fi" => ["name" => ["ru" => "Финляндия", "en" => "Finland"], "continent" => "EU"],
        "fj" => ["name" => ["ru" => "Фиджи", "en" => "Fiji"], "continent" => "OC"],
        "fk" => ["name" => ["ru" => "Фолклендские острова", "en" => "Falkland Islands"], "continent" => "SA"],
        "fm" => ["name" => ["ru" => "Микронезия, Фед. Шт.", "en" => "Micronesia"], "continent" => "OC"],
        "fo" => ["name" => ["ru" => "Фарерские острова", "en" => "Faroe Islands"], "continent" => "EU"],
        "fr" => ["name" => ["ru" => "Франция", "en" => "France"], "continent" => "EU"],
        "ga" => ["name" => ["ru" => "Габон", "en" => "Gabon"], "continent" => "AF"],
        "gb" => ["name" => ["ru" => "Великобритания", "en" => "United Kingdom"], "continent" => "EU"],
        "gd" => ["name" => ["ru" => "Гренада", "en" => "Grenada"], "continent" => "NA"],
        "ge" => ["name" => ["ru" => "Грузия", "en" => "Georgia"], "continent" => "AS"],
        "gf" => ["name" => ["ru" => "Французская Гвиана", "en" => "French Guiana"], "continent" => "SA"],
        "gg" => ["name" => ["ru" => "Гернси", "en" => "Guernsey"], "continent" => "EU"],
        "gh" => ["name" => ["ru" => "Гана", "en" => "Ghana"], "continent" => "AF"],
        "gi" => ["name" => ["ru" => "Гибралтар", "en" => "Gibraltar"], "continent" => "EU"],
        "gl" => ["name" => ["ru" => "Гренландия", "en" => "Greenland"], "continent" => "NA"],
        "gm" => ["name" => ["ru" => "Гамбия", "en" => "Gambia"], "continent" => "AF"],
        "gn" => ["name" => ["ru" => "Гвинея", "en" => "Guinea"], "continent" => "AF"],
        "gp" => ["name" => ["ru" => "Гваделупа", "en" => "Guadeloupe"], "continent" => "NA"],
        "gq" => ["name" => ["ru" => "Экваториальная Гвинея", "en" => "Equatorial Guinea"], "continent" => "AF"],
        "gr" => ["name" => ["ru" => "Греция", "en" => "Greece"], "continent" => "EU"],
        "gs" => ["name" => ["ru" => "Юж. Джорджия и Юж. Сандвичевы о-ва", "en" => "South Georgia and the South Sandwich Islands"], "continent" => "AN"],
        "gt" => ["name" => ["ru" => "Гватемала", "en" => "Guatemala"], "continent" => "NA"],
        "gu" => ["name" => ["ru" => "Гуам", "en" => "Guam"], "continent" => "OC"],
        "gw" => ["name" => ["ru" => "Гвинея-Бисау", "en" => "Guinea-Bissau"], "continent" => "AF"],
        "gy" => ["name" => ["ru" => "Гайана", "en" => "Guyana"], "continent" => "SA"],
        "hk" => ["name" => ["ru" => "Гонконг", "en" => "Hong Kong"], "continent" => "AS"],
        "hm" => ["name" => ["ru" => "О. Херд и о. Макдональд", "en" => "Heard Island and McDonald Islands"], "continent" => "AN"],
        "hn" => ["name" => ["ru" => "Гондурас", "en" => "Honduras"], "continent" => "NA"],
        "hr" => ["name" => ["ru" => "Хорватия", "en" => "Croatia"], "continent" => "EU"],
        "ht" => ["name" => ["ru" => "Гаити", "en" => "Haiti"], "continent" => "NA"],
        "hu" => ["name" => ["ru" => "Венгрия", "en" => "Hungary"], "continent" => "EU"],
        "id" => ["name" => ["ru" => "Индонезия", "en" => "Indonesia"], "continent" => "AS"],
        "ie" => ["name" => ["ru" => "Ирландия", "en" => "Ireland"], "continent" => "EU"],
        "il" => ["name" => ["ru" => "Израиль", "en" => "Israel"], "continent" => "AS"],
        "im" => ["name" => ["ru" => "Остров Мэн", "en" => "Isle of Man"], "continent" => "EU"],
        "in" => ["name" => ["ru" => "Индия", "en" => "India"], "continent" => "AS"],
        "io" => ["name" => ["ru" => "Британская т-я в И. о.", "en" => "British Indian Ocean Territory"], "continent" => "AS"],
        "iq" => ["name" => ["ru" => "Ирак", "en" => "Iraq"], "continent" => "AS"],
        "ir" => ["name" => ["ru" => "Иран, Исламская Рес-ка", "en" => "Iran"], "continent" => "AS"],
        "is" => ["name" => ["ru" => "Исландия", "en" => "Iceland"], "continent" => "EU"],
        "it" => ["name" => ["ru" => "Италия", "en" => "Italy"], "continent" => "EU"],
        "je" => ["name" => ["ru" => "Джерси", "en" => "Jersey"], "continent" => "EU"],
        "jm" => ["name" => ["ru" => "Ямайка", "en" => "Jamaica"], "continent" => "NA"],
        "jo" => ["name" => ["ru" => "Иордания", "en" => "Jordan"], "continent" => "AS"],
        "jp" => ["name" => ["ru" => "Япония", "en" => "Japan"], "continent" => "AS"],
        "ke" => ["name" => ["ru" => "Кения", "en" => "Kenya"], "continent" => "AF"],
        "kg" => ["name" => ["ru" => "Кыргызстан", "en" => "Kyrgyzstan"], "continent" => "AS"],
        "kh" => ["name" => ["ru" => "Камбоджа", "en" => "Cambodia"], "continent" => "AS"],
        "ki" => ["name" => ["ru" => "Кирибати", "en" => "Kiribati"], "continent" => "OC"],
        "km" => ["name" => ["ru" => "Коморы", "en" => "Comoros"], "continent" => "AF"],
        "kn" => ["name" => ["ru" => "Сент-Китс и Невис", "en" => "Saint Kitts and Nevis"], "continent" => "NA"],
        "kp" => ["name" => ["ru" => "Корея, Нар.-Дем. Рес-ка", "en" => "North Korea"], "continent" => "AS"],
        "kr" => ["name" => ["ru" => "Южная Корея", "en" => "South Korea"], "continent" => "AS"],
        "kw" => ["name" => ["ru" => "Кувейт", "en" => "Kuwait"], "continent" => "AS"],
        "ky" => ["name" => ["ru" => "Острова Кайман", "en" => "Cayman Islands"], "continent" => "NA"],
        "kz" => ["name" => ["ru" => "Казахстан", "en" => "Kazakhstan"], "continent" => "AS"],
        "la" => ["name" => ["ru" => "Лаос", "en" => "Laos"], "continent" => "AS"],
        "lb" => ["name" => ["ru" => "Ливан", "en" => "Lebanon"], "continent" => "AS"],
        "lc" => ["name" => ["ru" => "Сент-Люсия", "en" => "Saint Lucia"], "continent" => "NA"],
        "li" => ["name" => ["ru" => "Лихтенштейн", "en" => "Liechtenstein"], "continent" => "EU"],
        "lk" => ["name" => ["ru" => "Шри-Ланка", "en" => "Sri Lanka"], "continent" => "AS"],
        "lr" => ["name" => ["ru" => "Либерия", "en" => "Liberia"], "continent" => "AF"],
        "ls" => ["name" => ["ru" => "Лесото", "en" => "Lesotho"], "continent" => "AF"],
        "lt" => ["name" => ["ru" => "Литва", "en" => "Lithuania"], "continent" => "EU"],
        "lu" => ["name" => ["ru" => "Люксембург", "en" => "Luxembourg"], "continent" => "EU"],
        "lv" => ["name" => ["ru" => "Латвия", "en" => "Latvia"], "continent" => "EU"],
        "ly" => ["name" => ["ru" => "Ливия", "en" => "Libya"], "continent" => "AF"],
        "ma" => ["name" => ["ru" => "Марокко", "en" => "Morocco"], "continent" => "AF"],
        "mc" => ["name" => ["ru" => "Монако", "en" => "Monaco"], "continent" => "EU"],
        "md" => ["name" => ["ru" => "Молдова", "en" => "Moldova"], "continent" => "EU"],
        "me" => ["name" => ["ru" => "Черногория", "en" => "Montenegro"], "continent" => "EU"],
        "mf" => ["name" => ["ru" => "Сен-Мартен", "en" => "Saint Martin"], "continent" => "NA"],
        "mg" => ["name" => ["ru" => "Мадагаскар", "en" => "Madagascar"], "continent" => "AF"],
        "mh" => ["name" => ["ru" => "Маршалловы острова", "en" => "Marshall Islands"], "continent" => "OC"],
        "mk" => ["name" => ["ru" => "Македония", "en" => "Macedonia"], "continent" => "EU"],
        "ml" => ["name" => ["ru" => "Мали", "en" => "Mali"], "continent" => "AF"],
        "mm" => ["name" => ["ru" => "Мьянма", "en" => "Myanmar"], "continent" => "AS"],
        "mn" => ["name" => ["ru" => "Монголия", "en" => "Mongolia"], "continent" => "AS"],
        "mo" => ["name" => ["ru" => "Макао", "en" => "Macao"], "continent" => "AS"],
        "mp" => ["name" => ["ru" => "Сев. Марианские о-ва", "en" => "Northern Mariana Islands"], "continent" => "OC"],
        "mq" => ["name" => ["ru" => "Мартиника", "en" => "Martinique"], "continent" => "NA"],
        "mr" => ["name" => ["ru" => "Мавритания", "en" => "Mauritania"], "continent" => "AF"],
        "ms" => ["name" => ["ru" => "Монтсеррат", "en" => "Montserrat"], "continent" => "NA"],
        "mt" => ["name" => ["ru" => "Мальта", "en" => "Malta"], "continent" => "EU"],
        "mu" => ["name" => ["ru" => "Маврикий", "en" => "Mauritius"], "continent" => "AF"],
        "mv" => ["name" => ["ru" => "Мальдивы", "en" => "Maldives"], "continent" => "AS"],
        "mw" => ["name" => ["ru" => "Малави", "en" => "Malawi"], "continent" => "AF"],
        "mx" => ["name" => ["ru" => "Мексика", "en" => "Mexico"], "continent" => "NA"],
        "my" => ["name" => ["ru" => "Малайзия", "en" => "Malaysia"], "continent" => "AS"],
        "mz" => ["name" => ["ru" => "Мозамбик", "en" => "Mozambique"], "continent" => "AF"],
        "na" => ["name" => ["ru" => "Намибия", "en" => "Namibia"], "continent" => "AF"],
        "nc" => ["name" => ["ru" => "Новая Каледония", "en" => "New Caledonia"], "continent" => "OC"],
        "ne" => ["name" => ["ru" => "Нигер", "en" => "Niger"], "continent" => "AF"],
        "nf" => ["name" => ["ru" => "Остров Норфолк", "en" => "Norfolk Island"], "continent" => "OC"],
        "ng" => ["name" => ["ru" => "Нигерия", "en" => "Nigeria"], "continent" => "AF"],
        "ni" => ["name" => ["ru" => "Никарагуа", "en" => "Nicaragua"], "continent" => "NA"],
        "nl" => ["name" => ["ru" => "Нидерланды", "en" => "Netherlands"], "continent" => "EU"],
        "no" => ["name" => ["ru" => "Норвегия", "en" => "Norway"], "continent" => "EU"],
        "np" => ["name" => ["ru" => "Непал", "en" => "Nepal"], "continent" => "AS"],
        "nr" => ["name" => ["ru" => "Науру", "en" => "Nauru"], "continent" => "OC"],
        "nu" => ["name" => ["ru" => "Ниуэ", "en" => "Niue"], "continent" => "OC"],
        "nz" => ["name" => ["ru" => "Новая Зеландия", "en" => "New Zealand"], "continent" => "OC"],
        "om" => ["name" => ["ru" => "Оман", "en" => "Oman"], "continent" => "AS"],
        "pa" => ["name" => ["ru" => "Панама", "en" => "Panama"], "continent" => "NA"],
        "pe" => ["name" => ["ru" => "Перу", "en" => "Peru"], "continent" => "SA"],
        "pf" => ["name" => ["ru" => "Французская Полинезия", "en" => "French Polynesia"], "continent" => "OC"],
        "pg" => ["name" => ["ru" => "Папуа-Новая Гвинея", "en" => "Papua New Guinea"], "continent" => "OC"],
        "ph" => ["name" => ["ru" => "Филиппины", "en" => "Philippines"], "continent" => "AS"],
        "pk" => ["name" => ["ru" => "Пакистан", "en" => "Pakistan"], "continent" => "AS"],
        "pl" => ["name" => ["ru" => "Польша", "en" => "Poland"], "continent" => "EU"],
        "pm" => ["name" => ["ru" => "Сент-Пьер и Микелон", "en" => "Saint Pierre and Miquelon"], "continent" => "NA"],
        "pn" => ["name" => ["ru" => "Питкерн", "en" => "Pitcairn"], "continent" => "OC"],
        "pr" => ["name" => ["ru" => "Пуэрто-Рико", "en" => "Puerto Rico"], "continent" => "NA"],
        "ps" => ["name" => ["ru" => "Палестинская тер-ия", "en" => "Palestinian Territory"], "continent" => "AS"],
        "pt" => ["name" => ["ru" => "Португалия", "en" => "Portugal"], "continent" => "EU"],
        "pw" => ["name" => ["ru" => "Палау", "en" => "Palau"], "continent" => "OC"],
        "py" => ["name" => ["ru" => "Парагвай", "en" => "Paraguay"], "continent" => "SA"],
        "qa" => ["name" => ["ru" => "Катар", "en" => "Qatar"], "continent" => "AS"],
        "re" => ["name" => ["ru" => "Реюньон", "en" => "Reunion"], "continent" => "AF"],
        "ro" => ["name" => ["ru" => "Румыния", "en" => "Romania"], "continent" => "EU"],
        "rs" => ["name" => ["ru" => "Сербия", "en" => "Serbia"], "continent" => "EU"],
        "ru" => ["name" => ["ru" => "Россия", "en" => "Russia"], "continent" => "EU"],
        "rw" => ["name" => ["ru" => "Руанда", "en" => "Rwanda"], "continent" => "AF"],
        "sa" => ["name" => ["ru" => "Саудовская Аравия", "en" => "Saudi Arabia"], "continent" => "AS"],
        "sb" => ["name" => ["ru" => "Соломоновы острова", "en" => "Solomon Islands"], "continent" => "OC"],
        "sc" => ["name" => ["ru" => "Сейшелы", "en" => "Seychelles"], "continent" => "AF"],
        "sd" => ["name" => ["ru" => "Судан", "en" => "Sudan"], "continent" => "AF"],
        "se" => ["name" => ["ru" => "Швеция", "en" => "Sweden"], "continent" => "EU"],
        "sg" => ["name" => ["ru" => "Сингапур", "en" => "Singapore"], "continent" => "AS"],
        "sh" => ["name" => ["ru" => "Святая Елена", "en" => "Saint Helena"], "continent" => "AF"],
        "si" => ["name" => ["ru" => "Словения", "en" => "Slovenia"], "continent" => "EU"],
        "sj" => ["name" => ["ru" => "Шпицберген и Ян Майен", "en" => "Svalbard and Jan Mayen"], "continent" => "EU"],
        "sk" => ["name" => ["ru" => "Словакия", "en" => "Slovakia"], "continent" => "EU"],
        "sl" => ["name" => ["ru" => "Сьерра-Леоне", "en" => "Sierra Leone"], "continent" => "AF"],
        "sm" => ["name" => ["ru" => "Сан-Марино", "en" => "San Marino"], "continent" => "EU"],
        "sn" => ["name" => ["ru" => "Сенегал", "en" => "Senegal"], "continent" => "AF"],
        "so" => ["name" => ["ru" => "Сомали", "en" => "Somalia"], "continent" => "AF"],
        "sr" => ["name" => ["ru" => "Суринам", "en" => "Suriname"], "continent" => "SA"],
        "ss" => ["name" => ["ru" => "Южный Судан", "en" => "South Sudan"], "continent" => "AF"],
        "st" => ["name" => ["ru" => "Сан-Томе и Принсипи", "en" => "Sao Tome and Principe"], "continent" => "AF"],
        "sv" => ["name" => ["ru" => "Эль-Сальвадор", "en" => "El Salvador"], "continent" => "NA"],
        "sx" => ["name" => ["ru" => "Синт-Мартен", "en" => "Sint Maarten"], "continent" => "NA"],
        "sy" => ["name" => ["ru" => "Сирия", "en" => "Syria"], "continent" => "AS"],
        "sz" => ["name" => ["ru" => "Свазиленд", "en" => "Swaziland"], "continent" => "AF"],
        "tc" => ["name" => ["ru" => "Острова Теркс и Кайкос", "en" => "Turks and Caicos Islands"], "continent" => "NA"],
        "td" => ["name" => ["ru" => "Чад", "en" => "Chad"], "continent" => "AF"],
        "tf" => ["name" => ["ru" => "Французские Юж. тер.", "en" => "French Southern Territories"], "continent" => "AN"],
        "tg" => ["name" => ["ru" => "Того", "en" => "Togo"], "continent" => "AF"],
        "th" => ["name" => ["ru" => "Таиланд", "en" => "Thailand"], "continent" => "AS"],
        "tj" => ["name" => ["ru" => "Таджикистан", "en" => "Tajikistan"], "continent" => "AS"],
        "tk" => ["name" => ["ru" => "Токелау", "en" => "Tokelau"], "continent" => "OC"],
        "tl" => ["name" => ["ru" => "Тимор-Лесте", "en" => "East Timor"], "continent" => "OC"],
        "tm" => ["name" => ["ru" => "Туркменистан", "en" => "Turkmenistan"], "continent" => "AS"],
        "tn" => ["name" => ["ru" => "Тунис", "en" => "Tunisia"], "continent" => "AF"],
        "to" => ["name" => ["ru" => "Тонга", "en" => "Tonga"], "continent" => "OC"],
        "tr" => ["name" => ["ru" => "Турция", "en" => "Turkey"], "continent" => "AS"],
        "tt" => ["name" => ["ru" => "Тринидад и Тобаго", "en" => "Trinidad and Tobago"], "continent" => "NA"],
        "tv" => ["name" => ["ru" => "Тувалу", "en" => "Tuvalu"], "continent" => "OC"],
        "tw" => ["name" => ["ru" => "Тайвань (Китай)", "en" => "Taiwan"], "continent" => "AS"],
        "tz" => ["name" => ["ru" => "Танзания", "en" => "Tanzania"], "continent" => "AF"],
        "ua" => ["name" => ["ru" => "Украина", "en" => "Ukraine"], "continent" => "EU"],
        "ug" => ["name" => ["ru" => "Уганда", "en" => "Uganda"], "continent" => "AF"],
        "um" => ["name" => ["ru" => "Мал. Тихоокеанские отд. о-ва США", "en" => "United States Minor Outlying Islands"], "continent" => "OC"],
        "us" => ["name" => ["ru" => "Соединенные Штаты", "en" => "United States"], "continent" => "NA"],
        "uy" => ["name" => ["ru" => "Уругвай", "en" => "Uruguay"], "continent" => "SA"],
        "uz" => ["name" => ["ru" => "Узбекистан", "en" => "Uzbekistan"], "continent" => "AS"],
        "va" => ["name" => ["ru" => "Ватикан", "en" => "Vatican"], "continent" => "EU"],
        "vc" => ["name" => ["ru" => "Сент-Винсент и Гренад.", "en" => "Saint Vincent and the Grenadines"], "continent" => "NA"],
        "ve" => ["name" => ["ru" => "Венесуэла", "en" => "Venezuela"], "continent" => "SA"],
        "vg" => ["name" => ["ru" => "Виргинские о-ва, Брит.", "en" => "British Virgin Islands"], "continent" => "NA"],
        "vi" => ["name" => ["ru" => "Виргинские о-ва, США", "en" => "U.S. Virgin Islands"], "continent" => "NA"],
        "vn" => ["name" => ["ru" => "Вьетнам", "en" => "Vietnam"], "continent" => "AS"],
        "vu" => ["name" => ["ru" => "Вануату", "en" => "Vanuatu"], "continent" => "OC"],
        "wf" => ["name" => ["ru" => "Уоллис и Футуна", "en" => "Wallis and Futuna"], "continent" => "OC"],
        "ws" => ["name" => ["ru" => "Самоа", "en" => "Samoa"], "continent" => "OC"],
        "xk" => ["name" => ["ru" => "Косово", "en" => "Kosovo"], "continent" => "EU"],
        "ye" => ["name" => ["ru" => "Йемен", "en" => "Yemen"], "continent" => "AS"],
        "yt" => ["name" => ["ru" => "Майотта", "en" => "Mayotte"], "continent" => "AF"],
        "za" => ["name" => ["ru" => "Южная Африка", "en" => "South Africa"], "continent" => "AF"],
        "zm" => ["name" => ["ru" => "Замбия", "en" => "Zambia"], "continent" => "AF"],
        "zw" => ["name" => ["ru" => "Зимбабве", "en" => "Zimbabwe"], "continent" => "AF"]
    ];

    /**
     * Массив континентов и их названий на двух языках.
     *
     * @var array
     */
    private static $continents = [
        "AF" => ["ru" => "Африка", "en" => "Africa"],
        "AN" => ["ru" => "Антарктида", "en" => "Antarctica"],
        "AS" => ["ru" => "Азия", "en" => "Asia"],
        "EU" => ["ru" => "Европа", "en" => "Europe"],
        "NA" => ["ru" => "Северная Америка", "en" => "North America"],
        "OC" => ["ru" => "Океания", "en" => "Oceania"],
        "SA" => ["ru" => "Южная Америка", "en" => "South America"],
    ];

    /**
     * Код региона (страны).
     *
     * @var string
     */
    private static $regionCode = '';

    /**
     * Установить код региона (страны).
     *
     * @param string $code Код региона.
     *
     * @return void
     */
    public static function setRegionCode($code)
    {
        self::$regionCode = strtolower($code);
    }

    /**
     * Получить название континента в заданной локали.
     *
     * @param string $locale Локаль (ru или en).
     *
     * @return string
     */
    public static function getContinentName($locale = 'ru')
    {
        $continent_code = self::getContinentCode();

        if ($continent_code == '') {
            return '';
        }

        return self::$continents[$continent_code][$locale] ?? '';
    }

    /**
     * Получить код континента.
     *
     * @return string
     */
    public static function getContinentCode()
    {
        if (!isset(self::$countries[self::$regionCode])) {
            return '';
        }

        return self::$countries[self::$regionCode]['continent'];
    }

    /**
     * Получить название страны в заданной локали.
     *
     * @param string $locale Локаль (ru или en).
     *
     * @return string
     */
    public static function getCountryName($locale = 'ru')
    {
        return self::$countries[self::$regionCode]['name'][$locale] ?? '';
    }

    /**
     * Получить эмодзи флага страны в верхнем регистре.
     *
     * @return string
     * 
     * @throws InvalidArgumentException если код страны неверного формата.
     */
    public static function getFlagEmoji(): string
    {
        $country_code = self::$regionCode;
        $country_code = strtoupper($country_code);

        return
            (string) preg_replace_callback(
                "/./",
                static fn (array $letter) => mb_chr(
                    (ord($letter[0]) % 32) + 0x1f1e5
                ),
                $country_code
            );
    }
}
