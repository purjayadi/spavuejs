<template>
    <div>
    <navbar></navbar>
      <div class="container" v-if="this.token">
        <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">Form SubMenu</div>
                        <div class="panel-body">
                            <form @submit.prevent="saveForm" @keydown="$event.keyCode === 13 ? $event.preventDefault() : false">
                              <div class="form-group" :class="{ 'has-error': errors.menu_id }">
                                <label for="upc_ean_isbn">Parent Menu <span class="text-danger">*</span></label>
                                <select class="form-control show-tick" v-model="submenu.menu_id" >
                                    <option value="">-- Please select --</option>
                                    <option v-for="menu in menusData" :value="menu.idkey">{{ menu.namekey }}</option>
                                </select>
                                <div class="help-block" v-if="errors.menu_id">
                                  <div v-for="error in errors.menu_id"><strong>{{ error }}</strong></div>
                                </div>
                              </div>
                              <div class="form-group" :class="{ 'has-error': errors.name }">
                                <label for="item_name">Submenu <span class="text-danger">*</span></label>
                                <input type="text" v-model="submenu.name" class="form-control" id="name" placeholder="sub menu" autofocus>
                                <div class="help-block" v-if="errors.name">
                                  <div v-for="error in errors.name"><strong>{{ error }}</strong></div>
                                </div>
                              </div>
                              <div class="text-right">
                                <button type="submit" class="btn btn-primary" v-if="!edit">Save</button>
                              </div>
                            </form>
                            <div class="text-right">
                            <button class="btn btn-warning" @click="clearForm()" v-if="edit">Cancel</button>
                            <button class="btn btn-info" v-if="edit" @click="editForm(id)">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
              <div class="col-md-8">
                <div class="panel panel-default">
                <div class="panel-heading">List Submenu</div>
                  <div class="panel-body">
                      <div class="form-group form-float">
                        <div id="imaginary_container">
                            <div class="input-group stylish-input-group">
                                <input type="text" class="form-control"  placeholder="Search submenu" v-model="pencarian" >
                                <span class="input-group-addon">
                                    <button type="submit">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                              </div>
                       </div>
                      </div>
                    <!-- table -->
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Parent Menu</th>
                          <th>Submenu</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody v-if="submenus.length" class="data-ada">
                        <tr v-for="row in submenus">
                          <td>{{row.keyname}}</td>
                          <td>{{row.name}}</td>
                          <td width="130px">
                            <button class="btn btn-xs btn-warning" @click="showMenu(row.id)"><icon name="pencil" label="Forked Repository"></icon></button>
                            <button class="btn btn-xs btn-danger" v-bind:id="'delete-' + row.id" @click="deleteMenu(row.id, index,row.name)"><icon name="trash" label="Forked Repository"></icon></button>
                          </td>
                        </tr>
                      </tbody>
                      <tbody class="data-tidak-ada" v-else>
                        <tr>
                          <td colspan="5" class="text-center">Tidak Ada Data</td>
                        </tr>
                      </tbody>
                    </table>
                    <!-- .end table -->
                    <vue-simple-spinner v-if="loading"></vue-simple-spinner>
                    <div class="body" align="right">
                      <vue-pagination :data="submenusData" v-on:pagination-change-page="getSubmenus" :limit="3"></vue-pagination>
                    </div>
                  </div>

                </div>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
import Vue from 'vue'
import 'vue-awesome/icons/flag'
import 'vue-awesome/icons'
import Icon from 'vue-awesome/components/Icon'
Vue.component('icon', Icon)
import axios from 'axios';
    export default {
      components: {
          navbar: require('./Navbar.vue'),
          Icon
      },
      data: function () {
        return {
          errors: [],
          submenu: {
            id: '',
            name: '',
            menu_id: ''
          },
          submenus: [],
          submenusData: {},
          menus: [],
            token: localStorage.getItem('token'),
          menusData: {},
          menu: {
            idkey: '',
            namekey: ''
          },
          url : window.location.origin + window.location.pathname,
          loading: true,
          pencarian: '',
          edit: false
        }
      },
      mounted() {
        //do something after mounting vue instance
          if (this.token ===null){
              this.$router.replace('/login')
          }
          else {
              var app = this;
              app.getIdmenus();
              app.getSubmenus();
          }
      },
      watch: {
            // whenever question changes, this function will run
            pencarian: function (q) {
              if (q != '') {
                this.searchMenu()
              }
              else {
                this.getSubmenus()
              }

            }
          },
      methods: {
        clearForm() {
           this.edit = false
           this.submenu.name = '',
           this.submenu.menu_id=''
         },
        getSubmenus(page) {
          var app = this;
          if (typeof page === 'undefined'){
            page = 1;
          }
          axios.get('/api/getsubmenu?page='+page)
          .then(function (resp){
            app.submenus = resp.data.data;
            app.submenusData = resp.data;
            app.loading = false;
          })
          .catch(function (resp){
            console.log(resp);
            app.loading = false;
            alert("Could not load menu");
          });
        },
        getIdmenus(page) {
          var app = this;
          if (typeof page === 'undefined'){
            page = 1;
          }
          axios.get('/api/getidmenu?page='+page)
          .then(function (resp){
            app.menus = resp.data.data;
            app.menusData = resp.data;
            app.loading = false;
          })
          .catch(function (resp){
            console.log(resp);
            app.loading = false;
            alert("Could not load menu");
          });
        },
        searchMenu(page){
          var app = this;
          if (typeof page === 'undefined') {
            page = 1;
          }
          axios.get('/api/searchsubmenu?q='+app.pencarian+'&page='+page)
          .then(function (resp) {
            app.submenus = resp.data;
            app.submenusData = [];
          })
          .catch(function (resp) {
            console.log(resp);
            alert("Could not load menu");
          });
        },
        alert(pesan){
          this.$swal({
            title: "Berhasil Menghapus Submenu !",
            text: pesan,
            icon:"success",
          });
        },
        deleteMenu(id, index,name){
          if(confirm("Yakin Ingin Menghapus Submenu "+name+" ?")){
            var app = this;
            axios.delete('/api/submenu/' +id+'?token='+this.token)
            .then(function (resp) {
              app.getSubmenus();
              app.alert("Berhasil Menghapus Menu "+ name)
            })
            .catch(function (resp){
              alert("Could not delete menu");
            });
          }
        },
        saveForm() {
          var app = this;
          var newSubmenu = app.submenu;
          axios.post('/api/submenu?token='+this.token, newSubmenu)
          .then(function(resp){
            app.message = 'Sukses : Berhasil Menambah Menu ' +
            app.submenu.name;
            app.alert(app.message);
            app.clearForm(),
            app.getSubmenus();
            app.errors ='';
          })
          .catch(function (resp){
            app.success = false;
            app.errors = resp.response.data.errors;
          });
        },
        editForm() {
          var app = this;
          var newSubmenu = app.submenu;
          axios.put('/api/submenu/' + app.menuId+'?token='+this.token, newSubmenu)
          .then(function(resp){
            app.message = 'Sukses : Berhasil Ubah Menu ' +
            app.submenu.name;
            app.alert(app.message);
            app.clearForm(),
            app.getSubmenus();
            app.edit = false
            app.errors ='';
          })
          .catch(function (resp){
            app.success = false;
            app.errors = resp.response.data.errors;
          });
        },
        showMenu(id) {
          let app = this;
          app.menuId = id;
          axios.get('/api/editsubmenu/' + id+'?token='+this.token)
            .then(function(resp) {
                app.edit = true;
              app.submenu.id = resp.data.id;
              app.submenu = resp.data;
            })
            .catch(function() {
              alert("Could not load your halaman")
                this.$router.replace('/login')
            });
        },
        alert(pesan){
          this.$swal({
            title: "Berhasil !",
            text: pesan,
            icon: "success",
          });
        }
      }

    }
</script>
<style>
    .stylish-input-group .input-group-addon{
      background: white !important;
    }
    .stylish-input-group .form-control{
    border-right:0;
    box-shadow:0 0 0;
    border-color:#ccc;
    }
    .stylish-input-group button{
      border:0;
      background:transparent;
    }
</style>
