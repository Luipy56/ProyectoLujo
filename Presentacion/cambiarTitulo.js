var frases = [
  "¡No te vayas 😢!",
  "¡No te vayas 😢!",
  "Tonto el que lo lea 😢",
  "¿Seguro que quieres irte? 😢",
  "¡Espera! Hay más 😢",
  "¡Vuelve 😢!",
  "¡Vuelve 😢!"
];
function fraseAleatoria() {
    return frases[Math.floor(Math.random() * frases.length)];
}

document.addEventListener("visibilitychange", function() {
  if (document.visibilityState === 'hidden') {
    document.title = fraseAleatoria();"¡Vuelve 😢!";
  } else {
    document.title = "Presentación Proyecto Lujo";
  }
});