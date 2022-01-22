#install.packages("GA")
library(GA)

# Funkcja przykładowa z dokumentacji
# f <- function(x) (x^2+x)*cos(x) # Badana funkcja
# lbound <- -10 # Dolne ograniczenie funkcji
# rbound <- 10 # Górne ogranicznie funkcji

f <- function(x) x*sin(10*pi*x)+1

fitnessF <- function(x)
{
  f <- f(x)
  
  penalty1 <- 0
  if (x <= -1) {
    penalty1 <- -x-1
  }
  
  penalty2 <- 0
  if (x >= 2) {
    penalty2 <- x - 2
  }
  
  f + penalty1 + penalty2
}

lbound <- -1000
rbound <- 1000

fA <- function(x) -fitnessF(x) # Funkcja odwrotna do badanej, ponieważ minimalizujemy funkcję

# Genetic algorithm
GA <- ga(
   type = "real-valued",
   fitness = fA,
   lower = c(th = lbound), upper = rbound,
   popSize = 50, maxiter = 100000, run = 1000
)
summary(GA)
plot(GA)

curve(f, from = lbound, to = rbound, 200) # rysowanie wykresy funkcji
#points(GA@solution, GA@fitnessValue, col = 2, pch = 19) # ozaczanie punktu na wykresie / tutaj szukanego ekstremum w zakresie
points(GA@solution, fitnessF(GA@solution), col = 2, pch = 19) # Musimy uzyskać wartość z funkcji wyjściowej, gdyż badamy funkcje odwrotną
