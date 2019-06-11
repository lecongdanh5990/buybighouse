<template>
    <section id="dashboard" class="py-3 today-section">
        <animation-component></animation-component> 
        <div class="container">
            <div class="today-component__alert">
                <div v-if="listOfPurposes.length">
                </div>
            </div>
        <!-- end alert area -->
        <div class="all-target">
            <div class="uncompleted-target">
                <h2 for="">List of Targets</h2>
                <div class="table-responsive">
                    <table class="table table-bordered" id="today-worktable">
                    <thead>
                    <tr class="bg-warning text-center">
                        <th>Target</th>
                        <th>Frequency</th>
                        <th>Purpose</th>
                        <th>Last Completed day</th>
                        <th>Make Complete</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-success text-center"
                            v-for="target in listOfTargets">

                            <td>{{target.name}}</td>
                            <td>{{target.frequency}}</td>
                            <td>{{target.purpose_name}}</td>
                            <td>{{target.last_completed_day}}</td>
                            <td class="text-center">
                                <button class="btn btn-outline-warning"
                                        v-on:click="makeTargetComplete(target)">
                                        Complete
                                    </button>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
            <div class="completed-target">
                <h2 for="">Completed Targets</h2>
                <table class="table table-bordered" id="today-completeworktable">
                    <thead>
                    <tr class="bg-primary text-center">
                        <th>Order</th>
                        <th>Target</th>
                        <th>Purpose</th>
                        <th>Frequency</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-secondary text-center"
                            v-for="(target,index) in listOfCompletedTargets"
                            v-bind:style="{ 'background-color': backgroundColors[target.priority-1]}">
                            <td>{{index+1}}</td>
                            <td>{{target.name}}</td>
                            <td>{{target.purpose_name}}</td>
                            <td>{{target.frequency}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- form to insert target -->
            <div class="form-target bg-light">
                <h1>What will you do today?</h1>
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
                <div class="form-group">
                <label for="gender">Choose Purpose</label>
                <select class="form-control" v-model="formTarget.purpose_id">
                  <option v-for="purpose in listOfPurposes" v-bind:value="purpose.id">{{purpose.name}}</option>
                </select>
                </div>
                
           
                <div class="form-group d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
          </form>
            </div>
            <!-- end form to insert target -->

            <!-- form to rating for this day -->
            <div class="card mb-3">
            <div class="card-header">Rate you in today</div>
            <div class="card-body ">
                <h4 class="card-title">Star Rating</h4>
                
                    <div class="note-component__start-rating">
                        <span 
                            v-for="n in 5"
                            v-on:click="changeSelect(n)"
                            v-bind:class="[n<=ratingStart?activeClass:'',iconClass,customClass]">
                        </span>
                    </div>
                    <div class="description d-flex flex-column justify-content-center">
                        <h3 >Description</h3>
                        <textarea v-model="statusForm.content" class="form-control" id="exampleFormControlTextarea1" rows="3">
                        </textarea>
                        
                        <button v-on:click="updateStatus()" class="btn btn-primary ">Save</button>
                    </div>
            </div>
        </div>
            
            <!-- end rating form -->
        </div>
        <!-- modal -->
        <div class="modal" tabindex="-1" id="editWork" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add new purpose</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  v-on:submit.prevent="updateWork">
                <div class="modal-body">
                        <div class="form-group">
                                <label for="">Name of purpose</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                    v-model="formEdit.name">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Choose purpose</label>
                                        <select v-model="selectedpurpose" v-on:change="" name="" id="" class="form-control form-control-lg">
                                            <option v-for="purpose in listOfPurposes" v-bind:value="purpose.id">
                                                {{purpose.name}}
                                            </option>
                                        </select>
                                    </div>
                            
                                </div>
                                <div class="col-md-6">
                                    <!-- <div class="form-group">
                                        <label for="">Choose step</label>
                                        <select name="" id="" v-model="formEdit.step_id" disabled class="form-control form-control-lg">
                                            <option v-for="step in todaySteps" v-bind:value="step.id">{{step.name}}</option>
                                        </select>
                                    </div> -->
                                </div>
                            </div>
                        <div class="form-group">
                            <label for="exampleFormControlTextarea1">Description</label>
                            <textarea v-model="formEdit.description" class="form-control" 
                                    id="exampleFormControlTextarea1" rows="3">
                                    {{formEdit.description}}
                            </textarea>
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
        </div>
    </section>
</template>
<script>
export default {
    created(){
        // const loader = document.querySelector(".lds-hourglass");
        // loader.className += " hidden"; // class "loader hidden"

        Promise.all([
            this.getTodayPurpose(),
            this.getTodayTarget(),
            this.getCompletedTarget(),
            this.getTodayStatus(),
        ]).then(()=>{
            const loader = document.querySelector(".lds-hourglass");
            loader.className += " hidden"; // class "loader hidden"
        })
    },
    data:function(){
        return{
            alertDangerClass:'alert-danger',
            alertInfoClass:'alert-info',
            alertClass:'alert',
            backgroundColors:[
                '#FF0000',
                '#FF7F00',
                '#FFFF00',
                '#00FF00',
            ],
            formTarget:new Form({
                id:'',
                name:'',
                purpose_id:'',
                time_has_completed:'',
                time_to_complete:'',
                frequency:'',
            }),
            formEdit:new Form({
                name:'',
                id:'',
                description:'',
                selectedpurpose:'',
                priority:'',
                iscomplete:0,
                step_id:''
            }),
            selectedpurpose:1,
            listOfPurposes:[],
            listOfTargets:[],
            listOfCompletedTargets:[],
            iconClass:'fa fa-star',
            customClass:'note-component__start-rating',
            activeClass:'checked',
            isSelected:false,
            ratingStart:'',
            statusForm:new Form({
                id:'',
                content:'',
                rating:'',
            }),
            purposes:[],
            user:{}
        }
    },
    
    methods:{
        getTodayPurpose(){
            return new Promise((resolve,reject)=>{
                axios.get('/api/purpose/today')
                .then(response=>{
                    resolve('This is ok');
                    this.listOfPurposes=response.data;
                    
                })
            })
        },
        getTodayTarget(){
            return new Promise((resolve,reject)=>{
                axios.get('/api/target/today')
                    .then((response)=>{
                        resolve('This is ok');
                        this.listOfTargets=response.data;
                        
                })
            })
            
        },

        getCompletedTarget(){
            return new Promise((resolve,reject)=>{
                axios.get('/api/target/completed')
                .then(response=>{
                    resolve('This is ok');
                    this.listOfCompletedTargets=response.data;
                })
            })
        },

        addTarget(){
            this.formTarget.repeat=0;
            this.formTarget.frequency='Today';
            this.formTarget.post('api/target')
                .then( (response) => {
                    console.log(response.data);
                    this.getTodayTarget();
                    this.formTarget.reset();
                    toast.fire({
                        type: 'success',
                        title: 'Successful'
                    })
                });
        },
        
        makeTargetComplete(target){
            
            var id=target.id;
            var size=this.listOfTargets.length;
            
            this.updateListOfTarget(size,id);
            //if user completed all target of today, show congratulation for us.
            if(this.listOfTargets.length==0){
                swal.fire(
                    'Congratulation!',
                    'You have completed all Targets of today!',
                    'success'
                )
            }

            this.formTarget.patch('/api/target/make-complete/'+target.id)
                .then(()=>{
                    // this.getTodayTarget();
                    // this.getCompletedTarget();
                });
        },
        //get today status
        getTodayStatus(){
            return new Promise((resolve,reject)=>{
                axios.get('/api/status/today')
                .then(response=>{
                    // for (var i = 0; i < response.data.length; i++) { 
                    //     this.form.fill(response.data[i]); 
                    //     }
                    resolve('This is ok');
                    this.statusForm.fill(response.data[0]);
                    this.ratingStart=this.statusForm.rating;
                })
            })
        },
        //change start of rating
        changeSelect(n){
            this.isSelected=!this.isSelected;
            this.ratingStart=n;
        },

        updateStatus(){
            this.statusForm.rating=this.ratingStart;
           
            this.statusForm.patch('/api/status/'+this.statusForm.id)
                .then(()=>{
                    toast.fire({
                        type: 'success',
                        title: 'Updated successfully'
                    })
                })
        },

        //using binary search to update list of target
        //when a target is made complete
        updateListOfTarget(size,value){
            var right = size - 1; // chỉ số phần tử cuối
            var left = 0; // Chỉ số phần tử đầu tiên
            while (right >= left){
                var mid = Math.ceil(left + (right - left) / 2); 
                
                if (this.listOfTargets[mid].id == value){
                    this.listOfCompletedTargets.push(this.listOfTargets[mid]);
                    this.listOfTargets.splice(mid,1);
                    return;
                }
                if (this.listOfTargets[mid].id > value)
                    right = mid - 1;
                if(this.listOfTargets[mid].id<value)
                    left = mid + 1;
            }
        }
        // editWork(work){
        //     $('#editWork').modal('show');
        //     this.formEdit.fill(work);
        // },
        // updateWork(){
        //     this.formEdit.patch('/api/work/'+this.formEdit.id)
        //     .then( response => { 
        //         this.getTodayTarget();
        //         $('#editWork').modal('hide');
        //     })      
        // },
        // deleteWork(id){
        //     Swal({
        //         title: 'Are you sure?',
        //         text: "You won't be able to revert this!",
        //         type: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Yes, delete it!'
        //         }).then((result) => {
        //             this.form.delete('api/work/'+id)
        //                 .then( data => { 
        //                     this.getTodayTarget(); 
        //             });
        //         })
            
        // }
    }
}
</script>

<style>
    
    .checked{
        color: rgb(238, 184, 5);
        font-size:2rem;
    }
    .note-component__start-rating{
        cursor: pointer;
        font-size:2rem;
    }
</style>
