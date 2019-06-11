<template>
  <section id="today" class="py-3 today-section">
    <animation-component></animation-component>
    <div class="container">
      <div class="uncompleted-target">
        <h2 for>List of Targets</h2>
        <div class="table-responsive">
          <table class="table table-bordered" id="today-worktable">
            <thead>
              <tr class="bg-warning text-center">
                <th>Target</th>
                <th>Frequency</th>
                <th>Purpose</th>
              </tr>
            </thead>
            <tbody>
              <tr class="bg-success text-center" v-for="target in listOfTargets">
                <td>{{target.name}}</td>
                <td>{{target.frequency}}</td>
                <td>{{target.purpose_name}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- form to insert target -->
      <div class="form-target bg-light">
        <h1>What will you do in this week?</h1>
        <form v-on:submit.prevent="addTarget">
          <div class="form-group">
            <label for>Add new Target for This week</label>
            <input type="text" class="form-control" v-model="formTarget.name">
          </div>

          <div class="form-group">
            <label for>Choose Purpose</label>
            <select v-model="formTarget.purpose_id" class="form-control">
              <option
                v-for="purpose in uncompletedPurposes"
                v-bind:value="purpose.id"
              >{{purpose.name}}</option>
            </select>
          </div>

          <div class="form-group">
            <label for="exampleFormControlTextarea1">Description</label>
            <textarea
              v-model="formTarget.description"
              class="form-control"
              id="exampleFormControlTextarea1"
              rows="3"
            ></textarea>
          </div>
          <div class="form-group text-center">
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
        </form>
      </div>
      <!-- end form to insert target -->
    </div>
  </section>
</template>
<script>
export default {
  created() {
    Promise.all([
      // this.getTodayPurpose(),
      this.getThisWeekTargets(),
      this.getUncompletedPurposes()
      //this.getTodayStatus()
    ]).then(() => {
      const loader = document.querySelector(".lds-hourglass");
      loader.className += " hidden"; // class "loader hidden"
    });
  },
  data: function() {
    return {
      formTarget: new Form({
        id: "",
        name: "",
        purpose_id: "",
        time_has_completed: "",
        time_to_complete: "",
        frequency: ""
      }),
      
      uncompletedPurposes: [],
      listOfTargets: []
    };
  },

  methods: {
    getUncompletedPurposes() {
      return new Promise((resolve, reject) => {
        axios.get("/api/purpose/uncompleted").then(response => {
          resolve("This is ok");
          this.uncompletedPurposes = response.data;
        });
      });
    },
    getThisWeekTargets() {
      return new Promise((resolve, reject) => {
        axios.get("/api/target/in-week").then((response) => {
          resolve("This is ok");
          this.listOfTargets = response.data;
          console.log(this.listOfTargets);
        });
      });
    },

    addTarget() {
      this.formTarget.repeat = 0;
      this.formTarget.frequency = "This week";
      this.formTarget.post("api/target").then(() => {
        this.getThisWeekTargets();
        this.formTarget.reset();
        toast.fire({
          type: "success",
          title: "Successful"
        });
      });
    }
  }
};
</script>


