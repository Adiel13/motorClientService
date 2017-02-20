<?php

class obtenerinfo{

    function __construct() {
        
    }

    function conexionBD() {
        return pg_connect("user=usrconsulta password=c0nsult4 dbname=clientservice host=localhost port=5432");
    }

    function obtenerDatos($idempleado){

		$conexion = $this->conexionBD();
		$query = "select e.*, tu.*  from empleado e inner join usuario u on 
	e.id_empleado = u.id_empleado 
    inner join tipo_usuario tu on
    	tu.tipo_usuario = u.tipo_usuario where e.id_empleado = '" . $idempleado . "'";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return json_encode($datos);
            }			
        }		
        pg_close($conexion);
		return false;
    }
    
    function obtenerPromedioLlegada($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select extract(hour from inicio_venta) as y, count(*) as a from venta where
        id_sucursal = '".$sucursal."' and
        inicio_venta between '".$fechainicio."' and '".$fechafin."'
        group by 1
        order by 1
        ";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;
    }
    
    function obtenerTablaLlegada($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select e.nombre || ' ' || e.apellido as nombre, t.total as usuario, count(*) as total from venta v inner join empleado e on 
            v.id_empleado = e.id_empleado
                inner join (select v.id_sucursal, count(*) as total from venta v 
                    where
                        inicio_venta between '".$fechainicio."' and '".$fechafin."'
                        group by 1) t on
                    t.id_sucursal = v.id_sucursal
            where
                v.inicio_venta between '".$fechainicio."' and '".$fechafin."' and
                v.id_sucursal = '".$sucursal."'
            group by 1, 2";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;
    }
    
    function obtenerListadoSucursales($token){
        $conexion = $this->conexionBD();
		$query = "select u.tipo_usuario from sesion s inner join usuario u on
	       s.id_empleado = u.id_empleado
            where
    	       s.token = '".$token."'";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);		                
                if($datos[0]['tipo_usuario'] == 1){
                    $query = "select su.id_sucursal || '-' || su.direccion as name, su.id_sucursal as value from sesion s inner join usuario u on 
	                   s.id_empleado = u.id_empleado
                        inner join sucursal su on 
    	               su.id_empresa = u.id_empresa
                    where
	                   s.token = '".$token."'";		
                    if (!$conexion) {
                        return false;
                    } else {
                        $resultado = pg_exec($conexion, $query);
                        $total = pg_num_rows($resultado);
                    if ($total > 0) {
                        $datos2 = pg_fetch_all($resultado);		                
                    }
				    return $datos2;
                    }			        
                }
            }			
        }		
        pg_close($conexion);
		return false;
    }
    
    function obtenerAtencionVsVenta($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select e.id_empleado, e.nombre || ' ' || e.apellido as nombre, v.id_sucursal, count(v.id_venta) as clientes, count(vs.id_venta) as venta 
        from venta v inner join empleado e on
	       e.id_empleado = v.id_empleado 
            left join venta_satisfactoria vs on 
    	       vs.id_venta = v.id_venta
        where
            v.inicio_venta between '".$fechainicio."' and '".$fechafin."' and
            v.id_sucursal = '".$sucursal."'
        group by 1, 2, 3";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;        
    }
    
    function obtenerNumeroAtendidos($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select e.id_empleado, e.nombre || ' ' || e.apellido as nombre, v.id_sucursal, count(*) as atendidos from venta v inner join empleado e on
	           e.id_empleado = v.id_empleado 
            where
    	           v.inicio_venta between '".$fechainicio."' and '".$fechafin."' and
                    v.id_sucursal = '".$sucursal."'
            group by 1, 2, 3";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;        
    }    
    
    function obtenerTiempoAtencion($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select e.nombre || ' ' || e.apellido as nombre,  extract ('epoch' from  sum(fin_venta -inicio_venta)::interval)::float / 60  as tiempoventa 
	               From venta v inner join empleado e on
    	               v.id_empleado = e.id_empleado
                where
	               v.inicio_venta between '".$fechainicio."' and '".$fechafin."'  and
                   v.id_sucursal = '".$sucursal."'
                group by 1";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;        
    }   

    function obtenerTablaVenta($fechainicio, $fechafin, $sucursal){
        $conexion = $this->conexionBD();
		$query = "select s.*, ss.contindividual, ss.porcentajeventa, ss.vindividual, ss.porcentajemonto from
            (select t.*, (t.atendidos::float/tt.total::float)::float * 100 as porcentajeatendidos, ttt.tiempo, tttt.tiempoventa,
                ( ((extract ('epoch' from tttt.tiempoventa::interval))::float /3600)/ (ttt.tiempo*3600)) *100 as porcentajeatencion
                    from
                        (select e.id_empleado, e.nombre || ' ' || e.apellido as nombre, v.id_sucursal, count(*) as atendidos 
		                  from venta v inner join empleado e on
			                 e.id_empleado = v.id_empleado 
                           where
		                      v.inicio_venta between '".$fechainicio."' and '".$fechafin."'
	                       group by 1, 2, 3
                        )t	inner join (
                        select  v.id_sucursal, count(*) as total from venta v 
		                  where
		                      v.inicio_venta between '".$fechainicio."' and '".$fechafin."'
	                           group by 1
                            ) tt on
	                           t.id_sucursal = tt.id_sucursal
                                inner join 
    	                           (select id_empleado, sum(s.horas_contratacion) as tiempo 
				                    from(        
					                   select bs.fecha_inicio::date, bs.id_empleado,e.horas_Contratacion, count(*)as veces 
					                       From bitacora_Sesion bs
						                      inner join empleado e on 
							                     e.id_empleado = bs.id_empleado 
                                       where
						              bs.fecha_inicio between '".$fechainicio."' and '".$fechafin."'  and
                    	               bs.sistema = 2
					               group by 1,2,3
					               ) s
			                     group by 1) ttt on 
            	                   t.id_empleado = ttt.id_empleado
				                    inner join 
                                    (select id_empleado, sum(fin_venta -inicio_venta)  as tiempoventa 
						              From venta 
                                   where
						              inicio_venta between '".$fechainicio."' and '".$fechafin."'
					               group by 1
                                    ) tttt on 
                 	              t.id_empleado = tttt.id_empleado
        )s left join 
        (
            select t.id_empleado, t.id_sucursal,  t.contindividual, t.contindividual::float/tt.conttotal::float * 100 as porcentajeventa, 
            t.vindividual, t.vindividual::float/tt.vtotal::float * 100 as porcentajemonto
            from
                (select id_empleado, id_sucursal, sum(monto) as vindividual, count(monto) contindividual  From venta_satisfactoria vs inner join venta v on
	               vs.id_venta = v.id_venta 
                    where
    	               v.inicio_venta between '".$fechainicio."' and '".$fechafin."'
               group by 1, 2) t inner join 
                (
                select id_sucursal, sum(monto) as vtotal, count(monto) as conttotal  From venta_satisfactoria vs inner join venta v on
	               vs.id_venta = v.id_venta 
                    where
    	               v.inicio_venta between '".$fechainicio."' and '".$fechafin."'
                    group by 1
                ) tt on
                   t.id_sucursal = tt.id_sucursal
        ) ss on
 	          s.id_sucursal = ss.id_sucursal and
                s.id_empleado = ss.id_empleado
        where
    	   s.id_sucursal =  '".$sucursal."'
           group by 1,2,3,4,5,6,7,8,9,10,11,12";		
		if (!$conexion) {
            return false;
        } else {
            $resultado = pg_exec($conexion, $query);
            $total = pg_num_rows($resultado);
            if ($total > 0) {
                $datos = pg_fetch_all($resultado);				
				return $datos;
            }			
        }		
        pg_close($conexion);
		return false;        
    }   
    
}
?>
