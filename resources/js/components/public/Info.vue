<template>

    <section id="info" class=" py-3 text-white text-center">
        
        <div class="container">
            <div class="card bg-secondary text-center">
                <div class="card-body">
                    <h4 class="card-title">Today Prodomo:{{status.tomato}}</h4>
                    <h6 class="card-subtitle text-muted"></h6>
                    <p class="card-text"></p>
                    
                    <div v-if="isForcus">
                        <div v-if="stopping">
                            <button class="btn btn-primary" v-on:click="running=true;stopping=false;startCount();">Start forcus</button>
                        </div>
                        <div v-else>
                            <button class="btn btn-danger" v-on:click="running=false;stopping=true;stopCount();">Stop forcus</button>
                        </div>
                    </div>
                    <div v-else>
                        <button class="btn btn-primary" v-on:click="running=false;isRelax=false;isForcus=true;">Stop Relax</button>
                    </div>

                    <div class="menu-timer text-center">
                        <div id="menu-timer__mins">00</div> : <div id="menu-timer__secs">00</div>
                    </div>
                                
                </div>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    created(){
        this.getTodayStatuses();
    },
    mounted(){
        window.addEventListener('beforeunload', () => {
            this.stopCount();
        }, false)
    },
    
    data:function(){
        return{
            time:'',
            running:false,
            stopping:true,
            isRelax:false,
            isForcus:true,
            numOfSec:1500,
            form:new Form({
                id:'',
                tomato:'',
            }),
            status:{},

        }
    },
    methods:{
        startCount() {
            //alert(this.running);
            if(this.running){
                this.numOfSec=this.numOfSec-1;
                var mins=Math.floor(this.numOfSec/60);
                var secs=this.numOfSec-(mins*60);
                document.getElementById("menu-timer__mins").innerHTML = mins;
                document.getElementById("menu-timer__secs").innerHTML = secs;
                
                if(this.numOfSec<=0) {
                    //because the inititedvalue of is relax in false
                    this.isRelax=!this.isRelax;
                    if(this.isRelax){
                        //relax so isForcus equal false
                        this.updateStatus(1);
                        this.isForcus=false;
                        this.numOfSec=300;   
                    }
                    else{
                        //forcus so isForcus equal true
                        this.isForcus=true;
                        this.numOfSec=1500;
                    }
                }
            this.time = setTimeout(this.startCount, 1000);
            }
            else{
                clearTimeout(this.time);
                this.numOfSec=1500;
                document.getElementById("menu-timer__mins").innerHTML = 0;
                document.getElementById("menu-timer__secs").innerHTML = 0;
            }
        },
        stopCount(){
            var tomato=Number(((1500-this.numOfSec)/1500).toFixed(3));
            this.updateStatus(tomato);
        },
        getTodayStatuses(){
            axios.get('/api/status/today')
                .then(response=>{
                    this.status=response.data[0];
                    if(this.status===undefined||this.status.length==0){
                        this.createStatus();
                    }
            })
        },
        updateStatus(tomato){
            //can not add by this way i don't know why????
            //this.form.tomato+=tomato;
            var newTomato=this.status.tomato+tomato;
            
            this.form.tomato=newTomato;
            this.form.id=this.status.id;
            this.form.put('/api/status/tomato/'+this.form.id)
                .then(response => {
                    this.status=response.data;
                    this.form.reset();
                });
        },
        createStatus(){
            this.form.tomato=0;
            this.form.post('/api/status')
                .then( response => {
                    this.status=response.data;   
            });
        }
    }
}
</script>

<style>
    
</style>
