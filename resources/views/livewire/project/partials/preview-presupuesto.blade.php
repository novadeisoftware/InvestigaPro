<div class="my-6 border border-gray-300">
    <table class="w-full text-[10px] border-collapse">
        <tr class="bg-gray-100 font-bold uppercase">
            <th class="border border-gray-300 p-2 text-left">Descripción</th>
            <th class="border border-gray-300 p-2 text-center w-16">Cant.</th>
            <th class="border border-gray-300 p-2 text-right">Total ({{ $data['moneda'] ?? 'S/' }})</th>
        </tr>
        @php $total = 0; @endphp
        @foreach($data['items'] as $item)
            @php $total += ($item['cant'] * $item['precio']); @endphp
            <tr>
                <td class="border border-gray-300 p-2">{{ $item['item'] }}</td>
                <td class="border border-gray-300 p-2 text-center">{{ $item['cant'] }}</td>
                <td class="border border-gray-300 p-2 text-right">{{ number_format($item['cant'] * $item['precio'], 2) }}</td>
            </tr>
        @endforeach
        <tr class="font-bold bg-gray-50">
            <td colspan="2" class="border border-gray-300 p-2 text-right">TOTAL:</td>
            <td class="border border-gray-300 p-2 text-right">{{ number_format($total, 2) }}</td>
        </tr>
    </table>
</div>