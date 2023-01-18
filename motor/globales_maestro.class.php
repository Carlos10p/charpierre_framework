<?php

// Incluye libreria de Encriptacion
require_once __DIR__ . DIRECTORY_SEPARATOR . 'cypher.class.php';

/**
 * Clase que contendrá las variables globales a usar en todo el sistema.
 * 
 * @author Carlos Emilio Paredes 14/09/2015, Clase con acciones globales;
 */

class GlobalesMaestro
{
    //privadas
    private $arrFileExcep = array('reportes.php','facturacion.php','seguridad.php','CargaArchivo_v2.php');
    private $isDebug=false;
    private $pswGestorArchivosB="adminsiearchivos";
    private $error=false;
    private $errorMensaje="";
    private $pwdSupFact = "supfacturassie";
    private $pswTraductorTC = "admintraductortc";
    private $pswTraductorTEF = "admintraductortef";
    private $keyEncriptaColegio="4dMInc0l3g1oPsw";
    private $arrEnvolturaEncriptacion=array("VARINI_","_VARFIN");
    const REG_RFC = "/^[A-Z,—,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A]?$/D";
    const REG_CURP = "/^[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,—,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]$/D";
    
    //públicas
    public $res;
    public $sql;
    
    public $idMateria;
    public $idGrupo;
    public $idSalon;
    public $idCiclo;
    public $idExamenPeriodo;
    public $permiteOtrosHorarios;
    public $idUsuario;
    public $xmlNodoName="elemento";
    
    public $consulta = "";
    public $sp = "";
    public $origen = "SIE";
    public $isRegistaBitacoraEjecutado = 0;
    public $clg;
    public $idPais = 0;
    public $keyEncuestas = "sie2016encuesta";
    public $arrUniversidades = array("ucdv","demo_ucdv");
    public $arrFacturacionManual = array("rco");
    
    
    public function __construct()
    {
        return $this;
    }
   
    public function __destruct()
    {
        //unset($this);
    }
    
    public function dameFileExcep()
    {
        return $this->arrFileExcep;
    }
    
    /**
     * Función para regresar el listado de alumnos inscritos.
     *
     * @method global_regresaAlumnosInscritos
     * 
     * @param idEscuelas.- Escuelas a filtrar
     * @param idNiveles.- Niveles de estudios a filtrar
     * @para idCiclos.- Ciclos a filtrar
     * @para idGrados.- Grados a filtrar
     */
    public function global_regresaAlumnosInscritos($idEscuelas,$idNiveles,$idCiclos,$idGrados = null)
    {
        $filtroGrados = "";
        $idGrados = trim($idGrados);
        if($idGrados!="")
        {
            $filtroGrados = " and i.id_nivelescolar in (".$idGrados.") ";
        }
        
        $sql = "SELECT 
        i.matricula_alumno as matricula
        ,i.id_escuela as idEscuela
        ,i.id_nivelestudios as idNivelEstudios
        ,i.id_cicloescolar as idCiclo
        ,i.id_nivelescolar as idGrado
        ,i.id_inscripcion as idInscripcion
        ,i.id_planestudios as idPlanEstudios
        ,a.nombre_alumno as nombreAlumno
        ,a.paterno_alumno as paternoAlumno
        ,a.materno_alumno as maternoAlumno
        ,CONCAT_WS(' ',a.paterno_alumno,if(trim(a.materno_alumno) = '',null,a.materno_alumno),a.nombre_alumno) as nombreCompletoAlumno
        ,e.nombre_escuela as nombreEscuela
        ,ne.descripcion_nivelestudios as nivelEstudios
        ,gr.nombre_nivelescolar as grado
    FROM
        inscripcion i
            inner join
        alumno a ON a.matricula_alumno = i.matricula_alumno
            and i.id_cicloescolar in (".$idCiclos.")
            and i.id_escuela in (".$idEscuelas.")
            and i.id_nivelestudios in (".$idNiveles.")
            ".$filtroGrados."
            and i.tipo_inscripcion not in ('BAJA' , 'ELIMINADO')
            and i.fechabaja_inscripcion is null
            inner join
        escuela e on e.id_escuela = i.id_escuela
            inner join
        nivel_estudios ne on ne.id_nivelestudios = i.id_nivelestudios
            inner join
        nivel_escolar gr on gr.id_nivelescolar = i.id_nivelescolar
    order by i.id_escuela , i.id_cicloescolar , i.id_nivelestudios , i.id_nivelescolar, nombreCompletoAlumno
            ";
        
        return $sql;
    }
    
    /****************************************************************************************************
     FUNCION: dameError()
     OBJETIVO: RETORNA ARREGLO ERROR
     PARAMETROS: OBJETO:PROPIEDAD
     ****************************************************************************************************/
     public function dameError(){
        return array("ErrorMensaje"=>$this->errorMensaje,"Error"=>$this->error);
     }
    /****************************************************************************************************
    FUNCION: defineError()
    OBJETIVO: DEFINE  ERROR EN LAS PROPIEDADESDE LA CLASE
    PARAMETROS: $a_error["error"],["errorMEnsaje"]
    ****************************************************************************************************/
    public function defineError($a_error){
         $this->error=$a_error["Error"];
         $this->errorMensaje=$a_error["ErrorMensaje"];
    }
    
    /****************************************************************************************************
    FUNCION: defineErrorConsulta()
    OBJETIVO: DEFINE ERROR POR AL EJECUTAR CONSULTA
    PARAMETROS: $a_resultSet RESULSET,$a_mensaje MENSAJE DEL ERROR
    ****************************************************************************************************/
	public function defineErrorConsulta($a_resultSet,$a_mensaje){
	    if(!$this->error)
	    {
			if($a_resultSet===FALSE || bd_error())
			{
				    $this->error=true;
				    $this->errorMensaje=$a_mensaje." ".bd_MensajeError()."***".$this->sql;
			}   
	    }
	    
		
	}
    
    /**
     * Función para regresar las materias extraescolares del alumno
     *
     * @method global_regresaMateriasExtraEscolaresAlumno
     * @param idInscripcion.- Id de la inscripción
     * @param matricula.- Matrícula del alumno
     */
    
    public function global_regresaMateriasExtraEscolaresAlumno($idInscripcion,$matricula)
    {
        $sql = "
        SELECT 
            am.id_alumnomateria as idAlumnoMateria,
            am.id_grupo as idGrupo,
            am.id_aspectoevaluacion as idAspecto,
            am.id_profesor as idProfesor,
            mat.id_materia as idMateria,
            mat.nombre_materia as nombreMateria,
            mat.nombrecorto_materia as nombreCortoMateria,
            i.id_inscripcion as idInscripcion
            ,i.matricula_alumno as matriculaAlumno
            ,CONCAT_WS(' ',prof.paterno_profesor, if(trim(prof.materno_profesor)='',null,prof.materno_profesor), prof.nombre_profesor) as nombreProfesor
        FROM
            inscripcion i
                INNER JOIN
            alumno_materia am ON am.id_inscripcion = i.id_inscripcion
                AND am.id_inscripcion = ".$idInscripcion."
                AND am.matricula_alumno = '".$matricula."'
                INNER JOIN
            extraescolares_materias exm ON exm.id_materia = am.id_materia
                AND exm.id_escuela = i.id_escuela
                AND exm.id_cicloescolar = i.id_cicloescolar
                AND exm.id_nivelestudios = i.id_nivelestudios
                AND exm.id_nivelescolar = i.id_nivelescolar
                INNER JOIN
            materia mat ON mat.id_materia = am.id_materia
                LEFT OUTER JOIN profesor prof ON prof.id_profesor = am.id_profesor
                
        ";
        
        return $sql;
    }
    
    /**
     * Función para regresar las materias del alumno del alumno
     *
     * @method global_regresaMateriasAlumno
     * @param idInscripcion.- Id de la inscripción
     * @param matricula.- Matrícula del alumno
     */
    
    public function global_regresaMateriasAlumno($idInscripcion,$matricula)
    {
        $sql = "
        SELECT 
            am.id_alumnomateria as idAlumnoMateria,
            am.id_grupo as idGrupo,
            am.id_aspectoevaluacion as idAspecto,
            am.id_profesor as idProfesor,
            mat.id_materia as idMateria,
            mat.nombre_materia as nombreMateria,
            mat.nombrecorto_materia as nombreCortoMateria,
            i.id_inscripcion as idInscripcion,
            i.matricula_alumno as matriculaAlumno,
            CONCAT_WS(' ',
                    prof.paterno_profesor,
                    if(trim(prof.materno_profesor) = '',
                        null,
                        prof.materno_profesor),
                    prof.nombre_profesor) as nombreProfesor,
            if((select 
                        exm.id_extraescolarmateria
                    from
                        extraescolares_materias exm
                    where
                        exm.id_materia = am.id_materia
                            AND exm.id_escuela = i.id_escuela
                            AND exm.id_cicloescolar = i.id_cicloescolar
                            AND exm.id_nivelestudios = i.id_nivelestudios
                            AND exm.id_nivelescolar = i.id_nivelescolar) is null,
                0,
                1) as esExtraEscolar
        FROM
            inscripcion i
                INNER JOIN
            alumno_materia am ON am.id_inscripcion = i.id_inscripcion
                AND am.id_inscripcion = ".$idInscripcion."
                AND am.matricula_alumno = '".$matricula."'
                INNER JOIN
            materia mat ON mat.id_materia = am.id_materia
                LEFT OUTER JOIN
            profesor prof ON prof.id_profesor = am.id_profesor
                
        ";
        
        
        return $sql;
    
    
    }
    
    /**
     * Función para regresar la calificación de la evaluación del SIE Móvil y de la pantalla de extraescolar alumnos
     *
     * @method global_regresaCalificacionEvaluacion
     * @param idMateria.- Id de la materia
     * @param idInscripcion.- Id de la inscripción
     * @param matricula.- Matrícula del alumno
     * @param idAspectoEvaluacion.- Id del aspecto a tomar la calificación del SIE Móvil
     * @param idAspectoEvaluacionAEvaluar.- Id del aspecto a tomar la calificación de captura de extraescolares
     * @param esExtraEscolar.- Determina si extraescolar o no
     * @param evaluacion.- Nombre de la evaluación a obtener la calificación
     */
    
    function global_regresaCalificacionEvaluacion($idInscripcion,$matricula,$idMateria,$idAspectoEvaluacion,$idAspectoEvaluacionAEvaluar,$esExtraEscolar,$evaluacion)
    {
        if($esExtraEscolar == 1)//Voy a captura de extraescolares
        {
            $sql = "
            SELECT 
                avg(eam.valor_extraescolardetalle) as valorDetalleCalificacion
            FROM
                extraescolares_alumno_detalle eam
                    inner join
                extraescolares_alumno ea ON ea.id_extraescolaralumno = eam.id_extraescolaralumno
                    and eam.estatus_extraescolardetalle = 'ACTIVO'
                    and eam.id_aspectoevaluacion = ".$idAspectoEvaluacionAEvaluar."
                    and eam.id_materia = '".$idMateria."'
                    and ea.estatus_extraescolaralumno = 'ACTIVO'
                    INNER JOIN
                inscripcion i ON i.matricula_alumno = eam.matricula_alumno
                    AND i.matricula_alumno = '".$matricula."'
                    AND i.id_inscripcion = ".$idInscripcion."
                    AND i.id_cicloescolar = eam.id_cicloescolar
                    
            ";
        }
        else//Voy al SIE Móvil
        {
            $sql = "
            SELECT 
                pde.valor_detalleevaluacion as valorDetalleCalificacion
            FROM
                periodo_materia_evaluacion pme
                    INNER JOIN
                periodo_evaluacion pe ON pe.id_periodoevaluacion = pme.id_periodoevaluacion
                    AND trim(pme.nombre_mateval) = '".$evaluacion."'
                    AND pme.estatus_mateval = 'ACTIVO'
                    AND pe.id_materia = '".$idMateria."'
                    AND pe.estatus_periodoevaluacion = 'ACTIVO'
                    AND pe.id_aspectoevaluacion = ".$idAspectoEvaluacion."
                    INNER JOIN
                periodo_detalle_evaluacion pde ON pde.id_mateval = pme.id_mateval
                    INNER JOIN
                inscripcion i ON i.matricula_alumno = pde.matricula_alumno
                    AND i.matricula_alumno = '".$matricula."'
                    AND i.id_inscripcion = ".$idInscripcion."
                    AND i.id_cicloescolar = pe.id_cicloescolar
                    AND i.id_escuela = pe.id_escuela
                    AND i.id_nivelestudios = pe.id_nivelestudios
            ";
            
        }
        return $sql;
    }
    
    
    function global_regresaUltimoCambioCalificacion($a_idCiclo,$a_idAspecto,$a_idMateria,$a_matricula)
    {
        $this->sql = "select cc.id_cambio as idCambio, cc.id_detallemateria as idDetalleMateria, cc.valornuevo_cambio as valorNuevoCalificacion,cc.motivo_cambio as motivoCambio
        from cambio_calificacion cc
        where cc.id_cicloescolar = ".$a_idCiclo."
        and cc.id_aspectoevaluacion = ".$a_idAspecto."
        and cc.id_materia = '".$a_idMateria."'
        and cc.matricula_alumno = '".$a_matricula."'
        and not find_in_set(cc.motivo_cambio,'eliminar_metodologia,eliminar_criterio_evaluacion,registrar_grupos_metodologia,eliminar_grupo_metodologia,eliminar_criterio,registrar_criterio_evaluacion')
        order by cc.id_cambio desc
        limit 1;";
        
        return $this->sql;
    }
    
    /**
     * Función para regresar las materias del plan de estudios
     *
     * @method global_regresaPlanDeEstudios
     * @param idInscripcion.- Id de la inscripción
     * @param matricula.- Matrícula del alumno
     * @param idPlanEstudios.- Id del plan de estudios
     */
    function global_regresaPlanMateriaAlumno($idPlanEstudios)
    {
        $sql = "
        select 
        pm.id_planmateria as idPlanMateria,
        pm.orden_planmateria as ordenPlanMateria,
        pm.id_nivelescolar as idNivelEscolar,
        pm.creditos_planmateria as creditosPlanMateria,
        pm.visible_planmateria as visiplePlanMateria,
        pm.promedio_planmateria as promedioPlanMateria,
        mat.id_materia as idMateria,
        mat.nombre_materia as nombreMateria,
        mat.nombrecorto_materia as nombreCortoMateria
    from
        plan_materia pm
            inner join
        materia mat ON mat.id_materia = pm.id_materia
    where
        pm.id_planestudios = ".$idPlanEstudios."
    order by pm.id_planmateria
        ";
        
        return $sql;
    }
    
    /**
     * Función para regresar los cargos del alumno en el ciclo
     *
     * @method global_regresaCargosAlumnoEnCiclo
     * @param matricula.-  Matrícula del alumno
     * @param idCiclo.-  Id ciclo del alumno
     * @param filtroExtra.- Agrega un filtro a la consulta del cargo
     */
    function global_regresaCargosAlumnoEnCiclo($matricula,$idCiclo,$filtroExtra = null)
    {
        $sql = "
        SELECT
            c.id_cargo as idCargo
            ,c.id_cuenta as idCuenta
            ,c.id_subcuenta as idSubcuenta
            ,c.id_concepto as idConcepto
            ,c.matricula_alumno as matricula
            ,c.cantidad_cargo as cantidadCargo
            ,c.descripcion_cargo as descripcionCargo
            ,c.fecha_cargo as fechaCargo
            ,c.fecharecargo_cargo as fechaVencimientoRecibo
            ,c.vencimiento_cargo as fechaVencimiento
            ,c.monto_cargo as montoCargo
            ,c.saldo_cargo as saldoCargo
            ,c.referencia1_cargo as referencia1
            ,c.referencia2_cargo as referencia2
            ,c.observaciones_cargo as observacionesCargo
        FROM
            cargos c
        WHERE
            c.id_cicloescolar = ".$idCiclo."
                AND c.matricula_alumno = '".$matricula."'
                AND c.fechacancelacion_cargo is null
            ".$filtroExtra."
        ";
        
        return $sql;
    }
    
    
    
    /**
     * Función para regresar la información de las becas
     *
     * @method global_regresaBecasAlumno
     * @param matricula.-  Matrícula del alumno
     * @param idCiclo.-  Id ciclo del alumno
     */
    function global_regresaBecasAlumno($matricula,$idCiclo)
    {
        $errorCiclo = "";
        
        $sqlCiclo = "SELECT ce.id_cicloescolar as idCiclo FROM ciclo_escolar ce where ce.id_cicloescolar = ".$idCiclo;
        $resCiclo = bd_consultaSQL($sqlCiclo);
        
        $filaCiclo = bd_dameRegistro($resCiclo);
        if(bd_cuentaRegistros($resCiclo)>1 || trim($filaCiclo['idCiclo'])=="")
        {
            $errorCiclo = "Error al obtener el ciclo.";
        }

        
        $sql = $errorCiclo."
        select 
            b.id_becario as idBecario,
            b.matricula_alumno as matricula,
            b.id_cicloescolar as idCiclo,
            b.porcentaje_beca as porcentajeBeca,
            b.monto_beca as montoBeca,
            b.id_escuela as idEscuela,
            tb.nombre_tipobeca as nombreBeca,
            tb.id_tipobeca as tipoBecaId
        from
            becarios b
                inner join
            tipo_beca tb ON tb.id_tipobeca = b.id_tipobeca
                and b.matricula_alumno = '".$matricula."'
                and b.id_cicloescolar = ".$idCiclo."
                and b.estatus_beca = 'ACTIVO'
        ";

        return $sql;
    }
    
    
    function global_dameCalificacionExenta($matricula,$idInscripcion,$idAspecto,$idCiclo,$idMateria,$calificacionActual,$letraActual,$valorExe)
    {
      $mensajeError = "";
      $arrRetorno = array("Error"=>false,"Mensaje"=>"","calificacion"=>$calificacionActual,"letra"=>$letraActual,"isExento"=>false,"matricula"=>$matricula);
      
      //BUSCO SI HAY CALIFICACIÓN EN EL ASPECTO PARA LA MATRICULA
      
      $sql = "select distinct amd.id_detallemateria,amd.valor_detallemateria as calificacion, amd.valorletra_detallemateria as letra 
      from alumno_materia_detalle amd
      inner join alumno_materia am on am.id_alumnomateria = amd.id_alumnomateria
      and am.id_inscripcion = ".$idInscripcion."
      and am.id_materia = '".$idMateria."'
      and amd.id_cicloescolar = ".$idCiclo."
      and amd.id_aspectoevaluacion = ".$idAspecto."
      and amd.valorletra_detallemateria = '".$valorExe."'
      "
      ;
      
      $res = bd_consultaSQL($sql);
      
      if(bd_error())
      {
        $mensajeError.="Error en consulta: \n".$sql."\n".bd_mensajeError()."\n";
      }
      else if(bd_cuentaRegistros($res)==1)//Se encontró calificación, le asigno las califiaciones
      {
        $fila = bd_dameRegistro($res);
        
        $calificacion = $fila['calificacion'];
        $letra = $fila['letra'];
        
        if(!is_numeric($calificacion))
        {
          $calificacion = "null";
        }
        
        $arrRetorno['calificacion'] = $calificacion;
        $arrRetorno['letra'] = "'".$letra."'";
        $arrRetorno['isExento'] = true;
      }
      
      if($mensajeError!="")
      {
        $arrRetorno['Error'] = true;
        $arrRetorno['Mensaje'] = "Error: ".$mensajeError;
      }
      
      return $arrRetorno;
    }
    
    /**
     * Función para regresar el arreglo en base a las coincidencias del key
     * 
     * @method global_regresaArregloEnBaseAKey
     * @param $arregloGenerico.- Arreglo 
     * @param $keyTarget.- Llave a buscar
     * @param $valorTarget.- Valor a coincidir
     */
    
    function global_regresaArregloEnBaseAKey($arregloGenerico,$keyTarget,$valorTarget)
    {
        $arreglo = array();
        //20150929 luism se inicializa el elemento para concatenar valor
        $bit="";
        for($i = 0;$i < sizeof($arregloGenerico); $i++)
        {
            foreach($arregloGenerico[$i] as $key => $value)
            {
                $bit.=$key."|";
                
                if($key == $keyTarget)
                {
                    if($valorTarget == $value)
                    {
                        array_push($arreglo,$arregloGenerico[$i]);
                    }
                }
            }
        }    
        
        return $arreglo;
    }
    
    
    
    /**
    * Función para regresar el separador
    * 
    * @method global_regresaSeparador
    * @param identificador .- Identficador del separador
    */
    
    function global_regresaSeparador($identificador)
    {
        
        $separador = "|sie2015|";
        
        switch($identificador)
        {
            case 1:
                $separador = "|sie2015|";
            break;
        
            case 2:
                $separador = "|987a|";
            break;
        
            case 3:
                $separador = "|a987|";
            break;
            
            case 4:
                $separador = "|";
            break;
        
            case 5:
                $separador = "|458a|";
            break;
        
            case 6:
                $separador = "|sie2015a|";
            break;
        
            case 7:
                $separador = ":";
            break;
        }
        
        return $separador;
        
    }
    
    
    /**
     * Función para regresar la carpeta raíz desde donde se está ejecutando el script
     *
     * @method global_dameCarpetaEjecucionScript
     */
    private function global_dameCarpetaEjecucionScript()
    {
        //OBTENGO LAS RUTAS DE LOS SERVIDORES
        $servidor = $_SERVER['DOCUMENT_ROOT'];
        $servidorArray = explode("\\",$servidor);
        $carpetaScript = array_pop($servidorArray);//Guardo el último elemento
        
        return $carpetaScript;
        
    }
    
    
    /**
    * Función para procesar los parámetros null para las consultas mysql
    * 
    * @method global_procesaParametroNullSQL
    * @param param .- Parámetro a procesar
    */
    
    function global_procesaParametroNullSQL($param,$esString)
    {
        
        if(trim($param) == "")
        {
            $param = "null";
        }
        else
        {
            if($esString)
            {
                $param = "'".$param."'";
            }
        }
        
        return $param;
    }
    
    
    /**
    * Función para encontrar un arreglo en base al key
    * 
    * @method global_encuentraKeyCoincidente
    * @param $arrayBusqueda .- Array Multidimensional que contiene la búsqueda
    * @param $arrayValores .- Array que contiene los valores que se quieren buscar
    */
    
    function global_encuentraKeyCoincidente($arrayBusqueda,$arrayValores)
    {
        $idsEnvioArray = array();
        
        foreach($arrayBusqueda as $key =>$idBusquedaArray)
        {
            $encontrado = true;
                
            foreach($arrayValores as $idValor)
            {
                if(!in_array($idValor,$idBusquedaArray))
                {
                    $encontrado = false;
                    break;
                }
            }
            
            if($encontrado)
            {
                array_push($idsEnvioArray,$key);
            }
        }
        
        if(sizeof($idsEnvioArray)>0)
        {
            $idsEnvioArray = array_unique($idsEnvioArray);
        }
        
        return $idsEnvioArray;
    }
    
    /**
    * Función para procesar una fecha y convertirla en formato 2015-12-25
    * 
    * @method global_formateaFechaMySql
    * @param $fecha .- Fecha a procesar
    * 
    */
    
    function global_formateaFechaMySql($fecha)
    {

        if(trim($fecha)=='')
        {
            return $fecha;
        }
        
        
        $fechaArray = explode("/",$fecha);
        
        $fecha = $fechaArray[2]."-".$fechaArray[1]."-".$fechaArray[0];
        
        return $fecha;
    }
    
    
    /**
    * Función para procesar una fecha y convertirla en formato establecido
    * 
    * @method global_formateaFechaGeneralDesdeMYSQL
    * @param $a_fecha .- Fecha a procesar
    * @param $a_separador .- Separador de la nueva fecha
    * 
    */
    
    function global_formateaFechaGeneralDesdeMySql($a_fecha,$a_separador = '-')
    {
        
        if(trim($a_fecha)=='')
        {
            return $a_fecha;
        }
        
        $fechaArray = explode("-",$a_fecha);
        
        $a_fecha = $fechaArray[2].$a_separador.$fechaArray[1].$a_separador.$fechaArray[0];
        
        return $a_fecha;
    }
    
    /**
    * Función para procesar una hora a formato 12
    * 
    * @method global_formatea12Hora
    * @param $a_hora .- Hora a procesar
    * 
    */
    
    function global_formatea12Hora($a_hora)
    {
        $a_hora = date("h:i:s a", strtotime($a_hora));
        
        return $a_hora;
    }
    
    
    /**
    * Función para validar si la fecha es menor o igual a la de hoy
    * 
    * @method globa_validaFechaContraActual
    * @param $a_fecha .- Fecha a procesar 'YYYY-mm-dd'
    * 
    */
    
    function globa_validaFechaContraActual($a_fecha)
    {
        
        if(trim($a_fecha)=="")
        {
            return false;
        }
        
        $hoy = date("Y-m-d");
        if($a_fecha<=$hoy)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    /**
    * Función para validar si la fecha es menor o igual a la de hoy
    * 
    * @method globa_validaFechaContraActual
    * @param $a_fechaInicio .- Fecha a procesar 'YYYY-mm-dd'
    * @param $a_fechaFin .- Fecha a procesar 'YYYY-mm-dd'
    * 
    */
    function globa_validaFechaInicioContraFin($a_fechaInicio,$a_fechaFin)
    {
        
        if(trim($a_fechaInicio)=="" || trim($a_fechaFin)=="")
        {
            return false;
        }
        

        if($a_fechaInicio<=$a_fechaFin)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
    * Función para regresar los uuid móviles de desarrollo.
    * 
    * @method globa_regresaUUIDMovilesDesarrollo
    * 
    * 
    */
    
    function globa_regresaUUIDMovilesDesarrollo()
    {
        $arrUUID = array("575DFFA4-B3A0-4333-BD1B-71EF5F9F4BAD"
                        ,"B6D11C51-77B2-48D0-902C-E1A5C2941380"
                        ,"CF1FE5BB-BBE8-4610-A791-676816F1840B"
                        ,"119408F7-1F6E-4ACB-AC92-2EFED5E39DFE"
                        ,"BB410E58-4846-4EB8-A0A4-2D09E9BC4973"
                        ,"76AA48F1-3B92-4BFC-BA4F-C0307860EBB6"
                        ,"652C323B-C2B2-47A8-ABC2-A742A808DCAE"
                        ,"0EC1B664-ABBD-4AFB-977F-5B186D7F6235"
                        ,"4959994E-EDAE-44F8-9E17-EFC30F89E6B6"
                        ,"51579F53-9D50-4A30-9C7E-2C3001BE9786"
                        ,"8EB6FE5A-8EB1-4094-84CC-EDA33145B98D"
                        ,"F1C6E77A-F490-4A50-8905-3F1D168E6983"
                        ,"1928F763-DE8D-4251-8765-27B7E8340FDB"
                        ,"C698E7D6-CE84-45AE-AA97-CABA50523737"
                        ,"3FC91D2D-26AC-449D-A9CA-10E3C4B1CB47"
                        
                        ,"CDD69A97-C98F-4A92-9864-9B2B81C38947"
                        
                        //ipad 2, nueva version de la app
                        ,"4354ECBB-5AC3-43E0-9B57-618111589940"
                        
                        //ipad de Vero CFC, incluido por solitud de Miriam el 30092016
                        //,"8A5755AB-1983-4ED3-92D1-02F617EEF991"
                        
                        );
        
        return $arrUUID;
    }
    
    /**
    * Función para regresar los uuid móviles de desarrollo.
    * 
    * @method global_regresaMensajeErrorBD
    * 
    * 
    */
    
    function global_regresaMensajeErrorBD($sql,$imprimeConsulta = false,$clg)
    {
        if($imprimeConsulta)
        {
            $retorno = "Error en consulta: ".$sql." --> ".bd_mensajeError();
        }
        else
        {
            $retorno = "Error en consulta: ".bd_mensajeError();
        }
        
        
        return $retorno;
    }
    
    /**
    * Función para encriptar
    * 
    * @method global_encripta
    * 
    * 
    */
    
    function global_encripta($pure_string, $encryption_key)
    {
        /*
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        //luism:Emilio 20170608(27133) No codificar la cadena a encriptar utf8_encode, los modulos tienen su manejo de string independiente
        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, ($pure_string), MCRYPT_MODE_ECB, $iv);
        $r=base64_encode($encrypted_string);
        return $r;
        */
        $cipher = Cypher::encryptString($pure_string, $encryption_key);
        return $cipher;

    }
    
    
    /**
    * Función para desencriptar
    * 
    * @method global_desencripta
    * 
    * 
    */
    
    function global_desencripta($encrypted_string, $encryption_key)
    {
        /*
        $encrypted_string = base64_decode($encrypted_string);
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
        return $decrypted_string;
        */
        $plainText = Cypher::decryptString($encrypted_string, $encryption_key);
        return $plainText;

    }
    
    
    /**
    * Función para regresar los uuid móviles de desarrollo.
    * 
    * @method global_imprimeArregloFormateado
    * 
    * 
    */
    
    function global_imprimeArregloFormateado($a_arreglo)
    {
        echo '<pre>';
            print_r($a_arreglo);
        echo '</pre>';
    }

    /****************************************************************************************************
     FUNCION: global_arrayToXML()
     OBJETIVO: RETORNA ARREGLO ERROR
     PARAMETROS: a_array=arreglo a convertir a xml, a_xml referencia de objeto: no necesita return por ser referencia
     $_xml= new SimpleXMLElement("<?xml version=\"1.0\"?><DATOS></DATOS>");//REFERENCIA DEL OBJETO
     global_arrayToXML($_array,$_xml);
     ****************************************************************************************************/
    public function global_arrayToXML($a_array, &$a_xml,$a_file="",$a_attribute="") {

    
        $isDebug = $this->getDebug();
    
        if(!isset($a_attribute) || (!is_array($a_attribute) && !isset($a_attribute{0}))){
            $a_attribute=array();
        }
        if(!isset($a_file) || !isset($a_file{0})){
            $a_file="";
        }
            foreach($a_array as $key => $value) {
                if(is_array($value)) {
                    if(!is_numeric($key)){
                        $node = $a_xml->addChild($key);
                        if(isset($a_attribute) && sizeof($a_attribute)>0){
                            foreach ($a_attribute as $keyAttribute => $valueAttribute) {
                                $node->addAttribute($keyAttribute, $valueAttribute);
                            }
                        }
                        $this->global_arrayToXML($value, $node,"",$a_attribute);
                    }else{
                        //
                        if($this->isDebug){
                            //PARA DEBUGEAR LOS KEYS DE LOS ARREGLOS                            
                            $node = $a_xml->addChild("item$key");
                        }else{
                            $node = $a_xml->addChild($this->xmlNodoName);
                        }
                        $this->global_arrayToXML($value, $node,"",$a_attribute);
                    }
                }else {
                    if(!is_numeric($key)){
                        if($this->isDebug){
                             if(!isset($value{1})){
                                $value="";
                            } 
                        } 
                        if(method_exists($a_xml,"addChild")){                           
                            $node=$a_xml->addChild($key,htmlspecialchars(utf8_encode($value)));
                        }
                            if(isset($a_attribute) && sizeof($a_attribute)>0){                                
                                foreach ($a_attribute as $keyAttribute => $valueAttribute) {
                                    $node->addAttribute($keyAttribute, $valueAttribute);
                                }
                            }
                        }else{                           
                            $node=$a_xml->addChild($this->xmlNodoName,htmlspecialchars($value))->addAttribute('indiceArray', $key);
                        }
                }
            }
            if(isset($a_file{0})){
                $a_xml->asXML($a_file);
            }
         //return $a_xml;//rotornamos aun asi la referencia del OBJETO
        //return $a_xml->asXML("../../../archivosB/archivo.xml");
        }
     /****************************************************************************************************
     FUNCION: global_xmlToArray()
     OBJETIVO: RETORNA ARRAY DE XML ENVIADO
     PARAMETROS: a_xml = retorno de vista de SIE
     ****************************************************************************************************/
    public function global_xmlToArray($a_xml){
        $xml = simplexml_load_string($a_xml);
        $arrayResultado = unserialize(serialize(json_decode(json_encode((array) $xml,JSON_FORCE_OBJECT), 1)));        
       return  $arrayResultado;
    }
    
    
    public function global_strtoupper($valor)
    {
        return mb_strtoupper($valor,'utf-8');
    }
    /*
    public function dame_xmlGeneralesAlumnoAspirante($matricula,$idAspirante)
    {
        $clg = $this->dameColegio();
        
        $mensajeError = "";
        
        $datos = '<?xml version="1.0" encoding="utf-8"?>
				  <datos>';
                                  
        
        $tipoAlumno = "";
        if(trim($matricula)!="")
        {
            $tipoAlumno = 'alumno';
            $sql = "SELECT a.matricula_alumno as matricula, descripcion_nivelestudios as nivel, nombre_nivelescolar as grado,
				a.nombre_alumno as nombre, a.paterno_alumno as paterno, a.materno_alumno as materno, nombre_escuela as escuela,
				a.actual_nivelestudios as idNivel, a.id_escuela as idEscuela, nombre_grupo as grupo, numlista_inscripcion as nlista,
				i.id_cicloescolar as ciclo, i.id_nivelescolar as idGrado, g.id_grupo as idGrupo,ifnull(i.id_cicloescolar,'ALUMNO NO INSCRITO EN EL CICLO ACTUAL') as textoCiclo
			FROM alumno a
				INNER JOIN nivel_estudios ne ON ne.id_nivelestudios = a.actual_nivelestudios
					AND a.matricula_alumno = '".$matricula."'
				INNER JOIN nivel_escolar nes ON nes.id_nivelescolar = a.actual_nivelescolar
				INNER JOIN escuela e ON a.id_escuela = e.id_escuela
				LEFT OUTER JOIN inscripcion i ON i.matricula_alumno = a.matricula_alumno
                                    AND i.id_cicloescolar = dame_cicloActualAlumno('".$matricula."')
                                    AND i.tipo_inscripcion not in ('BAJA','ELIMINADO')
                                    AND i.fechabaja_inscripcion is null
				LEFT OUTER JOIN grupo g ON g.id_grupo = i.base_grupo
			ORDER BY fecha_inscripcion DESC
			";
            
            if(in_array($clg,$this->arrUniversidades))
            {
                $sql = "SELECT a.matricula_alumno as matricula, descripcion_nivelestudios as nivel, nombre_nivelescolar as grado,
                            a.nombre_alumno as nombre, a.paterno_alumno as paterno, a.materno_alumno as materno, nombre_escuela as escuela,
                            a.actual_nivelestudios as idNivel, a.id_escuela as idEscuela, nombre_grupo as grupo, numlista_inscripcion as nlista,
                            i.id_cicloescolar as ciclo, i.id_nivelescolar as idGrado, g.id_grupo as idGrupo,ifnull(i.id_cicloescolar,'ALUMNO NO INSCRITO EN EL CICLO ACTUAL') as textoCiclo
                        FROM alumno a
                            INNER JOIN nivel_estudios ne ON ne.id_nivelestudios = a.actual_nivelestudios
                                AND a.matricula_alumno = '".$matricula."'
                            INNER JOIN nivel_escolar nes ON nes.id_nivelescolar = a.actual_nivelescolar
                            INNER JOIN escuela e ON a.id_escuela = e.id_escuela
                            LEFT OUTER JOIN inscripcion i ON i.matricula_alumno = a.matricula_alumno
                                                AND i.id_cicloescolar = dame_cicloActualAlumno('".$matricula."','NORMAL')
                                                AND i.tipo_inscripcion not in ('BAJA','ELIMINADO')
                                                AND i.fechabaja_inscripcion is null
                            LEFT OUTER JOIN grupo g ON g.id_grupo = i.base_grupo
                        ORDER BY fecha_inscripcion DESC
                        ";
            }
            
        }
        else if($idAspirante>0)
        {
            $tipoAlumno = 'aspirante';
            $sql = "SELECT 
		a.id_aspirante as idAspirante,
		a.nombre_aspirante as nombre,
		a.paterno_aspirante as paterno,
		a.materno_aspirante as materno,
		e.id_escuela as idEscuela,
		e.nombre_escuela as escuela,
		ne.id_nivelestudios as idNivel,
		ne.descripcion_nivelestudios as nivel,
		gr.id_nivelescolar as idGrado,
		gr.nombre_nivelescolar as grado,
		ce.id_cicloescolar as ciclo
		FROM aspirante a
		inner join escuela e on e.id_escuela = a.id_escuela
		and a.id_aspirante = ".$idAspirante."
		inner join nivel_estudios ne on ne.id_nivelestudios = a.id_nivelestudios
		inner join nivel_escolar gr on gr.id_nivelescolar = a.id_nivelescolar
		inner join ciclo_escolar ce on ce.id_cicloescolar = a.id_cicloescolar";
        }
        
	$res = bd_consultaSQL($sql);
	$contadorRegistros = bd_cuentaRegistros($res);
        
        if(bd_error())
        {                    
            $mensajeError.= 'Error consulta: '.bd_mensajeError();
        }
	else if($contadorRegistros==1)
	{
		$fila = bd_dameRegistro($res);
                
                $nombreAlumno = trim($fila['paterno'].' '.$fila['materno'].' '.$fila['nombre']);
                $nombreTitular = trim($fila['nombre_profesor']. ' '. $fila['paterno_profesor'] . ' ' . $fila['materno_profesor']);
                
                //BUSCO LA BEC
                $porcenajeBeca = "";
                if($tipoAlumno=="alumno")
                {
                    $sqlBeca = "Call sp_finanzas_becaAlumnoCiclo(dame_cicloActualAlumno('".$matricula."'),'".$matricula."')";
                    if(in_array($clg,$this->arrUniversidades))
                    {
                        $sqlBeca = "Call sp_finanzas_becaAlumnoCiclo(dame_cicloActualAlumno('".$matricula."','NORMAL'),'".$matricula."')";
                    }
                    $resBeca = bd_consultaSQL($sqlBeca);
                    
                    if(bd_error())
                    {
                        $mensajeError.="Error al obtener la información de la beca: ".bd_mensajeError();
                    }
                    else if(bd_cuentaRegistros($resBeca)>0)
                    {
                        while($filaBeca = bd_dameRegistro($resBeca))
                        {
                            $porcenajeBeca+=$filaBeca['porcentaje'];
                        }
                        $porcenajeBeca = $porcenajeBeca."%";
                    }
                    
                }
                
                //OBTENGO EL COLEGIO
                $clg = $this->dameColegio();
                
                if($mensajeError=="")
                {
                    $datos .= '
                            <elemento>
                                    <tipoAlumno>'.$tipoAlumno.'</tipoAlumno>
                                    <idAspirante>'.$fila['idAspirante'].'</idAspirante>
                                    <matricula>'.$fila['matricula'].'</matricula>
                                    <escuela>'.$fila['escuela'].'</escuela>
                                    <nivel>'.$fila['nivel'].'</nivel>
                                    <grado>'.$fila['grado'].'</grado>
                                    <nombre>'.$nombreAlumno.'</nombre>
                                    <idNivel>'.$fila['idNivel'].'</idNivel>
                                    <idEscuela>'.$fila['idEscuela'].'</idEscuela>
                                    <grupo>'.$fila['grupo'].'</grupo>
                                    <nlista>'.$fila['nlista'].'</nlista>
                                    <titular>'.$nombreTitular.'</titular>
                                    <ciclo>'.$fila['ciclo'].'</ciclo>
                                    <obsCiclo>'.$fila['textoCiclo'].'</obsCiclo>
                                    <idGrado>'.$fila['idGrado'].'</idGrado>
                                    <idTitular>'.$fila['idTitular'].'</idTitular>
                                    <beca>'. $porcenajeBeca .'</beca>
                                    <idGrupo>'.$fila['idGrupo'].'</idGrupo>
                                    <clg>'.$clg.'</clg>
                                    <error>no</error>
                            </elemento>';
                }
			
	}
	else
	{
	    $mensajeError .= "No fue posible encontrar la información del ".$tipoAlumno.". --> ".$contadorRegistros;	
	}
        
        
        
        if($mensajeError!="")
        {
            $datos .= '
                        <elemento>
                            <error>si</error>
                            <descripcion>'.$mensajeError.'</descripcion>
                        </elemento>';
        }

        $datos.=' </datos>';
	
	return utf8_encode($datos);
    }
    */
    public function dame_xmlGeneralesAlumnoAspirante($matricula,$idAspirante,$isModuloAlumnos = false)
    {
        $clg = $this->dameColegio();
        
        $mensajeError = "";
        
        $datos = '<?xml version="1.0" encoding="utf-8"?>
				  <datos>';
                                  
        
        $adicionalDatos = "";
        
        if($isModuloAlumnos)
        {
            $adicionalDatos = ",a.fechanacimiento_alumno as nacimiento, a.curp_alumno as curp, a.lugarnacimiento_alumno as lugar, a.calleynum_alumno as calle,
                                a.colonia_alumno as colonia, a.codigopostal_alumno as cp, a.ciudad_alumno as ciudad,
                                a.estado_alumno as estado, a.telcasa_alumno as telcasa, a.telemergencia_alumno as telemergencia,
                                a.padrecel_alumno as celpapa, a.madrecel_alumno as celmama, a.matriculasep_alumno as matsep,
                                a.padre_alumno, a.madre_alumno, a.estatus_alumno, a.padreemail_alumno as email_padre, a.madreemail_alumno as email_madre,
                                ((YEAR(CURDATE())-YEAR(a.fechanacimiento_alumno))  - (RIGHT(CURDATE(),5)<RIGHT(LEFT(a.fechanacimiento_alumno,10),5))) as edad,
                                a.padreteloficina_alumno as padreofi, madreteloficina_alumno as madreofi";       
        
        }
        
        $tipoAlumno = "";
        if(trim($matricula)!="")
        {
            $tipoAlumno = 'alumno';
            $sql = "SELECT a.matricula_alumno as matricula,a.tiposangre_alumno  as tipoSangre, descripcion_nivelestudios as nivel, nombre_nivelescolar as grado,
				a.nombre_alumno as nombre, a.paterno_alumno as paterno, a.materno_alumno as materno, nombre_escuela as escuela,
				a.actual_nivelestudios as idNivel, a.id_escuela as idEscuela, nombre_grupo as grupo, numlista_inscripcion as nlista,
				i.id_cicloescolar as ciclo, i.id_nivelescolar as idGrado, g.id_grupo as idGrupo,ifnull(i.id_cicloescolar,'ALUMNO NO INSCRITO EN EL CICLO ACTUAL') as textoCiclo,
                a.foto_alumno ".$adicionalDatos."
			FROM alumno a
				INNER JOIN nivel_estudios ne ON ne.id_nivelestudios = a.actual_nivelestudios
					AND a.matricula_alumno = '".$matricula."'
				INNER JOIN nivel_escolar nes ON nes.id_nivelescolar = a.actual_nivelescolar
				INNER JOIN escuela e ON a.id_escuela = e.id_escuela
				LEFT OUTER JOIN inscripcion i ON i.matricula_alumno = a.matricula_alumno
                                    AND i.id_cicloescolar = dame_cicloActualAlumno('".$matricula."')
                                    AND i.tipo_inscripcion not in ('BAJA','ELIMINADO')
                                    AND i.fechabaja_inscripcion is null
				LEFT OUTER JOIN grupo g ON g.id_grupo = i.base_grupo
			ORDER BY fecha_inscripcion DESC
			";
            
            if(in_array($clg,$this->arrUniversidades))
            {
                $sql = "SELECT a.matricula_alumno as matricula,a.tiposangre_alumno  as tipoSangre, descripcion_nivelestudios as nivel, nombre_nivelescolar as grado,
                            a.nombre_alumno as nombre, a.paterno_alumno as paterno, a.materno_alumno as materno, nombre_escuela as escuela,
                            a.actual_nivelestudios as idNivel, a.id_escuela as idEscuela, nombre_grupo as grupo, numlista_inscripcion as nlista,
                            i.id_cicloescolar as ciclo, i.id_nivelescolar as idGrado, g.id_grupo as idGrupo,ifnull(i.id_cicloescolar,'ALUMNO NO INSCRITO EN EL CICLO ACTUAL') as textoCiclo".$adicionalDatos."
                        FROM alumno a
                            INNER JOIN nivel_estudios ne ON ne.id_nivelestudios = a.actual_nivelestudios
                                AND a.matricula_alumno = '".$matricula."'
                            INNER JOIN nivel_escolar nes ON nes.id_nivelescolar = a.actual_nivelescolar
                            INNER JOIN escuela e ON a.id_escuela = e.id_escuela
                            LEFT OUTER JOIN inscripcion i ON i.matricula_alumno = a.matricula_alumno
                                                AND i.id_cicloescolar = dame_cicloActualAlumno('".$matricula."','NORMAL')
                                                AND i.tipo_inscripcion not in ('BAJA','ELIMINADO')
                                                AND i.fechabaja_inscripcion is null
                            LEFT OUTER JOIN grupo g ON g.id_grupo = i.base_grupo
                        ORDER BY fecha_inscripcion DESC
                        ";
            }
            
        }
        else if($idAspirante>0)
        {
            $tipoAlumno = 'aspirante';
            $sql = "SELECT 
		a.id_aspirante as idAspirante,
		a.nombre_aspirante as nombre,
		a.paterno_aspirante as paterno,
		a.materno_aspirante as materno,
		e.id_escuela as idEscuela,
		e.nombre_escuela as escuela,
		ne.id_nivelestudios as idNivel,
		ne.descripcion_nivelestudios as nivel,
		gr.id_nivelescolar as idGrado,
		gr.nombre_nivelescolar as grado,
		ce.id_cicloescolar as ciclo,
        a.tiposangre_aspirante as tipoSangre
		FROM aspirante a
		inner join escuela e on e.id_escuela = a.id_escuela
		and a.id_aspirante = ".$idAspirante."
		inner join nivel_estudios ne on ne.id_nivelestudios = a.id_nivelestudios
		inner join nivel_escolar gr on gr.id_nivelescolar = a.id_nivelescolar
		inner join ciclo_escolar ce on ce.id_cicloescolar = a.id_cicloescolar";
        }
        
        //luism 20171105 (25907) Genere error en la respuesta
        //echo $sql;
	$res = bd_consultaSQL($sql);
	$contadorRegistros = bd_cuentaRegistros($res);
        
        if(bd_error())
        {                    
            $mensajeError.= 'Error consulta: '.bd_mensajeError();
        }
	else if($contadorRegistros==1)
	{
		$fila = bd_dameRegistro($res);
                
                $nombreAlumno = trim($fila['paterno'].' '.$fila['materno'].' '.$fila['nombre']);
                $nombreTitular = trim($fila['nombre_profesor']. ' '. $fila['paterno_profesor'] . ' ' . $fila['materno_profesor']);
                
                //BUSCO LA BEC
                $porcenajeBeca = "";
                if($tipoAlumno=="alumno")
                {
                    $sqlBeca = "Call sp_finanzas_becaAlumnoCiclo(dame_cicloActualAlumno('".$matricula."'),'".$matricula."')";
                    if(in_array($clg,$this->arrUniversidades))
                    {
                        $sqlBeca = "Call sp_finanzas_becaAlumnoCiclo(dame_cicloActualAlumno('".$matricula."','NORMAL'),'".$matricula."')";
                    }
                    $resBeca = bd_consultaSQL($sqlBeca);
                    
                    if(bd_error())
                    {
                        $mensajeError.="Error al obtener la información de la beca: ".bd_mensajeError();
                    }
                    else if(bd_cuentaRegistros($resBeca)>0)
                    {
                        while($filaBeca = bd_dameRegistro($resBeca))
                        {
                            $porcenajeBeca+=$filaBeca['porcentaje'];
                        }
                        $porcenajeBeca = $porcenajeBeca."%";
                    }
                    
                }
                
                //OBTENGO EL COLEGIO
                $clg = $this->dameColegio();
                
                if($mensajeError=="")
                {
                    $datosAdicional = "";
                    
                    if($isModuloAlumnos)
                    {
                        $datosAdicional = '<fechaNac>'.$fila['nacimiento'].'</fechaNac>
                                           <curp>'.$fila['curp'].'</curp>
                                           <lugarNac>'.$fila['lugar'].'</lugarNac>
                                           <direccion>'.$fila['calle'].'</direccion>
                                           <colonia>'.$fila['colonia'].'</colonia>
                                           <cp>'.$fila['cp'].'</cp>
                                           <ciudad>'.$fila['ciudad'].'</ciudad>
                                           <estado>'.$fila['estado'].'</estado>
                                           <telCasa>'.$fila['telcasa'].'</telCasa>
                                           <telEmer>'.$fila['telemergencia'].'</telEmer>
                                           <papa>'.$fila['padre_alumno'].'</papa>
                                           <emailPapa>'.$fila['email_padre'].'</emailPapa>
                                           <celPapa>'.$fila['celpapa'].'</celPapa>
                                           <mama>'.$fila['madre_alumno'].'</mama>
                                           <emailMama>'.$fila['email_madre'].'</emailMama>
                                           <celMama>'.$fila['celmama'].'</celMama>
                                           <matSep>'.$fila['matsep'].'</matSep>
                                           <estatus>'.$fila['estatus_alumno'].'</estatus>                                           
                                           <edad>'.$fila['edad'].'</edad>                                          
                                           <madreofi>'.$fila['madreofi'].'</madreofi>                                          
                                           <padreofi>'.$fila['padreofi'].'</padreofi>
                                           ';
                    }
                    
                    
                    $datos .= '
                            <elemento>
                                    <tipoAlumno>'.$tipoAlumno.'</tipoAlumno>
                                    <idAspirante>'.$fila['idAspirante'].'</idAspirante>
                                    <matricula>'.$fila['matricula'].'</matricula>
                                    <escuela>'.$fila['escuela'].'</escuela>
                                    <nivel>'.$fila['nivel'].'</nivel>
                                    <grado>'.$fila['grado'].'</grado>
                                    <nombre>'.$nombreAlumno.'</nombre>
                                    <idNivel>'.$fila['idNivel'].'</idNivel>
                                    <idEscuela>'.$fila['idEscuela'].'</idEscuela>
                                    <grupo>'.$fila['grupo'].'</grupo>
                                    <nlista>'.$fila['nlista'].'</nlista>
                                    <titular>'.$nombreTitular.'</titular>
                                    <ciclo>'.$fila['ciclo'].'</ciclo>
                                    <obsCiclo>'.$fila['textoCiclo'].'</obsCiclo>
                                    <idGrado>'.$fila['idGrado'].'</idGrado>
                                    <idTitular>'.$fila['idTitular'].'</idTitular>
                                    <beca>'. $porcenajeBeca .'</beca>
                                    <idGrupo>'.$fila['idGrupo'].'</idGrupo>
                                    <clg>'.$clg.'</clg>
                                    <fotoAlumno>'.$fila['foto_alumno'].'</fotoAlumno>
                                    <tipoSangre>'.$fila['tipoSangre'].'</tipoSangre>
                                    '.$datosAdicional.'
                                    <error>no</error>
                            </elemento>';
                }
			
	}
	else
	{
	    $mensajeError .= utf8_decode("No fue posible encontrar la información del ".$tipoAlumno.". --> ").$contadorRegistros;	
	}
        
        
        
        if($mensajeError!="")
        {
            $datos .= '
                        <elemento>
                            <error>si</error>
                            <descripcion>'.$mensajeError.'</descripcion>
                        </elemento>';
        }

        $datos.=' </datos>';
	
	return utf8_encode($datos);
    }
    
    public function dame_xmlGeneralesAlumnoAspirante_trabajandoGASV($matricula,$idAspirante,$isModuloAlumnos = false)
    {
        $clg = $this->dameColegio();
        
        if(in_array($clg,$this->arrUniversidades))
            $isAlumnoUniversidad = 1;
        else
            $isAlumnoUniversidad = 0;
        
        $mensajeError = "";
        
        $datos = '<?xml version="1.0" encoding="utf-8"?>
				  <datos>';
        
        if($isModuloAlumnos)
            $adicionalAlumnos = 1;
        else
            $adicionalAlumnos = 0;
        

        $sql = "CALL sp_general_dameGeneralesAlumnoAspirante(".$this->global_procesaParametroNullSQL($matricula,true).",".$this->global_procesaParametroNullSQL($idAspirante,false).",".$isAlumnoUniversidad.",".$adicionalAlumnos.")";  
        // GASV 20161010: SE REEMPLAZÓ LA CONSULTA POR SP.
        
        //echo $sql;
        
        $res = bd_consultaSQL($sql);
        $contadorRegistros = bd_cuentaRegistros($res);
            
        if(bd_error())
        {                    
            $mensajeError.= 'Error consulta: '.bd_mensajeError();
        }
        else if($contadorRegistros==1)
        {
            $fila = bd_dameRegistro($res);
                    
                    $nombreAlumno = trim($fila['paterno'].' '.$fila['materno'].' '.$fila['nombre']);
                    $nombreTitular = trim($fila['nombre_profesor']. ' '. $fila['paterno_profesor'] . ' ' . $fila['materno_profesor']);
                    
                    //BUSCO LA BEC
                    $porcenajeBeca = "";
                    if($matricula != "")
                    {
                        $tipoAlumno = "alumno";
                        $sqlBeca = "Call sp_finanzas_becaAlumnoCiclo(dame_cicloActualAlumno('".$matricula."'),'".$matricula."')";
                        if(in_array($clg,$this->arrUniversidades))
                        {
                            $sqlBeca = "Call sp_finanzas_becaAlumnoCiclo(dame_cicloActualAlumno('".$matricula."','NORMAL'),'".$matricula."')";
                        }
                        $resBeca = bd_consultaSQL($sqlBeca);
                        
                        if(bd_error())
                        {
                            $mensajeError.="Error al obtener la información de la beca: ".bd_mensajeError();
                        }
                        else if(bd_cuentaRegistros($resBeca)>0)
                        {
                            while($filaBeca = bd_dameRegistro($resBeca))
                            {
                                $porcenajeBeca+=$filaBeca['porcentaje'];
                            }
                            $porcenajeBeca = $porcenajeBeca."%";
                        }
                        
                    }
                    else
                    {
                        $tipoAlumno = 'aspirante';
                    }
                    
                    //OBTENGO EL COLEGIO
                    $clg = $this->dameColegio();
                    
                    if($mensajeError=="")
                    {
                        $datosAdicional = "";
                        
                        if($isModuloAlumnos)
                        {
                            $datosAdicional = '<fechaNac>'.$fila['nacimiento'].'</fechaNac>
                                               <curp>'.$fila['curp'].'</curp>
                                               <lugarNac>'.$fila['lugar'].'</lugarNac>
                                               <direccion>'.$fila['calle'].'</direccion>
                                               <colonia>'.$fila['colonia'].'</colonia>
                                               <cp>'.$fila['cp'].'</cp>
                                               <ciudad>'.$fila['ciudad'].'</ciudad>
                                               <estado>'.$fila['estado'].'</estado>
                                               <telCasa>'.$fila['telcasa'].'</telCasa>
                                               <telEmer>'.$fila['telemergencia'].'</telEmer>
                                               <papa>'.$fila['padre_alumno'].'</papa>
                                               <emailPapa>'.$fila['email_padre'].'</emailPapa>
                                               <celPapa>'.$fila['celpapa'].'</celPapa>
                                               <mama>'.$fila['madre_alumno'].'</mama>
                                               <emailMama>'.$fila['email_madre'].'</emailMama>
                                               <celMama>'.$fila['celmama'].'</celMama>
                                               <matSep>'.$fila['matsep'].'</matSep>
                                               <estatus>'.$fila['estatus_alumno'].'</estatus>                                           
                                               <edad>'.$fila['edad'].'</edad>                                          
                                               <madreofi>'.$fila['madreofi'].'</madreofi>                                          
                                               <padreofi>'.$fila['padreofi'].'</padreofi>';
                        }
                        
                        
                        $datos .= '
                                <elemento>
                                        <tipoAlumno>'.$tipoAlumno.'</tipoAlumno>
                                        <idAspirante>'.$fila['idAspirante'].'</idAspirante>
                                        <matricula>'.$fila['matricula'].'</matricula>
                                        <escuela>'.$fila['escuela'].'</escuela>
                                        <nivel>'.$fila['nivel'].'</nivel>
                                        <grado>'.$fila['grado'].'</grado>
                                        <nombre>'.$nombreAlumno.'</nombre>
                                        <idNivel>'.$fila['idNivel'].'</idNivel>
                                        <idEscuela>'.$fila['idEscuela'].'</idEscuela>
                                        <grupo>'.$fila['grupo'].'</grupo>
                                        <nlista>'.$fila['nlista'].'</nlista>
                                        <titular>'.$nombreTitular.'</titular>
                                        <ciclo>'.$fila['ciclo'].'</ciclo>
                                        <obsCiclo>'.$fila['textoCiclo'].'</obsCiclo>
                                        <idGrado>'.$fila['idGrado'].'</idGrado>
                                        <idTitular>'.$fila['idTitular'].'</idTitular>
                                        <beca>'. $porcenajeBeca .'</beca>
                                        <idGrupo>'.$fila['idGrupo'].'</idGrupo>
                                        <clg>'.$clg.'</clg>
                                        <fotoAlumno>'.$fila['foto_alumno'].'</fotoAlumno>
                                        '.$datosAdicional.'
                                        <error>no</error>
                                </elemento>';
                    }
                
        }
        else
        {
            if($matricula !="")
            {
                $tipoAlumno = "alumno";
            }
            else
            {
                 $tipoAlumno = "aspirante";
            }
            $mensajeError .= utf8_decode("No fue posible encontrar la información del ".$tipoAlumno.". --> ").$contadorRegistros;	
        }
            
            
            
            if($mensajeError!="")
            {
                $datos .= '
                            <elemento>
                                <error>si</error>
                                <descripcion>'.$mensajeError.'</descripcion>
                            </elemento>';
            }
    
            $datos.=' </datos>';
        
        return utf8_encode($datos);
    }
    
    function global_dameIsValeAplicadoAlumno($idCiclo,$idAspecto,$idMateria,$matricula)
    {
        $mensajeError = "";
	
	$error = false;
	$mensaje = "";
	$valeAplicado = false;
	
	$arrRetorno = array("Error"=>$error,"Mensaje"=>$mensaje,"ValeAplicado"=>$valeAplicado);
	
	
	//DETERMINO SI EL ALUMNO TIENE UN DESBLOQUEO DEL VALE
	$sqlValida = "SELECT adv.id_alumnodesbloqueo_vale FROM alumno_desbloqueo_vale adv where curdate() between fechaInicio_desbloqueo and fechaFin_desbloqueo and adv.matricula_alumno = '".$matricula."'";
	$resValida = bd_consultaSQL($sqlValida);
	
	if(bd_error())
	{
		$mensajeError.="Error en consulta: ".bd_mensajeError().'\n'.$sqlValida;
	}
	else
	{
		if(bd_cuentaRegistros($resValida)>0)
		{
			//YA EXISTE UN DESBLOQUEO
		}
		else
		{
			//VALIDO SI TIENE VALE APLICADO
			$sql = "SELECT v.id_vale as idVale,ae.nombre_aspectoevaluacion as nombreAspecto FROM vale v
			inner join aspecto_evaluacion ae on ae.id_aspectoevaluacion = v.id_aspectoevaluacion
			AND estatus_vale in ('APLICADO','REGISTRADO') and v.id_aspectoevaluacion = ".$idAspecto." and v.id_cicloescolar = ".$idCiclo." and v.id_materia = '".$idMateria."'
			AND v.matricula_alumno = '".$matricula."'
			
			";
			$res = bd_consultaSQL($sql);
			$contadorRegistros = bd_cuentaRegistros($res);
			if(bd_error())
			{
				$mensajeError.="Error en consulta: ".bd_mensajeError().'\n';
			}
			else if($contadorRegistros>0)//Existen registros para los datos
			{
				$fila = bd_dameRegistro($res);
				
				$nombreAspecto = $fila['nombreAspecto'];
				
				$valeAplicado = true;
				//$mensaje = "Se encontraron ".$contadorRegistros." vales aplicados y/o registrados para el aspecto ".$nombreAspecto.".\n Por lo que no es posible guardar las calificaciones.";
				
				$mensaje = "No se pueden modificar calificaciones con vales aplicados o registrados.";
			}	
		}
		

	}
	


	
	if($mensajeError!="")
	{
		$error = true;
		$mensaje = $mensajeError;
	}
	
	$arrRetorno = array("Error"=>$error,"Mensaje"=>$mensaje,"ValeAplicado"=>$valeAplicado);
	
	return $arrRetorno;
    }
    
    
    /****************************************************************************************************
     FUNCION: global_dameInfoFechaPorFecha()
     OBJETIVO: RETORNA ARRAY DE EL NOMBRE DE LA FECHA
     PARAMETROS: a_fecha.- Fecha en formato 'YYYY-mm-dd'
     ****************************************************************************************************/
    public function global_dameInfoFechaPorFecha($a_fecha)
    {
        $arrNombreFecha = array("NDia"=>"","Dia"=>"","NMes"=>"","Mes"=>"","Anio"=>"","Fecha"=>$a_fecha);
        
        //DÍAS
        $arrNombreFecha['NDia'] = $this->global_cambiaDiaInglesEspaniol(date('l', strtotime($a_fecha)));
        $arrNombreFecha['Dia'] = date('d', strtotime($a_fecha));
        $arrNombreFecha['NumDiaEnSemana'] = date('w', strtotime($a_fecha));//0 = domingo,
        
        //MES
        $arrNombreFecha['NMes'] = $this->global_cambiaMesInglesEspaniol(date('F', strtotime($a_fecha)));
        $arrNombreFecha['Mes'] = date('m', strtotime($a_fecha));
        
        //ANIO
        $arrNombreFecha['Anio'] = date('Y', strtotime($a_fecha));
        
        return $arrNombreFecha;
    }
    
    public function global_cambiaMesInglesEspaniol($a_mes)
    {
        
        $arrMonths = array("January","February","March","April","May","June","July","August","September","October","November","December");
        $arrMeses = array("enero","febrero","marzo","abril","mayo","junio","julio","agosto","septiembre","octubre","noviembre","diciembre");
        
        $a_mes = str_replace($arrMonths,$arrMeses,$a_mes);
        
        return $a_mes;
    }
    
    public function global_cambiaDiaInglesEspaniol($a_dia)
    {
        
        $arrDays = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
        $arrDias = array("lunes","martes","miércoles","jueves","viernes","sábado","domingo");
        
        $a_dia = str_replace($arrDays,$arrDias,$a_dia);
        
        return $a_dia;
    }

    /****************************************************************************************************
     FUNCION: global_sumaRestaDiasFecha()
     OBJETIVO: RETORNA LA FECHA SUMADA/RESTADA CON EL SELECCIONADO
     PARAMETROS: a_fecha.- Fecha en formato 'YYYY-mm-dd'    $a_dias.- Suma/Resta de días '+1|-1'
     ****************************************************************************************************/
    public function global_sumaRestaDiasFecha($a_fecha,$a_dias)
    {
        return date ("Y-m-d", strtotime($a_dias." day", strtotime($a_fecha)));
    }
    
    /****************************************************************************************************
     FUNCION: global_dameInfoBloquesSemanasEntreFechas()
     OBJETIVO: RETORNA UN ARREGLO CON LA INFO DE LAS SEMANAS
     PARAMETROS: $a_fechaInicio.- Fecha inicio en formato 'YYYY-mm-dd'    $a_fechaFin.- Fecha fin en formato 'YYYY-mm-dd'
     ****************************************************************************************************/
    public function global_dameInfoBloquesSemanasEntreFechas($a_fechaInicio,$a_fechaFin,$a_vista)
    {
        $isExisteHorarioMateria = false;
        $mensajeError = "";
        $error = false;
        $mensaje = "";
        $arrSemanas = array();
	$arrSemanaEnCurso = array();
        
        $arrRetorno = array("Error"=>$error,"Mensaje"=>$mensaje,"arrSemanas"=>$arrSemanas);
 
        switch($a_vista)
        {
            case 'examenProgramacion':
                
                $infoSemana = array();
                $infoSemana['id'] = 0;
                $infoSemana['textoFecha'] = "Seleccione";
                
                //GUARDO LA INFO DE LA SEMANA
                array_push($arrSemanas,$infoSemana);
                
            break;
        }
        
        
        $nSemana = 1;
        $fechaCiclo = $a_fechaInicio;
        $arrColorMateriaProcesada = array();
	while (strtotime($fechaCiclo) <= strtotime($a_fechaFin))
	{
                //INICIALIZO POR CICLO
                $infoFecha = array();
            
		//INICIALIZO EL ARRAY DE LA SEMANA EN CURSO EN CASO DE QUE NO EXISTA
		if(!isset($arrSemanaEnCurso[$nSemana]))
		{
		    $arrSemanaEnCurso[$nSemana] = array();
		}
                

		//OBTENGO EL DÍA
		$arrFechaEnCurso = $this->global_dameInfoFechaPorFecha($fechaCiclo);
		$nDia = trim($arrFechaEnCurso['NDia']);
                $numDiaEnSemana = $arrFechaEnCurso['NumDiaEnSemana'];
                
                
                //GENERO EL DETALLE DEL DÍA
                $infoFecha['id'] = $numDiaEnSemana;
                $infoFecha['fecha'] = $fechaCiclo;
                $infoFecha['existeHorario'] = "0";
                $infoFecha['inicioHoraClase'] = "";
                $infoFecha['duracionEnHorasHoraClase'] = "";
                $infoFecha['idMateria'] = "";
                $infoFecha['nombreMateria'] = "";
                $infoFecha['nombreCortoMateria'] = "";
                $infoFecha['numDiaEnSemana'] = "";
                $infoFecha['datosMateriaRegistrada'] = array();
                $infoFecha['colorNuevo'] = "";
                
                
                //SI LA VISTA ES examenProgramacion BUSCO EL HORARIO DE LA MATERIA
                if($a_vista=='examenProgramacion')
                {
                        
                        //SI TRAIGO GRUPO Y MATERIA; ES QUE ESTOY FILTRANDO POR UNA SOLA, DETERMINO SU HORARIO DE LA MATERIA PARA HABILITAR LOS CUADROS
                        if($this->idGrupo > 0 && trim($this->idMateria)!="")
                        {
                            $sql = "SELECT
                                        h.id_horario as idHorario,
                                        h.id_carrera as idCarrera,
                                        h.id_profesor as idProfesor,
                                        hdc.id_diaclase as idDiaClase,
                                        hdc.nombre_diaclase as nombreDiaClase,
                                        GROUP_CONCAT(ifnull(hhc.id_horaclase,'') ORDER BY hhc.id_horaclase) as idHoraClase,
                                        GROUP_CONCAT(ifnull(DATE_FORMAT(hhc.inicio_horaclase,'%H:%i'),'') ORDER BY hhc.id_horaclase) as inicioHoraClase,
                                        GROUP_CONCAT(ifnull(CEIL(hhc.duracion_horaclase/60),'') ORDER BY hhc.id_horaclase) as duracionHoraClase,
                                        mat.id_materia as idMateria,
                                        mat.nombre_materia as nombreMateria,
                                        mat.nombrecorto_materia as nombreCortoMateria,
                                        s.id_salon as idSalon,
                                        s.nombreCorto_salon as nombreCortoSalon,
                                        s.nombre_salon as nombreSalon,
                                        '".$this->global_colores_hex()."' as colorNuevo
                                FROM
                                        horarios h
                                INNER JOIN horarios_diaclase hdc ON hdc.id_diaclase = h.id_diaclase
                                AND h.id_cicloescolar = ".$this->idCiclo."";
                                
                                $sql.=" AND h.id_grupo = ".$this->idGrupo."
                                AND h.id_materia = '".$this->idMateria."'"; 
                                $sql.=" AND hdc.id_diaclase  = ".$numDiaEnSemana;
                                $sql.=" INNER JOIN horarios_horaclase hhc ON hhc.id_horaclase = h.id_horaclase
                                INNER JOIN materia mat on mat.id_materia = h.id_materia
                                LEFT OUTER JOIN salones s on s.id_salon = h.id_salon
                                GROUP BY hdc.id_diaclase
                                ";
                                
                            if($this->permiteOtrosHorarios == 1)
                            {
                                $sql = "select 
                                    distinct
                                    null as idHorario,
                                    null as idCarrera,
                                    NULL as idProfesor,
                                    null as idDiaClase,
                                    null as nombreDiaClase,
                                    null as idHoraClase,
                                    null as inicioHoraClase,
                                    null as duracionHoraClase,
                                    mat.id_materia as idMateria,
                                    mat.nombre_materia as nombreMateria,
                                    mat.nombrecorto_materia as nombreCortoMateria,
                                    null as idSalon,
                                    null as nombreCortoSalon,
                                    null as nombreSalon,
                                    null as colorNuevo
                                    from alumno_materia am
                                    inner join inscripcion i on i.id_inscripcion = am.id_inscripcion
                                    and i.id_cicloescolar = ".$this->idCiclo."
                                    and am.id_grupo = ".$this->idGrupo."
                                    and am.id_materia = '".$this->idMateria."'
                                    inner join materia mat on mat.id_materia = am.id_materia";
                            }
                            
                            $res = bd_consultaSQL($sql);
                            
                            if(bd_error())
                            {
                                $mensajeError.="Error consulta: ".bd_mensajeError()."\n";
                                break;
                            }
                            else if(bd_cuentaRegistros($res)==1)
                            {
                                //HABILITO LA BANDERA DE QUE SÍ HAY HORARIO MATERIA
                                $isExisteHorarioMateria = true;
                                
                                $fila = bd_dameRegistro($res);
                                
                                //QUITO LOS SEGUNDOS DEL INICIO
                                $inicioHoraClase = $fila['inicioHoraClase'];
                                //PROCESO EN HORAS
                                $duracionEnHorasHoraClase = $fila['duracionHoraClase'];//Convertimos a horas los minutos
                                
                                
                                //OBTENGO LA INFORMACIÓN DE LA MATERIA QUE SE OFERTA
                                $infoFecha['existeHorario'] = "1";
                                $infoFecha['inicioHoraClase'] = $inicioHoraClase;
                                $infoFecha['duracionEnHorasHoraClase'] = $duracionEnHorasHoraClase;
                                $infoFecha['idMateria'] = $fila['idMateria'];
                                $infoFecha['nombreMateria'] = "".$fila['nombreMateria'];
                                $infoFecha['nombreCortoMateria'] = "".$fila['nombreCortoMateria'];
                                $infoFecha['numDiaEnSemana'] = $numDiaEnSemana;
                                $infoFecha['colorNuevo'] = $fila['colorNuevo'];
                            }
                            else if(bd_cuentaRegistros($res)>1)
                            {
                                $mensajeError.="Error al obtener los horarios, se obtuvieron más horarios.\n";
                                break;
                            }
                        }
                        else
                        {
                            $isExisteHorarioMateria = true;
                        }
                        
                        
                        
                        //BUSCO SI EL GRUPO TIENE ALGÚN REGISTRO EN EXAMEN PROGRAMACIÓN
                        $sqlMateria = "select
                        1 as tieneRegistro,
                        ep.id_examen_programacion as idExamenProgramacion,
                        date(ep.fecha_examen_programacion) as fechaExamenProgramacion,
                        tm.id_tipomateria as idTipoMateria,
                        tm.nombre_tipomateria as nombreTipoMateria,
                        g.id_grupo as idGrupo,
                        g.nombre_grupo as nombreGrupo,
                        mat.id_materia as idMateria,
                        mat.nombrecorto_materia as nombreCortoMateria,
                        concat('',mat.nombre_materia) as nombreMateria,
                        p.id_profesor as idProfesor,
                        CONCAT_WS('-',p.paterno_profesor,p.materno_profesor,p.nombre_profesor) as nombreProfesor,
                        hdc.id_diaclase as idDiaClase,
                        hdc.nombre_diaclase as nombreDiaClase,
                        hhc.id_horaclase  as idHoraClase,
                        DATE_FORMAT(hhc.inicio_horaclase,'%H:%i') as inicioHoraClase,
                        CEIL(hhc.duracion_horaclase/60) as duracionHoraClase,
                        0 as color,
                        CONCAT(e.nombre_edificio,'.',s.nombre_salon) AS nombreSalon
                        from examenes_programacion ep
                        inner join examenes_programacion_periodos epp on epp.id_examen_periodo = ep.id_examen_periodo
                        and ep.usuario_registro = ".$this->idUsuario."
                        and epp.id_cicloescolar = ".$this->idCiclo."
                        and ep.id_examen_periodo = ".$this->idExamenPeriodo."";
                        
                        if($this->idGrupo > 0 && trim($this->idMateria)!="")
                        {
                            //$sqlMateria.=" AND ep.id_grupo = ".$this->idGrupo."
                            //AND ep.id_materia = '".$this->idMateria."'";
                            
                            //TICKET 13727.- CEPM 20160314, DICE QUE EL FITRO SE HAGA POR MATERIA, PERO ES POR GRUPO
                            
                            $sqlMateria.=" AND ep.id_grupo = ".$this->idGrupo."";
                        }
                        
                        
                        $sqlMateria.="
                        inner join materia mat on mat.id_materia = ep.id_materia
                        inner join tipo_materia tm on tm.id_tipomateria = ep.id_tipomateria
                        inner join grupo g on g.id_grupo = ep.id_grupo
                        inner join horarios_diaclase as hdc on hdc.id_diaclase = ep.id_diaclase
                        AND hdc.id_diaclase  = ".$numDiaEnSemana."
                        inner join horarios_horaclase hhc on hhc.id_horaclase = ep.id_horaclase
                        inner join profesor p on p.id_profesor = ep.id_profesor
                        LEFT JOIN salones s ON  s.id_salon = ep.id_salon
                        LEFT JOIN edificios e ON e.id_edificio = s.id_edificio
                        ORDER BY hdc.id_diaclase,hhc.id_horaclase,ep.id_grupo,ep.id_materia
                        ";
                        
                        $resMateria = bd_consultaSQL($sqlMateria);
                    
                        if(bd_mensajeError())
                        {
                            $mensajeError.="Error consulta: ".bd_mensajeError()."\n";
                            break;
                        }
                        else if(bd_cuentaRegistros($resMateria)>0)
                        {
                            while($filaMateria = bd_dameRegistro($resMateria))
                            {
                                $idMateria = $filaMateria['idMateria'];
                                $idGrupo = $filaMateria['idGrupo'];
                                $idMateriaGrupo = $idMateria.'-'.$idGrupo;
                                
                              
                                
                                
                                if($arrColorMateriaProcesada[$idMateriaGrupo]['color']=="")//NO trae color
                                {
                                    $arrColorMateriaProcesada[$idMateriaGrupo]['color'] = $this->global_colores_hex();
                                }
                                
                                //ASIGNO EL COLOR DE LA MATERIA
                                $filaMateria['color'] = $arrColorMateriaProcesada[$idMateriaGrupo]['color'];
                                
                                array_push($infoFecha['datosMateriaRegistrada'],$filaMateria);
                            }
                        }
                        else
                        {
                            array_push($infoFecha['datosMateriaRegistrada'],array("tieneRegistro"=>"0"));
                        }
                        
                }
                
                //GUARDO EL DÍA EN MI ARREGLO DE SEMANAS
		array_push($arrSemanaEnCurso[$nSemana],$infoFecha);
        
        if($_POST['idUsuario']==29)
        {
            //echo $sqlMateria.';';
        }
                
		
		// SI EL DÍA ES DOMINGO O ES EL ÚLTIMO DÍA, GUARDO EL BLOQUE Y CAMBIO EL KEY DE LA SEMANA //
		if($numDiaEnSemana == 0 || $fechaCiclo == $a_fechaFin)
		{

			//PROCESO EL ARREGLO DE LA SEMANA PARA OBTENER LA PRIMER FECHA Y LA ÚLTIMA, Y UN ARREGLO DE SEMANAS
			$infoSemana = array();
			$fechaInicioSemana = $arrSemanaEnCurso[$nSemana][0]['fecha'];
			$fechaFinSemana = $arrSemanaEnCurso[$nSemana][(sizeof($arrSemanaEnCurso[$nSemana])-1)]['fecha'];

                        
			$arrFechaInicioSemana = $this->global_dameInfoFechaPorFecha($fechaInicioSemana);
			$nFechaInicioSemana = utf8_decode($arrFechaInicioSemana['NDia']);

			$arrFechaFinSemana = $this->global_dameInfoFechaPorFecha($fechaFinSemana);
			$nFechaFinSemana = utf8_decode($arrFechaFinSemana['NDia']);
			
			$infoSemana['id'] = $nSemana;
			$infoSemana['textoFecha'] = "Del ".$fechaInicioSemana." al ".$fechaFinSemana;
			$infoSemana['diasSemana'] = $arrSemanaEnCurso[$nSemana];
			$infoSemana['fechaInicioSemana'] = $fechaInicioSemana;
			$infoSemana['fechaFinSemana'] = $fechaFinSemana;
			$infoSemana['nFechaInicioSemana'] = ($nFechaInicioSemana);
			$infoSemana['nFechaFinSemana'] = ($nFechaFinSemana);
                        $infoSemana['diasSemanaCompleta'] = $this->global_dameSemanaCompleta($fechaInicioSemana);
                        
            //$infoSemana['sql'] = $sqlMateria;
            
            
			
			//GUARDO LA INFO DE LA SEMANA
			array_push($arrSemanas,$infoSemana);
			
			//AUMENTO LA SEMANA
			$nSemana++;
			
			
		}
		
		//AUMENTO UN DÍA LA FECHA
		$fechaCiclo = $this-> global_sumaRestaDiasFecha($fechaCiclo,"+1");
	}
        
        if($mensajeError == "")
        {
            switch($a_vista)
            {
                case 'examenProgramacion':
                    if(!$isExisteHorarioMateria)
                    {
                        $mensajeError.="No existe un horario registrado para la materia seleccionada\nFavor de registrar un horario para poder continuar.\n";
                    }
                break;
            }
        }
        
        
        if($mensajeError!="")
        {
            $error = true;
            $mensaje = $mensajeError;
        }
        else
        {
            $mensaje = "Proceso realizado correctamente.";
        }
        
        $arrRetorno = array("Error"=>$error,"Mensaje"=>$mensaje,"arrSemanas"=>$arrSemanas);
        
        return $arrRetorno;
    }
    
    public function global_dameSemanaCompleta($a_fecha)
    {
        $arrSemanaCompleta = array();
        
        //OBTENGO EL INICIO DE LA SEMANA Y EL FIN DE LA SEMANA, DE LUNES A VIERNES
		
        #Obtengo el día de la primera fecha para saber desde que día incié
        $arrInfoInicio = $this->global_dameInfoFechaPorFecha($a_fecha);
        $numDiaSemanaFechaInicio = $arrInfoInicio['NumDiaEnSemana'];
        $numDiaSemanaFechaFin = $arrInfoInicio['NumDiaEnSemana'];
        
        #Determino cuál es el lunes de esa semana, eso me determinará FECHA INICIAL
        $fechaInicioSemanaCompleta = $a_fecha;
        $fechaFinSemanaCompleta = $a_fecha;
        
        #Obtengo la fecha inicial
        while($numDiaSemanaFechaInicio > 1)
        {
                $fechaInicioSemanaCompleta = $this->global_sumaRestaDiasFecha($fechaInicioSemanaCompleta,"-1");	
                
                $numDiaSemanaFechaInicio--;
        }
        
        #Obtengo la fecha final
        while ($numDiaSemanaFechaFin < 7)
        {
                $fechaFinSemanaCompleta = $this->global_sumaRestaDiasFecha($fechaFinSemanaCompleta,"+1");	
        
                $numDiaSemanaFechaFin++;
        }
        
        $fechaCiclo = $fechaInicioSemanaCompleta;
        while (strtotime($fechaCiclo) <= strtotime($fechaFinSemanaCompleta))
	{
            array_push($arrSemanaCompleta,$fechaCiclo);
            
            //AUMENTO UN DÍA LA FECHA
	    $fechaCiclo = $this-> global_sumaRestaDiasFecha($fechaCiclo,"+1");
        }
        
        return $arrSemanaCompleta;
    }
    
    private function random_color_part()
    {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }
    
    public function global_random_color()
    {
        return "#".$this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

    
    public function global_colores_hex()
    {
                
        $arrColores = array("B39EB5",
                            "FFB347",
                            "FF6961",
                            "58ACFA",
                            "538368",
                            "CFCFC4",
                            "AEC6CF",
                            "836953",
                            "DEA5A4",
                            "5F9F9F",
                            "9DB68C",
                            "2A0A1B",
                            "86B404",
                            "544335",
                            "835356",
                            "535683",
                            "536e83",
                            "00688B",
                            "000000");
        
        $max = sizeof($arrColores)-1;
        
        $random = rand(0,$max);
        
        return "#".$arrColores[$random];
    }
    
    /**
        METODOS DEBUG
    **/

    public function getDebug()
    {
            return $this->isDebug;
    }
            
    public function setDebug($a_debug)
    {
            $this->isDebug = $a_debug; 
    }

    public function global_finanzas_validaRFC($rfc)
    {
     $retorno = false;
     
     //HOTFIX CEPM 20160419 EL RFC DEBE DETERMINAR CON UNA LETRA A O UN DÍGITO
     //$regex = "/^[A-Z,—,&]{3,4}[0-9]{2}[0-1][0-9][0-3][0-9][A-Z,0-9]?[A-Z,0-9]?[0-9,A-Z]?$/D";
     
     $regex = self::REG_RFC;
     
     if (preg_match($regex, $rfc))
     {
      $retorno = true;
     }
     
     return $retorno;
    }
    
    function global_finanzas_validaCURP($curp)
    {
            $retorno = false;
            
            //$regex = "/^[A-Z][A,E,I,O,U,X][A-Z]{2}[0-9]{2}[0-1][0-9][0-3][0-9][M,H][A-Z]{2}[B,C,D,F,G,H,J,K,L,M,N,—,P,Q,R,S,T,V,W,X,Y,Z]{3}[0-9,A-Z][0-9]$/D";
            
            $regex = self::REG_CURP;
            
            if (preg_match($regex, $curp))
            {
                    $retorno = true;
            }
            
            return $retorno;
    }

    /****************************************************************************************************
    FUNCION: global_decodificaPOST()
    OBJETIVO: RETORNA SUPERGLOBAL POST CODIGFICADO PARA ALMACENAMIENTO EN MYSQL FUNCIONA CORRCTAMENTE CON 
                      CHARACTER_SET_NAME=latin1, COLLATION_NAME=latin1_swedish_ci
    PARAMETROS: OBJETO:SUPERGLOBAL POST
    ****************************************************************************************************/
     public function global_decodificaPOST()
    {
        foreach($_POST as $key => $value)
        {
            $_POST[$key] = utf8_decode($value);
        }
    }
    /****************************************************************************************************
    FUNCION: global_decodificaGET()
    OBJETIVO: RETORNA SUPERGLOBAL POST CODIGFICADO PARA ALMACENAMIENTO EN MYSQL FUNCIONA CORRCTAMENTE CON 
                      CHARACTER_SET_NAME=latin1, COLLATION_NAME=latin1_swedish_ci
    PARAMETROS: OBJETO:SUPERGLOBAL GET
    ****************************************************************************************************/
     public function global_decodificaGET()
    {
        foreach($_GET as $key => $value)
        {
            $_GET[$key] = utf8_decode($value);
        }
    }
/****************************************************************************************************
FUNCION: copyContentFile()
OBJETIVO: RETORNA ESTATUS DE LA OPERACION
PARAMETROS: OBJETO:PROPIEDAD
****************************************************************************************************/
     function copyContentFile($file1,$file2){ 
              $contentx =@file_get_contents($file1); 
              if ($contentx === FALSE) { 
                        $status=false; 
                }else{
                   $openedFile = fopen(trim($file2), "w");                    
                   if($openedFile=== FALSE){                    
                        $status=false;
                   } else{
                     $r=fwrite($openedFile, $contentx);                       
                     $status=true;
                   }
                   fclose($openedFile); 
                }
            return $status; 
        } 
    function convertPath($path)
    {
        //split networkpath into parts
        $parts = explode('\\',$path);
        // convert each part to SJIS
        foreach($parts as $index => $part)
        {
             $parts[$index] = iconv('UTF-8','SJIS//IGNORE',$part);
        }    
        // put the network path back together 
        return implode('\\',$parts);
    }
    
    
    
    /****************************************************************************************************
FUNCION: global_dameArregloSPExclusion()
OBJETIVO: REGRESA EL ARREGLO DE SP'S A EXCLUIR
PARAMETROS: OBJETO:PROPIEDAD
****************************************************************************************************/
public function global_dameArregloSPExclusion()
{
    $arrExcepcion = array
    (
        "sp_1"
        ,"sp_sistema_dameModulos"
    );
    
    return $arrExcepcion;
}

public function global_registraLogMovimiento()
{
    if(!bd_error())
    {
        $this->consulta = trim($this->consulta);
        
        $this->sql = "Call sp_general_registraBitacoraAcceso(
        ".$this->global_procesaParametroNullSQL($this->global_escapaParametro($this->sp),true).",
        ".$this->global_procesaParametroNullSQL($this->global_escapaParametro($this->consulta),true).",
        ".$this->global_procesaParametroNullSQL($this->global_escapaParametro($_SERVER['SCRIPT_FILENAME']),true).",
        ".$this->global_procesaParametroNullSQL($this->global_dameIdUsuario(),false).",
        ".$this->global_procesaParametroNullSQL($this->global_escapaParametro($this->origen),true)."
        
        
        )";
        
        //echo $this->sql;
        
        $res = bd_consultaSQL($this->sql);
    }
}

    
public function global_dameIdUsuario()
{
    $idUsuario = 0;
    
    $arrKey = array("usuarioId","idUsuario","usuario","usaurio");
    
    foreach($arrKey as $keyEnCurso)
    {
        if(isset($_POST[$keyEnCurso]))
        {
            $idUsuario = intval($_POST[$keyEnCurso]);
            break;
        }
        else if(isset($_GET[$keyEnCurso]))
        {
            $idUsuario = intval($_POST[$keyEnCurso]);
            break;
        }
        else if(isset($_SESSION[$keyEnCurso]))
        {
            $idUsuario = intval($_POST[$keyEnCurso]);
            break;
        }
    }
    
    
    return $idUsuario;
}


public function global_escapaParametro($valor)
{
	if(is_array($valor))
	return array_map(__METHOD__, $valor);
	
	if(!empty($valor) && is_string($valor))
	{
	    return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $valor);
	}
	return $valor; 
}


public function global_comparaSPEstructura($sql)
{
    /*DETERMINO SI ES UN SP Y ESTÁ ACTUALIZADO, COMPARANDO CONTRA ESTRUCTURA,SI NO EJECUTO*/
	
	//DETERMINO EL NOMBRE DEL SP
	$arrSQL = explode(" ",trim($sql));

	$nombreSP = "";
	$call = strtoupper(trim($arrSQL[0]));

	if($call=='CALL')
	{		

            $nombreSP = trim($this->global_dameNombreSP($sql));
            if($nombreSP!="")
            {
                    $arrRetornoCompara = $this->bd_comparaEstructura($nombreSP,$sql);
                    
                    if($arrRetornoCompara['Error'])//ERROR, EJECUTAMOS RAISE ERROR
                    {
                            $ejecutaConsulta = false;
                            
                            //EJECUTAMOS EL RAISE ERROR
                            $this->bd_ejecutaRaiseError($arrRetornoCompara['NoError'],$arrRetornoCompara['ErrorMensaje']);
                            
                    }

            }
		
	}
}

//FUNCIÓN PARA COMPARAR CONTRA ESTRUCTURA
private function bd_comparaEstructura($nombreSP,$sql)
{
	
	
	$arrRetorno = array("Error"=>false,"ErrorMensaje"=>"","NoError"=>0);
	
	if(strtoupper($nombreSP) == 'RAISERROR' || strtoupper($nombreSP)== strtoupper('sp_general_dameDefinicionSP') || strtoupper($nombreSP)== strtoupper('sp_general_registraBitacoraAcceso') || in_array($nombreSP,$this->global_dameArregloSPExclusion()))//SI EL SP ES RAISERROR O ESTÁ EN LA LISTA DE EXCLUSIÓN, NO HAY ERROR
	{
		return $arrRetorno;
	}
	
	//DETERMINO LA BD
	$sqlEnUso = "SELECT DATABASE() as bd;";
	$resEnUso = bd_consultaSQL($sqlEnUso);
	
	if(!bd_error() && $resEnUso)
	{
		if(bd_cuentaRegistros($resEnUso)==1)
		{
			$filaEnUso = bd_dameRegistro($resEnUso);
			
			$bdEnUso = $filaEnUso['bd'];
			
			
			//COMPARO CONTRA ESTRUCTURA EL PROCEDIMIENTO
			$arrCompara = $this->bd_comparaDefinicion($nombreSP,$bdEnUso,$sql);
			if($arrCompara['Error'])
			{
				$arrRetorno['Error'] = true;
				$arrRetorno['ErrorMensaje'] = $arrCompara['ErrorMensaje'];
				$arrRetorno['NoError'] = $arrCompara['NoError'];
			}
		}
	}
	else
	{
		$arrRetorno['Error'] = true;
		$arrRetorno['ErrorMensaje'] = "Ocurrió un error al obtener la bd de datos en uso.";
		$arrRetorno['NoError'] = 1;
	}
	
	return $arrRetorno;
}

//FUNCIÓN PARA COMPARAR DEFINICIÓN
private function bd_comparaDefinicion($nombreSP,$bd,$sql)
{
	$arrRetorno = array("Error"=>false,"ErrorMensaje"=>"","NoError"=>0);
	
	//OBTENO LA DEFINICIÓN DE ESTRUCTURA
	$sqlEstructura = "CALL sp_general_dameDefinicionSP('".$nombreSP."','lasalle_estructura')";
	$resEstructura = bd_consultaSQL($sqlEstructura);

	if(bd_cuentaRegistros($resEstructura)==1)
	{
		$filaEstructura = bd_dameRegistro($resEstructura);
		$definicionEstructura = trim($filaEstructura['definicion']);
		
		//OBTENGO LA DEFINICIÓN DE LA BD EN CURSO
		$sqlDefinicion = "CALL sp_general_dameDefinicionSP('".$nombreSP."','".$bd."')";
		$resDefinicion = bd_consultaSQL($sqlDefinicion);
		
		if(bd_cuentaRegistros($resDefinicion)!=1)
		{
			$arrRetorno['Error'] = true;
			$arrRetorno['ErrorMensaje'] = "Error al obtener la definición de la bd en curso.";
			$arrRetorno['NoError'] = 2;
		}
		else
		{
			
			$filaDefinicion = bd_dameRegistro($resDefinicion);
			$definicionBD = trim($filaDefinicion['definicion']);
			
			//COMPARO
			if($definicionBD!=$definicionEstructura)
			{
				$arrRetorno['Error'] = true;
				$arrRetorno['ErrorMensaje'] = "SP ".$bd.".".$nombreSP." desactualizado.";
				$arrRetorno['NoError'] = 2;
			}
			
		}
		
	}
	else
	{
		//echo 'Error: '.bd_mensajeError();
		
		$arrRetorno['Error'] = true;
		$arrRetorno['ErrorMensaje'] = "Error al obtener la definición. de estructura.";
		$arrRetorno['NoError'] = 1;
	}	
	
	return $arrRetorno;
}

//FUNCIÓN PARA EJECUTAR RAISE ERROR
public function bd_ejecutaRaiseError($noError,$mensaje)
{
	$sql = "Call RAISERROR(".$noError.",'".$mensaje."')";
	$res = bd_consultaSQL($sql);
}

public function global_dameNombreSP($sql)
{
    //DETERMINO EL NOMBRE DEL SP
    $arrSQL = explode(" ",trim($sql));

    $nombreSP = "";
    $call = strtoupper(trim($arrSQL[0]));
    
    if($call=='CALL')
    {		
            foreach($arrSQL as $key => $value)
            {
                    if($key==0)//NO PROCESO
                    {
                            continue;
                    }
                    
                    //PROCESO HASTA ENCONTRAR TEXTO
                    $value = trim($value);
                    if($value!="")
                    {
                            $nombreSP = $value;
                            
                            $arrNombreSP = explode("(",$nombreSP);
                            
                            $nombreSP = trim($arrNombreSP[0]);
                            
                            break;
                    }
            }
    }
    
    return $nombreSP;
    
    
}
/****************************************************************************************************
FUNCION: dameColegio()
OBJETIVO: RETORNA ARREGLO ERROR
PARAMETROS: OBJETO:PROPIEDAD
****************************************************************************************************/
public function dameColegio(){
    //$this->sql='select replace(DATABASE(),"lasalle_","") as colegio;';
    $this->sql = 'select if(DATABASE() = "lasalle","leon", replace(DATABASE(),"lasalle_","")) as colegio';
    $rsl=bd_consultaSQL($this->sql);
    $fila=bd_dameRegistro($rsl);
    $this->clg=$fila["colegio"];
    return $this->clg;
}




/****************************************************************************************************
FUNCION: global_descargaArchivo()
OBJETIVO: RETORNA ARREGLO FLUSH AL NAVEGADOR
PARAMETROS: a_file RUTA FISICA DEL ARCHIVO A DESCARGAR
****************************************************************************************************/
public function global_descargaArchivo($a_file) {
    //$typeMime=mime_content_type('test.php');
    if (file_exists($a_file)) {
        header('Content-Description: File Transfer');
        header('Content-Type: '.$typeMime);
        header('Content-Disposition: attachment; filename="'.basename($a_file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($a_file));
       ob_clean(); #THIS!
        flush();
        readfile($a_file);
        exit;
    }else{
        echo "El archivo ".basename($a_file)."no existe, favor de verificarlo.";
    }
}

/****************************************************************************************************
FUNCION: dameValidaContrasenaGestorBanco()
OBJETIVO: RETORNA BOLEANO DE VERIFICACION DE CONTRASENA PARA EL GESTOR DE ARCHIVOS
PARAMETROS: OBJETO:PROPIEDAD
****************************************************************************************************/
public function dameValidaContrasenaGestorBanco($a_contrsena){        
    if(md5($a_contrsena)==md5($this->pswGestorArchivosB)){
        return true;
    }
    return false;
}

/****************************************************************************************************
FUNCION: dameValidaContrasenaSupervision()
OBJETIVO: 
PARAMETROS: OBJETO:PROPIEDAD
****************************************************************************************************/
public function dameValidaContrasenaSupervision($a_contrasena)
{        
    if(md5($a_contrasena)==md5($this->pwdSupFact))
    {
        return true;
    }
    
    return false;
}

/****************************************************************************************************
FUNCION: dameValidaContrasenaTraductorTC()
OBJETIVO: 
PARAMETROS: OBJETO:PROPIEDAD
****************************************************************************************************/
public function dameValidaContrasenaTraductorTC($a_contrasena)
{        
    if(md5($a_contrasena)==md5($this->pswTraductorTC))
    {
        return true;
    }
    
    return false;
}

/****************************************************************************************************
FUNCION: dameValidaContrasenaTraductorTef()
OBJETIVO: 
PARAMETROS: OBJETO:PROPIEDAD
****************************************************************************************************/
public function dameValidaContrasenaTraductorTEF($a_contrasena)
{        
    if(md5($a_contrasena)==md5($this->pswTraductorTEF))
    {
        return true;
    }
    
    return false;
}



/****************************************************************************************************
FUNCION: global_PortalAlumnoPagoLineaLog()
OBJETIVO: RETORNA ARREGLO ERROR
PARAMETROS: OBJETO:SUPER GLOBALES GET -POST, 
****************************************************************************************************/
public function global_PortalAlumnoPagoLineaLog($a_templateFormulario,$a_arrKeyExcluir,$a_usuario,$a_bancoTEFTCId=0){
    $_xml= new SimpleXMLElement("<?xml version=\"1.0\"?><datos></datos>");//REFERENCIA DEL OBJETO

    $arrDatos=array();
    $arrDatos["GET"]=array();
    $arrDatos["POST"]=array();
    $idBancoTCTEF=$a_bancoTEFTCId;
    if($idBancoTCTEF==0)
    {
        if( array_key_exists("idBancoTC",$_SESSION) && $_SESSION['idBancoTC']>0)
        {
            $idBancoTCTEF = $_SESSION['idBancoTC'];
        }
    }
    foreach($_GET as $control => $valor){
        if(!in_array($control,$a_arrKeyExcluir)){
         $arrDatos["GET"][$control]="";
         $arrDatos["GET"][$control]=$valor;
        }else{
            $arrDatos["GET"][$control]=$this->dameBloqueValorExcluido($valor);
        }
    }
    if(sizeof($_POST)>0) {
            //$arrPost=array();

            foreach($_POST as $control => $valor){
                if(!in_array($control,$a_arrKeyExcluir)){
                 $arrDatos["POST"][$control]="";
                 $arrDatos["POST"][$control]=$valor;
                }else{
                    $arrDatos["POST"][$control]=$this->dameBloqueValorExcluido($valor);
                }
            }
            /*
            $_xml= new SimpleXMLElement("<?xml version=\"1.0\"?><datos></datos>");//REFERENCIA DEL OBJETO

            $this->global_arrayToXML($arrPost, $_xml,"","") ;
            $datos = $_xml->asXML();
            $sql="call sp_finanzas_registraPagoLineaLog (".$this->global_procesaParametroNullSQL($a_templateFormulario,true).","
                                    . $this->global_procesaParametroNullSQL($datos,true)
                                    .",'_POST','RECIBE',"
                                    .$this->global_procesaParametroNullSQL($_SERVER["REQUEST_URI"],true).","
                                    .$this->global_procesaParametroNullSQL($_SERVER["HTTP_REFERER"],true).","
                                    .$this->global_procesaParametroNullSQL($_SERVER["HTTP_USER_AGENT"],true).","
                                    ."'PORTALALUMNOS',"
                                    .$this->global_procesaParametroNullSQL($a_usuario,false).")";
            $rsl = bd_consultaSQL($sql);       
            */ 
        }
        $this->global_arrayToXML($arrDatos, $_xml,"","") ;
        $datos = $_xml->asXML();    
        $sql="call sp_finanzas_registraPagoLineaLog (".$this->global_procesaParametroNullSQL($a_templateFormulario,true).","
                                . $this->global_procesaParametroNullSQL($datos,true)
                                .",'GET/POST','RECIBE',"
                                .$this->global_procesaParametroNullSQL($_SERVER["REQUEST_URI"],true).","
                                .$this->global_procesaParametroNullSQL($_SERVER["HTTP_REFERER"],true).","
                                .$this->global_procesaParametroNullSQL($_SERVER["HTTP_USER_AGENT"],true).","
                                ."'PORTALALUMNOS',1,"
                                .$this->global_procesaParametroNullSQL($a_usuario,true).","
                                .$idBancoTCTEF
                                .")";
        $rsl = bd_consultaSQL($sql);
        if(isset($_GET["tst"])){
            echo bd_mensajeError();
        }
    }

/****************************************************************************************************
FUNCION: dameBloqueValorExcluido()
OBJETIVO: RETORNA STRING RELLENO CON ***
PARAMETROS: OBJETO:a_valor STRING PARA ENMASCARAR
****************************************************************************************************/
    public function dameBloqueValorExcluido($a_valor){
        $str=$a_valor;
        if(strlen ($str)==8){
            $strResultado=substr($str, -4, 4);
        }elseif(strlen ($str)<8){
            $strResultado=str_pad(substr($str, -4, 4),strlen ($str), "*",STR_PAD_LEFT);
        }else{
            $strResultado=substr($str, 0, 4).str_pad(substr($str, -4, 4),strlen ($str)-4, "*",STR_PAD_LEFT);
        }
    return $strResultado;
    }
    
    
    /****************************************************************************************************
    FUNCION: global_redondeaDecimal()
    OBJETIVO: RETORNO EL REDONDEO A 2 DECIMALES.
    PARAMETROS:
    ****************************************************************************************************/
    public function global_redondeaDecimal($a_valor)
    {
        //return $a_valor;
        return number_format((float)$a_valor, 2, '.', '');
    }
    
    /* creates a compressed zip file */
    public function global_creaZip($files = array(),$destination,$overwrite,$a_isSoloUnDestino)
    {
        $mensajeError = "";
        
        //echo '<pre>';print_r($files);echo '</pre>';
        
        //if the zip file already exists and overwrite is false, return false
        if(file_exists($destination) && !$overwrite)
        {
            $arrErrorLast = error_get_last();
            $mensajeError.='Ya existe un archivo con este nombre '.basename($destination).'<br />'.$arrErrorLast['message'].'<br />';
            
        }
        else
        {
            //vars
            $valid_files = array();
            //if files were passed in...
            if(is_array($files)) {
                    //cycle through each file
                    foreach($files as $file) {
                            //make sure the file exists
                            if(file_exists($file)) {
                                    $valid_files[] = $file;
                            }
                    }
            }
            //if we have good files...
            if(count($valid_files))
            {
                    //create the archive
                    $zip = new ZipArchive();
                    if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                            $arrErrorLast = error_get_last();
                            $mensajeError.='Ocurrió un error al abrir el archivo. Comprueba los permisos.<br />'.$arrErrorLast['message'].'<br />';
                    }
                    else
                    {
                        //add the files
                        foreach($valid_files as $file)
                        {
                            if($a_isSoloUnDestino)
                            {
                               /* $ext = pathinfo($file, PATHINFO_EXTENSION);
                                $fileNameSinExtension = basename($file,$ext);
                                $fileNameSinExtension = rtrim($fileNameSinExtension,".");
                                $nombreArchivo = $fileNameSinExtension.'_'.time().".".$ext;
                                
                                echo $nombreArchivo."<br />";*/
                                
                                $zip->addFile($file,basename($file));
                            }
                            else
                            {
                                $zip->addFile($file,$file);
                            }
                        }
                        //debug
                        //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
                        
                        //close the zip -- done!
                        $zip->close();
                        
                        //check to make sure the file exists
                        if(!file_exists($destination))
                        {
                            $arrErrorLast = error_get_last();
                            $mensajeError.='Error al crear el archivo ZIP. Comprueba los permisos.<br />'.$arrErrorLast['message'].'<br />';
                        }
                    }

            }
            else
            {
                    $mensajeError.='No se obtuvo información para generar el ZIP.<br />';
            }
        }
        

        
        
        if($mensajeError!="")
        {
            $this->defineError(array("Error"=>true,"ErrorMensaje"=>$mensajeError));
        }
    }
    public function global_fechaFlexToMySQL($a_fecha)
    {
        $arrFecha = explode("/",$a_fecha);
        
        $resFecha = $arrFecha[2].'-'.$arrFecha[1].'-'.$arrFecha[0];
        return $resFecha;
        
    }
    public function global_fechaMySQLToFlex($a_fecha)
    {
        $arrFecha = explode("-",$a_fecha);
        
        $resFecha = $arrFecha[2].'/'.$arrFecha[1].'/'.$arrFecha[0];
        return $resFecha;
        
    }
    
   public function strToHex($string){
    $hex = '';
    for ($i=0; $i<strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
        }
        return strToUpper($hex);
    }
    
   public function hexToStr($hex){
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }
    
    public function global_rellenaCerosIZQ($a_dato,$a_noCeros)
    {
        return str_pad($a_dato,$a_noCeros,'0',STR_PAD_LEFT);
    }
    
    public function global_dameLetraPorPosicion($a_pos)
    {
        $letra = "";
        $alphabet = array( 'a', 'b', 'c', 'd', 'e',
                       'f', 'g', 'h', 'i', 'j',
                       'k', 'l', 'm', 'n', 'o',
                       'p', 'q', 'r', 's', 't',
                       'u', 'v', 'w', 'x', 'y',
                       'z'
                       ,'aa','ab','ac','ad','ae','af','ag','ah','ai','aj','ak','al','am','an','ao','ap','aq'
                       ,'ar','as','at','av','aw','ax','ay','az'
                       ,'ba','bb','bc','bd','be','bf','bg','bh','bi','bj','bk','bl','bm','bn','bo','bp','bq'
                       ,'br','bs','bt','bv','bw','bx','by','bz'
                       );
        
        $letra = $alphabet[$a_pos];
        
        return $letra;
    }
    
    /****************************************************************************************************
    FUNCION: global_truncaNumero()
    OBJETIVO: RETORNO EL NUMERO TRUNCADO A LA CANTIDAD DE DECIMALES DESEADO DECIMALES.
    PARAMETROS:EL 
    ****************************************************************************************************/
    public function global_truncaNumero($a_valor, $a_decimales="0")
    {
        if(($posicion = strpos($a_valor, '.')) !== false) {
            //echo $p."<br>";;
            $val = floatval(substr($a_valor, 0, $posicion + 1 + $a_decimales));
        }
        
        return $val;
    }
    
    /****************************************************************************************************
    FUNCION: dameAtributosInfoModulo()
    OBJETIVO: RETORNA LOS ATRIBUTOS DE LOS MÓDULOS
    PARAMETROS: OBJETO:PROPIEDAD
    ****************************************************************************************************/
    public function dameAtributosInfoModulo($idModulo=0,$tipo='')
    {
        $atributos = '';
        $clg = $this->dameColegio();
        $hash = md5($clg.'usuariosie');
        //$idSession = session_id();
        
        //ARMO LA INFO
        $atributos .= ' separador = "'.$this->global_regresaSeparador(1).'" ';
        $atributos .= ' clg = "'.$clg.'" ';
        $atributos .= ' info1 = "'.$hash.'" ';
        
        //SI EXISTE EL MÓDULO ENTONCES BUSCO SU INFORMACIÓN
        if($idModulo>0)
        {
            $this->sql = "
            SELECT 
                m.id_modulo as idModulo,
                m.id_padre as idPadre,
                m.orden_modulo as ordenModulo,
                m.archivo_modulo as archivoModulo,
                m.icono_modulo as iconoModulo,
                m.plataforma_modulo as plataformaModulo,
                m.urlcontrolador_modulo as urlControlador
            FROM
                _modulo m
            WHERE
                m.id_modulo = ".$idModulo."
            ";
            $res = bd_consultaSQL($this->sql);
            $this->defineErrorConsulta($res,"Ocurrió un error al obtener la info. del módulo.");
            
            if(!$this->error)
            {
                if(bd_cuentaRegistros($res)==1)
                {
                    $fila = bd_dameRegistro($res);
                    
                    $atributos .= ' idModulo = "'.$fila['idModulo'].'" ';
                    $atributos .= ' plataformaModulo = "'.trim($fila['plataformaModulo']).'" ';
                    $atributos .= ' urlControlador = "'.trim($fila['urlControlador']).'" ';
                    $arrAtributos = array('separador'=>$this->global_regresaSeparador(1),
                                          'clg'=>$clg,
                                          'info1'=>$hash,
                                          'idModulo'=>$fila['idModulo'],
                                          'plataformaModulo'=>trim($fila['plataformaModulo']),
                                          'urlControlador'=>trim($fila['urlControlador'])
                                          );
                }
            }
        }
        if($tipo=='array')
        {
            return $arrAtributos;    
        }
        else
        {
            return $atributos;
        }
    }
    
    public function dameParametroFinanzas($a_campo)
    {
        $param = '';
        $a_campo = trim($a_campo);
        
        $this->sql = "Call sp_finanzas_dameParametrosFinanzas('".$a_campo."')";
        $resParam = bd_consultaSQL($this->sql);
        $this->defineErrorConsulta($resParam,"Error al recuperar la información del parámetro (1).");

        if(!$this->error)
        {
            if(bd_cuentaRegistros($resParam)==1)
            {
                $filaParam = bd_dameRegistro($resParam);
                
                $param = trim($filaParam['parametro']);
            }
        }
        
        return $param;
    }
    
    public function validaEjecutarBitacora($sql)
    {
        //IMPORTANTE, SI EJECUTAS UN FILTRO CON $sql TIENES QUE VER CÓMO TRATAR LA BANDERA DE isRegistaBitacoraEjecutado, FAVOR DE VALIDAR CON LOS ENCARGADOS DE DESARROLLO.
        
        $isEjecuta = true;
        
        $fileBaseName = basename($_SERVER['SCRIPT_FILENAME']);//Determino el archivo base.
        
        if(in_array($fileBaseName,$this->dameFileExcep()))//Si el archivo base no está en las excepeciones, ejecuto la bitácora.
        {
           $isEjecuta = false; 
        }

        return $isEjecuta;
    }
    
    /****************************************************************************************************
    FUNCION: global_inicializaGETPOST()
    OBJETIVO: INICIALIZA LOS GET
    PARAMETROS: $a_archivo: NOMBRE DEL ARCHIVO
    ****************************************************************************************************/
    public function global_inicializaGETPOST($a_archivo)
    {
        $arrayGET = array();
        $arrayPOST = array();
        
        switch(trim($a_archivo))
        {
            case 'TEST':
                $arrayGET = array();
                $arrayPOST = array("prueba1Post","prueba2Post");
            break;
            
            case 'PERIODO_EVALUACION_V2':
                $arrayGET = array("sieDebug");
                $arrayPOST = array("idProfesor","idGOriginal","noImagenes","idCurso","idPeriodo","adjuntosNombre","idGrupo","idMateria","idME","idNivel","idGN","idCiclo","web","idPE");
            break;
        }
        
        //INICIALIZO EL GET
        foreach($arrayGET as $keyGET)
        {
            if(!isset($_GET[$keyGET]))
            {
               $_GET[$keyGET] = null;
            } 
        }
        
        //INICIALIZO EL POST
        foreach($arrayPOST as $keyPOST)
        {
            if(!isset($_POST[$keyPOST]))
            {
               $_POST[$keyPOST] = null;
            } 
        }
    }
    //luism 20170606 (26577) Se agrega metodo de encriptacion
    public function global_encriptaCadena($a_valor)
    {   
        if($this->clg=="")
        {
            $this->clg=$this->dameColegio(); 
        }
        $cadenaEncriptada="";
        $keyCompuesta="".$this->clg.$this->keyEncriptaColegio;
        //luism:Emilio 20170608(27133) No codificar la cadena a encriptar utf8_encode, los modulos tienen su manejo de string independiente
        $cadenaEncriptada=$this->global_encripta(rtrim(ltrim(($this->arrEnvolturaEncriptacion[0].$a_valor.$this->arrEnvolturaEncriptacion[1]))),$keyCompuesta);
        $r = base64_encode($cadenaEncriptada);
        $r = str_replace("=","2018sieIgual2018",$r);
        return $r;
    }
    
    public function global_desencriptaCadena($a_valor)
    {
        $a_valor=str_replace("2018sieIgual2018","=",$a_valor);
        $a_valor=base64_decode($a_valor);
        $a_valor=($a_valor);
        if($this->clg=="")
        {
            $this->clg=$this->dameColegio(); 
        }
        //luism:Emilio 20170608(27133) No codificar la cadena a encriptar utf8_decode, los modulos tienen su manejo de string independiente
        $cadenaEncriptada="";
        $keyCompuesta="".$this->clg.$this->keyEncriptaColegio;
        $cadenaEncriptada=trim($this->global_desencripta(rtrim(ltrim(($a_valor))),$keyCompuesta));
        //echo substr($cadenaEncriptada,0,strlen($this->arrEnvolturaEncriptacion[0]))."__".$this->arrEnvolturaEncriptacion[0]."==".(substr($cadenaEncriptada,0,strlen($this->arrEnvolturaEncriptacion[0])) == $this->arrEnvolturaEncriptacion[0]?"ok":"false");
        if(substr($cadenaEncriptada,0,strlen($this->arrEnvolturaEncriptacion[0])) == $this->arrEnvolturaEncriptacion[0] &&
            substr($cadenaEncriptada,(strlen($this->arrEnvolturaEncriptacion[1])*-1)) == $this->arrEnvolturaEncriptacion[1])
        {
          //SE DESENCRIPTO BIEN
          $cadenaEncriptada=str_replace($this->arrEnvolturaEncriptacion,"",$cadenaEncriptada);
        }
        else
        {
            $cadenaEncriptada="";
        }
        return $cadenaEncriptada;
    }
    
    public function global_dameIsUniversidad($a_nivelEscolarId)
    {
        $isUniversidad=false;
        $this->sql = "select dame_parametro('PortalUnivesidadNivelEstudios') as idNivelesUniversidad;";
        $rsl = bd_consultaSQL($this->sql);
        $this->defineErrorConsulta($resParam,"Error al recuperar la información del parámetro.");

        if(!$this->error)
        {
            if(bd_cuentaRegistros($rsl)==1)
            {
                $filaPortal = bd_dameRegistro($rsl);
                $arrNivelEstudios=explode(',', $filaPortal['idNivelesUniversidad']);
                if(in_array($a_nivelEscolarId, $arrNivelEstudios))
                {
                    $isUniversidad=true;
                }
            }
        }
        return $isUniversidad;
    }
}
?>
