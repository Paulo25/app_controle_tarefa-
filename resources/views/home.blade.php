@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 alert alert-success" role="alert">
                Bem-vindo(a) {{ auth()->user()->name }}, você está logado(a)! 
                <p>Navegue no menu ao lado para acessar outras páginas do sistema.</p>
            </div>
            <div class="col-md-8">

                <div class="card">
                    <div class="card-header">Menu do sistema</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div>
                            <ul class="list-group">
                                <li class="list-group-item"><a href="{{ route('tarefa.index') }}">1 - TAREFA</a></li>
                            </ul>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
