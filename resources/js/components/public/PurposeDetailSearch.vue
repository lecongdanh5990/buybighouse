<template>
    <section class="purpose-detail" id="purposeDetail">
        <div class="container">
            
            <div class="chosse-mission p-3 text-center">
            <button class="btn btn-outline-info"
                data-toggle="modal" data-target="#choosePurpose"
                >Choose your Purpose</button>
            <!-- modal to choose purpose -->
            <div class="modal" id="choosePurpose">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Enter your purpose</h5>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <input class="form-control" type="text" v-model="nameOfPurpose">  
                            </div>
                            <ul class="list-group mb-5">
                                <a style="cursor:pointer" class="list-group-item" 
                                    v-for="purpose in filterPurposes"
                                    v-on:click="
                                    findSelectedPurpose(purpose.id);
                                    getTargetsOfPurpose(purpose.id);"
                                    >
                                    {{purpose.name}}
                                </a>
                            </ul>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end modal to choose purpose -->
        </div>
        <!-- end choose mission -->

        <!-- purpose process div -->
        <!-- <div class="purpose-progress">
            <h2>Process of this purpose</h2>
            <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar"
            aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" v-bind:style="{width:completedStep*100+'%'}">
                {{completedStep*100}}%
            </div>
        </div> -->
        <!-- end purpose process -->
        
        <div v-if="isCompletedPurpose" class="purpose-completed-detail">
            <div class="purpose">
                <table class="table">
                <thead>
                    <tr class="bg-secondary text-center">
                        <th>Purpose</th>
                        <th>Startday</th>
                        <th>Endday</th>
                        <th>Real endday</th>
                        <th>Target ontime rate</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="text-center">
                        <th>{{selectedPurpose.name}}</th>
                        <td>{{selectedPurpose.startday}}</td>
                        <td>{{selectedPurpose.endday}}</td>
                        <td>{{selectedPurpose.realendday}}</td>
                        <td>{{selectedPurpose.realendday}}</td>
                    </tr>  
                </tbody>
                </table>
            </div>
            <div class="target">
                <table class="table table-dark">
                <thead>
                    <tr class="bg-secondary text-center">
                        <th>Target</th>
                        <th>Repeat</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="target in targets" class="text-center">
                        <th>{{target.name}}</th>
                        <td>{{target.repeat}}</td>
                    </tr>  
                </tbody>
                </table>
            </div>
        </div>
        
        </div>
    </section>
</template>
<script>
export default {
    created(){
        alert('this is purpose detail component');
        this.getAllPurposes();
    },
    data:function(){
        return{
            missionStart:'',
            missionEnd:'',
            formMission:new Form({
                id:'',
                name:'',
                startday:'',
                iscomplete:'',
                realendday:'',
                endday:'',
            }),
            form:new Form({
                id:'',
                name:'',
                startday:'',
                iscomplete:'',
                realendday:'',
                endday:'',
                purpose_id:''
            }),
            formTarget:new Form({
                id:'',
                name:'',
                purpose_id:'',
                frequency:''
            }),
            purposes:{},
            targets:[],
            selectedPurpose:{},
            targetsHasCompleted:[],
            isCompletedPurpose:'',
            completedStep:0,
            nameOfPurpose:'',
            filterPurposes:[],
        }
    },
    
    watch:{
        nameOfPurpose:function(val){
            this.filterPurposes= this.purposes.filter((pur)=>{
                return pur.name.toLowerCase().match(this.nameOfPurpose.toLowerCase());
            });
        }
    },
    methods:{
        getTargetsOfPurpose(id){
            //hide the modal
            $('#choosePurpose').modal('hide');

            axios.get('/api/purpose/'+id+'/targets')
                .then(response=>{
                    this.targets=response.data;
                    for(var i=0;i<this.targets.length;i++){
                        var step=this.targets[i];
                        if(step.iscomplete) this.completedStep++;
                    }
                    this.completedStep/=this.targets.length;
                    this.completedStep=this.completedStep.toFixed(2);
                    if(this.completedStep==1){
                        this.makeMissionComplete();
                }
            })
        },
        getAllPurposes(){
            axios.get('/api/purpose')
            .then(response=>{
                this.purposes=response.data;
            })
        },
        findSelectedPurpose(id){
            var lengthOfPurpose=this.purposes.length;
            var index=this.searchPurpose(this.purposes,0,lengthOfPurpose-1,id);
            this.selectedPurpose=this.purposes[index];
            this.isCompletedPurpose=this.selectedPurpose.iscomplete;
        },
        searchPurpose(purposes,left,right,value){
            if (right >= left) {
                var mid = (right+left) / 2;
                if (purposes[mid].id == value)
                    return mid;
                if (purposes[mid].id > value)
                    return this.searchPurpose(purposes, left, mid - 1, value);
                return this.searchPurpose(purposes, mid + 1, right, value);
            }
            
            return -1;
            }
        },
        
        addTarget(){
            this.formTarget.purpose_id=this.selectedPurpose;
            alert(this.formTarget.name);
            console.log(this.formTarget);
            this.formTarget.post('/api/target')
                .then(()=> {
                    this.getTargets();
                });
            this.formTarget.reset();
        },
        updateTarget(){
            this.form.patch('/api/target/'+this.form.id)
                    .then( data => { 
                        this.getTargets();
                        $('#editStep').modal('hide');
                    });
        },
        deleteTarget(id){
            this.form.delete('/api/target/'+id)
                    .then( data => { 
                        this.getTargets();
                });
        },
        makeMissionComplete(){
            Swal.fire(
            'Good job!',
            'You completed this mission!'
            )
            var mission=this.purpose[this.selectedIndex];
            this.formMission.fill(mission);
            this.formMission.realendday=this.getCurrentDate();
            this.formMission.iscomplete=1;
        
            this.formMission.patch('/api/mission/'+this.formMission.id)
                    .then( data => {
                    });
        },
        getCurrentDate(){
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth()+1; //January is 0!
            var yyyy = today.getFullYear();
            if(dd<10) {
                dd = '0'+dd
            } 
            if(mm<10) {
                mm = '0'+mm
            } 
            today = yyyy + '-' + mm + '-' + dd;
            return today;
        }
}
</script>

