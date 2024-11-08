<html>
    <head>
        <style>
            .color-red{
                color: blue;
            }
            .no-padding{
                padding-top: 0px;
                padding-bottom: 0px;
                margin-top: 0px;
                margin-bottom: 0px;
            }


             .table {
                width: 100%;
                max-width: 100%;
                margin-bottom: 0px;
                background-color: #fff;
                border-bottom: 1px solid #a5a5a5;
            }

            .table th{
                background: #eff0f3;
                /* Color de titulos Opcional */
                color: #3e3cbb; 
            }

            .color-primary{
                color: #3e3cbb; 
            }

            th, td {
                padding: 8px;
                vertical-align: top;
                border-top: 1px solid #cbcbcb;
                text-align: left;
                color: #393939;
            }

            thead th {
                vertical-align: bottom;
                border-bottom: 1px solid red;
            }

            tbody + tbody {
                border-top: 1px solid red;
            }

            .table {
                background-color: #fff;
                border-radius: 0px;
                border: none;
                border-left: 2px solid #c7c7c7;
                border-right: 2px solid #c7c7c7;
                }

                .header-request{
                    margin-bottom: 15px;
                }

                .table-header{
                    border-left: 6px solid #3e3cbb;
                    padding: 10px;
                    color: #333333;
                    margin-bottom: 8px;
                }

                .title-request{
                    color: #333333;
                    font-size: 20px;
                    text-align: center;
                    margin-bottom: 11px;
                }

                .container-separator-fields{
                    margin-top: 20px;
                }

                h4,p{
                    color: #393939;
                }
        </style>
    </head>
    <body>
        <div class="header-request">
            <p class="no-padding"> Radicado : <strong><?php echo $radicado; ?> </strong> </p>
            <p class="no-padding"> Fecha : <strong> <?php echo $fecha; ?> </strong> </p>
        </div>

        <div class="title-request">
            Vinculación fondo de empleados 
        </div>

        <div class="container-separator-fields">

            <div class="table-header">
                INFORMACIÓN PERSONAL
            </div>
            <table class="table-fields table">
                <tbody>
                    <tr>
                        <th>Número de documento</th>
                        <th>Tipo de documento</th>
                    </tr>
                    <tr>
                        <td>  <?php echo $request_data['documento']; ?>  </td>
                        <td> <?php echo $request_data['tipo_documento']; ?>   </td>
                    </tr>

                    <tr>
                        <th>Primer apellido</th>
                        <th>Segundo apellido</th>
                    </tr>
                    <tr>
                        <td> <?php echo $request_data['primer_apellido']; ?> </td>
                        <td> <?php echo $request_data['segundo_apellido']; ?></td>
                    </tr>

                    <tr>
                        <th>Primer nombre</th>
                        <th>Segundo nombre</th>
                    </tr>
                    <tr>
                        <td> <?php echo $request_data['primer_nombre']; ?></td>
                        <td> <?php echo $request_data['segundo_nombre']; ?> </td>
                    </tr>

                    <tr>
                        <th>País expedición cédula</th>
                        <th>Departamento expedición cédula</th>
                    </tr>
                    <tr>
                        <td> <?php echo $request_data['pais_expedicion_cedula']; ?></td>
                        <td> <?php echo $request_data['departamento_expedicion_cedula']; ?></td>
                    </tr>

                    <tr>
                        <th>Municipio expedición cédula</th>
                        <th>Fecha de expedición cédula</th>
                    </tr>
                    <tr>
                        <td> <?php echo $request_data['municipio_expedicion_cedula']; ?></td>
                        <td> <?php echo $request_data['fecha_expedicion_cedula']; ?></td>
                    </tr>

                    <tr>
                        <th>Fecha de nacimiento</th>
                        <th>Estado civil</th>
                    </tr>
                    <tr>
                        <td> <?php echo $request_data['fecha_nacimiento']; ?></td>
                        <td> <?php echo $request_data['estado_civil']; ?></td>
                    </tr>

                    <tr>
                        <th>Nivel de estudio</th>
                        <th>Estrato social</th>
                    </tr>
                    <tr>
                        <td> <?php echo $request_data['nivel_estudio']; ?></td>
                        <td> <?php echo $request_data['estrato']; ?></td>
                    </tr>

                    <tr>
                        <th>Ocupación / profesión</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['profesion']; ?></td>
                    </tr>

                    <tr>
                        <th>Tipo de vivienda</th>
                        <th>Dirección de residencia</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['tipo_vivienda']; ?></td>
                        <td><?php echo $request_data['direccion']; ?></td>
                    </tr>

                    <tr>
                        <th>Departamento de la residencia</th>
                        <th>Municipio de la residencia</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['departamento']; ?></td>
                        <td><?php echo $request_data['municipio']; ?></td>
                    </tr>

                    <tr>
                        <th>Barrio de la residencia</th>
                        <th>Número de celular</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['barrio']; ?></td>
                        <td><?php echo $request_data['telefono_celular']; ?></td>
                    </tr>

                    <tr>
                        <th>Número de teléfono fijo</th>
                        <th>Email</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['telefono_fijo']; ?></td>
                        <td><?php echo $request_data['email_personal']; ?></td>
                    </tr>

                </tbody>

            </table>
        </div>


        


        <div class="container-separator-fields">

            <div class="table-header">
                INFORMACIÓN LABORAL
            </div>
            <table class="table-fields table">
                <tbody>
                    <tr>
                        <th>Empresa para la que laboras</th>
                        <th>Línea/área en la que trabajas</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['sociedad']; ?></td>
                        <td><?php echo $request_data['cliente_area']; ?></td>
                    </tr>

                    <tr>
                        <th>Cargo</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['cargo']; ?></td>
                        <td></td>
                    </tr>

                </tbody>

            </table>
        </div>



        <div class="container-separator-fields">

            <div class="table-header">
                INFORMACIÓN FINANCIERA
            </div>
            <table class="table-fields table">
                <tbody>
                    <tr>
                        <th>Ingresos mensuales (Salario)</th>
                        <th>Egresos mensuales (Gastos fijos)</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['salario_fijo']; ?></td>
                        <td><?php echo $request_data['egresos_mensuales']; ?></td>
                    </tr>

                    <tr>
                        <th>Activos (Bienes)</th>
                        <th>Pasivos (Deudas)</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['activos']; ?></td>
                        <td><?php echo $request_data['pasivos']; ?></td>
                    </tr>

                    <tr>
                        <th>Patrimonio ( Activos - Pasivos)</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['patrimonio']; ?></td>
                        <td></td>
                    </tr>

                </tbody>

            </table>
        </div>



        <div class="container-separator-fields">

            <div class="table-header">
                INFORMACIÓN BANCARIA
            </div>
            <table class="table-fields table">
                <tbody>
                    <tr>
                        <th>Entidad bancaria</th>
                        <th>Número de cuenta bancaria nómina</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['entidad_bancaria']; ?></td>
                        <td><?php echo $request_data['numero_cuenta_bancaria']; ?></td>
                    </tr>

                    <tr>
                        <th>Tipo de cuenta</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['tipo_cuenta']; ?></td>
                        <td></td>
                    </tr>

                </tbody>

            </table>
        </div>


        <div class="container-separator-fields">

            <div class="table-header">
                DECLARACIÓN PEP (PERSONA PÚBLICAMENTE EXPUESTA)
            </div>
            <table class="table-fields table">
                <tbody>
                    <tr>
                        <th>Usted o algún miembro de su famila ostenta o ha ostentado algún cargo público ?</th>
                        <th>Maneja usted recursos públicos o es persona expuesta políticamente?</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['ha_tenido_cargo_publico']; ?></td>
                        <td><?php echo $request_data['persona_expuesta_publicamente']; ?></td>
                    </tr>

                    <tr>
                        <th>Se le ha confiado una función pública prominente en una organización internacional o en el estado?</th>
                        <th>Pasivos (Deudas)</th>
                    </tr>
                    <tr>
                        <td><?php echo $request_data['ha_tenido_funcion_publica']; ?></td>
                        <td>0</td>
                    </tr>

                </tbody>

            </table>
        </div>



        <div class="container-separator-fields">

            <div class="table-header">
                AUTORIZACIONES DEDUCCIONES MENSUALES PARA AHORRO OBLIGATORIO
            </div>
            <table class="table-fields table">
                <tbody>
                    <tr>
                        <th> Ahorro permanente-Aporte social (AHORRO OBLIGATORIO) </th>
                    </tr>

                    <tr>
                        <td>Este ahorro equivale a un porcentaje de tu salario básico que esta entre el 3% hasta el 10% y solo se te entrega cuando te retires de la compañia o del fondo de empleados de manera voluntaria.</td>
                    </tr>

                    <tr>
                        <th> Seleccione el porcentaje del ahorro obligatorio </th>
                    </tr>

                    <tr>
                        <td><?php echo $request_data['porcentaje_ahorro_obligatorio']; ?>%</td>
                    </tr>

                </tbody>

            </table>
        </div>


        <div class="container-separator-fields">

            <div class="table-header">
                AUTORIZACIONES DEDUCCIONES MENSUALES VOLUNTARIAS DE AHORRO (TU DEFINES EL VALOR DESDE $10.000 AL MES)
            </div>
            <table class="table-fields table">
                <tbody>
                    <tr>
                        <th> Imprevisto </th>
                        <th> Voluntario </th>
                        <th> Navideño </th>
                    </tr>

                    <tr>
                        <td>$<?php echo $request_data['capital_imprevisto']; ?></td>
                        <td>$<?php echo $request_data['capital_voluntario']; ?></td>
                        <td>$<?php echo $request_data['capital_ahorro_navideno']; ?></td>
                    </tr>

                    <tr>
                        <th> Educativo </th>
                        <th> Fecha devolución </th>
                    </tr>

                    <tr>
                        <td>$<?php echo $request_data['capital_educativo']; ?></td>
                        <td><?php echo $request_data['fecha_capital_educativo']; ?></td>
                    </tr>

                    <tr>
                        <th> Vacacional </th>
                        <th> Fecha devolución </th>
                    </tr>

                    <tr>
                        <td>$<?php echo $request_data['capital_vacacional']; ?></td>
                        <td><?php echo $request_data['fecha_capital_devolucion']; ?></td>
                    </tr>

                    <tr>
                        <th> Junior </th>
                        <th> Fecha devolución </th>
                    </tr>

                    <tr>
                        <td>$<?php echo $request_data['capital_junior']; ?></td>
                        <td><?php echo $request_data['fecha_capital_junior']; ?></td>
                    </tr>

                    <tr>
                        <th> Cumpleaños </th>
                        <th> Fecha devolución </th>
                    </tr>

                    <tr>
                        <td>$<?php echo $request_data['capital_cumpleanos']; ?></td>
                        <td><?php echo $request_data['fecha_capital_cumpleanos']; ?></td>
                    </tr>

                    <tr>
                        <th> Prestacional </th>
                        <th> Fecha devolución </th>
                    </tr>

                    <tr>
                        <td>$<?php echo $request_data['capital_prestacional']; ?></td>
                        <td><?php echo $request_data['fecha_capital_prestacional']; ?></td>
                    </tr>
                    
                </tbody>

            </table>
        </div>


        <div class="container-separator-fields">

            <div class="table-header">
                POR QUE MEDIO SE ENTERO
            </div>
            <table class="table-fields table">
                <tbody>
                    <tr>
                        <th> Especificanos por que medio te enteraste del fondo konecta </th>
                    </tr>

                    <tr>
                        <td><?php echo $request_data['medio_escucha']; ?></td>
                    </tr>
                    
                </tbody>

            </table>
        </div>


        <div class="container-separator-fields">

            <div class="table-header">
                AUTORIZACIONES GENERALES
            </div>

                   <h4>DONACIÓN</h4>
                    <p>Autorizo al FONDO DE EMPLEADOS MULTIENLACE – CREA a que me realice la deducción de la DONACIÓN la cual será destinada de acuerdo a las políticas establecidas por la Junta Directiva.</p>
                    <p>Acepto la deducción de esta donación en cuatro (4) quincenas, las cuales será realizadas por deducción de nómina, en caso de reingreso acepto el pago del 50% del valor del ahorro obligatorio siempre y cuando no permanezca un año afiliado.</p>
                    
                    <h4>FONDO DE BIENESTAR SOCIAL</h4>
                    <p>Se te descontarán $13.000 mensuales los cuales se destinarán a las actividades de Bienestar Social.</p>
                    <p>Para conocer más ingresa a la sección de Auxilios en www.fondokonecta.com.co, podrás disfrutar hasta el 30% de 1 SMMLV una vez por año.</p>

                    <h4>FONDO DE BIENESTAR SOCIAL</h4>
                    <p>Con el propósito de dar cumplimiento a lo estipulado por la Superintendencia de la economía solidaria y las normas legales referentes a los servicios de ahorro y crédito, realizo la siguiente declaración sobre el origen de los bienes y/o recursos:</p>
                    <p>Declaro que los recursos que entrego no provienen de ninguna actividad ilícita de las contempladas en el código penal colombiano o en cualquier otra norma que lo modifique o adicione.</p>
                    <p>No admitiré que terceros efectúen depósitos a mis cuentas con recursos provenientes de actividades ilícitas contempladas en el código penal colombiano o en cualquier otra norma que le adicione, ni efectuare transacciones destinadas a tales actividades o a favor de personas relacionadas con las  mismas.</p>

                    <h4>AUTORIZACIÓN DE DESCUENTOS</h4>
                    <p>Autorizo expresa e irrevocablemente MULTIENLACE S.A.S., al actual y futuro pagador donde labore o llegaré a laborar o prestar servicios, para que descuente de mi salario, pensiones, vacaciones, prestaciones, indemnizaciones, liquidaciones o cualquier otro rubro que me corresponda por la prestación de mis labores o servicios y pague al FONDO DE EMPLEADOS MULTIENLACE – CREA, el valor correspondiente a todas las cuotas establecidas con destino a los aportes, ahorros ordinarios y extraordinarios, seguros, créditos y servicios que me fuesen otorgados, conforme a las disposiciones legales y reglamentarias.</p>
                    <p>En el evento que por alguna razón no sean descontadas de mi salario, me comprometo a cancelarlas directamente en las oficinas del FONDO DE EMPLEADOS MULTIENLACE – CREA   en la fecha acordada.</p>
                    <p>Autorizo a la EPS o ARL que descuente al citado ingreso por incapacidades, de forma indivisible, incondicional e interrumpida, hasta completar el monto total adeudado en capital, intereses, ahorros, aportes y demás obligaciones contraídas  al FONDO DE EMPLEADOS MULTIENLACE – CREA.</p>
                    <p>En caso tal de que exista retiro de la empresa que determina el vínculo de asociación o cualquier otra empresa en la que llegaré a laborar o prestar mis servicios sin darse la cancelación total de las obligaciones, autorizo al pagador de la respectiva empresa, al respectivo Fondo de Cesantías o la entidad correspondiente, para que se descuente y retenga sin límite de cuantía sobre cualquier suma que deba pagarse al autorizante por concepto de salarios, honorarios, vacaciones, prestaciones sociales, bonificaciones especiales, ocasionales o permanentes, bonos, indemnizaciones y cualquier otro pago que perciba por otro concepto en virtud a la relación contractual y no estipulado literalmente, la cantidad que sea necesaria para cubrir el saldo insoluto de la obligación contraída con el FONDO DE EMPLEADOS MULTIENLACE – CREA.</p>

                    <h4>ABONO AUTOMATICO CUENTA DE NOMINA</h4>
                    <p>Autorizo al FONDO DE EMPLEADOS MULTIENLACE - CREA para que abone a mi cuenta bancaria de nómina reportada por MULTIENLACE S.A.S. el actual o futuro pagador donde labore o llegaré a laborar o prestar servicios, en forma automática los valores que por cualquier concepto deba ser entregado a mi favor. Exonerando de cualquier responsabilidad distinta a la de realizar el abono automático, por lo tanto, no existe otra responsabilidad por el manejo posterior de la respectiva cuenta.</p>

                    <h4>CONSULTA Y REPORTES EN LISTAS</h4>
                    <p>Autorizo al FONDO DE EMPLEADOS MULTIENLACE - CREA para que de manera permanente y exclusiva para fines de información financiera, consulte, reporte, actualice, registre, rectifique y obtenga de las centrales de información y de los demás bancos de datos autorizados para tales efectos, la información relacionada con mis operaciones financieras y crediticias que bajo cualquier modalidad se me hubieren otorgado o se me otorguen en el futuro, hasta que así se considere necesario.</p>
                    <p>Autorizo al FONDO DE EMPLEADOS MULTIENLACE - CREA para que en caso de incumplimiento de mis obligaciones, efectúe el reporte  negativo de la información a la central de riesgo transcurridos veinte (20) días calendario siguientes a la fecha del envió de la comunicación a la dirección o correo electrónico registrada en esta solicitud o la última dirección o correo electrónico registrado en la base de datos.</p>
                    <p>Declaro que los recursos entregados al Fondo no provienen de ninguna actividad ilícita y autorizo al FONDO DE EMPLEADOS MULTIENLACE - CREA para que me consulte en los listados vinculados con el lavado de activos y financiación del terrorismo LA/FT. </p>

                    <h4>TRATAMIENTO DE DATOS PERSONALES</h4>
                    <p>En cumplimiento de lo dispuesto en la Ley 1581 de 2012, el Decreto Ley 1377 de 2013 y las que los modifiquen, así como las demás leyes que regulen el tema de Habeas Data, declaro que el Fondo de Empleados Multienlace Crea, como responsable del tratamiento y protección de mis datos personales, recolectados en virtud de mi calidad como asociado me ha dado a conocer los derechos que me asisten como titular de la información, para la protección, modificación o supresión de los mismos, además de la disponibilidad para consultarlos permanentemente a través de la página www.fondokonecta.com.co/, “Política para el tratamiento y la protección de datos personales”, por lo tanto, autorizo a el Fondo de Empleados Multienlace Crea o a quien represente u ostente en el futuro la calidad de contratante o cualquier calidad como titular de la información de forma permanente, para:</p>
                    <p>1. Recolectar, consultar, actualizar, modificar, procesar y eliminar la información referente a mis datos personales, consignados en esta solicitud y sus anexos, o proporcionada a través de cualquier otro medio (físico, virtual, telefónico o electrónico).</p>
                    <p>2. Soliciten, consulten, compartan, informen, reporten, procesen, modifiquen, aclaren, retiren o divulguen, ante las entidades de consulta de bases de datos públicas o privadas u operadores de Información y riesgo, o ante cualquier otra entidad que maneje o administre bases de datos con los fines legalmente definidos para este tipo de entidades todo lo referente a relaciones o servicios prestados o sostenidos con otras entidades o personas.</p>
                    <p>3. Compartan, transmitan, transfieran y divulguen mi información y documentación con otras entidades públicas o privadas, a fin de que las entidades usen mis datos, a partir de la recepción de los mismos para: gestionar, establecer, mantener, administrar y terminar la relación de asociación o para la prestación de servicios con terceros; administrar el riesgo de lavado de activos y de financiación del terrorismo; reportar información a las autoridades judiciales, aduaneras, de impuestos, y cualquier otra que lo requiera en virtud de sus funciones.</p>

                    <h4>CONTRATO DE MANDATO</h4>
                    <p>Autorizo, celebrar el siguiente CONTRATO DE MANDATO, el cual se regirá de acuerdo con los lineamientos generales aquí contemplados y por la legislación aplicable, de acuerdo a las siguientes cláusulas:</p>
                    <p>PRIMERA: El asociado quién en este contrato se denominará el MANDANTE, le confiere al FONDO DE EMPLEADOS MULTIENLACE CREA  quién se denominará el MANDATARIO, para que en nombre del primero (mandato con representación) o en el de éste (mandato sin representación) ejecute los actos de comercio que sean definidos y aprobados por la Junta Directiva del FONDO DE EMPLEADOS MULTIENLACE CREA.</p>
                    <p>SEGUNDA: El MANDATARIO se obligará a adquirir directamente y a su nombre, para después trasladar a favor del asociado, a cualquier título, con proveedores legalmente reconocidos, bienes y servicios que proporcionen bienestar al asociado y a su grupo familiar, siempre que desarrollen de conformidad con el marco legal de las entidades del sector de la economía solidaria en general y de los fondos de empleados en particular.</p>
                    <p>TERCERA: El MANDANTE, podrá cancelar la obligación adquirida con un pago al contado o mediante financiación de acuerdo a las tarifas establecidas con EL MANDATARIO, y siempre atendiendo aquellas que el MANDANTE considere más favorables para sí.</p>

                    <h4>AUTORIZACIÓN PARA COMPARTIR INFORMACIÓN</h4>
                    <p>Autorizo expresa e irrevocablemente a MULTIENLACE S.A.S. al actual y futuro pagador donde labore o llegaré a laborar o prestar servicios a través de FONDO DE EMPLEADOS MULTIENLACE –CREA- para compartir información sobre el estado de mi nómina y relación laboral, incluido, pero sin limitarse a ello, fecha de ingreso, cargo, salario, descuentos autorizados, disminución o incremento en ingresos, fecha de retiro, deducciones, devengado, capacidad de endeudamiento y el número y tipo de mi cuenta bancaria de nómina.</p>

                    <p class="color-primary"> <strong>Aceptado</strong> </p>

        </div>
            
        <p>Este formulario fue autorizado por medio de la aplicación Jarvis y mi ingreso se realizo con el logueo del usuario:  (<?php echo $request_data['usuario_red']; ?>).</p>

    </body>
</html>