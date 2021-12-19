@extends("layouts.base")

@section('title')
Listado
@endsection
@section('content')
<input type="hidden" id="url_delete" value="">
<div class="container mt-4">

    <div>
        @if(isset($languages))
            <x-language :languages="$languages" :languageSelected="$languageSelected" />
        @endif
    <span><a href="{{ route("events.index") }}" class=""> Eventos</a></span>
    </div>
    <br>
    <h1>Eventos</h1>
    <hr>
    <h2>Listado <a href="{{ route("events.create") }}" class="btn btn-primary"><i class="fa fa-plus"></i></a></h2>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Evento</th>
                <th scope="col">Categoría</th>
                <th scope="col" class="text-center">Ocupación</th>
                <th scope="col" class="text-center">Opciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $event)
            <tr>
                <th scope="row">{{ $event['id'] }}</th>
                <td>{{ $event['event_description'] }}</td>
                <td>{{ $event['category_name'] }}</td>
                <td class="text-center">{{$event['assistants_amount']}} /{{ $event['capacity'] }}</td>
                <td class="text-center">
                    <div class="btn-group">
                        <button class="btn"style="background:#84b6f4; color:#fff;"><i class="fa fa-eye"></i></button>
                        <a class="btn" style="background:#77dd77; color:#fff;" href="{{route('events.edit', $event['id'] )}}"><i class="fa fa-pencil"></i></a>
                        <button class="btn" style="background:#ff6961; color:#fff;" onclick="deleteEvent('{{ route("events.destroy", $event['id'] )}}')"><i class="fa fa-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {{$events->links()}}
    </div>
</div>
@endsection

@section("script")
    <script src="{{asset('/src/js/events.delete.js')}}"></script>
@endsection

