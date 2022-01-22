#install.packages("GA")
library(GA)

# Funkcja przykładowa z dokumentacji
# Rastrigin function
func3d <- function(x, y)
{
  20 + x^2 + y^2 - 10*(cos(2*pi*x) + cos(2*pi*y))
}

lbound <- -5.12
rbound <- 5.12

x <- y <- seq(lbound, rbound, by=0.1) # generowanie wektorów od lbound to rbound co 0.1
f <- outer(x, y, func3d)
persp3D(x, y, f, theta = 50, phi = 20, col.palette = bl2gr.colors) # Perspektywa 3D funkcji

filled.contour(x, y, f, color.palette = bl2gr.colors) # Rzut z góry wykresu funkcji

fA <- function(x) -func3d(x[1], x[2])

GA <- ga(
  type = "real-valued",
  fitness = function(x) -func3d(x[1], x[2]), # (minus) - minimalizacja funkcji 3D
  lower = c(lbound, lbound), upper = c(rbound, rbound),
  popSize = 50, maxiter = 1000, run = 100
)
summary (GA)
plot(GA)

filled.contour(
  x, y, f,
  color.palette = bl2gr.colors,
  plot.axes = {
    axis(1); axis(2);
    points(
      GA@solution[,1], GA@solution[,2],
      pch = 3, cex = 2, col = "white", lwd = 2
    )
  }
)
