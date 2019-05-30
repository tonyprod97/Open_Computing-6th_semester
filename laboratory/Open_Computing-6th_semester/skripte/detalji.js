window.addEventListener('load', () => {
    var rows = document.querySelectorAll('tbody tr')
    rows.forEach(r => {
        var rowColor = r.style.backgroundColor
        var fontColor = r.style.color
        r.addEventListener('mouseover', () => {
            r.style.backgroundColor = '#6d6d6d'
            r.style.color = 'white'
        })
        r.addEventListener('mouseout', () => {
            r.style.backgroundColor = rowColor
            r.style.color = fontColor
        })
    })
})

var req;
var detailElement;
var handleDetails = (crkvaId) => {
    detailElement = document.getElementById(crkvaId)
    detailElement.innerText = 'Tražim...'
    if (window.XMLHttpRequest) { // FF, Safari, Opera, IE7+
        req = new XMLHttpRequest(); // stvaranje novog objekta
    } else if (window.ActiveXObject) { // IE 6+
        req = new ActiveXObject("Microsoft.XMLHTTP"); //ActiveX
    } if (req) { // uspješno stvoren objekt
        req.onreadystatechange = handleReadyStateChange;
        req.open("GET", `detalji.php?id=${crkvaId}`, true); // metoda, URL, asinkroni način
        req.send(null); //slanje (null za GET, podaci za POST)
    }
}

var handleReadyStateChange = () => {
    if (req.readyState == 4) {
        if (req.status == 200) {
            detailElement.innerText = 'Detalji'
            var htmlInject = req.responseText;
            document.getElementById('detalji').innerHTML = req.responseText;
        } else {
            alert("Dogodila se pogereška, molimo Vas pokušajte ponovno:\n");
        }
    }
}