@if ($errors->any())
    <div class="field mt-6">
        <ul>
            @foreach ($errors->all() as $error)
                <li class="text-sm text-red">{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
