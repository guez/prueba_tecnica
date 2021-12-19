@extends("layouts.base")

@section('title')
Listado
@endsection
@section('content')
<div class="container mt-4">
    <div>
        <span><a href="{{ route("events.index") }}" class=""> Eventos</a></span> /
        <span> Crear</span>
    </div>
    <br>

    <h1>Eventos</h1>
    <hr>
    <h2>Crear nuevo evento</h2>
    <form action="{{ route('events.store') }}" method="POST" class="row" id="app-create-events" v-on:submit.prevent="saveData">
        <div class="col-4 mt-2">
            <label>Slug</label>
            <input name="slug"  required class="form-control" v-model="slug" placeholder="Ingrese un identificador...">
        </div>
        <div class="col-8 mt-2">
            <label>Nombre</label>
            <input name="name" required class="form-control" v-model="name" placeholder="Ingrese un Nombre...">
        </div>
        <div class="col-6 mt-2">
            <label>Capacidad</label>
            <input type="number" name="capacity" required class="form-control" v-model="capacity" placeholder="Ingrese la capacidad del Evento...">
        </div>
        <div class="col-6 mt-2">
            <label>Fecha</label>
            <input type="date" name="date" required class="form-control" v-model="date" placeholder="Ingrese la fecha del Evento...">
        <br>
        </div>
        <br>
        <hr>
        <div class="col-6">
            <label>Idioma</label>
            <input name="idioma"  class="form-control" v-model="languageNow" placeholder="Ingrese el idioma del Evento...">
        </div>
        <div class="col-6">
            <label>Categoría</label>
            <select id="category_id" required name="category_id" class="form-control" v-model="category_id">
                <option v-for="category in getCategories()" :value="category.id">${ category.description}</option>
            </select>
        </div>
        <div class="col-12 text-center mt-4">
        
            <button class="btn btn-success"><i class="fa fa-plus"></i> Guardar</button>
        </div>
        
    </form>
    
</div>
@endsection

@section("script")
    <script src="{{asset('/src/js/event/events.create.js')}}"></script>
    <script>
        var data_categories = @json($categories);
        app_events_create.setCategories(data_categories);
    </script>
@endsection


