<template>
  <section class="target" id="target">
    <animation-component></animation-component>
    <div class="container">
      <div class="target-header">
          <h1 class="d-inline bg-danger text-white text-center py-2 rounded">List of targets</h1>
          <button v-on:click="showModal();add=true;" class="btn btn-info">Add new Target</button>
      </div>
      <table class="table table-bordered">
        <thead class="bg-warning text-black">
          <tr>
            <th>Name</th>
            <th>Purpose</th>
            <th>Repeat</th>
            <th>Complete</th>
            <th>Edit</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <tr class="bg-primary text-white" v-for="target in listOfTargets">
            <td>
              <router-link :to="'/target/detail/'+target.id" class="text-white">{{target.name}}</router-link>
            </td>
            <td>{{target.purpose_name}}</td>
            <td>{{target.frequency}}</td>
            <td>{{target.isComplete}}</td>
            <td>
              <button type="button" class="btn btn-primary" v-on:click="editTarget(target);add=false">
                <i class="fa fa-edit"></i>
              </button>
            </td>
            <td>
              <button class="btn btn-danger" v-on:click="deleteTarget(target.id)">
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- modal -->
    <div class="modal" tabindex="-1" id="targetModal" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{add?'Add new Target':"Edit Target"}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form v-on:submit.prevent="add?addTarget():updateTarget()">
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
                <div class="form-group">
                <label for="gender">Choose Purpose</label>
                <select class="form-control" v-model="formTarget.purpose_id">
                  <option v-for="purpose in listOfPurposes" v-bind:value="purpose.id">{{purpose.name}}</option>
                </select>
                </div>
                
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">{{add?'Add':'Edit'}}</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- end modal -->
  </section>
</template>
<script>
export default {
  data: function() {
    return {
      formTarget: new Form({
        id: "",
        name: "",
        purpose_id:'',
        frequency:'',
        repeat:'',

      }),
      listOfTargets: [],
      listOfPurposes: [],
      add:''
    };
  },

  created() {
      Promise.all([
        this.getAllTargets(),
        this.getAllPurposes(),
        
        ]).then(()=>{
            const loader = document.querySelector(".lds-hourglass");
            loader.className += " hidden"; // class "loader hidden"
        })
    
  },
  methods: {
    getAllTargets() {
      return new Promise((resolve, reject) => {
        axios.get("/api/target").then(response => {
          resolve("This is ok");
          this.listOfTargets = response.data;
        });
      });
    },
    getAllPurposes() {
      return new Promise((resolve, reject) => {
        axios.get("/api/purpose").then(response => {
          resolve("This is ok");
          this.listOfPurposes = response.data;
        });
      });
    },
    showModal(){
        $('#targetModal').modal('show');
    },
    addTarget() {
      this.formTarget.post("api/target/").then((response) => {
        $("#targetModal").modal("hide");
        this.formTarget.reset();
        this.getAllTargets();
      });
    },

    editTarget(target) {
      $("#targetModal").modal("show");
      this.formTarget.fill(target);
    },

    updateTarget() {
      this.formTarget.put("api/target/"+this.formTarget.id)
        .then(() => {
        $("#targetModal").modal("hide");
        this.formTarget.reset();
        this.getAllTargets();
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
         this.formTarget.delete("api/target/"+id)
            .then(() => {
              this.formTarget.reset();
              this.getAllTargets();
              if (result.value) {
                swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                )
              }
      })


    })
  },
  }
}
</script>

