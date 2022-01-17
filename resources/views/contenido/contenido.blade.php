  <!-- Contenido Principal -->
  @extends('principal')
  @section('contenido')

    @if(Auth::check())
        @if(Auth::user()->rolid == 1)
            <template v-if="menu==0">
<<<<<<< HEAD
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
=======
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
              <consultaventa :ruta="ruta"></consultaventa>
            </template>

            <template v-if="menu==11">
              <productoagotado :ruta="ruta"></productoagotado>
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
>>>>>>> b103eb9778c2ee67c5803410930ece04270e0611
            </template>

            <template v-if="menu==10">
              <h1>Reporte ventas</h1>
            </template>

        @else

        @endif
    @endif

@endsection
        <!-- /Fin del contenido principal -->