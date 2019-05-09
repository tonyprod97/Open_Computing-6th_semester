var kapacitet_promjena = document.querySelector('#kapacitet_span');

window.addEventListener('load',()=> kapacitet_span.innerHTML = "");

očisti_obrazac=() => {
    kapacitet_span.innerHTML='';
}

kapacitet_promjena = (value,span) => {
    console.log(value,span)
    span.innerHTML = value;
}

