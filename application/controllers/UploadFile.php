<?php

use Restserver\Libraries\REST_Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Importamos la librería CodeIgniter Rest Controller
 */
require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';
require APPPATH . 'libraries/validators/FormPersonalValidator.php';
/**
 * Extendemos del controlador Rest Controller
 */
class UploadFile extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UploadFileModel');
        $this->load->library('validators/ErrorValidator');
    }

    public function uploadFile_post()
    {
        try { 
            COMMON::setInitValues(['key'=>'memory_limit','value'=>'1000M']);
            $result['file_uploads'] = $this->UploadFileModel->get_file_uploads();
            COMMON::sendResponse($result);
        } catch (Exception $error) {
            ErrorHandler::catch($error);
            COMMON::sendResponse(null, '0', $this->lang->line("error_aplication"), true, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function addFiles_post(){
        COMMON::setInitValues(['key' => 'memory_limit', 'value' => '512M']); 
        COMMON::setInitValues(['key' => 'max_execution_time', 'value' => '300']);

        $archivos = GlobalLoader::getGlobalFiles();
        $fileInfo = $archivos['file'];
        $timeLoad = date("Y-m-d H:i:s", now());
        $fileBlob = file_get_contents($fileInfo["tmp_name"]);

        $dataFileToInsert = array(
            'documento' => "Archivo Alcaldia",
            'fecha_carga' => $timeLoad,
            'estado' => 1,
            'nombre_file' => $fileInfo["name"],
            'tipo_file' => $fileInfo["type"],
            'file' => $fileBlob
        );

        $idSave = $this->UploadFileModel->saveFile($dataFileToInsert);
        COMMON::sendResponse($idSave);
    }

    public function downloadFile_get($idFile){
        $file = $this->UploadFileModel->getFile($idFile);
        if ($file['file'] == false) {
            return false;
        }
        header('Content-Type: '.$file['tipo_file']);
        header('Content-Disposition: attachment; filename="' . $file['nombre_file']);
        header('Content-Length: ' . strlen($file['file']));
        echo $file['file'];
    }


}