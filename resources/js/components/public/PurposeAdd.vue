<template>
    <section class="add-purpose" id="addPurpose">
        <animation-component></animation-component> 
        <div class="container">
            <!-- alert area -->
            <div v-if="!numOfPurposes">
                <div class="alert alert-info" role="alert">
                    Welcome to this page <strong id="name">{{nameOfUser}}</strong>, you must create some Purposes to start.
                    Purpose is something you want to achieve.
                </div>
                <div class="alert alert-success" role="alert">
                    You can also use Prodomo timer, simple by clicking to Start forcus button.
                    This will control that way how to you use time.
                </div>
            </div>

            <!-- end alert area -->
            <div class="purpose">
            <h2>PURPOSE</h2>
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-warning">
                        <th>Name</th>
                        <th>Start day</th>
                        <th>End day</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-primary text-white"
                        v-if="isPurposeAdded">
                        <td>{{newestPurpose.name}}</td>
                        <td>{{newestPurpose.startday}}</td>
                        <td>{{newestPurpose.endday}}</td>
                        <td>
                            <div class="btn btn-warning" 
                                    v-on:click="purposeModal(newestPurpose)">
                                Edit
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- form to add some new purpose -->
            <div v-if="!isPurposeAdded">
                <form  v-on:submit.prevent="addPurpose" >
                    <div class="form-group">
                        <label for="password">Name</label>
                        <input class="form-control is-valid" type="text" id="password"
                            v-model="formPurpose.name">
                        <div class="invalid-feedback">
                            Purpose of something is what it is intended to achieve.
                            You can't delete it, so must think carefully before add new one.
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">When will you start this?</label>
                                <input type="date" class="form-control" name="startday" 
                                    v-model="formPurpose.startday">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">When will you achieve this?</label>
                                <input type="date" class="form-control" name="endday" 
                                    v-bind:min="formPurpose.startday"
                                    v-model="formPurpose.endday">
                            </div>
                        </div>
                    </div>
                    
                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
            </div>

            <!--end form to add some new purpose -->
        </div>
        <!-- end div purpose -->

        <!-- and new target -->
        <div class="target" v-if="isPurposeAdded">
            <h2>Target of PURPOSE</h2>
            <table class="table table-bordered">
                <thead>
                    <tr class="bg-warning">
                        <th>Order</th>
                        <th>Name</th>
                        <th>Frequency</th>
                        <th>Modify</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-primary text-white"
                        v-for="(target,index) in listOfTargets">
                        <td>{{index+1}}</td>
                        <td>{{target.name}}</td>
                        <td>{{target.frequency}}</td>
                        <td>
                            <div class="btn btn-warning" 
                                    v-on:click="targetModal(target)">
                                    <i class="fas fa-edit">Edit</i>
                                
                            </div>
                            <div class="btn btn-danger" 
                                v-on:click="deleteTarget(target.id)">
                                <i class="fa fa-trash">Delete</i>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            
            <!-- add target section -->
            <form v-on:submit.prevent="addTarget()">
            
              <div class="form-group">
                <label for>Target</label>
                <input type="text" class="form-control"
                  v-model="formTarget.name">
              </div>
                <div class="form-group">
                  <label for="gender">How you do that?</label>
                  <select class="form-control" v-model="formTarget.repeat">
                    <option value="0">No repeat</option>
                    <option value="1">Repeat</option>
                  </select>
                </div>
                <div class="form-row">
                    
                    <div class="col" v-if="formTarget.repeat==0">
                        <label for="gender">When you do that?</label>
                        <select class="form-control" v-model="formTarget.frequency">
                            <option value="One time">One time</option>
                            <option value="Today">Today</option>
                            <option value="Tomorrow">Tomorrow</option>
                            <option value="This week">This week</option>
                            <option value="This month">This month</option>
                        </select>
                    </div>

                    <div class="col" v-if="formTarget.repeat==1">
                        <label for="gender">Choose frequency</label>
                        <select class="form-control" id="frequency" 
                            
                           v-model="formTarget.frequency">
                            <option value="Every day">Every day</option>
                            <option value="2 times a week">2 Times a week</option>
                            <option value="3 times a week">3 Times a week</option>
                            <option value="4 times a week">4 Times a week</option>
                            <option value="5 times a week">5 Times a week</option>
                            <option value="6 times a week">6 Times a week</option>
                            <option value="Every week">Every week</option>
                            <option value="Every month">Every month</option>
                        </select>
                    </div>
                </div>
                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
              
          </form>
            <!-- end add target form -->
        </div>
        <!-- end target div -->

        <!-- modal to edit purpose -->
        <div class="modal" tabindex="-1" id="editPurpose" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new purpose</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  v-on:submit.prevent="updatePurpose()">
                <div class="modal-body">
                        <div class="form-group">
                                <label for="">Name of purpose</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                    v-model="form.name">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Start day</label>
                                        <input type="date" class="form-control" name="startday" 
                                            v-model="form.startday">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">End day</label>
                                        <input type="date" class="form-control" name="endday" 
                                            v-model="form.endday">
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
        <!-- end modal to edit purpose -->

        <!-- modal to edit target -->

        <div class="modal" tabindex="-1" id="editTarget" role="dialog">
             <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Target</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form v-on:submit.prevent="updateTarget()">
            <div class="modal-body">
              <div class="form-group">
                <label for>Target</label>
                <input type="text" class="form-control"
                  v-model="formTarget.name">
              </div>
                <div class="form-group">
                  <label for="gender">How you do that?</label>
                  <select class="form-control" v-model="formTarget.repeat">
                    <option value="0">No repeat</option>
                    <option value="1">Repeat</option>
                  </select>
                </div>
                <div class="form-row">
                    
                    <div class="col" v-if="formTarget.repeat==0">
                        <label for="gender">When you do that?</label>
                        <select class="form-control" v-model="formTarget.frequency">
                            <option value="One time">One time</option>
                            <option value="Today">Today</option>
                            <option value="Tomorrow">Tomorrow</option>
                            <option value="This week">This week</option>
                            <option value="This month">This month</option>
                        </select>
                    </div>

                    <div class="col" v-if="formTarget.repeat==1">
                        <label for="gender">Choose frequency</label>
                        <select class="form-control" id="frequency" 
                            
                           v-model="formTarget.frequency">
                            <option value="Every day">Every day</option>
                            <option value="2 times a week">2 Times a week</option>
                            <option value="3 times a week">3 Times a week</option>
                            <option value="4 times a week">4 Times a week</option>
                            <option value="5 times a week">5 Times a week</option>
                            <option value="6 times a week">6 Times a week</option>
                            <option value="Every week">Every week</option>
                            <option value="Every month">Every month</option>
                        </select>
                    </div>
                </div>
               
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Edit</button>
            </div>
          </form>
        </div>
      </div>
        </div>
        </div>
    </section>
    
</template>
<script>
export default {
    created(){
        Promise.all([
            this.getAllPurposesOfUser(),
            this.getProfileOfUSer(),
            
        ]).then(()=>{
            const loader = document.querySelector(".lds-hourglass");
            loader.className += " hidden"; // class "loader hidden"
        })
    },  
    data:function(){
        return{
            isPurposeAdded:false,
            isShowtarget:false,
            form:new Form({
                id:'',
                name:'',
                startday:'',
                endday:'',
                purpose_id:''
            }),
            formPurpose:new Form({
                id:'',
                name:'',
                startday:'',
                endday:'',
            }),
            formTarget:new Form({
                id:'',
                name:'',
                purpose_id:'',
                frequency:'',
                repeat:''

            }),
            
            newestPurpose:{},
            listOfTargets:[],
            numOfPurposes:[],
            nameOfUser:''
        }
    },
    
    methods:{
        //to find if user is new user or not
        //if numOfPurposes==0 is new User
        getAllPurposesOfUser(){
            return new Promise((resolve,reject)=>{
                axios.get('/api/purpose/all')
                .then(response=>{
                    resolve('This is ok');
                    this.numOfPurposes=response.data.length;
                    
                })
            })
        },
        getProfileOfUSer(){
            return new Promise((resolve,reject)=>{
                axios.get('/api/user/profile')
                .then(response=>{
                    resolve('This is ok');
                    this.nameOfUser=response.data.name;
                })
            })
        },
        getNewestPurpose(){
            axios.get('/api/purpose/'+this.formPurpose.id)
            .then(data=>{
                this.newestPurpose=data.data;
            })
        },
        purposeModal(newestPurpose){
            this.ispurposeModal=true;
            $('#editPurpose').modal('show');
            this.form.fill(newestPurpose);
        },
        targetModal(target){
            $('#editTarget').modal('show');
            this.formTarget.fill(target);
        },
        addPurpose(){
            this.formPurpose.post('/api/purpose')
                .then( data => { 
                    this.isPurposeAdded=true;
                    this.formPurpose=data.data;
                    this.getNewestPurpose();
                    toast.fire({
                        type: 'success',
                        title: 'Added successfully'
                    })
            });   
        },
        updatePurpose(){
            
            this.form.patch('/api/purpose/'+this.form.id)
            .then( data => { 
                swal.fire({
                    position: 'top-end',
                    type: 'success',
                    title: 'Your work has been saved',
                    showConfirmButton: false,
                    timer: 1500
                        })
                        this.getNewestPurpose();
                        $('#editPurpose').modal('hide');
                })
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
                    this.form.delete('api/purpose/'+id)
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
        },
        getTargetOfPurpose(){
            axios.get('/api/purpose/'+this.newestPurpose.id+'/targets')
            .then(response=>{
                this.listOfTargets=response.data;
                
            })
        },
        addTarget(){
            this.formTarget.purpose_id=this.newestPurpose.id;
            
            this.formTarget.post('/api/target')
                .then( ()=> {
                    this.getTargetOfPurpose();
                    toast.fire({
                        type: 'success',
                        title: 'Added successfully'
                    })
                });
            this.formTarget.reset();
        },
        updateTarget(){
            
            this.formTarget.patch('/api/target/'+this.formTarget.id)
                .then( () => { 
                    swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: 'Your work has been saved',
                        showConfirmButton: false,
                        timer: 1500
                        })
                    this.formTarget.reset();
                    this.getTargetOfPurpose();
                    $('#editTarget').modal('hide');
                });
        },
        deleteTarget(id){
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value) {
                    this.form.delete('/api/target/'+id)
                    .then( () => { 
                        this.getTargetOfPurpose();
                        $('#editpurpose').modal('hide');

                        swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        })
                    }
                })
                     
        },

        showAlertBeforeDelete(){
            const swalWithBootstrapButtons = swal.mixin({
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
                    this.form.delete('api/purpose/'+id)
                        .then(()=>{
                            if (result.value) {
                            swalWithBootstrapButtons(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                            )}
                        else if (
                    // Read more about handling dismissals
                        result.dismiss === swal.DismissReason.cancel
                            ) {
                                swalWithBootstrapButtons(
                                'Cancelled',
                                'Your imaginary file is safe :)',
                                'error'
                                )
                            }
                        })
                        .then( data => { this.getPurpose(); });
                 
            }).catch(()=>{
                swal({
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

