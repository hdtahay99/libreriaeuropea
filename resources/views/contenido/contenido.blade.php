  <!-- Contenido Principal -->
  @extends('principal')
  @section('contenido')

    @if(Auth::check())
        @if(Auth::user()->rolid == 1)
            <template v-if="menu==0">
              <dashboard></dashboard>
            </template>

            <template v-if="menu==1">
              <categoria></categoria>    
            </template>

            <template v-if="menu==2">
              <producto></producto>
            </template>

            <template v-if="menu==3">
              <factura></factura>
            </template>

            <template v-if="menu==4">
              <cliente></cliente>
            </template>

            <template v-if="menu==7">
              <empleado></empleado>
            </template>

            <template v-if="menu==8">
              <user></user>
            </template>

            <template v-if="menu==9">
              <rol></rol>
            </template>

            <template v-if="menu==10">
              <consultaventa></consultaventa>
            </template>

            <template v-if="menu==11">
              <productoagotado></productoagotado>
            </template>
        @elseif(Auth::user()->rolid == 2)
            <template v-if="menu==3">
              <factura></factura>
            </template>

            <template v-if="menu==2">
              <producto></producto>
            </template>

            <template v-if="menu==4">
              <cliente></cliente>
            </template>

            <template v-if="menu==10">
              <h1>Reporte ventas</h1>
            </template>

        @else

        @endif
    @endif

@endsection
        <!-- /Fin del contenido principal -->