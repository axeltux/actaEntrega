<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Acuse entrega: {{ $oficio }}</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" media="all">
  </head>
  <body>
    <header class="clearfix">    
      <div id="logo" align="center">
        <img src="sat.png">
      </div>
      <div id="titulo">
        <b>Administración General de Recursos y Servicios<br>Administración Central de Control y Seguridad Institucional<br>Administración de Proyectos, Tecnologías de la Información, CCTV y Servicios Vehiculares</b>
      </div>      
    </header>
    <main>
    <center><h4>ACTA DE ENTREGA RECEPCIÓN DE BIENES (CREDENCIALES)<br>PROYECTO "CAyAS"</h4></center>
    <table border="1">
      <tr>
        <td style="width:10%">
          Partes que Intervienen
        </td>
        <td>
          <p align="justify">En la Ciudad de México, siendo las <b>{{ $hora }}</b> horas del día <b>{{ date("d", strtotime($fecha)) }}</b> de <b>{{ ToolsPHP::NombreMes(date("m", strtotime($fecha))) }}</b> del año <b>{{ strtolower(NumeroALetras::convertir(date("Y", strtotime($fecha)))) }}</b>, se reunieron en las oficinas del CERyS de la Ciudad de México, ubicado en Av. Hidalgo N° 77, Módulo V, segundo piso, Col. Guerrero, Delegación Cuauhtémoc, C.P. 06300, Ciudad de México, por parte del Servicio de Administración Tributaria, el Lic. Jesús Calleja Hernández y por los proveedores empresas DR México, S.A. de C.V., e Infracom Outsorcing, S.A. de C.V., proveedores del contrato Número <b>CS-300-LP-N-P-FC-061/17</b> del “Servicio de Arrendamiento de la Infraestructura para el Control de Acceso y Asistencia del SAT CAyAS”, representadas por el C. Gustavo Ambriz Romero, cuya representación se hace notar al final de la presente acta, en su carácter de Apoderado Legal, como testigos de asistencia los CC. y __________ quienes se identifican con credencial expedida por el Servicio de Administración Tributaria número  __________ y __________, todos ellos adscritos a la Administración General de Recursos y Servicios, con el objeto de levantar el acta de entrega de recepción de Credenciales oficiales de identificación del personal del SAT, de conformidad con lo establecido en el Contrato No. <b>CS-300-LP-N-P-FC-061/17</b> y sus anexos respectivos.-----------------------------------------------------------------------------------</p>
        </td>
      </tr>
      <tr>
        <td style="width:10%">
          <p align="center">Descripción de los bienes entregados</p>
        </td>
        <td>
          <p align="justify">Los proveedores empresas DR México, S.A. de C.V., e Infracom Outsorcing, S.A. de C.V., entregan al CERyS al rubro indicado las nuevas credenciales SAT, con las siguientes características: Elaboradas en tarjetas blancas de PVC (compuesto en 60% PVC y 40% poliéster), con chip doble tecnología Mifare y UHF, con elementos de seguridad visual y anti falsificación: Impresión Guilloche, Microtexto; tintas ultravioleta, fotografía fantasma, película de laminación con diseño  holográfico en 3D, y tinta fluorescente ultravioleta, microimpresión y logotipo de la dependencia:----------------------------------------------------------------</p>
          <table border="1">
            <thead>
              <tr>
                <th class="service">OFICIO</th>
                <th class="desc">LOTES</th>
                <th class="service">CANTIDAD</th>
              </tr>
            </thead>
            <tbody>
                @foreach($oficios as $element)
                    <tr>
                        <td class="service">{{ $element->oficio }}</td>
                        <td class="desc">{{ $element->lotes }}</td>
                        <td class="desc"><b>{{ $totalLotes }} credenciales</b></td>
                    </tr>
                @endforeach
            </tbody>
          </table>
          <p align="justify">
          Asimismo, se reciben <b>{{ $totalLotes }}</b> porta gafetes transparentes y <b>{{ $totalLotes }}</b> cintillas, que contiene el logotipo y nombre del SAT.---------------------------------------------------<br>
          Total de Credenciales: Número: <b>{{ $totalLotes }}</b> Letra: <b>{{ strtolower(NumeroALetras::convertir($totalLotes)) }}</b>------------------------------------.</p>          
        </td>
      </tr>
      <tr>
        <td style="width:10%">
          Objeto de la entrega de bienes
        </td>
        <td>
          <p align="justify">Las nuevas credenciales de identificación oficial para personal SAT, de las Unidades Administrativas antes indicadas fueron recibidas por medio de mensajería acelerada el día de hoy <b>{{ date("d") }}</b> de <b>{{ ToolsPHP::NombreMes(date("m")) }}</b> del año <b>{{ strtolower(NumeroALetras::convertir(date("Y"))) }}</b> y cumpliendo con toda y cada una de las especificaciones del Anexo Técnico y propuesta técnica del proveedor.----------------------------------------------------------</p>          
        </td>
      </tr>
      <tr>
        <td style="width:10%">
          Cierre
        </td>
        <td>
          <p align="justify">Las partes que intervienen en esta acta de entrega y recepción de bienes, hacen constar la entrega de las nuevas credenciales oficiales de identificación del   personal del SAT de conformidad con el Contrato No. <b>CS-300-LP-N-P-FC-061/17</b>, de fecha 14 de diciembre del 2017, celebrado entre el Servicio de Administración Tributaria y por los proveedores empresas DR México, S.A. de C.V., e Infracom Outsorcing, S.A. de C.V. sin más hechos que hacer constar se cierra la presente acta siendo las __________ horas del día de su inicio.------------------------</p>
        </td>
      </tr>
    </table>
      <div id="notices">
        <div>Firma digital:</div>
        <div class="notice" style="width:700px; word-wrap:break-word;"> {{ $firmaD }} </div>
      </div>
      <br>
      <div id="notices">
        <div>Sello digital:</div>
        <div class="notice" style="width:700px; word-wrap:break-word;">{{ $selloD }}</div>
      </div>
    </main>
    <footer>
      Documento firmado digitalmente.
    </footer>
  </body>
</html>