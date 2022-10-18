# Retro Platforma: Izsoļu un sludinājumu portāls

# Projekta apraksts

Šis ir mācību projekts Latvijas Universitātes kvalifikācijas darba repozitorijam.
Mājas lapa, kurā ir realizēts sludinājumu pivienošana un piedalīšana izsolēs.

# Izmantotās tehnoloģijas

Projektā tiek izmantots:
- HTML
- CSS
- Javascript
- PHP
- MySQL
- VueJS (+Vuetify, +Vuex)
- Laravel
- PayPal API

# Izmantotie avoti

- [Lararvel + Vue autorizācijas lietošanas rokasgrāmata](https://medium.com/@ripoche.b/create-a-spa-with-role-based-authentication-with-laravel-and-vue-js-ac4b260b882f)
  , kas iekļauj
- [Vue-Auth (JWT)](https://websanova.com/docs/vue-auth/methods/index)
- [Lararvel JWT-Auth](https://jwt-auth.readthedocs.io/en/develop/)

–––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––

- [Vuetify komponentu atsauksme](https://vuetifyjs.com/en/components/api-explorer/)

–––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––

- [Laravel __6.x__ dokumentācijas atsauksme](https://laravel.com/docs/6.x/readme)

–––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––

- [PayPal saprašana](https://developer.paypal.com/docs/checkout/reference/upgrade-integration/#1-understand-the-javascript-sdk-checkout-flow)
- [PayPal skripts](https://developer.paypal.com/docs/checkout/reference/upgrade-integration/#4-set-up-the-transaction)

# Uzstādīšanas instrukcijas
1. Lai lietotu git lejupielādējam [Git for windows](https://git-scm.com/download/win)
2. Instalējam git.
3. [Lejupielādējam WAMP](https://sourceforge.net/projects/wampserver/), lai varētu izveidot webserveri.
4. Instalējam WAMP.
5. Pārliecinamies par WAMP darbību atverot adresi http://127.0.0.1
6. !Jāizveido tukšo datubāzi ar nosaukumu __auction_platform__
6. Dodamies uz WAMP atrašanās vietu (parasti c:\wamp{64}\www).
7. Veicam labo klikšķi un izvēlamies opciju "git bash here" un izpildam zemāk raksīto komandu.
`git clone https://github.com/rvtprog-kvalifikacija-20/d42-PavelsZuravlovs-RetroPlatforma.git`
8. Lejupielādējam un instalējam [Composer](https://getcomposer.org/download/)
9. Lejupielādējam un instalējam [NodeJS kopā ar NPM (LTS)](https://nodejs.org/en/)
10. Dodamies uz projekta atrašanās vietu (tagad tas ir c:\wamp{64}\www\d42-PavelsZuravlovs-RetroPlatforma).
11. Izveidojam __.env__ failu
12. Dodamies uz [PasteBin](https://pastebin.com/Pq1BLe1w), kopējam visas rindas un ievietojam kodu __.env__ failā
13. Taisām vaļā 2 termināļus (CMD/bash/PowerShell)
14. Izpildam sekojošas komandas pēc kārtas 1. terminālā (ja būs prompti ar [yes/no], atbildam 'yes'):
```
composer i
php artisan migrate
php artisan jwt:secret
php artisan storage:link
php artisan serve
```
15. Izpildam sekojošas komandas pēc kārtas 2. terminālā (ja būs prompti ar [yes/no], atbildam 'yes'):
```
cd ./frontend/
npm i
npm run serve
```

16. Neaizverot terminālus, atveram adresi http://localhost:8080/

–––––––––––––––––––––––––––––––––––––––––––––––

# Datubāze
Lai ērtāk strādāt ar portālu un gribat aizpildīto datubāzi, iztukšojiet __auction_platform__ datubāzi (neizdzēsiet pašu DB) un importējiet šo failu:
- Database dump (tables + contents): https://pastebin.com/raw/0a5dKt4q
Lai piesliegties sistēmā, drīkst izmantot vai lietotājvārdu, vai e-pastu; visiem izveidotājiem lietotājiem parole ir `12345678`

# PayPal
Sistēma izmanto __sandbox__ režīmu, tas nozīme, ka sistēmā tagad atrodas tikai __development__ konti.

PayPal login: `projektsTest@mail.com`

PayPal password: `12345678`

Naudas apjoms: `100 000`
