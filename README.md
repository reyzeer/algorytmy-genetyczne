# Algorytmy genetyczne

* Imię i nazwisko: Zbigniew Czarnecki
* Indeks: 40873
* Kierunek: Informatyka
* Specjalizacja: Projektowanie systemów informatycznych i analiza danych
* Przedmiot: Algorytmy genetyczne
* Prowadzący: dr inż. Mateusz Gorczyca

## Implementacja projektu

Zadania zostały zaimplementowane w języku w PHP 8.\
Aby uruchomić zaimplementowane algorytmy konieczne jest zainstalowanie maszyny wirtualnej języka PHP 8.\
Oraz zainstalowanie narzędzia _composer_, które wygeneruje plik odpowiedzialny za wczytywanie klas na bazie PSR-4.

Projekt został zaimplementowany z wykorzystaniem programowania obiektowego
z podziałem kodu na odpowiednie pakiety i konteksty odpowiedzialności.\
Dla zapewnienia poprawności implementacji zostały zrealizowane
testy jednostkowe weryfikujące poprawność implementowanych zachowań.

## Instalacja projektu

Po zainstalowaniu maszyny języka PHP 8 oraz narzędzia composer,
jeśli w projekcie brakuje katalogu _vendor_ należy wywołać poniższe polecenie.

```
composer install
```

## Uruchomienie algorytmów (skrypty wykonywalne)

Metoda gradientu prostego dla funkcji

```
./gradient.php
```

Algorytm przeszukiwania lokalnego dla funkcji

```
./greedyAlgorithm.php
```

Algorytm wyżarzania dla funkcji

```
./annealing.php
```

Algorytm genetyczny dla funkcji

```
./genetic.php
```

Algorytm genetyczny dla problemu komiwojażera

```
./genetic-tsp.php
```

Uruchomienie testów jednostkowych w projekcie

```
./vendor/bin/phpunit ./Tests/
```

Poniżej opis implementacji wraz z odwołaniami do klas implementujących poszczególne algorytmy.

## Zadanie 1

Dla problemu minimalizacji wartości funkcji ciągłej f(x) = x * sin (10 * PI * x) + 1 w przedziale x ∈ [-1;2]

Klasa reprezentująca powyższą funkcję została zaimplementowana w pliku:

```
./Functions/Func.php
```

Implementacja klasy Func dostarcza:
* Obliczanie wartości po funkcji _x_,
* Obliczanie wartości pochodnej funkcji po _x_,
* Obliczanie wartości po _x_ na podstawie obiektu klasy reprezentacji binarnej,

Testy weryfikujące klasę `./Functions/Func.php`

```
./Tests/Func/FuncTest.php
```

### a) wykorzystaj metodę gradientu prostego

Metoda gradientu prostego została zaimplementowana w klasie:

```
./Algorithms/Func/GradientDescent.php
```

W ramach metody _algorithm_ zaimplementowano poszukiwanie optimum dzięki metodzie gradientu prostego.

Testy weryfikujące klasę `./Algorithms/Func/GradientDescent.php`

```
./Tests/Algorithms/Func/GradientDescentTest.php
```

### b) stwórz funkcję sąsiedztwa w reprezentacji binarnej

Klasa implementująca reprezentację binarną:

```
./Representations/Func/Binary.php
```

Klasa ta:
* Przechowuje obecnie badaną wartość jako ciąg 0 i 1,
* Jako iterator pozwala przesuwać się o jedną wartość w lewo lub prawo, a także o skok określaną wartość skoku, dla algorytmu wyżarzania,
* Dostarcza funkcje badające czy minimum lokalne znajduje się po lewej lub prawej stronie danej wartości reprezentacji,
* Na potrzeby algorytmu genetyczne posiada metody krzyżowania i mutacji,

Za zamianę wartości reprezentacji na wartość _x_
