@extends("layouts.base")

@section('title')
Listado
@endsection
@section('content')
<div class="container mt-4" id="app-edit-categories">
    <div>
    <span><a href="{{ route("categories.index") }}" class=""> Categorías</a></span> /
    <span> Editar</span>
    </div>
    <br>
    
    <h1>Categorías</h1>
    <hr>
    <h2>Editar categoría</h2>
    <div class="row">
        <div class="col-4 mt-2">
            <label>Slug</label>
            <input name="slug"  required class="form-control" v-model="slug" placeholder="Ingrese un identificador..." readonly>
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
    <script src="{{asset('/src/js/category/categories.edit.js')}}"></script>
    <script>
        var category = @json($category);
    
        app_categories_edit.setUrlCreateDescription('{{ route('category_descriptions.store') }}');
        app_categories_edit.setUrlRedirect('{{ route('categories.index') }}');
         
        

        app_categories_edit.loadData({
            ...category,
        });

    </script>
@endsection


