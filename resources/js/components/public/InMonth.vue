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
        <h1>What will you do in This month?</h1>
        <form v-on:submit.prevent="addTarget">
          <div class="form-group">
            <label for>Add new Target for today</label>
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
      this.getTomorrowTargets(),
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
      formEdit: new Form({
        name: "",
        id: "",
        description: "",
        selectedpurpose: "",
        priority: "",
        iscomplete: 0,
        step_id: ""
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
    getTomorrowTargets() {
      return new Promise((resolve, reject) => {
        axios.get("/api/target/in-month").then(response => {
          resolve("This is ok");
          this.listOfTargets = response.data;
        });
      });
    },

    addTarget() {
      this.formTarget.repeat = 0;
      this.formTarget.frequency = "This month";
      this.formTarget.post("api/target").then(() => {
        this.getTomorrowTargets();
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

<style>
.checked {
  color: rgb(238, 184, 5);
  font-size: 2rem;
}
.note-component__start-rating {
  cursor: pointer;
  font-size: 2rem;
}
</style>
