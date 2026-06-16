# Dokumentacja projektu – Komis Laravel

System CMS dla ogłoszeń motoryzacyjnych.

---

## Autorzy i podział ról

### Rafał Burbeło
**Moduł:** Strona główna + lista ogłoszeń + filtrowanie + panel użytkownika i admina

**Frontend:**
- Strona główna
- Lista aut (grid / lista)
- Filtry: marka, model, cena (od-do), rok, paliwo, skrzynia biegów, miasto
- Sortowanie (cena, data, popularność)
- Paginacja
- Panel admina: zarządzanie użytkownikami
- Panel użytkownika: moje ogłoszenia

**Backend:**
- API/listings
- Filtrowanie (Laravel query)
- Sortowanie + paginacja
- Zwiększanie views_count
- Panel admina: banowanie (bans)
- Panel usera (CRUD ogłoszeń)

### Jakub Czarnik
**Moduł:** Widok ogłoszenia + komentarze + chat + panel admina

**Frontend:**
- Strona pojedynczego auta: galeria zdjęć, dane auta, opis
- Sekcja komentarzy
- Chat między użytkownikami
- Panel admina: moderacja

**Backend:**
- API/listings/{id}
- Komentarze do ogłoszeń
- Chat (wiadomości user - user)
- Panel admina: usuwanie ogłoszeń, moderacja komentarzy

### Jakub Dobek
**Moduł:** Serwisy (usługi motoryzacyjne) – mechanik, naprawy, detailing itd.

**Frontend:**
- Lista usług
- Filtry (miasto, cena)
- Widok usługi: opis, cena, opinie
- Dodawanie usługi (formularz)

**Backend:**
- API/services
- CRUD usług: dodaj, edytuj, usuń
- Opinie (service_reviews)
- Średnia ocen
- Licznik wyświetleń

### Vladyslav Denysiuk
**Moduł:** Autoryzacja i użytkownicy

**Frontend:**
- Logowanie
- Rejestracja
- Walidacja formularzy

**Backend:**
- Rejestracja (users)
- Logowanie (Laravel Auth)
- Hashowanie haseł
- Middleware: user, admin
- Obsługa banów (is_banned)
- Sesje / tokeny

---

## Użyte technologie

| Technologia | Wersja | Zastosowanie |
|-------------|--------|--------------|
| **Laravel** | 13.15.0 | Framework backendowy MVC |
| **PHP** | ^8.2 | Język programowania |
| **Bootstrap** | 5.3.0 | Frontend – stylowanie, responsywność, komponenty UI |
| **Tailwind CSS** | 4.x | Frontend – dodatkowe style (via Vite) |
| **Vite** | 7.x | Bundler assetów frontendowych |
| **PostgreSQL** | – | Relacyjna baza danych (z obsługą pg_trgm) |
| **MySQL** | – | Alternatywna baza danych (obsługiwana przez config) |
| **Leaflet.js** | 1.9.4 | Mapy interaktywne (OpenStreetMap) |
| **Google Maps** | – | Osadzanie map w widokach |
| **Font Awesome** | 6.x | Ikony |
| **Composer** | – | Menadżer zależności PHP |
| **NPM** | – | Menadżer zależności JS |

---

## Przeznaczenie aplikacji

Aplikacja pełni funkcję platformy do wystawiania i przeglądania ogłoszeń motoryzacyjnych (sprzedaż samochodów) oraz usług warsztatowych (mechanika, detailing, naprawy). Umożliwia:

- Przeglądanie ogłoszeń aut z zaawansowanym filtrowaniem i wyszukiwaniem
- Dodawanie i zarządzanie ogłoszeniami przez użytkowników
- Wystawianie usług motoryzacyjnych z opiniami i ocenami
- Komunikację między użytkownikami przez wbudowany czat
- Zarządzanie treścią przez panel administratora

---

## Opis funkcjonalności

### Moduł Rafała Burbeło – Strona główna, listingi, panel admina/user

- **Strona główna**: lista polecanych ogłoszeń (6 najnowszych), popularne tagi, statystyki (liczba ogłoszeń, marek, usług)
- **Lista ogłoszeń**: widok grid z paginacją (10/12/20 na stronę)
- **Filtrowanie**: marka, model, cena (od-do), rok, paliwo, skrzynia biegów, nadwozie, miasto, lokalizacja (mapa, współrzędne), tagi, przebieg, moc, pojemność silnika
- **Wyszukiwanie**: pełnotekstowe z użyciem pg_trgm (similarity) po tytule, opisie, mieście, kolorze, marce, modelu
- **Sortowanie**: cena (ros./malej.), rok, przebieg, popularność, najnowsze
- **Panel admina**: zarządzanie użytkownikami (CRUD, banowanie – toggleBan, zabezpieczenie przed banowaniem siebie)
- **Panel użytkownika**: lista własnych ogłoszeń z możliwością edycji/usunięcia, dashboard ze statystykami
- **Autocomplete marek/modeli**: wyszukiwanie przez similarity() pg_trgm w formularzu dodawania/edycji ogłoszenia
- **Sortowanie i paginacja**: per_page (10, 12, 20, 50), sortowanie po cenie, roku, przebiegu, popularności

**Pliki:**
- `app/Http/Controllers/ListingController.php` – 329 linii (home, index, show, create, store, edit, update, destroy, search, searchModels)
- `app/Http/Controllers/Admin/UserAdminController.php` – 93 linie (index, store, update, destroy, toggleBan)
- `app/Http/Controllers/ListingImageController.php`
- `app/Http/Controllers/UserPanelController.php`
- `app/Models/Listing.php` – 77 linii (relacje: brand, carModel, fuel, transmission, bodyType, user, images, tags, conversations)
- `resources/views/home.blade.php`
- `resources/views/listings/index.blade.php`
- `resources/views/listings/_filters.blade.php`
- `resources/views/listings/_card.blade.php`
- `resources/views/admin/users.blade.php`

### Moduł Jakuba Czarnika – Widok ogłoszenia, komentarze, chat, admin

- **Widok ogłoszenia**: galeria zdjęć (Bootstrap carousel), dane techniczne auta (marka, model, rok, paliwo, skrzynia, moc, pojemność, przebieg, kolor), opis, cena, lokalizacja na mapie Google
- **Komentarze**: sekcja opinii dla ogłoszeń
- **Chat**: konwersacje między użytkownikami, lista konwersacji z licznikiem nieprzeczytanych wiadomości, obsługa zarówno listingów jak i usług, oznaczenie wiadomości jako przeczytane (is_read, visit_time)
- **Panel admina**: moderacja treści

**Pliki:**
- `app/Http/Controllers/ConversationController.php` – 92 linie (createOrOpenConversation, createOrOpenServiceConversation, index, show)
- `app/Http/Controllers/MessageController.php`
- `app/Models/Conversation.php`
- `app/Models/Message.php`
- `database/migrations/...create_conversations_table.php`
- `database/migrations/...create_messages_table.php`
- `database/migrations/...add_service_id_to_conversations_table.php`
- `resources/views/conversations/index.blade.php`
- `resources/views/listings/show.blade.php`

### Moduł Jakuba Dobka – Serwisy (usługi motoryzacyjne)

- **Lista usług**: widok kafelkowy z paginacją (12/str)
- **Filtrowanie**: miasto (select z listy miast), cena (min/max), wyszukiwanie tekstowe (tytuł, opis, miasto)
- **Sortowanie**: cena (ros./malej.), data, popularność (views_count), najlepiej oceniane (withAvg)
- **Widok usługi**: karuzela zdjęć, opis, cena, średnia ocen (gwiazdki full/half/empty), liczba opinii, miasto, data, wystawiający, mapa Google, licznik wyświetleń
- **CRUD**: create/store (z mapą Leaflet + reverse geocoding Nominatim), edit/update (z zarządzaniem zdjęciami: delete_images checkbox, new_images upload), destroy (usunięcie plików z dysku)
- **Opinie**: addReview/updateReview (1 opinia na użytkownika na usługę, duplicate check), rating 1-5, comment max 1000 znaków
- **Średnia ocen**: metoda `averageRating()` w modelu, sortowanie `best_rated` przez `withAvg()`
- **Panel admina**: ServiceAdminController – lista wszystkich usług, edycja statusu, usuwanie

- **API/services**: REST API (index, show, store, update, destroy) – `GET/POST/PUT/DELETE /api/services`

**Pliki:**
- `app/Http/Controllers/ServiceController.php` – 286 linii (index, show, create, store, edit, update, destroy, addReview, updateReview, myServices)
- `app/Http/Controllers/Api/ServiceApiController.php` – 62 linie (index, show, store, update, destroy)
- `app/Http/Controllers/Admin/ServiceAdminController.php` – 76 linii
- `app/Models/Service.php` – 53 linie (relacje: user, reviews, images, conversations; metoda averageRating)
- `app/Models/ServiceReview.php` – 27 linii
- `resources/views/services/index.blade.php` – 302 linie
- `resources/views/services/show.blade.php` – 225 linii
- `resources/views/services/create.blade.php` – 131 linii
- `resources/views/services/edit.blade.php` – 181 linii
- `resources/views/services/my-services.blade.php` – 88 linii
- `resources/views/admin/services.blade.php` – 593 linie
- `routes/web.php` – linie 58-99, 134-139
- `routes/api.php` – linia 10

### Moduł Vladyslava Denysiuka – Autoryzacja i użytkownicy

- **Rejestracja**: walidacja (name, email unique, password min:8 + confirmed), hash bcrypt, przekierowanie na login z flash message
- **Logowanie**: walidacja credentials, regeneracja sesji, obsługa banned (Auth::logout + session invalidate + komunikat)
- **Wylogowanie**: destrukcja sesji, regeneracja tokena CSRF
- **Profil użytkownika**: edycja danych (name, email) z walidacją unique, zmiana hasła (current_password, new password + confirmation)
- **Middleware**: `admin` alias w bootstrap/app.php, sprawdza `$user->is_admin`, 403 dla nieautoryzowanych, redirect na login dla gości
- **Obsługa banów**: pole `is_banned` w tabeli users, blokada logowania, admin może toggle ban (zabezpieczenie przed banowaniem siebie)
- **Sesje**: oparte o bazę danych (SESSION_DRIVER=database)

**Pliki:**
- `app/Http/Controllers/AuthController.php` – 81 linii
- `app/Http/Controllers/ProfileController.php` – 42 linie (edit, update, password)
- `app/Http/Middleware/AdminMiddleware.php` – 27 linii
- `app/Models/User.php` – 66 linii (fillable: name, email, password, is_admin, is_banned; relacje: listings, messages, conversations)
- `bootstrap/app.php` – rejestracja middleware 'admin'
- `routes/web.php` – linie 24-34, 109-111
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/user/profile.blade.php`

### Wspólne funkcjonalności

- **Walidacja**: backend (`$request->validate()`) z polskimi komunikatami błędów; frontend (`is-invalid`, `@error`, `required`)
- **Responsywność**: Bootstrap 5.3 grid (col-lg, col-md, col-), flex, table-responsive, navbar z togglerem
- **Obsługa błędów HTTP**: abort(403), abort(404), findOrFail(), firstOrFail(), flash messages (success/error)
- **ORM**: Eloquent z relacjami (belongsTo, hasMany, belongsToMany), query builder z bind parameters (whereRaw)
- **Seedery**: DatabaseSeeder uruchamia ImageSeeder (z rzeczywistymi zdjęciami z `database/seed-images/`), CarDataSeeder, ListingSeeder (8 realistycznych ogłoszeń aut), TagSeeder, ServiceSeeder

---

## Schemat ERD

![Diagram ERD](ERD.png)

### Relacje:

- `users` 1:N `listings`, `services`, `service_reviews`, `messages`
- `brands` 1:N `car_models`
- `listings` N:M `tags` (przez `listing_tags`)
- `listings` N:M `images` (przez `listing_images`)
- `services` N:M `images` (przez `service_images`)
- `listings` 1:N `conversations`
- `services` 1:N `conversations`
- `conversations` 1:N `messages`

### Tabele:

| Tabela | Opis |
|--------|------|
| `users` | Użytkownicy (name, email, password, is_admin, is_banned) |
| `brands` | Marki samochodów (name) |
| `car_models` | Modele samochodów (brand_id, name) |
| `fuels` | Rodzaje paliwa (name) |
| `transmissions` | Skrzynie biegów (name) |
| `body_types` | Typy nadwozia (name) |
| `listings` | Ogłoszenia aut (user_id, brand_id, model_id, fuel_id, transmission_id, body_type_id, title, description, price, status, city, year, mileage, engine_capacity, power_hp, color, views_count, latitude, longitude) |
| `tags` | Tagi (name) |
| `listing_tags` | Relacja listing-tag (listing_id, tag_id) – PK composite |
| `images` | Zdjęcia (original_name, file_name, file_type) |
| `listing_images` | Relacja listing-zdjęcie (listing_id, image_id, sort_order) – PK composite |
| `services` | Usługi (user_id, title, description, price, city, status, views_count) |
| `service_reviews` | Opinie o usługach (service_id, user_id, rating, comment) |
| `service_images` | Relacja usługa-zdjęcie (service_id, image_id, sort_order) – PK composite |
| `conversations` | Konwersacje (listing_id nullable, service_id nullable, user2_id) |
| `messages` | Wiadomości (conversation_id, sender_id, content, is_read, visit_time) |

---

## Kierunki dalszego rozwoju

### Co zrobilibyśmy inaczej

1. **Docker Compose** – projekt powinien być gotowy do uruchomienia przez `docker compose up`. Należy dodać `Dockerfile` (PHP-FPM + Nginx) oraz `docker-compose.yml` z serwisami: app, nginx, postgresql, pgadmin
2. **REST API** – brakuje wydzielonego API (`routes/api.php`, kontrolery API, Sanctum/Passport do autoryzacji tokenem, Resource classes, API documentation)
3. **Testy automatyczne** – brak unit/feature testów; warto dodać testy PHPUnit dla kluczowych ścieżek biznesowych (CRUD listingów, serwisów, autoryzacja)
4. **FormRequesty** – walidacja odbywa się inline w kontrolerach; lepiej wydzielić do klas FormRequest dla czytelności i reużywalności
5. **Soft deletes** – usuwanie ogłoszeń/usług jest twarde (hard delete); lepiej użyć `SoftDeletes` trait, aby zachować historię
6. **Dedyuplikacja views_count** – licznik wyświetleń incrementuje przy każdym wejściu, bez uwzględniania botów ani odświeżeń przez właściciela; warto dodać sesyjną/ip-based deduplikację
7. **Paginacja opinii** – opinie są ładowane jako kolekcja (`$service->reviews`), bez paginacji; dla dużej liczby opinii może być problem wydajnościowy

### Co wymaga dopracowania

1. **Moderacja treści** – panel admina ma podstawowe funkcje, ale brakuje dedykowanego widoku do moderacji komentarzy/opinii
2. **Walidacja JavaScript** – frontendowa walidacja ogranicza się do HTML5 `required` i `@error` directives; warto dodać walidację po stronie JS (np. Alpine.js lub Livewire)
3. **Powiadomienia** – brak powiadomień email/push o nowych wiadomościach w czacie
4. **Upload zdjęć** – brak generowania miniaturek (thumbnails), watermarków, konwersji do WebP; zdjęcia przechowywane w oryginalnym rozmiarze
5. **Obsługa wielu języków** – tylko język polski; brak mechanizmu translacji (laravel-lang jest w require-dev)
6. **Logowanie przez OAuth** – brak logowania przez Google/Facebook/GitHub (Socialite)
7. **CI/CD** – brak konfiguracji GitHub Actions do automatycznych testów/deploy
8. **Rate limiting** – brak ograniczeń na wysyłanie wiadomości/dodawanie ogłoszeń

---

## Instrukcja uruchomienia aplikacji

### Wymagania

- PHP ^8.2 z rozszerzeniami: `pgsql`, `mbstring`, `xml`, `curl`, `gd`, `fileinfo`
- Composer 2.x
- Node.js 20+ z npm
- PostgreSQL 15+ (lub SQLite dla trybu developerskiego)
- Rozszerzenie PostgreSQL `pg_trgm` (dla wyszukiwania similarity)

### Krok po kroku

1. **Klonowanie repozytorium**
   ```bash
   git clone <adres-repozytorium>
   cd Komis-Laravel
   ```

2. **Instalacja zależności PHP**
   ```bash
   composer install
   ```

3. **Konfiguracja środowiska**
   ```bash
   cp .env.example .env
   ```
   Edytuj plik `.env` i skonfiguruj bazę danych:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=komis
   DB_USERNAME=postgres
   DB_PASSWORD=postgres
   ```

4. **Generowanie klucza aplikacji**
   ```bash
   php artisan key:generate
   ```

5. **Instalacja zależności frontendowych**
   ```bash
   npm install
   npm run build
   ```

6. **Tworzenie bazy danych**
   ```sql
   CREATE DATABASE komis;
   CREATE EXTENSION IF NOT EXISTS pg_trgm;
   ```

7. **Migracja i seedowanie bazy danych**
   ```bash
   php artisan migrate --seed
   ```

8. **Linkowanie storage**
   ```bash
   php artisan storage:link
   ```

9. **Uruchomienie serwera deweloperskiego**
   ```bash
   php artisan serve
   ```
   Aplikacja dostępna pod adresem: http://localhost:8000

10. **Uruchomienie (opcjonalnie) – tryb deweloperski z Vite + queue**
    ```bash
    npm run dev
    # w osobnym terminalu:
    php artisan queue:listen
    ```

### Dane testowe

Po uruchomieniu `php artisan migrate --seed` dostępni są użytkownicy:
- `test@example.com` / `password` – zwykły użytkownik
- `second@example.com` / `password` – zwykły użytkownik

Aby nadać uprawnienia admina, wykonaj w konsoli:
```bash
php artisan tinker
User::where('email', 'test@example.com')->update(['is_admin' => true]);
```

### Użyteczne komendy

| Komenda | Opis |
|---------|------|
| `php artisan migrate` | Uruchomienie migracji |
| `php artisan migrate:fresh --seed` | Reset bazy + seedowanie |
| `php artisan storage:link` | Linkowanie storage |
| `php artisan queue:listen` | Obsługa kolejek |
| `npm run dev` | Tryb deweloperski Vite (HMR) |
| `npm run build` | Budowa assetów na produkcję |
| `composer run dev` | Uruchomienie serwera + queue + logs + Vite jednocześnie |

---

## Reprezentatywny przebieg użycia aplikacji

### Scenariusz 1: Sprzedawca wystawia ogłoszenie samochodu, kupujący pisze na czacie

1. **Rejestracja** – nowy użytkownik wypełnia formularz rejestracji (name, email, password + confirmation)
2. **Logowanie** – użytkownik loguje się, system regeneruje sesję, sprawdza czy nie jest zbanowany
3. **Dodanie ogłoszenia** – użytkownik klika "Dodaj ogłoszenie" w navbarze, wypełnia formularz:
   - Wybiera markę (autocomplete z similarity search)
   - Wybiera model (filtrowany po marce)
   - Ustawia rok produkcji, paliwo, skrzynię biegów, typ nadwozia
   - Podaje cenę, przebieg, moc, pojemność silnika, kolor
   - Wybiera lokalizację na mapie (kliknięcie ustawia pinezkę i wykrywa miasto)
   - Dodaje tagi (checkboxes)
   - Zatwierdza formularz (walidacja: wszystkie pola wymagane, range checks)
4. **Dodanie zdjęć** – po zapisie ogłoszenia użytkownik zostaje przekierowany do strony dodawania zdjęć (wiele plików, max 2MB każdy)
5. **Ogłoszenie widoczne** – ogłoszenie pojawia się na liście z statusem 'active'
6. **Kupujący szuka auta** – inny użytkownik wchodzi na stronę główną, widzi 6 polecanych ogłoszeń i statystyki; przechodzi do listy ogłoszeń
7. **Filtrowanie** – kupujący ustawia filtry: marka=Audi, cena do 50000 PLN, paliwo=diesel, sortuje po cenie rosnąco; paginacja pokazuje 12 wyników na stronę
8. **Widok ogłoszenia** – kupujący klika w wybrane ogłoszenie, widzi:
   - Karuzelę zdjęć
   - Pełne dane techniczne (marka, model, rok, paliwo, skrzynia, moc, pojemność, przebieg, kolor)
   - Opis samochodu
   - Cenę i lokalizację na mapie Google
   - Licznik wyświetleń (zwiększony o 1)
9. **Kontakt przez czat** – kupujący klika "Napisz do sprzedawcy", system tworzy konwersację (firstOrCreate), przekierowuje do widoku czatu; kupujący pisze wiadomość
10. **Odpowiedź sprzedawcy** – sprzedawca widzi w panelu konwersacji nową wiadomość (licznik nieprzeczytanych), odpowiada; wiadomość oznaczona jako przeczytana (is_read, visit_time)
11. **Zarządzanie** – sprzedawca może edytować ogłoszenie (zmiana danych, zdjęć) lub usunąć je w panelu "Moje ogłoszenia"

### Scenariusz 2: Mechanik dodaje usługę, klient wystawia opinię

1. **Dodanie usługi** – zalogowany użytkownik (mechanik) klika "Dodaj usługę" na liście usług, wypełnia formularz:
   - Tytuł, opis, cena
   - Kliknięcie na mapie Leaflet uruchamia reverse geocoding (Nominatim API) – automatycznie wpisuje miasto
   - Dodaje zdjęcia (opcjonalnie, wiele plików)
   - Zatwierdza (walidacja: title required string max:255, description required, price required numeric min:0)
2. **Przeglądanie usług** – klient wchodzi na listę usług, filtruje po mieście (select z dostępnych miast) i cenie (min/max), wyszukuje po nazwie; sortuje po ocenie malejąco
3. **Widok usługi** – klient widzi:
   - Karuzelę zdjęć usługi
   - Średnią ocenę (gwiazdki full/half/empty)
   - Opis i szczegóły (miasto, data dodania, wystawiający)
   - Mapę Google z lokalizacją
   - Cenę i przycisk "Napisz do usługodawcy"
4. **Czat** – klient klika "Napisz do usługodawcy", system tworzy konwersację dla usługi (service_id), klient wysyła wiadomość z pytaniem o termin
5. **Opinia** – po skorzystaniu z usługi klient wraca na stronę usługi, wystawia ocenę (1-5) i komentarz (opcjonalny, max 1000 znaków); system sprawdza czy użytkownik już dodał opinię (duplicate check); średnia ocen automatycznie się aktualizuje
6. **Edycja opinii** – jeśli klient już dodał opinię, widzi formularz edycji zamiast dodawania; może zmienić ocenę i komentarz
7. **Panel usługodawcy** – mechanik wchodzi w "Moje usługi", widzi listę swoich usług z liczbą wyświetleń, może edytować/usunąć każdą
8. **Panel admina** – administrator w panelu admina widzi wszystkie usługi, może zmienić status (active/inactive), edytować lub usunąć
