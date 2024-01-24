<script setup>
import { ref, onMounted, reactive } from 'vue';
import { Field, Form, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';

const createschema = yup.object({
  name: yup.string().required(),
  email: yup.string().required(),
  password: yup.string().min(8).required(),
});

const editSchema = yup.object({
  name: yup.string().required(),
  email: yup.string().required(),
  password: yup.string().when((password, schema) =>{
    return password ? schema.min(8) : schema;
  }),
});

const checkstatus = ref(false);

const users = ref([]);

const showme = ref(false);

const formValues = ref([]);

// This will help reset the form when we we want to edit it.
const resetform = ref(null);

const getUsers = () => {
    axios.get('/api/users').then((response) => {
        users.value = response.data;
    })
}

const storeauser = (form) =>{
    axios.post('/post/user', form)
    .then((response) => {
        users.value.unshift(response.data);
        // form.name = '';
        // form.email = '';
        // form.password = '';
    });
    // With this code only, you can now
    // send a post request
    // Form represents where it is being stored.
};

const handleuser = (values) => {
    if (checkstatus.value) {
        console.log(values);
        axios.put('api/users/' + formValues.value.id, values)
            .then((response) => {
                const index = users.value.findIndex(user => user.id === response.data.id);
                users.value[index] = response.data;
                $('#userFormModal').modal('hide');
            })
            .catch((error) => {
                console.log(error);
            })
            .finally(() => {
                resetform.value.resetForm(); // Assuming resetform is a ref pointing to the form instance
            });
    } else {
        storeauser(values);
    }
};


const editUser = (user) => {
  checkstatus.value = true;
  resetform.value.resetForm();
  $('#userFormModal').modal('show');
  formValues.value = {
    id: user.id,
    name: user.name,
    email: user.email,
 };
};

const adduserbutton = () =>{
    //  showme.value = true;
    $('#userFormModal').modal('show');
    checkstatus.value = false;

}

const form = reactive({
    name : '',
    email : '',
    password : '',
});


// Now we want the users to load each time we enter the page, we already have a get request. All we have to do is to put that function
onMounted(()=> {
    getUsers();
});

</script>
<template v-if="Object.keys(errors).length">

    <br/>

    <h1> Users </h1>

    <br/>
    <div class="content">
        <div class="container-fluid">

<button @click="adduserbutton" type="button" class="btn btn-primary">
    Add New User
  </button>

     <div class="card">
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox" /></th>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Email:</th>
                                <th>Registered Date</th>
                                <th>Role</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody >
                            <UserListItem  />
                        </tbody>
                        <tbody>
                            <tr v-for="(user, index) in users" :key="user.id">
                                <td> - </td>
                                <td> {{ index + 1}} </td>
                                <td> {{ user.name }} </td>
                                <td> {{ user.email }}</td>
                                <td> - </td>
                                <td> - </td>
                                <td>
                                    <a href="#" @click.prevent="editUser(user)"> <i class="fa fa-edit"> </i> </a> </td>
                                <!-- <td colspan="6" class="text-center">No results found...</td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
</div>
</div>

<!-- Modal template -->
<div class="modal fade" id="userFormModal" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                       <span v-if="checkstatus">  Edit User </span>
                       <span v-else>  Add New User </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <Form ref="resetform" @submit="handleuser" :validation-schema="checkstatus ? editSchema : createschema" v-slot="{errors}" :initial-values="formValues">
                <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <Field name="name" type="text" class="form-control " id="name"
                                aria-describedby="nameHelp" v-model="formValues.name" placeholder="Enter full name" />
                               <span style="color: red; font-size: 14px">{{ errors.name }}  </span>
                            </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <Field name="email" type="email" class="form-control " id="email"
                                aria-describedby="nameHelp" v-model="formValues.email"  placeholder="Enter full name" :class="{'is-invalid' : errors.email}" />
                               <span> {{ errors.email }}</span>
                            </div>

                    <div class="form-group">
                        <label for="email">Password</label>
                        <Field name="password" type="password" class="form-control " id="password"
                            aria-describedby="nameHelp" placeholder="Enter password"/>
                            <!-- <span> <ErrorMessage name="password" />  </span> -->
                            <span> {{ errors.password }} </span>
                         </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>

            </Form>

            </div>
        </div>
    </div>

</template>