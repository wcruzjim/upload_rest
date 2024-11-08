const general_helper = require('../../helpers/general');
const nomina_model = require("./models/nomina_model");
const fs = require("fs");
const os = require('os');
const path = require('path');


const route_files = '/mnt/windows_sap';

(async function start(){  
    general_helper.showDetails("Starting bot"); 
    try {
        await save_files();
        process.exit(0)
    } catch(error){
        general_helper.showDetails(error);
        process.exit(1)
    }
})();


async function save_files() {

    general_helper.showDetails("Getting files"); 
    const array_data = await get_files();

    if (array_data){
        general_helper.showDetails("Saving files " + array_data.length); 
        await validate_and_save_data(array_data);
        general_helper.showDetails("Files saved successfully"); 
    } else {
        general_helper.showDetails("Se presentó un error al momento de obtener los archivos");
    }

    return true;
}


async function get_files() {
    const data_files = [];
    const date = new Date();
    general_helper.showDetails("Start reading folder");
    const array_files_pdf = await fs.promises.readdir(route_files);
    general_helper.showDetails("Finish reading folder. Files found " + array_files_pdf.length);
    for (const file of array_files_pdf){

        const full_route = path.join(route_files, file);
        const file_content = await fs.promises.readFile(full_route);
        const name_file = path.basename(file).split('.');
        const data_user = name_file[0].split('_');
         
        data_files.push(
            {
            documento: data_user[0],
            fecha_carga: date,
            estado: 1,
            id_jarvis_uploads_tipos: 7,
            id_soporte_registro: data_user[1] + data_user[2],
            nombre_file: name_file[0],
            tipo_file: '.pdf',
            file: file_content
            }
        ); 
    }
    return(data_files)
}


async function validate_and_save_data(data) {
    if (data){
    let validation = nomina_model.data_validation(data);
      if(!validation){
        throw new Error('Error trying to save connections');
      }
    }  
    const type_upload = 7;
    await nomina_model.delete_data(type_upload);

    const chunk_size = 100;
    for (let index = 0; index < data.length; index = index + chunk_size) {
        let time_start = Date.now()
        general_helper.showDetails("Saving " + chunk_size + " files");
        let time_end = Date.now()
        const chunk = data.slice(index, index + chunk_size);
        await nomina_model.save_chunk(chunk)
        general_helper.showDetails("Finish save. Duration : " + ( (time_end - time_start) / 1000 )  + " seconds");
        await sleep(500);
    }

    return true;
}


function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
  }


  
