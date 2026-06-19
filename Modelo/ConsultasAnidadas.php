<?php
class consultas{
    public function __construct()
    {
        
    }
    
    public function listarCentro($Estado)
    {
            $sql = "SELECT centrooperativo.*, regional.NombreRegional  FROM centrooperativo INNER JOIN regional on regional.IDRegional=centrooperativo.IDRegionalCentroOP WHERE centrooperativo.EstadoCentroOP=$Estado";
            return ejecutarConsulta($sql);
    }
	
	
	    public function listarContrato($Estado)
    {
			if($Estado==1){
			 $sql = "SELECT contrato.*,detallecontrato.NoOS, uen.NombreUen,clientes.NombreCliente,empresas.NombreEmpresa,estadoContrato.nombreEstado FROM contrato INNER JOIN detallecontrato on detallecontrato.IDContratoDetalle=contrato.IDcontrato INNER join uen on uen.IDUen=contrato.IDUenContratro INNER JOIN empresas on empresas.IDempresa=contrato.IDEmpresaContrato INNER JOIN clientes on clientes.IDClinente=contrato.IDClienteContrato INNER JOIN estadoContrato on estadoContrato.Idestado=contrato.idestadocontrato WHERE contrato.idestadocontrato=1";
            return ejecutarConsulta($sql);	
			}else{
			$sql = "SELECT contrato.*,detallecontrato.NoOS, uen.NombreUen,clientes.NombreCliente,empresas.NombreEmpresa,estadoContrato.nombreEstado FROM contrato INNER JOIN detallecontrato on detallecontrato.IDContratoDetalle=contrato.IDcontrato INNER join uen on uen.IDUen=contrato.IDUenContratro INNER JOIN empresas on empresas.IDempresa=contrato.IDEmpresaContrato INNER JOIN clientes on clientes.IDClinente=contrato.IDClienteContrato INNER JOIN estadoContrato on estadoContrato.Idestado=contrato.idestadocontrato WHERE not contrato.idestadocontrato=1";
            return ejecutarConsulta($sql);		
			}
           
    }
	
 public function listarEjecucionContrato($Estado)
    {
            $sql = "SELECT ejecucioncontrato.*, contrato.NombreContrato,centrooperativo.NombreCentroOP FROM ejecucioncontrato INNER JOIN contrato on contrato.IDcontrato=ejecucioncontrato.IdContratoEjecuCicion INNER join centrooperativo on centrooperativo.IDCentroOP=ejecucioncontrato.IDCentroOPEjecucion WHERE ejecucioncontrato.EstadoEjecucion=$Estado";
            return ejecutarConsulta($sql);
    }
	
	
	 public function listarEjecucionContratoAsis($Estado)
    {
            $sql = "SELECT EJECUCION_CONTRATO_SER_ASIS.*, contrato.NombreContrato,centrooperativo.NombreCentroOP FROM EJECUCION_CONTRATO_SER_ASIS INNER JOIN contrato on contrato.IDcontrato=EJECUCION_CONTRATO_SER_ASIS.ID_CONTRATO INNER join centrooperativo on centrooperativo.IDCentroOP=EJECUCION_CONTRATO_SER_ASIS.ID_CENTROPEATIVO WHERE EJECUCION_CONTRATO_SER_ASIS.ESTADO_EJECICION_ASIS=$Estado";
            return ejecutarConsulta($sql);
    }
	
	public function selectCategoria($contrto,$fecha)
    {
            $sql = "SELECT CATEGORIA.* FROM CATEGORIA WHERE CATEGORIA.ESTADO_CATEGORIA=1 and NOT EXISTS (SELECT null FROM DETALLE_EJECUCION_PEAJES WHERE DETALLE_EJECUCION_PEAJES.ID_EJECUCION_CONTRATO=$contrto and DETALLE_EJECUCION_PEAJES.FEC_DET_EJE_PEAJE='$fecha' AND DETALLE_EJECUCION_PEAJES.CATEGORIA=CATEGORIA.IDCATEGORIA)";
            return ejecutarConsulta($sql);
    }
	
		public function selectFlotaVh()
    {
            $sql = "SELECT VEHICULOS.* from VEHICULOS WHERE VEHICULOS.ESTADO_VEHICULO=1 and NOT EXISTS(SELECT NULL FROM DISPONIBILIDAD_FLOTA WHERE DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS=VEHICULOS.ID_VEH AND DISPONIBILIDAD_FLOTA.ESTADO_DIS=1)";
            return ejecutarConsulta($sql);
    }
	
	public function selectCategoria2()
    {
            $sql = "SELECT CATEGORIA.* FROM CATEGORIA WHERE CATEGORIA.ESTADO_CATEGORIA=1";
            return ejecutarConsulta($sql);
    }
	
	public function selectContrato()
    {
            $sql = "SELECT ejecucioncontrato.IDEjecucion,centrooperativo.NombreCentroOP FROM ejecucioncontrato INNER JOIN centrooperativo on centrooperativo.IDCentroOP=ejecucioncontrato.IDCentroOPEjecucion WHERE ejecucioncontrato.EstadoEjecucion=1 and centrooperativo.TipoCentroOP='PEAJE'";
            return ejecutarConsulta($sql);
    }
	
	public function selectContrato2()
    {
            $sql = "SELECT EJECUCION_CONTRATO_SER_ASIS.ID_EJE_CON_SER_ASIS, centrooperativo.NombreCentroOP FROM EJECUCION_CONTRATO_SER_ASIS INNER JOIN centrooperativo on centrooperativo.IDCentroOP=EJECUCION_CONTRATO_SER_ASIS.ID_CENTROPEATIVO WHERE EJECUCION_CONTRATO_SER_ASIS.ESTADO_EJECICION_ASIS=1";
            return ejecutarConsulta($sql);
    }
	
	
	public function listarEjecucionpeaje($Estado,$centro,$fechai,$fechaf)
    {
		if((($centro=="null" or $centro=="") and $fechai=="" and $fechaf=="")){
			if($Estado==1){
			 $sql = "SELECT DETALLE_EJECUCION_PEAJES.*,CATEGORIA.NOMBRE_CATEGORIA,centrooperativo.NombreCentroOP FROM DETALLE_EJECUCION_PEAJES INNER JOIN CATEGORIA ON CATEGORIA.IDCATEGORIA=DETALLE_EJECUCION_PEAJES.CATEGORIA INNER JOIN ejecucioncontrato on ejecucioncontrato.IDEjecucion=DETALLE_EJECUCION_PEAJES.ID_EJECUCION_CONTRATO INNER JOIN centrooperativo on centrooperativo.IDCentroOP=ejecucioncontrato.IDCentroOPEjecucion WHERE DETALLE_EJECUCION_PEAJES.ESTADO_JECUCION=$Estado ORDER BY DETALLE_EJECUCION_PEAJES.IDEJECUCIONPEAJE  DESC limit 600";
		  }else{
			 $sql = "SELECT DETALLE_EJECUCION_PEAJES.*,CATEGORIA.NOMBRE_CATEGORIA,centrooperativo.NombreCentroOP FROM DETALLE_EJECUCION_PEAJES INNER JOIN CATEGORIA ON CATEGORIA.IDCATEGORIA=DETALLE_EJECUCION_PEAJES.CATEGORIA INNER JOIN ejecucioncontrato on ejecucioncontrato.IDEjecucion=DETALLE_EJECUCION_PEAJES.ID_EJECUCION_CONTRATO INNER JOIN centrooperativo on centrooperativo.IDCentroOP=ejecucioncontrato.IDCentroOPEjecucion WHERE not DETALLE_EJECUCION_PEAJES.ESTADO_JECUCION=1 ORDER BY DETALLE_EJECUCION_PEAJES.IDEJECUCIONPEAJE  DESC limit 600";
		  }
	    }else{
			
		if($Estado==1){
			 $sql = "SELECT DETALLE_EJECUCION_PEAJES.*,CATEGORIA.NOMBRE_CATEGORIA,centrooperativo.NombreCentroOP FROM DETALLE_EJECUCION_PEAJES INNER JOIN CATEGORIA ON CATEGORIA.IDCATEGORIA=DETALLE_EJECUCION_PEAJES.CATEGORIA INNER JOIN ejecucioncontrato on ejecucioncontrato.IDEjecucion=DETALLE_EJECUCION_PEAJES.ID_EJECUCION_CONTRATO INNER JOIN centrooperativo on centrooperativo.IDCentroOP=ejecucioncontrato.IDCentroOPEjecucion WHERE DETALLE_EJECUCION_PEAJES.ESTADO_JECUCION=$Estado and IDEjecucion=$centro and DETALLE_EJECUCION_PEAJES.FEC_DET_EJE_PEAJE BETWEEN '$fechai' and '$fechaf' ORDER BY DETALLE_EJECUCION_PEAJES.IDEJECUCIONPEAJE  DESC limit 600";
		  }else{
			 $sql = "SELECT DETALLE_EJECUCION_PEAJES.*,CATEGORIA.NOMBRE_CATEGORIA,centrooperativo.NombreCentroOP FROM DETALLE_EJECUCION_PEAJES INNER JOIN CATEGORIA ON CATEGORIA.IDCATEGORIA=DETALLE_EJECUCION_PEAJES.CATEGORIA INNER JOIN ejecucioncontrato on ejecucioncontrato.IDEjecucion=DETALLE_EJECUCION_PEAJES.ID_EJECUCION_CONTRATO INNER JOIN centrooperativo on centrooperativo.IDCentroOP=ejecucioncontrato.IDCentroOPEjecucion WHERE IDEjecucion=$centro and DETALLE_EJECUCION_PEAJES.FEC_DET_EJE_PEAJE BETWEEN '$fechai' and '$fechaf' and not DETALLE_EJECUCION_PEAJES.ESTADO_JECUCION=1 ORDER BY DETALLE_EJECUCION_PEAJES.IDEJECUCIONPEAJE  DESC limit 600";
		  }	
			
		}
		
           
            return ejecutarConsulta($sql);
    }
	
	
		public function listarEjecucionAsistecial($Estado,$centro,$fechai,$fechaf)
    {
		if((($centro=="null" or $centro=="") and $fechai=="" and $fechaf=="")){
			if($Estado==1){
			 $sql = "SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP, TIPO_VEHICULOS.NOM_TIPO_VEHICULO,tipo_evento_asistencial.NOMBRE_TIPO_EVENTO FROM servicio_asistencial
INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO
INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO
INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO
where servicio_asistencial.ESTADO_SERVICO=1";
		  }else{
			 $sql = "SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP, TIPO_VEHICULOS.NOM_TIPO_VEHICULO,tipo_evento_asistencial.NOMBRE_TIPO_EVENTO FROM servicio_asistencial
INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO
INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO
INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO
where not servicio_asistencial.ESTADO_SERVICO=1";
		  }
	    }else if(($centro=="null" or $centro=="") and $fechai!="" and $fechaf!=""){
			
		if($Estado==1){
			 $sql = "SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP, TIPO_VEHICULOS.NOM_TIPO_VEHICULO,tipo_evento_asistencial.NOMBRE_TIPO_EVENTO FROM servicio_asistencial
INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO
INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO
INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO
where servicio_asistencial.ESTADO_SERVICO=1 AND servicio_asistencial.FECHA_SERVICIO BETWEEN '$fechai' and '$fechaf'";
		  }else{
			 $sql = "SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP, TIPO_VEHICULOS.NOM_TIPO_VEHICULO,tipo_evento_asistencial.NOMBRE_TIPO_EVENTO FROM servicio_asistencial
INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO
INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO
INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO
where not servicio_asistencial.ESTADO_SERVICO=1 AND servicio_asistencial.FECHA_SERVICIO BETWEEN '$fechai' and '$fechaf'";
		  }	
			
		}else{
            
            if($Estado==1){
			 $sql = "SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP, TIPO_VEHICULOS.NOM_TIPO_VEHICULO,tipo_evento_asistencial.NOMBRE_TIPO_EVENTO FROM servicio_asistencial
INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO
INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO
INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO
where servicio_asistencial.ESTADO_SERVICO=1 and servicio_asistencial.CENTRO_SERVICIO=$centro AND servicio_asistencial.FECHA_SERVICIO BETWEEN '$fechai' and '$fechaf'";
		  }else{
			 $sql = "SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP, TIPO_VEHICULOS.NOM_TIPO_VEHICULO,tipo_evento_asistencial.NOMBRE_TIPO_EVENTO FROM servicio_asistencial
INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO
INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO
INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO
where not servicio_asistencial.ESTADO_SERVICO=1 and servicio_asistencial.CENTRO_SERVICIO=$centro AND servicio_asistencial.FECHA_SERVICIO BETWEEN '$fechai' and '$fechaf'";
		  }	
            
        }
		
         return ejecutarConsulta($sql);  
    }
	
	
	
	
	
	public function validar($contrato,$categoria,$fecha)
    {
            $sql = "SELECT * FROM DETALLE_EJECUCION_PEAJES WHERE DETALLE_EJECUCION_PEAJES.ID_EJECUCION_CONTRATO=$contrato and DETALLE_EJECUCION_PEAJES.CATEGORIA=$categoria and DETALLE_EJECUCION_PEAJES.FEC_DET_EJE_PEAJE='$fecha'";
            return ejecutarConsulta($sql);
    }
	
	public function validar2($contrato,$VEHICULO,$fecha)
    {
            $sql = "SELECT DETALLE_EJECUCION_SERVICIOS_ASISTENCIALES.ID_DET_EJE_SER_FLO FROM DETALLE_EJECUCION_SERVICIOS_ASISTENCIALES WHERE FEC_DET_EJE_SERASIS='$fecha' AND IDVEH_DETALLE='$VEHICULO' AND ID_EJECUCION_CONTRATO='$contrato'";
            return ejecutarConsulta($sql);
    }
	
	
	public function validarFlota($VEHICULO,$fecha)
    {
            $sql = "SELECT DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS FROM DISPONIBILIDAD_FLOTA WHERE DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS=$VEHICULO and ('$fecha'>=DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS and ('$fecha'<DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS AND NOT DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS=''))";
            return ejecutarConsulta($sql);
    }
	
	
	public function contratosdash($idproyecto)
    {
$sql = "SELECT contrato.IDcontrato,contrato.NombreContrato,estadoContrato.nombreEstado,clientes.NombreCliente,
clientes.IDClinente,tipoclientes.NombreTipoCliente,empresas.NombreEmpresa,
detallecontrato.ObjetoContrato,clientes.IDClinente,clientes.AdministradorCliente,empresas.AdministradorEmpresa,
detallecontrato.ValorMensualContrato,detallecontrato.ValorTotalContrato,detallecontrato.FechaInicio,
detallecontrato.fechaFinal,detallecontrato.TipoTarifa,detallecontrato.NoOS FROM contrato 
INNER JOIN estadoContrato on estadoContrato.Idestado =contrato.idestadocontrato 
INNER JOIN Proyectos on Proyectos.IDcontratoProyecto=contrato.IDcontrato 
INNER JOIN detallecontrato on detallecontrato.IDContratoDetalle=contrato.IDcontrato 
INNER JOIN empresas on empresas.IDempresa=contrato.IDEmpresaContrato 
INNER JOIN clientes on clientes.IDClinente=contrato.IDClienteContrato 
INNER JOIN tipoclientes on tipoclientes.IDTipoCliente=clientes.TipoCliente 
WHERE Proyectos.Idproyctos=$idproyecto";
return ejecutarConsultaSimpleFila($sql);
}
	
  public function socios($cliente)
    {
            $sql = "SELECT socios.NitSocio,socios.NombreSocio FROM clientessocios INNER JOIN socios on socios.IDSocio=clientessocios.IDSocioU WHERE clientessocios.IDClienteU=$cliente and socios.EstadoSocio=1";
            return ejecutarConsulta($sql);
    }	
	
public function detallecontrato($id)
    {
            $sql = "SELECT contrato.*, detallecontrato.* FROM contrato inner join detallecontrato on detallecontrato.IDContratoDetalle=contrato.IDcontrato WHERE contrato.IDcontrato=$id";
            return ejecutarConsultaSimpleFila($sql);
    }	
	
    public function listarClientes($estado)
    {
            $sql = "SELECT clientes.*,tipoclientes.NombreTipoCliente FROM clientes INNER JOIN tipoclientes on tipoclientes.IDTipoCliente=clientes.TipoCliente WHERE clientes.EtadoCliente=$estado";
            return ejecutarConsulta($sql);
    }	
		
	 public function listarProyectos($estado)
    {
            $sql = "SELECT Proyectos.*, contrato.NombreContrato FROM Proyectos INNER JOIN contrato on contrato.IDcontrato=Proyectos.IDcontratoProyecto WHERE Proyectos.estadoProyecto=$estado";
            return ejecutarConsulta($sql);
    }	
	
	
	 public function listarVehiculo($estado)
    {
            $sql = "SELECT VEHICULOS.*,TIPO_VEHICULOS.NOM_TIPO_VEHICULO FROM VEHICULOS INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH WHERE VEHICULOS.ESTADO_VEHICULO=$estado";
            return ejecutarConsulta($sql);
    }
	
	 public function listarNovedadesFlota($estado)
    {
            $sql = "SELECT NOVEDAD_FLOTA.*, VEHICULOS.PLACA_VEH, SISTEMA_AFECTADO.NOMBRE_SISTEMA_AFECTADO from NOVEDAD_FLOTA INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=NOVEDAD_FLOTA.ID_VEHICULO_NOVEDAD INNER JOIN SISTEMA_AFECTADO on SISTEMA_AFECTADO.ID_SISTEMA_AFECTADO=NOVEDAD_FLOTA.SISTEMA  WHERE NOVEDAD_FLOTA.ESTADO_NOVEDADA=$estado";
            return ejecutarConsulta($sql);
    }
	
	public function listarFlota($Estado,$proyecto)
    {
		if($proyecto=="null" or $proyecto==""){
			if($Estado==1){
			 $sql = "SELECT DISPONIBILIDAD_FLOTA.*,Proyectos.nombreProyecto, VEHICULOS.PLACA_VEH, TIPO_VEHICULOS.NOM_TIPO_VEHICULO FROM DISPONIBILIDAD_FLOTA INNER JOIN Proyectos on Proyectos.Idproyctos=DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS INNER JOIN  VEHICULOS on VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH WHERE DISPONIBILIDAD_FLOTA.ESTADO_DIS=1";
		  }else{
			 $sql = "SELECT DISPONIBILIDAD_FLOTA.*,Proyectos.nombreProyecto, VEHICULOS.PLACA_VEH, TIPO_VEHICULOS.NOM_TIPO_VEHICULO FROM DISPONIBILIDAD_FLOTA INNER JOIN Proyectos on Proyectos.Idproyctos=DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS INNER JOIN  VEHICULOS on VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH WHERE DISPONIBILIDAD_FLOTA.ESTADO_DIS=0";
		  }
	    }else{
			
		if($Estado==1){
			 $sql = "SELECT DISPONIBILIDAD_FLOTA.*,Proyectos.nombreProyecto, VEHICULOS.PLACA_VEH, TIPO_VEHICULOS.NOM_TIPO_VEHICULO FROM DISPONIBILIDAD_FLOTA INNER JOIN Proyectos on Proyectos.Idproyctos=DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS INNER JOIN  VEHICULOS on VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH WHERE DISPONIBILIDAD_FLOTA.ESTADO_DIS=1 and DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto";
		  }else{
			 $sql = "SELECT DISPONIBILIDAD_FLOTA.*,Proyectos.nombreProyecto, VEHICULOS.PLACA_VEH, TIPO_VEHICULOS.NOM_TIPO_VEHICULO FROM DISPONIBILIDAD_FLOTA INNER JOIN Proyectos on Proyectos.Idproyctos=DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS INNER JOIN  VEHICULOS on VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH WHERE DISPONIBILIDAD_FLOTA.ESTADO_DIS=0 and DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto";
		  }	
			
		}
		
         return ejecutarConsulta($sql);  
    }
	
	
	public function Flotaactivos($proyecto,$fechafinal)
    {
            $sql = "SELECT DISPONIBILIDAD_FLOTA.*, VEHICULOS.TIPO_VEH FROM DISPONIBILIDAD_FLOTA INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS WHERE DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto AND '$fechafinal'>=DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS AND DISPONIBILIDAD_FLOTA.ESTADO_DIS=1";
            return ejecutarConsulta($sql);
    }
	
	public function Flota2($proyecto,$fechainicio,$fechafinal)
    {
            $sql = "SELECT DISPONIBILIDAD_FLOTA.*, VEHICULOS.TIPO_VEH  FROM DISPONIBILIDAD_FLOTA INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS WHERE DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto and DISPONIBILIDAD_FLOTA.ESTADO_DIS=0 and '$fechainicio' BETWEEN DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS and DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS and not '$fechafinal' BETWEEN DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS and DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS";
            return ejecutarConsulta($sql);
    }
	
	public function Flota3($proyecto,$fechainicio,$fechafinal)
    {
            $sql = "SELECT DISPONIBILIDAD_FLOTA.*, VEHICULOS.TIPO_VEH  FROM DISPONIBILIDAD_FLOTA INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS WHERE DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto and DISPONIBILIDAD_FLOTA.ESTADO_DIS=0 and '$fechafinal' BETWEEN DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS and DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS";
            return ejecutarConsulta($sql);
    }
	
	
	
	public function minutosNovedades($proyecto,$fechainicial,$fechafinal)
    {
		if($fechainicial==$fechafinal){
			$sql="SELECT NOVEDAD_FLOTA.*,DISPONIBILIDAD_FLOTA.*,VEHICULOS.TIPO_VEH FROM NOVEDAD_FLOTA INNER JOIN VEHICULOS ON VEHICULOS.ID_VEH=NOVEDAD_FLOTA.ID_VEHICULO_NOVEDAD inner JOIN DISPONIBILIDAD_FLOTA on DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS=VEHICULOS.ID_VEH WHERE NOVEDAD_FLOTA.ESTADO_NOVEDADA=1 and DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto AND (((NOVEDAD_FLOTA.FECHA_HORA_INICIO BETWEEN CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00') and CONCAT(DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS,' ','23:59:59') and not DISPONIBILIDAD_FLOTA.ESTADO_DIS=1) or ((NOVEDAD_FLOTA.FECHA_HORA_FIN BETWEEN CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00') and CONCAT(DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS,' ','23:59:59') and not DISPONIBILIDAD_FLOTA.ESTADO_DIS=1) or (DISPONIBILIDAD_FLOTA.ESTADO_DIS=1 and  NOVEDAD_FLOTA.FECHA_HORA_FIN>CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00')))) and (('$fechainicial 00:00:00' BETWEEN NOVEDAD_FLOTA.FECHA_HORA_INICIO  and NOVEDAD_FLOTA.FECHA_HORA_FIN) or NOVEDAD_FLOTA.FECHA_HORA_INICIO BETWEEN '$fechainicial 00:00:00' and '$fechafinal 23:59:59') and not ('$fechafinal 00:00:00'  BETWEEN NOVEDAD_FLOTA.FECHA_HORA_INICIO and NOVEDAD_FLOTA.FECHA_HORA_FIN))";
		}else{
			$sql="SELECT NOVEDAD_FLOTA.*,DISPONIBILIDAD_FLOTA.*,VEHICULOS.TIPO_VEH FROM NOVEDAD_FLOTA INNER JOIN VEHICULOS ON VEHICULOS.ID_VEH=NOVEDAD_FLOTA.ID_VEHICULO_NOVEDAD inner JOIN DISPONIBILIDAD_FLOTA on DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS=VEHICULOS.ID_VEH WHERE NOVEDAD_FLOTA.ESTADO_NOVEDADA=1 and DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto AND (((NOVEDAD_FLOTA.FECHA_HORA_INICIO BETWEEN CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00') and CONCAT(DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS,' ','23:59:59') and not DISPONIBILIDAD_FLOTA.ESTADO_DIS=1) or ((NOVEDAD_FLOTA.FECHA_HORA_FIN BETWEEN CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00') and CONCAT(DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS,' ','23:59:59') and not DISPONIBILIDAD_FLOTA.ESTADO_DIS=1) or (DISPONIBILIDAD_FLOTA.ESTADO_DIS=1 and  NOVEDAD_FLOTA.FECHA_HORA_FIN>CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00')))) and (('$fechafinal 00:00:00'>NOVEDAD_FLOTA.FECHA_HORA_FIN and '$fechainicial 00:00:00' BETWEEN NOVEDAD_FLOTA.FECHA_HORA_INICIO  and NOVEDAD_FLOTA.FECHA_HORA_FIN and not (NOVEDAD_FLOTA.FECHA_HORA_FIN BETWEEN '$fechainicial 00:00:00' and '$fechafinal 23:59:59')) or ( NOVEDAD_FLOTA.FECHA_HORA_FIN>'$fechafinal 23:59:59' and NOVEDAD_FLOTA.FECHA_HORA_INICIO BETWEEN '$fechainicial 00:00:00' and '$fechafinal 23:59:59')))";
		}
            
            return ejecutarConsulta($sql);
    }
	
  public function minutosNovedades2($proyecto,$fechainicial,$fechafinal)
    {
	      if($fechainicial==$fechafinal){
			  $sql="SELECT NOVEDAD_FLOTA.*,DISPONIBILIDAD_FLOTA.*,VEHICULOS.TIPO_VEH FROM NOVEDAD_FLOTA INNER JOIN VEHICULOS ON VEHICULOS.ID_VEH=NOVEDAD_FLOTA.ID_VEHICULO_NOVEDAD inner JOIN DISPONIBILIDAD_FLOTA on DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS=VEHICULOS.ID_VEH WHERE NOVEDAD_FLOTA.ESTADO_NOVEDADA=1 and DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto AND (((NOVEDAD_FLOTA.FECHA_HORA_INICIO BETWEEN CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00') and CONCAT(DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS,' ','23:59:59') and not DISPONIBILIDAD_FLOTA.ESTADO_DIS=1) or ((NOVEDAD_FLOTA.FECHA_HORA_FIN BETWEEN CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00') and CONCAT(DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS,' ','23:59:59') and not DISPONIBILIDAD_FLOTA.ESTADO_DIS=1) or (DISPONIBILIDAD_FLOTA.ESTADO_DIS=1 and  NOVEDAD_FLOTA.FECHA_HORA_FIN>CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00')))) and ('$fechafinal 00:00:00'  BETWEEN NOVEDAD_FLOTA.FECHA_HORA_INICIO and NOVEDAD_FLOTA.FECHA_HORA_FIN))";
		  }else{
			$sql="SELECT NOVEDAD_FLOTA.*,DISPONIBILIDAD_FLOTA.*,VEHICULOS.TIPO_VEH FROM NOVEDAD_FLOTA INNER JOIN VEHICULOS ON VEHICULOS.ID_VEH=NOVEDAD_FLOTA.ID_VEHICULO_NOVEDAD inner JOIN DISPONIBILIDAD_FLOTA on DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS=VEHICULOS.ID_VEH WHERE NOVEDAD_FLOTA.ESTADO_NOVEDADA=1 and DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto AND (((NOVEDAD_FLOTA.FECHA_HORA_INICIO BETWEEN CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00') and CONCAT(DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS,' ','23:59:59') and not DISPONIBILIDAD_FLOTA.ESTADO_DIS=1) or ((NOVEDAD_FLOTA.FECHA_HORA_FIN BETWEEN CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00') and CONCAT(DISPONIBILIDAD_FLOTA.FECHA_FIN_DIS,' ','23:59:59') and not DISPONIBILIDAD_FLOTA.ESTADO_DIS=1) or (DISPONIBILIDAD_FLOTA.ESTADO_DIS=1 and  NOVEDAD_FLOTA.FECHA_HORA_FIN>CONCAT(DISPONIBILIDAD_FLOTA.FECHA_INICIO_DIS,' ','00:00:00')))) and (('$fechafinal 00:00:00'  BETWEEN NOVEDAD_FLOTA.FECHA_HORA_INICIO and NOVEDAD_FLOTA.FECHA_HORA_FIN and not (NOVEDAD_FLOTA.FECHA_HORA_INICIO BETWEEN '$fechainicial 00:00:00' and '$fechafinal 23:59:59')) or (NOVEDAD_FLOTA.FECHA_HORA_FIN BETWEEN '$fechainicial 00:00:00' and '$fechafinal 23:59:59')))";  
		  }
            return ejecutarConsulta($sql);
    }	
	
	public function listraNovedadesreporte($condicional)
    {
            $sql = "SELECT NOVEDAD_FLOTA.*, VEHICULOS.PLACA_VEH, TIPO_VEHICULOS.NOM_TIPO_VEHICULO, SISTEMA_AFECTADO.NOMBRE_SISTEMA_AFECTADO FROM NOVEDAD_FLOTA INNER JOIN SISTEMA_AFECTADO on SISTEMA_AFECTADO.ID_SISTEMA_AFECTADO=NOVEDAD_FLOTA.SISTEMA INNER JOIN VEHICULOS ON VEHICULOS.ID_VEH=NOVEDAD_FLOTA.ID_VEHICULO_NOVEDAD inner JOIN TIPO_VEHICULOS ON TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH WHERE  $condicional ";
            return ejecutarConsulta($sql);
    }
	
	public function listarexperiencia($condicional)
    {
            $sql = "SELECT contrato.*,detallecontrato.*, clientes.NombreCliente, uen.NombreUen FROM contrato INNER JOIN uen on uen.IDUen=contrato.IDUenContratro INNER JOIN detallecontrato on detallecontrato.IDContratoDetalle=contrato.IDcontrato INNER JOIN clientes on clientes.IDClinente=contrato.IDClienteContrato WHERE $condicional";
            return ejecutarConsulta($sql);
    }
	
	public function cantidadflota()
    {
            $sql = "SELECT  Proyectos.Idproyctos,Proyectos.nombreProyecto,COUNT(DISPONIBILIDAD_FLOTA.ID_DISPONIBILIDAD) AS CANTIDAD FROM DISPONIBILIDAD_FLOTA 
           INNER JOIN Proyectos ON  Proyectos.Idproyctos=DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS WHERE   DISPONIBILIDAD_FLOTA.ESTADO_DIS=1
           GROUP BY DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS ORDER BY Proyectos.nombreProyecto ASC";
            return ejecutarConsulta($sql);
    }
	
	public function cantidadflotaAD($proyecto)
    {
            $sql = "SELECT COUNT(VEHICULOS.ID_VEH) AS CANTIDADAD FROM DISPONIBILIDAD_FLOTA INNER JOIN VEHICULOS ON VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS WHERE DISPONIBILIDAD_FLOTA.ESTADO_DIS=1 AND VEHICULOS.TIPO_VEH=1 AND DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$proyecto";
            return ejecutarConsultaSimpleFila($sql);
    }
	
	public function listarCombustible($estado)
    {
            $sql = "SELECT COMBUSTIBLE.*,Proyectos.nombreProyecto, VEHICULOS.PLACA_VEH  FROM COMBUSTIBLE INNER JOIN Proyectos on Proyectos.Idproyctos=COMBUSTIBLE.PROYECTO_COMBUSTIBLE INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=COMBUSTIBLE.IDVEHUCULOCOMBUSTIBLE WHERE COMBUSTIBLE.ESTADO_COMBUSTIBLE=".$estado;
            return ejecutarConsulta($sql);
    }
	
	public function listarCombustiblegrafica($mes,$ano)
    {
            $sql = "SELECT COMBUSTIBLE.* FROM COMBUSTIBLE  WHERE  COMBUSTIBLE.ANO_COMBUSTIBLE='$ano' and COMBUSTIBLE.MES_COMBUSTIBLE='$mes' and COMBUSTIBLE.ESTADO_COMBUSTIBLE=1";
            return ejecutarConsulta($sql);
    }
	
	public function listarCombustibleTipo($mes,$ano,$tipo)
    {
            $sql = "SELECT COMBUSTIBLE.*, TIPO_VEHICULOS.NOM_TIPO_VEHICULO FROM COMBUSTIBLE INNER JOIN VEHICULOS ON VEHICULOS.ID_VEH=COMBUSTIBLE.IDVEHUCULOCOMBUSTIBLE INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH  WHERE  COMBUSTIBLE.ANO_COMBUSTIBLE='$ano' and COMBUSTIBLE.MES_COMBUSTIBLE='$mes' and COMBUSTIBLE.ESTADO_COMBUSTIBLE=1 and TIPO_VEHICULOS.ID_TIP_VEH=$tipo";
            return ejecutarConsulta($sql);
    }
	
	public function listarCombustibleProyecto($mes,$ano,$proyecto)
    {
            $sql = "SELECT COMBUSTIBLE.*, Proyectos.nombreProyecto FROM COMBUSTIBLE INNER JOIN Proyectos ON Proyectos.Idproyctos=COMBUSTIBLE.PROYECTO_COMBUSTIBLE WHERE  COMBUSTIBLE.ANO_COMBUSTIBLE='$ano' and COMBUSTIBLE.MES_COMBUSTIBLE='$mes' and COMBUSTIBLE.ESTADO_COMBUSTIBLE=1 and Proyectos.Idproyctos=$proyecto";
            return ejecutarConsulta($sql);
    }
	
	public function listarflotaCombustible($ano)
    {
            $sql = "SELECT COMBUSTIBLE.*,VEHICULOS.PLACA_VEH, TIPO_VEHICULOS.NOM_TIPO_VEHICULO, Proyectos.nombreProyecto FROM COMBUSTIBLE INNER JOIN Proyectos ON Proyectos.Idproyctos=COMBUSTIBLE.PROYECTO_COMBUSTIBLE INNER JOIN VEHICULOS ON VEHICULOS.ID_VEH=COMBUSTIBLE.IDVEHUCULOCOMBUSTIBLE INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH  WHERE  COMBUSTIBLE.ANO_COMBUSTIBLE='$ano' and  COMBUSTIBLE.ESTADO_COMBUSTIBLE=1;";
            return ejecutarConsulta($sql);
    }
	
	
	public function TipoVehiculos()
    {
            $sql = "SELECT TIPO_VEHICULOS.* FROM TIPO_VEHICULOS WHERE EXISTS (SELECT NULl FROM VEHICULOS inner join COMBUSTIBLE on COMBUSTIBLE.IDVEHUCULOCOMBUSTIBLE=VEHICULOS.ID_VEH WHERE VEHICULOS.TIPO_VEH=TIPO_VEHICULOS.ID_TIP_VEH and ESTADO_VEHICULO=1) ORDER BY TIPO_VEHICULOS.ID_TIP_VEH ASC;";
            return ejecutarConsulta($sql);
    }
	
	public function ProyectosCombustible()
    {
            $sql = "SELECT Proyectos.* FROM Proyectos WHERE EXISTS(SELECT null from COMBUSTIBLE WHERE COMBUSTIBLE.PROYECTO_COMBUSTIBLE=Proyectos.Idproyctos) ORDER BY Proyectos.Idproyctos ASC";
            return ejecutarConsulta($sql);
    }
	
	public function vehiculosAfectados($fechahoy)
    {
            $sql = "SELECT NOVEDAD_FLOTA.* FROM NOVEDAD_FLOTA WHERE NOVEDAD_FLOTA.FECHA_HORA_FIN>'$fechahoy'";
            return ejecutarConsulta($sql);
    }
	
	public function Mostraranotas($idnovedad)
    {
            $sql = "SELECT NOTAS_NOVEDADES.* FROM NOTAS_NOVEDADES WHERE NOTAS_NOVEDADES.ID_NOVEDAD_NOTA=$idnovedad ORDER BY NOTAS_NOVEDADES.IDNOTAS ASC";
            return ejecutarConsulta($sql);
    }
	
	public function conteootrosi($contrato)
    {
            $sql = "SELECT COUNT(OTROSICONTRATO.IDOTROSI) as cantidad FROM OTROSICONTRATO WHERE OTROSICONTRATO.IDCONTRATO_OTS=$contrato";
            return ejecutarConsultaSimpleFila($sql);
    }
	
	
	public function mostrarotrosi($contrato)
    {
            $sql = "SELECT OTROSICONTRATO.* FROM OTROSICONTRATO WHERE OTROSICONTRATO.IDCONTRATO_OTS=$contrato ORDER BY OTROSICONTRATO.IDOTROSI ASC";
            return ejecutarConsulta($sql);
    }
	
	public function mostrarNovedadetalle($id)
    {
            $sql = "SELECT DETALLE_NOVEDAD.* FROM DETALLE_NOVEDAD WHERE DETALLE_NOVEDAD.IDNOVEDAD_DETALLE=$id ORDER BY DETALLE_NOVEDAD.IDDETALLENOVEDAD ASC";
            return ejecutarConsulta($sql);
    }
	
	public function listarnovedadesr($estado)
    {
            $sql = "SELECT NOVEDADES.*, Proyectos.nombreProyecto,uen.NombreUen FROM NOVEDADES INNER JOIN Proyectos on Proyectos.Idproyctos=NOVEDADES.PROYECTONOVEDAD INNER JOIN uen on uen.IDUen=NOVEDADES.UENNOVEDAD WHERE NOVEDADES.ESTADO_NOVEDAD=$estado";
            return ejecutarConsulta($sql);
    }
	
	
	public function NovevdadesRegistro($fechai,$fechaf,$proyecto)
    {
            $sql = "SELECT * FROM NOVEDADES WHERE NOVEDADES.PROYECTONOVEDAD=$proyecto and (((('$fechai' BETWEEN NOVEDADES.FECHAREGISTRO and '$fechaf') or (NOVEDADES.FECHAREGISTRO  BETWEEN '$fechai' and '$fechaf')) and NOVEDADES.ESTADO_NOVEDAD=1) OR (((('$fechai' BETWEEN NOVEDADES.FECHAREGISTRO and NOVEDADES.FECHACIERRE) or (NOVEDADES.FECHAREGISTRO  BETWEEN '$fechai' and '$fechaf')) and NOVEDADES.ESTADO_NOVEDAD=0) or ((('$fechaf' BETWEEN  NOVEDADES.FECHAREGISTRO and NOVEDADES.FECHACIERRE) or (NOVEDADES.FECHACIERRE BETWEEN '$fechai' AND '$fechaf')) AND NOVEDADES.ESTADO_NOVEDAD=0)))";
            return ejecutarConsulta($sql);
    }
	
	public function ListarNovedadesN($fechai,$fechaf)
    {
            $sql = "SELECT NOVEDADES.*, Proyectos.nombreProyecto,uen.NombreUen FROM NOVEDADES INNER JOIN Proyectos on Proyectos.Idproyctos=NOVEDADES.PROYECTONOVEDAD INNER JOIN uen on uen.IDUen=NOVEDADES.UENNOVEDAD WHERE ((('$fechai' BETWEEN NOVEDADES.FECHAREGISTRO and '$fechaf') or (NOVEDADES.FECHAREGISTRO  BETWEEN '$fechai' and '$fechaf')) and NOVEDADES.ESTADO_NOVEDAD=1) OR (((('$fechai' BETWEEN NOVEDADES.FECHAREGISTRO and NOVEDADES.FECHACIERRE) or (NOVEDADES.FECHAREGISTRO  BETWEEN '$fechai' and '$fechaf')) and NOVEDADES.ESTADO_NOVEDAD=0) or ((('$fechaf' BETWEEN  NOVEDADES.FECHAREGISTRO and NOVEDADES.FECHACIERRE) or (NOVEDADES.FECHACIERRE BETWEEN '$fechai' AND '$fechaf')) AND NOVEDADES.ESTADO_NOVEDAD=0))";
            return ejecutarConsulta($sql);
    }
	
	
	public function Listarinventario($estado)
    {
            $sql = "SELECT VEHICULOS.PLACA_VEH, inventario.* FROM inventario INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=inventario.ID_VEHICULO_INVENTARIO WHERE inventario.ESTADO_INEVENTARIO=$estado";
            return ejecutarConsulta($sql);
    }
	
	public function MostrarInventario($id)
    {
            $sql="SELECT VEHICULOS.ID_VEH,VEHICULOS.PLACA_VEH, elementosflota.NOMBRE_ELEMENTO,elementosflota.IMAGEN_REFERENCIA,detalle_inventario.* FROM inventario INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=inventario.ID_VEHICULO_INVENTARIO INNER JOIN detalle_inventario on detalle_inventario.ID_INVENARIOINV=inventario.ID_INVENTARIO INNER JOIN elementosflota on elementosflota.IDELEMETO=detalle_inventario.ID_ELEMENTOINV where inventario.ID_INVENTARIO=$id";
            return ejecutarConsulta($sql);
    }
	
	
	public function vehiculosinventario()
    {
            $sql="SELECT VEHICULOS.* FROM VEHICULOS where VEHICULOS.ESTADO_VEHICULO=1 and not EXISTS(SELECT null from inventario where inventario.ID_VEHICULO_INVENTARIO=VEHICULOS.ID_VEH)";
            return ejecutarConsulta($sql);
    }
	
	
	public function PresupuestoValidar($anio,$id)
    {
            $sql="SELECT gestion_gasto.MES_EJECUCION FROM gestion_gasto WHERE gestion_gasto.ANIO_EJECUCION='$anio' and gestion_gasto.ID_PRESUPUESTO_G=$id ORDER BY gestion_gasto.MES_EJECUCION DESC;";
            return ejecutarConsulta($sql);
    }
	
	public function listarPresupuesto($estado)
    {
            $sql="SELECT proveedores.RAZON_SOCIAL, uen.NombreUen, empresas.NombreEmpresa, presupuesto.* FROM presupuesto INNER JOIN proveedores on proveedores.ID_PROVEEDOR=presupuesto.ID_PROVEEDOR_PRS INNER JOIN uen on uen.IDUen=presupuesto.UNIDAD_NEGOCIO_PRS INNER JOIN empresas on empresas.IDempresa=presupuesto.EMPRESA_PRS WHERE presupuesto.ESTADO_PRESUPUESTO=$estado";
            return ejecutarConsulta($sql);
    }
	
	public function BorarmeseI($id,$anio,$mes)
    {
            $sql="DELETE from meses_presupuesto WHERE meses_presupuesto.ID_PRESUPUESTOM=$id and meses_presupuesto.ANIOP=$anio and meses_presupuesto.MESP>$mes";
            return ejecutarConsulta($sql);
    }
	
	public function BorarmeseF($id,$anio,$fecha)
    {
            $sql="DELETE from meses_presupuesto WHERE meses_presupuesto.ID_PRESUPUESTOM=$id and meses_presupuesto.ANIOP>$anio and meses_presupuesto.FECHA_INICIALM='$fecha'";
            return ejecutarConsulta($sql);
    }
	
	public function mostrarPresupuesto($id)
    {
            $sql="SELECT presupuesto.*,proveedores.RAZON_SOCIAL,uen.NombreUen,empresas.NombreEmpresa FROM presupuesto INNER JOIN proveedores on proveedores.ID_PROVEEDOR=presupuesto.ID_PROVEEDOR_PRS INNER JOIN uen on uen.IDUen=presupuesto.UNIDAD_NEGOCIO_PRS INNER JOIN empresas on empresas.IDempresa=presupuesto.EMPRESA_PRS WHERE presupuesto.ID_PRESUPUESTO='$id'";
            return ejecutarConsultaSimpleFila($sql);
    }
	
	
	public function mostrarPresupuesto2($id,$anio,$mes)
    {
            $sql="SELECT presupuesto.*,proveedores.RAZON_SOCIAL,uen.NombreUen,empresas.NombreEmpresa, gestion_gasto.ID_GESTION, gestion_gasto.ESTADO_ESTION, gestion_gasto.OBSERVACION_GESTION, gestion_gasto.VALOR_TOTAL FROM presupuesto INNER JOIN proveedores on proveedores.ID_PROVEEDOR=presupuesto.ID_PROVEEDOR_PRS INNER JOIN uen on uen.IDUen=presupuesto.UNIDAD_NEGOCIO_PRS INNER JOIN empresas on empresas.IDempresa=presupuesto.EMPRESA_PRS INNER JOIN gestion_gasto on gestion_gasto.ID_PRESUPUESTO_G=presupuesto.ID_PRESUPUESTO WHERE presupuesto.ID_PRESUPUESTO='$id' and gestion_gasto.ANIO_EJECUCION='$anio' and gestion_gasto.MES_EJECUCION='$mes'";
            return ejecutarConsultaSimpleFila($sql);
    }
	
	public function ListarCompras($estado)
    {
            $sql="SELECT compras.*, uen.NombreUen, empresas.NombreEmpresa, proveedores.RAZON_SOCIAL FROM compras INNER JOIN uen on uen.IDUen=compras.IDUENCOMPRA INNER JOIN empresas on empresas.IDempresa=compras.IDEMPRESACOMPRA INNER JOIN proveedores on proveedores.ID_PROVEEDOR=compras.IDPROVEEDORCOMPRA WHERE compras.ESTADOCOMPRA=$estado";
            return ejecutarConsulta($sql);
    }
	
	
	public function ListarCompra2($mes,$anio)
    {
            $sql="SELECT compras.*, uen.NombreUen, empresas.NombreEmpresa, proveedores.RAZON_SOCIAL FROM compras INNER JOIN uen on uen.IDUen=compras.IDUENCOMPRA INNER JOIN empresas on empresas.IDempresa=compras.IDEMPRESACOMPRA INNER JOIN proveedores on proveedores.ID_PROVEEDOR=compras.IDPROVEEDORCOMPRA WHERE compras.ESTADOCOMPRA=1 and compras.MESCOMPRA='$mes' and compras.ANIOCOMPRA='$anio'";
            return ejecutarConsulta($sql);
    }
	
	
	
	
	public function PresupuestoFlota($estado,$anio,$mes)
    {
            $sql="SELECT PRESUPUESTO_FLOTA.*,VEHICULOS.PLACA_VEH,TIPO_VEHICULOS.NOM_TIPO_VEHICULO,Proyectos.nombreProyecto, TIPO_MANTENIMIENTO.NOMBRE_TIPO_MNT from PRESUPUESTO_FLOTA INNER JOIN TIPO_MANTENIMIENTO on TIPO_MANTENIMIENTO.ID_TIPO_MNT=PRESUPUESTO_FLOTA.TIPO_FALLA_PRESUPUESTO INNER JOIN Proyectos on Proyectos.Idproyctos=PRESUPUESTO_FLOTA.PROYECTO_PRESUPUESTO INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=PRESUPUESTO_FLOTA.ID_VEHICULO_PRESUPUESTO INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH where PRESUPUESTO_FLOTA.ESTADO_PRESUPUESTO=$estado and PRESUPUESTO_FLOTA.ANIO_PRESUPUESTO='$anio' and PRESUPUESTO_FLOTA.MES_PRESUPUESTO='$mes'";
            return ejecutarConsulta($sql);
    }
	
	
	public function MPresupuestoFlota($id)
    {
            $sql="SELECT PRESUPUESTO_FLOTA.*,VEHICULOS.PLACA_VEH,TIPO_VEHICULOS.NOM_TIPO_VEHICULO,Proyectos.nombreProyecto,Proyectos.Idproyctos, TIPO_MANTENIMIENTO.NOMBRE_TIPO_MNT from PRESUPUESTO_FLOTA INNER JOIN TIPO_MANTENIMIENTO on TIPO_MANTENIMIENTO.ID_TIPO_MNT=PRESUPUESTO_FLOTA.TIPO_FALLA_PRESUPUESTO INNER JOIN Proyectos on Proyectos.Idproyctos=PRESUPUESTO_FLOTA.PROYECTO_PRESUPUESTO INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=PRESUPUESTO_FLOTA.ID_VEHICULO_PRESUPUESTO INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH where PRESUPUESTO_FLOTA.ID_PRESUPUESTO_FLOTA=$id";
            return ejecutarConsultaSimpleFila($sql);
    }
	
	public function presupuestoVH($vh)
    {
            $sql="SELECT Proyectos.Idproyctos,Proyectos.nombreProyecto,TIPO_VEHICULOS.NOM_TIPO_VEHICULO from DISPONIBILIDAD_FLOTA INNER JOIN Proyectos on Proyectos.Idproyctos=DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH where VEHICULOS.ID_VEH=$vh and DISPONIBILIDAD_FLOTA.ESTADO_DIS=1";
            return ejecutarConsultaSimpleFila($sql);
    }
	
	
	public function ListarAprobacionFlota($estado1,$estado2)
    {
            $sql="SELECT PRESUPUESTO_FLOTA.*,VEHICULOS.PLACA_VEH,TIPO_VEHICULOS.NOM_TIPO_VEHICULO,Proyectos.nombreProyecto, TIPO_MANTENIMIENTO.NOMBRE_TIPO_MNT from PRESUPUESTO_FLOTA INNER JOIN TIPO_MANTENIMIENTO on TIPO_MANTENIMIENTO.ID_TIPO_MNT=PRESUPUESTO_FLOTA.TIPO_FALLA_PRESUPUESTO INNER JOIN Proyectos on Proyectos.Idproyctos=PRESUPUESTO_FLOTA.PROYECTO_PRESUPUESTO INNER JOIN VEHICULOS on VEHICULOS.ID_VEH=PRESUPUESTO_FLOTA.ID_VEHICULO_PRESUPUESTO INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH where PRESUPUESTO_FLOTA.ESTADO_PRESUPUESTO='$estado1' and PRESUPUESTO_FLOTA.ESTADO_AP_PRESUPUESTO='$estado2'";
            return ejecutarConsulta($sql);
    }
	
	
	public function detallePresuesto($idpresupuesto)
    {
            $sql="SELECT centrooperativo.NombreCentroOP, item.NOMBRE_ITEM, detalle_presupuesto.* FROM detalle_presupuesto
            INNER JOIN centrooperativo on centrooperativo.IDCentroOP=detalle_presupuesto.CENTRO_OPERATIVO
            INNER JOIN item on item.IDITEM=detalle_presupuesto.ITEM_PRESUPUESTO where detalle_presupuesto.ID_PRESUPUESTO_DETALLE='$idpresupuesto'";
            return ejecutarConsulta($sql);
    }
	
	public function detallePresuestoG($idpresupuesto)
    {
            $sql="SELECT centrooperativo.NombreCentroOP,item.NOMBRE_ITEM, detalle_gestion_presupuesto.* FROM detalle_gestion_presupuesto INNER JOIN centrooperativo on centrooperativo.IDCentroOP=detalle_gestion_presupuesto.CENTRO_OPDETALLE_GESTION INNER JOIN item on item.IDITEM=detalle_gestion_presupuesto.ITEM_GESTION_GASTOS WHERE detalle_gestion_presupuesto.ID_GESTION_PRS='$idpresupuesto'";
            return ejecutarConsulta($sql);
    }
    
    
    public function SelectVehiculo($idproyecto)
    {
            $sql="SELECT VEHICULOS.ID_VEH, VEHICULOS.PLACA_VEH, TIPO_VEHICULOS.NOM_TIPO_VEHICULO from DISPONIBILIDAD_FLOTA INNER JOIN VEHICULOS on  VEHICULOS.ID_VEH=DISPONIBILIDAD_FLOTA.ID_VEHICULOS_DIS INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH WHERE DISPONIBILIDAD_FLOTA.ID_PROYECTO_DIS=$idproyecto and DISPONIBILIDAD_FLOTA.ESTADO_DIS=1";
            return ejecutarConsulta($sql);
    }

     public function SelectVehiculoNormal()
    {
            $sql="SELECT VEHICULOS.ID_VEH, VEHICULOS.PLACA_VEH, TIPO_VEHICULOS.NOM_TIPO_VEHICULO from VEHICULOS  INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=VEHICULOS.TIPO_VEH WHERE VEHICULOS.ESTADO_VEHICULO=1";
            return ejecutarConsulta($sql);
    }
	
    public function ProyectoVehiculo($idcontrato)
    {
            $sql="SELECT Proyectos.Idproyctos from EJECUCION_CONTRATO_SER_ASIS INNER JOIN contrato ON contrato.IDcontrato=EJECUCION_CONTRATO_SER_ASIS.ID_CONTRATO INNER JOIN Proyectos on Proyectos.IDcontratoProyecto=contrato.IDcontrato WHERE EJECUCION_CONTRATO_SER_ASIS.ID_EJE_CON_SER_ASIS=$idcontrato";
            return ejecutarConsultaSimpleFila($sql);
    }

    public function ReporteAsistencialGrua($centro,$fecha_inicio,$fecha_final)
    {
         $listaCentros = [];
        foreach ($centro as $c) {
             $listaCentros[] = array_values($c)[0];
          }
            $sql="SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP, tipo_evento_asistencial.NOMBRE_TIPO_EVENTO,TIPO_VEHICULOS.NOM_TIPO_VEHICULO, detalle_servicio_general.* FROM servicio_asistencial 
            INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO 
            INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO 
            INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO 
            INNER JOIN detalle_servicio_general on detalle_servicio_general.ID_SERVICIO_DETALLE=servicio_asistencial.ID_SERVICIO_ASISTENCIAL 
            where servicio_asistencial.CENTRO_SERVICIO in (".implode(',', $listaCentros).")  and servicio_asistencial.FECHA_SERVICIO BETWEEN '$fecha_inicio' and '$fecha_final'";
            return ejecutarConsulta($sql);
    }
    
    public function ReporteAsistencialAmbulancia($centro,$fecha_inicio,$fecha_final)
    {

            foreach ($centro as $c) {
             $listaCentros[] = array_values($c)[0];
          }
            $sql="SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP, tipo_evento_asistencial.NOMBRE_TIPO_EVENTO,TIPO_VEHICULOS.NOM_TIPO_VEHICULO, datalle_ambulancia.* FROM servicio_asistencial
             INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO 
             INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO 
             INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO 
             INNER JOIN datalle_ambulancia on datalle_ambulancia.ID_SERVICIO_DETALLE_AMBU=servicio_asistencial.ID_SERVICIO_ASISTENCIAL 
             where servicio_asistencial.CENTRO_SERVICIO in (".implode(',', $listaCentros).") and servicio_asistencial.FECHA_SERVICIO BETWEEN '$fecha_inicio' and '$fecha_final'";
            return ejecutarConsulta($sql);
    }

      public function ReporteAsistencialGruaGeneral($proyecto,$fecha_inicio,$fecha_final)
    {
            if($proyecto!="todos"){
            $sql="SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP,Proyectos.nombreProyecto, tipo_evento_asistencial.NOMBRE_TIPO_EVENTO,TIPO_VEHICULOS.NOM_TIPO_VEHICULO, detalle_servicio_general.* FROM servicio_asistencial 
            INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO 
            INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO 
            INNER JOIN detalle_servicio_general on detalle_servicio_general.ID_SERVICIO_DETALLE=servicio_asistencial.ID_SERVICIO_ASISTENCIAL 
            INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO 
            INNER JOIN EJECUCION_CONTRATO_SER_ASIS on EJECUCION_CONTRATO_SER_ASIS.ID_CENTROPEATIVO=centrooperativo.IDCentroOP       
            INNER JOIN contrato on contrato.IDcontrato=EJECUCION_CONTRATO_SER_ASIS.ID_CONTRATO
            INNER JOIN Proyectos on Proyectos.IDcontratoProyecto=contrato.IDcontrato
            where Proyectos.Idproyctos=$proyecto and servicio_asistencial.FECHA_SERVICIO BETWEEN '$fecha_inicio' and '$fecha_final'";

        }else{

           $sql="SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP,Proyectos.nombreProyecto, tipo_evento_asistencial.NOMBRE_TIPO_EVENTO,TIPO_VEHICULOS.NOM_TIPO_VEHICULO, detalle_servicio_general.* FROM servicio_asistencial 
            INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO 
            INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO 
            INNER JOIN detalle_servicio_general on detalle_servicio_general.ID_SERVICIO_DETALLE=servicio_asistencial.ID_SERVICIO_ASISTENCIAL 
            INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO 
            INNER JOIN EJECUCION_CONTRATO_SER_ASIS on EJECUCION_CONTRATO_SER_ASIS.ID_CENTROPEATIVO=centrooperativo.IDCentroOP       
            INNER JOIN contrato on contrato.IDcontrato=EJECUCION_CONTRATO_SER_ASIS.ID_CONTRATO
            INNER JOIN Proyectos on Proyectos.IDcontratoProyecto=contrato.IDcontrato
            where servicio_asistencial.FECHA_SERVICIO BETWEEN '$fecha_inicio' and '$fecha_final'"; 
            }
            
            return ejecutarConsulta($sql);
    }

    public function ReporteAmbulanciaGeneral($proyecto,$fecha_inicio,$fecha_final)
    {
            if($proyecto!="todos"){
            $sql="SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP,Proyectos.nombreProyecto, tipo_evento_asistencial.NOMBRE_TIPO_EVENTO,TIPO_VEHICULOS.NOM_TIPO_VEHICULO, datalle_ambulancia.* FROM servicio_asistencial 
            INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO 
            INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO 
            INNER JOIN datalle_ambulancia on datalle_ambulancia.ID_SERVICIO_DETALLE_AMBU=servicio_asistencial.ID_SERVICIO_ASISTENCIAL 
            INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO 
            INNER JOIN EJECUCION_CONTRATO_SER_ASIS on EJECUCION_CONTRATO_SER_ASIS.ID_CENTROPEATIVO=centrooperativo.IDCentroOP       
            INNER JOIN contrato on contrato.IDcontrato=EJECUCION_CONTRATO_SER_ASIS.ID_CONTRATO
            INNER JOIN Proyectos on Proyectos.IDcontratoProyecto=contrato.IDcontrato
            where Proyectos.Idproyctos=$proyecto and servicio_asistencial.FECHA_SERVICIO BETWEEN '$fecha_inicio' and '$fecha_final'";
        }else{
           $sql="SELECT servicio_asistencial.*, centrooperativo.NombreCentroOP,Proyectos.nombreProyecto, tipo_evento_asistencial.NOMBRE_TIPO_EVENTO,TIPO_VEHICULOS.NOM_TIPO_VEHICULO, datalle_ambulancia.* FROM servicio_asistencial 
            INNER JOIN tipo_evento_asistencial on tipo_evento_asistencial.ID_TIPO_EVENTO=servicio_asistencial.TIPO_EVENTO_SERVICIO 
            INNER JOIN TIPO_VEHICULOS on TIPO_VEHICULOS.ID_TIP_VEH=servicio_asistencial.TIPO_VEHICULO_SERVICIO 
            INNER JOIN datalle_ambulancia on datalle_ambulancia.ID_SERVICIO_DETALLE_AMBU=servicio_asistencial.ID_SERVICIO_ASISTENCIAL 
            INNER JOIN centrooperativo on centrooperativo.IDCentroOP=servicio_asistencial.CENTRO_SERVICIO 
            INNER JOIN EJECUCION_CONTRATO_SER_ASIS on EJECUCION_CONTRATO_SER_ASIS.ID_CENTROPEATIVO=centrooperativo.IDCentroOP       
            INNER JOIN contrato on contrato.IDcontrato=EJECUCION_CONTRATO_SER_ASIS.ID_CONTRATO
            INNER JOIN Proyectos on Proyectos.IDcontratoProyecto=contrato.IDcontrato
            where servicio_asistencial.FECHA_SERVICIO BETWEEN '$fecha_inicio' and '$fecha_final'"; 
            }
            return ejecutarConsulta($sql);
    }
    
    	
	
}
?>