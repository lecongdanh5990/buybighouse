<template>
    <section class="purpose" id="purpose">
        <animation-component></animation-component> 
        <div class="container">
            <h1 class="bg-danger text-white text-center py-2 rounded">List of Purposes</h1>
            <table class="table table-bordered">
                <thead class="bg-warning text-black">
                <tr>
                    <th>Name</th>
                    <th>Start day</th>
                    <th>End day</th>
                    <th>Complete</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                    <tr class="bg-primary text-white" v-for="purpose in listOfPurposes">
                        <td >
                            <router-link :to="'/purpose/detail/'+purpose.id" class="text-white">
                                {{purpose.name}}
                            </router-link>
                        </td>
                        <td>{{purpose.startday}}</td>
                        <td>{{purpose.endday}}</td>
                        <td>{{purpose.isComplete}}</td>
                        <td>
                            <button type="button" class="btn btn-primary" 
                                    v-on:click="editPurpose(purpose)">
                                    <i class="fa fa-edit"></i>
                            </button>

                        </td>
                        <td>
                            <button class="btn btn-danger" 
                                v-on:click="deletePurpose(purpose.id)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- modal -->
        <div class="modal" tabindex="-1" id="editMission" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new mission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  v-on:submit.prevent="updateMission">
                <div class="modal-body">
                        <div class="form-group">
                                <label for="">Name of mission</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                    v-model="formEdit.name">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Start day</label>
                                        <input type="date" class="form-control" name="startday" 
                                            v-model="formEdit.startday">
                                    </div>
                            
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">End day</label>
                                        <input type="date" class="form-control" name="endday" 
                                            v-model="formEdit.endday">
                                    </div>
                                </div>
                            </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
                </div>
            </div>
        </div>
    </section>
    
</template>
<script>
export default {
    data:function(){
        return{
            form:new Form({
                name:'',
                startday:'',
                endday:'',
            }),
            formEdit:new Form({
                id:'',
                name:'',
                startday:'',
                endday:'',
                
            }),
            listOfPurposes:[],
            user:{},
            id:3
        }
    },
    
    created(){
        this.getPurpose()
            .then(()=>{
                const loader = document.querySelector(".lds-hourglass");
                loader.className += " hidden"; // class "loader hidden"
        });
        
    },
    methods:{
        getPurpose(){
            return new Promise((resolve,reject)=>{
                axios.get('/api/purpose')
                    .then(response=>{
                        resolve('This is ok');
                        this.listOfPurposes=response.data;
                })
            })
        },
        updateMission(){
            this.formEdit.patch('api/purpose/'+this.formEdit.id)
            .then(response=>{
                //this.listOfPurposes=response.data;
                $('#editMission').modal('hide');
                this.getPurpose(); 
            })   
        },

        editPurpose(purpose){
            $('#editMission').modal('show');
            this.formEdit.fill(purpose);
        },

        deletePurpose(id){
            const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
            })

            swalWithBootstrapButtons({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
                }).then((result) => {
                    this.form.delete('api/mission/'+id)
                        .then(()=>{
                            if (result.value) {
                            swalWithBootstrapButtons(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )}
                        else if (
                    // Read more about handling dismissals
                        result.dismiss === Swal.DismissReason.cancel
                            ) {
                                swalWithBootstrapButtons(
                                'Cancelled',
                                'Your imaginary file is safe :)',
                                'error'
                                )
                            }
                        }).then( data => { this.getPurpose(); });
                 
            }).catch(()=>{
                Swal({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    footer: '<a href>Why do I have this issue?</a>'
                    })
            })
        }
    },
}
</script>

