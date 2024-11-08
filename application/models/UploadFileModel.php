<?php
class UploadFileModel extends CI_Model
{

    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database("development", TRUE);
    }

    public function get_file_uploads() {
        $this->db->select('
            id_file_uploads,
            documento,
            fecha_carga,
            if(estado=1,"Activo","Inactivo") as estado,
            nombre_file,
            tipo_file
        ');
        $this->db->from('file_uploads');
        $data = $this->db->get();
        return $data? $data->result_array() : [];
    }
    
    public function saveFile($data)
    {
        $this->db->insert('file_uploads', $data);
        return $this->db->insert_id();
    }

    public function getFile($idFile)
    {
        $this->db->select('file,tipo_file,nombre_file');
        $this->db->from('file_uploads');
        $this->db->where('id_file_uploads', $idFile);
        $query = $this->db->get();
        return $query? $query->result_array()[0] : [];
    }

}
