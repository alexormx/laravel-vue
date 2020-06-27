<template>
    <div>
        <div class="row mt-5"  v-if="$gate.isAdmin()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Purchase Table</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i> </button>
                        </div>
                    </div>

                    <!-- /.card-header -->

                    <div class="card card-default color-palette-box" v-for="pur in purches.data" :key="pur.id" >
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-4 col-md-1">
                                    <img :src="'https://ui-avatars.com/api/?name=' + pur.user.name + '&background=0D8ABC&color=fff&rounded=true'" width="35">

                                </div>
                                <div class="col-sm-4 col-md-2">
                                    <h3 class="card-title">
                                        <i class="fas fa-tag"></i>
                                        {{pur.id}}
                                    </h3>
                                    <h6>Proveedor:  {{pur.user.name}}</h6>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-md-2">
                                    <h6>Fecha compra:{{pur.purdt}} </h6>
                                    <h6># Lines: {{pur.details_count}}</h6>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-md-1">
                                    <h6 class="text-center">Currency: {{pur.currencytype}}</h6>
                                    <h6 class="text-center">Rate: ${{pur.exchanger}}</h6>

                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-md-2">
                                    <h6 class="text-center">Tax cost: ${{pur.taxcost}}</h6>
                                    <h6 class="text-center">Ship cost: ${{pur.shipcost}}</h6>
                                </div>

                                <!-- /.col -->
                                <div class="col-sm-4 col-md-2">

                                    <h6 class="text-center">Product cost: ${{pur.prod_cost}}</h6>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-4 col-md-1">

                                    <h3 class="card-title">Total MXN: ${{pur.total_mxn}}</h3>
                                </div>
                                <div class="col-sm-4 col-md-1">
                                    <a  data-toggle="collapse" :href="'#collapse' + pur.id" role="button" aria-expanded="false" :aria-controls="'collapse' + pur.id">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    /
                                    <a   role="button" href="#" @click="editModal(pur)">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    /
                                    <a   role="button" href="#" @click="deletePur(pur.id)">
                                        <i class="fas fa-trash red"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="collapse" :id="'collapse' + pur.id">
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
                                                <a class="btn btn-primary" role="button" v-show="!addmode && !editingrow" href="#" @click="activeAdd(pur)">
                                                    <i class="fas fa-plus white"></i>
                                                </a>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="detail in pur.details" :key="detail.id">

                                            <td>
                                                <div v-if = "detail.id !== line.id ">
                                                    <label> {{detail.poline}} </label>
                                                </div>
                                                <input
                                                        v-if="detail.id === line.id && editingrow "
                                                        type="number"
                                                        name="poline"
                                                        v-model = "line.poline">
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
                                        <tr v-if = "!editingrow && addmode && auxiliar_id === pur.id">
                                            <td>
                                                <div>
                                                    <label> {{pur.details_count + 1}} </label>
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
                                                <a class="btn btn-success" role="button" href="#" @click="newProd(pur)">
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

                        <pagination :data="purches" :limit = 2
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
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Purchase's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updatePur() : createPur()">
                        <div class="modal-body">

                            <div class="form-group">
                                <select v-model="form.user_id" name="user_id" class="form-control" >
                                    <option>--- Select role ---</option>
                                    <option v-for="user in users" :key="user.id" :value="user.id">{{user.name }}</option>
                                </select>
                                <has-error :form="form" field="user_id"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.purdt" type="date" name="purdt"
                                       placeholder="Purchase Date"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('purdt') }">
                                <has-error :form="form" field="purdt"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.currencytype" type="text"  name="currencytype"
                                       placeholder="Currency"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('currencytype') }">
                                <has-error :form="form" field="currencytype"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.exchanger" type="number" step="0.01" name="exchanger"
                                       placeholder="Exchange Rate"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('exchanger') }">
                                <has-error :form="form" field="exchanger"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.taxcost" type="number" step="0.01" name="taxcost"
                                       placeholder="Tax Cost"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('taxcost') }">
                                <has-error :form="form" field="taxcost"></has-error>
                            </div>


                            <div class="form-group">
                                <input v-model="form.shipcost" type="number" step="0.01" name="shipcost"
                                       placeholder="Shipping Cost"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('shipcost') }">
                                <has-error :form="form" field="shipcost"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.prod_cost" type="number" step="0.01" name="prod_cost"
                                       placeholder="Product Cost"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('prod_cost') }">
                                <has-error :form="form" field="prod_cost"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.total_mxn" type="number" step="0.01" name="total_mxn"
                                       placeholder="Total Cost"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('total_mxn') }">
                                <has-error :form="form" field="total_mxn"></has-error>
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
                purches: {},
                users: {},
                form: new Form({
                    id: '',
                    user_id: '',
                    purdt: '',
                    currencytype: '',
                    exchanger: '',
                    taxcost: '',
                    shipcost: '',
                    prod_cost: '',
                    total_mxn: ''
                }),
                line: new Form({
                    id: '',
                    purch_id: '',
                    poline: '',
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
                axios.get('api/purch?page=' + page)
                    .then(response => {
                        this.purches = response.data;
                    });
            },
            updatePur(id){
                this.$Progress.start();
                //console.log('Editing');
                this.form.put('api/purch/'+this.form.id)
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
            newProd(pur){
                this.editingrow = false;
                this.line.purch_id = pur.id;
                this.line.poline = pur.details_count + 1;
                this.line.post('api/pdetail')
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
            activeAdd(pur){
                this.addmode = true;
                this.auxiliar_id = pur.id;

            },
            closeAdd(){
                this.addmode = false;
                this.auxiliar_id = '';

            },
            updateProd(id){
                this.$Progress.start();
                this.line.put('api/pdetail/'+this.line.id)
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
                        this.form.delete('api/pdetail/'+id).then(()=>{
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
            editProd(detail){
                this.editingrow = true;
                this.line.reset();
                this.line.fill(detail);
            },
            closeEdit(){
                this.editingrow = false;
                this.line.reset();
            },
            editModal(pur){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(pur);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deletePur(id){
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
                        this.form.delete('api/purch/'+id).then(()=>{
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
            loadPur(){
                if(this.$gate.isAdmin()){
                    axios.get("api/purch")
                        .then(({data})=>{this.purches = data;})
                }
            },
            createPur(){
                this.$Progress.start();
                this.form.post('api/purch')
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'Purchase Created in successfully'
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
            this.loadPur();
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findPur?q=' + query)
                    .then((data) => {
                        this.purches = data.data
                    })
                    .catch(() => {

                    })
            })
            Fire.$on('AfterCreate', ()=>{
                this.loadPur();
            });
            // setInterval(() => {this.loadUsers()},3000);
        }
    }
</script>