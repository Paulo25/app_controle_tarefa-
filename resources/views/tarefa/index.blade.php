@extends('layouts.app')

<style>
.table td#acoes{
    padding-left: 0.0rem;
    padding-right: 0.1rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
  }
</style>

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Tarefas
                        </div>
                            <div class="col-6" >
                            <div class="float-right" >
                                  <a class="btn btn-success mr-3" href="{{ route('tarefa.create') }}" title="Criar tarefa">Novo</a>
                                   <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Exportar
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                      <a class="dropdown-item" href="{{route('tarefa.exportacao', ['extensao' => 'xlsx'])}}">XLSX</a>
                                      <a class="dropdown-item" href="{{route('tarefa.exportacao', ['extensao' => 'csv'])}}">CSV</a>
                                      <a class="dropdown-item" href="{{route('tarefa.exportacao', ['extensao' => 'pdf'])}}">PDF</a>
                                      <a class="dropdown-item" href="{{route('tarefa.exportacao-pdf')}}" target="_blank">PDF V2</a>
                                    </div>
                                  </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <div class="card-body">
                        {{-- <div style="margin-bottom: 20px;">
                            <a class="btn btn-success" href="{{ route('tarefa.create') }}" title="Criar tarefa">Adicionar</a>
                            <a class="btn btn-primary float-right" href="{{ route('tarefa.exportacao') }}" title="Exportar XLSX">Exportar</a>
                        </div> --}}

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Tarefa</th>
                                    <th scope="col">Data limite de conclusão</th>
                                    <th scope="col" colspan="2" style="text-align:center;">Ações</th>
                                </tr>
                            </thead>
                            @foreach ($tarefas as $key => $tarefa)
                                <tbody>
                                    <tr>
                                        <th scope="row">{{ $tarefa->id }}</th>
                                        <td>{{ $tarefa->tarefa }}</td>
                                        <td>{{ date('d/m/Y', strtotime($tarefa->data_limite_conclusao)) }}</td>
                                        <td id="acoes">
                                            <a class="btn btn-warning"
                                                href="{{ route('tarefa.edit', ['tarefa' => $tarefa->id]) }}">
                                                Editar</a>
                                        </td>
                                         <td id="acoes">
                                            <form id="form_{{ $tarefa->id }}" method="post"
                                                action="{{ route('tarefa.destroy', ['tarefa' => $tarefa->id]) }}">
                                                @method('DELETE')
                                                @csrf
                                            </form>
                                            <a class="btn btn-danger"
                                                onclick="document.getElementById('form_{{ $tarefa->id }}').submit()"
                                                href="#"> Excluir</a>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                        {{-- {{ $tarefas->links() }} --}}

                        <div class="row justify-content-center">
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $tarefas->previousPageUrl() }}">Voltar</a></li>
                                    @for ($i = 1; $i <= $tarefas->lastPage(); $i++)
                                        <li class="page-item {{ $tarefas->currentPage() == $i ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $tarefas->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $tarefas->nextPageUrl() }}">Avançar</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

  
