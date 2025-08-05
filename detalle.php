<html>
<div class="row flex-wrap container-fluid">
    <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" align="center">
        <div class="input-group mb-3">
            <div class="form-floating">
                <input class="form-control" id="cdcli" name="cdcli" type="text" value="<?= htmlspecialchars($cdcli) ?>" style="padding-left:8px">
                <label for="cdcli">Denominación Viaje</label>
            </div>
        </div>
    </div>
</div>

<div class="row flex-wrap container-fluid">
    <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
        <div class="input-group mb-3">
            <div class="form-floating">
                <input class="form-control" id="fecha_ini" name="fecha_ini" type="date" placeholder="Fecha envío" style="padding-left:8px;font-size: 2.5em;">
                <label for="fecha_ini">Fecha Inicio</label>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
        <div class="input-group mb-3">
            <div class="form-floating">
                <input class="form-control" id="fecha_fin" name="fecha_fin" type="date" placeholder="Fecha envío" style="padding-left:8px;font-size: 2.5em;">
                <label for="fecha_fin">Fecha Fin</label>
            </div>
        </div>
    </div>
</div>

<div class="container" style="display:none;">
    <div class="input-group mb-3">
        <input class="form-control" id="cd_trabajador_bus" name="cd_trabajador_bus" type="text" placeholder="cd_trabajador_bus" style="max-width: 80px; display:none;" disabled>
        <div class="form-floating">
            <input class="form-control" id="deno_trabajador_bus" name="deno_trabajador_bus" type="text" placeholder="Trabajador" style="padding-left:8px" disabled>
            <label for="deno_trabajador_bus">Selección trabajador</label>
        </div>
        <!-- Selección de trabajador -->
        <button class="btn btn-outline-secondary" type="button" id="botonSelTrabajador" style="width: 80px; background: #003061" onclick="abrirModalSeleccionTrabajador()" title="Selección de trabajador"><i class="fa-solid fa-chevron-down" style="font-size: 2em; color: #FFFFFF;"></i></button>
    </div>
</div>

<!-- NG20241216 ESTA FORMA DE LLAMAR A LOS DISTINTOS DIVS MUESTRA EN TAMAÑO QUE QUEREMOS EN VENTANA COMPLETA O EN VERSION ANDROID
                Y EN VERSION ANDROID SE VEN EN FILAS INDEPENDIENTES A TAMAÑO COMPLETO. -->
<div class="row flex-wrap container-fluid">
    <div class="col-xl-12 col-md-12 col-xs-12 col-sm-12 p-1" align="center">
        <div class="input-group mb-3 d-flex align-items-end">
            <div class="form-floating me-3">
                <div class="d-flex flex-wrap">
                    <h4 class="text-start  mb-1" style="color: #003061; ">Trabajadores:</h4>
                    <h4 class="text-start  mb-1" id="txt_num_trab" style="color: #2edc00; margin-left: 10px;">0</h4>
                </div>
                <hr class="m-0" style="color: #003061;border-top-width: 5px;opacity: 100;">
                <div class="form-floating">
                </div>
            </div>

            <button class="btn btn-outline-secondary" type="button" id="botonMostrarGridTrab" style="width: 80px; height: 80px; background: #003061" onclick="verGridTrab()" title="Mostrar trabajadores"><i class="fa-solid fa-eye" style="font-size: 2em; color: #FFFFFF;"></i></button>

            <button class="btn btn-outline-secondary" type="button" id="botonOcultarGridTrab" style="width: 80px; height: 80px; background: #f97e66; display:none;" onclick="ocuGridTrab()" title="Ocultar artículos"><i class="fa-solid fa-eye-slash" style="font-size: 2em; color: #FFFFFF;"></i></button>

            <button class="btn btn-outline-secondary" type="button" id="botonAnaTrab" style="width: 80px; height: 80px; background: #09de50" onclick="abrirModalSeleccionTrabajador()" title="Añadir trabajador"><i class="fa-solid fa-plus" style="font-size: 2em; color: #FFFFFF;"></i></button>
        </div>
    </div>
</div>


<div class="row flex-wrap container-fluid" id="div_gridTrab" style="display:none; border-radius: 10px; border-color: #003061; border-style: solid; padding: 5px; margin-bottom: 10px; align-content: center;">
    <section class="container-xxxl" id="demo-content">
        <!-- TABLA CON INICIO SOLO SI PINCHAMOS EN BUSCAR (COMENTADO EN EL METODO AJAX DE ABAJO)-->
        <!-- https://examples.bootstrap-table.com/index.html#welcome.html#view-source -->
        <!-- Usar data-card-visible="false" para las que queremos que se muestren
                        en modo movil -->
        <div id="toolbar_trab">
            <button id="btn_remove_trab" class="btn btn-danger" onclick="eliminarTrab()">
                <i class="fa fa-trash"></i> Eliminar Seleccionado
            </button>
        </div>
        <table id="tableTrab" class="table table-striped table-sm"
            data-toggle="table"
            data-toolbar="#toolbar_trab"
            data-locale="es-ES"
            data-search="false"
            data-show-toggle="false"
            data-show-fullscreen="false"
            data-show-refresh="false"
            data-mobile-responsive="true"
            data-show-export="false"
            data-unique-id="id"
            data-id-field="id"
            data-click-to-select="true"
            data-checkbox-header="false"
            data-single-select="true"
            data-show-columns="false"
            data-show-columns-toggle-all="false"
            data-visible-search="true"
            data-group-by="true"
            data-sort-order="asc"
            data-height="200"
            data-fixed-scroll="true">
            <thead>
                <tr>
                    <!-- data-card-visible="false" meterlo en encapsulado th si queremos que no se vea en el movil -->
                    <th data-field="state" data-checkbox="true"></th>
                    <th data-field="id" data-visible="false">Codigo</th>
                    <th data-field="deno">Trabajador</th>
                </tr>
            </thead>
        </table>

    </section>
</div>


<!-- NG20241216 ESTA FORMA DE LLAMAR A LOS DISTINTOS DIVS MUESTRA EN TAMAÑO QUE QUEREMOS EN VENTANA COMPLETA O EN VERSION ANDROID
                Y EN VERSION ANDROID SE VEN EN FILAS INDEPENDIENTES A TAMAÑO COMPLETO. -->


<div class="row flex-wrap container-fluid">
    <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
        <div class="input-group mb-3">

            <input class="form-control" id="cdProyecto" name="cdProyecto" type="text" placeholder="cdProyecto" style="max-width: 80px; display:none;" disabled>
            <div class="form-floating">
                <input class="form-control" id="deno_proyecto" name="deno_proyecto" type="text" placeholder="Proyecto" style="padding-left:8px" disabled>
                <label for="deno_proyecto">Selección Proyecto</label>
            </div>
            <!-- Selección de Proyecto -->
            <button class="btn btn-outline-secondary" type="button" id="botonSelProyecto" style="width: 80px; background: #003061" onclick="abrirModalSeleccionProyecto()" title="Selección de Proyecto"><i class="fa-solid fa-chevron-down" style="font-size: 2em; color: #FFFFFF;"></i></button>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
        <div class="input-group mb-3">

            <!-- <input class="form-control" id="cdzonaent" name="cdzonaent" type="text" placeholder="cdzonaent" style="max-width: 80px; display:none;" disabled>  -->
            <div class="form-floating">
                <input class="form-control" id="deno_wp" name="deno_wp" type="text" placeholder="WP" autocomplete="off" style="padding-left:8px">
                <label for="deno_wp">WP</label>
            </div>
        </div>
    </div>
</div>

<!-- NG20241216 ESTA FORMA DE LLAMAR A LOS DISTINTOS DIVS MUESTRA EN TAMAÑO QUE QUEREMOS EN VENTANA COMPLETA O EN VERSION ANDROID
                Y EN VERSION ANDROID SE VEN EN FILAS INDEPENDIENTES A TAMAÑO COMPLETO. -->
<div class="row flex-wrap container-fluid">
    <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
        <div class="input-group mb-3">
            <div class="form-floating">
                <input class="form-control" id="origen" name="origen" type="text" placeholder="Origen" style="padding-left:8px;">
                <label for="origen" style="padding-left:8px;">Origen</label>
            </div>
        </div>
    </div>

    <div class="col-xl-6 col-md-6 col-xs-12 col-sm-12 p-1" align="center">
        <div class="input-group mb-3">
            <div class="form-floating">
                <input class="form-control" id="destino" name="destino" type="text" placeholder="Destino" style="padding-left:8px;">
                <label for="destino" style="padding-left:8px;">Destino</label>
            </div>
        </div>
    </div>
</div>

<div class="row flex-wrap container-fluid">
    <div class="col-xl-8 col-md-8 col-xs-6 col-sm-6 p-1" align="center">
        <div class="input-group mb-3">
            <div class="form-floating">
                <input class="form-control" id="motivo" name="motivo" type="text" placeholder="Motivo" style="padding-left:8px">
                <label for="motivo">Motivo</label>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-xs-6 col-sm-6 p-1" align="center" style="display:">
        <div class="input-group mb-3">
            <div class="form-floating">
                <!-- xml:lang="es" para coma como separacion decimal en el input -->
                <input type="number" class="form-control" id="total" name="total" placeholder="Total" pattern="^\d+$" min=0 step="0.5" style="padding-left:8px;" onChange="filtroDecimal('total')" disabled>
                <label for="total" style="padding-left:8px;">Total</label>
            </div>
        </div>
    </div>
</div>

<!-- NG20241216 ESTA FORMA DE LLAMAR A LOS DISTINTOS DIVS MUESTRA EN TAMAÑO QUE QUEREMOS EN VENTANA COMPLETA O EN VERSION ANDROID
                Y EN VERSION ANDROID SE VEN EN FILAS INDEPENDIENTES A TAMAÑO COMPLETO. -->
<div class="row flex-wrap container-fluid">

    <div class="col-xl-12 col-md-12 col-xs-6 col-sm-6 p-1">
        <div class="input-group mb-3 d-flex align-items-end">
            <div class="form-floating me-3">
                <div class="d-flex flex-wrap">
                    <h4 class="text-start  mb-1" style="color: #003061; ">Dietas:</h4>
                    <h4 class="text-start  mb-1" id="txt_num_dietas" style="color: #2edc00; margin-left: 10px;"></h4>
                </div>
                <hr class="m-0" style="color: #003061;border-top-width: 5px;opacity: 100;">
            </div>
            <button class="btn btn-outline-secondary" type="button" id="botonMostrarGridDietas" style="width: 80px; height: 80px; background: #003061" onclick="verGridDietas()" title="Mostrar dietas"><i class="fa-solid fa-eye" style="font-size: 2em; color: #FFFFFF;"></i></button>

            <button class="btn btn-outline-secondary" type="button" id="botonOcultarGridDietas" style="width: 80px; height: 80px; background: #f97e66; display:none;" onclick="ocuGridDietas()" title="Ocultar dietas"><i class="fa-solid fa-eye-slash" style="font-size: 2em; color: #FFFFFF;"></i></button>

            <button class="btn btn-outline-secondary" type="button" id="botonAnaDietas" style="width: 80px; height: 80px; background: #09de50" onclick="anadirDietas()" title="Añadir dietas"><i class="fa-solid fa-plus" style="font-size: 2em; color: #FFFFFF;"></i></button>
        </div>
    </div>
</div>


<div class="row flex-wrap container-fluid" id="div_gridDietas" style="display:none; border-radius: 10px; border-color: #003061; border-style: solid; padding: 5px; margin-bottom: 10px; padding-block-end: 40px; align-content: center;">
    <section class="container-xxxl" id="demo-content">
        <!-- TABLA CON INICIO SOLO SI PINCHAMOS EN BUSCAR (COMENTADO EN EL METODO AJAX DE ABAJO)-->
        <!-- https://examples.bootstrap-table.com/index.html#welcome.html#view-source -->
        <!-- Usar data-card-visible="false" para las que queremos que se muestren
                        en modo movil -->
        <div id="toolbar">
            <button id="btn_remove_dietas" class="btn btn-danger" onclick="eliminarDieta()">
                <i class="fa fa-trash"></i> Eliminar Seleccionada
            </button>
        </div>
        <table id="tableDietas" class="table table-striped table-sm"
            data-toggle="table"
            data-toolbar="#toolbar"
            data-locale="es-ES"
            data-search="false"
            data-show-toggle="false"
            data-show-fullscreen="false"
            data-show-refresh="false"
            data-mobile-responsive="true"
            data-show-export="false"
            data-unique-id="id"
            data-id-field="id"
            data-click-to-select="true"
            data-checkbox-header="false"
            data-single-select="true"
            data-show-columns="false"
            data-show-columns-toggle-all="false"
            data-visible-search="true"
            data-group-by="true"
            data-sort-name="fecha_num"
            data-sort-order="asc"
            data-height="200"
            data-fixed-scroll="true">
            <thead>
                <tr>
                    <!-- data-card-visible="false" meterlo en encapsulado th si queremos que no se vea en el movil -->
                    <th data-field="id" data-sortable="true" data-visible="false">Cd Dieta</th>
                    <th data-field="deno" data-sortable="true" data-formatter="cellStyleEstado">Descripción</th>
                    <th data-field="categ" data-sortable="true" data-formatter="cellStyleGeneral">Categoría</th>
                    <th data-field="fecha" data-sortable="true" data-formatter="cellStyleGeneral">Fecha</th>
                    <th data-field="importe" data-sortable="true" data-formatter="cellStyleGeneral">Importe</th>
                    <th data-field="acep" data-sortable="true" data-visible="false">Aceptado</th>
                    <th data-field="deneg" data-sortable="true" data-visible="false">Denegado</th>
                    <th data-field="estado_dieta" data-sortable="true" data-formatter="cellStyleEstado">Estado</th>
                    <th data-field="cdcli" data-sortable="true" data-formatter="cellStyleGeneral">Viaje</th>
                    <th data-field="adjunt"></th>
                    <th data-field="cdtrab_alta" data-sortable="true" data-visible="false">cdtrab</th>
                    <th data-field="deno_trab_alta" data-sortable="true" data-visible="false">deno_trab</th>
                </tr>
            </thead>
        </table>

    </section>
</div>

<div class="row flex-wrap container-fluid" id="div_radio" style="background: #028a86; border-radius: 10px; padding: 15px; margin-bottom: 10px; align-content: center;" align="center">

    <div class="col-xl-4 col-md-4 col-xs-12 col-sm-12 p-1" align="center">
        <div class="form-check">
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-xs-12 col-sm-12 p-1" align="center" style="border-radius: 10px;">
        <div class="form-floating">
            <h2 style="color: #FFFFFF;" id="estado">Estado</h2>
        </div>
    </div>

    <div class="col-xl-4 col-md-4 col-xs-12 col-sm-12 p-1" align="center">
        <div class="form-check" style="display: " id="div_checkbox_finalizar">
            <input class="granCheckbox" type="checkbox" value="Finalizar?" id="cbx_finalizar" />
            <label class="form-check-label" for="cbx_pce_finalizar" id="label_cbx_finalizar" style="margin-left: 10px; font-weight:bold; font-size: 20px; color: white;">
                Finalizar?
            </label>
        </div>
    </div>
</div>

<div class="container-fluid" id="div_obs_ant" style="display:none;">
    <div class="form-floating">
        <div class="row mb-6">
            <div class="col-xl-9" align="center" style="padding-left:8px">
                <div class="input-group mb-3">
                    <div class="form-floating">
                        <textarea class="form-control" id="pce_obs_ant" name="pce_obs_ant" type="text" placeholder="Observaciones" style="height: 168px;" readonly></textarea>
                        <label for="pce_obs_ant" class="form-label">Observaciones Ant.</label>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="form-floating">
        <div class="row mb-6">
            <div class="col-xl-12" align="center" style="padding-left:8px">
                <div class="input-group mb-3">
                    <div class="form-floating">
                        <textarea class="form-control" id="pce_obs" name="pce_obs" type="text" placeholder="Observaciones" style="height: 168px;"></textarea>
                        <label for="pce_obs">Observaciones</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</html>