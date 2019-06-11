<template>
  <section class="purpose-detail" id="purposeDetail">
    <animation-component></animation-component> 
    <div class="container">
      
        <div class="purpose">
          <table class="table">
            <thead>
              <tr class="bg-primary text-center">
                <th>Purpose</th>
                <th>Startday</th>
                <th>Endday</th>
                <th>Real endday</th>
                <th>Target ontime rate</th>
              </tr>
            </thead>
            <tbody>
              <tr class="text-center">
                <th>{{purpose.name}}</th>
                <td>{{purpose.startday}}</td>
                <td>{{purpose.endday}}</td>
                <td>{{purpose.realendday}}</td>
                <td>{{purpose.realendday}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="target">
          <div class="target-head d-flex justify-content-around">
            <h1 class="d-inline">Lists of Targets</h1>
            <button class="btn btn-success"
              v-on:click="addTarget=true;showTargetModal()"><i class="fas fa-plus-circle fa-2x"></i>Add new Targets</button>
          </div>
          <table class="table table-striped">
            <thead>
              <tr class="text-center">
                <th>Target</th>
                <th>Repeat</th>
                <th>Edit</th>
                <th>Delete</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(target,index) in targets" class="text-center">
                <th>{{target.name}}</th>
                <td>{{target.frequency}}</td>
                <td><button v-on:click="addTarget=false;targetIndex=index;showTargetModal()" class="btn btn-warning"><i class="far fa-edit"></i></button></td>
                <td><button v-on:click="deleteTarget(target.id)" class="btn btn-warning"><i class="far fa-trash-alt"></i></button></td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- rating section -->

        <!-- end rating section -->
      </div>
      <hr>
      <section class="rating" id="rating">
        <div class="container">
          
          <div class="target-head d-flex justify-content-around">
            <h1 class="d-inline">Lists of Targets</h1>
            
          </div>
          <table class="table ">
            <thead class="thead-dark">
              <tr class="text-center">
                <th>Date</th>
                <th>Rating</th>
                <th>Tomato</th>
                <th>Description</th>
                <th>Completed work</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(status,index) in statuses" class="text-center">
                <th>{{status.date}}</th>
                <td>{{status.rating}}</td>
                <td>{{status.tomato}}</td>
                <td>{{status.content}}</td>
                <td>{{status.tomato}}</td>
                
              </tr>
            </tbody>
            <tfoot>
            <tr>
              <td>Sum</td>
              <td>$180</td>
            </tr>
          </tfoot>
          </table>
        </div>
        
      </section>


      <!-- modal for add new or edit Target -->
      <div class="modal" tabindex="-1" id="addTarget" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Target</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  v-on:submit.prevent="addTarget?addNewTarget():updateTarget()">
                <div class="modal-body">
                        <div class="form-group">
                          <label for="">Name of Targets</label>
                          <input type="text" class="form-control" id="name" name="name" 
                              v-model="formTarget.name">
                        </div>
                        <div class="form-group">
                            <select class="form-control" v-model="formTarget.frequency">
                                <option >Choose frequency</option>
                                <option value="No repeat">No repeat</option>
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button v-if="addTarget" type="submit" class="btn btn-primary">Add</button>
                    <button v-else type="submit" class="btn btn-warning">Edit</button>
                </div>
                </form>
                </div>
            </div>
        </div>
      <!-- end modal for add new or edit Target -->
  </section>
</template>
<script>
  export default {
    created() {
       Promise.all([
            this.getDetailOfPurpose(),
            this.getTargets(),
            this.getAllStatuses(),
        ]).then(()=>{
            const loader = document.querySelector(".lds-hourglass");
            loader.className += " hidden"; // class "loader hidden"
        })
      
      },
    data: function() {
      return {
        purpose_id:this.$route.params.id,
        formTarget: new Form({
          id: "",
          name: "",
          purpose_id: "",
          frequency: ""
        }),
        purpose: {},
        targets: [],
        statuses:[],
        addTarget:'',
        targetIndex:'',
      };
    },
    methods: {
      
      getDetailOfPurpose() {
        return new Promise((resolve,reject)=>{
          axios.get("/api/purpose/"+this.$route.params.id)
            .then(response => {
              resolve('This is ok');
              this.purpose = response.data;
            });
            })
          },

      getTargets() {
        return new Promise((resolve,reject)=>{
          axios.get("/api/purpose/" +this.purpose_id+ "/targets")
            .then(response => {
              resolve('This is ok');
              this.targets = response.data;
              for (var i = 0; i < this.targets.length; i++) {
                var step = this.targets[i];
                if (step.iscomplete) this.completedStep++;
              }
              this.completedStep /= this.targets.length;
              this.completedStep = this.completedStep.toFixed(2);
              if (this.completedStep == 1) {
                this.makePurposeComplete();
              }
            })
          });
      },

      getAllStatuses() {
        return new Promise((resolve,reject)=>{
          axios.get("/api/purposes/" +this.purpose_id+ "/categories")
            .then(response => {
              resolve('This is ok');
              this.statuses = response.data;
              console.log(this.statuses);
            })
          });
      },
      showTargetModal(){
        
        if(!this.addTarget){
          this.formTarget.fill(this.targets[this.targetIndex]);
          }
        $('#addTarget').modal('show');
      },
      addNewTarget() {
        this.formTarget.purpose_id = this.purpose_id;
    
        this.formTarget.post('/api/target')
          .then(() => {
            this.getTargets();
            $("#addTarget").modal("hide");

            toast.fire({
              type: 'success',
              title: 'Added successfully'
                });
          });
        this.formTarget.reset();
      },
      updateTarget() {
        this.formTarget.purpose_id = this.purpose_id;
        console.log(this.formTarget);
        this.formTarget.patch("/api/target/" + this.formTarget.id).then(data => {
          this.getTargets();
          $("#addTarget").modal("hide");
        });
      },
      deleteTarget(id) {
        swal.fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          this.formTarget.delete("/api/target/" + id).then(() => {
            this.getTargets();

            if (result.value) {
            swal.fire(
              'Deleted!',
              'Your file has been deleted.',
              'success'
            )
          }
        });
          
        })
        
      },
      // makePurposeComplete() {
      //   Swal.fire("Good job!", "You completed this mission!");
      //   var mission = this.purpose[this.selectedIndex];
      //   this.formMission.fill(mission);
      //   this.formMission.realendday = this.getCurrentDate();
      //   this.formMission.iscomplete = 1;

      //   this.formMission
      //     .patch("/api/mission/" + this.formMission.id)
      //     .then(data => {});
      // },
      // getCurrentDate() {
      //   var today = new Date();
      //   var dd = today.getDate();
      //   var mm = today.getMonth() + 1; //January is 0!
      //   var yyyy = today.getFullYear();
      //   if (dd < 10) {
      //     dd = "0" + dd;
      //   }
      //   if (mm < 10) {
      //     mm = "0" + mm;
      //   }
      //   today = yyyy + "-" + mm + "-" + dd;
      //   return today;
      // },
    },
  }
</script>

