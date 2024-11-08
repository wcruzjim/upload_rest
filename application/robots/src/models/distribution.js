const COSMOS_CONFIG = require("../config/config");
const general_helper = require('../helpers/general');
const {_db} = require("../app/databases");

// Truncate table to flush innodb cache
function get_pending_users_geocode(){

  return new Promise(function(resolve, reject){

    _db.getConnection("jarvis", function(err, conn){

      if(err){
        return reject(err);
      }

      if(conn){
        conn.query(`SELECT
		ad.documento AS documento,
		ad.direccion AS address		
		from dp_actualizacion_datos AS ad
		LEFT JOIN dp_nomenclatura_castral AS n1 ON n1.abreviatura = ad.direccion_tipo_via1
		LEFT JOIN dp_nomenclatura_castral AS n2 ON n2.abreviatura = ad.direccion_tipo_via2
		LEFT JOIN dp_nomenclatura_castral AS n3 ON n3.abreviatura = ad.direccion_tipo_via3
		LEFT JOIN dp_orientaciones AS or1 ON or1.abreviatura = ad.direccion_orientacion1
		LEFT JOIN dp_orientaciones AS or2 ON or2.abreviatura = ad.direccion_orientacion2
		LEFT JOIN dp_departamentos AS dd ON dd.id_departamento = ad.id_departamento
		LEFT JOIN dp_municipios AS dm ON dm.id_municipio = ad.id_municipio
		LEFT JOIN dp_zonas AS dz ON dz.id_zona = ad.id_zona
		LEFT JOIN dp_barrios AS brr ON brr.id_barrio = ad.id_barrio
		WHERE ad.latitud IS NULL
		limit 1`,
        function(error, results){
          conn.release();
          return (error) ? reject(new Error(error)) : resolve(results);   
        });

      }

    });

  });
}





// Truncate table to flush innodb cache
function update_user_geocoords(user_document, latitude, longitude){

	return new Promise(function(resolve, reject){
  
	  	_db.getConnection("jarvis", function(err, conn){
  
			if(err){
			return reject(err);
			}
	
			if(conn){
				conn.query(`
				UPDATE dp_actualizacion_datos
				SET 
					latitud = ?,
					longitud = ?
				WHERE documento = ?
				`,
				[latitude, longitude, user_document],
				function(error){
					conn.release();
					return (error) ? reject(new Error(error)) : resolve(true);   
				});
	
			}
  
		});
  
	});
  }


  function getDistributionForSigma(){
	return new Promise(function(resolve, reject){
  
		_db.getConnection("jarvis", function(err, conn){
	
		  if(err){
			return reject(err);
		  }
	
		  if(conn){
			conn.query(`
				SELECT 
					UPPER(dg.nombre_completo) AS empleado, 
					dp.documento AS dmenumerodocumento, 
					CONCAT('MULTIENLACE---', da.usuario) AS rac_usuario_red, 
					da.usuario AS usuario_genesys, cl.id_dp_clientes AS codigocliente, 
					cl.cliente, SUBSTR(dp.id_dp_centros_costos, 4) AS codigoprograma, 
					pr.programa, 
					dp.cod_pcrc AS codigopcrc, 
					pc.pcrc, 
					cc.id_dp_centros_costos AS centrocosto,
					dh.cambio_realizado AS fecha_conexion_ultimo_pcrc
				FROM dp_distribucion_personal AS dp
				INNER JOIN dp_datos_generales AS dg ON dg.documento = dp.documento
				LEFT JOIN dp_historial_cambios AS dh ON dh.documento = dp.documento AND dh.id_dp_historial_tipo_cambios = 43 AND DATE(dh.fecha_fin) = '1900-01-01'
				INNER JOIN dp_pcrc AS pc ON pc.cod_pcrc = dp.cod_pcrc
				INNER JOIN dp_centros_costos AS cc ON cc.id_dp_centros_costos = pc.id_dp_centros_costos
				INNER JOIN dp_clientes AS cl ON cl.id_dp_clientes = cc.id_dp_clientes
				INNER JOIN dp_programa AS pr ON pr.id_dp_centros_costos = cc.id_dp_centros_costos
				INNER JOIN dp_usuarios_actualizacion AS da ON da.documento = dp.documento
				INNER JOIN dp_plataforma AS pl ON pl.id_dp_plataforma = da.id_dp_plataforma AND pl.marcacion_sigma = 1
				WHERE
				 dp.fecha_actual = ( SELECT valor FROM jarvis_configuracion_general WHERE nombre = 'mes_activo_dp' LIMIT 1 )
				 AND dp.id_dp_cargos IN(18190,39322,40323,40324,30264,39981)
				 AND (dp.cod_pcrc <> 0 AND dp.id_dp_centros_costos_adm = 0)
				 AND dp.id_dp_estados <> 301
				 GROUP BY dmenumerodocumento, usuario_genesys
			`,
			function(error, results){
			  conn.release();
			  return (error) ? reject(new Error(error)) : resolve(results.map(val => {
				  val.rac_usuario_red = val.rac_usuario_red.split('---').join('\\');
				  return val;
			  }));   
			});
	
		  }
	
		});
	
	  });
  }
  

//  get users in specific charge who has permissions to the target PCRC
function getParentChargesByPattern(data){
	
	if(!data.pattern || !data.target){
		return Promise.reject('Pattern or target not valid');
	}

	data.target = data.target.constructor === Array ? data.target : [data.target];

	return new Promise(function(resolve, reject){
  
	  	_db.getConnection("jarvis", function(err, conn){

			if(err){
				return reject(err);
			}

			if(conn){
			conn.query(`
					SELECT 
						DISTINCT
						pu.documento identify
					FROM 
						dp_usuarios_red r
					LEFT JOIN dp_distribucion_personal d ON d.documento = r.documento AND d.fecha_actual = (  SELECT co.valor FROM jarvis_configuracion_general co WHERE co.nombre = 'mes_activo_dp' LIMIT 1 )
					LEFT JOIN per_pcrc_usuario pu ON pu.pcrc = d.cod_pcrc
					LEFT JOIN dp_distribucion_personal d2 ON d2.documento = pu.documento AND d.fecha_actual = (  SELECT co.valor FROM jarvis_configuracion_general co WHERE co.nombre = 'mes_activo_dp' LIMIT 1 )
					WHERE 
						r.usuario_red IN ?
						AND d2.id_dp_cargos IN ?
			`,
			[[data.target], [data.charges]],
			function(error, results){
				general_helper.showDetails(error);
				conn.release();
				return (error) ? reject(new Error(error)) : resolve(results);   
			});
	
			}
  
	  	});
  
	});
}




module.exports = {
	get_pending_users_geocode,
	update_user_geocoords,
	getDistributionForSigma,
	getParentChargesByPattern
}
