<div style="width: 150px; float: right">
    <label>Idioma:</label>
    <select class="form-control" onchange="changeLanguage(event);">
        @foreach($languages as $language)
            @if($languageSelected == $language['language'])
                <option value"{{ $language['language'] }}" selected>{{$language['language']}}</option>
            @else
                <option value"{{ $language['language'] }}">{{$language['language']}}</option>
            @endif
        @endforeach
    </select>
</div>

<script>
    function changeLanguage(event){
        window.location.href = "{{ route("events.index") }}?language="+event.target.value;
    }
</script>