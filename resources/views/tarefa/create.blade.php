@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Adcionar tarefa</div>

                    <div class="card-body">
                        <form method="post" action="{{route('tarefa.store')}}">
                        @csrf
                            <div class="mb-3">
                                <label class="form-label">Tarefa</label>
                                <input type="text" class="form-control" name="tarefa" value="{{old('tarefa')}}">
                                <span style="color:red">{{$errors->has('tarefa') ? $errors->first('tarefa') : ''}}</span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Data limite conclusão</label>
                                <input type="date" class="form-control" name="data_limite_conclusao" value="{{old('data_limite_conclusao')}}">
                                <span style="color:red">{{$errors->has('data_limite_conclusao') ? $errors->first('data_limite_conclusao') : ''}}</span>
                            </div>
                            <button type="submit" class="btn btn-primary">Cadastrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
