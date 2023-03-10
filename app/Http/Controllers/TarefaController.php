<?php

namespace App\Http\Controllers;

use App\Exports\TarefasExport;
use App\Models\Tarefa;
use Illuminate\Http\Request;
use App\Mail\NovaTarefaMail;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class TarefaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $id = auth()->user()->id;
        // $nome = Auth::user()->name;
        // $email = Auth::user()->email;

        // return "ID: $id | Nome: $nome | E-mail: $email";

        // if(auth()->check()){
        //     $id = auth()->user()->id;
        //     $nome = Auth::user()->name;
        //     $email = Auth::user()->email;

        //     return "ID: $id | Nome: $nome | E-mail: $email";
        // }else{
        //     return 'usuário deve está autenticado para acessar está rota.';
        // }

        $idUserLogged = auth()->user()->id;
        $tarefas = Tarefa::with('user')->where('user_id', $idUserLogged)->orderBy('created_at', 'desc')->paginate(10);

        return view('tarefa.index', compact('tarefas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return view('tarefa.create');
        return view('tarefa.mpdf');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $regras = [
            'tarefa' => 'required|max:200|min:5',
            'data_limite_conclusao' => 'required'
        ];

        $feedback = [
            'required' => 'O campo :attribute deve ser preenchido',
            'max' =>  'O campo nome deve ter no máximo 200 caracteres',
            'min' => 'O campo nome deve ter no mínimo 3 caracteres'
        ];

        $request->validate($regras, $feedback);

        $dados = $request->all('tarefa', 'data_limite_conclusao');
        $dados['user_id'] = auth()->user()->id;

        $tarefa = Tarefa::create($dados);

        $destinatario = auth()->user()->email; //email do usuário autenticado
        Mail::to($destinatario)->send(new NovaTarefaMail($tarefa));

        return redirect()->route('tarefa.show', [$tarefa->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Tarefa $tarefa)
    {
        return view('tarefa.show', compact('tarefa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarefa $tarefa)
    {
        if ($tarefa->user_id != auth()->user()->id) {
            return redirect()->route('acesso.negado');
        }
        return view('tarefa.edit', compact('tarefa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        if ($tarefa->user_id == auth()->user()->id) {
            return redirect()->route('acesso.negado');
        }
        $tarefa->update($request->all());

        return redirect()->route('tarefa.show', ['tarefa' => $tarefa->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarefa $tarefa)
    {
        if ($tarefa->user_id != auth()->user()->id) {
            return redirect()->route('acesso.negado');
        }

        $tarefa->delete();
        return redirect()->route('tarefa.index');
    }

    public function exportacao(String $extensao = null)
    {
        if (!in_array(strtolower($extensao), ['xlsx', 'csv', 'pdf'])) {
            return redirect()->route('tarefa.index');
        }
        
        $mpdf = new \Mpdf\Mpdf();
        $html = view('tarefa.mpdf')->render();
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->WriteHTML(file_get_contents('css/mpdf.css'), 1);

        // $mpdf->SetHeader('Chapter 1 | Country list|{PAGENO}');
        // $mpdf->SetHeader('
        // <div class="border" >
        // <div  style="text-align: left;">
        //  <img style="width: 133px; height: 30px;" class="logo" src="https://agenciavirtual.guarida.com.br/assets/img/logoHeaderNew.png" alt="" /> 
        // </div>
  
        // <div class="column-right">
        //     <h3>ATA DA ASSEMBLEIA EXTRAORDINÁRIA
        //      CONDOMÍNIO SAINT PAUL
        //      RUA/AV . ANTONIO CARNEIRO PINTO, CORONEL, 63, CEP
        //      90460020
        //      PORTO ALEGRE/RS
        //  </h3>
        // </div>
        // </div>');

        $data = 'ASSEMBLEIA EXTRAORDINÁRIA';

        $mpdf->SetHeader('
        <img style="width: 133px; height: 30px;" class="logo" src="https://agenciavirtual.guarida.com.br/assets/img/logoHeaderNew.png" alt="" />
        <h3 style="margin-bottom:0px;" text-transform: uppercase; color: #0e3d73;>
        ATA DA ASSEMBLEIA EXTRAORDINÁRIA <br/>  
        CONDOMÍNIO SAINT PAUL,
        RUA/AV. ANTONIO CARNEIRO PINTO, CORONEL, 63 <br/> CEP
        90460020,
        PORTO ALEGRE/RS
        </h3>');
        $mpdf->SetFooter('página.{PAGENO}');
        

        $mpdf->showWatermarkText = true;
        $mpdf->AddPage('P', 
            '', 
            '', 
            '', 
            '',
            10, // margin_left
            10, // margin right
            45, // margin top
            10, // margin bottom
            5, // margin header
            1); //margin footer
        
            $mpdf->WriteHTML($html);
            $mpdf->Output();

        //return Excel::download(new TarefasExport, "lista_de_tarefas.$extensao");
    }

    public function exportacaoPDF(){
        $tarefas = auth()->user()->tarefas()->get();
        $pdf = PDF::loadView('tarefa.pdf', ['tarefas' => $tarefas]);

        /*tipo de papel: a4 ou letter*/ /* orientação: landscape(paisagem) ou portrait(retrato) */
        $pdf->setPaper('a4', 'landscape'); 
        
        // return $pdf->download('lista_de_tarefas.pdf');
        return $pdf->stream('lista_de_tarefas.pdf');
    }
}
