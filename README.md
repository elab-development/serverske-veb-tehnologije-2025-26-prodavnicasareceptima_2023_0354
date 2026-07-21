# Prodavnica sa receptima

Laravel API za veb prodavnicu namirnica povezanu sa receptima. Korisnik na osnovu sastojaka
koje unese može da dobije predloge recepata (preko Spoonacular API-ja), a obrnuto - na osnovu
izabranog recepta iz aplikacije, svi njegovi sastojci se automatski dodaju u korpu, spremni za
kupovinu.

## Funkcionalnosti

- Autentifikacija preko tokena (Laravel Sanctum) sa rolama `admin` i `customer`
- CRUD upravljanje kategorijama, proizvodima i receptima (admin-only izmene, javno čitanje)
- Korpa i narudžbine sa DB transakcijom prilikom checkout-a (sve uspe zajedno ili se sve poništi)
- Upload slika za proizvode i recepte (Storage disk `public`)
- Integracija sa Spoonacular API-jem - predlog recepata na osnovu unetih sastojaka
- Integracija sa Open Food Facts API-jem - nutritivni podaci za proizvod po nazivu
- Keširanje odgovora spoljnih API-ja (Spoonacular 60 min, Open Food Facts 24h)
- Paginacija, filtriranje (kategorija, cenovni opseg) i pretraga po nazivu za proizvode
- Admin statistika (top prodavani proizvodi, prihod po kategoriji) kroz JOIN + agregacione SQL upite

## Tehnologije

- PHP ^8.3
- Laravel Framework ^13.8
- Laravel Sanctum ^4.0 (autentifikacija preko tokena)
- Laravel Tinker ^3.0
- MySQL (baza podataka)
- Laravel HTTP Client (`Http` facade) za pozive ka Spoonacular i Open Food Facts API-jima
- Laravel Cache (`Cache` facade) za keširanje odgovora spoljnih servisa

## Pokretanje projekta lokalno

1. Kloniraj repozitorijum:
   ```bash
   git clone <URL_REPOZITORIJUMA>
   cd serverske-veb-tehnologije-2025-26-prodavnicasareceptima_2023_0354
   ```

2. Instaliraj PHP zavisnosti:
   ```bash
   composer install
   ```

3. Kopiraj `.env.example` u `.env` i podesi konekciju ka bazi i API ključ:
   ```bash
   cp .env.example .env
   ```
   U `.env` postavi:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=recepti_prodavnica
   DB_USERNAME=root
   DB_PASSWORD=

   SPOONACULAR_API_KEY=tvoj_spoonacular_kljuc
   ```

4. Generiši aplikacioni ključ:
   ```bash
   php artisan key:generate
   ```

5. Kreiraj MySQL bazu (npr. preko `mysql` klijenta ili HeidiSQL/phpMyAdmin):
   ```sql
   CREATE DATABASE recepti_prodavnica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

6. Pokreni migracije i napuni bazu test podacima:
   ```bash
   php artisan migrate --seed
   ```

7. Poveži javni storage disk (potrebno za prikaz upload-ovanih slika):
   ```bash
   php artisan storage:link
   ```

8. Pokreni razvojni server:
   ```bash
   php artisan serve
   ```

API je zatim dostupan na `http://127.0.0.1:8000/api` (ili na portu koji izabereš uz `--port`).

## Test nalozi

Nakon `php artisan migrate --seed`, dostupan je sledeći nalog:

- **Admin:** `admin@example.com` / `password`

Ostali seed korisnici (customer nalozi) generisani su nasumično preko Faker-a (nazivi i email
adrese se razlikuju iz seeda u seed), ali svi imaju istu lozinku: `password`. Listu trenutnih
korisnika možeš proveriti preko:
```bash
php artisan tinker --execute="App\Models\User::select('email','role')->get()->each(fn(\$u) => print(\$u->email . ' - ' . \$u->role . PHP_EOL));"
```

## Testiranje API-ja

API se testira preko Postman-a. U folderu [`postman/`](postman/) nalaze se:

- `recepti_prodavnica.postman_collection.json` - kolekcija svih ruta, organizovana po resursima
  (Auth, Categories, Products, Recipes, External APIs, Cart, Orders, Statistics)
- `recepti_prodavnica.postman_environment.json` - environment sa varijablama `base_url`,
  `admin_token`, `customer_token`, `customer2_token` (token vrednosti se popunjavaju ručno nakon
  login zahteva)

Uvezi oba fajla u Postman, izaberi environment "Recepti Prodavnica" i pokreni zahtev za login da
dobiješ token koji zatim upisuješ u odgovarajuću environment varijablu.

## GitHub repozitorijum

https://github.com/elab-development/serverske-veb-tehnologije-2025-26-prodavnicasareceptima_2023_0354
