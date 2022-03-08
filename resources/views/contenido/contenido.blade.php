  <!-- Contenido Principal -->
  @extends('principal')
  @section('contenido')

    @if(Auth::check())
        @if(Auth::user()->rolid == 1)
            <template v-if="menu==0">
              <dashboard :ruta="ruta"></dashboard>
            </template>

            <template v-if="menu==1">
              <categoria :ruta="ruta"></categoria>    
            </template>

            <template v-if="menu==2">
              <producto :ruta="ruta"></producto>
            </template>

            <template v-if="menu==3">
              <factura :ruta="ruta"></factura>
            </template>

            <template v-if="menu==4">
              <cliente :ruta="ruta"></cliente>
            </template>

            <template v-if="menu==7">
              <empleado :ruta="ruta"></empleado>
            </template>

            <template v-if="menu==8">
              <user :ruta="ruta"></user>
            </template>

            <template v-if="menu==9">
              <rol :ruta="ruta"></rol>
            </template>

            <template v-if="menu==10">
              <dailysale :ruta="ruta"></dailysale>
            </template>

            <template v-if="menu==11">
              <productoagotado :ruta="ruta"></productoagotado>
            </template>

            <template v-if="menu==12">
              <compra :ruta="ruta"></compra>
            </template>

            <template v-if="menu==13">
              <NuevaCompra :ruta="ruta"></NuevaCompra>
            </template>
        @elseif(Auth::user()->rolid == 2)
            <template v-if="menu==3">
              <factura :ruta="ruta"></factura>
            </template>

            <template v-if="menu==2">
              <producto :ruta="ruta"></producto>
            </template>

            <template v-if="menu==4">
              <cliente :ruta="ruta"></cliente>
            </template>

            <template v-if="menu==10">
              <h1>Reporte ventas</h1>
            </template>

        @else

        @endif
    @endif

@endsection
        <!-- /Fin del contenido principal -->