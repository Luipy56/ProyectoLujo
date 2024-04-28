const cards = document.querySelector(".cards-CL");  //Selecciona el elemento HTML con la clase "cards-CL" y lo almacena en la variable `cards`.
const images = document.querySelectorAll(".card__img-CL");  //Selecciona todos los elementos HTML con la clase "card__img-CL" y los almacena en la variable `images`.
const backgrounds = document.querySelectorAll(".card__bg-CL");  //Selecciona todos los elementos HTML con la clase "card__bg-CL" y los almacena en la variable `backgrounds`.
const range = 40; //Establece un valor de rango de 40 para ajustar la sensibilidad del efecto.
const calcValue = (a, b) => (a/b*range-range/2).toFixed(1) // Define una función `calcValue` que toma dos parámetros `a` y `b`, y devuelve un cálculo basado en esos parámetros.
let timeout;  // Establece una variable `timeout` inicialmente vacía.
document.addEventListener('mousemove', ({x, y}) => {  // Establece un listener de evento en el documento para el evento 'mousemove'.
  
  if (timeout) {// Si hay un `timeout` previamente definido, se cancela.
    window.cancelAnimationFrame(timeout);
  }
    
  timeout = window.requestAnimationFrame(() => {// Se establece un nuevo `timeout` utilizando `window.requestAnimationFrame()`.
    
    const yValue = calcValue(y, window.innerHeight);// Se calcula `yValue` utilizando la función `calcValue` con la posición vertical del ratón (`y`) y la altura de la ventana (`window.innerHeight`). 
    const xValue = calcValue(x, window.innerWidth);// Se calcula `xValue` utilizando la función `calcValue` con la posición horizontal del ratón (`x`) y el ancho de la ventana (`window.innerWidth`).    
    cards.style.transform = `rotateX(${yValue}deg) rotateY(${xValue}deg)`;// Se aplica una transformación CSS al elemento `cards` para rotarlo en los ejes X e Y según los valores calculados de `yValue` y `xValue`.
    
    [].forEach.call(images, (image) => {// Se itera sobre cada imagen en `images` y se aplica una transformación CSS para moverlas en sentido opuesto al movimiento horizontal del ratón y en la misma dirección que el movimiento vertical del ratón.
      image.style.transform = `translateX(${-xValue}px) translateY(${yValue}px)`;
    });
    
    [].forEach.call(backgrounds, (background) => {// Se itera sobre cada fondo en `backgrounds` y se ajusta su posición de fondo para crear un efecto de paralaje.
      background.style.backgroundPosition = `${xValue*.45}px ${-yValue*.45}px`;
    })
  })
}, false);

//Código de codinglabsolution https://www.codinglabsolution.com/2024/04/3d-css-parallax-depth-effect.html