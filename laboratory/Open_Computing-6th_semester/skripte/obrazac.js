očisti_obrazac=() => {
    document.querySelectorAll('span').forEach(el => el.innerHTML='');
}

raspon_promjena = (value,span) => {
    span.innerHTML = value;
}

