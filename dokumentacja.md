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
**Moduł:** Widok ogłoszenia + chat + panel admina (słowniki) + dodawanie ogłoszeń

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
| **PHP** | ^8.4 | Język programowania |
| **Bootstrap** | 5.3.0 | Frontend – stylowanie, responsywność, komponenty UI |
| **Tailwind CSS** | 4.x | Frontend – dodatkowe style (via Vite) |
| **Vite** | 7.x | Bundler assetów frontendowych |
| **PostgreSQL** |18.4 | Relacyjna baza danych (z obsługą pg_trgm) |
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

**Endpointy API:** `GET/POST/PUT/DELETE /api/listings`

**Pliki:**
- `app/Http/Controllers/ListingController.php`
- `app/Http/Controllers/Api/ListingApiController.php`
- `app/Http/Controllers/Admin/UserAdminController.php`
- `app/Http/Controllers/ListingImageController.php`
- `app/Http/Controllers/UserPanelController.php`
- `app/Models/Listing.php`
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

**Endpointy API:** `GET /api/listings/{id}`

**Pliki:**
- `app/Http/Controllers/ConversationController.php`
- `app/Http/Controllers/MessageController.php`
- `app/Models/Conversation.php`
- `app/Models/Message.php`
- `resources/views/conversations/index.blade.php`
- `resources/views/listings/show.blade.php`

### Moduł Jakuba Dobka – Serwisy (usługi motoryzacyjne)

- **Lista usług**: widok kafelkowy z paginacją (12/str)
- **Filtrowanie**: miasto (select z listy miast), cena (min/max), wyszukiwanie tekstowe (tytuł, opis, miasto)
- **Sortowanie**: cena (ros./malej.), data, popularność (views_count), najlepiej oceniane (withAvg)
- **Widok usługi**: karuzela zdjęć, opis, cena, średnia ocen (gwiazdki full/half/empty), liczba opinii, miasto, data, wystawiający, mapa Google, licznik wyświetleń
- **CRUD**: create/store (z mapą Leaflet + reverse geocoding Nominatim), edit/update (z zarządzaniem zdjęciami), destroy (usunięcie plików z dysku)
- **Opinie**: addReview/updateReview (1 opinia na użytkownika na usługę, duplicate check), rating 1-5, comment max 1000 znaków
- **Średnia ocen**: metoda `averageRating()` w modelu, sortowanie `best_rated` przez `withAvg()`

**Endpointy API:** `GET/POST/PUT/DELETE /api/services`

**Pliki:**
- `app/Http/Controllers/ServiceController.php`
- `app/Http/Controllers/Api/ServiceApiController.php`
- `app/Http/Controllers/Admin/ServiceAdminController.php`
- `app/Models/Service.php`
- `app/Models/ServiceReview.php`
- `resources/views/services/index.blade.php`
- `resources/views/services/show.blade.php`
- `resources/views/services/create.blade.php`
- `resources/views/services/edit.blade.php`
- `resources/views/services/my-services.blade.php`
- `resources/views/admin/services.blade.php`

### Moduł Vladyslava Denysiuka – Autoryzacja i użytkownicy

- **Rejestracja**: walidacja (name, email unique, password min:8 + confirmed), hash bcrypt, przekierowanie na login z flash message
- **Logowanie**: walidacja credentials, regeneracja sesji, obsługa banned (Auth::logout + session invalidate + komunikat)
- **Wylogowanie**: destrukcja sesji, regeneracja tokena CSRF
- **Profil użytkownika**: edycja danych (name, email) z walidacją unique, zmiana hasła (current_password, new password + confirmation)
- **Middleware**: `admin` alias w bootstrap/app.php, sprawdza `$user->is_admin`, 403 dla nieautoryzowanych, redirect na login dla gości
- **Obsługa banów**: pole `is_banned` w tabeli users, blokada logowania, admin może toggle ban (zabezpieczenie przed banowaniem siebie)
- **Sesje**: oparte o bazę danych (SESSION_DRIVER=database)

**Pliki:**
- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/ProfileController.php`
- `app/Http/Middleware/AdminMiddleware.php`
- `app/Models/User.php`
- `bootstrap/app.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/user/profile.blade.php`

### Wspólne funkcjonalności

- **Walidacja**: backend (`$request->validate()`) z polskimi komunikatami błędów; frontend (`is-invalid`, `@error`, `required`)
- **Responsywność**: Bootstrap 5.3 grid (col-lg, col-md, col-), flex, table-responsive, navbar z togglerem
- **Obsługa błędów HTTP**: abort(403), abort(404), findOrFail(), firstOrFail(), flash messages (success/error)
- **ORM**: Eloquent z relacjami (belongsTo, hasMany, belongsToMany), query builder z bind parameters (whereRaw)
- **Seedery**: DatabaseSeeder uruchamia ImageSeeder (z rzeczywistymi zdjęciami z `database/seed-images/`), CarDataSeeder, ListingSeeder, TagSeeder, ServiceSeeder

---

## Schemat ERD

Diagram ERD znajduje się w pliku `ERD.png` w głównym katalogu projektu.

### Tabele bazy danych:

| Tabela | Opis |
|--------|------|
| `users` | Użytkownicy (name, email, password, is_admin, is_banned) |
| `brands` | Marki samochodów |
| `car_models` | Modele samochodów (brand_id) |
| `fuels` | Rodzaje paliwa |
| `transmissions` | Skrzynie biegów |
| `body_types` | Typy nadwozia |
| `listings` | Ogłoszenia aut (user_id, brand_id, model_id, fuel_id, transmission_id, body_type_id, title, description, price, city, year, mileage, engine_capacity, power_hp, color, views_count, latitude, longitude) |
| `tags` | Tagi |
| `listing_tags` | Relacja listing-tag (PK composite) |
| `images` | Zdjęcia (original_name, file_name, file_type) |
| `listing_images` | Relacja listing-zdjęcie z sort_order |
| `services` | Usługi (user_id, title, description, price, city, status, views_count) |
| `service_reviews` | Opinie o usługach (service_id, user_id, rating 1-5, comment) |
| `service_images` | Relacja usługa-zdjęcie z sort_order |
| `conversations` | Konwersacje (listing_id, service_id, user2_id) |
| `messages` | Wiadomości (conversation_id, sender_id, content, is_read, visit_time) |

---

## Kierunki dalszego rozwoju

### Co zrobilibyśmy inaczej

1. **Docker Compose** – projekt powinien być gotowy do uruchomienia przez `docker compose up` (Dockerfile + docker-compose.yml z app, nginx, postgresql, pgadmin)
2. **Testy automatyczne** – brak unit/feature testów PHPUnit dla kluczowych ścieżek biznesowych
3. **FormRequesty** – walidacja odbywa się inline w kontrolerach; lepiej wydzielić do klas FormRequest
4. **Soft deletes** – usuwanie ogłoszeń/usług jest twarde; lepiej użyć `SoftDeletes` trait
5. **Dedyuplikacja views_count** – licznik wyświetleń incrementuje przy każdym wejściu, bez uwzględniania botów ani odświeżeń przez właściciela
6. **Paginacja opinii** – opinie ładowane jako kolekcja, bez paginacji

### Co wymaga dopracowania

1. **Moderacja treści** – panel admina ma podstawowe funkcje, brakuje dedykowanego widoku do moderacji komentarzy/opinii
2. **Walidacja JavaScript** – frontend ogranicza się do HTML5 `required`; brak walidacji JS
3. **Powiadomienia** – brak notyfikacji email/push o nowych wiadomościach
4. **Upload zdjęć** – brak miniaturek, watermarków, konwersji WebP
5. **Obsługa wielu języków** – tylko język polski
6. **Logowanie przez OAuth** – brak Socialite (Google/Facebook)
7. **CI/CD** – brak GitHub Actions do testów/deploy

---

## Instrukcja uruchomienia aplikacji

### Wymagania

- PHP ^8.4 z rozszerzeniami: `pgsql`, `mbstring`, `xml`, `curl`, `gd`, `fileinfo`
- Composer 2.x
- Node.js 20+ z npm
- PostgreSQL 15+ (lub SQLite)
- Rozszerzenie PostgreSQL `pg_trgm`

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
   Edytuj `.env`:
   ```
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=komis
   DB_USERNAME=postgres
   DB_PASSWORD=postgres
   ```

4. **Generowanie klucza**
   ```bash
   php artisan key:generate
   ```

5. **Instalacja zależności frontendowych**
   ```bash
   npm install
   npm run build
   ```

6. **Baza danych**
   ```sql
   CREATE DATABASE komis;
   CREATE EXTENSION IF NOT EXISTS pg_trgm;
   ```

7. **Migracja i seedowanie**
   ```bash
   php artisan migrate --seed
   ```

8. **Linkowanie storage**
   ```bash
   php artisan storage:link
   ```

9. **Uruchomienie**
   ```bash
   php artisan serve
   ```
   Aplikacja: http://localhost:8000

### Dane testowe

Po `php artisan migrate --seed`:
- `test@example.com` / `password`
- `second@example.com` / `password`

Nadanie admina:
```bash
php artisan tinker
User::where('email', 'test@example.com')->update(['is_admin' => true]);
```

### Użyteczne komendy

| Komenda | Opis |
|---------|------|
| `php artisan migrate` | Migracje |
| `php artisan migrate:fresh --seed` | Reset + seed |
| `php artisan storage:link` | Link storage |
| `php artisan queue:listen` | Kolejki |
| `npm run dev` | Vite HMR |
| `npm run build` | Budowa assetów |

---

## Przebieg użycia aplikacji

### Scenariusz 1: Sprzedawca wystawia ogłoszenie, kupujący pisze na czacie

1. **Rejestracja** – nowy użytkownik wypełnia formularz (name, email, password + confirmation)
2. **Logowanie** – system regeneruje sesję, sprawdza czy nie jest zbanowany
3. **Dodanie ogłoszenia** – użytkownik wypełnia formularz: marka (autocomplete), model, rok, paliwo, skrzynia, nadwozie, cena, przebieg, moc, pojemność, kolor, lokalizacja (mapa), tagi
4. **Dodanie zdjęć** – po zapisie przekierowanie do strony dodawania zdjęć
5. **Kupujący szuka** – używa filtrów (marka, model, cena, paliwo), sortuje, paginacja
6. **Widok ogłoszenia** – galeria, dane techniczne, opis, cena, mapa Google
7. **Czat** – kupujący klika "Napisz do sprzedawcy", system tworzy konwersację
8. **Sprzedawca odpowiada** – widzi nową wiadomość w panelu konwersacji

### Scenariusz 2: Mechanik dodaje usługę, klient wystawia opinię

1. **Dodanie usługi** – mechanik wypełnia formularz, wybiera lokalizację na mapie Leaflet (auto-geokodowanie), dodaje zdjęcia
2. **Przeglądanie** – klient filtruje po mieście i cenie, sortuje po ocenie
3. **Widok usługi** – karuzela zdjęć, opis, cena, średnia ocen, opinie, mapa
4. **Kontakt** – "Napisz do usługodawcy" przez czat
5. **Opinia** – klient wystawia ocenę (1-5) i komentarz, średnia automatycznie aktualizowana
6. **Panel usługodawcy** – "Moje usługi" z liczbą wyświetleń, edycja/usuwanie
