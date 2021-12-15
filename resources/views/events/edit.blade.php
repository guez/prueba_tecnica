@extends("layouts.base")

@section('title')
Listado
@endsection
@section('content')
<div class="container mt-4" id="app-edit-events">
    <div>
    <span><a href="{{ route("events.index") }}" class=""> Eventos</a></span> /
    <span> Editar</span>
    </div>
    <br>
    
    <h1>Eventos</h1>
    <hr>
    <h2>Editar evento</h2>
    <div class="row">
        <div class="col-4 mt-2">
            <label>Slug</label>
            <input name="slug"  required class="form-control" v-model="slug" placeholder="Ingrese un identificador...">
        </div>
        <div class="col-4 mt-2">
            <label>Capacidad</label>
            <input type="number" name="capacity" required class="form-control" v-model="capacity" placeholder="Ingrese la capacidad del Evento...">
        </div>
        <div class="col-4 mt-2">
            <label>Fecha</label>
            <input type="date" name="date" required class="form-control" v-model="date" placeholder="Ingrese la fecha del Evento...">
        </div>
        <div class="col-4 mt-2">
            <label>Filtrar por idioma:</label>
            <input name="idioma" class="form-control" v-model="languageNow" placeholder="Ingrese el idioma de la categoría...">
        </div>
        <div class="col-8 mt-2">
            <label>Categoría</label>
            <select id="category_id" required name="category_id" class="form-control" v-model="category_id">
                <option v-for="category in getCategories()" :value="category.id">${ category.description}</option>
            </select>
        </div>
        <div class="col-12 text-center mt-4">
            <button class="btn btn-success"><i class="fa fa-plus"></i> Guardar</button>
        </div>
    </div>
    <br>
    
    <hr>
    <h2>Descripciones</h2>

    <table class="table">
        <thead>
            <tr>
                {{-- <th scope="col">Código</th> --}}
                <th scope="col" class="text-center">Idioma</th>
                <th scope="col" class="text-center">Descripción</th>
                <th scope="col" class="text-center">Opciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <label>Nuevo Idioma</label>
                    <input type="text" v-model="newDescription.language" class="form-control">
                </td>
                <td>
                    <label>Nueva Descripción</label>
                    <input type="text" v-model="newDescription.name" class="form-control">
                </td>
                <td class="text-center">
                    <br>
                    <div class="btn btn-group">
                        <button class="btn" style="background:#ff6961; color:#fff;"  @click="createNewDescription()"><i class="fa fa-plus"></i></button>
                    </div>
                </td>
            </tr>
            
            <tr v-for="description in descriptions">
                <td class="text-center">
                    <span><b>${ description.language }</b></span>
                </td>
                <td  class="text-center">
                    <span v-if="!description.isEditing">${ description.name }</span>
                    <input  v-if="description.isEditing" type="text" v-model="tempDescription" class="form-control">
                </td>
                <td class="text-center">
                    <div class="btn btn-group">
                        <button class="btn" v-if="!description.isEditing" style="background:#77dd77; color:#fff;" @click="activeEditForDescription(description.id)"><i class="fa fa-pencil"></i></button>
                        <button class="btn" v-if="description.isEditing" style="background:#c1ff04; color:#302424;" @click="saveDescription(description.id)"><i class="fa fa-save"></i></button>
                        <button class="btn" style="background:#ff6961; color:#fff;"  @click="deleteDescription(description.id)"><i class="fa fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection

@section("script")
    <script src="{{asset('/src/js/events.edit.js')}}"></script>
    <script>
        var data_categories = @json($categories);
        var event = @json($event);
        var eventDescriptions = @json($event_descriptions);
    
        app_events_create.setUrlCreateDescription('{{ route('eventsDescriptions.store') }}');

        app_events_create.setCategories(data_categories);
        app_events_create.loadData({
            descriptions: eventDescriptions,
            ...event,
        });

    </script>
@endsection


