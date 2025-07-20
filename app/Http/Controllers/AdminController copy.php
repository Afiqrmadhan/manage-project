<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\ProjectModel;
use App\Models\HistoryModel;
use App\Models\DocsHistoryModel;


class AdminController extends BaseController
{
    protected $session;
    protected $sessionData;
    protected $userModel;
    protected $projectModel;
    protected $historyModel;
    protected $docsHistoryModel;

    public function __construct()
    {
        // Inisialisasi model
        $this->session = session();
        $this->userModel = new UserModel();
        $this->projectModel = new ProjectModel();
        $this->historyModel = new HistoryModel();
        $this->docsHistoryModel = new DocsHistoryModel();

        $this->sessionData = [
            'username' => $this->session->get('username'),
            'level' => ($this->session->get('level') == 1) ? 'Admin' : 'Project Manager'
        ];
    }

    // Method untuk menampilkan halaman dashboard admin
    public function index()
    {
        $keyword = $this->request->getGet('search'); // Ambil kata kunci pencarian
    
        // Hitung total proyek yang diassign ke Project Manager
        $totalProjects = $this->projectModel->countAllResults();
    
        // Hitung proyek yang sudah "Finished"
        $finishedProjects = $this->historyModel
            ->where('Status', 'Finish')
            ->countAllResults();
    
        // Ambil proyek dengan status "On Progress" sesuai pencarian
        if ($keyword) {
            $historyProjects = $this->historyModel
                ->where('Status', 'On Progress')
                ->like('Title', $keyword) // Filter berdasarkan keyword di Title
                ->findAll();
        } else {
            $historyProjects = $this->historyModel
                ->where('Status', 'On Progress')
                ->findAll();
        }
    
        // Kirim data ke view
        $data = [
            'historyprojects'  => $historyProjects,
            'totalProjects'    => $totalProjects,
            'finishedProjects' => $finishedProjects,
            'search'           => $keyword, // Kirimkan keyword ke view
        ];
    
        return view('admin/dashboard', array_merge($this->sessionData ?? [], $data));
    }

    // Method untuk menampilkan form tambah project
    public function addNewProject()
    {
        $data['projectManagers'] = $this->userModel->getProjectManagers();
        
        return view('admin/addnewproject', array_merge($this->sessionData ?? [], $data));;
    }

    // Method untuk menyimpan data project baru
    public function store()
    {
        // Ambil data dari form
        $ProjectManager = $this->request->getPost('ProjectManager');
        $Title = $this->request->getPost('Title');
        $ClientCompany  = $this->request->getPost('ClientCompany');
        $ClientName = $this->request->getPost('ClientName');
        $ProjectSchedule = $this->request->getPost('ProjectSchedule');

        // Validasi input
        if (empty($ProjectManager) || empty($Title) || empty($ClientCompany) || empty($ClientName) || empty($ProjectSchedule)) {
            $this->session->setFlashdata('error', 'All fields are required!');
            return redirect()->back()->withInput();
        }

        try {
            // Panggil procedure untuk menyimpan data
            $this->projectModel->addProjectUsingProcedure($ProjectManager, $Title, $ClientCompany, $ClientName, $ProjectSchedule);
            
            // Set flash message sukses
            $this->session->setFlashdata('success', 'Project successfully added!');
        } catch (\Exception $e) {
            // Set flash message error jika terjadi kesalahan
            $this->session->setFlashdata('error', 'Error occurred: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    // Method untuk menampilkan history project
    public function historyProject()
    {
        $keyword = $this->request->getGet('search'); // Ambil kata kunci pencarian
    
        if ($keyword) {
            $historyProjects = $this->historyModel
                ->like('Title', $keyword)
                ->findAll();
        } else {
            $historyProjects = $this->historyModel
                ->findAll();
        }

        // Ambil data dokumen history
        $docsHistory = [];
        foreach ($historyProjects as $history) {
            $docsHistory[$history['Id']] = $this->docsHistoryModel->where('Id', $history['Id'])->findAll();
        }
    
        $data = [
            'historyprojects' => $historyProjects,
            'search'          => $keyword, // Kirimkan keyword ke view
        ];
    
        return view('admin/history', array_merge($this->sessionData ?? [], $data));
    }

    // Method untuk mengupdate history project
    public function updateHistoryProject($Id = null)
    {
        if ($this->request->getMethod() == 'PUT') {
            $Id = $this->request->getPost('Id');
            
            $pdfFile = $this->request->getFile('Document');
            $uploadPath = WRITEPATH . 'uploads';

            // Perbarui status di historyModel
            $this->historyModel->update($Id, [
                'Status' => $this->request->getPost('ProjectStatus')
            ]);

            // Perbarui dokumen di docsHistoryModel
            if ($pdfFile && $pdfFile->isValid()) {
                $history = $this->historyModel->find($Id);
                if ($history) {
                    $newName = $history['Title'] . '_' . date('Ymd') . '.pdf';
                    $pdfFile->move($uploadPath, $newName);
    
                    $this->docsHistoryModel->insert([
                        'ProjectId' => $history['ProjectId'],
                        'Title'     => $history['Title'],
                        'Document'  => $newName,
                    ]);
                }
            }
    
            return redirect()->to('/admin/history')->with('success', 'Data successfully updated!');
        }
        
        $data['history'] = $this->historyModel->find($Id);
        $data['docshistory'] = $this->docsHistoryModel->where('ProjectId', $Id)->findAll();
        return view('admin/updateproject', array_merge($this->sessionData ?? [], $data));
    }


    // Method untuk menampilkan daftar dokumen berdasarkan ProjectId
    public function docsHistory($Id = null)
    {
        $keyword = $this->request->getGet('search'); // Ambil kata kunci pencarian
        
        // Ambil data dokumen history berdasarkan ProjectId
        if ($keyword) {
            $docsHistory = $this->docsHistoryModel
                ->where('ProjectId', $Id)
                ->like('DateAdded', $keyword)
                ->findAll();
        } else {
            $docsHistory = $this->docsHistoryModel
                ->where('ProjectId', $Id)
                ->findAll();
        }

        // Ambil judul project
        $project = $this->projectModel->find($Id);

        // Kirim data ke view
        $data = [
            'docshistory' => $docsHistory,
            'search'      => $keyword, // Kirimkan keyword ke view
            'Id'          => $Id,
            'projectTitle' => $project['Title'],
        ];
    
        return view('admin/list_document', array_merge($this->sessionData ?? [], $data));
    }

    // Method untuk download file
    public function download($Id)
    {
        $doc = $this->docsHistoryModel->find($Id);

        if (!$doc || empty($doc['Document'])) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }

        $filePath = WRITEPATH . 'uploads/' . $doc['Document'];

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan.');
        }

        return $this->response->download($filePath, null)->setFileName($doc['Document']);
    }
}