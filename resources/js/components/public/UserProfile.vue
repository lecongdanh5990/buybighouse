<template>
    <section id="userProfile">
        <div class="container">
            <div class="card">
                <div class="card-header ">
                   <div class="d-flex">
                       <div class="align-self-start mr-auto">
                            <img :src="photo" id="myImage" alt="">
                            
                        </div>
                        <div class="align-self-end ml-auto">
                            <h1>{{formProfile.name}}</h1>
                            
                            <p class="lead">Up load new Avatar here</p>
                            <input v-on:change="updateImage"  type="file" id="image">
                        </div>
                   </div>
                </div>

                <div class="card-body ">
                   <form v-on:submit.prevent="updateProfile">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input v-model="formProfile.name" class="form-control" type="text" id="name"
                                :class="{ 'is-invalid': formProfile.errors.has('name') }" >
                            <has-error :form="formProfile" field="name"></has-error>
                                    
                        </div>
                        <div class="form-group">
                            <label for="name">Email</label>
                            <input v-model="formProfile.email" class="form-control" type="text" id="name" 
                                :class="{ 'is-invalid': formProfile.errors.has('email') }">
                            <has-error :form="formProfile" field="email"></has-error>
                                    
                        </div>
                        <div class="form-group">
                            <label for="name">Password</label>
                            <input v-model="formProfile.password" class="form-control" type="password" id="name" 
                                :class="{ 'is-invalid': formProfile.errors.has('password') }">
                            <has-error :form="formProfile" field="password"></has-error>
                        </div>
                        <div class="form-group">
                            <label for="name">Phone number</label>
                            <input class="form-control" type="text" id="name" placeholder="Enter phone number">
                        </div>
                        <div class="form-row">
                            <div class="col">
                                <input type="text" class="form-control" placeholder="First name">
                            </div>
                            <div class="col">
                                <input type="text" class="form-control" placeholder="Last name">
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Change Info</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    created(){
        this.getProfile();
    },
    data:function(){
        return{
            photo:'',
            formProfile:new Form({
                name:'',
                email:'',
                password:'',
                photo:'',
            }),
        }
    },
    methods:{
        getProfile(){
            axios.get('/api/user/profile')
                .then(response=>{
                    this.formProfile.fill(response.data);
                    this.photo='/img/profile/'+this.formProfile.photo;
                    console.log(this.formProfile.photo);
                });
        },
        updateProfile(){
    
            this.formProfile.put('/api/user/profile/')
            .then(response=>{
                alert('Updated');
                this.getProfile(); 
            })   
        },
        updateImage(event){
            //change image for user when upload new image
            let readerChange=new FileReader();
            let file=event.target.files[0];

            if (file) {
                readerChange.onload = function (e) {
                    $('#myImage')
                        .attr('src', e.target.result)
                        .width(100)
                        .height(100);
                };

                readerChange.readAsDataURL(file);
            }
        

            //get name of file and check size of this
            
            let readerUpload=new FileReader();
            
            if(file['size']<2111775){
                readerUpload.onloadend=(file)=>{
                this.formProfile.photo = readerUpload.result;
                }
                readerUpload.readAsDataURL(file);
            }
            else{
                alert('You are uploading a large file');
                // swal({
                //     type:'error',
                //     title:'Opps,...',
                //     text:'You are uploading a large file'
                // })
            }
        },
    },
    
}
</script>

