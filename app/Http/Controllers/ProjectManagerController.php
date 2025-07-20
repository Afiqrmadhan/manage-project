<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\ProjectModel;
use App\Models\HistoryModel;
use App\Models\FeatureUATModel;
use App\Models\DocsHistoryModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class ProjectManagerController extends Controller
{
    protected $sessionData;
    protected $userModel;
    protected $projectModel;
    protected $historyModel;
    protected $featureModel;
    protected $docsHistoryModel;

    public function __construct()
    {
        $this->sessionData = [
            'username' => session('username'),
            'level'    => (session('level') == 1) ? 'Admin' : 'Project Manager'
        ];

        // Inisialisasi model
        $this->userModel    = new UserModel();
        $this->projectModel = new ProjectModel();
        $this->historyModel = new HistoryModel();
        $this->featureModel = new FeatureUATModel();
        $this->docsHistoryModel = new DocsHistoryModel();
    }

    // Method untuk menampilkan halaman dashboard project manager
    public function index(Request $request)
    {
        $userId = session('id'); // Ambil ID user yang login
        $keyword = $request->get('search'); // Ambil kata kunci pencarian
    
        // Hitung total proyek yang diassign ke Project Manager
        $totalProjects = $this->projectModel->where('ProjectManagerId', $userId)->count();
    
        // Hitung proyek yang sudah "Finished"
        $finishedProjects = $this->historyModel
            ->where('ProjectManagerId', $userId)
            ->where('Status', 'Finish')
            ->count();
    
        // Ambil proyek dengan status "On Progress" sesuai pencarian
        if ($keyword) {
            $historyProjects = $this->historyModel
                ->where('ProjectManagerId', $userId)
                ->where('Status', 'On Progress')
                ->where('Title', 'like', '%' . $keyword . '%') // Filter berdasarkan keyword di Title
                ->get();
        } else {
            $historyProjects = $this->historyModel
                ->where('ProjectManagerId', $userId)
                ->where('Status', 'On Progress')
                ->get();
        }
    
        // Kirim data ke view
        $data = [
            'historyprojects'  => $historyProjects,
            'totalProjects'    => $totalProjects,
            'finishedProjects' => $finishedProjects,
            'search'           => $keyword, // Kirimkan keyword ke view
        ];
    
        return view('projectmanager.dashboard', array_merge($this->sessionData ?? [], $data));
    }

    // Method untuk menampilkan form tambah project
    public function addNewProject()
    {
        $data['projectManagers'] = $this->userModel->getProjectManagers();
        return view('projectmanager.addnewproject', array_merge($this->sessionData ?? [], $data));
    }

    // Method untuk menyimpan data project baru
    public function store(Request $request)
    {
        // Ambil data dari form
        $ProjectManager   = $request->input('ProjectManager');
        $Title           = $request->input('Title');
        $ClientCompany  = $request->input('ClientCompany');
        $ClientName      = $request->input('ClientName');
        $ProjectSchedule = $request->input('ProjectSchedule');
    
        // Validasi input
        if (empty($ProjectManager) || empty($Title) || empty($ClientCompany) || empty($ClientName) || empty($ProjectSchedule)) {
            return redirect()->back()->with('error', 'All fields are required!')->withInput();
        }
    
        try {
            // Panggil procedure untuk menyimpan data
            $this->projectModel->addProjectUsingProcedure($ProjectManager, $Title, $ClientCompany, $ClientName, $ProjectSchedule);
            return redirect()->back()->with('success', 'Project successfully added!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred: ' . $e->getMessage());
        }
    }
    
    // Method untuk menampilkan daftar project
    public function listProject(Request $request)
    {
        $userId = session('id'); // Ambil ID user yang login
        $keyword = $request->get('search'); // Ambil kata kunci pencarian
    
        if ($keyword) {
            $projects = $this->projectModel
                ->where('ProjectManagerId', $userId)
                ->where('Title', 'like', '%' . $keyword . '%')
                ->get();
        } else {
            $projects = $this->projectModel
                ->where('ProjectManagerId', $userId)
                ->get();
        }
    
        // Kirimkan nilai pencarian ke view
        $data = [
            'projects' => $projects,
            'search'   => $keyword, // Tambahkan ini agar bisa digunakan di view
        ];
    
        return view('projectmanager.listproject', array_merge($this->sessionData ?? [], $data));
    }

    // Method untuk menampilkan halaman fitur project
    public function manageProject($projectId = null)
    {
        // Jika tidak ada projectId, redirect ke list project
        if (!$projectId) {
            return redirect()->route('projectmanager.listproject')->with('error', 'Please select a project to manage!');
        }

        $project = $this->projectModel->find($projectId);

        if (!$project) {
            return redirect()->route('projectmanager.listproject')->with('error', 'Project not found!');
        }

        $features = $this->featureModel->where('ProjectId', $projectId)->get();

        return view('projectmanager.manageproject', array_merge($this->sessionData ?? [], [
            'project' => $project,
            'features' => $features
        ]));
    }

    // Method untuk menyimpan fitur
    public function saveFeatures(Request $request)
    {
        $projectId = $request->input('ProjectId');
        $features  = $request->input('Feature'); // Diambil dari input name="Feature[]" pada manageproject.php

        // Pastikan fitur dikirim sebagai array
        if (!is_array($features) || empty($features)) {
            return redirect()->back()->with('error', 'Features are not empty!');
        }

        try {
            foreach ($features as $feature) {
                $this->featureModel->create([
                    'ProjectId' => $projectId,
                    'Feature'   => $feature
                ]);
            }

            return redirect()->route('projectmanager.featureuat', $projectId)
                           ->with('success', 'Features saved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error occurred: ' . $e->getMessage());
        }
    }

    // Method untuk mengupdate fitur tersimpan
    public function updateFeature(Request $request, $id)
    {
        $feature = $request->input('Feature');

        if (empty($feature)) {
            return redirect()->back()->with('error', 'Feature cannot be empty!');
        }

        $this->featureModel->where('id', $id)->update(['Feature' => $feature]);
        return redirect()->back()->with('success', 'Feature updated successfully!');
    }

    // Method untuk menghapus fitur tersimpan
    public function deleteFeature($id)
    {
        $this->featureModel->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Feature successfully removed!');
    }

    // Method untuk menampilkan dan mengupdate fitur UAT yang sudah disimpan
    public function featureUAT(Request $request, $projectId = null)
    {
        if($request->isMethod('PUT')) {
            $features = $request->all();
            foreach ($features['FeatureId'] as $key => $featureId) {
                $this->featureModel->where('id', $featureId)->update([
                    'UATDate'            => $features['UATDate'][$key],
                    'ValidationStatus'   => $features['ValidationStatus'][$key],
                    'ClientFeedbackStatus'=> $features['ClientFeedbackStatus'][$key],
                    'RevisionNotes'       => $features['RevisionNotes'][$key]
                ]);
            }
            return redirect()->back()->with('success', 'UAT data saved successfully!');
        }
        
        // Ambil data proyek dan fitur UAT berdasarkan ProjectId
        $data['features'] = $this->featureModel->where('ProjectId', $projectId)->get();

        // Ambil Informasi Project
        $data['project'] = $this->projectModel->find($projectId);

        if (!$data['project']) {
            return redirect()->back()->with('error', 'Project not found!');
        }
        
        return view('projectmanager.featureuat', array_merge($this->sessionData ?? [], $data));
    }

    // Method untuk menampilkan history project
    public function historyProject(Request $request)
    {
        $userId = session('id'); // Ambil ID user yang login
        $keyword = $request->get('search'); // Ambil kata kunci pencarian
    
        if ($keyword) {
            $historyProjects = $this->historyModel
                ->where('ProjectManagerId', $userId)
                ->where('Title', 'like', '%' . $keyword . '%')
                ->get();
        } else {
            $historyProjects = $this->historyModel
                ->where('ProjectManagerId', $userId)
                ->get();
        }

        // Ambil data dokumen history
        $docsHistory = [];
        foreach ($historyProjects as $history) {
            $docsHistory[$history['Id']] = $this->docsHistoryModel->where('Id', $history['Id'])->get();
        }
    
        $data = [
            'historyprojects' => $historyProjects,
            'search'          => $keyword, // Kirimkan keyword ke view
        ];
    
        return view('projectmanager.history', array_merge($this->sessionData ?? [], $data));
    }
    
    // Method untuk mengupdate history project
    public function updateHistoryProject(Request $request, $Id = null)
    {
        if ($request->isMethod('PUT')) {
            $Id = $request->input('Id');
            
            $pdfFile = $request->file('Document');
            $uploadPath = storage_path('app/public/uploads');
    
            // Perbarui status di historyModel
            $this->historyModel->where('id', $Id)->update([
                'Status' => $request->input('ProjectStatus'),
            ]);
    
            // Perbarui dokumen di docsHistoryModel
            if ($pdfFile && $pdfFile->isValid()) {
                $history = $this->historyModel->find($Id);
                if ($history) {
                    $newName = $history->Title . '_' . date('Ymd') . '.pdf';
                    $pdfFile->storeAs('public/uploads', $newName);
    
                    $this->docsHistoryModel->create([
                        'ProjectId' => $history->ProjectId,
                        'Title'     => $history->Title,
                        'Document'  => $newName,
                    ]);
                }
            }
    
            return redirect()->route('projectmanager.history')->with('success', 'Data successfully updated!');
        }
        
        $data['history'] = $this->historyModel->find($Id);
        $data['docshistory'] = $this->docsHistoryModel->where('ProjectId', $Id)->get();
        return view('projectmanager.updateproject', array_merge($this->sessionData ?? [], $data));
    }

    //Method untuk menampilkan daftar dokumen berdasarkan ProjectId
    public function docsHistory(Request $request, $Id = null)
    {
        $keyword = $request->get('search'); // Ambil kata kunci pencarian
        
        // Ambil data dokumen history berdasarkan ProjectId dan keyword pencarian
        if ($keyword) {
            $docsHistory = $this->docsHistoryModel
                ->where('ProjectId', $Id)
                ->where('DateAdded', 'like', '%' . $keyword . '%')
                ->get();
        } else {
            $docsHistory = $this->docsHistoryModel
                ->where('ProjectId', $Id)
                ->get();
        }

        // Ambil judul project
        $project = $this->projectModel->find($Id);
        
        // Kirim data ke view
        $data = [
            'docshistory'  => $docsHistory,
            'search'       => $keyword, // Kirimkan keyword ke view
            'Id'           => $Id,
            'projectTitle' => $project->Title ?? '',
        ];
    
        return view('projectmanager.list_document', array_merge($this->sessionData ?? [], $data));
    }

    // Method untuk download file
    public function download($Id)
    {
        $doc = $this->docsHistoryModel->find($Id);

        if (!$doc || empty($doc->Document)) {
            return redirect()->back()->with('error', 'Document not found!');
        }

        $filePath = storage_path('app/public/uploads/' . $doc->Document);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found!');
        }

        return response()->download($filePath, $doc->Document);
    }

    // Method untuk generate UAT
    public function generatePDF($projectId)
    {
        // Ambil data proyek dan fitur UAT berdasarkan ID proyek
        $project = $this->projectModel->find($projectId);
        $features = $this->featureModel->where('ProjectId', $projectId)->get();

        if (!$project) {
            return redirect()->route('projectmanager.listproject')->with('error', 'Project not found!');
        }

        // Load view ke dalam variabel
        $data = [
            'project' => $project,
            'features' => $features
        ];

        $html = view('projectmanager.pdf_view', $data)->render();

        // Konfigurasi DomPDF
        $options = new \Dompdf\Options();
        $options->set('defaultFont', 'Courier');
        $dompdf = new \Dompdf\Dompdf($options);
        
        // Load HTML ke DomPDF
        $dompdf->loadHtml($html);
        
        // Set ukuran kertas dan orientasi
        $dompdf->setPaper('letter', 'landscape');
        
        // Render PDF
        $dompdf->render();

        //Ganti nama file PDF sesuai dengan judul proyek
        $filename = str_replace(' ', '_', $project->Title) . '_UAT_Report.pdf';
        
        // Download PDF
        return response($dompdf->output())
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}