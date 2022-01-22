#install.packages("GA")
library(GA)

# Funkcja przykładowa z dokumentacji
# f <- function(x) (x^2+x)*cos(x) # Badana funkcja
# lbound <- -10 # Dolne ograniczenie funkcji
# rbound <- 10 # Górne ogranicznie funkcji

f <- function(x) x*sin(10*pi*x)+1
lbound <- -1
rbound <- 2

fA <- function(x) -f(x) # Funkcja odwrotna do badanej, ponieważ minimalizujemy funkcję

# Genetic algorithm
GA <- ga(
   type = "real-valued",
   fitness = fA,
   lower = c(th = lbound), upper = rbound,
   popSize = 50, maxiter = 1000, run = 100
)
summary(GA)
plot(GA)

curve(f, from = lbound, to = rbound, 1000) # rysowanie wykresy funkcji
#points(GA@solution, GA@fitnessValue, col = 2, pch = 19) # ozaczanie punktu na wykresie / tutaj szukanego ekstremum w zakresie
points(GA@solution, f(GA@solution), col = 2, pch = 19) # Musimy uzyskać wartość z funkcji wyjściowej, gdyż badamy funkcje odwrotną
