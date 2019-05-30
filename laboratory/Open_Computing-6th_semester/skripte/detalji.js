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
var coordinatesWikiaction;
var coordinatesWikimedia;
var idEng;
var id;
var wikimedia = 'https://en.wikipedia.org/api/rest_v1/page/summary/';
var wikiaction = 'https://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&rvsection=0&titles={titles}&format=json';

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
                    //kooridnateWikimedia = [data['coordinates']['lat'],data['coordinates']['lon']];
                    //wikiActionDohvat()
                    setMap(data['coordinates']['lat'],data['coordinates']['lon'])
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
                    console.log(data)
                    //setMap(data['coordinates']['lat'],data['coordinates']['lon'])
                } else alert('Failed')
            }
        }
    req.open("GET", wikiaction.replace("{titles}",idEng), true);
    req.setRequestHeader('Access-Control-Allow-Origin','*')
    req.send(null); 
    }
}
function setMap(lat,lon) {
    if(map != undefined || map != null){
        var container = document.getElementById('map_container')
        container.removeChild(map)
        var div = document.createElement('div')
        div.id = 'mapid'
        container.appendChild(div)
    }
    if(lat && lon) {
        var mymap = L.map('mapid').setView([lat, lon], 17)
        L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', 
        { attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>' })
        .addTo(mymap)
        var marker = L.marker([lat, lon]).addTo(mymap)
        /*
        var polyline = L.polyline([[45.80, 15.97],[45.81, 15.97]], {
            color: 'red'
            }).addTo(mymap);
        mymap.fitBounds(polyline.getBounds());*/
        marker.bindPopup(`<b>${naziv}</b><br/>širina: ${lat.toFixed(2)}<br/>duljina: ${lon.toFixed(2)}`).openPopup()
    } else {
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