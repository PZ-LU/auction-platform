# Izsoļu un sludinājumu portāls

# Projekta apraksts

Šis ir mācību projekts Latvijas Universitātes kvalifikācijas darba repozitorijam.
Mājas lapa, kurā ir realizēts sludinājumu pivienošana un piedalīšana izsolēs.

# Izmantotās tehnoloģijas

Projektā tiek izmantots:
- HTML
- CSS
- Javascript
- PHP
- PostgreSQL
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
1. Lai lietotu git, lejupielādējam [Git](https://git-scm.com/downloads) un instalējam.
2. [Lejupielādējam PostgreSQL](https://www.postgresql.org/download/), lai varētu izveidot datubāzes serveri un instalējam.
3. Iekš Postgres jāizveido tukšo datubāzi ar nosaukumu __auction_platform__
4. Izvēlāmies vietu projekta palaišanai
5. Veicam labo klikšķi un izvēlamies opciju "git bash here" un izpildam zemāk raksīto komandu.
`git clone https://github.com/PZ-LU/auction-platform.git`
6. Lejupielādējam un instalējam [Composer](https://getcomposer.org/download/)
7. Lejupielādējam un instalējam [NodeJS kopā ar NPM (LTS)](https://nodejs.org/en/)
8. Dodamies uz projekta atrašanās vietu
9. Izveidojam __.env__ failu un aizpildām mainīgus pēc __.env.example__ piemēra
10. Dodamies uz direktoriju `frontend/`
11. Izveidojam __.env__ failu un aizpildām mainīgus pēc __.env.example__ piemēra
12. Taisām vaļā 2 termināļus (CMD/bash/PowerShell/MSYS/...)
13. Izpildam sekojošas komandas pēc kārtas 1. terminālī (ja būs prompti ar [yes/no], atbilde - 'yes'):
```
composer i
php artisan migrate
php artisan jwt:secret
php artisan storage:link
php artisan serve
```
14. Izpildam sekojošas komandas pēc kārtas 2. terminālī (ja būs prompti ar [yes/no], atbilde - 'yes'):
```
cd ./frontend/
npm i
npm run serve
```

15. Dodamies uz adresi http://localhost:8080/

––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––

# Datubāze un dati
Lai ērtāk strādāt ar portālu un gribat aizpildīto datubāzi, lietotnē ir nokonfigurēta Laravel datu ievietošana:

`php artisan db:seed`

Sistēmas pieslēgšanai var izmantot lietotājvārdu vai e-pastu:
 * visiem parastiem reģistrētiem lietotājiem parole ir `12345678`
 * visiem administartoriem un virsadministratoram `mainAdmin` parole ir `secret`

# PayPal
Sistēma izmanto __sandbox__ režīmu, tas nozīme, ka sistēmā tagad atrodas tikai __development__ konti (izmantojot pareizus __.env__ vērtības (client_id)).

PayPal login: `projektsTest@mail.com`

PayPal password: `12345678`

Naudas apjoms: `100 000`

# Testēšana
Izmantots `WebDriverIO`. Goto `auction-platform-frontend-tests` un palaižam:

`npm run cucumber`
