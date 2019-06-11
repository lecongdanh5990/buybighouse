<template>
    <div>
        <animation-component></animation-component>
        <canvas id="myChart"></canvas>
        <table class="table table-dark">
            <thead>
            <tr>
                <th>Min</th>
                <th>Max</th>
                <th>Averange</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{detailOfWeek.min}}</td>
                <td>{{detailOfWeek.max}}</td>
                <td>{{detailOfWeek.averange}}</td>
            </tr>
            </tbody>
            </table>
        <div class="btn btn-success "
            v-on:click="
            getTomatoInDifferentWeek(1);
            ">Previous</div>
        <div class="btn btn-success "
            v-on:click="
            getTomatoInDifferentWeek(1);
            ">Next</div>
        <hr>
        <canvas id="monthChart"></canvas>
        <table class="table table-dark">
        <thead>
        <tr>
            <th>Min</th>
            <th>Max</th>
            <th>Averange</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>{{detailOfMonth.min}}</td>
            <td>{{detailOfMonth.max}}</td>
            <td>{{detailOfMonth.averange}}</td>
        </tr>
        </tbody>
        </table>
        
        <div class="btn btn-success"
            v-on:click="
            getTomatoInDifferentMonth(-1);
            ">Previous</div>
        <div class="btn btn-success "
            v-on:click="
            getTomatoInDifferentMonth(1);
            ">Next</div>
    </div>
</template>
<script>
export default {
    created(){
        Promise.all([
            this.getTomatoInThisWeek(),
            this.getTomatoInThisMonth(),
            this.getLimitedMonth(),
        ]).then(()=>{
            const loader = document.querySelector(".lds-hourglass");
            loader.className += " hidden"; // class "loader hidden"
        })
    },
    mounted(){
        this.getDateNow();
    },
    
    data:function(){
        return{
            isStart:false,
            tomatoInWeek:[],
            tomatoInMonth:[],
            tomatoInDifferentMonth:[],
            daysInMonth:[],
            daysInDifferentMonth:[],
            limitedDate:[],
            detailOfWeek:new Form({
                averange:'',
                min:'',
                max:''
            }),
            detailOfMonth:new Form({
                averange:'',
                min:'',
                max:''
            }),
            startDayOfMonth:'',
            startDayOfWeek:'',
            currentMonth:'',
            currentYear:'',
            
        }
    },
    
    methods:{
        getTomatoInThisWeek(){
            return new Promise((relsove,reject)=>{
                axios.get('/api/status/in-week')
                .then(response=>{
                    this.tomatoInWeek=response.data;
                    this.calculateDetail(this.tomatoInWeek,7);
                    setTimeout(this.drawWeekChart(),3000);
                    relsove('this is ok');
                })
            })
        },
        getTomatoInDifferentWeek(){
            var curr = new Date;
            var firstday = new Date(curr.setDate(curr.getDate() - curr.getDay()));
            var lastday = new Date(curr.setDate(curr.getDate() - curr.getDay()+6));
        },
        getTomatoInThisMonth(){
            return new Promise((relsove,reject)=>{
                var now= new Date();
                var month = now.getMonth();
                var year=now.getFullYear();
                var numOfDay=this.getDaysInMonth(month+1,year);
                for(var i=1;i<=numOfDay;i++){
                    this.daysInMonth[i-1]=i;
                }

                axios.get('/api/status/in-month')
                    .then(response=>{
                        this.tomatoInMonth=response.data; 
                        this.calculateDetail(this.tomatoInMonth,30);
                        setTimeout(this.drawMonthChart(this.tomatoInMonth,this.daysInMonth),3000);
                        relsove('this is ok');
                })
            })
            
        },
        getDateNow(){
            var day=new Date();
            this.currentMonth=day.getMonth()+1;
            this.currentYear=day.getFullYear();
            console.log(this.currentMonth);
            console.log(this.currentYear);
        },
        getLimitedMonth(){
            return new Promise((relsove,reject)=>{
                axios.get('/api/status/limited-month')
                    .then(response=>{
                        this.limitedDate=response.data;
                        relsove('this is ok');
                    })
            })
        },
        getTomatoInDifferentMonth(choose){
            var maxMonth=parseInt(this.limitedDate['maxMonth']);
            var maxYear=parseInt(this.limitedDate['maxYear']);
            var minMonth=parseInt(this.limitedDate['minMonth']);
            var minYear=parseInt(this.limitedDate['minYear']);
            if(choose==1){
                this.currentMonth+=1;
                if(this.currentMonth>12){
                    this.currentMonth=1;
                    this.currentYear+=1;
                }
                if(this.currentMonth>maxMonth && this.currentYear==maxYear){
                    this.currentMonth-=1;
                    alert('Do not have any data here');
                    return;
                }
            }
            else if(choose==-1){
                this.currentMonth-=1;
                if(this.currentMonth==0){
                    this.currentMonth=12;
                    this.currentYear-=1;
                }
                if(this.currentMonth<minMonth && this.currentYear==minYear){
                    this.currentMonth+=1;
                    alert('Do not have any data here');
                    return;
                }
            }
            var numOfDay=this.getDaysInMonth(this.currentMonth,this.currentYear);
            for(var i=1;i<=numOfDay;i++){
                this.daysInDifferentMonth[i-1]=i;
            }
            axios.get('/api/status/'+this.currentMonth+'/'+this.currentYear)
                .then(response=>{
                    this.tomatoInDifferentMonth=response.data; 
                    setTimeout(this.calculateDetail(this.tomatoInDifferentMonth,30),3000);
                    setTimeout(this.drawMonthChart(this.tomatoInDifferentMonth,this.daysInDifferentMonth),3000);
                });
        },
        getDaysInMonth (month, year) {
            return new Date(year, month, 0).getDate();
        },
        calculateCurrentTime(choose){
            if(choose==1){
                this.currentMonth+=1;
                if(this.currentMonth>12){
                    this.currentMonth=1;
                    this.currentYear+=1;
                }
            }
            else if(choose==-1){
                this.currentMonth-=1;
                if(this.currentMonth==0){
                    this.currentMonth=12;
                    this.currentYear-=1;
                }
            }

            console.log('current month is'+this.currentMonth);
            console.log('current year is'+this.currentYear);
        },
        calculateDetail(data,choose){
            let sum = data.reduce((previous, current) => current += previous);
            if(choose==7){
                this.detailOfWeek.averange = (sum/data.length).toFixed(2);
                this.detailOfWeek['min']=Math.min(...data).toFixed(2);
                this.detailOfWeek['max']=Math.max(...data).toFixed(2);
            }
            else if(choose==30){
                this.detailOfMonth.averange = (sum/data.length).toFixed(2);
                this.detailOfMonth['min']=Math.min(...data).toFixed(2);
                this.detailOfMonth['max']=Math.max(...data).toFixed(2);
            }
        },
        drawWeekChart(){
            var ctx = document.getElementById('myChart').getContext('2d');
            //Global option
            Chart.defaults.global.defaultFontFamily='Lato';
            Chart.defaults.global.defaultFontSize=18;
            Chart.defaults.global.defaultFontColor=18;

            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'line',//bar,horisontalBar,pie,line,doughtnut,radar, 

                // The data for our dataset
                data: {
                    labels:['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] ,
                    datasets: [{
                        label: "My First dataset",
                        backgroundColor: 'rgb(66, 244, 197)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: this.tomatoInWeek,
                    }]
                },

                // Configuration options go here
                options: {
                    title:{
                        display:true,
                        text:'How to train your dragon',
                        fontSize:25,

                    },
                    legend:{
                        display:false,
                        position:'right',
                        labels:{
                            fontColor:'rgb(9, 41, 186)'
                        }
                    },
                    layout:{
                        padding:{
                            bottom:0,
                            left:50,
                            right:50,
                            top:0
                        }
                    }
                }
            });
        },
        drawMonthChart(tomato,listOfDay){
            var ctx = document.getElementById('monthChart').getContext('2d');
            Chart.defaults.global.defaultFontFamily='Lato';
            Chart.defaults.global.defaultFontSize=18;
            Chart.defaults.global.defaultFontColor=18;

            var chart = new Chart(ctx, {
                // The type of chart we want to create
                type: 'line',//bar,horisontalBar,pie,line,doughtnut,radar, 

                // The data for our dataset
                data: {
                    labels:listOfDay,
                    datasets: [{
                        label: "My First dataset",
                        backgroundColor: 'rgb(66, 244, 197)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: tomato,
                    }]
                },

                // Configuration options go here
                options: {
                    title:{
                        display:true,
                        text:'How to train your dragon',
                        fontSize:25,

                    },
                    legend:{
                        display:false,
                        position:'right',
                        labels:{
                            fontColor:'rgb(9, 41, 186)'
                        }
                    },
                    layout:{
                        padding:{
                            bottom:0,
                            left:50,
                            right:50,
                            top:0
                        }
                    }
                }
            });
            //getTomatoInNextMonth();
            //getTomatoInPriviousMonth();
        },
        
    },
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
    #today-worktable tr td,
    #today-completeworktable tr td{
        color: white;
        text-align: center;
        font-weight: bold;
    }
    #today-worktable thead,
    #today-completeworktable thead{
        background-color:lavenderblush;
    }
</style>
