<template>
    <section class="status-section">
        <!-- alert area -->
        <!-- <div class="today-component__alert">
            <div class="alert alert-info">
            <strong>Info!</strong> Indicates a neutral informative change or action.
            </div>
            <div class="alert alert-success" role="alert" v-for="purpose in purposes">
                <h3>You have <strong>{{mission.dayleft}}</strong> days to complete <strong>{{mission.name}}</strong> mission.</h3>
                <hr>
                <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>
            </div>
            <div class="alert alert-warning">
            <strong>Warning!</strong> Indicates a warning that might need attention.
            </div>

            <div class="alert alert-danger">
            <strong>Danger!</strong> Indicates a dangerous or potentially negative action.
            </div>
        </div> -->
        <!-- end alert area -->
        
    </section>
</template>
<script>
export default {
    data:function(){
        return{
            iconClass:'fa fa-star',
            customClass:'note-component__start-rating',
            activeClass:'checked',
            isSelected:false,
            start:'',
            form:new Form({
                id:'',
                content:'',
                rating:'',

            }),
            purposes:[]
        }
    },
    
    created(){
        this.getPurposesOfToday();
        this.getToday();
    },
    methods:{
        getPurposesOfToday(){
            axios.get('/api/purpose/today')
            .then(response=>{
                this.purposes=response.data;
            })
        },
        
        changeSelect(n){
            this.isSelected=!this.isSelected;
            this.start=n;
        },
        updateStatus(){
            this.form.rating=this.start;
            console.log(this.form);
            this.form.patch('/api/status/'+this.form.id)
                .then(response=>{
                    alert("Updated");
                })
        }
    }
}
</script>

<style>
    .today-component__alert{
        font-size: 1.5rem;
    }
    .today-component__checkbox{
        width: 2rem;
        height: 2rem;
    }
    .checked{
        color: rgb(238, 184, 5);
        font-size:2rem;
    }
    .note-component__start-rating{
        cursor: pointer;
        font-size:2rem;
    }

</style>
