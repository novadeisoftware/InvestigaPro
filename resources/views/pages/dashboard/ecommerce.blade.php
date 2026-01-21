@extends('layouts.app')

@section('content')
  {{-- Definimos el título para que el Layout lo reconozca --}}
  @php $title = 'Panel de Control - InvestigaPro'; @endphp

  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <div class="col-span-12 space-y-6 xl:col-span-7">
      {{-- Métricas de Investigaciones/Envíos --}}
      <x-ecommerce.ecommerce-metrics />
      
      {{-- Gráfico Mensual --}}
      <x-ecommerce.monthly-sale />
    </div>

    <div class="col-span-12 xl:col-span-5">
        {{-- Metas de Monitoreo --}}
        <x-ecommerce.monthly-target />
    </div>

    <div class="col-span-12">
      <x-ecommerce.statistics-chart />
    </div>

    <div class="col-span-12 xl:col-span-5">
      <x-ecommerce.customer-demographic />
    </div>

    <div class="col-span-12 xl:col-span-7">
      {{-- Listado de casos recientes --}}
      <x-ecommerce.recent-orders />
    </div>
  </div>
@endsection