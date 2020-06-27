<template>
    <div>
        <div class="row mt-5"  v-if="$gate.isAdmin()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order Table</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i> </button>

                        </div>
                    </div>

                    <!-- /.card-header -->

                    <div class="card card-default color-palette-box" v-for="order in orders.data" :key="order.id" >
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-4 col-md-1">
                                    <img :src="'https://ui-avatars.com/api/?name=' + order.user.name + '&background=0D8ABC&color=fff&rounded=true'" width="35">

                                </div>
                                 <div class="col-sm-4 col-md-2">
                                    <h3 class="card-title">
                                        <i class="fas fa-tag"></i>
                                        {{order.order_id}}
                                    </h3>
                                     <h6>Metodo Venta: {{order.salemethod}}</h6>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-md-2">
                                    <h6>Cliente:{{order.user.name}}</h6>
                                    <h6># Lines: {{order.details_count}}</h6>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-md-1">
                                    <h6 class="text-center">Fecha compra: </h6>
                                    <h6 class="text-center">{{order.saledt}}</h6>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-md-2">
                                    <h6 class="text-center">Metodo Envio: {{order.shipmethod}}</h6>
                                    <h6 class="text-center"># Rastreo: {{order.tracking}}</h6>
                                </div>

                                <!-- /.col -->
                                <div class="col-sm-4 col-md-2">
                                    <h6 class="text-center">Costo Prrducto: ${{order.prodcost}}</h6>
                                    <h6 class="text-center">Costo Envio: ${{order.shipcost}}</h6>
                                    <h6 class="text-center bg-primary text-white">Status:{{order.status}}</h6>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-md-1">
                                    <h3 class="card-title">Total: ${{order.totalcost}}</h3>

                                </div>
                                <div class="col-sm-4 col-md-1">
                                    <a  data-toggle="collapse" :href="'#collapse' + order.id" role="button" aria-expanded="false" :aria-controls="'collapse' + order.id">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    /
                                    <a   role="button" href="#" @click="editModal(order)">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    /
                                    <a   role="button" href="#" @click="deleteOrder(order.id)">
                                        <i class="fas fa-trash red"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="collapse" :id="'collapse' + order.id">
                            <div class="card-body table-responsive p-0">
                                <div class="row col-md-12">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th width="20%">Line</th>
                                            <th width="20%">Quantity</th>
                                            <th width="20%">Product</th>
                                            <th width="20%">Unit Cost</th>
                                            <th width="20%">Modify
                                                <a class="btn btn-primary" role="button" v-show="!addmode && !editingrow" href="#" @click="activeAdd(order)">
                                                    <i class="fas fa-plus white"></i>
                                                </a>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="detail in order.details" :key="detail.id">

                                                    <td>
                                                        <div v-if = "detail.id !== line.id ">
                                                            <label> {{detail.order_line}} </label>
                                                        </div>
                                                        <input
                                                                v-if="detail.id === line.id && editingrow "
                                                                type="number"
                                                                name="order_line"
                                                                v-model = "line.order_line">
                                                    </td>

                                                    <td>
                                                        <div v-if = "detail.id !== line.id">
                                                            <label> {{detail.quantity}} </label>
                                                        </div>
                                                        <input v-if="detail.id === line.id && editingrow"
                                                               type="number"
                                                               name="quantity"
                                                               v-model = "line.quantity">
                                                    </td>
                                                    <td >
                                                        <div v-if = "detail.id !== line.id ">
                                                            <label> {{detail.product_id}} </label>
                                                        </div>
                                                        <input v-if="detail.id === line.id && editingrow "
                                                               type="text"
                                                               name="product_id"
                                                               v-model = "line.product_id">
                                                    </td>
                                                    <td>
                                                        <div v-if = "detail.id !== line.id">
                                                            <label>$ {{detail.unitcost}}</label>
                                                        </div>
                                                        <input v-if="detail.id === line.id && editingrow"
                                                               type="number"
                                                               name="unitcost"
                                                               v-model = "line.unitcost">
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-primary" v-if = "detail.id !== line.id "  role="button" href="#" @click="editProd(detail)">
                                                            <i class="fas fa-edit white"></i>
                                                        </a>
                                                        <a class="btn btn-danger" v-if = "detail.id !== line.id "  role="button" href="#" @click="deleteProd(detail.id)">
                                                            <i class="fas fa-trash white"></i>
                                                        </a>
                                                        <a class="btn btn-success" v-if="detail.id === line.id "  role="button" href="#" @click="updateProd(line)">
                                                            <i class="fas fa-save white"></i>
                                                        </a>
                                                        <a class="btn btn-danger" v-if="detail.id === line.id "  role="button" href="#" @click="closeEdit()">
                                                            <i class="fas fa-times-circle white"></i>
                                                        </a>
                                                    </td>

                                         </tr>
                                        <tr v-if = "!editingrow && addmode && auxiliar_id === order.id">
                                            <td>
                                                <div>
                                                    <label> {{order.details_count + 1}} </label>
                                                </div>
                                            </td>
                                            <td>
                                                <input
                                                        type="number"
                                                        name="quantity"
                                                        v-model = "line.quantity">
                                            </td>
                                            <td >
                                                <input
                                                        type="text"
                                                        name="product_id"
                                                        v-model = "line.product_id">
                                            </td>
                                            <td>
                                                <input
                                                        type="number"
                                                        name="unitcost"
                                                        v-model = "line.unitcost">
                                            </td>
                                            <td>
                                                <a class="btn btn-success" role="button" href="#" @click="newProd(order)">
                                                    <i class="fas fa-save white"></i>
                                                </a>
                                                <a class="btn btn-danger" role="button" href="#" @click="closeAdd()">
                                                    <i class="fas fa-times-circle white"></i>
                                                </a>
                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                      </div>


                    <!-- /.card-body -->
                    <div class="card-footer">

                        <pagination :data="orders" :limit = 2
                                    @pagination-change-page="getResults">
                            <span slot="prev-nav">&lt; Previous</span>
                            <span slot="next-nav">Next &gt;</span>
                        </pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <div v-if="!$gate.isAdmin()">
            <not-found></not-found>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Order's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateOrder() : createOrder()">
                        <div class="modal-body">

                            <div class="form-group">
                                <input v-model="form.order_id" type="text" name="order_id"
                                       placeholder="Order"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('order_id') }">
                                <has-error :form="form" field="order_id"></has-error>
                            </div>

                            <div class="form-group">
                                <select v-model="form.user_id" name="user_id" class="form-control" >
                                    <option>--- Select role ---</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{user.name }}</option>
                                </select>
                                <has-error :form="form" field="user_id"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.saledt" type="date" name="saledt"
                                       placeholder="Sale Date"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('saledt') }">
                                <has-error :form="form" field="saledt"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.salemethod" type="text" name="salemethod"
                                       placeholder="Sale Method"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('salemethod') }">
                                <has-error :form="form" field="salemethod"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.paymethod" type="text" name="paymethod"
                                       placeholder="Paymethod"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('paymethod') }">
                                <has-error :form="form" field="paymethod"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.shipmethod" type="text" name="shipmethod"
                                       placeholder="Shipmethod"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('shipmethod') }">
                                <has-error :form="form" field="shipmethod"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.tracking" type="text" name="tracking"
                                       placeholder="Tracking"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('tracking') }">
                                <has-error :form="form" field="tracking"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.status" type="text" name="status"
                                       placeholder="Status"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('status') }">
                                <has-error :form="form" field="status"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.shipcost" type="number" step="0.01" name="shipcost"
                                       placeholder="Shipping Cost"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('shipcost') }">
                                <has-error :form="form" field="shipcost"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.prodcost" type="number" step="0.01" name="prodcost"
                                       placeholder="Product Cost"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('prodcost') }">
                                <has-error :form="form" field="prodcost"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.totalcost" type="number" step="0.01" name="totalcost"
                                       placeholder="Total Cost"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('totalcost') }">
                                <has-error :form="form" field="totalcost"></has-error>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                editmode: false,
                editingrow: false,
                addmode: false,
                auxiliar_id: '',
                orders: {},
                users: {},
                form: new Form({
                    id: '',
                    order_id: '',
                    user_id: '',
                    saledt: '',
                    salemethod: '',
                    paymethod: '',
                    shipmethod: '',
                    tracking: '',
                    status: '',
                    shipcost: '',
                    prodcost: '',
                    totalcost: ''
                }),
                line: new Form({
                    id: '',
                    order_id: '',
                    order_line: '',
                    quantity: '',
                    product_id: '',
                    unitcost: ''
                })
            }
        },

        methods: {
            loadUsers(){
                if(this.$gate.isAdmin()){
                    axios.get("api/getusers")
                        .then(({data})=>{this.users = data;})
                }
            },
            getResults(page = 1) {
                axios.get('api/order?page=' + page)
                    .then(response => {
                        this.orders = response.data;
                    });
            },
            updateOrder(id){
                this.$Progress.start();
                //console.log('Editing');
                this.form.put('api/order/'+this.form.id)
                    .then(() =>{
                        $('#addNew').modal('hide');
                        Swal.fire(
                            'Updated!',
                            'Information has been updated.',
                            'success'
                        )
                        this.$Progress.finish();
                        Fire.$emit('AfterCreate');
                    })
                    .catch(()=>{
                        this.$Progress.fail();
                    });
            },
            newProd(order){
                this.editingrow = false;
                this.line.order_id = order.id;
                this.line.order_line = order.details_count + 1;
                this.line.post('api/orderdet')
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Line Created in successfully'
                        })
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        Swal.fire("Failed!", "There was something wronge.", "warning");
                    })
                this.line.reset();
                this.addmode = false;
            },
            activeAdd(order){
                this.addmode = true;
                this.auxiliar_id = order.id;

            },
            closeAdd(){
                this.addmode = false;
                this.auxiliar_id = '';

            },
            updateProd(id){
                this.$Progress.start();
                this.line.put('api/orderdet/'+this.line.id)
                    .then(() =>{
                        $('#addNew').modal('hide');
                        Swal.fire(
                            'Updated!',
                            'Information has been updated.',
                            'success'
                        )
                        this.$Progress.finish();
                        this.editingrow = false;
                        this.line.reset();
                        Fire.$emit('AfterCreate');
                    })
                    .catch(()=>{
                        this.$Progress.fail();
                    });
            },
            deleteProd(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    // Send request to the server
                    if (result.value) {
                        this.form.delete('api/orderdet/'+id).then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreate');
                        }).catch(()=> {
                            Swal.fire("Failed!", "There was something wronge.", "warning");
                        });
                    }
                })
            },
            createOrder(){
                console.log(this.line);
            },
            editProd(detail){
                this.editingrow = true;
                this.line.reset();
                this.line.fill(detail);
            },
            closeEdit(){
                this.editingrow = false;
                this.line.reset();
            },
            editModal(order){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(order);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteOrder(id){
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    // Send request to the server
                    if (result.value) {
                        this.form.delete('api/order/'+id).then(()=>{
                            Swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            Fire.$emit('AfterCreate');
                        }).catch(()=> {
                            Swal.fire("Failed!", "There was something wronge.", "warning");
                        });
                    }
                })
            },
            loadOrders(){
                if(this.$gate.isAdmin()){
                    axios.get("api/order")
                        .then(({data})=>{this.orders = data;})
                }
            },
            createOrder(){
                this.$Progress.start();
                this.form.post('api/order')
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Order Created in successfully'
                        })
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        Swal.fire("Failed!", "There was something wronge.", "warning");
                    })
            }
        },
        created() {
            this.loadUsers();
            this.loadOrders();
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findOrder?q=' + query)
                    .then((data) => {
                        this.orders = data.data
                    })
                    .catch(() => {

                    })
            })
            Fire.$on('AfterCreate', ()=>{
                this.loadOrders();
            });
            // setInterval(() => {this.loadUsers()},3000);
        }
    }
</script>