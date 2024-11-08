const teo_rest_communication = require('../../models/api_connection_map');
const general_helper = require('../../helpers/general');

async function send_chunks_inserts(data) {
    let array_promises = [];
    if(data.length > 0){
        const chunk_size = 1000;
        for (let index = 0; index < data.length; index = index + chunk_size) {

          const chunk = data.slice(index, index + chunk_size);
          array_promises.push(teo_rest_communication.save_chunk(chunk));

        }
        const results_all_promises = await Promise.all(array_promises);
        return results_all_promises;
    }
  }

  async function send_chunks_basic_data_user(data) {
    let array_promises = [];
    if(data.length > 0){
        const chunk_size = 1000;
        for (let index = 0; index < data.length; index = index + chunk_size) {

          const chunk = data.slice(index, index + chunk_size);
          array_promises.push(teo_rest_communication.save_chunk_basic_data_user(chunk));

        }
        const results_all_promises = await Promise.all(array_promises);
        return results_all_promises;
    }
  }

function isValidDate(date) {

  let confirmation_summary = false;
  let confirmation_inicio = false;
  let confirmation_fin = false;
  let formatoHora = /^([01]\d|2[0-3]):([0-5]\d):([0-5]\d)\.\d{5,6}$/;

  confirmation_inicio = formatoHora.test(date[0]);
  confirmation_fin = formatoHora.test(date[1]);

  if(confirmation_inicio && confirmation_fin){

    confirmation_summary = dataRangeValidation(date[0], date[1]);

    if(!confirmation_summary){

      general_helper.showDetails("Fecha inicio no puede ser mayor que fecha fin.");

    } else {

      confirmation_summary = true;

    }
    
  } else {

     general_helper.showDetails("Tu fecha no tiene el formato válido.");

  }
  
  return confirmation_summary;

}

function dataRangeValidation(date1, date2) {

  let confirmation = false;

  if(date1 < date2){

    confirmation = true;

  }

  return confirmation

}

  module.exports = {
    send_chunks_inserts,
    isValidDate,
    send_chunks_basic_data_user
  }
  


  