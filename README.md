# Algorytmy genetyczne

* Imię i nazwisko: Zbigniew Czarnecki
* Indeks: 40873
* Kierunek: Informatyka
* Specjalizacja: Projektowanie systemów informatycznych i analiza danych
* Przedmiot: Algorytmy genetyczne
* Prowadzący: dr inż. Mateusz Gorczyca

## Implementacja projektu

Zadania zostały zaimplementowane w języku w PHP 8.

Aby uruchomić zaimplementowane algorytmy konieczne jest zainstalowanie maszyny wirtualnej języka PHP 8.

Oraz zainstalowanie narzędzia _composer_, które wygeneruje plik odpowiedzialny za wczytywanie klas na bazie PSR-4.

Projekt został zaimplementowany z wykorzystaniem programowania obiektowego
z podziałem kodu na odpowiednie pakiety i konteksty odpowiedzialności.

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

Wydajność algorytmy można konfigurować poprzez wartości:

* Maksymalnej ilości iteracji,
* Wartości kroku,
* Współczynnika tolerancji poprawy wyniku,

Testy weryfikujące klasę `./Algorithms/Func/GradientDescent.php`

```
./Tests/Algorithms/Func/GradientDescentTest.php
```

Przykładowy wynik wywołania algorytmu:

```
/bin/php /home/reyzeer/projects/dsw/algorytmy-genetyczne/algorytmy-genetyczne/gradient.php
Step 0. f(-0.74820917802076) = 0.25297463484756 
Step 1. f(-0.74844119380992) = 0.25245607977122 
Step 2. f(-0.74865617409004) = 0.25201089939053 
Step 3. f(-0.74885534998795) = 0.25162878483319 
Step 4. f(-0.74903986711297) = 0.25130085823685 
Step 5. f(-0.74921079086664) = 0.25101947826909 
Step 6. f(-0.74936911154451) = 0.25077807105914 
Step 7. f(-0.74951574921182) = 0.25057098347674 
Step 8. f(-0.74965155834195) = 0.25039335599621 
Step 9. f(-0.74977733221279) = 0.25024101267551 
Step 10. f(-0.74989380706054) = 0.2501103660549 
Step 11. f(-0.75000166599375) = 0.24999833503351 
Result: f(-0.75000166599375) = 0.24999833503351
Time: 0.00043511390686035
```

Przykładowa znalezione wartość zbliżająca się do minimum: f(-0.75000166599375) = 0.24999833503351

Czas przetwarzania: 0.00043511390686035 sekundy

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

Za zamianę wartości reprezentacji na wartość _x_ odpowiada metoda _convertRepresentationToX_ z klasy:

```
./Functions/Func.php
```

Testy weryfikujące klasę `./Representations/Func/Binary.php`

```
./Tests/Representations/Func/BinaryTest.php
```

### c) wykorzystaj tę funkcję w zaimplementowanym przez siebie algorytmie przeszukiwania lokalnego (iteracyjne wybieranie najbliższego punktu z sąsiedztwa dopóki można ulepszyć wartość funkcji celu),

Algorytm przeszukiwania lokalnego został zaimplementowany w klasie:

```
./Algorithms/Func/GreedyAlgorithmBinary.php
```

W ramach metody _algorithm_ zaimplementowano poszukiwanie minimum lokalnego.

Testy weryfikujące klasę `./Algorithms/Func/GreedyAlgorithmBinary.php`

```
./Tests/Algorithms/Func/GreedyAlgorithmTest.php
```

Przykładowy wynik wywołania algorytmu:

```
(...)
Step 51521. f(0100011111111100101101 : -0.15640071783083) = 0.84675067086154 
Step 51522. f(0100011111111100101110 : -0.15640000257492) = 0.84675066981074 
Step 51523. f(0100011111111100101111 : -0.15639928731901) = 0.84675066884373 
Step 51524. f(0100011111111100110000 : -0.15639857206311) = 0.84675066796052 
Step 51525. f(0100011111111100110001 : -0.1563978568072) = 0.8467506671611 
Step 51526. f(0100011111111100110010 : -0.15639714155129) = 0.84675066644548 
Step 51527. f(0100011111111100110011 : -0.15639642629538) = 0.84675066581366 
Step 51528. f(0100011111111100110100 : -0.15639571103947) = 0.84675066526563 
Step 51529. f(0100011111111100110101 : -0.15639499578357) = 0.8467506648014 
Step 51530. f(0100011111111100110110 : -0.15639428052766) = 0.84675066442096 
Step 51531. f(0100011111111100110111 : -0.15639356527175) = 0.84675066412431 
Step 51532. f(0100011111111100111000 : -0.15639285001584) = 0.84675066391145 
Step 51533. f(0100011111111100111001 : -0.15639213475994) = 0.84675066378238 
Step 51534. f(0100011111111100111010 : -0.15639141950403) = 0.84675066373711 
Step 51535. f(0100011111111100111011 : -0.15639070424812) = 0.84675066377562 
Result: f(0100011111111100111010 : -0.15639141950403) = 0.84675066373711
Time: 0.51100015640259
```

Przykładowe znalezione minimum lokalne: f(0100011111111100111010 : -0.15639141950403) = 0.84675066373711

Czas przetwarzania: 0.00043511390686035 sekundy

### d) wykorzystaj tę funkcję w zaimplementowanym przez siebie algorytmie symulowanego wyżarzania (należy stworzyć sposób generowania losowego punktu z sąsiedztwa),

Algorytm symulowane wyżarzania został zaimplementowany w klasie:

```
./Algorithms/Func/Annealing.php
```

W ramach metody _algorithm_ zaimplementowano algorytm symulowanego wyżarzania.

W założeniach przyjęto, że:

* algorytm symulowanego wyżarzania dąży do minimum lokalnego,
* algorytm w momencie sytuacji losowej determinowanej przez wartość punktu wyżarzania wykonuje skok o wartość binarną reprezentacji "1000000000" w lewo lub prawo, zależnie od kierunku dążenia do minimum dla wartości reprezentacji w danym momencie przetwarzania algorytmu,

Wydajność algorytmy można konfigurować poprzez wartości:

* Maksymalnej ilości iteracji dla obecnej temperatury,
* Wartości współczynnika alfa spadku temperatury,
* Temperaturę początkową,
* Temperaturę minimalną,
* Współczynnika tolerancji poprawy wyniku,

Testy weryfikujące klasę `./Algorithms/Func/Annealing.php`

```
./Tests/Algorithms/Func/AnnealingTest.php
```

Przykładowy wynik wywołania algorytmu:

```
(...)
Step 219968. f(0110011101000010011110 : 0.21007423641067) = 1.0653822892881 
Step 219969. f(0110011101001010011110 : 0.21044044743549) = 1.0677927214013 
Step 219970. f(0110011101001010011110 : 0.21044044743549) = 1.0677927214013 
Step 219971. f(0110011101010010011110 : 0.2108066584603) = 1.0702021574706 
Step 219972. f(0110011101010010011110 : 0.2108066584603) = 1.0702021574706 
Step 219973. f(0110011101011010011110 : 0.21117286948511) = 1.0726102468252 
Step 219974. f(0110011101011010011110 : 0.21117286948511) = 1.0726102468252 
Step 219975. f(0110011101100010011110 : 0.21153908050992) = 1.075016637921 
Step 219976. f(0110011101100010011110 : 0.21153908050992) = 1.075016637921 
Step 219977. f(0110011101101010011110 : 0.21190529153473) = 1.0774209783914 
Step 219978. f(0110011101101010011110 : 0.21190529153473) = 1.0774209783914 
Step 219979. f(0110011101110010011110 : 0.21227150255954) = 1.0798229150986 
Step 219980. f(0110011101110010011110 : 0.21227150255954) = 1.0798229150986 
Step 219981. f(0110011101111010011110 : 0.21263771358435) = 1.0822220941845 
Step 219982. f(0110011101111010011110 : 0.21263771358435) = 1.0822220941845 
Step 219983. f(0110011110000010011110 : 0.21300392460917) = 1.0846181611226 
Step 219984. f(0110011110000010011110 : 0.21300392460917) = 1.0846181611226 
Step 219985. f(0110011110001010011110 : 0.21337013563398) = 1.08701076077 
Step 219986. f(0110011110001010011110 : 0.21337013563398) = 1.08701076077 
Step 219987. f(0110011110010010011110 : 0.21373634665879) = 1.0893995374189 
Step 219988. f(0110011110010010011110 : 0.21373634665879) = 1.0893995374189 
Step 219989. f(0110011110011010011110 : 0.2141025576836) = 1.0917841348495 
Step 219990. f(0110011110011010011110 : 0.2141025576836) = 1.0917841348495 
Step 219991. f(0110011110100010011110 : 0.21446876870841) = 1.0941641963817 
Step 219992. f(0110011110100010011110 : 0.21446876870841) = 1.0941641963817 
Step 219993. f(0110011110101010011110 : 0.21483497973322) = 1.0965393649286 
Step 219994. f(0110011110101010011110 : 0.21483497973322) = 1.0965393649286 
Step 219995. f(0110011110110010011110 : 0.21520119075804) = 1.0989092830486 
Step 219996. f(0110011110110010011110 : 0.21520119075804) = 1.0989092830486 
Step 219997. f(0110011110111010011110 : 0.21556740178285) = 1.1012735929987 
Step 219998. f(0110011110111010011110 : 0.21556740178285) = 1.1012735929987 
Step 219999. f(0110011111000010011110 : 0.21593361280766) = 1.1036319367879 
Result: f(1111101111001010011111 : 1.9506759525957) = -0.95023613658052
Jumps: 109858
Colds: 142
Time: 2.0700480937958
```

Przykładowa znalezione wartość zbliżająca się do minimum: f(1111101111001010011111 : 1.9506759525957) = -0.95023613658052

Czas przetwarzania: 2.0700480937958 sekundy

### e) zaimplementować omawiany na wykładzie algorytm genetyczny dla reprezentacji 22-bitowej.

Algorytm symulowane wyżarzania został zaimplementowany w traitcie:

```
./Algorithms/GeneticAlgorithm.php
```

Jest to uniwersalny trait, który jest bazą dla algorytmu genetyczne poszukującego minimum funkcji z zadania 1,
jak i dla poszukiwania rozwiązania problemu komiwojażera z zadania drugiego.
Algorytm zaimplementowano w ramach metody _algorithm_.

Kroki algorytmu genetycznego:

1. Wygenerowanie losowej populacji początkowej,
2. Selekcja najlepszych osobników,
3. Krzyżowanie osobników,
4. Przygotowanie populacji dla kolejnej iteracji,
5. Losowa mutacji, części osobników w populacji,
6. Weryfikacja czy od zdefiniowanej wcześniej liczby iteracji nie ma poprawy,
7. Jeśli nie jest to ostatnia iteracja powrót do kroku 2.

Wydajność algorytmy można konfigurować poprzez wartości:

* Wielkość populacji,
* Współczynnik przeżywalności,
* Ilość iteracji,
* Minimalna ilość iteracji,
* Ilość iteracji bez poprawy wyniku, po których należy przerwać algorytm,
* Prawdopodobieństwo mutacji,

Klasa determinująca zachowania algorytmu genetycznego dla funkcji z zadania 1 to:

```
./Algorithms/Func/Genetic.php
```

Implementacje metod mutacji są osadzone w klasie reprezentacji binarnej:

```
./Representations/Func/Binary.php
```

Mutacja została zaimplementowana jako podział ciągu bitowego na pół.
Gdzie pierwszej 11 bitów jest branych z pierwszego ciągu, a drugie 11 bitów z drugiego ciągu.

Krzyżowanie zostało zaimplementowane, jako losowa zmiana jednego bitu na wartość przeciwną.

Testy weryfikujące klasy `./Algorithms/Func/Genetic.php`, `./Representations/Func/Binary.php` i trait `./Algorithms/GeneticAlgorithm.php`

```
./Tests/Algorithms/Func/GeneticTest.php
./Tests/Representations/Func/BinaryTest.php
```

Przykładowy wynik wywołania algorytmu:

```
(...)
Step 5985. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5986. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5987. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5988. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5989. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5990. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5991. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5992. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5993. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5994. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5995. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5996. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5997. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5998. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 5999. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 6000. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Step 6001. f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465 
Result: f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465
Time: 3.006490945816
```

Przykładowa znalezione wartość zbliżająca się do minimum: f(1111101111000111000100 : 1.9505193115519) = -0.95025973447465

Czas przetwarzania: 3.006490945816 sekundy
