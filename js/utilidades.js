//NG20240724 PASO DE FECHA STRING A FECHA NUM
function Fecha_aNum(fecha) {
  var fecha_gsbase = 0;
  if (fecha != "") {
    lst_fecha = fecha.split("/", -1);
    sdia = lst_fecha[0];
    smes = lst_fecha[1];
    sano = lst_fecha[2];

    ano = parseInt(sano);
    mes = parseInt(smes);
    dia = parseInt(sdia);
    ano = ano - 2000;
    mes = mes - 1;
    dia = dia - 1;
    fecha_gsbase += ano * 372;
    fecha_gsbase += mes * 31;
    fecha_gsbase += dia;
  }
  return fecha_gsbase;
}

//NG20240724 PASO DE FECHA GSBASE INT A STRING FECHA
function Num_aFecha(fecha_gsbase) {
  var fecha = "";
  if (fecha_gsbase > 0) {
    let ano = parseInt(fecha_gsbase / 372);
    let dias = parseInt(fecha_gsbase - ano * 372);
    let mes = parseInt(dias / 31);

    //alert(mes);
    var diasMes = new Date(ano, mes + 1, 0).getDate();
    //alert(diasMes);

    let dia = parseInt(dias - mes * 31);
    ano = 2000 + ano;
    dia += 1;
    mes += 1;
    fecha =
      dia.toString().padStart(2, "0") +
      "/" +
      mes.toString().padStart(2, "0") +
      "/" +
      ano.toString().padStart(2, "0");
  }
  return fecha;
}

//NG20241120 PASO DE STRING FECHA A DIA DE LA SEMANA
function Fecha_aDiaSem(fecha_string) {
  var d = 0;
  var nombre_dia_sem = "";
  const dias = [
    "Domingo",
    "Lunes",
    "Martes",
    "Miércoles",
    "Jueves",
    "Viernes",
    "Sábado",
  ];

  if (fecha_string != "") {
    d = new Date(format_fecha_global(fecha_string));
    nombre_dia_sem = dias[d.getDay()];
  }

  //alert(nombre_dia_sem);
  return nombre_dia_sem;
}

//Devolverá el date correspondiente al dia de la semana, siendo int 1 Lunes y 7 Domingo
function getFechaDiaSem(int_dia) {
  const dias = [
    "Lunes",
    "Martes",
    "Miércoles",
    "Jueves",
    "Viernes",
    "Sábado",
    "Domingo",
  ];
  var curr = new Date(); // get current date
  var first = curr.getDate() - curr.getDay() + int_dia;

  var firstday = new Date(curr.setDate(first)).toUTCString();

  var good_day = formatDate(new Date(curr.setDate(first)));
  good_day = dias[int_dia - 1] + " " + good_day.split("-").reverse().join("/");

  alert(good_day);
  return good_day;
}

//NG20240725 A PARTIR DE UNA DATE DEVUELVE FECHA STRING FORMATO aaaa-mm-dd
function formatDate(date) {
  var d = new Date(date),
    month = "" + (d.getMonth() + 1),
    day = "" + d.getDate(),
    year = d.getFullYear();

  if (month.length < 2) month = "0" + month;
  if (day.length < 2) day = "0" + day;

  return [year, month, day].join("-");
}

//NG20240725 PASAMOS FECHA STRING FORMATO aaaa-mm-dd a string con formato dd/mm/aaaa
function format_fecha_local(fecha) {
  var fecha_devuelta = "";
  fecha_devuelta = fecha.split("-").reverse().join("/");
  return fecha_devuelta;
}

//NG20240725 PASAMOS FECHA STRING FORMATO dd/mm/aaaa a string con formato aaaa-mm-dd
function format_fecha_global(fecha) {
  var fecha_devuelta = "";
  fecha_devuelta = fecha.split("/").reverse().join("-");
  return fecha_devuelta;
}

//NG20240725 PASAMOS TIEMPO EN DECIMAL A SEXAGESIMAL
function convertirTiempo(tiempoEnDecimal) {
  if (tiempoEnDecimal == "") tiempoEnDecimal = 0;
  var hora_sex = "";
  var horas = parseInt(tiempoEnDecimal);
  var min = (tiempoEnDecimal - horas) * 60;
  //alert(min);

  hora_sex = tiempoEnDecimal.toString() + " (";
  if (horas > 0) {
    hora_sex += horas.toString() + " h ";
  }
  hora_sex += parseInt(min).toString().padStart(2, "0") + " min)";

  return hora_sex;
}

//NG20241217 A PARTIR DE UNA FECHA DEVOLVEMOS EL NUMERO DE SEMANA
function getDateWeek(fecha_date) {
  var d = new Date();
  //alert(d);
  //alert(fecha_date);
  if (fecha_date != "") {
    d = fecha_date;
  }
  let yearStart = +new Date(d.getFullYear(), 0, 1);
  let today = +new Date(d.getFullYear(), d.getMonth(), d.getDate());
  let dayOfYear = (today - yearStart + 1) / 86400000;
  let week = Math.ceil(dayOfYear / 7);
  return week;
}

//NG20240725 A PARTIR DE UN INT TIPO OBTENEMOS EL TIPO STRING DE AUSENCIA
function obtenerTipoAusInt(intTipo) {
  var tipoString = "";

  if (intTipo == 0) {
    tipoString = "Mitad Dia Vacaciones";
  } else if (intTipo == 1) {
    tipoString = "Accidente laboral";
  } else if (intTipo == 2) {
    tipoString = "Vacaciones";
  } else if (intTipo == 3) {
    tipoString = "Absentismo";
  } else if (intTipo == 4) {
    tipoString = "Permisos Retribuidos";
  } else if (intTipo == 5) {
    tipoString = "Asuntos propios";
  } else if (intTipo == 6) {
    tipoString = "Permiso Paternidad/Maternidad";
  } else if (intTipo == 7) {
    tipoString = "Fiesta local";
  } else if (intTipo == 8) {
    tipoString = "Reconocimiento Médico";
  } else if (intTipo == 9) {
    tipoString = "Mitad Dia Vacaciones";
  } else {
    tipoString = "Desconocido";
  }

  return tipoString;
}

//NG20240725 A PARTIR DEL TIPO STRING DE AUSENCIA OBTENEMOS  UN INT TIPO
function obtenerTipoAusString(tipoString) {
  var intTipo = 666;

  if (tipoString == "Enfermedad Común") {
    intTipo = 1;
  } else if (tipoString == "Accidente laboral") {
    intTipo = 2;
  } else if (tipoString == "Vacaciones") {
    intTipo = 3;
  } else if (tipoString == "Absentismo") {
    intTipo = 4;
  } else if (tipoString == "Permisos Retribuidos") {
    intTipo = 5;
  } else if (tipoString == "Asuntos propios") {
    intTipo = 6;
  } else if (tipoString == "Permiso Paternidad/Maternidad") {
    intTipo = 7;
  } else if (tipoString == "Fiesta local") {
    intTipo = 8;
  } else if (tipoString == "Reconocimiento Médico") {
    intTipo = 9;
  } else if (tipoString == "Mitad Dia Vacaciones") {
    intTipo = 0;
  }

  return intTipo;
}

function getDenoEstadoAusencia(stringCdEstadoAus) {
  var denoEstAus = "Solicitado";

  if (stringCdEstadoAus == "S") {
    denoEstAus = "Solicitado";
  } else if (stringCdEstadoAus == "A") {
    denoEstAus = "Aceptado";
  } else if (stringCdEstadoAus == "R") {
    denoEstAus = "Rechazado";
  }

  return denoEstAus;
}

//NG20240729 DEVUELVE true cuando las dos horas estan rellenas y la hora hasta es menor que la hora desde
function CompruebaHoras(horaDesde, horaHasta) {
  var valorDevuelto = false;
  //alert ("hora desde: " + horaDesde + ", hora hasta: " + horaHasta);
  if ((horaDesde != "") & (horaHasta != "")) {
    const time1 = horaDesde.split(":");
    const time2 = horaHasta.split(":");

    if (time1[0] > time2[0]) {
      valorDevuelto = true;
    } else if (time1[0] == time2[0]) {
      if (time1[1] > time2[1]) {
        valorDevuelto = true;
      }
    }
  }
  //alert ("devuelto:" + valorDevuelto);
  return valorDevuelto;
}

function getDenoEstadoFichaje(stringCdEstadoFich) {
  var denoEstFic = "Sin Fichaje";

  if (stringCdEstadoFich == "F") {
    denoEstFic = "Parado";
  } else if (stringCdEstadoFich == "I") {
    denoEstFic = "Iniciado";
  } else if (stringCdEstadoFich == "P") {
    denoEstFic = "Pausado";
  }

  return denoEstFic;
}

function getCdEstadoFichaje(denoEstFic) {
  var stringCdEstadoFich = "";

  if (denoEstFic == "Parado") {
    stringCdEstadoFich = "F";
  } else if (denoEstFic == "Iniciado") {
    stringCdEstadoFich = "I";
  } else if (denoEstFic == "Pausado") {
    stringCdEstadoFich = "P";
  }

  return stringCdEstadoFich;
}

function trunc(x, posiciones = 0) {
  var s = x.toString();
  var l = s.length;
  var decimalLength = s.indexOf(".") + 1;
  var numStr = s.substr(0, decimalLength + posiciones);
  return Number(numStr);
}

function getFileExtension(fileName) {
  let extension = fileName.substring(fileName.lastIndexOf(".") + 1);
  //console.log('File extension:', extension);

  return extension;
}

function intToSiNo(numero) {
  let respuesta = "No";
  if (numero == 1) respuesta = "Si";
  return respuesta;
}

//NG20241010 METODO QUE CON LOS CAMPOS DE PENDIENTE FIRMA Y FIRMADO
//DEVUELVE UN ESTADO RESUMIDO PARA LA VENTANA DETALLE
function devuelveEstadoFirma(pend_firma, firmado) {
  var est_firma = "No se requiere firma";
  if ((pend_firma == 1) & (firmado == 0)) {
    est_firma = "Pendiente firma";
  } else if (firmado == 1) {
    est_firma = "Documento ya firmado";
  }
  return est_firma;
}

function CalculaEstadoDieta(acept, deneg) {
  var est_dieta = "No se requiere firma";
  if ((acept == 0) & (deneg == 0)) {
    est_dieta = "Pendiente";
  } else if (acept == 1) {
    est_dieta = "Aceptado";
  } else if (deneg == 1) {
    est_dieta = "Denegado";
  }
  return est_dieta;
}
