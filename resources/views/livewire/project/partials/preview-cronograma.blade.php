<div class="my-6 overflow-hidden border border-gray-300 rounded-sm">
    <table class="w-full text-[9px] border-collapse">
        <tr class="bg-gray-100 font-bold">
            <th class="border border-gray-300 p-1 text-left">Actividad</th>
            @foreach(['E','F','M','A','M','J','J','A','S','O','N','D'] as $m)
                <th class="border border-gray-300 p-1 text-center w-5">{{$m}}</th>
            @endforeach
        </tr>
        @foreach($data as $row)
        <tr>
            <td class="border border-gray-300 p-1 font-medium">{{ $row['actividad'] }}</td>
            @foreach($row['meses'] as $check)
                <td class="border border-gray-300 p-1 text-center {{ $check ? 'bg-blue-100' : '' }}">
                    {{ $check ? 'X' : '' }}
                </td>
            @endforeach
        </tr>
        @endforeach
    </table>
</div>