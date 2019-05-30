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
var map;
var naziv;
var handleDetails = (crkvaId,crkvaEngId,crkvaNaziv) => {
    naziv = crkvaNaziv
    detailElement = document.getElementById(crkvaId)
    map = document.getElementById('mapid')
    detailElement.innerText = 'Tražim...'
    if (window.XMLHttpRequest) { // FF, Safari, Opera, IE7+
        req = new XMLHttpRequest(); // stvaranje novog objekta
    } else if (window.ActiveXObject) { // IE 6+
        req = new ActiveXObject("Microsoft.XMLHTTP"); //ActiveX
    } if (req) { // uspješno stvoren objekt
        req.onreadystatechange = handleReadyStateChange;
        req.open("GET", `detalji.php?id=${crkvaId}&eng=${crkvaEngId}`, true); // metoda, URL, asinkroni način
        req.send(null); //slanje (null za GET, podaci za POST)
    }
}

var handleReadyStateChange = () => {
    if (req.readyState == 4) {
        if (req.status == 200) {
            detailElement.innerText = 'Detalji'
            console.log(req.responseText)
            var res = JSON.parse(req.responseText)
            var htmlRes = res[0]
            var coordinates = res[1]
            document.getElementById('detalji').innerHTML = htmlRes

            if(map != undefined || map != null){
                var container = document.getElementById('map_container')
                container.removeChild(map)
                var div = document.createElement('div')
                div.id = 'mapid'
                container.appendChild(div)
            }
            if(coordinates) {
                var mymap = L.map('mapid').setView([coordinates[0], coordinates[1]], 17)
                L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
                { attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' })
                .addTo(mymap)
                var marker = L.marker([coordinates[0], coordinates[1]]).addTo(mymap)
                marker.bindPopup(`<b>${naziv}</b><br/>širina: ${coordinates[0].toFixed(2)}<br/>duljina: ${coordinates[1].toFixed(2)}`).openPopup()
            } else {
                document.getElementById('mapid').innerText = 'Koodrinate nisu poznate, prikaz karte nije dostupan.'
            }
        } else alert("Dogodila se pogereška, molimo Vas pokušajte ponovno:\n")
    }
}