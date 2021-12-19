@extends("layouts.base")

@section('title')
Listado
@endsection
@section('content')
<div class="container mt-4">
    <div>
        <span><a href="{{ route("categories.index") }}" class=""> Categorías</a></span> /
        <span> Crear</span>
    </div>
    <br>

    <h1>Categorías</h1>
    <hr>
    <h2>Crear nueva categoría</h2>
    <form action="{{ route('categories.store') }}" method="POST" class="row" id="app-create-categories" v-on:submit.prevent="saveData">
        <div class="col-2 mt-2 mb-2">
            <label>Slug</label>
            <input name="slug"  required class="form-control" v-model="slug" placeholder="Ingrese un identificador...">
        </div>
        <div class="col-6 mt-2 mb-2">
            <label>Nombre</label>
            <input name="name" required class="form-control" v-model="name" placeholder="Ingrese un Nombre...">
        </div>
        <div class="col-4 mt-2 mb-2">
            <label>Idioma</label>
            <input name="idioma" class="form-control" v-model="language" placeholder="Ingrese el idioma del Evento...">
        </div>
        <div class="col-12 text-center mt-4">
        
            <button class="btn btn-success"><i class="fa fa-plus"></i> Guardar</button>
        </div>
        
    </form>

</div>
@endsection

@section("script")
    <script src="{{asset('/src/js/category/categories.create.js')}}"></script>
@endsection


