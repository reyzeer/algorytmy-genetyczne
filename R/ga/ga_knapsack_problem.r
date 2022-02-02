library(GA)

p <- c(15, 100, 90, 60, 40, 15, 10, 1, 1, 2, 3, 4)      # wartości przedmiotów
w <- c(2,  20, 20, 30, 40, 30, 60, 10, 10, 40, 30, 50)  # wagi
cap <- 113                                              # pojemność plecaka

knapsackFitness <- function(knapsack) {
  value <- sum(knapsack * p)
  weight <- sum(knapsack * w)

  # Funkcja kary, gdy nie używamy monitora
  if (weight > cap) {
    # value <- 0  # prosty element kary
    # value <- value * (cap/weight/1.1)
    value <- value - weight + cap
  }

  value
}

knapsackMonitor <- function(obj, digits = getOption("digits"), ...)
{
  for(i in 1:nrow(obj@population)) {
    weight <- sum(obj@population[i,] * w)
    while (weight > cap) {
      removeThing <- sample.int(length(p) - 1, 1) + 1
      obj@population[i,removeThing] <- 0
      weight <- sum(obj@population[i,] * w)
    }
  }
  
  fitness <- na.exclude(obj@fitness)
  sumryStat <- c(mean(fitness), max(fitness))
  sumryStat <- format(sumryStat, digits = digits)
  cat(paste("GA | iter =", obj@iter, "| Mean =", sumryStat[1], 
            "| Best =", sumryStat[2]))
  cat("\n")
  flush.console()
}

set.seed(123)
GA <- ga(
  type = "binary",
  fitness = knapsackFitness,
  nBits = length(p),
  popSize = 50,
  maxiter = 1000,
  run = 100,
  seed = 123,
  monitor = knapsackMonitor
)

summary(GA)
plot(GA)
