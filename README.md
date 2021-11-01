# ShoperDistanceAPI
Aplikacja do wyliczenia odległość i czas podróży od dowolnego miejsca do biura firmy Shoper.pl na podstawie współrzędnych
z wykorzystaniem HereApi.

## Autor
Oktawian Jaworski
oktawian.jaworski2@gmail.com

## Środowisko lokalne
Zmienne środowiskowe znajdują się w pliku `.env`
Uruchomienie środowiska testowego odbywa się przy użyciu Dockera.

### Uruchomienie
W głównym katalogu należy użyć komendy:
```bash
$ docker-compose up -d --build
```

Środowisko lokalne zostanie uruchomione na adresach:
- ShoperDistanceApi: `localhost:81`
- MySQL:5.7: `localhost:82`
- PhpMyAdmin: `localhost:83`

### Wyłączenie
W głównym katalogu należy użyć komendy:
```bash
$ docker-compose down
```

### Dokumentacja żądań ShoperDistanceApi

|           ŚCIEŻKA        |  METODA |                                  JSON BODY                               |         QUERY       |                     OPIS
|--------------------------|---------|--------------------------------------------------------------------------|---------------------|--------------------------------------------------
| /office-distance/{id}    |  GET    |               {latitude:"latitude", longitude:"longitude"}               |  id = identyfikator |Wyliczenie odległości i czasu podróży od dowolnego miejsca do biura firmy Shoper.pl
| /office/all              |  GET    |                                     {}                                   |                     |Pobranie wszystkich biur zapisanych w bazie danych
| /office/{id}             |  GET    |                                     {}                                   |  id = identyfikator |Pobranie biura na podstawie identyfikatora
| /office                  |  POST   |{city:"city", street:"street", latitude:"latitude", longitude:"longitude"}|                     |Dodanie nowego adresu biura
| /office/{id}             |  DELETE |                                     {}                                   |  id = identyfikator |Usunięcie adresu biura na podstawie identyfikatora
| /headquarter/{productId} |  PATCH  |{city:"city", street:"street", latitude:"latitude", longitude:"longitude"}|  id = identyfikator |Edycja adresu już isniejącego biura

## Narzędzia - biblioteki deweloperskie
Aby użyć narzędzi deweloperskich na początku należy skorzystać z Composer'a
```bash
$ composer install
```

### Naprawa stylu kodu
```bash
$ bin/php-cs-fix fix
```
- [friendsofphp/php-cs-fixer](https://packagist.org/packages/friendsofphp/php-cs-fixer)

### Testy jednostkowe
```bash
$ bin/phpunit
```
- [phpunit/phpunit](https://packagist.org/packages/phpunit/phpunit)
