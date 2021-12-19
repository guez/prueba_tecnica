@extends("layouts.base")

@section('title')
    Listado
@endsection
@section('content')
    <input type="hidden" id="url_delete" value="">
    <div class="container mt-4">

        <div>
            @if (isset($languages))
                <x-language :languages="$languages" :languageSelected="$languageSelected" :redirect="'events.index'"  />
            @endif
            <span><a href="{{ route('events.index') }}" class=""> Eventos</a></span>
        </div>
        <br>
        <h1>Eventos</h1>
        <hr>
        <h2>Listado <a href="{{ route('events.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i></a></h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Evento</th>
                    <th scope="col">Categoría</th>
                    <th scope="col" class="text-center">Ocupación</th>
                    <th scope="col" class="text-center">Opciones</th>
                    <th scope="col" class="text-center">Entradas</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <th scope="row">{{ $event['id'] }}</th>
                        <td>{{ $event['event_description'] }}</td>
                        <td>{{ $event['category_name'] }}</td>
                        <td class="text-center">{{ $event['assistants_amount'] }} /{{ $event['capacity'] }}</td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a class="btn" style="background:#84b6f4; color:#fff;"  href="{{ route('events.show', $event['id']) }}">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <a class="btn" style="background:#77dd77; color:#fff;" href="{{ route('events.edit', $event['id']) }}">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <button class="btn" style="background:#ff6961; color:#fff;" onclick="deleteEvent('{{ route('events.destroy', $event['id']) }}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn btn-primary" onclick="openModalBuyTickets('{{ $event['id'] }}', '{{ $event['event_description'] }}', '{{ $event['category_name'] }}')">
                                    <i class="fa fa-ticket-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div>
            {{-- {{ $events->links() }} --}}
        </div>
    </div>
    <x-buy-tickets />
@endsection

@section('script')
    <script src="{{ asset('/src/js/event/events.delete.js') }}"></script>
    <script src="{{ asset('/src/js/event/events.buy_tickets.js') }}"></script>
@endsection
