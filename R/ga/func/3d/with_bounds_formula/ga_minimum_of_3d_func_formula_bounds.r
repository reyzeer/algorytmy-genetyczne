#install.packages("GA")
library(GA)

# https://cran.r-project.org/web/packages/GA/vignettes/GA.html#constrained-optimisation
func3d <- function(x)
{
  100*(x[1]^2-x[2]^2)+(1-x[1])^2
}

c1 <- function(x)
{
  x[1]*x[2] + x[1] - x[2] + 1.5
}

c2 <- function(x)
{
  10 - x[1]*x[2]
}

x1LBound <- 0
x1RBound <- 1
x2LBound <- 0
x2RBound <- 13

ngrid <- 250
x1 <- seq(x1LBound, x1RBound, length = ngrid)
x2 <- seq(x2LBound, x2RBound, length = ngrid)
x12 <- expand.grid(x1, x2)
col <- adjustcolor(bl2gr.colors(4)[2:3], alpha = 0.2)
plot(x1, x2, type = "n", xaxs = "i", yaxs = "i")
image(x1, x2, matrix(ifelse(apply(x12, 1, c1) <= 0, 0, NA), ngrid, ngrid), col = col[1], add = TRUE)
image(x1, x2, matrix(ifelse(apply(x12, 1, c2) <= 0, 0, NA), ngrid, ngrid), col = col[2], add = TRUE)
contour(x1, x2, matrix(apply(x12, 1, func3d), ngrid, ngrid), nlevels = 21, add = TRUE)

fitness <- function(x) 
{ 
  f <- -func3d(x)                    # we need to maximise -func3d(x)
  pen <- sqrt(.Machine$double.xmax)  # penalty term
  penalty1 <- max(c1(x),0)*pen       # penalisation for 1st inequality constraint
  penalty2 <- max(c2(x),0)*pen       # penalisation for 2nd inequality constraint
  f - penalty1 - penalty2            # fitness function value
}

GA <- ga("real-valued", fitness = fitness, 
         lower = c(x1LBound, x2LBound), upper = c(x1RBound, x2RBound), 
         # selection = GA:::gareal_lsSelection_R,
         maxiter = 1000, run = 200, seed = 123)
summary(GA)

fitness(GA@solution)
func3d(GA@solution)
c1(GA@solution)
c2(GA@solution)

plot(x1, x2, type = "n", xaxs = "i", yaxs = "i")
image(x1, x2, matrix(ifelse(apply(x12, 1, c1) <= 0, 0, NA), ngrid, ngrid), col = col[1], add = TRUE)
image(x1, x2, matrix(ifelse(apply(x12, 1, c2) <= 0, 0, NA), ngrid, ngrid), col = col[2], add = TRUE)
contour(x1, x2, matrix(apply(x12, 1, func3d), ngrid, ngrid), nlevels = 21, add = TRUE)
points(GA@solution[1], GA@solution[2], col = "dodgerblue3", pch = 3)  # GA solution
