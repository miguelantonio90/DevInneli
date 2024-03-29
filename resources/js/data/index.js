// const language = Vuetify.framework.lang
const continents = {
  AF: 'Africa',
  AN: 'Antarctica',
  AS: 'Asia',
  EU: 'Europe',
  NA: 'North America',
  OC: 'Oceania',
  SA: 'South America'
}
const countries = {
  AD: {
    name: 'Andorra',
    native: 'Andorra',
    code: '376',
    continent: 'EU',
    capital: 'Andorra la Vella',
    currency: 'EUR',
    languages: ['ca'],
    emoji: '🇦🇩',
    emojiU: 'U+1F1E6 U+1F1E9'
  },
  AE: {
    name: 'United Arab Emirates',
    native: 'دولة الإمارات العربية المتحدة',
    code: '971',
    continent: 'AS',
    capital: 'Abu Dhabi',
    currency: 'AED',
    languages: ['ar'],
    emoji: '🇦🇪',
    emojiU: 'U+1F1E6 U+1F1EA'
  },
  AF: {
    name: 'Afghanistan',
    native: 'افغانستان',
    code: '93',
    continent: 'AS',
    capital: 'Kabul',
    currency: 'AFN',
    languages: ['ps', 'uz', 'tk'],
    emoji: '🇦🇫',
    emojiU: 'U+1F1E6 U+1F1EB'
  },
  AG: {
    name: 'Antigua and Barbuda',
    native: 'Antigua and Barbuda',
    code: '1268',
    continent: 'NA',
    capital: 'Saint John\'s',
    currency: 'XCD',
    languages: ['en'],
    emoji: '🇦🇬',
    emojiU: 'U+1F1E6 U+1F1EC'
  },
  AI: {
    name: 'Anguilla',
    native: 'Anguilla',
    code: '1264',
    continent: 'NA',
    capital: 'The Valley',
    currency: 'XCD',
    languages: ['en'],
    emoji: '🇦🇮',
    emojiU: 'U+1F1E6 U+1F1EE'
  },
  AL: {
    name: 'Albania',
    native: 'Shqipëria',
    code: '355',
    continent: 'EU',
    capital: 'Tirana',
    currency: 'ALL',
    languages: ['sq'],
    emoji: '🇦🇱',
    emojiU: 'U+1F1E6 U+1F1F1'
  },
  AM: {
    name: 'Armenia',
    native: 'Հայաստան',
    code: '374',
    continent: 'AS',
    capital: 'Yerevan',
    currency: 'AMD',
    languages: ['hy', 'ru'],
    emoji: '🇦🇲',
    emojiU: 'U+1F1E6 U+1F1F2'
  },
  AO: {
    name: 'Angola',
    native: 'Angola',
    code: '244',
    continent: 'AF',
    capital: 'Luanda',
    currency: 'AOA',
    languages: ['pt'],
    emoji: '🇦🇴',
    emojiU: 'U+1F1E6 U+1F1F4'
  },
  AQ: {
    name: 'Antarctica',
    native: 'Antarctica',
    code: '672',
    continent: 'AN',
    capital: '',
    currency: '',
    languages: [],
    emoji: '🇦🇶',
    emojiU: 'U+1F1E6 U+1F1F6'
  },
  AR: {
    name: 'Argentina',
    native: 'Argentina',
    code: '54',
    continent: 'SA',
    capital: 'Buenos Aires',
    currency: 'ARS',
    languages: ['es', 'gn'],
    emoji: '🇦🇷',
    emojiU: 'U+1F1E6 U+1F1F7'
  },
  AS: {
    name: 'American Samoa',
    native: 'American Samoa',
    code: '1684',
    continent: 'OC',
    capital: 'Pago Pago',
    currency: 'USD',
    languages: ['en', 'sm'],
    emoji: '🇦🇸',
    emojiU: 'U+1F1E6 U+1F1F8'
  },
  AT: {
    name: 'Austria',
    native: 'Österreich',
    code: '43',
    continent: 'EU',
    capital: 'Vienna',
    currency: 'EUR',
    languages: ['de'],
    emoji: '🇦🇹',
    emojiU: 'U+1F1E6 U+1F1F9'
  },
  AU: {
    name: 'Australia',
    native: 'Australia',
    code: '61',
    continent: 'OC',
    capital: 'Canberra',
    currency: 'AUD',
    languages: ['en'],
    emoji: '🇦🇺',
    emojiU: 'U+1F1E6 U+1F1FA'
  },
  AW: {
    name: 'Aruba',
    native: 'Aruba',
    code: '297',
    continent: 'NA',
    capital: 'Oranjestad',
    currency: 'AWG',
    languages: ['nl', 'pa'],
    emoji: '🇦🇼',
    emojiU: 'U+1F1E6 U+1F1FC'
  },
  AX: {
    name: 'Åland',
    native: 'Åland',
    code: '358',
    continent: 'EU',
    capital: 'Mariehamn',
    currency: 'EUR',
    languages: ['sv'],
    emoji: '🇦🇽',
    emojiU: 'U+1F1E6 U+1F1FD'
  },
  AZ: {
    name: 'Azerbaijan',
    native: 'Azərbaycan',
    code: '994',
    continent: 'AS',
    capital: 'Baku',
    currency: 'AZN',
    languages: ['az'],
    emoji: '🇦🇿',
    emojiU: 'U+1F1E6 U+1F1FF'
  },
  BA: {
    name: 'Bosnia and Herzegovina',
    native: 'Bosna i Hercegovina',
    code: '387',
    continent: 'EU',
    capital: 'Sarajevo',
    currency: 'BAM',
    languages: ['bs', 'hr', 'sr'],
    emoji: '🇧🇦',
    emojiU: 'U+1F1E7 U+1F1E6'
  },
  BB: {
    name: 'Barbados',
    native: 'Barbados',
    code: '1246',
    continent: 'NA',
    capital: 'Bridgetown',
    currency: 'BBD',
    languages: ['en'],
    emoji: '🇧🇧',
    emojiU: 'U+1F1E7 U+1F1E7'
  },
  BD: {
    name: 'Bangladesh',
    native: 'Bangladesh',
    code: '880',
    continent: 'AS',
    capital: 'Dhaka',
    currency: 'BDT',
    languages: ['bn'],
    emoji: '🇧🇩',
    emojiU: 'U+1F1E7 U+1F1E9'
  },
  BE: {
    name: 'Belgium',
    native: 'België',
    code: '32',
    continent: 'EU',
    capital: 'Brussels',
    currency: 'EUR',
    languages: ['nl', 'fr', 'de'],
    emoji: '🇧🇪',
    emojiU: 'U+1F1E7 U+1F1EA'
  },
  BF: {
    name: 'Burkina Faso',
    native: 'Burkina Faso',
    code: '226',
    continent: 'AF',
    capital: 'Ouagadougou',
    currency: 'XOF',
    languages: ['fr', 'ff'],
    emoji: '🇧🇫',
    emojiU: 'U+1F1E7 U+1F1EB'
  },
  BG: {
    name: 'Bulgaria',
    native: 'България',
    code: '359',
    continent: 'EU',
    capital: 'Sofia',
    currency: 'BGN',
    languages: ['bg'],
    emoji: '🇧🇬',
    emojiU: 'U+1F1E7 U+1F1EC'
  },
  BH: {
    name: 'Bahrain',
    native: '‏البحرين',
    code: '973',
    continent: 'AS',
    capital: 'Manama',
    currency: 'BHD',
    languages: ['ar'],
    emoji: '🇧🇭',
    emojiU: 'U+1F1E7 U+1F1ED'
  },
  BI: {
    name: 'Burundi',
    native: 'Burundi',
    code: '257',
    continent: 'AF',
    capital: 'Bujumbura',
    currency: 'BIF',
    languages: ['fr', 'rn'],
    emoji: '🇧🇮',
    emojiU: 'U+1F1E7 U+1F1EE'
  },
  BJ: {
    name: 'Benin',
    native: 'Bénin',
    code: '229',
    continent: 'AF',
    capital: 'Porto-Novo',
    currency: 'XOF',
    languages: ['fr'],
    emoji: '🇧🇯',
    emojiU: 'U+1F1E7 U+1F1EF'
  },
  BL: {
    name: 'Saint Barthélemy',
    native: 'Saint-Barthélemy',
    code: '590',
    continent: 'NA',
    capital: 'Gustavia',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇧🇱',
    emojiU: 'U+1F1E7 U+1F1F1'
  },
  BM: {
    name: 'Bermuda',
    native: 'Bermuda',
    code: '1441',
    continent: 'NA',
    capital: 'Hamilton',
    currency: 'BMD',
    languages: ['en'],
    emoji: '🇧🇲',
    emojiU: 'U+1F1E7 U+1F1F2'
  },
  BN: {
    name: 'Brunei',
    native: 'Negara Brunei Darussalam',
    code: '673',
    continent: 'AS',
    capital: 'Bandar Seri Begawan',
    currency: 'BND',
    languages: ['ms'],
    emoji: '🇧🇳',
    emojiU: 'U+1F1E7 U+1F1F3'
  },
  BO: {
    name: 'Bolivia',
    native: 'Bolivia',
    code: '591',
    continent: 'SA',
    capital: 'Sucre',
    currency: 'BOB,BOV',
    languages: ['es', 'ay', 'qu'],
    emoji: '🇧🇴',
    emojiU: 'U+1F1E7 U+1F1F4'
  },
  BQ: {
    name: 'Bonaire',
    native: 'Bonaire',
    code: '5997',
    continent: 'NA',
    capital: 'Kralendijk',
    currency: 'USD',
    languages: ['nl'],
    emoji: '🇧🇶',
    emojiU: 'U+1F1E7 U+1F1F6'
  },
  BR: {
    name: 'Brazil',
    native: 'Brasil',
    code: '55',
    continent: 'SA',
    capital: 'Brasília',
    currency: 'BRL',
    languages: ['pt'],
    emoji: '🇧🇷',
    emojiU: 'U+1F1E7 U+1F1F7'
  },
  BS: {
    name: 'Bahamas',
    native: 'Bahamas',
    code: '1242',
    continent: 'NA',
    capital: 'Nassau',
    currency: 'BSD',
    languages: ['en'],
    emoji: '🇧🇸',
    emojiU: 'U+1F1E7 U+1F1F8'
  },
  BT: {
    name: 'Bhutan',
    native: 'ʼbrug-yul',
    code: '975',
    continent: 'AS',
    capital: 'Thimphu',
    currency: 'BTN,INR',
    languages: ['dz'],
    emoji: '🇧🇹',
    emojiU: 'U+1F1E7 U+1F1F9'
  },
  BV: {
    name: 'Bouvet Island',
    native: 'Bouvetøya',
    code: '47',
    continent: 'AN',
    capital: '',
    currency: 'NOK',
    languages: ['no', 'nb', 'nn'],
    emoji: '🇧🇻',
    emojiU: 'U+1F1E7 U+1F1FB'
  },
  BW: {
    name: 'Botswana',
    native: 'Botswana',
    code: '267',
    continent: 'AF',
    capital: 'Gaborone',
    currency: 'BWP',
    languages: ['en', 'tn'],
    emoji: '🇧🇼',
    emojiU: 'U+1F1E7 U+1F1FC'
  },
  BY: {
    name: 'Belarus',
    native: 'Белару́сь',
    code: '375',
    continent: 'EU',
    capital: 'Minsk',
    currency: 'BYN',
    languages: ['be', 'ru'],
    emoji: '🇧🇾',
    emojiU: 'U+1F1E7 U+1F1FE'
  },
  BZ: {
    name: 'Belize',
    native: 'Belize',
    code: '501',
    continent: 'NA',
    capital: 'Belmopan',
    currency: 'BZD',
    languages: ['en', 'es'],
    emoji: '🇧🇿',
    emojiU: 'U+1F1E7 U+1F1FF'
  },
  CA: {
    name: 'Canada',
    native: 'Canada',
    code: '1',
    continent: 'NA',
    capital: 'Ottawa',
    currency: 'CAD',
    languages: ['en', 'fr'],
    emoji: '🇨🇦',
    emojiU: 'U+1F1E8 U+1F1E6'
  },
  CC: {
    name: 'Cocos [Keeling] Islands',
    native: 'Cocos (Keeling) Islands',
    code: '61',
    continent: 'AS',
    capital: 'West Island',
    currency: 'AUD',
    languages: ['en'],
    emoji: '🇨🇨',
    emojiU: 'U+1F1E8 U+1F1E8'
  },
  CD: {
    name: 'Democratic Republic of the Congo',
    native: 'République démocratique du Congo',
    code: '243',
    continent: 'AF',
    capital: 'Kinshasa',
    currency: 'CDF',
    languages: ['fr', 'ln', 'kg', 'sw', 'lu'],
    emoji: '🇨🇩',
    emojiU: 'U+1F1E8 U+1F1E9'
  },
  CF: {
    name: 'Central African Republic',
    native: 'Ködörösêse tî Bêafrîka',
    code: '236',
    continent: 'AF',
    capital: 'Bangui',
    currency: 'XAF',
    languages: ['fr', 'sg'],
    emoji: '🇨🇫',
    emojiU: 'U+1F1E8 U+1F1EB'
  },
  CG: {
    name: 'Republic of the Congo',
    native: 'République du Congo',
    code: '242',
    continent: 'AF',
    capital: 'Brazzaville',
    currency: 'XAF',
    languages: ['fr', 'ln'],
    emoji: '🇨🇬',
    emojiU: 'U+1F1E8 U+1F1EC'
  },
  CH: {
    name: 'Switzerland',
    native: 'Schweiz',
    code: '41',
    continent: 'EU',
    capital: 'Bern',
    currency: 'CHE,CHF,CHW',
    languages: ['de', 'fr', 'it'],
    emoji: '🇨🇭',
    emojiU: 'U+1F1E8 U+1F1ED'
  },
  CI: {
    name: 'Ivory Coast',
    native: 'Côte d\'Ivoire',
    code: '225',
    continent: 'AF',
    capital: 'Yamoussoukro',
    currency: 'XOF',
    languages: ['fr'],
    emoji: '🇨🇮',
    emojiU: 'U+1F1E8 U+1F1EE'
  },
  CK: {
    name: 'Cook Islands',
    native: 'Cook Islands',
    code: '682',
    continent: 'OC',
    capital: 'Avarua',
    currency: 'NZD',
    languages: ['en'],
    emoji: '🇨🇰',
    emojiU: 'U+1F1E8 U+1F1F0'
  },
  CL: {
    name: 'Chile',
    native: 'Chile',
    code: '56',
    continent: 'SA',
    capital: 'Santiago',
    currency: 'CLF,CLP',
    languages: ['es'],
    emoji: '🇨🇱',
    emojiU: 'U+1F1E8 U+1F1F1'
  },
  CM: {
    name: 'Cameroon',
    native: 'Cameroon',
    code: '237',
    continent: 'AF',
    capital: 'Yaoundé',
    currency: 'XAF',
    languages: ['en', 'fr'],
    emoji: '🇨🇲',
    emojiU: 'U+1F1E8 U+1F1F2'
  },
  CN: {
    name: 'China',
    native: '中国',
    code: '86',
    continent: 'AS',
    capital: 'Beijing',
    currency: 'CNY',
    languages: ['zh'],
    emoji: '🇨🇳',
    emojiU: 'U+1F1E8 U+1F1F3'
  },
  CO: {
    name: 'Colombia',
    native: 'Colombia',
    code: '57',
    continent: 'SA',
    capital: 'Bogotá',
    currency: 'COP',
    languages: ['es'],
    emoji: '🇨🇴',
    emojiU: 'U+1F1E8 U+1F1F4'
  },
  CR: {
    name: 'Costa Rica',
    native: 'Costa Rica',
    code: '506',
    continent: 'NA',
    capital: 'San José',
    currency: 'CRC',
    languages: ['es'],
    emoji: '🇨🇷',
    emojiU: 'U+1F1E8 U+1F1F7'
  },
  CU: {
    name: 'Cuba',
    native: 'Cuba',
    code: '53',
    continent: 'NA',
    capital: 'Havana',
    currency: 'CUP,CUC',
    languages: ['es'],
    emoji: '🇨🇺',
    emojiU: 'U+1F1E8 U+1F1FA'
  },
  CV: {
    name: 'Cape Verde',
    native: 'Cabo Verde',
    code: '238',
    continent: 'AF',
    capital: 'Praia',
    currency: 'CVE',
    languages: ['pt'],
    emoji: '🇨🇻',
    emojiU: 'U+1F1E8 U+1F1FB'
  },
  CW: {
    name: 'Curacao',
    native: 'Curaçao',
    code: '5999',
    continent: 'NA',
    capital: 'Willemstad',
    currency: 'ANG',
    languages: ['nl', 'pa', 'en'],
    emoji: '🇨🇼',
    emojiU: 'U+1F1E8 U+1F1FC'
  },
  CX: {
    name: 'Christmas Island',
    native: 'Christmas Island',
    code: '61',
    continent: 'AS',
    capital: 'Flying Fish Cove',
    currency: 'AUD',
    languages: ['en'],
    emoji: '🇨🇽',
    emojiU: 'U+1F1E8 U+1F1FD'
  },
  CY: {
    name: 'Cyprus',
    native: 'Κύπρος',
    code: '357',
    continent: 'EU',
    capital: 'Nicosia',
    currency: 'EUR',
    languages: ['el', 'tr', 'hy'],
    emoji: '🇨🇾',
    emojiU: 'U+1F1E8 U+1F1FE'
  },
  CZ: {
    name: 'Czech Republic',
    native: 'Česká republika',
    code: '420',
    continent: 'EU',
    capital: 'Prague',
    currency: 'CZK',
    languages: ['cs', 'sk'],
    emoji: '🇨🇿',
    emojiU: 'U+1F1E8 U+1F1FF'
  },
  DE: {
    name: 'Germany',
    native: 'Deutschland',
    code: '49',
    continent: 'EU',
    capital: 'Berlin',
    currency: 'EUR',
    languages: ['de'],
    emoji: '🇩🇪',
    emojiU: 'U+1F1E9 U+1F1EA'
  },
  DJ: {
    name: 'Djibouti',
    native: 'Djibouti',
    code: '253',
    continent: 'AF',
    capital: 'Djibouti',
    currency: 'DJF',
    languages: ['fr', 'ar'],
    emoji: '🇩🇯',
    emojiU: 'U+1F1E9 U+1F1EF'
  },
  DK: {
    name: 'Denmark',
    native: 'Danmark',
    code: '45',
    continent: 'EU',
    capital: 'Copenhagen',
    currency: 'DKK',
    languages: ['da'],
    emoji: '🇩🇰',
    emojiU: 'U+1F1E9 U+1F1F0'
  },
  DM: {
    name: 'Dominica',
    native: 'Dominica',
    code: '1767',
    continent: 'NA',
    capital: 'Roseau',
    currency: 'XCD',
    languages: ['en'],
    emoji: '🇩🇲',
    emojiU: 'U+1F1E9 U+1F1F2'
  },
  DO: {
    name: 'Dominican Republic',
    native: 'República Dominicana',
    code: '1809,1829,1849',
    continent: 'NA',
    capital: 'Santo Domingo',
    currency: 'DOP',
    languages: ['es'],
    emoji: '🇩🇴',
    emojiU: 'U+1F1E9 U+1F1F4'
  },
  DZ: {
    name: 'Algeria',
    native: 'الجزائر',
    code: '213',
    continent: 'AF',
    capital: 'Algiers',
    currency: 'DZD',
    languages: ['ar'],
    emoji: '🇩🇿',
    emojiU: 'U+1F1E9 U+1F1FF'
  },
  EC: {
    name: 'Ecuador',
    native: 'Ecuador',
    code: '593',
    continent: 'SA',
    capital: 'Quito',
    currency: 'USD',
    languages: ['es'],
    emoji: '🇪🇨',
    emojiU: 'U+1F1EA U+1F1E8'
  },
  EE: {
    name: 'Estonia',
    native: 'Eesti',
    code: '372',
    continent: 'EU',
    capital: 'Tallinn',
    currency: 'EUR',
    languages: ['et'],
    emoji: '🇪🇪',
    emojiU: 'U+1F1EA U+1F1EA'
  },
  EG: {
    name: 'Egypt',
    native: 'مصر‎',
    code: '20',
    continent: 'AF',
    capital: 'Cairo',
    currency: 'EGP',
    languages: ['ar'],
    emoji: '🇪🇬',
    emojiU: 'U+1F1EA U+1F1EC'
  },
  EH: {
    name: 'Western Sahara',
    native: 'الصحراء الغربية',
    code: '212',
    continent: 'AF',
    capital: 'El Aaiún',
    currency: 'MAD,DZD,MRU',
    languages: ['es'],
    emoji: '🇪🇭',
    emojiU: 'U+1F1EA U+1F1ED'
  },
  ER: {
    name: 'Eritrea',
    native: 'ኤርትራ',
    code: '291',
    continent: 'AF',
    capital: 'Asmara',
    currency: 'ERN',
    languages: ['ti', 'ar', 'en'],
    emoji: '🇪🇷',
    emojiU: 'U+1F1EA U+1F1F7'
  },
  ES: {
    name: 'Spain',
    native: 'España',
    code: '34',
    continent: 'EU',
    capital: 'Madrid',
    currency: 'EUR',
    languages: ['es', 'eu', 'ca', 'gl', 'oc'],
    emoji: '🇪🇸',
    emojiU: 'U+1F1EA U+1F1F8'
  },
  ET: {
    name: 'Ethiopia',
    native: 'ኢትዮጵያ',
    code: '251',
    continent: 'AF',
    capital: 'Addis Ababa',
    currency: 'ETB',
    languages: ['am'],
    emoji: '🇪🇹',
    emojiU: 'U+1F1EA U+1F1F9'
  },
  FI: {
    name: 'Finland',
    native: 'Suomi',
    code: '358',
    continent: 'EU',
    capital: 'Helsinki',
    currency: 'EUR',
    languages: ['fi', 'sv'],
    emoji: '🇫🇮',
    emojiU: 'U+1F1EB U+1F1EE'
  },
  FJ: {
    name: 'Fiji',
    native: 'Fiji',
    code: '679',
    continent: 'OC',
    capital: 'Suva',
    currency: 'FJD',
    languages: ['en', 'fj', 'hi', 'ur'],
    emoji: '🇫🇯',
    emojiU: 'U+1F1EB U+1F1EF'
  },
  FK: {
    name: 'Falkland Islands',
    native: 'Falkland Islands',
    code: '500',
    continent: 'SA',
    capital: 'Stanley',
    currency: 'FKP',
    languages: ['en'],
    emoji: '🇫🇰',
    emojiU: 'U+1F1EB U+1F1F0'
  },
  FM: {
    name: 'Micronesia',
    native: 'Micronesia',
    code: '691',
    continent: 'OC',
    capital: 'Palikir',
    currency: 'USD',
    languages: ['en'],
    emoji: '🇫🇲',
    emojiU: 'U+1F1EB U+1F1F2'
  },
  FO: {
    name: 'Faroe Islands',
    native: 'Føroyar',
    code: '298',
    continent: 'EU',
    capital: 'Tórshavn',
    currency: 'DKK',
    languages: ['fo'],
    emoji: '🇫🇴',
    emojiU: 'U+1F1EB U+1F1F4'
  },
  FR: {
    name: 'France',
    native: 'France',
    code: '33',
    continent: 'EU',
    capital: 'Paris',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇫🇷',
    emojiU: 'U+1F1EB U+1F1F7'
  },
  GA: {
    name: 'Gabon',
    native: 'Gabon',
    code: '241',
    continent: 'AF',
    capital: 'Libreville',
    currency: 'XAF',
    languages: ['fr'],
    emoji: '🇬🇦',
    emojiU: 'U+1F1EC U+1F1E6'
  },
  GB: {
    name: 'United Kingdom',
    native: 'United Kingdom',
    code: '44',
    continent: 'EU',
    capital: 'London',
    currency: 'GBP',
    languages: ['en'],
    emoji: '🇬🇧',
    emojiU: 'U+1F1EC U+1F1E7'
  },
  GD: {
    name: 'Grenada',
    native: 'Grenada',
    code: '1473',
    continent: 'NA',
    capital: 'St. George\'s',
    currency: 'XCD',
    languages: ['en'],
    emoji: '🇬🇩',
    emojiU: 'U+1F1EC U+1F1E9'
  },
  GE: {
    name: 'Georgia',
    native: 'საქართველო',
    code: '995',
    continent: 'AS',
    capital: 'Tbilisi',
    currency: 'GEL',
    languages: ['ka'],
    emoji: '🇬🇪',
    emojiU: 'U+1F1EC U+1F1EA'
  },
  GF: {
    name: 'French Guiana',
    native: 'Guyane française',
    code: '594',
    continent: 'SA',
    capital: 'Cayenne',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇬🇫',
    emojiU: 'U+1F1EC U+1F1EB'
  },
  GG: {
    name: 'Guernsey',
    native: 'Guernsey',
    code: '44',
    continent: 'EU',
    capital: 'St. Peter Port',
    currency: 'GBP',
    languages: ['en', 'fr'],
    emoji: '🇬🇬',
    emojiU: 'U+1F1EC U+1F1EC'
  },
  GH: {
    name: 'Ghana',
    native: 'Ghana',
    code: '233',
    continent: 'AF',
    capital: 'Accra',
    currency: 'GHS',
    languages: ['en'],
    emoji: '🇬🇭',
    emojiU: 'U+1F1EC U+1F1ED'
  },
  GI: {
    name: 'Gibraltar',
    native: 'Gibraltar',
    code: '350',
    continent: 'EU',
    capital: 'Gibraltar',
    currency: 'GIP',
    languages: ['en'],
    emoji: '🇬🇮',
    emojiU: 'U+1F1EC U+1F1EE'
  },
  GL: {
    name: 'Greenland',
    native: 'Kalaallit Nunaat',
    code: '299',
    continent: 'NA',
    capital: 'Nuuk',
    currency: 'DKK',
    languages: ['kl'],
    emoji: '🇬🇱',
    emojiU: 'U+1F1EC U+1F1F1'
  },
  GM: {
    name: 'Gambia',
    native: 'Gambia',
    code: '220',
    continent: 'AF',
    capital: 'Banjul',
    currency: 'GMD',
    languages: ['en'],
    emoji: '🇬🇲',
    emojiU: 'U+1F1EC U+1F1F2'
  },
  GN: {
    name: 'Guinea',
    native: 'Guinée',
    code: '224',
    continent: 'AF',
    capital: 'Conakry',
    currency: 'GNF',
    languages: ['fr', 'ff'],
    emoji: '🇬🇳',
    emojiU: 'U+1F1EC U+1F1F3'
  },
  GP: {
    name: 'Guadeloupe',
    native: 'Guadeloupe',
    code: '590',
    continent: 'NA',
    capital: 'Basse-Terre',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇬🇵',
    emojiU: 'U+1F1EC U+1F1F5'
  },
  GQ: {
    name: 'Equatorial Guinea',
    native: 'Guinea Ecuatorial',
    code: '240',
    continent: 'AF',
    capital: 'Malabo',
    currency: 'XAF',
    languages: ['es', 'fr'],
    emoji: '🇬🇶',
    emojiU: 'U+1F1EC U+1F1F6'
  },
  GR: {
    name: 'Greece',
    native: 'Ελλάδα',
    code: '30',
    continent: 'EU',
    capital: 'Athens',
    currency: 'EUR',
    languages: ['el'],
    emoji: '🇬🇷',
    emojiU: 'U+1F1EC U+1F1F7'
  },
  GS: {
    name: 'South Georgia and the South Sandwich Islands',
    native: 'South Georgia',
    code: '500',
    continent: 'AN',
    capital: 'King Edward Point',
    currency: 'GBP',
    languages: ['en'],
    emoji: '🇬🇸',
    emojiU: 'U+1F1EC U+1F1F8'
  },
  GT: {
    name: 'Guatemala',
    native: 'Guatemala',
    code: '502',
    continent: 'NA',
    capital: 'Guatemala City',
    currency: 'GTQ',
    languages: ['es'],
    emoji: '🇬🇹',
    emojiU: 'U+1F1EC U+1F1F9'
  },
  GU: {
    name: 'Guam',
    native: 'Guam',
    code: '1671',
    continent: 'OC',
    capital: 'Hagåtña',
    currency: 'USD',
    languages: ['en', 'ch', 'es'],
    emoji: '🇬🇺',
    emojiU: 'U+1F1EC U+1F1FA'
  },
  GW: {
    name: 'Guinea-Bissau',
    native: 'Guiné-Bissau',
    code: '245',
    continent: 'AF',
    capital: 'Bissau',
    currency: 'XOF',
    languages: ['pt'],
    emoji: '🇬🇼',
    emojiU: 'U+1F1EC U+1F1FC'
  },
  GY: {
    name: 'Guyana',
    native: 'Guyana',
    code: '592',
    continent: 'SA',
    capital: 'Georgetown',
    currency: 'GYD',
    languages: ['en'],
    emoji: '🇬🇾',
    emojiU: 'U+1F1EC U+1F1FE'
  },
  HK: {
    name: 'Hong Kong',
    native: '香港',
    code: '852',
    continent: 'AS',
    capital: 'City of Victoria',
    currency: 'HKD',
    languages: ['zh', 'en'],
    emoji: '🇭🇰',
    emojiU: 'U+1F1ED U+1F1F0'
  },
  HM: {
    name: 'Heard Island and McDonald Islands',
    native: 'Heard Island and McDonald Islands',
    code: '61',
    continent: 'AN',
    capital: '',
    currency: 'AUD',
    languages: ['en'],
    emoji: '🇭🇲',
    emojiU: 'U+1F1ED U+1F1F2'
  },
  HN: {
    name: 'Honduras',
    native: 'Honduras',
    code: '504',
    continent: 'NA',
    capital: 'Tegucigalpa',
    currency: 'HNL',
    languages: ['es'],
    emoji: '🇭🇳',
    emojiU: 'U+1F1ED U+1F1F3'
  },
  HR: {
    name: 'Croatia',
    native: 'Hrvatska',
    code: '385',
    continent: 'EU',
    capital: 'Zagreb',
    currency: 'HRK',
    languages: ['hr'],
    emoji: '🇭🇷',
    emojiU: 'U+1F1ED U+1F1F7'
  },
  HT: {
    name: 'Haiti',
    native: 'Haïti',
    code: '509',
    continent: 'NA',
    capital: 'Port-au-Prince',
    currency: 'HTG,USD',
    languages: ['fr', 'ht'],
    emoji: '🇭🇹',
    emojiU: 'U+1F1ED U+1F1F9'
  },
  HU: {
    name: 'Hungary',
    native: 'Magyarország',
    code: '36',
    continent: 'EU',
    capital: 'Budapest',
    currency: 'HUF',
    languages: ['hu'],
    emoji: '🇭🇺',
    emojiU: 'U+1F1ED U+1F1FA'
  },
  ID: {
    name: 'Indonesia',
    native: 'Indonesia',
    code: '62',
    continent: 'AS',
    capital: 'Jakarta',
    currency: 'IDR',
    languages: ['id'],
    emoji: '🇮🇩',
    emojiU: 'U+1F1EE U+1F1E9'
  },
  IE: {
    name: 'Ireland',
    native: 'Éire',
    code: '353',
    continent: 'EU',
    capital: 'Dublin',
    currency: 'EUR',
    languages: ['ga', 'en'],
    emoji: '🇮🇪',
    emojiU: 'U+1F1EE U+1F1EA'
  },
  IL: {
    name: 'Israel',
    native: 'יִשְׂרָאֵל',
    code: '972',
    continent: 'AS',
    capital: 'Jerusalem',
    currency: 'ILS',
    languages: ['he', 'ar'],
    emoji: '🇮🇱',
    emojiU: 'U+1F1EE U+1F1F1'
  },
  IM: {
    name: 'Isle of Man',
    native: 'Isle of Man',
    code: '44',
    continent: 'EU',
    capital: 'Douglas',
    currency: 'GBP',
    languages: ['en', 'gv'],
    emoji: '🇮🇲',
    emojiU: 'U+1F1EE U+1F1F2'
  },
  IN: {
    name: 'India',
    native: 'भारत',
    code: '91',
    continent: 'AS',
    capital: 'New Delhi',
    currency: 'INR',
    languages: ['hi', 'en'],
    emoji: '🇮🇳',
    emojiU: 'U+1F1EE U+1F1F3'
  },
  IO: {
    name: 'British Indian Ocean Territory',
    native: 'British Indian Ocean Territory',
    code: '246',
    continent: 'AS',
    capital: 'Diego Garcia',
    currency: 'USD',
    languages: ['en'],
    emoji: '🇮🇴',
    emojiU: 'U+1F1EE U+1F1F4'
  },
  IQ: {
    name: 'Iraq',
    native: 'العراق',
    code: '964',
    continent: 'AS',
    capital: 'Baghdad',
    currency: 'IQD',
    languages: ['ar', 'ku'],
    emoji: '🇮🇶',
    emojiU: 'U+1F1EE U+1F1F6'
  },
  IR: {
    name: 'Iran',
    native: 'ایران',
    code: '98',
    continent: 'AS',
    capital: 'Tehran',
    currency: 'IRR',
    languages: ['fa'],
    emoji: '🇮🇷',
    emojiU: 'U+1F1EE U+1F1F7'
  },
  IS: {
    name: 'Iceland',
    native: 'Ísland',
    code: '354',
    continent: 'EU',
    capital: 'Reykjavik',
    currency: 'ISK',
    languages: ['is'],
    emoji: '🇮🇸',
    emojiU: 'U+1F1EE U+1F1F8'
  },
  IT: {
    name: 'Italy',
    native: 'Italia',
    code: '39',
    continent: 'EU',
    capital: 'Rome',
    currency: 'EUR',
    languages: ['it'],
    emoji: '🇮🇹',
    emojiU: 'U+1F1EE U+1F1F9'
  },
  JE: {
    name: 'Jersey',
    native: 'Jersey',
    code: '44',
    continent: 'EU',
    capital: 'Saint Helier',
    currency: 'GBP',
    languages: ['en', 'fr'],
    emoji: '🇯🇪',
    emojiU: 'U+1F1EF U+1F1EA'
  },
  JM: {
    name: 'Jamaica',
    native: 'Jamaica',
    code: '1876',
    continent: 'NA',
    capital: 'Kingston',
    currency: 'JMD',
    languages: ['en'],
    emoji: '🇯🇲',
    emojiU: 'U+1F1EF U+1F1F2'
  },
  JO: {
    name: 'Jordan',
    native: 'الأردن',
    code: '962',
    continent: 'AS',
    capital: 'Amman',
    currency: 'JOD',
    languages: ['ar'],
    emoji: '🇯🇴',
    emojiU: 'U+1F1EF U+1F1F4'
  },
  JP: {
    name: 'Japan',
    native: '日本',
    code: '81',
    continent: 'AS',
    capital: 'Tokyo',
    currency: 'JPY',
    languages: ['ja'],
    emoji: '🇯🇵',
    emojiU: 'U+1F1EF U+1F1F5'
  },
  KE: {
    name: 'Kenya',
    native: 'Kenya',
    code: '254',
    continent: 'AF',
    capital: 'Nairobi',
    currency: 'KES',
    languages: ['en', 'sw'],
    emoji: '🇰🇪',
    emojiU: 'U+1F1F0 U+1F1EA'
  },
  KG: {
    name: 'Kyrgyzstan',
    native: 'Кыргызстан',
    code: '996',
    continent: 'AS',
    capital: 'Bishkek',
    currency: 'KGS',
    languages: ['ky', 'ru'],
    emoji: '🇰🇬',
    emojiU: 'U+1F1F0 U+1F1EC'
  },
  KH: {
    name: 'Cambodia',
    native: 'Kâmpŭchéa',
    code: '855',
    continent: 'AS',
    capital: 'Phnom Penh',
    currency: 'KHR',
    languages: ['km'],
    emoji: '🇰🇭',
    emojiU: 'U+1F1F0 U+1F1ED'
  },
  KI: {
    name: 'Kiribati',
    native: 'Kiribati',
    code: '686',
    continent: 'OC',
    capital: 'South Tarawa',
    currency: 'AUD',
    languages: ['en'],
    emoji: '🇰🇮',
    emojiU: 'U+1F1F0 U+1F1EE'
  },
  KM: {
    name: 'Comoros',
    native: 'Komori',
    code: '269',
    continent: 'AF',
    capital: 'Moroni',
    currency: 'KMF',
    languages: ['ar', 'fr'],
    emoji: '🇰🇲',
    emojiU: 'U+1F1F0 U+1F1F2'
  },
  KN: {
    name: 'Saint Kitts and Nevis',
    native: 'Saint Kitts and Nevis',
    code: '1869',
    continent: 'NA',
    capital: 'Basseterre',
    currency: 'XCD',
    languages: ['en'],
    emoji: '🇰🇳',
    emojiU: 'U+1F1F0 U+1F1F3'
  },
  KP: {
    name: 'North Korea',
    native: '북한',
    code: '850',
    continent: 'AS',
    capital: 'Pyongyang',
    currency: 'KPW',
    languages: ['ko'],
    emoji: '🇰🇵',
    emojiU: 'U+1F1F0 U+1F1F5'
  },
  KR: {
    name: 'South Korea',
    native: '대한민국',
    code: '82',
    continent: 'AS',
    capital: 'Seoul',
    currency: 'KRW',
    languages: ['ko'],
    emoji: '🇰🇷',
    emojiU: 'U+1F1F0 U+1F1F7'
  },
  KW: {
    name: 'Kuwait',
    native: 'الكويت',
    code: '965',
    continent: 'AS',
    capital: 'Kuwait City',
    currency: 'KWD',
    languages: ['ar'],
    emoji: '🇰🇼',
    emojiU: 'U+1F1F0 U+1F1FC'
  },
  KY: {
    name: 'Cayman Islands',
    native: 'Cayman Islands',
    code: '1345',
    continent: 'NA',
    capital: 'George Town',
    currency: 'KYD',
    languages: ['en'],
    emoji: '🇰🇾',
    emojiU: 'U+1F1F0 U+1F1FE'
  },
  KZ: {
    name: 'Kazakhstan',
    native: 'Қазақстан',
    code: '76,77',
    continent: 'AS',
    capital: 'Astana',
    currency: 'KZT',
    languages: ['kk', 'ru'],
    emoji: '🇰🇿',
    emojiU: 'U+1F1F0 U+1F1FF'
  },
  LA: {
    name: 'Laos',
    native: 'ສປປລາວ',
    code: '856',
    continent: 'AS',
    capital: 'Vientiane',
    currency: 'LAK',
    languages: ['lo'],
    emoji: '🇱🇦',
    emojiU: 'U+1F1F1 U+1F1E6'
  },
  LB: {
    name: 'Lebanon',
    native: 'لبنان',
    code: '961',
    continent: 'AS',
    capital: 'Beirut',
    currency: 'LBP',
    languages: ['ar', 'fr'],
    emoji: '🇱🇧',
    emojiU: 'U+1F1F1 U+1F1E7'
  },
  LC: {
    name: 'Saint Lucia',
    native: 'Saint Lucia',
    code: '1758',
    continent: 'NA',
    capital: 'Castries',
    currency: 'XCD',
    languages: ['en'],
    emoji: '🇱🇨',
    emojiU: 'U+1F1F1 U+1F1E8'
  },
  LI: {
    name: 'Liechtenstein',
    native: 'Liechtenstein',
    code: '423',
    continent: 'EU',
    capital: 'Vaduz',
    currency: 'CHF',
    languages: ['de'],
    emoji: '🇱🇮',
    emojiU: 'U+1F1F1 U+1F1EE'
  },
  LK: {
    name: 'Sri Lanka',
    native: 'śrī laṃkāva',
    code: '94',
    continent: 'AS',
    capital: 'Colombo',
    currency: 'LKR',
    languages: ['si', 'ta'],
    emoji: '🇱🇰',
    emojiU: 'U+1F1F1 U+1F1F0'
  },
  LR: {
    name: 'Liberia',
    native: 'Liberia',
    code: '231',
    continent: 'AF',
    capital: 'Monrovia',
    currency: 'LRD',
    languages: ['en'],
    emoji: '🇱🇷',
    emojiU: 'U+1F1F1 U+1F1F7'
  },
  LS: {
    name: 'Lesotho',
    native: 'Lesotho',
    code: '266',
    continent: 'AF',
    capital: 'Maseru',
    currency: 'LSL,ZAR',
    languages: ['en', 'st'],
    emoji: '🇱🇸',
    emojiU: 'U+1F1F1 U+1F1F8'
  },
  LT: {
    name: 'Lithuania',
    native: 'Lietuva',
    code: '370',
    continent: 'EU',
    capital: 'Vilnius',
    currency: 'EUR',
    languages: ['lt'],
    emoji: '🇱🇹',
    emojiU: 'U+1F1F1 U+1F1F9'
  },
  LU: {
    name: 'Luxembourg',
    native: 'Luxembourg',
    code: '352',
    continent: 'EU',
    capital: 'Luxembourg',
    currency: 'EUR',
    languages: ['fr', 'de', 'lb'],
    emoji: '🇱🇺',
    emojiU: 'U+1F1F1 U+1F1FA'
  },
  LV: {
    name: 'Latvia',
    native: 'Latvija',
    code: '371',
    continent: 'EU',
    capital: 'Riga',
    currency: 'EUR',
    languages: ['lv'],
    emoji: '🇱🇻',
    emojiU: 'U+1F1F1 U+1F1FB'
  },
  LY: {
    name: 'Libya',
    native: '‏ليبيا',
    code: '218',
    continent: 'AF',
    capital: 'Tripoli',
    currency: 'LYD',
    languages: ['ar'],
    emoji: '🇱🇾',
    emojiU: 'U+1F1F1 U+1F1FE'
  },
  MA: {
    name: 'Morocco',
    native: 'المغرب',
    code: '212',
    continent: 'AF',
    capital: 'Rabat',
    currency: 'MAD',
    languages: ['ar'],
    emoji: '🇲🇦',
    emojiU: 'U+1F1F2 U+1F1E6'
  },
  MC: {
    name: 'Monaco',
    native: 'Monaco',
    code: '377',
    continent: 'EU',
    capital: 'Monaco',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇲🇨',
    emojiU: 'U+1F1F2 U+1F1E8'
  },
  MD: {
    name: 'Moldova',
    native: 'Moldova',
    code: '373',
    continent: 'EU',
    capital: 'Chișinău',
    currency: 'MDL',
    languages: ['ro'],
    emoji: '🇲🇩',
    emojiU: 'U+1F1F2 U+1F1E9'
  },
  ME: {
    name: 'Montenegro',
    native: 'Црна Гора',
    code: '382',
    continent: 'EU',
    capital: 'Podgorica',
    currency: 'EUR',
    languages: ['sr', 'bs', 'sq', 'hr'],
    emoji: '🇲🇪',
    emojiU: 'U+1F1F2 U+1F1EA'
  },
  MF: {
    name: 'Saint Martin',
    native: 'Saint-Martin',
    code: '590',
    continent: 'NA',
    capital: 'Marigot',
    currency: 'EUR',
    languages: ['en', 'fr', 'nl'],
    emoji: '🇲🇫',
    emojiU: 'U+1F1F2 U+1F1EB'
  },
  MG: {
    name: 'Madagascar',
    native: 'Madagasikara',
    code: '261',
    continent: 'AF',
    capital: 'Antananarivo',
    currency: 'MGA',
    languages: ['fr', 'mg'],
    emoji: '🇲🇬',
    emojiU: 'U+1F1F2 U+1F1EC'
  },
  MH: {
    name: 'Marshall Islands',
    native: 'M̧ajeļ',
    code: '692',
    continent: 'OC',
    capital: 'Majuro',
    currency: 'USD',
    languages: ['en', 'mh'],
    emoji: '🇲🇭',
    emojiU: 'U+1F1F2 U+1F1ED'
  },
  MK: {
    name: 'North Macedonia',
    native: 'Северна Македонија',
    code: '389',
    continent: 'EU',
    capital: 'Skopje',
    currency: 'MKD',
    languages: ['mk'],
    emoji: '🇲🇰',
    emojiU: 'U+1F1F2 U+1F1F0'
  },
  ML: {
    name: 'Mali',
    native: 'Mali',
    code: '223',
    continent: 'AF',
    capital: 'Bamako',
    currency: 'XOF',
    languages: ['fr'],
    emoji: '🇲🇱',
    emojiU: 'U+1F1F2 U+1F1F1'
  },
  MM: {
    name: 'Myanmar [Burma]',
    native: 'မြန်မာ',
    code: '95',
    continent: 'AS',
    capital: 'Naypyidaw',
    currency: 'MMK',
    languages: ['my'],
    emoji: '🇲🇲',
    emojiU: 'U+1F1F2 U+1F1F2'
  },
  MN: {
    name: 'Mongolia',
    native: 'Монгол улс',
    code: '976',
    continent: 'AS',
    capital: 'Ulan Bator',
    currency: 'MNT',
    languages: ['mn'],
    emoji: '🇲🇳',
    emojiU: 'U+1F1F2 U+1F1F3'
  },
  MO: {
    name: 'Macao',
    native: '澳門',
    code: '853',
    continent: 'AS',
    capital: '',
    currency: 'MOP',
    languages: ['zh', 'pt'],
    emoji: '🇲🇴',
    emojiU: 'U+1F1F2 U+1F1F4'
  },
  MP: {
    name: 'Northern Mariana Islands',
    native: 'Northern Mariana Islands',
    code: '1670',
    continent: 'OC',
    capital: 'Saipan',
    currency: 'USD',
    languages: ['en', 'ch'],
    emoji: '🇲🇵',
    emojiU: 'U+1F1F2 U+1F1F5'
  },
  MQ: {
    name: 'Martinique',
    native: 'Martinique',
    code: '596',
    continent: 'NA',
    capital: 'Fort-de-France',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇲🇶',
    emojiU: 'U+1F1F2 U+1F1F6'
  },
  MR: {
    name: 'Mauritania',
    native: 'موريتانيا',
    code: '222',
    continent: 'AF',
    capital: 'Nouakchott',
    currency: 'MRU',
    languages: ['ar'],
    emoji: '🇲🇷',
    emojiU: 'U+1F1F2 U+1F1F7'
  },
  MS: {
    name: 'Montserrat',
    native: 'Montserrat',
    code: '1664',
    continent: 'NA',
    capital: 'Plymouth',
    currency: 'XCD',
    languages: ['en'],
    emoji: '🇲🇸',
    emojiU: 'U+1F1F2 U+1F1F8'
  },
  MT: {
    name: 'Malta',
    native: 'Malta',
    code: '356',
    continent: 'EU',
    capital: 'Valletta',
    currency: 'EUR',
    languages: ['mt', 'en'],
    emoji: '🇲🇹',
    emojiU: 'U+1F1F2 U+1F1F9'
  },
  MU: {
    name: 'Mauritius',
    native: 'Maurice',
    code: '230',
    continent: 'AF',
    capital: 'Port Louis',
    currency: 'MUR',
    languages: ['en'],
    emoji: '🇲🇺',
    emojiU: 'U+1F1F2 U+1F1FA'
  },
  MV: {
    name: 'Maldives',
    native: 'Maldives',
    code: '960',
    continent: 'AS',
    capital: 'Malé',
    currency: 'MVR',
    languages: ['dv'],
    emoji: '🇲🇻',
    emojiU: 'U+1F1F2 U+1F1FB'
  },
  MW: {
    name: 'Malawi',
    native: 'Malawi',
    code: '265',
    continent: 'AF',
    capital: 'Lilongwe',
    currency: 'MWK',
    languages: ['en', 'ny'],
    emoji: '🇲🇼',
    emojiU: 'U+1F1F2 U+1F1FC'
  },
  MX: {
    name: 'Mexico',
    native: 'México',
    code: '52',
    continent: 'NA',
    capital: 'Mexico City',
    currency: 'MXN',
    languages: ['es'],
    emoji: '🇲🇽',
    emojiU: 'U+1F1F2 U+1F1FD'
  },
  MY: {
    name: 'Malaysia',
    native: 'Malaysia',
    code: '60',
    continent: 'AS',
    capital: 'Kuala Lumpur',
    currency: 'MYR',
    languages: ['ms'],
    emoji: '🇲🇾',
    emojiU: 'U+1F1F2 U+1F1FE'
  },
  MZ: {
    name: 'Mozambique',
    native: 'Moçambique',
    code: '258',
    continent: 'AF',
    capital: 'Maputo',
    currency: 'MZN',
    languages: ['pt'],
    emoji: '🇲🇿',
    emojiU: 'U+1F1F2 U+1F1FF'
  },
  NA: {
    name: 'Namibia',
    native: 'Namibia',
    code: '264',
    continent: 'AF',
    capital: 'Windhoek',
    currency: 'NAD,ZAR',
    languages: ['en', 'af'],
    emoji: '🇳🇦',
    emojiU: 'U+1F1F3 U+1F1E6'
  },
  NC: {
    name: 'New Caledonia',
    native: 'Nouvelle-Calédonie',
    code: '687',
    continent: 'OC',
    capital: 'Nouméa',
    currency: 'XPF',
    languages: ['fr'],
    emoji: '🇳🇨',
    emojiU: 'U+1F1F3 U+1F1E8'
  },
  NE: {
    name: 'Niger',
    native: 'Niger',
    code: '227',
    continent: 'AF',
    capital: 'Niamey',
    currency: 'XOF',
    languages: ['fr'],
    emoji: '🇳🇪',
    emojiU: 'U+1F1F3 U+1F1EA'
  },
  NF: {
    name: 'Norfolk Island',
    native: 'Norfolk Island',
    code: '672',
    continent: 'OC',
    capital: 'Kingston',
    currency: 'AUD',
    languages: ['en'],
    emoji: '🇳🇫',
    emojiU: 'U+1F1F3 U+1F1EB'
  },
  NG: {
    name: 'Nigeria',
    native: 'Nigeria',
    code: '234',
    continent: 'AF',
    capital: 'Abuja',
    currency: 'NGN',
    languages: ['en'],
    emoji: '🇳🇬',
    emojiU: 'U+1F1F3 U+1F1EC'
  },
  NI: {
    name: 'Nicaragua',
    native: 'Nicaragua',
    code: '505',
    continent: 'NA',
    capital: 'Managua',
    currency: 'NIO',
    languages: ['es'],
    emoji: '🇳🇮',
    emojiU: 'U+1F1F3 U+1F1EE'
  },
  NL: {
    name: 'Netherlands',
    native: 'Nederland',
    code: '31',
    continent: 'EU',
    capital: 'Amsterdam',
    currency: 'EUR',
    languages: ['nl'],
    emoji: '🇳🇱',
    emojiU: 'U+1F1F3 U+1F1F1'
  },
  NO: {
    name: 'Norway',
    native: 'Norge',
    code: '47',
    continent: 'EU',
    capital: 'Oslo',
    currency: 'NOK',
    languages: ['no', 'nb', 'nn'],
    emoji: '🇳🇴',
    emojiU: 'U+1F1F3 U+1F1F4'
  },
  NP: {
    name: 'Nepal',
    native: 'नपल',
    code: '977',
    continent: 'AS',
    capital: 'Kathmandu',
    currency: 'NPR',
    languages: ['ne'],
    emoji: '🇳🇵',
    emojiU: 'U+1F1F3 U+1F1F5'
  },
  NR: {
    name: 'Nauru',
    native: 'Nauru',
    code: '674',
    continent: 'OC',
    capital: 'Yaren',
    currency: 'AUD',
    languages: ['en', 'na'],
    emoji: '🇳🇷',
    emojiU: 'U+1F1F3 U+1F1F7'
  },
  NU: {
    name: 'Niue',
    native: 'Niuē',
    code: '683',
    continent: 'OC',
    capital: 'Alofi',
    currency: 'NZD',
    languages: ['en'],
    emoji: '🇳🇺',
    emojiU: 'U+1F1F3 U+1F1FA'
  },
  NZ: {
    name: 'New Zealand',
    native: 'New Zealand',
    code: '64',
    continent: 'OC',
    capital: 'Wellington',
    currency: 'NZD',
    languages: ['en', 'mi'],
    emoji: '🇳🇿',
    emojiU: 'U+1F1F3 U+1F1FF'
  },
  OM: {
    name: 'Oman',
    native: 'عمان',
    code: '968',
    continent: 'AS',
    capital: 'Muscat',
    currency: 'OMR',
    languages: ['ar'],
    emoji: '🇴🇲',
    emojiU: 'U+1F1F4 U+1F1F2'
  },
  PA: {
    name: 'Panama',
    native: 'Panamá',
    code: '507',
    continent: 'NA',
    capital: 'Panama City',
    currency: 'PAB,USD',
    languages: ['es'],
    emoji: '🇵🇦',
    emojiU: 'U+1F1F5 U+1F1E6'
  },
  PE: {
    name: 'Peru',
    native: 'Perú',
    code: '51',
    continent: 'SA',
    capital: 'Lima',
    currency: 'PEN',
    languages: ['es'],
    emoji: '🇵🇪',
    emojiU: 'U+1F1F5 U+1F1EA'
  },
  PF: {
    name: 'French Polynesia',
    native: 'Polynésie française',
    code: '689',
    continent: 'OC',
    capital: 'Papeetē',
    currency: 'XPF',
    languages: ['fr'],
    emoji: '🇵🇫',
    emojiU: 'U+1F1F5 U+1F1EB'
  },
  PG: {
    name: 'Papua New Guinea',
    native: 'Papua Niugini',
    code: '675',
    continent: 'OC',
    capital: 'Port Moresby',
    currency: 'PGK',
    languages: ['en'],
    emoji: '🇵🇬',
    emojiU: 'U+1F1F5 U+1F1EC'
  },
  PH: {
    name: 'Philippines',
    native: 'Pilipinas',
    code: '63',
    continent: 'AS',
    capital: 'Manila',
    currency: 'PHP',
    languages: ['en'],
    emoji: '🇵🇭',
    emojiU: 'U+1F1F5 U+1F1ED'
  },
  PK: {
    name: 'Pakistan',
    native: 'Pakistan',
    code: '92',
    continent: 'AS',
    capital: 'Islamabad',
    currency: 'PKR',
    languages: ['en', 'ur'],
    emoji: '🇵🇰',
    emojiU: 'U+1F1F5 U+1F1F0'
  },
  PL: {
    name: 'Poland',
    native: 'Polska',
    code: '48',
    continent: 'EU',
    capital: 'Warsaw',
    currency: 'PLN',
    languages: ['pl'],
    emoji: '🇵🇱',
    emojiU: 'U+1F1F5 U+1F1F1'
  },
  PM: {
    name: 'Saint Pierre and Miquelon',
    native: 'Saint-Pierre-et-Miquelon',
    code: '508',
    continent: 'NA',
    capital: 'Saint-Pierre',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇵🇲',
    emojiU: 'U+1F1F5 U+1F1F2'
  },
  PN: {
    name: 'Pitcairn Islands',
    native: 'Pitcairn Islands',
    code: '64',
    continent: 'OC',
    capital: 'Adamstown',
    currency: 'NZD',
    languages: ['en'],
    emoji: '🇵🇳',
    emojiU: 'U+1F1F5 U+1F1F3'
  },
  PR: {
    name: 'Puerto Rico',
    native: 'Puerto Rico',
    code: '1787,1939',
    continent: 'NA',
    capital: 'San Juan',
    currency: 'USD',
    languages: ['es', 'en'],
    emoji: '🇵🇷',
    emojiU: 'U+1F1F5 U+1F1F7'
  },
  PS: {
    name: 'Palestine',
    native: 'فلسطين',
    code: '970',
    continent: 'AS',
    capital: 'Ramallah',
    currency: 'ILS',
    languages: ['ar'],
    emoji: '🇵🇸',
    emojiU: 'U+1F1F5 U+1F1F8'
  },
  PT: {
    name: 'Portugal',
    native: 'Portugal',
    code: '351',
    continent: 'EU',
    capital: 'Lisbon',
    currency: 'EUR',
    languages: ['pt'],
    emoji: '🇵🇹',
    emojiU: 'U+1F1F5 U+1F1F9'
  },
  PW: {
    name: 'Palau',
    native: 'Palau',
    code: '680',
    continent: 'OC',
    capital: 'Ngerulmud',
    currency: 'USD',
    languages: ['en'],
    emoji: '🇵🇼',
    emojiU: 'U+1F1F5 U+1F1FC'
  },
  PY: {
    name: 'Paraguay',
    native: 'Paraguay',
    code: '595',
    continent: 'SA',
    capital: 'Asunción',
    currency: 'PYG',
    languages: ['es', 'gn'],
    emoji: '🇵🇾',
    emojiU: 'U+1F1F5 U+1F1FE'
  },
  QA: {
    name: 'Qatar',
    native: 'قطر',
    code: '974',
    continent: 'AS',
    capital: 'Doha',
    currency: 'QAR',
    languages: ['ar'],
    emoji: '🇶🇦',
    emojiU: 'U+1F1F6 U+1F1E6'
  },
  RD: {
    name: 'Republica Dominicana',
    native: 'Republica Dominicana',
    code: '262',
    continent: 'AF',
    capital: 'Saint-Denis',
    currency: 'EUR',
    languages: ['es'],
    emoji: '🇩🇴',
    emojiU: 'U+1F1F4 U+1F1E9'
  },
  RE: {
    name: 'Réunion',
    native: 'La Réunion',
    code: '262',
    continent: 'AF',
    capital: 'Saint-Denis',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇷🇪',
    emojiU: 'U+1F1F7 U+1F1EA'
  },
  RO: {
    name: 'Romania',
    native: 'România',
    code: '40',
    continent: 'EU',
    capital: 'Bucharest',
    currency: 'RON',
    languages: ['ro'],
    emoji: '🇷🇴',
    emojiU: 'U+1F1F7 U+1F1F4'
  },
  RS: {
    name: 'Serbia',
    native: 'Србија',
    code: '381',
    continent: 'EU',
    capital: 'Belgrade',
    currency: 'RSD',
    languages: ['sr'],
    emoji: '🇷🇸',
    emojiU: 'U+1F1F7 U+1F1F8'
  },
  RU: {
    name: 'Russia',
    native: 'Россия',
    code: '7',
    continent: 'EU',
    capital: 'Moscow',
    currency: 'RUB',
    languages: ['ru'],
    emoji: '🇷🇺',
    emojiU: 'U+1F1F7 U+1F1FA'
  },
  RW: {
    name: 'Rwanda',
    native: 'Rwanda',
    code: '250',
    continent: 'AF',
    capital: 'Kigali',
    currency: 'RWF',
    languages: ['rw', 'en', 'fr'],
    emoji: '🇷🇼',
    emojiU: 'U+1F1F7 U+1F1FC'
  },
  SA: {
    name: 'Saudi Arabia',
    native: 'العربية السعودية',
    code: '966',
    continent: 'AS',
    capital: 'Riyadh',
    currency: 'SAR',
    languages: ['ar'],
    emoji: '🇸🇦',
    emojiU: 'U+1F1F8 U+1F1E6'
  },
  SB: {
    name: 'Solomon Islands',
    native: 'Solomon Islands',
    code: '677',
    continent: 'OC',
    capital: 'Honiara',
    currency: 'SBD',
    languages: ['en'],
    emoji: '🇸🇧',
    emojiU: 'U+1F1F8 U+1F1E7'
  },
  SC: {
    name: 'Seychelles',
    native: 'Seychelles',
    code: '248',
    continent: 'AF',
    capital: 'Victoria',
    currency: 'SCR',
    languages: ['fr', 'en'],
    emoji: '🇸🇨',
    emojiU: 'U+1F1F8 U+1F1E8'
  },
  SD: {
    name: 'Sudan',
    native: 'السودان',
    code: '249',
    continent: 'AF',
    capital: 'Khartoum',
    currency: 'SDG',
    languages: ['ar', 'en'],
    emoji: '🇸🇩',
    emojiU: 'U+1F1F8 U+1F1E9'
  },
  SE: {
    name: 'Sweden',
    native: 'Sverige',
    code: '46',
    continent: 'EU',
    capital: 'Stockholm',
    currency: 'SEK',
    languages: ['sv'],
    emoji: '🇸🇪',
    emojiU: 'U+1F1F8 U+1F1EA'
  },
  SG: {
    name: 'Singapore',
    native: 'Singapore',
    code: '65',
    continent: 'AS',
    capital: 'Singapore',
    currency: 'SGD',
    languages: ['en', 'ms', 'ta', 'zh'],
    emoji: '🇸🇬',
    emojiU: 'U+1F1F8 U+1F1EC'
  },
  SH: {
    name: 'Saint Helena',
    native: 'Saint Helena',
    code: '290',
    continent: 'AF',
    capital: 'Jamestown',
    currency: 'SHP',
    languages: ['en'],
    emoji: '🇸🇭',
    emojiU: 'U+1F1F8 U+1F1ED'
  },
  SI: {
    name: 'Slovenia',
    native: 'Slovenija',
    code: '386',
    continent: 'EU',
    capital: 'Ljubljana',
    currency: 'EUR',
    languages: ['sl'],
    emoji: '🇸🇮',
    emojiU: 'U+1F1F8 U+1F1EE'
  },
  SJ: {
    name: 'Svalbard and Jan Mayen',
    native: 'Svalbard og Jan Mayen',
    code: '4779',
    continent: 'EU',
    capital: 'Longyearbyen',
    currency: 'NOK',
    languages: ['no'],
    emoji: '🇸🇯',
    emojiU: 'U+1F1F8 U+1F1EF'
  },
  SK: {
    name: 'Slovakia',
    native: 'Slovensko',
    code: '421',
    continent: 'EU',
    capital: 'Bratislava',
    currency: 'EUR',
    languages: ['sk'],
    emoji: '🇸🇰',
    emojiU: 'U+1F1F8 U+1F1F0'
  },
  SL: {
    name: 'Sierra Leone',
    native: 'Sierra Leone',
    code: '232',
    continent: 'AF',
    capital: 'Freetown',
    currency: 'SLL',
    languages: ['en'],
    emoji: '🇸🇱',
    emojiU: 'U+1F1F8 U+1F1F1'
  },
  SM: {
    name: 'San Marino',
    native: 'San Marino',
    code: '378',
    continent: 'EU',
    capital: 'City of San Marino',
    currency: 'EUR',
    languages: ['it'],
    emoji: '🇸🇲',
    emojiU: 'U+1F1F8 U+1F1F2'
  },
  SN: {
    name: 'Senegal',
    native: 'Sénégal',
    code: '221',
    continent: 'AF',
    capital: 'Dakar',
    currency: 'XOF',
    languages: ['fr'],
    emoji: '🇸🇳',
    emojiU: 'U+1F1F8 U+1F1F3'
  },
  SO: {
    name: 'Somalia',
    native: 'Soomaaliya',
    code: '252',
    continent: 'AF',
    capital: 'Mogadishu',
    currency: 'SOS',
    languages: ['so', 'ar'],
    emoji: '🇸🇴',
    emojiU: 'U+1F1F8 U+1F1F4'
  },
  SR: {
    name: 'Suriname',
    native: 'Suriname',
    code: '597',
    continent: 'SA',
    capital: 'Paramaribo',
    currency: 'SRD',
    languages: ['nl'],
    emoji: '🇸🇷',
    emojiU: 'U+1F1F8 U+1F1F7'
  },
  SS: {
    name: 'South Sudan',
    native: 'South Sudan',
    code: '211',
    continent: 'AF',
    capital: 'Juba',
    currency: 'SSP',
    languages: ['en'],
    emoji: '🇸🇸',
    emojiU: 'U+1F1F8 U+1F1F8'
  },
  ST: {
    name: 'São Tomé and Príncipe',
    native: 'São Tomé e Príncipe',
    code: '239',
    continent: 'AF',
    capital: 'São Tomé',
    currency: 'STN',
    languages: ['pt'],
    emoji: '🇸🇹',
    emojiU: 'U+1F1F8 U+1F1F9'
  },
  SV: {
    name: 'El Salvador',
    native: 'El Salvador',
    code: '503',
    continent: 'NA',
    capital: 'San Salvador',
    currency: 'SVC,USD',
    languages: ['es'],
    emoji: '🇸🇻',
    emojiU: 'U+1F1F8 U+1F1FB'
  },
  SX: {
    name: 'Sint Maarten',
    native: 'Sint Maarten',
    code: '1721',
    continent: 'NA',
    capital: 'Philipsburg',
    currency: 'ANG',
    languages: ['nl', 'en'],
    emoji: '🇸🇽',
    emojiU: 'U+1F1F8 U+1F1FD'
  },
  SY: {
    name: 'Syria',
    native: 'سوريا',
    code: '963',
    continent: 'AS',
    capital: 'Damascus',
    currency: 'SYP',
    languages: ['ar'],
    emoji: '🇸🇾',
    emojiU: 'U+1F1F8 U+1F1FE'
  },
  SZ: {
    name: 'Swaziland',
    native: 'Swaziland',
    code: '268',
    continent: 'AF',
    capital: 'Lobamba',
    currency: 'SZL',
    languages: ['en', 'ss'],
    emoji: '🇸🇿',
    emojiU: 'U+1F1F8 U+1F1FF'
  },
  TC: {
    name: 'Turks and Caicos Islands',
    native: 'Turks and Caicos Islands',
    code: '1649',
    continent: 'NA',
    capital: 'Cockburn Town',
    currency: 'USD',
    languages: ['en'],
    emoji: '🇹🇨',
    emojiU: 'U+1F1F9 U+1F1E8'
  },
  TD: {
    name: 'Chad',
    native: 'Tchad',
    code: '235',
    continent: 'AF',
    capital: 'N\'Djamena',
    currency: 'XAF',
    languages: ['fr', 'ar'],
    emoji: '🇹🇩',
    emojiU: 'U+1F1F9 U+1F1E9'
  },
  TF: {
    name: 'French Southern Territories',
    native: 'Territoire des Terres australes et antarctiques fr',
    code: '262',
    continent: 'AN',
    capital: 'Port-aux-Français',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇹🇫',
    emojiU: 'U+1F1F9 U+1F1EB'
  },
  TG: {
    name: 'Togo',
    native: 'Togo',
    code: '228',
    continent: 'AF',
    capital: 'Lomé',
    currency: 'XOF',
    languages: ['fr'],
    emoji: '🇹🇬',
    emojiU: 'U+1F1F9 U+1F1EC'
  },
  TH: {
    name: 'Thailand',
    native: 'ประเทศไทย',
    code: '66',
    continent: 'AS',
    capital: 'Bangkok',
    currency: 'THB',
    languages: ['th'],
    emoji: '🇹🇭',
    emojiU: 'U+1F1F9 U+1F1ED'
  },
  TJ: {
    name: 'Tajikistan',
    native: 'Тоҷикистон',
    code: '992',
    continent: 'AS',
    capital: 'Dushanbe',
    currency: 'TJS',
    languages: ['tg', 'ru'],
    emoji: '🇹🇯',
    emojiU: 'U+1F1F9 U+1F1EF'
  },
  TK: {
    name: 'Tokelau',
    native: 'Tokelau',
    code: '690',
    continent: 'OC',
    capital: 'Fakaofo',
    currency: 'NZD',
    languages: ['en'],
    emoji: '🇹🇰',
    emojiU: 'U+1F1F9 U+1F1F0'
  },
  TL: {
    name: 'East Timor',
    native: 'Timor-Leste',
    code: '670',
    continent: 'OC',
    capital: 'Dili',
    currency: 'USD',
    languages: ['pt'],
    emoji: '🇹🇱',
    emojiU: 'U+1F1F9 U+1F1F1'
  },
  TM: {
    name: 'Turkmenistan',
    native: 'Türkmenistan',
    code: '993',
    continent: 'AS',
    capital: 'Ashgabat',
    currency: 'TMT',
    languages: ['tk', 'ru'],
    emoji: '🇹🇲',
    emojiU: 'U+1F1F9 U+1F1F2'
  },
  TN: {
    name: 'Tunisia',
    native: 'تونس',
    code: '216',
    continent: 'AF',
    capital: 'Tunis',
    currency: 'TND',
    languages: ['ar'],
    emoji: '🇹🇳',
    emojiU: 'U+1F1F9 U+1F1F3'
  },
  TO: {
    name: 'Tonga',
    native: 'Tonga',
    code: '676',
    continent: 'OC',
    capital: 'Nuku\'alofa',
    currency: 'TOP',
    languages: ['en', 'to'],
    emoji: '🇹🇴',
    emojiU: 'U+1F1F9 U+1F1F4'
  },
  TR: {
    name: 'Turkey',
    native: 'Türkiye',
    code: '90',
    continent: 'AS',
    capital: 'Ankara',
    currency: 'TRY',
    languages: ['tr'],
    emoji: '🇹🇷',
    emojiU: 'U+1F1F9 U+1F1F7'
  },
  TT: {
    name: 'Trinidad and Tobago',
    native: 'Trinidad and Tobago',
    code: '1868',
    continent: 'NA',
    capital: 'Port of Spain',
    currency: 'TTD',
    languages: ['en'],
    emoji: '🇹🇹',
    emojiU: 'U+1F1F9 U+1F1F9'
  },
  TV: {
    name: 'Tuvalu',
    native: 'Tuvalu',
    code: '688',
    continent: 'OC',
    capital: 'Funafuti',
    currency: 'AUD',
    languages: ['en'],
    emoji: '🇹🇻',
    emojiU: 'U+1F1F9 U+1F1FB'
  },
  TW: {
    name: 'Taiwan',
    native: '臺灣',
    code: '886',
    continent: 'AS',
    capital: 'Taipei',
    currency: 'TWD',
    languages: ['zh'],
    emoji: '🇹🇼',
    emojiU: 'U+1F1F9 U+1F1FC'
  },
  TZ: {
    name: 'Tanzania',
    native: 'Tanzania',
    code: '255',
    continent: 'AF',
    capital: 'Dodoma',
    currency: 'TZS',
    languages: ['sw', 'en'],
    emoji: '🇹🇿',
    emojiU: 'U+1F1F9 U+1F1FF'
  },
  UA: {
    name: 'Ukraine',
    native: 'Україна',
    code: '380',
    continent: 'EU',
    capital: 'Kyiv',
    currency: 'UAH',
    languages: ['uk'],
    emoji: '🇺🇦',
    emojiU: 'U+1F1FA U+1F1E6'
  },
  UG: {
    name: 'Uganda',
    native: 'Uganda',
    code: '256',
    continent: 'AF',
    capital: 'Kampala',
    currency: 'UGX',
    languages: ['en', 'sw'],
    emoji: '🇺🇬',
    emojiU: 'U+1F1FA U+1F1EC'
  },
  UM: {
    name: 'U.S. Minor Outlying Islands',
    native: 'United States Minor Outlying Islands',
    code: '1',
    continent: 'OC',
    capital: '',
    currency: 'USD',
    languages: ['en'],
    emoji: '🇺🇲',
    emojiU: 'U+1F1FA U+1F1F2'
  },
  US: {
    name: 'United States',
    native: 'United States',
    code: '1',
    continent: 'NA',
    capital: 'Washington D.C.',
    currency: 'USD,USN,USS',
    languages: ['en'],
    emoji: '🇺🇸',
    emojiU: 'U+1F1FA U+1F1F8'
  },
  UY: {
    name: 'Uruguay',
    native: 'Uruguay',
    code: '598',
    continent: 'SA',
    capital: 'Montevideo',
    currency: 'UYI,UYU',
    languages: ['es'],
    emoji: '🇺🇾',
    emojiU: 'U+1F1FA U+1F1FE'
  },
  UZ: {
    name: 'Uzbekistan',
    native: 'O‘zbekiston',
    code: '998',
    continent: 'AS',
    capital: 'Tashkent',
    currency: 'UZS',
    languages: ['uz', 'ru'],
    emoji: '🇺🇿',
    emojiU: 'U+1F1FA U+1F1FF'
  },
  VA: {
    name: 'Vatican City',
    native: 'Vaticano',
    code: '379',
    continent: 'EU',
    capital: 'Vatican City',
    currency: 'EUR',
    languages: ['it', 'la'],
    emoji: '🇻🇦',
    emojiU: 'U+1F1FB U+1F1E6'
  },
  VC: {
    name: 'Saint Vincent and the Grenadines',
    native: 'Saint Vincent and the Grenadines',
    code: '1784',
    continent: 'NA',
    capital: 'Kingstown',
    currency: 'XCD',
    languages: ['en'],
    emoji: '🇻🇨',
    emojiU: 'U+1F1FB U+1F1E8'
  },
  VE: {
    name: 'Venezuela',
    native: 'Venezuela',
    code: '58',
    continent: 'SA',
    capital: 'Caracas',
    currency: 'VES',
    languages: ['es'],
    emoji: '🇻🇪',
    emojiU: 'U+1F1FB U+1F1EA'
  },
  VG: {
    name: 'British Virgin Islands',
    native: 'British Virgin Islands',
    code: '1284',
    continent: 'NA',
    capital: 'Road Town',
    currency: 'USD',
    languages: ['en'],
    emoji: '🇻🇬',
    emojiU: 'U+1F1FB U+1F1EC'
  },
  VI: {
    name: 'U.S. Virgin Islands',
    native: 'United States Virgin Islands',
    code: '1340',
    continent: 'NA',
    capital: 'Charlotte Amalie',
    currency: 'USD',
    languages: ['en'],
    emoji: '🇻🇮',
    emojiU: 'U+1F1FB U+1F1EE'
  },
  VN: {
    name: 'Vietnam',
    native: 'Việt Nam',
    code: '84',
    continent: 'AS',
    capital: 'Hanoi',
    currency: 'VND',
    languages: ['vi'],
    emoji: '🇻🇳',
    emojiU: 'U+1F1FB U+1F1F3'
  },
  VU: {
    name: 'Vanuatu',
    native: 'Vanuatu',
    code: '678',
    continent: 'OC',
    capital: 'Port Vila',
    currency: 'VUV',
    languages: ['bi', 'en', 'fr'],
    emoji: '🇻🇺',
    emojiU: 'U+1F1FB U+1F1FA'
  },
  WF: {
    name: 'Wallis and Futuna',
    native: 'Wallis et Futuna',
    code: '681',
    continent: 'OC',
    capital: 'Mata-Utu',
    currency: 'XPF',
    languages: ['fr'],
    emoji: '🇼🇫',
    emojiU: 'U+1F1FC U+1F1EB'
  },
  WS: {
    name: 'Samoa',
    native: 'Samoa',
    code: '685',
    continent: 'OC',
    capital: 'Apia',
    currency: 'WST',
    languages: ['sm', 'en'],
    emoji: '🇼🇸',
    emojiU: 'U+1F1FC U+1F1F8'
  },
  XK: {
    name: 'Kosovo',
    native: 'Republika e Kosovës',
    code: '377,381,383,386',
    continent: 'EU',
    capital: 'Pristina',
    currency: 'EUR',
    languages: ['sq', 'sr'],
    emoji: '🇽🇰',
    emojiU: 'U+1F1FD U+1F1F0'
  },
  YE: {
    name: 'Yemen',
    native: 'اليَمَن',
    code: '967',
    continent: 'AS',
    capital: 'Sana\'a',
    currency: 'YER',
    languages: ['ar'],
    emoji: '🇾🇪',
    emojiU: 'U+1F1FE U+1F1EA'
  },
  YT: {
    name: 'Mayotte',
    native: 'Mayotte',
    code: '262',
    continent: 'AF',
    capital: 'Mamoudzou',
    currency: 'EUR',
    languages: ['fr'],
    emoji: '🇾🇹',
    emojiU: 'U+1F1FE U+1F1F9'
  },
  ZA: {
    name: 'South Africa',
    native: 'South Africa',
    code: '27',
    continent: 'AF',
    capital: 'Pretoria',
    currency: 'ZAR',
    languages: ['af', 'en', 'nr', 'st', 'ss', 'tn', 'ts', 've', 'xh', 'zu'],
    emoji: '🇿🇦',
    emojiU: 'U+1F1FF U+1F1E6'
  },
  ZM: {
    name: 'Zambia',
    native: 'Zambia',
    code: '260',
    continent: 'AF',
    capital: 'Lusaka',
    currency: 'ZMW',
    languages: ['en'],
    emoji: '🇿🇲',
    emojiU: 'U+1F1FF U+1F1F2'
  },
  ZW: {
    name: 'Zimbabwe',
    native: 'Zimbabwe',
    code: '263',
    continent: 'AF',
    capital: 'Harare',
    currency: 'USD,ZAR,BWP,GBP,AUD,CNY,INR,JPY',
    languages: ['en', 'sn', 'nd'],
    emoji: '🇿🇼',
    emojiU: 'U+1F1FF U+1F1FC'
  }
}

const payments = {
  cash: {
    name: 'Cash',
    value: 'cash',
    en: 'Cash',
    es: 'Efectivo'
  },
  card: {
    name: 'Card',
    value: 'card',
    en: 'Card',
    es: 'Targeta'
  },
  check: {
    name: 'Check',
    value: 'check',
    en: 'Check',
    es: 'Cheque'
  },
  deposit: {
    name: 'Deposit',
    value: 'deposit',
    en: 'Deposit',
    es: 'Depósito'
  },
  wire_transfer: {
    name: 'Wire Transfer',
    value: 'wire_transfer',
    en: 'Wire Transfer',
    es: 'Transferencia Bancaria'
  },
  digital_transfer: {
    name: 'Digital Transfer',
    value: 'digital_transfer',
    en: 'Digital Transfer',
    es: 'Transferencia Digital'
  },
  other: {
    name: 'Other',
    value: 'other',
    en: 'Other',
    es: 'Otro'
  }
}
const getCountryToSelect = () => {
  const result = []
  Object.keys(countries).map(key => {
    const split = countries[key].currency.split(',')
    let currency
    if (split.length > 0) {
	  currency = split[0]
    } else {
	  currency = countries[key].currency
    }
    result.push({
	  id: key,
	  name: countries[key].name + '(' + countries[key].native + ')',
	  emoji: countries[key].emoji,
	  code: '+' + countries[key].code,
	  currency: currency
    })
  })
  return result
}
const getPaymentToSelect = () => {
  const result = []
  Object.keys(payments).map(key => {
    result.push({
	  key: payments[key].value,
	  name: payments[key].en + '(' + payments[key].es + ')'
    })
  })
  return result
}
const getCurrencyToSelect = () => {
  const result = []
  Object.keys(countries).map(key => {
    const split = countries[key].currency.split(',')
    const currencyId =
	  split.length > 0 ? split[0] : countries[key].currency
    result.push({
	  id: key,
	  name: countries[key].name + '(' + currencyId + ')',
	  emoji: countries[key].emoji,
	  currencyId: currencyId
    })
  })
  return result
}
const getSector = () => {
  return [
    { value: 'agricultural' },
    { value: 'arts' },
    { value: 'automotive' },
    { value: 'construction' },
    { value: 'consultancy' },
    { value: 'drink' },
    { value: 'education' },
    { value: 'entertainment' },
    { value: 'food' },
    { value: 'health' },
    { value: 'home' },
    { value: 'hostelry' },
    { value: 'industrial' },
    { value: 'ironmongery' },
    { value: 'rental' },
    { value: 'restaurant' },
    { value: 'service' },
    { value: 'shops' },
    { value: 'technologies' },
    { value: 'tourism' },
    { value: 'transport' },
    { value: 'real_state' },
    { value: 'others' }
  ]
}

const getUM = () => {
  return [
    {
	  name: 'u',
	  group: 'unit',
	  presentation: 'u'
    },
    { divider: true },
    {
	  name: 'mm',
	  group: 'distance',
	  presentation: 'mm'
    },
    {
	  name: 'cm',
	  group: 'distance',
	  presentation: 'cm'
    },
    {
	  name: 'pp',
	  group: 'distance',
	  presentation: ''
    },
    {
	  name: 'm',
	  group: 'distance',
	  presentation: 'm'
    },
    {
	  name: 'footer',
	  group: 'distance',
	  presentation: ''
    },
    {
	  name: 'yard',
	  group: 'distance',
	  presentation: ''
    },
    {
	  name: 'km',
	  group: 'distance',
	  presentation: 'km'
    },
    {
	  name: 'mll',
	  group: 'distance',
	  presentation: 'mll'
    },
    { divider: true },
    {
	  name: 'c',
	  group: 'temp',
	  presentation: 'ºC'
    },
    {
	  name: 'k',
	  group: 'temp',
	  presentation: 'ºK'
    },
    {
	  name: 'f',
	  group: 'temp',
	  presentation: 'ºF'
    },
    { divider: true },
    {
	  name: 's',
	  group: 'time',
	  presentation: 's'
    },
    {
	  name: 'mt',
	  group: 'time',
	  presentation: 'm'
    },
    {
	  name: 'h',
	  group: 'time',
	  presentation: 'h'
    },
    {
	  name: 'd',
	  group: 'time',
	  presentation: 'd'
    },
    { divider: true },
    {
	  name: 'mg',
	  group: 'ms',
	  presentation: 'mg'
    },
    {
	  name: 'g',
	  group: 'ms',
	  presentation: 'g'
    },
    {
	  name: 'kg',
	  group: 'ms',
	  presentation: 'kg'
    },
    {
	  name: 'ton',
	  group: 'ms',
	  presentation: 't'
    },
    {
	  name: 'lb',
	  group: 'ms',
	  presentation: 'lb'
    },
    { divider: true },
    {
	  name: 'm_s',
	  group: 'velocity',
	  presentation: 'm/s'
    },
    {
	  name: 'km_h',
	  group: 'velocity',
	  presentation: 'km/h'
    },
    {
	  name: 'ms2',
	  group: 'velocity',
	  presentation: 'm/s__2'
    },
    { divider: true },
    {
	  name: 'm3',
	  group: 'vol',
	  presentation: 'm__3'
    },
    {
	  name: 'm2',
	  group: 'vol',
	  presentation: 'm__2'
    },
    {
	  name: 'gl',
	  group: 'vol',
	  presentation: 'gl'
    },
    {
	  name: 'l',
	  group: 'vol',
	  presentation: 'L'
    },
    { divider: true },
    {
	  name: 'bit',
	  group: 'digital',
	  presentation: 'bit'
    },
    {
	  name: 'byte',
	  group: 'digital',
	  presentation: 'byte'
    },
    {
	  name: 'mega',
	  group: 'digital',
	  presentation: 'mega'
    },
    {
	  name: 'tr',
	  group: 'digital',
	  presentation: 'tr'
    },
    {
	  name: 'px',
	  group: 'digital',
	  presentation: 'px'
    },
    {
	  name: 'mPx',
	  group: 'digital',
	  presentation: 'mPx'
    },
    { divider: true },
    {
	  name: 'mhz',
	  group: 'other',
	  presentation: 'mHz'
    },
    {
	  name: 'hz',
	  group: 'other',
	  presentation: 'Hz'
    },
    {
	  name: 'hom',
	  group: 'other',
	  presentation: 'W'
    },
    {
	  name: 'volt',
	  group: 'other',
	  presentation: 'V'
    },
    {
	  name: 'amp',
	  group: 'other',
	  presentation: 'A'
    },
    {
	  name: 'joule',
	  group: 'other',
	  presentation: 'J'
    }
  ]
}

export default {
  continents: continents,
  countries: countries,
  payments: payments,
  getSector: getSector,
  getCountryToSelect: getCountryToSelect,
  getPaymentToSelect: getPaymentToSelect,
  getCurrencyToSelect: getCurrencyToSelect,
  getUM: getUM
}
