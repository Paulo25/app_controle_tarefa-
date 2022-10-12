@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Atualizar tarefa</div>

                    <div class="card-body">
                        <form method="post" action="{{route('tarefa.update', ['tarefa' => $tarefa->id])}}">
                        @csrf
                        @method('PUT')
                            <div class="mb-3">
                                <label class="form-label">Tarefa</label>
                                <input type="text" class="form-control" name="tarefa" value="{{$tarefa->tarefa}}">
                                <span style="color:red">{{$errors->has('tarefa') ? $errors->first('tarefa') : ''}}</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Data limite conclusão</label>
                                <input type="date" class="form-control" name="data_limite_conclusao" value="{{$tarefa->data_limite_conclusao}}">
                                <span style="color:red">{{$errors->has('data_limite_conclusao') ? $errors->first('data_limite_conclusao') : ''}}</span>
                            </div>
                             <div class="float-right">
                            <a class="btn btn-primary" href="{{route('tarefa.index')}}">Voltar</a>
                            <button type="submit" class="btn btn-success">Atualizar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
