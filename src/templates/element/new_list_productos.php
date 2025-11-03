<div id="prod_list">
    <div class="row">
        <div class="col-md-12 mb-2">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="filtro_producto_list" v-model="nombre" :readonly="loading" placeholder="Busque por Marca / Categoría / Nombre" autocomplete="off" v-on:keyup.enter="getItems()">
                <select name="" class="form-control" id="" v-model="opt_categoria" >
                    <option value="">-Seleccione-</option>
                    <option v-for="(cat,index_p) in categorias"  :key="index_p" style="cursor: pointer" :value="cat.id"  >
                        {{cat.nombre}}
                    </option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-sm btn-outline-primary" id="btnBuscarItems" type="button" @click="getItems()">
                        <i :class="`${buscar_icon}`"></i> {{buscar_label}}
                    </button>
                    <button class="btn btn-sm btn-outline-primary" id="btnBuscarItems" type="button" @click="limpiar()">
                        <i :class="`${clean_icon}`"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div v-if="pagination.totalRowsNumber > 0">
                <div class="table-responsive container-table">
                    <div v-if="isLoading" class="loading-container">
                        <div class="spinner-border load-table" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Categoría</th>
                                <th>Marca</th>
                                <th>Código</th>
                                <th>Foto</th>
                                <th>Producto</th>
                                <th>Precio Venta</th>
                                <th>Moneda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="item" v-for="(producto,index_p) in items" :key="index_p" style="cursor: pointer" @click="setData(producto)">
                                <td>{{producto.categoria || ' Sin Categoria '}} </td>
                                <td>{{producto.marca || ' Sin Marca '}} </td>
                                <td>{{producto.codigo}} </td>
                                <th> <img style="text-align:center;border-radius:5px" :src="`${producto.img_ruta != '' ? base_root + producto.img_ruta : producto_placeholder}`" width="64px" alt=""> </th>
                                <td>
                                    {{ producto.nombre }}
                                    <br>
                                    <span style="font-size: .8rem; opacity: .6;">
                                        {{ producto.ubicacion }}
                                    </span>
                                </td>
                                <td>{{parseFloat(producto.precio_venta).toFixed(2)}} </td>
                                <td>{{producto.tipo_moneda}} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <br>

            </div>
            <div v-else>
                <div class="row">
                    <div class="col-md-12">
                        No se han encontrado registros <i class="fa fa-exclamation-triangle fa-fw"></i>
                    </div>
                </div>
            </div>

            <div>
                <div class="center-paginator">
                    <div class="pagination-grid" :style="`grid-template-columns: repeat(${4 + pagesArray.length}, 1fr);`">
                        <div>
                            <button :disabled="!(pagination.page > 1)" @click="newPage = 1" :class="`pagination_box btn btn-sm flat-btn`">
                                <i class="fas fa-chevron-left"></i>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        </div>
                        <div>
                            <button :disabled="!(pagination.page > 1)" @click="newPage = (parseFloat(pagination.page) - 1)" :class="`pagination_box btn btn-sm flat-btn`">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                        </div>
                        <div v-for="(page,index_page) in pagesArray" :id="`page_${page.value}`" :key="index_page">
                            <button @click="newPage = page.value" :class="`pagination_box btn btn-sm ${pagination.page == page.value ? 'btn-shadow' : 'flat-btn'}`">
                                {{ page.value }}
                            </button>
                        </div>
                        <!-- :disabled="page.isDisabled" -->
                        <div>
                            <button :disabled="!(pagination.page < pagesNumber)" @click="newPage = (parseFloat(pagination.page) + 1)" :class="`pagination_box btn btn-sm flat-btn`">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                        <div>
                            <button :disabled="!(pagination.page < pagesNumber)" @click="newPage = pagesNumber" :class="`pagination_box btn btn-sm flat-btn`">
                                <i class="fas fa-chevron-right"></i>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var itemList = new Vue({
        el: "#prod_list",
        data() {
            return {
                items: [],
                categorias: [],
                nombre: '',
                opt_categoria: '',
                buscar_label: 'Buscar',
                buscar_icon: "fa fa-search fa-fw",
                clean_icon: "fa fa-eraser fa-fw",
                loading: false,
                endpoint: `${base}api/get-items-new/`,
                producto_placeholder: `${base_root}media/iconos/placeholder_items.png`,
                pagesNumber: 1,
                pagination: {
                    sortBy: "id",
                    descending: true,
                    page: 1,
                    limit: 10,
                    totalRowsNumber: 1,
                    pages_to_show: 3,

                },
                pagesArray: [{
                    value: 1,
                    isDisabled: true
                }],
                newPage: 1,
                isLoading: false
            }
        },
        created() {
            this.getItems();
            this.poblarCategorias();
        },
        methods: {
            limpiar() {
                this.nombre = '';
                this.opt_categoria = '';
                if (this.newPage == 1) {
                    this.getItems();
                } else {
                    this.newPage = 1;
                }

            },
            async getItems(fromBegining = true, opt_unidad = '') {

                this.pagination.page = fromBegining ? 1 : this.pagination.page;
                this.loading = true;
                var url = `${this.endpoint}?q=${this.nombre}&page=${this.pagination.page}&limit=${this.pagination.limit}&opt_categoria=${this.opt_categoria}&opt_unidad=${opt_unidad}`;

                axios
                    .get(url)
                    .then(response => {
                        console.log(response);
                        if (response.data.success) {
                            this.items = response.data.data.items;

                            this.pagesNumber = response.data.data.paginacion.pagesNumber;
                            this.pagination.page = response.data.data.paginacion.page;
                            this.pagination.totalRowsNumber = response.data.data.paginacion.totalRowsNumber;

                            this.setPagesArray();
                        }
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error);
                        alert('Ha habido un error para extraer los datos');
                        this.loading = false;
                        this.limpiar();

                    })
            },
            setData(item) {

                var itemObj = {
                    id: item.id,
                    codigo: item.codigo,
                    nombre: item.nombre,
                    categoria: item.categoria,
                    marca: item.marca,
                    inc_igv: item.inc_igv,
                    unidad: item.unidad,
                    img_ruta: item.img_ruta,
                    precio_compra: parseFloat(item.precio_compra),
                    precio_venta: parseFloat(item.precio_venta),
                    tipo_moneda: item.tipo_moneda,
                };

                newItemsList.callback(itemObj);

            },
            setPagesArray() {
                var pages = [];

                for (i = this.startPageFromList; i <= Math.min(this.startPageFromList + this.extraPagesToShow, this.pagesNumber); i++) {
                    pages.push({
                        value: i,
                        isDisabled: i == this.pagination.page ? true : false
                    });
                }
                this.pagesArray = pages;
            },
            poblarCategorias () {
                this.loading = true;
                var url = `${base}api/get-item-categorias`;
                axios
                    .get(url)
                    .then(response => {
                        console.log(response.data);
                        if (response.data.success) {
                            this.categorias = response.data.data.map((e)=>{
                                return { id: e.id , nombre: e.nombre}
                            });
                        }
                        this.loading = false;
                    })
                    .catch((error) => {
                        console.log(error);
                        alert('Ha habido un error para extraer los datos');
                        this.loading = false;
                    })
            },
        },
        watch: {
            loading(new_value) {
                if (new_value == true) {
                    this.buscar_label = 'Buscando';
                    this.buscar_icon = 'spinner-grow spinner-grow-sm text-primary';
                    this.clean_icon = 'spinner-grow spinner-grow-sm text-primary';
                    this.isLoading = true;

                } else {
                    this.buscar_label = 'Buscar';
                    this.buscar_icon = 'fa fa-search fa-fw';
                    this.clean_icon = 'fa fa-eraser fa-fw';
                    this.isLoading = false;
                }
            },
            newPage(new_value) {
                this.pagination.page = parseFloat(new_value);
                this.getItems(false);
            }
        },
        computed: {
            startPageFromList() {
                //Cuando la pagina actual es la primera
                if (this.pagination.page == 1) {
                    return 1;
                }
                //Cuando la pagina actual es la ultima
                if (this.pagination.page == this.pagesNumber) {
                    return this.pagesNumber - this.extraPagesToShow;
                }
                //Cuando la pagina actual está en el medio
                return this.pagination.page - 1;
            },
            //Representa la cantidad de paginas a mostrar adicional a la actual
            extraPagesToShow() {
                return this.pagination.pages_to_show - 1;
            },

        }
    });
</script>

<style>
    .pagination_box {
        padding: 10px 15px;
        background-color: #E5F2F3;

    }

    .container-table {
        position: relative;
    }

    .loading-container {
        position: absolute;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: rgba(255, 255, 255, 0.5);
    }

    .center-paginator {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pagination-grid {
        display: grid;
        gap: 0;
    }

    .flat-btn {
        background-color: rgba(0, 0, 0, 0);
        cursor: pointer;
        font-weight: bold;
        color: #407DEA;
    }

    .flat-btn:hover {
        background-color: rgba(0, 0, 0, 0.1);
    }

    .btn-shadow {
        background-color: #407DEA !important;
        color: #fff;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        font-weight: bold;
    }

    .btn-shadow:hover {
        color: #fff;
    }
</style>
