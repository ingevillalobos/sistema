    <template>
                <main class="main">
                <!-- Breadcrumb -->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
                </ol>
                <div class="container-fluid">
                    <!-- Ejemplo de tabla Listado -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-align-justify"></i> Ventas
                        </div>
                        <!-- Listado -->
                        <template v-if="listado==1">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <select class="form-control col-md-3" v-model="criterio">
                                        <option value="tipo_comprobante">Tipo Comprobante</option>
                                        <option value="num_comprobante">Num. Comprobante</option>
                                        <option value="fecha_hora">Fecha-Hora</option>
                                        </select>
                                        <input type="text" v-model="buscar" @keyup.enter="listarVenta(1,buscar,criterio)" class="form-control" placeholder="Texto a buscar">
                                        <button type="submit" @click="listarVenta(1,buscar,criterio)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>Opciones</th>
                                        <th>Usuario</th>
                                        <th>Cliente</th>
                                        <th>Tipo Comprobante</th>
                                        <th>Serie Comprobante</th>
                                        <th>Numero Comprobante</th>
                                        <th>Fecha-Hora</th>
                                        <th>Total</th>
                                        <th>Impuesto</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venta in arrayVenta" :key="venta.id">
                                        <td>
                                            <button type="button" @click="verVenta(venta.id)" class="btn btn-success btn-sm">
                                            <i class="icon-eye"></i>
                                            </button>
                                            &nbsp;
                                            <button type="button" @click="pdfVenta(venta.id)" class="btn btn-info btn-sm">
                                            <i class="icon-doc"></i>
                                            </button>
                                        </td>
                                        <td v-text="venta.usuario"></td>
                                        <td v-text="venta.nombre"></td>
                                        <td v-text="venta.tipo_comprobante"></td>
                                        <td v-text="venta.serie_comprobante"></td>
                                        <td v-text="venta.num_comprobante"></td>
                                        <td v-text="venta.fecha_hora"></td>
                                        <td v-text="venta.total"></td>
                                        <td v-text="venta.impuesto"></td>
                                        <td>
                                            <div v-if="venta.estado=='Registrado'">
                                            <span class="badge badge-success" v-text="venta.estado"></span>
                                        </div>
                                        <div v-else>
                                            <span class="badge badge-danger" v-text="venta.estado"></span>
                                        </div>
                                        </td>
                                    </tr>                                
                                </tbody>
                            </table>
                            </div>
                            <nav>
                                <ul class="pagination">
                                    <li class="page-item" v-if="pagination.current_page > 1">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1,buscar,criterio)">Ant</a>
                                    </li>
                                    <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActive ? 'active' : '']">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(page,buscar,criterio)" v-text="page"></a>
                                    </li>
                                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                        <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1,buscar,criterio)">Sig</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        </template>
                        <!-- Fin listado -->
                        <!-- Ver Venta-->
                        <template v-else-if="listado==2">
                        <div class="card-body">
                            <div class="form-group row border">
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="">Cliente</label>
                                      <p v-text="cliente"></p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                        <label for="">Impuesto</label>
                                        <p v-text="impuesto"></p>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Tipo Comprobante</label>
                                       <p v-text="tipo_comprobante"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Serie Comprobante</label>
                                        <p v-text="serie_comprobante"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Numero Comprobante(*)</label>
                                        <p v-text="num_comprobante"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row border">
                                <div class="teable-responsive col-md-12">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr>
                                                <th>Articulo</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Descuento</th>
                                                <th>Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody v-if="arrayDetalle.length">
                                            <tr v-for="detalle in arrayDetalle" :key="detalle.id">
                                                <td v-text="detalle.articulo">                                                 
                                                </td>
                                                <td v-text="detalle.precio">
                                                </td>
                                                <td v-text="detalle.cantidad">
                                                </td>
                                                <td v-text="detalle.descuento"></td>
                                                <td>
                                                    {{ detalle.precio * detalle.cantidad - detalle.descuento}}
                                                </td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="4" align="right"><strong>Total Parcial:</strong></td>
                                                <td>$ {{totalParcial=(total-totalImpuesto).toFixed(2)}}</td>
                                            </tr>
                                            <tr style="background-color: #CEECF5">
                                                <td colspan="4" align="right"><strong>Total Impuesto:</strong></td>
                                                <td>$ {{totalImpuesto=((total*impuesto)).toFixed(2)}}</td>
                                            </tr>
                                                <tr style="background-color: #CEECF5">
                                                <td colspan="4" align="right"><strong>Total:</strong></td>
                                                <td>$ {{total}}</td>
                                            </tr>
                                        </tbody>
                                        <tbody v-else>
                                            <tr>
                                                <td colspan="5">
                                                    No hay articulos agregados
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <button type="button" @click="ocultarDetalle()" class="btn btn-secondary">Cerrar</button>
                                </div>
                            </div>
                        </div>
                        </template>
                        <!-- Fin ver venta -->
                    </div>
                    <!-- Fin ejemplo de tabla Listado -->
                </div>
            </main>
    </template>

    <script>
        import vSelect from 'vue-select';
        export default {
            data (){
                return {
                    venta_id : 0,
                    idcliente : '',
                    cliente: '',
                    tipo_comprobante:'BOLETA',
                    serie_comprobante: '',
                    num_comprobante:'',
                    impuesto : 0.16,
                    total : 0.0,
                    totalImpuesto: 0.0,
                    totalParcial: 0.0,
                    arrayVenta : [],
                    arrayDetalle: [],
                    arrayCliente: [],
                    listado: 1,
                    modal : 0,
                    tituloModal : '',
                    tipoAccion : 0,
                    errorVenta : 0,
                    errorMostrarMsjVenta : [],
                    pagination: {
                        'total' : 0,
                        'current_page': 0,
                        'per_page': 0,
                        'last_page': 0,
                        'from': 0,
                        'to': 0
                    },
                    offset: 3,
                    criterio: 'num_comprobante',
                    buscar: '',
                    criterioArt: 'nombre',
                    buscarArt: '',
                    arrayArticulo: [],
                    idarticulo: 0,
                    codigo: '',
                    articulo: '',
                    precio: 0,
                    cantidad: 0,
                    descuento: 0,
                    stock: 0
                }
            },
            components: {
                vSelect
            },
            computed:{
                isActive: function(){
                    return this.pagination.current_page;
                },
                pagesNumber: function(){
                    if(!this.pagination.to){
                        return [];
                    }

                    var from = this.pagination.current_page - this.offset;
                    if(from < 1){
                        from = 1;
                    }

                    var to = from + (this.offset * 2);
                    if(to >= this.pagination.last_page){
                        to = this.pagination.last_page;
                    }

                    var pagesArray = [];
                    while(from <= to){
                        pagesArray.push(from);
                        from++;
                    }
                    return pagesArray;  
                },
                calcularTotal: function(){
                    var resultado=0.0;
                    for(var i=0;i<this.arrayDetalle.length;i++){
                        resultado = resultado + (this.arrayDetalle[i].precio*this.arrayDetalle[i].cantidad - this.arrayDetalle[i].descuento)
                    }
                    return resultado;
                }
            },
            methods : {
                listarVenta(page,buscar,criterio){
                    let me=this;
                    var url = '/venta?page=' + page + '&buscar='+ buscar + '&criterio='+ criterio;
                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        me.arrayVenta = respuesta.ventas.data;
                        me.pagination = respuesta.pagination;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                },
                pdfVenta(id){
                    window.open('http://sistema.test/venta/pdf/' + id + ',' + '_blank');
                },
                cambiarPagina(page,buscar,criterio){
                    let me = this;
                    //Actualiza la pagina actual
                    me.pagination.current_page = page;
                    me.listarVenta(page,buscar,criterio);
                },
                mostrarDetalle(){
                    let me = this;
                    me.listado = 0;
                    me.idproveedor=0;
                    me.tipo_comprobante='BOLETA';
                    me.serie_comprobante='';
                    me.num_comprobante='';
                    me.impuesto=0.16;
                    me.total=0.0;
                    me.idarticulo=0;
                    me.articulo=0;
                    me.cantidad=0;
                    me.precio=0;
                    me.arrayDetalle= [];
                },
                ocultarDetalle(){
                    this.listado = 1;
                },
                verVenta(id){
                    let me = this;
                    me.listado = 2;

                    //Obtener datos de venta
                    var arrayVentaTemp=[];
                    var url = '/venta/obtenerCabecera?id=' + id;

                    axios.get(url).then(function (response) {
                        var respuesta = response.data;
                        arrayVentaTemp = respuesta.venta;
                        
                        me.cliente = arrayVentaTemp[0]['nombre'];
                        me.tipo_comprobante = arrayVentaTemp[0]['tipo_comprobante'];
                        me.serie_comprobante = arrayVentaTemp[0]['serie_comprobante'];
                        me.num_comprobante = arrayVentaTemp[0]['num_comprobante'];
                        me.total = arrayVentaTemp[0]['total'];
                        me.impuesto = arrayVentaTemp[0]['impuesto'];
                        me.total = arrayVentaTemp[0]['total'];
                    })
                    .catch(function (error) {
                        console.log(error);
                    });

                    //Obtener los datos de los detalles
                    var urldetalles = '/venta/obtenerDetalles?id=' + id;

                    axios.get(urldetalles).then(function (response) {
                        var respuesta = response.data;
                        me.arrayDetalle = respuesta.detalles;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                }
            },
            mounted() {
                this.listarVenta(1,this.buscar,this.criterio);
            }
        }
    </script>
    <style>    
        .modal-content{
            width: 100% !important;
            position: absolute !important;
        }
        .mostrar{
            display: list-item !important;
            opacity: 1 !important;
            position: absolute !important;
            background-color: #3c29297a !important;
        }
        .div-error{
            display: flex;
            justify-content: center;
        }
        .text-error{
            color: red !important;
            font-weight: bold;
        }

        @media(min-width: 600px){
            .btnagregar {
                margin-top: 2rem;
            }
        }
    </style>
