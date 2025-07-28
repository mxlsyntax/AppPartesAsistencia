
const contenedorQR = document.getElementById('contenedorQR');
const formulario = document.getElementById('formulario');
const url_texto = document.getElementById('url_texto'); 

//NG20240626 CARGAMOS LA URL QUE NOS VIENE DE LAS PREF
var url = document.getElementById('qrbutton_pref').value;

//alert(url);

if (url == ''){
	url = 'https://globalsystem.es/AppWeb/AppViajesDietas/';
}


var text = document.createTextNode(url);
url_texto.appendChild(text);
document.getElementById('url_texto').href = url;


const QR = new QRCode(contenedorQR, url);

/**formulario.addEventListener('submit', (e) => {
	alert("llega");
	e.preventDefault();
	QR.makeCode(formulario.link.value);
});*/
