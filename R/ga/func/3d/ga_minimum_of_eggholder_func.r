#install.packages("GA")
library(GA)

# Funkcja Eggholder function
func3d <- function(x, y)
{
  -(x+47)*sin(sqrt(abs(x/2+(y+47))))-x*sin(sqrt(abs(x-(y+47))))
}
lbound <- -512
rbound <- 512

x <- y <- seq(lbound, rbound, by=2) # generowanie wektorów od lbound to rbound co 0.1
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
