<template>
    <div>
        <div class="row mt-5"  v-if="$gate.isAdmin()">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Role Table</h3>

                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Add New <i class="fas fa-user-plus fa-fw"></i> </button>
                        </div>
                    </div>

                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Created</th>
                                <th>Modify</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="role in roles" :key="role.id">
                                <td>{{role.id}}</td>
                                <td>{{role.name}}</td>
                                <td>{{role.display_name}}</td>
                                <td>{{role.created_at| myDate}}</span></td>
                                <td>
                                    <a href="#" @click="editModal(role)">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    /
                                    <a href="#" @click="deleteUser(role.id)">
                                        <i class="fa fa-trash red"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->

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
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Role's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateRole() : createRole()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name"
                                       placeholder="Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>

                            <div class="form-group">
                                <input v-model="form.display_name" type="text" name="display_name"
                                       placeholder="Display Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('display_name') }">
                                <has-error :form="form" field="display_name"></has-error>
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
                roles: {},
                form: new Form({
                    id: '',
                    name: '',
                    display_name: ''
                })
            }
        },

        methods: {
            getResults(page = 1) {
                axios.get('api/role?page=' + page)
                    .then(response => {
                        this.roles = response.data;
                    });
            },
            updateRole(id){
                this.$Progress.start();
                //console.log('Editing');
                this.form.put('api/role/'+this.form.id)
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
            editModal(role){
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(role);
            },
            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            deleteRole(id){
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
                        this.form.delete('api/role/'+id).then(()=>{
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
            loadRoles(){
                if(this.$gate.isAdmin()){
                    axios.get("api/role").then(({data})=>(this.roles = data));
                }
            },
            createRole(){
                this.$Progress.start();
                this.form.post('api/role')
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide')
                        Toast.fire({
                            type: 'success',
                            title: 'User Created in successfully'
                        })
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        Swal.fire("Failed!", "There was something wronge.", "warning");
                    })
            }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findRole?q=' + query)
                    .then((data) => {
                        this.roles = data.data
                    })
                    .catch(() => {

                    })
            })
            this.loadRoles();
            Fire.$on('AfterCreate', ()=>{
                this.loadRoles();
            });
            // setInterval(() => {this.loadUsers()},3000);
        }
    }
</script>