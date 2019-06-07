
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


var req;
var detailElement;
var map;
var naziv;
var coordinatesWikiaction;
var coordinatesWikimedia;
var idEng;
var id;
var wikimedia = 'https://en.wikipedia.org/api/rest_v1/page/summary/';

var handleDetails = (crkvaId,crkvaEngId,crkvaNaziv) => {
    naziv = crkvaNaziv
    idEng = crkvaEngId
    id = crkvaId
    detailElement = document.getElementById(crkvaId)
    map = document.getElementById('mapid')
    detailElement.innerText = 'Tražim...'
    var req = createRequestObject();
    if (req) { 
        req.onreadystatechange = handleReadyStateChange;
        req.open("GET", `detalji.php?id=${crkvaId}`, true);
        req.send(null); 
    }
}

var handleReadyStateChange = () => {
    if (req.readyState == 4) {
        if (req.status == 200) {
            detailElement.innerText = 'Detalji'
            var res = req.responseText
            document.getElementById('detalji').innerHTML = res
            console.log('RESPONSE : ',res)
            wikimediaDohvat()
        } else alert("Dogodila se pofreška prilikom dohvata podataka, molimo Vas pokušajte ponovno:\n")
    }
}

function wikimediaDohvat(){
    var req = createRequestObject();
    if(req) {
        req.onreadystatechange = () => {
            if (req.readyState == 4) {
                if (req.status == 200) {
                    var data = JSON.parse(req.responseText)
                    coordinatesWikimedia = [data['coordinates']['lat'],data['coordinates']['lon']];
                    wikiActionDohvat()
                } else setMap()
            }
        };
        req.open("GET", wikimedia+idEng, true)
        req.send(null);
    }
}

function wikiActionDohvat(){
    var req = createRequestObject();
    if(req) {
        req.onreadystatechange = () => {
            if (req.readyState == 4) {
                if (req.status == 200) {
                    var data = JSON.parse(req.responseText)
                    console.log('Wiki action data:',data)
                    coordinatesWikiaction = [+data['širina'],+data['duljina']]
                    setMap()
                } else alert('Failed')
            }
        }
    req.open("GET", 'detalji.php?action=wikiAction&title='+id, true);
    req.send(null); 
    }
}
function setMap() {
    console.log(coordinatesWikiaction,coordinatesWikimedia)
    if(map != undefined || map != null){
        var container = document.getElementById('map_container')
        container.removeChild(map)
        var div = document.createElement('div')
        div.id = 'mapid'
        container.appendChild(div)
    }
    if(coordinatesWikiaction[0] && coordinatesWikimedia[0]) {
        var mymap = L.map('mapid').setView(coordinatesWikiaction, 17)
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
        { attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' })
        .addTo(mymap)
        var polyline = L.polyline([coordinatesWikiaction,coordinatesWikimedia], {
            color: 'red'
            }).addTo(mymap);
        var markerAction = L.marker(coordinatesWikiaction).addTo(mymap)
        var markerMedia = L.marker(coordinatesWikimedia).addTo(mymap)
        
        markerAction.bindPopup(`<b>${naziv} - wiki Action</b><br/>širina: ${coordinatesWikiaction[0].toFixed(2)}<br/>duljina: ${coordinatesWikiaction[1].toFixed(2)}`).openPopup()
        markerMedia.bindPopup(`<b>${naziv} - wiki Media</b><br/>širina: ${coordinatesWikimedia[0].toFixed(2)}<br/>duljina: ${coordinatesWikimedia[1].toFixed(2)}`).openPopup()
    
        mymap.fitBounds(polyline.getBounds());
    } else if(coordinatesWikimedia[0]) {
        var [širina,duljina] = coordinatesWikimedia
        var mymap = L.map('mapid').setView([širina, duljina], 17)
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
        { attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' })
        .addTo(mymap)
        var marker = L.marker([širina, duljina]).addTo(mymap)
        marker.bindPopup(`<b>${naziv}</b><br/>širina: ${širina.toFixed(2)}<br/>duljina: ${duljina.toFixed(2)}`).openPopup()
    }
    else {
        document.getElementById('mapid').innerText = 'Koodrinate nisu poznate, prikaz karte nije dostupan.'
    }
}

function  createRequestObject() {
    if (window.XMLHttpRequest) { // FF, Safari, Opera, IE7+
        req = new XMLHttpRequest(); // stvaranje novog objekta
    } else if (window.ActiveXObject) { // IE 6+
        req = new ActiveXObject("Microsoft.XMLHTTP"); //ActiveX
    }
    return req
}