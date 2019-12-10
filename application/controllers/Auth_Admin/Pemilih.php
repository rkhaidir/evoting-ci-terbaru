<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemilih extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model("PemilihModel");
        $this->load->model('AdminModel');
    }

    public function index() {
        $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
        $pemilih['pemilih'] = $this->PemilihModel->showPemilih()->result_array();
        $data['title'] = "Data Pemilih - E-voting";
        $this->load->view('layout/header_layout', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('content/pemilih', $pemilih, $data);
		$this->load->view('layout/footer_layout');
    }

    public function tambahPemilih() {
        if($this->input->post('simpan-pemilih')) {
            $this->form_validation->set_message('required', 'Tidak Boleh Kosong');
            $this->form_validation->set_message('min_length', 'Panjang harus 18 karakter');
            $this->form_validation->set_rules('nopemilih', 'Nomor Pemilih', 'trim|required|min_length[18]');
            $this->form_validation->set_rules('namapemilih', 'Nama Pemilih', 'trim|required');
            $this->form_validation->set_rules('jnspegawai', 'Jenis Pegawai', 'required');
            $this->form_validation->set_rules('gelardpn', 'Gelar Depan', 'trim|required');
            $this->form_validation->set_rules('gelars3', 'Gelar S3', 'trim|required');
            $this->form_validation->set_rules('gelarblkng', 'Gelar Belakang', 'trim|required');
            $this->form_validation->set_rules('jkelamin', 'Jenis Kelamin', 'trim|required');
            $this->form_validation->set_rules('nidn', 'NIDN/NDIK/NUPN', 'trim|required|numeric');
            $this->form_validation->set_rules('pendterakhir', 'Pendidikan Terakhir', 'trim|required');
            $this->form_validation->set_rules('golterakhir', 'Golongan Terakhir', 'trim|required');
            $this->form_validation->set_rules('jabterakhir', 'Jabatan Terakhir', 'trim|required');
            $this->form_validation->set_rules('prodi', 'Program Studi', 'trim|required');

            if($this->form_validation->run() == FALSE) {
                $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
                if($data['user']['role_id'] != 1) {
                    redirect('Auth_Admin/Beranda');
                }
                $data['title'] = "Data Pemilih - E-voting";
                $this->load->view('layout/header_layout', $data);
                $this->load->view('layout/sidebar', $data);
                $this->load->view('layout/topbar', $data);
                $this->load->view('content/pemilih_tambah');
                $this->load->view('layout/footer_layout'); 
            } else {
                $config['upload_path'] = './assets/img/img-pemilih';
                $config['allowed_types'] = 'jpg|png|JPG|PNG';
                $config['max_size'] = '4096';

                $this->load->library('upload', $config);
                if($this->upload->do_upload('foto')) {
                    $file = array('upload_data' => $this->upload->data());

                    $jenispegawai = $this->input->post('jnspegawai');
                    $nopemilih = $this->input->post('nopemilih');
                    $namapemilih = $this->input->post('namapemilih');
                    $gelardpn = $this->input->post('gelardpn');
                    $gelars3 = $this->input->post('gelars3');
                    $gelarblkng = $this->input->post('gelarblkng');
                    $jkelamin = $this->input->post('jkelamin');
                    $nidn = $this->input->post('nidn');
                    $pendterakhir = $this->input->post('pendterakhir');
                    $golterakhir = $this->input->post('golterakhir');
                    $jabterakhir = $this->input->post('jabterakhir');
                    $image = $file['upload_data']['file_name'];
                    $prodi = $this->input->post('prodi');
                    $verifikasi = "0";
                    $memilih = "0";

                    $data = array(
                        "pemilih_jenis_pegawai" => $jenispegawai,
                        "pemilih_nomor" => $nopemilih,
                        "pemilih_nama" => $namapemilih,
                        "pemilih_gelar_depan" => $gelardpn,
                        "pemilih_gelar_s3" => $gelars3,
                        "pemilih_gelar_belakang" => $gelarblkng,
                        "pemilih_jk" => $jkelamin,
                        "pemilih_nidn" => $nidn,
                        "pemilih_pend_akhir" => $pendterakhir,
                        "pemilih_golongan" => $golterakhir,
                        "pemilih_jabatan" => $jabterakhir,
                        "pemilih_foto" => $image,
                        "pemilih_prodi" => $prodi,
                        "pemilih_verifikasi" => $verifikasi,
                        "pemilih_status" => $memilih
                    );
                    $this->PemilihModel->insertPemilih($data);
                    redirect('/Auth_Admin/Pemilih','refresh');
                }
            }
        } else {
            $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
            if($data['user']['role_id'] != 1) {
                redirect('Auth_Admin/Beranda');
            }
            $data['title'] = "Data Pemilih - E-voting";
            $this->load->view('layout/header_layout', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('layout/topbar', $data);
            $this->load->view('content/pemilih_tambah');
            $this->load->view('layout/footer_layout'); 
        }
    }

    public function ubahPemilih($id) {
        if($this->input->post('ubah-pemilih')) {
            if(empty($_FILES['foto']['name'])) {
                $this->form_validation->set_message('required', 'Tidak Boleh Kosong');
                $this->form_validation->set_rules('nopemilih', 'Nomor Pemilih', 'trim|required|min_length[18]');
                $this->form_validation->set_rules('namapemilih', 'Nama Pemilih', 'trim|required');
                $this->form_validation->set_rules('jnspegawai', 'Jenis Pegawai', 'required');
                $this->form_validation->set_rules('gelardpn', 'Gelar Depan', 'trim|required');
                $this->form_validation->set_rules('gelars3', 'Gelar S3', 'trim|required');
                $this->form_validation->set_rules('gelarblkng', 'Gelar Belakang', 'trim|required');
                $this->form_validation->set_rules('jkelamin', 'Jenis Kelamin', 'trim|required');
                $this->form_validation->set_rules('nidn', 'NIDN/NDIK/NUPN', 'trim|required|numeric');
                $this->form_validation->set_rules('pendterakhir', 'Pendidikan Terakhir', 'trim|required');
                $this->form_validation->set_rules('golterakhir', 'Golongan Terakhir', 'trim|required');
                $this->form_validation->set_rules('jabterakhir', 'Jabatan Terakhir', 'trim|required');
                $this->form_validation->set_rules('prodi', 'Program Studi', 'trim|required');

                if($this->form_validation->run() == FALSE) {
                    $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
                    if($data['user']['role_id'] != 1) {
                        redirect('Auth_Admin/Beranda');
                    }
                    $pemilih['pemilih'] = $this->PemilihModel->getPemilih($id)->row_array();
                    $data['title'] = "Data Pemilih - Administrator";
                    $this->load->view('layout/header_layout', $data);
                    $this->load->view('layout/sidebar', $data);
                    $this->load->view('layout/topbar', $data);
                    $this->load->view('content/pemilih_ubah', $pemilih);
                    $this->load->view('layout/footer_layout'); 
                } else {
                    
                    $jenispegawai = $this->input->post('jnspegawai');
                    $nopemilih = $this->input->post('nopemilih');
                    $namapemilih = $this->input->post('namapemilih');
                    $gelardpn = $this->input->post('gelardpn');
                    $gelars3 = $this->input->post('gelars3');
                    $gelarblkng = $this->input->post('gelarblkng');
                    $jkelamin = $this->input->post('jkelamin');
                    $nidn = $this->input->post('nidn');
                    $pendterakhir = $this->input->post('pendterakhir');
                    $golterakhir = $this->input->post('golterakhir');
                    $jabterakhir = $this->input->post('jabterakhir');
                    $verifikasi = $this->input->post('verifikasi');
                    $memilih = $this->input->post('memilih');
                    $prodi = $this->input->post('prodi');
                    $object = array(
                        "pemilih_jenis_pegawai" => $jenispegawai,
                        "pemilih_nomor" => $nopemilih,
                        "pemilih_nama" => $namapemilih,
                        "pemilih_gelar_depan" => $gelardpn,
                        "pemilih_gelar_s3" => $gelars3,
                        "pemilih_gelar_belakang" => $gelarblkng,
                        "pemilih_jk" => $jkelamin,
                        "pemilih_nidn" => $nidn,
                        "pemilih_pend_akhir" => $pendterakhir,
                        "pemilih_golongan" => $golterakhir,
                        "pemilih_jabatan" => $jabterakhir,
                        "pemilih_prodi" => $prodi,
                        "pemilih_verifikasi" => $verifikasi,
                        "pemilih_status" => $memilih
                    );
                    $this->PemilihModel->updatePemilih($id, $object);
                    redirect('/Auth_Admin/Pemilih','refresh');
                }
            } else {
                $this->form_validation->set_message('required', 'Tidak Boleh Kosong');
                $this->form_validation->set_rules('nopemilih', 'Nomor Pemilih', 'trim|required|min_length[18]');
                $this->form_validation->set_rules('namapemilih', 'Nama Pemilih', 'trim|required');
                $this->form_validation->set_rules('jnspegawai', 'Jenis Pegawai', 'required');
                $this->form_validation->set_rules('gelardpn', 'Gelar Depan', 'trim|required');
                $this->form_validation->set_rules('gelars3', 'Gelar S3', 'trim|required');
                $this->form_validation->set_rules('gelarblkng', 'Gelar Belakang', 'trim|required');
                $this->form_validation->set_rules('jkelamin', 'Jenis Kelamin', 'trim|required');
                $this->form_validation->set_rules('nidn', 'NIDN/NDIK/NUPN', 'trim|required|numeric');
                $this->form_validation->set_rules('pendterakhir', 'Pendidikan Terakhir', 'trim|required');
                $this->form_validation->set_rules('golterakhir', 'Golongan Terakhir', 'trim|required');
                $this->form_validation->set_rules('jabterakhir', 'Jabatan Terakhir', 'trim|required');
                $this->form_validation->set_rules('prodi', 'Program Studi', 'trim|required');
                if($this->form_validation->run() == FALSE) {
                    $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
                    if($data['user']['role_id'] != 1) {
                        redirect('Auth_Admin/Beranda');
                    }
                    $pemilih['pemilih'] = $this->PemilihModel->getPemilih($id)->row_array();
                    $data['title'] = "Data Pemilih - Administrator";
                    $this->load->view('layout/header_layout', $data);
                    $this->load->view('layout/sidebar', $data);
                    $this->load->view('layout/topbar', $data);
                    $this->load->view('content/pemilih_ubah', $pemilih);
                    $this->load->view('layout/footer_layout'); 
                } else {
                    $picture = $this->PemilihModel->getPemilih($id)->row_array();
                    unlink('./assets/img/img-pemilih'.$picture['pemilih_foto']);

                    $config['upload_path'] = './assets/img/img-pemilih';
                    $config['allowed_types'] = 'jpg|png|JPG|PNG';
                    $config['max_size'] = '4096';

                    $this->load->library('upload', $config);
                    if($this->upload->do_upload('foto')) {
                        $file = array('upload_data' => $this->upload->data());

                        $jenispegawai = $this->input->post('jnspegawai');
                        $nopemilih = $this->input->post('nopemilih');
                        $namapemilih = $this->input->post('namapemilih');
                        $gelardpn = $this->input->post('gelardpn');
                        $gelars3 = $this->input->post('gelars3');
                        $gelarblkng = $this->input->post('gelarblkng');
                        $jkelamin = $this->input->post('jkelamin');
                        $nidn = $this->input->post('nidn');
                        $pendterakhir = $this->input->post('pendterakhir');
                        $golterakhir = $this->input->post('golterakhir');
                        $jabterakhir = $this->input->post('jabterakhir');
                        $image = $file['upload_data']['file_name'];
                        $prodi = $this->input->post('prodi');
                        $verifikasi = "0";
                        $memilih = "0";

                        $obj2 = array(
                            "pemilih_jenis_pegawai" => $jenispegawai,
                            "pemilih_nomor" => $nopemilih,
                            "pemilih_nama" => $namapemilih,
                            "pemilih_gelar_depan" => $gelardpn,
                            "pemilih_gelar_s3" => $gelars3,
                            "pemilih_gelar_belakang" => $gelarblkng,
                            "pemilih_jk" => $jkelamin,
                            "pemilih_nidn" => $nidn,
                            "pemilih_pend_akhir" => $pendterakhir,
                            "pemilih_golongan" => $golterakhir,
                            "pemilih_jabatan" => $jabterakhir,
                            "pemilih_foto" => $image,
                            "pemilih_prodi" => $prodi,
                            "pemilih_verifikasi" => $verifikasi,
                            "pemilih_status" => $memilih
                        );
                        $this->PemilihModel->updatePemilih($id, $obj2);
                        redirect('/Auth_Admin/Pemilih','refresh');
                    }
                }
            }
        } else {
            $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
            if($data['user']['role_id'] != 1) {
                redirect('Auth_Admin/Beranda');
            }
            $pemilih['pemilih'] = $this->PemilihModel->getPemilih($id)->row_array();
            $data['title'] = "Data Pemilih - E-voting";
            $this->load->view('layout/header_layout', $data);
            $this->load->view('layout/sidebar', $data);
            $this->load->view('layout/topbar', $data);
            $this->load->view('content/pemilih_ubah', $pemilih);
            $this->load->view('layout/footer_layout'); 
        }
    }

    public function hapusPemilih($id) {
        $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
        if($data['user']['role_id'] != 1) {
            redirect('Auth_Admin/Beranda');
        }
        $this->PemilihModel->deletePemilih($id);
        redirect('/Auth_Admin/Pemilih','refresh');
    }

    public function importExcel() {
        $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
            if($data['user']['role_id'] != 1) {
                redirect('Auth_Admin/Beranda');
            }
        if($this->input->post('import-excel')) {
            
            $this->load->library(array('PHPExcel', 'PHPExcel/IOFactory'));
            $fileName = $_FILES['fileexcel']['name'];

            $config['upload_path'] = './assets/';
            $config['file_name'] = $fileName;
            $config['allowed_types'] = 'xlsx';
            $config['max_size'] = 102400;

            $this->load->library('upload');
            $this->upload->initialize($config);
            
            if($this->upload->do_upload('fileexcel')) {
                $media = $this->upload->data('fileexcel');
                $inputFileName = './assets/'.$this->upload->file_name;
                
                try {
                    $inputFileType = IOFactory::identify($inputFileName);
                    $objReader = IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                } catch(Exception $e) {
                    die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
                }

                if($this->input->post('hapus') == 1)
                {
                    $this->db->truncate('tb_pemilih');
                }

                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                
                for ($row = 2; $row <= $highestRow; $row++) 
                {                  //  Read a row of data into an array                 
                    $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                    //Sesuaikan sama nama kolom tabel di database                                
                    $data = array(
                        "pemilih_jenis_pegawai" => $rowData[0][0],
                        "pemilih_nomor"=> $rowData[0][1],
                        "pemilih_nama"=> $rowData[0][2],
                        "pemilih_gelar_depan" => $rowData[0][3],
                        "pemilih_gelar_s3" => $rowData[0][4],
                        "pemilih_gelar_belakang" => $rowData[0][5],
                        "pemilih_jk" => $rowData[0][6],
                        // "pemilih_nidn" => $rowData[0][7],
                        "pemilih_pend_akhir" => $rowData[0][7],
                        "pemilih_golongan" => $rowData[0][8],
                        "pemilih_jabatan" => $rowData[0][9],
                        "pemilih_prodi" => $rowData[0][10],
                        "pemilih_verifikasi"=> "0",
                        "pemilih_status"=> "0"
                    );

                    $this->PemilihModel->insertPemilih($data);
                }
                unlink('./assets/'.$this->upload->file_name);
                redirect('/Auth_Admin/Pemilih','refresh');
            }
        }
    }

    public function pemilihDetail($id) {
        $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
        $pemilih['pemilih'] = $this->PemilihModel->getPemilih($id)->row_array();
        $data['title'] = "Data Pemilih - E-voting";
        $this->load->view('layout/header_layout', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('content/pemilih_detail', $pemilih);
        $this->load->view('layout/footer_layout'); 
    }

    public function pemilihVerifikasi($id) {
        $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
        $pemilih['pemilih'] = $this->PemilihModel->getPemilih($id)->row_array();
        $data['title'] = "Data Pemilih - E-voting";
        $this->load->view('layout/header_layout', $data);
        $this->load->view('layout/sidebar', $data);
        $this->load->view('layout/topbar', $data);
        $this->load->view('content/pemilih_verifikasi', $pemilih);
        $this->load->view('layout/footer_layout'); 
    }

    public function verifikasi($id) {
        $verifikasi = "1";
        $object = array("pemilih_verifikasi" => $verifikasi);
        $this->PemilihModel->updatePemilih($id, $object);
        redirect('/Auth_Admin/Pemilih/pemilihVerifikasi/'.$id, 'refresh');
    }

    public function resetPemilih() {
        $data['user'] = $this->AdminModel->getAdmin($this->session->userdata('username'))->row_array();
        if($data['user']['role_id'] != 1) {
            redirect('Auth_Admin/Beranda');
        }
        $verifikasi = "0";
        $memilih = "0";
        $data = array("pemilih_verifikasi" => $verifikasi, "pemilih_status" => $memilih);
        $this->PemilihModel->resetPemilih($data);
        redirect('/Auth_Admin/Pemilih','refresh');
    }
}